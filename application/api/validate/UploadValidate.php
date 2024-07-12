<?php
// +----------------------------------------------------------------------
// | cms_api [ iActing ]
// +----------------------------------------------------------------------
// | Copyright (c) 2018~2088 https://www.ijiandian.com All rights reserved.
// +----------------------------------------------------------------------
// | Data: 2020/5/18 0018 11:49
// +----------------------------------------------------------------------
// | Author: iActing <758246061@qq.com>
// +----------------------------------------------------------------------

namespace app\api\validate;


class UploadValidate extends BaseValidate
{
    protected $rule =   [
        'filename'  => 'require',
    ];

    protected $message  =   [
        'filename.require' => '请填写文件名',
    ];

    protected $scene = [
        'upload'  =>  ['filename'=>'require']
    ];
}