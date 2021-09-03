<?php

use nuke2015\api\base;
use nuke2015\api\org;

class curlTest extends base\TestCase
{
    public function testindex()
    {
        $x = org\myhttp::curl('https://www.baidu.com');
        $this->check(strlen($x) > 1000);
    }
}
