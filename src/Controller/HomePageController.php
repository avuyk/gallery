<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use Faker\Factory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomePageController extends AbstractController
{
    private $faker;

    public function __construct()
    {
        $this->faker = Factory::create();
    }

    /**
     * @Route("/", name="app_homepage")
     * @param CategoryRepository $categoryRepository
     * @return Response Twig template
     */
    public function homepage(CategoryRepository $categoryRepository)
    {
        $categoryDescription = $this->faker->sentence(23, true);
        $categories = $categoryRepository->findAll();
        return $this->render('home.html.twig', [
            'categories' => $categories,
            'categoryDescription' => $categoryDescription,
        ]);
    }
}
