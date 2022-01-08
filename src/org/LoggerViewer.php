<?php

namespace nuke2015\api\org;

// 文件夹日志查看
class LoggerViewer
{
    static $path = '/home/ddys_run/cube/log';

    // 日期
    public function day_list()
    {
        $start  = mktime(0, 0, 0);
        $result = [];
        for ($i = $start - 86400 * 5; $i <= $start; $i += 86400) {
            $result[] = date('Y_m_d', $i);
        }
        $result = array_reverse($result);

        return $result;
    }

    // 文件列表
    public function filelist($keyword = '', $page = 1, $size = 50)
    {
        // glob不支持大小写
        $path    = self::$path;
        $keyword = $this->keyword_scan($keyword);
        chdir($path);
        $cmd_total = "ls|" . $keyword;
        exec($cmd_total, $list, $return_val);

        $total = count($list);
        $start = ($page - 1) * $size;
        $list  = array_slice($list, $start, $size);

        return [0, ['total' => $total, 'cmd' => $cmd_total, 'page' => $page, 'size' => $size, 'count' => count($list), 'data' => $list]];
    }

    // 关键词拆分
    public static function keyword_scan($keyword)
    {
        $keyword = trim($keyword);
        if (strlen($keyword) > 0 && strpos($keyword, '|') !== false) {
            $list = explode('|', $keyword);
        } elseif (strlen($keyword) > 0) {
            $list = [$keyword];
        } else {
            $list = [date('_m_d')];
        }

        // 组装
        if ($list && count($list)) {
            foreach ($list as $key => &$value) {
                $value = strip_tags($value);
                $value = "grep -i '{$value}'";
            }
            unset($value);
        }
        $result = implode("|", $list);
        return $result;
    }

    // 文件名校验
    public function file_check($file)
    {
        $path = self::$path;
        $file = $path . '/' . $file;
        $list = glob($file);

        return $list;
    }

    // 文件行数
    public function line_count($cmd)
    {
        $cmd_count = $cmd . '|wc -l';
        exec($cmd_count, $data_count);
        if ($data_count && count($data_count)) {
            $data_count = intval($data_count[0]);
        } else {
            $data_count = 0;
        }

        return $data_count;
    }

    // 文件查找
    public function grep($cmd, $keyword)
    {
        if (stripos($keyword, '|') !== false) {
            $arr = explode('|', $keyword);
            if ($arr && count($arr)) {
                foreach ($arr as $key => $value) {
                    $cmd .= " |grep $value";
                }
            }
        } else {
            $cmd .= " |grep $keyword";
        }

        return $cmd;
    }

    // 详情查看
    public function view($file, $keyword, $page, $size)
    {
        $filelist = $this->file_check($file);
        if ($filelist && count($filelist)) {
            $myfile = trim($filelist[0]);
            $cmd    = "tac $myfile";
            if ($keyword) {
                $cmd = $this->grep($cmd, $keyword);
            }

            $start  = ($page - 1) * $size + 1;
            $offset = $start + $size - 1;
            $total  = $this->line_count($cmd);

            $cmd .= "|sed -n $start,{$offset}p";

            // 结果还原
            $result = [];
            exec($cmd, $data);
            if ($data && count($data)) {
                foreach ($data as $key => $value) {
                    if ($value) {
                        $tmp = json_decode($value, 1);
                        if ($tmp && count($tmp)) {
                            $tmp['_raw'] = $value;
                            $result[]    = $tmp;
                        } else {
                            $result[] = $value;
                        }
                    }
                }
            }
            return [0, ['total' => $total, 'cmd' => $cmd, 'page' => $page, 'size' => $size, 'count' => count($result), 'data' => $result]];
        } else {
            return [1, 'file not found!'];
        }
    }
}
