<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="container my-auto">
    <div class="row justify-content-center">
        <div class="col-8 col-md-6 col-lg-4 col-xl-3 text-center mt-5">
            <form class="form-signin" id="loginForm" method="post" action="<?php echo prep_url($this->config->item('site_config')->url_moodle);?>/login/index.php" id="loginForm">
                <img class="mb-4" src="<?php echo base_url();?>assets/img/logo_uba_horizontal_negrita.png" alt="Logo UBA" height="100">
                <?php if(@$error):;?>
                    <div class="alert alert-danger">
                        <?php echo $message;?>
                    </div>
                <?php endif;?>
                <?php if($this->session->flashdata('message')):?>
                    <div class="alert alert-<?php echo $this->session->flashdata('message')['alert'];?>">
                        <?php echo $this->session->flashdata('message')['message'];?>
                    </div>
                <?php endif;?>
                <div>
                    <label for="username" class="sr-only">Email address</label>
                    <input type="text" id="username" name="username" class="form-control" placeholder="Usuario" required autofocus value="<?php echo set_value('username'); ?>">
                    <?php echo form_error('username'); ?>
                </div>
                <div>
                    <label for="password" class="sr-only">Contraseña</label>
                    <input type="password" autocomplete id="password" name="password" class="form-control" placeholder="Contraseña" maxlength="72" required>
                    <?php echo form_error('password'); ?>
                </div>
                <div id="captchaError">
                </div>
                <div>
                    <input type="hidden" id="g-recaptcha" name="g-recaptcha" class="mb-2" data-site-key="<?php echo $this->data_captcha_google['siteKey'];?>">
                    <?php echo form_error('g-recaptcha'); ?>
                    <button class="mt-3 btn btn-primary btn-block g-recaptcha" onclick="submitForm(event)" type="submit">Iniciar sesión</button>
                </div>
                <div id="message" class="text-center mt-2"></div>
            </form>
        </div>
    </div>
</div>