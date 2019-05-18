<?php
namespace App\Controller;

use App\Entity\ImageFile;
use App\Exception\CouldNotSaveImageFileException;
use App\Form\FileUploadFormType;
use App\Repository\CategoryRepository;
use App\Repository\ImageFileRepository;
use App\Service\UploaderHelper;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminEditImagePropsController extends AbstractController
{
    /**
     * @Route("/admin/image/{id}/edit", name="admin_image_edit")
     * @param ImageFile $file
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @param UploaderHelper $uploaderHelper
     * @param ImageFileRepository $imageFileRepository
     * @param LoggerInterface $logger
     * @param CategoryRepository $categoryRepository
     * @return RedirectResponse|Response
     */
    public function edit(
        ImageFile $file,
        EntityManagerInterface $entityManager,
        Request $request,
        UploaderHelper $uploaderHelper,
        ImageFileRepository $imageFileRepository,
        LoggerInterface $logger,
        CategoryRepository $categoryRepository
    ) {

        $form = $this->createForm(FileUploadFormType::class, $file);
        $categories = $categoryRepository->findAll();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            /** @var ImageFile $file */
            $file = $form->getData();
            $file->setImageFileTitle($uploaderHelper->normalizeImageTitle($file->getImageFileTitle()));
            $this->addFlash('success', sprintf('Hi, you updated %s!', $file->getImageFileTitle()));

            try {
                $imageFileRepository->save($file);
            } catch (CouldNotSaveImageFileException $exception) {
                $logger->error('could not edit image properties, exception: ' . $exception->getMessage());
                $this->addFlash('error', 'Could not edit image properties, please try again later!');
            }

            return($this->redirectToRoute('admin_manage_files', [
                'id' => $file->getId(),
            ]));
        }
        return $this->render('admin/editImageProps.html.twig', [
            'uploadForm' => $form->createView(),
            'title' => 'Edit properties',
            'categories' => $categories,
        ]);
    }
}
