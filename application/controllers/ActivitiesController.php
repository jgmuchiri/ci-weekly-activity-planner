<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ActivitiesController extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('My_activity', 'activity');
    }

    public function index()
    {
        $activities = $this->activity->activities();
        $days = $this->activity->days();

        $this->load->view('welcome_message', compact('activities', 'days'));
    }

    public function create()
    {
        $this->form_validation->set_rules('name', 'Activity name', 'required|trim|xss_clean');

        $error = "success";
        if ($this->form_validation->run() == true) {
            if ($this->activity->insert()) {
                $msg = 'Activity added';
            } else {
                $msg = 'Error adding activity';
                $status = "error";
            }

        } else {
            $msg = validation_errors();
            $status = "error";
        }

        echo json_encode(['msg' => $msg, 'status' => $status]);
    }

    public function update()
    {
        $this->form_validation->set_rules('name', 'Activity name', 'required|trim|xss_clean');
        $this->form_validation->set_rules('activity_date', 'Date', 'required|trim|xss_clean');
        $this->form_validation->set_rules('activity_start', 'Time', 'required|trim|xss_clean');
        if ($this->form_validation->run() == true) {
            if ($this->activity->update(uri_segment(3))) {
                $msg = 'Activity updated';
                $status = "success";
            } else {
                $msg = 'Error updating activity';
                $status = "error";
            }

        } else {
            $msg = validation_errors();
            $status = "error";
        }

        echo json_encode(['msg' => $msg, 'status' => $status]);
    }

    public function delete()
    {
        $id = uri_segment(3);

        $this->db->where('id', $id)->delete('activity_plan');
        $msg = "Activity deleted";
        $status = "success";

        echo json_encode(['msg' => $msg, 'status' => $status]);
    }

    public function copy()
    {
        $this->activity->copy();

        $msg = "Activity plan copied to next week";
        $status = "success";

        echo json_encode(['msg' => $msg, 'status' => $status]);
    }

    public function clear()
    { 
        $this->activity->clear();

        $msg = "Activity plan has been cleared";
        $status = "success";

        echo json_encode(['msg' => $msg, 'status' => $status]);
    }
}
