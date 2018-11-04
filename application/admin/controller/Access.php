<?php
namespace app\admin\controller;
use app\admin\model\Access as AccessModel;
use think\facade\Request;
use think\facade\Session;
class Access extends \app\common\controller\Base
{   
    protected $middleware = ['Auth'];
    /**
     * 获取权限组列表并显示
     */
    public function lst()
    {
        $access = new AccessModel();
        $list = $access->getAccessList();
        //获取当前登录用户权限,输出到页面
        // $login_access = Session::get('userinfo')['access'];
        $login_access = $this->getLoginAccess(); 
        
        $this->assign('access',$login_access);
        $this->assign('list',$list);
        return $this->fetch();
    }
    
    /**
     * 获取权限组信息
     */
    public function getAccess($id){

        if(Request::isGet()){
            $access = new AccessModel();
            $info = $access->getAccess($id);
            if($info){
                $this->response(200,'成功',$info);
            }
            $this->response(201,'失败');
        }
    }

    /**
     * 添加权限组
     */
    public function add(){

        $a = new AccessModel();

        if(Request::isPost()){
            $add_info = Request::post();
            $res = $a->addAccess($add_info);
            if($res){
                $this->response(200,"成功");
            }
            $this->response(201,"失败");
        }

        //获取权限规则
        $rules = $a->rules();
        // $new_rules = mergeArray($info['rules'],$rules);
        $this->assign('list',$rules);
        return $this->fetch();
    }

    /**
     * 删除权限组
     */
    public function del(){
        if(Request::isDelete()){
            $id = Request::delete()['id'];
            $a = new AccessModel();
            $res = $a->del($id);
            if($res){
                $this->response(200,"成功");
            }
            $this->response(201,"失败");
        }
        $this->response(201,"error");
    }

    /**
     * 编辑权限组
     */
    public function edit($id)
    {   
        $a = new AccessModel();
        if(Request::isGet()){
            $id = Request::get();
            //获取权限组信息
            $info = $a->getAccess($id['id']);
            $info['rules'] = explode(',', $info['rules']);
            //获取权限规则
            $rules = $a->rules();
            $new_rules = mergeArray($info['rules'],$rules);
            $this->assign('info',$info);
            $this->assign('rules',$info['rules']);
            $this->assign('list',$new_rules);

            return $this->fetch('edit');
        }else if(Request::isPost()){
            $edit_info = Request::post();
            unset($edit_info['rid']);
            // dump($edit_info);die;
            $res = $a->edit($edit_info);
            if($res){
                $this->response(200,"成功");
            }
            $this->respnse(201,"修改失败，确认是否有信息被修改");
        }
    }

    /**
     * 添加权限规则
     */
    public function addRules()
    {
        if(Request::isPost()){
            $data = Request::post();

            //判断规则url是否符合,,,如果没有规则url也无法显示页面，可无需判断
            $a = new AccessModel();
            $data['create_time'] = time();
            $res = $a->addRules($data);
            if($res){
                $this->response(200,"成功");
            }
            $this->response(201,"失败");
        }

        return $this->fetch('add_rules');
        
    }

    /**
     * 权限规则列表
     */
    public function ruleList()
    {   
        $a = new  AccessModel();
        $lst = $a->ruleList();
        //获取当前登录用户权限,输出到页面
        // $login_access = Session::get('userinfo')['access'];
        $login_access = $this->getLoginAccess(); 
        // dump($login_access);die;
        $this->assign('access',$login_access);
        $this->assign('list',$lst);
        return $this->fetch('rule_list');
    }

    /**
     * 获取单个规则信息
     */
    public function getRuleInfo()
    {   
        if(Request::isGet()){
            $id = Request::get()['id'];
            $a = new AccessModel();
            $info = $a->getRuleInfo($id);
            if($info){
                $this->response(200,"成功",$info);
            }
            $this->response(201,"请求数据失败");

        }
    }
     /**
      * 编辑权限规则
      */
    public function editRule(){
        if(Request::isPost()){
            $data = Request::post();
            $data['update_time'] = time();
            $a = new AccessModel();
            $res = $a->editRule($data);
            if($res){
                $this->response(200,'修改成功');
            }
            $this->response(210, "修改失败，请确认是否有信息被修改");
        }
    }
     /**
      * 删除权限规则
      */
    
      public function delRule()
      {
          if(Request::isDelete()){
              $id = Request::delete()['id'];
              $a = new AccessModel;
              $res = $a->delRule($id);
              if($res){
                  $this->response(200,"删除成功");
              }
              $this->response(201,"删除失败");
          }
      }
}