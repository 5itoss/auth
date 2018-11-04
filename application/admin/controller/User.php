<?php 
namespace app\admin\controller;
use think\facade\Request;
use app\admin\model\User as UserModel;
use think\facade\Session;
use think\Db;
use think\facade\Config;
use app\admin\validate\User as VUser;

class  User  extends  \app\common\controller\Base
{
	protected $middleware = [
		'Auth' => ['except'=>[
					'login','logout','getVerify'
				]
			]
		];

	/**
	 * 用户登录
	 */
	public function login()
	{	
		if(Request::isPost()){
			$login_info = Request::post();
			$this->checkVerify($login_info['code']);

			unset($login_info['code']);
			$login_info['password'] = \jiaMi($login_info['password']);
			$login_info['login_ip'] = Request::ip();
			$login_info['login_time'] = time();
			
			$user = new UserModel();
			$login_res = $user->login($login_info);
			if(!$login_res){
				$this->response(201,'用户名或密码错误');
			}

			Session::set('uid',$login_res['id']);
			Session::set('userinfo',$login_res);
			$this->response(200,'登录成功');
		}

		return $this->fetch();
	}

	/**
	 * 验证码
	 */
	public function getVerify()
	{
		return $this->verify();
	}

	/**
	 * 获取用户列表
	 */
	public function lst()
	{	
		$user = new UserModel();
		$users = $user->lst();
		$groups = $user->getGoups();

		//获取当前登录用户权限,输出到页面
		$login_access = $this->getLoginAccess(); 
		$this->assign('access',$login_access);
		
		$this->assign("groups",$groups);
		$this->assign("users",$users);
		return $this->fetch();
	}

	/**
	 * 退出登录
	 */
	public function logout()
	{
		if(Request::isPost()){
			Session::flush();
			$this->response(200,'退出成功');
		}
	}

	/**
	 * 获取用户信息
	 */
	public function getUserInfo($id)
	{	
		$user = new UserModel();
		$user_info = $user->getUserInfo($id);
		if($user_info){
			$this->response(200,'获取用户数据成功',$user_info);
		}
		$this->response(201,'获取用户数据失败');
	}

	/**
	 * 修改用户信息
	 */
	public function edit(){

		if(Request::isPost()){
			$edit_info = Request::post();
			if($edit_info['password1'] != $edit_info['password1']){
				$this->response(201,'两次密码不一致');
			}else if(!$edit_info['password'] && !$edit_info['password1']){
				unset($edit_info['password']);
				unset($edit_info['password1']);
			}else{
				unset($edit_info['password1']);
				$edit_info['password'] = \jiaMi($edit_info['password']);
			}
			$user = new UserModel();
			$res = $user->editUser($edit_info);
			if($res){
				$this->response(200,'成功');
			}
			$this->response(201,"修改失败，请确认是否有更改的信息");
		}
	}

	/**
	 * 添加用户
	 */
	public function add(){
		$user = new UserModel();
		if(Request::isPost()){
			$add_info = Request::post();
			if($add_info['password'] == ''){
				$add_info['password'] = \jiaMi(Config::get('default_password'));
			}else{
				$add_info['password'] = \jiaMi($add_info['password']);
			}
			$add_info['create_time'] = time();
			
			//重复验证
			$validate = new \app\admin\validate\User();
			if (!$validate->check($add_info)) {
				$this->response(202,$validate->getError());
			}

			$res = $user->add($add_info);
			switch ($res) {
				case 1:
					$this->response(200,'新增用户并分配权限成功');
					break;
				case 2:
					$this->response(201,'新增用户分配权限失败');
					break;
				case 3:
					$this->response(200,'新增用户成功');
					break;
				case 4:
					$this->response(201,'新增用户失败');
					break;
			}
			
		}

		$groups = $user->getGoups();
		$this->assign("groups",$groups);
		return $this->fetch();
	}

	/**
	 * 删除用户
	 */
	public function del(){
		if(Request::isDelete()){
			$id = Request::delete()['id'];
			$user = new UserModel();
			$res = $user->del($id);
			if($res){
				$this->response(200,"删除成功");
			}
			$this->response(201,"删除失败");
		}
	}
}