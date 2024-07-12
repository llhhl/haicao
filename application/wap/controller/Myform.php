<?php
namespace app\wap\controller;
use think\Db;
use app\admin\model\FormModelSend;
use app\wap\model\PushModel;
class Myform extends Common
{
	public function index(){
		$param = input();
		$returntype = isset($param['__returntype__']) && $param['__returntype__'] == 'json' ? 'json' : 'default';
		if (!isset($param['__formid__']) || !isset($param['__token__'])) {
			$info = ['status'=>'error','msg'=>'参数异常请不要非法操作!'];
			return $this->onresponse($info, $returntype);
			exit();
		}
		/*if ($param['__token__'] != session('__token__')) {
			$info = ['status'=>'error','msg'=>'令牌验证验证失败!'];
			return $this->onresponse($info, $returntype);
			exit();
		}*/
		$diyform = new FormModelSend();
		$info = $diyform->getOnediyform($param['__formid__']);
		if (!$info) {
			$info = ['status'=>'error','msg'=>'表单类别不存在!'];
			return $this->onresponse($info, $returntype);
			exit();
		}
		$info = $diyform->insertForm($param,$info['id']);
		/*if(config('sms.sms_send')==1){//开启短信发送
			$codeid = config('sms.codeid');//短信模板id
	        $Notes = new NotesModel();
			if(config('sms.sms_object')==1){//发送给管理员
				$phone = config('sms.phone');//接收短信电话号码
				$con = array(  // 短信模板中字段的值
		            // "name"=>$param['name'],
		            // "phone"=>$param['phone'],
		            "code"=>rand(100000, 999999),//验证码
		        );
			}else{
				$phone = $param['phone'];
				$con = array(  // 短信模板中字段的值
		            "code"=>rand(100000, 999999),//验证码
		        );
			}
	        $row = $Notes->getNotes($phone,$con,$codeid);//执行getNotes，发送短信
		}
		if(config('push.push_send')==1){//开启消息推送
			$id = $param['__formid__'];
	        $push = new PushModel();
	        $list = $push->getPush($id);
	        $appId = config('push.appid');//公众号id
            $appSecret = config('push.appsecret');//公众号秘钥
            $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$appId.'&secret='.$appSecret;//请求获取access_token
            $cont = file_get_contents($url);//执行上面的post请求
            $str = json_decode($cont,1);//转换成数组

            $urluser = 'https://api.weixin.qq.com/cgi-bin/user/get?access_token='.$str['access_token'].'&next_openid=olIpy5tqbsg3j9zDfgIOV9tFqW70';//提交模板消息url
            $contuser = file_get_contents($urluser);//执行上面的post请求
            $user = json_decode($contuser,1);//转换成数组

            $listuser = $user['data'];
            foreach ($listuser['openid'] as $key => $value) {
            	if(session('openid')==$value){
            		$urlto = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token='.$str['access_token'];//提交模板消息url
	                $openid = $value;//发送用户的唯一标识openid
	                $template_id = $list['template_id'];//模板消息的模板id
	                $time = date('Y-m-d H:i:s',time());
	                //发送模板消息的数据
	                $data= array(
	                    $list['parameter']=>array('value'=>$param[$list['content']],'color'=>"#00CD00"),
	                    $list['parameter2']=>array('value'=>$param[$list['content2']],'color'=>'#EE5C42'),
	                    $list['parameter3']=>array('value'=>$param[$list['content3']],'color'=>'#030303'),
	                    $list['parameter4']=>array('value'=>$param[$list['content4']],'color'=>'#030303'),
	                    $list['parameter5']=>array('value'=>$param[$list['content5']],'color'=>'#030303'),
	                    $list['parameter6']=>array('value'=>$param[$list['content6']],'color'=>'#030303'),
	                );
	                //模板消息发送时的参数
	                $content = array(
	                  'touser' => $openid,
	                  'template_id' => $template_id,
	                  'url' => "",//跳转的url
	                  'topcolor' => '#030303',
	                  'data' => $data,
	                );
	                $content = json_encode($content);//将数组转换json格式
	                $ch = curl_init($urlto);//初始化url
	                curl_setopt($ch,CURLOPT_CUSTOMREQUEST,'POST');//post提交方式
	                curl_setopt($ch,CURLOPT_POSTFIELDS,$content);//提交的数据
	                curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);//要求结果为字符串且输出到屏幕上
	                curl_setopt($ch,CURLOPT_HTTPHEADER,array('Content-Type:application/json','Content-Length'.strlen($content)));//设置header类型
	                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	                $content = curl_exec($ch);//运行urlc
            	}
                
            }
		}*/
		return $this->onresponse($info, $returntype);

	}
	public function captcha(){
		$fid = input('id');
		$captcha = new \tpcaptcha\Captcha();
		return $captcha->entry($fid);
	}
	private function onresponse($info, $returntype){
		if ($returntype == 'default') {
			if ($info['status'] == 'error') {
				$this->error($info['msg']);
			}else{
				$this->success($info['msg']);
			}
			exit();
		}else{
			return json($info);
		}
	}


}
?>