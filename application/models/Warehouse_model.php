<?php
defined('BASEPATH') or exit('No direct script access allowed');

class warehouse_model extends CI_Model
{

    const TABLE_NAME = 'warehouse';
    public function insert_warehouse($name, $address)
    {
        $this->db->insert($this::TABLE_NAME, array(
            'name' => $name,
            'address' => $address
        ));

        return $this->db->affected_rows();
    }

    public function get_all_warehouse()
    {
        return $this->db->get($this::TABLE_NAME)->result_array();
    }

    public function get_warehouse_where($id)
    {
        return $this->db->get_where($this::TABLE_NAME, "id='{$id}'")->result_array();
    }

    public function is_not_exists($id)
    {
        if ($this->db->get_where($this::TABLE_NAME, "id='{$id}'")->num_rows() == 0) return true;
        else return false;
    }

    public function update_warehouse($id, $name, $address)
    {
        // Check apakah tidak merubah apa-apa?
        // kenapa perlu? karena jika update tidak ada perubahan affected_rows() return 0
        $result = $this->db->get_where($this::TABLE_NAME, array(
            'id' => $id,
            'name' => $name,
            'address' => $address
        ));
        if ($result->num_rows() > 0) return true;

        // Update
        $this->db->update($this::TABLE_NAME, array(
            'name' => $name,
            'address' => $address
        ), "id='{$id}'");

        return $this->db->affected_rows();
    }

    public function delete_warehouse($id)
    {
        $this->db->delete($this::TABLE_NAME, "id='{$id}'");
        return $this->db->affected_rows();
    }
}
