<?php
require '..\config.php';

use nuke2015\api\base;

// view-source:http://com.loc.jjys168.com/example/TokenService.php
var_dump('Testing TokenService!');

// 测试用例
$TokenService = new base\TokenService('user');
$token        = $TokenService->make_token(100);
var_dump($token);

$check = $TokenService->check_token(100, $token);
var_dump($check);

$check = $TokenService->check_token(100, $token . 'hello');
var_dump($check);
