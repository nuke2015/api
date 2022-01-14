<?php

// composer
require_once '/git/composer/vendor/autoload.php';

defined('RUNTIME_PATH') or define('RUNTIME_PATH', '/home/ddys_run/example/');
defined('CACHE_PATH') or define('CACHE_PATH', RUNTIME_PATH . '/cache/');
define('ROOT_PATH', __DIR__);

// 本地配置
require_once '/home/ddys_conf/init.php';

use nuke2015\api\org;

// 报错
org\TError::listen();

// 路由
org\Flogger::plog('route', $_REQUEST);

// 开发中半自动装载
$loader = new \Composer\Autoload\ClassLoader();
$loader->setPsr4('nuke2015\\api\\base\\', ROOT_PATH . '/src/base/');
$loader->setPsr4('nuke2015\\api\\org\\', ROOT_PATH . '/src/org/');
$loader->setPsr4('nuke2015\\api\\service\\', ROOT_PATH . '/src/service/');

// 配置文件
$loader->setPsr4('nuke2015\\api\\config\\', ROOT_PATH . '/app/config/');
$loader->register(true);
