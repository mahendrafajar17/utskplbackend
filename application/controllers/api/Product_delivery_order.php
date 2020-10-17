<?php

defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

use Restserver\Libraries\REST_Controller;

class Product_delivery_order extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('product_model');
        $this->load->model('delivery_order_model');
        $this->load->model('product_delivery_order_model');
    }

    // POST - input data baru
    public function index_post()
    {
        $product_id = $this->post('productId');
        $delivery_order_id = $this->post('deliveryOrderId');

        if (!isset($delivery_order_id) || !isset($product_id)) {
            $required_parameters = [];
            if (!isset($delivery_order_id)) array_push($required_parameters, 'deliveryOrderId');
            if (!isset($product_id)) array_push($required_parameters, 'productId');
            $this->response(
                array(
                    'status' => FALSE,
                    'message' => $this::REQUIRED_PARAMETER_MESSAGE . implode(', ', $required_parameters)
                ),
                REST_Controller::HTTP_BAD_REQUEST
            );
            return;
        }

        if ($this->delivery_order_model->is_not_exists($delivery_order_id)) {
            $this->response(
                array(
                    'status' => FALSE,
                    'message' => $this::INVALID_ID_MESSAGE . " productId does not exist"
                ),
                REST_Controller::HTTP_BAD_REQUEST
            );
            return;
        }

        foreach ($product_id as $item) {
            if ($this->product_model->is_not_exists($item)) {
                $this->response(
                    array(
                        'status' => FALSE,
                        'message' => $this::INVALID_ID_MESSAGE . " delivery_orderId does not exist"
                    ),
                    REST_Controller::HTTP_BAD_REQUEST
                );
                return;
            }
        }

        if ($this->product_delivery_order_model->insert_product_delivery_order($delivery_order_id, $product_id)) {
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
        $product_id = $this->get('productId');

        if (isset($delivery_order_id)) $this->response($this->product_delivery_order_model->get_product_delivery_order_where($product_id), REST_Controller::HTTP_OK);
        else $this->response($this->product_delivery_order_model->get_all_product_delivery_order(), REST_Controller::HTTP_OK);
    }

    // PUT - update data sesuai dengan id grup user task, kemudian merubah semua task yang dimiliki oleh user pada grup tersebut
    public function index_put()
    {
        $product_id = $this->put('productId');
        $delivery_order_id = $this->put('deliveryOrderId');

        if (!isset($product_id) || !isset($delivery_order_id)) {
            $required_parameters = [];
            if (!isset($product_id)) array_push($required_parameters, 'productId');
            if (!isset($delivery_order_id)) array_push($required_parameters, 'deliveryOrderId');

            $this->response(
                array(
                    'status' => FALSE,
                    'message' => $this::REQUIRED_PARAMETER_MESSAGE . implode(', ', $required_parameters)
                ),
                REST_Controller::HTTP_BAD_REQUEST
            );
            return;
        }


        if ($this->delivery_order_model->is_not_exists($delivery_order_id)) {
            $this->response(
                array(
                    'status' => FALSE,
                    'message' => $this::INVALID_ID_MESSAGE . " deliveryOrderId does not exist"
                ),
                REST_Controller::HTTP_BAD_REQUEST
            );
            return;
        }

        foreach ($product_id as $item) {
            if ($this->product_model->is_not_exists($item)) {
                $this->response(
                    array(
                        'status' => FALSE,
                        'message' => $this::INVALID_ID_MESSAGE . " productId does not exist"
                    ),
                    REST_Controller::HTTP_INTERNAL_SERVER_ERROR
                );
                return;
            }
        }

        if ($this->product_delivery_order_model->update_product_delivery_order($delivery_order_id, $product_id)) {
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


    // DELETE - menghapus seluruh record sesuai dengan id grup user task
    public function index_delete()
    {
        $id = $this->input->get('id');
        $product_id = $this->input->get('productId');

        if (isset($id)) {
            if ($this->product_delivery_order_model->is_not_exists($id)) {
                $this->response(
                    array(
                        'status' => FALSE,
                        'message' => $this::INVALID_ID_MESSAGE . " id does not exist"
                    ),
                    REST_Controller::HTTP_BAD_REQUEST
                );
                return;
            }

            if ($this->product_delivery_order_model->delete_product_delivery_order($id)) {
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


        if(isset($product_id)){
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

            if ($this->product_delivery_order_model->delete_product_delivery_order_by_product_id($product_id)) {
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
