<?php

namespace nuke2015\api\base;

use nuke2015\api\org;

trait CURDAction
{
    // protected static $db = 'didiyuesao\\com\\model\\';

    // 参数标准化
    protected function _param($p = [])
    {
        if (!$p) {
            $p = org\input::xss($_REQUEST);
        }

        //默认第一页;
        $page = intval($p['page']);
        if ($page < 1) {
            $page = 1;
        }
        $p['page'] = $page;

        //默认每页10条;
        $size = intval($p['size']);
        if (!$size || $size > 20) {
            $size = 20;
        }
        $p['size'] = $size;

        return $p;
    }

    // 数据读取
    protected function _list($table, $sql, $page, $size, $order = 'id DESC', $func = null)
    {
        $model = $this->_model($table);
        if (!$page) {
            $page = 1;
        }
        if (!$size) {
            $size = 20;
        }
        list($total, $data) = $model->subquery($sql, $page, $size, $order);
        if ($func) {
            // formater
            $func($data);
        }

        // 返回值
        $return = array('page' => strval($page), 'size' => strval($size), 'total' => strval($total), 'count' => strval(count($data)), 'data' => (array) $data);

        return $return;
    }

    // 通用详情
    public function _info($table, $sql, $func = null)
    {
        $return = $this->_list($table, $sql, 1, 1, null, $func);
        if ($return && count($return)) {
            return $return['data'][0];
        }
    }

    // 通用添加
    protected function _add($table, $data)
    {
        unset($data['id']);
        return $this->_save($table, $data);
    }

    // 通用保存
    protected function _save($table, $data)
    {
        $model = $this->_model($table);
        $save = $model->wash($data);
        if ($save && count($save)) {
            if ($save['id']) {
                $do = $model->save($save);
                if ($do) {
                    $return = intval($save['id']);
                }
            } else {
                $return = $model->insert($save);
            }
        }

        return $return;
    }

    // 快速初始化
    protected function _model($table)
    {
        $table = self::$db . trim($table);
        $model = new $table();

        return $model;
    }

    // 通用删除
    protected function _delete($table, $p)
    {
        $id = intval($p['id']);
        if (!$id) {
            return [ERR_WRONG_ARG, 'id'];
        }

        $model = $this->_model($table);

        return $model->delete(['id' => $id]);
    }

    // 通用上下架
    protected function _status($table, $p)
    {
        $id = intval($p['id']);
        if (!$id) {
            return [ERR_WRONG_ARG, 'id'];
        }

        $status = intval($p['status']);

        return $this->_save($table, ['id' => $id, 'status' => $status]);
    }
}
