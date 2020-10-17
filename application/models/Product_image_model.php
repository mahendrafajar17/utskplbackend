<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Product_image_model extends CI_Model
{
    const TABLE_NAME = 'product_image';

    public function insert_product_image($product_id, $image_id)
    {
        foreach ($image_id as $item) {
            $result = $this->db->get_where($this::TABLE_NAME, "image_id='{$item}' 
                AND product_id='{$product_id}'")->num_rows();
            if ($result == 0) {
                $this->db->insert($this::TABLE_NAME, array(
                    'product_id' => $product_id,
                    'image_id' => $item
                ));
            }
            
        }
        return $this->db->affected_rows();
    }

    public function get_all_product_image()
    {
        $this->db->select("id, product_id as productId, image_id as imageId");
        $this->db->from($this::TABLE_NAME);
        return $this->db->get()->result_array();
    }

    public function get_product_image_where($product_id)
    {   
        $this->db->select("image_id as imageId");
        $this->db->from($this::TABLE_NAME);
        $this->db->where("product_id = '{$product_id}'");
        $res = [];
        foreach($this->db->get()->result_array() as $image){
            array_push($res,$image['imageId']);
        }
        return $res;


      
    }

    public function is_not_exists($id)
    {
        if ($this->db->get_where($this::TABLE_NAME, "id='{$id}'")->num_rows() == 0) return true;
        else return false;
    }

    public function update_product_image($product_id, $image_id)
    {
        $result = $this->db->get_where($this::TABLE_NAME, "product_id = '{$product_id}'")->result_array();

        $current_images = [];
        foreach ($result as $row) {
            array_push($current_images, $row['image_id']);
        }

        $insert_data = array_diff($image_id, $current_images);
        $delete_data = array_diff($current_images, $image_id);

        // return $insert_data;

        $this->insert_product_image($product_id, $insert_data);

        foreach ($delete_data as $image) {
            $this->db->delete($this::TABLE_NAME, array(
                'product_id' => $product_id,
                'image_id' => $image
            ));
        }
        
        return $this->db->affected_rows();

    }

    public function delete_product_image($id)
    {
        $this->db->delete($this::TABLE_NAME, "id='{$id}'");
        return $this->db->affected_rows();
    }

    public function delete_product_image_by_product_id($product_id)
    {
        $this->db->delete($this::TABLE_NAME, "product_id='{$product_id}'");
        return $this->db->affected_rows();
    }
}
