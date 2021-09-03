<?php
namespace nuke2015\api\base;

use didiyuesao\com\org;

// 家家月嫂路由中心
class route_ddys
{
    // 单例模式
    public static $application;

    // 一键监听八个项目
    public static function listen()
    {
        // 兼容apache
        if (isset($_SERVER['HTTP_MODULE']) && $_SERVER['HTTP_MODULE']) {
            $host = strtolower(trim($_SERVER['HTTP_MODULE']));
        } else {
            $host = strtolower(trim($_SERVER['HTTP_HOST']));
        }

        $app = self::app_select($host);
        if (!$app) {
            exit('route listen 404!');
        }

        if ($app != 'logcenter') {
            // 是否上报日志中心
            if (!defined('CUBE_LOGSEND')) {
                define('CUBE_LOGSEND', 1);
            }
        }

        define('MODULE_NAME', $app);

        // 增加个小常量
        define('CUBE_MODULE', 'didiyuesao_' . $app);

        // 外部文件拦截
        self::seo_optimize();

        return self::init($app);
    }

    // 启动路由
    public static function init($app)
    {
        // 服务日志
        org\Flogger::json_log('route', ['app' => $app, 'req' => $_REQUEST, 'env' => org\Flogger::env()]);

        // 纯接口
        if (in_array($app, ['api', 'logcenter', 'yapi', 'saler', 'saler_club', 'api_exam', 'rpc', 'saler', 'yuyingshi', 'api_crm', 'api_union', 'paysystem'])) {
            org\header_ddys::json();
            org\header_ddys::gzip();
        }

        // 入口初始化
        if (!self::$application) {
            $ctrl              = "\didiyuesao\app\\$app\controller\Index";
            self::$application = new $ctrl();
        }

        // 统一参数传送
        $param = org\input::xss($_REQUEST);

        return self::$application->index($param);
    }

    // 路由缓存
    public static function app_select($host)
    {
        $key         = 'route_ddys#' . $host;
        $media_cache = media_io::connect('route');
        $cache       = $media_cache->get($key);
        if (!$cache) {
            $app = self::route_app($host);
            if ($app) {
                $media_cache->set($key, $app, 600);
            }
            $cache = $app;
        }

        return $cache;
    }

    // 识别
    private static function route_app($HTTP_MODULE)
    {
        $config = self::config();

        // 细分组
        $tmp = explode('.', $HTTP_MODULE);
        $tag = array_shift($tmp);
        $tag = strtolower($tag);

        // 外网不开此域名,这是定时任务!
        if ($tag == 'crontab' || $tag == 'rpc') {
            if (defined('ENV_ONLINE') && ENV_ONLINE) {
                exit('online,forbiden!');
            }
        }

        foreach ($config as $key => $app) {
            if ($tag == $app) {
                if ($app == 'www') {
                    return 'pc';
                } elseif ($app == 'm') {
                    return 'weixin';
                } else {
                    return $app;
                }
                break;
            }
        }
    }

    // 配置组
    private static function config()
    {
        $str    = 'news,paysystem,crontab,www,saler,rpc,m,yapi,saler_club,api_exam,yuyingshi,api,api_crm,yuandong_crm,yuandong,zhishi,union,m_union,shop_union,logcenter,api_union,ziyuehuanghou';
        $config = explode(',', $str);
        // 先判断长的,否则会不准确!
        arsort($config);

        return $config;
    }

    //seo拦截
    public static function seo_optimize()
    {
        if ($_REQUEST['s'] == '/robots.txt') {
            $file_path = __DIR__ . '/seo/robots_' . ENV_ONLINE . '.txt';
            if (file_exists($file_path)) {
                header('Content-Type: text/plain'); //纯文本格式
                echo file_get_contents($file_path);
                exit;
            }
        }

        return;
    }

}
