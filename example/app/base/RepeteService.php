<?php

use nuke2015\api\base;

// view-source:http://com.loc.jjys168.com/example/RepeteService.php
var_dump('Testing RepeteService');

// 测试demo
$RepeteService = new base\RepeteService();
$key           = 'AppHelloAction#hello';
$check         = $RepeteService->check($key);
if (!$check) {
    var_dump(time());
    var_dump($check);
    $RepeteService->lock($key);
}
var_dump($RepeteService->check($key));
