<?php

namespace GenDiff\Tests;

use PHPUnit\Framework\TestCase;
use GenDiff\Utils;

class UtilsTest extends TestCase
{
    public function testUtils(): void
    {
        $this->assertEquals('string', Utils\toString('string'));
        $this->assertEquals('', Utils\toString(''));
        $this->assertEquals('1', Utils\toString(1));
        $this->assertEquals('0', Utils\toString(0));
        $this->assertEquals('1.23', Utils\toString(1.23));
        $this->assertEquals('true', Utils\toString(true));
        $this->assertEquals('false', Utils\toString(false));
        $this->assertEquals('null', Utils\toString(null));
    }
}
