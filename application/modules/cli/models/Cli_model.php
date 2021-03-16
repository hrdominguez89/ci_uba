<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cli_model extends CI_Model {
    
    public function __construct()
	{
        parent::__construct();
        $this->load->database();
        $this->config->load('custom_config');
        $this->site_config = $this->config->item('site_config');

    }
    public function setCookieDomain($cookie_domain){
        $this->db = $this->db = $this->load->database('moodle_db', TRUE);

        $this->db->trans_start();

        $this->db->where('name','sessioncookiedomain');
        $this->db->update('mdl_config',$cookie_domain);

        $this->db->trans_complete();

        return $this->db->trans_status();
    }
}