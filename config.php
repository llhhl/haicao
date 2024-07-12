<?php
//error_reporting(7);
$result = require('./application/extra/share.php');
$appid = $result['appId'];

$appsecret = $result['appSecret'];

//$list = json_decode($result);
print($appid);die();


$appurl=$_GET["htmlurl"];

if($appurl)

	$appurl=str_replace("__","&",$appurl);

$noncestr="12d34aabb123f4";

$fp = fopen("access_token2017.txt","r");

$access_token = fread($fp,filesize("access_token2017.txt"));



$url = 'https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token='.$access_token.'&type=jsapi';

$rurl = file_get_contents($url);

$rurl = json_decode($rurl,true);

if($rurl['errcode'] != 0){

// 重新获取access_token 两小时有效

        $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$appid.'&secret='.$appsecret;

        $rurl = file_get_contents($url);

        $rurl = json_decode($rurl,true);

        $access_token = $rurl['access_token'];

	    $fp = fopen('access_token2017.txt','w');

	    fwrite($fp,$access_token);





// 获取jsticket 

        $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=".$access_token."&type=jsapi"; 

        $rurl = file_get_contents($url);

        $rurl = json_decode($rurl,true);

        if($rurl['errcode'] != 0){

            return false;

        }else{

            $jsapi_ticket = $rurl['ticket'];

        }

		

}

else

{

		$jsapi_ticket = $rurl['ticket'];

}



// 获取 signature

        $timestamp = time();

        $string1 = 'jsapi_ticket='.$jsapi_ticket.'&noncestr='.$noncestr.'&timestamp='.$timestamp.'&url='.$appurl;

        $signature = sha1($string1);



?>