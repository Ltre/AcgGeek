<?php
/**
 * 参照__env.php建议，按己所需，重新定制特性
 */
$hostname = substr( ($h = $_SERVER['HTTP_HOST']), 0, (false !== ($pos = strpos($h, ':')) ? $pos : strlen($h)) );
switch ($hostname) {
    //以下使用本地
    case '127.0.0.1':
    case '192.168.1.100':
    case 'localhost':
    case 'acggeek.dev':
    case 'acggeek.webdev.duowan.com':
        {
            define('DI_ROUTE_REWRITE', true);
            break;
        }
    case 'acggeek.com':
    case 'www.acggeek.com':
        {
            define('DI_DEBUG_MODE', false);
            define('DI_IO_RWFUNC_ENABLE', true);
            define('DI_ROUTE_REWRITE', true);
            break;
        }
    //线上测试环境
    case 'test.www.acggeek.com':
        {
            define('DI_DEBUG_MODE', true);
            define('DI_IO_RWFUNC_ENABLE', true);
            break;
        }
    default:die;//环境不明确，终止执行
}


define('DI_SMARTY_DEFAULT', true);//暂时所有环境不默认采用smarty
