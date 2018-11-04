<?php
namespace app\admin\model;
use think\Db;

class Access extends \think\Model
{
    protected $name = "user_group";

    /**
     * 获取权限组列表
     */
    public function getAccessList()
    {   
        $list = $this->where('id','<>',1)->select();
        return $list;
    }

    /**
     * 获取单个权限组信息
     */
    public function getAccess($id)
    {
        $info = $this->where('id',$id)->find();
        return $info;
    }

    /**
     * 添加权限组
     */
    public function addAccess($data)
    {
        $res = $this->insert($data);
        return $res;
    }

    /**
     * 删除权限组
     */
    public function del($id)
    {
        $res = $this->where('id',$id)->delete();
        return $res;
    }

    /**
     * 获取权限规则
     */
    public function rules()
    {
        $rules = Db::name('auth_rule')->where('status',0)->field('id as rid,name')->select();
        return $rules;
    }

    /**
     * 修改权限组信息
     */
    public function edit($data)
    {
        $res = Db::name('user_group')->where('id',$data['id'])->update($data);
        return $res;
    }

    /**
     * 添加规则
     */
    public function addRules($data)
    {
        $res = Db::name('auth_rule')->insert($data);
        return $res;
    }

    /**
     * 获取权限规则列表
     */
    public function ruleList()
    {
        $res = Db::name('auth_rule')->select();
        return $res;
    }

    /**
     * 获取单个规则信息
     */
    public function getRuleInfo($id)
    {
        $info = Db::name('auth_rule')->where('id',$id)->find();
        return $info;
    }

    /**
     * 修改单个规则信息
     */
    public function editRule($data)
    {
        $res = Db::name('auth_rule')->where('id',$data['id'])->update($data);
        return $res;
    }

    /**
     * 删除单个规则
     */
    public function delRule($id)
    {
        $res = Db::name('auth_rule')->where('id',$id)->delete();
        return $res;
    }
}
