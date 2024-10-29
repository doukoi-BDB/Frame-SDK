<?php



class Other_Date_list_model extends MY_Model{
    public function __construct(){
        parent::__construct();
        $this->table_name   = 'date_list';
        $this->table        = 'date_list';
    }

    public function insert($data) {
        $this->db->insert($this->table,$data);
        return $this->db->insert_id();
    }


}