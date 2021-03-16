<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class E404 extends MX_Controller {

    public function __construct()
	{
        parent::__construct();
    }
    

	public function index()
	{
        $data['sections_view'] = "e404_view";
        $this->load->view('layout_home_front_view',$data);
    }
}