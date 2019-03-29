<?php
require 'config.php';
use nuke2015\api\base;

// view-source:http://com.loc.jjys168.com/example/CacheFile.php
var_dump('Testing CacheFile!');

// 文件式缓存,相关操作示例
base\CacheFile::set('fs', time());
var_dump(base\CacheFile::get('fs'));
base\CacheFile::rm('fs');
var_dump(base\CacheFile::get('fs'));
base\CacheFile::clear();
base\CacheFile::set('fs2', 'hello world');
