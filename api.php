<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 定义应用目录
define('APP_PATH', __DIR__ . '/application/');
// 绑定当前入口文件到admin模块
define('BIND_MODULE','api');
// 加载框架基础文件
require __DIR__ . '/thinkphp/start.php';
