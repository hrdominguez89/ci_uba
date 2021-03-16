<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends MX_Controller {

    public function __construct()
	{
        parent::__construct();
        $this->load->helper('cookie');
        $session_id = get_cookie('MoodleSession');
        if(!(@$this->session->userdata('user_data') && $this->session->userdata('user_data')->session_id === $session_id)){
            redirect(base_url().'auth/login');
        }
    }
    

	public function index()
	{
        $data['sections_view'] = "home_view";
        $this->load->view('layout_home_front_view',$data);
    }
}