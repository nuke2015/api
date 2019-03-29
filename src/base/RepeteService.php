<?php
namespace nuke2015\api\base;

// 排重组件,避免一件事情重复做
class RepeteService
{

    // 上锁,到期自动解锁
    public static function lock($key, $time = 600)
    {
        $key = self::key($key);
        return CacheRedis::set($key, 1, $time);
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
        return "common#base#RepeteService#{$key}";
    }
}
