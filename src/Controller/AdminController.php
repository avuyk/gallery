<?php
namespace App\Controller;

use App\Entity\File;
use App\Form\FileUploadFormType;
use App\Repository\CategoryRepository;
use App\Repository\FileRepository;
use App\Service\UploaderHelper;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
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
    public function new(EntityManagerInterface $entityManager, Request $request, UploaderHelper $uploaderHelper)
    {
        $form = $this->createForm(FileUploadFormType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {

            /** @var UploadedFile $uploadedFile */
            $uploadedFile = $form['imageFile']->getData();

            if ($uploadedFile) {
                $newFilename= $uploaderHelper->uploadFile($uploadedFile);

                /** @var File $file */
                $file = $form->getData();
                $file->setFileName($newFilename);

                $this->addFlash('success', 'File uploaded.');
                $entityManager->persist($file);
                $entityManager->flush();
            }
            return($this->redirectToRoute('admin_add_file'));
        }
        return $this->render('admin/adminFileNew.html.twig', [
            'uploadForm' => $form->createView(),
            'title' => 'Upload your art',
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
            'title' => 'Available files',
            'pagination' => $pagination
        ]);
    }

}