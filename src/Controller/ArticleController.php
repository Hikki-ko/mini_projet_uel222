<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;


#[Route('/article')]
class ArticleController extends AbstractController
{

    private function getFilteredArticles(Request $request, ArticleRepository $articleRepository)
    {
        $categoryId = $request->query->getInt('category') ?: null;
        $authorId   = $request->query->getInt('author') ?: null;

        return $articleRepository->findByFilters($categoryId, $authorId);
    }

    
    #[Route('/', name: 'article_timeline', methods: ['GET'])]
    public function timeline(
        Request $request,
        ArticleRepository $articleRepository,
        CategoryRepository $categoryRepository,
        UserRepository $userRepository
    ): Response {
        $articles = $this->getFilteredArticles($request, $articleRepository);

        return $this->render('article/timeline.html.twig', [
            'articles'   => $articles,
            'categories' => $categoryRepository->findAll(),
            'authors'    => $userRepository->findAll(),
        ]);
    }

    
    #[Route('/list', name: 'article_index', methods: ['GET'])]
    public function index(
        Request $request,
        ArticleRepository $articleRepository,
        CategoryRepository $categoryRepository,
        UserRepository $userRepository
    ): Response {
        $articles = $this->getFilteredArticles($request, $articleRepository);

        return $this->render('article/index.html.twig', [
            'articles'   => $articles,
            'categories' => $categoryRepository->findAll(),
            'authors'    => $userRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'article_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute('article_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('article/new.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'article_show', methods: ['GET'])]
    public function show(Article $article): Response
    {
        return $this->render('article/show.html.twig', [
            'article' => $article,
            'isFollowing' => $this->getUser()->isFollowing($article->getAuthor())

        ]);
    }

    #[Route('/{id}/edit', name: 'article_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Article $article, EntityManagerInterface $entityManager): Response
    {
       // $this->denyAccessUnlessGranted('EDIT', $article); //
       if (!$this->isGranted('EDIT', $article)) {
        $this->addFlash('error', 'Vous ne pouvez pas modifier cet article !');
        return $this->redirectToRoute('blog');

        }

        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('article_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('article/edit.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'article_delete', methods: ['POST'])]
    public function delete(Request $request, Article $article, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->request->get('_token'))) {
            $entityManager->remove($article);
            $entityManager->flush();
        }

        return $this->redirectToRoute('article_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/user/{id}/follow', name: 'user_follow', methods: ['GET', 'POST'])]
    public function follow(User $user, Request $request, EntityManagerInterface $entityManager): Response
    {

    /** @var User $currentUser */
    $currentUser = $this->getUser();
    $currentUser->follow($user);
    $entityManager->flush();

    return $this->redirect($request->headers->get('referer'));
    }

    #[Route('/user/{id}/unfollow', name: 'user_unfollow', methods: ['GET', 'POST'])]
    public function unfollow(User $user, Request $request, EntityManagerInterface $entityManager): Response
    {

    /** @var User $currentUser */
    $currentUser = $this->getUser();
    $currentUser->unfollow($user);
    $entityManager->flush();
    
    return $this->redirect($request->headers->get('referer'));
    }
}
