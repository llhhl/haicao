<?php
$configsys = include_once('../../../config/extra/sys.php');
$isopen = isset($configsys['qiniu']) && $configsys['qiniu'] == 1 ? 1 : 0;
return array(
	'upload_type' => $isopen ? 'qiniu' : 'local',  // [qiniu|local] 设置上传方式 qiniu 上传到七牛云存储 ,local 上传到本地
	/* 本地上传配置信息 */
	'orderby'     => 'desc',   // [desc|asc] 列出文件排序方式，仅仅在本地上传时候有效
	'root_path'	  => $_SERVER['DOCUMENT_ROOT'], //本地上传 本地的绝对路径

	/* 七牛云存储信息配置 */
	'bucket'      => $configsys['qiniu_bucket'], // 七牛Bucket的名称
	'host'        => $configsys['qiniu_domain'],
	'access_key'  => $configsys['qiniu_accesskey'],
	'secret_key'  => $configsys['qiniu_secretkey'],

	/* 上传配置 */
	'timeout'     => '3600',  // 上传时间
	'save_type'   => 'date',  // 保存类型

	/* 水印设置 */
	'use_water'   => false,  // 是否开启水印
	/* 七牛水印图片地址 */
	'water_url'   => 'http://gitwiduu.u.qiniudn.com/ueditor-bg.png',

	'uploadQiniuUrl' => isset($configsys['qiniu_upurl']) ? $configsys['qiniu_upurl'] : 'http://up.qiniu.com',
	/* 水印显示设置 */ 
	'dissolve'    => 50,  // 水印透明度
	'gravity'	  => 'SouthEast',  // 水印位置具体见文档图片说明和选项
	'dx'		  => 10,  //边距横向位置
	'dy'		  => 10,   //边距纵向位置
);


