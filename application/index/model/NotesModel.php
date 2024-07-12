<?php
namespace app\index\model;
use think\Model;
use think\Db;
use think\Loader;
class NotesModel extends Model
{
    public function getNotes($phone,$con,$codeid){
        //Loader::import('./extend.aliyun.api_demo.SmsDemo');
        require './extend/aliyun/api_demo/SmsDemo.php';
        header('Content-Type: text/plain; charset=utf-8');
        $response = \SmsDemo::sendSms($phone,$con,$codeid);//调用阿里云短信接口
        return $response;
        
        //print_r($rand);
    }

}