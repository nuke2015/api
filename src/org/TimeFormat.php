<?php
namespace nuke2015\api\org;

// 时间格式化
class TimeFormat
{

    /**
     * 时间格式化
     * @param datetime [varname] [标准时间格式]
     * @return [type] [字符串,类似于 1小时前]
     */
    public static function beautify($datetime)
    {
        $result    = '';
        $timestamp = strtotime($datetime);
        if ($timestamp >= 0) {
            $result = self::beautify_time($timestamp);
        } else {
            $timestamp = $datetime;
        }
        return $result;
    }

    /**
     * 时间格式化
     * @param datetime [varname] [标准时间格式]
     * @return [type] [字符串,类似于 1小时前]
     */
    public static function beautify_time($timestamp)
    {
        $result   = '';
        $difftime = time() - $timestamp;
        if ($difftime <= 600) {
            $result = "刚刚";
        } elseif ($difftime > 600 && $difftime <= 3600) {
            $minute = intval($difftime / 60);
            $result = "{$minute}分钟前";
        } elseif ($difftime > 3600 && $difftime <= 86400) {
            $hour   = intval($difftime / 3600);
            $result = "{$hour}小时前";
        } elseif ($difftime > 86400 && $difftime <= 86400 * 7) {
            $day    = intval($difftime / 86400);
            $result = "{$day}天前";
        } elseif ($difftime > 86400 * 7 && $difftime <= 31536000) {
            $result = date("m月d日", $timestamp);
        } else {
            $result = ceil($difftime / 31536000) . "年前";
        }
        return $result;
    }
}
