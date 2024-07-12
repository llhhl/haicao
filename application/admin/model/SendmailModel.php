<?php
namespace app\admin\model;
use think\Model;
use think\Db;
use PHPMailer\PHPMailer;

class SendmailModel extends Model
{
 public function sendmail($param){ 
 /*
 
 mail_smtp
 mail_smtpport
 mail_username
 mail_password
 mail_setname
 subject
 msg
 mail_addr
 */

 $mail_smtp=isset($param['mail_smtp'])?$param['mail_smtp']:config("sys.mail_smtp");// SMTP 服务器
 $mail_smtpport=isset($param['mail_smtpport'])?$param['mail_smtpport']:config("sys.mail_smtpport");//服务器的端口号
 $mail_username=isset($param['mail_username'])?$param['mail_username']:config("sys.mail_username");// SMTP服务器用户名
 $mail_password=isset($param['mail_password'])?$param['mail_password']:config("sys.mail_password");// SMTP服务器密码
 $mail_setname=isset($param['mail_setname'])?$param['mail_setname']:config("sys.mail_setname");//发件人名称
 /*
$mail->Username = config("sys.mail_username");    // SMTP服务器用户名
$mail->Password = config("sys.mail_password");     // SMTP服务器密码
$mail->SetFrom(config("sys.mail_username"), config("sys.mail_setname"));
	
$mail->Subject = $this->updateMailCon($param, $diyformcon['mailtitle']);
$mail->MsgHTML($this->updateMailCon($param, $diyformcon['mailcontent']));
$mail->AddAddress($diyformcon['mailadd'], '');
$jg =  $mail->Send() ? true : $mail->ErrorInfo;
							
		echo("<br>mail_smtp=".$mail_smtp);
		echo("<br>mail_smtpport=".$mail_smtpport);	
		echo("<br>mail_username=".$mail_username);	
		echo("<br>mail_password=".$mail_password);	
		echo("<br>mail_setname=".$mail_setname);
		echo("<br>msg=".$param['msg']);
		echo("<br>subject=".$param['subject']);
		echo("<br>mail_addr=".$param['mail_addr']);
		*/			
            $mail = new PHPMailer(true);
            $mail->CharSet = 'UTF-8';           //设定邮件编码，默认ISO-8859-1，如果发中文此项必须设置，否则乱码
            $mail->IsSMTP();                    // 设定使用SMTP服务
            $mail->SMTPDebug = 0;               // SMTP调试功能 0=关闭 1 = 错误和消息 2 = 消息
            $mail->SMTPAuth = true;             // 启用 SMTP 验证功能
            $mail->SMTPSecure = 'ssl';          // 使用安全协议
            $mail->Host = $mail_smtp; // SMTP 服务器
            $mail->Port = $mail_smtpport;                  // SMTP服务器的端口号
            $mail->Username = $mail_username;    // SMTP服务器用户名
            $mail->Password = $mail_password;     // SMTP服务器密码
            $mail->SetFrom($mail_username, $mail_setname);
            $mail->Subject =$param['subject'];
            $mail->MsgHTML($param['msg']);
            $mail->AddAddress($param['mail_addr'], '');
            $jg =  $mail->Send() ? true : $mail->ErrorInfo;
            if ($jg != true) {
                return json(['code' => 0, 'data' => '', 'msg' =>$jg]);
            }else{
                return json(['code' => 1, 'data' => '', 'msg' => '邮件发送成功']);
            }
        
    }
}