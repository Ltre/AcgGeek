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
                $contentStr = self::_sample();
                $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                return $resultStr;
//             } else {
//                 return 'Input something...';
//             }
        }
    }
    
    
    
    private static function _sample(){
        $msgList = self::_sample('msgList') ?: array();
        $answers = self::_sample('answers') ?: array();
        $mode = self::_sample('mode') ?: 'wait';//默认为等待话题模式
        $msg = arg('msg');
        if ($msg !== NULL) {
            $msgList[] = $msg;
            self::_sample('msgList', $msgList);
        }
        if ($mode === 'wait') {
            return "I'm a bot!";
            self::_sample('mode', 'chat');
        } elseif ($mode === 'learn') {
            $lastMsg = @$msgList[count($msgList) - 2];
            if ($lastMsg != '') {
                $answers[$lastMsg] = $msg;//学习答案
                self::_sample('answers', $answers);
            }
            self::_sample('mode', 'chat');
            return "已学习！";
        } elseif ($mode === 'chat') {
            if (isset($answers[$msg])) {
                return $answers[$msg];
            } else {
                self::_sample('mode', 'learn');//遇到不懂的，改为学习模式
                return "纳尼索类意米挖干奶（快教我咋回答）";
            }
        }
    }
    
    
    private static function _sampleStore($key, $content = null){
        $file = DI_CACHE_PATH."wechat.samplemsg.{$key}";
        if (null === $file) return unserialize(file_get_contents($file));
        else file_put_contents($file, serialize($content));
    }
    
    
}