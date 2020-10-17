<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Unit_model extends CI_Model
{

    const TABLE_NAME = 'unit';
    public function insert_unit($name, $abbreviation, $description)
    {
        if(!isset($description)) $description = '';
        $this->db->insert($this::TABLE_NAME, array(
            'name' => $name,
            'abbreviation' => $abbreviation,
            'description' => $description

        ));

        return $this->db->insert_id();
    }

    public function get_all_unit()
    {
        return $this->db->get($this::TABLE_NAME)->result_array();
    }

    public function get_unit_where($id)
    {
        return $this->db->get_where($this::TABLE_NAME, "id='{$id}'")->result_array();
    }

    public function get_by_abbreviation($abbrev){
        return $this->db->get_where($this::TABLE_NAME, "abbreviation='{$abbrev}'")->result_array()[0]['id'];
    }

    public function is_not_exists($id)
    {
        if ($this->db->get_where($this::TABLE_NAME, "id='{$id}'")->num_rows() == 0) return true;
        else return false;
    }

    public function is_abbreviation_exists($abbrev){
        if($this->db->get_where($this::TABLE_NAME, "abbreviation='{$abbrev}'")->num_rows() > 0)  return true;
        else return false;
    }

    public function is_name_exists($name){
        if($this->db->get_where($this::TABLE_NAME, "name='{$name}'")->num_rows() > 0) return true;
        else return false;
    }
    
    public function update_unit($id, $name, $abbreviation, $description)
    {
        // Check apakah tidak merubah apa-apa?
        // kenapa perlu? karena jika update tidak ada perubahan affected_rows() return 0
        if(!isset($description)) $description = '';
        $result = $this->db->get_where($this::TABLE_NAME, array(
            'id' => $id,
            'name' => $name,
            'abbreviation' => $abbreviation,
            'description' => $description
        ));
        if ($result->num_rows() > 0) return true;

        // Update
        $this->db->update($this::TABLE_NAME, array(
            'name' => $name,
            'abbreviation' => $abbreviation,
            'description' => $description
        ), "id='{$id}'");

        return $this->db->affected_rows();
    }

    public function delete_unit($id)
    {
        $this->db->delete($this::TABLE_NAME, "id='{$id}'");
        return $this->db->affected_rows();
    }
}
