	<?php echo $this->Html->script(array('jquery.js','sweetalert2.min.js','bootstrap.min.js','jquery.signaturepad.min.js')); ?>
    <?php echo $this->Html->css(array('sweetalert2.min.css','bootstrap.min.css','jquery.signaturepad.css')); ?>

    <div  class="login-page" style="display: block">

        <div class="form">
            <form class="login-form">
                <div class="main_head_div">
                    <p class="head_name"><i class="fa fa-user"></i> <?php echo $consent_data['receiver_mobile']; ?></p>
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
                <?php }else{ ?>
                    <label class="message">I have read this consent carefully.</label>
                    <div class="radio_div">
                        <label><input class="rad_btn" type="radio"  value="AGREE" name="option"><span>Agree</span></label>
                        <label><input class="rad_btn" type="radio" checked="checked" value="DISAGREE" name="option"><span>Disagree</span></label>
                    </div>
                <?php } ?>

               <?php if($consent_data['action_type'] =="PENDING"){ ?>
               <div class="sigPad" id="linear" style="margin: 0 auto;display:block;">
                    <ul class="sigNav">
                        <li class="draw"><a href="javascript:void();" >Signature Here</a></li>
                        <li class="clear"><a href="javascript:void(0);" class="clear_btn">Erase</a></li>
                    </ul>
                    <div class="sig sigWrapper">
                        <div class="typed"></div>
                        <canvas class="pad" id="pad" style="margin: 0; "></canvas>
                        <input type="hidden" name="output" class="output">
                    </div>
                </div>
               <button type="submit" class="sub_btn">Submit</button>
               <?php }else{ ?>
                    <div class="sigPad" id="linear" style="margin: 0 auto;"><img class="sig_img" src="<?php echo $consent_data['signature_image']; ?>">
                    </div>
               <?php } ?>

                <div class="prop">
                    <?php if($consent_data['action_type'] != "PENDING"){ ?>
                    <p>Consent From : <b><?php echo $consent_data['app_name']; ?></b></p>
                    <p class="footer_time">
                        <label style="color: <?php echo ($consent_data['action_type'] =='AGREE')?'#5d9e39':'red';?>;">You have <?php echo strtolower($consent_data['action_type'])."d";?> for this consent on </label><br>
                        <i class="fa fa-calendar"></i> <?php echo date('d M, Y',strtotime($consent_data['action_time'])); ?>
                        <i class="fa fa-clock-o"></i> <?php echo date('h:i A',strtotime($consent_data['action_time'])); ?>
                    </p>
                    <?php } ?>
                </div>
            </form>
        </div>
    </div>


<?php if($consent_data['action_type'] == "PENDING"){ ?>
<script>
    $(document).ready(function() {
        var inter = setInterval(function(){
            var obj = $('#linear').signaturePad({
                drawOnly:true,
                lineTop:300,
                clear : '.clear_btn',
                penColour : '#145394',
                errorClass :'error_pad',
                errorMessageDraw : 'Please draw your signature before submit.',
                onFormError:myFunction
            });
            function myFunction(obj,obj2) {
                var action_type = $('input[name=option]:checked').val();
                var thisButton = $(".sub_btn");
                if((obj.drawInvalid === false && action_type == "AGREE") || action_type == "DISAGREE"){
                    var canvas = document.getElementById("pad");
                    var base = (canvas.toDataURL("image/png"));
                    var thin_app_id = "<?php echo $consent_data['thinapp_id'];?>";
                    var consent_id = "<?php echo $consent_data['id'];?>";
                    var mobile = "<?php echo $consent_data['receiver_mobile'];?>";
                    var otp = "<?php echo $consent_data['verify_otp'];?>";
                    var param = {
                        thin_app_id:thin_app_id,
                        app_key:"MB",
                        mobile:mobile,
                        consent_id:consent_id,
                        action_type:action_type,
                        signature_image:base,
                        action_from:"WEB",
                    };
                    $.ajax({
                        url: "<?php echo SITE_PATH;?>services/update_consent_action",
                        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                        data:JSON.stringify(param),
                        type:'POST',
                        beforeSend:function () {
                            $(thisButton).button('loading').text('Please wait..');
                        },
                        success: function(result){
                            result = JSON.parse(result);
                            if (result.status == 1) {
                                swal({
                                    type: 'success',
                                    title: result.message,
                                    showCancelButton: false,
                                    showConfirmButton: false
                                });
                                var inter = setInterval(function () {
                                    var data_id = "<?php echo base64_encode($consent_data['id']); ?>";
                                    $.ajax({
                                        url: "<?php echo SITE_PATH;?>folder/verify",
                                        data:{data_id:data_id,otp:otp},
                                        type:'POST',
                                        beforeSend:function () {
                                            swal({
                                                text: 'Please wait...',
                                                showCancelButton: false,
                                                showConfirmButton: false
                                            });
                                        },
                                        success: function(result){
                                            result = JSON.parse(result);
                                            if (result.status == 1) {
                                                $(".signature_content").html(result.html);
                                                swal.close();
                                            } else {
                                                swal(
                                                    'Oops...',
                                                    result.message,
                                                    'error'
                                                )
                                            }
                                        },error:function () {

                                        }
                                    });

                                    clearInterval(inter);
                                },1000);

                            } else {
                                $(thisButton).button('reset');
                                swal(
                                    'Oops...',
                                    result.message,
                                    'error'
                                );
                            }
                        },
                        error:function () {
                            $(thisButton).button('reset');
                            swal(
                                'Oops...',
                                'Something went wrong. Please try again',
                                'error'
                            );
                        }
                    });
                }else{
                    swal(
                        'Oops...',
                        "Please draw signature",
                        'error'
                    )

                    return false;
                }
            }
            function resizeCanvas() {
                var ratio =  Math.max(window.devicePixelRatio || 1, 1);
                var canvas = document.getElementById('pad');
                canvas.width = $(".sigWrapper").width();
                canvas.height = $(".sigWrapper").height();
                canvas.getContext("2d");


                // $(".typed, .sig, #linear").width(canvas.offsetWidth * ratio);
                //$(".pad").height(canvas.offsetHeight * ratio);
                // signaturePad.clear(); // otherwise isEmpty() might return incorrect value
            }
            resizeCanvas();
            signaturePad = $.fn.signaturePad;
            $(document).on("click",".clear_btn",function () {
                resizeCanvas();
            });
            $(document).on("submit","form",function (e) {
                e.preventDefault();
            });
            clearInterval(inter);
        },50);
    });
</script>
    <?php } ?>