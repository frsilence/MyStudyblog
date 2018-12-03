<?php
namespace app\blog\controller;
use think\Controller;
class Index extends Controller
{
    public function index()
    {
        return $this->fetch('',['title'=>'首页']);
    }
}
