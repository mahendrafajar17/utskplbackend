<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

use Restserver\Libraries\REST_Controller;

class Stock_opname extends REST_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model('stock_opname_model');
        $this->load->model('product_stock_opname_model');
    }

    public function index_post(){

        $opname_number = $this->post('opnameNumber');
        $description = $this->post('description');
        $date_start = $this->post('dateStart');
        $date_finish = $this->post('dateFinish');
        $status = $this->post('status');
        $description = $this->post('description');
        $products = $this->post('products');

        if(!isset($description)) $description = 'undefined';

        if(!isset($opname_number) || !isset($description) || !isset($date_start) || !isset($date_finish) || !isset($status) || !isset($products)){
            $required_parameters = [];
            if(!isset($opname_number)) array_push($required_parameters, 'opnameNumber');
            if(!isset($description)) array_push($required_parameters, 'description');
            if(!isset($date_start)) array_push($required_parameters, 'dateStart');
            if(!isset($date_finish)) array_push($required_parameters, 'dateFinish');
            if(!isset($status)) array_push($required_parameters, 'status');
            if(!isset($products)) array_push($required_parameters, 'products: array of objects(productId, realStock, status, description(optional) )');
            $this->response(
                array(
                    'status' => FALSE,
                    'message' => $this::REQUIRED_PARAMETER_MESSAGE.implode(', ', $required_parameters)
                ),REST_Controller::HTTP_BAD_REQUEST
            );
            return;

        };

        if($opname_id = $this->stock_opname_model->insert_stock_opname($opname_number, $date_start, $date_finish, $status, $description)){
            foreach($products as $product){
                if(!isset($product['description'])) $product['description'] = 'undefined';
                $this->product_stock_opname_model->insert_product_stock_opname($opname_id, $product['productId'], null, $product['realStock'], null, null, $product['status'], $product['description'], $product['checked']);
            }
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

        if(isset($id)) {
            $result = $this->stock_opname_model->get_stock_opname_where($id);
            $products = $this->product_stock_opname_model->get_product_stock_opname_by_opname_id($id);
            $temp = array();
            foreach($products as $product){
                array_push($temp, 
                    array(
                        'id' => $product['id'],
                        'productId' => $product['productId'],
                        'productName' => $product['productName'],
                        'realStock' => $product['realStock'],
                        'opnameStock' => $product['opnameStock'],
                        'status' => $product['status'],
                        'checked' => $product['checked'],
                    ));
            }
            $result = array_merge($result[0], array('products' => $temp));
            
            $this->response($result, REST_Controller::HTTP_OK);
            return;
        }
        else{
            $result = $this->stock_opname_model->get_all_stock_opname();
            $response = array();
            foreach($result as $res){
                $products = $this->product_stock_opname_model->get_product_stock_opname_by_opname_id($res['id']);
                $temp = array();
                foreach($products as $product){
                    array_push($temp, 
                        array(
                            'id' => $product['id'],
                            'productId' => $product['productId'],
                            'productName' => $product['productName'],
                            'realStock' => $product['realStock'],
                            'opnameStock' => $product['opnameStock'],
                            'status' => $product['status'],
                            'checked' => $product['checked'],
                        ));
                }
                $row = array_merge($res, array('products' => $temp));
                array_push($response, $row);
                
            }
            $this->response($response);

            return;
        }
    }

    public function index_put(){
        $id = $this->put('id');
        $opname_number = $this->put('opnameNumber');
        $description = $this->put('description');
        $date_start = $this->put('dateStart');
        $date_finish = $this->put('dateFinish');
        $status = $this->put('status');
        $description = $this->put('description');

        if(!isset($id) || !isset($opname_number) || !isset($description) || !isset($date_start) || !isset($date_finish) || !isset($status)){
            $required_parameters = [];
            if(!isset($id)) array_push($required_parameters, 'id');
            if(!isset($opname_number)) array_push($required_parameters, 'opnameNumber');
            if(!isset($description)) array_push($required_parameters, 'description');
            if(!isset($date_start)) array_push($required_parameters, 'dateStart');
            if(!isset($date_finish)) array_push($required_parameters, 'dateFinish');
            if(!isset($status)) array_push($required_parameters, 'status');

            $this->response(
                array(
                    'status' => FALSE,
                    'message' => $this::REQUIRED_PARAMETER_MESSAGE.implode(', ', $required_parameters)
                ),REST_Controller::HTTP_BAD_REQUEST
            );
            return;

        };

        if($this->stock_opname_model->is_not_exists($id)){
            $this->response(
                array(
                    'status' => FALSE,
                    'message' => $this::INVALID_ID_MESSAGE
                ),REST_Controller::HTTP_BAD_REQUEST
            );
            return;
        }

        if($this->stock_opname_model->update_stock_opname($id, $opname_number, $date_start, $date_finish, $status, $description)){
            $this->response(
                array(
                    'status' => TRUE,
                    'message' => $this::UPDATE_SUCCESS_MESSSAGE
                ), REST_Controller::HTTP_OK
            );
            return;
        }else{
            $this->response(
                array(
                    'status' => FALSE,
                    'message' => $this::UPDATE_FAILED_MESSAGE
                ), REST_Controller::HTTP_INTERNAL_SERVER_ERROR
            );
            return;
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

        if($this->stock_opname_model->is_not_exists($id)){
            $this->response(
                array(
                    'status' => FALSE,
                    'message' => $this::INVALID_ID_MESSAGE." id does not exist"
                ),REST_Controller::HTTP_BAD_REQUEST
            );
            return;
        }

        if($this->stock_opname_model->delete_stock_opname($id)){
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