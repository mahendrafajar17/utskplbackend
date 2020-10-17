<?php

defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

use Restserver\Libraries\REST_Controller;

class Product_variant extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model("product_variant_model");
        $this->load->model("product_model");
    }

    public function index_post()
    {
        $product_id = $this->post('productId');
        $variant = $this->post('variant');

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

        if (!isset($variant)) {
            $this->response(
                array(
                    'status' => FALSE,
                    'message' => $this::REQUIRED_PARAMETER_MESSAGE . "variant"
                ),
                REST_Controller::HTTP_BAD_REQUEST
            );
        }

        foreach ($variant as $item) {
            if ($this->product_variant_model->insert_product_variant($product_id, $item)) {
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
    }

    public function index_get()
    {
        $product_id = $this->get('productId');

        if (isset($product_id)) {
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
            $this->response($this->product_variant_model->get_product_variant_where($product_id), REST_Controller::HTTP_OK);
        } else $this->response($this->product_variant_model->get_all_product_variant(), REST_Controller::HTTP_OK);
    }

    public function index_put()
    {
        // post product id nya juga
        // mekanisme update nya gimana? batch atau 1/1?

        $id = $this->put('id');
        $product_id = $this->put('productId');
        $size = $this->put('size');

        if ($this->product_model->is_not_exists($product_id)) {
            $this->response(
                array(
                    'status' => FALSE,
                    'message' => $this::INVALID_ID_MESSAGE . " productId does not exist"
                ),REST_Controller::HTTP_BAD_REQUEST
            );
            return;
        }


        if ($this->product_variant_model->is_not_exists($id)) {
            $this->response(
                array(
                    'status' => FALSE,
                    'message' => $this::INVALID_ID_MESSAGE . " ID does not exist"

                ),
                REST_Controller::HTTP_BAD_REQUEST
            );
            return;
        }


        // if(isset($product_id)){
        //     if ($this->product_model->is_not_exists($product_id)) {
        //         $this->response(
        //             array(
        //                 'status' => FALSE,
        //                 'message' => $this::INVALID_ID_MESSAGE . " productId does not exist"
        //             ),
        //             REST_Controller::HTTP_BAD_REQUEST
        //         );
        //         return;
        //     }
        // }


        if ($this->product_variant_model->update_product_variant($id, $product_id, $size)) {
            $this->response(
                array(
                    'status' => TRUE,
                    'message' => $this::UPDATE_SUCCESS_MESSSAGE
                ),
                REST_Controller::HTTP_OK
            );
            return;
        } else {
            $this->response(
                array(
                    'status' => FALSE,
                    'message' => $this::UPDATE_FAILED_MESSAGE
                ),
                REST_Controller::HTTP_INTERNAL_SERVER_ERROR
            );
            return;
        }
    }

    public function index_delete()
    {
        $id = $this->input->get('id');
        if ($this->product_variant_model->is_not_exists($id)) {
            $this->response(
                array(
                    'status' => FALSE,
                    'message' => $this::INVALID_ID_MESSAGE . " ID does not exist"

                ),
                REST_Controller::HTTP_BAD_REQUEST
            );
            return;
        }

        if ($this->product_variant_model->delete_product_variant($id)) {
            $this->response(
                array(
                    'status' => TRUE,
                    'message' => $this::DELETE_SUCCESS_MESSSAGE
                ),
                REST_Controller::HTTP_OK
            );
            return;
        }
    }
}
