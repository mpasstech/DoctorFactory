<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Patient History</title>
    <?php
    echo $this->Html->css(array(
        'bootstrap.min.css',
        'bootstrap-theme.min.css',
        'font-awesome.min.css',
        'style.css',
        'custom.css',
        'file_module.css',
        'bootstrap-treeview.min.css'
    ),array("media"=>'all','fullBase' => true));

    echo $this->Html->script(array(
        'jquery.js',
        'bootstrap.min.js',
        'bootstrap-treeview.min.js'
    ),array('fullBase' => true));
    ?>
</head>

<style>
    .upper_row td {

        line-height: 3px !important;
        color: #04a6f0;
        border-top: 4px solid #ddd !important;
        font-size: 10px;
        font-weight: bold;
    }

    .table{
        background: #fff;
    }
    body{
        background:#ddd;
    }
    .table-curved {
        border-collapse: separate;
        border: solid #ddd 1px;
        border-radius: 6px;
        border-left: 0px;
        border-top: 0px;
    }
    .table-curved > thead:first-child > tr:first-child > th {
        border-bottom: 0px;
        border-top: solid #ddd 1px;
    }
    .table-curved td, .table-curved th {
        /*border-left: 1px solid #ddd;*/
        border-top: 1px solid #ddd;
    }
    .table-curved > :first-child > :first-child > :first-child {
        border-top-left-radius: 6px;
    }
    .table-curved > :first-child > :first-child > :last-child {
        border-top-right-radius: 6px;
    }
    .table-curved > :last-child > :last-child > :first-child {
        border-bottom-left-radius: 6px;
    }
    .table-curved > :last-child > :last-child > :last-child {
        border-bottom-right-radius: 6px;
    }
    .upper_row > :last-child {
        text-align: right;
    }
    .lower_row > :last-child {
        text-align: right;
    }
    th{
        color:#439943;
    }
    .modal-header {
        background: #449a44;
        color: #FFFFFF;
    }
    .modal-header .close {
       color: #FFFFFF;
    }


    @media (min-width: 10px) and (max-width: 220px) {

            .main_box{
                padding-left: 10px;
                padding-right: 10px;
            }

        .upper_row > :last-child {
            text-align: right;
            font-size: 10px;
            line-height: 0.9 !important;
        }.upper_row > :first-child {

            font-size: 10px;

        }

        .low_row > :last-child {
            text-align: right;
            font-size: 12px;
            line-height: 0.9 !important;
        }.lower_row > :last-child {
             font-size: 12px;

         }

        .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
            padding: 5px 0px;
            line-height: 1.428571429;
            font-size: 11px;
            vertical-align: middle;
            border-top: 1px solid #ddd;
        }
        .btn, .btn:hover, .btn:active, .btn:focus{
            font-size: 11px;
            padding: 1px 2px;
            background: no-repeat;
            color: red;
            border: none;
        }


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

    }


</style>

