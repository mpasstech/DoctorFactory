<?php
    $booked_with_payment_success = "#78f704";
    $booked_with_payment_pending = "#fbec3a";
    $available_slots = "#FFFFFF";
    $expired_time_slots = "#927d7d";
    $refunded_appointment = "#04f3ff";
    $blocked_slots = "#9765d8";
    $emergency = "#ff0000";


?>
<style>
    .header{
        display: none;
        position: relative;
    }
</style>


<?php
$login = $this->Session->read('Auth.User');
$filter_array = $this->AppAdmin->get_doctor_filter_appointment_array($login['User']['thinapp_id']);
$staff_data = $this->AppAdmin->get_doctor_by_id($login['AppointmentStaff']['id'],$login['User']['thinapp_id']);

echo $this->Html->script(array('jquery.maskedinput-1.2.2-co.min.js','comman.js'));



?>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 option_div" style="padding: 0px;">
    <div class="form-group">

        <div class="col-lg-2 col-md-3 col-sm-2 col-xs-2  append_doctor">
            <label>Select Doctor</label>
            <?php echo $this->Form->input('doctor',array('id'=>'doctor_drp','type'=>'select','label'=>false,'options'=>array(),'class'=>'form-control')); ?>
        </div>
        <div class="col-lg-2 col-md-3 col-sm-2 col-xs-2  append_service">
            <label>Select Service</label>
            <?php echo $this->Form->input('service',array('id'=>'service_drp','type'=>'select','label'=>false,'empty'=>'Select address','options'=>array(),'class'=>'form-control')); ?>

        </div>
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3  append_address">

            <label>Select Address</label>
            <?php echo $this->Form->input('address',array('id'=>'address_drp','type'=>'select','label'=>false,'empty'=>'Select address','options'=>array(),'class'=>'form-control')); ?>

        </div>

        <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
            <label style="display: block;">&nbsp;</label>
            <button type="button" class="btn btn-default" id="refresh_btn">Refresh List</button>
            <a class="btn btn-info" href="<?php echo Router::url('/app_admin/add_appointment_tmp',true)?>" >All Appointment</a>
            <a class="btn btn-success" href="<?php echo Router::url('/app_admin/get_all_appointment_list_by_filter/PENDING',true)?>" >Pending</a>
            <a class="btn btn-warning" href="<?php echo Router::url('/app_admin/get_all_appointment_list_by_filter/SUCCESS',true)?>" >Success</a>
            <a href="<?php echo Router::url('/app_admin/add_appointment',true);?>"  class="btn btn-danger">Back</a>
        </div>


        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3  append_date" style="display: none;">

            <label>Select Date</label>


            <div class="input-group">
                <span class="input-group-addon app_slot_span"><span class="glyphicon glyphicon-calendar"></span></span>
                <input data-date-format = 'dd/mm/yyyy' type="text" id="appointment_date" class="form-control" placeholder="Date" name="appointment_date">
            </div>

        </div>


        <div class="append_appointment">

        </div>
        <div class="col-md-12 loading_box_controll">
            <div class="loading_div_app">
                <span class="fa fa-spinner fa-spin slot_loading"></span> &nbsp; <lable class="app_loader_msg"></lable>
            </div>
        </div>
    </div>

</div>

<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 slot_list_div">
    <div id="custom_error_msg"></div>
    <div id="load_slot_div"></div>
</div>


