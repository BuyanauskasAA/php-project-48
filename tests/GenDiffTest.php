<?php

namespace GenDiff\Tests;

use PHPUnit\Framework\TestCase;
use function GenDiff\genDiff;

class GenDiffTest extends TestCase
{
    public function testGenDiff(): void
    {
        $expected = file_get_contents(__DIR__ . "/fixtures/expectedPlain.txt");
        $actual = genDiff(__DIR__ . "/fixtures/file1.json", __DIR__ . "/fixtures/file2.json");
        $this->assertEquals($expected, $actual);
    }
}