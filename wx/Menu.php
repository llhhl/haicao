<?php
	include 'config.php';
	$config = config();
    $appId = $config['appid'];//公众号id
    print_r($appId);
    $appSecret = $config['secret'];//公众号秘钥
    $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$appId.'&secret='.$appSecret;//请求获取access_token
    $cont = file_get_contents($url);//执行上面的post请求
    $str = json_decode($cont,1);//转换成数组
    $urlto = 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token='.$str['access_token'];//提交模板消息url
    
    //print_r($urls);die();
    // $scene_id = '111';
    // $post_data = array(); 
    // $post_data['expire_seconds'] = 3600 * 24; //有效时间 
    // $post_data['action_name'] = 'QR_LIMIT_SCENE'; 
    // $post_data['action_info']['scene']['scene_id'] = $scene_id; //传参用户uid，微信端可获取 
    // $json = curlPost($urlto, json_encode($post_data));
    $content = ' {
	    "button":[
	    {    
	        "type":"view",
            "name":"进入平台",
            "url":"http://zgpt.400060.com/wx/geturl.php"
	    }]
	 }';
    //$content = json_encode($content);//将数组转换json格式
    $ch = curl_init($urlto);//初始化url
    curl_setopt($ch,CURLOPT_CUSTOMREQUEST,'POST');//post提交方式
    curl_setopt($ch,CURLOPT_POSTFIELDS,$content);//提交的数据
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);//要求结果为字符串且输出到屏幕上
    curl_setopt($ch,CURLOPT_HTTPHEADER,array('Content-Type:application/json','Content-Length'.strlen($content)));//设置header类型
    $content = curl_exec($ch);//运行url
    //curl_close($ch);
    print_r($content);//在页面上数据执行后的代码
?>