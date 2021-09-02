<?php

// 此config仅适用于当前的demo
$myconfig = [

    // 单机文件式缓存
    'MyCacheFile'  => [
        'type'   => 'File',
        'path'   => CACHE_PATH . '/file/',
        'prefix' => '',
        'expire' => 0,
    ],

    // 单机文件式计数器
    'MyCacheCount' => [
        'type'   => 'File',
        'path'   => CACHE_PATH . '/count/',
        'prefix' => '',
        'expire' => 0,
    ],

    // Redis缓存
    'MyCacheRedis' => [
        'REDIS_HOST' => '127.0.0.1',
        'REDIS_PORT' => '6379',
        'REDIS_PWD'  => '123456',
    ],
];
think\Config::set($myconfig);
