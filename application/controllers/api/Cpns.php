<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

use Restserver\Libraries\REST_Controller;

class Cpns extends REST_Controller {
    
    public function __construct(){
        parent::__construct();

        // $this->load->model("tasks_model");
        // $this->load->model("user_task_groups_model");
        // $this->load->model("users_model");

        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        $method = $_SERVER['REQUEST_METHOD'];
        if($method == "OPTIONS") {
            die();
        }
    }

    public function index_get(){
        echo 'API CPNS Buana Paksa';
    }
}