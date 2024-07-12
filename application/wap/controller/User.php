<?php
namespace app\wap\controller;

use think\Controller;
use think\Config;
use think\Db;
class User extends Controller
{
        public function index()
	{
		echo 11;die();
        	header("Content-type:text/html;charset=utf-8");
                $code = $_GET['code'];//获取code
                //print_r($code);die();
                $appid = config('push.appid');//公众号登录的appid
                $secret = config('push.appsecret');//公众号的appsecret
                $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$appid.'&secret='.$secret.'&code='.$code.'&grant_type=authorization_code';
                $cont = file_get_contents($url);//执行上面的post请求url
                $str = json_decode($cont,1);//转换成数组
                //echo $code;
                $url_refresh = 'https://api.weixin.qq.com/sns/oauth2/refresh_token?appid='.$appid.'&grant_type=refresh_token&refresh_token='.$str['refresh_token'];//refresh_token进行刷新获取access_token
                $refresh = file_get_contents($url_refresh);//执行上面的post请求url_refresh获得access_token和openid
                $row = json_decode($refresh,1);//转换成数组
                $user_url='https://api.weixin.qq.com/sns/userinfo?access_token='.$row['access_token'].'&openid='.$row['openid'].'&lang=zh_CN';
                $user = file_get_contents($user_url);//执行上面的post请求user_url得到用户信息
                $userinfo = json_decode($user,1);//转换成数组
                $openId = $userinfo['openid'];
                session('openid', $openId); 
	}
}
?>