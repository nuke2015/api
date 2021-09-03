<?php

namespace nuke2015\api\base;

// 阿里云的oss在使用时,用户需要bucket名称的意思就是:
// 若你不付费,我就把根目录删除,同时不影响付费用户的正常使用.
// 哈希云存储;
class Thashstore
{
    public static $Tfilecache;

    // 开顶级目录,非必要函数
    public static function conn($dir_sub = 'default', $expire = 86400)
    {
        $dir_to = CACHE_PATH . '/hashstore/';
        if (!is_dir($dir_to)) {
            mkdir($dir_to, 0777);
        }

        if (!self::$Tfilecache) {
            self::$Tfilecache = new Tfilecache();
        }

        self::$Tfilecache::$dir_store = $dir_to . $dir_sub;
        return self::$Tfilecache;
    }

    // 读取
    public static function get($k)
    {
        list($dir_to, $file) = self::dir_hash($k);
        // var_dump($dir_to, $file);
        // exit;
        $Tfilecache = self::conn($dir_to);
        return $Tfilecache::get($file);
    }

    // 写入
    public static function set($k, $v, $t = 0)
    {
        list($dir_to, $file) = self::dir_hash($k);
        // var_dump('put',$dir_to, $file);exit;
        $Tfilecache = self::conn($dir_to);
        return $Tfilecache::set($file, $v, $t);
    }

    // 伪清除
    public static function remove($k)
    {
        list($dir_to, $file) = self::dir_hash($k);
        $Tfilecache = self::conn($dir_to);
        return $Tfilecache::remove($file);
    }

    // 存在性判断
    public static function exist($k)
    {
        list($dir_to, $file) = self::dir_hash($k);
        $Tfilecache = self::conn($dir_to);
        return $Tfilecache::exist($file);
    }

    // 哈希目录
    public static function dir_hash($k)
    {
        $md5 = md5($key);
        return array(substr($md5, 0, 6), $key);
    }
}
