<?php
$login = $this->Session->read('Auth.User');
$appointment_palace_list = $this->AppAdmin->getAppointmentAddress($login['User']['thinapp_id']);

?>

<div class="Inner-banner">
    <div class="container">
        <div class="row">
            <!--  SLIDER IMAGE -->
            <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 inner-page-text"> <h2>Appointment Setting</h2> </div>
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
                <!--box 1 -->
                <div class="middle-block">
                    <!-- Heading -->

                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">


                        <div class="Social-login-box payment_bx">

                            <?php echo $this->element('message'); ?>
                            <h3>Add new address</h3>
                            <?php echo $this->Form->create('AppointmentAddress'); ?>


                            <div class="form-group">

                                <div class="col-sm-3 place_textbox">
                                    <?php echo $this->Form->input('place', array('type' => 'text', 'placeholder' => 'Enter place', 'label' => 'Enter place name', 'class' => 'form-control')); ?>
                                </div>

                                <div class="col-sm-3 place_dropdown" style="display: none;">
                                    <?php echo $this->Form->input('place_list', array('type' => 'select','options'=>$appointment_palace_list,'label' => 'Select Place', 'class' => 'form-control')); ?>
                                </div>

                                <div class="col-sm-6">
                                    <?php echo $this->Form->input('address', array('type' => 'text', 'placeholder' => 'Enter address', 'label' => 'Enter address for place', 'class' => 'form-control')); ?>
                                </div>

                                <div class="col-sm-2">
                                    <?php echo $this->Form->label('&nbsp;'); ?>
                                    <?php echo $this->Form->submit('Save',array('class'=>'Btn-typ3','id'=>'search')); ?>
                                </div>
                                <div class="col-sm-12">
                                    <div class="checkbox">
                                        <label><input name="is_new" class="place_checkbox" type="checkbox">Add with exist place</label>
                                    </div>
                                </div>
                            </div>


                            <?php echo $this->Form->end(); ?>


                            <?php echo $this->element('message'); ?>
                            <div class="form-group row">
                                <div class="col-sm-12">

                            <div class="table-responsive">
                            <?php if(!empty($appointment_address_list)){ ?>
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Place</th>
                                        <th width="80%">Address</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($appointment_address_list as $key => $list){?>
                                    <tr>
                                        <td><?php echo $key+1; ?></td>
                                        <td><?php echo $list['AppointmentAddress']['place']; ?></td>
                                        <td class="address_td"><?php echo $list['AppointmentAddress']['address']; ?></td>
                                        <td>
                                            <div class="action_icon">
                                                <?php
                                                echo $this->Html->link('','javascript:void(0);',array('class' => 'fa fa-edit add_edit','data-id'=>base64_encode($list['AppointmentAddress']['id']), 'title' => 'Edit address'));
                                                ?>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                    </tbody>
                                </table>
                                <?php echo $this->element('paginator'); ?>
                            </div>

                            <?php }else{ ?>
                                <div class="no_data">
                                    <h2>You have no address for appointment</h2>
                                </div>
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

        $(document).on('change','.place_checkbox',function(e){
          if($(this).is(":checked")){
              $(".place_dropdown").show();
              $(".place_textbox").hide();
          }else{
              $(".place_textbox").show();
              $(".place_dropdown").hide();
          }
        });

        $(document).on('click','.add_edit',function () {
            var obj = $(this).closest('tr').find(".address_td");
            var data_id =  $(this).attr('data-id');
            var textbox = "<input class='custom_box form-control' type='text' value='"+obj.text()+"'>";
            $(obj).html(textbox);
            var checked = '<a title="Save address" data-id="'+data_id+'" class="fa fa-check update_save" href="javascript:void(0);"></a>';
            $(this).closest(".action_icon").html(checked);
        });



        $(document).on('click','.update_save',function(e){

            var data_id =  $(this).attr('data-id');
            var obj = $(this).closest('tr').find(".address_td");
            var action_icon = $(this).closest('tr').find(".action_icon");
            var address = obj.find('.custom_box').val();
            var thisButton = $(this);
            $.ajax({
                url: baseurl+'/app_admin/update_appo_address',
                data:{data_id:data_id,address:address},
                type:'POST',
                beforeSend:function(){
                    $(thisButton).closest(".action_icon").button('loading').html('<i class="fa fa-spinner fa-pulse">');
                },
                success: function(result){
                    $(thisButton).closest(".action_icon").button('reset');
                    var result = JSON.parse(result);
                    if(result.status == 1)
                    {
                        $(obj).html(address);
                        var check_btn = '<a title="Save address" data-id="'+data_id+'" class="fa fa-edit add_edit" href="javascript:void(0);"></a>';
                        $(action_icon).html(check_btn);
                    }else{
                        alert(result.message);
                    }
                }
            });
        });


    });
</script>






