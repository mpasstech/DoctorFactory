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

                    <?php // echo $this->element('admin_leftsidebar'); ?>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <?php echo $this->element('app_admin_supplires'); ?>


                        <div class="Social-login-box payment_bx">

                            <?php echo $this->element('message'); ?>

                            <?php echo $this->Form->create('Supplier',array('type'=>'file','method'=>'post','class'=>'form-horizontal')); ?>


                            <div class="form-group">
                                <div class="col-sm-6">
                                    <label>Name<span class="red">*</span></label>
                                    <?php echo $this->Form->input('name',array('type'=>'text','placeholder'=>'Supplier Name','label'=>false,'class'=>'form-control cnt','required'=>true)); ?>

                                </div>
                                <div class="col-sm-6">
                                    <label>Telephone</label>
                                    <?php echo $this->Form->input('tel',array('type'=>'text','placeholder'=>'Supplier Telephone','label'=>false,'class'=>'form-control cnt','required'=>false)); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-6">
                                    <label>Mobile<span class="red">*</span></label>
                                    <?php echo $this->Form->input('mobile',array('type'=>'text','placeholder'=>'Supplier Mobile','label'=>false,'class'=>'form-control cnt','required'=>true)); ?>

                                </div>
                                <div class="col-sm-6">
                                    <label>Whatsapp Mobile</label>
                                    <?php echo $this->Form->input('whatsapp_mobile',array('type'=>'text','placeholder'=>'Supplier Whatsapp Mobile','label'=>false,'class'=>'form-control cnt','required'=>false)); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-6">
                                    <label>Email</label>
                                    <?php echo $this->Form->input('email',array('type'=>'email','placeholder'=>'Supplier Email','label'=>false,'class'=>'form-control cnt','required'=>false)); ?>

                                </div>
                                <div class="col-sm-6">
                                    <label>Address</label>
                                    <?php echo $this->Form->input('address',array('type'=>'text','placeholder'=>'Supplier Address','label'=>false,'class'=>'form-control cnt','required'=>false)); ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-2 col-sm-offset-5">
                                    <?php echo $this->Form->submit('Add Supplier',array('class'=>'Btn-typ5')); ?>
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