<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller{
    public function index(){
		if($this->session->userdata("leads") == NULL){
			$date_array = array(
				"from_date" => "1970-01-01",
				"to_date" => date("Y-m-d"),
			);
			
			$this->load->model("Lead");
			$query = $this->Lead->getLeads($date_array);
	
			$this->session->set_userdata("from_date", date("M d, Y", strtotime($date_array["from_date"])));
			$this->session->set_userdata("to_date", date("M d, Y", strtotime($date_array["to_date"])));
			$this->session->set_userdata("leads", $query);
		}
        $this->load->view("index");
    }

	public function setDateRange(){
		/* DATE VALIDATION */
		$this->form_validation->set_rules("from_date", "Date", "required");
		$this->form_validation->set_rules("to_date", "Date", "required");

		/* 
			If form is submitted but no date is entered 
		*/
		if($this->form_validation->run() == FALSE){
			$this->session->set_userdata("date_error", "Date can't be empty");
			$this->session->set_userdata("leads", NULL);
			redirect(base_url());
		}
		/* 
			If TO DATE is earlier than FROM DATE
		*/
		else if($this->input->post("to_date") < $this->input->post("from_date")){
			$this->session->set_userdata("date_error", "To Date can't be earlier than From Date!");
			$this->session->set_userdata("leads", NULL);
			redirect(base_url());
		}
		/* 
			If there are no errors
		*/
		else{
			/* 
				Convert entered dates in a readable format and store it in a session
			*/
			$this->session->set_userdata("from_date", date("M d, Y", strtotime($this->input->post("from_date"))));
			$this->session->set_userdata("to_date", date("M d, Y", strtotime($this->input->post("to_date"))));

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
				$this->session->set_userdata("leads", "None");
				redirect(base_url());
			}
		}
	}
}