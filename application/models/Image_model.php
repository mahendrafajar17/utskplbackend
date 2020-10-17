<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Image_model extends CI_Model{

    const TABLE_NAME = 'image';
    public function insert_image($category_id, $image){
        $this->db->insert($this::TABLE_NAME, array(
            'category_id' => $category_id,
            'image' => $image
        ));
        
        return $this->db->affected_rows();
    }

    public function get_all_image(){
        return $this->db->get($this::TABLE_NAME)->result_array();
    }

    public function get_image_where($id){
        return $this->db->get_where($this::TABLE_NAME, "id='{$id}'")->result_array();
    }

    public function is_not_exists($id){
        if($this->db->get_where($this::TABLE_NAME, "id='{$id}'")->num_rows() == 0) return true;
        else return false;
    }

    public function update_image($id, $category_id, $image){
        // Check apakah tidak merubah apa-apa?
        // kenapa perlu? karena jika update tidak ada perubahan affected_rows() return 0
        $result = $this->db->get_where($this::TABLE_NAME, array(
            'category_id' => $category_id,
            'image' => $image
        ));
        if($result->num_rows() > 0) return true;

        // Update
        $this->db->update($this::TABLE_NAME, array(
            'category_id' => $category_id,
            'image' => $image
        ), "id='{$id}'");
        
        return $this->db->affected_rows();
    }

    public function delete_image($id){
        $this->db->delete($this::TABLE_NAME, "id='{$id}'");
        return $this->db->affected_rows();
    }
}