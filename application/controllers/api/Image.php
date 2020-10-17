<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

use Restserver\Libraries\REST_Controller;

class Image extends REST_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model('image_model');
        $this->load->model('category_model');
    }

    public function index_post(){
        $catergory_id = $this->post('categoryId');
        $image = $this->post('image');

        if(!isset($catergory_id) || !isset($image)){
            $required_parameters = [];
            if(!isset($catergory_id)) array_push($required_parameters, 'categoryId');
            if(!isset($image)) array_push($required_parameters, 'image');
            $this->response(
                array(
                    'status' => FALSE,
                    'message' => $this::REQUIRED_PARAMETER_MESSAGE.implode(', ', $required_parameters)
                ),REST_Controller::HTTP_BAD_REQUEST
            );
            return;
        }

        if($this->category_model->is_not_exists($catergory_id)){
            $this->response(
                array(
                    'status' => FALSE,
                    'message' => $this::INVALID_ID_MESSAGE." categoryId does not exist"
                ),REST_Controller::HTTP_BAD_REQUEST
            );
            return;
        };

        if($this->image_model->insert_image($catergory_id, $image)){
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

        if(isset($id)) $this->response($this->image_model->get_image_where($id),REST_Controller::HTTP_OK);
        else $this->response($this->image_model->get_all_image(),REST_Controller::HTTP_OK);
    }

    public function index_put(){
        $id = $this->put('id');
        $catergory_id = $this->put('categoryId');
        $image = $this->put('image');

        if(!isset($id) || !isset($catergory_id) || !isset($image)){
            $required_parameters = [];
            if(!isset($id)) array_push($required_parameters, 'id');
            if(!isset($catergory_id)) array_push($required_parameters, 'categoryId');
            if(!isset($image)) array_push($required_parameters, 'image');
            $this->response(
                array(
                    'status' => FALSE,
                    'message' => $this::REQUIRED_PARAMETER_MESSAGE.implode(', ', $required_parameters)
                ),REST_Controller::HTTP_BAD_REQUEST
            );
            return;
        }

        if($this->category_model->is_not_exists($catergory_id)){
            $this->response(
                array(
                    'status' => FALSE,
                    'message' => $this::INVALID_ID_MESSAGE." categoryId does not exist"
                ),REST_Controller::HTTP_BAD_REQUEST
            );
            return;
        };
        
        if($this->image_model->update_image($id, $catergory_id, $image)){
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

        if($this->image_model->is_not_exists($id)){
            $this->response(
                array(
                    'status' => FALSE,
                    'message' => $this::INVALID_ID_MESSAGE." id does not exist"
                ),REST_Controller::HTTP_BAD_REQUEST
            );
            return;
        }

        if($this->image_model->delete_image($id)){
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