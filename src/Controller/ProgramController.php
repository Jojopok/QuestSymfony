<?php
// src/Controller/ProgramController.php
namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProgramRepository;
use App\Repository\SeasonRepository;
use App\Form\ProgramType;
use App\Entity\Program;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/program', name: 'program_')]
Class ProgramController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(ProgramRepository $repository): Response
    {
        $programs = $repository->findAll();

        return $this->render('program/index.html.twig', ['programs' => $programs]);
    }

    #[Route('/new', name: 'new')]
    public function new(Request $request, EntityManagerInterface $entityManager) : Response
    {
        $program = new Program();
        $form = $this->createForm(ProgramType::class, $program);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $entityManager->persist($program);
            $entityManager->flush();
        }

        return $this->render('program/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/show/{id<^[0-9]+$>}', name: 'show')]
    public function show(int $id, ProgramRepository $repository): Response
    {
        $program = $repository->findOneBy(['id' => $id]);

        if (!$program) {
            throw $this->createNotFoundException('No program with id: ' . $id . ' found in the program\'s table.');
        }

        return $this->render('program/show.html.twig', ['program' => $program]);
    }

    #[Route('/{programId}/seasons/{seasonId}', name: 'season_show')]
    public function showSeason(int $programId, int $seasonId, ProgramRepository $programRepository, SeasonRepository $seasonRepository): Response
    {
        $program = $programRepository->findOneBy(['id' => $programId]);

        if (!$program) {
            throw $this->createNotFoundException('No program with id: ' . $programId . ' found in the program\'s table.');
        }

        $season = $seasonRepository->findOneBy(['id' => $seasonId, 'program' => $program]);

        if (!$season) {
            throw $this->createNotFoundException('No season found for the given program.');
        }

        return $this->render('program/season_show.html.twig', ['program' => $program, 'season' => $season]);
    }
}