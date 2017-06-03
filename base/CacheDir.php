<?php
namespace nuke2015\api\base;

// 按目录文件缓存
class CacheDir
{
    public static function connect($dir = '', $expire = 86400)
    {
        if (!$dir) {
            $dir = MODULE_NAME;
        }
        // 单机文件式缓存
        $options = [
            'type'   => 'File',
            'path'   => CACHE_PATH . "/{$dir}/",
            'prefix' => '',
            'expire' => $expire,
        ];
        $cache = \think\Cache::connect($options);
        return $cache;
    }

    public static function set($key, $value, $expire = 0)
    {
        $cache = self::connect();
        return $cache->set($key, $value, $expire);
    }

    public static function get($key)
    {
        $cache = self::connect();
        return $cache->get($key);
    }

    public static function rm($key)
    {
        $cache = self::connect();
        return $cache->rm($key);
    }

    public static function clear()
    {
        $cache = self::connect();
        return $cache->clear();
    }
}
