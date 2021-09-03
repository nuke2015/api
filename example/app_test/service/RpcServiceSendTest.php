<?php

use \nuke2015\api\service;
use \nuke2015\api\base;

class RpcServiceSendTest extends base\TestCase
{
    public function testCheck()
    {

        $topic = 'zhihu';
        $key   = 'hello world';

        $rpc                   = new service\RpcService($topic, $key);
        $sign                  = '1630632172_9f9f9b30e568cabad7444753d3630d49';
        $_REQUEST['_sign_rpc'] = $sign;
        $result                = $rpc->get_sign(123);
        $this->check($result == '123_febc71cc30db30355e2c23876f5c752b');
    }
}
