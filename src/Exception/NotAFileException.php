<?php

namespace Okapi\Filesystem\Exception;

/**
 * # Not a file exception
 *
 * Thrown when a file is expected but a directory is found.
 */
class NotAFileException extends FilesystemException
{
    /**
     * NotAFileException constructor.
     *
     * @param string $path
     */
    public function __construct(string $path)
    {
        parent::__construct("Cannot write to the provided path '$path' because it is a directory.");
    }
}
