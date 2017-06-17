<?php
namespace nuke2015\api\base;

use think\Cache;
use think\Config;

// 单机计数器(文件存储),相关操作示例
class CacheCount
{

    public static function set($key, $value)
    {
        $cache = self::connect();
        $value = intval($value);
        $key   = base64_encode($key);
        return $cache->set($key, $value, 86400);
    }

    public static function get($key)
    {
        $cache = self::connect();
        $key   = base64_encode($key);
        $value = $cache->get($key);
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
        $cache = self::connect();
        $key   = base64_encode($key);
        return $cache->rm($key);
    }

    public static function clear()
    {
        $cache = self::connect();
        return $cache->clear();
    }

    public static function connect()
    {
        $options = Config::get('MyCacheCount');
        if (isset($options['path'])) {
            $options['path'] = CACHE_PATH . date("Ymd") . '/';
        }
        $cache = Cache::connect($options);
        return $cache;
    }
}
