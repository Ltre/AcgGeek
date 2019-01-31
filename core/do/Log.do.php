<?php

class LogDo extends DIDo {

    function append(){
        $key = arg('key');
        $group = arg('group', 'MY-TMP-LOG');
        $str = arg('str', '');
        if (empty($key) || strlen($str) < 1) die('0');
        $url = $this->_appendLog($key, $str, $group);
        exit($url);
    }


    private function _getUrlHost(){
        if ('log.acggeek.com' == $GLOBALS['current_domain']) {
            $url = 'http://cms.acggeek.com/';
            $host = null;
        } else {
            $url = 'http://127.0.0.1/';
            $host = 'cms.acggeek.fuck';
        }
        return [$url, $host];
    }


    //现在向cms.acggeek写数据，必须先经过简单登录验证
    private function _loginCms(){
        list ($url, $host) = $this->_getUrlHost();
        $token = 'bh5005uiyrtcv849g1lk';
        $time = time();
        $params = array('time' => $time, 'sign' => sha1($token.$time));
        $this->_req($url, $params, $host);
    }


    private function _appendLog($key, $str, $group){
        $key = "{$group}/{$key}";
        // $keySplit = explode('/', $key);
        // foreach ($keySplit as $k => $v) $keySplit[$k] = urlencode($v);
        // $key = join('/', $keySplit);
        /*if ('log.acggeek.com' == $GLOBALS['current_domain']) {
            $url = 'http://cms.acggeek.com/';
            $host = null;
        } else {
            $url = 'http://127.0.0.1/';
            $host = 'cms.acggeek.fuck';
        }*/
        list ($url, $host) = $this->_getUrlHost();
        $ret = $this->_req($url.$key, array(), $host);
        if (false !== $ret) {
            $ret .= (''==$ret?'':"\r\n\r\n") . $str;
            $hehe = $this->_req($url, array(
                "setmirror/{$key}" => '',
                "data" => $ret,
            ), $host);
        }
        return $url.$key;
    }

    private function _req($url, $args, $host){
        import('net/dwHttp');
        $http = new dwHttp();
        if (empty($host)) {
            $ret = $http->post($url, $args);
        } else {
            $ret = $http->post($url, $args, 20, "Host: {$host}");
        }
        return $ret;
    }


    function test(){
        import('net/dwHttp');
        $key = 'wangba';
        $str = '测试4561100000000000000';
        if ($GLOBALS['current_domain'] == 'log.acggeek.com') {
            $url = 'http://log.acggeek.com/log/append';
            $host = null;
        } else {
            $url = 'http://127.0.0.1/log/append';
            $host = 'log.acggeek.fuck';
        }
        $this->_loginCms();
        $ret = $this->_req($url, array(
            'key' => $key, 
            'str' => $str
        ), $host);
        print_r($ret);
    }

}