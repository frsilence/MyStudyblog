<?php

namespace app\admin\validate;

use think\Validate;

class Blog extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */	
	protected $rule = [
        'category_id|文章分类ID'=>'require|number',
        'categoryid_list|文章分类ID集合'=>'require',
        'category_status|文章分类状态'=>'require|number|in:0,1',
        'category_createtimemin|文章分类创建时间min'=>'date',
        'category_createtimemax|文章分类创建时间max'=>'date',
        'category_title|文章分类标题'=>'require|unique:article_category|max:15',
        'category_content|文章分类简介'=>'require|max:200',
        'username|管理员用户名' => 'require|unique:admin_user|max:20',
        'password|密码' => 'require|min:6|max:20',
        'password_confirm|确认密码' => 'require|confirm:password|min:6',
        'email|邮箱' => 'require|email|unique:admin_user|max:35',
        'adminuser_searchname|管理员搜索名称'=>'max:30',
        'limit|分页每页限制数'=>'number',
        'page|分页当前页码'=>'number',
        'adminuser_createtimemin|管理员创建时间min'=>'date',
        'adminuser_createtimemax|管理员创建时间max'=>'date',
        'vercode|验证码' => 'require|captcha',
    ];
    
    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */	
    protected $message = [];

    /**
     * 应用场景
     */
    protected $scene = [
        'category_statuschange'=>['category_id','category_status'],
        'category_search'=>['category_createtimemin','category_createtimemax'],
        'category_update'=>['category_id','category_title','category_content'],
        'category_add'=>['category_title','category_content'],
        'category_delete'=>['categoryid_list'],
        'admin_authlogin'=>['username','password'],
        'adminuser_add'=>['username','email','password','password_confirm'],
        'adminuser_search'=>['limit','page','adminuser_createtimemin','adminuser_createtimemax'],
        'login'=>['username','password','vercode'],
    ];
}
