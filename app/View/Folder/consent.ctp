<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
   <meta charset="UTF-8">
   <meta name="mobile-web-app-capable" content="yes">
   <meta name="theme-color" content="#7f0b00">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <title>Consent</title>
	<?php echo $this->Html->script(array('jquery.js','es6-promise.auto.min.js','sweetalert2.min.js','jquery.signaturepad.min.js')); ?>
	<?php echo $this->Html->css(array('sweetalert2.min.css','bootstrap.min.css','jquery.signaturepad.css')); ?>
    <link href="<?php echo SITE_PATH;?>css/font-awesome.min.css" rel="stylesheet">

<style>
    .swal2-title{font-size: 18px !important;}
    .error_message{
        text-align: center;
    }

        .swal2-title{font-size: 18px !important;}
        .login-page {
            width: 100%;
            padding: 1% 0 0;
            margin: auto;
            float: left;
        }
        .form {
            position: relative;
            z-index: 1;
            background: #FFFFFF;
            max-width: 100%;
            margin: 0 auto 0px;
            padding: 10px;
            text-align: center;
            box-shadow: 0 0 20px 0 rgba(0, 0, 0, 0.2), 0 5px 5px 0 rgba(0, 0, 0, 0.24);
            width: 100%;
            float: left;
        }
        .form input {
            font-family: "Roboto", sans-serif;
            outline: 0;
            background: #f2f2f2;
            width: 100%;
            border: 0;
            margin: 0 0 15px;
            padding: 15px;
            box-sizing: border-box;
            font-size: 14px;
        }
        .form button {
            font-family: "Roboto", sans-serif;
            text-transform: uppercase;
            outline: 0;
            background: #4CAF50;
            width: 100%;
            border: 0;
            padding: 15px;
            color: #FFFFFF;
            font-size: 14px;
            -webkit-transition: all 0.3 ease;
            transition: all 0.3 ease;
            cursor: pointer;
            margin-top: 30px;;
        }
        .form button:hover,.form button:active,.form button:focus {
            background: #43A047;
        }
        .form .message {
            margin: 15px 0 0;
            color: #4e0505;
            font-size: 16px;
            text-align: left;
            width: 100%;
            float: left;
        }
        .form .message a {
            color: #4CAF50;
            text-decoration: none;
        }

        body {
            background: #76b852; /* fallback for old browsers */
            background: -webkit-linear-gradient(right, #76b852, #8DC26F);
            background: -moz-linear-gradient(right, #76b852, #8DC26F);
            background: -o-linear-gradient(right, #76b852, #8DC26F);
            background: linear-gradient(to left, #76b852, #8DC26F);
            font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        .condition{
            padding: 8px 6px;
            border: 1px solid #2fca1c;
            border-radius: 2px;
            margin: 4px 0px;
            text-align: left;
            font-size: 14px;
            color: black;
            min-height: 300px;
             height: auto !important;
            display: block;
            float: left;
            clear: both;
            overflow: auto;
            border-left-style: groove;
            width: 100%;
            word-wrap: break-word;
            overflow-y: none;
        }
        .title{
            border-bottom: 1px dashed #072308;
            font-size: 17px;
            font-weight: 600;
            padding: 0px !important;
            margin: 5px 0px;
            text-align: center;
            float: left;
            width: 100%;
        }
        .radio_div{
            padding: 3px 2px;
            border: none;
            position: relative;
            float: left;
            width: 100%;
            margin: 6px 2px;
            text-align: left;
        }
        .radio_div label{
            float: left;
            width: 30%;
            margin: 0px;
            padding: 3px;
            position: relative;
            font-size: 18px;;
        }
        .radio_div input{
            width: auto !important;
            margin: 0px 3px !important;
        }.radio_div span{
             margin-top: -5px !important;
             position: absolute;
         }

        .form .head_time {
            margin: 0px;
            color: #5d9e39;
            font-size: 14px;
            text-align: right;
            float: left;
            width: 50%;
        } .form .head_name {
              float: left;
              font-size: 14px;
              text-align: left;
              width: 50%;
              margin: 0px;
          }

        .error_message{
            font-size: 21px;
            text-align: center;
            color: #fff;
            margin: 24px 0px;
        }
        .swal2-title{
            font-size: 17px !important;
        }

        .draw a{
            text-decoration: none;
            cursor: none;
            float: left;
        }
        .clear a{
            float: right;
        }
        .sigNav li{
            width: 50%;
            text-align: left;
        }
        .sigPad{
            width: 100%;
            margin: 0 auto;
            position: relative;
            float: left;
            height: 106px;
        }
        .sigWrapper{
            width: 100%;
            height: 90px !important;
        }
        .error_pad{
            color: red;
        }
        .form{
            float: left;
        }

        .action_img{
            float: right;
            position: absolute;
            bottom: 100px;
            right: 0;
            z-index: 9999;
            width: 20%;
        }

        .prop, .prop p{
            text-align: left !important;
        }
        .sig_img{
            border-bottom: 1px dashed;
            position: relative;
            width: 100%;
            float: left;
        }
    .sigPad{
        width: 100% !important;
    }
    </style>
</head>
	<body>
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding: 1px 0px;">
    <?php if($user_type=='DOCTOR'){ ?>

        <div data-val="<?php echo base64_encode($consent_data['id']); ?>" class="signature_content" style="display: block">
            <div  class="login-page" style="display: block">

                <div class="form">
                    <form class="login-form">
                        <div class="main_head_div">
                            <p class="head_name">
                                <i class="fa fa-user"></i> <?php echo $consent_data['receiver_mobile']; ?> <br>
                                <b style="color: <?php echo ($consent_data['action_type']=='AGREE' || $consent_data['receiver_view_status'] =='SEEN')?'green':'red'; ?>;"><i class="fa fa-eye"></i> <?php echo ($consent_data['action_type']!='PENDING')?$consent_data['action_type']:$consent_data['receiver_view_status']; ?></b>

                            </p>
                            <p class="head_time">
                                <i class="fa fa-calendar"></i> <?php echo date('d M, Y',strtotime($consent_data['created'])); ?><br>
                                <i class="fa fa-clock-o"></i> <?php echo date('H:i',strtotime($consent_data['created'])); ?>
                            </p>
                        </div>

                        <h3 class="title"><?php echo $consent_data['consent_title']; ?></h3>
                        <div class="condition">
                            <?php
                            echo  preg_replace('~(?<=\s)\s~', '&nbsp;', nl2br($consent_data['consent_message']));
                            ?>
                        </div>

                        <?php if($consent_data['action_type'] == 'AGREE'){ ?>
                            <img src="<?php echo SITE_PATH;?>thinapp_images/agree.png" class="action_img">
                        <?php }else if($consent_data['action_type'] == 'DISAGREE'){ ?>
                            <img src="<?php echo SITE_PATH;?>thinapp_images/disagree.png" class="action_img">
                        <?php } ?>

                        <?php if($consent_data['action_type'] != "PENDING"){ ?>
                            <div class="sigPad" id="linear" style="margin: 0 auto;"><img class="sig_img" src="<?php echo $consent_data['signature_image']; ?>">
                            </div>
                        <?php } ?>

                        <div class="prop">
                            <?php if($consent_data['action_type'] != "PENDING"){ ?>
                                <p>Consent From : <b><?php echo $consent_data['app_name']; ?></b></p>
                                <p class="footer_time">
                                    <label style="color: <?php echo ($consent_data['action_type'] =='AGREE')?'#5d9e39':'red';?>;">Patient is <?php echo strtolower($consent_data['action_type'])."d";?> for this consent on </label><br>
                                    <i class="fa fa-calendar"></i> <?php echo date('d M, Y',strtotime($consent_data['action_time'])); ?>
                                    <i class="fa fa-clock-o"></i> <?php echo date('h:i A',strtotime($consent_data['action_time'])); ?>
                                </p>
                            <?php } ?>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    <?php }else{ ?>
    <?php if($verify_data == 'SHOW_DIV'){ ?>
        <div data-val="<?php echo base64_encode($consent_data['id']); ?>" class="signature_content" style="display: block"></div>
    <?php }else if($verify_data == 'OTP_FAIL'){ ?>
        <h4 class="error_message">OTP could not sent. Please try again and reload the page.</h4>
    <?php }else{ ?>
        <h4 class="error_message">Bad Request</h4>
    <?php } ?>
    </div>



    </body>
<?php if($verify_data == 'SHOW_DIV'){ ?>
    <script>
        $(document).ready(function () {
            swal({
                title: 'Please enter OTP to verify consent.',
                input: 'number',
                showCancelButton: false,
                confirmButtonText: 'Verify',
                showLoaderOnConfirm: true,
                preConfirm: function (otp) {
                    return new Promise(function (resolve, reject) {
                        var data_id= $(".signature_content").attr("data-val");
                        $.ajax({
                            url: "<?php echo SITE_PATH;?>folder/verify",
                            data:{data_id:data_id,otp:otp},
                            type:'POST',
                            success: function(result){
                                result = JSON.parse(result);
                                if (result.status == 1) {
                                    resolve();
                                    $(".signature_content").html(result.html);
                                } else {
                                    reject(result.message);
                                }
                            },error:function () {
                                reject(result.message);
                            }
                        });
                    })
                },
                allowOutsideClick: false
            }).then(function (otp) {
                swal({
                    type: 'success',
                    title: 'OTP verify successfully',
                    showCancelButton: false,
                    showConfirmButton: false
                });
                var inter = setInterval(function () {
                    swal.close();
                    $(".login-page").show();
                    clearInterval(inter);
                },1000);
            })
        });
    </script>
<?php } ?>
    <?php } ?>


</html>