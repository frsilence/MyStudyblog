<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use app\common\controller\Adminbasic;
class Auth extends Adminbasic
{

    /**
     *控制器初始化 
     */
    public function __construct()
    {
        parent::__construct();
        $action = $this->request->action();
        //已登录的会话访问注册和登录相关方法，强制跳转至首页
        if(!empty(session('adminuser')) && (($action == 'postlogin')||($action == 'login'))){
            return $this->redirect(url('master/index'));
        }

    }
    /**
     * 显示用户登录界面.
     *
     * @return \think\Response
     */
    public function login()
    {
        return $this->fetch('admin_login');
    }

    /**
     * 管理界面/管理设置/管理员设置/管理员列表
     */
    public function AdminuserManage()
    {
        return $this->fetch('adminuser_manage');
    }
    /**
     * 管理界面/管理设置/管理员设置/新增管理员
     */
    public function getaddAdminuser()
    {
        return $this->fetch('adminuser_manageadd');
    }
    /**
     * 管理界面/管理设置/角色设置
     */
    public function AdminroleManage()
    {
        return $this->fetch('adminrole_manage');
    }

    /**
     * 用户登录
     *@param  $ararry 登录信息
     * @return \think\Response
     */
    public function postlogin(Request $request)
    {
        //验证传入参数
        $result = $this->validate($request->post(),'app\admin\validate\Blog.login');
        if(true !== $result) return json(['code'=>1,'msg'=>$result]);
        //验证用户信息
        $adminuser_login = model('AdminUser')->login($request->post('username'),$request->post('password'));
        if($adminuser_login['code'] == 1) return json($adminuser_login);
        //登录成功记录时间
        $adminuser_login['adminuser']['login_time'] = time();
        //用户信息载入session
        session('adminuser',$adminuser_login['adminuser']);
        //记录登录日志
        $this->LoginLog(0);
        return json($adminuser_login);
        
    }

    /**
     * 新增管理员账号
     *
     * @return \think\Response
     */
    public function addAdminuser(Request $request)
    {
        //验证传入参数
        $posts = $request->post();
        $result = $this->validate($posts,'app\admin\validate\Blog.adminuser_add');
        if(true !== $result) return json(['code'=>1,'msg'=>$result]);
        //注册用户
        $role = model('AdminRole')->where(['id'=>$post['id'],'status'=>0])->find();
        if(empty($role)) return json(['code'=>1,'msg'=>'所指定的角色无法使用']);
        $member_register = model('AdminUser')->register($posts);
        if($member_register['code'] == 1) return json(['code'=>$member_register['code'],'msg'=>$member_register['msg'],'adminuser'=>$member_register['adminuser']]);
        //注册成功，并记录
        if($member_register['code'] == 0) 
            {
                return json(['code'=>$member_register['code'],'msg'=>$member_register['msg']]);
            }
    }

    /**
     * 获取管理员列表
     */
    public function getAdminuserList(Request $request)
    {
        //验证输入参数
        $post_info = $request->get();
        $result = $this->validate($post_info,'app\admin\validate\Blog.adminuser_search');
        if(true !== $result) return json(['code'=>1,'msg'=>$result]);
        $post_info['adminuser_createtimemin'] !='' ? $adminuser_createtimemin = $post_info['adminuser_createtimemin'] : $adminuser_createtimemin = '2018-01-01';
        $post_info['adminuser_createtimemax'] !='' ? $adminuser_createtimemax = $post_info['adminuser_createtimemax'] : $adminuser_createtimemax = date("Y-m-d",strtotime("1 day"));;
        if($post_info['adminuser_searchname'] !=''){
            $article_categorys = model('AdminUser')->where('category_title','LIKE',"%{$post_info['adminuser_searchname']}%")->whereTime('create_time','between',[$adminuser_createtimemin,$adminuser_createtimemax])->order('id','asc')->paginate(request()->param('limit'),false,['var_page' => 'page','query'=>request()->param()]);
        }else{
            $article_categorys = model('AdminUser')->whereTime('create_time','between',[$adminuser_createtimemin,$adminuser_createtimemax])->order('id','asc')->paginate(request()->param('limit'),false,['var_page' => 'page','query'=>request()->param()]);
        }
        //return json($article_categorys);   
        $data = [
            'code' =>0,
            'message' => "success",
            'count' => $article_categorys->toArray()['total'],
            'data' => $article_categorys->toArray()['data'],
        ];
        return json($data);
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
