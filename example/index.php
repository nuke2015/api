<?php

define('MODULE_NAME', 'demo_cube');

// 增加个项目名
define('CUBE_MODULE', 'cube_demo');

define('CUBE_PATH', dirname(dirname(__DIR__)));

require "autoload.php";

$c       = isset($_GET['c']) ? trim($_GET['c']) : '';
$app_dir = __DIR__ . '/app/';
$files   = glob($app_dir . '/*/*.php');
// var_dump($files);exit;
if ($c) {
    // 召唤
    if ($files && count($files)) {
        foreach ($files as $key => $value) {
            if (md5($value) == $c && $c) {
                require $value;
                exit;
            }
        }
    }
} else {
    print_r('<h1>Hello didiyuesao Testing!</h1>');
    // 列表
    if ($files && count($files)) {
        foreach ($files as $key => $value) {
            $show = str_ireplace($app_dir, '', $value);
            $link = md5($value);
            echo "<a href='http://" . $_SERVER['HTTP_HOST'] . "/deploy/Demo.php?c={$link}' target='_blank'>{$show}</a><br/><br/>";
        }
    }
}
