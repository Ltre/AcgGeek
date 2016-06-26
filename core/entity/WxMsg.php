<?php
/**
 * wechat 消息相关
 */
class WxMsg extends DIEntity {
    
    /**
     * 响应消息
     */
    static function getResponse($postStr){
        if (empty($postStr)) {
            return '';
        } else {
            /* libxml_disable_entity_loader is to prevent XML eXternal Entity Injection,
             the best way is to check the validity of xml by yourself */
            libxml_disable_entity_loader(true);
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $fromUsername = $postObj->FromUserName;
            $toUsername = $postObj->ToUserName;
            $keyword = trim($postObj->Content);
            $time = time();
            $textTpl = 
                "<xml>
				<ToUserName><![CDATA[%s]]></ToUserName>
				<FromUserName><![CDATA[%s]]></FromUserName>
				<CreateTime>%s</CreateTime>
				<MsgType><![CDATA[%s]]></MsgType>
				<Content><![CDATA[%s]]></Content>
				<FuncFlag>0</FuncFlag>
				</xml>";
//             if (empty($keyword)) {
                $msgType = "text";
                $contentStr = self::_sample($fromUsername, $keyword);
                $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                return $resultStr;
//             } else {
//                 return 'Input something...';
//             }
        }
    }
    
    
    
    private static function _sample($fromUsername, $msg){
        $msgList = self::_sampleStore('msgList') ?: array();
        $answers = self::_sampleStore('answers') ?: array();
        $modeKey = "mode.".sha1($fromUsername);
        $mode = self::_sampleStore($modeKey) ?: 'wait';//默认为等待话题模式
        if ($msg !== NULL) {
            $msgList[] = $msg;
            self::_sampleStore('msgList', $msgList);
        }
        if ($mode === 'wait') {
            self::_sampleStore($modeKey, 'chat');
            return "I'm a bot!";
        } elseif ($mode === 'learn') {
            $lastMsg = @$msgList[count($msgList) - 2];
            if ($lastMsg != '') {
                $answers[$lastMsg] = $msg;//学习答案
                self::_sampleStore('answers', $answers);
            }
            self::_sampleStore($modeKey, 'chat');
            return "已学习！";
        } elseif ($mode === 'chat') {
            if (isset($answers[$msg])) {
                return $answers[$msg];
            } else {
                self::_sampleStore($modeKey, 'learn');//遇到不懂的，改为学习模式
                return "纳尼索类意米挖干奶（快教我咋回答）";
            }
        }
    }
    
    
    private static function _sampleStore($key, $content = null){
        $file = DI_CACHE_PATH."wechat.samplemsg.{$key}";
        if (null === $content) return @unserialize(file_get_contents($file)) ?: null;
        else file_put_contents($file, serialize($content));
    }
    
    
}