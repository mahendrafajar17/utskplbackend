<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

use Restserver\Libraries\REST_Controller;

class Order_status extends REST_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model("order_status_model");
    }

    public function index_post(){
        $status = $this->post('status');

        if(!isset($status)){
            $this->response(
                array(
                    'status' => FALSE,
                    'message' => $this::REQUIRED_PARAMETER_MESSAGE."status"
                ),REST_Controller::HTTP_BAD_REQUEST
            );
            return;
        }

        if($this->order_status_model->insert_order_status($status)){
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
                ),REST_Controller::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    public function index_get(){
        $id = $this->get('id');

        if(isset($id)) $this->response($this->order_status_model->get_order_status_where($id),REST_Controller::HTTP_OK);
        else $this->response($this->order_status_model->get_all_order_status(),REST_Controller::HTTP_OK);
    }

    public function index_put(){
        $id = $this->put('id');
        $status = $this->put('status');

        if(!isset($id) || !isset($status)){
            $request_parameters = [];
            if(!isset($id)) array_push($request_parameters, 'id');
            if(!isset($status)) array_push($request_parameters, 'status');

            $this->response(
                array(
                    'status' => FALSE,
                    'message' => $this::REQUIRED_PARAMETER_MESSAGE. implode(', ', $request_parameters)
                ),REST_Controller::HTTP_BAD_REQUEST
            );
            return;
        }

        if($this->order_status_model->is_not_exists($id)){
            $this->response(
                array(
                    'status' => FALSE,
                    'message' => $this::INVALID_ID_MESSAGE." id does not exist"
                ),REST_Controller::HTTP_BAD_REQUEST
            );
            return;
        }
        
        if($this->order_status_model->update_order_status($id, $status)){
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
                    'message' => $this::REQUIRED_PARAMETER_MESSAGE."id"
                ),REST_Controller::HTTP_BAD_REQUEST
            );
            return;
        }

        if($this->order_status_model->is_not_exists($id)){
            $this->response(
                array(
                    'status' => FALSE,
                    'message' => $this::INVALID_ID_MESSAGE." id does not exist"
                ),REST_Controller::HTTP_BAD_REQUEST
            );
            return;
        }

        if($this->order_status_model->delete_order_status($id)){
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