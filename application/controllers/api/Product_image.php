<?php

defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

use Restserver\Libraries\REST_Controller;

class Product_image extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('product_model');
        $this->load->model('image_model');
        $this->load->model('product_image_model');
    }

    // POST - input data baru
    public function index_post()
    {
        $product_id = $this->post('productId');
        $image_id = $this->post('imageId');

        if (!isset($image_id) || !isset($product_id)) {
            $required_parameters = [];
            if (!isset($image_id)) array_push($required_parameters, 'imageId');
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

        foreach ($image_id as $item) {
            if ($this->image_model->is_not_exists($item)) {
                $this->response(
                    array(
                        'status' => FALSE,
                        'message' => $this::INVALID_ID_MESSAGE . " imageId does not exist"
                    ),
                    REST_Controller::HTTP_BAD_REQUEST
                );
                return;
            }
        }

        if ($this->product_image_model->insert_product_image($product_id, $image_id)) {
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

        if (isset($product_id)) $this->response($this->product_image_model->get_product_image_where($product_id), REST_Controller::HTTP_OK);
        else $this->response($this->product_image_model->get_all_product_image(), REST_Controller::HTTP_OK);
    }

    // PUT - update data sesuai dengan id grup user task, kemudian merubah semua task yang dimiliki oleh user pada grup tersebut
    public function index_put()
    {
        $product_id = $this->put('productId');
        $image_id = $this->put('imageId');

        if (!isset($product_id) || !isset($image_id)) {
            $required_parameters = [];
            if (!isset($product_id)) array_push($required_parameters, 'productId');
            if (!isset($image_id)) array_push($required_parameters, 'imageId');

            $this->response(
                array(
                    'status' => FALSE,
                    'message' => $this::REQUIRED_PARAMETER_MESSAGE . implode(', ', $required_parameters)
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

        foreach ($image_id as $item) {
            if ($this->image_model->is_not_exists($item)) {
                $this->response(
                    array(
                        'status' => FALSE,
                        'message' => $this::INVALID_ID_MESSAGE . " imageId does not exist"
                    ),
                    REST_Controller::HTTP_INTERNAL_SERVER_ERROR
                );
                return;
            }
        }

        if ($this->product_image_model->update_product_image($product_id, $image_id)) {
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
            if ($this->product_image_model->is_not_exists($id)) {
                $this->response(
                    array(
                        'status' => FALSE,
                        'message' => $this::INVALID_ID_MESSAGE . " id does not exist"
                    ),
                    REST_Controller::HTTP_BAD_REQUEST
                );
                return;
            }

            if ($this->product_image_model->delete_product_image($id)) {
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

            if ($this->product_image_model->delete_product_image_by_product_id($product_id)) {
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
