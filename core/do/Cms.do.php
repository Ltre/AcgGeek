<?php
/**
 * CMS动态重写支持
 * 用于cms.acggeek.com
 */
class CmsDo extends DIDo {
    
    protected function _init(){
        if ('cms' != AG_CONST_REWRITE_MODE) {
            die('Error: not cms rewrite mode, can\'t request CmdDo!');
        }
    }
    
    /**
     * 入口指令：正则指令
     *      设置指定URL内容的接口：http://cms.acggeek.dev/?setmirror/{自定义路径}&data={自定义内容}
     *      删除指定URL内容的接口：http://cms.acggeek.dev/?delmirror/{自定义路径}
     *      访问指定URL的内容：http://cms.acggeek.dev/?{自定义路径}
     *      显示列表：http://cms.acggeek.dev/?list
     */
    function start(){
        if (in_array(DI_REGEXP_SHELL, array(
            'main/start',
            'cms/mirror',
            'test/lets',
        ))) {
            dispatch(DI_REGEXP_SHELL);
        }
        
        if (preg_match('/^setmirror\/(.*)$/', DI_REGEXP_SHELL, $matches)) {//设置内容
            $setpath = $matches[1];
            $this->_setContent($setpath, arg('data'));
            $this->_updateList($setpath, 'set');
        } elseif (preg_match('/^delmirror\/(.*)$/', DI_REGEXP_SHELL, $matches)) {//删除指定内容
            if (! $this->_isLogin()) die('NO LOGIN!');
            $delpath = $matches[1];
            $this->_delContent($delpath);
            $this->_updateList($delpath, 'del');
        } elseif (preg_match('/^list\/?$/i', DI_REGEXP_SHELL)) {//json列出内容列表
            if (! $this->_isLogin()) die(json_encode(array(), JSON_FORCE_OBJECT));
            $list = $this->_getList();
            echo json_encode($list, JSON_FORCE_OBJECT);
        } elseif (preg_match('/^listview\/?$/i', DI_REGEXP_SHELL)) {//list的视图形式
            if (! $this->_isLogin()) die('NO LOGIN!');
            echo $this->_getListviewHTML();
        } elseif (preg_match('/^cleartrash\/?$/i', DI_REGEXP_SHELL)) {
            if (! $this->_isLogin()) die('NO LOGIN!');
            $this->_clearTrash();//一些恶意请求，会插入空串内容的记录，使用该命令可清空
        } elseif (preg_match('/^fucklogin'.date('Ymd').'\/?$/i', DI_REGEXP_SHELL)) {//简单到不能再简单的登录，自动写入cookie
            $this->_setLogin();
        } else {//获取内容
            echo $this->_getContent(DI_REGEXP_SHELL);
        }
    }


    /**
     * 入口指令：cms/mirror
     * 工具页：可根据path增加或编辑内容
     *      增加模式：http://acggeek.dev/?cms/mirror
     *      编辑模式：http://acggeek.dev/?cms/mirror&path={自定义路径}
     */
    function mirror(){
        $path = arg('path') ?: '';
        $data = $this->_getFileContent($path);
        $h = '<!DOCTYPE html><html><body>';
        $h .= '<form action="/" method="post" target="tmp">';
        $h .= '<input id="path" name="setmirror/'.$path.'" type="hidden"><br>';
        $h .= '<input id="on-writing-path" value="'.$path.'">';
        $h .= '<input id="preview" type="button" value="预览"><br>';
        $h .= '<textarea name="data" style="width: 640px; height: 480px;">'.$data.'</textarea><br>';
        $h .= '<input type="submit">';
        $h .= '</form>';
        $h .= '<iframe name="tmp" style="display:none;"></iframe>';
        $h .= '<script>
            document.getElementById("on-writing-path").onkeyup = function(){
                document.getElementById("path").name = "setmirror/" + this.value;
                console.log(document.getElementById("path").name);
            };
            document.getElementById("preview").onclick = function(){
                var u = "/" + encodeURI(document.getElementById("on-writing-path").value);
                u != "/" && window.open(u, "_blank");
            };
        </script>';
        $h .= '</body></html>';
        echo $h;
    }


