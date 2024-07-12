<?php
// +----------------------------------------------------------------------
// | cms_api [ iActing ]
// +----------------------------------------------------------------------
// | Copyright (c) 2018~2088 https://www.ijiandian.com All rights reserved.
// +----------------------------------------------------------------------
// | Data: 2020/5/18 0018 14:16
// +----------------------------------------------------------------------
// | Author: iActing <758246061@qq.com>
// +----------------------------------------------------------------------

namespace app\api\service;


class Token
{
    public static function generateToken(){
        return md5(rand_str(10) . time());
    }

    public static function getCurrentTokenValue($key){
        $token = \think\Request::instance()->header('token');
        $cacheValue = \think\Cache::get($token);
        if(!$cacheValue){
            returnErrors("身份验证异常：Token无效或已过期~","",30401);
        }else{
            if(!is_array($cacheValue)){
                $cacheData = json_decode($cacheValue,true);
            }
            if(array_key_exists($key,$cacheData)){
                return $cacheData[$key];
            }else{
                throw new Exception('无效参数：尝试获取的'.$key.'变量不存在！');
            }
        }
    }

    public static function getCurrentTokenUserId(){
        return self::getCurrentTokenValue('id');
    }

    public static function saveToCache($cacheValue){
        $key = self::generateToken();
        $value = json_encode($cacheValue);
        // 1小时=3600
        $result = cache($key,$value,config('cache.expire'));
        if(!$result){
            returnErrors("登录失败");
        }
        return $key;
    }
}