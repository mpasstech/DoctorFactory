<div class="modal fade"   id="vaccination_detail" tabindex="-1"  aria-labelledby="vaccination_detail" aria-hidden="true">


    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" ><?php echo $data['child_name']; ?><span class="age_title"><?php echo $data['title']; ?></span></h5>
                <button type="button"  class="close VacDetailClose" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="padding: 0.5rem;" >
                <div class="vac_container_table" style="width: 100%;display: block;float: left;">
                    <table style="width: 100%;" class="vac_detail_table">


                        <tr>
                            <td colspan="2"><label>Vaccination </label></td><td><?php echo $data['vac_name']; ?></td>
                        </tr>
                        <tr>
                            <td colspan="2"><label>Dose </label></td><td><?php echo $data['dose']; ?></td>
                        </tr>
                        <tr>
                            <td colspan="2"> <label>Due Date </label></td><td><?php echo $data['due_date']; ?></td>
                        </tr>
                        <tr>
                            <td colspan="2"><label>Given Date </label></td><td><?php echo $data['vac_done_date']; ?></td>
                        </tr>

                        <tr>
                            <td colspan="2"><label>Vaccination Status</label></td><td class="vaccination_status_val"><?php echo $data['status']; ?></td>
                        </tr>


                        <?php if(!empty($data['description'])){ ?>
                        <tr class="description_td">
                            <td colspan="3">
                                <label>Description </label>
                                <span><?php echo $data['description']; ?></span>
                            </td>
                        </tr>
                        <?php } ?>

                        <tr class="remark_td">
                            <td colspan="3">
                                <label>

                                    <?php

                                    if($user_role=='USER'){
                                        echo "Remark";
                                    }else{
                                        echo "Enter Remark";
                                    }
                                    ?>


                                </label>
                                <textarea <?php echo ($user_role=='USER')?'disabled':''; ?> id="remark_box" rows="5"><?php echo $data['remark']; ?></textarea>
                            </td>
                        </tr>

                        <tr class="image_tr_heading">
                            <td colspan="3">
                                <h5>
                                    <?php

                                        if($user_role=='USER'){
                                            echo "Images";
                                        }else{
                                            echo "Upload Images";
                                        }
                                    ?>

                                </h5>
                                <?php echo $this->Form->create('AppointmentStaff',array('type'=>'file','method'=>'post','class'=>'form-horizontal','id'=>'sub_frm')); ?>
                                <?php echo $this->Form->input('file',array("accept"=>"image/x-png,image/gif,image/jpeg",'type'=>'file','label'=>false,'class'=>'form-control hidden_file_browse','style'=>'display:none;')) ?>
                                <?php echo $this->Form->end(); ?>
                            </td>
                        </tr>
                        <tr class="vac_image_td">
                            <?php
                            $default =Router::url('/img/vaccination_avator.png',true);
                            $vac_image =empty($data['vac_image'])?$default:$data['vac_image'];
                            $vac_image_1 =empty($data['vac_image_2'])?$default:$data['vac_image_2'];
                            $vac_image_2 =empty($data['vac_image_3'])?$default:$data['vac_image_3'];

                            $class = ($user_role=='USER')?'disabled':'';

                            ?>
                            <td>

                                <img id="vac_img_1" class="<?php echo $class; ?>" src="<?php echo $vac_image; ?>" />
                                <input type="hidden" id="vac_image" class="vac_image_input" value="<?php echo $data['vac_image']; ?>">
                                <label style="display: <?php echo ($user_role !='USER')?'block':'none'; ?>" ><a href="javascript:void(0);" class="imageDeleteButton"><i class="fa fa-trash"></i> Remove</a></label>
                            </td>
                            <td>

                                <img id="vac_img_2" class="<?php echo $class; ?>" src="<?php echo $vac_image_1; ?>" />
                                <input type="hidden" id="vac_image1" class="vac_image_input" value="<?php echo $data['vac_image_1']; ?>">
                                <label style="display: <?php echo ($user_role !='USER')?'block':'none'; ?>" ><a href="javascript:void(0);" class="imageDeleteButton"><i class="fa fa-trash"></i> Remove</a></label>
                            </td>
                            <td>

                                <img id="vac_img_3" class="<?php echo $class; ?>" src="<?php echo $vac_image_2; ?>" />
                                <input type="hidden" id="vac_image2" class="vac_image_input" value="<?php echo $data['vac_image_2']; ?>">
                                <label style="display: <?php echo ($user_role !='USER')?'block':'none'; ?>" ><a href="javascript:void(0);" class="imageDeleteButton"><i class="fa fa-trash"></i> Remove</a></label>
                            </td>

                        </tr>




                        <?php if($data['status']=='PENDING' && $user_role !='USER'){ ?>
                            <tr class="reschedule_tr">
                                <td colspan="3"><H5 style="padding: 1rem 0px;">Reschedule this vaccination <input type="checkbox" value="YES" id="rescheduleVacCheckbox"> </H5></td>
                            </tr>
                        <?php } ?>


                        <?php if($user_role !='USER'){ ?>

                            <tr>
                                <td colspan="2"><label style="padding: 1rem 0px;"><i class="calendar_icon fa fa-calendar" aria-hidden="true"></i> Select Vaccination Date</label></td><td> <input id="vac_date_input" type="text" class="form-control vac_date_input" value="<?php echo ($data['vac_done_date']=='N/A')?'':$data['vac_done_date']; ?>"> </td>
                            </tr>


                        <?php } ?>




                        <tr class="blank_tr">
                            <td colspan="3"></td>
                        </tr>

                        <tr class="slider_tr">
                            <td colspan="3">

                                <?php if($previous && !empty($previous)){ ?>
                                    <h5>Previous Vaccination History</h5>
                                    <div id="vac_banner" class="carousel slide" data-ride="carousel" >

                                        <ol class="carousel-indicators">
                                            <?php foreach ($previous as $key => $vaccine) {   ?>
                                                <li data-target="#carouselExampleIndicators" data-slide-to="<?php echo $key; ?>" class="<?php echo ($key==0)?'active':''; ?>""></li>
                                            <?php } ?>
                                        </ol>


                                        <div class="carousel-inner">
                                            <?php foreach ($previous as $key => $vaccination) {   ?>
                                                <div data-ci="<?php echo base64_encode($child_id); ?>" data-id="<?php echo base64_encode($vaccination['id']); ?>"  class="slider_vac_item carousel-item <?php echo ($key==0)?'active':''; ?>">
                                                    <table>
                                                        <tr>
                                                            <td class="first_td"><i style="background:blue;" class="fa fa-eyedropper"></i> </td>
                                                            <td class="second_td">

                                                                <h6><?php echo $vaccination['vac_name']; ?> </h6>
                                                                <span><?php echo $vaccination['vac_dose']; ?></span>
                                                            </td>
                                                            <td class="third_td">
                                                                <label><?php echo $vaccination['status']; ?></label>
                                                                <span>Due-<?php echo $vaccination['vac_date']; ?></span>
                                                                <span>Give-<?php echo $vaccination['given_date']; ?></span>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            <?php } ?>
                                        </div>
                                        <a class="carousel-control-prev" href="#carouselExampleInterval" role="button" data-slide="prev">
                                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                            <span class="sr-only">Previous</span>
                                        </a>
                                        <a class="carousel-control-next" href="#carouselExampleInterval" role="button" data-slide="next">
                                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                            <span class="sr-only">Next</span>
                                        </a>
                                    </div>
                                <?php } ?>


                            </td>
                        </tr>

                    </table>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn-warning btn VacDetailClose" data-dismiss="modal">Cancel</button>

                <?php if(in_array($user_role,array('ADMIN','DOCTOR','STAFF'))){ ?>
                    <button type="submit" data-ci="<?php echo base64_encode($child_id); ?>" data-id="<?php echo base64_encode($vac_id); ?>" class="btn-info btn vac_update_btn" data-label="<?php echo $label = ($data['status']=='DONE')?'Update Vaccine':'Give Vaccine' ?>" ><?php echo $label; ?></button>
                <?php } ?>
            </div>

        </div>
    </div>
    <style>

        .reschedule_tr{
            background: #ffff5a;

        }
        .reschedule_tr input{
            float: right;
            width: 30px;
            height: 30px;
        }
        .vac_date_input{
            border: none;
            border-bottom: 1px solid;
            border-radius: 0;
            padding: 0 0.3rem;
        }

        .vac_detail_table td{
            padding:0.3rem;
        }
        .vac_detail_table td label{
            font-weight: 600 !important;
        }
        .description_td span{
            display: block;
            width: 100%;
            height: 62px;
            overflow-y: auto;
            border-top: 1px solid #eedfdf;
            border-radius: 0px;
            margin: 0.5rem 0;
        }

         .remark_td textarea{
            display: block;
            width: 100%;
            height: 62px;
            overflow-y: auto;
            border: 1px solid #eedfdf;
            border-radius: 0px;
            margin: 0.5rem 0;
        }


        .vaccination_status_val{
            color: blue;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 5px;
        }
        .vac_image_td img{
            width: 90px;
            height: 90px;
            border: 1px solid #cfc9c9;
            border-radius: 3px;
            padding: 0.1rem;
        }



        #vac_banner .carousel-indicators li {
            width: 7px;
            height: 7px;
            border-radius: 100%;
            background-color: #ccc !important;
        }

        #vac_banner .carousel-indicators li.active {
            background-color: #4aaf27 !important;
        }


        #vac_banner .carousel-indicators {
            bottom: -30px;
        }


        #vac_banner .carousel-control-prev,
        #vac_banner .carousel-control-next{
            display: none;
        }


        #vac_banner .third_td label{
            background: red;
            color: #fff;
            padding: 0.1rem 0.4rem;
            font-size: 0.7rem;
            text-align: center;
            border-radius: 12%;
            float: right;
        }

        #vac_banner{
            margin: 0.8rem 0;
            border-top: 1px solid #fff;
            padding: 0.5rem 0;
            float: left;
            display: block;
            width: 100%;
        }

        .vac_detail_table .slider_tr td{
            background: #eaeaea;
        }

        .vac_detail_table .blank_tr{
            height: 20px;
        }

      

        .datepicker-switch{
            text-align: center !important;
        }
    </style>

    <script type="text/javascript">

    </script>


</div>



