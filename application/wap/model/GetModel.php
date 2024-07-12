<?php
namespace app\wap\model;
use think\Model;
use think\Db;

class GetModel extends Model
{
	/**
	 * 
	 * 通过跳转获取用户的openid，跳转流程如下：
	 * 1、设置自己需要调回的url及其其他参数，跳转到微信服务器https://open.weixin.qq.com/connect/oauth2/authorize
	 * 2、微信服务处理完成之后会跳转回用户redirect_uri地址，此时会带上一些参数，如：code
	 * 
	 * @return 用户的openid
	 */
	public function GetOpenid()
	{
		//通过code获得openid
		if (!isset($_GET['code'])){
			//触发微信返回code码
			$baseUrl = urlencode('http://'.config('sys.site_url'));
			$url = $this->_CreateOauthUrlForCode($baseUrl);
			Header("Location: $url");
			exit();
		} else {
			//获取code码，以获取openid
		    $code = $_GET['code'];
			$openid = $this->getOpenidFromMp($code);
			return $openid;
		}
	}
	
	
	
	/**
	 * 
	 * 通过code从工作平台获取openid机器access_token
	 * @param string $code 微信跳转回来带上的code
	 * 
	 * @return openid
	 */
	public function GetOpenidFromMp($code)
	{
		$code = $_GET['code'];//获取code
        //print_r($code);die();
        
		$appid = config('sys.mp_appId');//公众号id
		//echo("appid".$appid."------/n");
       	$secret = config('sys.mp_appSecret');//公众号秘钥 
        $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$appid.'&secret='.$secret.'&code='.$code.'&grant_type=authorization_code&scope=snsapi_userinfo';
        $cont = file_get_contents($url);//执行上面的post请求url
        $str = json_decode($cont,1);//转换成数组
        //echo $code;
		
		
        $url_refresh = 'https://api.weixin.qq.com/sns/oauth2/refresh_token?appid='.$appid.'&grant_type=refresh_token&refresh_token='.$str['refresh_token'];//refresh_token进行刷新获取access_token
			
        $refresh = file_get_contents($url_refresh);//执行上面的post请求url_refresh获得access_token和openid
		
        $row = json_decode($refresh,1);//转换成数组

        $user_url='https://api.weixin.qq.com/sns/userinfo?access_token='.$row['access_token'].'&openid='.$row['openid'].'&lang=zh_CN';
		//echo("-----appid=".$row['openid']."------/n");
		//echo($user_url);
        $user = file_get_contents($user_url);//执行上面的post请求user_url得到用户信息

        $userinfo = json_decode($user,1);//转换成数组
        $openid = $userinfo['openid'];
		print_r($openid);die();
		return $openid;
	}
	
	/**
	 * 
	 * 拼接签名字符串
	 * @param array $urlObj
	 * 
	 * @return 返回已经拼接好的字符串
	 */
	private function ToUrlParams($urlObj)
	{
		$buff = "";
		foreach ($urlObj as $k => $v)
		{
			if($k != "sign"){
				$buff .= $k . "=" . $v . "&";
			}
		}
		
		$buff = trim($buff, "&");
		return $buff;
	}
	
	
	
	/**
	 * 
	 * 构造获取code的url连接
	 * @param string $redirectUrl 微信服务器回跳的url，需要url编码
	 * 
	 * @return 返回构造好的url
	 */
	private function _CreateOauthUrlForCode($redirectUrl)
	{
		$urlObj["appid"] = config('sys.mp_appId');
		$urlObj["redirect_uri"] = "$redirectUrl";
		
	   
		$urlObj["response_type"] = "code";
		$urlObj["scope"] = "snsapi_base";
		$urlObj["state"] = "STATE"."#wechat_redirect";
		$bizString = $this->ToUrlParams($urlObj);
		return "https://open.weixin.qq.com/connect/oauth2/authorize?".$bizString;
	}
	
	/**
	 * 
	 * 构造获取open和access_toke的url地址
	 * @param string $code，微信跳转带回的code
	 * 
	 * @return 请求的url
	 */
	private function __CreateOauthUrlForOpenid($code)
	{
		$urlObj["appid"] = config('sys.mp_appId');
		$urlObj["secret"] = config('sys.mp_appSecret');
		
		$urlObj["code"] = $code;
		$urlObj["grant_type"] = "authorization_code";
		$bizString = $this->ToUrlParams($urlObj);
		return "https://api.weixin.qq.com/sns/oauth2/access_token?".$bizString;
	}
}