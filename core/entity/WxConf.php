<?php
/**
 * wechat静态配置相关
 */
class WxConf extends DIEntity {
    
    static function setAppId($appId){
        file_put_contents(WX_CONF_FILE_APPID, $appId);
    }
    
    
    static function setAppSecret($secret){
        file_put_contents(WX_CONF_FILE_APPSECRET, $secret);
    }
    
    
    static function setToken($token){
        file_put_contents(WX_CONF_FILE_TOKEN, $token);
    }
    
    
    static function getAppId(){
        return @file_get_contents(DI_CACHE_PATH.'wechat.appid') ?: '';
    }
    
    
    static function getAppSecret(){
        return @file_get_contents(DI_CACHE_PATH.'wechat.appsecret') ?: '';
    }
    
    
    static function getToken(){
        return @file_get_contents(DI_CACHE_PATH.'wechat.token') ?: '';
    }
    
    
    //文档：https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421140183&token=&lang=zh_CN
    static function getAccessToken(){
        $json = file_get_contents(WX_CONF_FILE_ACCESSTOKEN);
        $data = json_decode($json, 1);
        if (isset($data['access_token'], $data['expires_in'], $data['start_time'])) {
            if ($data['start_time'] + $data['expires_in']/2 >= time()) {
                return $data['access_token'];
            }
        }
        $url = "https://api.weixin.qq.com/cgi-bin/token";
        $url .= '?'.http_build_query(array(
            'grant_type' => 'client_credential',
            'appid' => self::getAppId(),
            'secret' => self::getAppSecret(),
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
    
}
