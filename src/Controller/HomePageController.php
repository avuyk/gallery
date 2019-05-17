<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomePageController extends AbstractController
{
    private $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @Route("/", name="app_homepage")
     */
    public function homepage()
    {
        $categories = $this->categoryRepository->findAll();
        return $this->render('home.html.twig', [
            'categories' => $categories,
        ]);
    }
}
