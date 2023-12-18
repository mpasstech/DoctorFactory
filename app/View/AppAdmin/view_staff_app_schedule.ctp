<?php
$login1 = $this->Session->read('Auth.User');
$login = $this->Session->read('Auth.User.User');
$date =date('Y-m-d');
$reqData = $this->request->query;
if(isset($reqData['d']) && !empty($reqData)){
    $date = $reqData['d'];
}
//echo $date;die;
?>

<?php  echo $this->Html->css(array('dataTableBundle.css')); ?>
<?php  echo $this->Html->script(array('dataTableBundle.js','jquery.maskedinput-1.2.2-co.min.js','comman.js')); ?>

<div class="Home-section-2">

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="container">
            <?php echo $this->element('app_admin_book_appointment'); ?>
            <div class="row" >


                <div class="middle-block">
                    <h3 class="screen_title">Doctor Appointment</h3>

                    <!--div style="padding: 15px 0px;" class="col-lg-11 col-md-11 col-sm-11 col-xs-11 col-lg-offset-1 col-md-offset-1 col-sm-offset-1 col-xs-offset-1 custom_form_box"-->
                        <div class=" col-lg-12 col-md-12 col-sm-12 col-xs-12 custom_form_box right_box_div">
                        <?php $user_role = $this->AppAdmin->getHospitalUserRole($login['thinapp_id'], $login['mobile'], $login['role_id']); ?>
                        <div class="form-group">
                            <?php echo $this->element('app_admin_inner_tab_staff_appointment_filter'); ?>
                            <div class="loader_div" style="text-align: center;">
                                <img style="position: absolute;width:40px; margin-top:12px;"
                                     src="<?php echo Router::url('/img/ajaxloader.gif', true); ?>">
                            </div>
                        </div>
                        <div class="append_table"></div>
                    </div>

                </div>


            </div>

        </div>
    </div>
</div>



<div class="modal fade" id="reschedule" role="dialog"  keyboard="true"></div>


<style>

    .container {
        width: 95%;
    }

    .dashboard_icon_li li {

        text-align: center;
        width: 23%;

    }

    .custom_form_box {
        float: right;
    }
