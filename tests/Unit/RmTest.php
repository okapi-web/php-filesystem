<?php

namespace Okapi\Filesystem\Tests\Unit;

use Okapi\Filesystem\Exception\DirectoryNotEmptyException;
use Okapi\Filesystem\Exception\FileOrDirectoryNotWritableException;
use Okapi\Filesystem\Exception\FileOrDirectoryNotFoundException;
use Okapi\Filesystem\Filesystem;
use Okapi\Filesystem\Tests\DeleteTmpAfterEachTest;

class RmTest extends DeleteTmpAfterEachTest
{
    public function testRmFile(): void
    {
        $file = self::TMP_DIR . '/test.txt';
        $content = 'Hello world!';

        $this->assertFileDoesNotExist($file);
        Filesystem::writeFile($file, $content);
        $this->assertFileExists($file);

        Filesystem::rm($file);

        $this->assertFileDoesNotExist($file);
    }

    public function testRmDir(): void
    {
        $dir = self::TMP_DIR . '/test';

        $this->assertDirectoryDoesNotExist($dir);
        Filesystem::mkdir($dir, recursive: true);
        $this->assertDirectoryExists($dir);

        Filesystem::rm($dir);

        $this->assertDirectoryDoesNotExist($dir);
    }

    public function testRmDirNotEmpty(): void
    {
        $dir = self::TMP_DIR . '/test';

        $this->assertDirectoryDoesNotExist($dir);
        Filesystem::mkdir($dir, recursive: true);
        $this->assertDirectoryExists($dir);

        $file = $dir . '/test.txt';
        $content = 'Hello world!';
        Filesystem::writeFile($file, $content);

        $this->expectException(DirectoryNotEmptyException::class);
        Filesystem::rm($dir);
    }

    public function testRmDirOnNonExistentDir(): void
    {
        $dir = self::TMP_DIR . '/test';

        $this->assertDirectoryDoesNotExist($dir);

        $this->expectException(FileOrDirectoryNotFoundException::class);
        Filesystem::rm($dir);
    }

    public function testRmDirRecursive(): void
    {
        $dir = self::TMP_DIR . '/test/recursive';

        $this->assertDirectoryDoesNotExist($dir);
        Filesystem::mkdir($dir, recursive: true);

        Filesystem::rm($dir, recursive: true);
        $this->assertDirectoryDoesNotExist($dir);
    }

    public function testRmDirRecursiveOnNonExistentDir(): void
    {
        $dir = self::TMP_DIR . '/test/recursive';

        $this->assertDirectoryDoesNotExist($dir);

        $this->expectException(FileOrDirectoryNotFoundException::class);
        Filesystem::rm($dir, recursive: true);
    }

    public function testRmDirRecursiveOnNonWritableDir(): void
    {
        $dir = self::TMP_DIR . '/test/recursive';

        $this->assertDirectoryDoesNotExist($dir);
        Filesystem::mkdir($dir, mode: 0400, recursive: true);
        chmod($dir, 0400);
        chmod($dir . '/..', 0400);

        $this->expectException(FileOrDirectoryNotWritableException::class);
        Filesystem::rm($dir, recursive: true);
    }

    public function testRmDirRecursiveOnNonWritableDirWithForce(): void
    {
        $dir = self::TMP_DIR . '/test/recursive';

        $this->assertDirectoryDoesNotExist($dir);
        Filesystem::mkdir($dir, mode: 0400, recursive: true);

        Filesystem::rm($dir, recursive: true, force: true);
        $this->assertDirectoryDoesNotExist($dir);
    }
}
