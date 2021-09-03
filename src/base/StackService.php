<?php
namespace nuke2015\api\base;

//接口签名
class StackService
{
    private static $key = '';

    // 指定水管长度
    public function __construct($key, $size)
    {
        // 队列越长,查询越慢
        if ($size > 100) {
            die('ling long,query slow');
        }
        $store     = ['size' => $size, 'data' => []];
        self::$key = self::modify($key);
        $exist     = CacheRedis::isExists(self::$key);
        if (!$exist) {
            CacheRedis::set(self::$key, $store);
        } else {
            // 重新调整大小
            $store = CacheRedis::get(self::$key);
            if ($store['size'] != $size) {
                CacheRedis::remove(self::$key);
            }
        }
    }

    // 入栈
    public function push($item)
    {
        $store = CacheRedis::get(self::$key);
        if ($store && count($store)) {
            if (is_array($store['data']) && count($store['data']) >= $store['size']) {
                array_shift($store['data']);
            }
            // 压入新值
            array_push($store['data'], $item);
            CacheRedis::set(self::$key, $store);
        }
    }

    // 第一个,只读取不改变.
    public function get_first()
    {
        $store = CacheRedis::get(self::$key);
        if ($store && count($store)) {
            if ($store['data'] && count($store['data'])) {
                return array_shift($store['data']);
            }
        }
    }

    // 最后一个,只读取不改变
    public function get_last()
    {
        $store = CacheRedis::get(self::$key);
        if ($store && count($store)) {
            if ($store['data'] && count($store['data'])) {
                return array_pop($store['data']);
            }
        }
    }

    // 读取全部
    public function get_all()
    {
        $store = CacheRedis::get(self::$key);
        if ($store && count($store)) {
            if ($store['data'] && count($store['data'])) {
                return $store['data'];
            }
        }
    }

    // 键名修饰
    public static function modify($key)
    {
        return 'StackService#' . md5($key);
    }
}
