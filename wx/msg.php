<?php
    $appId = 'wxf332e034e86a5e05';//公众号id
    $appSecret = 'e1fd2e4506da7c1f4788a94c861036f8';//公众号秘钥
    $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$appId.'&secret='.$appSecret;//请求获取access_token
    $cont = file_get_contents($url);//执行上面的post请求
    $str = json_decode($cont,1);//转换成数组
    $urlto = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token='.$str['access_token'];//提交模板消息url
    $openid = 'olIpy5ilGxtQKJLHFGS_MZLug4BY';//发送用户的唯一标识openid
    $template_id = "NaDC-Z3VxLSi2fD_12Wyqdpv7SKlSfjZY8Tr7le_LJM";//模板消息的模板id
    //发送模板消息的数据
    $name = "张三";
    $phone = "13912345678";
    $time = date('Y-m-d H:i:s',time());
    $data=array(
            'first'=>array('value'=>$name,'color'=>"#00CD00"),
            'Good'=>array('value'=>$phone,'color'=>'#EE5C42'),
            'contentType'=>array('value'=>$time,'color'=>'#030303'),
            'remark'=>array('value'=>'请尽快联系！','color'=>'#030303'),
        );
    //模板消息发送时的参数
    $content = array(
      'touser' => $openid,
      'template_id' => $template_id,
      'url' => "http://fbd.400060.com/",//跳转的url
      'topcolor' => '#030303',
      'data' => $data
    );
    $content = json_encode($content);//将数组转换json格式
    $ch = curl_init($urlto);//初始化url
    curl_setopt($ch,CURLOPT_CUSTOMREQUEST,'POST');//post提交方式
    curl_setopt($ch,CURLOPT_POSTFIELDS,$content);//提交的数据
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);//要求结果为字符串且输出到屏幕上
    curl_setopt($ch,CURLOPT_HTTPHEADER,array('Content-Type:application/json','Content-Length'.strlen($content)));//设置header类型
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $content = curl_exec($ch);//运行url
    if(curl_errno($ch)){
        var_dump(curl_error($ch)); //若错误打印错误信息
    }
    //curl_close($ch);
    print_r($content);//在页面上数据执行后的代码
?>