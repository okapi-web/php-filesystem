<?php

namespace Okapi\Filesystem\Exception;

/**
 * # File exists exception
 *
 * Thrown when a file already exists.
 */
class FileExistsException extends FilesystemException
{
    /**
     * FileExistsException constructor.
     *
     * @param string $path
     */
    public function __construct(string $path)
    {
        parent::__construct("Cannot create directory '$path': File exists.");
    }
}
