<?php

namespace nuke2015\api\org;

// 全捕捉
define('E_FATAL', E_ERROR | E_USER_ERROR | E_CORE_ERROR |
    E_COMPILE_ERROR | E_RECOVERABLE_ERROR | E_PARSE);

// TError::listen();

// 出错监听
class TError
{
    // 监听
    public static function listen()
    {
        set_error_handler([__CLASS__, 'error_handler']);
        set_exception_handler([__CLASS__, 'exception_handler']);
        register_shutdown_function([__CLASS__, 'fatal_handler']);
    }

    // 获取fatal error
    public static function fatal_handler()
    {
        // 未端勾子
        $error = error_get_last();
        // 识别目标错误类型
        if ($error && ($error['type'] === ($error['type'] & E_FATAL))) {
            self::error_handler($error['type'], $error['message'], $error['file'], $error['line']);
        }
    }

    // 获取所有的error
    public static function exception_handler($except)
    {
        $e = ['code' => $except->getCode(), 'message' => $except->getMessage(), 'file' => $except->getFile(), 'line' => $except->getLine()];
        // 忽略notice
        if ($e['code'] < 8) {
            self::elog($e);
            if (defined('ENV_ONLINE') && !ENV_ONLINE) {
                echo '<pre>';
                var_dump($e);
                exit;
            } else {
                $MODULE_NAME = defined('MODULE_NAME') ? MODULE_NAME : 'default';
                exit($MODULE_NAME . ' error:' . $e['file'] . '#' . $e['line']);
            }
        }
    }

    // 获取所有的error
    public static function error_handler($errno, $errstr, $errfile, $errline)
    {
        $e = ['code' => $errno, 'message' => $errstr, 'file' => $errfile, 'line' => $errline];
        // 忽略notice
        if ($e['code'] < 8) {
            self::elog($e);
            if (defined('ENV_ONLINE') && !ENV_ONLINE) {
                echo '<pre>';
                var_dump($e);
                exit;
            } else {
                $MODULE_NAME = defined('MODULE_NAME') ? MODULE_NAME : 'default';
                exit($MODULE_NAME . ' error:' . $e['file'] . '#' . $e['line']);
            }
        }
    }

    // 输出日志
    public static function elog($e)
    {
        Flogger::plog('error', $e);
    }
}
