<?php

namespace Okapi\Filesystem\Tests\Unit;

use Okapi\Filesystem\Exception\FileExistsException;
use Okapi\Filesystem\Exception\FileOrDirectoryNotFoundException;
use Okapi\Filesystem\Filesystem;
use Okapi\Filesystem\Tests\DeleteTmpAfterEachTestTrait;
use PHPUnit\Framework\TestCase;

class MkdirTest extends TestCase
{
    use DeleteTmpAfterEachTestTrait;

    public function testMkdir(): void
    {
        $path = $this->tmpDir . '/test';

        $this->assertDirectoryDoesNotExist($path);
        Filesystem::mkdir($path, recursive: true);
        $this->assertDirectoryExists($path);
    }

    public function testMkdirDeepWithoutRecursive(): void
    {
        $path = $this->tmpDir . '/test/test/test';

        $this->assertDirectoryDoesNotExist($path);
        $this->expectException(FileOrDirectoryNotFoundException::class);
        Filesystem::mkdir($path);
    }

    public function testMkdirOnFile(): void
    {
        $path = $this->tmpDir . '/test';
        Filesystem::writeFile($path, 'test');

        $this->assertFileExists($path);
        $this->expectException(FileExistsException::class);
        Filesystem::mkdir($path);
    }
}
