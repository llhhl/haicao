<?php
// +----------------------------------------------------------------------
// | whaicao.okimg.cn [ iActing ]
// +----------------------------------------------------------------------
// | Copyright (c) 2018~2088 https://www.ijiandian.com All rights reserved.
// +----------------------------------------------------------------------
// | Data: 2020/5/21 0021 14:05
// +----------------------------------------------------------------------
// | Author: iActing <758246061@qq.com>
// +----------------------------------------------------------------------

namespace app\api\model;


class ReportList extends BaseModel
{
    public function getHouseTypeAttr($value)
    {
        $data = ['精装房','毛坯房'];
        return $data[$value];
    }

    public function getPdfUrlAttr($value){
        return http_url().$value;
    }
}