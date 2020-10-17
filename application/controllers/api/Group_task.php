<?php

defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

use Restserver\Libraries\REST_Controller;

class Group_task extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('group_task_model');
        $this->load->model('user_task_group_model');
        $this->load->model('task_model');
        $this->load->model('user_task_model');
    }


    // POST - input data baru
    public function index_post()
    {
        $user_task_group_id = $this->post('userTaskGroupId');
        $task_id = $this->post('taskId');

        if (!isset($user_task_group_id) || !isset($task_id)) {
            $required_parameters = [];
            if (!isset($user_task_group_id)) array_push($required_parameters, 'userTaskGroupId');
            if (!isset($task_id)) array_push($required_parameters, 'taskId');
            $this->response(
                array(
                    'status' => FALSE,
                    'message' => $this::REQUIRED_PARAMETER_MESSAGE . implode(', ', $required_parameters)
                ),
                REST_Controller::HTTP_BAD_REQUEST
            );
            return;
        }

        if ($this->user_task_group_model->is_not_exists($user_task_group_id)) {
            $this->response(
                array(
                    'status' => FALSE,
                    'message' => $this::INVALID_ID_MESSAGE
                ),
                REST_Controller::HTTP_BAD_REQUEST
            );
            return;
        }

        foreach ($task_id as $item) {
            if ($this->task_model->is_not_exists($item)) {
                $this->response(
                    array(
                        'status' => FALSE,
                        'message' => $this::INVALID_ID_MESSAGE . " taskId"
                    ),
                    REST_Controller::HTTP_BAD_REQUEST
                );
                return;
            }
        }

        if ($this->group_task_model->insert_group_task($user_task_group_id, $task_id)) {
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

    // GET - mengambil data
    public function index_get()
    {
        $user_task_group_id = $this->get('userTaskGroupId');

        if (isset($user_task_group_id)) $this->response($this->group_task_model->get_group_task_where($user_task_group_id), REST_Controller::HTTP_OK);
        else $this->response($this->group_task_model->get_all_group_task(), REST_Controller::HTTP_OK);
    }

    // PUT - update data sesuai dengan id grup user task, kemudian merubah semua task yang dimiliki oleh user pada grup tersebut
    public function index_put()
    {
        $id = $this->put('id');
        $user_task_group_id = $this->put('userTaskGroupId');
        $task_id = $this->put('taskId');

        if (!isset($user_task_group_id) || !isset($task_id)) {
            $required_parameters = [];
            if (!isset($user_task_group_id)) array_push($required_parameters, 'userTaskGroupId');
            if (!isset($task_id)) array_push($required_parameters, 'taskId');

            $this->response(
                array(
                    'status' => FALSE,
                    'message' => $this::REQUIRED_PARAMETER_MESSAGE . implode(', ', $required_parameters)
                ),
                REST_Controller::HTTP_BAD_REQUEST
            );
            return;
        }

        if ($this->user_task_group_model->is_not_exists($user_task_group_id)) {
            $this->response(
                array(
                    'status' => FALSE,
                    'message' => $this::INVALID_ID_MESSAGE
                ),
                REST_Controller::HTTP_BAD_REQUEST
            );
            return;
        }

        foreach ($task_id as $item) {
            if ($this->task_model->is_not_exists($item)) {
                $this->response(
                    array(
                        'status' => FALSE,
                        'message' => $this::INVALID_ID_MESSAGE . " taskId does not exist"
                    ),
                    REST_Controller::HTTP_INTERNAL_SERVER_ERROR
                );
                return;
            }
        }
        $new_task = $this->group_task_model->update_group_task($user_task_group_id, $task_id);
        
        if ($new_task) {
            $result = $this->db->get_where('user', "user_task_group_id ='{$user_task_group_id}'");
            $users = $result->result_array();
            $count = $result->num_rows();
          
            // $users = $this->db->query("SELECT * FROM user WHERE user_task_group_id = 1")->result_array();
            if ($count) {
                foreach ($users as $user) {
                    $this->user_task_model->update_user_task($user['id'], $new_task);
                    $this->response(
                        array(
                            'status' => TRUE,
                            'message' => $this::UPDATE_SUCCESS_MESSSAGE,
                        ),
                        REST_Controller::HTTP_OK

                    );
                }
            } else {
                $this->response(    
                    array(
                        'status' => TRUE,
                        'message' => $this::UPDATE_SUCCESS_MESSSAGE . " Details: no user task row affected" 

                    ),
                    REST_Controller::HTTP_OK

                );
            }
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
        $user_task_group_id = $this->input->get('userTaskGroupId');

        if (!isset($user_task_group_id)) {
            $this->response(
                array(
                    'status' => FALSE,
                    'message' => $this::REQUIRED_PARAMETER_MESSAGE . "userTaskGroupId"
                ),
                REST_Controller::HTTP_BAD_REQUEST
            );
            return;
        }

        if ($this->group_task_model->is_not_exists($user_task_group_id)) {
            $this->response(
                array(
                    'status' => FALSE,
                    'message' => $this::INVALID_ID_MESSAGE
                ),
                REST_Controller::HTTP_BAD_REQUEST
            );
            return;
        }

        if ($this->group_task_model->delete_group_task($user_task_group_id)) {
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
