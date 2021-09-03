<?php

namespace nuke2015\api\org;

// 文件树

class Thashtree
{
    // 目录扫描
    public static function dir_scan($dir)
    {
        $result = [];
        self::dir_recursion($dir, function ($file) use (&$result) {
            $result[] = $file;
        });
        return $result;
    }

    // 取得目录树
    public static function hashtree($dir)
    {
        $result = [];
        self::dir_recursion($dir, function ($file) use (&$result) {
            $hash               = md5_file($file);
            $result[md5($file)] = [$file, $hash];
        });
        return $result;
    }

    public static function dir_recursion($dir, $func)
    {
        if (!is_dir($dir)) {
            return false;
        }
        //打开目录
        $handle = opendir($dir);
        while (($file = readdir($handle)) !== false) {
            //排除掉当前目录和上一个目录
            if ($file == "." || $file == "..") {
                continue;
            }
            $file = $dir . DIRECTORY_SEPARATOR . $file;
            //如果是文件就打印出来，否则递归调用
            if (is_file($file)) {
                $func($file);
            } elseif (is_dir($file)) {
                self::dir_recursion($file, $func);
            }
        }
    }
}
