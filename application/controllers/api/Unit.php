<?php

defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

use Restserver\Libraries\REST_Controller;

class Unit extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model("unit_model");
    }

    public function index_post()
    {
        $name = $this->post('name');
        $abbreviation = $this->post('abbreviation');
        $description = $this->post('description');

        if (!isset($name) || !isset($abbreviation)) {
            $required_parameters = [];
            if (!isset($name)) array_push($required_parameters, 'name');
            if (!isset($abbreviation)) array_push($required_parameters, 'abbreviation');
            $this->response(
                array(
                    'status' => FALSE,
                    'message' => $this::REQUIRED_PARAMETER_MESSAGE . implode(', ', $required_parameters)
                ),
                REST_Controller::HTTP_BAD_REQUEST
            );
            return;
        }

        if($this->unit_model->is_name_exists($name)){
            $this->response(
                array(
                    'status' => FALSE,
                    'message' => $this::NAME_EXISTS_MESSAGE
                ),REST_Controller::HTTP_BAD_REQUEST
            );
            return;
        }

        if ($this->unit_model->insert_unit($name, $abbreviation, $description)) {
            $this->response(
                array(
                    'status' => TRUE,
                    'message' => $this::INSERT_SUCCESS_MESSSAGE
                ),
                REST_Controller::HTTP_CREATED
            );
        } else {
            $this->response(
                array(
                    'status' => FALSE,
                    'message' => $this::REQUIRED_PARAMETER_MESSAGE
                ),
                REST_Controller::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    public function index_get()
    {
        $id = $this->get('id');

        if (isset($id)) $this->response($this->unit_model->get_unit_where($id), REST_Controller::HTTP_OK);
        else $this->response($this->unit_model->get_all_unit(), REST_Controller::HTTP_OK);
    }

    public function index_put()
    {
        $id = $this->put('id');
        $name = $this->put('name');
        $abbreviation = $this->put('abbreviation');
        $description = $this->put('description');


        if (!isset($id) || !isset($abbreviation) || !isset($name) || !isset($description)) {
            $required_parameters = [];
            if (!isset($id)) array_push($required_parameters, 'id');
            if (!isset($name)) array_push($required_parameters, 'name');
            if (!isset($abbreviation)) array_push($required_parameters, 'abbreviation');
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

        if ($this->unit_model->is_not_exists($id)) {
            $this->response(
                array(
                    'status' => FALSE,
                    'message' => $this::INVALID_ID_MESSAGE . " id does not exist"
                ),
                REST_Controller::HTTP_BAD_REQUEST
            );
            return;
        }

        if ($this->unit_model->update_unit($id, $name, $abbreviation, $description)) {
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

    public function index_delete()
    {
        $id = $this->input->get('id');

        if (!isset($id)) {
            $this->response(
                array(
                    'status' => FALSE,
                    'message' => $this::REQUIRED_PARAMETER_MESSAGE . "id"
                ),
                REST_Controller::HTTP_BAD_REQUEST
            );
            return;
        }

        if ($this->unit_model->is_not_exists($id)) {
            $this->response(
                array(
                    'status' => FALSE,
                    'message' => $this::INVALID_ID_MESSAGE . " id does not exist"
                ),
                REST_Controller::HTTP_BAD_REQUEST
            );
            return;
        }

        if ($this->unit_model->delete_unit($id)) {
            $this->response(
                array(
                    'status' => TRUE,
                    'message' => $this::DELETE_SUCCESS_MESSSAGE
                ),
                REST_Controller::HTTP_OK
            );
        } else {
            $this->response(
                array(
                    'status' => FALSE,
                    'message' => $this::DELETE_FAILED_MESSAGE
                ),
                REST_Controller::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
