<?php
namespace nuke2015\api\org;

// header补充
class header_ddys
{
    // 纯网页
    public static function html()
    {
        @header('Content-type:text/html;charset=utf-8');
        @header('Server:ijiazhen.com');
        @header('X-Powered-By:ijiazhen.com');
        return;
    }

    //跨域ajax调用
    public static function json()
    {
        @header('Access-Control-Allow-Origin: *');
        // 禁用cookie,分布式架构
        @header('Access-Control-Allow-Credentials:true');
        @header('Content-type:application/json;charset=utf-8');
        // 授权
        @header('Server:ijiazhen.com');
        @header('X-Powered-By:ijiazhen.com');
        // ajax
        @header('Access-Control-Allow-Methods:OPTIONS, GET, POST'); // 允许option，get，post请求
        @header('Access-Control-Allow-Headers:x-requested-with'); // 允许x-requested-with请求头
        @header('Access-Control-Max-Age:86400'); // 允许访问的有效期
        return;
    }

    // gzip
    public static function gzip()
    {
        // 末端gzip压缩,客户端+服务端都支持才压缩
        if (extension_loaded("zlib")) {
            ini_set('zlib.output_compression', 'On');
            ini_set('zlib.output_compression_level', '4');
            @header('Content-Encoding:gzip');
        }
        return;
    }
}
