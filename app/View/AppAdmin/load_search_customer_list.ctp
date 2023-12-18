<?php
$login = $this->Session->read('Auth.User');
$thin_app_id = $login['User']['thinapp_id'];
?>
<?php if(strtoupper($flag) != 'UHID'){ ?>
    <div class="patient_radio_button_div">
        <div class="inner_content_div">
            <label><input type="radio" name="add_type" value="CUSTOMER" id="userType" data-cus="<?php echo(0); ?>"
                          data-user="<?php echo(0); ?>">&nbsp;Add New Patient </label>
            <label><input type="radio" name="add_type" value="CHILDREN" id="userType" data-cus="<?php echo(0); ?>"
                          data-user="<?php echo(0); ?>">&nbsp;Add New Children</label>
        </div>
    </div>
<?php } ?>
<?php if (!empty($customer_list)) { ?>
    <h4 style="padding: 2px 6px; font-weight: 600; margin: 0px;">Searched Patient List</h4>
    <div class="table-wrapper-scroll-y">
        <table class="table" id="search_data_table">
            <thead>
            <tr>
                <th >#</th>
                <th >Name</th>
                <th >Mobile</th>
                <th >MEngage UHID</th>
                <th >UHID</th>
                <th>Address</th>
                <th>Last Visit</th>
                <th >Patient Type</th>
                <th >Action</th>

            </tr>
            </thead>
            <tbody>
            <?php foreach ($customer_list as $key => $list) {
                if (empty($list['profile_photo'])) {
                    $list['profile_photo'] = Router::url('/thinapp_images/staff.jpg', true);
                }
                ?>
                <tr data-c="<?php echo base64_encode($list['id']); ?>" data-ut="<?php echo empty($list['child_id']) ? "CUSTOMER" : "CHILDREN" ?>" >
                    <td class="td_valign gray"><?php echo $key + 1; ?></td>
                    <td class="td_valign white" ><?php echo $list['name']; ?></td>
                    <td class="td_valign gray"><?php echo $list['mobile']; ?></td>
                    <td class="td_valign white"><?php echo empty($list['uhid']) ? '-' : $list['uhid']; ?></td>
                    <td class="td_valign white"><?php echo empty($list['third_party_uhid']) ? '-' : $list['third_party_uhid']; ?></td>
                    <td class="td_valign gray"><?php echo $list['address']; ?></td>
                    <td class="td_valign white"><?php echo (!empty($list['last_visit'])?date('d/m/Y',strtotime($list['last_visit'])):''); ?></td>
                    <td class="td_valign gray"><?php echo !empty($list['child_id']) ? 'Child' : 'Patient'; ?></td>
                    <td class="td_valign inline-btn" user-type="<?php echo empty($list['child_id']) ? "CUSTOMER" : "CHILDREN" ?>">
                        <button data-name="<?php echo $list['name']; ?>" class="btn btn-success btn-xs app_book_now_direct" type="button"><i class="fa fa-lock"></i> Book</button>
                        <button data-name="<?php echo $list['name']; ?>" class="btn btn-success btn-xs app_book_checkin" type="button"><i class="fa fa-lock"></i> Book & Check-In</button>
                        <button class="btn btn-success btn-xs app_book_now" data-btn="detail" type="button"><i class="fa fa-pencil"></i> Edit & Book </button>

                        <?php if(!empty($list['uhid'])){ ?>
                            <button class="btn btn-xs btn-info history_btn" src="<?php echo Router::url('/app_admin/get_patient_history/', true) . base64_encode($thin_app_id) . '/' . base64_encode($list['uhid']); ?>"><i class="fa fa-sign-in"></i> History</a>
                        <?php } ?>


                    </td>


                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>


<?php } else {
    if (strtoupper($flag) == 'UHID') {
        echo "<div class='search_msg_param'>No patient found by search values</div>";
    } else { ?>

    <?php }
} ?>

<div class="add_cus_div_app add_cus_div_app_load"></div>






<script>
    /* code use for book appointment */

    $(".select_patient_option").html($(".patient_radio_button_div").html());
    $(".patient_radio_button_div").remove();


    $(document).off("click", ".app_book_now_direct");
    $(document).on("click", ".app_book_now_direct", function () {
        $(".file_error").fadeOut('slow');
        var user_type = $(this).closest('tr').attr("data-ut");
        var customer_id = $(this).closest('tr').attr("data-c");
        $.ajax({
            type:'POST',
            url: baseurl+"app_admin/web_book_appointment",
            data:{
                d:btoa($("#dialog_doctor_drp").val()),
                s:btoa($("#dialog_service_drp").val()),
                a:btoa($("#dialog_address_drp").val()),
                app_type:$("#search_cus_modal").attr('data-type'),
                app_slot:$("#search_cus_modal").attr('data-slot'),
                app_queue:$("#search_cus_modal").attr('data-queue'),
                booking_date:$("#appointment_date").val(),
                app_mobile:$("#app_search_customer").val(),
                p:customer_id,
                user_type:user_type,
                direct_book:true,
                cn :$(this).attr("data-name")
            },
            beforeSend:function(){
                isSearching = 'YES';
                $('#addAppointment').button('loading').html('Loading...');
                $(".app_error_msg").hide();
                $(".customer_loading").show();
            },
            success:function(data){
                isSearching = 'NO';
                $('#addAppointment').button('reset');
                $(".customer_loading").hide();

                var response = JSON.parse(data);
                if(response.status==1){

                    $("#search_cus_modal").modal("hide");
                    $("#appointment_date").trigger('changeDate');
                    if(response.payment_dialog){
                        var html = $(response.payment_dialog).filter('#myModalFormAdd');
                        $(html).modal('show');
                    }


                }else{

                    $(".app_error_msg").html(response.message).fadeIn('slow');
                }
            },
            error: function(data){
                isSearching = 'NO';
                $('#addAppointment').button('reset');
                $(".customer_loading").hide();
                $(".app_error_msg").html("Sorry something went wrong on server.").fadeIn('slow');
            }
        });


    });

    $(document).off("click", ".app_book_checkin");
    $(document).on("click", ".app_book_checkin", function () {
        $(".file_error").fadeOut('slow');
        var user_type = $(this).closest('tr').attr("data-ut");
        var customer_id = $(this).closest('tr').attr("data-c");
        $.ajax({
            type:'POST',
            url: baseurl+"app_admin/web_book_appointment",
            data:{
                d:btoa($("#dialog_doctor_drp").val()),
                s:btoa($("#dialog_service_drp").val()),
                a:btoa($("#dialog_address_drp").val()),
                app_type:$("#search_cus_modal").attr('data-type'),
                app_slot:$("#search_cus_modal").attr('data-slot'),
                app_queue:$("#search_cus_modal").attr('data-queue'),
                booking_date:$("#appointment_date").val(),
                app_mobile:$("#app_search_customer").val(),
                p:customer_id,
                user_type:user_type,
                checked_in:'YES',
                direct_book:true,
                cn :$(this).attr("data-name")
            },
            beforeSend:function(){
                isSearching = 'YES';
                $('#addAppointment').button('loading').html('Loading...');
                $(".app_error_msg").hide();
                $(".customer_loading").show();
            },
            success:function(data){
                isSearching = 'NO';
                $('#addAppointment').button('reset');
                $(".customer_loading").hide();

                var response = JSON.parse(data);
                if(response.status==1){

                    $("#search_cus_modal").modal("hide");
                    $("#appointment_date").trigger('changeDate');

                }else{

                    $(".app_error_msg").html(response.message).fadeIn('slow');
                }
            },
            error: function(data){
                isSearching = 'NO';
                $('#addAppointment').button('reset');
                $(".customer_loading").hide();
                $(".app_error_msg").html("Sorry something went wrong on server.").fadeIn('slow');
            }
        });


    });

    $(document).off("click", "#userType, .app_book_now");
    $(document).on("click", "#userType, .app_book_now", function () {
        var $btn = $(this);
        var obj = $(this);
        var list_action = $(this).attr("data-btn");
        var user_type = "";
        var type ="";
        var customer_id = 0;
        if(list_action){
            type = 'OLD';
            user_type = $(this).closest('tr').attr("data-ut");
            customer_id = $(this).closest('tr').attr("data-c");
        }else{
            user_type = $('#userType:checked').val();
            type = 'NEW';
        }


        var number = $("#app_search_customer").val();
        if (number.length < 10 && $.isNumeric(number) ) {
            $(".app_error_msg").html("Please enter valid 10 digit mobile number.").fadeIn('slow');
        } else {
            if (isSearching == 'NO') {
                $.ajax({
                    type: 'POST',
                    url: baseurl + "app_admin/get_book_new_appointment",
                    data: {user_type: user_type, customer_id: customer_id, type: type},
                    beforeSend: function () {
                        isSearching = 'YES';
                        $btn.attr('readonly', true);
                        $(".customer_loading").show();
                        $(".app_error_msg").hide();
                    },
                    success: function (data) {
                        isSearching = 'NO';
                        if (data) {
                            $("#load_customer_div .add_cus_div_app_load").remove();
                            $(".add_cus_div_app_load").html(data);
                            $btn.attr('readonly', false);
                            $(".customer_loading").hide();
                        } else {
                            $(".app_error_msg").html("Sorry, Couldn't Get Data!").fadeIn('slow');
                            $btn.attr('readonly', false);
                            $(".customer_loading").hide();
                        }

                    },
                    error: function (data) {
                        isSearching = 'NO';
                        $btn.attr('readonly', false);
                        $(".customer_loading").hide();

                    }
                });
            }


        }


    });






</script>
<style>



    #search_data_table {
        border-collapse: collapse;
        width: 100%;
        font-size: 12px;
    }

    #search_data_table th, #search_data_table td {
        text-align: left;
        padding: 3px 2px;
    }

    #search_data_table tr:nth-child(even) {background-color: #f2f2f2;}

    .app_error_msg {
        text-align: center;
        color: #ea1212;
        top: 0px;
        position: relative;
        width: 100%;
        float: right;
        padding: 0px;
        font-size: 15px;
        font-weight: 600;
        line-height: 18px;
    }


    .patient_radio_button_div{
        width: 25%;
        padding: 0px;
        margin-left: 302px;
        display: block;
        margin-top: 8px;
        margin-bottom: 2px;
    }


    .white{
       /* background: #f6f6f6 !important;*/
    }
    .gray{
       /* background: #8f8c8c30 !important;*/
    }

    .table-wrapper-scroll-y table thead{
        background: #e8e8e8 !important;
    }




    .search_msg_param {
        text-align: center;
        color: #03A8F4;
        font-size: 20px;
    }

    .add_appointment {
        text-align: center;
    }

    .add_appointment h3 {
        color: #04a6f0;
    }

    .add_appointment label {
        color: #04a6f0;
        font-size: medium;
    }

    .customer_first_name, .customer_last_name {
        float: left;
        width: 70% !important;
    }

    .customer_gender {
        float: left; /*width: 30% !important;*/
    }

    .table-responsive {
        max-height: 205px;
        overflow-y: scroll;
    }

    .table-wrapper-scroll-y {
        display: block;
        max-height: 116px;
        overflow-y: auto;
        background: #fafafbbf;
        -ms-overflow-style: -ms-autohiding-scrollbar;
        border: 1px solid #c0bfc1;
        border-radius: 2px;
        padding: 0px 0px;
    }

.inline-btn{
    display:inline-flex;
}
    .inline-btn > button{
        margin-left : 2px;
    }


</style>