<?php
namespace app\admin\validate;

use think\Validate;

class User extends Validate
{   
    protected $rule =   [
        'account'  => 'require|max:12|min:6|unique:user',
        'username'   => 'require|min:2|max:10|unique:user',
        'email' => 'require|unique:user|email',    
    ];
    
    protected $message  =   [
        'account.require' => '登录账号必填',
        'account.max'     => '账号最多不能超过12个字符',
        'account.min'   => '账号最少6个字符',
        'account.unique'  => '账号已存在',
        'username.require' => '昵称必填',
        'username.max'     => '昵称最多不能超过10个字符',
        'username.min'   => '昵称最少2个字符',
        'username.unique'  => '昵称已存在',
        'email.require'        => '邮箱必填',    
        'email.unique'        => '邮箱已存在',    
        'email.email'        => '邮箱格式错误',    
    ];

}