<?php
$login = $this->Session->read('Auth.User');
?>

<div class="col-md-8 col-md-offset-2">
    <div class="input-group">
        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
        <input type="text" class="form-control slot_datepicker" placeholder="Username" name="date">
        <span class="input-group-addon"><span class="fa fa-spinner fa-spin slot_loading"></span></span>
    </div>
</div>


<script>
    $(document).ready(function(){
        $(".slot_datepicker").datepicker('setDate', new Date()).on('changeDate', function(ev){

            var $btn = $(this);
            $(this).datepicker('hide');
            var appointment_id = "<?php echo $content['appointment_id']; ?>";
            var service_id = "<?php echo $content['service']; ?>";
            var staff_id = "<?php echo $content['staff_id']; ?>";
            var date =  $(this).val();
            $.ajax({
                type:'POST',
                url: baseurl+"app_admin/load_slot_data",
                data:{appointment_id:appointment_id,service_id:service_id,staff_id:staff_id,date:date},
                beforeSend:function(){
                    $btn.attr('readonly',true);
                    $(".slot_loading").show();
                },
                success:function(data){
                    $("#load_slot_div").html(data);
                    $btn.attr('readonly',false);
                    $(".slot_loading").hide();
                },
                error: function(data){
                    $btn.attr('readonly',false);
                    $(".slot_loading").hide();
                    $(".file_error").html("Sorry something went wrong on server.");
                }
            });
        });;
        $(".slot_datepicker").trigger('changeDate');
    });


</script>


