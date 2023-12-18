<?php
$login = $this->Session->read('Auth.User');
?>
<div class="Inner-banner">
    <div class="container">
        <div class="row">
            <!--  SLIDER IMAGE -->
            <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 inner-page-text"> <h2>Doctor Referral</h2> </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 pull-right create-btn-box">
            </div>
        </div>
    </div>
</div>

<style>
.box-form {
    padding: 5px;
    border: 1px solid;
    margin: 7px;
    border-radius: 6px;
}
</style>

<div class="Home-section-2">

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="container">
            <div class="row">
                <!--box 1 -->
                <!--box 1 -->
                <div class="middle-block">
                    <!-- Heading -->
                    <!--left sidebar-->

                    <?php echo $this->element('support_admin_leftsidebar'); ?>
                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">





                        <div class="progress-bar channel_tap">

                            <a id="v_app_channel_list" href="<?php echo Router::url('list_referral_users'); ?>"><i class="fa fa-list"></i> List Doctor Referral</a>

                            <a id="v_add_channel" class='active'  href="<?php echo Router::url('add_referral_users'); ?>" ><i class="fa fa-television"></i> Add Doctor Referral</a>


                        </div>
                        <style>
                            .channel_tap a{ width:33% !important; }
                        </style>




                        <div class="Social-login-box payment_bx">

                            <?php echo $this->element('message'); ?>

                            <?php echo $this->Form->create('DoctorRefferal',array('type'=>'file','method'=>'post','class'=>'form-horizontal')); ?>

                        <div id="addMore">
                            <div class="box-form">

                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <label>Name</label>
                                            <?php echo $this->Form->input('reffered_name.',array('type'=>'text','placeholder'=>'Reffered Name','label'=>false,'class'=>'form-control cnt','required'=>true)); ?>
                                        </div>
                                    </div>                                    
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <label>Mobile</label>
                                            <?php echo $this->Form->input('reffered_mobile.',array('type'=>'text','placeholder'=>'Reffered Mobile','label'=>false,'class'=>'form-control cnt','required'=>true)); ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <label>Thinapp</label>
                                            <?php echo $this->Form->input('thinapp_id.',array('type'=>'select','options'=>$thinappList,'empty'=>'Select Thinapp','label'=>false,'class'=>'form-control cnt','required'=>true)); ?>
                                        </div>
                                    </div>

                            </div>
                        </div>

                            <div class="form-group">
                                <div class="col-sm-3 pull-left">
                                <button type="button" class="btn btn-primary btn-xs" id="addMoreBtn" >Add More</button>
                                </div>
                            </div>


                            <div class="form-group">
                                <div class="col-sm-3 pull-right">
                                    <?php echo $this->Form->submit('Submit',array('class'=>'Btn-typ5')); ?>
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
    $(document).on("click","#addMoreBtn",function(){
        $("#addMore").append('<div class="box-form"><div class="form-group"><div class="col-sm-12"><label>Name</label><div class="input text"><input name="data[DoctorRefferal][reffered_name][]" placeholder="Reffered Name" class="form-control cnt" required="required" id="DoctorRefferalRefferedName" type="text"></div></div></div><div class="form-group"><div class="col-sm-12"><label>Mobile</label><div class="input text"><input name="data[DoctorRefferal][reffered_mobile][]" placeholder="Reffered Mobile" class="form-control cnt" required="required" id="DoctorRefferalRefferedMobile" type="text"></div></div></div><div class="form-group"><div class="col-sm-12"><label>Thinapp</label><div class="input select"><select name="data[DoctorRefferal][thinapp_id][]" class="form-control cnt" required="required" id="DoctorRefferalThinappId"><option value="">Select Thinapp</option><?php foreach($thinappList AS $key => $dataShow){ ?><option value="<?php echo $key; ?>"><?php echo preg_replace("/[^a-zA-Z]/", " ", $dataShow); ?></option><?php }?></select></div></div></div></div>');
    });
</script>