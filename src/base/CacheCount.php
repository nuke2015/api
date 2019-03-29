<?php
namespace nuke2015\api\base;

// 单机计数器(文件存储),相关操作示例
class CacheCount
{

    public static function set($key, $value)
    {
        $value = intval($value);
        $key   = self::key_modify($key);
        return CacheRedis::set($key, $value, 86400);
    }

    public static function get($key)
    {
        $key   = self::key_modify($key);
        $value = CacheRedis::get($key);
        return intval($value);
    }

    public static function set_inc($key, $count = 1)
    {
        $value = self::get($key);
        $value += $count;
        return self::set($key, $value);
    }

    public static function remove($key)
    {
        $key   = self::key_modify($key);
        return CacheRedis::remove($key);
    }

    // 修饰
    public static function key_modify($key)
    {
        return 'base_CacheCount' . md5($key);
    }

}
