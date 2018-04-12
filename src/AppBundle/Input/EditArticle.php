<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Input;

use AppBundle\Entity\Article;
use Symfony\Component\HttpFoundation\Request;

/**
 * Description of EditArticle
 *
 * @author rob
 */
class EditArticle
{

    public $path;
    public $title;
    public $summary;
    public $content;

    public static function createFromArticle(Article $article): self
    {
        $editArticle = new self;
        $editArticle->path = $article->getPath();
        $editArticle->title = $article->getTitle();
        $editArticle->summary = $article->getSummary();
        $editArticle->content = $article->getContent();

        return $editArticle;
    }

}
