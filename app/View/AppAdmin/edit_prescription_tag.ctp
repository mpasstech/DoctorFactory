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

                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">



                        <div class="Social-login-box payment_bx">

                            <?php echo $this->element('message'); ?>
                            <h3 class="screen_title">Edit Prescription Tag</h3>
                            <?php echo $this->element('prescription_tab'); ?>
                            <?php echo $this->Form->create('PrescriptionTag',array('type'=>'file','method'=>'post','class'=>'form-horizontal')); ?>


							<div class="form-group">
                                <div class="col-sm-12">
                                    <label>Name</label>
                                    <?php echo $this->Form->input('name',array('type'=>'text','placeholder'=>'Page Title','label'=>false,'class'=>'form-control cnt')); ?>

                                </div>
                            </div>
							


                            <div class="form-group">
                                <div class="col-sm-12">
                                    <label>Description</label>
                                    <?php echo $this->Form->input('description',array('type'=>'textarea','placeholder'=>'Channel description','label'=>false,'id'=>'editor1','class'=>'form-control')); ?>                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-6">
                                    <label>Status</label>
                                    <?php echo $this->Form->input('status',array('type'=>'select','label'=>false,'options'=>array('ACTIVE'=>'Active','INACTIVE'=>'Inactive'),'class'=>'form-control cnt')); ?>
                                </div>
							


                            <div class="form-group">
                                <div class="col-sm-3 pull-right">
                                    <label>&nbsp;</label>
                                    <?php echo $this->Form->submit('Save',array('class'=>'Btn-typ5','id'=>'signup')); ?>
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


    $(".channel_tap a").removeClass('active');
    $("#v_app_cms_list").addClass('active');


    CKEDITOR.replace( 'editor1');

    $('#CmsDocDashboardTitle').on('keyup', function() {
        limitText(this, 256)
    });

    function limitText(field, maxChar){
        var ref = $(field),
            val = ref.val();
        if ( val.length >= maxChar ){
            ref.val(function() {
                console.log(val.substr(0, maxChar))
                return val.substr(0, maxChar);
            });
        }
    }


});
</script>