    //定制的可操作列表视图页
    function _getListviewHTML(){
        $html = '<!DOCTYPE html>
            <html>
            <head>
            <script src="//cdn.bootcss.com/jquery/2.2.0/jquery.min.js"></script>
            </head>
            <body>
            <script>
            $.get(\'/list\', function(j){
            $.each(j, function(i, e){
            document.write(\'<a href="/\'+i+\'" target="_blank">\'+i+\'</a><br>\');
            });
            }, \'json\');
            </script>
            </body>
            </html>';
        return $html;
    }
    

    protected function _getList(){
        $cont = $this->_getContent('list') ?: '[]';
        $arr = json_decode($cont, 1);
        $arr = array_reverse($arr, true);//按清单文件添加顺序的倒序排列，如遇数字键，则JS不能针对这些键倒序排列
        //unset($arr['mirror'], $arr['list']); 
        foreach ($this->_getItemNamesNoWrite() as $v) {
            unset($arr[$v]);//屏蔽特殊的项
        }
        return $arr;
    }

    
    //在列表设置、更改某一项
    protected function _updateList($path, $method='set'){
        @$listJson = $this->_getContent('list') ?: '[]';
        $list = json_decode($listJson, 1);
        
        switch (strtolower($method)) {
        	case 'set': 
                if (! in_array($path, $list)) {
                    $list[$path] = sha1($path);
                }
                break;
        	case 'del':
        	    unset($list[$path]);
        	    break;
        }
        
        $this->_setContent('list', json_encode($list));
    }


    protected function _getContent($name){
        $mixedName = $this->_getMixedDataName($name);
        $content = Mixed::get($mixedName);
        if (null !== $content) return $content;
        $content = $this->_getFileContent($name);
        Mixed::set($mixedName, $content);
        return $content;
    }

    protected function _getFileContent($name){
        if (empty($name) && ! is_numeric($name)) return '';
        $file = $this->_getHashFile($name);
        if (! file_exists($file)) return '';
        $content = file_get_contents($file);
        $content = $content === false ? '' : $content;
        return $content;
    }

    protected function _setContent($name, $content){
        if (in_array($name, $this->_getItemNamesNoWrite())) return;
        $mixedName = $this->_getMixedDataName($name);
        Mixed::set($mixedName, $content);
        $file = $this->_getHashFile($name);
        file_put_contents($file, $content);
    }

    protected function _delContent($name){
        if (in_array($name, $this->_getItemNamesNoWrite())) return;
        $mixedName = $this->_getMixedDataName($name);
        Mixed::setValid($mixedName, 0);
        $file = $this->_getHashFile($name);
        @unlink($file);
    }

    protected function _getHashFile($name){
        $file = DI_DATA_PATH.'cache/'.sha1($name);
        return $file;
    }

    protected function _clearTrash(){
        $driver = new SeniorModel;
        $mixedName = $this->_getMixedDataName('');
        $sql = "DELETE FROM agk_mixed WHERE `name` LIKE :name AND `content` = '' ";
        $op = supermodel()->execute($sql, array('name' => $mixedName.'%'));
        echo 'result is ' . ($op !== false ? 'success' : 'failure') . ',<br>';
        echo 'clear ' . intval($op) . ' trash items.';
    }
    
    //获取mixed表数据用的带前缀name，避免存储污染
    protected function _getMixedDataName($name){
        return 'cms.acggeek: '.$name;
    }

    //不进行读写的项
    protected function _getItemNamesNoWrite(){
        return array('mirror', 'setmirror', 'delmirror', 'listview');
    }


    //设置简易的登录态
    protected function _setLogin(){
        $lgkey = str_replace('.', '', microtime(1));
        setcookie('lgkey', $lgkey, time() + 259200, '/');
        session('lgkey', $lgkey);
    }


    //判断登录态
    protected function _isLogin(){
        return $this->_isRPCLogin() || $this->_isClientLogin();
    }


    //判断登录态：提供给远程调用方(通常是服务端调用)
    protected function _isRPCLogin(){
        $token = 'bh5005uiyrtcv849g1lk';
        $time = arg('time');
        $sign = arg('sign');
        return sha1($token.$time) == $sign && $time - time() <= 60;
    }


    //判断客户端登录态
    protected function _isClientLogin(){
        return ! empty(session('lgkey')) && @$_COOKIE['lgkey'] == session('lgkey');
    }

}