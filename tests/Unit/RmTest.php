<?php

namespace Okapi\Filesystem\Tests\Unit;

use Okapi\Filesystem\Exception\DirectoryNotEmptyException;
use Okapi\Filesystem\Exception\FileOrDirectoryNotWritableException;
use Okapi\Filesystem\Exception\FileOrDirectoryNotFoundException;
use Okapi\Filesystem\Filesystem;
use Okapi\Filesystem\Tests\DeleteTmpAfterEachTestTrait;
use PHPUnit\Framework\TestCase;

class RmTest extends TestCase
{
    use DeleteTmpAfterEachTestTrait;

    public function testRmFile(): void
    {
        $file = $this->tmpDir . '/test.txt';
        $content = 'Hello world!';

        $this->assertFileDoesNotExist($file);
        Filesystem::writeFile($file, $content);
        $this->assertFileExists($file);

        Filesystem::rm($file);

        $this->assertFileDoesNotExist($file);
    }

    public function testRmDir(): void
    {
        $dir = $this->tmpDir . '/test';

        $this->assertDirectoryDoesNotExist($dir);
        Filesystem::mkdir($dir, recursive: true);
        $this->assertDirectoryExists($dir);

        Filesystem::rm($dir);

        $this->assertDirectoryDoesNotExist($dir);
    }

    public function testRmDirNotEmpty(): void
    {
        $dir = $this->tmpDir . '/test';

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
        $dir = $this->tmpDir . '/test';

        $this->assertDirectoryDoesNotExist($dir);

        $this->expectException(FileOrDirectoryNotFoundException::class);
        Filesystem::rm($dir);
    }

    public function testRmDirRecursive(): void
    {
        $dir = $this->tmpDir . '/test/recursive';

        $this->assertDirectoryDoesNotExist($dir);
        Filesystem::mkdir($dir, recursive: true);

        Filesystem::rm($dir, recursive: true);
        $this->assertDirectoryDoesNotExist($dir);
    }

    public function testRmDirRecursiveOnNonExistentDir(): void
    {
        $dir = $this->tmpDir . '/test/recursive';

        $this->assertDirectoryDoesNotExist($dir);

        $this->expectException(FileOrDirectoryNotFoundException::class);
        Filesystem::rm($dir, recursive: true);
    }

    public function testRmDirRecursiveOnNonWritableDir(): void
    {
        $dir = $this->tmpDir . '/test/recursive';

        $this->assertDirectoryDoesNotExist($dir);
        Filesystem::mkdir($dir, mode: 0400, recursive: true);

        if (PHP_OS === 'Linux') {
            $this->markTestSkipped('For some reason, permissions for directories are not set on Linux.');
        }

        $this->expectException(FileOrDirectoryNotWritableException::class);
        Filesystem::rm($dir, recursive: true);
    }

    public function testRmDirRecursiveOnNonWritableDirWithForce(): void
    {
        $dir = $this->tmpDir . '/test/recursive';

        $this->assertDirectoryDoesNotExist($dir);
        Filesystem::mkdir($dir, mode: 0400, recursive: true);

        Filesystem::rm($dir, recursive: true, force: true);
        $this->assertDirectoryDoesNotExist($dir);
    }
}
