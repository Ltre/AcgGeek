<?php
class MainDo extends DIDo {

    function start() {
        echo '<style>body{background-image: url(http://res.miku.us/res/img/default/2016/01/04/234621-381-hex8c.jpg); background-size:100%;}</style>';
        die('<div style="width:100%; text-align:center; position:fixed; bottom:0; margin-bottom:10px; font-size: 19px; font-family:微软雅黑;"><a href="http://larele.com/">Ltre Inc.</a> &copy; <a href="http://acggeek.com/">ACG极客</a> · <a href="http://www.miitbeian.gov.cn/">粤ICP备15066774号-3</a></div>');
    }
    
    function mirror(){
        $h = '<!DOCTYPE html><html><body>';
        $h .= '<form id="mirrorform" action="/" method="post" target="tmp">';
        $h .= '<input id="path" name="setmirror"><br>';
        $h .= '<textarea name="data"></textarea><br>';
        $h .= '<input type="submit">';
        $h .= '</form>';
        $h .= '<iframe name="tmp" style="display:none;"></iframe>';
        $h .= '<script>
            function hehe(){
                var domPath = document.getElementById("path");
                var path = domPath.value;
                domPath.name = "setmirror/" + path;
                domPath.value = "";
            }
            document.getElementsByTagName("form")[0].setAttribute("onsubmit", "return hehe()");
        </script>';
        $h .= '</body></html>';
        echo $h;
    }
    
}