<?php
    class Lead extends CI_Model{
        public function getLeads($date_array){
            $this->db->select("CONCAT(client.first_name, ' ', client.last_name) AS 'client_name', COUNT(lead.leads_id) AS 'number_of_leads'");
            $this->db->from("clients AS client");
            $this->db->join("sites AS site", "client.client_id = site.client_id");
            $this->db->join("leads AS lead", "site.site_id = lead.site_id");
            $this->db->where("lead.registered_datetime >=", $date_array["from_date"]);
            $this->db->where("lead.registered_datetime <=", $date_array["to_date"]);
            $this->db->group_by("client.client_id");
            $query = $this->db->get();

            return $query->result_array();
        }
    }
?>