<?php

namespace nuke2015\api\org;


// 利用openssl跨项目加解密
class SignSSLService
{
    // 加密
    public static function encode($data, $mix = '')
    {
        $key = hash_env($mix);
        $str = serialize($data);
        $str = openssl_encrypt($str, 'DES-ECB', md5($key));
        return base64_encode($str);
    }

    // 解密
    public static function decode($str, $mix = '')
    {
        $key = hash_env($mix);
        $str = base64_decode($str);
        $str = openssl_decrypt($str, 'DES-ECB', md5($key));
        return unserialize($str);
    }
}
