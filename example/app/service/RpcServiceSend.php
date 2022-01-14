<?php

use \nuke2015\api\service;

$host  = 'http://127.0.0.100/deploy/Demo.php?c=e11cc2d94c607bcc55fe204dbea787b1';
$topic = 'zhihu';
$key   = 'hello world';
$rpc   = new service\RpcService($topic, $key);
// var_dump($rpc);

$param = ['methodName' => 'homeindex', 'version' => '2.0', 'user_id' => 32, 'rnd' => time()];
$res   = $rpc->send($host, $param);
var_dump($res, $rpc->get_sign($param['rnd']));
exit;
