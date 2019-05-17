<?php
namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Service\PaginationHelper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminManageImagesController extends AbstractController
{
    /**
     * @Route("/admin/images/manage", name="admin_manage_files")
     * @param Request $request
     * @param PaginationHelper $paginationHelper
     * @param CategoryRepository $categoryRepository
     * @return Response Twig template
     */
    public function manageImages(
        Request $request,
        PaginationHelper $paginationHelper,
        CategoryRepository $categoryRepository
    )
    {
        $categories = $categoryRepository->findAll();
        $pagination = $paginationHelper->queryAndPaginate($request, 10);

        return $this->render('admin/manageImages.html.twig', [
            'title' => 'Manage images',
            'categories' => $categories,
            'pagination' => $pagination
        ]);
    }
}

