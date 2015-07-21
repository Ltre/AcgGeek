<?php
/**
 * 配置数据库基本信息
 * 【！！强烈建议！！】
 * 不要将本文件中的外网数据库信息提交
 */
$hostname = substr( ($h = $_SERVER['HTTP_HOST']), 0, (false !== ($pos = strpos($h, ':')) ? $pos : strlen($h)) );
if (in_array($hostname, array(
    '127.0.0.1',
    'localhost',
    'acggeek.dev',//绑定本地HOSTS
))){
	
    class DIDBConfig {
        static $driver = 'DIMySQL';//驱动类
        static $host = '127.0.0.1';
        static $port = 3306;
        static $db = 'acggeek';
        static $user = 'acggeek';
        static $pwd = 'acggeek';
        static $table_prefix = 'ag_';//表前缀
    }
    class DIMMCConfig {
        static $domain = 'acggeek';
        static $host = '127.0.0.1';
        static $port = 11211;
    }
    
} elseif (in_array($hostname, array(
	'acggeek.webdev.duowan.com'
))) {
    
    class DIDBConfig {
        static $driver = 'DIMySQL';//驱动类
        static $host = '172.16.43.111';
        static $port = 3306;
        static $db = 'acggeek';
        static $user = 'acggeek';
        static $pwd = 'acggeek';
        static $table_prefix = 'ag_';//表前缀
    }
    class DIMMCConfig {
        static $domain = 'acggeek';
        static $host;
        static $port;
    }
    
} elseif (in_array($hostname, array(
	'acggeek.com',
    'www.acggeek.com',
    'test.www.acggeek.com',
))) {
    
    class DIDBConfig {
        static $driver = 'DIMySQL';//驱动类
        static $host = 'localhost';
        static $port = 3306;
        static $db = 'acggeek';
        static $user = 'acggeek';
        static $pwd = 'a1ad82a06fb30a71f9470fedfe811243dd5c8c8c';
        static $table_prefix = 'ag_';//表前缀
    }
    class DIMMCConfig {
        static $domain = 'acggeek';
        static $host;
        static $port;
    }
    
} else {
    
    die;//环境不明确，终止
    
}