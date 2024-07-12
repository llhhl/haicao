<?php
// +----------------------------------------------------------------------
// | cms_api [ iActing ]
// +----------------------------------------------------------------------
// | Copyright (c) 2018~2088 https://www.ijiandian.com All rights reserved.
// +----------------------------------------------------------------------
// | Data: 2020/5/18 0018 11:50
// +----------------------------------------------------------------------
// | Author: iActing <758246061@qq.com>
// +----------------------------------------------------------------------

namespace app\api\validate;


use think\Request;
use think\Validate;

class BaseValidate extends Validate
{
    public function goCheck($scene)
    {
        $params =Request::instance()->param();
        $result = $this->scene($scene)->batch()->check($params);
        if (!$result) {
            $msg = current($this->error);
            returnErrors($msg,$this->error,40000);
        } else {
            return $params;
        }
    }
}