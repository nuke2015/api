<?php

namespace nuke2015\api\org;

class Tcurl
{
    // 从api接口取数据
    public static function curl_api($param = array(), $timeout = 1)
    {
        if (!$param) {
            $param = $_REQUEST;
        }
        if (isset($param['s'])) {
            unset($param['s']);
        }
        if (isset($param['_QUERY_STRING'])) {
            unset($param['_QUERY_STRING']);
        }

        // 全局参数
        $param['version']  = '2.5';
        $param['platform'] = 0;

        // 内存召唤
        return self::namesapce_call('api', $param);

        // 内网召唤
        // return self::web_curl('127.0.0.128:80',$param,$timeout);
        // return self::web_curl('api.t.example.com',$param,$timeout);

        // 文件召唤,性能不好
        // return self::file_get('http://127.0.0.128/index.php', $param);
        // return self::file_get('http://127.0.0.191/index.php', $param);
    }

    // 从api_crm接口取数据
    public static function curl_api_crm($param = array(), $timeout = 1)
    {
        if (!$param) {
            $param = $_REQUEST;
        }
        unset($param['s']);

        // 内存召唤
        return self::namesapce_call('api_crm', $param);

        // 内网召唤
        // return self::web_curl('127.0.0.137:80',$param,$timeout);
        // return self::web_curl('api.t.example.com',$param,$timeout);

        // 文件召唤,性能不好
        // return self::file_get('http://127.0.0.137/index.php', $param);
    }

    // 内存调用性能好,但架构不如socket纯粹
    public static function namesapce_call($app, $param)
    {
        $ctrl = "\ijiazhen\app\\$app\controller\Index";
        $api  = new $ctrl();
        return $api->index($param);
    }

    // 内网召唤,直走socket架构简单,但是不快
    public static function web_curl($url, $param, $timeout)
    {
        $json = myhttp::curl($url, 'GET', $param, array(), [CURLOPT_TIMEOUT => $timeout]);
        if ($json) {
            return json_decode($json, 1);
        }
    }

    // 本地文件
    public static function file_get($url, $param)
    {
        $url .= '?' . http_build_query($param);
        // var_dump($url);exit;
        $txt = file_get_contents($url);
        if ($txt) {
            return json_decode($txt, 1);
        }
    }
}
