<?php
namespace App\Controller;

use App\Entity\Category;
use App\Entity\ImageFile;
use App\Form\FileUpdateFormType;
use App\Form\FileUploadFormType;
use App\Repository\CategoryRepository;
use App\Repository\ImageFileRepository;
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
    private $imageFileRepository;

    public function __construct(CategoryRepository $categoryRepository, ImageFileRepository $imageFileRepository)
    {
        $this->categoryRepository = $categoryRepository;
        $this->imageFileRepository = $imageFileRepository;
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
        $file = new ImageFile();
        $form = $this->createForm(FileUploadFormType::class, $file);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {

            /** @var UploadedFile $uploadedFile */
            $uploadedFile = $form['imageFile']->getData();

            if ($uploadedFile) {
                $newFilename= $uploaderHelper->uploadImageFile($uploadedFile);

                 /** @var ImageFile $file */
                $file = $form->getData();
                $file->setImageFileName($newFilename);
                $file->setImageFileTitle($uploaderHelper->getImageFileTitle($uploadedFile));

                $this->addFlash('success', 'File uploaded.');
                $entityManager->persist($file);
                $entityManager->flush();
            }
            return($this->redirectToRoute('admin_home'));
        }
        return $this->render('admin/adminFileNew.html.twig', [
            'uploadForm' => $form->createView(),
            'title' => 'Upload your art',
        ]);
    }

    /**
     * @Route("/admin/file/{id}/edit", name="admin_file_edit")
     */
    public function edit(ImageFile $file, EntityManagerInterface $entityManager, Request $request, UploaderHelper $uploaderHelper)
    {
       // dd($file->getCategories());

        $form = $this->createForm(FileUpdateFormType::class, $file);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {

            /** @var UploadedFile $uploadedFile */
            $uploadedFile = $form['imageFile']->getData();

            if ($uploadedFile) {
                $newFilename= $uploaderHelper->uploadImageFile($uploadedFile);

                /** @var ImageFile $file */
                $file = $form->getData();
                $file->setImageFileName($newFilename);
                $file->setImageFileTitle($uploaderHelper->getImageFileTitle($uploadedFile));

                $this->addFlash('success', 'Properties updated.');
                $entityManager->persist($file);
                $entityManager->flush();
            }

            return($this->redirectToRoute('admin_file_edit', [
                'id' => $file->getId(),
            ]));
        }
        return $this->render('admin/adminFileEdit.html.twig', [
            'uploadForm' => $form->createView(),
            'title' => 'Image properties',
        ]);
    }

    /**
     * @Route("/admin/file/{id}/delete)", name="admin_file_delete")
     */
    public function delete(PaginatorInterface $paginator, Request $request)
    {
        // todo delete logic
        return $this->render('admin/adminHome.html.twig');
    }
     /**
     * @Route("/admin/file/manage", name="admin_manage_files")
     */
    public function list(PaginatorInterface $paginator, Request $request) {

        $queryBuilder = $this->imageFileRepository->getAllOrderedByQueryBuilder();
        $query = $queryBuilder->getQuery();

        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1)/*page number*/,
            10/*limit per page*/
        );

        $pagination->setCustomParameters([
            'size' => 'small',
        ]);

        return $this->render('admin/manageFile.html.twig', [
            'title' => 'Manage files',
            'pagination' => $pagination
        ]);
    }

}