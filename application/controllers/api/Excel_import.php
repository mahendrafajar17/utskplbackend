<?php

defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

use Restserver\Libraries\REST_Controller;

class Excel_import extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('product_model');
        $this->load->model('category_model');
        $this->load->model('unit_model');
        $this->load->model('product_tag_model');
        $this->load->model('product_image_model');
        $this->load->model('image_model');
        $this->load->model('tag_model');
            $this->load->library('excel');
            $this->load->library('upload');
            $this->load->helper(array('form', 'url'));
    }

    function index()
    {
        $data = array(1);
        $this->load->view('import', $data);
    }

    function fetch()
    {
        $data = $this->excel_import_model->select();
        $output = $data->num_rows();
        $res = array();

        foreach ($data->result() as $row) {
            array_push($res, $row->product_id);
            array_push($res, $row->product_name);
            array_push($res, $row->specification);
            array_push($res, $row->category);
            array_push($res, $row->stock);
            array_push($res, $row->unit);
            array_push($res, $row->open_price);
        }

        return($data);
    }

    function index_get(){
        // $data['product'] = $this->product_model->get_all_product();
        $data['product'] = "BOI";
        $this->response($data);
    }



    function index_post ()
    {   $data['product'] = "BOI";
        $data['files'] = $_FILES;
        $data['filename'] = $_FILES["file"]["name"];
        $this->response($data);
        // print_r($_FILES);
        // $this->response(array($this->post(), $_FILES));
        // return;
        
        if (isset($_FILES["file"]["name"])) {
            
            
            $path = ($_FILES["file"]["tmp_name"]);
            $object = PHPExcel_IOFactory::load($path);
            $datas = [];
            foreach ($object->getWorksheetIterator() as $worksheet) {
                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();
                for ($row = 2; $row < $highestRow; $row++) {
                    $product_code = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                    $product_name = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                    $specification = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
                    $stock = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
                    $unit = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
                    $open_price = $worksheet->getCellByColumnAndRow(6, $row)->getValue();
                    $category = $worksheet->getCellByColumnAndRow(7, $row)->getValue();
                    $retail_id = $worksheet->getCellByColumnAndRow(8,$row)->getValue();
                    

                    if(!isset($product_name)) $product_name = "undefined";
                    if(!isset($product_code)) $product_code = "undefined";
                    if(!isset($category)) $category = "undefined";
                    if(!isset($open_price)) $open_price = 0;
                    if(!isset($specification)) $specification = "undefined";
                    if(!isset($unit)) $unit = "undefined";
                    if(!isset($stock)) $stock = 0;
                    if(!isset($retail_id)) $retail_id = "undefined";



                    if(!$this->category_model->is_name_exists($category)){
                        $category_id = $this->category_model->insert_category($category);
                    }
                    else{
                        $category_id = $this->category_model->get_by_name($category);
                    }

                    if(!$this->unit_model->is_abbreviation_exists($unit)){
                        $unit_id = $this->unit_model->insert_unit("undefined", $unit, "undefined");
                    }else{
                        $unit_id = $this->unit_model->get_by_abbreviation($unit);
                    }


                    $data = array(  
                        'product_code' => $product_code,
                        'name' => $product_name,
                        'open_price' => $open_price,
                        'bottom_price' => 0,
                        'specification' => $specification,
                        'stock' => $stock,
                        'category_id' => $category_id,
                        'unit_id' => $unit_id,
                        'retail_id' => $retail_id
                    );
                    array_push($datas, $data);
                }
                // $this->response($datas);
                // return;
            }
            if ($this->product_model->insert_product_import($datas)) {
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
            return;
        }

        $this->response(
            array(
                'status' => FALSE,
                'message' => $this::REQUIRED_PARAMETER_MESSAGE . " File path does not set yet"
            ),
            REST_Controller::HTTP_BAD_REQUEST
        );
        return;
    }
}
