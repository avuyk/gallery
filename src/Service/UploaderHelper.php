<?php


namespace App\Service;


use Gedmo\Sluggable\Util\Urlizer;
use League\Flysystem\FilesystemInterface;
use Symfony\Component\Asset\Context\RequestStackContext;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploaderHelper
{

    const GALLERY = 'gallery';

    private $uploadsPath;

    private $requestStackContext;

    private $publicUploadsFilesystem;

    public function __construct(FilesystemInterface $publicUploadsFilesystem, string $uploadsPath, RequestStackContext $requestStackContext)
    {
        $this->uploadsPath = $uploadsPath;
        $this->requestStackContext = $requestStackContext;
        $this->publicUploadsFilesystem = $publicUploadsFilesystem;
    }

    private function getNormalizedOriginalImageFilename(UploadedFile $uploadedFile) {

        // get the original filename without extension
        // create a unique filename without spaces/special chars/uppercase and with right extension
        $originalImageFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
        $normalizedOriginalImageFilename = Urlizer::urlize($originalImageFilename);
        return $normalizedOriginalImageFilename;
    }

    /**
     * @param UploadedFile $uploadedFile
     * @return string filename
     */
    public function uploadImageFile(UploadedFile $uploadedFile): string
    {
        $destination = $this->uploadsPath;
        $newImageFilename = $this->getNormalizedOriginalImageFilename($uploadedFile) . '-' . uniqid() . '.' . $uploadedFile->guessExtension();

//        $this->publicUploadsFilesystem->write(
//          '/'.$newImageFilename,
//            file_get_contents($uploadedFile->getPathname())
//        );

        $uploadedFile->move(
            $destination,
            $newImageFilename
        );
        return $newImageFilename;
    }

    public function getImageFileTitle(UploadedFile $uploadedFile): string
    {
        $imageTitle = $this->getNormalizedOriginalImageFilename($uploadedFile);
        return $imageTitle;
    }

    public function getPublicPath(string $path): string
    {
        // part of, and normally handled by the Twig asset() function
        // to determine if there should be a slash or subdirectory prepended
        return $this->requestStackContext
                ->getBasePath().'/images/'.$path;
    }
}