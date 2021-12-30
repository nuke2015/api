<?php

namespace nuke2015\api\org;

class Tupload
{
    // 公共上传
    public static function upload_oss($dir = 'ijiazhen')
    {
        list($err, $data) = self::upload();
        if (!$err) {
            if ($data && count($data)) {
                $file = array_shift($data);
            } else {
                return array(2, '没有文件!');
            }

            // 文件扩展名
            $extension = '';
            $ext       = explode('.', $file['name']);
            if ($ext && count($ext)) {
                $extension = array_pop($ext);
            }

            // 图片压缩时有img.blob.jpg
            if ($extension == 'blob') {
                $extension = 'jpg';
            }

            // 补充文件扩展名
            $obj = $dir . '/' . date('Ymd') . '/' . time() . uniqid() . '.' . $extension;
            if (file_exists($file['tmp_name'])) {
                // 阿里云上传
                $url_obj = OSSHelper::uploadFile($obj, $file['tmp_name']);
                return array(0, $url_obj);
            } else {
                return array(1, '请选择要上传的文件!');
            }
        } else {
            return array($err, $data);
        }
    }

    // 上传到临时目录
    public static function upload($types = 'png,jpg,jpeg,xlsx,xls,csv')
    {
        return self::check($_FILES, $types);
    }

    // 校验
    public static function check($list, $types)
    {
        $result = array();
        if ($list && count($list)) {
            foreach ($list as $key => $file) {
                $fileattach_size_limit = \tp5x\Config::get('fileattach_size_limit');
                // 限制10M
                if ($file && $fileattach_size_limit > 0 && $file['size'] > $fileattach_size_limit) {
                    return [1, 'filesize limit'];
                }
                $result[] = $file;
            }
        }
        return [0, $result];
    }
}
