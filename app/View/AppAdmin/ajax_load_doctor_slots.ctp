<?php

$login = $this->Session->read('Auth.User');



?>


<div class="col-lg-12">
    <div class="card">
        <div class="header">
            <h2><strong>Doctor</strong> List</h2>
        </div>
        <?php if(!empty($list)){ ?>
            <div class="body">

                    <div class="row clearfix">


                        <div class="col-md-12 loading_box_controll">
                            <div class="loading_div_app">
                                <span class="fa fa-spinner fa-spin slot_loading"></span> &nbsp; <lable class="app_loader_msg"></lable>
                            </div>
                        </div>


                        <div class="col-md-6">

                            <?php echo $this->Form->input('doctor',array('type'=>'select','div'=>false,'label'=>false,'options'=>$list,'class'=>'form-control show-tick address_drp')); ?>

                        </div>

                        <div class="col-md-6 append_appointment">

                        </div>



                    </div>

                    <div class="row clearfix" id="load_slot_div">

                    </div>


            </div>
        <?php } else{ ?>
            <h3> There is no slot available.</h3>
        <?php }  ?>
    </div>

</div>

<style>
    .profile_img{height: 40px;}
    .selectpicker{display: block !important;}
</style>

<div class="modal fade" id="search_cus_modal" role="dialog"></div>





