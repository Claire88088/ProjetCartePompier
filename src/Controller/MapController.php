<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class MapController extends AbstractController
{
    #[Route('/map', name: 'map')]
    public function index(EntityManagerInterface $em, Request $request): Response
    {
        $calques = $em->getRepository('App:Calque')->findAll();

        return $this->render('map/index.html.twig', [
            'calques' => $calques,
        ]);

    }
}
