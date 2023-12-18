<?php
$login = $this->Session->read('Auth.User.User');
$login1 = $this->Session->read('Auth.User');

echo $this->Html->css(array('select2.min.css'));
echo $this->Html->script(array('select2.min.js'));
?>
<?php  echo $this->Html->css(array('dataTableBundle.css','bootstrap-toggle.min.css')); ?>
<?php  echo $this->Html->script(array('dataTableBundle.js','jquery.maskedinput-1.2.2-co.min.js','comman.js','bootstrap-toggle.min.js')); ?>

<style>

    #search_form .form-group{
        float: left;
        width: 100%;

    }
    .col-md-2{
        width: 14%;
    }
    .dashboard_icon_li li {

        text-align: center;
        width: 18%;

    }
    .middle-block {
        margin-top: 30px;
    }
    label {
        font-size: 0.8em;
    }
    .form-control{
        padding: 2px !important;
    }
    .search_row{
        padding-left: 15px;
    }
    tr td, th{
        padding: 2px 3px !important;
    }
    .search_row .col-md-1 ,.search_row .col-md-2, .col-md-3{
        padding-right: 1px;
        padding-left: 1px;
    }
    .search_row .col-md-1 input{
        padding: 6px 10px;

    }
    .search_row {
        margin-top: 30px;
    }
</style>


<div class="Home-section-2">

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="container">
            <div class="row">
                <!--box 1 -->
                <!--box 1 -->
                <div class="middle-block">

                    <h3 class="screen_title" style="margin-bottom: -10px;">List View Appointemnt</h3>
                    <div class="col-lg-12 right_box_div">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 search_row">
                            <?php echo $this->Form->create('Search',array('type'=>'POST','id'=>'search_form','url'=>array('controller'=>'app_admin','action' => 'search_list_view_appointment'),'admin'=>true)); ?>
                            <div class="form-group" style="margin-bottom: 0;">

                                <div class="col-md-2" style="width: 8% !important;">
                                    <?php echo $this->Form->input('from_date', array('type'=>'text','placeholder' => 'Date From', 'label' => 'Date From', 'class' => 'form-control date_from')); ?>
                                </div>
                                <div class="col-md-2" style="width: 8% !important;">
                                    <?php echo $this->Form->input('to_date', array('type'=>'text','placeholder' => 'Date To', 'label' => 'Date To', 'class' => 'form-control date_to')); ?>
                                </div>

                                <div class="col-md-2">
                                    <?php echo $this->Form->input('payment_status', array('type' => 'select','empty'=>'All','options'=>array('PENDING'=>'Pending','SUCCESS'=>'Success','FAILURE'=>'Failure'),'label' => 'Payment Status', 'class' => 'form-control')); ?>
                                </div>

                                <div class="col-md-2">
                                    <?php echo $this->Form->input('consulting_type', array('type' => 'select','empty'=>'All','options'=>array('OFFLINE'=>'Hospital Visit','VIDEO'=>'Video','AUDIO'=>'Audio','CHAT'=>'Chat'),'label' => 'Consulting Type', 'class' => 'form-control')); ?>
                                </div>
                                <!--<div class="col-md-2">
                                    <?php /*echo $this->Form->input('booking_payment_type', array('type' => 'select','options'=>array('ONLINE'=>'Online','OFFLINE'=>'Offline'),'label' => 'Payment Mode', 'class' => 'form-control')); */?>
                                </div>-->
                                <div class="col-md-2">
                                    <?php echo $this->Form->input('status', array('type' => 'select','empty'=>'All','options'=>array('NEW'=>'New','CONFIRM'=>'Confirmed','RESCHEDULE'=>'Reschedule','CLOSED'=>'Closed','REFUND'=>'Refunded','CLOSED'=>'Closed'),'label' => 'Appointment Status', 'class' => 'form-control')); ?>
                                </div>

                                <div class="col-md-2">
                                    <?php echo $this->Form->input('booked_via', array('type' => 'select','empty'=>'All','options'=>array('APP'=>'App','WEB'=>'Web','IVR'=>'IVR','DOCTOR_PAGE'=>'Doctor Page','FACE_READER_TAB'=>'Dialar'),'label' => 'Booked From', 'class' => 'form-control')); ?>
                                </div>

                                <div class="col-md-2">
                                    <?php echo $this->Form->input('convince_fee', array('type' => 'select','empty'=>'All','options'=>array('YES'=>'YES','NO'=>'No'),'label' => 'Convince Fee', 'class' => 'form-control')); ?>
                                </div>


                                <div class="col-md-2">
                                    <?php echo $this->Form->input('doctor', array('type' => 'select','empty'=>'All','options'=>$this->AppAdmin->getHospitalDoctorList($login['thinapp_id']),'label' => 'Search By Doctor', 'class' => 'form-control','id'=>'doctor_list')); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-6 action_btn" >
                                    <div class="input text">

                                        <button type="button" onclick="window.history.back();" class="btn btn-info">Back</button>
                                        <button style="width: 30%" type="submit" id="search_button" class="btn btn-success">Search</button>
                                        <a class="btn btn-warning" href="<?php echo $this->Html->url(array('controller'=>'app_admin','action'=>'list_view_appointment')) ?>">Reset</a>
                                        <input type="checkbox" id="toggle-two" checked> </div>

                                </div>
                            </div>
                            <?php echo $this->Form->end(); ?>
                        </div>
                        <div class="Social-login-box content_box">
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

    .modal-header{
        background-color: #3a80bc;
        color: #FFFFFF;
        text-align: center;
    }
    .close {
        margin-top: 35px;
    }

    form .form-control{
        height: 35px;
    }
    .select2-container .select2-selection--single{
        height:35px !important;
    }
    .select2-container--default .select2-selection--single{
        border: 1px solid #ccc !important;
        border-radius: 0px !important;
    }



</style>






<script>
    $(document).ready(function () {

        var in_process = false;
        $(document).on("submit","#search_form",function (e) {

            e.preventDefault();
            if(in_process===false){
                var $btn = $("#search_button");
                $.ajax({
                    type:'POST',
                    url: baseurl+"app_admin/search_list_view_appointment",
                    data:$(this).serialize(),
                    beforeSend:function(){
                        in_process=true;
                        $btn.button('loading').html('<i class="fa fa-spinner fa-pulse fa-1x fa-fw"></i>  Search');
                    },
                    success:function(response){
                        $btn.button('reset');
                        in_process=false;
                        $(".content_box").html(response);
                    },
                    error: function(data){
                        in_process=false;
                        $btn.button('reset');
                    }
                });
            }

        });
        $("#search_form").trigger("submit");

        $('#toggle-two').bootstrapToggle({
            on: 'Auto Refresh On',
            off: 'Auto Refresh Off'
        });
        var interval =setTiming();
        $('#toggle-two').change(function() {
            if($(this).prop('checked')){
                interval = setTiming();
            }else{
                clearInterval(interval);
            }
        });

        function setTiming(){
            return setInterval(function () {
                $("#search_form").trigger("submit");
            },30000);
        }

        $('#doctor_list').select2();



        var date = new Date();
        var last = (new Date(date.getTime() - (670 * 24 * 60 * 60 * 1000)));
        $(".date_from, .date_to").datepicker({clearBtn:true,format: 'dd/mm/yyyy', startDate: last ,autoclose:true,orientation: "bottom auto",endDate: new Date()});
        $(".date_from, .date_to").mask("99/99/9999", {placeholder: 'dd/mm/yyyy'});



    });
</script>
