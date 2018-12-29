<?php
namespace app\index\controller;
use think\Controller;

class Index extends Controller
{
    public function index()
    {
        return '<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} a{color:#2E5CD5;cursor: pointer;text-decoration: none} a:hover{text-decoration:underline; } body{ background: #fff; font-family: "Century Gothic","Microsoft yahei"; color: #333;font-size:18px;} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.6em; font-size: 42px }</style><div style="padding: 24px 48px;"> <h1>:) </h1><p> ThinkPHP V5.1<br/><span style="font-size:30px">12载初心不改（2006-2018） - 你值得信赖的PHP框架</span></p></div><script type="text/javascript" src="https://tajs.qq.com/stats?sId=64890268" charset="UTF-8"></script><script type="text/javascript" src="https://e.topthink.com/Public/static/client.js"></script><think id="eab4b9f840753f8e7"></think>';
    }

	//产生文章分类
    public function create_category()
    {
    	$categorys = [
    		['category_title'=>'Linux运维','category_image'=>'\static\image\test.PNG','category_content'=>'Linux运维相关博客'],
    		['category_title'=>'PHP开发','category_image'=>'\static\image\test.PNG','category_content'=>'PHP开发相关博客'],
    		['category_title'=>'Pyhthon开发','category_image'=>'\static\image\test.PNG','category_content'=>'Pyhthon开发相关博客'],
    		['category_title'=>'Mysql数据库','category_image'=>'\static\image\test.PNG','category_content'=>'Mysql数据库相关博客'],
    		['category_title'=>'JavaScript技术','category_image'=>'\static\image\test.PNG','category_content'=>'JavaScript技术相关博客'],
    		['category_title'=>'Html技术','category_image'=>'\static\image\test.PNG','category_content'=>'Html技术相关博客'],
    		['category_title'=>'Vue技术','category_image'=>'\static\image\test.PNG','category_content'=>'Vue技术相关博客']

    	];
    	if(empty(model('app\blog\model\ArticleCategory')->select()->toArray())){
    		model('app\blog\model\ArticleCategory')->saveAll($categorys);
    		$this->success('文章分类保存成功',url('blog/index/index'));
    	}
    	else{
    		$this->error('文章分类已存在',url('blog/index/index'));
    	}
        
    }
}
