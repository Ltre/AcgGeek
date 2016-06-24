<?php
/**
 * wechat安全性验证
 */
class WxValidate extends DIEntity {
    
    /**
     * 验证公众号绑定
     */
    static function checkBind($signature, $timestamp, $nonce){
        $token = WxConf::getToken();
        if (empty($token)) {
            throw new Exception('TOKEN is not defined!');
        }
        if (empty($timestamp) || abs(time()-$timestamp) > 60) {
            throw new Exception('time expired');
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
