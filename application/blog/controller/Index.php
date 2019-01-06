<?php
namespace app\blog\controller;
use think\Controller;
use app\common\controller\Appbasic;
class Index extends Appbasic
{
    public function index()
    {
    	$data =[
    		'title'=>'首页',
    		'article_category'=>$this->article_category,
    		'latest_article'=>model('Article')->getLatestArticle(),
    		];
    	//return json($data['latest_article']);
       sleep(5);
        return $this->fetch('',$data);
    }
}
