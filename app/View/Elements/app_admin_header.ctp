    <?php

$login = $this->Session->read('Auth.User');
$is_first_login = $this->AppAdmin->app_has_default_channel($login['User']['thinapp_id']);
$template ='';
if(isset($login['User']['thinapp_id'])){
    $template = $this->AppAdmin->get_app_template($login['User']['thinapp_id']);
}

$action = $this->request->params['action'];
$role  = $this->Session->read('Auth.User.USER_ROLE');

$allow_token_booking = "YES";
if($role=="RECEPTIONIST"){
    $staff_data = $this->AppAdmin->get_doctor_by_id($login['AppointmentStaff']['id'],$login['User']['thinapp_id']);
    if($staff_data['allow_web_token_booking']=="NO"){
        $allow_token_booking = "NO";
    }
}






?>

<div class="header">
    <div class="top-bar"> </div>

    <div class="navbar navbar-inverse" >
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
                
            </div>
            <?php if(!empty($login)){ ?>
            <div class="center_title">
                <img style="width:65px;height: 65px;" class="logo" src="<?php echo $login['Thinapp']['logo']; ?>" alt="logo">
                <span style="color:#03A9F5;"><?php echo $login['Thinapp']['name'];?></span>

            </div>
            <?php } ?>


            <div class="collapse navbar-collapse navbar-right wow bounceInLeft dropdown">
                <div class="top-info">

                    <?php if(!empty($login)){ ?>

                        <label class="dashboard "  >
                            <?php if(isset($login['LabPharmacyUser'])){ ?>
                            Hi... <?php echo $login['LabPharmacyUser']['name']; ?>
                            <?php }
                            else
                            { ?>
                                Hi... <?php echo isset($login['AppointmentStaff']['name'])?$login['AppointmentStaff']['name']:$login['User']['username']; ?>
                            <?php }?>

                        </label>
                        <a class="dashboard center_box" href="<?php echo Router::url('/app_admin/dashboard',true); ?>"><i class="fa fa-home"></i> Dashboard</a>
                        <?php if($login['USER_ROLE'] =='ADMIN'){ ?>
                            <a class="dashboard center_box" href="<?php echo Router::url('/app_admin/doctor',true); ?>" ><i class="fa fa-users"></i> Doctors</a>

                        <?php }else if($login['USER_ROLE'] =='LAB' || $login['USER_ROLE'] =='PHARMACY'){ ?>
                            <a class="dashboard center_box change_lab_password" data-type='lab' data-id="<?php echo base64_encode($login['LabPharmacyUser']['id']); ?>" href="javascript:void(0);" ><i class="fa fa-key"></i> Change Password</a>

                        <?php }else{ ?>
                            <a class="dashboard center_box change_password" data-type="doctor" data-id="<?php echo base64_encode($login['AppointmentStaff']['id']); ?>" href="javascript:void(0);" ><i class="fa fa-key"></i> Change Password</a>
                        <?php } ?>


                        <a class="dashboard logout_link" href="<?php echo Router::url('/app_admin/logout')?>" > <i class="fa-power-off fa"></i>&nbsp;Logout</a>
                    <?php } ?>
                   <!-- <?php /*if(empty($login)){ */?>
                            <a href="<?php /*echo Router::url('/register-org',true); */?>" class="dashboard" title="Create Own APP"><i class="fa-android fa"></i>&nbsp; Create Own APP</a>
                            <a href="javascript:void(0);" class="dashboard button-bg logout_link"><i class="fa-sign-in fa"></i>&nbsp; Login/Signup</a>
                    --><?php /*} */?>

                </div>

            </div>



        </div>

    </div>
</div>

