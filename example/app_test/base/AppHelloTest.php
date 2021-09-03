<?php

use nuke2015\api\base;

class AppHelloTest extends base\TestCase
{
    public function testindex()
    {
        $this->assertEquals('base', 'base');
    }
}
