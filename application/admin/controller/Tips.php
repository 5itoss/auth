<?php
namespace app\admin\controller;

class Tips extends \app\common\controller\Base
{
    public function tips()
    {   
        $this->response(403,"对不起，您当前无操作权限，请联系管理员为您开通！");
    }
}