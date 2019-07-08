<?php
namespace Tesla\Chrome2Pdf;

use PHPUnit\Framework\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    protected function emptyTempDirectory()
    {
        $tempDirPath = __DIR__ . '/temp';
        $files = scandir($tempDirPath);
        foreach ($files as $file) {
            if (! in_array($file, ['.', '..', '.gitignore'])) {
                unlink("{$tempDirPath}/{$file}");
            }
        }
    }
}
