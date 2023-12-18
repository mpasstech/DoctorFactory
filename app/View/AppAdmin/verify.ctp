
<div class="Inner-banner">
    <div class="container">
        <div class="row">
            <!--  SLIDER IMAGE -->
            <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 inner-page-text"> <h2>OTP Verification</h2> </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 pull-right create-btn-box">

            </div>
        </div>
    </div>
</div>

<div class="Home-section-2">


    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="container">
            <div class="row">
                <h2 class="title1">OTP Verify</h2>
            </div>
        </div>
    </div>

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="container">
            <div class="row">
                <!--box 1 -->
                <div class="middle-block">
                     <div class="col-md-offset-2 col-lg-8 col-md-8 col-sm-12 col-xs-12">

                        <div class="Login-box">

                            <div class="Social-login-box">
                                <?php echo $this->element('message'); ?>

                                <?php echo $this->Form->create('Verify',array('class'=>'contact-form','id'=>'verify_form')); ?>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <?php echo $this->Form->input('code',array('placeholder'=>'Enter Code','type'=>'text','label'=>false,'class'=>'form-control cnt','required'=>true)); ?>
                                        <?php echo $this->Form->hidden('m_code',array('value'=>@$_REQUEST['m_c']));?>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-sm-6 col-md-offset-3">
                                        <?php echo $this->Form->submit('Verify',array('type'=>'submit','id'=>'verify_submit','class'=>'Btn-typ5')); ?>
                                    </div>
                                </div>

                                <?php echo $this->Form->end(); ?>
                                <div class="clearfix"></div>



                            </div>

                            <div class="form-group">
                                <div class="col-sm-12 forgot-box">
                                    <a href="forgot-password.html" class="link1" title="Forgot your password?">Forgot your password?</a><br />
                                    Don't have an account yet? <a href="singup.html" class="link1" title="Sign up">Sign up</a>

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
</div>
