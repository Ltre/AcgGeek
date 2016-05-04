<?php
import('store/dwCache');

/**
 * 杂项配置
 */
class Mixed extends DIEntity {
    
    static function set($name, $content, $note = '', $valid = 1){
        $name = sha1($name);
        $item = supertable('Mixed')->find(array('name' => $name));
        $data = array(
            'name' => $name,
            'content' => $content,
            'note' => $note,
            'create_ip' => getip(),
            'create_time' => time(),
            'update_ip' => getip(),
            'update_time' => time(),
            'create_user' => 0,
            'update_user' => 0,
            'valid' => $valid,
        );
        if (empty($item)) {
            return supertable('Mixed')->insert($data);
        } else {
            unset($data['create_ip'], $data['create_time'], $data['create_user']);
            $success = false !== supertable('Mixed')->update(compact('name'), $data);
            $success AND dw_cache()->delete(__CLASS__.$name);
            return $success;
        }
    }

    
    static function setValid($name, $valid){
        $name = sha1($name);
        $valid = (int) $valid;
        $success = false !== supertable('Mixed')->update(compact('name'), compact('valid'));
        $success AND dw_cache()->delete(__CLASS__.$name);
        return $success;
    }
    
    
    static function get($name){
        $item = self::getData($name);
        if (null === $item) return null;
        return $item['content'];
    }
    
    
    static function getData($name){
        $name = sha1($name);
        $cache = dw_cache()->get(__CLASS__.$name);
        if ($cache) return $cache;
        
        $item = supertable('Mixed')->find(array('name' => $name, 'valid' => 1));
        if (empty($item)) return null;
        $item = (array) $item;
        
        dw_cache()->set(__CLASS__.$name, $item);
        return $item;
    }
    
}


/*
CREATE TABLE `agk_mixed` (
  `mid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL COMMENT '代号,用于标识配置项',
  `content` longtext NOT NULL COMMENT '配置值(多个值存储时需序列化)',
  `note` varchar(64) NOT NULL DEFAULT '' COMMENT '注释',
  `create_ip` varchar(15) NOT NULL DEFAULT '',
  `create_time` int(11) NOT NULL,
  `update_ip` varchar(15) NOT NULL DEFAULT '',
  `update_time` int(11) NOT NULL,
  `create_user` bigint(20) NOT NULL COMMENT '创建人的user_id',
  `update_user` bigint(20) NOT NULL COMMENT '修改人的user_id',
  `valid` tinyint(4) NOT NULL DEFAULT '1' COMMENT '是否有效',
  PRIMARY KEY (`mid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
 */