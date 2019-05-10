<?php


namespace App\Service;


use App\Entity\ImageFile;
use Gedmo\Sluggable\Util\Urlizer;
use League\Flysystem\FileNotFoundException;
use League\Flysystem\FilesystemInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Asset\Context\RequestStackContext;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploaderHelper
{

    const GALLERY = 'gallery';

    private $uploadsPath;

    private $requestStackContext;

    private $publicUploadsFilesystem;

    private $logger;

    public function __construct(FilesystemInterface $publicUploadsFilesystem, string $uploadsPath, RequestStackContext $requestStackContext, LoggerInterface $logger)
    {
        $this->uploadsPath = $uploadsPath;
        $this->requestStackContext = $requestStackContext;
        $this->publicUploadsFilesystem = $publicUploadsFilesystem;
        $this->logger = $logger;
    }

    /**
     * @param UploadedFile $uploadedFile
     * @return string filename
     * Processes original filename, removes extension
     * Returns unique filename without spaces/special chars/uppercase and with guessed extension
     */
    private function getNormalizedOriginalImageFilename(UploadedFile $uploadedFile) {

        $originalImageFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
        $normalizedOriginalImageFilename = Urlizer::urlize($originalImageFilename);
        return $normalizedOriginalImageFilename;
    }

    /**
     * @param UploadedFile $uploadedFile
     * @return string filename
     */
    public function getImageFileTitle(UploadedFile $uploadedFile, ImageFile $imageFile): string
    {
        // set title to users' input if present, otherwise to filename
        $title = $imageFile->getImageFileTitle();
        $imageTitle = $this->getNormalizedOriginalImageFilename($uploadedFile);
        return $title ?? $imageTitle;
    }

    public function normalizeImageTitle(string $title): string
    {
        $normalizedTitle = Urlizer::urlize($title);
        return $normalizedTitle;
    }

    /**
     * @param UploadedFile $uploadedFile
     * @return string filename
     */
    public function uploadImageFile(UploadedFile $uploadedFile): string
    {
        $newImageFilename = $this->getNormalizedOriginalImageFilename($uploadedFile) . '-' . uniqid() . '.' . $uploadedFile->guessExtension();

        $stream = fopen($uploadedFile->getPathname(), 'r');
        $result = $this->publicUploadsFilesystem->writeStream(
            '/'.self::GALLERY.'/'.$newImageFilename,
            $stream
        );
        if ($result === false) {
            throw new \Exception(sprintf('Could not write uploaded file "%s"', $newImageFilename));
        }
        if (is_resource($stream)) {
            fclose($stream);
        }
        return $newImageFilename;
    }

    public function getPublicPath(string $path): string
    {
        // part of, and normally handled by the Twig asset() function
        // to determine if there should be a slash or subdirectory prepended
        return $this->requestStackContext
                ->getBasePath().'/images/'.$path;
    }

    public function deleteImageFile(string $fileName)
    {
        $filesystem = $this->publicUploadsFilesystem;

        try {
            $result = $filesystem->delete('/'.self::GALLERY.'/'.$fileName);
            if($result === false) {
                throw new \Exception(sprintf('Error deleting "%s"', $fileName));
            }
        } catch (FileNotFoundException $e) {
            $this->logger->alert(sprintf('File "%s" was missing when trying to delete', $fileName));
        }
    }

}