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

// [ 应用入口文件 ]
if (version_compare("5.4", PHP_VERSION, ">")) {
     die("PHP 5.4 or greater is required!!!");
}
if (version_compare("7.1", PHP_VERSION, "<")) {
     die("PHP version should not exceed 7.0!!!");
}
define('APP_AUTO_BUILD', TRUE);
define('APP_DEBUG', TRUE);
// 定义应用目录
define('APP_PATH', __DIR__ . '/application/');
// 加载框架引导文件
require __DIR__ . '/thinkphp/start.php';
