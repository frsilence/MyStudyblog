<?php
namespace app\admin\controller;

use app\common\controller\Adminbasic;
use app\admin\controller\Blog;

class Index extends Adminbasic
{
    /**
     * 检测用户登录中间件
     */
    protected $middleware = ['AdminAuth'];
    /**
     * 获取管理界面
     * @return [type] [description]
     */
    public function index()
    {
        return $this->fetch('admin_index');
    }
    /**
     * 管理界面/Blog管理/我的桌面 页面
     * @return [type] [description]
     */
    public function welcome()
    {
    	$server_info = [
    		'server_os' => php_uname(),
    		'server_port' => $_SERVER['SERVER_PORT'],
            'SERVER_NAME' => $_SERVER['SERVER_NAME'],
            'HTTP_HOST' => $_SERVER['HTTP_HOST'],
            'DOCUMENT_ROOT' => $_SERVER['DOCUMENT_ROOT'],
            'SERVER_SOFTWARE' => $_SERVER['SERVER_SOFTWARE'],

    	];
        $blog = new Blog();
        //return json($server_info);
    	return $this->fetch('welcome',['BlogStatisticsInformation'=>$blog->getBlogStatisticsInformation(),'ServerInfo'=>$server_info]);
    }

    /**
     * 权限测试
     */
    public function isnode()
    {
        $user = model('AdminUser')->where('id',2)->find();
        if($user->checknode('admin/blog/getCategoryList')) return 'ok';
        return 'no';
    }
    

    
}
