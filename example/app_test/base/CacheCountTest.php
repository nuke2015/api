<?php

use nuke2015\api\base;

// 测试redis可用性.
class CacheCountTest extends base\TestCase
{
    // 断言
    public function testRand()
    {
        $r = rand(0, 100);
        $key = 'fengzi';
        $CacheCount = new base\CacheCount();

        // 测试写入
        $res1 = $CacheCount->set($key, $r);
        $this->check($res1 === true);

        // 测试读取
        $res2 = $CacheCount->get($key);
        $this->check($res2 == $r);


        // 测试累加 
        $step = rand(0, 10);
        $res3 = $CacheCount->setinc($key, $step);
        $this->check($res3 == $r + $step);
        
        // var_dump($res1,$res2,$step,$res3);exit;

        // 测试删除
        $res4 = $CacheCount->remove($key);
        $this->check($res4 === 1);

        // 测试删除后再读取.
        $res5 = $CacheCount->get($key);
        $this->check($res5 === 0);
    }
}
