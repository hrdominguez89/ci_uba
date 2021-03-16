<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="container mb-">
    <div class="row">
        <div class="col-12 mt-2">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-color">
                    <li class="breadcrumb-item"><a href="<?php echo base_url();?>admin">Panel administrador</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo base_url();?>platforms/list">Listado de plataformas</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?php echo $title;?></li>
                </ol>
            </nav>
            <h2 class="mb-2"><?php echo $title;?></h2>
        </div>
    </div>
</div>
<div class="container mt-4 mb-3">
    <div class="row">
        <div class="col-6">
            <?php if(@$error_message):;?>
            <div class="alert alert-danger">
                <?php echo $error_message;?>
            </div>
            <?php endif;?>
            <form action="<?php echo base_url();?>platforms/<?php echo $accion == 'insert' ? 'insert':'edit/'.$platform->id;?>" method="POST" enctype="multipart/form-data">
                <div class="my-2">    
                    <label for="platform_name">Nombre de la plataforma</label>
                    <input type="text" id="platform_name" name="platform_name" class="form-control" value="<?php echo set_value('platform_name')? set_value('platform_name'):@$platform->name;?>" required>
                    <?php echo form_error('platform_name'); ?>
                </div>

                <div class="my-2">    
                    <label for="url">URL</label>
                    <input type="url" id="url" name="url" class="form-control" value="<?php echo set_value('url')? set_value('url'):@$platform->url;?>" maxlength="255" required >
                    <?php echo form_error('url'); ?>
                </div>

                <?php if($accion == 'edit'):;?>
                <div class="my-2">
                    <p>Imagen actual:</p>
                    <img class="img-fluid img-platform-show-edit" src="<?php echo base_url();?>uploads/platforms/<?php echo $platform->id;?>.png" alt="logo de <?php echo $platform->name;?>">
                </div>
                <?php endif;?>

                <div class="my-2">  
                    <label for="platform_logo">Imagen</label>
                    <input type="file" id="platform_logo" name="platform_logo" class="form-control" accept=".png" <?php echo $accion == "insert"? 'required':'';?>>
                </div>

                <div class="text-right mt-5">
                    <button type="submit" class="btn btn-success">Guardar plataforma</button>
                    <a href="<?php echo base_url();?>platforms/list" class="btn btn-secondary">Volver al listado</a>
                </div>
            </form>
        </div>
    </div>
</div>
