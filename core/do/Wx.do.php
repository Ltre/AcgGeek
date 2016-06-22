<?php
/**
 * Wechat关键部分
 */
class WxDo extends DIDo {
    
    function setAppSecret($secret){
        file_put_contents(DI_DATA_PATH.'wechat.appsecret', $secret);
    }
    
}