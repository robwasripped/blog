<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use AppBundle\Entity\Article;
use Doctrine\ORM\EntityRepository;
use AppBundle\Form\EditArticleType;
use AppBundle\Input\EditArticle;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Form\Form;

/**
 * Description of ArticleController
 *
 * @author rob
 */
class ArticleController extends Controller
{

    /**
     * @Route(
     *  "/admin/articles",
     *  name="admin_list_articles",
     *  requirements = {
     *      "page": "\d+",
     *      "per_page": "\d+"
     *  },
     *  defaults = {
     *      "page": 1,
     *      "per_page": 30
     *  }
     * )
     */
    public function listAction(Request $request, int $page, int $per_page, EntityRepository $articleRepository)
    {
        $articles = $articleRepository->findBy([], null, $per_page, $page - 1);

        return $this->render('admin/article/list.html.twig', [
                    'articles' => $articles,
        ]);
    }

    /**
     * @Route("admin/article/new", methods="GET", name="new_edit_article")
     */
    public function createAction()
    {
        $editArticle = new EditArticle;

        $editArticleForm = $this->createEditArticleForm($editArticle, $this->generateUrl('publish_article'));

        return $this->render('admin/article/new.html.twig', [
                    'editArticleForm' => $editArticleForm->createView(),
        ]);
    }

    /**
     * @Route("admin/article", methods="POST", name="publish_article")
     */
    public function publishAction(Request $request, EntityManager $entityManager)
    {
        $editArticle = new EditArticle;

        $editArticleForm = $this->createEditArticleForm($editArticle);

        $editArticleForm->handleRequest($request);

        if ($editArticleForm->isSubmitted() && $editArticleForm->isValid()) {
            $article = Article::createFromInput($editArticle);
            $entityManager->persist($article);

            $entityManager->flush();

            return new RedirectResponse($this->generateUrl('get_edit_article', [
                        'id' => $article->getId(),
            ]));
        }
    }

    /**
     * @Route("admin/article/{id}/edit", methods="GET", name="get_edit_article")
     */
    public function editAction(Article $article)
    {
        $editArticle = EditArticle::createFromArticle($article);

        $editArticleForm = $this->createEditArticleForm($editArticle, $this->generateUrl('post_edit_article', [
                    'id' => $article->getId(),
        ]));

        return $this->render('admin/article/edit.html.twig', [
                    'article' => $article,
                    'editArticleForm' => $editArticleForm->createView(),
        ]);
    }

    /**
     * @Route("admin/article/{id}", methods="POST", name="post_edit_article")
     */
    public function updateAction(Request $request, Article $article, EntityManager $entityManager)
    {
        $editArticle = EditArticle::createFromArticle($article);

        $editArticleForm = $this->createEditArticleForm($editArticle);

        $editArticleForm->handleRequest($request);

        if ($editArticleForm->isSubmitted() && $editArticleForm->isValid()) {
            $article->updateArticle($editArticle);
            $entityManager->persist($article);

            $entityManager->flush();
        }

        return new RedirectResponse($this->generateUrl('get_edit_article', [
                    'id' => $article->getId(),
        ]));
    }

    private function createEditArticleForm(EditArticle $editArticle, string $action = null): Form
    {
        $options = [];
        if ($action) {
            $options['action'] = $action;
        }

        $editArticleForm = $this->createForm(EditArticleType::class, $editArticle, $options);
        $editArticleForm->add('submit', SubmitType::class);

        return $editArticleForm;
    }

}
