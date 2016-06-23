<?php
/**
 * Wechat关键部分
 */
class WxDo extends DIDo {
    
    function start(){
        $signature = arg('signature');
        $timestamp = arg('timestamp');
        $nonce = arg('nonce');
        $echoStr = arg('echostr');
        if($this->checkSignature($signature, $timestamp, $nonce)){
            echo $echoStr;
            exit;
        }
    }
    
    
    function setAppId($appId){
        file_put_contents(DI_CACHE_PATH.'wechat.appid', $appId);
    }
    
    
    function setAppSecret($secret){
        file_put_contents(DI_CACHE_PATH.'wechat.appsecret', $secret);
    }
    
    
    function setToken($token){
        file_put_contents(DI_CACHE_PATH.'wechat.token', $token);
    }
    
    
    private function _getAppSecret(){
        return @file_get_contents(DI_CACHE_PATH.'wechat.appsecret') ?: '';
    }
    
    
    private function _getToken(){
        return @file_get_contents(DI_CACHE_PATH.'wechat.token') ?: '';
    }
    
    
    private function checkSignature($signature, $timestamp, $nonce){
        $token = $this->_getToken();
        if (empty($token)) {
            throw new Exception('TOKEN is not defined!');
        }
    
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );
    
        if ($tmpStr == $signature) {
            return true;
        } else {
            return false;
        }
    }
    
}