<?php

use nuke2015\api\base;

// view-source:http://com.loc.jjys168.com/example/LockService.php
var_dump('Testing LockService!');

// 测试用例
$do = base\LockService::lock('user1', 600);
var_dump($do);
$info = base\LockService::check('user1');
var_dump($info);
$do = base\LockService::unlock('user1');
var_dump($do);
$info = base\LockService::check('user1');
var_dump($info);
