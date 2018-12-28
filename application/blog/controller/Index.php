<?php
namespace app\blog\controller;
use think\Controller;
use app\common\controller\Appbasic;
class Index extends Appbasic
{
    protected $middleware = ['BlogAuth'];
    public function index()
    {
    	$data =[
    		'title'=>'首页',
    		'article_category'=>$this->article_category,
    		'latest_article'=>model('Article')->getLatestArticle(),
    		];
    	//return json($data['latest_article']);
        return $this->fetch('',$data);
    }
}
