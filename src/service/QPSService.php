<?php

namespace nuke2015\api\service;

use nuke2015\api\base;
use nuke2015\api\org;

class QPSService extends BaseService
{
    private static $app = MODULE_NAME;

    // $status = $this->check('13530861042', 5, 3, 86400);
    // 达到5次,锁3秒,统计周期一天
    public static function check($index, $limit, $expire, $watch_time = '60')
    {
        $return = false;
        // 增加app模块区分
        $key = self::key_modify($index);

        // 锁了没
        $lock = base\LockService::check($key);
        if (!$lock) {
            // 带上本次,一分钟计数器
            $count = base\CacheCount::setinc($key, 1, $watch_time);
            if ($count >= $limit) {

                // 记日志
                self::plog('qps', array('app' => self::$app, 'key' => $index, 'limit' => $limit, 'count' => $count));
                // 锁住
                base\LockService::lock($key, $expire);

                // 同时清空数据
                base\CacheCount::remove($key);
            } else {
                $return = true;
            }
        }
        return $return;
    }

    // 手动清除
    public static function remove($key)
    {
        $key = self::key_modify($key);
        return base\CacheCount::remove($key);
    }

    // 解锁
    public static function unlock($key, $app = '')
    {
        if ($app) {
            self::$app = $app;
        }

        self::remove($key);
        //var_dump(self::$app, $key);
        $key = self::key_modify($key);
        return base\LockService::unlock($key);
    }

    // 修饰
    private static function key_modify($key)
    {
        return self::$app . '#QPSService#' . md5($key);
    }

    // 发送短信验证码,攻击拦截
    public static function sms_send($mobile, $max = 3)
    {
        // 单ip限100次,锁半天,统计周期一天
        $ip = org\myhttp::client_ip();
        $ua = org\UserAgentParse::ua();

        // ua15次锁定两小时,加强时间锁定!
        $key_qps = CUBE_MODULE . '#sms_send#ipua_' . $ip . $ua;
        if (!self::check($key_qps, 30, 7200)) {
            return array(ERR_FREQUENTLY, '请休息2小时后再试!');
        }

        $key_qps = CUBE_MODULE . '#sms_send#ip' . $ip;
        if (!self::check($key_qps, 300, 43200, 86400)) {
            return array(ERR_FREQUENTLY, '请明天再试!');
        }

        // 单号码限3次,销半天,统计周期半天
        $key_qps = CUBE_MODULE . '#sms_send#mobile#' . $mobile;
        if (!self::check($key_qps, $max, 43200, 600)) {
            return array(ERR_FREQUENTLY, '此号码操作太频繁!');
        }
    }

    // 发送短信验证码,攻击拦截
    public static function device_send($mobile)
    {
        // 单ip限100次,锁半天,统计周期一天
        $ip = org\myhttp::client_ip();
        $ua = org\UserAgentParse::ua();

        // ua15次锁定两小时,加强时间锁定!
        $key_qps = CUBE_MODULE . '#sms_send#ipua_' . $ip . $ua;
        if (!self::check($key_qps, 30, 7200)) {
            return array(ERR_FREQUENTLY, '请休息2小时后再试!');
        }

        $key_qps = CUBE_MODULE . '#sms_send#ip' . $ip;
        if (!self::check($key_qps, 300, 43200, 86400)) {
            return array(ERR_FREQUENTLY, '请明天再试!');
        }
    }

    // 校验短信验证码,攻击拦截
    public static function sms_check($mobile)
    {
        // 单ip限100次,锁半天,统计周期一天
        $ip = org\myhttp::client_ip();
        $ua = org\UserAgentParse::ua();

        // ua15次锁定两小时,加强时间锁定!
        $key_qps = CUBE_MODULE . '#sms_check#ipua_' . $ip . $ua;
        if (!self::check($key_qps, 160, 7200)) {
            return array(ERR_FREQUENTLY, '请休息2小时后再试!');
        }

        $key_qps = CUBE_MODULE . '#sms_check#ip' . $ip;
        if (!self::check($key_qps, 900, 43200, 86400)) {
            return array(ERR_FREQUENTLY, '请明天再试!');
        }

        // 单号码限3次,销半天,统计周期半天
        $key_qps = CUBE_MODULE . '#sms_check#mobile#' . $mobile;
        if (!self::check($key_qps, 40, 43200, 43200)) {
            return array(ERR_FREQUENTLY, '请换个号码再试!');
        }
    }
}
