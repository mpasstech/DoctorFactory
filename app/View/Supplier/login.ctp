

<div class="Home-section-2">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="container">
            <div class="row">

                <h2 class="title1">Access to your "MEngage Supplier" Account</h2>
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

                                <?php echo $this->Form->create('Login',array('id'=>'login_form','class'=>'contact-form')); ?>

                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <?php echo $this->Form->input('mobile',array('type'=>'text','placeholder'=>'Mobile No','maxlength'=>13,'label'=>false,'id'=>'mobile','class'=>'form-control cnt')); ?>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <?php echo $this->Form->input('password',array('placeholder'=>'Password','label'=>false,'class'=>'form-control cnt')); ?>
                                    </div>
                                </div>



                                <div class="mob-no-box">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 coad">
                                        <div class="form-group" style="float:right;width: 100%;">
                                            <?php echo $this->Form->submit('Login',array('class'=>'Btn-typ5','id'=>'signup')); ?>
                                        </div>
                                    </div>
                                </div>
                                <?php echo $this->Form->end(); ?>
                                <div class="clearfix"></div>
                            </div>

                            <div class="form-group">
                                <!--div class="col-sm-12 forgot-box">
                                    <a href="#" class="link1" title="Forgot your password?">Forgot your password?</a>

                                </div-->
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
