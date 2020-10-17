<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Stock_opname_model extends CI_Model
{

    const TABLE_NAME = 'stock_opname';
    public function insert_stock_opname($opname_number, $date_start, $date_finish, $status, $description)
    {
        $this->db->insert($this::TABLE_NAME, array(
            'opname_number' => $opname_number,
            'date_start' => $date_start,
            'date_finish' => $date_finish,
            'status' => $status,
            'description' => $description
        ));

        return $this->db->insert_id();
    }

    public function get_all_stock_opname()
    {
        $this->db->select('id, opname_number as opnameNumber, date_start as dateStart, date_finish as dateFinish, status, description');
        $this->db->from($this::TABLE_NAME);
        $this->db->order_by('date_start', 'DESC');
        return $this->db->get()->result_array();
    }

    public function get_stock_opname_where($id)
    {
        $this->db->select('id, opname_number as opnameNumber, date_start as dateStart, date_finish as dateFinish, status, description');
        $this->db->from($this::TABLE_NAME);
        $this->db->where("id = '{$id}'");
        return $this->db->get()->result_array();
        
    }

    public function is_not_exists($id)
    {
        if ($this->db->get_where($this::TABLE_NAME, "id='{$id}'")->num_rows() == 0) return true;
        else return false;
    }

    public function update_stock_opname($id, $opname_number, $date_start, $date_finish, $status, $description)
    {
        // Update
        $this->db->update($this::TABLE_NAME, array(
            'opname_number' => $opname_number,
            'date_start' => $date_start,
            'date_finish' => $date_finish,
            'status' => $status,
            'description' => $description
        ), "id='{$id}'");

        return $this->db->affected_rows();
    }

    public function delete_stock_opname($id)
    {
        $this->db->delete($this::TABLE_NAME, "id='{$id}'");
        return $this->db->affected_rows();
    }
}
