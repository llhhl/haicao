<?php


namespace app\admin\Validate;


use think\Validate;

class Upload extends Validate
{
    protected $rule =   [
        'filepath'  => 'require',
    ];

    protected $message  =   [
        'filepath.require' => '请填写上传路径',
    ];
}