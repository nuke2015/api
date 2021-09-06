<?php

namespace nuke2015\api\org;

// 接口版本路由
// 功能:每次取最接近的上一个版本进行服务,若无版本取最低版本.
class Tversion
{

    // 版本树
    public static function version_tree($path)
    {
        $files  = glob($path . "/*Action.php");
        $result = array();
        if ($files && count($files)) {
            foreach ($files as $value) {
                $filename   = str_ireplace('Action.php', '', basename($value));
                $tmp        = explode('_', $filename);
                $methodName = array_shift($tmp);

                if (count($tmp) >= 2) {
                    $version = strval(array_shift($tmp) . '.' . implode('', $tmp));
                } else {
                    $version = 0;
                }
                $result[$methodName][$filename] = floatval($version);
            }

            if ($result && count($result)) {
                foreach ($result as &$value) {
                    arsort($value);
                }
                unset($value);
            }
        }
        return $result;
    }

    // 图片地址补全
    public static function picval($str)
    {
        if (stripos($str, 'http://') !== false || stripos($str, 'https://') !== false) {
            return $str;
        } else {
            if ($str) {
                // 兼容https模式
                // $str = "https://www.example.com/upload/" . $str;
                // http官方外链
                $str = "http://upload.example.com/" . $str;
            } else {
                $str = '';
            }
        }
        return $str;
    }

    // 批量加前缀
    public static function picval_ary(&$arr, $field)
    {
        if ($arr && count($arr)) {
            foreach ($arr as $key => &$value) {
                if ($value[$field]) {
                    $value[$field] = self::picval($value[$field]);
                }
            }
            unset($value);
        }
        return;
    }

    // 数组取列
    public function array_column($data, $tag = 'id')
    {
        $result = array();
        if ($data && count($data)) {
            foreach ($data as $value) {
                if (isset($value[$tag])) {
                    $result[] = $value[$tag];
                }
            }
        }
        return $result;
    }

    //加载器
    public static function loader($path)
    {
        $list = glob($path . '/*.php');
        if ($list && count($list)) {
            foreach ($list as $key => $value) {
                require_once $value;
            }
        }
    }

    public static function route_seo()
    {
        $return = array();
        // 必须query_string
        $str = $_SERVER['QUERY_STRING'];
        // 记入日志
        $_REQUEST['_QUERY_STRING'] = $str;
        if (strpos($str, 's=') !== false) {
            $str = str_replace('//', '/', $str);
            if (strpos($str, '&') > 0) {
                $str = substr($str, 2, strpos($str, '&') - 2);
            } else {
                $str = substr($str, 2);
            }
            if (strripos($str, '.html')) {
                $str = str_ireplace('.html', '', $str);
            }
            if (strripos($str, '.htm')) {
                $str = str_ireplace('.html', '', $str);
            }
            $return = explode('/', $str);
        }
        return input::xss($return);
    }
}
