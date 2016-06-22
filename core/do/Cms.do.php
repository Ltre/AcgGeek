<?php
/**
 * CMS动态重写支持
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
    function get(){
        if (in_array(DI_REGEXP_SHELL, array(
            'main/start',
            'cms/mirror',
            'test/lets',
        ))) {
            dispatch(DI_REGEXP_SHELL);
        }
        
        if (preg_match('/^setmirror\/(.*)$/', DI_REGEXP_SHELL, $matches)) {
            $setpath = $matches[1];
            file_put_contents(DI_DATA_PATH.'cache/'.sha1($setpath), arg('data'));
            $this->_updateList($setpath, 'set');
        } elseif (preg_match('/^delmirror\/(.*)$/', DI_REGEXP_SHELL, $matches)) {
            $delpath = $matches[1];
            @unlink(DI_DATA_PATH.'cache/'.sha1($delpath));
            $this->_updateList($delpath, 'del');
        } elseif (preg_match('/^list\/?$/i', DI_REGEXP_SHELL)) {
            $cont = @file_get_contents(DI_DATA_PATH.'cache/'.sha1('list'));
            $arr = json_decode($cont, 1);
            unset($arr['mirror'], $arr['list']);
            echo json_encode($arr);
        } else {
            echo @file_get_contents(DI_DATA_PATH.'cache/'.sha1(DI_REGEXP_SHELL));
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
        $data = @file_get_contents(DI_DATA_PATH.'cache/'.sha1($path)) ?: '';
        $h = '<!DOCTYPE html><html><body>';
        $h .= '<form action="/" method="post" target="tmp">';
        $h .= '<input id="path" name="setmirror/'.$path.'" type="hidden"><br>';
        $h .= '<input id="on-writing-path" value="'.$path.'"><br>';
        $h .= '<textarea name="data">'.$data.'</textarea><br>';
        $h .= '<input type="submit">';
        $h .= '</form>';
        $h .= '<iframe name="tmp" style="display:none;"></iframe>';
        $h .= '<script>
            document.getElementById("on-writing-path").onkeyup = function(){
                document.getElementById("path").name = "setmirror/" + this.value;
                console.log(document.getElementById("path").name);
            };
        </script>';
        $h .= '</body></html>';
        echo $h;
    }
    
    
    protected function _updateList($path, $method='set'){
        $listFile = DI_DATA_PATH.'cache/'.sha1('list');
        @$listJson = file_get_contents($listFile) ?: '[]';
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
        
        file_put_contents($listFile, json_encode($list));
    }
    
}