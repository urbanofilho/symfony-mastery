<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="default")
     */
    public function index()
    {
    
        $name = "urbano filho";
      return $this->render('index.html.twig',[
          'name' => $name
      ]);
    }

    /**
     * @Route("/product/{slug}", name="product_single")
     */
    public function product($slug)
    {
    
      return $this->render('single.html.twig',compact('slug'));
    }
}
