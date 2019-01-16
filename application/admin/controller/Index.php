<?php
namespace app\admin\controller;

use app\common\controller\Adminbasic;

class Index extends Adminbasic
{
    public function index()
    {
        return $this->fetch('admin_index');
    }
    public function welcome()
    {
    	$data = [
    		'server_os' => php_uname(),
    		'server_ip' => GetHostByName($_SERVER['SERVER_NAME']),
    		'server_port' => $_SERVER['SERVER_PORT'],
    	];
    	return $this->fetch('welcome',$data);
    }
    public function sys()
    {
    	return '系统类型版本：'.php_uname();
    }
}
