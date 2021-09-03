<?php

namespace nuke2015\api\base;

// 数据文件永久存储
class Tfilestore
{
    public static $dir_store = RUNTIME_PATH . '/datastore/';

    // 读取
    public static function get($k)
    {
        $k = self::key($k);
        if (file_exists($k)) {
            $txt         = file_get_contents($k);
            if ($txt) {
                $data=unserialize($txt);
                return $data;
            }
        }
    }

    // 存在性判断,存在不代表有效
    public static function exist($k)
    {
        $k = self::key($k);
        return file_exists($k);
    }

    // 写入
    public static function set($k, $v)
    {
        $k = self::key($k);
        return file_put_contents($k, serialize($v));
    }

    // 伪清除
    public static function remove($k)
    {
        $k = self::key($k);
        unlink($k);
    }

    // 统一key
    private static function key($key)
    {
        return self::$dir_store . '/' . md5(strtolower($key)) . '_store.php';
    }
}
