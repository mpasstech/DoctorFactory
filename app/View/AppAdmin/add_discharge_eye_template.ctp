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



                    <h3 class="screen_title">Add Discharge Template</h3>


                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 custom_form_box print_box">


                        <?php echo $this->element('message'); ?>


                        <?php echo $this->Form->create('HospitalDischargeTemplate',array('type'=>'file','method'=>'post','class'=>'form-horizontal')); ?>



                        <div class="value_form col-lg-12 col-md-12 col-sm-12 col-xs-12">

                            <div class="form-group">

                                <div class="col-sm-2">
                                    <label>Template Name</label>
                                    <?php echo $this->Form->input('name',array('type'=>'text','placeholder'=>'','label'=>false,'class'=>'form-control cnt')); ?>

                                </div>

                                <div class="col-sm-2">
                                    <label>Rohini ID</label>
                                    <?php echo $this->Form->input('rohini_id',array('type'=>'text','placeholder'=>'','label'=>false,'class'=>'form-control cnt')); ?>

                                </div>
                                <div class="col-sm-1">
                                    <label>Lama</label>
                                    <?php echo $this->Form->input('lama',array('type'=>'text','placeholder'=>'','label'=>false,'class'=>'form-control cnt')); ?>

                                </div>
                                <div class="col-sm-1">
                                    <label>Death</label>
                                    <?php echo $this->Form->input('death',array('type'=>'text','placeholder'=>'','label'=>false,'class'=>'form-control cnt')); ?>

                                </div>
                                <div class="col-sm-1">
                                    <label>MLC</label>
                                    <?php echo $this->Form->input('mlc',array('type'=>'text','placeholder'=>'','label'=>false,'class'=>'form-control cnt')); ?>

                                </div>

                            </div>


                            <div class="form-group">

                                <div class="col-sm-12">

                                    <label>Drug Allergies :-</label>
                                    <?php echo $this->Form->input('drug_allergies',array('type'=>'textarea','placeholder'=>'','label'=>false,'class'=>'form-control cnt')); ?>
                                </div>


                                <div class="col-sm-12">

                                    <label>Final Diagnosis :-</label>
                                    <?php echo $this->Form->input('final_diagnosis',array('type'=>'textarea','placeholder'=>'','label'=>false,'class'=>'form-control cnt')); ?>
                                </div>

                            </div>

                            <div class="form-group">

                                <div class="col-sm-12">

                                    <label>Chief Complaints :-</label>
                                    <?php echo $this->Form->input('chief_complaints',array('type'=>'textarea','placeholder'=>'','label'=>false,'class'=>'form-control cnt')); ?>
                                </div>


                                <div class="col-sm-12">

                                    <label>Patient History :-</label>
                                    <?php echo $this->Form->input('patient_history',array('type'=>'textarea','placeholder'=>'','label'=>false,'class'=>'form-control cnt')); ?>
                                </div>

                            </div>


                            <div class="form-group">

                                <div class="col-sm-12">

                                    <label>Clinical Examination :-</label>
                                    <?php echo $this->Form->input('clinical_examination',array('type'=>'textarea','placeholder'=>'','label'=>false,'class'=>'form-control cnt')); ?>
                                </div>


                                <div class="col-sm-12">
                                    <label>Investigation Details :-</label>
                                    <?php echo $this->Form->input('investigation_detail',array('type'=>'textarea','placeholder'=>'','label'=>false,'class'=>'form-control cnt')); ?>


                                </div>

                            </div>





                            <div class="form-group">

                                <div class="col-sm-12">


                                    <label>Treatment Given :-</label>
                                    <?php echo $this->Form->input('treatment_given',array('type'=>'textarea','placeholder'=>'','label'=>false,'class'=>'form-control cnt')); ?>


                                </div>


                                <div class="col-sm-12">

                                    <label>Condition At Discharge :- </label>
                                    <?php echo $this->Form->input('condition_at_discharge',array('type'=>'textarea','placeholder'=>'','label'=>false,'class'=>'form-control cnt')); ?>
                                </div>

                            </div>

                            <div class="form-group">

                                <div class="col-sm-12">

                                    <label>Discharge Advice :-</label>
                                    <?php echo $this->Form->input('discharge_advice',array('type'=>'textarea','placeholder'=>'','label'=>false,'class'=>'form-control cnt')); ?>
                                </div>


                                <div class="col-sm-12">

                                    <label>Medication :-</label>
                                    <?php echo $this->Form->input('medication',array('type'=>'textarea','placeholder'=>'','label'=>false,'class'=>'form-control cnt')); ?>
                                </div>

                            </div>

                            <div class="form-group">

                                <div class="col-sm-12">

                                    <label>Follow Up :-</label>
                                    <?php echo $this->Form->input('follow_up',array('type'=>'textarea','placeholder'=>'','label'=>false,'class'=>'form-control cnt')); ?>
                                </div>

                                <div class="col-md-3 col-md-offset-10 save_btn_box">

                                    <label>&nbsp;</label>


                                    <?php if(in_array($login['USER_ROLE'],array('ADMIN','RECEPTIONIST'))){ ?>
                                        <?php echo $this->Form->button('Save',array('div'=>false,'type'=>'submit','class'=>'btn btn-info','id'=>'save')); ?>
                                        <button type="reset" class="btn-success btn" >Reset</button>
                                    <?php } ?>

                                </div>

                            </div>


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






<style>
    body, .form-control{
        color:#000;

    }
    textarea{
        height: 70px !important;
    }
    .print_content{height: 100%!important;overflow: visible;}
    .print_btn{
        float: right;
        position: relative;
        right: 20px;
        top: 20px;

    }
    .saprate_div{
        background: #9a989840;
        min-height: 157px;
        margin: 0px 2px;
        /*width: 49%;*/
        padding: 0px 6px;
    }

    .div_first{
        width: 58%;
        float: left;
        border-right: 1px solid;
        margin-right: 3px !important;
    }
    .div_second{width: 40%; float: left}

    .patient_form_group{
        margin-bottom: 0px;
        padding:0px;

        float:left;
    }
    .saprate_div h3{
        font-size: 14px;
        text-decoration: underline;
    }

    #signup{
        width: 100%;
    }

    div[class^="col-sm-"]{padding: 0px 1px;margin: 0px;   }
    .value_form div[class^="col-sm-"]{
        margin-bottom: 15px;
    }

    .form-control{marging-top: 10px; margin-bottom: 2px !important;}


    span{margin: 0px;padding: 0px;} label{margin-bottom: 0px !important;}
    .value_form .form-group{
        margin-bottom: 6px;
    }

    .value_form{
        padding-right: 3px;
        padding-left: 3px;
    }
    .value_form .form-group {

        margin-left: 0px;
    }
    .form-control {
        width: 100%;
        height: 30px;
        padding: 1px 3px;
    }
    .col-sm-3 {
        width: 20%;
    }
    .ui-autocomplete {
        max-width: 92%;
        max-height: 70px !important;
        overflow-y: auto !important;
        overflow-x: auto !important;
        padding-right: 20px !important;
    }
    .template_btn {
        float: left;
        position: relative;
        right: 20px;
        top: 20px;
    }
</style>


