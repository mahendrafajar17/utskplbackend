<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

use Restserver\Libraries\REST_Controller;

class Stock_record extends REST_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model("stock_record_model");
        $this->load->model('user_model');
        $this->load->model('product_model');
    }

    public function index_post(){
        $broker = $this->post('broker');
        $order_item = $this->post('orderItem');
        $order_date = $this->post('orderDate');
        $order_number = $this->post('orderNumber');
        $quantity_in = $this->post('quantityIn');
        $quantity_out = $this->post('quantityOut');
        $order_status = $this->post('orderStatus');
        $seller = $this->post('seller');
        
        

        if(!isset($broker) || !isset($order_item) || !isset($order_date) || !isset($order_number) || 
        !isset($quantity_in) || !isset($quantity_out) || !isset($order_status) || !isset($seller)){
            $required_parameters = [];
            if(!isset($broker)) array_push($required_parameters, 'broker');
            if(!isset($order_item)) array_push($required_parameters, 'orderItem');
            if(!isset($order_date)) array_push($required_parameters, 'orderDate');
            if(!isset($order_number)) array_push($required_parameters, 'orderNumber');
            if(!isset($quantity_in)) array_push($required_parameters, 'quantityIn');
            if(!isset($quantity_out)) array_push($required_parameters, 'quantityOut');
            if(!isset($order_status)) array_push($required_parameters, 'orderStatus');
            if(!isset($seller)) array_push($required_parameters, 'seller');
            $this->response(
                array(
                    'status' => FALSE,
                    'message' => $this::REQUIRED_PARAMETER_MESSAGE.implode(', ', $required_parameters)
                ),REST_Controller::HTTP_BAD_REQUEST
            );
            return;
        }

        // if($this->user_model->is_not_exists($broker)){
        //     $this->response(
        //         array(
        //             'status' => FALSE,
        //             'message' => $this::INVALID_ID_MESSAGE." broker does not exist"
        //         ),REST_Controller::HTTP_BAD_REQUEST
        //     );
        //     return;
        // };

        if($this->product_model->is_not_exists($order_item)){
            $this->response(
                array(
                    'status' => FALSE,
                    'message' => $this::INVALID_ID_MESSAGE." orderItem does not exist"
                ),REST_Controller::HTTP_BAD_REQUEST
            );
            return;
        };

        if($this->stock_record_model->insert_stock_record($broker, $order_item, $order_date, $order_number,
        $quantity_in, $quantity_out, $order_status, $seller)){
            $this->response(
                array(
                    'status' => TRUE,
                    'message' => $this::INSERT_SUCCESS_MESSSAGE
                ),REST_Controller::HTTP_CREATED
            );
        }else{
            $this->response(
                array(
                    'status' => FALSE,
                    'message' => $this::INSERT_FAILED_MESSAGE
                ),REST_Controller::HTTP_BAD_REQUEST
            );
        }
    }

    public function index_get(){
        $id = $this->get('id');

        if(isset($id)) $this->response($this->stock_record_model->get_stock_record_where($id),REST_Controller::HTTP_OK);
        else $this->response($this->stock_record_model->get_all_stock_record(),REST_Controller::HTTP_OK);
    }

    public function index_put(){
        $id = $this->put('id');
        $broker = $this->put('broker');
        $order_item = $this->put('orderItem');
        $order_date = $this->put('orderDate');
        $order_number = $this->put('orderNumber');
        $quantity_in = $this->put('quantityIn');
        $quantity_out = $this->put('quantityOut');
        $order_status = $this->put('orderStatus');
        $seller = $this->put('seller');

        if(!isset($id) || !isset($broker) || !isset($order_item) || !isset($order_date) || !isset($order_number) || 
        !isset($quantity_in) || !isset($quantity_out) || !isset($order_status) || !isset($seller)){
            $required_parameters = [];
            if(!isset($broker)) array_push($required_parameters, 'broker');
            if(!isset($order_item)) array_push($required_parameters, 'orderItem');
            if(!isset($order_date)) array_push($required_parameters, 'orderDate');
            if(!isset($order_number)) array_push($required_parameters, 'orderNumber');
            if(!isset($quantity_in)) array_push($required_parameters, 'quantityIn');
            if(!isset($quantity_out)) array_push($required_parameters, 'quantityOut');
            if(!isset($order_status)) array_push($required_parameters, 'orderStatus');
            if(!isset($seller)) array_push($required_parameters, 'seller');
            $this->response(
                array(
                    'status' => FALSE,
                    'message' => $this::REQUIRED_PARAMETER_MESSAGE.implode(', ', $required_parameters)
                ),REST_Controller::HTTP_BAD_REQUEST
            );
            return;
        }

        if($this->stock_record_model->is_not_exists($broker) || $this->user_model->is_not_exists($broker)){
            $this->response(
                array(
                    'status' => FALSE,
                    'message' => $this::INVALID_ID_MESSAGE." broker does not exist"
                ),REST_Controller::HTTP_BAD_REQUEST
            );
            return;
        }

        if($this->product_model->is_not_exists($order_item)){
            $this->response(
                array(
                    'status' => FALSE,
                    'message' => $this::INVALID_ID_MESSAGE." orderItem does not exist"
                ),REST_Controller::HTTP_BAD_REQUEST
            );
            return;
        };
        
        if($this->stock_record_model->update_stock_record($id, $broker, $order_item, $order_date, $order_number,
        $quantity_in, $quantity_out, $order_status, $seller)){
            $this->response(
                array(
                    'status' => TRUE,
                    'message' => $this::UPDATE_SUCCESS_MESSSAGE
                ),REST_Controller::HTTP_OK
            );
        }else{
            $this->response(
                array(
                    'status' => FALSE,
                    'message' => $this::UPDATE_FAILED_MESSAGE
                ),REST_Controller::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    public function index_delete(){
        $id = $this->input->delete('id');

        if(!isset($id)){
            $this->response(
                array(
                    'status' => FALSE,
                    'message' => $this::REQUIRED_PARAMETER_MESSAGE." id"
                ),REST_Controller::HTTP_BAD_REQUEST
            );
            return;
        }

        if($this->stock_record_model->is_not_exists($id)){
            $this->response(
                array(
                    'status' => FALSE,
                    'message' => $this::INVALID_ID_MESSAGE." id does not exist"
                ),REST_Controller::HTTP_BAD_REQUEST
            );
            return;
        }

        if($this->stock_record_model->delete_stock_record($id)){
            $this->response(
                array(
                    'status' => TRUE,
                    'message' => $this::DELETE_SUCCESS_MESSSAGE
                ),REST_Controller::HTTP_OK
            );
        }else{
            $this->response(
                array(
                    'status' => FALSE,
                    'message' => $this::DELETE_FAILED_MESSAGE
                ),REST_Controller::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}