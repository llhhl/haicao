<?php
    //用户网页扫码登录
    //$code = $_GET['code'];//获取code
    //$code = '061efpn20HoM7J1zvtm20Juzn20efpnz';
    include 'config.php';
    $config = config();
    $appid = $config['appid'];//公众号登录的appid
    $secret = $config['secret'];//公众号的appsecret
    $redirect_url = 'http://cms.400060.com/index.php/wap/user/index/';
    $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$appid.'&redirect_uri='.$redirect_url.'&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect';
    //$url = 'https://api.weixin.qq.com/sns/th2/access_token?appid='.$appid.'&secret='.$secret.'&code='.$code.'&grant_type=authorization_code';//请求获取access_token
    //$cont = file_get_contents($url);//执行上面的post请求url
    //$cont = file_get_contents($url);//执行上面的post请求url
    //print_r($cont);
    header('location:'.$url);

?>
