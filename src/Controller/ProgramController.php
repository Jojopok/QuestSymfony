<?php
    // src/Controller/ProgramController.php
    namespace App\Controller;

    use App\Entity\Category;
    use App\Entity\Episode;
    use App\Form\CategoryType;
    use App\Form\EpisodeType;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use App\Repository\ProgramRepository;
    use App\Repository\SeasonRepository;
    use App\Form\ProgramType;
    use App\Entity\Program;
    use Symfony\Component\HttpFoundation\Request;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Component\HttpFoundation\Session\SessionInterface;
    use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
    use Symfony\Component\String\Slugger\SluggerInterface;
    use App\Service\ProgramDuration;
    use App\Entity\Season;
    use Symfony\Component\Mailer\MailerInterface;
    use Symfony\Component\Mime\Email;


    #[Route('/program', name: 'program_')]
    Class ProgramController extends AbstractController
    {
        #[Route('/', name: 'index')]
        public function index(ProgramRepository $repository): Response
        {
            $programs = $repository->findAll();

            return $this->render('program/index.html.twig', ['programs' => $programs]);
        }


        #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
        public function new(MailerInterface $mailer, Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
        {
            $program = new Program();
            $form = $this->createForm(ProgramType::class, $program);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $slug = $slugger->slug($program->getTitle())->lower();
                $program->setSlug($slug);


                $entityManager->persist($program);
                $entityManager->flush();

                $this->addFlash('success', 'Bravo ! La série a été créée avec succès.');

                $email = (new Email())
                    ->from($this->getParameter('mailer_from'))
                    ->to($this->getParameter('mailer_from'))
                    ->subject('Une nouvelle série vient d\'être publiée !')
                    ->html($this->renderView('Program/newProgramEmail.html.twig', [
                        'program' => $program,
                    ]));

                $mailer->send($email);

                return $this->redirectToRoute('program_index');

            }

            return $this->render('program/new.html.twig', [
                'form' => $form,
            ]);
        }

        #[Route('/show/{slug}', name: 'show')]
        public function show(string $slug, ProgramRepository $repository, ProgramDuration $programDuration): Response
        {
            $program = $repository->findOneBy(['slug' => $slug]);

            if (!$program) {
                throw $this->createNotFoundException('No program with id: ' . $slug . ' found in the program\'s table.');
            }

            $duration = $programDuration->calculate($program);

            return $this->render('program/show.html.twig', ['program' => $program,
                'programDuration' => $duration]);
        }

        #[Route('/{slug}/season/{number}', name: 'season_show')]
        public function showSeason( Program $program, Season $season, SluggerInterface $slugger): Response
        {
            $slug = $slugger->slug($program->getTitle());
            $program->setSlug($slug);
            return $this->render('program/season_show.html.twig', [
                'program' => $program,
                'season' => $season,
            ]);
        }

        #[Route('/edit/{slug}', name: 'edit', methods: ['GET', 'POST'])]
        public function edit(Request $request, Program $program, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
        {
            $form = $this->createForm(ProgramType::class, $program);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $slug = $slugger->slug($program->getTitle());
                $program->setSlug($slug);
                $entityManager->flush();

                $this->addFlash('success', 'La série a été modifié');

                return $this->redirectToRoute('program_index');
            }

            return $this->render('program/new.html.twig', [
                'program' => $program,
                'form' => $form,
            ]);
        }

        #[Route('/delete/{slug}', name: 'delete', methods: ['POST'])]
        public function delete(Request $request, Program $program, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
        {
            if ($this->isCsrfTokenValid('delete'.$program->getId(), $request->request->get('_token'))) {
                $slug = $slugger->slug($program->getTitle());
                $program->setSlug($slug);
                $entityManager->remove($program);
                $entityManager->flush();
            }

            $this->addFlash('danger', 'La série a été supprimé');

            return $this->redirectToRoute('program_index' , [], Response::HTTP_SEE_OTHER);
        }
    }