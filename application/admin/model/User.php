<?php 
namespace app\admin\model;
use think\facade\Session;
use think\Db;

class  User  extends  \think\Model
{
	
	public function login($data){

		$res = $this->where('account',$data['account'])->where('password',$data['password'])->find();
		if($res){
			$data['login_num'] = $res['login_num'] + 1;
			$this->where('id',$res['id'])->update($data);
			$res=$this->getUserInfo($res['id']);
		}
		return $res;
	}

	public function lst()
	{

		$sql  = "select think_user.id,think_user.create_time,think_user.is_admin,think_user.email,think_user.account,think_user.username,think_user.status,think_user.login_time,think_user.login_ip,think_user.login_num,think_user_group.title from think_user ";
		$sql .= "left join think_user_group_access on think_user_group_access.uid=think_user.id "; 
		$sql .= "left join think_user_group on think_user_group_access.group_id=think_user_group.id;"; 
		
		$res = $this->query($sql);
		return $res;
	}

	public function getGoups()
	{	
		$sql = "select id,title,status from think_user_group where status = 0 and id != 1";
		$res = $this->query($sql);
		return $res;
	}

	public function getUserInfo($id)
	{	
		$sql = "select think_user.id,think_user.is_admin,think_user.username,think_user.account,think_user.email,think_user.status,think_user_group_access.group_id ";
		$sql .= "from think_user left join think_user_group_access on think_user_group_access.uid=think_user.id where id=". $id .";";
		$res = $this->query($sql);
		return $res[0];
	}
	
	public function editUser($data)
	{	
		// dump($data['group_id']);die;
		$_res = 0;
		
		
		if(!isset($data['group_id'])){
			//当前没有对group_id进行操作，不做任何更改
			
		}else if($data['group_id'] == 0){
			//如果group_id为0，则当前没有设置权限组ID，删掉user_group_access表中的对应uid权限id
			$_res = Db::name('user_group_access')
						->where('uid',$data['id'])
						->delete();

			unset($data['group_id']);

		}else if($data['group_id'] != 0){
			//如果group_id不为0，则当前已经设置了权限组id
			$group_id = $data['group_id'];
			unset($data['group_id']);
			//查询未更改之前的权限组id
			$old_group_id =  Db::name('user_group_access')
								->where('uid',$data['id'])
								->select();
			if(!$old_group_id){
				//如果未查询到数据，则是添加权限组
				$_res = Db::name('user_group_access')
						->insert(['group_id'=>$group_id,'uid'=>$data['id']]);

			}else if($group_id != $old_group_id['group_id']){
				//如果查询到的权限组id 和 传递过来的权限组id不同，则为更改
				$_res = Db::name('user_group_access')
						->where('uid',$data['id'])
						->update(['group_id'=>$group_id]);
			}
			unset($data['group_id']);
		}

		
		$res = $this->where('id',$data['id'])->update($data);
		if(!$_res && !$res){
			return false;
		}
		return true;
	}

	public function add($data){
		$u_gid = $data['id'];
		unset($data['id']);
		$res = $this->insertGetId($data);
		if($res){
			if($u_gid){
				$access_data = ['uid'=> $res,'group_id'=>$u_gid];
				$_res = Db::name('user_group_access')->insert($access_data);
				if($_res){
					return 1;
				}else{

					return 2;
				}
			}
			return 3;
		}
		return 4;
	}

	public function del($id){
		$res = $this->where('id',$id)->delete($id);
		if($res){
			$this->query("delete from think_user_group_access where uid=".$id);
			return true;
		}
		return $res;
	}

	/**
	 * 获取当前登录用户的所有权限 以及全部权限
	 */
	public function getLoginUserAccess(){

		$id = Session::get('uid');
		$res = array();
		if($id == 1){
			$all_rule = Db::name('auth_rule')->select();
		
			foreach ($all_rule as $key => $value) {
				$res[] = $value['url'];
			}

		}else{
			$user_access = Db::view('user_group_access','group_id')
					->view('user_group','rules','user_group_access.group_id = user_group.id', 'LEFT')
					->where('user_group_access.uid',$id)
					->find();

			$rules = explode(',',$user_access['rules']);
			foreach($rules as $v){
				$rule = Db::name('auth_rule')->where('id',$v)->find();
				$res[] = $rule['url'];
			}

		}

		return $res;
	}
}