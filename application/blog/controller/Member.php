<?php

namespace app\blog\controller;

use think\Controller;
use think\Request;
use app\common\controller\Appbasic;

class Member extends Appbasic
{
    /**
     * 检测用户登录中间件
     * @var [type]
     */
    protected $middleware = [
        'BlogAuth' => [
            'only' => ['updateInfoForm','updateUserimage','updateUserimage','getMemberSelfLoginRecord']
        ]

    ];

    /**
     * 显示会员个人主页
     *@param  $id 会员id
     * @return \think\Response
     */
    public function readMember($id)
    {
        if(empty(model('AppMember')->where(['id'=>$id,'status'=>0,'is_delete'=>0])->find())) return $this->fetch('public\404',['title'=>'404Page',
            'article_category'=>$this->article_category]);
        //访问本人
        if(session('?member') && session('member.id') == $id){
            $member = model('AppMember')->getMyMemberInfo();
            //return json($member);
            $data =[
            'title'=>'个人中心',
            'article_category'=>$this->article_category,
            'member_info'=>$member,
            ];
            return $this->fetch('my_index',$data);
        }
        //访问他人
        $member = model('AppMember')->getAppMemberInfoById($id);
        $data = [
            'title'=>'用户信息',
            'article_category'=>$this->article_category,
            'member_info'=>$member,
        ];
        return $this->fetch('member_index',$data);
        
    }

    /**
     * 会员信息更新表单
     * @param int $id 用户id
     */
    public function updateInfoForm($id)
    {
        if(empty(model('AppMember')->where(['id'=>$id,'status'=>0,'is_delete'=>0])->find())) return json(['code'=>1,'msg'=>'暂无此人','token'=>request()->token()]);
        if(session('?member') && session('member.id') == $id){
            $result = $this->validate(request()->post(),'app\blog\validate\Auth.updateform');
            if(true !== $result) return json(['code'=>1,'msg'=>$result,'token'=>request()->token()]);
            //return json($member);
            $updateForm = model('AppMember')->updateInfoForm($id,request()->post());
            if($updateForm['code']==0){
                return json(['code'=>0,'msg'=>'更新成功','token'=>request()->token()]);
            }else{
                return json(['code'=>1,'msg'=>'更新失败','token'=>request()->token()]);
            }

            return $this->fetch('update_memberinfo',$data);
        }
        return json(['code'=>1,'msg'=>'非本人操作']);
        
    }

    /**
     * 更新会员头像
     * @param int $id 被更新用户id
     * @return json 执行提示
     */
    public function updateUserimage(Request $request,$id)
    {
        if(empty(model('AppMember')->where(['id'=>$id,'status'=>0,'is_delete'=>0])->find())) return json(['code'=>1,'msg'=>'暂无此人','token'=>request()->token()]);
        if(session('?member') && session('member.id') == $id){
            if($request->has('user_imageurl')){
                $filename = '../public'.$request->post('user_imageurl');
                if(file_exists($filename)){
                    return json(model('AppMember')->updateUserimage($id,$request->post('user_imageurl')));
                }
            }
            return json(['code'=>1,'msg'=>'头像图片不存在，无法设置']);
        }
        return json(['code'=>1,'msg'=>'非本人操作']);
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
     * 获取会员所有评论
     * @param   $id 用户id
     */
    public function getMemberCommentList(Request $request,$id)
    {
        $MemberCommentList = model('ArticleComment')->getMemberCommentList($request,$id);
        return json($MemberCommentList);
    }

    /**
     * 获取会员收藏文章列表
     * @param int $id 用户id
     */
    public function getMemberCollectArticleList($id)
    {
        if(session('?member.id') && $id==session('member.id')){
            $MemberCollectArticleList = model('ArticleMember')->getMemberCollectArticleList($id);
        }else{
            $MemberCollectArticleList = ['code'=>1,'未登录或者非法请求'];
        }
        return json($MemberCollectArticleList);
    }

    /**
     * 获取当前会话会员的登录记录
     * 
     */
    public function getMemberSelfLoginRecord()
    {
        $MemberSelfLoginRecord = model('AppMemberLoginRecord')->getMemberSelfLoginRecord();
        return json($MemberSelfLoginRecord);
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
