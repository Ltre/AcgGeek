<?php
/**
 * 常量：重写模式识别常量
 * 调用点：urlshell.php, rewrite.php
 */
$__config = array();
$hostname = substr( ($h = $_SERVER['HTTP_HOST']), 0, (false !== ($pos = strpos($h, ':')) ? $pos : strlen($h)) );
switch ($hostname) {
    case 'localhost': //正常重写模式
    case '127.0.0.1':
	case 'acggeek.com':
	case 'www.acggeek.com':
	case 'wx.acggeek.com':
	case 'wx.larele.com':
    case 'log.acggeek.com':
	case 'acggeek.fuck':
	case 'www.acggeek.fuck':
	case 'wx.acggeek.fuck':
    case 'log.acggeek.fuck':
	case 'acggeek.webdev.duowan.com':
	    define('AG_CONST_REWRITE_MODE', 'normal');
	    break;
	case 'cms.acggeek.com': //CMS动态重写模式（正式环境）
	case 'cms.larele.com': //CMS动态重写模式（正式环境）
	case 'cms.acggeek.fuck': //CMS动态重写模式（测试环境）
	    define('AG_CONST_REWRITE_MODE', 'cms');
	    break;
	default:
	    die('Invalid hostname for setup rewrite rules!');
}

/**
 * wechat常量配置(文件部分)
 */
define('WX_CONF_FILE_APPID', DI_DATA_PATH.'cache/wechat.appid');
define('WX_CONF_FILE_APPSECRET', DI_DATA_PATH.'cache/wechat.appsecret');
define('WX_CONF_FILE_TOKEN', DI_DATA_PATH.'cache/wechat.token');
define('WX_CONF_FILE_ACCESSTOKEN', DI_DATA_PATH.'cache/wechat.accesstoken');

/**
 * wechat API配置
 */
define('WX_API_GET_ACCESSTOKEN', 'https://api.weixin.qq.com/cgi-bin/token');

$__config['current_domain'] = $hostname;
$GLOBALS = array_merge($GLOBALS, $__config);
