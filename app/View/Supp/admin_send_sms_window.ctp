<div class="Inner-banner">
    <div class="container">
        <div class="row">
            <!--  SLIDER IMAGE -->
            <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 inner-page-text"> <h2>Send Message</h2> </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 pull-right create-btn-box">

            </div>
        </div>
    </div>
</div>
<?php

echo $this->Html->css(array('select2.min.css'));
echo $this->Html->script(array('select2.min.js'));

?>


<div class="Home-section-2">

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="container">
            <div class="row">
                <!--box 1 -->
                <div class="middle-block">
                    <!-- Heading -->

                    <?php echo $this->element('support_admin_leftsidebar'); ?>
                    <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">

                        <div class="Social-login-box dashboard_box" style="padding: 15px; 20px;">
                            <h1>Send SMS To App Admins</h1>
                            <?php echo $this->element('message'); ?>
                            <?php echo $this->Form->create('sms',array('type'=>'post','url'=>array('controller'=>'supp','action' => 'send_sms_window/APP_ADMIN'),'admin'=>true)); ?>
                            <div class="form-group">
                                <div class="row">
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <?php echo $this->Form->input('message',array('required'=>'required','type'=>'textarea','label'=>"Enter Your Message Heare",'class'=>'form-control message_box')); ?>
                                        </div>
                                        <div class="col-sm-6">
                                            <?php
                                             $type_array =array(
                                                     '50'=>"50 Doctors",
                                                     '100'=>"100 Doctors",
                                                     '150'=>"150 Doctors",
                                                     '200'=>"200 Doctors",
                                                     '300'=>"300 Doctors",
                                                     '400'=>"400 Doctors",
                                                     '500'=>"500 Doctors",
                                                     '600'=>"600 Doctors",
                                                     '-1'=>"All Doctors"
                                             );
                                            ?>
                                            <?php echo $this->Form->input('numbers',array('required'=>'required','type'=>'select','label'=>"Send message to Top",'empty'=>'Select Numbers','options'=>$type_array,'class'=>'form-control')); ?>
                                        </div>

                                        <div class="col-sm-6">
                                            <label style="display: block;">&nbsp;</label>
                                            <button class="btn btn-info send_sms_btn" data-number="100" type="submit" > Send Message</button>

                                        </div>


                                    </div>
                                </div>
                            </div>
                            <?php echo $this->Form->end(); ?>
                            <div class="clear"></div>
                        </div>

                        <div class="Social-login-box dashboard_box" style="padding: 15px; 20px;">
                            <h1>Send SMS To Futuristic Technologies Susbscribers</h1>
                            <?php echo $this->element('message'); ?>
                            <?php echo $this->Form->create('sms',array('type'=>'post','url'=>array('controller'=>'supp','action' => 'send_sms_window/FT'),'admin'=>true)); ?>
                            <div class="form-group">
                                <div class="row">
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <?php echo $this->Form->input('message',array('required'=>'required','type'=>'textarea','label'=>"Enter Your Message Heare",'class'=>'form-control message_box')); ?>
                                            <label>Add #UNSUBSCRIBE# var in message to send unsubscribe link AND #WHATSAPP# for whatsapp</label>
                                        </div>

                                        <div class="col-sm-4">
                                            <?php
                                            $type_array =array(
                                                'TEST_USER'=>"Test Users",
                                                'ALL_USER'=>"All Subscribed Users"
                                            );
                                            ?>
                                            <?php echo $this->Form->input('send_to',array('required'=>'required','type'=>'select','label'=>"Send Only To",'options'=>$type_array,'class'=>'form-control')); ?>
                                        </div>

                                        <div class="col-sm-4">
                                            <?php
                                            $type_array =array(
                                                'all'=>"All",
                                                'HOSPITAL'=>"Hospital",
                                                'DOCTOR'=>"Doctor"
                                            );
                                            ?>
                                            <?php echo $this->Form->input('role',array('required'=>'required','type'=>'select','label'=>"Send message to",'options'=>$type_array,'class'=>'form-control')); ?>
                                        </div>

                                        <div class="col-sm-4">
                                            <?php
                                            $type_array =array(
                                                '0,1000'=>"1 To 1000",
                                                '1000,1000'=>"1000 To 2000",
                                                '2000,1000'=>"2000 To 3000",
                                                '3000,1000'=>"3000 To 4000",
                                                '4000,1000'=>"4000 To 5000",
                                                '5000,1000'=>"5000 To 6000"
                                            );
                                            ?>
                                            <?php echo $this->Form->input('limit',array('required'=>'required','type'=>'select','label'=>"Send message to limit",'options'=>$type_array,'class'=>'form-control')); ?>
                                        </div>

                                        <div class="col-sm-6">
                                            <label style="display: block;">&nbsp;</label>
                                            <button class="btn btn-info send_sms_btn" data-number="100" type="submit" > Send Message</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php echo $this->Form->end(); ?>
                            <div class="clear"></div>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
   .btn{
       padding: 10px 20px;
   }

</style>

<script type="text/javascript">
    $(function(){

        $(document).on('submit','form',function(e) {
            if(!confirm("Are you sure you want to send?")){
                return false;
            }
        });

    });
</script>
