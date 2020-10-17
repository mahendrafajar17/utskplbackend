<?php

defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

use Restserver\Libraries\REST_Controller;

class Task extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model("task_model");
    }

    public function index_post()
    {
        $action = $this->post('action');
        $label =  $this->post('label');
        $modul = $this->post('modul');

        if (!isset($action) || !isset($label) || !isset($modul)) {
            $required_parameters = [];
            if(!isset($action)) array_push($required_parameters, 'action');
            if(!isset($label)) array_push($required_parameters, 'label');
            if(!isset($modul)) array_push($required_parameters, 'modul');
            $this->response(
                array(
                    'status' => FALSE,
                    'message' => $this::REQUIRED_PARAMETER_MESSAGE.implode(', ', $required_parameters)
                ),REST_Controller::HTTP_BAD_REQUEST
            );
            return;
        }

        if ($this->task_model->insert_task($action, $label, $modul)) {
            $this->response(
                array(
                    'status' => TRUE,
                    'message' => $this::INSERT_SUCCESS_MESSSAGE
                ),REST_Controller::HTTP_OK
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

        if (isset($id)) $this->response($this->task_model->get_task_where($id),REST_Controller::HTTP_OK);
        else $this->response($this->task_model->get_all_task(),REST_Controller::HTTP_OK);
    }

    public function index_put()
    {
        $id = $this->put('id');
        $action = $this->put('action');
        $label = $this->put('label');
        $modul = $this->put('modul');

        if (!isset($id) || !isset($action) || !isset($label) || !isset($modul)) {
            $required_parameters = [];
            if(!isset($id)) array_push($required_parameters, 'id');
            if(!isset($action)) array_push($required_parameters, 'action');
            if(!isset($label)) array_push($required_parameters, 'label');
            if(!isset($modul)) array_push($required_parameters, 'modul');
            $this->response(
                array(
                    'status' => FALSE,
                    'message' => $this::REQUIRED_PARAMETER_MESSAGE.implode(', ', $required_parameters)
                ),REST_Controller::HTTP_BAD_REQUEST
            );
            return;
        }

        if ($this->task_model->is_not_exists($id)) {
            $this->response(
                array(
                    'status' => FALSE,
                    'message' => $this::INVALID_ID_MESSAGE." id does not exist"
                ),REST_Controller::HTTP_BAD_REQUEST
            );
            return;
        }

        if ($this->task_model->update_task($id, $action, $label, $modul)) {
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
        $id = $this->input->get('id');

        if (!isset($id)) {
            $this->response(
                array(
                    'status' => FALSE,
                    'message' => $this::REQUIRED_PARAMETER_MESSAGE." id"
                ),REST_Controller::HTTP_BAD_REQUEST
            );
            return;
        }

        if ($this->task_model->is_not_exists($id)) {
            $this->response(
                array(
                    'status' => FALSE,
                    'message' => $this::INVALID_ID_MESSAGE." id does not exist"
                ),REST_Controller::HTTP_BAD_REQUEST
            );
            return;
        }

        if ($this->task_model->delete_task($id)) {
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
