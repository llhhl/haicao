<?php
namespace app\admin\controller;
use app\admin\model\MpTempModel;
use think\Config;
use think\Db;
use PHPMailer\PHPMailer;

class Mptemp extends Common
{	
   	 public $fields;

				
    public function _initialize()//获取公众号用户信息
    {
       //获取管理信息
		//global $appId,$appSecret,$url,$cont,$str,$urluser,$contuser,$user,$list;
		
       parent::_initialize();
		
        /*$appId = config('sys.appid');//公众号id
       $appSecret = config('sys.appsecret');//公众号秘钥		
		
        $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$appId.'&secret='.$appSecret;//请求获取access_token
        $cont = file_get_contents($url);//执行上面的post请求
        $str = json_decode($cont,1);//转换成数组
	
        $urluser = 'https://api.weixin.qq.com/cgi-bin/user/get?access_token='.$str['access_token'];//提交模板消息url
        $contuser = file_get_contents($urluser);//执行上面的post请求
        $user = json_decode($contuser,1);//转换成数组
			
        $list = $user['data'];//获取关注公众号用户openid
		
        $userlist=array();*/
       // foreach ($list['openid'] as $key => $value) {
//            $info = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$str['access_token'].'&openid='.$value.'&lang=zh_CN';
//            $continfo = file_get_contents($info);//获取关注公众号用户昵称
//            $listinfo = json_decode($continfo,1);//转换成数组
//            $userlist[] = $listinfo;
//        }
       // return $userlist;
       // $this->assign('list',$userlist);
    }


    //公众号模板页面
    public function index()
    {
      
        $MpTemp = new MpTempModel();
        $flag = $MpTemp->getAllMpTemp();

/*        foreach ($flag as $key => $value) {
            $flag[$key]['formname'] = $diyform->getOneFormname($flag[$key]['formid']);
        }*/
        $this->assign('listinfo',$flag);
        return $this->fetch();
    }

