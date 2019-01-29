<?php

use think\migration\Migrator;
use think\migration\db\Column;

class AdminRole extends Migrator
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
        //角色信息
        $table = $this->table('admin_role');
        $table->addColumn('rolename','string',['limit'=>20,'comment'=>'角色名'])
        ->addColumn('status','integer',['default'=>0,'comment'=>'状态'])
        ->addColumn('remark','string',['limit'=>30,'comment'=>'备注'])
        ->addColumn('create_by','integer',['comment'=>'创建者'])
        ->addTimestamps()
        ->addIndex('rolename',['unique'=>true])
        ->create();
        
        //管理员用户和角色对应表
        $table = $this->table('admin_user_role')
        ->addColumn('adminuser_id','integer',['comment'=>'管理员用户ID'])
        ->addColumn('role_id','integer',['comment'=>'角色id'])
        ->addTimestamps()
        ->create();
    }
}
