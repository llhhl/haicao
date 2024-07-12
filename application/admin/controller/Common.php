<?php
namespace app\admin\controller;
use think\Controller;
use app\admin\model\Node;
use app\admin\model\UserType;
use app\admin\model\UserModel;
use app\admin\model\ContentModel;
use app\admin\model\CategoryModel;
class Common extends Controller{

 	public function _initialize()
    {
    	error_reporting(E_ALL);
    	
        $module     = strtolower(request()->module());
        $controller = strtolower(request()->controller());
        $action     = strtolower(request()->action());
        $url        = $module."/".$controller."/".$action;

        //无需验证控制器
        $noauthurl = [
            'admin/index/login',
            'admin/index/dologin',
            'admin/index/loginout',
            'admin/index/cache',
            'admin/upload/upload',
            'admin/upload/diyupload',
            'admin/upload/uploadfile',
            'admin/upload/browsefile',
            'admin/content/sortcontent',
            'admin/content/movecategory',
            'admin/category/sortcategory',
            'admin/category/etitlecategory',
            'admin/diyform/showcode',
            'admin/area/showurl',
            'admin/content/baidu',
            'admin/content/xzh',
            'admin/content/media',
            'admin/content/stateall',
            'admin/content/mainurl',
            'admin/content/getarea',
            'admin/content/baiduqc',
            'admin/userinfo/index',
            'admin/report/doexport',
        ];
     
        $assign = [];
        //跳过验证
        if(!in_array($url, $noauthurl)){
            if(!session('admin_uid')){
                $this->redirect('/admin.php');
            }

            $auth = new \com\Auth();

            //跳过检测以及主页权限
            if(session('groupid') != 1){
                if(!in_array($url, ['admin/index/index'])){
					
                    if(!$auth->check($url,session('admin_uid'))){
                        $this->error('抱歉，您没有操作权限');
                    }
                }
            }

            $usermod = new UserModel();
            $hasUser = $usermod->getOneUser(session('admin_uid'));
			
            $user = new UserType();
			//echo("----".$hasUser['groupid']);
            $roleinfo = $user->getRoleInfo($hasUser['groupid']);

            $node = new Node();
            $menu_list = $node->getMenu($roleinfo['rules']);
            $menu_child = $node->getMenuchild($url, $roleinfo['rules']);

            $position = $node->getPosition($url);
            $position['name'] = $position['name'] ? $position['name'] : "管理控制台";

            $category = new CategoryModel();
            $content = new ContentModel();
            $nav = new \org\Leftnav;
            $maincatlist = [];
                //内容主页面显示栏目分类
            $topcat = $category->getCategoryByPid(0);
            $allcate = $category->getAllcategory();
            foreach ($topcat as $k => $v) {
                $v['concount'] = $content->getContentCount(['cid'=>$v['id']]);
                $maincat['parentcat'] = $v;
                $childcat = $nav::rule($allcate, '--', $v['id']);
                foreach ($childcat as $k1 => $v1) {
                    $childcat[$k1]['concount'] = $content->getContentCount(['cid'=>$v1['id']]);
                }
                $maincat['childcat'] = $childcat;
                $maincatlist[] = $maincat;
            }
            
            $assign = [
                'username' => $hasUser['username'],
                'menu' => $menu_list,
                'menu_child' => $menu_child,
                'rolename' => $roleinfo['title'],
                'position' => $position,
                'maincatlist' => $maincatlist,
            ];
        }else{
            $assign = [
                'position' => ['name'=>'管理员登陆'],               
            ];
        }
        $assign['copy_sysname'] = config('sys.copy_sysname') ? config('sys.copy_sysname') : '网站管理后台';
        $assign['site_title'] = config('sys.site_title') ? config('sys.site_title') : '网站管理后台';
        $this->assign($assign);

   
    }

}