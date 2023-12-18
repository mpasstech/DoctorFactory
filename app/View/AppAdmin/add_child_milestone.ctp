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
                            <h3 class="screen_title">Add Child Milestone</h3>
                            <?php echo $this->element('child_milestone_tab'); ?>

                            <?php echo $this->Form->create('PrescriptionTag',array('type'=>'file','method'=>'post','class'=>'form-horizontal')); ?>

                            <div class="form-group">


                                <div class="col-sm-12">
                                    <label>Milestone For</label>

                                    <?php echo $this->Form->input('name',array('type'=>'select','label'=>false,'options'=>$this->AppAdmin->getChildMilestonePeriod(),'class'=>'form-control cnt')); ?>

                                </div>
                            </div>

                            <div class="form-group">
                            <div class="col-sm-12">

                                <?php
                                $options = array('MALE' => 'Male', 'FEMALE' => 'Female');
                                $attributes = array('value'=>'MALE','legend' => "Gender",'class'=>'radio-inline','div'=>'label');
                                echo $this->Form->radio('gender', $options, $attributes);

                                ?>
                            </div>
                                </div>



                            <div class="form-group">
                                <div class="col-sm-12">
                                    <label> Description</label>
                                    <?php echo $this->Form->input('description',array('type'=>'textarea','placeholder'=>'Channel description','label'=>false,'id'=>'editor1','class'=>'form-control')); ?>                                </div>
                            </div>


                            <div class="form-group">
                                <div class="col-sm-3 pull-right">
                                    <?php echo $this->Form->submit('Add',array('class'=>'Btn-typ5','id'=>'signup')); ?>
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
        $("#v_add_cms").addClass('active');
        CKEDITOR.replace( 'editor1' );

    });
</script>
    <style>

        fieldset label{
            margin: 0px 6px 0px 4px;
        }

    </style>





