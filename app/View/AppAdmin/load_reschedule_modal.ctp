<?php
$login = $this->Session->read('Auth.User');
$filter_array = $this->AppAdmin->get_doctor_filter_appointment_array($login['User']['thinapp_id']);

?>


    <div class="modal-dialog modal-lg">
        <div class="modal-content form-horizontal">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title">Reschedule Appointment </h5>
            </div>
            <div class="modal-body">
                <div class="form-group option_box_reschedule">



                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3  res_append_doctor">
                            <label>Select Doctor</label>
                            <?php echo $this->Form->input('doctor',array('id'=>'res_doctor_drp','type'=>'select','label'=>false,'options'=>array(),'class'=>'form-control')); ?>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3  res_append_service">
                            <label>Select Service</label>
                            <?php echo $this->Form->input('service',array('id'=>'res_service_drp','type'=>'select','label'=>false,'empty'=>'Select address','options'=>array(),'class'=>'form-control')); ?>

                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3  res_append_address">

                            <label>Select Address</label>
                            <?php echo $this->Form->input('address',array('id'=>'res_address_drp','type'=>'select','label'=>false,'empty'=>'Select address','options'=>array(),'class'=>'form-control')); ?>

                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3  res_append_date">
                            <label>Select Date</label>
                            <div class="input-group">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                <input type="text" class="form-control slot_datepicker" placeholder="Username" name="date" id="res_appointment_date">
                                <span class="input-group-addon"><span class="fa fa-spinner fa-spin slot_loading"></span></span>
                            </div>
                        </div>




                    </div>
                    <div class="form-group">
                    <div class="col-sm-12">
                        <div id="load_slot_div_reschadule"></div>
                    </div>
                </div>
            </div>
        </div>
   </div>

<style>
    .option_box_reschedule input, .option_box_reschedule select{
        height: 30px !important;
        padding:0px 5px;
    }
</style>
<script>
    $(document).ready(function(){


        var appointment_id = "<?php echo $content['appointment_id']; ?>";

        var tempArray = <?php echo json_encode($filter_array); ?>;


        function create_doctor_dropdown(selected_doctor_id=0){

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
                $("#res_doctor_drp").html(doctor_string);
                if(selected_doctor_id>0){
                    $("#res_doctor_drp").val(selected_doctor_id);
                }
                setTimeout(function(){$("#res_doctor_drp").trigger('change');},10);
            }


        }
        function create_service_dropdown(doctor_id,selected_service_id=0){

            var option="";
            $("#res_service_drp").html("");
            for (var doctor_key in tempArray) {
                if (tempArray.hasOwnProperty(doctor_key)) {
                    if(doctor_key == doctor_id){
                        object = tempArray[doctor_key]['service'];
                        for (var service_key in object) {
                            if (object.hasOwnProperty(service_key)) {
                                option += "<option data-value="+object[service_key].service_amount+" value='"+service_key+"'>"+object[service_key].service_name+"</option>";
                            }
                        }
                        $("#res_service_drp").html(option);

                        if(selected_service_id>0){
                            $("#res_service_drp").val(selected_service_id);
                        }
                        setTimeout(function(){$("#res_service_drp").trigger('change');},10);
                        break;
                    }
                }
            }

        }
        function create_address_dropdown(service_id,doctor_id,selected_address_id=0){
            var option="";
            $("#res_address_drp").html("");
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
                                    $("#res_address_drp").html(option);
                                    if(selected_address_id>0){
                                        $("#res_address_drp").val(selected_address_id);
                                    }
                                    setTimeout(function(){$("#res_address_drp").trigger('change');},10);

                                }
                            }

                        }
                        break;
                    }
                }
            }



        }
        create_doctor_dropdown();



        setTimeout(function () {

            var doctor_id = '<?php echo base64_decode($content['staff_id']); ?>';
            var service_id = '<?php echo base64_decode($content['service_id']); ?>';
            var address_id = '<?php echo base64_decode($content['address_id']); ?>';
            $("#res_doctor_drp").val(doctor_id);
            create_service_dropdown(doctor_id,service_id);
            create_address_dropdown(doctor_id,service_id,address_id);

        },30);


        $(document).off('change','#res_doctor_drp');
        $(document).on('change','#res_doctor_drp',function(e){
            var doctor_id = $(this).val();
            create_service_dropdown(doctor_id);
        });
        $(document).off('change','#res_service_drp');
        $(document).on('change','#res_service_drp',function(e){
            var service_id = $(this).val();
            var doctor_id = $('#res_doctor_drp').val();
            create_address_dropdown(service_id,doctor_id);
        });
        $(document).off('change','#res_address_drp');
        $(document).on('change','#res_address_drp',function(e){
            $("#res_appointment_date").trigger('changeDate');
        });

        $("#res_appointment_date").datepicker('setDate', new Date()).off('changeDate');
        $("#res_appointment_date").datepicker('setDate', new Date()).on('changeDate', function(ev){
            $('#res_appointment_date').datepicker('hide');
            load_reschedule_slot(this);
        });


        function load_reschedule_slot(obj){
            var $btn = $(obj);
            $(obj).datepicker('hide');
            var appointment_id = "<?php echo $content['appointment_id']; ?>";
            var service_id = $("#res_service_drp").val();
            var address_id = $("#res_address_drp").val();
            var doctor_id = $("#res_doctor_drp").val();
            var date =  $(obj).val();
            $.ajax({
                type:'POST',
                url: baseurl+"app_admin/load_slot_data_reschadule",
                data:{appointment_id:appointment_id,address_id:btoa(address_id),service_id:btoa(service_id),doctor_id:btoa(doctor_id),date:date},
                beforeSend:function(){
                    $btn.attr('readonly',true);
                    $(".slot_loading").show();
                },
                success:function(data){
                    $("#load_slot_div_reschadule").html(data);
                    $btn.attr('readonly',false);
                    $(".slot_loading").hide();
                    $("#appointment_date").trigger("change");
                },
                error: function(data){
                    $btn.attr('readonly',false);
                    $(".slot_loading").hide();
                    $(".file_error").html("Sorry something went wrong on server.");
                }
            });

        }





    });
</script>


