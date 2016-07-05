<?php
import('net/dwHttp');

/**
 * Wechat关键部分
 */
class WxDo extends DIDo {
    
    function start(){
        $signature = arg('signature');
        $timestamp = arg('timestamp');
        $nonce = arg('nonce');
        $echoStr = arg('echostr');
        if (isset($_GET['echostr'])) { //验证公众号绑定
            if (WxValidate::checkBind($signature, $timestamp, $nonce)) echo $echoStr;
            exit;
        } else { //其它类型请求
            $postStr = @$GLOBALS["HTTP_RAW_POST_DATA"] ?: file_get_contents("php://input");
            echo WxMsg::getResponse($postStr);
        }
    }


    function testAccessToken(){
        echo WxConf::getAccessToken();
    }
    

    function setAppId($appId){
        WxConf::setAppId($appId);
    }
    

    function setAppSecret($secret){
        WxConf::setAppSecret($secret);
    }
    
    
    function setToken($token){
        WxConf::setToken($token);
    }
    
}