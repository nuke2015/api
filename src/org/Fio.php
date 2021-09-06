<?php

namespace nuke2015\api\org;

use Exception;

// $url  = 'http://video.example.com/gaogainaiyansudabingganA.mp4';
// $file = "test.mp4";
// Fio::stream_to_stream($url, $file);

// echo 'done!';

// 史上最刁的字节流
class Fio
{
    // 外封装
    public static function stream_to_stream($url, $file)
    {
        self::pipe_streams(fopen($url, 'r'), fopen($file, 'w'));
    }

    // io流
    public static function pipe_streams($in, $out)
    {
        $size = 0;
        while (!feof($in)) {
            $size += fwrite($out, fread($in, 8192));
        }

        return $size;
    }

    // 下载文件.
    public static function download($path, $filename = '')
    {
        header('Content-type:text/html;charset=utf-8');
        //用以解决中文不能显示出来的问题
        // $file_name = iconv('utf-8', 'gb2312', $filename);
        $pathinfo = pathinfo($path);
        $file_name = empty($filename) ? $pathinfo['basename'] : $filename;

        //判断给定的文件存在与否
        if (!file_exists($path)) {
            throw new \Exception('文件不存在!');
        }
        $fp = fopen($path, 'r');
        $file_size = filesize($path);
        header('Content-type: application/octet-stream');
        header('Accept-Ranges: bytes');
        header('Accept-Length:'.$file_size);
        header('Content-Disposition: attachment; filename='.$file_name);
        // header("X-Sendfile: $path");
        readfile($path);
    }
}
