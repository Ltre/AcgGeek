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
    'acggeek.fuck',//绑定本地HOSTS
    'cms.acggeek.fuck',
    'wx.acggeek.fuck',
    'log.acggeek.fuck',
))){
	
    class DIDBConfig {
        static $driver = 'DIMySQL';//驱动类
        static $host = '127.0.0.1';
        static $port = 3306;
        static $db = 'acggeek';
        static $user = 'acggeek';
        static $pwd = 'acggeek';
        static $table_prefix = 'agk_';//表前缀
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
        static $host = '172.26.42.222';
        static $port = 3306;
        static $db = 'acggeek';
        static $user = 'acggeek';
        static $pwd = 'acggeek';
        static $table_prefix = 'agk_';//表前缀
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
    'cms.acggeek.com',
    'wx.acggeek.com',
    'log.acggeek.com',
    //计划迁移到larele
    'acggeek.larele.com',
    'cms.acggeek.larele.com',
    'wx.acggeek.larele.com',
    'log.acggeek.larele.com',
))) {
    
    class DIDBConfig {
        static $driver = 'DIMySQL';//驱动类
        static $host = '127.0.0.1';
        static $port = 3306;
        static $db = 'acggeek';
        static $user = 'acggeek';
        static $pwd = 'a1ad82a06fb30a71f9470fedfe811243dd5c8c8c';
        static $table_prefix = 'agk_';//表前缀
    }
    class DIMMCConfig {
        static $domain = 'acggeek';
        static $host;
        static $port;
    }
    
} else {
    
    die;//环境不明确，终止
    
}
