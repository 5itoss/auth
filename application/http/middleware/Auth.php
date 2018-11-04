<?php
namespace app\http\middleware;
use app\facade\Auth as AuthCheck;
use think\facade\Response;
use app\facade\Base;
use think\facade\Session;
use traits\controller\Jump;

class Auth
{	
	use Jump;

    public function handle($request, \Closure $next)
    {	

    	//认证url
    	$name = strtolower($request->module() . '/' . $request->controller() . '/' .$request->action());
		//认证用户id，从session中读取
		$uid = Session::get('uid');
		$is_admin = Session::get('userinfo')['is_admin'];
    	//分类
	    $type= 0; //0 可以定义规则匹配条件,1不可以
	    //执行check的模式
	    $mode= 'url';
	    //'or' 表示满足任一条规则即通过验证;
		//'and'则表示需满足所有规则才能通过验证
		
		$relation= 'and';
		if(!$uid){
			$this->error("未登录",url('user/login'));
		}
		
		if(!$is_admin){
			$this->error("非管理员用户，不能访问",url('user/login'));
		}

		$res = AuthCheck::check($name,$uid);
		if($res === false){
			return \view('tips');
			die;   		
		};
		
		return $next($request);
		
    }
}
