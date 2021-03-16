<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="container my-auto">
    <form method="post" action="<?php echo base_url();?>auth/changepassword/<?php echo $user->id;?>">
        <div class="row justify-content-center">
            <div class="col-8 col-md-6 col-lg-4 col-xl-4 text-center mt-5">
                <h2><?php echo $title;?></h2>
                
                <?php if(@$error_message):;?>
                    <div class="alert alert-danger">
                        <?php echo $error_message;?>
                    </div>
                <?php endif;?>
                <div class="mt-3">
                    <label for="current-password" class="sr-only">Contraseña actual</label>
                    <input type="password" id="current-password" autocomplete name="current-password" class="form-control" placeholder="Contraseña actual" required autofocus value="<?php echo set_value('current-password'); ?>">
                    <?php echo form_error('current-password'); ?>
                </div>
                <div class="mt-3">
                    <label for="password" class="sr-only">Nueva contraseña</label>
                    <input type="password" id="password" autocomplete name="password" class="form-control" placeholder="Nueva contraseña" maxlength="72" required>
                    <?php echo form_error('password'); ?>
                </div>
                <div class="mt-3">
                    <label for="re-password" class="sr-only">Confirmar contraseña</label>
                    <input type="password" id="re-password" autocomplete name="re-password" class="form-control" placeholder="Confirmar contraseña" maxlength="72" required>
                    <?php echo form_error('re-password'); ?>
                </div>
                <button type="button" class="btn btn-link" data-toggle="modal" data-target="#exampleModal">
                    Ver requisitos
                </button>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-8 col-md-6 col-lg-4 col-xl-5 text-center">
                
                <button class="mt-3 btn btn-primary" type="submit" title="Cambiar contraseña">Cambiar contraseña</button>
                <a href="<?php echo base_url();?>home" class="mt-3 btn btn-secondary" type="submit" title="Cancelar">Cancelar</a>
            </div>
        </div>
        
    </form>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Requisitos de contraseña</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        La contraseña debe cumplir lo siguiente:
        <ol>
            <li>Debe contener al menos 6 caracteres.</li>
            <li>Debe contener al menos 1 número.</li>
            <li>Debe contener al menos 1 letra mayuscula.</li>
            <li>Debe contener al menos 1 letra minuscula.</li>
        </ol>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

</div>