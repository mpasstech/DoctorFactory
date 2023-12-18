
<div class="Inner-banner">
    <div class="container">
        <div class="row">
            <!--  SLIDER IMAGE -->
            <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 inner-page-text"> <h2>Forgot Password</h2> </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 pull-right create-btn-box">
                <a href="<?php echo Router::url('/register-org',true); ?>" class="Btn-typ4" title="Create Own APP">Create Own APP</a>
            </div>
        </div>
    </div>
</div>

<div class="Home-section-2">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="container">
            <div class="row">

                <h2 class="title1">Reset Password For Your "MEngage" Account</h2>
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

                                <?php echo $this->Form->create('User',array('method'=>'post','id'=>'login_form','class'=>'contact-form')); ?>

                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <img style="display: none;" class="loading_mob" src="<?php echo Router::url('/img/9.gif',true);?>">
                                        <?php echo $this->Form->input('mobile',array('type'=>'text','placeholder'=>'Mobile No','maxlength'=>13,'label'=>false,'id'=>'mobile','class'=>'form-control cnt')); ?>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-sm-12">

                                        <?php $theme = $this->AppAdmin->getThemeDropDown(); ?>
                                        <?php $theme = array(); ?>
                                        <?php echo $this->Form->input('slug',array('label'=>false,'type'=>'select','options'=>$theme,'empty'=>'Select Orgnization','placeholder'=>'','class'=>'form-control cnt slug_drp','id'=>'text'));?>

                                    </div>
                                </div>

                                <div class="mob-no-box">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 coad">
                                        <div class="form-group" style="float:right;width: 100%;">
                                            <?php echo $this->Form->submit('Reset',array('class'=>'Btn-typ5','id'=>'login_btn')); ?>
                                        </div>
                                    </div>
                                </div>
                                <?php echo $this->Form->end(); ?>
                                <div class="clearfix"></div>
                            </div>

                        </div>

                    </div>

                </div>
                <!-- box 1 -->


            </div>
            <!--box 2 -->


        </div>
    </div>
</div>

<script>
    $(document).ready(function (){



        var mob = $("#mobile").val();
        if(mob!=""){
            checkOrg(mob);
        }


        $(document).on('keyup blur','#mobile',function () {
            var mob = $(this).val();
            if(1){
                checkOrg(mob);
            }
        })


        function checkOrg(mob){
            $.ajax({
                url:baseurl+"app_admin/get_org",
                type:'POST',
                data:{mob:mob},
                beforeSend:function(){
                    $(".loading_mob").show();
                },
                success:function(res){
                    if(res!=0){
                        $('#login_btn').prop('disabled',false);
                        $(".slug_drp").html(res);
                    }else{
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

    });
</script>
