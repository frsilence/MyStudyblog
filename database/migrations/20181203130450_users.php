<?php

use think\migration\Migrator;
use think\migration\db\Column;

class Users extends Migrator
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
        
        //会员信息
        $table = $this->table('app_member');
        $table->addColumn('member_pid','string',['limit'=>20,'comment'=>'用户唯一ID'])
        ->addColumn('username','string',['limit'=>30,'comment'=>'用户名'])
        ->addColumn('password','string',['limit'=>20,'comment'=>'密码'])
        ->addColumn('userimage','string',['limit'=>65,'default'=>'/static/image/app/user_image.jpg','comment'=>'用户头像'])
        ->addColumn('phone','string',['limit'=>15,'default'=>NULL,'comment'=>'手机号'])
        ->addColumn('email','string',['limit'=>30,'comment'=>'用户邮箱'])
        ->addColumn('province','string',['limit'=>30,'comment'=>'省份'])
        ->addColumn('city','string',['limit'=>30,'comment'=>'城市'])
        ->addColumn('sex','integer',['default'=>0,'comment'=>'性别，0=男，1=女'])
        ->addColumn('status','integer',['default'=>0,'comment'=>'状态'])
        ->addColumn('is_delete','integer',['default'=>0,'comment'=>'是否删除'])
        ->addTimestamps()
        ->addIndex('member_pid',['unique'=>true])
        ->addIndex('username',['unique'=>true])
        ->addIndex('email',['unique'=>true])
        ->addIndex('phone',['unique'=>true])
        ->create();
        //会员fans
        $table = $this->table('app_member_follow')
        ->addColumn('member_id','integer',['comment'=>'被关注者用户ID'])
        ->addColumn('follower_id','integer',['comment'=>'粉丝ID'])
        ->addColumn('is_delete','integer',['default'=>0,'comment'=>'是否已删除'])
        ->addTimestamps()
        ->addIndex(['member_id','follower_id'],['unique'=>true])
        ->create();
        //会员登录退出记录
        $table = $this->table('app_member_login_record')
        ->addColumn('member_id','integer',['comment'=>'用户ID'])
        ->addColumn('type','integer',['default'=>0,'comment'=>'0=登录，1退出'])
        ->addColumn('login_ip','string',['limit'=>20,'default'=>NULL,'comment'=>'登录IP'])
        ->addColumn('login_area','string',['limit'=>30,'default'=>NULL,'comment'=>'登陆地址'])
        ->addColumn('remark','string',['limit'=>30,'default'=>NULL,'comment'=>'备注'])
        ->addTimestamps()
        ->create();

    }
}
