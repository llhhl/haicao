<?php
namespace app\admin\controller;
use app\admin\model\AreaModel;
use app\admin\model\ReportUserModel;
use app\admin\service\PHPExcel;
use app\admin\model\ReportListModel;
use com\Page;
use think\Db;

class Report extends Common
{
    // 测试postman xxx.cc/admin/user/test
    public function test(){
        $params = input('get.area_id');

        $firmExcel = new PHPExcel();

        $filename = '积分记录';
        $title = ['用户id'];
        $field = ['id'];
        $p = new ReportUserModel();

        $database = $p->select();// 数据
        success(0,"成功",$database);
    }


    //报表用户显示列表
    public function index(){
        $job_number = input('job_number');
        $map = [];
        if($job_number&&$job_number!=="")
        {
            $map['job_number'] = ['like',"%" . $job_number . "%"];
        }
        $user = new ReportUserModel();
        $infolist = $user->getUsersByWhere($map, 1, 200);
        foreach($infolist as $k=>$v)
        {
            $infolist[$k]['last_login_time'] = $v['last_login_time'] ? date('Y-m-d H:i:s', $v['last_login_time']) : '';
        }

        $this->assign('page',$infolist->render());
        $this->assign('infolist', $infolist);
        return $this->fetch();
    }
    //添加报表用户
    public function adduser()
    {
        if(request()->isAjax()){

            $param = input('post.');
            $is_exist = ReportUserModel::where('mobile',$param['mobile'])->find();
            if($is_exist){
                return json(['code' => 0, 'data' => [], 'msg' => "用户已经存在"]);
            }

            $is_exist = ReportUserModel::where('job_number',$param['job_number'])->find();
            if($is_exist){
                return json(['code' => 0, 'data' => [], 'msg' => "工号已存在"]);
            }


            $param['password'] = md5(md5($param['password']));
            $user = new ReportUserModel();
            $flag = $user->insertUser($param);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }

        return $this->fetch();
    }
    //修改管理员密码及权限
    public function edituser()
    {
        $user = new ReportUserModel();
        if(request()->isAjax()){
            $param = input('post.');
            if(empty($param['password'])){
                unset($param['password']);
            }else{
                $param['password'] = md5(md5($param['password']));
            }
            $flag = $user->editUser($param);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }

        // 用户信息
        $id = input('param.id');
        $this->assign('info',$user->getOneUser($id));
        // 城市
        $area = new AreaModel();
        $this->assign('area',$area->getAllList());

        return $this->fetch();
    }
    //修改报表用户密码、姓名
    public function myuser()
    {
        $user = new ReportUserModel();
        if(request()->isAjax()){
            $param = input('post.');
            if(empty($param['password'])){
                unset($param['password']);
            }else{
                $param['password'] = md5(md5($param['password']));
            }
            $flag = $user->editUser($param);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }

        $id = session('admin_uid');
        $this->assign('info', $user->getOneUser($id));


        return $this->fetch();
    }
    //删除管理员
    public function deluser()
    {
        $id = input('param.ids');
        $role = new ReportUserModel();
        $flag = $role->delUser($id);
        return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
    }
    //管理员是否允许登录
    public function stateuser()
    {
        $id = input('param.id');
        $db = Db::name('report_user');
        $status = $db->where(['id'=>$id])->value('status');//判断当前状态情况
        if($status==1)
        {
            $flag = $db->where(['id'=>$id])->setField(['status'=>0]);
            return json(['code' => 1, 'data' => $flag['data'], 'msg' => '已禁止']);
        }else
        {
            $flag = $db->where(['id'=>$id])->setField(['status'=>1]);
            return json(['code' => 0, 'data' => $flag['data'], 'msg' => '已开启']);
        }
    }

    public function downloaduser()
    {
        $id = input('param.id');
        $db = Db::name('report_user');
        $download = $db->where(['id'=>$id])->value('download');//判断当前状态情况
        if($download==1)
        {
            $flag = $db->where(['id'=>$id])->setField(['download'=>0]);
            return json(['code' => 1, 'data' => $flag['data'], 'msg' => '已禁止']);
        }else
        {
            $flag = $db->where(['id'=>$id])->setField(['download'=>1]);
            return json(['code' => 0, 'data' => $flag['data'], 'msg' => '已开启']);
        }
    }



