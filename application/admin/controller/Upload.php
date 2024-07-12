<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;
use think\Env;
use think\File;
use think\Request;
use com\Dir;

use Qiniu\Auth as Auth;
use Qiniu\Storage\BucketManager;
use Qiniu\Storage\UploadManager;

class Upload extends Common
{
	//图片上传
  	public function upload(){

	    $file = request()->file(input('file'));

	    $info = $file->validate(['ext'=>config('sys.upload_image_ext'),'size'=>config('sys.upload_image_size') * 1024])->move(ROOT_PATH.'uploads/image/');
	    if($info){
		    $fileinfo = $info->getInfo();
		    $res['name'] = mb_substr($fileinfo['name'], 0,  -4, "UTF-8"); 
		 	$res['status'] = 1;
		    $res['image_name'] = '/uploads/image/'.str_replace("\\", "/", $info->getSaveName());


		    if (config('sys.image_watermark')) {
                $image = \Image\Image::open('.'.$res['image_name']);
     
                if (config('sys.image_watermark') == 1) {
                   	$font = '.'.config('sys.image_watermark_text_font');
                    if (is_file($font)) {
                        $image->text(config('sys.image_watermark_text'), $font, config('sys.image_watermark_text_size'), "#".config('sys.image_watermark_text_color'),config('sys.image_watermark_pos'),0,config('sys.image_watermark_text_angle'))
                        ->save('.'.$res['image_name']); 
                    }else{
                       	$res['status'] = 0; 
				        $res['error_info'] = '水印文字，不存在的字体文件';
				        return json_encode($res);
                    }
                }else{
                    if (is_file('.'.config('sys.image_watermark_pic'))) {
                        $image->water('.'.config('sys.image_watermark_pic'), config('sys.image_watermark_pos'), config('sys.image_watermark_pic_opacity'))
                        ->save('.'.$res['image_name']); 
                    }else{
                       	$res['status'] = 0; 
				       	$res['error_info'] = '水印图片，不存在的图片文件';
				        return json_encode($res);
                    }
                }
            } 

	        if (config('sys.qiniu')) {
			    try {
                    require_once Env::get("root_path") . 'vendor/php-sdk-7.2.10/autoload.php';
				    //上传到七牛后保存的文件名
				    $key = substr($res['image_name'], 1);

				    // 需要填写你的 Access Key 和 Secret Key
				    $accessKey = config('sys.qiniu_accesskey');
				    $secretKey = config('sys.qiniu_secretkey');
				    $auth = new Auth($accessKey, $secretKey);
				    $bucket = config('sys.qiniu_bucket');
				    $domain = config('sys.qiniu_domain');
				    $token = $auth->uploadToken($bucket);

				    $uploadMgr = new UploadManager();
				    list($ret, $err) = $uploadMgr->putFile($token, $key, '.'.$res['image_name']);
				    if ($err !== null) {
				        $res['status'] = 0;
				        $res['error_info'] = is_object($err) ? "七牛云配置异常，请检查" : $err ;
				    } else {
				        $res['name'] = mb_substr($file->getInfo('name'), 0,  -4, "UTF-8"); 
				        $res['status'] = 1;
				        $res['image_name'] = $domain . $ret['key'];
				    }
			    } catch (Exception $e) {
			       	$res['status'] = 0; 
			        $res['error_info'] = '七牛云配置异常，请检查';
			        return json_encode($res);
			    }
		   	}

	   	}else{
		    $res['status'] = 0; 
		    $res['error_info'] = $file->getError();
	    }
        $data = json_encode($res);
        exit($data);
	}

	//上传ico
	public function uploadico(){
	    $file = request()->file(input('file'));
	    $info = $file->validate(['ext'=>'ico'])->move(ROOT_PATH, 'favicon.ico');
	    if($info){
		    $fileinfo = $info->getInfo();
		    $res['name'] = ''; 
		 	$res['status'] = 1;
		    $res['image_name'] = '/favicon.ico';

	   	}else{
		    $res['status'] = 0; 
		    $res['error_info'] = $file->getError();
	    }
	    return json_encode($res);
	}

