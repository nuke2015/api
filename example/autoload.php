<?php

// 加载thinkphp5框架引导文件
require_once dirname(__DIR__) . '/vendor/topthink/framework/base.php';
// composer
require_once dirname(__DIR__) . '/vendor/autoload.php';

// 开发中半自动装载
$loader = new \Composer\Autoload\ClassLoader();
$loader->setPsr4('nuke2015\\api\\base\\', dirname(__DIR__) . '/src/base/');
$loader->setPsr4('nuke2015\\api\\org\\', dirname(__DIR__) . '/src/org/');
$loader->setPsr4('nuke2015\\api\\service\\', dirname(__DIR__) . '/src/service/');
$loader->register(true);

//example+test需要装载此配置文件,其它项目不需要
require_once __DIR__. '/config.php';

