<?php
    $login = $this->Session->read('Auth.User');

echo $this->Html->css(array('easy-autocomplete.min.css'),array("media"=>'all','fullBase' => true));
echo $this->Html->script(array('jquery.easy-autocomplete.min.js','flash-alert.js'),array('fullBase' => true));



?>




<div class="Home-section-2">

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="container">
            <div class="row">
                <!--box 1 -->
                <!--box 1 -->


                <div class="middle-block">
                    <!-- Heading -->
                    <h3 class="screen_title">Doctor Setting</h3>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 custom_form_box">
                        <?php echo $this->element('message'); ?>


                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 hospital_search_box">
                            <div class="row">

                                <div class="progress-bar channel_tap">
                                    <a style="width: 20%;" id="hours_tab" href="<?php echo Router::url("/app_admin/doctor_setting/$doctor_id_url"); ?>"><i class="fa fa-clock-o"> </i> Hours Setting </a>
                                    <a style="width: 20%;" id="address_tab" href="<?php echo Router::url("/app_admin/doctor_setting/$doctor_id_url/address"); ?>" ><i class="fa fa-map-pin"> </i> Address Setting</a>
                                    <a style="width: 20%;" id="break_tab" href="<?php echo Router::url("/app_admin/doctor_setting/$doctor_id_url/break"); ?>" ><i class="fa fa-coffee"> </i> Break Setting</a>
                                    <a style="width: 20%;" id="service_tab" href="<?php echo Router::url("/app_admin/doctor_setting/$doctor_id_url/service"); ?>" ><i class="fa fa-coffee"> </i> Service Setting</a>
                                </div>
                            </div>

                        </div>

                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 hospital_table_box">
                            <div class="row">

                                    <?php if($type=='address'){ ?>

                                        <div class="table-responsive">
                                            <form method="post" id="address_form">
                                                <table id="example" class="table">
                                                    <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Address</th>
                                                        <th>Time From</th>
                                                        <th>Time To</th>
                                                        <th>Checked</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php foreach ($address_list as $key => $list){ ?>
                                                        <tr>
                                                            <?php

                                                            //   $status =  $list['AppointmentStaffHour']['status'];
                                                            ?>

                                                            <td><?php echo $key+1; ?></td>
                                                            <td><?php echo $list['address']; ?>  </td>
                                                            <td><input name="from_time[<?php echo $list['address_id']; ?>]" onkeydown="return false" class="form-control time_box ft" type="text" value="<?php echo $list['from_time']; ?>" ></td>
                                                            <td><input name="to_time[<?php echo $list['address_id']; ?>]" onkeydown="return false" class="form-control time_box tt" type="text" value="<?php echo $list['to_time']; ?>" ></td>
                                                            <td>
                                                                <input class="address_checkbox" type="checkbox" name="box[<?php echo $list['address_id']; ?>]" <?php echo !empty($list['selected'])?'checked':''; ?> >
                                                            </td>
                                                        </tr>




                                                    <?php } ?>

                                                    <tr>
                                                        <td colspan="5" style="text-align: right;">
                                                            <button type="reset" class="btn  btn-warning">Reset</button>
                                                            <button type="button" class="btn  btn-info" id="address_list_btn">Manage Address</button>
                                                            <button type="submit" class="btn  btn-success">Save</button>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </form>
                                        </div>

                                    <?php }else if($type=='break'){ ?>
                                          <div class="table-responsive">
                                                <form method="post" id="break_form">
                                                    <table id="example" style="width: 100%;" class="table">
                                                        <tbody>
                                                        <?php foreach ($break_array as $key => $list){


                                                            $dowMap = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');
                                                            $day = $dowMap[$list['appointment_day_time_id'] - 1];
                                                            $day_num =  $list['appointment_day_time_id'];

                                                            ?>
                                                            <tr style="background: #6d6d6d;color: #fff;">
                                                                <th style="width: 90%;"><?php echo $day; ?></th>
                                                                <th><button type="button" data-day="<?php echo $list['appointment_day_time_id']; ?>" class="btn btn-info add_more_break"><i class="fa fa-plus"></i> Add Break</button></th>
                                                            </tr>

                                                                <tr>
                                                                    <td colspan="2">
                                                                        <table style="width: 100%; border: none;" id="inner_table" class="table">
                                                                            <thead>
                                                                            <tr>
                                                                                <th>#</th>
                                                                                <th style="width: 45%;">Time From</th>
                                                                                <th style="width: 45%;">Time To</th>
                                                                                <th></th>

                                                                            </tr>
                                                                            </thead>
                                                                            <tbody id="tbody<?php echo $day_num; ?>" class="append_child_table">
                                                                            <?php if($list['data_list']){ ?>
                                                                                <?php foreach ($list['data_list'] as $key_break => $break){ ?>
                                                                                                <tr>
                                                                                                    <td><?php echo $key_break+1; ?></td>
                                                                                                    <td><input name="<?php echo $day_num."[from_time][]"; ?>" onkeydown="return false" class="form-control time_box" type="text" value="<?php echo $break['time_from']; ?>" ></td>
                                                                                                    <td><input name="<?php echo $day_num."[to_time][]"; ?>" onkeydown="return false" class="form-control time_box" type="text" value="<?php echo $break['time_to']; ?>" ></td>
                                                                                                    <td><button type="button" class="btn btn-danger delete_break"><i class="fa fa-trash"></i></button></td>
                                                                                                </tr>
                                                                                            <?php } ?>

                                                                            <?php } ?>
                                                                            </tbody>
                                                                        </table>
                                                                    </td>

                                                                </tr>



                                                        <?php } ?>

                                                        <tr>
                                                            <td colspan="2" style="text-align: right;">
                                                                <button type="reset" class="btn  btn-warning">Reset</button>
                                                                <button type="submit" class="btn  btn-success">Save</button>
                                                            </td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </form>
                                            </div>
                                    <?php }else if($type=='service') { ?>

                                        <div class="table-responsive">
                                            <form method="post" id="service_form">
                                                <table id="example" class="table" style="padding: 8px;">
                                                    <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th><b>Service Name</b></th>
                                                        <th>Clinc/ Hospital Visit Fee</th>
                                                        <th>Video Consultation Fee</th>
                                                        <th>Audio Consultation Fee</th>
                                                        <th>Chat Consultation Fee</th>
                                                        <th>Select</th>

                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php foreach ($service_list as $key => $list){ ?>
                                                        <tr>

                                                            <td><?php echo $key+1; ?></td>
                                                            <td><?php echo $list['name']; ?>  </td>
                                                            <td><i class="fa fa-inr" aria-hidden="true"></i> <?php echo $list['service_amount']; ?>  </td>
                                                            <td><i class="fa fa-inr" aria-hidden="true"></i> <?php echo $list['video_consulting_amount']; ?>  </td>
                                                            <td><i class="fa fa-inr" aria-hidden="true"></i> <?php echo $list['audio_consulting_amount']; ?>  </td>
                                                            <td><i class="fa fa-inr" aria-hidden="true"></i> <?php echo $list['chat_consulting_amount']; ?>  </td>

                                                            <td>
                                                                <input class="selected_service" type="radio" name="selected_service" value="<?php echo $list['id']; ?>" <?php echo !empty($list['selected'])?'checked':''; ?> >
                                                            </td>
                                                        </tr>

                                                    <?php } ?>

                                                    <tr>
                                                        <td colspan="7" style="text-align: right;">
                                                            <button type="reset" class="btn  btn-warning">Reset</button>
                                                            <button type="submit" class="btn  btn-success">Save</button>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </form>
                                        </div>

                                    <?php }else{ ?>

                                             <div class="table-responsive">
                                        <form method="post">
                                            <table id="hours_table" class="table">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Day</th>
                                            <th>Time From</th>
                                            <th>Time To</th>
                                            <th>Status</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php foreach ($hours_list as $key => $list){ ?>


                                                <tr>
                                                    <?php
                                                    $dowMap = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday','Sunday');
                                                    $day = $dowMap[$list['AppointmentStaffHour']['appointment_day_time_id']-1];
                                                    $id = $list['AppointmentStaffHour']['id'];

                                                    $status =  $list['AppointmentStaffHour']['status'];
                                                    ?>

                                                    <td><?php echo $key+1; ?></td>
                                                    <td><?php echo $day; ?>  </td>
                                                    <td><input name="from_time[<?php echo $id; ?>]" onkeydown="return false" class="form-control time_box" type="text" value="<?php echo $list['AppointmentStaffHour']['time_from']; ?>" ></td>
                                                    <td><input name="to_time[<?php echo $id; ?>]" onkeydown="return false" class="form-control time_box" type="text" value="<?php echo $list['AppointmentStaffHour']['time_to']; ?>" ></td>
                                                    <td>
                                                        <select class="form-control" name="status[<?php echo $id; ?>]" >
                                                            <option value="OPEN" <?php echo ($status=='OPEN')?'selected':''; ?> >OPEN</option>
                                                            <option value="CLOSED" <?php echo ($status=='CLOSED')?'selected':''; ?>>CLOSED</option>
                                                        </select>
                                                    </td>
                                                </tr>




                                        <?php } ?>

                                        <tr>
                                            <td colspan="5" style="text-align: right;">
                                                <button type="reset" class="btn  btn-warning">Reset</button>
                                                <button type="submit" class="btn  btn-success">Save</button>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                        </form>
                                    </div>

                                    <?php } ?>

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

