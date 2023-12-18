<div class="Inner-banner">
    <div class="container">
        <div class="row">

            <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 inner-page-text"> <h2>Gift List</h2> </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 pull-right create-btn-box">
            </div>
        </div>
    </div>
</div>


<section class="Home-section-2">

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="container">
            <div class="row">

                <div class="middle-block">

                   <?php echo $this->element('support_admin_leftsidebar'); ?>
                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                        <div class="progress-bar channel_tap">
                            <a href="<?php echo Router::url('/admin/supp/gift_list'); ?>"   ><i class="fa fa-list"></i> Gift List</a>
                            <a href="<?php echo Router::url('/admin/supp/gift_redeem_list'); ?>" ><i class="fa fa-list"></i> Redeem</a>
                            <a href="<?php echo Router::url('/admin/supp/add_gift'); ?>"  ><i class="fa fa-list"></i> Add Gift</a>
                        </div>


                        <div class="Social-login-box payment_bx">

                            <?php echo $this->element('message'); ?>

                            <?php echo $this->Form->create('Gift',array('type'=>'file','method'=>'post','class'=>'form-horizontal')); ?>


                            <div class="form-group">
                                <div class="col-sm-12">
                                    <label>Gift Name</label>
                                    <?php echo $this->Form->input('gift_name',array('type'=>'text','placeholder'=>'Gift name','label'=>false,'class'=>'form-control cnt','required'=>true)); ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-12">
                                    <label>Gift Description</label>
                                    <?php echo $this->Form->input('gift_description',array('type'=>'textarea','placeholder'=>'Gift description','label'=>false,'class'=>'form-control cnt','required'=>true)); ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-12">
                                    <label>Points</label>
                                    <?php echo $this->Form->input('points',array('type'=>'number','placeholder'=>'Points','label'=>false,'class'=>'form-control cnt','required'=>true)); ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-12">
                                    <label>Quantity</label>
                                    <?php echo $this->Form->input('quantity',array('type'=>'number','placeholder'=>'Points','label'=>false,'class'=>'form-control cnt','required'=>true)); ?>
                                    <?php echo $this->Form->input('redeem_count',array('type'=>'hidden','label'=>false,'required'=>true)); ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-12">
                                    <label>End Date</label>
                                    <?php echo $this->Form->input('end_date',array('type'=>'text','placeholder'=>'End Date','label'=>false,'class'=>'form-control cnt','required'=>true,'readonly'=>'readonly')); ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-12">
                                    <label>End Time</label>
                                    <?php echo $this->Form->input('end_time',array('type'=>'text','placeholder'=>'End Time','label'=>false,'class'=>'form-control cnt','required'=>true)); ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-12">
                                    <label>Image</label>
                                    <?php echo $this->Form->input('image',array('type'=>'file','accept'=>'image/*','label'=>false,'class'=>'form-control cnt')); ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-3 pull-right">
                                    <?php echo $this->Form->submit('Edit Gift',array('class'=>'Btn-typ5','id'=>'addEvent')); ?>
                                </div>
                            </div>



                            <?php echo $this->Form->end(); ?>


                        </div>



                    </div>






                </div>



            </div>



        </div>
    </div>
</section>




<script>
    $(document).ready(function () {

            $('#GiftEndDate').datepicker({
                    startDate: new Date(),
                    minDate: new Date(),
                    autoclose: true,
                    format: 'yyyy-mm-dd'
            });

            $(document).ready(function () {
                $('#GiftEndTime').timepicker({'timeFormat': 'H:i:s'});
                $('#GiftEndTime').keydown(function () { return false; });
            });


    });
</script>