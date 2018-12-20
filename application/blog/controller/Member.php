<?php

namespace app\blog\controller;

use think\Controller;
use think\Request;
use app\common\controller\Appbasic;

class Member extends Appbasic
{
    /**
     * 显示会员个人主页
     *@param  $id 会员id
     * @return \think\Response
     */
    public function readMember($id)
    {
        if(empty(model('AppMember')->where(['id'=>$id,'status'=>0,'is_delete'=>0])->find())) return $this->fetch('public\404',['title'=>'404Page',
            'article_category'=>$this->article_category]);
        if(session('?member') && session('member.id') == $id){
            $member = model('AppMember')->getMyMemberInfo();
            //return json($member);
            $data =[
            'title'=>'首页',
            'article_category'=>$this->article_category,
            'member_info'=>$member,
            ];
            return $this->fetch('my_index',$data);
        }
        return 'you';
    }

    /**
     * 获取会员所有文章
     * @param  init  $id [description]
     * @return [type]     [description]
     */
    public function getMemberArticleList($id)
    {
        $MemberArticleList = model('Article')->getMemberArticleList($id);
        return json($MemberArticleList);
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        //
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        //
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
        //
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        //
    }
}