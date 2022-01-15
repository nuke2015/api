<?php

namespace nuke2015\api\service;

use nuke2015\api\config;
use nuke2015\api\org;
use nuke2015\api\org\weixin\aes;

// 小程序登陆类
// https://developers.weixin.qq.com/miniprogram/dev/api/getPhoneNumber.html
class WxappLoginService extends BaseService
{
    private $config;

    // 指定是哪个小程序的服务
    public function __construct($appid)
    {
        $config = config\ConfigWxapp::get_by_appid($appid);
        if (!$config) {
            throw new \Exception("wxapp config fail", 1);
        }
        $this->config = $config;
    }

    // 提函数
    public function jscode2session($js_code)
    {
        // 单ip一分钟内并发100个注册动作就拦停
        $ip      = org\myhttp::client_ip();
        $key_qps = 'weixinlogin#ip_' . $ip;
        if (!QPSService::check($key_qps, 100, 43200)) {
            throw new \Exception('ip-qps', 1);
        }

        $config = $this->config;
        $url    = "https://api.weixin.qq.com/sns/jscode2session?appid={$config['appid']}&secret={$config['appsecret']}&js_code={$js_code}&grant_type=authorization_code";

        $json = org\myhttp::curl($url, 'get', [], [], [CURLOPT_TIMEOUT => 3]);
        $this->log('wxapplogin', ['jscode2session', $js_code, $json]);
        // {"session_key":"gsBDZMbZToPNNgZCBYH2Tw==","openid":"o1Xil5KHXu100rL8v0IWAnraYRIk"}
        if ($json) {
            $data = json_decode(trim($json), 1);
            if ($data && count($data)) {
                return $data;
            } else {
                throw new \Exception($json, 1);
            }
        } else {
            throw new \Exception("jscode2session connect fail", 1);
        }
    }

    public function login($js_code, $encryptedData, $iv)
    {
        $sess   = $this->jscode2session($js_code);
        $openid = $sess['openid'];
        $this->log('wxapplogin', [$sess, $encryptedData, $iv]);
        // 配置
        $config = $this->config;

        $WXBizDataCrypt = new aes\WXBizDataCrypt($config['appid'], $sess['session_key']);
        $errCode        = $WXBizDataCrypt->decryptData($encryptedData, $iv, $json);
        $this->log('wxapplogin', ['login', $errCode, $json]);
        if (!$errCode) {

            // 三方登陆
            $data = json_decode(trim($json), 1);

            if ($data && count($data)) {
                // {
                //     "openId": "OPENID",
                //     "nickName": "NICKNAME",
                //     "gender": GENDER,
                //     "city": "CITY",
                //     "province": "PROVINCE",
                //     "country": "COUNTRY",
                //     "avatarUrl": "AVATARURL",
                //     "unionId": "UNIONID",
                //     "watermark":
                //     {
                //         "appid":"APPID",
                //     "timestamp":TIMESTAMP
                //     }
                // }
                return $data;
            } else {
                throw new \Exception($josn, 1);
            }
            throw new \Exception($josn, 1);
        } else {
            throw new \Exception($errCode, 1);
        }
    }

    // 配置
    public function regedit($js_code, $encryptedData, $iv)
    {
        $sess   = $this->jscode2session($js_code);
        $openid = $sess['openid'];
        $this->log('wxapplogin', [$sess, $encryptedData, $iv]);

        // 配置
        $config = $this->config;

        $WXBizDataCrypt = new aes\WXBizDataCrypt($config['appid'], $sess['session_key']);
        $errCode        = $WXBizDataCrypt->decryptData($encryptedData, $iv, $json);
        $this->log('wxapplogin', ['regedit', $errCode, $json]);
        if (!$errCode) {
            // 三方登陆,注册的时候只有手机号相关,并没有用户昵称与头像
            $data = json_decode(trim($json), 1);
            if ($data && count($data)) {
                // {
                //     "phoneNumber":"13580006666",
                //     "purePhoneNumber":"13580006666",
                //     "countryCode":"86",
                //     "watermark":
                //     {
                //         "appid":"APPID",
                //         "timestamp":TIMESTAMP
                //     }
                // }
                $data['openId'] = $openid;
                return $data;
            } else {
                throw new \Exception($json, 1);
            }
        } else {
            throw new \Exception($errCode, 1);
        }
    }
}
