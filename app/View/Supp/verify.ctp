
<div class="Inner-banner">
    <div class="container">
        <div class="row">
            <!--  SLIDER IMAGE -->
            <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 inner-page-text"> <h2>Create Account</h2> </div>
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
                    <div class="col-md-offset-2 col-lg-8 col-md-8 col-sm-12 col-xs-12">
                        <h2 class="title2">Create New APP</h2>
                        <h3 class="title1">Step 1: Organization Information</h3>

                       <?php echo $this->Form->create('Verify',array('url'=>array('controller'=>'users','action'=>'verify'),'class'=>'contact-form','id'=>'verify_form')); ?>
                        <div class="form-group">
                            <div class="col-sm-6">
                                <?php echo $this->Form->input('code',array('placeholder'=>'Enter Code','type'=>'text','label'=>false,'class'=>'form-control cnt','required'=>true)); ?>
                                <?php echo $this->Form->hidden('m_code',array('value'=>@$_REQUEST['m_c']));?>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <?php echo $this->Form->submit('Verify',array('type'=>'submit','id'=>'verify_submit','class'=>'Btn-typ5')); ?>
                        </div>
                        <?php echo $this->Form->end(); ?>

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

        $('#verify_submit').click(function(){
            $("#verify_form").ajaxForm({
                dataType: "json",
                success: function(resp){
                    if(resp.status === 1){
                        window.location.href = '<?php echo Router::url('/'); ?>'+resp.org;
                    }else{
                        $('#verify_response').html(resp.message);
                    }
                }
            }).submit();
        });
    });
</script>