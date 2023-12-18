<div class="message_span_slot"></div>
<?php if(!empty($final_array)){ ?>
    <ul class="slot_blok_reschadule">
        <?php foreach ($final_array as $key => $list){
            if($list['status'] != 'BLOCKED')
            {
                if(empty($list['profile_photo'])){
                    $list['profile_photo'] =Router::url('/thinapp_images/staff.jpg',true);
                }
                ?>
                <li>
                    <a data-slot = "<?php echo base64_encode($list['time']); ?>" class="<?php echo $list['flag']; ?>_RESCHADULE" <?php if($list['flag']=='BOOKED'){ ?> rel='tooltip' data-original-title="<div class='main_div_tooltip'><div class'main_two' ><img class='tool_user_image' src='<?php echo $list['profile_photo']; ?>'></div><div class'main_two' ><label><i class='fa fa-user'></i>&nbsp; <?php echo $list['name']; ?> </label><br><label><i class='fa fa-phone'></i>&nbsp;  <?php echo $list['mobile']; ?></label></div><div>" <?php } ?>  href="javascript:void(0)">
                        <div class='token_class_reschadule'><?php echo $list['queue_number']; ?></div>
                        <div class='time_con_reschadule'>
                            <?php echo $list['time']; ?>
                            <br><small><?php echo $list['flag']; ?></small>
                        </div>
                    </a>
                </li>
            <?php } } ?>
    </ul>
<?php }else{ ?>
    <div class="no_data">
        <h3>This is not a working day. </h3>
    </div>
<?php } ?>
<style>
    .slot_blok_reschadule li a:hover, .slot_blok_reschadule li a.active_li{
        background:#03b21082;
    }
    .token_class_reschadule {
        float: left;
        text-align: center;
        padding: 14px 0px;
        margin: 0% 34%;
        width: 50px;
        font-weight: 600;
        font-size: 18px;
        color: #0009;
        border: 1px solid #cccccc;
        border-radius: 26px;
        height: 50px;
        background: #fff;
    }
    .time_con_reschadule {
        float: left;
        font-size: 20px;
        color: #045705b3;
        width: 100%;
        font-weight: 600;
        margin-top: 15px;
    }
    .time_con_reschadule small{
        font-size: 12px;
        margin: 6px 15%;
        width: 70%;
        display: block;
        border-top: 1px solid #109d2e38;
        padding: 0px 10px;
    }
</style>

<script>


    $(document).off("click", ".slot_blok_reschadule li a");
    $(document).on("click", ".slot_blok_reschadule li a",  function () {

        $(".slot_blok_reschadule li a").removeClass("active_li");
        if($(this).hasClass("AVAILABLE_RESCHADULE")){
            $(this).addClass("active_li");
        }

    });



    $(document).off("click", ".AVAILABLE_RESCHADULE");

    $(document).on("click", ".AVAILABLE_RESCHADULE", function () {
        var $btn = $(this);
        var cur_obj = $(this);
        var token_number= $(this).find(".token_class_reschadule").html();


        var slot = $(this).attr('data-slot');
        var appointment_id = "<?php echo $appointment_id ?>";
        var service_id = $("#res_service_drp").val();
        var address_id = $("#res_address_drp").val();
        var doctor_id = $("#res_doctor_drp").val();
        var date =  $("#res_appointment_date").val();
        var app = "<?php echo base64_decode($appointment_id); ?>";
        var is_loading = false;
        if(app == 0 && is_loading === false){
            var obj = $(".slot_date");
            $.ajax({
                type:'POST',
                url: baseurl+"app_admin/search_appointment_customer",
                beforeSend:function(){
                    $(cur_obj).find(".token_class_reschadule").html('<i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>');
                    loading(obj,true,"Loading...");
                    is_loading=true;
                },
                success:function(data){
                    $("#search_cus_modal").html(data).modal("show").attr("slot",slot);
                    loading(obj,false);
                    is_loading = false;
                    $(cur_obj).find(".token_class_reschadule").html(token_number);
                    //$(".service_drp").trigger("change");
                },
                error: function(data){



                    loading(obj,false);
                    is_loading = false;
                    $(cur_obj).find(".token_class_reschadule").html(token_number);
                    alert("Sorry something went wrong on server.");

                }
            });
        }else{
            $.ajax({
                type:'POST',
                url: baseurl+"app_admin/reschedule_appointment",
                data:{appointment:appointment_id,date:date,di:btoa(doctor_id),slot:slot,si:btoa(service_id),ai:btoa(address_id)},
                beforeSend:function(){
                    $(cur_obj).addClass('disabled');
                    $(".slot_loading").show();
                    $(".message_span_slot").html("");
                },
                success:function(data){
                    var response = JSON.parse(data);
                    if(response.status==1){
                        $("#appointment_date").trigger("changeDate");
                        $(".message_span_slot").html(response.message);
                        $("#reschedule").modal("hide");

                        //window.location.reload();
                    }else{
                        $(".message_span_slot").html(response.message);
                    }

                    $(cur_obj).removeClass('disabled');
                    $(".slot_loading").hide();

                },
                error: function(data){
                    $(cur_obj).removeClass('disabled');
                    $(".slot_loading").hide();
                    $(".message_span_slot").html(response.message);
                }
            });
        }

    })
    $("[rel=tooltip]").tooltip({html:true})

    if(showModalReschadule){
        showModalReschadule = 'NO';
    }else
        {
            var showModalReschadule = 'NO';
        }


    if(showModalReschadule == 'NO')
    {
        $(".AVAILABLE_RESCHADULE").first().addClass( "is-selected" );
        //$(".AVAILABLE").first().addClass( "is-selected" ).focus();
    }

    $(document).off("keyup",'body');
    $(document).on("keyup",'body',function(e) {
        if(e.which == 13 && showModalReschadule == 'NO') {
            e.preventDefault();
            $(".AVAILABLE_RESCHADULE.is-selected").trigger('click');
        }

        var keyCode = e.keyCode || e.which;
        if (keyCode == 9 && showModalReschadule == 'NO'){
                e.preventDefault();
            $( ".AVAILABLE_RESCHADULE.is-selected" ).removeClass( "is-selected" ).parent().nextAll().find('.AVAILABLE_RESCHADULE').first().addClass( "is-selected" ).focus();
        }

        if (keyCode == 27 && showModalReschadule == 'NO') {
            e.preventDefault();
            $("#reschedule").modal("hide");
        }

    });




</script>

