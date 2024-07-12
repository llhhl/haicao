<?php
// +----------------------------------------------------------------------
// | cms_api [ iActing ]
// +----------------------------------------------------------------------
// | Copyright (c) 2018~2088 https://www.ijiandian.com All rights reserved.
// +----------------------------------------------------------------------
// | Data: 2020/5/18 0018 13:39
// +----------------------------------------------------------------------
// | Author: iActing <758246061@qq.com>
// +----------------------------------------------------------------------

namespace app\api\model;


class ReportUser extends BaseModel
{
    public function getAvatarAttr($value)
    {
        return http_url().$value;
    }
    public function getPrincipalPhone2Attr($value)
    {
        return http_url().$value;
    }
    public function getPrincipalPhoneAttr($value)
    {
        return http_url().$value;
    }
    public function getPrincipalPhone1Attr($value)
    {
        return http_url().$value;
    }
}