<?php

namespace nuke2015\api\org;

// 后台管理员登陆助手
class crm_helper
{

    static $config = [
        'url_api'  => 'https://api.ijiazhen.com/',
        'url_jump' => '#',
        'title' => '管理后台入口',
        'image' => '/static/images/login-bg.jpg',
    ];

    public static function check_login($config)
    {
        // 首次登陆
        if (isset($_REQUEST['admin_id']) && $_REQUEST['token']) {
            $admin_id = intval($_REQUEST['admin_id']);
            $token    = trim($_REQUEST['token']);
            // 校验并登陆
            $req = ['methodName' => 'AdminInfo', 'admin_id' => $admin_id, 'token' => $token];
            $txt = myhttp::curl($config['url_api'], 'get', $req);
            if ($txt) {
                $info = json_decode($txt, 1);
                if ($info && count($info)) {
                    return [intval($info['code']), $info['data']];
                } else {
                    return [3, '登陆网关数据异常!'];
                }
            } else {
                return [2, '网络连接不上!'];
            }
        } else {
            return [1, '请用微信扫码登陆!'];
        }
    }

    // 显示微信登陆码
    public static function show_login_code($config)
    {
        // 兼容旧版本
        $config = array_merge(self::$config, $config);

        echo <<<doc
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>{$config['title']}</title>
        <script src="https://apps.bdimg.com/libs/jquery/2.1.1/jquery.min.js"></script>
    </head>
    <body
        style="width: 100%; background-color: rgb(140, 197, 255); background-image: url({$config['image']}); background-repeat: no-repeat; background-size: cover;">
        <center>
            <div class="code">
                <h1>{$config['title']}</h1>
                <h4>请用微信扫码登陆!</h4>
                <img src="#" id="qrcode" alt="" style="width:215px;height:215px;">
            </div>
        </center>
        <script language="javascript">
            api = {
                seed: '',
                host: "{$config['url_api']}",
                randomWord: function(randomFlag, min, max) {
                    var str = "",
                        range = min,
                        arr = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h',
                            'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z',
                            'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R',
                            'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'
                        ];
                    // 随机产生
                    if (randomFlag) {
                        range = Math.round(Math.random() * (max - min)) + min;
                    }
                    for (var i = 0; i < range; i++) {
                        pos = Math.round(Math.random() * (arr.length - 1));
                        str += arr[pos];
                    }
                    return str;
                },
                SeedCode: function() {
                    api.seed = api.randomWord(0, 32);
                    var req = {
                        "methodName": "SeedCode",
                        "seed": api.seed,
                    }
                    api.ajax(req, function(res) {
                        api.checkError(res);
                        var qrcode = res.data.qrcode;
                        $("#qrcode").attr("src", qrcode);
                    })
                },
                SeedCheck: function() {
                    var req = {
                        "methodName": "SeedCheck",
                        "seed": api.seed
                    }
                    api.ajax(req, function(res) {
                        console.log(res);
                        if (res.code > 0) {
                            alert(res.msg);
                        } else {
                            if (res.code == 0 && res.data.admin_id) {
                                location.href = "{$config['url_jump']}&admin_id=" + res.data.admin_id +
                                    "&token=" + res.data.token;
                            }
                        }
                    });
                },
                ajax: function(req, res) {
                    $.post(api.host, req, res);
                },
                checkError: function(res) {
                    console.info(res.data);
                }
            }
            var init = (function() {
                api.SeedCode();
                // 每秒一次
                setInterval(api.SeedCheck, 1000);
                //30秒后不查
                clearInterval(api.SeedCheck, 30000);
            })()
        </script>

    </body>
</html>

doc;
    }
}