<style>
    #example_length {
        width: 32%;
        text-align: right;
    }
    .option_btn_panel .btn{
        width: 49%;
        float: left;
        height: 22px;
        margin: 1px 1px;
    }

    .form-control {
        width: 100%;
        height: 30px;
        padding: 6px 5px;
    }

    .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {

    }

    .alert .header{
        position: relative !important;
    }

</style>


<script>
    $(document).ready(function(){

        $(".channel_tap a").removeClass('active');
        $("#<?php echo $tab; ?>").addClass('active');
        $('.time_box').datetimepicker({
            format: 'hh:mm A'
        });

        $(document).on('submit','#address_form',function(e){
            var allow = true;
            $("#address_form input").css('border-color',"#ccc");
            $(this).find(".address_checkbox:checked").each(function (index,value) {
                var from_time = $(this).closest('tr').find('.ft');
                var to_time = $(this).closest('tr').find('.tt');
                if($(from_time).val() ==''){
                    allow = false;
                    $(from_time).css('border-color',"red");
                }
                if($(to_time).val() ==''){
                    allow = false;
                    $(to_time).css('border-color',"red");
                }
            });
            if(allow===false){
                e.preventDefault();
            }
        });


        $(document).on('submit','#break_form',function(e){
            var allow = true;
            $("#break_form input").css('border-color',"#ccc");
            $(this).find("input").each(function (index,value) {
                var from_time = $(this);
                var to_time = $(this);
                if($(from_time).val() ==''){
                    allow = false;
                    $(from_time).css('border-color',"red");
                }
                if($(to_time).val() ==''){
                    allow = false;
                    $(to_time).css('border-color',"red");
                }
            });
            if(allow===false){
                e.preventDefault();
            }
        });

        $(document).on('click','.delete_break',function(e){
            $(this).closest('tr').remove();
        });

        $(document).on('submit','#service_form',function(e){
            if($(this).find('.selected_service:checked').length==0){
                alert('Please select one service to save setting');
                return false;
            }else{
                if(!confirm('Are you sure you want to updae service? if you press "OK" then your advance booking appointment with last selected service will be loss.')){
                    return  false;
                }
            }
        });


        $(document).on('click','.add_more_break',function(e){
            var day_num = $(this).attr('data-day');
            var total_row= $("#tbody"+day_num).find('tr').length+1;
            var from_name = day_num+"[from_time][]";
            var to_name = day_num+"[to_time][]";

            var string = '<tr><td>'+total_row+'</td><td><input name="'+from_name+'" value="08:00 AM" onkeydown="return false" class="form-control time_box" type="text"></td><td><input value="08:00 PM" name="'+to_name+'" onkeydown="return false" class="form-control time_box" type="text"></td><td><button type="button" class="btn btn-danger delete_break"><i class="fa fa-trash"></i></button></td></tr>';
            $("#tbody"+day_num).append(string);

            $('.time_box').datetimepicker({
                format: 'hh:mm A'
            });



        });

        $(document).on('click','#address_list_btn',function(){
            var $btn = $(this).find('.label_text');
            $.ajax({
                url: "<?php echo Router::url('/prescription/address_list_modal',true);?>",
                type:'POST',
                beforeSend:function(){
                    $btn.button('loading').text('Wait..');
                },
                success: function(result){
                    $btn.button('reset');
                    var html = $(result).filter('#addressListModal');
                    $(html).modal('show');
                },error:function () {
                    $btn.button('reset');
                }
            });
        });
        $(document).on('click','#service_list_btn',function(){
            var $btn = $(this).find('.label_text');
            $.ajax({
                url: "<?php echo Router::url('/prescription/service_list_modal',true);?>",
                type:'POST',
                beforeSend:function(){
                    $btn.button('loading').text('Wait..');
                },
                success: function(result){
                    $btn.button('reset');
                    var html = $(result).filter('#serviceListModal');
                    $(html).modal('show');
                },error:function () {
                    $btn.button('reset');
                }
            });
        });



        function flash(title,message,type,position){
            $.alert(message, {
                autoClose: true,
                closeTime: 3000,
                withTime: false,
                type: type,
                position: [position, [-0.42, 0]],
                title: title,
                icon: false ,
                close: true,
                speed: 'normal',
                isOnly: true,
                minTop: 10,
                animation: false,
                animShow: 'fadeIn',
                animHide: 'fadeOut',
                onShow: function () {
                },
                onClose: function () {
                }
            });
        }

    });
</script>





