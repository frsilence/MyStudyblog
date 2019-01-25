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
    ];
}
