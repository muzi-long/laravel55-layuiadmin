<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //清空表
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        \Illuminate\Support\Facades\DB::table('model_has_permissions')->truncate();
        \Illuminate\Support\Facades\DB::table('model_has_roles')->truncate();
        \Illuminate\Support\Facades\DB::table('role_has_permissions')->truncate();
        \Illuminate\Support\Facades\DB::table('users')->truncate();
        \Illuminate\Support\Facades\DB::table('roles')->truncate();
        \Illuminate\Support\Facades\DB::table('permissions')->truncate();
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        //用户
        $user = \App\Models\User::create([
            'username' => 'root',
            'phone' => '18908221080',
            'nickname' => '超级管理员',
            'email' => 'root@dgg.net',
            'password' => '123456',
            'api_token' => hash('sha256', Str::random(60)),
        ]);

        //角色
        $role = \App\Models\Role::create([
            'name' => 'root',
            'display_name' => '超级管理员'
        ]);

        //权限
        $permissions = [
            [
                'name' => 'system',
                'display_name' => '系统管理',
                'route' => '',
                'icon' => 'layui-icon-set',
                'child' => [
                    [
                        'name' => 'system.user',
                        'display_name' => '用户管理',
                        'route' => 'admin.user',
                        'child' => [
                            ['name' => 'system.user.create', 'display_name' => '添加用户','route'=>'admin.user.create'],
                            ['name' => 'system.user.edit', 'display_name' => '编辑用户','route'=>'admin.user.edit'],
                            ['name' => 'system.user.destroy', 'display_name' => '删除用户','route'=>'admin.user.destroy'],
                            ['name' => 'system.user.role', 'display_name' => '分配角色','route'=>'admin.user.role'],
                            ['name' => 'system.user.permission', 'display_name' => '分配权限','route'=>'admin.user.permission'],
                        ]
                    ],
                    [
                        'name' => 'system.role',
                        'display_name' => '角色管理',
                        'route' => 'admin.role',
                        'child' => [
                            ['name' => 'system.role.create', 'display_name' => '添加角色','route'=>'admin.role.create'],
                            ['name' => 'system.role.edit', 'display_name' => '编辑角色','route'=>'admin.role.edit'],
                            ['name' => 'system.role.destroy', 'display_name' => '删除角色','route'=>'admin.role.destroy'],
                            ['name' => 'system.role.permission', 'display_name' => '分配权限','route'=>'admin.role.permission'],
                        ]
                    ],
                    [
                        'name' => 'system.permission',
                        'display_name' => '权限管理',
                        'route' => 'admin.permission',
                        'child' => [
                            ['name' => 'system.permission.create', 'display_name' => '添加权限','route'=>'admin.permission.create'],
                            ['name' => 'system.permission.edit', 'display_name' => '编辑权限','route'=>'admin.permission.edit'],
                            ['name' => 'system.permission.destroy', 'display_name' => '删除权限','route'=>'admin.permission.destroy'],
                        ]
                    ],
                    [
                        'name' => 'system.config_group',
                        'display_name' => '配置组',
                        'route' => 'admin.config_group',
                        'child' => [
                            ['name' => 'system.config_group.create', 'display_name' => '添加组','route'=>'admin.config_group.create'],
                            ['name' => 'system.config_group.edit', 'display_name' => '编辑组','route'=>'admin.config_group.edit'],
                            ['name' => 'system.config_group.destroy', 'display_name' => '删除组','route'=>'admin.config_group.destroy'],
                        ]
                    ],
                    [
                        'name' => 'system.configuration',
                        'display_name' => '配置项',
                        'route' => 'admin.configuration',
                        'child' => [
                            ['name' => 'system.configuration.create', 'display_name' => '添加组','route'=>'admin.configuration.create'],
                            ['name' => 'system.configuration.edit', 'display_name' => '编辑组','route'=>'admin.configuration.edit'],
                            ['name' => 'system.configuration.destroy', 'display_name' => '删除组','route'=>'admin.configuration.destroy'],
                        ]
                    ],
                    [
                        'name' => 'system.login_log',
                        'display_name' => '登录日志',
                        'route' => 'admin.login_log',
                        'child' => [
                            ['name' => 'system.login_log.destroy', 'display_name' => '删除','route'=>'admin.login_log.destroy'],
                        ]
                    ],
                    [
                        'name' => 'system.operate_log',
                        'display_name' => '操作日志',
                        'route' => 'admin.operate_log',
                        'child' => [
                            ['name' => 'system.operate_log.destroy', 'display_name' => '删除','route'=>'admin.operate_log.destroy'],
                        ]
                    ],
                ]
            ],
            [
                'name' => 'information',
                'display_name' => '资讯管理',
                'route' => '',
                'icon' => 'layui-icon-read',
                'child' => [
                    [
                        'name' => 'information.category',
                        'display_name' => '分类管理',
                        'route' => 'admin.category',
                        'child' => [
                            ['name' => 'information.category.create', 'display_name' => '添加分类','route'=>'admin.category.create'],
                            ['name' => 'information.category.edit', 'display_name' => '编辑分类','route'=>'admin.category.edit'],
                            ['name' => 'information.category.destroy', 'display_name' => '删除分类','route'=>'admin.category.destroy'],
                        ]
                    ],
                    [
                        'name' => 'information.tag',
                        'display_name' => '标签管理',
                        'route' => 'admin.tag',
                        'child' => [
                            ['name' => 'information.tag.create', 'display_name' => '添加标签','route'=>'admin.tag.create'],
                            ['name' => 'information.tag.edit', 'display_name' => '编辑标签','route'=>'admin.tag.edit'],
                            ['name' => 'information.tag.destroy', 'display_name' => '删除标签','route'=>'admin.tag.destroy'],
                        ]
                    ],
                    [
                        'name' => 'information.article',
                        'display_name' => '文章管理',
                        'route' => 'admin.article',
                        'child' => [
                            ['name' => 'information.article.create', 'display_name' => '添加文章','route'=>'admin.article.create'],
                            ['name' => 'information.article.edit', 'display_name' => '编辑文章','route'=>'admin.article.edit'],
                            ['name' => 'information.article.destroy', 'display_name' => '删除文章','route'=>'admin.article.destroy'],
                        ]
                    ],
                ]
            ],
        ];

        foreach ($permissions as $pem1) {
            //生成一级权限
            $p1 = \App\Models\Permission::create([
                'name' => $pem1['name'],
                'display_name' => $pem1['display_name'],
                'route' => $pem1['route']??'',
                'icon' => $pem1['icon']??1,
            ]);
            //为角色添加权限
            $role->givePermissionTo($p1);
            //为用户添加权限
            $user->givePermissionTo($p1);
            if (isset($pem1['child'])) {
                foreach ($pem1['child'] as $pem2) {
                    //生成二级权限
                    $p2 = \App\Models\Permission::create([
                        'name' => $pem2['name'],
                        'display_name' => $pem2['display_name'],
                        'parent_id' => $p1->id,
                        'route' => $pem2['route']??1,
                        'icon' => $pem2['icon']??1,
                        'type' => isset($pem2['type']) ? $pem2['type'] : 2,
                    ]);
                    //为角色添加权限
                    $role->givePermissionTo($p2);
                    //为用户添加权限
                    $user->givePermissionTo($p2);
                    if (isset($pem2['child'])) {
                        foreach ($pem2['child'] as $pem3) {
                            //生成三级权限
                            $p3 = \App\Models\Permission::create([
                                'name' => $pem3['name'],
                                'display_name' => $pem3['display_name'],
                                'parent_id' => $p2->id,
                                'route' => $pem3['route']??'',
                                'icon' => $pem3['icon']??1,
                                'type' => isset($pem2['type']) ? $pem2['type'] : 2,
                            ]);
                            //为角色添加权限
                            $role->givePermissionTo($p3);
                            //为用户添加权限
                            $user->givePermissionTo($p3);
                        }
                    }

                }
            }
        }

        //为用户添加角色
        $user->assignRole($role);

        //初始化的角色
        $roles = [
            ['name' => 'business', 'display_name' => '商务'],
            ['name' => 'assessor', 'display_name' => '审核员'],
            ['name' => 'channel', 'display_name' => '渠道专员'],
            ['name' => 'editor', 'display_name' => '编辑人员'],
            ['name' => 'admin', 'display_name' => '管理员'],
        ];
        foreach ($roles as $role) {
            \App\Models\Role::create($role);
        }
    }
}
