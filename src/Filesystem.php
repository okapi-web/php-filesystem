<?php

namespace Okapi\Filesystem;

use Okapi\Filesystem\Exception\DirectoryNotEmptyException;
use Okapi\Filesystem\Exception\FileExistsException;
use Okapi\Filesystem\Exception\FileOrDirectoryNotWritableException;
use Okapi\Filesystem\Exception\FileNotFoundException;
use Okapi\Filesystem\Exception\FileOrDirectoryNotFoundException;
use Okapi\Filesystem\Exception\NotAFileException;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;

/**
 * # Filesystem
 *
 * Filesystem abstraction.
 */
class Filesystem
{
    /**
     * Write a file.
     *
     * @param string $path
     * @param string $content
     * @param int    $fileMode
     *
     * @return void
     */
    public static function writeFile(string $path, string $content, int $fileMode = 0770): void
    {
        // Check if path is a directory
        if (is_dir($path)) {
            throw new NotAFileException($path);
        }

        // Create parent directory if it does not exist
        $parentDir = dirname($path);
        if (!is_dir($parentDir)) {
            self::mkdir($parentDir, 0777, true);
        }

        // Create file
        file_put_contents($path, $content, LOCK_EX);

        // Set file mode
        chmod($path, $fileMode);
    }

    /**
     * Read a file.
     *
     * @param string $path
     *
     * @return string
     */
    public static function readFile(string $path): string
    {
        // Check if path is a directory
        if (is_dir($path)) {
            throw new NotAFileException($path);
        }

        if (!is_file($path)) {
            throw new FileNotFoundException($path);
        }

        return file_get_contents($path);
    }

    /**
     * Remove a file or directory.
     *
     * @param string $path
     * @param bool   $recursive
     * @param bool   $force
     *
     * @return void
     */
    public static function rm(
        string $path,
        bool $recursive = false,
        bool $force = false,
    ): void {
        if ($recursive && is_dir($path)) {
            $it = new RecursiveDirectoryIterator($path);
            $it = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::CHILD_FIRST);

            foreach ($it as $file) {
                /** @var $file SplFileInfo */
                if ($file->getFilename() === '.' || $file->getFilename() === '..') {
                    continue;
                }

                self::rm($file->getPathname(), false, $force);
            }

            self::rm($path, false, $force);
            return;
        }

        if (!$recursive && is_dir($path) && count(scandir($path)) > 2) {
            throw new DirectoryNotEmptyException($path);
        }

        if (!$force && !file_exists($path)) {
            throw new FileOrDirectoryNotFoundException($path);
        }

        if (!$force && !is_writable($path)) {
            throw new FileOrDirectoryNotWritableException($path);
        }

        if ($force) {
            @chmod($path, 0777);
        }

        if (is_file($path)) {
            @unlink($path);
        } elseif (is_dir($path)) {
            @rmdir($path);
        }
    }

    /**
     * Create a directory.
     *
     * @param string $path
     * @param int    $mode
     * @param bool   $recursive
     *
     * @return void
     */
    public static function mkdir(string $path, int $mode = 0777, bool $recursive = false): void
    {
        if (!$recursive && !is_dir(dirname($path))) {
            throw new FileOrDirectoryNotFoundException(dirname($path));
        }

        if (is_file($path)) {
            throw new FileExistsException($path);
        }

        if (!is_dir($path)) {
            mkdir($path, 0777, $recursive);
        }

        chmod($path, $mode);
    }
}
