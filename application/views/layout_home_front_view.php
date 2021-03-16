<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<!DOCTYPE html>
<html lang="es-ar" class="h-100">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">    
    <meta name="description" content="Universidad de Buenos Aires">
    <meta name="author" content="Universidad de Buenos Aires">

    <script src="https://kit.fontawesome.com/a7dbba11bb.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/styles.css?ver=<?php echo rand();?>">
    <link rel="icon" type="image/png" href="<?php echo base_url();?>assets/img/logo_uba_circulo.png"/>
    
    <?php if(@$recaptcha):;?>
    <script src="https://www.google.com/recaptcha/api.js?render=<?php echo $this->data_captcha_google['siteKey'];?>&hl=es-419"></script>
    <?php endif;?>
    <title><?php echo @$this->session->userdata('user_data')->faculty_name ? $this->session->userdata('user_data')->faculty_name : 'Universidad de Buenos Aires';?></title>
  
    <?php if(isset($files_css)){
      foreach ($files_css as $file) {
        # code...
        echo '<link href="'.base_url().'assets/css/'.$file.'?ver='.rand().' rel="stylesheet" type="text/css" />';
      }
} ?>
  	
</head>

<body class="d-flex flex-column h-100">
    
    <?php if(isset($this->session->userdata('user_data')->session_id) && $this->session->userdata('user_data')->session_id == $_COOKIE['MoodleSession'] && @$this->session->userdata('user_data')->is_admin /*is_admin true = admin*/):?>
    <?php
      $this->load->view('header_view');
    ?>
    <?php elseif(isset($this->session->userdata('user_data')->session_id) && $this->session->userdata('user_data')->session_id == @$_COOKIE['MoodleSession'] && !@$this->session->userdata('user_data')->is_admin /*is_admin false = user_general*/):?>
    <div class="container">
      <div class="row mt-3">
        <div class="col-12 text-right">
        <div class="dropdown">
          <a class="btn bg-white text-dark dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <?php echo $this->session->userdata('user_data')->firstname.' ',$this->session->userdata('user_data')->lastname;?>
          </a>

          <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">

            <a class="dropdown-item" href="<?php echo prep_url($this->config->item('site_config')->url_moodle);?>/user/edit.php?id=<?php echo $this->session->userdata('user_data')->id;?>&returnto=profile">Editar perfil</a>
            <a class="dropdown-item" href="<?php echo prep_url($this->config->item('site_config')->url_moodle);?>/login/change_password.php">Cambiar contraseña</a>
            <a class="dropdown-item" href="<?php echo base_url('/auth/logout');?>">Cerrar sesión</a>
          </div>
        </div>
        </div>
      </div>
    </div>
    <?php endif;?>

    <!-- SECTIONS -->
    <?php $this->load->view($sections_view);?>
    <!-- END SECTIONS -->

    <?php 
    // if($this->session->userdata('user_data') && $this->session->userdata('user_data')->is_admin /*is_admin true = admin*/){
    //   $this->load->view('footer_view');
    // };
    ?>
  
  <script src="<?php echo base_url(); ?>assets/js/jquery-3.5.1.slim.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/popper.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/bootstrap.min.js"></script>

    
  <!-- CONDICIONAL PARA CARGAR LOS SCRIPT DESDE EL CONTROLLER -->
  <?php if(isset($files_js)){
      foreach ($files_js as $file_js) {
        # code...
        echo '<script src="'.base_url().'assets/js/'.$file_js.'?v='.rand().'"></script>'; 
      }
    } ?>
</body>
</html> 