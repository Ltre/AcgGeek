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
        if($this->checkSignature($signature, $timestamp, $nonce)){
            echo $echoStr;
            exit;
        }
    }
    
    function testAccessToken(){
        echo $this->_getAccessToken();
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
    
    
    //文档：https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421140183&token=&lang=zh_CN
    private function _getAccessToken(){
        $json = file_get_contents(DI_CACHE_PATH.'wechat.accesstoken');
        $data = json_decode($json, 1);
        if (isset($data['access_token'], $data['expires_in'], $data['start_time'])) {
            if ($data['start_time'] + $data['expires_in']/2 >= time()) {
                return $data['access_token'];
            }
        }
        $url = "https://api.weixin.qq.com/cgi-bin/token";
        $url .= '?'.http_build_query(array(
        	'grant_type' => 'client_credential',
            'appid' => $this->_getAppId(),
            'secret' => $this->_getAppSecret(),
        ));
        $http = new dwHttp();
        $ret = $http->get($url);
        $data = json_decode($ret, 1);
        if (isset($data['access_token'], $data['expires_in'])) {
            $data['start_time'] = time();
            file_put_contents(DI_CACHE_PATH.'wechat.accesstoken', json_encode($data));
            return $data['access_token'];
        } else {
            throw new DIException('can not gen access_token!');
        }
    }
    
    
    private function _getAppId(){
        return @file_get_contents(DI_CACHE_PATH.'wechat.appid') ?: '';
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