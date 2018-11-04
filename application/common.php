<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件

function jiaMi($string){
    return md5(sha1('auth_'.$string .'_auth'));
}


/**
     * 递归实现无限极分类
     * @param $array 分类数据
     * @param $pid 父ID
     * @param $level 分类级别
     * @return $list 分好类的数组 直接遍历即可 $level可以用来遍历缩进
     */

    function getTree($array, $pid =0, $level = 0){

        //声明静态数组,避免递归调用时,多次声明导致数组覆盖
        static $list = [];
        foreach ($array as $key => $value){
            //第一次遍历,找到父节点为根节点的节点 也就是pid=0的节点
            if ($value['pid'] == $pid){
                //父节点为根节点的节点,级别为0，也就是第一级
                $value['level'] = $level;
                $value['name'] = str_repeat('┣━', $level)." ".$value['name'];
                //把数组放到list中
                $list[] = $value;
                //把这个节点从数组中移除,减少后续递归消耗
                unset($array[$key]);
                
                //开始递归,查找父ID为该节点ID的节点,级别则为原级别+1
                getTree($array, $value['id'], $level+1);
            }
        }
        return $list;
    }

    /**
     * 处理两个数组
     */
/*
array(3) {
  [0] => string(1) "4"
  [1] => string(1) "1"
  [2] => string(1) "2"
}
array(5) {
  [0] => array(2) {
    ["rid"] => int(1)
    ["name"] => string(12) "增加文章"
  }
  [1] => array(2) {
    ["rid"] => int(2)
    ["name"] => string(12) "修改文章"
  }
  [2] => array(2) {
    ["rid"] => int(3)
    ["name"] => string(12) "删除文章"
  }
  [3] => array(2) {
    ["rid"] => int(4)
    ["name"] => string(30) "拥有文章的增删改权限"
  }
  [4] => array(2) {
    ["rid"] => int(5)
    ["name"] => string(12) "后台首页"
  }
}
*/
    function mergeArray($arr, $ar){
        asort($arr);
        foreach ($ar as $key => $value) {
            foreach ($arr as $k => $val) {
                if($value['rid'] == $val){
                    $ar[$key]['is_check'] = 1;
                    break;
                }else{
                    $ar[$key]['is_check'] = 0;
                    // break;
                }
            }
        }

        return $ar;
    }