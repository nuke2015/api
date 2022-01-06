<?php
namespace nuke2015\api\base;

use nuke2015\api\org\aliyun;

// 多机文件读写同步
class OssFileCache
{
    public $prefix = '';

    // 用私仓,因为公仓可访问
    public $bucket = 'runtime-ddys';

    public function __construct($dir = 'cache')
    {
        $this->prefix = 'PHP_env_' . ENV_ONLINE . '/' . $dir;
    }

    public function get($key)
    {
        $obj    = $this->modify($key);
        $res    = aliyun\myoss::get_object($this->bucket, $obj, array());
        $status = aliyun\myoss::isOK($res);
        $result = array();
        if ($status) {
            list($t, $data) = unserialize($res->body);
            if (time() < $t) {
                $result = $data;
            } else {
                $this->remove($key);
            }
        }
        return $result;
    }

    public function is_object_exist($key)
    {
        $obj = $this->modify($key);
        $res = aliyun\myoss::is_object_exist($this->bucket, $obj);
        return aliyun\myoss::isOK($res);
    }

    public function set($key, $data, $expire = 86400)
    {
        $obj     = $this->modify($key);
        $content = serialize([time() + $expire, $data]);
        $res     = aliyun\myoss::upload_file_by_content($content, $obj, $this->bucket, $content_type = 'txt');
        return aliyun\myoss::isOK($res);
    }

    // 直传
    public function fput($file, $to_obj)
    {
        $res = aliyun\myoss::upload_file_by_file($this->bucket, $file, $this->prefix . '/' . $to_obj);
        return aliyun\myoss::isOK($res);
    }

    // 直取
    public function fget($obj, $to_file)
    {
        $res = aliyun\myoss::get_object($this->bucket, $this->prefix . '/' . $obj, array());
        $do  = aliyun\myoss::isOK($res);
        if ($do) {
            return file_put_contents($to_file, $res->body);
        } else {
            throw new \Exception("oss not found:" . $obj, 1);
        }
    }

    // 删除
    public function remove($key)
    {
        $obj = $this->modify($key);
        $res = aliyun\myoss::delete_object($obj, $this->bucket);
        return aliyun\myoss::isOK($res);
    }

    // 清空
    public function clear($next = '')
    {
        $i                        = 0;
        list($data, $more, $next) = $this->list_object($this->prefix, $next, 20);
        if ($data && count($data)) {
            foreach ($data as $key => $value) {
                $do = $this->remove(basename($value));
                if ($do) {
                    $i++;
                }
            }
        }
        if ($more) {
            $i += $this->clear($next);
        }
        return $i;
    }

    public function mkdir($dirname)
    {
        $res = aliyun\myoss::mkdir($this->prefix . '/' . $dirname, $this->bucket);
        return aliyun\myoss::isOK($res);
    }

    // 有限列表
    public function list_object($dirname = '', $next = '', $size = 20)
    {
        $dir    = $this->prefix . '/' . $dirname;
        $res    = aliyun\myoss::list_object($dir, $next, $size, $this->bucket);
        $result = array();
        // 这是阿里云的bug,字符串
        $more = ($res['IsTruncated'] === 'true') ? true : false;
        // 还有吗?
        if ($res['Contents'] && count($res['Contents'])) {
            if (!$res['Contents']['Key']) {
                foreach ($res['Contents'] as $key => $value) {
                    if ($value['Size'] > 0) {
                        $result[] = $value['Key'];
                    }
                }
            } else {
                // 这是阿里云的bug
                $result[] = $res['Contents']['Key'];
            }
        }
        return [$result, $more, $res['NextMarker']];
    }

    // 慎用
    public function list_all($dir, $next = '', $max = 20)
    {
        $result                   = array();
        list($data, $more, $next) = $this->list_object($dir, $next, $max);
        if ($data && count($data)) {
            $result = array_merge($result, $data);
            // 提前中断,分量分批
            if (count($result) >= $max) {
                return [$result, $next];
            }
        }
        // 递归
        if ($more) {
            list($data2, $next) = self::list_all($dir, $next, $max);
            $result             = array_merge($result, $data2);
            // 提前中断,分量分批
            if (count($result) >= $max) {
                return [$result, $next];
            }
        }
        // 必须要有这个next才能递归
        return [$result, $next];
    }

    // 下载
    public function download($dir)
    {
        $i      = 0;
        $result = array();

        // 递归
        $more = 1;
        $next = '';
        while ($more) {
            list($data, $more, $next) = $this->list_object('', $next, 20);
            if ($data && count($data)) {
                foreach ($data as $key => $value) {
                    $res = aliyun\myoss::get_object($this->bucket, $value);
                    if (aliyun\myoss::isOK($res)) {
                        $i++;
                        $file_to = $dir . '/' . basename($value);
                        if (!file_exists($file_to)) {
                            self::write($file_to, $res->body);
                        }
                    }
                }
            }
        }
        return $i;
    }

    // 本地清空
    public static function clean_local($dir)
    {
        $i    = 0;
        $list = glob($dir . '*');
        if ($list && count($list)) {
            foreach ($list as $key => $value) {
                if (file_exists($value)) {
                    $i++;
                    unlink($value);
                }
            }
        }
        return $i;
    }

    // 本地写操作
    private static function write($filename, $data)
    {
        return file_put_contents($filename, $data);
    }

    // 同步修饰
    private function modify($key)
    {
        $module_name = defined('MODULE_NAME') ? MODULE_NAME : 'default';
        return $this->prefix . '/' . $module_name . '_' . md5($key);
    }
}
