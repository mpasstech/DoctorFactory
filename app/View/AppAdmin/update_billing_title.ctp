<?php
$login = $this->Session->read('Auth.User');
?>
<style>
    .radio_button label{
        padding: 2px 4px;
    }

    .radio_button legend{
        font-size: 13px;
    }
</style>


<div class="Home-section-2">

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 admit_form_box">
        <div class="container">
            <?php echo $this->element('billing_setting_inner_header'); ?>
            <div class="row">
                <!--box 1 -->
                <!--box 1 -->
                <div class="middle-block">

                    <h3 class="screen_title">Update Receipt Setting</h3>
                    <?php //echo $this->element('app_admin_leftsidebar'); ?>

                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 custom_form_box">


                        <?php echo $this->element('message'); ?>


                        <?php echo $this->Form->create('Thinapp',array('type'=>'file','method'=>'post','class'=>'form-horizontal','id'=>'sub_frm')); ?>


                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">


                            <div class="form-group">

                                <div class="col-sm-3">
                                    <label>Receipt Left Top Title</label>
                                    <?php echo $this->Form->input('receipt_top_left_title',array('type'=>'text','placeholder'=>'','label'=>false,'class'=>'form-control cnt')); ?>
                                </div>

                                <div class="col-sm-3">
                                    <label>Receipt Header Title</label>
                                    <?php echo $this->Form->input('receipt_header_title',array('type'=>'text','placeholder'=>'','label'=>false,'class'=>'form-control cnt')); ?>
                                </div>

                                <div class="col-sm-3">
                                    <label>Receipt Footer Title</label>
                                    <?php echo $this->Form->input('receipt_footer_title',array('type'=>'text','placeholder'=>'','label'=>false,'class'=>'form-control cnt')); ?>
                                </div>
                                <div class="col-sm-3">
                                    <label>Report Title</label>
                                    <?php echo $this->Form->input('report_title',array('type'=>'text','placeholder'=>'','label'=>false,'class'=>'form-control cnt')); ?>
                                </div>

                                <div class="col-sm-4 radio_button">
                                    <?php
                                    $options = array('YES'=>'Yes','NO'=>'No');
                                    $attributes = array('value'=>@$this->request->data['Thinapp']['show_token_on_receipt'],'legend' => "Show token number on receipt?",'class'=>'radio-inline','div'=>'label');
                                    echo $this->Form->radio('show_token_on_receipt', $options, $attributes);
                                    ?>
                                </div>
                                <div class="col-sm-4 radio_button">
                                    <?php
                                    $options = array('YES'=>'Yes','NO'=>'No');
                                    $attributes = array('value'=>@$this->request->data['Thinapp']['show_token_time_on_receipt'],'legend' => "Show token time on receipt?",'class'=>'radio-inline','div'=>'label');
                                    echo $this->Form->radio('show_token_time_on_receipt', $options, $attributes);
                                    ?>
                                </div>
                                <div class="col-sm-4 radio_button">
                                    <?php
                                    $options = array('YES'=>'Yes','NO'=>'No');
                                    $attributes = array('value'=>@$this->request->data['Thinapp']['show_doctor_on_receipt'],'legend' => "Show doctor name on receipt?",'class'=>'radio-inline','div'=>'label');
                                    echo $this->Form->radio('show_doctor_on_receipt', $options, $attributes);
                                    ?>
                                </div>
                                <div class="col-sm-4 radio_button">
                                    <?php
                                    $options = array('YES'=>'Yes','NO'=>'No');
                                    $attributes = array('value'=>@$this->request->data['Thinapp']['show_referrer_on_receipt'],'legend' => "Show referrer name on receipt?",'class'=>'radio-inline','div'=>'label');
                                    echo $this->Form->radio('show_referrer_on_receipt', $options, $attributes);
                                    ?>
                                </div>

                                <div class="col-sm-4 radio_button">
                                    <?php
                                    $options = array('YES'=>'Yes','NO'=>'No');
                                    $attributes = array('value'=>@$this->request->data['Thinapp']['show_patient_mobile_on_receipt'],'legend' => "Show patient's mobile number?",'class'=>'radio-inline','div'=>'label');
                                    echo $this->Form->radio('show_patient_mobile_on_receipt', $options, $attributes);
                                    ?>
                                </div>
                                <div class="col-sm-4 radio_button">
                                    <?php
                                    $options = array('YES'=>'Yes','NO'=>'No');
                                    $attributes = array('value'=>@$this->request->data['Thinapp']['show_department_on_receipt'],'legend' => "Show doctor department?",'class'=>'radio-inline','div'=>'label');
                                    echo $this->Form->radio('show_department_on_receipt', $options, $attributes);
                                    ?>
                                </div>



                                <div class="col-sm-4 radio_button">
                                    <?php
                                    $options = array('YES'=>'Yes','NO'=>'No');
                                    $attributes = array('value'=>@$this->request->data['Thinapp']['show_paid_user_order_number_on_receipt'],'legend' => "Show paid receipt number for OPD?",'class'=>'radio-inline','div'=>'label');
                                    echo $this->Form->radio('show_paid_user_order_number_on_receipt', $options, $attributes);
                                    ?>
                                </div>


                                <div class="col-sm-4 radio_button">
                                    <?php
                                    $options = array('YES'=>'Yes','NO'=>'No');
                                    $attributes = array('value'=>@$this->request->data['Thinapp']['show_date_on_receipt'],'legend' => "Show receipt date?",'class'=>'radio-inline','div'=>'label');
                                    echo $this->Form->radio('show_date_on_receipt', $options, $attributes);
                                    ?>
                                </div>



                                <div class="col-sm-4 radio_button">
                                    <?php
                                    $options = array('YES'=>'Yes','NO'=>'No');
                                    $attributes = array('value'=>@$this->request->data['Thinapp']['show_time_on_receipt'],'legend' => "Show receipt time?",'class'=>'radio-inline','div'=>'label');
                                    echo $this->Form->radio('show_time_on_receipt', $options, $attributes);
                                    ?>
                                </div>

                                <div class="col-sm-4 radio_button">
                                    <?php
                                    $options = array('YES'=>'Yes','NO'=>'No');
                                    $attributes = array('value'=>@$this->request->data['Thinapp']['show_number_of_days_on_receipt'],'legend' => "Show days on IPD receipt?",'class'=>'radio-inline','div'=>'label');
                                    echo $this->Form->radio('show_number_of_days_on_receipt', $options, $attributes);
                                    ?>
                                </div>

                            </div>


                            <div class="form-group">

                                <div class="col-sm-12">

                                    <label>&nbsp;</label>
                                    <?php echo $this->Form->submit('Save',array('class'=>'btn-info btn save_edit_end_btn')); ?>
                                </div>

                            </div>


                        </div>



                    </div>

                    <?php echo $this->Form->end(); ?>
                </div>
                <!-- box 1 -->


            </div>
            <!--box 2 -->

        </div>
    </div>
</div>