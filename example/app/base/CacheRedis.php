<?php
require 'config.php';
use nuke2015\api\base;

// view-source:http://com.loc.jjys168.com/example/CacheRedis.php
var_dump('Testing CacheRedis!');

//测试用例
base\CacheRedis::set('fs', 'fengsu ok');
var_dump(base\CacheRedis::get('fs'));

base\CacheRedis::remove('fs');
var_dump(base\CacheRedis::get('fs'));
