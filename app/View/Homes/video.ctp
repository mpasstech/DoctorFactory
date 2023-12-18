<html>
<?php if(!empty($data)){ ?>

    <head id ="row_content" class="row_content" >
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
        <meta name="mobile-web-app-capable" content="yes">
        <meta name="theme-color" content="#7f0b00">
        <meta http-equiv="cache-control" content="no-store"/>
        <meta http-equiv="expires" content="0"/>
        <meta http-equiv="pragma" content="no-cache"/>
        <meta name="author" content="mengage">
        <script>
            var baseUrl = '<?php echo Router::url('/',true); ?>';
        </script>
        <?php echo $this->Html->script(array('jquery-3.5.1.min.js','popper.min.js','bootstrap4.min.js','sweetalert2.min.js')); ?>
        <?php echo $this->Html->css(array( 'bootstrap4.min.css','font-awesome.min.css','sweetalert2.min.css' ),array("media"=>'all')); ?>




        <title>Video</title>
        <style>
            body{
                font-size: 1rem;
                background: #f7f7f7;
            }
            .firs_row{
                border-bottom: 1px solid #dcdbdb;
                padding-bottom: 10px;
                margin-top: 30px;


            }
            .firs_row label{
                text-align: left !important;
            }

            .third_row p{
                font-size: 1.1rem;
                text-align: center;
            }
            .third_row{
                margin-top: 23px;
                border-top: 0px solid #dcdbdb;
                padding-top: 9px;
            }
            .container{
                padding: 0px 0px;
                background: #fff;
                margin: 0px;
                display: block;

                width: 100%;

            }
            .row{
                margin-top: 15px;
                width: 100%;
                margin-right: 0px !important;
                margin-left: 0px !important;
            }
            .swal2-modal{
                padding: 0px 0px 20px 0px !important;
            }
            img{
                width: 80px;
                height: 80px;
                border-radius: 42px;
                position: relative;
                margin: 0;
            }
            .second_row label{
                font-weight: 600;
                text-align: center;
                width: 100%;
                display: block;
            }

            .second_row div{
                text-align: center;
                width: 100%;
                display: block;
            }
            .middle_box{
                border-right: 1px solid;
                border-left: 1px solid;
                border-color: #e4e2e2;
                margin: 0px;
                padding: 0px;
            }

            .success-box .swal2-title{
                font-size: 25px;
                background: #0080ff;
                color: #fff;
                padding: 10px 0px;
            }

            .browser-icon label{
                width: 100%;
                display: block;
            }
            .browser-icon img{
                width: 50px;
                height: 50px;
            }

            .browser-icon{
                background: #e8e8e8;
                padding: 8px 0px;
            }
            .browser-icon p{
                font-weight: 600;
                width: 100%;
                text-align: center;
                padding: 0px 5px;
            }
            
            .blinking{
    			animation:blinkingText 1.2s infinite;
               
			}
@keyframes blinkingText{
    0%{     color: red;    }
    49%{    color: red; }
    60%{    color: transparent; }
    99%{    color:transparent;  }
    100%{   color: red;    }
}


        </style>
    </head>
    <body  ng-app="myApp" id="body">
    <div class="container" >
        <div class="row firs_row">
            <div class="col-3">
                <img src="<?php echo $data['photo']; ?>">
            </div>
            <div class="col-9">
                <h4><?php echo ($request_from=='PATIENT')?$data['doctor_name']:$data['appointment_patient_name']; ?></h4>
                <label><?php echo ($request_from=='PATIENT')?$data['eduction']:$data['patient_mobile']; ?></label>
            </div>
        </div>
        <div class="row second_row">
            <div class="col-4">
                <label>Token</label>
                <div><?php echo $data['queue_number']; ?></div>
            </div>
            <div class="col-4 middle_box">
                <label>Date</label>
                <div><?php echo date('d/m/Y',strtotime($data['appointment_datetime'])); ?></div>
            </div>
            <div class="col-4">
                <label>Time</label>
                <div><?php echo date('h:i A',strtotime($data['appointment_datetime'])); ?></div>
            </div>
        </div>



        <div class="row browser-icon">
            <p class="blinking">Video consultation support only following browsers</p>
            <div class="col-4">
                <label>Chrome</label>
                <img alt="" src="data:image/png;base64,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" />
            </div>
            <div class="col-4 middle_box">
                <label>Firefox</label>
                <img alt="" src="data:image/jpeg;base64,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171//9k=" />
            </div>
            <div class="col-4">
                <label>Safari</label>
                <img alt="" src="data:image/jpeg;base64,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" />
            </div>
        </div>

        <div class="row third_row">
            <div class="col-12">
                <?php if($request_from!='DOCTOR'){ ?>
                    <p class="short_message"></p>

                <?php } ?>

            </div>

        </div>

    </div>
    <script>
        $(function () {

            function disableBack() { window.history.forward() }
            window.onload = disableBack();
            window.onpageshow = function(evt) { if (evt.persisted) disableBack() }


            var type = "<?php echo $request_from; ?>";
            var finish = "<?php echo $finish; ?>";
            var html = $("body").html();
            $("body").html('');
            if(type=='PATIENT'){
                swal({
                    type:'',
                    title: "Video Consultation",
                    html: html,
                    showCancelButton: false,
                    showConfirmButton: false,
                    confirmButtonText: 'Pay Now',
                    customClass:"success-box",
                    allowOutsideClick: false
                });
                var pending =false;

                if(finish=='0'){
                    var timer = setInterval(function () {
                        if(pending==false){
                            $.ajax({
                                type:'POST',
                                url: baseUrl+"homes/check_connection",
                                data:{ai:"<?php echo base64_encode($appointment_id)?>",room:"<?php echo $room; ?>"},
                                beforeSend:function(){
                                    pending =true;
                                },
                                success:function(data){
                                    pending = false;
                                    var data = JSON.parse(data);
                                    $(".short_message").html(data.message);
                                    if(data.api_status == 1){
                                        if(data.status ='JOINED'){
                                            pending =true;
                                            joinRoom();
                                        }
                                    }
                                },
                                error: function(data){
                                    pending = false;
                                }
                            });
                        }
                    },3000);
                }else{
                    swal({
                        type:'success',
                        title: "Video Call Ended",
                        html: "Thankyou for connect with us",
                        showCancelButton: false,
                        showConfirmButton: false,
                        customClass:"ssuccess-box",
                        allowOutsideClick: false
                    });
                }
            }else{
                 swal({
                    type:'',
                    title: "Video Consultation",
                    html: html,
                    showCancelButton: false,
                    showConfirmButton: true,
                    confirmButtonText: 'Start',
                    customClass:"success-box",
                    allowOutsideClick: false
                }).then(function (result) {
                     $.getJSON("<?php echo Router::url('/homes/send_link/'.base64_encode($appointment_id),true)?>",
                         function(data) {
                             joinRoom();
                     });

                });
            }

            function joinRoom(){
                var video_url = "<?php echo $video_base; ?>";
                setTimeout(function () {
                   window.location.href = video_url;
                },100);
            }

        });

    </script>
    </body>
<?php }else{ echo "This link has been expired";die; }  ?>
<html>


