<?php
namespace app\admin\controller;
use app\admin\model\DiyformModel;
use app\admin\model\DiyfieldModel;
use app\admin\model\FormconModel;
use app\admin\model\SendmailModel;
use think\Config;
use think\Db;
use PHPMailer\PHPMailer;

class Message extends Common
{
     public function index(){
		 $coffile = CONF_PATH.DS.'extra'.DS.'sys.php';
        if(request()->isAjax()){
            Config::load($coffile, '', 'sys');
            $conflist = Config::get('','sys');
            $param = input('post.');  
			$param['mp_send'] = array_key_exists("mp_send", $param) ? 1 : 0;
            $param['ali_send'] = array_key_exists("ali_send", $param) ? 1 : 0;
            $param = add_slashes_recursive($param);
            //$param['push_send'] = array_key_exists("push_send", $param) ? 1 : 0;
            setConfigfile($coffile, add_slashes_recursive(array_merge($conflist, $param)));
            return json(['code' => 1, 'data' => '', 'msg' => '设置成功']);
            exit();
        }

        return $this->fetch();
    }
 
 
  public function remind()
    {
        $coffile = CONF_PATH.DS.'extra'.DS.'sys.php';
        if(request()->isAjax()){
            Config::load($coffile, '', 'sys');
            $conflist = Config::get('','sys');
            $param = input('post.');  
            unset($param['mail_demo']);
            $param = add_slashes_recursive($param);

            setConfigfile($coffile, add_slashes_recursive(array_merge($conflist, $param)));
            return json(['code' => 1, 'data' => '', 'msg' => '更新设置成功']);
            exit();
        }      
    }
	
	  
  
  
 public function demomail(){
                    $param = input('param.');
			 $mail = new SendmailModel();
			 $param['subject']='测试邮件';
			 $param['msg']='当您看到此封邮件时，说明邮件提醒配置参数已经配置成功，感谢您的使用！';	 
			 $mail->sendmail($param);
			 
			 if (!$mail) {
				   return json(['code' => 0, 'data' => '', 'msg' => '邮件发送失败：']);	
		}
		else{
			 return json(['code' => 1, 'data' => '', 'msg' => '邮件发送成功']);	
			}
			 
 /* 
            $mail = new PHPMailer(true);
            $mail->CharSet = 'UTF-8';           //设定邮件编码，默认ISO-8859-1，如果发中文此项必须设置，否则乱码
            $mail->IsSMTP();                    // 设定使用SMTP服务
            $mail->SMTPDebug = 0;               // SMTP调试功能 0=关闭 1 = 错误和消息 2 = 消息
            $mail->SMTPAuth = true;             // 启用 SMTP 验证功能
            $mail->SMTPSecure = 'ssl';          // 使用安全协议
            $mail->Host = $param['mail_smtp']; // SMTP 服务器
            $mail->Port = $param['mail_smtpport'];                  // SMTP服务器的端口号
            $mail->Username = $param['mail_username'];    // SMTP服务器用户名
            $mail->Password = $param['mail_password'];     // SMTP服务器密码
            $mail->SetFrom($param['mail_username'], $param['mail_setname']);

            $mail->Subject = "测试邮件";
            $mail->MsgHTML("当您看到此封邮件时，说明邮件提醒配置参数已经配置成功，感谢您的使用！");
            $mail->AddAddress($param['mail_demo'], '');
            $jg =  $mail->Send() ? true : $mail->ErrorInfo;
            if ($jg != true) {
                return json(['code' => 2, 'data' => '', 'msg' => '测试邮件发送失败：'.$jg]);
            }else{
                return json(['code' => 1, 'data' => '', 'msg' => '测试邮件发送成功']);
            }
        
    }*/
	
}	
	
}
