<?php
namespace nuke2015\api\service;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

/**
 * 远程响应网关
 */
class RpcService
{
    private $topic;
    private $key;
    private $host;

    public function __construct($topic, $key)
    {
        $this->topic = $topic;
        $this->key   = $key;
        return $this;
    }

    // 上行
    public function send($host, $param)
    {
        $rnd               = time();
        $sign              = $this->md5($this->topic, $this->key);
        $sign              = $this->md5($sign, $rnd);
        $sign              = $rnd . '_' . $sign;
        list($code, $data) = $this->guzzle($host, $param, $sign);
        if ($code == 200) {
            return json_decode($data, 1);
        }
    }

    // 发包
    public function guzzle($host, $data, $sign)
    {
        $client = new Client([
            'base_uri' => $host,
            'timeout'  => 2.0,
        ]);
        $response = $client->request('POST', $host, [
            'form_params' => ['sign_rpc' => $sign, 'package' => $data],
        ]);
        $result = '';
        $code   = $response->getStatusCode(); // 200
        if ($code == 200) {
            $body   = $response->getBody();
            $result = $body->getContents();
        }

        return [$code, $result];
    }

    // 先校验签名才回复数据
    public function result($data)
    {
        $sign_rpc             = trim($_REQUEST['sign_rpc']);
        list($rnd, $sign_may) = explode('_', $sign_rpc);
        if (!$rnd || !$sign_may) {
            // 无签名
            $result = [-1, 'sign_rpc empty!'];
        } else {
            $sign = $this->md5($this->topic, $this->key);
            $sign = $this->md5($sign, $rnd);
            if (!$sign_may || $sign != $sign_may) {
                // 签名错误
                $result = [-2, 'sign_rpc fail!'];
            } elseif ($rnd < time() - 600) {
                // 限制十分钟内
                $result = [-3, 'sign_rpc expire!'];
            } else {
                $result = [0, $data];
            }
        }
        echo json_encode($result);
        return;
    }

    // 签名
    private function md5($k, $v)
    {
        return md5(md5($k) . md5($v));
    }
}
