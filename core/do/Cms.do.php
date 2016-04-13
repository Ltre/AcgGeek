<?php
class CmsDo extends DIDo {
    
    //http://acggeek.dev/?setmirror/test&data=testdata
    //http://acggeek.dev/?test
    //http://acggeek.dev/?list
    function get(){
        if (in_array(DI_REGEXP_SHELL, array('main/start', 'main/mirror'))) {
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
            echo @file_get_contents(DI_DATA_PATH.'cache/'.sha1('list'));
        } else {
            echo @file_get_contents(DI_DATA_PATH.'cache/'.sha1(DI_REGEXP_SHELL));
        }
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