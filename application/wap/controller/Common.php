<?php
namespace app\wap\controller;
use app\wap\model\GetModel;
use app\wap\model\UserModel;
use think\Controller;
use think\Config;

class Common extends Controller{

 	public function _initialize()
    {
        if (config('app_debug')) {
            error_reporting(E_ALL);
        }else{
            error_reporting(E_ERROR);
        }
    	$module     = strtolower(request()->module());
        $controller = strtolower(request()->controller());
        $action     = strtolower(request()->action());
        $this->tpl_file = './template/'.config('sys.theme_style').'/'.$module.'/';
        //城市
        $area = '';
        if (input('area')) {
        	$area = input('area');
        }

        $levelurl = "";
        if ($_SERVER['HTTP_HOST'] != config('sys.site_url')) {
            $levelurl = str_replace(config('sys.site_levelurl'), '', $_SERVER['HTTP_HOST']);
            if ($levelurl != '') {
            	$levelurl = str_replace('.', '', $levelurl);
            	$area = $levelurl != 'm' ? $levelurl : $area;
                if ($levelurl != 'm') {
                    $areadata = db('area')->where(['etitle'=>$area,'isurl'=>1])->find();
                    if (!$areadata) {
                        abort(404);
                    }
                }
            }
        }

        if (config('sys.wap_levelurl') == 1) {
            if ($levelurl == "") {
                abort(404);
                exit();
            }
        }else{
            if ($levelurl == "m") {
                abort(404);
                exit();
            }
        }
        
        if (!$area && config('sys.seo_default_area')) {
            $defaultarea = db('area')->where(['id'=>config('sys.seo_default_area')])->value('etitle');
            if ($defaultarea) {
                $area = $defaultarea;
            }
        }

        if ($area) {
            $areainfo = db('area')->where(['etitle'=>$area])->find();
            $this->area = $area;
            config('sys.sys_area', $area);
            session('sys_area', $area);
            session('sys_areainfo', $areainfo);
        }else{
            config('sys.sys_area', null);
            session('sys_area', null);
            session('sys_areainfo', null);
        }
        config('sys.sys_levelurl', $levelurl);
        session('sys_levelurl', $levelurl ? $levelurl : null);

        if (is_dir(RUNTIME_PATH.'temp'.DS)) {//分站开启，自动清除缓存
            $path = RUNTIME_PATH.'temp'.DS;
            dir_del($path);
        }

        //获取openid
        
        $user = new UserModel();
        $getopenid = $user->getWeChat(session('openid'));
        if(!isset($getopenid)){
                if ( strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false ) {
                $tools = new GetModel();
                $openId = $tools->GetOpenid();//获取openid
                $user = new UserModel();
                $id = $user->getWeChat($openId);
                if(!$id){
                    //获取access_token
                    $appId = config('sys.mp_appId');//公众号id
                    $appSecret = config('sys.mp_appSecret');//公众号秘钥   
                    $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$appId.'&secret='.$appSecret;//请求获取access_token
                    $cont = file_get_contents($url);//执行上面的post请求
                    $str = json_decode($cont,1);//转换成数组
                    //获取用户昵称信息
                    $info = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$str['access_token'].'&openid='.$openId.'&lang=zh_CN';
                    $continfo = file_get_contents($info);//获取关注公众号用户昵称
                    $listinfo = json_decode($continfo,1);//转换成数组
                    $listinfo['create_time'] = time();
                    
                    $list = $user->insertWeChat($listinfo);
                }
                session('openid', $openId); 
            }
        }
        // if(!isset()){
            // if ( strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false ) {
            //     $tools = new GetModel();
            //     $openId = $tools->GetOpenid();//获取openid
                
            //     $id = $user->getWeChat($openId);
            //     if(!$id){
            //         //获取access_token
            //         $appId = config('push.appid');//公众号id
            //         $appSecret = config('push.appsecret');//公众号秘钥        
                    
            //         $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$appId.'&secret='.$appSecret;//请求获取access_token
            //         $cont = file_get_contents($url);//执行上面的post请求
            //         $str = json_decode($cont,1);//转换成数组
            //         //获取用户昵称信息
            //         $info = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$str['access_token'].'&openid='.$openId.'&lang=zh_CN';
            //         $continfo = file_get_contents($info);//获取关注公众号用户昵称
            //         $listinfo = json_decode($continfo,1);//转换成数组
            //         $listinfo['create_time'] = time();

            //         $list = $user->insertWeChat($listinfo);
            //     }
            //     session('openid', $openId); 
            // }
        // }
        
    }



   
}