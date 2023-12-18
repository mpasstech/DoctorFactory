
<div class="Inner-banner">
    <div class="container">
        <div class="row">
            <!--  SLIDER IMAGE -->
            <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 inner-page-text"> <h2>Forgot Password</h2> </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 pull-right create-btn-box">

            </div>
        </div>
    </div>
</div>

<div class="Home-section-2">

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

                                <?php echo $this->Form->create('User',array('id'=>'login_form','class'=>'contact-form')); ?>

                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <?php echo $this->Form->input('email',array('type'=>'email','placeholder'=>'Enter your registered email','label'=>false,'id'=>'email','class'=>'form-control')); ?>
                                    </div>
                                </div>
                                <div class="mob-no-box">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 coad">
                                        <div class="form-group" style="float:right;width: 100%;">
                                            <?php echo $this->Form->submit('Send',array('class'=>'Btn-typ5','id'=>'signup')); ?>
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