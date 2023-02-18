<?php

namespace Okapi\Filesystem\Tests\Unit;

use Okapi\Filesystem\Exception\FileNotFoundException;
use Okapi\Filesystem\Exception\NotAFileException;
use Okapi\Filesystem\Filesystem;
use Okapi\Filesystem\Tests\DeleteTmpAfterEachTestTrait;
use PHPUnit\Framework\TestCase;

class WriteAndReadFileTest extends TestCase
{
    use DeleteTmpAfterEachTestTrait;

    public function testWriteAndReadFile(): void
    {
        $file = $this->tmpDir . '/test.txt';
        $content = 'Hello world!';

        $this->assertFileDoesNotExist($file);
        Filesystem::writeFile($file, $content);
        $this->assertFileExists($file);

        $fileContent = Filesystem::readFile($file);
        $this->assertEquals($content, $fileContent);
    }

    public function testWriteFileOnDirectory(): void
    {
        $directory = $this->tmpDir . '/test';

        $this->assertDirectoryDoesNotExist($directory);
        Filesystem::mkdir($directory, recursive: true);
        $this->assertDirectoryExists($directory);

        $this->expectException(NotAFileException::class);
        Filesystem::writeFile($directory, 'Hello world!');
    }

    public function testWriteFileDeepAndReadFile(): void
    {
        $file = $this->tmpDir . '/deep/test.txt';
        $content = 'Hello world!';

        $this->assertFileDoesNotExist($file);

        Filesystem::writeFile($file, $content);

        $this->assertFileExists($file);

        $fileContent = Filesystem::readFile($file);

        $this->assertEquals($content, $fileContent);
    }

    public function testWriteFileWithFileMode0666(): void
    {
        $this->writeFileWithFileMode(0666);
    }

    public function testWriteFileWithFileMode0444(): void
    {
        $this->writeFileWithFileMode(0444);
    }

    private function writeFileWithFileMode(int $fileMode): void
    {
        $file = $this->tmpDir . '/test.txt';
        $content = 'Hello world!';

        $this->assertFileDoesNotExist($file);
        Filesystem::writeFile($file, $content, $fileMode);
        $this->assertFileExists($file);

        $fileContent = Filesystem::readFile($file);
        $this->assertEquals($content, $fileContent);

        $actualFileMode = fileperms($file);
        if (PHP_OS === 'Linux') {
            $actualFileMode = $actualFileMode & 0666;
        } else {
            $actualFileMode = $actualFileMode & 0777;
        }

        $this->assertEquals($fileMode, $actualFileMode);
    }

    public function testReadFileOnDirectory(): void
    {
        $directory = $this->tmpDir . '/test';

        $this->assertDirectoryDoesNotExist($directory);
        Filesystem::mkdir($directory, recursive: true);
        $this->assertDirectoryExists($directory);

        $this->expectException(NotAFileException::class);
        Filesystem::readFile($directory);
    }

    public function testReadFileOnNonExistentDirectory(): void
    {
        $directory = $this->tmpDir . '/test';

        $this->assertDirectoryDoesNotExist($directory);

        $this->expectException(FileNotFoundException::class);
        Filesystem::readFile($directory);
    }
}
