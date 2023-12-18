<html>
<head id ="row_content" class="row_content" >
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <meta name="mobile-web-app-capable" content="yes">


    <meta http-equiv="cache-control" content="no-store"/>
    <meta http-equiv="expires" content="0"/>
    <meta http-equiv="pragma" content="no-cache"/>

    <?php echo $this->Html->script(array('jquery-3.5.1.min.js','bootstrap4.min.js','popper.min.js','sweetalert2.min.js','jquery.maskedinput-1.2.2-co.min.js','jquery-confirm.min.js','moment.js', 'timepicker.min.js')); ?>
    <?php echo $this->Html->css(array( 'bootstrap4.min.css','font-awesome.min.css','sweetalert2.min.css','jquery-confirm.min.css','timepicker.min.css'),array("media"=>'all')); ?>
    <style>

        .purpose{
            display: block;
            color: #2ba539;
            font-weight: 600;
        }
        .action_btn{
            padding: 4px 3px;
            font-size: 12px;
            float: left;
            display: inline-block;
            margin: 2px;
        }
        .wizard {
            margin: 20px auto;
            background: #fff;
        }

        .wizard .nav-tabs {
            position: relative;
            margin: 40px auto;
            margin-bottom: 0;
            border-bottom-color: #e0e0e0;
        }

        .wizard > div.wizard-inner {
            position: relative;
        }

        .connecting-line {
            height: 2px;
            background: #e0e0e0;
            position: absolute;
            width: 80%;
            margin: 0 auto;
            left: 0;
            right: 0;
            top: 50%;
            z-index: 1;
        }

        .wizard .nav-tabs > li.active > a, .wizard .nav-tabs > li.active > a:hover, .wizard .nav-tabs > li.active > a:focus {
            color: #555555;
            cursor: default;
            border: 0;
            border-bottom-color: transparent;
        }

        span.round-tab {
            width: 70px;
            height: 70px;
            line-height: 70px;
            display: inline-block;
            border-radius: 100px;
            background: #fff;
            border: 2px solid #e0e0e0;
            z-index: 2;
            position: absolute;
            left: 0;
            text-align: center;
            font-size: 25px;
        }
        span.round-tab i{
            color:#555555;
        }
        .wizard li.active span.round-tab {
            background: #fff;
            border: 2px solid #5bc0de;

        }
        .wizard li.active span.round-tab i{
            color: #5bc0de;
        }

        span.round-tab:hover {
            color: #333;
            border: 2px solid #333;
        }

        .wizard .nav-tabs > li {
            width: 25%;
        }

        .wizard li:after {
            content: " ";
            position: absolute;
            left: 46%;
            opacity: 0;
            margin: 0 auto;
            bottom: 0px;
            border: 5px solid transparent;
            border-bottom-color: #5bc0de;
            transition: 0.1s ease-in-out;
        }

        .wizard li.active:after {
            content: " ";
            position: absolute;
            left: 46%;
            opacity: 1;
            margin: 0 auto;
            bottom: 0px;
            border: 10px solid transparent;
            border-bottom-color: #5bc0de;
        }

        .wizard .nav-tabs > li a {
            width: 70px;
            height: 70px;
            margin: 20px auto;
            border-radius: 100%;
            padding: 0;
        }

        .wizard .nav-tabs > li a:hover {
            background: transparent;
        }

        .wizard .tab-pane {
            position: relative;
            padding-top: 10px;
        }

        .wizard h3 {
            margin-top: 0;
        }

        section{
            width: 100%;
        }


        .container {
            padding: 5% 3%;
        }
        @media( max-width : 585px ) {

            .wizard {
                width: 90%;
                height: auto !important;
            }

            span.round-tab {
                font-size: 16px;
                width: 50px;
                height: 50px;
                line-height: 50px;
            }

            .wizard .nav-tabs > li a {
                width: 50px;
                height: 50px;
                line-height: 50px;
            }

            .wizard li.active:after {
                content: " ";
                position: absolute;
                left: 35%;
            }
        }

        .button-box{
            width: 100%;
            display: block;
            float: left;
        }

        .button-box li{
            float: left;
            width: auto;
            margin: 5px;
            list-style: none;
        }

        .tab-pane .col-sm-12{
            height: 90px;
        }


        .table td, .table th {
            padding: .1rem .3rem;
            vertical-align: top;
            border-top: 1px solid #dee2e6;
            font-size: 0.9rem;
        }

        header{
            background: green;
            color: #fff;
            text-align: center;
        }

        .main_heading{
            border-bottom: 1px solid #dadada;
            padding-bottom: 4px;
        }


        .main_detail{
            float: left;
            width: 100%;
            margin: 0 auto;
            padding-left: 15px !important;
        }

        .main_detail li label{
            font-size: 0.9rem;
            padding: 10px 0px;
            display: block;
            width: 100%;
            float: left;
        }

        .main_detail li{
            list-style: decimal;
            border-bottom: 1px solid #d0d0d0;
            float: left;
            width: 100%;
            margin: 6px 0px;
        }

        .barcode_i{
            font-size: 2rem;
            color: #000;
            border: 1px solid;

            border-radius: 5px;
            height: 40px;
            width: 40px;
            padding: 6px;
        }
        .btn{
            margin: 3px 0px;
        }
        .barcode_btn{
            margin-top: 2px;
            position: absolute;
            margin-left: 10px;
        }
    </style>
