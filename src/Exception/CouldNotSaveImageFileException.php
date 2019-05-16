<?php

namespace App\Exception;

use Doctrine\ORM\ORMException;

// http://rosstuck.com/formatting-exception-messages
class CouldNotSaveImageFileException extends \Exception
{
    public static function forError(ORMException $error)
    {
        return new self('Database by ORM not available', 0, $error);
    }
}