    //报表数据显示列表
    public function report_list(){
        $map = [];

        $job_number = input('job_number');
        if($job_number&&$job_number!=="")
        {
            $map['job_number'] = ['like',"%" . $job_number . "%"];
        }

        $area_id = input('house_address');
        if($area_id&&$area_id!=="")
        {
            $map['house_address'] = ['like',"%" . $area_id . "%"];
        }

        $client_mobile = input('client_mobile');
        if($client_mobile&&$client_mobile!=="")
        {
            $map['client_mobile'] = ['like',"%" . $client_mobile . "%"];
        }
//

        $start_time = input('start_time');
        $end_time = input('end_time');
        if($start_time&&$start_time!=="" && $end_time&&$end_time!=="")
        {
            if($start_time ==$end_time){
                $end_time =date('Y-m-d H:i:s',strtotime($start_time) + 86399);
            }else{
                $end_time =date('Y-m-d H:i:s',strtotime($end_time) + 86399);
            }
            $map['create_time'] = ['between time', "$start_time,$end_time"];
        }


        $user = new ReportListModel();
        $infolist = $user->getUsersByWhere($map,$job_number,$area_id,$client_mobile,$start_time,$end_time, 1, 10);

        $this->assign('infolist', $infolist);

        $this->assign('page',$infolist->render());

        if(!empty($end_time)){
            $end_time = date('Y-m-d',strtotime($end_time));
        }

        $this->assign([
            'house_address'=>$area_id,
            'job_number' =>$job_number,
            'start_time' =>$start_time,
            'end_time'=>$end_time,
            'client_mobile'=>$client_mobile
        ]);

        return $this->fetch('list');
    }

    //删除报表 + 实体资源
    public function delreport()
    {
        $id = input('param.ids');
        $role = new ReportListModel();

        $info = ReportListModel::where('id',$id)->field(['pdf_url','content','client_signature','image_folder'])->find();
        delDir('./uploads/image/'.$info['image_folder']);
//        $content =object_array($info['content']);
//        $content = json_decode($content, true);
//
//        // 删除内容里的图片
//        $images = $content[0]['images'];
//        foreach ($images as $n=>$m){
//            $url_path = substr($m, 25);
//            $filename = './'.$url_path;
//            if(file_exists($filename)){
//                unlink($filename);
//            }
//        }
//
        // 删除pdf_url
        $pdf_url = $info['pdf_url'];
        if(file_exists('./'.$pdf_url)){
            unlink('./'.$pdf_url);
        }

        // 删除签名图片
        $client_signature = $info['client_signature'];
        $signature_url_path = substr($client_signature, 25);
        $signature_filename = './'.$signature_url_path;
        if(file_exists($signature_filename)){
            unlink($signature_filename);
        }
//
//        // 删除图片所在的文件夹
//        if(!empty($info['image_folder'])){
//            rmdir('./uploads/image/'.$info['image_folder']);
//        }

        $flag = $role->delUser($id);
        return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
    }


    // 导出报表
    public function doexport(){



        $map = [];
        $all = input('param.');


        if(!empty($all['area_id'])){
            $map['house_address'] = ['like',"%" . $all['area_id'] . "%"];
        }

        if(!empty($all['client_mobile'])){
            $map['client_mobile'] = ['like',"%" . $all['client_mobile'] . "%"];
        }

        if(!empty($all['job_number']))
        {
            $map['job_number'] = ['like',"%" . $all['job_number'] . "%"];
        }

        $start_time = $all['start_time'];
        $end_time = $all['end_time'];
        if(!empty($start_time) && !empty($end_time)){

            if($start_time ==$end_time){
                $end_time =date('Y-m-d H:i:s',strtotime($start_time) + 86399);
            }else{
                $end_time =date('Y-m-d H:i:s',strtotime($end_time) + 86399);
            }
            $map['create_time'] = ['between time', "$start_time,$end_time"];
        }

        $firmExcel = new PHPExcel();
        $filename = '报表数据';
        $title = ['ID','验房师真实姓名','工号','PDF地址','客户姓名','客户电话','城市','楼盘名称','房子类型','房间号','房间面积','备注','创建时间'];
        $field = ['id','real_name','job_number','pdf_url','client_username','client_mobile','house_address','house_name','house_type','house_number','house_area','remark','create_time'];
        $database = ReportListModel::where($map)->select();

        foreach ($database as $k=>$v){
            $database[$k]['real_name']=$v['user_id']['real_name'];
            $house_type = ['精装房','毛坯房'];
            $database[$k]['house_type'] =$house_type[$v['house_type']];
            $database[$k]['create_time'] = date('Y年m月d日',$v['create_time']);
//            $database[$k]['area_title']=$v['area_id']['title'];
        }

//        return json(['code' => 1, 'data' => $database, 'msg' => '看下数据']);
        $firmExcel->export($filename, $title, $field, $database);
    }


