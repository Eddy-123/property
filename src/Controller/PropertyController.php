<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Property;
use App\Repository\PropertyRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @Route("/proprietes", name="property_")
 */
class PropertyController extends AbstractController
{
    /**
     * @var PropertyRepository
     */
    private $repository;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    
    public function __construct(PropertyRepository $repository, EntityManagerInterface $entityManager)
    {
        $this->repository = $repository;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/", name="home")
     */
    public function home(): Response
    {
        $properties = $this->repository->findAllVisible();
        return $this->render('property/home.html.twig');
    }

    /**
     * @Route("/{slug}-{id}", name="show", requirements={"slug": "[a-z0-9\-]*"})
     */
    public function show(Property $property, $slug): Response
    {
        if($slug !== $property->getSlug()){
            return $this->redirectToRoute("property_show", [
                "id" => $property->getId(),
                "slug" => $property->getSlug()
            ], 301);
        }
        return $this->render('property/show.html.twig', [
            "property" => $property
        ]);
    }
}
