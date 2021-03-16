<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="container mb-">
    <div class="row">
        <div class="col-12 mt-2">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-color">
                    <li class="breadcrumb-item"><a href="<?php echo base_url();?>admin">Panel administrador</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?php echo $title;?></li>
                </ol>
            </nav>
            <h2 class="mb-2"><?php echo $title;?></h2>
            <div class="text-right">
                <a href="insert" class="btn btn-primary"><i class="fas fa-plus"></i> Agregar Usuario</a>
            </div>
        </div>
    </div>
</div>
<div class="container mt-5 mb-3">
    <div class="row">
        <?php if($this->session->flashdata('message')):?>
            <div class="col-12">
                <div class="alert alert-<?php echo $this->session->flashdata('message')['alert'];?>">
                    <?php echo $this->session->flashdata('message')['message'];?>
                </div>
            </div>
        <?php endif;?>
        <div class="col-12">
            <table id="TablaDataTable" class="table table-sm dataTables table-striped" style="width:100%">
                <thead>
                    <tr class="text-center">
                        <th>Nombre</th>
                        <th>Nombre de usuario</th>
                        <th>E-Mail</th>
                        <th>Rol</th>
                        <th>Facultad</th>
                        <th>Editar</th>
                        <th>Deshabilitado / Habilitado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($users as $user):;?>
                    <tr>
                        <td>
                            <?php echo $user->firstname.' '.$user->lastname;?>
                        </td>
                        <td>
                            <?php echo $user->username;?>
                        </td>
                        <td>
                            <?php echo $user->email;?>
                        </td>
                        <td>
                            <?php
                            if(gettype(array_search($user->id,$admins))=="integer"){
                                echo 'Admin';
                            }else{
                                echo 'Usuario General';
                            }
                            ?>
                        </td>
                        <td>
                            <?php echo $user->faculty_name;?>
                        </td>
                        <td class="text-center">
                            <a href="edit/<?php echo $user->id;?>" class="text-warning"><i class="fas fa-edit"></i></a>
                        </td>
                        <td class="text-center">
                            <label class="switch toggle<?php echo $user->id;?>" for="toggle<?php echo $user->id;?>" title="<?php echo $user->suspended==1?"Activar usuario":"Desactivar usuario";?>">
                                <input type="checkbox" onclick="enableDisableUsers(this)" class="toggleUser" <?php echo $user->suspended==1? "":"checked";?> data-user-id="<?php echo $user->id;?>" id="toggle<?php echo $user->id;?>" />
                                <div class="slider round"></div>
                            </label>
                        </td>
                    </tr>
                    <?php endforeach;?>
                </tbody>
            </table>
        </div>
    </div>
</div>
