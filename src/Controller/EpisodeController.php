<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\EpisodeType;
use App\Repository\EpisodeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Program;
use App\Entity\Season;
use App\Entity\Episode;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/episode')]
class EpisodeController extends AbstractController
{
    #[Route('/', name: 'app_episode_index', methods: ['GET'])]
    public function index(EpisodeRepository $episodeRepository): Response
    {
        return $this->render('episode/index.html.twig', [
            'episodes' => $episodeRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_episode_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger, MailerInterface $mailer): Response
    {
        $episode = new Episode();
        $form = $this->createForm(EpisodeType::class, $episode);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slug = $slugger->slug($episode->getTitle());
            $episode->setSlug($slug);
            $entityManager->persist($episode);
            $entityManager->flush();

            $this->addFlash('success', 'Le nouvel épisode a été créé');

            $email = (new Email())
                ->from($this->getParameter('mailer_from'))
                ->to($this->getParameter('mailer_from'))
                ->subject('Un nouvel épisode vient d\'être publiée !')
                ->html($this->renderView('Episode/newEpisodeEmail.html.twig', [
                    'episode' => $episode,
                ]));

            $mailer->send($email);

            return $this->redirectToRoute('app_episode_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('episode/new.html.twig', [
            'episode' => $episode,
            'form' => $form,
        ]);
    }

    #[Route('/{slug}', name: 'app_episode_show', methods: ['GET'])]
    public function show(Episode $episode, SluggerInterface $slugger): Response
    {
        $slug = $slugger->slug($episode->getTitle());
        $episode->setSlug($slug);
        return $this->render('episode/show.html.twig', [
            'episode' => $episode,
        ]);
    }

    #[Route('/{slug}/season/{season}/episode/{episode}', name: 'episode_show')]
    #[Route('/{slug}/edit', name: 'app_episode_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Episode $episode, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(EpisodeType::class, $episode);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slug = $slugger->slug($episode->getTitle());
            $episode->setSlug($slug);
            $entityManager->flush();

            $this->addFlash('success', 'L\'épisode a été modifié');

            return $this->redirectToRoute('app_episode_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('episode/edit.html.twig', [
            'episode' => $episode,
            'form' => $form,
        ]);
    }

    #[Route('/{slug}', name: 'app_episode_delete', methods: ['POST'])]
    public function delete(Request $request, Episode $episode, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        if ($this->isCsrfTokenValid('delete'.$episode->getId(), $request->request->get('_token'))) {
            $slug = $slugger->slug($episode->getTitle());
            $episode->setSlug($slug);
            $entityManager->remove($episode);
            $entityManager->flush();
        }

        $this->addFlash('danger', 'L\'épisode a été supprimé');

        return $this->redirectToRoute('app_episode_index', [], Response::HTTP_SEE_OTHER);
    }
}