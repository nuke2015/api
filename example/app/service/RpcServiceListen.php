<?php
require 'config.php';

use \nuke2015\api\service;

$topic = 'zhihu';
$key   = 'hello world';
$rpc   = new service\RpcService($topic, $key);
// var_dump($rpc);
$rpc->result($_REQUEST);
