<?php

namespace Okapi\Filesystem\Tests;

use Okapi\Filesystem\Filesystem;
use PHPUnit\Framework\TestCase;

abstract class DeleteTmpAfterEachTest extends TestCase
{
    public const TMP_DIR = __DIR__ . '/tmp';

    protected function tearDown(): void
    {
        Filesystem::rm(
            self::TMP_DIR,
            recursive: true,
            force: true,
        );
    }
}
