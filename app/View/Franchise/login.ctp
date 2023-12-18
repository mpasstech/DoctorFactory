<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

    <div class="container">

        <div class="row">
            <!--box 1 -->
            <div class="middle-block">


                <!-- Heading -->
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding">


                    <div class="Login-box login_box" style="width:370px;">

                        <div class="Social-login-box" style=" height: 450px; padding: 10px 15px;">
                            <div class="login_box_header">
                                <a class="" href="<?php echo Router::url('/',true); ?>"><img class="logo" src="<?php echo Router::url('/images/logo.png',true); ?>" alt="logo"></a>

                                <p class="title1">Welcome to MEngage</p>

                            </div>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <div class="radio_lbl_vac">
                                    <fieldset>
                                        <legend style="width: 100%; padding: 8px 0px;">Franchise Login Panel</legend>
                                    </fieldset>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-12">
                                    <img style="display: none;" class="loading_mob" src="<?php echo Router::url('/img/9.gif',true);?>">
                                    <label>Enter 10 digit mobile number</label>
                                    <input type="number" name="mobile" max="10" min="10" id="mobile" placeholder="Enter mobile number" class="form-control">
                                </div>
                            </div>

                            <div class="form-group" id="option_btn_div">
                                <div class="col-sm-6" >
                                    <div class="form-group" style="float:right;width: 100%;">
                                        <button type="button" disabled="disabled" data-t="otp" class='otp_btn_box button_inactive' >Send OTP</button>
                                    </div>
                                </div>
                                <div class="col-sm-6" >
                                    <div class="form-group" style="float:right;width: 100%;">
                                        <button type="button" disabled="disabled" data-t='password' class='otp_btn_box button_inactive' >Enter Password</button>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group append_response">

                            </div>


                            <div class="clearfix"></div>

                            <?php echo $this->element('message'); ?>



                        </div>


                    </div>

                </div>

            </div>
            <!-- box 1 -->


        </div>
        <!--box 2 -->


    </div>
</div>



<style>
    body{

    }
    .header{display: none;}
    .login_radio{
        margin:0px !important;
    }

    .forgot_loading_mob{
        float: right;
        position: absolute;
        right: -2px;
        top: 11px;
    }
    .login_box_header{
        text-align: center;
    }

    .otp_btn_box, .login_btn{
        padding: 10px;
        border: none;
        font-size: 15px;
        width: 98%;
        color: #fff;
        margin: 2px;

    }
    .button_inactive{
        background: gray;
        cursor: not-allowed !important;
    }

    .button_active{
        background: #04A6F0;
        color: #fff;

    }

    .login_box div[class*="col-sm-"]{
        padding: 0px;
    }




</style>
<script>
    $(document).ready(function (){

        var role = 0; var  last_id = 0;
        var org_value = "<?php echo (isset($this->request->data['User']['slug']))?$this->request->data['User']['slug']:'';?>";

        function checkOrg(obj){
            var $btn = $(obj);
            var type = $(obj).attr('data-t');
              $.ajax({
                url:baseurl+"franchise/send_otp",
                type:'POST',
                data:{m:$("#mobile").val(),t:type},
                beforeSend:function(){
                    $($btn).button('loading').html("Wait...");
                    buttonDisable(true);
                },
                success:function(res){
                    buttonDisable(true);
                   $(".append_response").html(res);
                    $($btn).button('reset');


                },
                error:function () {
                    $($btn).button('reset');
                }
            })
        }






        function validMobileNumber(){
            return /^\d{10}$/.test(($("#mobile").val()).trim());
        }


        function buttonDisable(value) {
            if(value===true){
                $(".otp_btn_box").prop("disabled",true);
                $(".otp_btn_box").addClass("button_inactive").removeClass("button_active");
                $(".error_message").remove();
            }else{
                $(".otp_btn_box").prop("disabled",false);
                $(".otp_btn_box").addClass("button_active").removeClass("button_inactive");
            }
        }

        $(document).on("input","#mobile", function(e) {
            if(validMobileNumber()){
                buttonDisable(false);
            }else{
                buttonDisable(true);
            }
        });

        $(document).on("click",".button_active", function(e) {
            if(validMobileNumber()){
                checkOrg($(this));
            }else{
                $.alert('Invalid Mobile Number')
            }
        });

















    });
</script>
