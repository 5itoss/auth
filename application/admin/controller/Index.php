<?php
namespace app\admin\controller;
use think\facade\Session;
use app\admin\model\User;

class Index extends \app\common\controller\Base
{	
	protected $middleware = ['Auth'];
	
    public function index()
    {	
        $login_access = $this->getLoginAccess();
        // dump($login_access);die;
        $this->assign('name',Session::get('userinfo')['username']);
        return $this->fetch();
    }
    
    public function welcome()
    {
    	return $this->fetch();
    }
}
