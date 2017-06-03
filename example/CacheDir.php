<?php
require '..\config.php';
use nuke2015\api\base;

// view-source:http://com.loc.jjys168.com/example/CacheFile.php
var_dump('Testing CacheDir!');

// 文件式缓存,相关操作示例
base\CacheDir::set('fs', time());
var_dump(base\CacheDir::get('fs'));
base\CacheDir::rm('fs');
var_dump(base\CacheDir::get('fs'));
base\CacheDir::clear();
base\CacheDir::set('fs2', 'hello world');