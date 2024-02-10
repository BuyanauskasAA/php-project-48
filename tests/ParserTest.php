<?php

namespace GenDiff\Tests;

use PHPUnit\Framework\TestCase;
use GenDiff\Parser;

class ParserTest extends TestCase
{
    private $expected1 = [
        'host' => 'hexlet.io',
        'timeout' => 50,
        'proxy' => '123.234.53.22',
        'follow' => false
    ];

    private $expected2 = [
        'timeout' => 20,
        'verbose' => true,
        'host' => 'hexlet.io'
    ];

    public function testParseJson(): void
    {
        $actual1 = Parser\parseFile(__DIR__ . '/fixtures/file1.json');
        $this->assertEquals($this->expected1, $actual1);
    }

    public function testParseYaml(): void
    {
        $actual1 = Parser\parseFile(__DIR__ . '/fixtures/file1.yaml');
        $actual2 = Parser\parseFile(__DIR__ . '/fixtures/file2.yml');
        $this->assertEquals($this->expected1, $actual1);
        $this->assertEquals($this->expected2, $actual2);
    }
}
