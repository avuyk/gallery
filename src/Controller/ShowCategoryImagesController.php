<?php


namespace App\Controller;


use App\Repository\CategoryRepository;
use App\Repository\ImageFileRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ShowCategoryImagesController extends AbstractController
{
    private $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @Route("/category/{name}", name="show_images_from_category")
     */
    public function showImagesFromCategory($name, ImageFileRepository $imageFileRepository)
    {
        $category = $this->categoryRepository->findBy(['categoryName' => $name]);
        var_dump($category);die;
        $images = $imageFileRepository->findBy(['categories' => $name]);
        dd($images);
        return $this->render('show_images_from_category.html.twig', [
            'title' => 'Images in this category',
            'name' => 'name',
        ]);
    }
}
