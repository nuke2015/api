<?php

namespace nuke2015\api\base;

use didiyuesao\com\org;

class ApiJiazhen extends ApiBaseAction implements InterfaceAction
{
    public function _initialize()
    {
        parent::_initialize();
    }

    public function index($param)
    {
        // 象征性函数
    }

    public function _empty($action)
    {
        $this->error('404la-' . $action);
    }
}
