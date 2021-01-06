<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\PropertyRepository;
use App\Entity\Property;
use App\Form\PropertyType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @Route("/administration", name="admin_property_")
 */
class AdminPropertyController extends AbstractController
{
    /**
     * @var PropertyRepository
     */
    private $repository;
    
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(PropertyRepository $repository, EntityManagerInterface $entityManager) {
        $this->repository = $repository;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/", name="home")
     */
    public function home(): Response
    {
        $properties = $this->repository->findAll();
        return $this->render('admin/admin_property/home.html.twig', [
            "properties" => $properties
        ]);
    }
    
    /**
     * @Route("/nouveau", name="create")
     */
    public function create(Request $request): Response
    {
        $property = new Property();
        $form = $this->createForm(PropertyType::class, $property);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid())
        {
            $this->entityManager->persist($property);
            $this->entityManager->flush();
            $this->addFlash('success', 'Element created successfully');
            return $this->redirectToRoute("admin_property_home");
        }
        
        return $this->render("admin/admin_property/create.html.twig", [
            "form" => $form->createView()
        ]);
    }


    /**
     * @Route("/{id}", name="edit", methods="POST|GET")
     */
    public function edit(Property $property, Request $request) {
        $form = $this->createForm(PropertyType::class, $property);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $this->entityManager->flush();
            $this->addFlash('success', 'Element updated successfully');
            return $this->redirectToRoute("admin_property_home");
        }
        return $this->render("admin/admin_property/edit.html.twig", [
            "form" => $form->createView()
        ]);
    }
    
    /**
     * @Route("/{id}", name="delete", methods="DELETE")
     */
    public function delete(Property $property, Request $request)
    {
        if($this->isCsrfTokenValid('delete' . $property->getId(), $request->get('_token')))
        {
            $this->entityManager->remove($property);
            $this->entityManager->flush();
            $this->addFlash('success', "Element deleted successfully");
        }
        return $this->redirectToRoute("admin_property_home");
    }
}