<script type="text/javascript">
    $(function(){
            var action = "<?php echo $this->request->params['action'];?>";

            function setCookie(cname, cvalue, exdays) {
                var d = new Date();
                d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
                var expires = "expires="+d.toUTCString();
                document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
            }

            function getCookie(cname) {
                var name = cname + "=";
                var ca = document.cookie.split(';');
                for(var i = 0; i < ca.length; i++) {
                    var c = ca[i];
                    while (c.charAt(0) == ' ') {
                        c = c.substring(1);
                    }
                    if (c.indexOf(name) == 0) {
                        return c.substring(name.length, c.length);
                    }
                }
                return "";
            }

            if(action=="add_appointment"){
                setCookie("bookingSetting","OLD_SCREEN",3600);
            }
            if(action=="lite_book_appointment"){
                setCookie("bookingSetting","NEW_SCREEN",3600);
            }

            $(".app_nav a").removeClass("button-bg");
            $("."+action).addClass("button-bg");
            if(action=="download" || action =="payment"){
                $(".app_info").addClass("button-bg");
            }

        if(action=="edit_cms" || action=="add_cms"){
            $(".cms").addClass("button-bg");
        }

        if( action=="edit_channel" || action=="add_channel" || action=="add_channel" || action =="add_message" || action=="factory"){
            $(".channel").addClass("button-bg");
        }

        if( action=="add_subscriber" || action=="subscriber" ){
            $(".subscriber").addClass("button-bg");
        }


        $(document).on("mouseover", ".nav li", function(){
            if(!$(this).hasClass("nav_cls")){
                $(".nav .dropdown-menu").hide();
            }else{
                $(".nav .dropdown-menu").show();
            }

        })
        $(document).on("mouseout", ".nav .dropdown-menu", function(){
                $(this).hide();
        })


        $(document).on("click", ".change_password", function(){

            var pass = prompt("Please enter password", "");
            var id = $(this).attr('data-id');
            var type = $(this).attr('data-type');

            if (pass != null) {

                $.ajax({
                    url: baseurl+'app_admin/change_doctor_password',
                    data:{i:id,p:pass,type:type},
                    type:'POST',
                    beforeSend:function () {

                    },
                    success: function(result){
                       var data = JSON.parse(result);
                        $.alert(data.message);
                    },
                    error:function () {
                        $btn.button('reset');
                    }
                });

            }


        })

        $(document).on("click", ".change_lab_password", function(){

            var pass = prompt("Please enter password", "");
            var id = $(this).attr('data-id');
            var type = $(this).attr('data-type');

            if (pass != null) {

                $.ajax({
                    url: baseurl+'app_admin/change_lab_password',
                    data:{i:id,p:pass,type:type},
                    type:'POST',
                    beforeSend:function () {

                    },
                    success: function(result){
                       var data = JSON.parse(result);
                        $.alert(data.message);
                    },
                    error:function () {
                        $btn.button('reset');
                    }
                });

            }


        })


        $(document).on("click", ".nav .dropdown-toggle", function(){
            $(".nav .dropdown-menu").toggle();
        })


        $(document).off('click','.get_bed_status');
        $(document).on('click','.get_bed_status',function(e){
            var $btn = $(this);
            $.ajax({
                url: "<?php echo Router::url('/app_admin/get_ipd_bed_status',true);?>",
                type:'POST',
                contentType: "application/json",
                beforeSend: function () {
                    $btn.button('loading').html('Loading...')
                },
                success: function(result){
                    var html = $(result).filter('#get_ipd_bed_status');
                    $(html).modal('show');
                    $btn.button('reset');
                },error:function () {
                    $btn.button('reset');
                }



            });

        });


      /* $(document).off("click", ".AVAILABLE, [data-type='ADD_MORE_TOKEN'], [data-type='WALK-IN'], [data-type='SUB_TOKEN']");*/

        /* this code work for load appointment modal */
        $(document).off("click", ".AVAILABLE, .EXPIRED, .add_more_token, .add_sub_token, .add_walk-in_token, .add_walk-in_token_sidebar, .add_emergency_token");
        var loadProcess = false;
        var allow_token_booking = "<?php echo $allow_token_booking; ?>";

        $(document).on("click", ".AVAILABLE, .EXPIRED, .add_more_token, .add_sub_token, .add_walk-in_token, .add_walk-in_token_sidebar, .add_emergency_token", function (e) {
            $("#search_cus_modal_without_token").html('');
            if(loadProcess == false)
            {

                var bookingSetting = getCookie("bookingSetting");
                activeQueue = $(this).attr("queue_number");
                $( ".AVAILABLE" ).removeClass( "is-selected" );
                $(this).addClass("is-selected");
                var $btn = $(this);
                var slot = $(this).attr('data-slot');
                var custom = "NO";
                var queue = 0;
                var is_loading = false;

                var appointment_type = 'AVAILABLE_TOKEN';
                if($(this).attr("data-type") == "ADD_MORE_TOKEN"){
                    queue = $(this).attr('queue');
                    custom = 'YES';
                    slot = $(this).attr('data-slot');
                    appointment_type = "ADD_MORE_TOKEN";
                }else if($(this).attr("data-type") == "SUB_TOKEN"){
                    appointment_type = "SUB_TOKEN";
                    custom = "NO";
                    queue = $(this).attr('queue_number');

                }else if($(this).attr("data-type") == "WALK-IN"){
                    appointment_type = "WALK-IN";
                    custom = "NO";
                    if($(this).hasClass("break_walk_in")){
                        slot = $(this).attr('data-last-time');
                    }
                }else if($(this).attr("data-type") == "EMERGENCY"){
                    appointment_type = "EMERGENCY";
                    custom = "NO";
                }else{
                    appointment_type = "AVAILABLE_TOKEN";
                    custom = "NO";
                    queue = $(this).attr('queue_number');
                }

if(allow_token_booking=="NO" && (appointment_type == "AVAILABLE_TOKEN" || appointment_type == "SUB_TOKEN")){
                     $.alert("You can book WALK-IN, EMERGENCY, BOOK MORE tokens only.");
                }else{
                var obj = $(".slot_date");
                var date = ($('#appointment_date').val())?$('#appointment_date').val():'';
                $.ajax({
                    type:'POST',
                    url: baseurl+"app_admin/search_appointment_customer",
                    data:{date:date},
                    beforeSend:function(){
                        loading(obj,true,"");
                        is_loading=true;
                        loadProcess = true;
                    },
                    success:function(data){

                        $('#search_cus_modal').remove();
                        var html = $(data).filter('#search_cus_modal');
                        $(html).modal('show').modal("show").attr("data-slot",slot).attr("data-type",appointment_type).attr('data-queue',queue);

                        loading(obj,false);
                        is_loading = false;
                        loadProcess = false;
                        //$(".service_drp").trigger("change");
                    },
                    error: function(data){
                        loading(obj,false);
                        is_loading = false;
                        loadProcess = false;
                        alert("Sorry something went wrong on server.");

                    }
                });
                }

            }
        });

        $(document).on('click','.edit_patient_btn',function(){
            var pi = $(this).attr('data-pi');
            var pt = $(this).attr('data-pt');
            var id = $(this).attr('data-id');
            var data = {pi:pi,pt:pt};
            if(id){
                data = {pi:pi,pt:pt,ai:id};
            }

            var reload = $(this).attr('reload');
            var $btn = $(this);
            $.ajax({
                url: "<?php echo Router::url('/app_admin/load_edit_customer_modal',true);?>",
                type:'POST',
                data:data,
                beforeSend:function(){
                    $btn.button('loading').html('Wait..');
                },
                success: function(result){
                    var html = $(result).filter('#edit_customer_dialog');
                    $(html).modal('show');
                    $btn.button('reset');
                    if(reload){
                        window.location.reload();
                    }


                },error:function () {
                    $btn.button('reset');

                }
            });
        });

        $(document).on('hidden.bs.modal', '#block_slot_modal, #lab_more_patient, #add_new_patient, #update_ward_modal, #ward_history, #myModalFormAdd, #editPaymentModal, #search_cus_modal, #edit_customer_dialog, #send_sms_modal, #send_blog_modal, #myModalRefundForm, #switchModal', function () {
            $(this).remove();
        });



    })
