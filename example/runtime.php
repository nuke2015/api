<?php

// thinkphp 运行时文件夹初始化
var_dump('tp5x is ok!');
define('RUNTIME_PATH', '/home/ddys_run/example/');
mkdir(RUNTIME_PATH, 0777);
$dir  = 'cache,cookie,datastore,debug,jiazhen_club,log,logcenter,nginx,temp,crm,zhihu_club,jiazhen_share_image,jiazhen_share_html';
$dirs = explode(',', $dir);
foreach ($dirs as $key => $value) {
    $value = RUNTIME_PATH . $value;
    if (!is_dir($value)) {
        mkdir($value, 0777);
    }
}
var_dump('done!');
