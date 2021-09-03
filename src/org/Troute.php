<?php

namespace nuke2015\api\org;

// 兼容路由
// php index.php "/a/baiduLiandao/?a=1&b=2&c=3"
// http://chrome.loc.qinqinyuesao.com/a/baiduLiandao/?a=1&b=2&c=3
class Troute
{
    // 把cli时的argv附加参数转化为标准的$_request变量.
    public static function argv_to_request()
    {
        if ($_SERVER['argv'] && count($_SERVER['argv']) > 1) {
            $str = $_SERVER['argv'][1];
            if (stripos($str, '?') !== false) {
                $arr = explode('?', $str);
                if ($arr && count($arr)) {
                    $_REQUEST['s'] = $arr[0];
                    parse_str($arr[1], $tmp);
                    if ($tmp && count($tmp)) {
                        $_REQUEST = array_merge($_REQUEST, $tmp);
                    }
                }
            } else {
                $_REQUEST['s'] = $str;
            }
            $_SERVER['REQUEST_URI'] = $str;
            $_SERVER['QUERY_STRING'] = 's='.$str;
        }

        return $_REQUEST;
    }

    // 软路由
    // 浏览器:http://crontab.loc.qinqinyuesao.com/index.php?s=/a/b/c/d.html
    // 命令行 php crontab_cube.php "/a/b/c/d.html"
    public static function route_seo()
    {
        // 参数注入
        self::argv_to_request();

        $return = array();
        
        // 必须query_string
        $str = $_SERVER['REQUEST_URI'];
        if (!$str) {
            $str = trim($_SERVER['argv'][1]);
        } else {
            if (strripos($str, '.html')) {
                $str = str_ireplace('.html', '', $str);
            }
            // 记入日志
            $_REQUEST['_REQUEST_URI'] = $str;
        }
        // 前半截
        if (strripos($str, '?')) {
            $str = substr($str, stripos($str, '?'));
        }
        $return = explode('/', $str);
        if ($return && count($return)) {
            array_shift($return);
        }
        return $return;
    }
}
