<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

use Restserver\Libraries\REST_Controller;

class User_task extends REST_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model('user_task_model');
    }

    public function index_post(){
        $user_id = $this->post('userId');
        $data = $this->post('taskId'); //array of task_id
       

        if(!isset($user_id) || !isset($data)){
            $required_parameters = [];
            if(!isset($user_id)) array_push($required_parameters, 'id');
            if(!isset($data)) array_push($required_parameters, 'data');
            $this->response(
                array(
                    'status' => FALSE,
                    'message' => $this::REQUIRED_PARAMETER_MESSAGE
                ),REST_Controller::HTTP_BAD_REQUEST
            );
            return;
        }

        if($this->user_task_model->insert_user_task($user_id, $data)){
            $this->response(
                array(
                    'status' => TRUE,
                    'message' => $this::INSERT_SUCCESS_MESSSAGE
                ),REST_Controller::HTTP_OK
            );
        }else{
            $this->response(
                array(
                    'status' => FALSE,
                    'message' => $this::INVALID_ID_MESSAGE
                ),REST_Controller::HTTP_INTERNAL_SERVER_ERROR
            );
        }

    }

    public function index_get(){
        $id = $this->get('id');
        
        if(isset($id)) $this->response($this->user_task_model->get_user_task_where($id), REST_Controller::HTTP_OK);
        else $this->response($this->user_task_model->get_all_user_task(), REST_Controller::HTTP_OK);
    }

    public function index_put(){
        // $id = $this->put('id');
        $user_id = $this->put('userId');
        $data = $this->put('taskId');

        if(!isset($user_id)){
            $this->response(
                array(
                    'status' => FALSE,
                    'message' => $this::REQUIRED_PARAMETER_MESSAGE."userId"
                ),REST_Controller::HTTP_BAD_REQUEST
            );
            return;
        }

        if($this->user_task_model->is_not_exists($user_id)){
            $this->response(
                array(
                    'status' => FALSE,
                    'message' => $this::INVALID_ID_MESSAGE
                ),REST_Controller::HTTP_BAD_REQUEST
            );
            return;
        }

        if($this->user_task_model->update_user_task($user_id, $data)){
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
        $user_id = $this->input->get('userId');

        if(!isset($user_id)){
            $this->response(
                array(
                    'status' => FALSE,
                    'message' => $this::REQUIRED_PARAMETER_MESSAGE."userId"
                ),REST_Controller::HTTP_BAD_REQUEST
            );
            return;
        }

        if($this->user_task_model->is_not_exists($user_id)){
            $this->response(
                array(
                    'status' => FALSE,
                    'message' => $this::INVALID_ID_MESSAGE." userId does not exist"
                ),REST_Controller::HTTP_BAD_REQUEST
            );
            return;
        }

        if($this->user_task_model->delete_user_task($user_id)){
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