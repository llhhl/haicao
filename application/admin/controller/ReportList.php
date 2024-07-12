<?php
namespace app\admin\controller;
use app\admin\model\AreaModel;
use app\admin\model\ReportUserModel;
use app\admin\model\UserModel;
use app\admin\model\UserType;
use app\admin\service\PHPExcel;
use app\model\ReportListModel;
use app\model\ReportUser;
use think\Cache;
use think\Db;

class ReportList extends Common
{

    //管理员显示列表
    public function index(){
        $key = input('key');
        $map = [];
        if($key&&$key!=="")
        {
            $map['username'] = ['like',"%" . $key . "%"];          
        }       
        $user = new ReportListModel();
        $infolist = $user->getUsersByWhere($map, 1, 200);
        foreach($infolist as $k=>$v)
        {
            $infolist[$k]['last_login_time'] = $v['last_login_time'] ? date('Y-m-d H:i:s', $v['last_login_time']) : '';
        }    
        $this->assign('infolist', $infolist);
        return $this->fetch();
    }


    // 导出excel
    public function doexport(){
//        $params = input('get.area_id');

        $firmExcel = new PHPExcel();
        $filename = '报表数据';
        $title = ['ID','城市'];
        $field = ['id','username'];
        $p = new ReportUserModel();
        $database = $p->select();// 数据
        $firmExcel->export($filename, $title, $field, $database);
    }


}