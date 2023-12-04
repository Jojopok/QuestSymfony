<?php
// src/Controller/ProgramController.php
namespace App\Controller;

use App\Repository\ProgramRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CategoryRepository;
use App\Form\CategoryType;
use App\Entity\Category;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/category', name: 'category_')]
Class CategoryController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();

        return $this->render(
            'category/index.html.twig',
            ['categories' => $categories]
        );
    }

    #[Route('/new', name: 'new')]
    public function new(Request $request, EntityManagerInterface $entityManager) : Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $entityManager->persist($category);
            $entityManager->flush();
        }

        return $this->render('category/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{categoryName}', name: 'show')]
    public function show(string $categoryName, CategoryRepository $categoryRepository, ProgramRepository $programRepository): Response
    {
        // Récupérer l'objet Category par le nom
        $category = $categoryRepository->findOneBy(['name' => $categoryName]);

        // Vérifier si la catégorie existe, sinon retourner une erreur 404
        if (!$category) {
            throw $this->createNotFoundException('Category not found');
        }

        // Récupérer les séries appartenant à la catégorie, au maximum 3, ordonnées par ID décroissant
        $series = $programRepository->findBy(
            ['category' => $category],
            ['id' => 'DESC'],
            3
        );

        // Rendre la vue avec les séries et la catégorie
        return $this->render('category/show.html.twig', [
            'category' => $category,
            'series' => $series,
        ]);
    }


}