</head>
<body>
<header>
    <h1>QuToT</h1>
</header>
<div class="container">
    <div class="main_content" style="width: 100%;float: left;">
        <div class="row">
            <div class="col-12" style="text-align: center;">

                <h6>Your web apps are ready with your <b style="display: block;font-size: 1.5rem;"><?php echo $app_data[0]['app_name']; ?></b></h6>
            </div>
        </div>
        <div class="row">
            <div class="col-12">

                <?php

                $data = $app_data[0];

                ?>
                <ul class="main_detail">
                    <li>
                        <h5>QuTOT Walk-in QR Code</h5>
                        <span>
                        <a target="_blank" href="<?php echo Router::url('/qutot/qutot_user_booking/'.base64_encode($thin_app_id),true)?>" class="btn btn-info"><i class="fa fa-list"></i> User Token Booking </a>
                        <a target="_blank" href="<?php echo Router::url('/qutot_row/qr-code-generate.php?t='.base64_encode($thin_app_id),true)?>" class="barcode_btn"><i class="fa fa-qrcode barcode_i"></i></a>
                        </span>

                        <span class="purpose">Purpose : For users  to book token on site </span>
                        <label>The above interface will only be used by users only.</label>
                    </li>
                    <li>
                        <h5>QuTOT Schedule</h5>
                        <span>
                            <a  class="btn btn-xs btn-success" target="_blank" href="<?php echo $this->AppAdmin->short_url(Router::url('/qutot/web_app/'.$data['thinapp_id'].'/?t='.base64_encode($data['id']),true),$data['thinapp_id'] );?>"><i class="fa fa-android"></i> Web App Link</a>
                            <a href="javascript:void(0);" data-href="<?php echo $this->AppAdmin->short_url(Router::url('/qutot/web_app/'.$data['thinapp_id'].'/?t='.base64_encode($data['id']),true),$data['thinapp_id'] );?>" class="btn btn-success whatsapp"><i class="fa fa-whatsapp"></i> Share </a>
                        </span>
                        <span class="purpose">Purpose : For users  to book token  from home </span>

                        <label>The above interface will only be used by users only to book Token online from home or anywhere.</label>
                    </li>
                    <li>
                        <h5>QuTOT Counter</h5>
                        <span><a  class="btn btn-xs btn-info" target="_blank" href="<?php echo Router::url('/qutot/dti/'.base64_encode($data['thinapp_id']).'/'.base64_encode($data['id']),true)?>"><i class="fa fa-android"></i> DTI Link</a></span>
                        <span class="purpose">Purpose : For Counter 1 to manage token on Mobile / Desktop to  manage Queue  </span>
                        <label>You can also upload your logo, make changes your schedule and availability etc with the option of profile settings on upper right corner.</label>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function () {





        function CheckLogin(password,convert){
            var $btn =  $("#login_btn");
            var data = {
                si:"<?php echo base64_encode($app_data[0]['id']); ?>",
                p:password,
                convert:convert
            }
            $.ajax({
                type:'POST',
                url: "<?php echo Router::url('/qutot/checkLogin',true); ?>",
                data:data,
                beforeSend:function(){
                    $($btn).prop('disabled',true).text('Login...');
                },
                success:function(response){
                    $($btn).prop('disabled',false).text('Login');
                    var response = JSON.parse(response);
                    if(response.status == 1){
                        $(".main_content").show();
                        login_dialog.close();
                    }else{
                        $.alert('You have enter wrong password');
                    }
                },
                error: function(data){
                    $($btn).prop('disabled',false).text('Login');
                    $.alert('Something, went wrong on server');
                }
            });
        }


        var baseurl = "<?php echo Router::url('/',true); ?>";

        $(document).on("click", '.whatsapp', function () {
            if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
                var sText = "Now you can book token via this application";
                var sUrl = $(this).attr('data-href');
                var sMsg = encodeURIComponent(sText) + " - " + encodeURIComponent(sUrl);
                var whatsapp_url = "whatsapp://send?text=" + sMsg;
                window.location.href = whatsapp_url;
            }
            else {
                alert("Whatsapp client not available.");
            }
        });

        var content='';



    });
</script>
</body>

</html>