<body>
    <div class="container"  style="width: 100%; padding: 0px;">
        <div class="row">
            <div class="main_box col-md-12">

                <table class="table table-curved">
                    <thead>
                    <tr class="upper_row">
                        <td>Name</td>
                        <td>UHID</td>
                    </tr>
                    <tr>
                        <th><?php echo $patientDetail['name']; ?></th>
                        <th style="text-align:right;"><?php echo $patientDetail['uhid']; ?></th>
                    </tr>
                    </thead>
                    <tbody>

                    <?php foreach($dataToSend AS $val){ ?>
                        <?php if($val['type'] == 'APPOINTMENT'){ ?>
                            <tr class="upper_row">
                                <td>Appointment</td>
                                <td><?php echo date('d/m/Y h:i A',strtotime($val['appointment_datetime'])); ?></td>
                            </tr>
                            <tr class="lower_row">
                                <td>Doctor: <?php echo $val['doc_name']; ?></td>
                                <td>Status: <?php echo $val['status']; ?></td>
                            </tr>
                        <?php }
                        else if($val['type'] == 'PATIENT'){ ?>

                            <tr class="upper_row">
                                <td>Registration</td>
                                <td><?php echo date('d/m/Y h:i A',strtotime($val['created'])); ?></td>
                            </tr>
                            <tr class="lower_row">
                                <td>Name: <?php echo isset($val['first_name'])?$val['first_name']:$val['child_name']; ?></td>
                                <td>UHID: <?php echo $val['uhid']; ?></td>
                            </tr>

                        <?php  }
                        else if($val['type'] == 'BILLING'){  ?>

                            <tr class="upper_row">
                                <?php $billingType = 'General'; if($val['is_opd'] == 'Y'){$billingType = 'OPD';}else if($val['is_advance'] == 'Y'){$billingType = 'IPD Advance Deposit';}else if($val['is_settlement'] == 'Y'){$billingType = 'IPD Settlement';}else if($val['is_package'] == 'Y'){$billingType = 'Package';}else if($val['is_emergency'] == 'YES'){$billingType = 'Emergecy';}else if($val['is_expense'] == 'Y'){$billingType = 'IPD Expense';}?>
                                <td>Billing (<?php echo $billingType; ?>)</td>
                                <td><?php echo date('d/m/Y h:i A',strtotime($val['created'])); ?></td>
                            </tr>
                            <tr class="lower_row">
                                <td>Amount: <i class="fa fa-inr"></i> <?php echo $val['total_amount']; ?>
                                <br>
                                    <?php echo $val['service_name']; ?>
                                </td>
                                <td>
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


                                </td>
                            </tr>

                        <?php }
                        else if($val['type'] == 'FILE'){ ?>

                            <tr class="upper_row">
                                <td><?php echo ucfirst( strtolower($val['category_name'])); ?></td>
                                <td><?php echo date('d/m/Y h:i A',strtotime($val['created'])); ?></td>
                            </tr>
                            <tr class="lower_row">
                                <td>
                                    <?php if(!empty($val['tags'])){ ?>
                                        Tags: <?php echo $val['tags']; ?>
                                    <?php } ?>
                                </td>
                                <td><button class="btn btn-success btn-xs file_btn" data-id="<?php echo $val['id']; ?>" ><i class="fa fa-eye"></i> View </button></td>
                            </tr>

                        <?php  }
                        else if($val['type'] == 'IPD_ADMIT'){ ?>

                            <tr class="upper_row">
                                <td>IPD Admit</td>
                                <td><?php echo date('d/m/Y h:i A',strtotime($val['admit_date'])); ?></td>
                            </tr>
                            <tr class="lower_row">
                                <td style="text-align: left !important;" colspan="1">IPD Reg. ID: <?php echo $val['ipd_unique_id']; ?>
                                <br>
                                    Ward/Room:&nbsp; <?php echo $val['ward']; ?>
                                </td>
                                <td  colspan="1">Doctor: <?php echo $val['doc_name']; ?></td>
                            </tr>

                        <?php  }
                        else if($val['type'] == 'IPD_DISCHARGE'){ ?>

                            <tr class="upper_row">
                                <td>IPD Discharge</td>
                                <td><?php echo date('d/m/Y h:i A',strtotime($val['discharge_date'])); ?></td>
                            </tr>
                            <tr class="lower_row">
                                <td colspan="1" style="text-align: left !important;">IPD Reg. ID: <?php echo $val['ipd_unique_id']; ?></td>
                                <td colspan="1">

                                    <a class="btn btn-xs btn-success" target="_blank" href="<?php echo Router::url('/app_admin/discharge_patient/',true).base64_encode($val['hospital_ipd_id']).'/'.base64_encode($val['id']); ?>">
                                        <i class="fa fa-eye"></i> Report
                                    </a>
                                </td>

                            </tr>

                        <?php  }
                        else if($val['type'] == 'EMERGENCY'){ ?>

                            <tr class="upper_row">
                                <td>Emergency Admit</td>
                                <td><?php echo date('d/m/Y h:i A',strtotime($val['admit_date'])); ?></td>
                            </tr>
                            <tr class="lower_row">
                                <td colspan="2" style="text-align: left !important;">Admit Date: <?php echo date('d/m/Y h:i A',strtotime($val['admit_date'])); ?></td>
                            </tr>

                        <?php  }  ?>
                    <?php } ?>

                    </tbody>
                </table>
            </div>
        </div>

        <div class="modal fade" id="show_file" role="dialog"></div>
    </div>
<script>
    var baseurl = '<?php echo Router::url('/',true); ?>';
    $(document).on("click",".file_btn",function(){

        var fileID = $(this).attr('data-id');
        var thisButton = $(this);
        $.ajax({
            url: baseurl+'app_admin/get_show_drive_file_modal',
            data:{fileID:fileID},
            type:'POST',
            beforeSend:function(){
                $(thisButton).button('loading').html('Loading...');
            },
            success: function(result){
                $(thisButton).button('reset');
                $("#show_file").html(result).modal('show');
            },
            error: function(err){
                $(thisButton).button('reset');
            }
        });
    });
</script>
</body>
</html>