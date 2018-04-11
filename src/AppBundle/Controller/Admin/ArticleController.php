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
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Form\Form;
use AppBundle\Model\ArticleManager;
use Symfony\Component\Workflow\StateMachine;

/**
 * Description of ArticleController
 *
 * @author rob
 */
class ArticleController extends Controller
{

    /**
     * @var StateMachine
     */
    private $articleStatusWorkflow;

    public function __construct(StateMachine $articleStatusWorkflow)
    {
        $this->articleStatusWorkflow = $articleStatusWorkflow;
    }

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
     * @Route("admin/article/new", methods={"GET", "POST"}, name="new_article")
     */
    public function createAction(Request $request, ArticleManager $articleManager)
    {
        $editArticle = new EditArticle;

        $editArticleForm = $this->createEditArticleForm($editArticle);

        $editArticleForm->handleRequest($request);

        if ($editArticleForm->isSubmitted() && $editArticleForm->isValid()) {
            $article = Article::createFromInput($editArticle);
            $articleManager->updateArticle($article, $editArticleForm->getClickedButton()->getName());

            return new RedirectResponse($this->generateUrl('edit_article', [
                        'id' => $article->getId(),
            ]));
        }

        return $this->render('admin/article/new.html.twig', [
                    'editArticleForm' => $editArticleForm->createView(),
        ]);
    }

    /**
     * @Route("admin/article/{id}/edit", methods={"GET", "POST"}, name="edit_article")
     */
    public function editAction(Request $request, Article $article, ArticleManager $articleManager)
    {
        $editArticle = EditArticle::createFromArticle($article);

        $editArticleForm = $this->createEditArticleForm($editArticle, $article);

        $editArticleForm->handleRequest($request);

        if ($editArticleForm->isSubmitted() && $editArticleForm->isValid()) {
            $article->updateArticle($editArticle);
            $articleManager->updateArticle($article, $editArticleForm->getClickedButton()->getName());

            return new RedirectResponse($this->generateUrl('edit_article', [
                        'id' => $article->getId(),
            ]));
        }

        return $this->render('admin/article/edit.html.twig', [
                    'article' => $article,
                    'editArticleForm' => $editArticleForm->createView(),
        ]);
    }

    private function createEditArticleForm(EditArticle $editArticle, Article $article = null): Form
    {
        $options = [];

        $enabledTransitions = [];

        if ($article) {
            $options['action'] = $this->generateUrl('edit_article', [
                'id' => $article->getId()]);
            
            $enabledTransitions = $this->articleStatusWorkflow->getEnabledTransitions($article);
        } else {
            $options['action'] = $this->generateUrl('new_article');

            $allTransitions = $this->articleStatusWorkflow->getDefinition()->getTransitions();
            $initialPlace = $this->articleStatusWorkflow->getDefinition()->getInitialPlace();
            
            $enabledTransitions = array_filter($allTransitions, function($transition) use($initialPlace) {
                return in_array($initialPlace, $transition->getFroms());
            });
            
        }

        $editArticleForm = $this->createForm(EditArticleType::class, $editArticle, $options);

        foreach($enabledTransitions as $transition) {
            $editArticleForm->add($transition->getName(), SubmitType::class);
        }

        return $editArticleForm;
    }

}
