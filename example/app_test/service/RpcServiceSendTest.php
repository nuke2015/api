<?php

use PHPUnit\Framework\TestCase;
use \nuke2015\api\service;

class RpcServiceSendTest extends TestCase
{
    public function testCheck()
    {

        $topic = 'zhihu';
        $key   = 'hello world';

        $rpc    = new service\RpcService($topic, $key);
        $sign = '1630632172_9f9f9b30e568cabad7444753d3630d49';
        $_REQUEST['_sign_rpc'] = $sign;
        $result = $rpc->check();
        $this->assertEquals(0, $result[0]);
    }
}
