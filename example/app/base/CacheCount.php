<?php


use \nuke2015\api\base;

// view-source:http://com.loc.jjys168.com/example/CacheCount.php
var_dump('testing CacheCount!');

// 单机计数器(文件存储),相关操作示例
$key_test = 'fengsu2';
var_dump($key_test);

// 测试写入
base\CacheCount::set($key_test, 100);
var_dump(base\CacheCount::get($key_test));

// 测试累加
base\CacheCount::setinc($key_test, 3);
var_dump(base\CacheCount::get($key_test));

// 测试累加
base\CacheCount::setinc($key_test, 2);
var_dump(base\CacheCount::get($key_test));

// 测试删除与清空
base\CacheCount::remove($key_test);
var_dump(base\CacheCount::get($key_test));
