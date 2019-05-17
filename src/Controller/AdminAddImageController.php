<?php
namespace App\Controller;

use App\Entity\ImageFile;
use App\Exception\CouldNotSaveImageFileException;
use App\Exception\CouldNotUploadImageFileException;
use App\Form\FileUploadFormType;
use App\Repository\CategoryRepository;
use App\Repository\ImageFileRepository;
use App\Service\UploaderHelper;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminAddImageController extends AbstractController
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
     * @Route("/admin/image/new", name="admin_add_image")
     * @param Request $request
     * @param UploaderHelper $uploaderHelper
     * @param CategoryRepository $categoryRepository
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function new(
        Request $request,
        UploaderHelper $uploaderHelper,
        CategoryRepository $categoryRepository
    ) {
        $categories = $categoryRepository->findAll();

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
            return ($this->redirectToRoute('admin_manage_files'));
        }
        return $this->render('admin/newImage.html.twig', [
            'uploadForm' => $form->createView(),
            'title' => 'Upload your art',
            'categories' => $categories,
        ]);
    }
}
