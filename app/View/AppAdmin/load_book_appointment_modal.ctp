<div class="col-md-<?php echo !empty($content['service_id'])?'3':'4'; ?>">
    <label>Select Date</label>
    <div class="input-group">
        <span class="input-group-addon app_slot_span"><span class="glyphicon glyphicon-calendar"></span></span>
        <input type="text" class="form-control slot_date" placeholder="Date" name="date">
    </div>
</div>


<script>
        $(function () {
            $(document).off('changeDate',".slot_date");
            $(".slot_date").datepicker('setDate', new Date()).on('changeDate', function(ev){
                if(isLoadingSloat == 'NO')
                {
                    var obj = $(this);
                    $(this).datepicker('hide');
                    var appointment_id = "<?php echo $content['appointment_id']; ?>";
                    var address_id = "<?php echo $content['address_id']; ?>";
                    var doctor_id = "<?php echo $content['doctor_id']; ?>";
                    var service_id = "<?php echo $content['service_id']; ?>";
                    var date =  $(this).val();
                    $.ajax({
                        type:'POST',
                        url: baseurl+"app_admin/load_slot_data",
                        data:{appointment_id:appointment_id,address_id:address_id,doctor_id:doctor_id,service_id:service_id,date:date},
                        beforeSend:function(){
                            isLoadingSloat = 'YES';
                            $("#load_slot_div").html('');
                            loading(obj,true, "Loading...");
                        },
                        success:function(data){
                            isLoadingSloat = 'NO';
                            $("#load_slot_div").html(data);
                            loading(obj,false);
                            openPayDialog();

                        },
                        error: function(data){
                            isLoadingSloat = 'NO';
                            loading(obj,false);
                            $(".file_error").html("Sorry something went wrong on server.");
                        }
                    });

                }
            });

            $(".slot_date").trigger('changeDate');
        });


</script>


