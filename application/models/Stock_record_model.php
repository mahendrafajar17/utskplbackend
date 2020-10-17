<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stock_record_model extends CI_Model{

    const TABLE_NAME = 'stock_record';
    public function insert_stock_record($broker, $order_item, $order_date, $order_number, $quantity_in,
    $quantity_out, $order_status, $seller){
        $this->db->insert($this::TABLE_NAME, array(
            'broker' => $broker,
            'order_item' => $order_item,
            'order_date' => $order_date,
            'order_number' => $order_number,
            'quantity_in' => $quantity_in,
            'quantity_out' => $quantity_out,
            'order_status' => $order_status,
            'seller' => $seller
        ));
        
        return $this->db->affected_rows();
    }

    public function get_all_stock_record(){
        return $this->db->get($this::TABLE_NAME)->result_array();
    }

    public function get_stock_record_where($id){
        return $this->db->get_where($this::TABLE_NAME, "id='{$id}'")->result_array();
    }

    public function is_not_exists($id){
        if($this->db->get_where($this::TABLE_NAME, "id='{$id}'")->num_rows() == 0) return true;
        else return false;
    }

    public function update_stock_record($id, $broker, $order_item, $order_date, $order_number, $quantity_in,
    $quantity_out, $order_status, $seller){
        // Check apakah tidak merubah apa-apa?
        // kenapa perlu? karena jika update tidak ada perubahan affected_rows() return 0
        $result = $this->db->get_where($this::TABLE_NAME, array(
            'broker' => $broker,
            'order_item' => $order_item,
            'order_date' => $order_date,
            'order_number' => $order_number,
            'quantity_in' => $quantity_in,
            'quantity_out' => $quantity_out,
            'order_status' => $order_status,
            'seller' => $seller
        ));
        if($result->num_rows() > 0) return true;

        // Update
        $this->db->update($this::TABLE_NAME, array(
            'broker' => $broker,
            'order_item' => $order_item,
            'order_date' => $order_date,
            'order_number' => $order_number,
            'quantity_in' => $quantity_in,
            'quantity_out' => $quantity_out,
            'order_status' => $order_status,
            'seller' => $seller
        ), "id='{$id}'");
        
        return $this->db->affected_rows();
    }

    public function delete_stock_record($id){
        $this->db->delete($this::TABLE_NAME, "id='{$id}'");
        return $this->db->affected_rows();
    }
}