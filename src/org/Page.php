<?php

namespace nuke2015\api\org;

// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2009 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
// $Id$

class Page
{
    // 起始行数
    public $firstRow;

    // 列表每页显示行数
    public $listRows;

    // 页数跳转时要带的参数
    public $parameter;

    // 分页总页面数
    public $totalPages;

    // 总行数
    public $totalRows;

    // 当前页数
    public $nowPage;

    // 分页的栏的总页数
    public $coolPages;

    // 分页栏每页显示的页数
    public $rollPage;

    // 分页url定制
    public $urlrule;

    /**
    +----------------------------------------------------------
     * 架构函数.
    +----------------------------------------------------------
    +----------------------------------------------------------
     * @param array $totalRows 总的记录数
     * @param array $listRows  每页显示记录数
     * @param array $parameter 分页跳转的参数
    +----------------------------------------------------------
     */
    public function __construct($totalRows, $listRows, $p = '')
    {
        $this->totalRows = $totalRows;
        $this->parameter = $parameter;
        $this->rollPage = 2;
        if ($listRows > 100 || $listRows < 0) {
            $listRows = 100;
        }

        $this->listRows = ($listRows > 0) ? $listRows : 20;
        $this->totalPages = ceil($this->totalRows / $this->listRows);

        //总页数
        $this->coolPages = ceil($this->totalPages / $this->rollPage);
        if ($p) {
            $this->nowPage = $p;
        } else {
            $this->nowPage = ($_GET['page'] > 0) ? intval($_GET['page']) : 1;
        }
        if (!empty($this->totalPages) && $this->nowPage > $this->totalPages) {
            $this->nowPage = $this->totalPages;
        }
        $this->firstRow = $this->listRows * ($this->nowPage - 1);
    }

    public function show()
    {
        if ($this->totalRows == 0 or $this->listRows == 0 or $this->totalPages <= 1) {
            return '';
        }
        //urldecode
        if (!$this->urlrule) {
            $p = 'page';
            $nowCoolPage = ceil($this->nowPage / $this->rollPage);
            $url = $_SERVER['REQUEST_URI'].(strpos($_SERVER['REQUEST_URI'], '?') ? '' : '?').$this->parameter;
            $parse = parse_url($url);
            if (isset($parse['query'])) {
                parse_str($parse['query'], $params);
                unset($params[$p]);
                $urlrule = $parse['path'].'?'.urldecode(http_build_query($params));
                $urlrule = $urlrule.'&'.$p.'={$page}';
            } else {
                $urlrule = $urlrule.'?'.$p.'={$page}';
            }
        } else {
            $p = 'page';
            $nowCoolPage = ceil($this->nowPage / $this->rollPage);
            $url = $_SERVER['REQUEST_URI'].(strpos($_SERVER['REQUEST_URI'], '?') ? '' : '?').$this->parameter;
            $parse = parse_url($url);
            if (isset($parse['query'])) {
                parse_str($parse['query'], $params);
                unset($params[$p]);
                unset($params['s']);
                $urlrule = $this->urlrule.'?'.urldecode(http_build_query($params));
                $urlrule = $urlrule.'&'.$p.'={$page}';
            } else {
                $urlrule = $urlrule.'?'.$p.'={$page}';
            }
        }
        $pre_page = $this->nowPage - 1;
        $next_page = $this->nowPage + 1;

        if ($this->nowPage >= $this->totalPages) {
            $next_page = $this->nowPage = $this->totalPages;
        }
        if ($this->nowPage <= 1) {
            $pre_page = $this->nowPage = 1;
        }

        $output = '';
        $output .= '<a class="a1">共'.$this->totalRows.'条'.'</a>';
        $output .= '<a href="'.$this->pageurl($urlrule, 1, $this->parameter).'">'.'第一页'.'</a>';
        $output .= '<a href="'.$this->pageurl($urlrule, $pre_page, $this->parameter).'">'.'上一页'.'</a>';
        $show_nums = $this->rollPage * 2 + 1;

        // 显示页码的个数

        if ($this->totalPages <= $show_nums) {
            for ($i = 1; $i <= $this->totalPages; ++$i) {
                if ($i == $this->nowPage) {
                    $output .= '<span>'.$i.'</span>';
                } else {
                    $output .= '<a href="'.$this->pageurl($urlrule, $i, $this->parameter).'">'.$i.'</a>';
                }
            }
        } else {
            if ($this->nowPage < (1 + $this->rollPage)) {
                for ($i = 1; $i <= $show_nums; ++$i) {
                    if ($i == $this->nowPage) {
                        $output .= '<span>'.$i.'</span>';
                    } else {
                        $output .= '<a href="'.$this->pageurl($urlrule, $i, $this->parameter).'">'.$i.'</a>';
                    }
                }
            } elseif ($this->nowPage >= ($this->totalPages - $this->rollPage)) {
                for ($i = $this->totalPages - $show_nums; $i <= $this->totalPages; ++$i) {
                    if ($i == $this->nowPage) {
                        $output .= '<span>'.$i.'</span>';
                    } else {
                        $output .= '<a href="'.$this->pageurl($urlrule, $i, $this->parameter).'">'.$i.'</a>';
                    }
                }
            } else {
                $start_page = $this->nowPage - $this->rollPage;
                $end_page = $this->nowPage + $this->rollPage;
                for ($i = $start_page; $i <= $end_page; ++$i) {
                    if ($i == $this->nowPage) {
                        $output .= '<span>'.$i.'</span>';
                    } else {
                        $output .= '<a href="'.$this->pageurl($urlrule, $i, $this->parameter).'">'.$i.'</a>';
                    }
                }
            }
        }
        $output .= '<a href="'.$this->pageurl($urlrule, $next_page, $this->parameter).'">'.'下一页'.'</a>';
        $output .= '<a href="'.$this->pageurl($urlrule, $this->totalPages, $this->parameter).'">'.'最后页'.'</a>';

        return $output;
    }

    public function pageurl($urlrule, $page, $array = array())
    {
        $url = str_replace('{$page}', $page, $urlrule);

        return $url;
    }

    // 简易辅助分页条
    public static function pagebar($total, $size, $urlrule = '')
    {
        $page = intval($_REQUEST['page']);
        if (!$page) {
            $page = 1;
        }
        $size = intval($size);
        if ($size < 1 || $size > 100) {
            $size = 100;
        }

        if ($total > $size) {
            $Page_helper = new Page($total, $size);
            if ($urlrule) {
                $Page_helper->urlrule = $urlrule;
            }
            $pagebar = $Page_helper->show();
        }

        return ['page' => $page, 'size' => $size, 'total' => $total, 'pagebar' => $pagebar];
    }
}
