<?php

use nuke2015\api\base;

// 测试redis可用性.
class CacheRedisTest extends base\TestCase
{
    // 断言
    public function testRand()
    {
        $r          = rand(0, 100);
        $key        = 'fengzi';
        $CacheRedis = new base\CacheRedis();

        // 测试写入
        $res1 = $CacheRedis->set($key, $r, 10);
        $this->check($res1 === true);

        // 测试读取
        $res2 = $CacheRedis->get($key);
        $this->check($res2 == $r);

        $res_ttl = $CacheRedis->ttl($key);
        $this->check($res_ttl == 10);
        sleep(2);
        $res_ttl2 = $CacheRedis->ttl($key);
        // var_dump($res_ttl2);
        $this->check($res_ttl2 == 8);

        // 测试累加
        $step = rand(0, 10);
        $res3 = $CacheRedis->setinc($key, $step);
        $this->check($res3 == $r + $step);

        // var_dump($res1,$res2,$step,$res3);exit;

        // 测试删除
        $res4 = $CacheRedis->remove($key);
        $this->check($res4 === 1);

        // 测试删除后再读取.
        $res5 = $CacheRedis->get($key);
        $this->check($res5 === false);
    }
}
