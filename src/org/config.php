<?php

namespace nuke2015\api\org;

//系统配置类
class config
{
    public static $domain = [
        'baike'  => 'baike%s.jjys168.com',
        'jjys'   => 'www%s.jjys168.com',
        'school' => 'school%s.jjys168.com',
    ];

    // 落地开放城市配置
    public static function config_city_open()
    {
        $config   = array();

        // 修复.t与dev跳转不对的问题.
        if (ENV_ONLINE < 2) {
            foreach ($config as $key => &$value) {
                if (ENV_ONLINE == 1) {
                    $value['host'] = 't.jjys168.com';
                } else {
                    if (stripos($_SERVER['HTTP_HOST'], '.loc.') !== false) {
                        $value['host'] = 'loc.qinqinyuesao.com';
                    } else {
                        $value['host'] = 'dev.qinqinyuesao.com';
                    }
                }
            }
        }

        return $config;
    }

    // 翻译城市码
    public static function cityserver_trans($citycode)
    {
        $config = self::config_city_open();
        $return = 'sz';
        foreach ($config as $key => $value) {
            if ($value['code'] == $citycode) {
                $return = $value['tag'];
            }
        }

        return $return;
    }

    // 检测城市码是否合格
    public static function citycode_check($code)
    {
        $config = self::config_city_open();
        $codes  = array_column($config, 'code');
        if (in_array($code, $codes)) {
            return 1;
        }

        return 0;
    }

    // 网站统一后缀
    public static function host_title($host_act)
    {
        $config                         = array();
        $config['bestphp.net']          = '家家月子学院';
        // 默认
        $result = '深圳家家母婴';
        return $result;
    }

    // 相应城市识别
    public static function city_identify($code)
    {
        $config = self::config_city_open();
        if ($config && count($config)) {
            foreach ($config as $key => $value) {
                if ($value['code'] == $code) {
                    return $value['city'];
                }
            }
        }
    }

    // 微信公众号网关
    public static function host_weixin($is_https = 0)
    {
        if ($is_https) {
            // 强制https
            $scheme = 'https';
        } else {
            // 自动兼容https
            $scheme = strip_tags(trim($_SERVER['REQUEST_SCHEME']));
        }
        if (defined('MODULE_NAME') && MODULE_NAME == 'erp') {
            // 智护项目
            if (ENV_ONLINE == 2) {
                $host = $scheme . '://www.zhihuguanjia.com/weixin/?';
            } elseif (ENV_ONLINE == 1) {
                $host = $scheme . '://erp.t.jjys168.com/weixin/?';
            } else {
                $host = $scheme . '://erp.loc.qinqinyuesao.com/weixin/?';
            }

            return $host;
        } else {
            // 家家项目
            $tmp = explode('.', HOST_ACT);
            if ($tmp && count($tmp)) {
                $tmp[0] = 'm';
            }
            $host = $scheme . '://' . implode('.', $tmp);
        }

        return $host;
    }

    // 商城网关
    public static function host_shop()
    {
        if (defined('ENV_ONLINE') && ENV_ONLINE == 2) {
            $host = 'http://shop.jjys168.com';
        } elseif (defined('ENV_ONLINE') && ENV_ONLINE == 1) {
            $host = 'http://shop.t.jjys168.com';
        } else {
            if (stripos($_SERVER['HTTP_HOST'], '.loc.') !== false) {
                $host = 'http://shop.loc.jjys168.com/';
            } else {
                $host = 'http://shop.dev.jjys168.com/';
            }
        }

        return $host;
    }

    // pc 站点网关
    public static function host_domain()
    {
        if (defined('ENV_ONLINE') && ENV_ONLINE == 2) {
            $host_tag = '';
        } elseif (defined('ENV_ONLINE') && ENV_ONLINE == 1) {
            $host_tag = '.t';
        } else {
            $host_tag = '.loc';
        }
        $domain     = self::$domain;
        $domian_arr = array();
        foreach ($domain as $key => $value) {
            $host             = sprintf($value, $host_tag);
            $domian_arr[$key] = $host;
        }

        return $domian_arr;
    }

    // 育婴师佣金+保险配置
    public static function skiller_yuying($citycode)
    {
        $config = array();

        // 统一按深圳算法,平台服务费+保险
        $config['103212'] = array(
            '2' => array('charge_fee' => 5600, 'charge_insurance' => 200, 'month' => 5600),
            '3' => array('charge_fee' => 6600, 'charge_insurance' => 200, 'month' => 6600),
            '4' => array('charge_fee' => 7600, 'charge_insurance' => 200, 'month' => 7600),
            '5' => array('charge_fee' => 8600, 'charge_insurance' => 200, 'month' => 8600),
            '6' => array('charge_fee' => 9600, 'charge_insurance' => 200, 'month' => 9600),
            '7' => array('charge_fee' => 12600, 'charge_insurance' => 200, 'month' => 12600),
        );

        return $config['103212'];
    }

