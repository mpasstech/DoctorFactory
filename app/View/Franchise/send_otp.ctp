<?php if($otp_send || $type =="PASSWORD"){ ?>
    <div class="form-group margin_bottem">
        <div class="col-sm-12">
            <?php if($type =="OTP"){ ?>
                <?php echo $this->Form->input('otp',array('id'=>'otp','placeholder'=>'Enter OTP','label'=>"Enter OTP",'class'=>'form-control')); ?>
            <?php }else{ ?>
                <?php echo $this->Form->input('password',array('id'=>'password','placeholder'=>'Enter Password','label'=>"Enter Password",'class'=>'form-control')); ?>
            <?php } ?>
         </div>
    </div>
    <div class="form-group">
        <div class="col-sm-12" style="padding: 0px;">
            <div class="form-group" style="float:right;width: 100%;">
                <button type="button" style="color:#fff;background: #04A6F0;" class='login_btn' >Login</button>
            </div>
        </div>
    </div>
    <script>
        $(function () {
            $("#mobile").prop('disabled',true);
            $("#option_btn_div").remove();
        })
    </script>
<?php }else{ ?>
   <label style="width: 100%;text-align: center;color: red;" class="error_message"><?php echo $response['message']; ?></label>
<?php } ?>

<script>
    $(document).ready(function (){


        function validOtpNumber(type){
            if(type=="OTP"){
                return /^\d{4}$/.test(($("#otp").val()).trim());
            }else{
                if($("#password").val()==''){
                    return false;
                }
                return true;
            }

        }
        $(document).off("click",".login_btn");
        $(document).on("click",".login_btn", function(e) {

            var $btn = $(this);
            var redirect_url = "<?php echo Router::url('/franchise/dashboard',true);?>";
            var type = "<?php echo $type; ?>";
            if(validOtpNumber(type)){
                var id = "<?php echo base64_encode($mediator_id); ?>";
                $.ajax({
                    url:baseurl+"franchise/verify",
                    type:'POST',
                    data:{c:$("#otp").val(),i:id,t:type,p:$("#password").val()},
                    beforeSend:function(){
                        $($btn).button('loading').html("Verifying...");
                    },
                    success:function(response){

                        $(".loading_mob").hide();
                        var response = JSON.parse(response);
                       if(response.status == 1){
                           window.location.href = redirect_url;
                       }else{
                           $.alert(response.message);
                           $($btn).button('reset');
                       }

                    },
                    error:function () {
                        $($btn).button('reset');
                        $(".loading_mob").hide();
                    }
                })
            }else{
                if(type=="OTP"){
                    $.alert('Please enter valid OTP')
                }else{
                    $.alert('Please enter password')
                }
            }
        });

    });
</script>


