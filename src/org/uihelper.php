<?php

namespace nuke2015\api\org;

// 界面辅助翻译
class uihelper
{
    // 月嫂等级判断
    public function level_yuesao($id)
    {
        $config = config::config_yuesao_level();
        foreach ($config as $key => $value) {
            if ($key == $id) {
                return $value;
            }
        }
    }
    // 育婴师等级判断
    public function level_yuying($id)
    {
        $config = config::config_skiller_yuying_level();
        foreach ($config as $key => $value) {
            if ($key == $id) {
                return $value;
            }
        }
    }

    /**
     * 字符串截取，支持中文和其他编码
     * @static
     * @access public
     * @param string $str 需要转换的字符串
     * @param string $start 开始位置
     * @param string $length 截取长度
     * @param string $charset 编码格式
     * @param string $suffix 截断显示字符
     * @return string
     */
    public function msubstr($str, $start = 0, $length, $charset = "utf-8", $suffix = true)
    {
        return Tstring::msubstr($str, $start = 0, $length, $charset = "utf-8", $suffix = true);
    }

    // 手动封装u函数，简单处理
    public function U($url, $domain)
    {
        // 多域名支持
        $host_act = $this->host_act();
        return ($this->isSsl() ? 'https://' : 'http://') . $host_act . $url;
    }

    // 当前域名
    protected function host_act()
    {
        if ($_SERVER['HTTP_ACT']) {
            $return = trim($_SERVER['HTTP_ACT']);
        } else {
            $return = "www.example.com";
        }
        return $return;
    }

    /**
     * 当前是否ssl
     * @access public
     * @return bool
     */
    public function isSsl()
    {
        $server = $_SERVER;
        if (isset($server['HTTPS']) && ('1' == $server['HTTPS'] || 'on' == strtolower($server['HTTPS']))) {
            return true;
        } elseif (isset($server['REQUEST_SCHEME']) && 'https' == $server['REQUEST_SCHEME']) {
            return true;
        } elseif (isset($server['SERVER_PORT']) && ('443' == $server['SERVER_PORT'])) {
            return true;
        } elseif (isset($server['HTTP_X_FORWARDED_PROTO']) && 'https' == $server['HTTP_X_FORWARDED_PROTO']) {
            return true;
        }
        return false;
    }

    public function analysisKeyword($title = "", $content = "")
    {
        if (empty($title)) {
            return array();
        }
        if (empty($content)) {
            return array();
        }
        $data = $title . $title . $title . $content; // 为了增加title的权重，这里连接3次
        //这个地方写上phpanalysis对应放置路径
        $phpanalysis            = new phpanalysis\PhpAnalysis('utf-8', 'utf-8', false);
        $phpanalysis::$loadInit = false;
        $phpanalysis->LoadDict();
        $phpanalysis->SetSource($data);
        $phpanalysis->StartAnalysis(true);
        $tags    = $phpanalysis->GetFinallyKeywords(5); // 获取文章中的五个关键字
        $tagsArr = explode(",", $tags);
        return $tagsArr; //返回关键字数组
    }
}
