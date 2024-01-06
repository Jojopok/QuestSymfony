<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function index(): Response
    {
        return $this->render('index.html.twig', [
            'message' => 'Bienvenue !',
        ]);
    }

    #[Route('/my-profile', name: 'app_profile')]
    public function profile(): Response
    {
        return $this->render('security/profile.html.twig');
    }
}