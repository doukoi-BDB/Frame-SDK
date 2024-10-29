<?php



class Other_Date_text_model extends MY_Model{
    public function __construct(){
        parent::__construct();
        $this->table_name   = 'date_text';
        $this->table        = 'date_text';
    }

    public function insert($data) {
        $this->db->insert($this->table,$data);
        return $this->db->insert_id();
    }


}
