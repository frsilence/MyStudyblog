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
        'category_searchtitle|搜索文章分类标题'=>'max:30',
        'category_title|文章分类标题'=>'require|unique:article_category|max:15',
        'category_content|文章分类简介'=>'require|max:200',
        'article_id|文章ID'=>'require|number',
        'articleid_list|文章ID集合'=>'require',
        'article_status|文章状态'=>'require|number|in:0,1',
        'article_createtimemin|文章创建时间min'=>'date',
        'article_createtimemax|文章创建时间max'=>'date',
        'article_searchtitle|搜索文章标题'=>'max:30',
        'article_user|文章作者搜索名称'=>'max:20',
        'article_searchcategory|文章分类搜索值'=>'number',
        'article_searchstatus|文章搜索状态'=>'number|in:0,1',
        'article_title|文章标题'=>'require|max:100',
        'article_tag|文章标签'=>'require',
        'article_category|文章分类'=>'require|number',
        'article_content|文章内容'=>'require|max:30000',
        'article_updateid|更新文章id'=>'require|number',
        'username|管理员用户名' => 'require|max:20',
        'newusername|新增管理员用户名' => 'require|unique:admin_user,username|max:20',
        'password|密码' => 'require|min:6|max:20',
        'password_confirm|确认密码' => 'require|confirm:password|min:6',
        'email|邮箱' => 'require|email|unique:admin_user|max:35',
        'adminuser_searchname|管理员搜索名称'=>'max:30',
        'limit|分页每页限制数'=>'number',
        'page|分页当前页码'=>'number',
        'adminuser_createtimemin|管理员创建时间min'=>'date',
        'adminuser_createtimemax|管理员创建时间max'=>'date',
        'adminuser_searchname|搜索管理员名称'=>'max:30',
        'adminrole_createtimemin|角色创建时间min'=>'date',
        'adminrole_createtimemax|角色创建时间max'=>'date',
        'adminrole_searchname|搜索角色名称'=>'max:30',
        'adminrole_id|角色ID'=>'require|number',
        'adminrole_status|角色状态'=>'require|number|in:0,1',
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
        'category_search'=>['category_createtimemin','category_createtimemax','categort_searchtitle'],
        'category_update'=>['category_id','category_title','category_content'],
        'category_add'=>['category_title','category_content'],
        'category_delete'=>['categoryid_list'],
        'article_search'=>['article_createtimemin','article_createtimemax','article_searchtitle','article_user','article_searchcategory','article_searchstatus'],
        'article_statuschange'=>['article_id','article_status'],
        'article_delete'=>['articleid_list'],
        'article_update'=>['article_title','article_tag','article_category','article_content','article_updateid'],
        'article_add'=>['article_title','article_tag','article_category','article_content'],
        'admin_authlogin'=>['username','password'],
        'adminuser_add'=>['newusername','email','adminrole_id','password','password_confirm'],
        'adminuser_search'=>['limit','page','adminuser_createtimemin','adminuser_createtimemax','adminuser_searchname'],
        'login'=>['username','password','vercode'],
        'adminrole_search'=>['limit','page','adminrole_searchname','adminrole_createtimemin','adminrole_createtimemax','adminrole_searchname'],
        'adminrole_statuschange'=>['adminrole_id','adminrole_status'],
    ];
}
