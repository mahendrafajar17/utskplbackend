<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product_model extends CI_Model{

    const TABLE_NAME = 'product';
    
    
    public function insert_product_import($datas){
        foreach($datas as $data){
            
            if($this->is_name_and_specification_exists($data['name'], $data['specification'])) continue;
            else{
                $this->db->set($data);
                $this->db->insert($this::TABLE_NAME, $data);
            }
        }
        return $this->db->affected_rows();
    }

    public function insert_product($product_code, $name, $category_id, $specification, $description, $stock, $unit_id, $open_price, $bottom_price, $retail_id, $min_stock){
        
        if(!isset($stock)){
            $stock = 0;
        }
       
        $this->db->insert($this::TABLE_NAME, array(
            'product_code' => $product_code,
            'name' => $name,
            'category_id ' => $category_id,
            'specification' => $specification,
            'description' => $description,
            'stock' => $stock,
            'unit_id' => $unit_id,
            'open_price' => $open_price,
            'bottom_price' => $bottom_price,
            'retail_id' => $retail_id,
            'min_stock' => $min_stock
        ));
        return $this->db->insert_id();
    }

    public function get_all_product(){
        $this->db->select('id, product_code as productCode, name, category_id as categoryId, specification, description, unit_id as unitId, open_price as openPrice, bottom_price as bottomPrice, stock, min_stock as minStock, retail_id as retailId');
        $this->db->from($this::TABLE_NAME);
        return $this->db->get()->result_array();
    }

    public function get_product_where($id){
        $this->db->select('id, product_code as productCode, name, category_id as categoryId, specification, description, unit_id as unitId, open_price as openPrice, bottom_price as bottomPrice, stock, min_stock as minStock, retail_id as retailId');
        $this->db->from($this::TABLE_NAME);
        $this->db->where("id='{$id}'");
        return $this->db->get()->result_array();
    }

    public function is_not_exists($id){
        if($this->db->get_where($this::TABLE_NAME, "id='{$id}'")->num_rows() == 0) return true;
        else return false;
    }

    public function is_name_exists($name){
        if($this->db->get_where($this::TABLE_NAME, "name='{$name}'")->num_rows() > 0) return true;
        else return false;
    }

    public function is_name_and_specification_exists($name, $specification){

        if($this->db->get_where($this::TABLE_NAME, array('name' => $name, 'specification' => $specification))->num_rows() > 0) return true;
        else return false;
    }

    public function update_product($id, $datas){
        // Check apakah tidak merubah apa-apa?
        // kenapa perlu? karena jika update tidak ada perubahan affected_rows() return 0
        $result = $this->db->get_where($this::TABLE_NAME, $datas);
        if($result->num_rows() > 0) return true;

        // Update
        $this->db->update($this::TABLE_NAME, $datas, "id='{$id}'");
        
        return $this->db->affected_rows();
    }

    public function delete_product($id){
        $this->db->delete($this::TABLE_NAME, "id='{$id}'");
        return $this->db->affected_rows();
    }
}