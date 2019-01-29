<?php

use think\migration\Migrator;
use think\migration\db\Column;

class SystemNode extends Migrator
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
        //系统节点信息
        $table = $this->table('systemadmin_node');
        $table->addColumn('node','string',['limit'=>40,'comment'=>'节点信息'])
        ->addColumn('node_name','string',['limit'=>20,'comment'=>'节点名称'])
        ->addColumn('type','integer',['comment'=>'节点层级(1：模块，2：控制器，3：节点)'])
        ->addColumn('is_RBAC','integer',['default'=>1,'comment'=>'是否启用RBAC权限控制(基于角色的权限访问控制)'])
        ->addColumn('create_by','integer',['comment'=>'创建者'])
        ->addTimestamps()
        ->addIndex('node',['unique'=>true])
        ->create();
        
        //系统节点和角色对应表
        $table = $this->table('role_systemadminnode')
        ->addColumn('role_id','integer',['comment'=>'管理员角色'])
        ->addColumn('node_id','integer',['comment'=>'系统节点id'])
        ->addTimestamps()
        ->create();
    }
}
