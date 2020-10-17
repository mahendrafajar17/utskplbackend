<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model{
    const TABLE_NAME = "user";

    public function insert_user($user_task_group_id, $name, $email, $place_of_birth, $date_of_birth, $religion, $status,
    $telephone, $address, $uid){
        $this->db->insert($this::TABLE_NAME, array(
            'user_task_group_id' => $user_task_group_id,
            'name' => $name,
            'email' => $email,
            'place_of_birth' => $place_of_birth,
            'date_of_birth' => $date_of_birth,
            'religion' => $religion,
            'status' => $status,
            'telephone' => $telephone,
            'address' => $address,
            'uid' => $uid
        ));

        return $this->db->affected_rows();
    }

    public function get_all_user(){
        $this->db->select('id, user_task_group_id as '.Schema::USER_TASK_GROUP_ID.', name, email, 
        telephone, address, uid, place_of_birth as '. Schema::PLACE_OF_BIRTH.', date_of_birth as dateOfBirth, religion, status');
        $this->db->from('user');
        return $this->db->get()->result_array();
    }

    public function get_user_where($id){
        $this->db->select('id, user_task_group_id as '.Schema::USER_TASK_GROUP_ID.', name,
        telephone, address, uid');
        $this->db->from('user');
        $this->db->where("id='{$id}'");
        return $this->db->get()->result_array();
    }

    public function get_user_where_uid($uid){
        $this->db->select('id, user_task_group_id as '.Schema::USER_TASK_GROUP_ID.', name,
        telephone, address, uid');
        $this->db->from('user');
        $this->db->where("uid='{$uid}'");
        $result = $this->db->get();
        if($result->num_rows() > 0) return $result->unbuffered_row('array');
        else return array();
    }

    public function get_by_user_task_group_id($user_task_group_id){
        return $this->db->get_where($this::TABLE_NAME, "user_task_group_id='{$user_task_group_id}'")->result_array();
    }

    public function is_not_exists($id){
        if($this->db->get_where($this::TABLE_NAME, "id='{$id}'")->num_rows() == 0) return true;
        else false;
    }

    public function update_user($id, $data){
        // Check apakah tidak merubah apa-apa?
        // kenapa perlu? karena jika update tidak ada perubahan affected_rows() return 0
        $result = $this->db->get_where($this::TABLE_NAME, $data);
        if($result->num_rows() > 0) return true;

        // Update
        $this->db->update($this::TABLE_NAME, $data, "id='{$id}'");
        
        return $this->db->affected_rows();
    }

    public function delete_user($id){
        $this->db->delete($this::TABLE_NAME, "id='{$id}'");
        return $this->db->affected_rows();
    }
}