    // 育婴师佣金识别
    public static function skiller_yuying_get($level_id = 0, $citycode = 103212)
    {
        $citycode = 103212; // 锁定深圳算法
        $config   = self::skiller_yuying($citycode);
        if ($level_id) {
            return $config[$level_id];
        } else {
            return array();
        }
    }

    // 域名检查
    public static function host_check($url_to)
    {
        // 合法域名
        $host_weixin = self::host_weixin();

        // 域名拆分
        $arr        = parse_url($url_to);
        $arr_weixin = parse_url($host_weixin);
        $status     = false;
        
        // 家家矩阵
        if (stripos($arr['host'], 'jjys') !== false) {
            $status = true;
        }
        // 智护
        if (stripos($arr['host'], 'zhihuguanjia.com') !== false) {
            $status = true;
        }
        // 联盟
        if (stripos($arr['host'], 'mccia.com.cn') !== false) {
            $status = true;
        }

        return $status;
    }

    // 月嫂等级配置
    public static function config_yuesao_level()
    {
        $config = array(1 => '一星月嫂', 2 => '二星月嫂', 3 => '三星月嫂', 4 => '四星月嫂', 5 => '五星月嫂', 6 => '六星月嫂', 7 => '金牌月嫂', 8 => '月子管家', 11 => '黄金月子管家', 12 => '铂金月子管家', 13 => '钻石月子管家');

        return $config;
    }

    // 育婴师等级配置
    public static function config_skiller_yuying_level()
    {
        $config = array(2 => '二星育婴师', 3 => '三星育婴师', 4 => '四星育婴师', 5 => '五星育婴师', 6 => '六星育婴师', 7 => '金牌育婴师', 8 => '钻石育婴师');

        return $config;
    }

    // 月嫂证书配置
    public static function config_yuesao_cert()
    {
        $config = array(1 => '身份证', 2 => '月嫂证', 3 => '健康证', 4 => '母婴护理证', 5 => '催乳师证', 6 => '营养师证', 7 => '护士证', 8 => '早教证', 9 => '健康管理师证', 10 => '幼师证', 11 => '育婴师证', 12 => '香港探亲证', 13 => '港澳通行证', 14 => '产后修复证', 15 => '小儿推拿证', 16 => '养老护理员');

        return $config;
    }

    // 订单处理流程
    public static function config_order_process()
    {
        $config = array('预约下单', '支付定金', '支付全款', '上门服务', '更换月嫂', '完成服务', '评价完成', '结算服务费', '结算服务品质奖', '取消订单', '分期支付');

        return $config;
    }

    // 支付渠道
    public static function channel_list()
    {
        $config     = array();
        $config[1]  = array('type' => 'alipay', 'title' => '支付宝手机支付');
        $config[2]  = array('type' => 'alipay_wap', 'title' => '支付宝手机网页支付');
        $config[3]  = array('type' => 'alipay_qr', 'title' => '支付宝扫码支付');
        $config[4]  = array('type' => 'offline_cash', 'title' => '线下支付');
        $config[5]  = array('type' => 'wx', 'title' => '微信支付');
        $config[6]  = array('type' => 'wx_pub', 'title' => '微信公众账号支付');
        $config[7]  = array('type' => 'wx_pub_qr', 'title' => '微信公众账号扫码支付');
        $config[8]  = array('type' => 'alipay_pc_direct', 'title' => '支付宝 PC 网页支付');
        $config[9]  = array('type' => 'transfer_pay', 'title' => '转账支付');
        $config[10] = array('type' => 'offline_card', 'title' => '微信小程序');
        $config[11] = array('type' => 'wx_lite', 'title' => '微信小程序');
        $config[12] = array('type' => 'offline_qr', 'title' => '线下扫码支付');
        $config[13] = array('type' => 'wx_wap', 'title' => '微信H5支付');
        $config[14] = array('type' => 'cmb_wallet', 'title' => '招行一网通');

        return $config;
    }

    // 支付渠道翻译
    public static function channel_list_to_id($channel)
    {
        $config = self::channel_list();
        $return = 0;
        if ($config && count($config)) {
            foreach ($config as $key => $value) {
                if ($value['type'] == $channel) {
                    $return = $key;
                }
            }
        }

        return $return;
    }

    // 圈子管理后台tab切换
    public static function club_forum()
    {
        $config    = array();
        $config[1] = ['id' => 1, 'title' => '月嫂圈'];
        $config[2] = ['id' => 2, 'title' => '联盟圈'];

        return $config;
    }

    // 爱学习管理后台tab切换
    public static function club_study()
    {
        $config    = array();
        $config[1] = ['id' => 1, 'title' => '月嫂爱学习'];

        return $config;
    }
}
