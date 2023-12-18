
<div class="Inner-banner">
    <div class="container">
        <div class="row">
            <!--  SLIDER IMAGE -->
            <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 inner-page-text"> <h2>My Account</h2> </div>
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
                    <?php echo $this->element('app_admin_leftsidebar'); ?>

                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">



                        <div class="Social-login-box payment_bx">
                            <?php echo $this->element('message'); ?>
                        <?php echo $this->Form->create('User',array('type'=>'file','class'=>'contact-form','admin'=>true)); ?>
                        <?php echo $this->Form->input('User.id',array('type'=>'hidden'));?>

                            <div class="form-group">
                                <div class="col-sm-12">
                                    <lable>New Password</lable>
                                    <?php echo $this->Form->input('password',array('type'=>'password','placeholder'=>'Password','label'=>false,'class'=>'form-control cnt')); ?>

                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <lable>Confirm Password</lable>
                                    <?php echo $this->Form->input('confirm_password',array('type'=>'password','placeholder'=>'Confirm Password','label'=>false,'class'=>'form-control cnt')); ?>

                                </div>
                            </div>

                            <div class="col-sm-3">
                                <?php echo $this->Form->submit('Update Password',array('class'=>'Btn-typ3','id'=>'signup')); ?>


                            </div>


                            <div class="clear"></div>

                        <?php echo $this->Form->end();?>
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
        $(".form-group .password").removeClass('button-bg');

   });
</script>


