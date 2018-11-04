<?php 
namespace app\admin\controller;
use think\facade\Request;

class Menu extends \app\common\controller\Base
{
    protected $middleware = ['Auth'];

    public function lst(){
        $menu = new \app\admin\model\Menu();
        $menus = $menu->getMenu();
        $new_menus = getTree($menus);
        
        $this->assign('menus', $new_menus);
        return $this->fetch();
    }


    public function add(){

        $menu = new \app\admin\model\Menu();

        if(Request::isPost()){
            $add_info = Request::post();
            $add_info['create_time'] = time();
            $res = $menu->addMenu($add_info);
            if($res){
                $this->response(200,"成功");
            }
            $this->response(0,'添加失败');
            die;
        }

        
        $menus = $menu->getMenu();
        $new_menus = getTree($menus);
        $this->assign('menus', $new_menus);
        return $this->fetch();
    }

    
}