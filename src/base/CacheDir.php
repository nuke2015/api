<?php

namespace nuke2015\api\base;

// 按目录文件缓存,唯独这个不区分module_name避免重复缓存
class CacheDir
{
    // 静态变量
    public static $Tfilecache;

    public static function connect($dir = 'default', $expire = 86400)
    {
        $dir = CACHE_PATH . '/' . $dir . '/';
        if (!is_dir($dir)) {
            mkdir($dir, 0777);
        }

        if (!self::$Tfilecache) {
            self::$Tfilecache = new Tfilecache();
        }

        self::$Tfilecache::$dir_store = $dir;
        return self::$Tfilecache;
    }

    // 设置
    public static function set($key, $value, $expire = 86400)
    {
        $cache = self::connect();
        return $cache->set($key, $value, $expire);
    }

    // 取出
    public static function get($key)
    {
        $cache = self::connect();
        return $cache->get($key);
    }

    // 单键删除
    public static function rm($key)
    {
        $cache = self::connect();
        return $cache->remove($key);
    }

    // 别名,兼容
    public static function remove($key)
    {
        return self::rm($key);
    }

    // 整个清除
    public static function clear($dir = 'default')
    {
        $dir = CACHE_PATH . '/' . $dir . '/';
        if ($dir && is_dir($dir)) {
            $files = glob($dir . '*');
            if ($files && count($files)) {
                foreach ($files as $key => $file) {
                    if (file_exists($file)) {
                        unlink($file);
                    }
                }
            }
        }
    }
}