    //公众号设置
    public function remind()
    {
        $coffile = CONF_PATH.DS.'extra'.DS.'push.php';
        if(request()->isAjax()){
            Config::load($coffile, '', 'push');
            $conflist = Config::get('','push');
            $param = input('post.');  
            $param = add_slashes_recursive($param);
            //$param['push_send'] = array_key_exists("push_send", $param) ? 1 : 0;
            setConfigfile($coffile, add_slashes_recursive(array_merge($conflist, $param)));
            return json(['code' => 1, 'data' => '', 'msg' => '设置成功']);
            exit();
        }

        return $this->fetch();
    }
    //管理员消息推送设置
    public function pushremind()
    {	//global $appId,$appSecret,$url,$cont,$str,$urluser,$contuser,$user,$list;
        $coffile = CONF_PATH.DS.'extra'.DS.'push.php';
		 Config::load($coffile, '', 'push');
         $conflist = Config::get('','push');
		
        if(request()->isPost()){
           
            $param = input('post.');  
            $param = add_slashes_recursive($param);
            setConfigfile($coffile, add_slashes_recursive(array_merge($conflist, $param)));

            $appId = config('push.appid');//公众号id
            $appSecret = config('push.appsecret');//公众号秘钥
            $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$appId.'&secret='.$appSecret;//请求获取access_token
            $cont = file_get_contents($url);//执行上面的post请求
            $str = json_decode($cont,1);//转换成数组

            $urluser = 'https://api.weixin.qq.com/cgi-bin/user/get?access_token='.$str['access_token'].'&next_openid=olIpy5tqbsg3j9zDfgIOV9tFqW70';//提交模板消息url
            $contuser = file_get_contents($urluser);//执行上面的post请求
            $user = json_decode($contuser,1);//转换成数组

            $list = $user['data'];
           //foreach ($list['openid'] as $key => $value) {
                $urlto = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token='.$str['access_token'];//提交模板消息url
                $openid = $param['openid'];//发送用户的唯一标识openid
                $template_id = $param['template_id'];//模板消息的模板id
                $time = date('Y-m-d H:i:s',time());
                //发送模板消息的数据
				/*消息格式
				 'keynote1'=>array('value'=>'发送文字内容','color'=>"#00CD00"),
                    'keynote2'=>array('value'=>‘发送文字内容’,'color'=>'#EE5C42'),
				*/
                $data= array(
                    $param['one']=>array('value'=>$param['content1'],'color'=>"#00CD00"),
                    $param['two']=>array('value'=>$param['content2'],'color'=>'#EE5C42'),
                    $param['three']=>array('value'=>$param['content3'],'color'=>'#030303'),
                    $param['four']=>array('value'=>$param['content4'],'color'=>'#030303'),
                    $param['keyword1']=>array('value'=>$param['content5'],'color'=>'#030303'),
                    $param['keyword2']=>array('value'=>$param['content6'],'color'=>'#030303'),
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
            //}

            
            //curl_close($ch);
            return json(['code' => 1, 'data' => '', 'msg' => '推送成功']);
            exit();
        }

        return $this->fetch();
    }

    //用户推送设置
    public function userremind()
    {
        //获取表单
        $diyform = new diyformModel();
        $infolist = $diyform->getAlldiyform(); 
        if(request()->isPost()){
            $param = input('post.');
            $push = new PushModel();
            $flag = $push->insertPush($param);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
        $this->assign('infolist',$infolist);
        return $this->fetch();
    }

    //获取表单字段
    public function allform()
    {

        $mid = input('id');
        $diyfield = new DiyfieldModel();
        $infolist = $diyfield->getAllDiyfield($mid,3);
        foreach ($infolist as $k => $v) {
            $infolist[$k]['tname'] = $this->fields[$v['ftype']];
        }
        return $infolist;
    }

    //添加模板
    public function addmptemp()
    { 
   		return $this->fetch();
		
    }

    //编辑模板
    public function editmptemp()
    { $id = input('id');
        $push = new MpTempModel();
        $list = $push->getMpTemp($id);
		$data = unserialize($list['data']);
		//var_dump($data);
		$id = input('id');
        $push = new MpTempModel();
		if (!empty($id)){
        $list = $push->getMpTemp($id);
		$data = unserialize($list['data']);
		}		
   		$this->post();
       $this->assign('list',$list);
		$this->assign('data',$data);
		return $this->fetch();
    }

public function post(){
	$id = input('id');	
    if(request()->isPost()){
        $param = input('post.',array());	
		
		$keywords = input('tp_kw/a',array());
		$value = input('tp_value/a',array());
		$color = input('tp_color/a',array());

		if (!empty($keywords)) {
				$data = array();
				foreach ($keywords as $key => $val) {
					$data[] = array('tp_kw' => $keywords[$key], 'tp_value' => $value[$key], 'tp_color' => $color[$key]);
				}			
			$param['data'] = serialize($data);				
		}
		
		$insert = array('template_id' => $param['template_id'],'data'=>isset($data)?$param['data']:'','temptitle' => $param['temptitle'],'first' => $param['first'],'firstcolor' => $param['firstcolor'],'tp_remark' => $param['tp_remark'],'remarkcolor' => $param['remarkcolor'],'id' => $id);
            $push = new MpTempModel();
            $flag = $push->insertMpTemp($insert);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }

		
		
		       
        
		}
    //删除模板
    public function delMpTemp()
    {
        $id = input('ids');
        $push = new MpTempModel();
        $flag = $push->delMpTemp($id);
        return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
    }


    //微信公众号新闻分享
    public function share()
    {
        $coffile = CONF_PATH.DS.'extra'.DS.'share.php';
        if(request()->isAjax()){
            Config::load($coffile, '', 'share');
            $conflist = Config::get('','share');
            $param = input('post.');  
            $param = add_slashes_recursive($param);
            $param['share_star'] = array_key_exists("share_star", $param) ? 1 : 0;
            setConfigfile($coffile, add_slashes_recursive(array_merge($conflist, $param)));

            return json(['code' => 1, 'data' => '', 'msg' => '设置成功']);
            exit();
        }

        return $this->fetch();
    }
 
 	public function tpl(){
		$data = array('tp_kw'=>'','tp_value'=>'','tp_color'=>'');
		$this->assign('data',$data);
		return $this->fetch('tpl');
	}
   
}
