<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

use Restserver\Libraries\REST_Controller;


class User_task_group extends REST_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model('user_task_group_model');
        $this->load->model('user_model');
        $this->load->model('user_task_model');
    }

    public function index_post(){
        $name = $this->post('name');

        if(!isset($name)){
            $this->response(
                array(
                    'status' => FALSE,
                    'message' => $this::REQUIRED_PARAMETER_MESSAGE."name"
                ),REST_Controller::HTTP_BAD_REQUEST
            );
            return;
        }

        if($this->user_task_group_model->insert_user_task_group($name)){
            $this->response(
                array(
                    'status' => TRUE,
                    'id' => $this->db->insert_id(),
                    'message' => $this::INSERT_SUCCESS_MESSSAGE
                ),REST_Controller::HTTP_CREATED
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

        if(isset($id)) $this->response($this->user_task_group_model->get_user_task_group_where($id),REST_Controller::HTTP_OK);
        else $this->response($this->user_task_group_model->get_all_user_task_group(),REST_Controller::HTTP_OK);
    }

    public function index_put(){
        $id = $this->put('id');
        $name = $this->put('name');

        if(!isset($id) || !isset($name)){
            $required_parameters = [];
            if(!isset($id)) array_push($required_parameters, 'id');
            if(!isset($name)) array_push($required_parameters, 'name');

            $this->response(
                array(
                    'status' => FALSE,
                    'message' => $this::REQUIRED_PARAMETER_MESSAGE.implode(', ', $required_parameters)
                ),REST_Controller::HTTP_BAD_REQUEST
            );
            return;
        }

        if($this->user_task_group_model->is_not_exists($id)){
            $this->response(
                array(
                    'status' => FALSE,
                    'message' => $this::INVALID_ID_MESSAGE." id does not exist"
                ),REST_Controller::HTTP_BAD_REQUEST
            );
            return;
        }

        if($this->user_task_group_model->update_user_task_group($id, $name)){
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
        $id = $this->input->get('id');

        if(!isset($id)){
            $this->response(
                array(
                    'status' => FALSE,
                    'message' => $this::REQUIRED_PARAMETER_MESSAGE."id"
                ),REST_Controller::HTTP_BAD_REQUEST
            );
            return;
        }

        if($this->user_task_group_model->is_not_exists($id)){
            $this->response(
                array(
                    'status' => FALSE,
                    'message' => $this::INVALID_ID_MESSAGE." id does not exist"
                ),REST_Controller::HTTP_BAD_REQUEST
            );
            return;
        }

        $user_task_group_id = $this->user_task_group_model->get_user_task_group_where($id)[0]['id'];
        $result = $this->user_model->get_by_user_task_group_id($user_task_group_id);
        if($this->user_task_group_model->delete_user_task_group($id)){
            foreach ($result as $user){
                if(!$this->user_task_model->delete_user_task($user['id'])){
                    $this->response(
                        array(
                            'status' => TRUE,
                            'message' => $this::DELETE_FAILED_MESSAGE." Details: delete user_task_group succeed but failed on delete user_task"
                        ),REST_Controller::HTTP_INTERNAL_SERVER_ERROR
                    );
                    return;
                }
            }
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