</script>
<style>
        .center_title{           
            float: left;
            text-align: center;
            font-size: 30px;
            color: #fff;
        }
        .center_title img{
            width: 65px;
            height: 65px;

            border-radius: 57px;
            margin: 2px;
        }
        .dashboard{
            font-size: 15px;
            /* height: 31px; */
            background: #94c405;
            color: #fff;
            padding: 9px 9px !important;
            margin: 0 auto !important;
            float: left;
            border-radius: 15px 0px 0px 15px;
            border-right: 1px solid #fff;
        }
        .logout_link{
            border-radius: 0px 15px 15px 0px;
        }
        .center_box{
            border-radius: 0px;
        }

    </style>

<?php if($template=="THEME_2"){ ?>
        <style>


            <?php if($action=="add_appointment"){ ?>
            .center_title{

                display: none !important;
            }
            <?php } ?>

            .option_btn_panel{
        width: 250px;
    }


    .slot_blok li {
        float: left;
        list-style: outside none none;
        margin: 2px 0px;
        padding: 0 3px;
        text-align: center;
        width: 16.6%;
    }

    .slot_blok li .button-div button {
         white-space: normal;
        line-height: 10px;
        font-size: 11px;
        width: 47%;
        float: left;
        margin: 1px 1px;
        padding: 1px 0px;
        border-radius: 1px !important;
        height: 25px;
    }


    .option_btn_panel button{
        float: left;
        width: 48%;
        margin: 1px 2px;
        padding: 2px 0px;
    }

    .today_collection_lbl{
        float: left;
        padding: 2px 0px;

    }
    .today_collection_lbl strong{
        padding: 0px 3px;
        margin: 0px;
        font-size: 20px;
        width: 100%;
        float: left;
        border-right: 2px solid #167dc7cc;
        background: none;
    }
    .total_earning_div ul{
        float: left;
        width: 100%;
        padding: 0px 0px;
        margin: 0px;

    }
    .total_earning_div{
        width: 49%;
        height: 70px;
        position: fixed;
        left: 0px;
        background: #fff;
        z-index: 1030;
        top: 5px;
        list-style: none;

    }
    .total_earning_div .total_amount_lbl{
        list-style: none;
        float: left;
        font-size: 12px;
        padding: 1px 6px;
        background: blue;
        color: #fff;
        border-radius: 33px;
        margin: 1px 2px;
    }
    .extra_link{
        width: 100%;
        padding: 1px 2px;
        border: none;
        float: left;
        border-radius: 0px;
        text-align: left;
        color: #167dc7cc;
        font-weight: 600;
        text-transform: capitalize;
        line-height: 15px;
        height: 17px !important;
    }

    .short_cut{
        font-size: 10px;
        float: left;
        position: absolute;
        left: 15px;
        transform: rotate(0deg);
        top: -8px;
        font-weight: 600;
        color: red;

    }




    .slide_fix {
        width: auto;
        float: left;
        position: fixed;
        right: 0;
        z-index: 1030;
        padding: 0px 0px;
        background: #fff;
        border-radius: 0px 0px 10px 0px;
        border: 0px solid rgba(148, 196, 5, 1);
        top: 5px;
        border-right: 1px solid #d2d6de;
        height: 70px;
    }


    .slide_fix .dash_img > img {
        height: 25px;
        width: 25px;
        margin: 2px 0px 5px 0px;
    }

    .slide_fix .side_bar_box_btn {
        margin-bottom: 1px;
        margin-top: 1px;
        text-align: center;
        display: block;
        padding: 1px 3px;
        border-left: 1px solid #dedada;

        width: auto;
        height: 100%;
    }
    .slide_fix .content_div {
        border-radius: 1em;
        line-height: 20px;
        font-size: 9px !important;
        padding: 2px 2px !important;
        border: 0px solid #94C405 !important;
        font-size: 12px;
        line-height: 10px;
        margin-top:5px;
        text-transform: uppercase;

        width: 100% !important;
        text-align: center;

        width: min-intrinsic;
        width: -webkit-min-content;
        width: -moz-min-content;
        width: min-content;
        display: table-caption;
        display: -ms-grid;
        -ms-grid-columns: min-content;

    }

    .list_load_section{
        margin-top: -15px !important;
    }
    .search_mobile_main_div{
        padding: 0px 4px;
        margin: 0px;
        border-left: 1px solid #f1f1f1;
    }
    .select_patient_option{
        float: left;
        width: 100%;
        display: block;
        text-align: center;
        height: 100%;
    }
    .error_messages_box{
        float: left;
        width: 100%;
        display: block;
        text-align: center;
        height: 100%;
        margin-top: -8px;
    }
    .dynamic_option_box{
        margin-bottom: 5px;
        height: 30px;
        padding: 0px;
        width: 100%;
        float: left;
        position: relative;
        display: block;
    }

    .patient_radio_button_div .inner_content_div{
        margin: 0px;
        padding: 0px 10px;
    }
    .loader_icon_on_booking{
        position: absolute;
        left: 40.2%;
        top: 21px;
    }

    .side_bar_box_btn a{
        float: left;
        width: 100%;
    }


    @media only screen and (max-width: 760px),(max-device-width: 1024px)  {
        .slide_fix .side_bar_box_btn{
            float: left !important;
            min-width: auto;
            padding: 1px 2px;
        }
        .list_btn_tab li{
            width: 10%;
            margin: 0px 0px;
            display: inline-table;
        }
        .total_earning_div{
            width: 35%;
        }
        .option_div {
            margin-top: 50px;
        }
        .list_btn_tab .btn{
            font-size: 12px;
        }
        #show-all-appointment{
            display: none;
        }
        .slot_blok li{
            width: 10%;
        }
        .slot_blok li .button-div button{
            font-size: 10px;
        }
        .add_cus_div_app_load label{
            font-size: 9px;
        }
        #myModalFormAdd .input{
            padding: 0px 3px;
        }
        #myModalFormAdd .input label{
            font-size: 9px;
        }
        #formPaySearch .input .close, #formPaySearch .removeRowAdd, #formPaySearch .removeRowSearch, #formPay .removeRow, #formPayAdd .removeRowAdd {
            padding: 0px 1px !important;
            opacity: 1;
            color: #3274AC;
            border-radius: 41px;
            border: 1px solid !important;
            float: left;
            height: 25px;
            width: 25px;
        }
    }

</style>
<?php }else if($template=="THEME_1"){ ?>
<style>

    .slot_blok .button-div button{
        width: 100%;
    }
    .slot_blok li {
        height: 280px;
    }
    .slot_blok .slot_main_div{
        height: 280px !important;
    }
    .right_box_div{
        width: 92% !important;
        float: right !important;
    }

    .total_earning_div{
        position: fixed;
        padding: 0px;
        margin: 0px;
        list-style: none;
        background: #eb9316;
        top:32%;
        right: 0;
        width: 10%;

    }
    .total_earning_div ul{
        margin: 0px;
        padding: 0px;
        float: left;
        width: 100%;
    }
    .total_earning_div li{
        margin: 0px;
        padding: 0px 5px;
        list-style: none;
        color:#fff;
        width: 100%;
        float: left;
        display: block;
        text-align: left;
    }

</style>
<?php } ?>

