<?php

use PHPUnit\Framework\TestCase;
use \nuke2015\api\service;

class RpcServiceListenTest extends TestCase
{
    public function testCheck()
    {

        $topic = 'zhihu';
        $key   = 'hello world';

        $rpc    = new service\RpcService($topic, $key);
        $result = $rpc->send('http://127.0.0.1', ['methodName' => "hello"]);
        $sign   = $rpc->get_sign(1630632172);
        $this->assertEquals($sign, '1630632172_9f9f9b30e568cabad7444753d3630d49');
    }
}
