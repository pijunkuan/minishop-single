<?php

return [

    //配置文件存储路径
    'save' => storage_path('/minishop_config.json'),

    //SEO
    'seo' => [
        'index' => [
            'title' => '在线商城',
            'keywords' => '',
            'description' => 'Minishop开源在线商城系统'
        ],
        'types' => [
            'title' => '在线商城',
            'keywords' => '',
            'description' => 'Minishop开源在线商城系统'
        ]
    ],

    //系统配置
    'system' => [
        //site
        'site' => [
            'icp' => '',
            'favicon' => '/favicon.ico',
            'logo' => '/images/logo.png',
            'title' => 'Minishop'
        ],

        //统计代码
        'js' => '',

        //主题
        'theme' => [
            'use' => 'BasicTheme',
            'path' => base_path('templates/BasicTheme/pages')
        ],
    ],
];
