<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="container mb-">
    <div class="row">
        <div class="col-12 mt-2">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-color">
                    <li class="breadcrumb-item"><a href="<?php echo base_url();?>home">Inicio</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?php echo $title;?></li>
                </ol>
            </nav>
            <h2 class="mb-2"><?php echo $title;?></h2>
        </div>
    </div>
</div>
<div class="container mt-3 mb-3">
    <div class="row">
        <div class="col-12">
            <?php if(@$error_message):;?>
            <div class="alert alert-danger">
                <?php echo $error_message;?>
            </div>
            <?php endif;?>
            <form method="POST" action="<?php echo base_url().'users/editprofile/'.$user->id;?>">
                <div class="form-row">

                    <div class="form-group col-md-4">
                        <label for="surname">Apellido: <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="surname" name="surname" value="<?php echo set_value('surname')? set_value('surname'):@$user->surname;?>" required>
                        <?php echo form_error('surname'); ?>
                    </div>

                    <div class="form-group col-md-4">
                        <label for="name">Nombre: <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" value="<?php echo set_value('name')? set_value('name'):@$user->name;?>" required>
                        <?php echo form_error('name'); ?>
                    </div>


                    <div class="form-group col-md-4">
                        <label for="email">E-Mail: <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo set_value('email')? set_value('email'):@$user->email;?>" required>
                        <?php echo form_error('email'); ?>
                    </div>

                </div>
                <div class="form-row my-2">
                    <h3>Datos soporte multimedial</h3>
                </div>
                <div class="form-row">

                    <div class="form-group col-md-4">
                        <label for="username_multimedial">Usuario Soporte Multimedial: <?php echo $accion=='insert'? '<span class="text-danger">*</span>':'';?></label>
                        <input type="text" class="form-control" id="username_multimedial" name="username_multimedial" value="<?php echo set_value('username_multimedial')? set_value('username_multimedial'):@$user->username_multimedial;?>">
                        <?php echo form_error('username_multimedial'); ?>
                    </div>

                    <div class="form-group col-md-4">
                        <label for="password_multimedial">Password Soporte Multimedial: <?php echo $accion=='insert'? '<span class="text-danger">*</span>':'';?></label>
                        <input type="password" class="form-control" id="password_multimedial" name="password_multimedial" value="" autocomplete="new-password" <?php $accion =='insert' ? 'required': '';?>>
                        <?php echo form_error('password_multimedial'); ?>
                    </div>
                    
                </div>
                <div class="text-right mt-4">
                    <button type="submit" title="Guardar perfil" class="btn btn-success">Guardar perfil</button>
                    <a href="<?php echo base_url();?>home" title="Volver al inicio" class="btn btn-secondary">Volver al inicio</a>
                </div>
            </form>
        </div>
    </div>
</div>
