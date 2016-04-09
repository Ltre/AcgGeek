<?php
class CmsDo extends DIDo {
    
    //http://acggeek.dev/?setmirror/test&data=testdata
    //http://acggeek.dev/?test
    //http://acggeek.dev/?list
    function get(){
        $setpath = preg_replace('/^setmirror\//i', '', DI_REGEXP_SHELL);
        if ($setpath != DI_REGEXP_SHELL) { //set mirror
            file_put_contents(DI_DATA_PATH.'cache/'.sha1($setpath), arg('data'));
            $this->_updateList($setpath);
        } else { //get mirror
            echo @file_get_contents(DI_DATA_PATH.'cache/'.sha1(DI_REGEXP_SHELL));
        }
    }
    
    protected function _updateList($path){
        $listFile = DI_DATA_PATH.'cache/'.sha1('list');
        @$listJson = file_get_contents($listFile) ?: '[]';
        $list = json_decode($listJson, 1);
        if (! in_array($path, $list)) {
            $list[] = $path;
        }
        file_put_contents($listFile, json_encode($list));
    }
    
}