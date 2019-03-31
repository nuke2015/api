<?php
require 'config.php';

use \nuke2015\api\service;

$host  = 'http://127.0.0.100/deploy/Demo.php?c=1273b3093395ab6ef9b80b312f10f7d0';
$topic = 'zhihu';
$key   = 'hello world';
$rpc   = new service\RpcService($topic, $key);
// var_dump($rpc);

$param = ['methodName' => 'homeindex', 'version' => '2.0', 'user_id' => 32];
$res   = $rpc->send($host, $param);
var_dump($res);
exit;
