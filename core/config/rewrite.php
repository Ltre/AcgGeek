<?php
class DIRouteRewrite {
    
    /**
     * 自定义路由重写规则
     * 书写原则，特殊在前，通用在后
     * 详见：
     *      DIRoute::rewrite() @ __route.php
     */
    static $rulesMap = array(
        'mirror' => 'cms/mirror',
        //由于DIUrlShell::regexpshell()的配置，将所有非空URI定向到cms/get，因此，以下配置无效
        /* '://acggeek.dev' => 'main/start',
        '://test.www.acggeek.com' => 'main/start',
        '://www.acggeek.com' => 'main/start',
        '://acggeek.com' => 'main/start',
        '://acggeek.webdev.duowan.com' => 'main/start',
        
        '<D>' => '<D>/start',
        '<D>/<F>' => '<D>/<F>',
        '<D>-<F>' => '<D>/<F>',
        '<A>.<B>' => '<A>.<B>', */
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