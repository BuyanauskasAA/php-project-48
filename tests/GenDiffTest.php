<?php

namespace Differ\Tests;

use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;

class GenDiffTest extends TestCase
{
    public function testGenDiffStylish(): void
    {
        $expected = file_get_contents(__DIR__ . '/fixtures/expectedStylish.txt');
        $actual = genDiff(__DIR__ . "/fixtures/file1.json", __DIR__ . "/fixtures/file2.json");
        $this->assertEquals($expected, $actual);
    }
}
