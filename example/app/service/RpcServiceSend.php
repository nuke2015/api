<?php
require 'config.php';

use \nuke2015\api\service;

$host  = 'http://127.0.0.100/deploy/Demo.php?c=500660ff5d51b3eb40f440a4025ac597';
$topic = 'zhihu';
$key   = 'hello world';
$rpc   = new service\RpcService($topic, $key);
// var_dump($rpc);

$param = ['methodName' => 'homeindex', 'version' => '2.0', 'user_id' => 32, 'rnd' => time()];
$res   = $rpc->send($host, $param);
var_dump($res, $rpc->get_sign($param['rnd']));
exit;
