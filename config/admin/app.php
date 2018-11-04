<?php

return [
    // 应用调试模式
    'app_debug'              => true,
    // 应用Trace
    'app_trace'              => true,
    //auth权限配置
    'auth'	=> [
        'auth_on' => 1, // 权限开关
        'auth_type' => 1, // 认证方式，1为实时认证；2为登录认证。
        'auth_group' => 'user_group', // 用户组数据表名
        'auth_group_access' => 'user_group_access', // 用户-用户组关系表
        'auth_rule' => 'auth_rule', // 权限规则表
        'auth_user' => 'member', // 用户信息表
    ],

    //默认密码
    'default_password' =>'123456',
];