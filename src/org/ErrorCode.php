<?php

namespace nuke2015\api\org;

// 错误值 错误含义

/**
 * Api错误码,此错误码只对api的result方法有用,
 * 所以,放在配置文件里,不放在全局变量中.
 */
class ErrorCode
{
    public static $ERROR_MSGS;

    //出错消息定义;
    public static function config_map()
    {
        $ERROR_MSGS = array();
        $ERROR_MSGS['ERR_NONE'] = array('code' => 0, 'msg' => 'success');
        $ERROR_MSGS['ERR_EMPTY'] = array('code' => -2, 'msg' => '暂时无数据!');
        $ERROR_MSGS['HTML'] = array('code' => 200, 'msg' => 'html');
        $ERROR_MSGS['ERR_WRONG_UNKOWN'] = array('code' => -1, 'msg' => '未知原因失败');
        $ERROR_MSGS['ERR_NOT_FOUND'] = array('code' => 404, 'msg' => '当前接口不存在!');
        $ERROR_MSGS['ERR_OUT_OF_IP'] = array('code' => 9999, 'msg' => '对不起, 此ip禁止访问!');

        $ERROR_MSGS['ERR_WRONG_TOKEN'] = array('code' => 10001, 'msg' => '对不起,登陆态过期!');
        $ERROR_MSGS['ERR_WRONG_SERVER'] = array('code' => 10002, 'msg' => '系统繁忙,请稍候再试。');
        $ERROR_MSGS['ERR_WRONG_ARG'] = array('code' => 10003, 'msg' => '对不起，参数不合法。');
        $ERROR_MSGS['ERR_WRONG_NO_ORDER'] = array('code' => 10004, 'msg' => '对不起，目标数据不存在。');
        $ERROR_MSGS['ERR_WRONG_NO_REGION'] = array('code' => 20001, 'msg' => '对不起，不在服务范围内。');
        $ERROR_MSGS['ERR_WRONG_NO_STOCK'] = array('code' => 20002, 'msg' => '对不起，库存不足。');
        $ERROR_MSGS['ERR_WRONG_NO_MASER'] = array('code' => 20003, 'msg' => '对不起，该月嫂暂时不能提供服务。');
        $ERROR_MSGS['ERR_WRONG_ADDRESS'] = array('code' => 20004, 'msg' => '收货地址无法识别。');
        $ERROR_MSGS['ERR_NO_DATA'] = array('code' => 10011, 'msg' => '对不起，此数据已无效，请查证。');
        $ERROR_MSGS['ERR_NO_RIGHT'] = array('code' => 10012, 'msg' => '对不起，您无权处理此记录。');
        $ERROR_MSGS['ERR_OUT_OF_TIME'] = array('code' => 10013, 'msg' => '对不起，已经超时，请重新获取验证码。');
        $ERROR_MSGS['ERR_OUT_OF_EDIT'] = array('code' => 10014, 'msg' => '对不起，此数据不能修改。');
        $ERROR_MSGS['ERR_OUT_OF_DELETE'] = array('code' => 10015, 'msg' => '对不起，此数据不能删除。');
        $ERROR_MSGS['ERR_DATA_EXIST'] = array('code' => 10016, 'msg' => '对不起，数据已存在。');
        $ERROR_MSGS['ERR_DATA_MISS'] = array('code' => 10017, 'msg' => '对不起，数据缺少，请确认是否已完全输入。');
        $ERROR_MSGS['ERR_DATA_DONE'] = array('code' => 10018, 'msg' => '对不起，数据已经处理完成。');
        $ERROR_MSGS['ERR_NO_ACC'] = array('code' => 10019, 'msg' => '对不起，你已被禁言！');
        $ERROR_MSGS['ERR_NO_LOGIN'] = array('code' => 10020, 'msg' => '对不起，此帐号未登录。');
        $ERROR_MSGS['ERR_WRONG_PWD'] = array('code' => 10021, 'msg' => '对不起，帐号密码错误。');
        $ERROR_MSGS['ERR_ACC_LOCKED'] = array('code' => 10022, 'msg' => '对不起，此帐号已被锁定。');
        $ERROR_MSGS['ERR_WRONG_CODE'] = array('code' => 10023, 'msg' => '验证码不正确，请重新输入');
        $ERROR_MSGS['ERR_FILEUPLOAD'] = array('code' => 10024, 'msg' => '对不起，文件上传失败。');
        $ERROR_MSGS['ERR_SENSITIVE'] = array('code' => 10025, 'msg' => '包含敏感词，请修改后再试。');
        $ERROR_MSGS['ERR_FREQUENTLY'] = array('code' => 10026, 'msg' => '您的操作太频繁，请稍候再试。');
        $ERROR_MSGS['ERR_USER_REG_WRONG'] = array('code' => 10027, 'msg' => '此账号已注册,试试找回密码。');
        $ERROR_MSGS['ERR_USER_REG_MISS'] = array('code' => 10028, 'msg' => '对不起，账号未注册。');
        $ERROR_MSGS['ERR_WRONG_CODE_IMAGE'] = array('code' => 10029, 'msg' => '图形验证码不正确，请重新输入。');

        // 2015年12月1日 17:24:57
        $ERROR_MSGS['ERR_WRONG_SECRET'] = array('code' => 10100, 'msg' => '验证授权失败！请检查appKey和secret以及签名参数sign是否正确');
        $ERROR_MSGS['ERR_WRONG_JAVA'] = array('code' => 10101, 'msg' => '系统程序错误！');
        $ERROR_MSGS['ERR_WRONG_MODIFY'] = array('code' => 10300, 'msg' => '数据被非法篡改。');
        $ERROR_MSGS['ERR_WRONG_NO_ENOUGH'] = array('code' => 30003, 'msg' => '金额不足');
        $ERROR_MSGS['ERR_VOUCHERS_REPEAT'] = array('code' => 30004, 'msg' => '您已领新用户红包');
        $ERROR_MSGS['ERR_VOUCHERS_COUNT'] = array('code' => 30005, 'msg' => '您已超过领取红包次数');
        $ERROR_MSGS['ERR_WRONG_NO_USER'] = array('code' => 30006, 'msg' => '用户不存在');
        $ERROR_MSGS['ERR_WRONG_PAYTYPE'] = array('code' => 30007, 'msg' => '对不起，该支付类型暂不支持！');
        $ERROR_MSGS['ERR_WRONG_ORDER_STATUS'] = array('code' => 30008, 'msg' => '订单不是待付款状态！');
        $ERROR_MSGS['ERR_WRONG_ADDRESS_NOT_EXIST'] = array('code' => 30009, 'msg' => '收货地址不存在！');
        $ERROR_MSGS['ERR_WRONG_ADDRESS_USER'] = array('code' => 30010, 'msg' => '收货地址与用户id不一致！');
        $ERROR_MSGS['ERR_WRONG_COMMIDITY_OUT'] = array('code' => 30011, 'msg' => '该商品不存在或已下架！');
        $ERROR_MSGS['ERR_WRONG_COMMIDITY_FIT'] = array('code' => 30012, 'msg' => '亲，该商品不支持红包兑换！');
        $ERROR_MSGS['ERR_WRONG_COMMIDITY_LACK'] = array('code' => 30013, 'msg' => '亲，该商品库存告急！');
        $ERROR_MSGS['ERR_WRONG_ORDER_NOT_EXIST'] = array('code' => 30014, 'msg' => '订单不存在！');
        $ERROR_MSGS['ERR_WRONG_NOT_BUY'] = array('code' => 30015, 'msg' => '没有购买记录！');

        //2016-5-31
        $ERROR_MSGS['ERR_WRONG_SCHEDULE_OCCUPY'] = array('code' => 30016, 'msg' => '月嫂档期已占用');
        $ERROR_MSGS['ERR_WRONG_ORDER_COMMENT'] = array('code' => 30017, 'msg' => '只有服务已完成的订单才能进行评价！');
        $ERROR_MSGS['ERR_WRONG_BIND_MASER'] = array('code' => 30018, 'msg' => '重复绑定失败,请先解绑！');
        $ERROR_MSGS['ERR_WRONG_ORDER_PROCESS'] = array('code' => 30019, 'msg' => '订单状态与相关操作不对应！');
        $ERROR_MSGS['ERR_WRONG_MOBILE'] = array('code' => 30020, 'msg' => '请输入正确的手机号码!');
        $ERROR_MSGS['ERR_WRONG_COUPON'] = array('code' => 30021, 'msg' => '优惠券无效!');
        $ERROR_MSGS['ERR_WRONG_COUPON_NO_SUIT'] = array('code' => 30022, 'msg' => '优惠券对当前产品不适用!');
        $ERROR_MSGS['ERR_WRONG_PAY_CHANNEL'] = array('code' => 30023, 'msg' => '很抱歉,暂不支持当前的支付渠道!');
        $ERROR_MSGS['ERR_WRONG_COUPON_CODE'] = array('code' => 30024, 'msg' => '口令错误');
        $ERROR_MSGS['ERR_WRONG_COUPON_RECEIVED'] = array('code' => 30025, 'msg' => '你已兑换该优惠券');
        $ERROR_MSGS['ERR_WRONG_WORD'] = array('code' => 30026, 'msg' => '输入的口令有误');
        $ERROR_MSGS['ERR_COUPON_REVICVED'] = array('code' => 30027, 'msg' => '优惠券已兑换');
        $ERROR_MSGS['ERR_ACTIVITY_NOT_FOUND'] = array('code' => 30028, 'msg' => '活动不存在或已过期');
        $ERROR_MSGS['ERR_ACTIVITY_REPEAT'] = array('code' => 30029, 'msg' => '已参与过活动,请勿重复操作');
        $ERROR_MSGS['ERR_WRONG_SCHEDULE_EMPTY'] = array('code' => 30030, 'msg' => '月嫂档期已占用,请重新选择');
        $ERROR_MSGS['ERR_WRONG_OPEN_ID'] = array('code' => 30031, 'msg' => '第三方open_id无效!');
        $ERROR_MSGS['ERR_FAIL_SALER_ID'] = array('code' => 30032, 'msg' => '对不起,合伙人信息不存在!');

        $ERROR_MSGS['ERR_LONG_ASK_CONTENT'] = array('code' => 30033, 'msg' => '问题长度不能大于150个字!');
        $ERROR_MSGS['ERR_VOTE_NOT_YET_START'] = array('code' => 30034, 'msg' => '活动投票未开始');

        $ERROR_MSGS['ERR_WRONG_EXIST_CONTENT'] = array('code' => 30035, 'msg' => '已评论过了');
        $ERROR_MSGS['ERR_FAIL_CASHIER_ID'] = array('code' => 30036, 'msg' => '收款员账号不存在');
        $ERROR_MSGS['ERR_FAIL_SALER_ID'] = array('code' => 30037, 'msg' => '归属人不存在');
        $ERROR_MSGS['ERR_HAS_FOUND_CUSTOMER_DATA'] = array('code' => 30038, 'msg' => '已录入该客户资料');
        $ERROR_MSGS['ERR_NOT_FOUND_DATA_ID'] = array('code' => 30039, 'msg' => '数据ID不存在');
        $ERROR_MSGS['ERR_TASK_HAS_REMARK'] = array('code' => 30040, 'msg' => '该任务已跟进');
        $ERROR_MSGS['ERR_EXPIRE_ORDER_STATUS'] = array('code' => 30041, 'msg' => '订单已过期,请重新下单!');
        $ERROR_MSGS['ERR_ORDER_NOT_SERVING'] = array('code' => 30042, 'msg' => '只有服务中的订单才能续单!');
        $ERROR_MSGS['ERR_ORDER_NOT_LONG'] = array('code' => 30043, 'msg' => '只有住家月子服务才能指定月嫂!');
        $ERROR_MSGS['ERR_OCCUPY_SKILLERYUYING'] = array('code' => 30044, 'msg' => '育婴师档期占用!');
        $ERROR_MSGS['ERR_VOCATION_TOO_LONG'] = array('code' => 30045, 'msg' => '请假天数太长!');
        $ERROR_MSGS['ERR_VOCATION_REMOVE_FAIL'] = array('code' => 30046, 'msg' => '已经过了销假时间!');
        $ERROR_MSGS['ERR_CITYCODE'] = array('code' => 30047, 'msg' => '请选择您所在的城市!');

        $ERROR_MSGS['ERR_PARTNER_HAS_JOIN'] = array('code' => 30048, 'msg' => '你已经是注册用户,请直接登录APP使用!');
        $ERROR_MSGS['ERR_PARTNER_HAS_RECOMMEND'] = array('code' => 30049, 'msg' => '你已经是被推荐过了,更多内容请到家家月嫂APP查看!');

        $ERROR_MSGS['ERR_MORE_THAN_SUBMIT_TIME'] = array('code' => 30050, 'msg' => '护理时间超过48小时不能记录');

        $ERROR_MSGS['ERR_EXITS_HAS_ROOM'] = array('code' => 30051, 'msg' => '该客户合同已经订房');
        $ERROR_MSGS['ERR_NOT_FOUND_ORDER_CONTRCAT_DATA'] = array('code' => 30052, 'msg' => '找不到订单归属合同,请先核实');
        $ERROR_MSGS['ERR_VERSION_UPDATE'] = array('code' => 30053, 'msg' => '客户端版本太低,请及时升级!');
        $ERROR_MSGS['ERR_PRIVILEGE_NO'] = array('code' => 30054, 'msg' => '无操作权限!');

        return $ERROR_MSGS;
    }

    //错误信息查询
    public static function get_by_key($code)
    {
        $result = '';
        $ERROR_MSGS = self::config_map();
        if ($ERROR_MSGS && count($ERROR_MSGS)) {
            foreach ($ERROR_MSGS as $key => $value) {
                if ($value['code'] == $code) {
                    $result = $value['msg'];
                }
            }
        }

        return $result;
    }

    // 码与值对应
    public static function key_code_map()
    {
        $result = array();
        $ERROR_MSGS = self::config_map();
        if ($ERROR_MSGS && count($ERROR_MSGS)) {
            foreach ($ERROR_MSGS as $key => $value) {
                $result[$key] = $value['code'];
            }
        }

        return $result;
    }
}
