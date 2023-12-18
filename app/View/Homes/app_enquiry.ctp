
<div class="Inner-banner">
    <div class="container">
        <div class="row">
            <!--  SLIDER IMAGE -->
            <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 inner-page-text"> <h2>Custom Development</h2> </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 pull-right create-btn-box">
                <a href="<?php echo Router::url('/register-org'); ?>" class="Btn-typ4" title="Create Own APP">Create Own APP</a>
            </div>
        </div>
    </div>
</div>
<!--//----- Google map Start here ---//-->



<div class="Home-section-2">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="container">
            <div class="row">

                <div itemscopeitemtype='https://schema.org/WPHeader'>
                    <h2 itemprop='name headline' class="title1">Custom Mobile Application Development</h2>
                </div>



                <p class="Align-Center">To help you devlop custom mobile application and make full usage of android and iphone technologies you need a partner with expertise and skill.</p>
            </div>
        </div>
    </div>




    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 hr-line"></div>
                <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
                    <div class="Ticket-box">

                        <div itemscopeitemtype='https://schema.org/WPHeader'>
                            <h3 itemprop='name headline'>Request a custom app quote</h3>
                            <meta itemprop='description' content='Request a custom app quote'/>
                        </div>



                        <?php echo $this->Session->flash('success'); ?>
                        <?php echo $this->Session->flash('error'); ?>

                        <div class="clear" style="margin-bottom: 15px;"></div>

                        <?php echo $this->Form->create('AppEnquiry',array('type'=>'file','id'=>'login_form','class'=>'form-horizontal')); ?>


                        <div class="form-group">
                            <div class="col-sm-12">
                                <?php echo $this->Form->input('name',array('type'=>'text','placeholder'=>'Name','label'=>false,'id'=>'mobile','class'=>'form-control cnt')); ?>

                            </div>
                         </div>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <?php echo $this->Form->input('email',array('type'=>'email','placeholder'=>'E-mail','label'=>false,'id'=>'mobile','class'=>'form-control cnt')); ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-12">
                                <?php echo $this->Form->input('phone',array('type'=>'text','placeholder'=>'Phone','label'=>false,'id'=>'mobile','class'=>'form-control cnt')); ?>

                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-12">
                                <?php echo $this->Form->input('message',array('type'=>'textarea','placeholder'=>'Your note here','label'=>false,'class'=>'form-control cnt')); ?>
                            </div>
                        </div>


                        <div class="form-group">
                            <div class="col-sm-12">
                                <?php echo $this->Form->input('attachment',array('type'=>'file','placeholder'=>'Upload your docuemtn','label'=>false,'class'=>'form-control cnt','required'=>false)); ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-6 col-md-offset-3">
                                <?php echo $this->Form->submit('Submit',array('class'=>'Btn-typ5','id'=>'signup')); ?>
                            </div>
                        </div>


                        <?php echo $this->Form->end(); ?>



                    </div>
                </div>

                <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                    <div class="Faq-box">
                        <p><strong>Membership Quires:</strong></p>
                        <a href="<?php echo Router::url('/support'); ?>">How to sign up in the website?</a><br />
                        <a href="<?php echo Router::url('/support'); ?>">I unable to get the confirmation email.</a><br />
                        <a href="<?php echo Router::url('/support'); ?>">How to verify account?</a><br />
                        <a href="<?php echo Router::url('/support'); ?>">Need more info</a><br />

                    </div>

                    <div class="Faq-box">
                        <p><strong>Membership Quires:</strong></p>
                        <a href="<?php echo Router::url('/support'); ?>">How to sign up in the website?</a><br />
                        <a href="<?php echo Router::url('/support'); ?>">I unable to get the confirmation email.</a><br />
                        <a href="<?php echo Router::url('/support'); ?>">How to verify account?</a><br />
                        <a href="<?php echo Router::url('/support'); ?>">Need more info</a><br />

                    </div>

                    <div class="Faq-box">
                        <p><strong>Membership Quires:</strong></p>
                        <a href="<?php echo Router::url('/support'); ?>">How to sign up in the website?</a><br />
                        <a href="<?php echo Router::url('/support'); ?>">I unable to get the confirmation email.</a><br />
                        <a href="<?php echo Router::url('/support'); ?>">How to verify account?</a><br />
                        <a href="<?php echo Router::url('/support'); ?>">Need more info</a><br />

                    </div>




                </div>



            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function(){
        $('#datePick').datepicker({startDate:new Date(),format: 'dd-M-yyyy',});
        $('#timePick').timepicker();
    });
</script>