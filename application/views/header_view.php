<header>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="<?php echo base_url();?>">
            <img class="logo-navbar" src="<?php echo base_url();?>assets/img/faculties/<?php echo $this->session->userdata('user_data')->faculty_id;?>.png" alt="Logo <?php echo $this->session->userdata('user_data')->faculty_name;?>">
        </a>
        <?php if($this->session->userdata('user_data') && $this->session->userdata('user_data')->is_admin)/* true == admin, false == usuario general */:?>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <?php echo $this->session->userdata('user_data')->firstname.' '.$this->session->userdata('user_data')->lastname;?> 
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="<?php echo base_url();?>home">Home</a>
                        <a class="dropdown-item" href="<?php echo base_url();?>admin">Panel administrador</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="<?php echo prep_url($this->config->item('site_config')->url_moodle);?>/admin/search.php">Repositorio administrador</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="<?php echo prep_url($this->config->item('site_config')->url_moodle);?>/user/edit.php?id=<?php echo $this->session->userdata('user_data')->id;?>&returnto=profile">Editar perfil</a>
                        <a class="dropdown-item" href="<?php echo prep_url($this->config->item('site_config')->url_moodle.'/login/change_password.php');?>">Cambiar contraseña</a>
                        <a class="dropdown-item" href="<?php echo base_url('/auth/logout');?>">Cerrar sesión</a>
                    </div>
                </li>
            </ul>
        </div>
        <?php endif?>
    </nav>
</header>