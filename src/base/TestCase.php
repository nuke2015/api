<?php
namespace nuke2015\api\base;

use PHPUnit\Framework\TestCase as unittest;

class TestCase extends unittest
{
    // 断言
    public function check($true)
    {
        if ($true) {
            $this->assertTrue(true);
        } else {
            $this->assertTrue(false);
        }
        return;
    }
}
