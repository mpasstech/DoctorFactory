<?php
$login = $this->Session->read('Auth.User');
?>

<style>
    legend{
        font-size: 13px !important;
        font-weight: 600;
    }
    fieldset label{
        padding: 0px 4px;
    }
    .border_div{
        margin-bottom: 20px;
    }
    .input_validity{
        position: absolute;
        width: 17%;
        padding: 3px 7px;
        float: right;
        margin-left: 110px;
        margin-top: -36px;
        border: 1px solid #d6cdcd;
        border-radius: 3px;
    }

</style>

<div class="Home-section-2">

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="container">
            <div class="row">
                <!--box 1 -->
                <!--box 1 -->
                <h3 class="screen_title">App Permission Setting</h3>
                <div class="middle-block">
                    <!-- Heading -->





                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">


                        <div class="Social-login-box payment_bx">

                            <?php echo $this->element('message'); ?>

                            <h3>App Setting</h3>
                            <?php echo $this->Form->create('Thinapp',array('type'=>'file','method'=>'post','class'=>'form-horizontal','id'=>'sub_frm')); ?>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">


                                <div class="form-group">


                                    <div class="col-sm-8">
                                        <div class="col-sm-6 radio_button">
                                            <?php
                                            $options = array('YES'=>'Yes','NO'=>'No');
                                            $attributes = array('value'=>@$this->request->data['Thinapp']['show_expire_token_slot'],'legend' => "Show expired token slots?",'class'=>'radio-inline','div'=>'label');
                                            echo $this->Form->radio('show_expire_token_slot', $options, $attributes);
                                            ?>
                                        </div>
                                        <div class="col-sm-6 radio_button">
                                            <?php
                                            $options = array('YES'=>'Yes','NO'=>'No');
                                            $attributes = array('global_service_validity'=>@$this->request->data['Thinapp']['global_service_validity'],'legend' => "Same service validity for all service?",'class'=>'radio-inline','div'=>'label');
                                            echo $this->Form->radio('global_service_validity', $options, $attributes);
                                            echo $this->Form->input('service_validity_days',array('type'=>'number','placeholder'=>'','label'=>false,'div'=>false,'class'=>'input_validity','min'=>0,'max'=> '99','required'=>'required'));

                                            ?>
                                        </div>
                                        <div class="col-sm-6">
                                            <label>Web Theme</label>
                                            <?php echo $this->Form->input('web_theme',array('type'=>'select', 'options'=>array('THEME_1'=>'Theme 1','THEME_2'=>'Theme 2'),'label'=>false,'class'=>'form-control cnt')); ?>
                                        </div>
                                        <?php if($this->AppAdmin->check_app_enable_permission($login['User']['thinapp_id'],"SMART_CLINIC")){ ?>
                                            <div class="col-sm-6">
                                                <label>Smart Clinic Tracker Checkin</label>
                                                <?php echo $this->Form->input('smart_clinic_tracker_queue',array('type'=>'select', 'options'=>array('AUTO_ASSIGN'=>'AUTO ASSIGN','MANUAL_ASSIGN'=>'MANUAL ASSIGN'),'label'=>false,'class'=>'form-control cnt')); ?>
                                            </div>

                                            <div class="col-sm-6">
                                                <label>Kiosk booking auto check-in</label>
                                                <?php echo $this->Form->input('kiosk_booking_auto_checkin',array('type'=>'select', 'options'=>array('YES'=>'Yes','NO'=>'No'),'label'=>false,'class'=>'form-control cnt')); ?>
                                            </div>
                                            
                                        <?php } ?>
                                        <div class="col-sm-6">
                                            <label>Appointment List Order</label>
                                            <?php echo $this->Form->input('appointment_list_token_order',array('type'=>'select', 'options'=>array('ASC'=>'Ascending','DESC'=>'Descending'),'label'=>false,'class'=>'form-control cnt')); ?>
                                        </div>
                                        <div class="col-sm-6">
                                            <label>Cashfree App ID</label>
                                            <?php
                                            echo $this->Form->input('cashfree_app_id',array('type'=>'text','placeholder'=>'Cashfree App ID','label'=>false,'div'=>false,'class'=>'input_cashfree form-control'));
                                            ?>
                                        </div>
                                        <div class="col-sm-6">
                                            <label>Cashfree Secret Key</label>

                                            <?php
                                            echo $this->Form->input('cashfree_secret_key',array('type'=>'text','placeholder'=>'Cashfree Secret Key','label'=>false,'div'=>false,'class'=>'input_cashfree form-control'));
                                            ?>
                                        </div>
                                        <div class="col-sm-6">
                                            <label>Multiple Appointment With Same Number</label>
                                            <?php echo $this->Form->input('multiple_booking_from_same_number',array('type'=>'select', 'options'=>array('ACTIVE'=>'Active','INACTIVE'=>'Inactive'),'label'=>false,'class'=>'form-control cnt')); ?>
                                        </div>
                                        
                                        <div class="col-sm-6">
                                            <label>Single Field in OPD Form filling from patient</label>
                                            <?php echo $this->Form->input('patient_single_field_form',array('type'=>'select', 'options'=>array('YES'=>'Yes','NO'=>'No'),'label'=>false,'class'=>'form-control cnt')); ?>
                                        </div>
                                        
                                        
                                         <div class="col-sm-6">
                                            <label>Display sub token on tracker as</label>
                                            <?php echo $this->Form->input('show_sub_token_name_on_tracker',array('type'=>'select', 'options'=>array('YES'=>'Token Number','NO'=>'Emergency'),'label'=>false,'class'=>'form-control cnt')); ?>
                                        </div>
                                        
                                        <div class="col-sm-6">
                                            <label>Allow patient to pay clinic visit fee at booking time</label>
                                            <?php echo $this->Form->input('pay_clinic_visit_fee_online',array('type'=>'select', 'options'=>array('YES'=>'Yes','NO'=>'No'),'label'=>false,'class'=>'form-control cnt')); ?>
                                        </div>
                                        
                                        
                                        

                                    </div>

                                    <div style="padding: 0px;" class="col-sm-4" style="padding: 0px;">
                                        <div class="col-sm-12">
                                            <label>App Open Dialog Box Content</label>
                                            <?php
                                            echo $this->Form->input('custom_dialog_text',array('id'=>'dialog_content','type'=>'textarea','label'=>false,'div'=>false,'class'=>'input_cashfree form-control'));
                                            ?>
                                        </div>
                                    </div>






                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12" style="text-align: right;">
                                        <a style="display: none;" class="btn btn-danger" href="<?php echo Router::url('/app_admin/sms_template/',true); ?>">Sms Template</a>
                                        <button type="submit" class="btn-success btn">Save</button>
                                        <button type="reset" class="btn-warning btn">Reset</button>
                                    </div>
                                </div>



                            </div>
                            <?php echo $this->Form->end(); ?>



                            <h3>User Permission</h3>
                            <div class="form-group row">

                                <div class="col-sm-12">
                                    <?php if(!empty($app_fun_type_list)){ ?>
                                        <?php foreach($app_fun_type_list as $m_key => $m_value ){ ?>


                                        <?php if($m_value['AppFunctionalityType']['label_key'] != "GULLAK"){ ?>
                                        <?php $is_app_enable = $this->AppAdmin->check_app_fun_enable_permission($login['User']['thinapp_id'],$m_value['AppFunctionalityType']['id']); ?>
                                                <?php
                                                $bloc_array =array(11,15);

                                                if($is_app_enable && !in_array($m_value['AppFunctionalityType']['id'],$bloc_array)){ ?>
                                         <div class="app_fun_div" >
                                           <?php
                                                $fun_list = $this->AppAdmin->get_user_function_type_list($m_value['AppFunctionalityType']['id']);
                                                $status_lbl = !empty($is_app_enable)?"Active":"Inactive";
                                           ?>

                                           <label><?php echo $m_value['AppFunctionalityType']['label_value']; ?>
                                                  <label class="btn-xs status_lbl label label-<?php echo !empty($is_app_enable)?"success":"danger"; ?>"><?php echo $status_lbl; ?></label>
                                           </label>
                                        <?php if($is_app_enable){ ?>
                                            <div class="table-responsive permission_table">

                                            <?php if(!empty($fun_list)){ ?>
                                                <table class="table" app-fun-type-id="<?php echo base64_encode($m_value['AppFunctionalityType']['id']);  ?>" app-enb-fun-id="<?php echo base64_encode($is_app_enable['AppEnableFunctionality']['id']);  ?>">
                                                    <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th width="50%">Permision Name</th>
                                                        <th width="30%"></th>
                                                        <th style="text-align: right;">Status</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php foreach ($fun_list as $key => $list){ ?>

                                                    <tr>
                                                        <td><?php echo $key+1; ?></td>
                                                        <td>
                                                            <?php echo $list['UserFunctionalityType']['label_text']; ?>
                                                        </td>

                                                        <?php $is_function_data = $this->AppAdmin->check_app_user_permission($login['User']['thinapp_id'],$list['UserFunctionalityType']['id']); ?>
                                                        <?php if(!empty($is_function_data) && $is_function_data['permission']=="YES"){ ?>
                                                            <td class="has_textarea">
                                                                <?php if($list['UserFunctionalityType']['label_key'] == "DIALOG_BEFORE_APPOINTMENT_BOOKING" || $list['UserFunctionalityType']['label_key'] == "DIALOG_BEFORE_ONLINE_PAYMENT" ){ ?>

                                                                        <?php
                                                                        if($list['UserFunctionalityType']['label_key'] == "DIALOG_BEFORE_ONLINE_PAYMENT"){
                                                                            $vaiInput = !empty($is_function_data['description'])?$is_function_data['description']:"Online payment will not be refundable";
                                                                        }
                                                                        else
                                                                        {
                                                                            $vaiInput = $is_function_data['description'];
                                                                        }
                                                                         ?>
                                                                    <input type="text" class="form-control textarea" placeholder="Enter Dialog Text Here" value="<?php echo $vaiInput; ?>">
                                                                <?php } ?>
                                                            </td>
                                                            <td>
                                                                <button user-fun-type-id="<?php echo base64_encode($list['UserFunctionalityType']['id']);  ?>" user-ena-fun-per-id="<?php echo base64_encode($is_function_data['id']);  ?>" type="button" class="action_btn btn btn-success btn-xs" ><?php echo "ACTIVE"; ?></button>
                                                            </td>
                                                        <?php }else{ ?>
                                                            <td class="has_textarea">
                                                                <?php if($list['UserFunctionalityType']['label_key'] == "DIALOG_BEFORE_APPOINTMENT_BOOKING" || $list['UserFunctionalityType']['label_key'] == "DIALOG_BEFORE_ONLINE_PAYMENT" ){
                                                                    $vaiInput = !empty($is_function_data['description'])?$is_function_data['description']:"Online payment will not be refundable"; ?>
                                                                    <input type="text" class="form-control textarea" placeholder="Enter Dialog Text Here" value="<?php echo $vaiInput; ?>">
                                                                <?php } ?>
                                                            </td>
                                                            <td>
                                                                                                                                <button user-fun-type-id="<?php echo base64_encode($list['UserFunctionalityType']['id']);  ?>" user-ena-fun-per-id="<?php echo base64_encode(0);  ?>" type="button" class="action_btn btn btn-warning btn-xs" ><?php echo "INACTIVE"; ?></button>
                                                            </td>
                                                        <?php } ?>


                                                    </tr>
                                                    <?php } ?>
                                                    </tbody>
                                                </table>
                                            <?php } ?>
                                            </div>
                                        <?php } ?>
                                       </div>


                                    <?php } ?>
                                    <?php } ?>
                                    <?php } ?>


                                    <?php } ?>

                                </div>

                            </div>
                            <div class="clear"></div>
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



        CKEDITOR.replace( "dialog_content",{
            toolbarGroups: [
                { name: 'links', groups: [ 'links' ] },
                { name: 'colors', groups: [ 'colors' ] },
                {name: 'basicstyles', groups: ['basicstyles', 'cleanup']},
                {name: 'styles', groups: ['styles']},
                {name: 'paragraph', groups: ['list', 'indent', 'blocks', 'align', 'bidi', 'paragraph']}
            ],
            removeButtons:'Strike,Subscript,Superscript,BidiLtr,BidiRtl,Language,CopyFormatting,RemoveFormat',
            height:300,
            autoParagraph :false,
            enterMode : CKEDITOR.ENTER_BR,
            shiftEnterMode: CKEDITOR.ENTER_P
        } );


        $(".channel_tap a").removeClass('active');
        $("#v_app_channel_list").addClass('active');

        $(document).on('click','.action_btn',function(e){
            var textarea = $(this).parent().siblings('.has_textarea').find(".textarea");
            var desc ='';
            if(textarea){
                 desc = $.trim($(textarea).val());
            }


            var user_permission_id = $(this).attr('user-ena-fun-per-id');
            var user_fun_type_id = $(this).attr('user-fun-type-id');
            var app_fun_type_id = $(this).closest('table').attr('app-fun-type-id');
            var app_enb_fun_id = $(this).closest('table').attr('app-enb-fun-id');

            var thisButton = $(this);
            $.ajax({
                url: baseurl+'/app_admin/change_user_permission',
                data:{
                    user_permission_id:user_permission_id,
                    user_fun_type_id:user_fun_type_id,
                    app_fun_type_id:app_fun_type_id,
                    app_enb_fun_id:app_enb_fun_id,
                    desc:desc
                },
                type:'POST',
                beforeSend:function(){
                    $(thisButton).button('loading').html('<i class="fa fa-spinner fa-pulse">');
                },
                success: function(result){
                    $(thisButton).button('reset');
                    var result = JSON.parse(result);

                    if(result.status == 1)
                    {
                        $(thisButton).closest('td').html(result.html_string);
                        if(result.html_string.includes("INACTIVE")){
                            $(textarea).hide();
                        }
                        else
                        {
                            $(textarea).show();
                        }
                    }
                    else
                    {
                        alert(result.message);
                    }
                }
            });
        });
        $('.action_btn').prop('disabled', false);

        $(document).on("blur",".has_textarea > .textarea",function(){

            var textarea = $(this);
            var desc = $(textarea).val()
            var btn = $(this).parent().siblings('td').find(".action_btn");
            var user_permission_id = $(btn).attr('user-ena-fun-per-id');
            var user_fun_type_id = $(btn).attr('user-fun-type-id');
            var app_fun_type_id = $(btn).closest('table').attr('app-fun-type-id');
            var app_enb_fun_id = $(btn).closest('table').attr('app-enb-fun-id');

            var thisButton = $(btn);
            $.ajax({
                url: baseurl+'/app_admin/change_user_permission',
                data:{
                    user_permission_id:user_permission_id,
                    user_fun_type_id:user_fun_type_id,
                    app_fun_type_id:app_fun_type_id,
                    app_enb_fun_id:app_enb_fun_id,
                    desc:desc,
                    status:'DONT_CHANGE'
                },
                type:'POST',
                beforeSend:function(){
                    $(thisButton).button('loading').html('<i class="fa fa-spinner fa-pulse">');
                },
                success: function(result){
                    $(thisButton).button('reset');
                    var result = JSON.parse(result);

                    if(result.status == 1)
                    {
                        $(thisButton).closest('td').html(result.html_string);
                        if(result.html_string.includes("INACTIVE")){
                            $(textarea).hide();
                        }
                        else
                        {
                            $(textarea).show();
                        }
                    }
                    else
                    {
                        alert(result.message);
                    }
                }
            });


        });
        $(document).on("change","input[name='data[Thinapp][global_service_validity]']",function(){

               $("#ThinappServiceValidityDays").val(0);

        });


    });
</script>








