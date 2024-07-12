<?php
namespace app\admin\controller;
use app\admin\model\NotesModel;
use think\Loader;

class Notes extends Common
{
    public function index()
    {
        return $this->fetch();
    }

    public function notemsg(){
        //$param = input('param.');
        $phone = '';//接收短信电话号码
        $codeid = 'SMS_163530105';//短信模板id
        $Notes = new NotesModel();
        $con = array(  // 短信模板中字段的值
            "name"=>'张三',
            "phone"=>'13691234567',
            "code"=>rand(100000, 999999),//验证码
        );
        $row = $Notes->getNotes($phone,$con,$codeid);//执行getNotes，发送短信
        //$infolist = json_decode($row,TRUE);
        //print_r($row);
    	
    }
}
