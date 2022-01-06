<?php

namespace nuke2015\api\org;

// 压缩打包并下载,包括远程文件
class TZipFile
{
    // 删除目录
    public static function dir_remove($dirPath, $deleteParent = true)
    {
        foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dirPath, \FilesystemIterator::SKIP_DOTS), \RecursiveIteratorIterator::CHILD_FIRST) as $path) {
            $path->isFile() ? unlink($path->getPathname()) : rmdir($path->getPathname());
        }
        if ($deleteParent) {
            rmdir($dirPath);
        }

    }

    // 批量下载到本地
    public static function check_and_get($local_path, $file_list)
    {
        if ($local_path && !is_dir($local_path)) {
            mkdir($local_path);
        }

        // var_dump($local_path);exit;
        if ($file_list && count($file_list)) {
            foreach ($file_list as $key => $value) {
                $value            = picval($value);
                list($err, $data) = myhttp::head($value);
                // var_dump($data['http_code'],$value);
                if ($data['http_code'] == 200) {
                    $file = $local_path . '/' . basename($value);
                    // var_dump($file, $value);exit;
                    Fio::stream_to_stream($value, $file);
                }
            }
        }
        return;
    }

    // 打包压缩目录
    public static function zip_dir($zipFile_to, $dir_scan)
    {
        $zipArchive = new \ZipArchive();
        if (file_exists($zipFile_to)) {
            unlink($zipFile_to);
        }

        if ($zipArchive->open($zipFile_to, (\ZipArchive::CREATE | \ZipArchive::OVERWRITE)) !== true) {
            die("Failed to create archive\n");
        }
        chdir($dir_scan);
        $zipArchive->addGlob("./*");
        if ($zipArchive->status != \ZIPARCHIVE::ER_OK) {
            echo "Failed to write files to zip\n";
        }
        $zipArchive->close();
        return;
    }

    // 浏览
    public static function header_302($filename_local)
    {
        header("Content-type: application/octet-stream");
        header("Content-Transfer-Encoding: binary");
        header("Content-Length: " . filesize($filename_local));
        $basename = basename($filename_local);
        header("Content-Disposition: attachment; filename='{$basename}'");
        header("X-Accel-Redirect: $filename_local");
    }

    // 流水线输出,仅为备注,提醒大家有此方法
    public static function readfile($filename_local)
    {
        return readfile($filename_local);
    }

    // 压缩并下载
    public static function remote_to_local_and_zip($url_list, $filename = '')
    {
        if (!$filename) {
            $filename = time() . uniqid();
        }

        // 本地
        $path         = RUNTIME_PATH . '/temp/' . $filename;
        $zipFile_name = RUNTIME_PATH . "temp/{$filename}.zip";

        // 远程判断并下载
        self::check_and_get($path, $url_list);

        // 目录压缩
        self::zip_dir($zipFile_name, $path);

        /// 目录清除
        self::dir_remove($path);

        // 吐出
        return $zipFile_name;
    }
}
