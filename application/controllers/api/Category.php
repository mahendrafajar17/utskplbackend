<?php

defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

use Restserver\Libraries\REST_Controller;

class Category extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model("category_model");
    }

    public function index_post()
    {
        $name = $this->post('name');

        if (!isset($name)) {
            $this->response(
                array(
                    'status' => FALSE,
                    'message' => $this::REQUIRED_PARAMETER_MESSAGE . " Required parameter(s): name"
                ),
                REST_Controller::HTTP_BAD_REQUEST
            );
            return;
        }

        if($this->category_model->is_name_exists($name)){
            $this->response(
                array(
                    'status' => FALSE,
                    'message' => $this::NAME_EXISTS_MESSAGE
                ),REST_Controller::HTTP_BAD_REQUEST
            );
            return;
        }

        if ($this->category_model->insert_category($name)) {
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
                    'message' => $this::INSERT_FAILED_MESSAGE
                ),
                REST_Controller::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    public function index_get()
    {
        $id = $this->get('id');

        if (isset($id)) $this->response($this->category_model->get_category_where($id), REST_Controller::HTTP_OK);
        else $this->response($this->category_model->get_all_category(), REST_Controller::HTTP_OK);
    }

    public function index_put()
    {
        $id = $this->put('id');
        $name = $this->put('name');

        if (!isset($id) || !isset($name)) {
            $required_parameters = [];
            if (!isset($id)) array_push($required_parameters, 'id');
            if (!isset($name)) array_push($required_parameters, 'name');

            $this->response(
                array(
                    'status' => FALSE,
                    'message' => $this::REQUIRED_PARAMETER_MESSAGE . " Required parameter(s): " . implode(', ', $required_parameters)
                ),
                REST_Controller::HTTP_BAD_REQUEST
            );
            return;
        }

        if ($this->category_model->is_not_exists($id)) {
            $this->response(
                array(
                    'status' => FALSE,
                    'message' => $this::INVALID_ID_MESSAGE
                ),
                REST_Controller::HTTP_BAD_REQUEST
            );
            return;
        }

        if ($this->category_model->update_category($id, $name)) {
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

        if ($this->category_model->is_not_exists($id)) {
            $this->response(
                array(
                    'status' => FALSE,
                    'message' => $this::INVALID_ID_MESSAGE
                ),
                REST_Controller::HTTP_BAD_REQUEST
            );
            return;
        }

        if ($this->category_model->delete_category($id)) {
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
