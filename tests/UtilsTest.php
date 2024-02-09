<?php

namespace GenDiff\Tests;

use PHPUnit\Framework\TestCase;
use GenDiff\Utils;

class UtilsTest extends TestCase
{
    public function testUtils(): void
    {
        $this->assertEquals('string', Utils\formatToString('string'));
        $this->assertEquals('', Utils\formatToString(''));
        $this->assertEquals('1', Utils\formatToString(1));
        $this->assertEquals('0', Utils\formatToString(0));
        $this->assertEquals('1.23', Utils\formatToString(1.23));
        $this->assertEquals('true', Utils\formatToString(true));
        $this->assertEquals('false', Utils\formatToString(false));
        $this->assertEquals('null', Utils\formatToString(null));
    }
}
