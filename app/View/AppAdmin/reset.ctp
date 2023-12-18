
<div class="Inner-banner">
    <div class="container">
        <div class="row">
            <!--  SLIDER IMAGE -->
            <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 inner-page-text"> <h2>Reset Password</h2> </div>
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

                <h2 class="title1">Reset Password For Your "mEngage" Account</h2>
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


                                <?php if($showForm == true){ ?>
                                <?php echo $this->Form->create('User',array('method'=>'post','id'=>'login_form','class'=>'contact-form')); ?>

                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <?php echo $this->Form->input('password',array('type'=>'password','placeholder'=>'Enter new password','label'=>false,'id'=>'password','class'=>'form-control cnt')); ?>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <?php echo $this->Form->input('conf_password',array('type'=>'password','placeholder'=>'Enter confirm your password','label'=>false,'id'=>'conf_password','class'=>'form-control cnt')); ?>
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
                                <?php } ?>
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
        $(document).on('submit','#login_form',function (ev) {
            var pass = $("#password").val();
            var confPass = $("#conf_password").val();
            if(pass != confPass)
            {
                alert("Confirm password does not match.");
                return false;
            }

        });
    });
</script>
