<?php

namespace App\Controller;

use App\Entity\Actor;
use App\Repository\ActorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/actor', name: 'actor_')]
class ActorController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('Actor/index.html.twig', [

        ]);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(Actor $actor): Response
    {
        return $this->render('actor/show.html.twig', [
            'actor' => $actor,
        ]);
    }

    #[Route('/actor/show-actor-links', name: 'show_actor_links', methods: ['GET'])]
    public function showActorLinks(ActorRepository $actorRepository): Response
    {
        $actors = $actorRepository->findAll();

        return $this->render('actor/_actor_links.html.twig', [
            'actors' => $actors,
        ]);
    }
}