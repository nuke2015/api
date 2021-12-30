<?php

namespace nuke2015\api\org;

class myhttp
{
    public static $debug = 0;
    // 移动端
    public static $mobile = 1;

    // 单例模式优化
    public static $ch;

    // 共享内存句柄
    private static function init()
    {
        if (!self::$ch) {
            self::$ch = curl_init();
        }

        return self::$ch;
    }

    // 单线程
    public static function curl($url, $method = 'get', $params = array(), $header = array(), $opts = array())
    {
        $ch = self::init();
        // 参数构造
        self::build($opts, $method, $header, $params);
        // 目标地址
        $url = trim($url);
        // GET请求
        if (strtolower($method) == 'get' && is_array($params) && count($params)) {
            $url .= '?' . http_build_query($params);
        }

        // 修复head协议
        if (strtolower($method) == 'head') {
            $opts[CURLOPT_NOBODY] = true;
        } else {
            $opts[CURLOPT_NOBODY] = false;
        }

        $opts[CURLOPT_URL] = $url;
        curl_setopt_array($ch, $opts);
        $data = curl_exec($ch);
        $error = curl_error($ch);
        if (self::$debug && $error) {
            throw new Exception('请求发生错误：' . $url . '#' . $error);
        }

        return $data;
    }

    // 模拟多线程
    public static function multicurl($urls, $method = 'get', $params = array(), $header = array(), $opts = array())
    {
        // 参数构造
        self::build($opts, $method, $header, $params);
        $mh = curl_multi_init();
        $chArray = array();
        foreach ($urls as $key => $url) {
            // 目标地址
            $url = trim($url);
            // GET请求
            if (strtolower($method) == 'get' && is_array($params) && count($params)) {
                $url .= '?' . http_build_query($params);
            }
            $opts[CURLOPT_URL] = $url;
            $ch = curl_init();
            curl_setopt_array($ch, $opts);
            curl_multi_add_handle($mh, $ch);
            $chArray[$key] = $ch;
        }

        // 归零
        $running = null;
        do {
            curl_multi_exec($mh, $running);
        } while ($running > 0);

        $return = array();
        foreach ($chArray as $key => $ch) {
            $return[$key] = curl_multi_getcontent($ch);
            curl_multi_remove_handle($mh, $ch);
        }
        curl_multi_close($mh);

        return $return;
    }

    // 方法构造
    private static function build(&$opts, $method, $header = array(), $params = '')
    {
        //补充参数
        $opts_base = array(CURLOPT_TIMEOUT => 1, CURLOPT_RETURNTRANSFER => 1, CURLOPT_SSL_VERIFYPEER => false, CURLOPT_FOLLOWLOCATION => 1, CURLOPT_SSL_VERIFYHOST => false);

        if (self::$mobile) {
            // iphone
            $opts[CURLOPT_USERAGENT] = 'Mozilla/5.0 (iPhone; CPU iPhone OS 9_1 like Mac OS X) AppleWebKit/601.1.46 (KHTML, like Gecko) Version/9.0 Mobile/13B143 Safari/601.1';
        } else {
            // pc端
            $opts[CURLOPT_USERAGENT] = 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/59.0.3047.4 Safari/537.36';
        }
        foreach ($opts_base as $key => $value) {
            if (!isset($opts[$key])) {
                $opts[$key] = $value;
            }
        }
        // 补充头文件
        if (is_array($header) && count($header)) {
            $opts[CURLOPT_HTTPHEADER] = $header;
        }
        if (self::$debug) {
            // 临时调试
            $opts[CURLOPT_HEADER] = 1;
        }
        if (strtolower($method) == 'post') {
            $opts[CURLOPT_POST] = 1;

            // 参数
            if (is_array($params) && count($params)) {
                $vars = http_build_query($params);
            } elseif (is_string($params)) {
                $vars = $params;
            }
            $opts[CURLOPT_POSTFIELDS] = $vars;
        }

        return $opts;
    }

    // 轻量协议头
    // http://sd
    // Array
    // (
    //     [0] => -1
    //     [1] => Resolving timed out after 1606 milliseconds
    // )
    public static function head($url, $proxy = '')
    {
        if ($proxy) {
            $opts[CURLOPT_PROXY] = $proxy;
        }
        self::curl($url, 'head', [], [], $opts);

        return self::header();
    }

    // 异步提取
    public static function header()
    {
        // 提取返回值
        if (curl_errno(self::$ch)) {
            $return = array(-1, curl_error(self::$ch));
        } else {
            $return = array(1, curl_getinfo(self::$ch));
        }

        return $return;
    }

    //客户ip
    public static function client_ip($type = 0)
    {
        $type = $type ? 1 : 0;
        static $ip = null;
        if ($ip !== null) {
            return $ip[$type];
        }

        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            $pos = array_search('unknown', $arr);
            if (false !== $pos) {
                unset($arr[$pos]);
            }

            $ip = trim($arr[0]);
        } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        // IP地址合法验证
        $long = sprintf('%u', ip2long($ip));
        $ip = $long ? array($ip, $long) : array('0.0.0.0', 0);

        return $ip[$type];
    }

    // post纯文本变量
    public static function postfield($url, $text = '', $header = array(), $opt = array())
    {
        return self::curl($url, 'post', $text, $header, $opt);
    }

    // 带cookie的采集
    public static function curl_cookie($url, $header, $cookie_file = '', $proxy = '')
    {
        // proxy
        if ($proxy) {
            $opts[CURLOPT_PROXY] = $proxy;
        }
        // cookie
        if ($cookie_file) {
            $cookie_file = RUNTIME_PATH . '/cookie/' . md5($cookie_file);
        } else {
            $cookie_file = tempnam('/tmp', time() . rand(0, 999999));
        }

        $opts[CURLOPT_COOKIEJAR] = $cookie_file;

        return self::curl($url, 'get', [], $header, $opts);
    }

    // 特殊请求DELETE,json;
    public function post_delete($url, $text = '', $header = array(), $opt = array())
    {
        // 增加删除指令
        $opt[CURLOPT_CUSTOMREQUEST] = 'DELETE';

        return self::postfield($url, $text, $header, $opt);
    }

    // curl -F发送文件,注意超时影响;
    // $file = '/www/php/site/demo.png';
    // $req = ['description' => 'demo', 'media' => new \CURLFile($file)];
    public function sendfile($url, $data = '', $method = 'POST')
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        if (!empty($data)) {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);

        return $output;
    }

    // 证书读取
    public function cert_info($server_name)
    {
        // 容错
        $server_name = str_ireplace(['http://', 'https://'], [], $server_name);
        $context = stream_context_create([
            'ssl' => [
                'capture_peer_cert' => true,
                'capture_peer_cert_chain' => true,
            ],
        ]);
        $resource = stream_socket_client("ssl://$server_name:443", $errno, $errstr, 30, STREAM_CLIENT_CONNECT, $context);
        $params = stream_context_get_params($resource);
        $cert = $params['options']['ssl']['peer_certificate'];
        $cert_info = openssl_x509_parse($cert);
        return $cert_info;
    }

    // 单例模式
    public function __destruct()
    {
        if(self::$ch)\curl_close(self::$ch);
    }
}
