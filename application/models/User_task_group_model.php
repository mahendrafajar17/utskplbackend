<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_task_group_model extends CI_Model{
    const TABLE_NAME = 'user_task_group';

    public function insert_user_task_group($name){
        $this->db->insert($this::TABLE_NAME, array(
            'name' => $name
        ));

        return $this->db->affected_rows();
    }

    public function get_all_user_task_group(){
        return $this->db->get($this::TABLE_NAME)->result_array();
    }

    public function get_user_task_group_where($id){
        return $this->db->get_where($this::TABLE_NAME, "id='{$id}'")->result_array();
    }

    public function is_not_exists($id){
        if($this->db->get_where($this::TABLE_NAME, "id='{$id}'")->num_rows() == 0) return true;
        else false;
    }

    public function update_user_task_group($id, $name){
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

    public function delete_user_task_group($id){
        $this->db->delete($this::TABLE_NAME, "id='{$id}'");
        return $this->db->affected_rows();
    }
}