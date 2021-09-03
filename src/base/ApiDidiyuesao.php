<?php

namespace nuke2015\api\base;

class ApiDidiyuesao extends ApiBaseAction
{
    public function _initialize()
    {
        parent::_initialize();
    }

    public function index()
    {
        // 象征性函数
    }

    public function _empty($action)
    {
        $this->error('404la-' . $action);
    }
}
