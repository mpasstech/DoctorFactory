


<div class="Inner-banner">
    <div class="container">
        <div class="row">
            <!--  SLIDER IMAGE -->
            <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 inner-page-text"> <h2>Sign Up</h2> </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 pull-right create-btn-box">
                <a href="<?php Router::url('/register-org',true); ?>" class="Btn-typ4" title="Create Own APP">Create Own APP</a>
            </div>
        </div>
    </div>
</div>


<div class="Home-section-2">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="container">
            <div class="row">
                <h2 class="title1">Create New Account</h2>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="container">
            <div class="row">
                <!--box 1 -->
                <div class="middle-block">

                    <!-- Heading -->
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding">

                        <div class="Login-box">
                            <div class="Social-login-box">

                                <?php echo $this->element('message'); ?>

                                    <?php echo $this->Form->create('User',array('id'=>'login_form','class'=>'contact-form','inputDefaults'=>array('required'=>true))); ?>
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <?php echo $this->Form->input('mobile',array('id'=>'mobile','type'=>'text','placeholder'=>'Enter mobile number','label'=>false,'class'=>'form-control cnt')); ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <?php echo $this->Form->input('org_name',array('type'=>'text','placeholder'=>'Organisation Name','label'=>false,'id'=>'mobile','class'=>'form-control cnt')); ?>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            Ex :-  <?php echo Router::url('/',true)?><label class="url_leble">unique_url</label>
                                            <?php echo $this->Form->input('org_unique_url',array('type'=>'text','placeholder'=>'Organisation Unique Url','label'=>false,'id'=>'org_unique_url','class'=>'form-control url_box')); ?>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <?php echo $this->Form->input('org_type',array('empty'=>'Organization type','options'=>$organization_type,'label'=>false,'class'=>'form-control cnt')); ?>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <?php echo $this->Form->input('email',array('type'=>'email','placeholder'=>'Email','label'=>false,'class'=>'form-control cnt')); ?>

                                        </div>

                                    </div>


                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <?php echo $this->Form->input('password',array('type'=>'password','placeholder'=>'Password','label'=>false,'class'=>'form-control cnt')); ?>

                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <?php echo $this->Form->input('confirm_password',array('type'=>'password','placeholder'=>'Confirm Password','label'=>false,'class'=>'form-control cnt')); ?>

                                        </div>
                                    </div>


                                 <!--   <div class="form-group">
                                        <div class="col-sm-12">
                                            <?php /*echo $this->Form->input('comments',array('type'=>'textarea','placeholder'=>'Comment','label'=>false,'class'=>'form-control cnt')); */?>
                                        </div>
                                    </div>-->

                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <?php echo $this->Form->submit('Next',array('class'=>'Btn-typ5','id'=>'signup')); ?>
                                    </div>
                                </div>




                                    <?php echo $this->Form->end(); ?>
                                <div class="clearfix"></div>






                            </div>
                           <!-- <div class="Social-login-box1">
                                <h3>Signin with..</h3>
                                <div class="social-login"><a href="#"><img src="images/google-sigin.png" alt="google signin" /></a>    </div>
                                <div class="social-login"><a href="#"><img src="images/facebook-sigin.png" alt="facebook signin" /></a>    </div>
                                <div class="social-login"><a href="#"><img src="images/twitter-signin.png" alt="Twitter signin" /></a>    </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12 forgot-box">
                                    Already have an Account! <a href="login.html" class="link1" title="Sign up">Sign In</a>

                                </div>
                            </div>-->
                        </div>

                    </div>

                </div>
                <!-- box 1 -->


            </div>
            <!--box 2 -->


        </div>
    </div>
</div>



<script type="text/javascript">

    $(function(){
        var submit =false;
        $("#phone").on("countrychange", function(e, countryData) {
            //console.log($("#phone").intlTelInput("getNumber",intlTelInputUtils.numberFormat.E164));
            //console.log($("#phone").intlTelInput("isValidNumber"));
            // do something with countryData
            $("#mobile").trigger("keyup");

        });

        $(document).on("keyup","#mobile", function() {
            //console.log($("#phone").intlTelInput("getNumber",intlTelInputUtils.numberFormat.E164));
            if($(this).intlTelInput("isValidNumber")){
                $(this).css('border-color',"#ccc");
            }else{
                $(this).css('border-color',"red");
            }
        });



        $(document).on("submit","#login_form", function() {
            //console.log($("#phone").intlTelInput("getNumber",intlTelInputUtils.numberFormat.E164));
            if($("#mobile").intlTelInput("isValidNumber")){
                var mob = $("#mobile").intlTelInput("getNumber",intlTelInputUtils.numberFormat.E164);
                $("#mobile").val(mob);
            }else{
                return false;
            }
        });



        $(document).on("keyup", "#org_unique_url", function() {
            var str = $(this).val();
            if(str.match(/^([a-z0-9]+-)*[a-z0-9]+$/i)){
                $(this).css('border-color',"#ccc");
            }else{
                $(this).css('border-color',"red");
            }
        });


        $.get("https://ipinfo.io", function(response) {
            //console.log(response.country);
            //console.log(response.city);
            $("#mobile").intlTelInput({
                allowExtensions: true,
                autoFormat: false,
                autoHideDialCode: false,
                autoPlaceholder:  false,
                initialCountry: response.country,
                ipinfoToken: "yolo",
                nationalMode: true,
                numberType: "MOBILE",
                //onlyCountries: ['us', 'gb', 'ch', 'ca', 'do'],
                //preferredCountries: ['cn', 'jp'],
                preventInvalidNumbers: true,
                utilsScript: "<?php echo Router::url('/js/utils.js',true); ?>"
            });
        }, "jsonp");



        $(document).on("keyup",".url_box",function () {
            var val = $(this).val();
            if(val!=""){
                $(".url_leble").html($(this).val());
            }else{

                $(".url_leble").html("");
            }

        })
    })
</script>
