<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
   <meta charset="UTF-8">
   <meta name="mobile-web-app-capable" content="yes">
   <meta name="theme-color" content="#7f0b00">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Consent</title>
	<?php echo $this->Html->script(array('jquery.js','jquery-ui.min.js', 'es6-promise.auto.min.js','sweetalert2.min.js','jquery.geocomplete.min')); ?>
	<?php echo $this->Html->css(array('sweetalert2.min.css','bootstrap.min.css')); ?>
    <script src="http://maps.googleapis.com/maps/api/js?key=AIzaSyCesZ_URzn7gYpBbNFpIx9Pe11-0xUCuks&libraries=places"></script>
    <?php echo $this->Html->script(array('jquery.geocomplete.min')); ?>
    <?php echo $this->Html->css(array('sweetalert2.min.css','bootstrap.min.css','jquery.signaturepad.css')); ?>

    <link href="<?php echo SITE_PATH;?>css/font-awesome.min.css" rel="stylesheet">

<style>
    .swal2-title{font-size: 18px !important;}
    .error_message{
        text-align: center;
    }
</style>
</head>
	<body>
    <?php if($verify_data == 'SHOW_DIV'){ ?>

    <div data-val="<?php echo base64_encode($consent_data['id']); ?>" class="signature_content" style="display: block"></div>
    <?php }else if($verify_data == 'OTP_FAIL'){ ?>
        <h4 class="error_message">OTP could not sent. Please try again and reload the page.</h4>
    <?php }else{ ?>
        <h4 class="error_message">Bad Request</h4>
    <?php } ?>
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
                        url: "<?php echo SITE_PATH;?>folder/verify_lab_request",
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

</html>