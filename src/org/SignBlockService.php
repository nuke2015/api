<?php

namespace nuke2015\api\org;

// 区块链签名技术
final class SignBlockService
{
    // 上签名,按时间自动递增
    public static function block_sign($data, $channel = 'shop_order', $now = 0)
    {
        if (!$now) {
            $now = time();
        }

        $key = hash_env($channel . $now);
        $str = self::block_zip($data);

        return $now . '_' . md5($channel . md5($str) . md5($key));
    }

    // 验证签名,可验证历史签名
    public static function block_check($data, $sign_be_check, $channel = 'shop_order')
    {
        $result                = 0;
        list($now, $sign_null) = explode('_', $sign_be_check);
        if ($now) {
            $sign_real_str = self::block_sign($data, $channel, $now);
            if ($sign_be_check && $sign_real_str && $sign_be_check == $sign_real_str) {
                $result = 1;
            }
        }

        return $result;
    }

    // 会过期的签名服务
    public static function block_expire_sign($channel = 'shop_order', $data, $expire = 86400)
    {
        $now     = time() + $expire;
        $channel = hash_env($channel . '_expire_auto_' . $now);
        return self::block_sign($data, $channel, $now);
    }

    // 会过期的blockchain服务
    public static function block_expire_check($channel = 'shop_order', $data, $sign_check)
    {
        $result                = 0;
        list($now, $sign_null) = explode('_', $sign_check);
        if ($now >= time()) {
            $channel = hash_env($channel . '_expire_auto_' . $now);
            $result  = self::block_check($data, $sign_check, $channel);
        }
        return $result;
    }

    // 规范化
    // 改用json,避免int型与string的差异导致验签失败.
    public static function block_zip($data)
    {
        return json_encode($data);
    }
}
