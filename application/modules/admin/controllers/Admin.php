<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends MX_Controller {

    public function __construct()
	{
        parent::__construct();
        $this->load->helper('cookie');
        $session_id = get_cookie('MoodleSession');
        if(!(@$this->session->userdata('user_data') && $this->session->userdata('user_data')->session_id === $session_id)){
            redirect(base_url().'auth/login');
        }
        if(!$this->session->userdata('user_data')->is_admin){
            redirect('denied');
        }
    }

	public function index()
	{
        $data['title'] = 'Panel administrador';
        $data['sections_view'] = "admin_view";
        $this->load->view('layout_front_view',$data);
    }
}