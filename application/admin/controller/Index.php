<?php
namespace app\admin\controller;

use app\common\controller\Adminbasic;
use app\admin\controller\Blog;

class Index extends Adminbasic
{
    public function index()
    {
        return $this->fetch('admin_index');
    }
    public function welcome()
    {
    	$server_info = [
    		'server_os' => php_uname(),
    		'server_ip' => GetHostByName($_SERVER['SERVER_NAME']),
    		'server_port' => $_SERVER['SERVER_PORT'],
            'server_name' => $_SERVER,
    	];
        $blog = new Blog();
        return json($server_info);
    	return $this->fetch('welcome',['BlogStatisticsInformation'=>$blog->getBlogStatisticsInformation(),'ServerInfo'=>$server_info]);
    }
    public function sys()
    {
    	return '系统类型版本：'.php_uname();
    }
}
