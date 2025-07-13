<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Dompdf\Dompdf;
use Dompdf\Options;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\BinaryFileResponse;


#[Route('/cart')]
class CartController extends AbstractController
{

    #[Route( name: 'cart_index', methods: ['GET'])]
    public function index(SessionInterface $session, ArticleRepository $articleRepository): Response
    {
        $cart = $session->get('cart', []);

        $cartWithData = [];

        foreach ($cart as $id => $quantity) {
            $cartWithData[] = [
                'id'=>$id,
                'article' => $articleRepository->find($id),
                'quantity' => $quantity
            ];
        }
        return $this->render('cart/index.html.twig', [
            'items' => $cartWithData
        ]);
    }


    #[Route('/add/{id}', name: 'cart_add', methods: ['GET', 'POST'])]
    public function add($id, SessionInterface $session): RedirectResponse // SessionInterface permet de récupérer la session

    {
        $cart = $session->get('cart', []); // si pas encore de panier dans la session on affecte un panier vide

        if (!empty($cart[$id])) {
            $cart[$id]++;
        } else {
            $cart[$id] = 1;
        }

        $session->set('cart', $cart); // on injecte dans la session le panier modifié
        return $this->redirectToRoute("article_index");
    }

    #[Route('/remove/{id}', name: 'cart_remove', methods: ['GET', 'POST'])]
    public function remove($id, SessionInterface $session): RedirectResponse
    {
        $cart = $session->get('cart', []);
        if (!empty($cart[$id])) {
            unset($cart[$id]);
        }
        $session->set('cart', $cart);
        return $this->redirectToRoute("cart_index");
    }

    #[Route('/pdf', name: 'cart_pdf')]
    public function exportPDF(SessionInterface $session, ArticleRepository $articleRepository): void
    {
        // Configuration des options
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instancie Dompdf avec nos options
        $dompdf = new Dompdf($pdfOptions);

        $cart = $session->get('cart', []);
        foreach ($cart as $id => $quantity) {
            $cartWithData[] = [
                'id' => $id,
                'article' => $articleRepository->find($id),
                'quantity' => $quantity
            ];
        }

        $html = $this->render('cart/pdf.html.twig', [
            'items' => $cartWithData
        ]);
        // Dompdf récupère le HTML généré
        $dompdf->loadHtml($html);

        // (Optionnel) mise en page
        $dompdf->setPaper('A4', 'portrait');

        // HTML mis en PDF
        $dompdf->render();

        // générer le pdf sous forme de fichier à télécharger ("Attachment" => true) si false le pdf s'ouvre dans le navigateur
        $dompdf->stream("liste.pdf", [
            "Attachment" => true
        ]);
    }

    #[Route('/excel', name: 'cart_excel')]
    public function exportExcel(SessionInterface $session, ArticleRepository $articleRepository): BinaryFileResponse
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Bon de commande');

        // En-tête principal
        $sheet->mergeCells('C2:H2');
        $sheet->setCellValue('C2', 'Bon de commande');
        $sheet->getStyle('C2')->applyFromArray([
            'font' => ['bold' => true, 'size' => 18, 'name' => 'Arial'],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => '90EE90']],
        ]);
        $sheet->getRowDimension('2')->setRowHeight(30);

        // Coordonnées fournisseur (à gauche)
        $sheet->setCellValue('E4', 'Sté:')->mergeCells('E4:E5');
        $sheet->setCellValue('E6', 'Adr:');
        $sheet->setCellValue('E7', 'CP:');
        $sheet->setCellValue('E8', 'Ville:');
        $sheet->setCellValue('E9', 'TEL:');
        $sheet->setCellValue('E10', 'Email:');
        foreach (range(4, 10) as $i) {
            $sheet->mergeCells("F{$i}:G{$i}");
        }
        $sheet->getStyle('E4:E10')->applyFromArray([
            'font' => ['name' => 'Arial', 'bold' => true],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);
        $sheet->getColumnDimension('E')->setWidth(5);

        // Coordonnées client
        $sheet->setCellValue('C9', 'Adresse du client - Code Postal Ville');
        $sheet->setCellValue('C10', 'Tél fixe: xx.xx.xx.xx.xx - Tél mobile : xx.xx.xx.xx.xx');
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->getColumnDimension('D')->setWidth(33);

        // En-têtes du tableau en ligne 12
        $headers = ['Nom article', 'Désignation', 'Qté', 'PU HT', 'PT HT', 'PT TTC'];
        $sheet->fromArray($headers, null, 'C12');
        $sheet->getStyle('C12:H12')->applyFromArray([
            'font' => ['bold' => true],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'borders' => ['bottom' => ['borderStyle' => Border::BORDER_THIN]],
        ]);

        // Récupération des articles
        // DONNÉES DU PANIER (à partir de la ligne 13)
        $cart = $session->get('cart', []);
        $row = 13;
        $totalHT = 0;

        foreach ($cart as $id => $quantity) {
            $article = $articleRepository->find($id);
            if (!$article) {
                continue;
            }
            $priceHT = $article->getPrice();
            $totalArticleHT = $priceHT * $quantity;
            $totalArticleTTC = $totalArticleHT * 1.2;

            $sheet->setCellValue("C{$row}", $article->getName());
            $sheet->setCellValue("D{$row}", $article->getCategory()?->getTitle() ?? 'N.C.');
            $sheet->setCellValue("E{$row}", $quantity);
            $sheet->setCellValue("F{$row}", number_format($priceHT, 2, ',', ' ') . ' €');
            $sheet->setCellValue("G{$row}", number_format($totalArticleHT, 2, ',', ' ') . ' €');
            $sheet->setCellValue("H{$row}", number_format($totalArticleTTC, 2, ',', ' ') . ' €');

            $totalHT += $totalArticleHT;
            $row++;
        }

        // Frais de port et total
        $sheet->mergeCells("C{$row}:F{$row}");
        $sheet->setCellValue("C{$row}", 'Frais de port');
        $sheet->setCellValue("G{$row}", '0,00 €');
        $row++;

        $sheet->mergeCells("C{$row}:F{$row}");
        $sheet->setCellValue("C{$row}", 'Total');
        $sheet->setCellValue("G{$row}", number_format($totalHT, 2, ',', ' ') . ' €');
        $sheet->setCellValue("H{$row}", number_format($totalHT * 1.2, 2, ',', ' ') . ' €');

        $sheet->getStyle("C{$row}:H{$row}")->applyFromArray([
            'font' => ['bold' => true],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT],
            'borders' => ['top' => ['borderStyle' => Border::BORDER_THIN]],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'ffff80']],
        ]);

        // Bordures tableau
        $sheet->getStyle("C12:H{$row}")->applyFromArray([
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
        ]);

        // Export
        $writer = new Xlsx($spreadsheet);
        $fileName = 'bon_de_commande.xlsx';
        $tempFile = tempnam(sys_get_temp_dir(), $fileName);
        $writer->save($tempFile);

        return $this->file($tempFile, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);
    }

}