<?php
namespace nuke2015\api\org;

// 年份处理
class year
{
    // 计算年龄
    public static function age($str)
    {
        $timestamp = strtotime($str);
        $result    = 0;
        if ($timestamp) {
            $result = date('Y') - date('Y', $timestamp);
        }
        // 解决0岁问题
        return $result + 1;
    }
}
