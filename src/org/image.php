<?php
namespace nuke2015\api\org;

// 测试
// $file = org\image::remote_to_loacl('https://www.demo.com/x.jpg');
// var_dump($file);
// exit;

//系统配置类
class image
{
    // 远程logo本地化
    public static function remote_to_loacl($logo, $dir = 'zhihu_club')
    {
        $file = 'logo_' . md5($logo) . '.png';
        $dir  = RUNTIME_PATH . $dir;
        if (!is_dir($dir)) {
            mkdir($dir);
        }
        // 只下载远程图片
        if (stripos($logo, 'http:') === false && stripos($logo, 'https:') === false) {
            throw new \Exception("logo url fail! #" . $logo, 1);
        }

        $file = $dir . '/' . $file;
        if (!file_exists($file)) {
            $data = file_get_contents($logo);
            file_put_contents($file, $data);
        }
        return $file;
    }

    // 创建示例图片
    public static function image_demo($txt, $x, $y, $show = 1)
    {
        $im  = imagecreatetruecolor($x, $y);
        $red = imagecolorallocate($im, 255, 0, 0);
        imagestring($im, 5, $x / 2.5, $y / 2, $txt, $red);
        if ($show) {
            header("content-type: image/png");
        }
        imagepng($im);
        imagedestroy($im);
    }
}
