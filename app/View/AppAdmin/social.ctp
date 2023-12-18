<?php
$login = $this->Session->read('Auth.User');
?>


<div class="Home-section-2">

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="container">
            <div class="row">
                <!--box 1 -->
                <!--box 1 -->
                <div class="middle-block">
                    <!-- Heading -->
                    <!--left sidebar-->


                    <!--left sidebar-->
                    <h3 class="screen_title">Social Setting</h3>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="Social-login-box payment_bx">

                            <?php echo $this->element('message'); ?>

                            <?php echo $this->Form->create('Thinapp',array('type'=>'file','method'=>'post','class'=>'form-horizontal','id'=>'sub_frm')); ?>
                            <div class="form-group">

                                <div class="col-sm-6">
                                    <label>Website Url</label>
                                    <?php echo $this->Form->input('website_url',array('type'=>'text','placeholder'=>'http://www.yourwebsite.com','label'=>false,'class'=>'form-control cnt')); ?>

                                </div>

                                <div class="col-sm-6">
                                    <label>Facebook Url</label>
                                    <?php echo $this->Form->input('facebook_url',array('type'=>'text','placeholder'=>'http://www.facebook.com','label'=>false,'class'=>'form-control cnt')); ?>

                                </div>


                                <div class="col-sm-6">
                                    <label>Twitter Url</label>
                                    <?php echo $this->Form->input('twitter_url',array('type'=>'text','placeholder'=>'http://www.twitter.com.','label'=>false,'class'=>'form-control cnt')); ?>
                                </div>

                                <div class="col-sm-6">
                                    <label>Instagram Url</label>
                                    <?php echo $this->Form->input('instagram_url',array('type'=>'text','placeholder'=>'http://www.instagram.com','label'=>false,'class'=>'form-control cnt')); ?>
                                </div>

                                <div class="col-sm-6">
                                    <label>YouTube Url</label>
                                    <?php echo $this->Form->input('youtube_url',array('type'=>'text','placeholder'=>'http://www.youtube.com','label'=>false,'class'=>'form-control cnt')); ?>
                                </div>

                                <div class="col-sm-6">
                                    <label>Pinterest Url</label>
                                    <?php echo $this->Form->input('pinterest_url',array('type'=>'text','placeholder'=>'http://www.pintrest.com','label'=>false,'class'=>'form-control cnt')); ?>
                                </div>

                                <div class="col-sm-6">
                                    <label>LinkedIn Url</label>
                                    <?php echo $this->Form->input('linkedin_url',array('type'=>'text','placeholder'=>'http://www.linkedin.com','label'=>false,'class'=>'form-control cnt')); ?>
                                </div>

                            </div>

                            <div class="form-group">
                                <div class="col-sm-3 pull-right">
                                    <?php echo $this->Form->submit('Update',array('class'=>'Btn-typ5','id'=>'signup')); ?>
                                </div>
                            </div>

                            <?php echo $this->Form->end(); ?>

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
    $(document).ready(function(){

        var upload = false;
        $(".channel_tap a").removeClass('active');
        $("#v_add_staff").addClass('active');

    });
</script>






