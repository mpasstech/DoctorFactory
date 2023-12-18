<!DOCTYPE>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="theme-color" content="#7f0b00">
    <meta http-equiv="cache-control" content="no-store"/>
    <meta http-equiv="expires" content="0"/>
    <meta http-equiv="pragma" content="no-cache"/
    <meta name="author" content="mengage">
    <script>
        var baseurl = '<?php echo Router::url('/',true); ?>';
    </script>
    <?php echo $this->Html->script(array('jquery-3.5.1.min.js','popper.min.js','bootstrap4.min.js','font-awesome.js')); ?>
    <?php echo $this->Html->css(array( 'bootstrap4.min.css','font-awesome.min.css'),array("media"=>'all')); ?>
    <title>Patient History</title>

</head>

<style>

    /* width */
    ::-webkit-scrollbar {
        width: 3px;
    }

    /* Track */
    ::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    /* Handle */
    ::-webkit-scrollbar-thumb {
        background: #888;
    }

    /* Handle on hover */
    ::-webkit-scrollbar-thumb:hover {
        background: #555;
    }


    .upper_row li {
        list-style: none;
        line-height: 3px !important;
        color: #04a6f0;
        border-top: 4px solid #ddd !important;
        font-size: 10px;
        font-weight: bold;
    }


    body{
        background:#f9f9f9;
    }

    .col-12{

        padding: 0.8rem 0.5rem;
    }

    li{
        list-style: none;
        float: left;

    }

    .info_icon_div{
          text-align: center;
        color: green;
        font-size: 2.5rem;
        border-radius: 63px;
        border: 1px solid;
        margin: 0 auto;
        width: 70px;
        margin-top: 7px;
        font-weight: 100;
        height: 70px;
    }

    .name_heading, .uhid_heading, .gender_heading{
        text-align: center;
        width: 100%;
        display: block;
    }

    .card-header{
        background: #fff;
    }
    .card-header .icon_li{
        float: left;
        width: 20% !important;
        font-size: 1.8rem;
        text-align: left;
    }

    .card-header li{
        width: 80%;
    }

    .card-header .title{
        font-weight: 600;
        font-size: 1rem;

    }

    .card-body li{
        width: 100%;
        display: block;
    }
    .card-body .btn{
        float: right;
        font-size: 0.8rem;
        line-height: 0.3;
    }

    .card-body li span{
        font-weight: 600;
        min-width: 22%;
        display: block;
        float: left;
    }
    .file_btn{
        outline: none;
    }
    .card-body .btn{
        height: 27px;
    }
    .card-body .btn{
        display: none;
    }
    @media (min-width: 10px) and (max-width: 220px) {




    }


</style>

