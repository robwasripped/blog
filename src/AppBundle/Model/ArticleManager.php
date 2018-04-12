<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Model;

use Symfony\Component\Workflow\StateMachine;
use Doctrine\ORM\EntityManager;
use AppBundle\Entity\Article;

/**
 * Description of ArticleManager
 *
 * @author rob
 */
class ArticleManager
{

    /**
     * @var StateMachine
     */
    private $articleStatusWorkflow;

    /**
     * @var EntityManager
     */
    private $entityManager;

    public function __construct(StateMachine $articleStatusWorkflow, EntityManager $entityManager)
    {
        $this->articleStatusWorkflow = $articleStatusWorkflow;
        $this->entityManager = $entityManager;
    }

    public function updateArticle(Article $article, ?string $status = null)
    {
        if ($status) {
            $this->articleStatusWorkflow->apply($article, $status);
        }

        $this->entityManager->persist($article);
        $this->entityManager->flush();
    }

    public function updateDraft(Article $article)
    {
        return $this->updateArticle($article, 'draft');
    }

    public function updatePublished(Article $article)
    {
        return $this->updateArticle($article, 'published');
    }

}
