<?php
/**
  * wechat php test
  */
 
//define your token
define("TOKEN", "weixin");
$wechatObj = new wechatCallbackapiTest();
$wechatObj->responseMsg();//执行接受器方法
class wechatCallbackapiTest
{
    public function valid()
    {
        $echoStr = $_GET["echostr"];
 
        //valid signature , option
        if($this->checkSignature()){
            echo $echoStr;
            exit;
        }
    }
    
    public function responseMsg()
    {
        //get post data, May be due to the different environments
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
 
        //extract post data
        if (!empty($postStr)){
                /* libxml_disable_entity_loader is to prevent XML eXternal Entity Injection,
                   the best way is to check the validity of xml by yourself */
                libxml_disable_entity_loader(true);
                $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
                $fromUsername = $postObj->FromUserName;
                $toUsername = $postObj->ToUserName;
                $keyword = trim($postObj->Content);
                $msgType = $postObj->MsgType;//消息类型
                $event = $postObj->Event;//时间类型，subscribe（订阅）、unsubscribe（取消订阅）
                $time = time();
                $textTpl = "<xml>
                            <ToUserName><![CDATA[%s]]></ToUserName>
                            <FromUserName><![CDATA[%s]]></FromUserName>
                            <CreateTime>%s</CreateTime>
                            <MsgType><![CDATA[%s]]></MsgType>
                            <Content><![CDATA[%s]]></Content>
                            <FuncFlag>0</FuncFlag>
                            </xml>";  
                switch($msgType){
                   case "event":
                    if($event=="subscribe"){
                    $contentStr = "Hi,欢迎关注!"."\n"."回复数字'1',了解店铺地址."."\n"."回复数字'2',了解商品种类.";
                    } 
                    break;
                    case "text":
                    switch($keyword){
                    case "1":
                    $contentStr = "店铺地址："."\n"."重庆"; 
                    break;
                    case "2":
                    $contentStr = "商品种类:"."\n"."杯子、碗、棉签、水桶、垃圾桶、洗碗巾(刷)、拖把、扫把、"
                     ."衣架、粘钩、牙签、垃圾袋、保鲜袋(膜)、剪刀、水果刀、饭盒等.";
                    break;
                    default:
                    $contentStr = "对不起,你的内容我会稍后回复";
                    }
                    break;
                  }
                   $msgType = "text";
                   $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                   echo $resultStr;
                
        }else {
            echo "";
            exit;
        }
    }
    

    private function checkSignature()
    {
        // you must define TOKEN by yourself
        if (!defined("TOKEN")) {
            throw new Exception('TOKEN is not defined!');
        }
         
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
                 
        $token = TOKEN;
        $tmpArr = array($token, $timestamp, $nonce);
        // use SORT_STRING rule
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );
         
        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }
}
 
?>