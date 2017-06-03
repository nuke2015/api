<?php
namespace nuke2015\api\base;

class CacheRedis extends MyRedis
{
    public static $modify_key = 'api_';

    public static $quene_key = 'quene';

    /**
     * 提取
     * @param  [type] $key [description]
     * @return [type]      [description]
     */
    public static function get($key)
    {
        $key   = self::modify_key($key);
        $redis = self::conn();
        $value = $redis->get($key);
        return unserialize($value);
    }

    /**
     * 保存
     * @param [type]  $key    [description]
     * @param [type]  $value  [description]
     * @param integer $expire [description]
     */
    public static function set($key, $value, $expire = 0)
    {
        $key   = self::modify_key($key);
        $redis = self::conn();
        $value = serialize($value);
        if ($expire > 0) {
            $status = $redis->setex($key, $expire, $value);
        } else {
            $status = $redis->set($key, $value);
        }
        return $status;
    }

    // 单独设置list的过期时间
    public static function expire($key, $expire = 3600)
    {
        $key = self::modify_key($key);
        return $redis->expire($key, $expire);
    }

    /**
     * 删除
     * @param  [type] $key [description]
     * @return [type]      [description]
     */
    public static function remove($key)
    {
        $key   = self::modify_key($key);
        $redis = self::conn();
        return $redis->delete($key);
    }

    // 大仓储存入
    public static function store_get($store, $key)
    {
        $diffkey = strval($store . $key);
        return self::get($diffkey);
    }

    // 大仓储取出
    public static function store_set($store, $key, $value, $expire = 604800)
    {
        $diffkey = strval($store . $key);
        return self::set($diffkey, $value, $expire = 0);
    }

    /**
     * 关闭链接
     */
    public function close()
    {
        $redis = self::conn();
        $redis->close();
    }

    /**
     * 析构函数，关闭redis链接，使用长连接时，最好主动调用关闭
     */
    public function __destruct()
    {
        $this->close();
    }

    /**
     * 向队列插入一条信息
     * @param $message
     * @return mixed
     */
    public static function lpush($message)
    {
        $key   = self::modify_key(self::$quene_key);
        $redis = self::conn();
        return $redis->lpush($key, $message);
    }

    /**
     * 向队列中插入一串信息
     * @param $message
     * @return mixed
     */
    public static function lpushs()
    {
        $key           = self::modify_key(self::$quene_key);
        $redis         = self::conn();
        $params        = func_get_args();
        $message_array = array_merge(array($key), $params);
        return call_user_func_array(array($redis, 'LPUSH'), $message_array);
    }

    /**
     * 出列 从左到右第一个 并删除
     */
    public static function lpop()
    {
        $key   = self::modify_key(self::$quene_key);
        $redis = self::conn();
        return $redis->LPOP($key);
    }

    /**
     * 获取所有列表数据（从头到尾取）
     * @param sting $key KEY名称
     * @param int $head  开始
     * @param int $tail     结束
     */
    public static function lrange($start, $stop)
    {
        $key   = self::modify_key(self::$quene_key);
        $redis = self::conn();
        return $redis->LRANGE($key, $start, $stop);
    }

    /**
     * 修剪现有列表
     */

    public static function ltrim($start, $stop)
    {
        $key   = self::modify_key(self::$quene_key);
        $redis = self::conn();
        return $redis->LTRIM($key, $start, $stop);
    }

    /**
     * 获得队列状态，即目前队列中的消息数量
     * @return mixed
     */
    public static function size()
    {
        $key   = self::modify_key(self::$quene_key);
        $redis = self::conn();
        return $redis->lSize($key);
    }

    /**
     * 判断key是否存在
     * @param string $key KEY名称
     */
    public static function isExists()
    {
        $key   = self::modify_key(self::$quene_key);
        $redis = self::conn();
        return $redis->exists($key);
    }

    /**
     * 获取所有key名，不是值
     */
    public static function keyAll()
    {
        $redis = self::conn();
        return $redis->keys('*');
    }

}
