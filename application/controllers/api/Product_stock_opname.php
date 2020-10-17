<?php

defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

use Restserver\Libraries\REST_Controller;

class Product_stock_opname extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('product_model');
        $this->load->model('user_model');
        $this->load->model('stock_opname_model');
        $this->load->model('product_stock_opname_model');
    }

    // POST - input data baru
    public function index_post()
    {
        $opname_id = $this->post('opnameId');
        $product_id = $this->post('productId');
        $inspector_id = $this->post('inspectorId');
        $real_stock = $this->post('realStock');
        $opname_stock = $this->post('opnameStock');
        $opname_date = $this->post('opnameDate');
        $status = $this->post('status');
        $description = $this->post('description');

        if (!isset($opname_id) || !isset($product_id) || !isset($real_stock) || !isset($status) || !isset($description) ) {
            $required_parameters = [];
            if (!isset($opname_id)) array_push($required_parameters, 'opnameId');
            if (!isset($product_id)) array_push($required_parameters, 'productId');
            if (!isset($real_stock)) array_push($required_parameters, 'realStock');
            if (!isset($status)) array_push($required_parameters, 'status');
            if (!isset($description)) array_push($required_parameters, 'description');
            $this->response(
                array(
                    'status' => FALSE,
                    'message' => $this::REQUIRED_PARAMETER_MESSAGE . implode(', ', $required_parameters)
                ),
                REST_Controller::HTTP_BAD_REQUEST
            );
            return;
        }

        if ($this->stock_opname_model->is_not_exists($opname_id)) {
            $this->response(
                array(
                    'status' => FALSE,
                    'message' => $this::INVALID_ID_MESSAGE . " opnameId does not exist"
                ),
                REST_Controller::HTTP_BAD_REQUEST
            );
            return;
        }

        if ($this->product_model->is_not_exists($product_id)) {
            $this->response(
                array(
                    'status' => FALSE,
                    'message' => $this::INVALID_ID_MESSAGE . " productId does not exist"
                ),
                REST_Controller::HTTP_BAD_REQUEST
            );
            return;
        }

        if(isset($inspector_id))
        if ($this->user_model->is_not_exists($inspector_id)) {
            $this->response(
                array(
                    'status' => FALSE,
                    'message' => $this::INVALID_ID_MESSAGE . " inspectorId does not exist"
                ),
                REST_Controller::HTTP_BAD_REQUEST
            );
            return;
        }

        if ($this->product_stock_opname_model->insert_product_stock_opname($opname_id, $product_id, $inspector_id, $real_stock, $opname_stock, $opname_date, $status, $description)) {
            $this->response(
                array(
                    'status' => TRUE,
                    'message' => $this::INSERT_SUCCESS_MESSSAGE,
                    
                ),
                REST_Controller::HTTP_CREATED
            );
        } else {
            $this->response(
                array(
                    'status' => FALSE,
                    'message' => $this::INSERT_FAILED_MESSAGE
                ),
                REST_Controller::HTTP_INTERNAL_SERVER_ERROR
            );
        }

        return;
    }

    // GET - mengambil data
    public function index_get()
    {
        $opname_id = $this->get('opnameId');
        $id = $this->get('id');
        if (isset($id)) $this->response($this->product_stock_opname_model->get_product_stock_opname_where($id), REST_Controller::HTTP_OK);
        else if (isset($opname_id)) $this->response($this->product_stock_opname_model->get_product_stock_opname_by_opname_id($opname_id), REST_Controller::HTTP_OK);
        else $this->response($this->product_stock_opname_model->get_all_product_stock_opname(), REST_Controller::HTTP_OK);
    }

    // PUT - update data sesuai dengan id grup user task, kemudian merubah semua task yang dimiliki oleh user pada grup tersebut
    public function index_put()
    { 
        $id = $this->put('id');
        $opname_id = $this->put('opnameId');
        $product_id = $this->put('productId');
        $inspector_id = $this->put('inspectorId');
        $real_stock = $this->put('realStock');
        $opname_stock = $this->put('opnameStock');
        $opname_date = $this->put('opnameDate');
        $status = $this->put('status');
        $description = $this->put('description');
        $checked = $this->put('checked');

        if (!isset($id) || !isset($opname_id) || !isset($product_id) || !isset($real_stock) || !isset($description) ) {
            $required_parameters = [];
            if (!isset($id)) array_push($required_parameters, 'id');
            if (!isset($opname_id)) array_push($required_parameters, 'opnameId');
            if (!isset($product_id)) array_push($required_parameters, 'productId');
            if (!isset($real_stock)) array_push($required_parameters, 'realStock');
            if (!isset($description)) array_push($required_parameters, 'description');
            $this->response(
                array(
                    'status' => FALSE,
                    'message' => $this::REQUIRED_PARAMETER_MESSAGE . implode(', ', $required_parameters)
                ),
                REST_Controller::HTTP_BAD_REQUEST
            );
            return;
        }

        if ($this->product_stock_opname_model->is_not_exists($id)) {
            $this->response(
                array(
                    'status' => FALSE,
                    'message' => $this::INVALID_ID_MESSAGE . " id does not exist"
                ),
                REST_Controller::HTTP_BAD_REQUEST
            );
            return;
        }

        if ($this->product_model->is_not_exists($product_id)) {
            $this->response(
                array(
                    'status' => FALSE,
                    'message' => $this::INVALID_ID_MESSAGE . " productId does not exist"
                ),
                REST_Controller::HTTP_BAD_REQUEST
            );
            return;
        }

        if(isset($inspector_id))
        if ($this->user_model->is_not_exists($inspector_id)) {
            $this->response(
                array(
                    'status' => FALSE,
                    'message' => $this::INVALID_ID_MESSAGE . " inspectorId does not exist"
                ),
                REST_Controller::HTTP_BAD_REQUEST
            );
            return;
        }

        if ($this->product_stock_opname_model->update_product_stock_opname($id, $opname_id, $product_id, $inspector_id, $real_stock, $opname_stock, $opname_date, $status, $description, $checked)) {
            $this->response(
                array(
                    'status' => TRUE,
                    'message' => $this::UPDATE_SUCCESS_MESSSAGE
                ),
                REST_Controller::HTTP_OK
            );
        } else {
            $this->response(
                array(
                    'status' => FALSE,
                    'message' => $this::UPDATE_FAILED_MESSAGE
                ),
                REST_Controller::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }


    // DELETE
    public function index_delete()
    {
        $id = $this->input->get('id');
        $opname_id = $this->input->get('opnameId');

        if (isset($id)) {
            if ($this->product_stock_opname_model->is_not_exists($id)) {
                $this->response(
                    array(
                        'status' => FALSE,
                        'message' => $this::INVALID_ID_MESSAGE . " id does not exist"
                    ),
                    REST_Controller::HTTP_BAD_REQUEST
                );
                return;
            }

            if ($this->product_stock_opname_model->delete_product_stock_opname($id)) {
                $this->response(
                    array(
                        'status' => TRUE,
                        'message' => $this::DELETE_SUCCESS_MESSSAGE
                    ),
                    REST_Controller::HTTP_OK
                );
                return;
            } else {
                $this->response(
                    array(
                        'status' => FALSE,
                        'message' => $this::DELETE_FAILED_MESSAGE
                    ),
                    REST_Controller::HTTP_OK
                );
                return;
            }
        }


        if(isset($opname_id)){
            if ($this->stock_opname_model->is_not_exists($opname_id)) {
                $this->response(
                    array(
                        'status' => FALSE,
                        'message' => $this::INVALID_ID_MESSAGE . " productId does not exist"
                    ),
                    REST_Controller::HTTP_BAD_REQUEST
                );
                return;
            }

            if ($this->product_stock_opname_model->delete_product_stock_opname_by_product_id($opname_id)) {
                $this->response(
                    array(
                        'status' => TRUE,
                        'message' => $this::DELETE_SUCCESS_MESSSAGE
                    ),
                    REST_Controller::HTTP_OK
                );
                return;
            } else {
                $this->response(
                    array(
                        'status' => FALSE,
                        'message' => $this::DELETE_FAILED_MESSAGE
                    ),
                    REST_Controller::HTTP_OK
                );
                return;
            }
        }
    }
}
