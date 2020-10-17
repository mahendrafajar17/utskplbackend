<?php
defined('BASEPATH') or exit('No direct script access allowed');

class product_variant_model extends CI_Model
{
    const TABLE_NAME = 'product_variant';

    public function insert_product_variant($product_id, $size)
    {
        $result = $this->db->insert($this::TABLE_NAME, array(
            'product_id' => $product_id,
            'size_a' => $size[0],
            'size_b' => $size[1],
            'size_c' => $size[2],
            'size_d' => $size[3]
        ));
        return $this->db->affected_rows();
    }

    public function get_all_product_variant()
    {
        $this->db->select('id, product_id as productId, size_a as sizeA, size_b as sizeB, size_c as sizeC, size_d as sizeD');
        $this->db->from($this::TABLE_NAME);
        return $this->db->get()->result_array();
    }

    public function get_product_variant_where($product_id)
    {
        $this->db->select('id, product_id as productId, size_a as sizeA, size_b as sizeB, size_c as sizeC, size_d as sizeD');
        $this->db->from($this::TABLE_NAME);
        $this->db->where($this::TABLE_NAME . ".product_id='{$product_id}'");
        return $this->db->get()->result_array();
        
    }

    public function is_not_exists($id)
    {
        if ($this->db->get_where($this::TABLE_NAME, "id='{$id}'")->num_rows() == 0) return true;
        else return false;
    }

    public function update_product_variant($id, $product_id, $size)
    {
        $this->db->update($this::TABLE_NAME,array(
            'product_id' => $product_id,
            'size_a' => $size[0],
            'size_b' => $size[1],
            'size_c' => $size[2],
            'size_d' => $size[3]
        ),"id = '{$id}'"
    );

        return $this->db->affected_rows();
    }

    public function delete_product_variant($id)
        {
            $this->db->delete($this::TABLE_NAME, "id='{$id}'");
            return $this->db->affected_rows();
        }
}
