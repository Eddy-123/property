<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\PropertyRepository;


class MainController extends AbstractController
{    
    /**
     * @Route("/", name="main_home")
     */
    public function home(PropertyRepository $repository): Response
    {
        $properties = $repository->findLatest();
        return $this->render('main/home.html.twig',[
            'properties' => $properties
        ]);
    }
}
