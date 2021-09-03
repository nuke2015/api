<?php

namespace nuke2015\api\org;

// 算命专用
// 星座名称,出生日期（公历）,构成元素,颜色,英文名称
// 白羊座,03月21日─04月20日,火,红,Aries
// 金牛座,04月21日─05月20日,土,绿,Taurus
// 双子座,05月21日─06月21日,空气,黄,Gemini
// 巨蟹座,06月22日─07月22日,水,白,Cancer
// 狮子座,07月23日─08月22日,火,橙,Leo
// 处女座,08月23日─09月22日,土,灰,Virgo
// 天秤座,09月23日─10月22日,空气,淡红,Libra
// 天蝎座,10月23日─11月21日,水,深红,Scorpio
// 射手座,11月22日─12月21日,火,紫红,Sagittarius
// 摩羯座,12月22日─01月19日,土,黑,Capricorn
// 水瓶座,01月20日─02月18日,空气,黑,Aquarius
// 双鱼座,02月19日─03月20日,水,蓝,Pisces
class fate
{

    // 时间戳算命
    public function birthdayToExtendByTimestamp($timestamp)
    {
        $mydate = date('Y-m-d', $timestamp);
        return self::birthdayToExtend($mydate);
    }

    /**
     * 根据出生日期计算年龄、生肖、星座
     * @param string $mydate = "2018-10-23" 日期
     * @param string $symbol 符号
     * @return $array
     * */
    public static function birthdayToExtend($mydate, $symbol = '-')
    {

        //计算年龄
        $birth              = $mydate;
        list($by, $bm, $bd) = explode($symbol, $birth);
        $cm                 = date('n');
        $cd                 = date('j');
        $age                = date('Y') - $by - 1;
        if ($cm > $bm || $cm == $bm && $cd > $bd) {
            $age++;
        }

        $array['age'] = $age;

        //计算生肖
        $animals = array(
            '鼠', '牛', '虎', '兔', '龙', '蛇',
            '马', '羊', '猴', '鸡', '狗', '猪',
        );
        $key              = ($by - 1900) % 12;
        $array['animals'] = $animals[$key];

        $idcard_str = $bm . $bd;
        if ('0120' <= $idcard_str && $idcard_str <= '0218') {
            $constellation = '水瓶座';
        } elseif ('0219' <= $idcard_str && $idcard_str <= '0320') {
            $constellation = '双鱼座';
        } elseif ('0321' <= $idcard_str && $idcard_str <= '0419') {
            $constellation = '白羊座';
        } elseif ('0420' <= $idcard_str && $idcard_str <= '0520') {
            $constellation = '金牛座';
        } elseif ('0521' <= $idcard_str && $idcard_str <= '0621') {
            $constellation = '双子座';
        } elseif ('0622' <= $idcard_str && $idcard_str <= '0722') {
            $constellation = '巨蟹座';
        } elseif ('0723' <= $idcard_str && $idcard_str <= '0822') {
            $constellation = '狮子座';
        } elseif ('0823' <= $idcard_str && $idcard_str <= '0922') {
            $constellation = '处女座';
        } elseif ('0923' <= $idcard_str && $idcard_str <= '1023') {
            $constellation = '天秤座';
        } elseif ('1024' <= $idcard_str && $idcard_str <= '1122') {
            $constellation = '天蝎座';
        } elseif ('1123' <= $idcard_str && $idcard_str <= '1221') {
            $constellation = '射手座';
        } else {
            $constellation = '摩羯座';
        }
        $array['constellation'] = $constellation;
        return $array;
    }

}
