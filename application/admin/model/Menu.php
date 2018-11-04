<?php
namespace app\admin\model;

class Menu extends \think\Model
{
    public function getMenu()
    {
        $menus = $this->select();
        return $menus;
    }

    public function addMenu($data)
    {
        $res = $this->insert($data);
        return $res;
    }
   
}