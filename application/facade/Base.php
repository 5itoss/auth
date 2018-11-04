<?php
namespace app\facade;

use think\Facade;

class Base extends Facade
{
    protected static function getFacadeClass()
    {
    	return 'app\common\controller\Base';
    }
}