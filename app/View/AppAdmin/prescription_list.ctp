<?php
$login = $this->Session->read('Auth.User.User');
$login1 = $this->Session->read('Auth.User');
echo $this->Html->css(array('select2.min.css'));
echo $this->Html->script(array('select2.min.js'));

?>
<?php  echo $this->Html->css(array('dataTableBundle.css')); ?>
<?php  echo $this->Html->script(array('dataTableBundle.js','jquery.maskedinput-1.2.2-co.min.js','comman.js')); ?>


<style>

    .select2-container .select2-selection--single{
        height:35px !important;
    }
    .select2-container--default .select2-selection--single{
        border: 1px solid #ccc !important;
        border-radius: 0px !important;
    }

    .form-control{
        height: 36px;
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

                    <h3 class="screen_title">Prescription List</h3>

                    <div class="col-lg-12 right_box_div">

                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 search_row">
                            <?php echo $this->Form->create('Search',array('type'=>'POST','url'=>array('controller'=>'app_admin','action' => 'search_prescription_list'))); ?>
                            <div class="form-group">

                                <div class="col-md-2" >
                                    <?php echo $this->Form->input('from_date', array('type'=>'text','placeholder' => 'Date From', 'label' => 'Date From', 'class' => 'form-control date_from')); ?>
                                </div>
                                <div class="col-md-2" >
                                    <?php echo $this->Form->input('to_date', array('type'=>'text','placeholder' => 'Date To', 'label' => 'Date To', 'class' => 'form-control date_to')); ?>
                                </div>

                                <div class="col-md-2" >
                                    <?php echo $this->Form->input('patient_name', array('type'=>'text','placeholder' => '', 'label' => 'Patient Name', 'class' => 'form-control')); ?>
                                </div>

                                <div class="col-md-2" >
                                    <?php echo $this->Form->input('patient_mobile', array('type'=>'text','placeholder' => '', 'label' => 'Patient Mobile', 'class' => 'form-control')); ?>
                                </div>

                                <div class="col-md-2">
                                    <?php echo $this->Form->input('doctor_id', array('type' => 'select','empty'=>'All','options'=>$this->AppAdmin->getHospitalDoctorList($login['thinapp_id']),'label' => 'Search By Doctor', 'id'=>'doctor_id', 'class' => 'form-control')); ?>
                                </div>
                                <div class="col-sm-3 action_btn" style="width: 19%;" >
                                    <div class="input text">
                                        <label style="display: block;">&nbsp;</label>
                                        <button type="submit" class="btn btn-success">Search</button>
                                        <button type="button" onclick="window.history.back();" class="btn btn-info">Back</button>
                                        <a class="btn btn-warning" href="<?php echo $this->Html->url(array('controller'=>'app_admin','action'=>'prescription_list')) ?>">Reset</a>
                                    </div>

                                </div>

                            </div>
                            <?php echo $this->Form->end(); ?>
                        </div>

                        <div class="Social-login-box payment_bx">

                            <div class="form-group row">
                                <div class="col-sm-12">

                                    <?php if(!empty($list)){ ?>
                                    <div class="table table-responsive">
                                        <table id="data_table" class="table table-striped table-bordered" style="width:100%">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Patient Name</th>
                                                <th>Patient Mobile</th>
                                                <th>Doctor Name</th>
                                                <th>Date</th>
                                                <th>Action</th>


                                            </tr>
                                            </thead>
                                            <tbody>

                                            <?php foreach($list AS $key => $value){ ?>
                                                <tr>
                                                    <td><b><?php echo $key+1; ?>.</b></td>
                                                    <td>
                                                        <b><?php echo $value['patient_name']; ?></b>
                                                    </td>
                                                    <td><?php echo $value['patient_mobile']; ?> </td>
                                                    <td><?php echo $value['doctor_name']; ?> </td>
                                                    <td><?php echo date('d-m-Y h:i A',strtotime($value['created'])); ?> </td>
                                                    <td>

                                                        <a class="btn btn-xs btn-success" href="<?php echo Router::url('/app_admin/download_prescription/',true ).base64_encode($value['file_path']).'/'.base64_encode($value['patient_name']); ?>">Download</a>
                                                        <a class="btn btn-xs btn-info" href="<?php echo $value['file_path'] ?>" target="_blank">View</a>


                                                    </td>
                                                </tr>
                                            <?php } ?>


                                            </tbody>
                                        </table>
                                    </div>
                                    <?php }else{ ?>
                                        <h4 style="text-align: center;width: 100%;"> No prescription found</h4>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="clear"></div>

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
    #example1_length {
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
</style>
<script>
    $(document).ready(function () {



        $('#doctor_id').select2();

        var date = new Date();
        var last = (new Date(date.getTime() - (365 * 24 * 60 * 60 * 1000)));
        $(".date_from, .date_to").datepicker({clearBtn:true,format: 'dd/mm/yyyy', startDate: last ,autoclose:true,orientation: "bottom auto",endDate: new Date()});
        $(".date_from, .date_to").mask("99/99/9999", {placeholder: 'dd/mm/yyyy'});


        $(document).on('click','.pay_due_amount_btn',function(e){
            var $btn = $(this);
            var obj = $(this);
            var id = $(this).attr('data-id');
            var jc = $.confirm({
                title: 'Payment',
                content: 'Are you sure you have collected this amount?',
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
                                url: baseurl+"app_admin/pay_due_appointment",
                                data:{id:id},
                                beforeSend:function(){
                                    $btn.button('loading').html('Wait..')
                                },
                                success:function(data){
                                    var response = JSON.parse(data);
                                    if(response.status==1){
                                        jc.close();
                                        $(obj).closest("tr").find(".status_box").html("PAID");
                                        $btn.remove();

                                        var ID = response.order_id;
                                        window.open(baseurl+"app_admin/print_invoice/"+ID+"/DUE","_blank");
                                        window.location.reload();



                                    }else{
                                        $.alert(response.message);
                                        $btn.button('reset');
                                    }
                                },
                                error: function(data){
                                    $btn.button('reset');
                                    $.alert("Sorry something went wrong on server.");
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
        $(document).on('click','.undo_btn',function(e){
            var $btn = $(this);
            var obj = $(this);
            var id = $(this).attr('data-id');
            var jc = $.confirm({
                title: 'Revert Payment',
                content: 'Are you sure you want to revert this amount?',
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
                                url: baseurl+"app_admin/refund_due_appointment",
                                data:{id:id},
                                beforeSend:function(){
                                    $btn.button('loading').html('Wait..')
                                },
                                success:function(data){
                                    var response = JSON.parse(data);
                                    if(response.status==1){
                                        jc.close();
                                        window.location.reload();

                                    }else{
                                        $.alert(response.message);
                                        $btn.button('reset');
                                    }
                                },
                                error: function(data){
                                    $btn.button('reset');
                                    $.alert("Sorry something went wrong on server.");
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

    });
</script>
