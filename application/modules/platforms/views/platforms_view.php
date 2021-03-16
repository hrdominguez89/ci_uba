<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="container">
    <div class="row">
        <div class="col-12 mt-2">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-color">
                    <li class="breadcrumb-item"><a href="<?php echo base_url();?>">Inicio</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?php echo $title;?></li>
                </ol>
            </nav>
            <h2 class="mb-2">Elija una plataforma</h2>
        </div>
    </div>
</div>
<div class="container mt-5">
    <div class="row">
        <div class="col-12 d-flex justify-content-around">
            <div class="row">
                <?php foreach($platforms as $platform):;?>
                <div class="col-6 col-md-3 col-xl-3 my-2 d-flex align-items-stretch">
                    <a class="card text-center card-contorno card-platform" href="<?php echo $platform->url;?>">
                        <div>
                            <img class="img-fluid" src="<?php echo base_url();?>uploads/platforms/<?php echo $platform->id;?>.png" alt="<?php echo $platform->name;?>" title="<?php echo $platform->name;?>">
                        </div>
                    </a>
                </div>
                <?php endforeach;?>
            </div>
        </div>
    </div>
</div>