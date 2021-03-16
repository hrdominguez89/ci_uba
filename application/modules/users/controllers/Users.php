<?php



defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends MX_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->helper('cookie');
        $session_id = get_cookie('MoodleSession');
        if(!(@$this->session->userdata('user_data') && $this->session->userdata('user_data')->session_id === $session_id)){
            redirect(base_url().'auth/login');
        }
        if(!$this->session->userdata('user_data')->is_admin){
            redirect('denied');
        }
        
		$this->config->load('custom_config');//cargo los datos de configuración
        $this->site_config = $this->config->item('site_config');
        
        $this->email_config = $this->config->item('email_config');//cargo los datos de configuracion de correo
        $this->no_reply = $this->config->item('no_reply');//cargo los datos de configuracion de correo
        $this->load->library('email'); //cargo libreria de correo

        $this->load->model('Users_model');
        $this->load->model('Faculties/Faculties_model');

        $this->load->library(array('my_form_validation'));
        $this->form_validation->run($this);
    }
    
	public function index(){
        if(!$this->session->userdata('user_data')->is_admin){
            redirect('denied');
        }
        redirect('users/list');
    }

    public function list(){
        if(!$this->session->userdata('user_data')->is_admin){
            redirect('denied');
        }
        $this->load->model('auth/Auth_model');
        $data['files_js'] = array('users.js');
        $data['title'] = 'Listado de usuarios';
        $data['users'] = $this->Users_model->getUsers();
        $data['admins'] = $this->Auth_model->getAdmins();
        $data['admins'] = explode(',', $data['admins']->value);
        $data['sections_view'] = "users_abm_view";
        $this->load->view('layout_front_view',$data);
    }
    
    public function insert(){
        if(!$this->session->userdata('user_data')->is_admin){
            redirect('denied');
        }
        if($this->input->post()){
            
            $this->rules_user_register();
            if(!$this->form_validation->run()==FALSE){
                
                $user['firstname'] = mb_convert_case($this->input->post('firstname'), MB_CASE_TITLE, "UTF-8");
                $user['lastname'] = mb_convert_case($this->input->post('lastname'), MB_CASE_TITLE, "UTF-8");
                $user['email'] = strtolower($this->input->post('email'));
                $user['username'] = strtolower($this->input->post('username'));
                $user['confirmed'] = 1;
                $user['mnethostid '] = 1;
                $user['country'] = 'AR';
                $user['lang'] = 'es_mx';
                $user['timezone'] = 'America/Argentina/Buenos_Aires';
                //GENERO PASSWORD Y HASHEO
				$password = $this->create_password();
                $user['password'] = password_hash($password,PASSWORD_BCRYPT);
                //FIN DE GENERACION DE PASSWORD

                $user['faculty_id'] = $this->input->post('faculty_id');
                $user_id_registered = $this->Users_model->insertUser($user);
                if($user_id_registered){
                    if($this->input->post('is_admin')){
                        $this->load->model('auth/Auth_model');
                        $admins = $this->Auth_model->getAdmins();
                        $data_admin['value'] = $admins->value.','.$user_id_registered;
                        if($this->Users_model->updateAdminSite($data_admin)){
                            $cli_file = $this->site_config->route_moodle.'/admin/cli/purge_caches.php';
                            if(file_exists($cli_file)){
                                exec('php '. $cli_file);
                            }
                        }
                    }
                    
                    $this->email->initialize($this->email_config);
					// $this->email->set_header('MIME-Version', '1.0; charset=utf-8');
					// $this->email->set_header('Content-type', 'text/html');

					$this->email->from($this->no_reply['from_email'], $this->no_reply['from_name']);
					$this->email->to($user['email']);
					//$this->email->cc('another@another-example.com');
					//$this->email->bcc('them@their-example.com');

					
					$html = '
                        <!DOCTYPE html>
                        <html lang="es">
                        <head>
                            <title>Alta de usuario</title>
                        </head>
                        <body style="width: 100%;">
                                    <table style="width: 100%;">
                                        <tbody style="width: 100%;">
                                            <tr style="width: 100%;">
                                                <td style="width: 100%;">
                                                    <table style="width: 500px;">
                                                        <tr>
                                                            <td style="text-align: center;">
                                                                <h1>Alta de usuario</h1>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <p>Estimado/a '.$user['name'].':</p>
                                                                <p>Se ha generado un usuario.</p>
                                                                <p><b><u>Usuario:</u> </b>'.$user['username'].'</p>
                                                                <p><b><u>Contraseña:</u></b>'.$password.'</p>
                                                                <p>Para acceder haga <a href="'.base_url().'admin">click aquí</a> e ingrese los datos mencionados arriba.</p>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr style="width: 100%;">
                                                <td style="width: 100%;">
                                                    <table style="width: 500px;background-color: #efefef;"">
                                                        <tr style="text-align: center;">
                                                            <td>
                                                                <img style="vertical-align: middle;" src="'.base_url().'assets/img/logo_uba_horizontal_negrita.png" alt="Logo UBA">
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </body>
                        </html>
					';

					$this->email->subject('Alta de usuario');
					$this->email->message($html);

					if($this->email->send()){
                        $message = array(
                            'message' => '<i class="fas fa-check-circle"></i> Se agregó el usuario correctamente.',
                            'alert' => 'success'
                        );
                    }else{
                        $message = array(
                            'message' => '<i class="fas fa-exclamation-triangle"></i> No fue posible enviar el correo al usuario, por favor comuníquese con el usuario <b>'.$user['surname'].', '.$user['name'].'</b> e indiquelé sus credenciales.<br>Usuario: <b>'.$user['username'].'</b><br>Contraseña: <b>'.$password.'</b>',
                            'alert' => 'warning'
                        );
                    }
                    $this->session->set_flashdata('message',$message);
                    redirect(base_url().'users/list');
                }else{
                    $data['error_message'] = 'No fue posible registrar el usuario, por favor intente en unos instantes, si el problema persiste contactese con el administrador.';
                }
            }
        }
        $data['accion'] = 'insert';
        $data['title'] = 'Nuevo usuario';
        $data['faculties'] = $this->Faculties_model->getFaculties();
        $data['sections_view'] = "users_form_view";
        $this->load->view('layout_front_view',$data);
    }
    
    public function edit($user_id = FALSE){
        if(!$this->session->userdata('user_data')->is_admin){
            redirect('denied');
        }
        if(!$user_id){
            redirect(base_url().'users/list');
        }
        $data['user'] = $this->Users_model->getUserById($user_id);
        $this->load->model('auth/Auth_model');
        $admins = $this->Auth_model->getAdmins();
        $admins = explode(',', $admins->value);
        $data['is_admin'] = false;
        for($i=0; $i < count($admins); $i++){
            if($admins[$i] == $data['user']->id){
                $data['is_admin'] = true;
                break;
            }
        }
        if($data['user']){
            if($this->input->post()){
                $this->rules_user_edit($data['user'],$this->input->post('username'),$this->input->post('email'),$this->input->post('password_multimedial'));
                if(!$this->form_validation->run()==FALSE){
                    $user['firstname'] = mb_convert_case($this->input->post('firstname'), MB_CASE_TITLE, "UTF-8");
                    $user['lastname'] = mb_convert_case($this->input->post('lastname'), MB_CASE_TITLE, "UTF-8");
                    $user['email'] = strtolower($this->input->post('email'));
                    $user['username'] = strtolower($this->input->post('username'));
                    $user['faculty_id'] = $this->input->post('faculty_id');
                    if($this->Users_model->updateUser($user,$user_id)){
                        if($data['is_admin'] && !$this->input->post('is_admin')){
                            $admins = array_diff($admins,array($data['user']->id));
                            $admins = implode(',',$admins);
                            $data_admin['value'] = $admins;
                            if($this->Users_model->updateAdminSite($data_admin)){
                                $cli_file = $this->site_config->route_moodle.'/admin/cli/purge_caches.php';
                                if(file_exists($cli_file)){
                                    exec('php '. $cli_file);
                                }
                            }
                        }
                        if (!$data['is_admin'] && $this->input->post('is_admin')){
                            $admins = implode(',',$admins);
                            $admins = $admins.','.$data['user']->id;
                            $data_admin['value'] = $admins;
                            if($this->Users_model->updateAdminSite($data_admin)){
                                $cli_file = $this->site_config->route_moodle.'/admin/cli/purge_caches.php';
                                if(file_exists($cli_file)){
                                    exec('php '. $cli_file);
                                }
                            }
                        }

                        if($user['username'] != $data['user']->username){
                            $this->email->initialize($this->email_config);
                            // $this->email->set_header('MIME-Version', '1.0; charset=utf-8');
                            // $this->email->set_header('Content-type', 'text/html');
    
                            $this->email->from($this->no_reply['from_email'], $this->no_reply['from_name']);
                            $this->email->to($user['email']);
                            //$this->email->cc('another@another-example.com');
                            //$this->email->bcc('them@their-example.com');
    
                            
                            $html = '
                                <!DOCTYPE html>
                                <html lang="es">
                                <head>
                                    <title>Se modificó su nombre de usuario</title>
                                </head>
                                <body style="width: 100%;">
                                            <table style="width: 100%;">
                                                <tbody style="width: 100%;">
                                                    <tr style="width: 100%;">
                                                        <td style="width: 100%;">
                                                            <table style="width: 500px;">
                                                                <tr>
                                                                    <td style="text-align: center;">
                                                                        <h1>Se modificó su nombre de usuario</h1>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>
                                                                        <p>Estimado/a '.$user['name'].':</p>
                                                                        <p>Se ha modificado su nombre de usuario</p>
                                                                        <p><b><u>Usuario:</u> </b>'.$user['username'].'</p>
                                                                        <p>Para acceder haga <a href="'.base_url().'admin">click aquí</a> e ingrese los datos mencionados arriba.</p>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                    <tr style="width: 100%;">
                                                        <td style="width: 100%;">
                                                            <table style="width: 500px;background-color: #efefef;"">
                                                                <tr style="text-align: center;">
                                                                    <td>
                                                                        <img style="vertical-align: middle;" src="'.base_url().'assets/img/logo_uba_horizontal_negrita.png" alt="Logo UBA">
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </body>
                                </html>
                            ';
    
                            $this->email->subject('Se modificó su nombre de usuario');
                            $this->email->message($html);
    
                            if(!$this->email->send()){
                                $message = array(
                                    'message' => '<i class="fas fa-exclamation-triangle"></i> No fue posible enviar el correo al usuario, por favor comuníquese con el usuario <b>'.$user['surname'].', '.$user['name'].'</b> e indiquelé sus credenciales.<br>Usuario: <b>'.$user['username'].'</b>',
                                    'alert' => 'warning'
                                );
                                $this->session->set_flashdata('message',$message);
                                redirect(base_url().'users/list');
                            }
                        }
                        $message = array(
                            'message' => '<i class="fas fa-check-circle"></i> Se editó el usuario correctamente.',
                            'alert' => 'success'
                        );
                        $this->session->set_flashdata('message',$message);
                        redirect(base_url().'users/list');
                    }else{
                        $data['error_message'] = 'No fue posible editar el usuario, por favor intente en unos instantes, si el problema persiste contactese con el administrador.';
                    }
                }
            }
        }else{
            $message = array(
                'message' => 'El ID indicado no existe.',
                'alert' => 'danger'
            );
            $this->session->set_flashdata('message',$message);
            redirect(base_url().'users/list');
        }
        $data['accion'] = 'edit';
        $data['title'] = 'Editar usuario';
        $data['faculties'] = $this->Faculties_model->getFaculties();
        $data['sections_view'] = "users_form_view";
        $this->load->view('layout_front_view',$data);
    }

    public function resetpassword ($user_id = FALSE){
        if(!$this->session->userdata('user_data')->is_admin){
            redirect('denied');
        }
        if(!$user_id){
            redirect(base_url().'users/list');
        }
        $data['user'] = $this->Users_model->getUserById($user_id);
        if($data['user']){
            if($this->input->post()){

                $password = $this->create_password();
                $user['password'] = password_hash($password,PASSWORD_BCRYPT);
                if($this->Users_model->updateUser($user,$user_id)){
                    
                    $this->email->initialize($this->email_config);
					// $this->email->set_header('MIME-Version', '1.0; charset=utf-8');
					// $this->email->set_header('Content-type', 'text/html');

					$this->email->from($this->no_reply['from_email'], $this->no_reply['from_name']);
					$this->email->to($data['user']->email);
					//$this->email->cc('another@another-example.com');
					//$this->email->bcc('them@their-example.com');

					
					$html = '
                        <!DOCTYPE html>
                        <html lang="es">
                        <head>
                            <title>Reset de contraseña</title>
                        </head>
                        <body style="width: 100%;">
                                    <table style="width: 100%;">
                                        <tbody style="width: 100%;">
                                            <tr style="width: 100%;">
                                                <td style="width: 100%;">
                                                    <table style="width: 500px;">
                                                        <tr>
                                                            <td style="text-align: center;">
                                                                <h1>Reset de contraseña</h1>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <p>Estimado/a '.$data['user']->name.':</p>
                                                                <p>Se ha reseteo su contraseña.</p>
                                                                <p><b><u>Usuario:</u> </b>'.$data['user']->username.'</p>
                                                                <p><b><u>Contraseña:</u></b>'.$password.'</p>
                                                                <p>Para acceder haga <a href="'.base_url().'admin">click aquí</a> e ingrese los datos mencionados arriba.</p>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr style="width: 100%;">
                                                <td style="width: 100%;">
                                                    <table style="width: 500px;background-color: #efefef;"">
                                                        <tr style="text-align: center;">
                                                            <td>
                                                                <img style="vertical-align: middle;" src="'.base_url().'assets/img/logo_uba_horizontal_negrita.png" alt="Logo UBA">
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </body>
                        </html>
					';

					$this->email->subject('Reset de contraseña');
					$this->email->message($html);

					if($this->email->send()){
                        $message = array(
                            'message' => '<i class="fas fa-check-circle"></i> Se reseteo la contraseña del usuario correctamente.',
                            'alert' => 'success'
                        );
                    }else{
                        $message = array(
                            'message' => '<i class="fas fa-exclamation-triangle"></i> No fue posible enviar el correo al usuario, por favor comuníquese con el usuario <b>'.$data['user']->surname.', '.$data['user']->name.'</b> e indiquelé sus credenciales.<br>Usuario: <b>'.$data['user']->username.'</b><br>Contraseña: <b>'.$password.'</b>',
                            'alert' => 'warning'
                        );
                    }
                }else{
                    $message = array(
                        'message' => '<i class="fas fa-exclamation-triangle"></i> No fue posible resetear la contraseña del usuario, por favor intente en unos instantes, si el problema persiste contactese con el administrador.',
                        'alert' => 'danger'
                    );
                }
            }
        }else{
            $message = array(
                'message' => '<i class="fas fa-exclamation-triangle"></i> El ID indicado no existe.',
                'alert' => 'danger'
            );
        }
        $this->session->set_flashdata('message',$message);
        redirect(base_url().'users/list');
    }

    private function rules_user_register(){
        if(!$this->session->userdata('user_data')){
            redirect(base_url().'auth/login');
        }
        $this->form_validation->set_error_delimiters('<div class="alert alert-danger my-2">', '</div>');
        $this->form_validation->set_rules(
            'firstname',
            'Nombre',
            'trim|required',
            array(
                'required' => 'El campo {field} es obligatorio.'
            ));
        $this->form_validation->set_rules(
            'lastname', 
            'Apellido', 
            'trim|required',
            array(
                'required' => 'El campo {field} es obligatorio.',
            )
        );
        $this->form_validation->set_rules(
            'email', 
            'E-Mail', 
            'valid_email|required|callback_check_email',
            array(
                'required' => 'El campo {field} es obligatorio.',
                'check_email' => 'El {field} ya se encuentra registrado, intente con otro E-Mail',
            )
        );
        $this->form_validation->set_rules(
            'username', 
            'nombre de usuario', 
            'trim|required|alpha_numeric|callback_check_username',
            array(
                'required' => 'El campo {field} es obligatorio.',
                'check_username' => 'El {field} ya se encuentra registrado, intente con otro nombre de usuario',
            )
        );

        $this->form_validation->set_rules(
            'faculty_id', 
            'Accesso a Soporte Multimedial', 
            'integer|required',
            array(
                'required' => 'El campo {field} es obligatorio.',
            )
        );
        
    }

    private function rules_user_edit($user_data,$username,$email,$password_multimedial){
        if(!$this->session->userdata('user_data')){
            redirect(base_url().'auth/login');
        }
        $this->form_validation->set_error_delimiters('<div class="alert alert-danger my-2">', '</div>');
        $this->form_validation->set_rules(
            'firstname',
            'Nombre',
            'trim|required',
            array(
                'required' => 'El campo {field} es obligatorio.'
            ));
        $this->form_validation->set_rules(
            'lastname', 
            'Apellido', 
            'trim|required',
            array(
                'required' => 'El campo {field} es obligatorio.',
            )
        );
        if($user_data->email != $email){
            $this->form_validation->set_rules(
                'email', 
                'E-Mail', 
                'valid_email|required|callback_check_email',
                array(
                    'required' => 'El campo {field} es obligatorio.',
                    'check_email' => 'El {field} ya se encuentra registrado, intente con otro E-Mail',
                )
            );
        }

        if($user_data->username != $username){
            $this->form_validation->set_rules(
                'username', 
                'Nombre de usuario', 
                'trim|required|alpha_numeric|callback_check_username',
                array(
                    'required' => 'El campo {field} es obligatorio.',
                    'check_username' => 'El {field} ya se encuentra registrado, intente con otro nombre de usuario',
                )
            );
        }

        $this->form_validation->set_rules(
            'faculty_id', 
            'Accesso a soporte multimedial', 
            'integer|required',
            array(
                'required' => 'El campo {field} es obligatorio.',
            )
        );
        
    }

    public function check_username($username){
        if($this->Users_model->getUserByWhere('username',$username)){
            return FALSE;
        }
        else{
            return TRUE;
        }
    }

    public function check_email($email){
        if($this->Users_model->getUserByWhere('email',$email)){
            return FALSE;
        }
        else{
            return TRUE;
        }
    }

    public function enable_disable_user(){
        if(!$this->session->userdata('user_data')->is_admin){
            redirect('denied');
        }
        if(!$this->input->post()){
            redirect('./users/list');
        }
        $user_id =  $this->input->post('user_id');
        $user_info = $this->Users_model->getUserById($user_id);
        if($user_info){
            $user['suspended'] = $this->input->post('enabled');
            echo json_encode($this->Users_model->updateUser($user,$user_id));
        }else{
            echo json_encode(false);
        }
        
    }

    public function enableUser(){
        if(!$this->session->userdata('user_data')->is_admin){
            redirect('denied');
        }
        if(!$this->input->post()){
            redirect('./users/list');
        }
        $user_id =  $this->input->post('user_id');
        $user_info = $this->Users_model->getUserById($user_id);
        if($user_info){
            $user['suspended'] = 0;
            echo json_encode($this->Users_model->updateUser($user,$user_id));
        }else{
            echo json_encode(false);
        }
    }

    public function disableUser(){
        if(!$this->session->userdata('user_data')->is_admin){
            redirect('denied');
        }
        if(!$this->input->post()){
            redirect('./users/list');
        }
        $user_id =  $this->input->post('user_id');
        $user_info = $this->Users_model->getUserById($user_id);
        if($user_info){
            $user['suspended'] = 1;
            echo json_encode($this->Users_model->updateUser($user,$user_id));
        }else{
            echo json_encode(false);
        }
    }

    private function create_password(){
        $caracteres = array('ABCDEFGHIJKLMNOPQRSTUVWXYZ','abcdefghijklmnopqrstuvwxyz','0123456789','!#$%&');
        $pw="";
        $longpalabra=3;
        for ($j=0;$j<2;$j++){
            for($i=0; $i < $longpalabra; $i++){
                $random_number_1=rand(0,count($caracteres)-2);
                $cantidad_de_caracteres = strlen($caracteres[$random_number_1])-1;
                $random_number_2 = rand(0,$cantidad_de_caracteres);
                $pw.=$caracteres[$random_number_1][$random_number_2];
            }
            $random_number_3 = rand(0,strlen($caracteres[3])-1);
            $pw.=$caracteres[3][$random_number_3];
        }
        return $pw;
    }
}