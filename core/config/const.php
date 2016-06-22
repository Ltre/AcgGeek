<?php
/**
 * 重写模式识别
 * 调用点：urlshell.php, rewrite.php
 */
$hostname = substr( ($h = $_SERVER['HTTP_HOST']), 0, (false !== ($pos = strpos($h, ':')) ? $pos : strlen($h)) );
switch ($hostname) {
	case 'acggeek.com': //正常重写模式
	case 'www.acggeek.com':
	case 'acggeek.dev':
	case 'www.acggeek.dev':
	case 'acggeek.webdev.duowan.com':
	    define('AG_CONST_REWRITE_MODE', 'normal');
	    break;
	case 'cms.acggeek.com': //CMS动态重写模式
	case 'cms.acggeek.dev':
	    define('AG_CONST_REWRITE_MODE', 'cms');
	    break;
	default:
	    die('Invalid hostname for setup rewrite rules!');
}