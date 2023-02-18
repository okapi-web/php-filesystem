<?php

namespace Okapi\Filesystem\Tests\Unit;

use Okapi\Filesystem\Exception\FileOrDirectoryNotFoundException;
use Okapi\Filesystem\Exception\NotAFileException;
use Okapi\Filesystem\Filesystem;
use Okapi\Filesystem\Tests\DeleteTmpAfterEachTest;

class MkdirTest extends DeleteTmpAfterEachTest
{
    public function testMkdir(): void
    {
        $path = self::TMP_DIR . '/test';

        $this->assertDirectoryDoesNotExist($path);
        Filesystem::mkdir($path, recursive: true);
        $this->assertDirectoryExists($path);
    }

    public function testMkdirDeepWithoutRecursive(): void
    {
        $path = self::TMP_DIR . '/test/test/test';

        $this->assertDirectoryDoesNotExist($path);
        $this->expectException(FileOrDirectoryNotFoundException::class);
        Filesystem::mkdir($path);
    }

    public function testMkdirOnFile(): void
    {
        $path = self::TMP_DIR . '/test';
        Filesystem::writeFile($path, 'test');

        $this->assertFileExists($path);
        $this->expectException(NotAFileException::class);
        Filesystem::mkdir($path);
    }
}
