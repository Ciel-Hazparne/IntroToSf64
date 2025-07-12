<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/search')]
class SearchArticleController extends AbstractController
{
    #[Route('/article', name: 'search_article', methods: ['GET'])]
    public function search(Request $request, ArticleRepository $articleRepository): Response
    {
        $searchName = $request->query->get('keyword');

        $articles = $articleRepository->findArticleByName($searchName);

        return $this->render('article/search_article.html.twig', [
            'articles' => $articles,
            'searchName' => $searchName,
        ]);
    }
}