<?php

namespace nuke2015\api\base;

// 基于命名空间的,自动映射技术
// 如:
// $config = ['nuke2015\\api\\org' => 'didiyuesao\\com\\org', 'nuke2015\\api\\base' => 'didiyuesao\\com\\base'];
// base\NamespaceMap::auto_reg($config);
class NamespaceMap
{
    public static function auto_reg($config)
    {
        spl_autoload_register(function ($class_name) use ($config) {
            foreach ($config as $src => $sp) {
                if (stripos($class_name, $sp) !== false) {
                    $other = substr($class_name, strlen($sp) + 1);
                    $src .= "\\" . $other;
                    $sp .= "\\" . $other;
                    class_alias($src, $sp);
                }
            }
        });
    }
}
