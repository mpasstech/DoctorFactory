<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

    <div class="container">

        <div class="row">
            <!--box 1 -->
            <div class="middle-block">


                <!-- Heading -->
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding">


                    <div class="Login-box login_box" style="width:600px;">

                        <div class="Social-login-box">
                            <div class="login_box_header">
                                <a class="" href="<?php echo Router::url('/',true); ?>"><img class="logo" src="https://www.mpasscheckin.com/assets/images/logo/logo.webp" alt="logo"></a>

                                <p class="title1">Access to your "mPass" Account</p>

                            </div>


                            <?php echo $this->Form->create('User',array('method'=>'post','id'=>'login_form','class'=>'contact-form')); ?>


                            <div class="form-group">
                                <div class="col-sm-12">
                                    <div class="radio_lbl_vac">

                                        <?php
                                        $options = array('ADMIN' => 'Admin', 'RECEPTIONIST' => 'Receptionist','DOCTOR'=>'Doctor','LAB'=>'Lab','PHARMACY'=>'Pharmacy');
                                        $attributes = array('id'=>'role_type','legend' => "Who are you?",'class'=>'radio-inline login_radio','div'=>'label');
                                        echo $this->Form->radio('visible_for', $options, $attributes);

                                        ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <img style="display: none;" class="loading_mob" src="<?php echo Router::url('/img/9.gif',true);?>">
                                    <?php echo $this->Form->input('mobile',array("autocomplete"=>"off",'id'=>'mobile','type'=>'text','placeholder'=>'Enter mobile number','label'=>false,'class'=>'form-control cnt')); ?>
                                    <?php /*echo $this->Form->input('mobile',array('type'=>'text','placeholder'=>'Mobile No','maxlength'=>13,'label'=>false,'id'=>'mobile','class'=>'form-control cnt')); */?>
                                </div>
                            </div>
                            <div class="form-group margin_bottem">
                                <div class="col-sm-12">

                                    <?php $theme = array(); ?>
                                    <?php echo $this->Form->input('slug',array('label'=>false,'type'=>'select','options'=>$theme,'empty'=>'Select Organization','class'=>'form-control cnt slug_drp','id'=>'text'));?>
                                    <label class="register_label" style="display: none;">You need to <a href="<?php echo Router::url('/register-org',true);?>">register</a> before login </label>
                                </div>
                            </div>
                            <div class="form-group margin_bottem">
                                <div class="col-sm-12">
                                    <?php echo $this->Form->input('password',array('placeholder'=>'Password','label'=>false,'class'=>'form-control cnt')); ?>
                                </div>
                            </div>
                            <div class="mob-no-box">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 coad">
                                    <div class="form-group" style="float:right;width: 100%;">
                                        <?php echo $this->Form->submit('Login',array('class'=>'Btn-typ5','id'=>'login_btn')); ?>
                                    </div>
                                </div>
                            </div>

                            <?php echo $this->Form->end(); ?>

                            <div class="clearfix"></div>

                            <?php echo $this->element('message'); ?>



                        </div>

                        <div class="form-group">
                            <div class="col-sm-12 forgot-box">
                                <a href="javascript:void(0);" class="forgot_password" title="Forgot your password?">Forgot your password?</a><br />
                                Don't have an account yet? <a href="<?php echo Router::url('/register-org')?>" class="link1" title="Sign up">Sign up</a>

                            </div>
                        </div>
                    </div>

                </div>

            </div>
            <!-- box 1 -->


        </div>
        <!--box 2 -->


    </div>
</div>


