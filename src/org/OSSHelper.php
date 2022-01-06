<?php

namespace nuke2015\api\org;

use nuke2015\api\config;
use OSS\Core\OssException;
use OSS\OssClient;

class OSSHelper
{

    private static $ossClient;

    // 连接
    public static function conn()
    {
        if (!self::$ossClient) {
            $config          = config\params::oss_config();
            self::$ossClient = new OssClient($config['accessKeyId'], $config['accessKeySecret'], $config['endpoint']);
        }
        return self::$ossClient;
    }

    // 多仓传送
    public static function uploadFileTo($bucket, $objectName, $filePath, $finishedUnlinkFile = 0)
    {
        $ossClient = self::conn();
        try {
            $objectName = self::fuck_first_slash($objectName);
            $ossClient->uploadFile($bucket, $objectName, $filePath);
            if ($finishedUnlinkFile) {
                //如果上传完成需要删除本地文件
                unlink($filePath);
            }
        } catch (OssException $e) {
            return $e->getMessage();
        }

        // 补齐根目录         
        return '/' . $objectName;
    }

    // 去掉首杠
    public static function fuck_first_slash($dir)
    {
        while (stripos($dir, '/') === 0) {
            $dir = substr($dir, 1);
        }
        return $dir;
    }

    // 去掉双杠
    public static function fuck_double_slash($dir)
    {
        while (stripos($dir, '//')) {
            $dir = str_ireplace('//', '/', $dir);
        }
        return $dir;
    }

    // 固定传送
    public static function uploadFile($objectName, $filePath, $finishedUnlinkFile = 0)
    {
        $config = config\params::oss_config();
        $bucket = $config['bucket'];
        return self::uploadFileTo($bucket, $objectName, $filePath, $finishedUnlinkFile);
    }
}
