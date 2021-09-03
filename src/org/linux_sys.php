<?php
namespace nuke2015\api\org;

class linux_sys
{
    // vmstat
    public static function vmstat()
    {
        $cmd = 'vmstat';
        return [$cmd, self::shell($cmd)];
    }

    // 进程列表
    public static function ps()
    {
        $cmd = 'ps -ef';
        return [$cmd, self::shell($cmd)];
    }

    // 活动进程
    public static function top()
    {
        $cmd = '/usr/bin/top -b -n 1';
        return [$cmd, self::shell($cmd)];
    }

    // 执行命令
    public static function shell($cmd)
    {
        ob_start();
        passthru($cmd);
        $output = ob_get_clean();
        ob_clean();
        return $output;
    }
}
