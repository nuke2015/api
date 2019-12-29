<?php
// 开发中半自动装载
$loader = new \Composer\Autoload\ClassLoader();
$loader->setPsr4('nuke2015\\api\\base\\', dirname(__DIR__) . '/src/base/');
$loader->setPsr4('nuke2015\\api\\org\\', dirname(__DIR__) . '/src/org/');
$loader->setPsr4('nuke2015\\api\\service\\', dirname(__DIR__) . '/src/service/');
$loader->register(true);
