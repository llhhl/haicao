<?php
return [
    'url_route_on' => false,
    'url_route_must'  =>  false,
    
    // 视图输出字符串内容替换
    'view_replace_str'       => [
        '__ADM_PUBLIC__' =>  '/statics',
        '__ADM_PUB__' =>  '/statics/argee',
        '__UEDITOR__' =>  '/statics/ueditor'
    ],
    // URL伪静态后缀
    'url_html_suffix'        => '',
    'template'               => [
       // 模板引擎类型 支持 php think 支持扩展
        'type'         => 'Think',
        // 模板路径
        //'view_path'    => ROOT_PATH.'template'.DS . 'admin' . DS . 'default' . DS,
        // 模板后缀
        'view_suffix'  => 'html',
        // 模板文件名分隔符
        'view_depr'    => DS,
        // 模板引擎普通标签开始标记
        'tpl_begin'    => '{',
        // 模板引擎普通标签结束标记
        'tpl_end'      => '}',
        // 标签库标签开始标记
        'taglib_begin' => '{',
        // 标签库标签结束标记
        'taglib_end'   => '}',
    ],

];
