<?php

namespace Okapi\Filesystem\Exception;

/**
 * # FileOrDirectoryNotWritableException
 *
 * Exception thrown when a file or directory is not writable.
 */
class FileOrDirectoryNotWritableException extends FilesystemException
{
    /**
     * FileOrDirectoryNotWritableException constructor.
     *
     * @param string $path
     */
    public function __construct(string $path)
    {
        parent::__construct("File or directory '$path' is not writable.");
    }
}
