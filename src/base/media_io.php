<?php
namespace nuke2015\api\base;

// 全站读写控制
class media_io
{
    // 数据总线
    public static function connect($dir)
    {
        // 阿里云runtime
        $runtime_oss = \think\Config::get('runtime_oss');
        if (defined('ENV_ONLINE') && ENV_ONLINE > 0 && $runtime_oss) {
            $media = new OssFileCache(ENV_ONLINE . '_' . $dir);
        } else {
            $media = CacheDir::connect($dir, $expire);
        }
        return $media;
    }
}
