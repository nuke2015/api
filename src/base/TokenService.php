<?php
namespace nuke2015\api\base;

//接口签名
class TokenService
{
    public $token_prefix;

    public function __construct($type)
    {
        $this->token_prefix = $type;
    }

    // 修饰
    private function diffkey($key)
    {
        return $this->token_prefix . '_' . md5(strval($key));
    }

    // 重载
    public function get($key)
    {
        $diffkey = $this->diffkey($key);
        return CacheRedis::get($diffkey);
    }

    // 重载
    public function remove($key)
    {
        $diffkey = $this->diffkey($key);
        return CacheRedis::remove($diffkey);
    }

    // 重载,默认两个月
    public function set($key, $value, $expire_time = 5184000)
    {
        $diffkey = $this->diffkey($key);
        return CacheRedis::set($diffkey, $value, $expire_time);
    }

    // 白名单
    public function blacklist()
    {
        $WHITE_LIST_METHODS = 'SmsLoginYuesao,SmsLoginUser,SmsCheckYuesao,SmsCheckUser';
        return explode(',', $WHITE_LIST_METHODS);
    }

    /**
     * 来访请求,通信指纹校验
     * @return [type] [description]
     */
    public function check_token($key, $token_check)
    {
        $diffkey = $this->diffkey($key);
        $result  = false;

        // 独立存储不走重载方法,避免两次diffkey
        $token_real = CacheRedis::get($diffkey);
        if ($token_real && $token_real == $token_check) {
            $result = true;
        }
        return $result;
    }

    /**
     * 通信指纹产生并存储;
     * @return [type] [description]
     */
    public function make_token($key, $expire_time = 5184000)
    {
        $diffkey = $this->diffkey($key);

        // 随机指纹避免逆运算
        $token = strtolower(md5($diffkey . time() . uniqid(mt_rand(), true)));

        // 独立存储不走重载方法,避免两次diffkey
        CacheRedis::set($diffkey, $token, $expire_time);
        return $token;
    }
}
