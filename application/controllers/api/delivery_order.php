<?php

defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

use Restserver\Libraries\REST_Controller;

class Delivery_order extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model("delivery_order_model");
        $this->load->model("product_delivery_order_model");
    }

    public function index_post()
    {
        $name = $this->post('name');
        $receiver_name = $this->post('receiverName');
        $reference_number = $this->post('referenceNumber');
        $date = $this->post('date');
        $address = $this->post('address');
        $items = $this->post('items');
        $description = $this->post('description');
        $status = $this->post('status');
        $type = $this->post('type');

        if (!isset($name) || !isset($receiver_name) || !isset($reference_number) || !isset($date) || !isset($address) || !isset($items) || !isset($description) || !isset($status)) {
            $required_parameters = [];
            if (!isset($name)) array_push($required_parameters, 'name');
            if (!isset($receiver_name)) array_push($required_parameters, 'receiverName');
            if (!isset($reference_number)) array_push($required_parameters, 'referenceNumber');
            if (!isset($date)) array_push($required_parameters, 'date');
            if (!isset($address)) array_push($required_parameters, 'address');
            if (!isset($description)) array_push($required_parameters, 'description');
            if (!isset($items)) array_push($required_parameters, 'items');
            if (!isset($status)) array_push($required_parameters, 'status');
            if (!isset($type)) array_push($required_parameters, 'type');
            $this->response(
                array(
                    'status' => FALSE,
                    'message' => $this::REQUIRED_PARAMETER_MESSAGE . implode(', ', $required_parameters)
                ),
                REST_Controller::HTTP_BAD_REQUEST
            );
            return;
        }

        if ($insert_id = $this->delivery_order_model->insert_delivery_order($name, $receiver_name,  $date, $reference_number, $address, $description, $status, $type)) {
            if ($this->product_delivery_order_model->insert_product_delivery_order($insert_id, $items)) {
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
        $id = $this->get('id');

        if (isset($id)){
            $result = $this->delivery_order_model->get_delivery_order_where($id);
            $products = $this->product_delivery_order_model->get_product_delivery_order_where($id);

            $result =  array_merge($result[0], array('products' => $products[0]));


            $this->response($result,REST_Controller::HTTP_OK);
        }    
        else{
            $result = [];
            $index = 0;
            $datas = $this->delivery_order_model->get_all_delivery_order();
            foreach($datas as $data){
                $temp = array_merge($datas[$index], array('items' => $this->product_delivery_order_model->get_product_delivery_order_where($data['id'])));
                array_push($result, $temp);
                $index++;
            }
            $this->response($result, REST_Controller::HTTP_OK);
        }
    }

    public function index_put()
    {
        $id = $this->put('id');
        $name = $this->put('name');
        $receiver_name = $this->put('receiverName');
        $reference_number = $this->put('referenceNumber');
        $date = $this->put('date');
        $address = $this->put('address');
        $items = $this->put('items');
        $description = $this->put('description');
        $status = $this->put('status');
        $type = $this->put('type');

        if (!isset($id) || !isset($name) || !isset($receiver_name) || !isset($reference_number) || !isset($date) || !isset($address) || !isset($items) || !isset($description) || !isset($status) || !isset($type)) {
            $required_parameters = [];
            if (!isset($id)) array_push($required_parameters, 'id');
            if (!isset($name)) array_push($required_parameters, 'name');
            if (!isset($receiver_name)) array_push($required_parameters, 'receiverName');
            if (!isset($reference_number)) array_push($required_parameters, 'referenceNumber');
            if (!isset($date)) array_push($required_parameters, 'date');
            if (!isset($address)) array_push($required_parameters, 'address');
            if (!isset($description)) array_push($required_parameters, 'description');
            if (!isset($items)) array_push($required_parameters, 'items');
            if (!isset($status)) array_push($required_parameters, 'status');
            if (!isset($type)) array_push($required_parameters, 'type');
            $this->response(
                array(
                    'status' => FALSE,
                    'message' => $this::REQUIRED_PARAMETER_MESSAGE . implode(', ', $required_parameters)
                ),
                REST_Controller::HTTP_BAD_REQUEST
            );
            return;
        }

        if ($this->delivery_order_model->is_not_exists($id)) {
            $this->response(
                array(
                    'status' => FALSE,
                    'message' => $this::INVALID_ID_MESSAGE . " id does not exist"
                ),
                REST_Controller::HTTP_BAD_REQUEST
            );
            return;
        }

        if ($this->delivery_order_model->update_delivery_order($id, $name, $receiver_name, $reference_number, $date, $address, $description, $status, $type)) {
            if ($this->product_delivery_order_model->update_product_delivery_order($id, $items)) {

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
    }

    public function index_delete()
    {
        $id = $this->input->get('id');

        if (!isset($id)) {
            $this->response(
                array(
                    'status' => FALSE,
                    'message' => $this::REQUIRED_PARAMETER_MESSAGE . " id"
                ),
                REST_Controller::HTTP_BAD_REQUEST
            );
            return;
        }

        if ($this->delivery_order_model->is_not_exists($id)) {
            $this->response(
                array(
                    'status' => FALSE,
                    'message' => $this::INVALID_ID_MESSAGE . " id does not exist"
                ),
                REST_Controller::HTTP_BAD_REQUEST
            );
            return;
        }

        if ($this->delivery_order_model->delete_delivery_order($id)) {
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
