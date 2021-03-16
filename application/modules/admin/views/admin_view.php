<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="container">
    <div class="row">
        <div class="col-12 my-4">
            <?php if($this->session->flashdata('message')):?>
                <div class="col-12">
                    <div class="alert alert-<?php echo $this->session->flashdata('message')['alert'];?>">
                        <?php echo $this->session->flashdata('message')['message'];?>
                    </div>
                </div>
            <?php endif;?>
            <h2 class="mb-2"><?php echo $title;?></h2>
        </div>
        <div class="col-12 d-flex justify-content-around">
            <div class="row">

                <div class="my-2 col-6 col-md-6 d-flex align-items-stretch">
                    <a class="card text-center card-home card-contorno" href="<?php echo base_url();?>users">
                        <div class="align-self-center">
                            <div class="mt-5">
                            <i class="fas fa-users icon-card"></i>
                            </div>
                            <div class="mb-3 card-body text-center">
                                <p class="card-text">Usuarios</p>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="my-2 col-6 col-md-6">
                    <a class="card text-center card-home card-contorno" href="<?php echo base_url();?>platforms/list">
                        <div class="align-self-center">
                            <div class="mt-5">
                                <i class="fas fa-video icon-card"></i>
                            </div>
                            <div class="mb-3 card-body text-center">
                                <p class="card-text">Salas de videoconferencia</p>
                            </div>
                        </div>
                    </a>
                </div>
                
            </div>
        </div>
    </div>
</div>