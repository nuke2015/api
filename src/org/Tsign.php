<?php

namespace nuke2015\api\org;

// 远程签名
class Tsign
{
    // 自带私签
    public static function sign_private($key, $private)
    {
        $key = self::modify($key);

        return md5(md5($key).md5($private));
    }

    // 私签校验
    public static function sign_private_check($key, $private, $be_check)
    {
        $checkok = self::sign_private($key, $private);
        if ($be_check && $be_check == $checkok) {
            return 1;
        } else {
            return 0;
        }
    }

    // 自签名
    public static function sign($key)
    {
        $key = self::modify($key);

        return hash_env("tsign/sign#$key");
    }

    // 签名校验
    public static function sign_check($key, $be_check)
    {
        $checkok = self::sign($key);
        if ($be_check && $be_check == $checkok) {
            return 1;
        } else {
            return 0;
        }
    }

    // 单ip自动签名带偏移
    public static function signone_perip($mix = '')
    {
        $key = md5(time().uniqid());
        $ip = myhttp::client_ip();
        $sign = self::sign($key.md5($ip.$mix));

        return $key.'_'.$sign;
    }

    // 单ip自动校验带偏移
    public static function signone_check_perip($mix = '', $sign_check)
    {
        $result = 0;
        if ($sign_check && strlen($sign_check)) {
            list($key, $sign_now) = explode('_', $sign_check);
            if (strlen($key) > 8) {
                $ip = myhttp::client_ip();
                $signok = self::sign($key.md5($ip.$mix));
                if ($signok && $signok == $sign_now) {
                    $result = 1;
                }
            }
        }

        return $result;
    }

    //  一键签名
    public static function signone()
    {
        $key = md5(time().uniqid());
        $sign = self::sign($key);

        return $key.'_'.$sign;
    }

    // 一键校验
    public static function signone_check($sign_check)
    {
        $result = 0;
        if ($sign_check && strlen($sign_check)) {
            list($key, $sign_now) = explode('_', $sign_check);
            if (strlen($key) > 8) {
                $signok = self::sign($key);
                if ($signok && $signok == $sign_now) {
                    $result = 1;
                }
            }
        }

        return $result;
    }

    // 半开
    public static function half($str)
    {
        $len = strlen($str);
        if ($len % 2 == 0) {
            return array(substr($str, 0, $len / 2), substr($str, $len / 2));
        } else {
            return array(substr($str, 0, $len / 2), substr($str, $len / 2 + 1));
        }
    }

    // 自我修饰
    protected static function modify($key)
    {
        return 'tsign#'.strval($key);
    }
}
