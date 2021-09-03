<?php

// thinkphp 运行时文件夹初始化
var_dump('runtime init!');
define('RUNTIME_PATH', './runtime/');
mkdir(RUNTIME_PATH,1);
$dir = 'cache,cookie,datastore,debug,jiazhen_club,log,logcenter,nginx,temp,crm,zhihu_club';
$dirs = explode(',', $dir);
foreach ($dirs as $key => $value) {
    $value = RUNTIME_PATH.$value;
    if (!is_dir($value)) {
        mkdir($value, 0777);
    }
}
var_dump('done!');
