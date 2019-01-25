<?php

use think\migration\Migrator;
use think\migration\db\Column;

class AdminUsers extends Migrator
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
        $table = $this->table('admin_user');
        $table->addColumn('username','string',['limit'=>30,'comment'=>'用户名'])
        ->addColumn('password','string',['limit'=>65,'comment'=>'密码'])
        ->addColumn('userimage','string',['limit'=>65,'default'=>'/static/image/app/user_image.jpg','comment'=>'用户头像'])
        ->addColumn('phone','string',['limit'=>15,'default'=>'','comment'=>'手机号'])
        ->addColumn('email','string',['limit'=>30,'comment'=>'用户邮箱'])
        ->addColumn('province','string',['limit'=>30,'default'=>'','comment'=>'省份'])
        ->addColumn('city','string',['limit'=>30,'default'=>'','comment'=>'城市'])
        ->addColumn('sex','integer',['default'=>0,'comment'=>'性别，0=男，1=女'])
        ->addColumn('status','integer',['default'=>0,'comment'=>'状态'])
        ->addColumn('is_delete','integer',['default'=>0,'comment'=>'是否删除'])
        ->addTimestamps()
        ->addIndex('username',['unique'=>true])
        ->addIndex('email',['unique'=>true])
        ->create();
        
        //管理员登录退出记录
        $table = $this->table('admin_user_login_record')
        ->addColumn('admin_userid','integer',['comment'=>'管理员用户ID'])
        ->addColumn('type','integer',['default'=>0,'comment'=>'0=登录，1退出'])
        ->addColumn('login_ip','string',['limit'=>20,'default'=>'','comment'=>'登录IP'])
        ->addColumn('login_area','string',['limit'=>30,'default'=>'','comment'=>'登录地址'])
        ->addColumn('remark','string',['limit'=>30,'default'=>'','comment'=>'备注'])
        ->addTimestamps()
        ->create();

    }
}
