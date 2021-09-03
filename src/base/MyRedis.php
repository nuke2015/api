<?php

namespace nuke2015\api\base;

use think\Config;
use think\Exception;

// 原生的php连接组件
class MyRedis
{
    public static $modify_key = 'Redis_';
    public static $redis;

    /**
     * 连接数据库.
     *
     * @return [type] [description]
     */
    public static function conn()
    {
        //原生长链接,单例模式
        if (!self::$redis) {
            self::$redis = new \redis();
            $options = Config::get('MyCacheRedis');
            if (!$options || !is_array($options)) {
                throw new \Exception('redis no config,yet', 60008);
            }
            self::$redis->connect($options['REDIS_HOST'], $options['REDIS_PORT']);

            //如果有设置密码
            if ($options['REDIS_PWD']) {
                self::$redis->auth($options['REDIS_PWD']);
            }
        }

        return self::$redis;
    }

    /**
     * 键名修饰.
     */
    public static function modify_key($key)
    {
        $env = defined('ENV_ONLINE') ? ENV_ONLINE : 0;
        $prefix = $env.self::$modify_key;

        // 此处不用md5,可在单一业务的key进行md5,增加多城市区分
        $key = $prefix.base64_encode($key);

        return $key;
    }
}
