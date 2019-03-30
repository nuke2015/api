<?php
require 'config.php';

use \nuke2015\api\service;

if ($_POST) {
    file_put_contents(__DIR__ . '/log.txt', json_encode($_POST), FILE_APPEND);
    var_dump($_POST);
    exit;
} else {

    $host  = 'http://10.0.17.103/';
    $topic = 'zhihu';
    $key   = 'hello world';
    $rpc   = new service\RpcService($host, $topic, $key);
    // var_dump($rpc);

    $param = ['methodName' => 'homeindex', 'version' => '2.0', 'user_id' => 32];
    $res   = $rpc->send($param);
    var_dump($res);
}
echo 'f';
