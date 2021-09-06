<?php

namespace nuke2015\api\org;

// 超远程签名,一次失效,避免签名重复使用
class RpcSign
{

    // 自签名
    public static function sign_req()
    {
        $req             = [];
        $req['time']     = time();
        $req['half']     = Tsign::signone();
        $req['timesign'] = self::timesign($req['time'], $req['half']);
        return $req;
    }

    // 签名校验
    public static function sign_check($time, $half, $timesign)
    {
        $return = 0;
        $check  = Tsign::signone_check($half);
        if ($check) {
            $timesign_now = self::timesign($time, $half);
            if ($timesign_now == $timesign) {
                $return = 1;
            }
        }
        return $return;
    }

    // 时间校验
    private static function timesign($time, $key)
    {
        $time = strval($time);
        $key  = strval($key);
        return md5(md5($time) .'#'. md5($key));
    }
}
