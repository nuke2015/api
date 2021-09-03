<?php
namespace nuke2015\api\org;

/*
 *  长文章分页类
 */
class cutpage
{
    private $pagestr; //被切分的内容
    private $pagearr; //被切分文字的数组格式
    private $sum_word; //总字数(UTF-8格式的中文字符也包括)
    private $sum_page; //总页数
    private $page_word; //一页多少字
    private $cut_tag; //自动分页符
    private $cut_custom; //手动分页符
    private $part; //当前切分的页数，第几页
    private $url;

    public function __construct($pagestr, $page_word = 1000)
    {
        $this->page_word  = $page_word;
        $this->cut_tag    = array("</table>", "</div>", "</p>", "<br/>", "”。", "。", ".", "！", "……", "？", ",");
        $this->cut_custom = "{nextpage}";
        $tmp_page         = intval(trim($_GET["part"]));
        $this->part       = $tmp_page > 1 ? $tmp_page : 1;
        $this->pagestr    = $pagestr;
    }

    //统计总字数
    public function get_page_word()
    {
        $this->sum_word = $this->strlen_utf8($this->pagestr);
        return $this->sum_word;
    }

    /*  统计UTF-8编码的字符长度
     *  一个中文，一个英文都为一个字
     */
    public function strlen_utf8($str)
    {
        $i     = 0;
        $count = 0;
        $len   = strlen($str);
        while ($i < $len) {
            $chr = ord($str[$i]);
            $count++;
            $i++;
            if ($i >= $len) {
                break;
            }

            if ($chr & 0x80) {
                $chr <<= 1;
                while ($chr & 0x80) {
                    $i++;
                    $chr <<= 1;
                }
            }
        }
        return $count;
    }

    //设置自动分页符号
    public function set_cut_tag($tag_arr = array())
    {
        $this->cut_tag = $tag_arr;
    }

    //设置手动分页符
    public function set_cut_custom($cut_str)
    {
        $this->cut_custom = $cut_str;
    }

    public function show_cpage($part = 0)
    {
        $this->cut_str();
        $part = $part ? $part : $this->part;
        return $this->pagearr[$part];
    }

    // todo,处理标签截断的问题
    public function cut_str()
    {
        $page_arr = str_split($this->pagestr, $this->page_word);
        $this->sum_page = count($page_arr); //总页数
        $this->pagearr  = $page_arr;
        return $page_arr;
    }

    // 当前内容
    public function pageshow($part)
    {
        $pages = $this->cut_str();
        if ($pages && count($pages)) {
            return $pages[$part - 1];
        }
    }

    //显示上一条，下一条
    public function pagenav()
    {
        $this->set_url();
        $str = '';

        //$str .= $this->part.'/'.$this->sum_page;

        for ($i = 1; $i <= $this->sum_page; $i++) {
            if ($i == $this->part) {
                $str .= "<a href='#' class='cur'>" . $i . "</a> ";
            } else {
                $str .= "<a href='" . $this->url . $i . "'>" . $i . "</a> ";
            }
        }

        return $str;
    }

    // 下2
    public function show_prv_next2()
    {
        $this->set_url();
        $str = '<div class="pageBox">';
        if ($this->sum_page > 1 and $this->part > 1) {
            $str .= "<a href='" . $this->url . ($this->part - 1) . "'>&lt;&lt;上一页</a> ";
        }
        if ($this->sum_page > 1 and $this->part < $this->sum_page) {
            $str .= "<a href='" . $this->url . ($this->part + 1) . "'>下一页&gt;&gt;</a>";
        }
        $str .= '</div>';
        return $str;
    }

    public function show_page_select()
    {
        if ($this->sum_page > 1) {
            $str = "   <select onchange='location.href=this.options[this.selectedIndex].value'>";
            for ($i = 1; $i <= $this->sum_page; $i++) {
                $str .= "<option value='" . $this->url . $i . "' " . (($this->part) == $i ? " selected='selected'" : "") . ">第" . $i . "页</option>";
            }
            $str .= "</select>";
        }
        return $str;
    }
    public function show_page_select_wap()
    {
        if ($this->sum_page > 1) {
            $str = "<select ivalue='" . ($this->part - 1) . "'>";
            for ($i = 1; $i <= $this->sum_page; $i++) {
                $str .= "<option onpick='" . $this->url . $i . "'>第" . $i . "节</option>";
            }
            $str .= "</select>";
        }
        return $str;
    }

    public function set_url()
    {
        parse_str($_SERVER["QUERY_STRING"], $arr_url);
        if ($arr_url && count($arr_url)) {
            unset($arr_url["part"]);
        }
        $str = http_build_query($arr_url);
        $str = "/?" . urldecode($str);
        $str = str_ireplace('/?s=', '/a/', $str);
        $str .= '?part=';
        $this->url = $str;
        return;
    }
}
