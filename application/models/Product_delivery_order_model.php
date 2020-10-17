<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Product_delivery_order_model extends CI_Model
{
    const TABLE_NAME = 'product_delivery_order';

    public function insert_product_delivery_order($delivery_order_id, $items)
    {
        /**
         * items = array of item
         * item = object{'id', 'amount'}
         * 
         */


        foreach ($items as $item) {
            $result = $this->db->get_where($this::TABLE_NAME, "product_id=".$item['productId']." 
                AND delivery_order_id='{$delivery_order_id}'")->num_rows();
            if ($result == 0) {
                $this->db->insert($this::TABLE_NAME, array(
                    'delivery_order_id' => $delivery_order_id,
                    'product_id' => $item['productId'],
                    'amount' => $item['amount']
                ));
            }
            
        }
        return $this->db->affected_rows();
    }

    public function get_all_product_delivery_order()
    {
        $this->db->select("id, delivery_order_id as deliveryOrderId, product_id as productId, amount");
        $this->db->from($this::TABLE_NAME);
        return $this->db->get()->result_array();
    }

    public function get_product_delivery_order_where($delivery_order_id)
    {   
        
        $res = $this->db->query("SELECT product_delivery_order.id, product_id AS productId, name, amount, stock 
                    FROM product_delivery_order
                    JOIN product
                    ON product_delivery_order.product_id = product.id
                    WHERE product_delivery_order.delivery_order_id = '{$delivery_order_id}' 
                    ");
        // $this->db->select("product_delivery_order.id, product_id as productId, amount");
        // $this->db->from($this::TABLE_NAME);
        // $this->db->where("delivery_order_id = '{$delivery_order_id}'");
        // $this->db->join("product", "'product_delivery_order.product_id'='product.id'");
        return $res->result_array();
    }

    public function is_not_exists($id)
    {
        if ($this->db->get_where($this::TABLE_NAME, "id='{$id}'")->num_rows() == 0) return true;
        else return false;
    }

    public function update_product_delivery_order($delivery_order_id, $items)
    {
        
        $this->db->delete($this::TABLE_NAME, array(
            'delivery_order_id' => $delivery_order_id,
        ));
        
        $this->insert_product_delivery_order($delivery_order_id, $items);

        // $result = $this->db->get_where($this::TABLE_NAME, "delivery_order_id = '{$delivery_order_id}'")->result_array();

        // $current_products = [];
        // foreach ($result as $row) {
        //     array_push($current_products, $row['product_id']);
        // }

        // $ids = [];
        // foreach($items as $item) array_push($ids, $item['id']);
        // $delete_data = array_diff($current_products, $ids);
        // $insert_ids = array_diff($ids, $current_products);



        // $this->insert_product_delivery_order($delivery_order_id, $insert_data);
        // foreach ($delete_data as $delivery_order) {
        //     $this->db->delete($this::TABLE_NAME, array(
        //         'delivery_order_id' => $delivery_order_id,
        //         'product_id' => $delivery_order
        //     ));
        // }
        
        return $this->db->affected_rows();

    }

    public function delete_product_delivery_order($id)
    {
        $this->db->delete($this::TABLE_NAME, "id='{$id}'");
        return $this->db->affected_rows();
    }

    public function delete_product_delivery_order_by_delivery_order_id($delivery_order_id)
    {
        $this->db->delete($this::TABLE_NAME, "delivery_order_id='{$delivery_order_id}'");
        return $this->db->affected_rows();
    }
}
