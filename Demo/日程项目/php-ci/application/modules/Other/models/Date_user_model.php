<?php



class Other_Date_user_model extends MY_Model{
    public function __construct(){
        parent::__construct();
        $this->table_name   = 'date_user';
        $this->table        = 'date_user';
    }

    public function insert($data) {
        $this->db->insert($this->table,$data);
        return $this->db->insert_id();
    }


}