<body>
<div class="main_box">

    <div class="card header_card">
        <div class="col-12 info_icon_div">
            <i class="fa fa-info"></i>
        </div>
        <div class="col-12">
            <h3 class="name_heading"><?php echo $patientDetail['name']; ?></h3>

            <h6 class="uhid_heading" >UHID :
                <?php echo $patientDetail['uhid']; ?> </h6>

            <h6 class="gender_heading">Gender :
                <?php echo $patientDetail['gender']; ?></h6>
        </div>
    </div>
    
     <?php if(!empty($patientDetail['flag']) || !empty($patientDetail['allergy']) || !empty($patientDetail['medical_history'])){ ?>
    <div class="col-12">
        <div class="card">
            <div class="card-header"><h3>Patient Info</h3></div>
            <div class="card-body">
            	  <?php
                    $display = '';
                    $tmp = explode("###",$patientDetail['notes']);
                    if(!empty($tmp[0])){
                        $display = $tmp[0];
                        if(isset($tmp[1])){
                            $display .= "<br> From :- ( ".$tmp[1]." )";
                        }
                    }

                ?>

                <?php if(!empty($display)){ ?>
                    <h5>Symptoms</h5>
                    <p class="allergy"><?php echo $display; ?></p>
                <?php } ?>
                <?php if(!empty($patientDetail['flag'])){ ?>
                    <h5>Flags</h5>
                    <p class="allergy"><?php echo $patientDetail['flag']; ?></p>
                <?php } ?>
                <?php if(!empty($patientDetail['allergy'])){ ?>
                    <h5>Allergy</h5>
                    <p class="allergy"><?php echo $patientDetail['allergy']; ?></p>
                <?php } ?>
                <?php if(!empty($patientDetail['medical_history'])){ ?>
                  <h5>Disease/Operation History</h5>
                  <p class="allergy"><?php echo $patientDetail['medical_history']; ?></p>
                <?php } ?>
            </div>
        </div>
    </div>
    <?php } ?>

    <?php foreach($dataToSend AS $val){ ?>
        <div class="col-12">

            <?php if($val['type'] == 'APPOINTMENT'){ ?>
                <div class="card">
                    <div class="card-header">
                        <li class="icon_li"><i class="fa fa fa-stethoscope"></i> </li>
                        <li class="title">Appointment</li>
                        <li class="header_date"><?php echo date('d/m/Y h:i A',strtotime($val['appointment_datetime'])); ?></li>
                    </div>
                    <div class="card-body">
                        <li><span>Doctor:</span> <?php echo $val['doc_name']; ?></li>
                        <li><span>Status:</span> <?php echo ucfirst(strtolower($val['status'])); ?></li>
                        <?php
                        if($val['consulting_type']=='OFFLINE'){
                            $val['consulting_type'] = "Hospital/Clinic Visit";
                        }
                        ?>
                        <li><span>Type:</span> <?php echo ucfirst(strtolower($val['consulting_type']))." Consulting"; ?></li>
                        <?php if(!empty($val['reason_of_appointment'])){ ?>
                            <li><span>Remark:</span> <?php echo $val['reason_of_appointment']; ?></li>
                        <?php } ?>



                    </div>
                </div>
            <?php }
            else if($val['type'] == 'PATIENT'){ ?>


                <div class="card">
                    <div class="card-header">
                        <li class="icon_li"><i class="fa fa-address-card-o" aria-hidden="true"></i> </li>
                        <li  class="title">Registration</li>
                        <li class="header_date"><?php echo date('d/m/Y h:i A',strtotime($val['created'])); ?></li>

                    </div>
                    <div class="card-body">
                        <li><span>Name:</span> <?php echo $val['name']; ?></li>
                        <li><span>UHID:</span> <?php echo $val['uhid']; ?></li>
                        <li><span>Mobile:</span> <?php echo $val['mobile']; ?></li>

                        <?php if(!empty($val['age'])){ ?>
                            <li><span>Age:</span> <?php echo @$val['age']; ?></li>
                        <?php } ?>

                    </div>
                </div>


            <?php  }
            else if($val['type'] == 'BILLING'){  ?>



                <div class="card">
                    <div class="card-header">
                        <?php $billingType = 'General'; if($val['is_opd'] == 'Y'){$billingType = 'OPD';}else if($val['is_advance'] == 'Y'){$billingType = 'IPD Advance Deposit';}else if($val['is_settlement'] == 'Y'){$billingType = 'IPD Settlement';}else if($val['is_package'] == 'Y'){$billingType = 'Package';}else if($val['is_emergency'] == 'YES'){$billingType = 'Emergecy';}else if($val['is_expense'] == 'Y'){$billingType = 'IPD Expense';}?>
                        <li class="icon_li"><i class="fas fa-receipt"></i> </li>
                        <li  class="title">Billing (<?php echo $billingType; ?>)</li>
                        <li class="header_date"><?php echo date('d/m/Y h:i A',strtotime($val['created'])); ?></li>


                    </div>
                    <div class="card-body">
                        <li><span>Amount:</span> <i class="fa fa-inr"></i> <?php echo $val['total_amount']; ?>
                            <br>
                            <?php echo $val['service_name']; ?>
                        </li>
                        <li>
                            <?php if($val['is_opd'] == 'Y'){ ?>
                                <a class="btn btn-success btn-xs" href="<?php echo Router::url('/').'app_admin/print_invoice/'.base64_encode($val['id']); ?>" target="_blank"><i class="fa fa-edit"></i> Receipt</a>
                            <?php }else{
                                if($val['is_advance'] == 'Y'){ ?>
                                    <a class="btn btn-success btn-xs" href="<?php echo Router::url('/').'app_admin/print_invoice/'.base64_encode($val['id'])."/IAD"; ?>" target="_blank"><i class="fa fa-edit"></i> Receipt</a>
                                <?php }else if($val['is_settlement'] == 'Y'){ ?>
                                    <a class="btn btn-success btn-xs" href="<?php echo Router::url('/').'app_admin/print_invoice_non_opd_settlement/'.base64_encode($val['id']); ?>" target="_blank"><i class="fa fa-edit"></i> Receipt</a>
                                <?php }else{ ?>
                                    <a class="btn btn-success btn-xs" href="<?php echo Router::url('/').'app_admin/print_invoice/'.base64_encode($val['id'])."/IPD"; ?>" target="_blank"><i class="fa fa-edit"></i> Receipt</a>
                                <?php }
                            } ?>


                        </li>
                    </div>
                </div>



            <?php }
            else if($val['type'] == 'FILE'){ ?>


                <div class="card">
                    <div class="card-header">
                        <li class="icon_li"><i class="fas fa-prescription"></i></li>

                        <li  class="title"><?php echo ucfirst( strtolower($val['category_name'])); ?></li>
                        <li class="header_date"><?php echo date('d/m/Y h:i A',strtotime($val['created'])); ?></li>
                    </div>
                    <div class="card-body">
                        <li>

                            <?php if(!empty($val['file_name'])){ ?>
                                <?php echo "New ".strtolower($val['category_name'])." added"; ?>
                            <?php } ?>

                            <?php if(!empty($val['tags'])){ ?>
                                <span>Tags:</span> <?php echo $val['tags']; ?>
                            <?php } ?>
                        </li>
                        <li><button type="button" class="btn btn-success btn-xs file_btn" data-id="<?php echo $val['id']; ?>" ><i class="fa fa-eye"></i> View </button></li>

                    </div>
                </div>





            <?php  }
            else if($val['type'] == 'IPD_ADMIT'){ ?>



                <div class="card">
                    <div class="card-header">
                        <li class="icon_li"><i class="fa fa-bed" aria-hidden="true"></i> </li>
                        <li  class="title">IPD Admit</li>
                        <li class="header_date"><?php echo date('d/m/Y h:i A',strtotime($val['admit_date'])); ?></li>


                    </div>
                    <div class="card-body">
                        <li style="text-align: left !important;" colspan="1">

                            <span>IPD Reg. ID:</span> <?php echo $val['ipd_unique_id']; ?>
                            <br>
                            <span>Ward/Room:</span><?php echo $val['ward']; ?>
                        </li>
                        <li ><span>Doctor:</span> <?php echo $val['doc_name']; ?></li>
                    </div>
                </div>



            <?php  }
            else if($val['type'] == 'IPD_DISCHARGE'){ ?>

                <div class="card">
                    <div class="card-header">
                        <li class="icon_li"><i class="fab fa-accessible-icon"></i> </li>


                        <li  class="title">IPD Discharge</li>
                        <li class="header_date"><?php echo date('d/m/Y h:i A',strtotime($val['discharge_date'])); ?></li>

                    </div>
                    <div class="card-body">
                        <li><span>IPD Reg. ID:</span> <?php echo $val['ipd_unique_id']; ?></li>
                        <li>

                            <a class="btn btn-xs btn-success" target="_blank" href="<?php echo Router::url('/app_admin/discharge_patient/',true).base64_encode($val['hospital_ipd_id']).'/'.base64_encode($val['id']); ?>">
                                <i class="fa fa-eye"></i> Report
                            </a>
                        </li>
                    </div>
                </div>



            <?php  }
            else if($val['type'] == 'EMERGENCY'){ ?>
                <div class="card">
                    <div class="card-header">
                        <li class="icon_li"><i class="fa fa-bed" aria-hidden="true"></i> </li>
                        <li  class="title">Emergency Admit</li>
                        <li class="header_date"><?php echo date('d/m/Y h:i A',strtotime($val['admit_date'])); ?></li>

                    </div>
                    <div class="card-body">
                        <li><span>Admit Date: </span><?php echo date('d/m/Y h:i A',strtotime($val['admit_date'])); ?></li>
                    </div>
                </div>



            <?php  }  ?>
        </div>
    <?php } ?>


</div>
<div class="modal fade" id="show_file" role="dialog"></div>
<script>



    function is_desktop(){
        if(/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
            return false;
        }return true;
    }

    if(is_desktop()){
        $(".btn").show();
    }

    $(".file_btn").show();




    var baseurl = '<?php echo Router::url('/',true); ?>';
    $(document).on("click",".file_btn",function(){
        var fileID = $(this).attr('data-id');
        var $thisButton = $(this);
        var clone = $(this).clone();

        $.ajax({
            url: baseurl+'tracker/get_show_drive_file_modal',
            data:{fileID:fileID},
            type:'POST',
            beforeSend:function(){
                $thisButton.button('loading').html('<i class="fa fa-spinner fa-pulse fa-1x fa-fw"></i>  Wait..');
            },
            success: function(result){
                $($thisButton).replaceWith(clone);
                $("#show_file").html(result).modal('show');
            },
            error: function(err){
                $thisButton.button('reset');
            }
        });
    });
</script>
</body>
</html>