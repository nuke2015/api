<?php

namespace nuke2015\api\base;

// 文件缓存,底层;
class Tfilecache
{
    public static $dir_store = RUNTIME_PATH . '/tfilecache/';

    // 读取
    public static function get($k)
    {
        $k = self::key($k);
        if (file_exists($k)) {
            $txt         = file_get_contents($k);
            list($t, $v) = unserialize(substr($txt, 7));
            // 过期不取
            if (time() > $t) {
                return;
            } else {
                return $v;
            }
        }
    }

    // 存在性判断,存在不代表有效
    public static function exist($k)
    {
        $k = self::key($k);
        return file_exists($k);
    }

    // 写入
    public static function set($k, $v, $t = 0)
    {
        // 写检查
        if (!is_dir(self::$dir_store)) {
            mkdir(self::$dir_store, 0777);
        }

        if ($t > 0) {
            // 后偏移
            $t = time() + $t;
        }
        $k = self::key($k);
        return file_put_contents($k, '<?php//' . serialize(array($t, $v)));
    }

    // 伪清除
    public static function remove($k)
    {
        self::set($k, null, -1);
    }

    // 统一key
    private static function key($key)
    {
        return self::$dir_store . '/' . md5(strtolower($key)) . '_tfc.php';
    }
}