  	//文件上传
  	public function uploadfile(){
        $file = request()->file(input('file'));
	    $info = $file->validate([
	        'ext'=>config('sys.upload_file_ext'),
            'size'=>config('sys.upload_file_size')*1024
        ])->move(ROOT_PATH.'uploads/file/');
	    if($info){
		    $res['status'] = 1;
		    $res['file_name'] = $info->getFilename();
		    $res['file_path'] = '/uploads/file/'.str_replace("\\", "/", $info->getSaveName());

		    if (config('sys.qiniu')) {
		    	try {
                    require_once Env::get("root_path") . 'vendor/php-sdk-7.2.10/autoload.php';
			      	// 上传到七牛后保存的文件名
			      	$key = substr($res['file_path'], 1);

			      	// 需要填写你的 Access Key 和 Secret Key
			      	$accessKey = config('sys.qiniu_accesskey');
				    $secretKey = config('sys.qiniu_secretkey');
				    $auth = new Auth($accessKey, $secretKey);
				    $bucket = config('sys.qiniu_bucket');
				    $domain = config('sys.qiniu_domain');
				    $token = $auth->uploadToken($bucket);

				    $uploadMgr = new UploadManager();
			      	list($ret, $err) = $uploadMgr->putFile($token, $key, '.'.$res['file_path']);
			      	if ($err !== null) {
				        $res['status'] = 0;
				        $res['error_info'] = is_object($err) ? "七牛云配置异常，请检查" : $err ;
			      	} else {
				        $res['name'] = mb_substr($file->getInfo('name'), 0,  -4, "UTF-8");
				        $res['status'] = 1;
				        $res['file_path'] = $domain . $ret['key'];

                        $data = [
                            "name"=>mb_substr($file->getInfo('name'), 0,  -4, "UTF-8"),
                            "status"=>1,
                            "file_path"=>$domain . $ret['key']
                        ];
                        $data = json_encode($data);
                        exit($data);

			      	}
			    } catch (Exception $e) {
                    $data = [
                        "status"=>0,
                        "error_info"=>'七牛云配置异常，请检查'
                    ];
                    $data = json_encode($data);
                    exit($data);
		      	}
	    	}
	    }else{
            $data = [
                "status"=>0,
                "error_info"=>'七牛配置异常:'.$file->getError(),
            ];
            $data = json_encode($data);
            exit($data);
	    }
        $data = [
            "name"=>mb_substr($file->getInfo('name'), 0,  -4, "UTF-8"),
            "status"=>1,
            "file_path"=>$domain . $ret['key']
        ];
        $data = json_encode($data);
        exit($data);
  	}

    //文件/夹管理
  	public function browsefile($spath = '', $stype = 'picture') {
	    $spath = input('spath');
	    $stype = input('stype');
	    $docname = input('docname');

	    $base_path = '/uploads/img';
	    $enocdeflag = input('encodeflag', 0, 'intval');
	    switch ($stype) {
	      	case 'picture':
	        	$base_path = '/uploads/image';
	        	break;
	      	case 'file':
		        $base_path = '/uploads/file';
		        break;      
	      	default:
	        	exit('参数错误');
	        	break;
	    }
	    if ($enocdeflag) {
	      	$spath = base64_decode($spath);
	    }
	    $spath = str_replace('.', '', ltrim($spath,$base_path));
	    $path = $base_path . '/'. $spath;

	    $dirlist = new Dir('.'.$path);//加上.      '.'.$path

	    $list = $dirlist->toArray();

	    for ($i=0; $i < count($list); $i++) { 
	      	$list[$i]['isImg'] = 0;
	      	if ($list[$i]['isFile']) {
		        $url = rtrim($path,'/') . '/'. $list[$i]['filename'];
		        $ext = explode('.', $list[$i]['filename']);
		        $ext = end($ext);
		        if (in_array($ext, explode(',', config('sys.upload_file_ext')))) {
		          	$list[$i]['isImg'] = 1;
		        }
	      	}else {
	        	$url = url('upload/browsefile', array('docname' => $docname,'spath'=>base64_encode(rtrim($path,'/') . '/'. $list[$i]['filename']),'stype' => $stype, 'encodeflag' => 1 ));
	      	} 
	      	$list[$i]['url'] = $url;      
	      	$list[$i]['size'] = get_byte($list[$i]['size']);
	    }
	    $last_names = $this->array_column($list, 'filename');
		array_multisort($last_names, SORT_DESC, $list);

	    //p($list);
	    $parentpath = substr($path, 0, strrpos($path, '/'));
	    $this->assign([
	      	'purl'=>  url('upload/browsefile', array('docname' => $docname,'spath'=> base64_encode($parentpath),'encodeflag' => 1, 'stype' => $stype)),
	      	'infolist'=> $list,
	      	'docname'=> $docname,
	      	'stype'=> $stype
	    ]);
	    return $this->fetch();
	}
	
