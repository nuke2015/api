<?php
namespace nuke2015\api\base;

// 分布式事务锁
class LockService
{

    // 上锁,到期自动解锁
    public static function lock($key, $time = 600)
    {
        $key = self::key($key);
        return CacheRedis::set($key, true, $time);
    }

    // 提前解锁
    public static function unlock($key)
    {
        $key = self::key($key);
        return CacheRedis::remove($key);
    }

    // 校验
    public static function check($key)
    {
        $key = self::key($key);
        return CacheRedis::get($key);
    }

    // 统一前缀
    public static function key($key)
    {
        return "common#base#LockService#{$key}";
    }
}
