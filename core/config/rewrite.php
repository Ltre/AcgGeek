<?php
class DIRouteRewrite {
    
    /**
     * 自定义路由重写规则
     * 书写原则，特殊在前，通用在后
     * 详见：
     *      DIRoute::rewrite() @ __route.php
     */
    static $rulesMap = array(
    );
    
    /**
     * 不需要重写的
     * 左侧为相对于脚本目录的URI
     * 右侧表示重写失败时是否终止程序
     * 这些规则不受常量DI_KILL_ON_FAIL_REWRITE影响
     */
    static $withoutMap = array(
        'index.php' => false,
        'index.html' => false,
        'index.htm' => false,
        'favicon.ico' => true,
        'robots.txt' => true,
    );
    
}

//重写模式选择，详见const.php
switch (AG_CONST_REWRITE_MODE) {
	case 'normal':
	    DIRouteRewrite::$rulesMap = array(
            '://acggeek.dev' => 'main/start',
            '://www.acggeek.com' => 'main/start',
            '://acggeek.com' => 'main/start',
            '://acggeek.webdev.duowan.com' => 'main/start',
            '://wx.acggeek.dev' => 'wx/start',
            '://wx.acggeek.com' => 'wx/start',
            
            '<D>' => '<D>/start',
            '<D>/<F>' => '<D>/<F>',
            '<D>-<F>' => '<D>/<F>',
            '<A>.<B>' => '<A>.<B>', 
	    );
	    break;
	case 'cms':
	    DIRouteRewrite::$rulesMap = array(
            'mirror' => 'cms/mirror',
	    );
	    break;
}