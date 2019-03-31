<?php
require 'config.php';

use \nuke2015\api\service;

$host  = 'http://10.0.17.103';
$topic = 'zhihu';
$key   = 'hello world';
$rpc   = new service\RpcService($topic, $key);
// var_dump($rpc);

$param = ['methodName' => 'homeindex', 'version' => '2.0', 'user_id' => 32];
$res   = $rpc->send($host, $param);
var_dump($res);
exit;
