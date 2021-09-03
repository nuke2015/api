<?php

namespace nuke2015\api\org;

//定义开始时间
define('LOGGER_TIME_INIT', microtime(1));

class Flogger
{
    public static $log_data = array();

    //登记
    public static function sign($tag)
    {
        self::$log_data[$tag] = microtime(1);
    }

    //耗时
    public static function spent($tag1, $tag2)
    {
        $log_data = self::$log_data;
        if (isset($log_data[$tag1]) && isset($log_data[$tag2])) {
            return round(($log_data[$tag1] - $log_data[$tag2]), 4) * 1000;
        } else {
            return -1;
        }
    }

    //详情
    public static function logs()
    {
        $result = array();
        $result['LOGGER_TIME_INIT'] = LOGGER_TIME_INIT * 1000;
        if (self::$log_data && count(self::$log_data)) {
            foreach (self::$log_data as $key => $value) {
                $result[$key] = round($value - LOGGER_TIME_INIT, 4) * 1000;
            }
        }

        return $result;
    }

    // 日志记录
    public static function save($title, $logdata, $code, $spent, $client_ip)
    {
        $insert = array();
        $insert['group'] = MODULE_NAME;
        $insert['title'] = $title;
        $insert['code'] = (int) $code;
        $insert['ip'] = $client_ip;
        $insert['useragent'] = UserAgentParse::ua();
        $insert['spent'] = $spent;
        $insert['create_at'] = time();
        $insert['data'] = $logdata;

        return $insert;
    }

    //线上临时追踪
    public static function trace($title, $data)
    {
        if (isset($_REQUEST['_trace'])) {
            self::plog('_trace_' . $title, $data);
        }
    }

    // 仿mongo的json文件格式化存储,只记录不中断
    public static function json_log($filename, $data)
    {
        // 本地存档
        self::write($filename, json_encode((object) $data, JSON_UNESCAPED_UNICODE) . "\r\n");
        // self::mqtt($filename, $data);
    }

    // 直接存储只记录不中断
    public static function plog($filename, $data)
    {
        $data_log = ['data' => $data, 'req' => $_REQUEST, 'env' => self::env()];
        // 本地存档
        self::write($filename, json_encode($data_log, JSON_UNESCAPED_UNICODE) . "\r\n");
        // self::mqtt($filename, $data_log);
    }

    // mqtt
    public static function mqtt($filename, $data)
    {
        $module_name = defined('CUBE_MODULE') ? CUBE_MODULE : 'default';
        // 外网才发消息队列,本地就算了,都是些debug
        if (defined('ENV_ONLINE') && ENV_ONLINE > 0) {
            return aliyun\mqtt::send($filename, ['src' => $module_name . '_' . $filename, 'data' => $data]);
        }
    }

    // 写日志
    public static function write($filename, $txt)
    {
        $module_name = defined('CUBE_MODULE') ? CUBE_MODULE : 'debug';
        $runtime_path = defined('RUNTIME_PATH') ? RUNTIME_PATH : '/home/ddys_run/';
        $filename = $runtime_path . '/log/' . $module_name . '_' . $filename . '_' . date('Y_m_d') . '.log';
        if (defined('MODULE_NAME') && (MODULE_NAME != 'logcenter')) {
            // 避免写两份
            return file_put_contents($filename, $txt, FILE_APPEND);
        }
    }

    // 上报日志中心
    public static function logcenter_notify($filename, $data)
    {
        return;
    }

    // 取得环境信息
    public static function env()
    {
        $data_env = array();

        $data_env['url'] = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $data_env['ip'] = myhttp::client_ip();
        $data_env['time'] = date('Y_m_d H:i:s');
        $data_env['ua'] = UserAgentParse::ua();
        $data_env['method'] = $_SERVER['SERVER_PROTOCOL'] . '_' . $_SERVER['REQUEST_METHOD'];
        $data_env['refer'] = isset($_SERVER['HTTP_REFERER']) ? trim($_SERVER['HTTP_REFERER']) : '';
        $data_env['PHPSESSID'] = isset($_COOKIE['PHPSESSID']) ? trim($_COOKIE['PHPSESSID']) : '';

        return $data_env;
    }
}
