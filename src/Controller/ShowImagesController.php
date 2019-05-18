<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\ImageFileRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ShowImagesController extends AbstractController
{
    /**
     * @Route("/category/{categoryName}", name="show_images_from_category")
     * @param string $categoryName
     * @param ImageFileRepository $imageFileRepository
     * @param CategoryRepository $categoryRepository
     * @return Response Twig template
     */
    public function showImages(
        $categoryName,
        ImageFileRepository $imageFileRepository,
        CategoryRepository $categoryRepository
    ) {
        $categories = $categoryRepository->findAll();
        $allImagesInCategory = $imageFileRepository->getAllImageFilesInCategory($categoryName);

        return $this->render('show_images_from_category.html.twig', [
            'title' => 'Images in this category',
            'categoryName' => $categoryName,
            'allImagesInCategory' => $allImagesInCategory,
            'categories' => $categories,
        ]);
    }
}
