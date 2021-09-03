<?php
namespace nuke2015\api\org;

// 数组辅助
class Tarray
{

    //类似于excel 根据列排序
    public function sort_by_index($arrays, $sort_key, $sort_order = SORT_ASC, $sort_type = SORT_NUMERIC)
    {
        if (is_array($arrays)) {
            foreach ($arrays as $array) {
                if (is_array($array)) {
                    $key_arrays[] = $array[$sort_key];
                } else {
                    return false;
                }
            }
        } else {
            return false;
        }
        array_multisort($key_arrays, $sort_order, $sort_type, $arrays);
        return $arrays;
    }

    // 树形
    public static function tree($arr, $tag_sub = 'list')
    {
        $refer = array();
        $tree  = array();
        foreach ($arr as $k => $v) {
            $refer[$v['id']] = &$arr[$k]; //创建主键的数组引用
        }
        foreach ($arr as $k => $v) {
            $pid = $v['pid']; //获取当前分类的父级id
            if ($pid == 0) {
                $tree[] = &$arr[$k]; //顶级栏目
            } else {
                if (isset($refer[$pid])) {
                    $refer[$pid][$tag_sub][] = &$arr[$k]; //如果存在父级栏目，则添加进父级栏目的子栏目数组中
                }
            }
        }
        return $tree;
    }
}
