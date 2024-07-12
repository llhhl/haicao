<?php
// +----------------------------------------------------------------------
// | cms_api [ iActing ]
// +----------------------------------------------------------------------
// | Copyright (c) 2018~2088 https://www.ijiandian.com All rights reserved.
// +----------------------------------------------------------------------
// | Data: 2020/5/19 0019 17:46
// +----------------------------------------------------------------------
// | Author: iActing <758246061@qq.com>
// +----------------------------------------------------------------------

namespace app\api\controller;


use app\api\service\Token;
use Image\Image;

class Upload extends BaseController
{

    // 单/多 图片上传
    public function image()
    {
        $user_id = Token::getCurrentTokenUserId();
        $file = request()->file('file');
        if ($file == null && $file == "") {
            returnErrors("请上传图片");
        }

        // 图片类型
        $type = input('type');
        $phone = input('phone');
        if(empty($phone)){
            returnErrors("请输入客户手机号");
        }
        if($type==1){
            $type_str = 'MP';
        }else{
            $type_str = 'JQ';
        }

        $user_info = \app\api\model\ReportUser::where('id', $user_id)->find();
        if ($user_info['status'] != 1) {
            returnErrors("帐户被禁用");
        }

        $time = date('Y-m-d');

        $file_folder = 'uploads/image/' .$phone.'_'.$time.'_'.$type_str.'_' . $user_info['job_number'].'/';//  文件夹 uploads/image/13013090543_1001 命名为 手机号_员工编号
        // 文件是否存在，则创建
        if (!is_dir($file_folder)) {
            mkdir($file_folder, 0777, true);
        }


        // 单文件上传
        if (!is_array($file)) {
            $file_info = $file->getInfo();// 文件信息

            $file_suffix= substr(strrchr($file_info['name'], '.'), 0); // .png
            $file_temp= substr(strrchr($file_info['tmp_name'], '/'), 1); //
            $file_name = $file_temp.$file_suffix;

            $info = $file->validate(['size' => 10240000, 'ext' => 'jpg,png,gif'])->move($file_folder, $file_name);// 10M
            // 上传成功
            if ($info) {
                // 图片缩略图处理
                 $image = Image::open(ROOT_PATH.$file_folder.$file_name);
                 $image->thumb(600,450,Image::THUMB_FIXED)->save(ROOT_PATH.$file_folder.$file_name);
                $res = [
                    'url' => $file_folder  . $file_name//uploads/image/13013000000_10002/Snipaste_2020-05-19_15-29-36.png
                ];


                returnSuccess("上传成功", $res);
            }
            // 上传失败
            returnErrors($file->getError());
        }

        // 多文件上传
        $url = ''; // 拼接字符串
        foreach($file as $item){
            $item_info = $item->getInfo();
            $file_suffix= substr(strrchr($file_info['name'], '.'), 0); // .png
            $file_temp= substr(strrchr($file_info['tmp_name'], '/'), 1); // .png
            $file_name = $file_temp.$file_suffix;

            $info = $item->validate(['size' => 10240000, 'ext' => 'jpg,png,gif'])->move($file_folder, $file_name);// 10M
            // 上传成功
            if ($info) {
                // 图片缩略图处理
                $image = Image::open(ROOT_PATH.$file_folder.$file_name);
                $image->thumb(600,450,Image::THUMB_FILLED)->save(ROOT_PATH.$file_folder.$file_name);
                $url.= $file_folder . '_' . $file_name.',';
            }else{
                // 上传失败
                returnErrors($item->getError(),$item_info['name']);
            }
        }
        $url = rtrim($url, ",");
        returnSuccess("上传成功",['url'=>$url]);
    }

    public function image1(){
        $image = Image::open(ROOT_PATH.'uploads/image/13013000000_2020-05-26_MP_10002/phpr0Mlph.jpg');
        $image = $image->thumb(600,450,Image::THUMB_FILLED)->save(ROOT_PATH.'uploads/image/13013000000_2020-05-26_MP_10002/phpnQYC65_1111.png');
    }

    public function uploadssss($base64,$path)
    {

//        // $file = base64_decode(request()->file('image'));//图片
//        //传输 base64图片数据 及 保存的路径
//        $post = input('post.');
//        if (empty($post['base64']))
//            returnErrors("base64参数必填");
//        // $post['base64'] = "data:image/jpeg;base64,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";
//        // 因前端值不一样 这里手动加了文件格式属性,和换行符
//        $base64 = $post['base64'];
        $base64 = str_replace('\n','',$base64);
        $res = $this->base64_image_content($base64, $path);
        return $res;

    }


    //base64图片转换
    function base64_image_content($base64_image_content, $path)
    {
        //匹配出图片的格式
        if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image_content, $result)) {
            $type = $result[2]; // 文件后缀jpeg 不加
            $new_file = $path;
            if (!file_exists($new_file)) {
                //检查是否有该文件夹，如果没有就创建，并给予最高权限
                mkdir($new_file, 0777,true);
            }
            $filename = rand_str(). ".{$type}"; // 文件名
            $new_file = $new_file . $filename;  // 完整路径+文件名 "uploads/user/20190601/7060f88960ba0f86c3ea414c7e9af83c.jpeg"

            $result = file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_image_content))); // 大小

            if ($result) {
                $image = Image::open(ROOT_PATH.$new_file);
                $image->thumb(600,450,Image::THUMB_FIXED)->save(ROOT_PATH.$new_file);
                return $new_file;

            } else {
               return -1;
            }
        } else {
            return -2;
        }
    }
}