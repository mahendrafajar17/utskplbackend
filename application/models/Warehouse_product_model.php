<?php
defined('BASEPATH') or exit('No direct script access allowed');

class warehouse_product_model extends CI_Model
{

    const TABLE_NAME = 'warehouse_product';
    public function insert_warehouse_product($warehouse_id, $product_id)
    {
        $this->db->insert($this::TABLE_NAME, array(
            'warehouse_id' => $warehouse_id,
            'product_id' => $product_id
        ));

        return $this->db->affected_rows();
    }

    public function get_all_warehouse_product()
    {
        return $this->db->get($this::TABLE_NAME)->result_array();
    }

    public function get_warehouse_product_where($id)
    {
        return $this->db->get_where($this::TABLE_NAME, "id='{$id}'")->result_array();
    }

    public function is_not_exists($id)
    {
        if ($this->db->get_where($this::TABLE_NAME, "id='{$id}'")->num_rows() == 0) return true;
        else return false;
    }

    public function update_warehouse_product($id, $warehouse_id, $product_id)
    {
        // Check apakah tidak merubah apa-apa?
        // kenapa perlu? karena jika update tidak ada perubahan affected_rows() return 0
        $result = $this->db->get_where($this::TABLE_NAME, array(
            'id' => $id,
            'warehouse_id' => $warehouse_id,
            'product_id' => $product_id
        ));
        if ($result->num_rows() > 0) return true;

        // Update
        $this->db->update($this::TABLE_NAME, array(
            'warehouse_id' => $warehouse_id,
            'product_id' => $product_id
        ), "id='{$id}'");

        return $this->db->affected_rows();
    }

    public function delete_warehouse_product($id)
    {
        $this->db->delete($this::TABLE_NAME, "id='{$id}'");
        return $this->db->affected_rows();
    }
}
