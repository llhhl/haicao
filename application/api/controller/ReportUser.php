<?php
// +----------------------------------------------------------------------
// | cms_api [ iActing ]
// +----------------------------------------------------------------------
// | Copyright (c) 2018~2088 https://www.ijiandian.com All rights reserved.
// +----------------------------------------------------------------------
// | Data: 2020/5/18 0018 11:43
// +----------------------------------------------------------------------
// | Author: iActing <758246061@qq.com>
// +----------------------------------------------------------------------

namespace app\api\controller;


use app\api\service\Token;
use app\api\validate\PeportUserValidate;
use think\Request;

class ReportUser extends BaseController
{
    // api xxx.cc/api.php/report_user/index
    public function index()
    {
        return '111';
    }

    // post xxx.cc/api.php/report_user/login
    public function login()
    {
        $params = (new PeportUserValidate())->goCheck('login');
        $info = \app\api\model\ReportUser::where('mobile', $params['mobile'])->find();
        if (!$info) {
            returnErrors("用户名不存在");
        }
        if (md5(md5($params['password'])) !== $info['password']) {
            returnErrors("用户名或密码错误");
        }
        if ($info['status'] != 1) {
            returnErrors("帐户被禁用");
        }
        // 更新用户状态
        $param = [
            'loginnum' => $info['loginnum'] + 1,
            'last_login_ip' => $this->request->ip(),
            'last_login_time' => time()
        ];
        \app\api\model\ReportUser::where('id', $info['id'])->update($param);

        // 保存缓存
        unset($info['password']);
        $key = Token::saveToCache($info);
        // 返回给前端
        $user = [
            'id'=>$info['id'],
            'avatar'=>$info['avatar'],
            'real_name'=>$info['real_name'],
            'mobile'=>$info['mobile'],
            'job_number'=>$info['job_number'],
            'principal_phone'=>$info['principal_phone'],
            'principal_phone1'=>$info['principal_phone1'],
            'principal_phone2'=>$info['principal_phone2']
        ];
        $data = [
            'token' => $key,
            'expire' => config('cache.expire') * 1000,// 有效期两天
            'info' => $user,
        ];
        returnSuccess("登录成功", $data);
    }

    // 获取用户信息
    public function info()
    {
        $user_id = Token::getCurrentTokenUserId();
        $info = \app\api\model\ReportUser::where('id', $user_id)->find();
        if ($info['status'] != 1) {
            returnErrors("帐户被禁用");
        }
        returnSuccess("成功", $info);
    }

    /**
     * @method 修改密码
     * @param token,old_password new_password new_password_confirm
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function password()
    {
        $user_id = Token::getCurrentTokenUserId();
        $params = (new PeportUserValidate())->goCheck('edit');
        $info = \app\api\model\ReportUser::where('id', $user_id)->find();
        if ($info['status'] != 1) {
            returnErrors("帐户被禁用");
        }
        if (md5(md5($params['old_password'])) !== $info['password']) {
            returnErrors("旧密码错误");
        }
        $param['password'] = md5(md5($params['new_password']));
        $result =  \app\api\model\ReportUser::update($param,['id'=>$user_id]);
        if(!$result){
            returnErrors("修改失败");
        }
        returnSuccess("修改成功");
    }


}