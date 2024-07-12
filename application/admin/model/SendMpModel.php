<?php
namespace app\admin\model;
use think\Model;
use think\Db;
use think\Loader;
class SendMpModel extends Model
{
	/*
	appId         公众号appId
	appSecret	  公众号appSecret
	openid        接收人微信id
	template_id   模板id
	data      array('value'=>array('value'=>'111','color'=>"#00CD00"));[数组]发送内容
	url          跳转url
	*/
	
    public function sendmpmessage($appId,$appSecret,$openid,$template_id,$data,$tourl=''){
		
       	 		//echo($openid);	
            $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$appId.'&secret='.$appSecret;//请求获取access_token
            $cont = file_get_contents($url);//执行上面的post请求
            $str = json_decode($cont,1);//转换成数组

            /* $urluser = 'https://api.weixin.qq.com/cgi-bin/user/get?access_token='.$str['access_token'];//提交模板消息url
            $contuser = file_get_contents($urluser);//执行上面的post请求
           $user = json_decode($contuser,1);//转换成数组

            $listuser = $user['data'];
            foreach ($listuser['openid'] as $key => $value) {
					if(session('openid')==$value){*/
							$urlto = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token='.$str['access_token'];//提交模板消息url
							
							//$openid = $value;//发送用户的唯一标识openid
							//$template_id = $list['template_id'];//模板消息的模板id
							$time = date('Y-m-d H:i:s',time());
							//发送模板消息的数据
/*							$data= array(
								$list['parameter']=>array('value'=>$param[$list['content']],'color'=>"#00CD00"),
								$list['parameter2']=>array('value'=>$param[$list['content2']],'color'=>'#EE5C42'),
								$list['parameter3']=>array('value'=>$param[$list['content3']],'color'=>'#030303'),
								$list['parameter4']=>array('value'=>$param[$list['content4']],'color'=>'#030303'),
								$list['parameter5']=>array('value'=>$param[$list['content5']],'color'=>'#030303'),
								$list['parameter6']=>array('value'=>$param[$list['content6']],'color'=>'#030303'),
							);*/
							//模板消息发送时的参数
							$content = array(
							  'touser' => $openid,
							  'template_id' => $template_id,
							  'url' => $tourl,//跳转的url
							  'topcolor' => '#030303',
							  'data' => $data,
							);
							var_dump($content);
							$content = json_encode($content);//将数组转换json格式
							$ch = curl_init($urlto);//初始化url
							curl_setopt($ch,CURLOPT_CUSTOMREQUEST,'POST');//post提交方式
							curl_setopt($ch,CURLOPT_POSTFIELDS,$content);//提交的数据
							curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);//要求结果为字符串且输出到屏幕上
							curl_setopt($ch,CURLOPT_HTTPHEADER,array('Content-Type:application/json','Content-Length'.strlen($content)));//设置header类型
							curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
							$content = curl_exec($ch);//运行urlc
							//var_dump($content);
							/*try{
								$content = curl_exec($ch);//运行urlc
								return json(['code' => '1','msg' => '发送成功']); 
							}
							catch( PDOException $e){
								return ['status' => 'error', 'msg' => $e->getMessage()];
							}*/
/*							
						}
			}*/
    }
	

}