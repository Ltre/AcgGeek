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
            '://www.acggeek.com' => 'main/start',
            '://wx.acggeek.com' => 'wx/start',
            '://wx.acggeek.com/wx' => 'wx/start',
            '://wx.acggeek.com/<F>' => 'wx/<F>',
            '://log.acggeek.com' => 'log/start',
            '://log.acggeek.com/log' => 'log/start',
            '://log.acggeek.com/<F>' => 'log/<F>',
            '://acggeek.com' => 'main/start',
		    
            '://acggeek.larele.com' => 'main/start',
            '://wx.larele.com' => 'wx/start',
            '://wx.larele.com/wx' => 'wx/start',
            '://wx.larele.com/<F>' => 'wx/<F>',
            '://log.larele.com' => 'log/start',
            '://log.larele.com/log' => 'log/start',
            '://log.larele.com/<F>' => 'log/<F>',
            
            '://www.acggeek.fuck' => 'main/start',
            '://wx.acggeek.fuck' => 'wx/start',
            '://wx.acggeek.fuck/wx' => 'wx/start',
            '://wx.acggeek.fuck/<F>' => 'wx/<F>',
            '://log.acggeek.fuck' => 'log/start',
            '://log.acggeek.fuck/log' => 'log/start',
            '://log.acggeek.fuck/<F>' => 'log/<F>',
            '://acggeek.fuck' => 'main/start',
            
            '://acggeek.webdev.duowan.com' => 'main/start',
            
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
