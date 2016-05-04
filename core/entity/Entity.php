<?php
/**
 * 杂项配置
 */
class Mixed extends Model {
    
    protected $table_name = 'v_mixed';
    
    public function set($name, $content, $note = '', $valid = 1){
        $name = sha1($name);
        $item = $this->find(array('name' => $name));
        $data = array(
            'name' => $name,
            'content' => json_encode($content),
            'note' => $note,
            'create_ip' => getip(),
            'create_time' => time(),
            'update_ip' => getip(),
            'update_time' => time(),
            'create_user' => '',
            'update_user' => '',
            'valid' => $valid,
        );
        if (empty($item)) {
            unset($data['update_ip'], $data['update_time'], $data['update_user']);
            return $this->insert($data);
        } else {
            unset($data['create_ip'], $data['create_time'], $data['create_user']);
            $success = false !== $this->update(compact('name'), $data);
            $success AND obj('Cache')->delOther(__CLASS__, $name);
            return $success;
        }
    }

    
    public function setValid($name, $valid){
        $name = sha1($name);
        $valid = (int) $valid;
        $success = false !== $this->update(compact('name'), compact('valid'));
        $success AND obj('Cache')->delOther(__CLASS__, $name);
        return $success;
    }
    
    
    public function get($name){
        $item = $this->getData($name);
        if (null === $item) return null;
        return $item['content'];
    }
    
    
    public function getData($name){
        $name = sha1($name);
        $cache = obj('Cache')->getOther(__CLASS__, $name);
        if ($cache) return $cache;
        
        $item = $this->find(array('name' => $name, 'valid' => 1));
        if (empty($item)) return null;
        $item['content'] = json_decode($item['content'], 1);
        
        obj('Cache')->setOther(__CLASS__, $name, $item);
        return $item;
    }
    
}
