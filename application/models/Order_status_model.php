<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Order_status_model extends CI_Model{

    const TABLE_NAME = 'order_status';
    public function insert_order_status($status){
        $this->db->insert($this::TABLE_NAME, array(
            'status' => $status
        ));
        
        return $this->db->affected_rows();
    }

    public function get_all_order_status(){
        return $this->db->get($this::TABLE_NAME)->result_array();
    }

    public function get_order_status_where($id){
        return $this->db->get_where($this::TABLE_NAME, "id='{$id}'")->result_array();
    }

    public function is_not_exists($id){
        if($this->db->get_where($this::TABLE_NAME, "id='{$id}'")->num_rows() == 0) return true;
        else return false;
    }

    public function update_order_status($id, $status){
        // Check apakah tidak merubah apa-apa?
        // kenapa perlu? karena jika update tidak ada perubahan affected_rows() return 0
        $result = $this->db->get_where($this::TABLE_NAME, array(
            'id' => $id,
            'status' => $status
        ));
        if($result->num_rows() > 0) return true;

        // Update
        $this->db->update($this::TABLE_NAME, array(
            'status' => $status
        ), "id='{$id}'");
        
        return $this->db->affected_rows();
    }

    public function delete_order_status($id){
        $this->db->delete($this::TABLE_NAME, "id='{$id}'");
        return $this->db->affected_rows();
    }
}