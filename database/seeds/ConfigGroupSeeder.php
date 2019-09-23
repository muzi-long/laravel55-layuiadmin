<?php

use Illuminate\Database\Seeder;

class ConfigGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \Illuminate\Support\Facades\DB::table('configuration')->truncate();
        \Illuminate\Support\Facades\DB::table('config_group')->truncate();
        $datas = [
            [
                'name'=>'系统配置',
                'sort'=>1,
                'configuration' => [
                    [
                        'label' => '登录日志',
                        'key' => 'login_log',
                        'val' => 0,
                        'type' => 'radio',
                        'content' => '0:关闭|1:开启',
                        'tips' => '开启后将记录后台登录日志',
                    ],
                    [
                        'label' => '删除登录日志',
                        'key' => 'delete_login_log',
                        'val' => 0,
                        'type' => 'radio',
                        'content' => '0:禁止|1:允许',
                        'tips' => '开启后将允许后台删除登录日志',
                    ],
                    [
                        'label' => '操作日志',
                        'key' => 'operate_log',
                        'val' => 0,
                        'type' => 'radio',
                        'content' => '0:关闭|1:开启',
                        'tips' => '开启后将记录后台操作日志',
                    ],
                    [
                        'label' => '删除操作日志',
                        'key' => 'delete_operate_log',
                        'val' => 0,
                        'type' => 'radio',
                        'content' => '0:禁止|1:允许',
                        'tips' => '开启后将允许后台删除操作日志',
                    ]
                ],
            ],
            [
                'name'=>'站点配置',
                'sort'=>2,
                'configuration' => [
                    [
                        'label' => '标题',
                        'key' => 'site_title',
                        'val' => 'laravel6.0LTS后台管理',
                        'type' => 'input',
                        'content' => '',
                        'tips' => '',
                    ],
                    [
                        'label' => '关键词',
                        'key' => 'site_keywords',
                        'val' => '后台管理系统管理',
                        'type' => 'input',
                        'content' => '',
                        'tips' => '',
                    ],
                    [
                        'label' => '描述',
                        'key' => 'site_description',
                        'val' => '后台管理系统管理，laravel6，layuiadmin，layui',
                        'type' => 'textarea',
                        'content' => '',
                        'tips' => '',
                    ],

                ]
            ],
            [
                'name'=>'七牛云配置',
                'sort'=>4,
                'configuration' => [
                    [
                        'label' => 'AccessKey',
                        'key' => 'qiniu_access_key',
                        'val' => 'vYbGKXlPhvshlYNe1laBfnUtlXpNpGpbB7dWd',
                        'type' => 'input',
                        'content' => '',
                        'tips' => '',
                    ],
                    [
                        'label' => 'SecretKey',
                        'key' => 'qiniu_secret_key',
                        'val' => 'OZKXcY7F_tD1i6YX_zbLcaCm71OjnDTJrCdtxka4',
                        'type' => 'input',
                        'content' => '',
                        'tips' => '',
                    ],
                    [
                        'label' => 'Bucket',
                        'key' => 'qiniu_bucket',
                        'val' => 'company',
                        'type' => 'input',
                        'content' => '',
                        'tips' => '',
                    ],
                    [
                        'label' => 'Domain',
                        'key' => 'qiniu_domain',
                        'val' => 'static.nicaicai.top',
                        'type' => 'input',
                        'content' => '',
                        'tips' => 'or host: https://xxxx.clouddn.com',
                    ]
                ]
            ],
            [
                'name'=>'微信公众号配置',
                'sort'=>5,
                'configuration' => [
                    [
                        'label' => 'AppID',
                        'key' => 'wechat_app_id',
                        'val' => 'your-app-id',
                        'type' => 'input',
                        'content' => '',
                        'tips' => '',
                    ],
                    [
                        'label' => 'AppSecret',
                        'key' => 'wechat_secret',
                        'val' => 'your-app-secret',
                        'type' => 'input',
                        'content' => '',
                        'tips' => '',
                    ],
                    [
                        'label' => 'Token',
                        'key' => 'wechat_token',
                        'val' => 'your-token',
                        'type' => 'input',
                        'content' => '',
                        'tips' => '',
                    ],
                    [
                        'label' => 'EncodingAESKey',
                        'key' => 'wechat_aes_key',
                        'val' => '',
                        'type' => 'input',
                        'content' => '',
                        'tips' => '兼容与安全模式下请一定要填写！！！',
                    ],
                ]
            ],
        ];
        foreach ($datas as $data){
            $group = \App\Models\ConfigGroup::create([
                'name' => $data['name'],
                'sort' => $data['sort'],
            ]);
            if (isset($data['configuration']) && !empty($data['configuration'])){
                foreach ($data['configuration'] as $configuration){
                    $configuration['group_id'] = $group->id;
                    \App\Models\Configuration::create($configuration);
                }
            }
        }
    }
}
