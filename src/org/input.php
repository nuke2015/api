<?php

namespace nuke2015\api\org;

// $p = org\input::xss($_REQUEST);
// print_r($p);

class input
{

    /**
     * 过滤富文本
     * @param  [type] $string [description]
     * @return [type]         [description]
     */
    public static function safe_replace($string)
    {
        $string = trim($string);
        $string = str_replace(array('\\', ';', '\'', '%2527', '%27', '%20', '"', '<', '>'), array('', '', '', '', '', '', '&quot;', '&lt;', '&gt;'), $string);
        return $string;
    }

    //判断手机号是否正确!
    public static function check_mobile($mobile)
    {
        if (preg_match("/^1[3456789]\d{9}$/", $mobile)) {
            return 1;
        }
        return 0;
    }

    /**
     * 安全过滤
     * @param  [type] $array [description]
     * @return [type]        [description]
     */
    public static function get_safe_replace($array)
    {
        if (!is_array($array)) {
            return self::safe_replace(strip_tags($array));
        } else {
            foreach ($array as $k => $val) {
                $array[$k] = self::get_safe_replace($val);
            }
        }

        return $array;
    }

    // 同名函数
    public static function xss($data)
    {
        return self::get_safe_replace($data);
    }

    //防暴
    public static function get_safe_md5($str)
    {
        $hack = str_split('@;#/.%*~^&=<>-{}(,[]"\')');
        $str  = trim($str);
        return str_ireplace($hack, '', $str);
    }

    // 最简,字母数字-_.,避免sql注入.
    public function isafe($text)
    {
        // $text = 'In the 电影_后天 230809-people died.';
        if (is_string($text)) {
            $txt = preg_replace('/[^a-zA-Z0-9.\-_]/', "", trim($text));
        } else {
            $txt = '';
        }
        return $txt;
    }

    // 取纯数字
    public static function number($str)
    {
        return filter_var($str, FILTER_SANITIZE_NUMBER_INT);
    }

    // 极限成行
    public static function compress_html($string)
    {
        return ltrim(rtrim(preg_replace(array("/> *([^ ]*) *</", "//", "'/\*[^*]*\*/'", "/\r\n/", "/\n/", "/\t/", '/>[ ]+</', '#<!--[^\!\[]*?(?<!\/\/)-->#', '/\s+/'), array(">\\1<", '', '', '', '', '', '><', '', ' '), $string)));
    }

    // 强制入口清洗
    public static function firewall()
    {
        if ($_GET) {
            $_GET = self::xss($_GET);
        }
        if ($_POST) {
            $_POST = self::xss($_POST);
        }
        if ($_REQUEST) {
            $_REQUEST = self::xss($_REQUEST);
        }
        return;
    }

    // 取id列表
    public static function get_ids($str, $tag = ',')
    {
        $rbac_node_id = trim($str);
        $ids_todo     = array();
        if ($rbac_node_id) {
            $tmp = explode($tag, $rbac_node_id);
            if ($tmp && count($tmp)) {
                foreach ($tmp as $key => $value) {
                    $value = intval($value);
                    if ($value > 0) {
                        $ids_todo[] = $value;
                    }
                }
            }
        }
        return $ids_todo;
    }
}
