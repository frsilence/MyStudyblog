<?php
namespace app\admin\controller;

use app\common\controller\Adminbasic;
use app\admin\controller\Blog;

class Index extends Adminbasic
{
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
     * 管理界面/Blog管理/分类管理 页面
     * @return [type] [description]
     */
    public function getBlogCategory()
    {
    	return $this->fetch('category_manage');
    }
    /**
     * 管理界面/Blog管理/分类管理/分类编辑页面
     * @param $id 分类id
     */
    public function getBlogCategoryEdit($id)
    {
        $category = model('blog/ArticleCategory')->where('id',$id)->find();
        if(empty($category)) return '未找到该文章分类，请刷新该页面再试！';
        return $this->fetch('category_manageedit',['category'=>$category]);
    }
}
