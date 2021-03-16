<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Platforms extends MX_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->helper('cookie');
        $session_id = get_cookie('MoodleSession');
        if(!(@$this->session->userdata('user_data') && $this->session->userdata('user_data')->session_id === $session_id)){
            redirect(base_url().'auth/login');
        }
        $this->load->model('Platforms_model');
    }

	public function index(){
        $data['title'] = 'Salas de videoconferencia';
        $data['platforms'] = $this->Platforms_model->getPlatforms();
        $data['sections_view'] = "platforms_view";
        $this->load->view('layout_front_view',$data);
    }

    public function list(){
        if(!$this->session->userdata('user_data')->is_admin){
            redirect('denied');
        }
        $data['title'] = 'Listado de plataformas';
        $data['platforms'] = $this->Platforms_model->getPlatforms();
        $data['sections_view'] = "platforms_abm_view";
        $this->load->view('layout_front_view',$data);
    }
    
    public function insert(){
        if(!$this->session->userdata('user_data')->is_admin){
            redirect('denied');
        }
        if($this->input->post()){
            $this->rules_platforms();
            if (!$this->form_validation->run() == FALSE){
                $platform['name'] = $this->input->post('platform_name');
                $platform['url'] = $this->input->post('url');
                $platform['who_created'] = $this->session->userdata('user_data')->id;
                $platform['date_created'] = date('Y-m-d H:i:s',time());
                $platform_id = $this->Platforms_model->insertPlatform($platform);
                if($platform_id){

                    $config['upload_path'] = './uploads/platforms';
                    $config['allowed_types'] = 'png';
                    $config['max_size'] = 1000;
                    $config['file_name'] = $platform_id;
                    $config['overwrite'] = TRUE;
                    $config['detect_mime'] = TRUE;
                    $config['file_ext_tolower'] = TRUE;

                    $this->load->library('upload', $config);

                    if (!$this->upload->do_upload('platform_logo')){
                        $error = $this->upload->display_errors();
                        $this->Platforms_model->deletePlatform($platform_id);
                        $data['error_message'] = $error;
                    }else{
                        $message = array(
                            'message' => 'Se registró la plataforma correctamente.',
                            'alert' => 'success'
                        );
                        $this->session->set_flashdata('message',$message);
                        redirect(base_url().'platforms/list');
                    }
                }else{
                    $data['error_message'] = 'No fue posible registrar la nueva plataforma. Por favor intente nuevamente.';
                }
            }
        }
        $data['accion'] = 'insert';
        $data['title'] = 'Nueva plataforma';
        $data['sections_view'] = "platforms_form_view";
        $this->load->view('layout_front_view',$data);
    }
    
    public function edit($platform_id = FALSE){
        if(!$this->session->userdata('user_data')->is_admin){
            redirect('denied');
        }

        if(!$platform_id){
            redirect(base_url().'platforms/list');
        }

        $data['platform'] = $this->Platforms_model->getPlatformById($platform_id);
        if(!$data['platform']){
            $message = array(
                'message' => 'El ID indicado no existe.',
                'alert' => 'danger'
            );
            $this->session->set_flashdata('message',$message);
            redirect(base_url().'platforms/list');
        }

        if($this->input->post()){
            $this->rules_platforms();
            if (!$this->form_validation->run() == FALSE){
                $platform['name'] = $this->input->post('platform_name');
                $platform['url'] = $this->input->post('url');
                $platform['who_modified'] = $this->session->userdata('user_data')->id;
                $platform['date_modified'] = date('Y-m-d H:i:s',time());
                if($this->Platforms_model->updatePlatform($platform,$platform_id)){
                    if (!empty($_FILES['platform_logo']['name'])) {
                        $config['upload_path'] = './uploads/platforms';
                        $config['allowed_types'] = 'png';
                        $config['max_size'] = 1000;
                        $config['file_name'] = $platform_id;
                        $config['overwrite'] = TRUE;
                        $config['detect_mime'] = TRUE;
                        $config['file_ext_tolower'] = TRUE;
    
                        $this->load->library('upload', $config);
    
                        if (!$this->upload->do_upload('platform_logo')){
                            $error = $this->upload->display_errors();
                            $this->Platforms_model->deletePlatform($platform_id);
                            $data['error_message'] = $error;
                        }else{
                            $message = array(
                                'message' => 'Se editó la plataforma correctamente.',
                                'alert' => 'success'
                            );
                            $this->session->set_flashdata('message',$message);
                            redirect(base_url().'platforms/list');
                        }
                    }else{
                        $message = array(
                            'message' => 'Se editó la plataforma correctamente.',
                            'alert' => 'success'
                        );
                        $this->session->set_flashdata('message',$message);
                        redirect(base_url().'platforms/list');
                    }
                }else{
                    $data['error_message'] = 'No fue posible editar la nueva plataforma. Por favor intente nuevamente.';
                }
            }
        }
        
        $data['accion'] = 'edit';
        $data['title'] = 'Editar plataforma';
        $data['sections_view'] = "platforms_form_view";
        $this->load->view('layout_front_view',$data);
    }

    public function delete(){
        if(!$this->session->userdata('user_data')->is_admin){
            redirect('denied');
        }
        if($this->input->post()){
            $platform_id = $this->input->post('platform_id');
            $platform = $this->Platforms_model->getPlatformById($platform_id);
            if($platform){
                $this->Platforms_model->deletePlatform($platform_id);
                $filename = './uploads/platforms/'.$platform_id.'.png';
                if (file_exists($filename))
                {
                    unlink($filename);
                }
                $message = array(
                    'message' => 'Se eliminó la plataforma correctamente.',
                    'alert' => 'success'
                );
            }else{
                $message = array(
                    'message' => 'El ID indicado no existe.',
                    'alert' => 'danger'
                );
            }
        }else{
            $message = array(
                'message' => 'Por favor indique la plataforma que desea eliminar.',
                'alert' => 'info'
            );
        }
        $this->session->set_flashdata('message',$message);
        redirect(base_url().'platforms/list');
    }

    private function rules_platforms(){
        if(!$this->session->userdata('user_data')->is_admin){
            redirect('denied');
        }
        $this->form_validation->set_error_delimiters('<div class="alert alert-danger my-2">', '</div>');
        $this->form_validation->set_rules(
            'platform_name',
            'Nombre de la plataforma',
            'trim|required',
            array(
                'required' => 'El campo {field} es obligatorio.'
            ));
        $this->form_validation->set_rules(
            'url', 
            'URL', 
            'trim|required|max_length[255]|valid_url',
            array(
                'required' => 'El campo {field} es obligatorio.',
                'max_length' => 'El campo {field}, debe tener un máximo de {param} caracteres.',
                'valid_url' => 'El campo {field}, debe contener una URL válida.'
            )
        );
    }
}