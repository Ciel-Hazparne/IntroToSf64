<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Knp\Component\Pager\PaginatorInterface;

#[Route('/article')]
final class ArticleController extends AbstractController
{
    #[Route(name: 'article_index', methods: ['GET'])]
    public function index(ArticleRepository $articleRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $articles = $paginator->paginate($articleRepository->findAll(),
            $request->query->getInt('page', 1), // on démarre à la page 1
            3 // on ne veut afficher que 3 articles/page
        );
        return $this->render('article/index.html.twig', [
            'current_menu' => 'articles',
            'articles' => $articles,
        ]);
    }

    #[IsGranted('ROLE_EDITOR')]
    #[Route('/new', name: 'article_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ArticleRepository $articleRepository): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $articleRepository->save($article, true);
            $this->addFlash('success', "L'article <strong>{$article->getName()}</strong> a bien été enregistré");

            return $this->redirectToRoute('article_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('article/new.html.twig', [
            'current_menu' => 'articles',
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'article_show', methods: ['GET'])]
    public function show(Article $article): Response
    {
        return $this->render('article/show.html.twig', [
            'current_menu' => 'articles',
            'article' => $article,
        ]);
    }

    #[IsGranted('ROLE_EDITOR')]
    #[Route('/{id}/edit', name: 'article_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Article $article, ArticleRepository $articleRepository): Response
    {
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $articleRepository->save($article, true);
            $this->addFlash('success', "L'article <strong>{$article->getName()}</strong> a bien été modifié");

            return $this->redirectToRoute('article_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('article/edit.html.twig', [
            'current_menu' => 'articles',
            'article' => $article,
            'form' => $form,
        ]);
    }


    #[IsGranted('ROLE_EDITOR')]
    #[Route('/{id}', name: 'article_delete', methods: ['POST'])]
    public function delete(Request $request, Article $article, ArticleRepository $articleRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->getPayload()->getString('_token'))) {
            $articleRepository->remove($article, true);
            $this->addFlash('success', "L'article <strong>{$article->getName()}</strong> a bien été supprimé");
        }

        return $this->redirectToRoute('article_index', [], Response::HTTP_SEE_OTHER);
    }

}
