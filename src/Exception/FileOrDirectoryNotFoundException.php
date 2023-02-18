<?php

namespace Okapi\Filesystem\Exception;

/**
 * # File or Directory Not Found Exception
 *
 * Exception for file or directory not found errors.
 */
class FileOrDirectoryNotFoundException extends FilesystemException
{
    /**
     * FileOrDirectoryNotFoundException constructor.
     *
     * @param string $fileOrDir
     */
    public function __construct(string $fileOrDir)
    {
        parent::__construct(
            "File or directory '$fileOrDir' not found.",
        );
    }
}
