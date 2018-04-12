<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\Article;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request, EntityRepository $articleRepository)
    {
        $articles = $articleRepository->findBy([
            'status' => Article::STATUS_PUBLISHED,
        ], null, $request->query->getInt('per_page', 10), $request->query->getInt('page', 1) - 1);
        
        return $this->render('default/index.html.twig', [
            'articles' => $articles,
        ]);
    }
}
