<?php

return [
    /**
     * 插件存放的数据表名称
     */
    'table' => 'plugins',

    /**
     * 插件模型完整路径
     */
    'model' => 'Gallery\Plugin\Models\Plugin',

    /**
     * 存放插件的文件夹
     */
    'root' => 'plugins/',

    /**
     * 插件入口文件后缀
     */
    'ext' => '.php',

    /**
     * 钩子列表
     * type: 0 action hook, 1 filter hook 2 once hook
     */
    'hooks' => [
        'all' => [
            'desc' => 'Do all'
        ],
        'initialize' => [
            'desc' => 'Early life cycle'
        ],
        'initialized' => [
            'desc' => 'After initialization'
        ],
        'save_string' => [
            'desc' => 'Filter string'
        ],
        'hello' => [
            'desc' => 'Say hello'
        ],
    ],

    /**
     * 插件配置列吧
     */
    'plugins' => [
        'too' => [
//            'entry' => 'path to Plugin.php',
            'config' => [],
            'enable' => true,
        ]
    ],
];
