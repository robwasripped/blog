<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Article;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ArticleController extends Controller
{
    /**
     * @Route("/article/{path}", name="view_article")
     */
    public function getAction(Request $request, Article $article)
    {
        $response = new Response;

        $response->setLastModified($article->getModifiedAt());
        $response->setSharedMaxAge(3600);
        $response->setpublic();

        if ($response->isNotModified($request)) {
            return $response;
        }

        return $this->render('article/get.html.twig', array(
            'article' => $article,
        ), $response);
    }

}
