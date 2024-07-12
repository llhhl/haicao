<?php
namespace app\admin\model;
use think\Model;
use think\Db;
use app\admin\model\DiyfieldModel;
use PHPMailer\PHPMailer;
use app\admin\model\SendmailModel;
use app\admin\model\SmsModel;
use app\admin\model\MpTempModel;
use app\admin\model\SendMpModel;
class FormModelSend extends Model
{
    protected $name = 'diyform';

    public function getAlldiyform()
    {
        return $this->order('id desc')->select();
    }

    public function insertDiyform($param)
    {
        try{
            $param['status'] = array_key_exists("status", $param) ? 1 : 0;
            $result = $this->validate('DiyformValidate')->save($param);
            if(false === $result){            
                writelog(session('admin_uid'),session('admin_username'),'用户【'.session('admin_username').'】创建表单失败',2);
                return ['code' => -1, 'data' => '', 'msg' => $this->getError()];
            }else{
                writelog(session('admin_uid'),session('admin_username'),'用户【'.session('admin_username').'】创建表单成功',1);
                return ['code' => 1, 'data' => '', 'msg' => '添加表单成功'];
            }
        }catch( PDOException $e){
            
        }
    }

    public function insertForm($param, $mid){
		
		 try{
            $dbfield = DB::name('diyfield');
            foreach ($param as $k => $v) {
                if (is_array($v)) {
                    $param[$k] = implode(',', $v);
                }
                //验证必填
                $fielddata = $dbfield->where(['field'=>$k,'mid'=>$mid])->find();
                if ($fielddata) {
                    if ($fielddata['isnotnull'] == 1) {
                        if (!$v) {
                            return ['status' => 'error', 'msg' => $fielddata['title']."请正确填写！"];
                        }
                    }
                }
            }

            //验证验证码
					if (isset($param['__captcha'.$mid."__"])) {
						$captcha = new \tpcaptcha\Captcha();
						if (!$captcha->check($param['__captcha'.$mid."__"], $mid)) {
							return ['status' => 'error', 'msg' => "验证错误"];
						}
					}

            		$diyformcon = DB::name('diyform')->where(['id'=>$mid])->find();
					if($diyformcon['status']=='1'){//是否开启状态
						try{	
							$param['fid'] = $mid;
							$param['vid'] = DB::name('form_'.$diyformcon['tabname'])->strict(false)->insertGetId($param);
							$param['create_time'] = time();
							$param['update_time'] = time();
							$param['ip'] = request()->ip();
				
							$result = DB::name('formcon')->strict(false)->insertGetId($param);
							if(false === $result){            
								return ['status' => 'error', 'msg' => $this->getError()];
							}else{
							   				unset($param['__token__']);
											unset($param['__formid__']);
											unset($param['__returntype__']);
											unset($param['fid']);
											unset($param['create_time']);
											unset($param['update_time']);
											unset($param['vid']);
											unset($param['ip']);  
											
								
								if($diyformcon['smswarn']&& $diyformcon['smsadd']&& $diyformcon['smstemp']){//触发表单短信发送
									//echo("短信发送");
								/*
								触发表单短信发送
								------------------------------------------------------------
								*/
							
								try{			
											$Notes = new SmsModel();
											$row = $Notes->getSms($diyformcon['smsadd'],$param,$diyformcon['smstemp'],$diyformcon['smssign']);//执行getNotes，发送短信
								}
								catch( PDOException $e){
								return ['status' => 'error', 'msg' => $e->getMessage()];
								}
								
						}
								/*
								触发表单邮件发送
								------------------------------------------------------------
								*/
									if ($diyformcon['mailwarn'] && $diyformcon['mailadd'] && $diyformcon['mailcontent']) { 
									//echo("邮件发送");
										try{
											 $mail = new SendmailModel();
											 $param['subject']=$this->updateMailCon($param, $diyformcon['mailtitle']);;
											 $param['msg']=$this->updateMailCon($param, $diyformcon['mailcontent']);
											 $param['mail_addr']= $diyformcon['mailadd'];												 			
											 $mail->sendmail($param);
										}
										catch( PDOException $e){
										return ['status' => 'error', 'msg' => $e->getMessage()];
										}										
									}
									/*
								触发表单邮件发送end
								------------------------------------------------------------
								*/
								}
							}
							catch( PDOException $e){
							return ['status' => 'error', 'msg' => $e->getMessage()];
							}
								
								/*
								触发公众号消息发送
								------------------------------------------------------------
								*/	
								
							if ($diyformcon['mpwarn'] && $diyformcon['mptemp'] && $diyformcon['mpaddr']) {
								$appId = config('sys.mp_appId');//公众号id
            					$appSecret = config('sys.mp_appSecret');//公众号秘钥 
								 $mptemp = new MpTempModel();
								$keya=$mptemp->getMpTemp($diyformcon['mptemp']);
																						
								$keywords=unserialize($keya['data']);	
								$data = array();							
								//var_dump($keywords);
									$data['first'] = array('value' =>$keya['first'], 'color' => $keya['firstcolor']);
									if (!empty($keywords)) {
										
										foreach ($keywords as $key) {
										$data[$key['tp_kw']] = array('value' =>$key['tp_value'], 'color' => $key['tp_color']);
										}
										//var_dump($data);	
										
										$data['remark'] = array('value' =>$keya['tp_remark'], 'color' => $keya['remarkcolor']);									
									}
									try{
										 $mpsend = new SendMpModel();
										$send=$mpsend->sendmpmessage($appId,$appSecret,$diyformcon['mpaddr'],$keya['template_id'],$data,'');
											
									}
									catch( PDOException $e){
										return ['status' => 'error', 'msg' => $e->getMessage()];
									}	
									
							}
							
							/*
								触发公众号消息发送end
								------------------------------------------------------------
							*/	
								
							
								return ['status' => 'success', 'msg' => '提交成功'];
						
					}
					else{
							return ['status' => '0', 'msg' => '请打开表单状态'];
					}
				}
				
				catch( PDOException $e){
					return ['status' => 'error', 'msg' =>'请检查配置文件及是否关注公众号'];
				}
		 
    }

    public function updateMailCon($param, $mailcon)
    {
       
        $preg = '/%[\s\S]*?\%/i';
        preg_match_all($preg, $mailcon, $filelist);
        if ($filelist) {
            foreach ($filelist[0] as $k => $v) {
                $filename = str_replace("%", "", $v);
                if (array_key_exists($filename, $param)) {
                    $mailcon = str_replace($v, $param[$filename], $mailcon);
                } 
            }
        }
        return $mailcon;
    }

    public function getOneDiyform($id)
    {
        return $this->where(['id'=>$id])->find();
    }

    public function getOneFormcon($info)
    {
        $tabname = $this->where(['id'=>$info['fid']])->value('tabname');
        $info2 = DB::name('form_'.$tabname)->where(['conid'=>$info['vid']])->find();
        return array_merge($info, $info2);
    }
}