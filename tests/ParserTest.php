<?php

namespace GenDiff\Tests;

use PHPUnit\Framework\TestCase;
use GenDiff\Parser;

class ParserTest extends TestCase
{
    private $expected1 = [
        "common" => [
            "setting1" => "Value 1",
            "setting2" => 200,
            "setting3" => true,
            "setting6" => [
                "key" => "value",
                "doge" => [
                    "wow" => ""
                ]
            ]
        ],
        "group1" => [
            "baz" => "bas",
            "foo" => "bar",
            "nest" => [
                "key" => "value"
            ]
        ],
        "group2" => [
            "abc" => 12345,
            "deep" => [
                "id" => 45
            ]
        ]
    ];

    private $expected2 = [
        "common" => [
            "follow" => false,
            "setting1" => "Value 1",
            "setting3" => null,
            "setting4" => "blah blah",
            "setting5" => [
                "key5" => "value5"
            ],
            "setting6" => [
                "key" => "value",
                "ops" => "vops",
                "doge" => [
                    "wow" => "so much"
                ]
            ]
        ],
        "group1" => [
            "foo" => "bar",
            "baz" => "bars",
            "nest" => "str"
        ],
        "group3" => [
            "deep" => [
                "id" => [
                    "number" => 45
                ]
            ],
            "fee" => 100500
        ]
    ];

    public function testParseJson(): void
    {
        $actual1 = Parser\parseFile(__DIR__ . '/fixtures/file1.json');
        $actual2 = Parser\parseFile(__DIR__ . '/fixtures/file2.json');
        $this->assertEquals($this->expected1, $actual1);
        $this->assertEquals($this->expected2, $actual2);
    }

    public function testParseYaml(): void
    {
        $actual1 = Parser\parseFile(__DIR__ . '/fixtures/file1.yaml');
        $actual2 = Parser\parseFile(__DIR__ . '/fixtures/file2.yml');
        $this->assertEquals($this->expected1, $actual1);
        $this->assertEquals($this->expected2, $actual2);
    }
}
