<?php
class MainDo extends DIDo {

    function start() {
        echo '<style>body{background-image: url(http://res.miku.us/res/img/default/2016/01/04/234621-381-hex8c.jpg); background-size:100%;}</style>';
        die('<div style="width:100%; text-align:center; position:fixed; bottom:0; margin-bottom:10px; font-size: 19px; font-family:微软雅黑;"><a href="http://larele.com/">Ltre Inc.</a> &copy; <a href="http://acggeek.com/">ACG极客</a> · <a href="http://www.miitbeian.gov.cn/">粤ICP备15066774号-3</a></div>');
    }
    
    function cms(){
        dump(DI_REGEXP_SHELL);
        die('main/cms');
    }

}