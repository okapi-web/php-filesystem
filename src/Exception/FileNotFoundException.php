<?php

namespace Okapi\Filesystem\Exception;

/**
 * # File Not Found Exception
 *
 * This exception is thrown when a file is not found.
 */
class FileNotFoundException extends FilesystemException
{
    /**
     * FileNotFoundException constructor.
     *
     * @param string $path
     */
    public function __construct(string $path)
    {
        parent::__construct("File '$path' not found.");
    }
}
