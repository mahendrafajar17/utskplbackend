<?php

defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

use Restserver\Libraries\REST_Controller;

class warehouse_product extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('warehouse_model');
        $this->load->model('product_model');
        $this->load->model('warehouse_product_model');
    }

    public function index_post()
    {
        $warehouse_id = $this->post('warehouseId');
        $product_id = $this->post('productId');

        if (!isset($warehouse_id) || !isset($product_id)) {
            $requrired_parameters = [];
            if(!isset($warehouse_id)) array_push($requrired_parameters, 'warehouseId');
            if(!isset($product_id)) array_push($requrired_parameters, 'productId');
            $this->response(
                array(
                    'status' => FALSE,
                    'message' => $this::REQUIRED_PARAMETER_MESSAGE.implode(', ', $requrired_parameters)
                ),REST_Controller::HTTP_BAD_REQUEST
            );
            return;
        }

        if ($this->warehouse_product_model->insert_warehouse_product($warehouse_id, $product_id)) {
            $this->response(
                array(
                    'status' => TRUE,
                    'message' => $this::INSERT_SUCCESS_MESSSAGE
                ), REST_Controller::HTTP_CREATED
            );
        } else {
            $this->response(
                array(
                    'status' => FALSE,
                    'message' => $this::INSERT_FAILED_MESSAGE
                ),REST_Controller::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    public function index_get()
    {
        $id = $this->get('id');

        if (isset($id)) $this->response($this->warehouse_product_model->get_warehouse_product_where($id),REST_Controller::HTTP_OK);
        else $this->response($this->warehouse_product_model->get_all_warehouse_product(),REST_Controller::HTTP_OK);
    }

    public function index_put()
    {
        $id = $this->put('id');
        $warehouse_id = $this->put('warehouseId');
        $product_id = $this->put('productId');

        if (!isset($id) || !isset($warehouse_id) || !isset($product_id)) {
            $requrired_parameters = [];
            if(!isset($id)) array_push($requrired_parameters, 'id');
            if(!isset($warehouse_id)) array_push($requrired_parameters, 'warehouseId');
            if(!isset($product_id)) array_push($requrired_parameters, 'productId');
            $this->response(
                array(
                    'status' => FALSE,
                    'message' => $this::REQUIRED_PARAMETER_MESSAGE.implode(', ', $requrired_parameters)
                ),REST_Controller::HTTP_BAD_REQUEST
            );
            return;
        }

        if ($this->warehouse_model->is_not_exists($warehouse_id)) {
            $this->response(
                array(
                    'status' => FALSE,
                    'message' => $this::INVALID_ID_MESSAGE." warehouseId does not exist"
                ),REST_Controller::HTTP_BAD_REQUEST
            );
            return;
        }

        if ($this->product_model->is_not_exists($product_id)) {
            $this->response(
                array(
                    'status' => FALSE,
                    'message' => $this::INVALID_ID_MESSAGE." productId does not exist"
                ),REST_Controller::HTTP_BAD_REQUEST
            );
            return;
        }

        if ($this->warehouse_product_model->update_warehouse_product($id, $warehouse_id, $product_id)) {
            $this->response(
                array(
                    'status' => TRUE,
                    'message' => $this::UPDATE_SUCCESS_MESSSAGE
                ),REST_Controller::HTTP_OK
            );
        } else {
            $this->response(
                array(
                    'status' => FALSE,
                    'message' => $this::UPDATE_FAILED_MESSAGE
                ),REST_Controller::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    public function index_delete()
    {
        $id = $this->input->delete('id');

        if (!isset($id)) {
            $this->response(
                array(
                    'status' => FALSE,
                    'message' => $this::REQUIRED_PARAMETER_MESSAGE."id"
                ),REST_Controller::HTTP_BAD_REQUEST
            );
            return;
        }

        if ($this->warehouse_product_model->is_not_exists($id)) {
            $this->response(
                array(
                    'status' => FALSE,
                    'message' => $this::INVALID_ID_MESSAGE." id does not exist"
                ),REST_Controller::HTTP_BAD_REQUEST
            );
            return;
        }

        if ($this->warehouse_product_model->delete_warehouse_product($id)) {
            $this->response(
                array(
                    'status' => TRUE,
                    'message' => $this::DELETE_SUCCESS_MESSSAGE
                ),REST_Controller::HTTP_OK
            );
        } else {
            $this->response(
                array(
                    'status' => FALSE,
                    'message' => $this::DELETE_FAILED_MESSAGE
                ),REST_Controller::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
