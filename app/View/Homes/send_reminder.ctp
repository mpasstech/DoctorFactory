<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Token Booking</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <meta name="mobile-web-app-capable" content="yes">
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta name="author" content="mengage">
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <meta http-equiv="cache-control" content="no-store"/>
    <meta http-equiv="expires" content="0"/>
    <meta http-equiv="pragma" content="no-cache"/>

    <?php echo $this->Html->script(array('jquery-3.5.1.min.js','popper.min.js','bootstrap4.min.js','loader.js','es6-promise.auto.min.js','sweetalert2.min.js','jquery.maskedinput-1.2.2-co.min.js','jquery-confirm.min.js','moment.js','moment.js','bootstrap-datepicker.min.js')); ?>
    <?php echo $this->Html->css(array( 'bootstrap4.min.css','font-awesome.min.css','sweetalert2.min.css','jquery-confirm.min.css','dropzone.min.css','jquery.typeahead.css','bootstrap-tagsinput.css','bootstrap-datepicker.min.css' ),array("media"=>'all')); ?>


    <style>
        #logo_image{
            width: 50px;
            height: 50px;
            border-radius: 60px;
        }
        .head_table{
            background: blue;
            color: #fff;
            border: 1px solid;
            width: 100%;
            display: block;
        }
        .body_table th, .body_table td{
            padding: 10px 10px;
            border: 1px solid #ececec;

        }
        .message{
            text-align: center;
        }
    </style>
</head>



<body>

<?php if(!empty($data)){ ?>
    <table class="head_table">
        <tr>
            <td style="width: 20%;"><img id="logo_image"  src="<?php echo $data['logo']; ?>" alt="Logo Image" /></td>
            <td style="text-align: center;"><h4 style="text-align: center; width: 100%;display: block;">Reminder</h4></td>
        </tr>
    </table>

    <h4 style="width: 100%;text-align: center;padding: 10px;">Appointment Detail</h4>
    <p style="width: 100%;display: block;text-align: center;">If the doctor forgot to make call you then you can send reminder alert to the doctor from here.</p>
    <table class="body_table" style="width: 100%;">
        <tr>
            <th style="width: 40%;">Doctor's Name</th>
            <td style="width: 60%;"><?php echo $data['doctor_name']; ?></td>
        </tr>
        <tr>
            <th>Patient's Name</th>
            <td><?php echo $data['patient_name']; ?></td>
        </tr>
        <tr>
            <th>Appointment Date</th>
            <td><?php echo date('d-m-Y',strtotime($data['appointment_datetime'])); ?></td>
        </tr>
        <tr>
            <th>Appointment Time</th>
            <td><?php echo date('h:i A',strtotime($data['appointment_datetime'])); ?></td>
        </tr>
        <tr>
            <th>Token</th>
            <td><?php echo $this->AppAdmin->create_queue_number($data); ?></td>
        </tr>
        <?php
            $plus_four_hours =  strtotime("+4 HOURS", strtotime($data['appointment_datetime']));
            $plus_half_hour =  strtotime("+30 MINUTES", strtotime($data['appointment_datetime']));
        ?>

        <?php if(date('Y-m-d',strtotime($data['appointment_datetime'])) == date('Y-m-d') && in_array($data['appointment_status'],array('NEW','CONFIRM','RESCHEDULE'))){ ?>

        <?php if($data['tot_alert_send'] < 2){ ?>
                <tr>
                    <td class="message" colspan="2">You will be able to send alert between
                        <br>
                        <b><?php echo date('h:i A',($plus_half_hour)).' - '.date('h:i A',($plus_four_hours)); ?></b>

                    </td>
                </tr>
                <?php if(strtotime($data['appointment_datetime']) < strtotime(date('Y-m-d H:i'))  ){ ?>
                    <?php if(strtotime(date('Y-m-d H:i')) >= $plus_half_hour  &&  strtotime(date('Y-m-d H:i')) <= $plus_four_hours ){ ?>
                        <tr>
                            <th><?php echo "Alert Left : ". (2 - $data['tot_alert_send']); ?></th>
                            <td><button type="button" class="btn btn-success send_alert">Send Alert</button></td>
                        </tr>
                    <?php }else{ ?>
                        <tr>
                            <td class="message" colspan="2">Sorry, you can not sent reminder now.</td>
                        </tr>
                    <?php } ?>
                <?php }else{ ?>
                    <tr>
                        <td class="message" colspan="2">You will be able to send reminder when you appointment time passed</td>
                    </tr>
                <?php } ?>
        <?php }else{ ?>
            <tr>
                <td class="message" colspan="2">You have sent 2 number of alerts to the doctor. Doctor will call you back soon.</td>
            </tr>
        <?php } ?>





        <?php }else{ ?>
            <tr>
                <td class="message" colspan="2">This token has been expired now</td>
            </tr>
        <?php } ?>
    </table>

<?php } ?>



</body>

<script>
    $( document ).ready(function() {

        $(document).on("click",".send_alert",function(e){
            var $btn =  $(this);
            var text =  $(this).text();
            $.ajax({
                type:'POST',
                url: "<?php echo Router::url('/homes/send_doctor_alert',true); ?>",
                data:{ai:"<?php echo base64_encode($appointment_id); ?>"},
                beforeSend:function(){
                    $($btn).prop('disabled',true).text('Sening..');
                },
                success:function(response){
                    $($btn).prop('disabled',false).text(text);
                    var response = JSON.parse(response);
                    $.alert(response.message);
                    if(response.status == 1){
                        window.location.reload();
                    }
                },
                error: function(data){
                    $($btn).prop('disabled',false).text(text);
                    $.alert('Unable to send request');
                }
            });
        });


    });
</script>
</html>


