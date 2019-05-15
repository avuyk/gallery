<?php
namespace App\Controller;

use App\Entity\ImageFile;
use App\Exception\CouldNotSaveImageFileException;
use App\Exception\CouldNotUploadImageFileException;
use App\Form\FileUploadFormType;
use App\Repository\CategoryRepository;
use App\Repository\ImageFileRepository;
use App\Service\UploaderHelper;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    private $categoryRepository;
    private $imageFileRepository;
    private $logger;

    public function __construct(
        CategoryRepository $categoryRepository,
        ImageFileRepository $imageFileRepository,
        LoggerInterface $logger
    ) {
        $this->categoryRepository = $categoryRepository;
        $this->imageFileRepository = $imageFileRepository;
        $this->logger = $logger;
    }

    /**
     * @Route("/admin", name="admin_home")
     */
    public function adminHome()
    {
        return $this->render('admin/admin.html.twig', [
            'title' => 'Admin Home',
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
        if ($form->isSubmitted() && $form->isValid()) {

            /** @var UploadedFile $uploadedFile */
            $uploadedFile = $form['imageFile']->getData();

            if ($uploadedFile) {
                try {
                    $newFilename = $uploaderHelper->uploadImageFile($uploadedFile);
                } catch (CouldNotUploadImageFileException $exception) {
                    $this->logger->error($exception->getMessage());
                    $this->addFlash('error', 'Could not upload file, please try again later.');
                }

                /** @var ImageFile $file */
                $file = $form->getData();
                $file->setImageFileName($newFilename);
                $file->setImageFileTitle($uploaderHelper->getImageFileTitle($uploadedFile, $file));

                $this->addFlash('success', 'Thanks for your image!');
                try {
                    $this->imageFileRepository->save($file);
                } catch (CouldNotSaveImageFileException $exception) {
                    $this->logger->error('could not save image, exception: ' . $exception->getMessage());
                    $this->addFlash('error', 'Could not create new image, please try again later!');
                }
            }
            return($this->redirectToRoute('admin_manage_files'));
        }
        return $this->render('admin/newImage.html.twig', [
            'uploadForm' => $form->createView(),
            'title' => 'Upload your art',
        ]);
    }

    /**
     * @Route("/admin/file/{id}/edit", name="admin_file_edit")
     */
    public function edit(
        ImageFile $file,
        EntityManagerInterface $entityManager,
        Request $request,
        UploaderHelper $uploaderHelper
    ) {
       // dd($file->getCategories());

        $form = $this->createForm(FileUploadFormType::class, $file);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            /** @var ImageFile $file */
            $file = $form->getData();
            $file->setImageFileTitle($uploaderHelper->normalizeImageTitle($file->getImageFileTitle()));
            $this->addFlash('success', sprintf('Hi, you updated %s!', $file->getImageFileTitle()));

            try {
                $this->imageFileRepository->save($file);
            } catch (CouldNotSaveImageFileException $exception) {
                $this->logger->error('could not edit image properties, exception: ' . $exception->getMessage());
                $this->addFlash('error', 'Could not edit image properties, please try again later!');
            }

            return($this->redirectToRoute('admin_manage_files', [
                'id' => $file->getId(),
            ]));
        }
        return $this->render('admin/editImageProps.html.twig', [
            'uploadForm' => $form->createView(),
            'title' => 'Edit properties',
        ]);
    }

    /**
     * @Route("/admin/file/{id}/delete", name="admin_file_delete", methods={"DELETE"})
     */
    public function deleteImageFile(
        ImageFile $imageFile,
        UploaderHelper $uploaderHelper,
        EntityManagerInterface $entityManager
    ) {
        //$this->denyAccessUnlessGranted('MANAGE', $imageFile);
        $entityManager->beginTransaction();
        try {
            $entityManager->remove($imageFile);
            $entityManager->flush();
            $uploaderHelper->deleteImageFile($imageFile->getImageFilePath());
            $entityManager->commit();
        } catch (\Exception $exception) {
            $entityManager->rollback();
            $this->logger->error('could not remove image, exception: ' . $exception->getMessage());
            return new Response(null, Response::HTTP_EXPECTATION_FAILED);
        }
        return new Response(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @Route("/admin/file/manage", name="admin_manage_files")
     */
    public function list(PaginatorInterface $paginator, Request $request)
    {
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

        return $this->render('admin/manageImages.html.twig', [
            'title' => 'Manage images',
            'pagination' => $pagination
        ]);
    }
}
