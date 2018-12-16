<?php
namespace app\blog\controller;
use think\Controller;
use app\common\controller\Appbasic;
class Index extends Appbasic
{
    public function index()
    {
        return $this->fetch('',['title'=>'首页','article_category'=>$this->article_category]);
    }
}
