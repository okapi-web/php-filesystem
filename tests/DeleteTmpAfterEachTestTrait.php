<?php

namespace Okapi\Filesystem\Tests;

use Okapi\Filesystem\Filesystem;

trait DeleteTmpAfterEachTestTrait
{
    public string $tmpDir = __DIR__ . '/tmp';

    protected function tearDown(): void
    {
        Filesystem::rm(
            $this->tmpDir,
            recursive: true,
            force: true,
        );
    }
}
