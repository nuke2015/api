<?php
namespace nuke2015\api\base;

// 单机计数器(文件存储),相关操作示例
class CacheCount
{
    public static function set($key, $value, $expire = 86400)
    {
        $value = intval($value);
        $key   = self::key_modify($key);
        return CacheRedis::set($key, $value, $expire);
    }

    public static function get($key)
    {
        $key   = self::key_modify($key);
        $value = CacheRedis::get($key);
        return intval($value);
    }

    // 周期内自增,超过周期则重置
    public static function setinc($key, $count = 1, $expire = 86400)
    {
        $key   = self::key_modify($key);
        $value = CacheRedis::setinc($key, $count, $expire);
        return intval($value);
    }

    public static function remove($key)
    {
        self::setinc($key, 0, -1);
        $key = self::key_modify($key);
        return CacheRedis::remove($key);
    }

    // 修饰
    public static function key_modify($key)
    {
        return 'base_CacheCount' . md5($key);
    }
}
