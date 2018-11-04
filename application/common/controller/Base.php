<?php 
namespace app\common\controller;
use think\captcha\Captcha;

class  Base  extends  \think\Controller
{	
	/**
	 * 返回信息
	 * @param  [int]    $code [code码]
	 * @param  [string] $msg  [信息]
	 * @param  array    $data [数据]
	 * @return [json]         [返回json]
	 */
	public function response($code,$msg,$data=[])
	{
		$res['code'] = $code;
		$res['msg'] = $msg;
		$res['data'] = $data;
		echo  json_encode($res);
		die;
	}

	/**
	 * 验证码设置
	 */
	public function verify()
	{	
		$config =    [
			//验证码字符集合
			'codeSet'	=> '1234567890',
			//是否画混淆曲线
			'useCurve'  => false,
			//是否添加杂点
			'useNoise'  => false,
		    // 验证码字体大小
		    'fontSize'    =>    30,    
		    // 验证码位数
		    'length'      =>    4,   
		    // 关闭验证码杂点
		    'useNoise'    =>    false, 
		    //验证成功后是否重置
		    'reset'		  => true,
		];

		$captcha = new Captcha($config);
		return $captcha->entry();
	}

	/**
	 * 验证码校验 失败返回错误信息，成功返回true
	 */
	public function checkVerify($value)
	{
		$captcha = new Captcha();
		if( !$captcha->check($value))
		{
			$this->response(201,'验证码错误');
		}
		return true;
	}
	
	/**
	 * 调用admin 下 的model下的user模型中的getLoginUserAccess()方法，获取当前登录用户的权限
	 */
	public function  getLoginAccess(){
		$a = new \app\admin\model\User();
		return $a->getLoginUserAccess();
	}
}