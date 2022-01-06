<?php
namespace nuke2015\api\org;

// 年份处理
class ueditor
{
    // 计算年龄
    public static function config()
    {
        $ueditorDir = __DIR__ . "/ueditor/";
        $config     = json_decode(preg_replace("/\/\*[\s\S]+?\*\//", "", file_get_contents($ueditorDir . "config.json")), true);
        return $config;
    }
}
