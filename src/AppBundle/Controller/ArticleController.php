<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Article;

class ArticleController extends Controller
{
    /**
     * @Route("/article/{path}", name="view_article")
     */
    public function getAction(Article $article)
    {
        return $this->render('article/get.html.twig', array(
            'article' => $article,
        ));
    }

}
