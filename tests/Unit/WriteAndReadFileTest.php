<?php

namespace Okapi\Filesystem\Tests\Unit;

use Okapi\Filesystem\Exception\FileNotFoundException;
use Okapi\Filesystem\Exception\NotAFileException;
use Okapi\Filesystem\Filesystem;
use Okapi\Filesystem\Tests\DeleteTmpAfterEachTest;

class WriteAndReadFileTest extends DeleteTmpAfterEachTest
{
    public function testWriteAndReadFile(): void
    {
        $file = self::TMP_DIR . '/test.txt';
        $content = 'Hello world!';

        $this->assertFileDoesNotExist($file);
        Filesystem::writeFile($file, $content);
        $this->assertFileExists($file);

        $fileContent = Filesystem::readFile($file);
        $this->assertEquals($content, $fileContent);
    }

    public function testWriteFileOnDirectory(): void
    {
        $directory = self::TMP_DIR . '/test';

        $this->assertDirectoryDoesNotExist($directory);
        Filesystem::mkdir($directory, recursive: true);
        $this->assertDirectoryExists($directory);

        $this->expectException(NotAFileException::class);
        Filesystem::writeFile($directory, 'Hello world!');
    }

    public function testWriteFileDeepAndReadFile(): void
    {
        $file = self::TMP_DIR . '/deep/test.txt';
        $content = 'Hello world!';

        $this->assertFileDoesNotExist($file);

        Filesystem::writeFile($file, $content);

        $this->assertFileExists($file);

        $fileContent = Filesystem::readFile($file);

        $this->assertEquals($content, $fileContent);
    }

    public function testWriteFileWithFileMode0777(): void
    {
        $this->writeFileWithFileMode(0777);
    }

    public function testWriteFileWithFileMode0400(): void
    {
        $this->writeFileWithFileMode(0400);
    }

    private function writeFileWithFileMode(int $fileMode): void
    {
        $file = self::TMP_DIR . '/test.txt';
        $content = 'Hello world!';

        $this->assertFileDoesNotExist($file);

        Filesystem::writeFile($file, $content, $fileMode);

        $this->assertFileExists($file);

        $fileContent = file_get_contents($file);

        $this->assertEquals($content, $fileContent);

        $this->assertEquals($fileMode & ~0111, fileperms($file) & $fileMode);
    }

    public function testReadFileOnDirectory(): void
    {
        $directory = self::TMP_DIR . '/test';

        $this->assertDirectoryDoesNotExist($directory);
        Filesystem::mkdir($directory, recursive: true);
        $this->assertDirectoryExists($directory);

        $this->expectException(NotAFileException::class);
        Filesystem::readFile($directory);
    }

    public function testReadFileOnNonExistentDirectory(): void
    {
        $directory = self::TMP_DIR . '/test';

        $this->assertDirectoryDoesNotExist($directory);

        $this->expectException(FileNotFoundException::class);
        Filesystem::readFile($directory);
    }
}
