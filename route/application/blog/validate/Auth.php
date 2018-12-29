<?php

namespace app\blog\validate;

use think\Validate;

class Auth extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */	
	protected $rule = [
        'username|用户名' => 'require|max:20|token',
        'email|邮箱' => 'require|email|max:35',
        'password|密码' => 'require|min:6|max:20',
        'password_confirm|确认密码' => 'require|confirm:password|min:6',
        'vercode|验证码' => 'require|captcha',
        'phone|电话' => 'number|max:13',
        'sex|性别' => 'require|max:8',
        'province|省份' => 'require|max:8',
        'city|城市' => 'require|max:8',
    ];
    
    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */	
    protected $message = [
        'username.token' => '令牌过期，请刷新页面',
        'vercode.captcha' => '验证码错误，点击刷新验证码。'
    ];

    /**
     * 应用场景：
     */
    protected $scene = [
        'login' => ['username','password','vercode'],
    ];

    /**
     * 单独定义场景：注册
     */
    public function sceneRegister()
    {
        return $this->only(['username','email','password','password_confirm','vercode'])->append([
                'username'=>'uniqueUsername',
                'email'=>'uniqueEmail',
            ]);
    }

    public function sceneUpdateform()
    {
        return $this->only(['phone','sex','province','city']);
    }

    /**
     * 检测注册用户名是否唯一
     * @param  $value
     * @param   $rule
     */
    public function uniqueUsername($value,$rule,$data=[])
    {
        if(!empty(model('AppMember')->where(['username'=>$value,'is_delete'=>0])->find())) return '该用户名已经存在，请尝试其他用户名！';
        return true;
    }

    /**
     * 检测注册用户名是否唯一
     * @param  $value
     * @param   $rule
     */
    public function uniqueEmail($value,$rule,$data=[])
    {
        if(!empty(model('AppMember')->where(['email'=>$value,'is_delete'=>0])->find())) return '该邮箱已经被注册，请尝试其他邮箱！';
        return true;
    }


}
