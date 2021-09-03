<?php

use PHPUnit\Framework\TestCase;

class AppHelloTest extends TestCase
{
    public function testindex()
    {
        $this->assertEquals('base', 'base');
    }
}
