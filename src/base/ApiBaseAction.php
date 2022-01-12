<?php

namespace nuke2015\api\base;

use nuke2015\api\org;
use nuke2015\api\config;

// 全站基类
class ApiBaseAction
{
    use ApiDataListAction;
    use CURDAction;

    //curd
    protected static $db = 'nuke2015\\api\\model\\';

    // 常规日志
    protected static function log($filename, $data)
    {
        //存档日志
        org\Flogger::json_log($filename, $data);
    }

    // 文本日志
    protected static function plog($filename, $data)
    {
        org\Flogger::plog($filename, $data);
    }

    // 微信公众号网关
    protected static function host_weixin($is_https = 0)
    {
        return config::host_weixin($is_https);
    }

    // 缓存读取
    protected static function cache_get($k)
    {
        // 小键位,避免与其它缓存碰撞
        $k         = 'base_' . MODULE_NAME . '_' . md5($k);
        $cache_dir = self::connect_dir(MODULE_NAME, 8640000);
        return $cache_dir->get($k);
    }

    // 缓存写入
    protected static function cache_set($k, $v, $expire = 166400)
    {
        // 小键位,避免与其它缓存碰撞
        $k         = 'base_' . MODULE_NAME . '_' . md5($k);
        $cache_dir = self::connect_dir(MODULE_NAME, 8640000);
        return $cache_dir->set($k, $v, $expire);
    }

    // 缓存清除
    protected function cache_remove($k)
    {
        // 小键位,避免与其它缓存碰撞
        $k         = 'base_' . MODULE_NAME . '_' . md5($k);
        $cache_dir = self::connect_dir(MODULE_NAME, 8640000);
        return $cache_dir->remove($k);
    }

    // 缓存读取
    protected function redis_get($k)
    {
        // 小键位,避免与其它缓存碰撞
        $k = 'base_' . MODULE_NAME . '_' . md5($k);
        return CacheRedis::get($k);
    }

    // 缓存写入
    protected function redis_set($k, $v, $expire = 166400)
    {
        // 小键位,避免与其它缓存碰撞
        $k = 'base_' . MODULE_NAME . '_' . md5($k);
        return CacheRedis::set($k, $v, $expire);
    }

    // 缓存清除
    protected function redis_remove($k)
    {
        // 小键位,避免与其它缓存碰撞
        $k = 'base_' . MODULE_NAME . '_' . md5($k);
        return CacheRedis::remove($k);
    }

    // 全局缓存读取,大场景，中间层com组件,统一缓存
    protected function common_redis_get($k)
    {
        // 统一键位,避免与其它缓存碰撞
        $k = 'common_redis_' . md5($k);
        return CacheRedis::get($k);
    }

    // 全局缓存写入,大场景，中间层com组件,统一缓存
    protected function common_redis_set($k, $v, $expire = 166400)
    {
        // 统一键位,避免与其它缓存碰撞
        $k = 'common_redis_' . md5($k);
        return CacheRedis::set($k, $v, $expire);
    }

    // 全局缓存清除,大场景，中间层com组件,统一缓存
    protected function common_redis_remove($k)
    {
        // 统一键位,避免与其它缓存碰撞
        $k = 'common_redis_' . md5($k);
        return CacheRedis::remove($k);
    }

    // 独立缓存
    protected function connect_dir($dir, $expire = 864000)
    {
        return media_io::connect($dir);
    }

    // 返回值常量定义
    protected function code_define()
    {
        $ERROR_MSGS = org\ErrorCode::key_code_map();
        if ($ERROR_MSGS && count($ERROR_MSGS)) {
            foreach ($ERROR_MSGS as $key => $code) {
                if (!defined($key)) {
                    define($key, $code);
                }
            }
        }
        return;
    }
}
