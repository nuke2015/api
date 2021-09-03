<?php

namespace nuke2015\api\base;

// 事务处理型脚手架,基于file基类
trait CacheOrDo
{
    // 干.有则取之,无则补之
    public static function cache_or_do($param, $func, $expire = 3600)
    {
        $key = self::cache_or_key($param);
        $CacheMedia = self::connect();
        $data = $CacheMedia->get($key);
        if (!$data) {
            $data = $func($param);
            if (!$data['code']) {
                // 有数据,才缓存无数据,会导致重取!
                $CacheMedia = self::connect();
                $CacheMedia->set($key, $data, $expire);
            }
        }

        return $data;
    }

    // 干-,有则除之
    public static function cache_or_remove($param)
    {
        $key = self::cache_or_key($param);
        $CacheMedia = self::connect();
        $CacheMedia->remove($key);
    }

    // 干,提名
    private static function cache_or_key($param)
    {
        ksort($param);
        $key = md5(json_encode($param));

        return 'key_'.$key;
    }

    // 分量分批
    public static function step_by_step($key, $length = 20, $do, $source, $expire = 3600)
    {
        $CacheMedia = self::connect();
        $key = 'step_'.md5($key);
        list($total, $data) = $CacheMedia->get($key);
        if (!$data) {
            // 首次补充原始数据
            $data = $source();
            $total = count($data);
        }
        // 若有数据开始剪切
        if ($data && count($data)) {
            $tmp = array_splice($data, 0, $length);
            if ($tmp && count($tmp)) {
                // 有切片
                if ($tmp) {
                    $done = $do($tmp);
                }
            }
            // 处理完了
            if (count($data) == 0) {
                return [count($data), $total];
            } else {
                // 匿名函数,会导致上下文发生变化,所以,对外部资源,要慎之又慎!
                $CacheMedia = self::connect();
                if ($done) {
                    $CacheMedia->set($key, [$total, $data], $expire);
                }
            }
        }

        return [count($data), $total];
    }

    // 清除分量分批
    public static function step_by_step_remove($key)
    {
        $CacheMedia = self::connect();
        $key = 'step_'.md5($key);

        return $CacheMedia->remove($key);
    }

    // 分量分批,按页码
    public static function page_by_page($key, $size = 20, $do, $total, $expire = 3600)
    {
        $CacheMedia = self::connect();
        $key = 'page_'.md5($key);
        $page = $CacheMedia->get($key);
        if (!$page) {
            $page = 1;
        }
        if ($page <= ceil($total/$size)) {
            $done = $do($page, $size);
            if ($done) {
                ++$page;
                // 匿名函数,会导致上下文发生变化,所以,对外部资源,要慎之又慎!
                $CacheMedia = self::connect();
                $CacheMedia->set($key, $page, $expire);
            }
        }

        return $page;
    }

    // 清除分量分批,按页码
    public static function page_by_page_remove($key)
    {
        $CacheMedia = self::connect();
        $key = 'page_'.md5($key);

        return $CacheMedia->remove($key);
    }
}
