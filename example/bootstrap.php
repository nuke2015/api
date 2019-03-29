<?php
// 开发中半自动装载
$loader = new \Composer\Autoload\ClassLoader();
$loader->setPsr4('nuke2015\\api\\base\\', dirname(__DIR__) . '/src/base/');
$loader->register(true);