<div class="modal fade" id="forgot" role="dialog">
    <div class="modal-dialog modal-md">

        <div class="modal-content">

            <form method="post" id="forgot_form">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Forgot Password</h4>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <div class="col-sm-12">
                            <div class="radio_lbl_vac">

                                <?php
                                $options = array('ADMIN' => 'Admin', 'RECEPTIONIST' => 'Receptionist','DOCTOR'=>'Doctor','LAB'=>'Lab','PHARMACY'=>'Pharmacy');
                                $attributes = array('id'=>'forgot_role_type','legend' => "Who are you?",'class'=>'radio-inline login_radio','div'=>'label');
                                echo $this->Form->radio('forgot_for', $options, $attributes);

                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12">
                            <img style="display: none;" class="forgot_loading_mob" src="<?php echo Router::url('/img/9.gif',true);?>">
                            <?php echo $this->Form->input('forgot_mobile',array('id'=>'forgot_mobile','type'=>'text','placeholder'=>'Enter mobile number','label'=>false,'class'=>'form-control cnt')); ?>
                            <?php /*echo $this->Form->input('mobile',array('type'=>'text','placeholder'=>'Mobile No','maxlength'=>13,'label'=>false,'id'=>'mobile','class'=>'form-control cnt')); */?>
                        </div>
                    </div>
                    <div class="form-group margin_bottem">
                        <div class="col-sm-12">
                            <?php $theme = array(); ?>
                            <?php echo $this->Form->input('thin_app',array('label'=>false,'type'=>'select','options'=>$theme,'empty'=>'Select Organization','class'=>'form-control cnt thin_app_drp'));?>
                        </div>
                    </div>

                    <div class="form-group append">

                    </div>


                </div>

                <div class="modal-footer" style="clear: both;">
                    <input class="btn btn-danger" data-dismiss="modal" value="Close" type="button">
                    <input class="btn btn-info"  value="Reset" type="reset">
                    <input class="btn btn-success generate_btn" style="border: none;" value="Generate OTP" type="button">
                </div>

            </form>

        </div>
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
</style>
<script>
    $(document).ready(function (){

        var role = 0; var  last_id = 0;
        var org_value = "<?php echo (isset($this->request->data['User']['slug']))?$this->request->data['User']['slug']:'';?>";

        function checkOrg(){
            var mob_obj =$("#mobile");
            if(/^\d{10}$/.test((mob_obj.val()).trim())){
                $(mob_obj).css('border-color',"#ccc");
                var mob = $(mob_obj).val();
                var role_type = $("input[name='data[User][visible_for]']:checked").val();
                if(mob!=""){
                    $.ajax({
                        url:baseurl+"app_admin/get_org",
                        type:'POST',
                        data:{mob:"+91"+mob,role_type:role_type},
                        beforeSend:function(){

                            $('.slug_drp').html('<option>Select Organization</option>');
                            $(".loading_mob").show();
                        },
                        success:function(res){
                            if(res!=0){
                                $('.register_label').fadeOut();
                                $('#login_btn').prop('disabled',false);
                                $(".slug_drp").html(res);
                                if(org_value!=""){
                                    $(".slug_drp").val(org_value);
                                    $(".slug_drp").prop('selectedIndex', 0);

                                }
                            }else{
                                $('.register_label').fadeIn();
                                $('#login_btn').prop('disabled',true);
                                $(".slug_drp").html("<option>Select Organization</option>");

                            }

                            $(".loading_mob").hide();
                        },
                        error:function () {
                            $(".loading_mob").hide();
                        }
                    })
                }
            }else{
                $(this).css('border-color',"red");
            }
        }


        function checkForgotOrg(){


            var mob_obj =$("#forgot_mobile");
            if(/^\d{10}$/.test((mob_obj.val()).trim())){
                $(mob_obj).css('border-color',"#ccc");
                var mob = "+91"+$(mob_obj).val();
                var role_type = $("input[name='data[forgot_for]']:checked").val();

                $.ajax({
                    url:baseurl+"app_admin/get_org",
                    type:'POST',
                    data:{mob:mob,role_type:role_type},
                    beforeSend:function(){
                        $('.thin_app_drp').html('<option>Select Organization</option>');
                        $(".forgot_loading_mob").show();
                    },
                    success:function(res){

                        if(res!=0){
                            $(".thin_app_drp").html(res);
                            if(org_value!=""){
                                $(".thin_app_drp").val(org_value);
                                $(".thin_app_drp").prop('selectedIndex', 0);
                            }
                        }else{
                            $(".thin_app_drp").html("<option value='0'>Select Organization</option>");
                        }

                        $(".forgot_loading_mob").hide();
                    },
                    error:function () {
                        $(".forgot_loading_mob").hide();
                    }
                })
            }else{
                $(this).css('border-color',"red");
            }




        }


        $("#mobile").on("countrychange", function(e, countryData) {
            //console.log($("#phone").intlTelInput("getNumber",intlTelInputUtils.numberFormat.E164));
            //console.log($("#phone").intlTelInput("isValidNumber"));
            // do something with countryData
            $("#mobile").trigger("keyup");

        });

        $(document).on("keyup change  select","#mobile", function(e) {

            var keyCode = (e.keyCode ? e.keyCode : e.which);
            if(e.type =='select' || (keyCode > 0 && keyCode != 9  && keyCode != 67 && keyCode != 65 && keyCode != 17)){

                checkOrg();
            }
        });

        $(document).on("click",".forgot_password", function(e) {
            $("#forgot").modal('show');
        });


        $(document).on("reset","#forgot form", function(e) {
            var inter = setInterval(function () {
                $(".append").html('');
                $("#forgot_role_typeADMIN").prop("checked", true);

                $(".thin_app_drp").html("<option value='0'>Select Organization</option>");
                $("input[name='data[forgot_for]'], #forgot_mobile, .thin_app_drp").prop('disabled',false);
                $('.save_pass').remove();
                $('.generate_btn').show();
                clearInterval(inter);
            },1);
        });

        $('#forgot').on('hidden.bs.modal', function() {
            $("#forgot form").trigger('reset');
        })


        $(document).on("click",".generate_btn", function(e) {

            var mob_obj =$("#forgot_mobile");
            var $btn =$(this);
            if($(mob_obj).intlTelInput("isValidNumber")){
                $(mob_obj).css('border-color',"#ccc");

                var mob = "+91"+$(mob_obj).val();
                var role_type = $("input[name='data[forgot_for]']:checked").val();
                var t = $(".thin_app_drp option:selected").val();

                if(mob!=""){
                        $.ajax({
                        url:baseurl+"app_admin/generate_forgot_otp",
                        type:'POST',
                        data:{m:mob,r:role_type,t:t},
                        beforeSend:function(){
                            $btn.button('loading').val('Sending OTP..');
                        },
                        success:function(res){


                            res = JSON.parse(res);

                            if(res.status ==1){

                                role = res.role;
                                last = res.last;
                                $(".append").hide();
                                $("input[name='data[forgot_for]'], #forgot_mobile, .thin_app_drp").prop('disabled',true);
                                var str = "<div class='col-sm-4'><div class='input text'><label>Enter OTP</lable><input autocomplete='off' type='text' value='' name='otp' class='form-control otp' /></div></div>";
                                str += "<div class='col-sm-8'><div class='input text'><label>Enter new password</lable><input autocomplete='off' value='' type='password' name='password' class='form-control new_password' /></div></div>";
                                $(".append").html(str);
                                $(".modal-footer").append("<input type ='button' class='btn btn-success save_pass' value='Change Password' />");
                                $('.generate_btn').hide();

                                var inter = setInterval(function () {
                                    $(".new_password, .otp").val('');
                                    $(".append").show();
                                    $btn.button('reset');
                                    clearInterval(inter);
                                },400);



                            }else{
                                $btn.button('reset');
                                $.alert(res.message);
                            }

                        },
                        error:function () {
                            $btn.button('reset');;
                            $.alert(res.message);
                        }
                    })
                }
            }else{
                $(this).css('border-color',"red");
            }

        });


        $(document).on("click",".save_pass", function(e) {

            var otp =$(".otp").val();
            var password =$(".new_password").val();
            var $btn =$(this);

            if(otp ==""){
                $.alert('Please enter valid OTP');
            }else if(password == ""){
                $.alert('Please enter valid password');
            }else{

                $.ajax({
                    url:baseurl+"app_admin/update_password",
                    type:'POST',
                    data:{o:otp,p:password,l:last,r:role},
                    beforeSend:function(){
                        $btn.button('loading').val('Changing...');
                    },
                    success:function(res){
                        $btn.button('reset');
                        res = JSON.parse(res);
                        if(res.status ==1){
                            window.location.reload();
                        }else{
                            $.alert(res.message);
                        }
                    },
                    error:function () {
                        $btn.button('reset');;
                        $.alert(res.message);
                    }
                })
            }


        });


        $(document).on("change",".login_box input[type=radio]", function(e) {
            checkOrg();
        });

        $(document).on("change","#forgot input[type=radio]", function(e) {
            checkForgotOrg();
        });

        $(document).on("keyup change  select","#forgot_mobile", function(e) {

            var keyCode = (e.keyCode ? e.keyCode : e.which);
            if(e.type =='select' || (keyCode > 0 && keyCode != 9  && keyCode != 67 && keyCode != 65 && keyCode != 17)){
                checkForgotOrg();
            }
        });

        $(document).on("submit","#login_form", function() {
            var mob = $("#mobile").val();
            $("#mobile").val("+91"+mob);
        });

        $("#mobile").val($("#mobile").val().split(" ").join(""));



        setTimeout(function(){
            var mobile = '<?php echo @substr($this->request->data['User']['mobile'],-10); ?>';
            if(mobile!=''){
                $("#mobile").val(mobile);
            }
            checkOrg();
        },15);

        var load = '<?php echo @$this->request->data['User']['visible_for'] ?>';
        if(!load){
            $("#role_typeADMIN").prop("checked", true);

        }
        $("#forgot_role_typeADMIN").prop("checked", true);


    });
</script>
