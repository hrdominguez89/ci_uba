<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="container my-auto">
    <div class="row">
        <div class="col-12 text-center">
            <img class="logo-uba-home my-3" src="<?php echo base_url();?>assets/img/faculties/<?php echo $this->session->userdata('user_data')->faculty_id;?>.png" alt="Logo <?php echo $this->session->userdata('user_data')->faculty_name;?>">
        </div>
        <div class="col-12 d-flex justify-content-around">
            <div class="row">
                <div class="col-6 d-flex align-items-stretch">
                    <a class="card text-center card-home card-contorno" href="<?php echo base_url();?>platforms">
                        <div class="align-self-center">
                            <div class="mt-5">
                                <img class="icon-card" src="<?php echo base_url();?>assets/icons/cinema.svg">
                            </div>
                            <div class="mb-3 card-body text-center">
                                <p class="card-text">AULA VIRTUAL</p>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-6 d-flex align-items-stretch">
                    <a class="card text-center card-home card-contorno" href="<?php echo prep_url($this->config->item('site_config')->url_moodle);?>">
                        <div class="align-self-center">
                            <div class="mt-5">
                                <img class="icon-card" src="<?php echo base_url();?>assets/icons/multimedia.svg">
                            </div>
                            <div class="mb-3 card-body text-center">
                                <p class="card-text">SOPORTE MULTIMEDIAL</p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        <!-- </form> -->
    </div>
</div>