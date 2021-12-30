<?php

namespace nuke2015\api\org;

class TFileRead
{
    // 做一行
    public static function do_by_line($filename, $func)
    {
        $fp    = new \SplFileObject($filename, 'rb');
        $total = self::get_lines_count($filename);
        for ($i = 0; $i <= $total; ++$i) {
            $fp->seek($i);
            $line = $fp->current();
            if ($line) {
                $func(trim($line));
            }
            $fp->next();
        }
    }

    // 找一行
    public static function seek_line($filename, $line_seek, $func)
    {
        $fp    = new \SplFileObject($filename, 'rb');
        $total = self::get_lines_count($filename);
        for ($i = 0; $i <= $total; ++$i) {
            if ($i == $line_seek) {
                $fp->seek($i);
                $line = $fp->current();
                $line && $func(trim($line));
            } else {
                $fp->next();
            }
        }
    }

    // 按行写入json
    public static function write_line($filename, $data)
    {
        $txt = json_encode($data, JSON_UNESCAPED_UNICODE) . "\r\n";

        return file_put_contents($filename, $txt, FILE_APPEND);
    }

    // 取n行
    public static function get_lines($filename, $startLine = 1, $limitLine = 5, $method = 'rb')
    {
        $content = array();
        $fp      = new \SplFileObject($filename, $method);
        $fp->seek($startLine - 1);
        for ($i = 0; $i < $limitLine; ++$i) {
            $line = $fp->current();
            if ($line) {
                $content[] = trim($line);
            }

            $fp->next();
        }

        return $content;
    }

    // 总行数
    public static function get_lines_count($filename)
    {
        $count = 0;
        $file  = new \SplFileObject($filename, 'r');
        $file->seek(PHP_INT_MAX);
        $count = $file->key();

        return $count;
    }

    // 随机取一批
    public static function get_by_random($file, $size)
    {
        $count = self::get_lines_count($file);
        if ($size > $count) {
            $size = $count;
        }
        // 从第一行开始
        $r = rand(1, $count - $size) + 1;
        // var_dump($r, $size);
        $data = self::get_lines($file, $r, $size);
        return [$data, $r];
    }
}
