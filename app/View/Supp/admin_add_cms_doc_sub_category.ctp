<div class="Inner-banner">
    <div class="container">
        <div class="row">

            <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 inner-page-text"> <h2>Doc CMS Subcategories</h2> </div>
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
                            <a href="<?php echo Router::url('/admin/supp/list_cms_doc_sub_category'); ?>" ><i class="fa fa-list"></i> Subcategory List</a>
                            <a href="<?php echo Router::url('/admin/supp/add_cms_doc_sub_category'); ?>" class="active" ><i class="fa fa-list"></i> Add Subcategory</a>
                        </div>


                        <div class="Social-login-box payment_bx">

                            <?php echo $this->element('message'); ?>

                            <?php echo $this->Form->create('CmsDocHealthTipSubCategory',array('type'=>'file','method'=>'post','class'=>'form-horizontal')); ?>


                            <div class="form-group">
                                <div class="col-sm-12">
                                    <label>Subcategory Name</label>
                                    <?php echo $this->Form->input('sub_category_name',array('type'=>'text','placeholder'=>'Subcategory Name','label'=>false,'class'=>'form-control cnt','required'=>true)); ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-12">
                                    <label>Category Name</label>
									<?php echo $this->Form->input('category',array('type'=>'select','empty'=>'Please select','label'=>false,'options'=>array("HEALTH_TIP"=>"Health Tip","EMERGENCY"=>"Emergency","MENGAGE_CHANNEL"=>"mEngage Channel"),'class'=>'form-control cnt','required'=>true)); ?>
								</div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-3 pull-right">
                                    <?php echo $this->Form->submit('Add',array('class'=>'Btn-typ5','id'=>'addEvent')); ?>
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