</style>
<?php $medicalProductData = $this->AppAdmin->get_app_medical_product_list($login['thinapp_id']); ?>
<script>
    var payBtnChecked = "";
    $(document).ready(function(){

        load_list(true);


        $(document).on("keypress",".main_div input[type='text']",function(e){
            var keyCode = (e.keyCode ? e.keyCode : e.which);
            if(keyCode == 13){
                load_list(true);
            }
        });




        $(document).on("click",".search_btn",function(){
            load_list(true);
        })



        function load_list(showloader){


            var staff_id = "<?php echo @$this->request->query['st']; ?>";
            var date = $("[name='date']").val();
            var month = $("[name='month']").val();
            var name = $("[name='name']").val();
            var mobile = $("[name='mobile']").val();
            var service_name = $("[name='service']").val();
            var address = $("[name='ad']").val();
            var appointment_status = $("[name='as']").val();
            var app_payment_status = $("[name='aps']").val();
            var app_payment_type = $("[name='apt']").val();
            var has_token = $("[name='has_token']").val();
            var booking_via = $("[name='booking_via']").val();
            var sort = $(".sort_list").val();

            $.ajax({
                type:'POST',
                url: baseurl+"app_admin/load_staff_appointment",
                data:{
                    st_id:staff_id,d:date,n:name,m:mobile,booking_via:booking_via,
                    sv:service_name,ad:address,s:appointment_status,month:month,p:app_payment_status,sort:sort,apt:app_payment_type,has_token:has_token
                },
                beforeSend:function(){
                    if(showloader===true){
                        $(".loader_div").show();
                     }

                },
                success:function(data){

                    $(".loader_div").hide();
                  $(".append_table").html(data);
                },
                error: function(data){
                    $(".loader_div").hide();
                    if(showloader===true){
                       // $.alert("Sorry something went wrong on server.");
                    }

                }
            });

        }




        $(document).off('click','#deleteSelected');
        $(document).on('click','#deleteSelected',function(){


            var total_checked = $('[name="select_id"]:checked').length;
            var $btn = $(this);
            var selectedID = [];
            if(total_checked > 0){
                var jc = $.confirm({
                    title: 'Delete Appointment',
                    content: 'Are you sure you want to delete selected appointments?',
                    type: 'red',
                    buttons: {
                        ok: {
                            text: "Yes",
                            btnClass: 'btn-primary',
                            keys: ['enter'],
                            name:"ok",
                            action: function(e){
                                $(document).find(".select_id").each(function(){

                                    if ($(this).prop('checked')==true){
                                        selectedID.push($(this).val());
                                    }
                                });
                                if(selectedID.length > 0){
                                    $.ajax({
                                        type: 'POST',
                                        url: baseurl + "app_admin/delete_appointment",
                                        data: {ids: selectedID},
                                        beforeSend: function () {
                                            $btn.button('loading').html('Wait..')
                                        },
                                        success: function (data) {
                                            jc.close();
                                            load_list(true);
                                        },
                                        error: function (data) {
                                            $btn.button('reset');
                                            alert("Sorry something went wrong on server.");
                                        }
                                    });

                                }
                                return false;
                            }
                        },
                        cancel: function(){

                        }
                    }
                });
            }else{
                $.alert('Please select at least one appointment.');
            }




        });

        $(document).off('click','#followUp');
        $(document).on('click','#followUp',function(){
            var $btn = $(this);
            var selectedID = [];
            $(document).find(".select_id").each(function(){

                if ($(this).prop('checked')==true){
                    selectedID.push($(this).val());
                }
            });
            if(selectedID.length > 0){

                $.ajax({
                    type: 'POST',
                    url: baseurl + "app_admin/follow_up_appointment",
                    data: {ids: selectedID},
                    beforeSend: function () {
                        $btn.button('loading').html('Wait..')
                    },
                    success: function (data) {
                            load_list(true);

                    },
                    error: function (data) {
                        $btn.button('reset');
                        alert("Sorry something went wrong on server.");
                    }
                });

            }
        });


        $(".channel_tap a").removeClass('active');
        $("#v_app_channel_list").addClass('active');
        
        var schedule_in_process =false;
        $(document).on('click','.btn_reschedule',function(e){

                if(schedule_in_process === false){
                    var $btn = $(this);
                    var id = $(this).attr('data-id');
                    var service = $(this).attr('data-service');
                    var address = $(this).attr('data-address');
                    var st_id = $(this).attr('data-st_id');

                    $.ajax({
                        type:'POST',
                        url: baseurl+"app_admin/load_reschedule_modal",
                        data:{id:id,service:service,st_id:st_id,address:address},
                        beforeSend:function(){
                            schedule_in_process = true;
                            $btn.button('loading').val('Wait..')
                        },
                        success:function(data){
                            load_list(true);
                            $("#reschedule").html(data).modal("show");
                            $btn.button('reset');
                            schedule_in_process = false;
                        },
                        error: function(data){
                            schedule_in_process =false;
                            $btn.button('reset');
                            $(".file_error").html("Sorry something went wrong on server.");
                        }
                    });
                }


        });


        $(document).on('click','.close_btn',function(e){
            var $btn = $(this);
            var obj = $(this);
            var id = $(this).attr('data-id');
            var jc = $.confirm({
                title: 'Close Appointment',
                content: 'Are you sure you want to close this appointment?',
                type: 'yellow',
                buttons: {
                    ok: {
                        text: "Yes",
                        btnClass: 'btn-primary',
                        keys: ['enter'],
                        name:"ok",
                        action: function(e){

                            $.ajax({
                                type:'POST',
                                url: baseurl+"app_admin/close_appointment",
                                data:{id:id},
                                beforeSend:function(){
                                    $btn.button('loading').html('Wait..')
                                },
                                success:function(data){
                                    var response = JSON.parse(data);
                                    if(response.status==1){
                                        //load_list(true);

                                        jc.close();
                                        $(obj).closest("tr").find(".status_td").html("Closed");
                                        $(obj).closest("tr").find(".payment_status").html("Success");

                                        var AddButton = '<button type="button" class="btn btn-xs btn-info" ' +
                                            'onclick="var win = window.open(\''+baseurl+'app_admin/print_invoice/'+ btoa(response.orderID)+'\', \'_blank\'); if (win) { win.focus(); } else { alert(\'Please allow popups for this website\'); }"' +
                                        ' ><i class="fa fa-money"></i> Receipt</button>';
                                            <?php if(($login1['USER_ROLE'] == 'RECEPTIONIST' && $login1['AppointmentStaff']['edit_appointment_payment'] =="YES" ) || $login1['USER_ROLE'] == 'ADMIN' || $login1['USER_ROLE'] =='DOCTOR' ){ ?>
                                        AddButton +='<button type="button" class="btn btn-primary btn-xs editBtn" data-id="'+ btoa(response.orderID)+'"><i class="fa fa-inr" aria-hidden="true"></i> Edit</button>';
                                            <?php } ?>
                                        $(obj).closest("tr").find(".app_btn_td .option_btn_panel").html(AddButton);
                                    }else{
                                        alert(response.message);
                                        $btn.button('reset');
                                    }
                                },
                                error: function(data){
                                    $btn.button('reset');
                                    alert("Sorry something went wrong on server.");
                                }
                            });
                            return false;
                        }
                    },
                    cancel: function(){

                    }
                }
            });

        });


        $(document).on('click','.cancel_btn',function(e){

            if(confirm("Are you sure you want to cancel this appointment ?")) {
                var $btn = $(this);
                var obj = $(this);
                var id = $(this).attr('data-id');
                $.ajax({
                    type: 'POST',
                    url: baseurl + "app_admin/cancel_appointment",
                    data: {id: id},
                    beforeSend: function () {
                        $btn.button('loading').html('Wait..')
                    },
                    success: function (data) {
                        var response = JSON.parse(data);
                        if (response.status == 1) {
                            load_list(true);
                            $(obj).closest("tr").find(".status_td").html("Cancel");
                            $(obj).closest("tr").find(".app_btn_td").html("");
                        } else {
                            alert(response.message);
                            $btn.button('reset');
                        }
                    },
                    error: function (data) {
                        $btn.button('reset');
                        alert("Sorry something went wrong on server.");
                    }
                });
            }

        });


        $(".datepicker").datepicker(
            'setDate', new Date("<?php echo $date; ?>")
        ).on('changeDate', function(ev){
            $(this).datepicker('hide');
        });



        function clickEvent(e){
            var keyCode = (e.keyCode ? e.keyCode : e.which);
            if(keyCode==43){
                e.preventDefault();
                $("#addMoreAdd").trigger('click');
                updateTotal();
            }else if(keyCode == 45){
                e.preventDefault();
                var obj = $('.removeRowAdd:last');
                $(obj).trigger('click');
                updateTotal();
            }
        }





        $(document).on('hidden.bs.modal','#reschedule', function () {

            load_list(true);

        });









        $(document).on('click','.pay_btn',function(e){
            var $btn = $(this);
            payBtnChecked = $btn;
            var obj = $(this);
            var ai = $(this).attr('data-id');
            $.ajax({
                type: 'POST',
                url: baseurl + "app_admin/get_add_appointment_payment",
                data: {ai: ai},
                beforeSend: function () {
                    $btn.button('loading').html('Wait..')
                },
                success: function (data) {
                    $btn.button('reset');
                    var html = $(data).filter('#myModalFormAdd');
                    $(html).modal('show');
                },
                error: function (data) {
                    $btn.button('reset');
                    alert("Sorry something went wrong on server.");
                }
            });

        });







    });

</script>








