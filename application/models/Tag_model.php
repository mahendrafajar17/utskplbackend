<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Tag_model extends CI_Model
{

    const TABLE_NAME = 'tag';
    public function insert_tag($name)
    {
        if(!isset($description)) $description = '';
        $this->db->insert($this::TABLE_NAME, array(
            'tag_name' => $name
        ));

        return $this->db->affected_rows();
    }

    public function get_all_tag()
    {
        $this->db->select("id, tag_name as tagName");
        $this->db->from($this::TABLE_NAME);
        return $this->db->get()->result_array();
    }

    public function get_tag_where($id)
    {
        $this->db->select("id, tag_name as tagName");
        $this->db->from($this::TABLE_NAME);
        $this->db->where("id = '{$id}'");
        return $this->db->get()->result_array();
    }

    public function is_not_exists($id)
    {
        if ($this->db->get_where($this::TABLE_NAME, "id='{$id}'")->num_rows() == 0) return true;
        else return false;
    }

    public function is_name_exists($name){
        if($this->db->get_where($this::TABLE_NAME, "tag_name='{$name}'")->num_rows() > 0) return true;
        else return false;
    }

    public function update_tag($id, $name)
    {
        // Check apakah tidak merubah apa-apa?
        // kenapa perlu? karena jika update tidak ada perubahan affected_rows() return 0
        $result = $this->db->get_where($this::TABLE_NAME, array(
            'id' => $id,
            'tag_name' => $name
        ));
        if ($result->num_rows() > 0) return true;

        // Update
        $this->db->update($this::TABLE_NAME, array(
            'tag_name' => $name
        ), "id='{$id}'");

        return $this->db->affected_rows();
    }

    public function delete_tag($id)
    {
        $this->db->delete($this::TABLE_NAME, "id='{$id}'");
        return $this->db->affected_rows();
    }
}
