<?php


namespace App\Service;


use Gedmo\Sluggable\Util\Urlizer;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploaderHelper
{
    private $uploadsPath;

    public function __construct(string $uploadsPath)
    {
        $this->uploadsPath = $uploadsPath;
    }

    /**
     * @param UploadedFile $uploadedFile
     * @return string filename
     */
    public function uploadFile(UploadedFile $uploadedFile): string
    {
        $destination = $this->uploadsPath;

        // get the original filename without extension
        // create a unique filename without spaces/special chars/uppercase and with right extension
        $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
        $newFilename = Urlizer::urlize($originalFilename) . '-' . uniqid() . '.' . $uploadedFile->guessExtension();

        $uploadedFile->move(
            $destination,
            $newFilename
        );
        return $newFilename;
    }
}