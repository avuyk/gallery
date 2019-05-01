<?php
namespace App\Controller;

use App\Entity\Category;
use App\Entity\File;
use App\Form\FileUploadFormType;
use App\Repository\CategoryRepository;
use App\Repository\FileRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    private $categoryRepository;
    private $fileRepository;

    public function __construct(CategoryRepository $categoryRepository, FileRepository $fileRepository)
    {
        $this->categoryRepository = $categoryRepository;
        $this->fileRepository = $fileRepository;
    }

    /**
     * @Route("/admin", name="admin_home")
     */
    public function adminHome() {
        return $this->render('admin/adminHome.html.twig',[
            'title'=>'Admin Home',
        ]);
    }

    /**
     * @Route("/admin/file/new", name="admin_add_file")
     */
    public function new(EntityManagerInterface $entityManager, Request $request)
    {
        $form = $this->createForm(FileUploadFormType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('success', 'File uploaded.');
            /** @var File $file */
            $file = $form->getData();
            $entityManager->persist($file);
            $entityManager->flush();
            return($this->redirectToRoute('admin_add_file'));
        }
        return $this->render('admin/adminFileNew.html.twig', [
            'form' => $form->createView(),
            'title' => 'Upload a file',
        ]);
    }

    /**
     * @Route("/admin/file/list", name="admin_list_files")
     */
    public function list(PaginatorInterface $paginator, Request $request) {

        $queryBuilder = $this->fileRepository->getAllOrderedByQueryBuilder();
        $query = $queryBuilder->getQuery();

        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1)/*page number*/,
            10/*limit per page*/
        );

        return $this->render('admin/adminList.html.twig', [
            'title' => 'List of files',
            'pagination' => $pagination
        ]);
    }

}