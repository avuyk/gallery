<?php


namespace App\Exception;


use Symfony\Component\HttpFoundation\File\UploadedFile;

// http://rosstuck.com/formatting-exception-messages
class CouldNotUploadImageFileException extends \Exception
{
    const WRONG_ARGUMENT_OR_FILE_EXISTS = 'Wrong argument or file exists, ClientOriginalName: %s, Exception %s';
    const COULD_NOT_WRITE_STREAM = 'Could not write stream, ClientOriginalName: %s';

    public function __construct(string $reason, \Throwable $previous = null)
    {
        parent::__construct($reason, 0, $previous);
    }

    public static function invalidStreamOrFileExist(UploadedFile $uploadedFile, \Throwable $previous)
    {
        return new self(
            sprintf(
                self::WRONG_ARGUMENT_OR_FILE_EXISTS,
                $uploadedFile->getClientOriginalName(),
                $previous->getMessage()
            )
        );
    }

    public static function couldNotWriteStream(UploadedFile $uploadedFile)
    {
        return new self(sprintf(self::COULD_NOT_WRITE_STREAM, $uploadedFile->getClientOriginalName()));
    }
}
