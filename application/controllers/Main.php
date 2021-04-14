<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller{
    public function index(){
        $this->load->view("index");
    }

	public function setDateRange(){
		$this->form_validation->set_rules("from_date", "Date", "required");
		$this->form_validation->set_rules("to_date", "Date", "required");

		if($this->form_validation->run() == FALSE){
			$this->session->set_userdata("date_error", "Date can't be empty");
			$this->session->set_userdata("leads", NULL);
			redirect(base_url());
		}
		else if($this->input->post("to_date") < $this->input->post("from_date")){
			$this->session->set_userdata("date_error", "To Date can't be earlier than From Date!");
			$this->session->set_userdata("leads", NULL);
			redirect(base_url());
		}
		else{
			$this->session->set_userdata("from_date", date("M m, Y", strtotime($this->input->post("from_date"))));
			$this->session->set_userdata("to_date", date("M m, Y", strtotime($this->input->post("to_date"))));

			$date_array = array(
				"from_date" => $this->input->post("from_date"),
				"to_date" => $this->input->post("to_date"),
			);
			
			$this->load->model("Lead");
			$query = $this->Lead->getLeads($date_array);

			if($query){
				$this->session->set_userdata("leads", $query);
				redirect(base_url());
			}
			else{
				$this->session->set_userdata("leads", NULL);
				redirect(base_url());
			}
		}
	}
}