	function array_column($input, $columnKey, $indexKey = null)
		{
			if (!function_exists('array_column')) {
				$columnKeyIsNumber = (is_numeric($columnKey)) ? true : false;
				$indexKeyIsNull = (is_null($indexKey)) ? true : false;
				$indexKeyIsNumber = (is_numeric($indexKey)) ? true : false;
				$result = array();
				foreach ((array)$input as $key => $row) {
					if ($columnKeyIsNumber) {
						$tmp = array_slice($row, $columnKey, 1);
						$tmp = (is_array($tmp) && !empty($tmp)) ? current($tmp) : null;
					} else {
						$tmp = isset($row[$columnKey]) ? $row[$columnKey] : null;
					}
					if (!$indexKeyIsNull) {
						if ($indexKeyIsNumber) {
							$key = array_slice($row, $indexKey, 1);
							$key = (is_array($key) && !empty($key)) ? current($key) : null;
							$key = is_null($key) ? 0 : $key;
						} else {
							$key = isset($row[$indexKey]) ? $row[$indexKey] : 0;
						}
					}
					$result[$key] = $tmp;
				}
				return $result;
			} else {
				return array_column($input, $columnKey, $indexKey);
			}
		}

    // 多文件上传
    public function diyupload(){
        if (request()->isPost()) {
            $files = $this->request->file('files');
            if (!$files) {
                return $this->error('请上传文件');
            }
            $params = input('param.');
            $validate = new \app\admin\Validate\Upload();
            if(!$validate->check($params)){
                error(1,$validate->getError());
            }

            $imgs = [];
            $img_str = '';
            foreach ($files as $key => $file) {
                $info = $file->validate(['size'=>10485760,'ext'=>'jpg,png,gif'])->rule('date')->move(ROOT_PATH . DS.'uploads'.DS.'image'.DS.$params['filepath']);
                if ($info) {
                    //写入到附件表
                    $data = [];
                    $data['filename'] = $info->getFilename();//文件名
                    $data['filepath'] = DS . 'uploads' . DS . 'image'.DS.$params['filepath'] . DS . $info->getFilename();//文件路径

                    $data['fileext'] = $info->getExtension();//文件后缀
                    $data['filesize'] = $info->getSize();//文件大小
                    $data['create_time'] = time();//时间
                    $data['uploadip'] = $this->request->ip();//IP
                    $data['status'] = 1;
                    $res['id'] = Db::name('attachment')->insertGetId($data);
                    $res['src'] = DS . 'uploads' . DS . 'image'.DS.$params['filepath'] . DS . $info->getSaveName();
                    $img_str .= $res['id'] . ',';
                    array_push($imgs, $res);
                } else {
                    // 上传失败获取错误信息
                    return $this->error('上传失败：' . $files->getError());
                }
            }
            $r_data['img_ids'] = substr($img_str, 0, strlen($img_str) - 1);
            $r_data['img_info'] = $imgs;
            success(0,"上传成功",$r_data);
        }else{
            error(1,"请求类型错误");
        }
    }
}