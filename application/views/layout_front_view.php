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
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/DataTables/datatables.min.css"/>
    
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
    <!-- HEADER -->
    <?php $this->load->view('header_view');?>
    <!-- FIN HEADER -->

    <!-- SECTIONS -->
    <div class="mb-3">
      <?php $this->load->view($sections_view);?>
    </div>
    <!-- END SECTIONS -->

    <!-- FOOTER -->
    <?php
    // $this->load->view('footer_view');
    ?> 
    <!-- END FOOTER -->
  
  <script src="<?php echo base_url(); ?>assets/js/jquery-3.5.1.slim.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/popper.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="<?php echo base_url(); ?>assets/DataTables/datatables.min.js"></script>

  <!-- CONDICIONAL PARA CARGAR LOS SCRIPT DESDE EL CONTROLLER -->
  <?php if(isset($files_js)){
      foreach ($files_js as $file_js) {
        # code...
        echo '<script src="'.base_url().'assets/js/'.$file_js.'?v='.rand().'"></script>'; 
      }
    } ?>
    
  <script>
    $(document).ready(function(){
      if($('#TablaDataTable').length){
        $('#TablaDataTable').DataTable({
          "language": {
            "url": "<?php echo base_url();?>assets/DataTables/language/Spanish.lang",
          }
        });
      }
    });
  </script>
  
</body>
</html> 