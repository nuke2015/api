<?php

namespace nuke2015\api\base;

// 通用 数据列表
// 2020年3月14日 15:07:04
// 正式启用数据继承服务,提升业务协同能力.
// 参照jiazhen/controller/Demo*,对不同角色权限的数据实现
trait ApiDataListAction
{
    protected function _sql_field()
    {
        // 输出 字段 控制
    }

    protected function _sql_from()
    {
        // 输出 主体逻辑 构造
    }

    // 通用列表性控制器
    protected function _sql_str($sql_where)
    {
        $sql_field = $this->_sql_field();
        $sql_from  = $this->_sql_from();
        $sql       = <<<doc
select $sql_field
from $sql_from
where $sql_where
doc;
        return $sql;
    }
}
