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
     * @Route("/",
     *  name="homepage",
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
    public function indexAction(Request $request, int $page, int $per_page, EntityRepository $articleRepository)
    {
        $articles = $articleRepository->findBy([], null, $per_page, $page - 1);
        
        return $this->render('default/index.html.twig', [
            'articles' => $articles,
        ]);
    }
}
