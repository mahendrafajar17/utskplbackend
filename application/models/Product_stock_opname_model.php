<?php
defined('BASEPATH') or exit('No direct script access allowed');

class product_stock_opname_model extends CI_Model
{
    const TABLE_NAME = 'product_stock_opname';

    public function insert_product_stock_opname($opname_id, $product_id, $inspector_id, $real_stock, $opname_stock, $opname_date, $status, $description, $checked)
    {
        $result = $this->db->insert($this::TABLE_NAME, array(
           'opname_id' => $opname_id,
           'product_id' => $product_id,
           'inspector_id' => $inspector_id,
           'real_stock' => $real_stock,
           'opname_stock' => $opname_stock,
           'opname_date' => $opname_date,
           'status' => $status,
           'description' => $description,
           'checked' => $checked
        ));
        return $this->db->affected_rows();
    }

    public function get_all_product_stock_opname()
    {
        $this->db->select('product_stock_opname.id, opname_id as opnameId, product_id as productId, product.name as productName, inspector_id as inspectorId, real_stock as realStock, opname_stock as opnameStock, opname_date as opnameDate, status, product_stock_opname.description, checked');
        $this->db->from($this::TABLE_NAME);
        $this->db->join('product', 'product.id = product_stock_opname.product_id');
        return $this->db->get()->result_array();
    }

    public function get_product_stock_opname_where($id)
    {
        $this->db->select('product_stock_opname.id, opname_id as opnameId, product_id as productId, product.name as productName, inspector_id as inspectorId, real_stock as realStock, opname_stock as opnameStock, opname_date as opnameDate, status, product_stock_opname.description, checked');
        $this->db->from($this::TABLE_NAME);
        $this->db->join('product', 'product.id = product_stock_opname.product_id');
        $this->db->where($this::TABLE_NAME . ".id='{$id}'");
        return $this->db->get()->result_array();
        
    }

    public function get_product_stock_opname_by_opname_id($opname_id)
    {
        $this->db->select('product_stock_opname.id, opname_id as opnameId, product_id as productId, product.name as productName, inspector_id as inspectorId, real_stock as realStock, opname_stock as opnameStock, opname_date as opnameDate, status, product_stock_opname.description, checked');
        $this->db->from($this::TABLE_NAME);
        $this->db->join('product', 'product.id = product_stock_opname.product_id');
        $this->db->where($this::TABLE_NAME . ".opname_id='{$opname_id}'");
        $this->db->ORDER_BY('opname_stock', 'ASC');
        return $this->db->get()->result_array();
        
    }

    public function is_not_exists($id)
    {
        if ($this->db->get_where($this::TABLE_NAME, "id='{$id}'")->num_rows() == 0) return true;
        else return false;
    }

    public function update_product_stock_opname($id, $opname_id, $product_id, $inspector_id, $real_stock, $opname_stock, $opname_date, $status, $description,$checked)
    {
        $this->db->update($this::TABLE_NAME,
            array(
                'opname_id' => $opname_id,
                'product_id' => $product_id,
                'inspector_id' => $inspector_id,
                'real_stock' => $real_stock,
                'opname_stock' => $opname_stock,
                'opname_date' => $opname_date,
                'status' => $status,
                'description' => $description,
                'checked' => $checked
            ),"id = '{$id}'"
        );

        return $this->db->affected_rows();
    }

    public function delete_product_stock_opname($id)
        {
            $this->db->delete($this::TABLE_NAME, "id='{$id}'");
            return $this->db->affected_rows();
        }
}
