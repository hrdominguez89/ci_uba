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
                <a href="insert" class="btn btn-primary"><i class="fas fa-plus"></i> Agregar plataforma</a>
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
                        <th>Plataforma</th>
                        <th>URL</th>
                        <th>Imagen</th>
                        <th>Editar</th>
                        <th>Eliminar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($platforms as $platform):;?>
                    <tr>
                        <td>
                            <?php echo $platform->name;?>
                        </td>
                        <td>
                            <a href="<?php echo $platform->url;?>"><?php echo $platform->url;?></a>
                        </td>
                        <td>
                            <a href="#" data-open="<?php echo "imagen".$platform->id; ?>" title="Ver imagen" data-toggle="modal" data-target="#<?php echo "imagen".$platform->id; ?>">
                                <img class="img-table-platforms" src="<?php echo base_url();?>uploads/platforms/<?php echo $platform->id;?>.png" alt="<?php echo $platform->name;?>" title="<?php echo $platform->name;?>">
                            </a>
                            <!-- MODAL IMAGEN -->
                            <div class="modal fade" id="<?php echo "imagen".$platform->id; ?>" tabindex="-1" role="dialog" aria-labelledby="<?php echo "imagen".$platform->id; ?>Label">
                                <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5><?php echo $platform->name;?></h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        </div>
                                        <div class="modal-body text-center">
                                            <img class="img-fluid img-platform-modal" src="<?php echo base_url();?>uploads/platforms/<?php echo $platform->id;?>.png" alt="<?php echo $platform->name;?>" title="<?php echo $platform->name;?>">
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- FIN MODAL IMAGEN -->
                        </td>
                        <td class="text-center">
                            <a href="platforms/edit/<?php echo $platform->id;?>" class="text-warning"><i class="fas fa-edit"></i></a>
                        </td>
                        <td class="text-center"> 
                            <a href="#" data-open="<?php echo "delete".$platform->id; ?>" title="Eliminar plataforma" data-toggle="modal" data-target="#<?php echo "delete".$platform->id; ?>" class="text-danger">
                                <i class="fas fa-trash-alt size-18" aria-hidden="true"></i><span class="show-for-sr"></span>
                            </a>
                            <!-- MODAL DELETE -->
                            <div class="modal fade" id="<?php echo "delete".$platform->id; ?>" tabindex="-1" role="dialog" aria-labelledby="<?php echo "delete".$platform->id; ?>Label">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h3 class="text-left">¿Está seguro que desea eliminar esta plataforma?</h3>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        </div>
                                        <div class="modal-body text-left">
                                            Plataforma: <?php echo $platform->name; ?>
                                        </div>
                                        <div class="modal-footer">
                                            <form action="<?php echo base_url();?>platforms/delete" method="POST">
                                            <input type="hidden" name="platform_id" value="<?php echo $platform->id;?>">
                                            <button class="btn btn-danger" type="submit" title="Eliminar plataforma">Si, eliminar</a>
                                            </form>
                                            <button type="button" class="btn btn-primary" data-dismiss="modal">No, cancelar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- FIN MODAL DELETE -->             
                        </td>
                    </tr>
                    <?php endforeach;?>
                </tbody>
            </table>
        </div>
    </div>
</div>
