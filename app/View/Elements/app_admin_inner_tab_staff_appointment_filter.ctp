<?php  echo $this->Html->script(array('jquery.maskedinput-1.2.2-co.min.js')); ?>
<div class="row search_row appointment_search_bar">

    <div class="col-xs-12 col-xs-offset-0 main_div">

        <div class="row">

            <input type="hidden" name="as" value="<?php echo @$this->request->query['s']; ?>" id="app_status">

            <input type="hidden" name="aps" value="<?php echo @$this->request->query['p']; ?>" id="app_pay_status">
            <input type="hidden" name="apt" value="<?php echo @$this->request->query['pt']; ?>" id="app_pay_type">
            <input type="hidden" name="has_token" value="<?php echo @$this->request->query['has_token']; ?>" id="has_token">
            <input type="hidden" name="booking_via" value="ALL" id="booking_via">


            <div class="col-xs-2">
                <label>Patient Name</label>
                <input type="text" class="form-control large_search_box pat_name"  value="<?php echo @$this->request->query['n']; ?>" name="name" placeholder="Patient Name">

            </div>


            <div class="col-xs-2">
                <label>Patient Mobile</label>
                <input type="text" class="form-control large_search_box pat_mobile"  value="<?php echo @$this->request->query['m']; ?>" name="mobile" placeholder="Patient Mobile">

            </div>


            <div class="col-xs-3" style="display: none;">
                <input style="display: none;" type="text" class="form-control large_search_box"  value="<?php echo @$this->request->query['sv']; ?>" name="service" placeholder="Service Name">

            </div>

            <div class="col-xs-2">
                <label>Date From</label>
                <input type="text" class="form-control large_search_box datepicker from_date"  value="<?php echo date('d-m-Y'); ?>" name="date" placeholder="From Date">

            </div>

            <div class="col-xs-2">
                <label>Date To</label>
                <input type="text" class="form-control large_search_box datepicker to_date"  value="<?php echo date('d-m-Y'); ?>" name="month" placeholder="To date">

            </div>
            <div class="col-xs-4">
                <label>Select Address</label>
               <?php echo $this->Form->input('ad',array('name'=>'ad','type'=>'select','value'=>@$this->request->query['ad'],'div'=>false,'label'=>false,'empty'=>'All Address','options'=>$address_list,'class'=>'form-control large_search_box address_list')); ?>

               <select style="display: none;" class="form-control large_search_box sort_list">
           <option value="appointment_datetime##desc">Appointment DateTime - Descending</option>
           <option value="appointment_datetime##asc">Appointment DateTime - Ascending</option>
           <!--<option value="queue_number##asc">Token Number - Ascending</option>
           <option value="queue_number##desc">Token Number - Descending</option>-->
       </select>

            </div>


            <div class="col-xs-2">
                <label style="display: block;">Appointment Status</label>
                <div class="input-group-btn search-panel status_panel">
                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                        <span id="search_status_btn">All</span> <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="#">All</a></li>
                        <li><a href="#NEW">New</a></li>
                        <li><a href="#CONFIRM">Confirm</a></li>
                        <li><a href="#RESCHEDULE">Rescheduled</a></li>
                        <li><a href="#CANCELED">Canceled</a></li>
                        <li><a href="#CLOSED">Closed</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-xs-2">
                <label style="display: block;">Payment Status</label>
                <div class="input-group-btn search-panel payment_panel">
                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                        <span id="search_payment_btn">All</span> <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="#">All</a></li>
                        <li><a href="#PENDING">Pending</a></li>
                        <li><a href="#SUCCESS">Success</a></li>
                        <li><a href="#FAILURE">Failure</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-xs-1">
                <label style="display: block;">Has Token</label>
                <div class="input-group-btn search-panel has_token">
                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                        <span id="search_has_token_btn">All</span> <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="#">All</a></li>
                        <li><a href="#YES">Yes</a></li>
                        <li><a href="#NO">No</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-xs-2">
                <label style="display: block;">Payment Via</label>
                <div class="input-group-btn search-panel payment_type_panel">
                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                        <span id="search_payment_type_btn">All</span> <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="#">All</a></li>
                        <li><a href="#CASH">Cash</a></li>
                        <li><a href="#ONLINE">Online</a></li>
                        <?php
                        $login = $this->Session->read('Auth.User.User');
                        $list  = $this->AppAdmin->getHospitalPaymentTypeArray($login['thinapp_id']);

                        if(!empty($list)){
                            foreach ($list as $key => $value){
                                echo  "<li><a href='#$key'>$value</a></li>";
                            }
                        }

                        ?>


                    </ul>
                </div>
            </div>
            <div class="col-xs-1">
                <label style="display: block;">Booking Via</label>
                <div class="input-group-btn search-panel booking_from_panel">
                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                        <span id="search_booking_via_btn">All</span> <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="#">All</a></li>
                        <li><a href="#IVR">IVR</a></li>
                        <li><a href="#APP">App</a></li>
                        <li><a href="#WEB">Web</a></li>
                        <li><a href="#DOCTOR_PAGE">Doctor Page</a></li>
                    </ul>
                </div>
            </div>



            <div class="col-xs-4">
                <label style="display: block;">&nbsp;</label>
                <button class="btn btn-default search_btn" id="search_btn" type="button"><span class="glyphicon glyphicon-search"></span> Search</button>
                <button class="btn btn-default reset_btn search_btn" type="button" data-ref ="<?php echo Router::url('/app_admin/view_staff_app_schedule',true)."?st=".@$this->request->query['st']."&&d=".@$this->request->query['d']; ?>"><span class="glyphicon glyphicon-refresh"></span> Refresh </button>

                <a onclick="window.history.back();" class="btn btn-default search_btn" type="button"><span class="fa fa-arrow-left"></span> Go Back</a>
                <a class="btn btn-info" style="margin: 0px 5px;" href="<?php echo Router::url('/app_admin/list_view_appointment',true)?>"><i class="fa fa-bars"></i> List View</a>

            </div>









        </div>


    </div>
