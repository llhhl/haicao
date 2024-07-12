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


class PeportUserValidate extends BaseValidate
{
    protected $rule =   [
        'mobile'  => 'require',
        'password'  => 'require',
        'old_password'=>'require',
        'new_password'=>'require|confirm'
    ];

    protected $message  =   [
        'mobile.require' => '请填写用户名',
        'password.require' => '请填写密码',
        'old_password.require' => '请填写旧密码',
        'new_password.require' => '新密码必填',
        'new_password.confirm' => '两次密码不一致',//confirm自动相互验证
    ];

    protected $scene = [
        'login'  =>  ['mobile'=>'require','password'=>'require'],
        'edit'  =>  ['old_password'=>'require','new_password'=>'require|confirm'],
    ];
}