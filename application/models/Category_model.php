<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Category_model extends CI_Model{

    const TABLE_NAME = 'category';
    public function insert_category($name){
        $this->db->insert($this::TABLE_NAME, array(
            'name' => $name
        ));
        
        return $this->db->insert_id();
    }

    public function get_all_category(){
        return $this->db->get($this::TABLE_NAME)->result_array();
    }

    public function get_category_where($id){
        return $this->db->get_where($this::TABLE_NAME, "id='{$id}'")->result_array();
    }

    public function get_by_name($category_name){
        return $this->db->get_where($this::TABLE_NAME, "name='{$category_name}'")->result_array()[0]['id'];
    }

    public function is_not_exists($id){
        if($this->db->get_where($this::TABLE_NAME, "id='{$id}'")->num_rows() == 0) return true;
        else return false;
    }

    public function is_name_exists($name){
        if($this->db->get_where($this::TABLE_NAME, "name='{$name}'")->num_rows() > 0) return true;
        else return false;
    }

    public function update_category($id, $name){
        // Check apakah tidak merubah apa-apa?
        // kenapa perlu? karena jika update tidak ada perubahan affected_rows() return 0
        $result = $this->db->get_where($this::TABLE_NAME, array(
            'id' => $id,
            'name' => $name
        ));
        if($result->num_rows() > 0) return true;

        // Update
        $this->db->update($this::TABLE_NAME, array(
            'name' => $name
        ), "id='{$id}'");
        
        return $this->db->affected_rows();
    }

    public function delete_category($id){
        $this->db->delete($this::TABLE_NAME, "id='{$id}'");
        return $this->db->affected_rows();
    }
}