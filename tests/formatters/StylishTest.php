<?php

namespace GenDiff\Tests\Formatters;

use PHPUnit\Framework\TestCase;

use function Gendiff\genDiff;
use function GenDiff\Formatters\Stylish\makeStylish;

class StylishTest extends TestCase
{
    public function testStylish(): void
    {
        $diff = genDiff(__DIR__ . "/../fixtures/file1.json", __DIR__ . "/../fixtures/file2.json");
        $expected = file_get_contents(__DIR__ . '/../fixtures/expectedStylish.txt');
        $actual = makeStylish($diff);
        $this->assertEquals($expected, $actual);
    }
}
