<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="container mb-">
    <div class="row">
        <div class="col-12 mt-2">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-color">
                    <li class="breadcrumb-item"><a href="<?php echo base_url();?>admin">Panel administrador</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo base_url();?>users/list">Listado de usuarios</a></li>
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
            <form method="POST" action="<?php echo base_url().'users/'; echo $accion == 'insert'? 'insert':'edit/'.$user->id;?>">
                <div class="form-row">

                    <div class="form-group col-md-4">
                        <label for="lastname">Apellido: <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="lastname" name="lastname" value="<?php echo set_value('lastname')? set_value('lastname'):@$user->lastname;?>" required>
                        <?php echo form_error('lastname'); ?>
                    </div>

                    <div class="form-group col-md-4">
                        <label for="firstname">Nombre: <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="firstname" name="firstname" value="<?php echo set_value('firstname')? set_value('firstname'):@$user->firstname;?>" required>
                        <?php echo form_error('firstname'); ?>
                    </div>
                    
                    <div class="form-group col-md-4">
                        <label for="email">E-Mail: <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo set_value('email')? set_value('email'):@$user->email;?>" required>
                        <?php echo form_error('email'); ?>
                    </div>

                    <div class="form-group col-md-4">
                        <label for="username">Nombre de usuario: <?php echo $accion=='insert'? '<span class="text-danger">*</span>':'';?></label>
                        <input type="text" class="form-control" id="username" name="username" value="<?php echo set_value('username')? set_value('username'):@$user->username;?>" required>
                        <?php echo form_error('username'); ?>
                    </div>
                    
                    <div class="form-group col-md-4">
                        <label for="faculty_id">Facultad: <span class="text-danger">*</span></label>
                        <select id="faculty_id" name="faculty_id" class="form-control" required>
                            <option selected hidden disabled>Seleccione una facultad</option>
                            <?php foreach($faculties as $faculty):?>
                            <option value="<?php echo $faculty->id;?>" <?php echo set_select('faculty_id',$faculty->id)? set_select('faculty_id',$faculty->id):(@$user->faculty_id == $faculty->id? 'selected':'');?>><?php echo $faculty->name;?></option>
                            <?php endforeach;?>
                        </select>
                        <?php echo form_error('faculty_id'); ?>
                    </div>
                </div>
                <div class="form-row">

                    <div class="form-group col-md-4">
                        <div class="form-group form-check">
                            <input type="checkbox" class="form-check-input" id="is_admin" name="is_admin" value="1" <?php echo set_checkbox('is_admin', "1"); ?> <?php echo isset($is_admin) && $is_admin? 'checked':'';?>>
                            <label class="form-check-label" for="is_admin">Administrador</label>
                        </div>
                        <?php echo form_error('is_admin'); ?>
                    </div>

                </div>
                <div class="text-right mt-4">
                    <?php if($accion == 'edit'):?>
                    <button type="button" title="Reset Contraseña" class="btn btn-warning text-white" data-toggle="modal" data-target="#staticBackdrop">Reset contraseña</button>
                    <?php endif;?>
                    <button type="submit" title="Guardar usuario" class="btn btn-success">Guardar usuario</button>
                    <a href="<?php echo base_url();?>users/list" title="Volver al listado" class="btn btn-secondary">Volver al listado</a>
                </div>
            </form>
        </div>
        <?php if($accion == 'edit'):?>
        <form action="<?php echo base_url();?>users/resetpassword/<?php echo $user->id;?>" method="POST">
            <div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="staticBackdropLabel">Reseteo de contraseña</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body text-center">
                            <div class="text-danger" style="font-size:50px;">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            <h5 class="modal-title" id="staticBackdropLabel">¿Está seguro que desea resetear la contraseña de este usuario?</h5>
                        </div>
                        <div class="modal-footer">
                            <input type="hidden" name="user_id" value="<?php echo $user->id;?>">
                            <button type="submit" class="btn btn-danger">Si, resetear contraseña</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">No, cancelar</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <?php endif;?>
    </div>
</div>
