<?php
require 'config.php';

use \nuke2015\api\service;

$topic = 'zhihu';
$key   = 'hello world';

$rpc   = new service\RpcService($topic, $key);
// var_dump($rpc);
list($err, $data) = $rpc->check();
if (!$err) {
    echo json_encode(['code' => 0, 'data' => $_REQUEST]);
} else {
    echo json_encode(['code' => $err, 'data' => $data]);
}
