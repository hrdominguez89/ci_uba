<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cli extends MX_Controller {

    public function __construct()
	{
        parent::__construct();
        if(!is_cli()){
            redirect(base_url().'e404');
        }
        $this->config->load('custom_config');//cargo los datos de configuración
        $this->site_config = $this->config->item('site_config');
        $this->load->model('Cli_model');
    }

    public function set_cookie_domain(){
        $data['value'] = $this->site_config->sessioncookiedomain;
        if($this->Cli_model->setCookieDomain($data)){
            echo 'Se actualizó la cookie de session de dominio correctamente'.PHP_EOL;
            $this->purge_cache();
        }else{
            echo 'No fue posible actualizar la cookie de session de dominio'.PHP_EOL;
        }
    }

    public function purge_cache(){
        $cli_file = $this->site_config->route_moodle.'/admin/cli/purge_caches.php';
        if(file_exists($cli_file)){
            exec('php '. $cli_file);
            echo 'se purgó la caché correctamente'.PHP_EOL;
        }else{
            echo 'no se pudo purgar la caché, verifique que la ruta de "route_moodle" ubicada en el archivo ./application/config/custom_config.php se encuentre correctamente'.PHP_EOL;
        }
    }
}