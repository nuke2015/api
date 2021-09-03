<?php

namespace nuke2015\api\org;

class Time
{
    // 月时间
    public function month($timestamp)
    {
        $time_start = strtotime(date('Y-m', $timestamp));
        $time_end   = mktime(23, 59, 59, date('m', $time_start) + 1, 0, date('Y', $time_start));

        return [$time_start, $time_end];
    }

    /**
     * 友好的时间显示.
     *
     * @param int    $sTime 待显示的时间
     * @param string $type  类型. normal | mohu | full | ymd | other
     * @param string $alt   已失效
     *
     * @return string
     */
    public function friendlyDate($sTime, $type = 'normal', $alt = 'false')
    {
        if (!$sTime) {
            return '';
        }

        //sTime=源时间，cTime=当前时间，dTime=时间差
        $cTime = time();
        $dTime = $cTime - $sTime;
        $dDay  = intval(date('z', $cTime)) - intval(date('z', $sTime));
        //$dDay     =   intval($dTime/3600/24);
        $dYear = intval(date('Y', $cTime)) - intval(date('Y', $sTime));
        //normal：n秒前，n分钟前，n小时前，日期
        if ($type == 'normal') {
            if ($dTime < 60) {
                if ($dTime < 10) {
                    return '刚刚';
                } else {
                    return intval(floor($dTime / 10) * 10) . '秒前';
                }
            } elseif ($dTime < 3600) {
                return intval($dTime / 60) . '分钟前';
                //今天的数据.年份相同.日期相同.
            } elseif ($dYear == 0 && $dDay == 0) {
                //return intval($dTime/3600).L('_HOURS_AGO_');
                return '今天' . date('H:i', $sTime);
            } elseif ($dYear == 0) {
                return date('m月d日 H:i', $sTime);
            } else {
                return date('Y-m-d H:i', $sTime);
            }
        } elseif ($type == 'mohu') {
            if ($dTime < 60) {
                return $dTime . '秒前';
            } elseif ($dTime < 3600) {
                return intval($dTime / 60) . '分钟前';
            } elseif ($dTime >= 3600 && $dDay == 0) {
                return intval($dTime / 3600) . '小时前';
            } elseif ($dDay > 0 && $dDay <= 7) {
                return intval($dDay) . '天前';
            } elseif ($dDay > 7 && $dDay <= 30) {
                return intval($dDay / 7) . '周前';
            } elseif ($dDay > 30) {
                return intval($dDay / 30) . '个月前';
            }
            //full: Y-m-d , H:i:s
        } elseif ($type == 'full') {
            return date('Y-m-d , H:i:s', $sTime);
        } elseif ($type == 'ymd') {
            return date('Y-m-d', $sTime);
        } else {
            if ($dTime < 60) {
                return $dTime . '秒前';
            } elseif ($dTime < 3600) {
                return intval($dTime / 60) . '分钟前';
            } elseif ($dTime >= 3600 && $dDay == 0) {
                return intval($dTime / 3600) . '小时前';
            } elseif ($dYear == 0) {
                return date('Y-m-d H:i:s', $sTime);
            } else {
                return date('Y-m-d H:i:s', $sTime);
            }
        }
    }

    /*
     * 生成指定日期区间的日期列表
     */
    public function generate_date($start_time, $end_time, $format = 'Y-m-d')
    {
        $j     = date('d', $end_time); //获取当前月份天数
        $array = array();
        for ($i = $start_time; $i <= $end_time; $i += 86400) {
            $array[] = date($format, $i);
        }

        return $array;
    }

    /*
     * 生成指定日期区间的月分列表,时间戳
     */
    public function generate_month($start_time, $format = 'Y-m')
    {
        $array = array();
        for ($i = 0; $i < 12; ++$i) {
            $array[] = date($format, strtotime("+$i month", $start_time));
        }

        return $array;
    }

    // 时间转饼图
    public function time_to_pie($time_min, $time_max, $time_start, $time_end, $tag_id = 1)
    {
        $start  = ($time_min <= $time_start) ? $time_start : $time_min;
        $end    = ($time_max >= $time_end) ? $time_end : $time_max;
        $result = [];
        for ($i = $start; $i < $end; $i += 86400) {
            $result[date('Ymd', $i)] = $tag_id;
        }
        return $result;
    }
}
