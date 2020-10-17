<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Product_tag_model extends CI_Model
{
    const TABLE_NAME = 'product_tag';

    public function insert_product_tag($product_id, $tag_id)
    {
        foreach ($tag_id as $item) {
            $result = $this->db->get_where($this::TABLE_NAME, "tag_id='{$item}' 
                AND product_id='{$product_id}'")->num_rows();
            if ($result == 0) {
                $this->db->insert($this::TABLE_NAME, array(
                    'product_id' => $product_id,
                    'tag_id' => $item
                ));
            }
            
        }
        return $this->db->affected_rows();
    }

    public function get_all_product_tag()
    {
        $this->db->select("id, product_id as productId, tag_id as tagId");
        $this->db->from($this::TABLE_NAME);
        return $this->db->get()->result_array();
    }

    public function get_product_tag_where($product_id)
    {   
        $this->db->select("tag_id as tagId");
        $this->db->from($this::TABLE_NAME);
        $this->db->where("product_id = '{$product_id}'");
        $res = [];
        foreach($this->db->get()->result_array() as $tag){
            array_push($res,$tag['tagId']);
        }
        return $res;


    }

    public function is_not_exists($id)
    {
        if ($this->db->get_where($this::TABLE_NAME, "id='{$id}'")->num_rows() == 0) return true;
        else return false;
    }

    public function update_product_tag($product_id, $tag_id)
    {
        $result = $this->db->get_where($this::TABLE_NAME, "product_id = '{$product_id}'")->result_array();

        $current_tags = [];
        foreach ($result as $row) {
            array_push($current_tags, $row['tag_id']);
        }

        $insert_data = array_diff($tag_id, $current_tags);
        $delete_data = array_diff($current_tags, $tag_id);



        $this->insert_product_tag($product_id, $insert_data);

        foreach ($delete_data as $tag) {
            $this->db->delete($this::TABLE_NAME, array(
                'product_id' => $product_id,
                'tag_id' => $tag
            ));
        }
        
        return $this->db->affected_rows();

    }

    public function delete_product_tag($id)
    {
        $this->db->delete($this::TABLE_NAME, "id='{$id}'");
        return $this->db->affected_rows();
    }

    public function delete_product_tag_by_product_id($product_id)
    {
        $this->db->delete($this::TABLE_NAME, "product_id='{$product_id}'");
        return $this->db->affected_rows();
    }
}
