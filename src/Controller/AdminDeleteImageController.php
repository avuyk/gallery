<?php
namespace App\Controller;

use App\Entity\ImageFile;
use App\Service\UploaderHelper;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminDeleteImageController extends AbstractController
{
    /**
     * @Route("/admin/image/{id}/delete", name="admin_image_delete", methods={"DELETE"})
     * @param ImageFile $imageFile
     * @param UploaderHelper $uploaderHelper
     * @param EntityManagerInterface $entityManager
     * @param LoggerInterface $logger
     * @return Response
     */
    public function deleteImageFile(
        ImageFile $imageFile,
        UploaderHelper $uploaderHelper,
        EntityManagerInterface $entityManager,
        LoggerInterface $logger
    ) {
        $entityManager->beginTransaction();
        try {
            $entityManager->remove($imageFile);
            $entityManager->flush();
            $uploaderHelper->deleteImageFile($imageFile->getImageFilePath());
            $entityManager->commit();
        } catch (\Exception $exception) {
            $entityManager->rollback();
            $logger->error('could not remove image, exception: ' . $exception->getMessage());
            return new Response(null, Response::HTTP_EXPECTATION_FAILED);
        }
        return new Response(null, Response::HTTP_NO_CONTENT);
    }
}
