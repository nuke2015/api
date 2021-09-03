<?php

namespace nuke2015\api\org;

use think\Config;

// 利用openssl跨项目加解密
class SignSSLService
{
    // 加密
    public static function encode($data, $mix = '')
    {
        $key = Config::get('token_salt');
        $key .= $mix;
        $str = serialize($data);
        $str = openssl_encrypt($str, 'DES-ECB', md5($key));
        return base64_encode($str);
    }

    // 解密
    public static function decode($str, $mix = '')
    {
        $key = Config::get('token_salt');
        $key .= $mix;
        $str = base64_decode($str);
        $str = openssl_decrypt($str, 'DES-ECB', md5($key));
        return unserialize($str);
    }
}
