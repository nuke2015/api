<?php
require 'config.php';

use \nuke2015\api\service;

$topic = 'zhihu';
$key   = 'hello world';

$rpc = new service\RpcService($topic, $key);
// var_dump($rpc);
list($err, $data) = $rpc->check();

$result['param']    = $data;
$result['sign_rnd'] = $rpc->get_sign($data['rnd']);
echo json_encode($result);
