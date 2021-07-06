<?php


class BDB_Wechat_User_model extends MY_Model{
    public function __construct(){
        parent::__construct();
        $this->table_name   = 'xxxx';
        $this->table        = 'xxxx';
    }

    public function insert($data) {
        $this->db->insert($this->table,$data);
        return $this->db->insert_id();
    }

}