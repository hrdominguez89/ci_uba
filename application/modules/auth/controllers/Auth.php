<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends MX_Controller {

    public function __construct()
	{
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('Auth_model');

        $this->load->library(array('my_form_validation'));
        $this->form_validation->run($this);
        
        $this->config->load('custom_config');
        $this->data_captcha_google = $this->config->item('data_captcha_google');
    }

	public function index(){
        if($this->session->has_userdata('user_data')){
            redirect('home');
        }else{
            redirect('auth/login');
        }
    }
    public function login(){
        $this->load->helper('cookie');
        $session_id = get_cookie('MoodleSession');
        if($session_id){//si existe cookie de sesion moodle me fijo si existe session de CI
            if(@$this->session->userdata('user_data') && $this->session->userdata('user_data')->session_id === $session_id){
                redirect('home');
            }
            $moodle_session = $this->Auth_model->getUserByCookie($session_id);
            if(isset($moodle_session->userid) && $moodle_session->userid){
                $user_data = $this->Auth_model->getUserDataById($moodle_session->userid);
                $user_data->session_id = $session_id;
                $user_data->is_admin = false;
                $admins = $this->Auth_model->getAdmins();
                $admins = explode(",", $admins->value);
                foreach ($admins as $admin){
                    if($admin ==  $user_data->id){
                        $user_data->is_admin = true;
                        break;
                    }
                }
                $this->session->set_userdata('user_data',$user_data);
                redirect('home');
            }
        }
        $data['error'] = $this->input->get('errorcode');
        switch ($data['error']){
            case "0":
                redirect(base_url().'auth/login');
            case "1":
                $data['message'] = "No se pudo loguear porque el usuario no existe.";
                break;
            case "2":
                $data['message'] = "No se pudo loguear porque el usuario se encuentra suspendido.";
                break;
            case "3":
                $data['message'] = "Usuario y/o contraseña invalido.";
                break;
            case "4":
                $data['message'] = "El usuario se encuentra blockeado, comuniquese con el administrador.";
                break;
            case "5":
                $data['message'] = "El usuario no se encuentra autorizado, comuniquese con el administrador.";
                break;
        }
        $this->session->sess_destroy();
        $data['recaptcha'] = true;
        $data['files_js'] = array('login.js');
        $data['sections_view'] = "login_view";
        $this->load->view('layout_home_front_view',$data);
    }
    public function logout(){
        $this->session->sess_destroy();
        $this->load->helper(
            array('cookie','file')
        );
        $path = $this->config->item('site_config')->route_moodle_data_sessions.'/sess_'.get_cookie('MoodleSession');
        @unlink($path);
        $this->Auth_model->deleteCookie(get_cookie('MoodleSession'));
        delete_cookie('MoodleSession');
        redirect(base_url().'auth/login');
    }

    public function valid_captcha(){
        // Guardo la respuesta que me da el captcha del front
        $recaptcha_response = $this->input->post('recaptcha_response');
        $recaptcha = file_get_contents($this->data_captcha_google['url'] . '?secret=' . $this->data_captcha_google['secretKey'] . '&response=' . $recaptcha_response);
        echo $recaptcha;
    }
}