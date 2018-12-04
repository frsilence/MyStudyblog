<?php

use think\migration\Migrator;
use think\migration\db\Column;

class Blogs extends Migrator
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        //文章表单
        $table = $this->table('article')
        ->addColumn('member_id','integer',['comment'=>'文章用户'])
        ->addColumn('category_id','integer',['comment'=>'所属文章分类ID'])
        ->addColumn('title','string',['limit'=>100,'comment'=>'文章标题'])
        ->addColumn('content','text',['comment'=>'文章内容'])
        ->addColumn('recommend','integer',['comment'=>'推荐级别'])
        ->addColumn('praise_num','integer',['comment'=>'点赞数量'])
        ->addColumn('click_num','integer',['comment'=>'点击量'])
        ->addColumn('collect_num','integer',['comment'=>'收藏量'])
        ->addColumn('is_delete','integer',['default'=>0,'comment'=>'是否删除'])
        ->addColumn('is_open','integer',['default'=>1,'comment'=>'是否公开'])
        ->addColumn('update_userid','integer',['comment'=>'更新用户'])
        ->addTimestamps()
        ->create();

        //文章分类
        $table = $this->table('article_category')
        ->addColumn('category_title','string',['limit'=>20,'comment'=>'分类名称'])
        ->addColumn('category_image','string',['limit'=>65,'comment'=>'分类图片'])
        ->addColumn('category_content','string',['limit'=>250,'comment'=>'分类描述'])
        ->addColumn('status','integer',['default'=>0,'comment'=>'状态'])
        ->addIndex(['category_title'],['unique'=>true])
        ->addTimestamps()
        ->create();

        //文章评论
        $table = $this->table('article_comment')
        ->addColumn('member_id','integer',['comment'=>'用户ID'])
        ->addColumn('article_id','integer',['comment'=>'文章ID'])
        ->addColumn('content','string',['limit'=>350,'comment'=>'评论内容'])
        ->addColumn('is_delete','integer',['default'=>0,'comment'=>'是否删除'])
        ->addTimestamps()
        ->create();

        //文章关注者表单
        $table = $this->table('article_member')
        ->addColumn('member_id','integer',['comment'=>'用户ID'])
        ->addColumn('article_id','integer',['comment'=>'文章ID'])
        ->addColumn('is_delete','integer',['default'=>0,'comment'=>'是否删除'])
        ->addIndex(['member_id','article_id'],['unique'=>true])
        ->addTimestamps()
        ->create();


        //文章标签
        $table = $this->table('blog_tag')
        ->addColumn('name','string',['limit'=>10,'comment'=>'标签名称'])
        ->addColumn('status','integer',['default'=>0,'comment'=>'状态'])
        ->addTimestamps()
        ->addIndex(['name'],['unique'=>true])
        ->create();

        //文章与标签对应表
        $table = $this->table('article_tag')
        ->addColumn('article_id','integer',['comment'=>'文章ID'])
        ->addColumn('tag_id','integer',['comment'=>'标签ID'])
        ->addIndex(['article_id','tag_id'],['unique'=>true])
        ->addTimestamps()
        ->create();

        //应用设置
        $table = $this->table('blog_config')
        ->addColumn('name','string',['limit'=>20,'comment'=>'变量名称'])
        ->addColumn('group','string',['limit'=>20,'comment'=>'分类'])
        ->addColumn('value','string',['limit'=>60,'comment'=>'值'])
        ->addColumn('remark','string',['limit'=>50,'comment'=>'配置项描述'])
        ->addTimestamps()
        ->create();

        //应用公告表
        $table = $this->table('blog_announcement')
        ->addColumn('title','string',['limit'=>60,'comment'=>'公告标题'])
        ->addColumn('content','string',['limit'=>300,'comment'=>'公告内容'])
        ->addColumn('url','string',['limit'=>65,'comment'=>'公告url'])
        ->addColumn('status','integer',['default'=>0,'comment'=>'状态'])
        ->addTimestamps()
        ->create();

        //文章搜索记录及数量
        $table = $this->table('blog_search')
        ->addColumn('word','string',['limit'=>70,'comment'=>'搜索关键词'])
        ->addColumn('search_num','integer',['default'=>1,'comment'=>'搜索次数'])
        ->addIndex(['word'],['unique'=>true])
        ->addTimestamps()
        ->create();

        //首页轮播图
        $table = $this->table('blog_slider')
        ->addColumn('title','string',['limit'=>30,'comment'=>'轮播标题'])
        ->addColumn('image','string',['limit'=>65,'comment'=>'图片地址'])
        ->addColumn('url','string',['limit'=>65,'comment'=>'链接url'])
        ->addColumn('status','integer',['default'=>0,'comment'=>'状态'])
        ->addTimestamps()
        ->create();

        //友情站点链接
        $table = $this->table('website_link')
        ->addColumn('name','string',['limit'=>20,'comment'=>'站点名称'])
        ->addColumn('logo','string',['limit'=>65,'comment'=>'站点logo'])
        ->addColumn('url','string',['limit'=>65,'comment'=>'链接url'])
        ->addColumn('remark','string',['limit'=>100,'comment'=>'备注信息'])
        ->addColumn('status','integer',['default'=>0,'comment'=>'状态'])
        ->addTimestamps()
        ->create();
        

    }
}