<script>
    var tokenSearch = "";
    var tabType = "";
    $(document).ready(function(){

        clickedIndex = -1;




        var tempArray = <?php echo json_encode($filter_array); ?>;
        function create_doctor_dropdown(){

            if(tempArray){
                var doctor_string = "";
                for (var key in tempArray) {
                    if (tempArray.hasOwnProperty(key)) {
                        if (tempArray.hasOwnProperty(key)) {
                            var doctor_name = tempArray[key].doctor_name;
                            doctor_string += "<option value='"+key+"'>"+doctor_name+"</option>";
                        }
                    }
                }
                $("#doctor_drp").html(doctor_string);
                setTimeout(function(){$("#doctor_drp").trigger('change');},10);
            }


       }
        function create_service_dropdown(doctor_id){

            var option="";
            for (var doctor_key in tempArray) {
                if (tempArray.hasOwnProperty(doctor_key)) {
                    if(doctor_key == doctor_id){
                        object = tempArray[doctor_key]['service'];
                        for (var service_key in object) {
                            if (object.hasOwnProperty(service_key)) {
                                option += "<option data-value="+object[service_key].service_amount+" value='"+service_key+"'>"+object[service_key].service_name+"</option>";
                            }
                        }
                        $("#service_drp").html(option);
                        setTimeout(function(){$("#service_drp").trigger('change');},10);
                        break;
                    }
                }
            }

        }
        function create_address_dropdown(service_id,doctor_id){
            var option="";
            for (var doctor_key in tempArray) {
                if (tempArray.hasOwnProperty(doctor_key)) {
                    if(doctor_key == doctor_id){
                        object = tempArray[doctor_key]['service'];
                        for (var service_key in object) {
                            if (object.hasOwnProperty(service_key)) {
                                if(service_key == service_id){
                                    object_2 = object[service_key]['address'];
                                    for (var address_key in object_2) {
                                        if (object_2.hasOwnProperty(address_key)) {
                                            option += "<option value='"+address_key+"'>"+object_2[address_key].address+"</option>";
                                        }
                                    }
                                    $("#address_drp").html(option);
                                    setTimeout(function(){$("#address_drp").trigger('change');},10);

                                }
                            }

                        }
                        break;
                    }
                }
            }


        }
        create_doctor_dropdown();

        $(document).on('change','#doctor_drp',function(e){

            var doctor_id = $(this).val();
            create_service_dropdown(doctor_id);
        });
        $(document).on('change','#service_drp',function(e){
            var service_id = $(this).val();
            var doctor_id = $('#doctor_drp').val();
            create_address_dropdown(service_id,doctor_id);
        });
        $(document).on('change','#address_drp',function(e){
            $("#appointment_date").trigger('changeDate');
        });
        $("#appointment_date").datepicker('setDate',new Date()).on('changeDate', function(ev){
            $('#appointment_date').datepicker('hide');
                loadingSlotTab = false;
                load_slot_data();
        });


        $(document).on('click','#refresh_btn',function(e){
            $("#appointment_date").trigger('changeDate');
        });





        loadingSlotTab =false;
        loadingSlot =false;
        function load_slot_data(){

            if(loadingSlot ==false && loadingSlotTab == false){
                if(tabType != "")
                {

                    getListByTabBtn(tabType);
                }
                else
                {

                    // refreshProduct();
                    var address_id = $('#address_drp').val();
                    var doctor_id = $('#doctor_drp').val();
                    var service_id = $('#service_drp').val();
                    var date =  $("#appointment_date").val();
                    obj =  $("#appointment_date");
                    $.ajax({
                        type:'POST',
                        url: baseurl+"app_admin/load_slot_data_tmp",
                        data:{address_id:btoa(address_id),doctor_id:btoa(doctor_id),service_id:btoa(service_id),date:date},
                        beforeSend:function(){
                            loadingSlot =true;
                            loading(obj,true, "");
                        },
                        success:function(data){

                            loadingSlot =false;
                            $("#load_slot_div").html(data);
                            loading(obj,false);


                        },
                        error: function(data){
                            loadingSlot =false;
                            loading(obj,false);
                            $(".file_error").html("Sorry something went wrong on server.");
                        }
                    });

                }

            }
        }


        $.ajax({url: "<?php echo Router::url('/app_admin/create_data_list',true);?>"});


        $(document).on('click','#blog_slot_btn',function(e){

            var $btn = $(this);


            $.ajax({
                url: "<?php echo Router::url('/app_admin/block_slot_modal',true);?>",
                type:'POST',
                beforeSend:function(){
                    $btn.button('loading').html('<i class="fa fa-spinner fa-pulse fa-1x fa-fw"></i>  Wait..');
                },
                success: function(result){
                    $btn.button('reset');
                    var html = $(result).filter('#block_slot_modal');
                    $(html).modal('show');
                },error:function () {

                }
            });
        });


       $(document).on("click",".btn-list-appointment",function(){
           $(".btn-selected").removeClass("btn-selected");
            $(this).addClass("btn-selected").focus();
            var elementID = $(this).attr("id");
            $(this).removeClass('hover');

            if(elementID == "show-book-appointment"){
                loadingSlotTab =false;
                tabType = "";
                load_slot_data();
            }
            else if(elementID == "show-booked-appointment")
            {
                var type = "ALL_BOOKED";
                tabType = "ALL_BOOKED";
                getListByTabBtn(type);
            }
            else if(elementID == "show-cancel-appointment")
            {
                var type = "CANCEL";
                tabType = "CANCEL";
                getListByTabBtn(type);
            }
            else if(elementID == "show-close-appointment")
            {
                var type = "CLOSE";
                tabType = "CLOSE";
                getListByTabBtn(type);
            }
            else if(elementID == "show-checked-in-appointment")
            {
                var type = "CHECKED_IN";
                tabType = "CHECKED_IN";
                getListByTabBtn(type);
            }
            else if(elementID == "show-emergency-appointment")
            {
                var type = "EMERGENCY";
                tabType = "EMERGENCY";
                getListByTabBtn(type);
            }else if(elementID == "show-all-appointment")
            {
                var type = "ALL_APPOINTMENT";
                tabType = "ALL_APPOINTMENT";
                getListByTabBtn(type);
            }

        });

        $(document).on("click",".color_picker",function(e){
            <?php if($login['USER_ROLE'] != 'ADMIN'){ ?>
                e.preventDefault();
            <?php } ?>
        });

        $(document).on("change",".color_picker",function(e){


            <?php if($login['USER_ROLE'] == 'ADMIN'){ ?>
            var colorCode = $(this).val();
            var type = $(this).attr("data-value");
            obj =  $(this);
            $.ajax({
                type:'POST',
                url: baseurl+"app_admin/update_appointment_slot_color",
                data:{color_code:colorCode,type:type},
                beforeSend:function(){
                    loading(obj,true, "Loading...");
                },
                success:function(data){
                    loading(obj,false);
                },
                error: function(data){
                    loading(obj,false);
                    $(".file_error").html("Sorry something went wrong on server.");
                }
            });
            <?php }else{ ?>
            e.preventDefault();
            <?php } ?>

        });

        $(document).on('click','.history_btn',function(e){
            e.preventDefault();
            var url = $(this).attr('src');
            $("#ifram").attr("src",url);
            $("#patient_history_modal").modal('show');
        });



    /*    $(document).on('click','.top_navigation_btn',function(e){
            navigationHeader($(this).find('i').attr('visible'));
        });

        function navigationHeader(flag){
            console.log(flag);
            if(flag=='true'){
                $(".header, .slide_fix .total_earning_div").slideDown(600).show();
                $(".top_navigation_btn i").removeClass('fa-arrow-down').addClass('fa-arrow-up').attr('visible',false);
            }else{
                $(".header, .slide_fix, .total_earning_div").slideUp(600).hide();
                $(".top_navigation_btn i").removeClass('fa-arrow-up').addClass('fa-arrow-down').attr('visible',true);
            }
        }

        navigationHeader(false);
*/

    });






</script>











