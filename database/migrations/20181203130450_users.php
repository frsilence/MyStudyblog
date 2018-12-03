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
        
        
        $table = $this->table('app_member');
        $table->addColumn('memberid','string','')
        ->addColumn('username','string',['limit'=>30,'comment'=>'用户名'])
        ->addColumn('password','string',['limit'=>20,'comment'=>'密码'])
        ->addColumn('userimage','string',['limit'=>30,'/static/image/app/user_image.jpg','用户头像'])
        ->addColumn('phone','string',['limit'=>15,'default'=>NULL,'comment'=>'手机号'])
        ->addColumn('email','string',['limit'=>30,'comment'=>'用户邮箱'])
        ->addColumn('province','string',['limit'=>30,'comment'=>'省份'])
        ->addColumn('city','string',['limit'=>30,'comment'=>'城市'])
        ->addColumn('sex','integer',['default'=>0,'comment'=>'性别，0=男，1=女'])
        ->addColumn('status','integer',['default'=>0,'comment'=>'状态'])
        ->addColumn('is_delete','integer',['default'=>0,'comment'=>'是否删除'])
        ->addTimestamps()
        ->addIndex(['username','email'],['unique'=>true])
        ->create();
    }
}
