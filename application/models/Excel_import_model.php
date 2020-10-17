<?php
class Excel_import_model extends CI_Model
{
    const TABLE_NAME = 'product';
    function select()
    {
        $query = $this->db->get($this::TABLE_NAME);
        return $query;
    }

    function insert($datas){
        $this->db->insert_batch($this::TABLE_NAME, $datas);
        return $this->db->affected_rows();
    }

}
