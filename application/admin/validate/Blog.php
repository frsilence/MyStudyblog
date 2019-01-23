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
        'category_status|文章分类状态'=>'require|number|in:0,1',
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
    ];
}
