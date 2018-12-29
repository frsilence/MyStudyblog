<?php

namespace app\blog\validate;

use think\Validate;

class Article extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */	
	protected $rule = [
        'article_title|文章标题'=>'require|max:100|token',
        'article_tag|文章标签'=>'require',
        'article_category|文章分类'=>'require|number',
        'article_content|文章内容'=>'require|max:30000',
        'article_id|文章id'=>'require|number',
        'comment_content|评论内容'=>'require',
    ];
    
    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */	
    protected $message = [];

    //应用场景
    protected  $scene = [
        'addarticle' => ['article_title','article_tag','article_category','article_content'],
        'addcomment' => ['article_id','comment_content'],
    ];
}