    // 查看PDF
    public function download($id){

        $result = ReportListModel::where('id',$id)->find();

        if(!empty($result['pdf_url'])  && !file_exists($result['pdf_url'])){
            return "<script language='javascript' type='text/javascript'>window.location.href= '".$result['pdf_url']."'</script>";
        }else{
            $userInfo = \app\api\model\ReportUser::where('id', $result['user_id']['id'])->find();
            // 重新生成
            $spdf = new \app\api\controller\Report();
            if($result['house_type']==1){
                $type_str = 'MP';
            }else{
                $type_str = 'JZ';
            }
            // $report_url = '/uploads/pdf/' . $result['client_mobile']. '_'.$result['create_time']. '_' .$type_str.'_'. $result['job_number'] . ".pdf"; 1
            // $report_url = '/uploads/pdf/' . $result['create_time']. '-'.$result['job_number']. '-' .$type_str.'-'. $result['client_mobile'] . ".pdf"; 2
            $time_pdf = date('Y_m_d',$result['create_time']);
            $time_pdf_h = date('H.i.s',$result['create_time']);
            $report_url = '/uploads/pdf/' . $time_pdf. '_'.$result['job_number']. '_'.$time_pdf_h.'_' .$type_str.'_'. $result['client_mobile'] .
                ".pdf";





            // $report_url = '/uploads/pdf/' . rand_str(12) . '_' . $result['user_id']['id'] . "_pdf.pdf";
            $result['pdf_url'] = $report_url;
            $result['inspector']= $userInfo['real_name'];
            $result['house_id'] = date("YmdHis");
            //$result['create_time'] = time();
            $content =object_array($result['content']);
            //$content = json_decode($result['content']);
            //$content = json_decode($content);
            $content = json_decode($content, true);

//            return dump($content);exit();
            if($result['moban']==2){
                $spdf->create_pdf1($result,$content);// 宜居
            }else if($result['moban']==1){
                $spdf->create_pdf2($result,$content);// 海草
            }else{
                $spdf->create_pdf($result,$content);//
            }


            ReportListModel::where('id',$id)->update([
                'pdf_url'=>$report_url
            ]);



            return "<script language='javascript' type='text/javascript'>window.location.href= '".$report_url."'</script>";
        }



    }





    // 批量删除 + 实体资源
    public function delbyids()
    {

        $ids = input('param.ids');
        $ids = rtrim ( $ids ,  "," ); // "1,2,3"
        $arr_ids = explode(",",$ids);
        foreach ($arr_ids as $k=>$v){
            $info = ReportListModel::where('id',$v)->field(['pdf_url','content','client_signature','image_folder'])->find();
            $path_ = $info['image_folder'];

//                return json(['code' => 1, 'data' => ['id'=>$v], 'msg' => $path_]);

            // 删除pdf_url
            $pdf_url = $info['pdf_url'];
            if(file_exists('./'.$pdf_url)){
                unlink('./'.$pdf_url);
            }

            // 删除签名图片
            $client_signature = $info['client_signature'];
            $signature_url_path = substr($client_signature, 25);
            $signature_filename = './'.$signature_url_path;
            if(file_exists($signature_filename)){
                unlink($signature_filename);
            }

            // 删除内容下得所有图片
             delDir('./uploads/image/'.$path_);

        }


        $flag=Db::name("report_list")->where("id in ($ids)")->delete();
        if($flag){
            return json(['code' => 0, 'data' => [], 'msg' => "批量删除成功"]);
        }


    }








}