</div>
<style>
    input.large_search_box {

    }
    .input-group-btn button{
        width: 100%;
    }
    .input-group-btn{
        vertical-align: top !important;
        display: inline-table;
        width: 100%;
        float: left;
        margin: 0px 4px;
    }

    .payment_type_panel{

    }

    nput.large_search_box:focus {
        border-color: #339933;
    }
    select.large_search_box {

    }
    select.large_search_box:focus {
        border-color: #339933;
    }
    .appointment_search_bar .large_search_box {

        width: 100% !important;
        padding: 5px 5px;

    }
    .address_list{
        width:100%;
    }
    .pat_name, .pat_mobile{

    }
    .ser_lbl{
        position: absolute;
    }
    .appointment_search_bar .address_list {
        width: 100% !important;
    }
</style>
<script>
    $(document).ready(function(){
        /*serach box script start*/
        var concept = $('#app_status').val();

        if(concept!=""){
            $('#search_status_btn').text(ucfirst(concept,true));
        }
        $('.status_panel .dropdown-menu').find('a').click(function(e) {
            e.preventDefault();
            var param = $(this).attr("href").replace("#","");
            var concept = $(this).text();
            $('.search-panel span#search_status_btn').text(concept);

            $('#app_status').val(param);
        });



        var concept = $('#app_pay_status').val();
        if(concept!=""){
            $('#search_payment_btn').text(ucfirst(concept,true));
        }
        $('.payment_panel .dropdown-menu').find('a').click(function(e) {
            e.preventDefault();
            var param = $(this).attr("href").replace("#","");
            var concept = $(this).text();
            $('.search-panel span#search_payment_btn').text(concept);
            $('#app_pay_status').val(param);
        });

        $('.payment_type_panel .dropdown-menu').find('a').click(function(e) {
            e.preventDefault();
            var param = $(this).attr("href").replace("#","");
            var concept = $(this).text();
            $('.search-panel span#search_payment_type_btn').text(concept);
            $('#app_pay_type').val(param);
        });

        $('.has_token .dropdown-menu').find('a').click(function(e) {
            e.preventDefault();
            var param = $(this).attr("href").replace("#","");
            var concept = $(this).text();
            $('.search-panel span#search_has_token_btn').text(concept);
            $('#has_token').val(param);
        });


        $('.booking_from_panel .dropdown-menu').find('a').click(function(e) {
            e.preventDefault();
            var param = $(this).attr("href").replace("#","");
            var concept = $(this).text();
            $('.search-panel span#search_booking_via_btn').text(concept);
            $('#booking_via').val(param);
        });


        $('.reset_btn').find('a').click(function(e) {
            e.preventDefault();
            var param = $(this).attr("href").replace("#","");
            var concept = $(this).text();
            $('.search-panel span#search_payment_btn').text(concept);
            $('#app_pay_status').val(param);
        });

        $(document).on("click", ".reset_btn", function(){
            window.location.href = $(this).attr("data-ref");
        });

    $(document).on("click", ".back_btn", function(){
            window.location.href = $(this).attr("data-ref");
        });


        function ucfirst(str,force){
            str=force ? str.toLowerCase() : str;
            return str.replace(/(\b)([a-zA-Z])/,
                function(firstLetter){
                    return   firstLetter.toUpperCase();
                });
        }

        $('.datepicker').datepicker({
            format:'dd-mm-yyyy'
        });


    });
</script>