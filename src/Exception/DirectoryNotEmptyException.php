<?php

namespace Okapi\Filesystem\Exception;

/**
 * # Directory Not Empty Exception
 *
 * Thrown when a directory is not empty.
 */
class DirectoryNotEmptyException extends FilesystemException
{
    /**
     * DirectoryNotEmptyException constructor.
     *
     * @param string $path
     */
    public function __construct(string $path)
    {
        parent::__construct("Directory '$path' is not empty.");
    }
}
