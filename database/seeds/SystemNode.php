<?php

use think\migration\Seeder;

class SystemNode extends Seeder
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     */
    public function run()
    {
        $data = [
            [
                'node' => 'admin',
                'node_name'=>'后台管理模块',
                'type'=>1,
                'is_RBAC'=>1,
                'create_by'=>0,
            ],
            [
                'node' => 'admin/auth',
                'node_name'=>'管理员设置',
                'type'=>2,
                'is_RBAC'=>1,
                'create_by'=>0,
            ],
            [
                'node' => 'admin/blog',
                'node_name'=>'Blog管理',
                'type'=>2,
                'is_RBAC'=>1,
                'create_by'=>0,
            ],
            [
                'node' => 'admin/blog/getBlogStatisticsInformation',
                'node_name'=>'Blog管理/获取系统信息',
                'type'=>3,
                'is_RBAC'=>1,
                'create_by'=>0,
            ],
            [
                'node' => 'admin/blog/getCategoryList',
                'node_name'=>'Blog管理/获取文章分类列表',
                'type'=>3,
                'is_RBAC'=>1,
                'create_by'=>0,
            ],

        ];
        $this->table('systemadmin_node')->insert($data)->save();
    }
}