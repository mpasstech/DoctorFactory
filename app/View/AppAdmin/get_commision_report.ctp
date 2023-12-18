<?php
$login = $this->Session->read('Auth.User.User');
$login1 = $this->Session->read('Auth.User');
$thinappName = $login1['Thinapp']['name'];

$show_conceive_date = $this->AppAdmin->has_category($login['thinapp_id'],array(17,22,23));
$table_columns = $this->AppAdmin->get_billing_table_column($login['thinapp_id']);


?>
<?php  echo $this->Html->css(array('dataTableBundle.css','magicsuggest-min.css','bootstrap-multiselect.css')); ?>
<?php  echo $this->Html->script(array('dataTableBundle.js','jquery.maskedinput-1.2.2-co.min.js','comman.js','magicsuggest-min.js','bootstrap-multiselect.js')); ?>




<style>

    #example1_wrapper{
        overflow-x: auto;
    }


    .blink_text{
        float: right !important;
        right: 330px;
        position: absolute !important;
        color: #b12b2b !important;
        margin: 8px 0px !important;
        font-size: 13px;
    }
    .ms-ctn .ms-sel-ctn {

        margin-left: unset;
        max-height: 26px;
        min-height: 26px;

    }
    .ms-res-ctn .ms-res-item {

        line-height: unset;
        text-align: left;
        padding: 9px 5px;
        color: #666;
        cursor: pointer;

    }
    .ms-helper{ display:none !important; }
    .ms-sel-ctn .ms-sel-item{ color: #000000 !important;min-height: 24px; }


    .ms-close-btn {
        top: 0;
        position: absolute;
        right: 33px;
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
    .form-control {
        height: 35px;
        padding: 1px 2px !important;
    }
    .action_btn{
        padding: 0px 1px;
        float: left;
        text-align: right;
    }
    .heading_lable{
        text-align: center;
        background: #d6d6d6;
        padding: 5px 0px;
        float: left;
        width: 100%;
    }

    .multiselect-native-select .btn-group{
        float: right !important;
    }

    .multiselect{
        padding: 4px 2px;
    }
    .search_row{
        padding-left: 15px;
    }
    tr td, th{
        padding: 2px 2px !important;
    }
    .search_row .col-md-1 ,.search_row .col-md-2{
        padding-right: 1px;
        padding-left: 1px;
    }
    .search_row .col-md-1 input{
        padding: 6px 10px;

    }
    .search_row {
        margin-top: 30px;
    }

    #example_length {
        width: 32%;
        text-align: right;
    }
    #example1_length {
        width: 32%;
        text-align: right;
    }
    #example1{
        font-size: 12px !important;
    }
    .modal-header{
        background-color: #3a80bc;
        color: #FFFFFF;
        text-align: center;
    }
    .close {
        margin-top: 35px;
    }
    .multiselect-container li{
        width: 50%;
        float: left;
        padding: 0px 3px;
        text-align: left;
    }
</style>

<div class="Home-section-2">

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="container">
            <div class="row">
                <!--box 1 -->
                <!--box 1 -->
                <div style="margin-right: -40px;" class="middle-block">
                    <!-- Heading -->
                    <h3 class="screen_title">Commission Reports</h3>
                    <?php
                    echo $this->element('billing_inner_header');
                    ?>
                    <div  class="col-lg-12 right_box_div">

                        <div style="background: #d4d1d126;"  class="col-lg-12 col-md-12 col-sm-12 col-xs-12 search_row">
                            <?php echo $this->Form->create('Search',array('type'=>'POST','url'=>array('controller'=>'app_admin','action' => 'search_get_commision_report'),'admin'=>true)); ?>
                            <div class="form-group">


                                <?php $col = 7; $offset = 1; $sm = 2; if( $login1['USER_ROLE'] !="ADMIN") {$col = 3; $offset=0; $sm=1; } ?>

                                <?php if ( $login1['USER_ROLE'] != 'LAB' && $login1['USER_ROLE'] != 'PHARMACY') { ?>
                                <div class="col-md-1">
                                    <?php echo $this->Form->input('receipt_category', array('type' => 'select','empty'=>'All','options'=>array('OPD'=>'OPD','IPD'=>'IPD','LAB'=>'LAB','PHARMACY'=>'PHARMACY','OTHER'=>'Direct Billing','PACKAGE'=>'Package','DUE_AMOUNT'=>'Due Amount'),'label' => 'By Category', 'class' => 'form-control')); ?>
                                </div>
                                <?php } ?>

                                <div class="col-md-2">
                                    <?php echo $this->Form->input('category', array('type' => 'select','label' => 'Search by Service Category', "empty"=>"All", "options"=>$this->AppAdmin->billing_report_category_list($login['thinapp_id']), 'class' => 'form-control')); ?>
                                </div>
                                <div class="col-md-1" >
                                    <?php echo $this->Form->input('from_date', array('autocomplete'=>'off', 'type'=>'text','placeholder' => '', 'label' => 'Date From', 'class' => 'form-control date_from')); ?>
                                </div>
                                <div class="col-md-1" >
                                    <?php echo $this->Form->input('to_date', array('autocomplete'=>'off', 'type'=>'text','placeholder' => '', 'label' => 'Date To', 'class' => 'form-control date_to')); ?>
                                </div>
                                <div class="col-md-2">
                                    <?php echo $this->Form->input('service', array('type' => 'select','empty'=>'All','options'=>$this->AppAdmin->billing_report_service_list($login['thinapp_id']),'label' => 'Search By Product', 'class' => 'form-control')); ?>
                                </div>

                                <?php if ( $login1['USER_ROLE'] != 'LAB' && $login1['USER_ROLE'] != 'PHARMACY') { ?>
                                <div class="col-md-2">
                                    <?php echo $this->Form->input('doctor', array('type' => 'select','empty'=>'All','options'=>$this->AppAdmin->getHospitalDoctorList($login['thinapp_id']),'label' => 'Search By Doctor', 'class' => 'form-control')); ?>
                                </div>
                                <?php } ?>

                                <?php if ( $login1['USER_ROLE'] =="ADMIN") { ?>
                                    <div class="col-md-2">
                                        <?php echo $this->Form->input('biller', array('type' => 'select','empty'=>'All','options'=>$this->AppAdmin->billing_report_biller_list($login['thinapp_id']),'label' => 'Search By Biller', 'class' => 'form-control')); ?>
                                    </div>
                                <?php } ?>



                                <div class="col-md-1">
                                    <?php

                                    $payment_types = $payment_type_table_header = $this->AppAdmin->billing_report_payment_type_list($login['thinapp_id']);
                                    $payment_types['PAY_BY_USER']='Online payments by patients';

                                    ?>
                                    <?php echo $this->Form->input('payment', array('type' => 'select','empty'=>'All','options'=>$payment_types,'label' => 'By Payment', 'class' => 'form-control')); ?>
                                </div>
                                <div class="col-md-1">
                                    <?php echo $this->Form->input('uhid', array('type' => 'text','placeholder'=>'','label' => 'By UHID', 'class' => 'form-control')); ?>
                                </div>
                                <div class="col-md-2">
                                    <?php echo $this->Form->input('name', array('type' => 'text','placeholder'=>'','label' => 'Search By Name', 'class' => 'form-control')); ?>
                                </div>


                                <div class="col-sm-9" >
                                    <div class="input text">
                                        <label style="display: block;">&nbsp;</label>
                                        <button type="submit" class="btn btn-info"><i class="fa fa-search"></i> Search</button>
                                        <a class="btn btn-warning" href="<?php echo $this->Html->url(array('controller'=>'app_admin','action'=>'get_commision_report')) ?>"><i class="fa fa-refresh"></i> Reset</a>
                                    </div>

                                </div>

                            </div>
                            <?php echo $this->Form->end(); ?>
                        </div>

                        <div class="Social-login-box payment_bx">



                            <div class="form-group row">
                                <div class="col-sm-12">


                                    <div class="table table-responsive">
                                        <h3 class="heading_lable">
                                            <span style="float: left;margin: 6px;5px;">Receipts of Billing</span>

                                            <!--span class="blink_text">Choose Table Column <i class="fa fa-arrow-right"></i> </span-->
                                            <select id="multi-select-demo" multiple="multiple" style="display: none;"></select>
                                        </h3>
                                        <table id="example1" class="table table-striped table-bordered" style="width:100%">
                                            <thead>
                                            <tr>
                                                <th class="show_column">#</th>
                                                <th class="show_column">UHID</th>
                                                <th class="show_column">Patient Name</th>
                                                <th class="show_column">Patient Mobile</th>
                                                <th class="show_column">Patient Address</th>
                                                <th class="show_column">Receipt No.</th>
                                                <th class="show_column">Date</th>
                                                <th class="show_column">Doctor</th>
                                                <th class="show_column">Commission(%)</th>
                                                <th class="show_column">Commission Amount</th>

                                                <th class="show_column">Payment Via</th>
                                                <th class="show_column">Description</th>
                                                <th class="show_column">Details</th>
                                                <th class="show_column">Receipt Type</th>
                                                <th class="show_column">Reason</th>
                                                <th class="show_column">Remark</th>
                                                <th class="show_column">Payment Status</th>
                                                <th class="show_column">Total Amount</th>
                                                <th class="show_column">Total Tax</th>
                                                <th class="show_column">Biller</th>
                                                <th class="show_column">Option</th>
                                            </tr>
                                            </thead>

                                            <tbody>
                                            <?php $totalAmount = 0; $totalTax = 0; $totalCommission = 0; ?>

                                            <?php $counter = 1; foreach($orderDetails AS $item){ ?>

                                                <tr>
                                                    <td><?php echo $counter; ?>.</td>
                                                    <td><?php echo $item['uhid']; ?></td>
                                                    <td><?php echo $item['patient_name']; ?></td>
                                                    <td><?php echo $item['patient_mobile']; ?></td>
                                                    <td><?php echo $item['patient_address']; ?></td>
                                                    <td><?php echo $item['unique_id']; ?></td>

                                                    <td><?php echo $item['date']; ?></td>
                                                    <td><?php $department = !empty($item['doctor_department'])?"<br>(".$item['doctor_department'].")":''; echo $item['doctor_name'].$department; ?></td>
                                                    <td><?php echo $item['commission_rate']; ?></td>
                                                    <td><?php $totalCommission = $totalCommission+$item['commission_amount']; echo round($item['commission_amount'],2); ?></td>

                                                    <td><?php echo $item['payment_type_name']; ?></td>
                                                    <td><?php echo $item['payment_description']; ?></td>
                                                    <td><?php


                                                        $total_rebate = $item['payable_amount'] - $item['total_amount'];
                                                        if($total_rebate > 0 && $item['is_settlement']=='Y' && $item['settlement_payment_status'] =='RECEIVED'){
                                                            echo "Rebate Amount ".($total_rebate);
                                                        }else{


                                                            echo $item['service_name'];

                                                            if(!empty($item['appointment_customer_staff_service_id']) && !empty($item['refund_amount']) && $item['refund_amount'] != 0){
                                                                if(!empty($item['service_name'])){
                                                                    echo "<br>";
                                                                }
                                                                echo "( Refund ".$item['refund_amount']." Rs/- ";
                                                                if(!empty($item['refund_reason'])){
                                                                    echo ", ".mb_strimwidth($item['refund_reason'], 0, 30, '...');
                                                                }
                                                                echo " )";

                                                            }

                                                        }



                                                        ?></td>

                                                    <td>
                                                        <?php



                                                                $type = "";


                                                                if($item['settlement_payment_status']=="RECEIVED" && $item['is_settlement'] =='Y'){
                                                                    $type = "Deposit";
                                                                }else if($item['settlement_payment_status']=="REFUND" && $item['is_settlement'] =='Y'){
                                                                    $type = 'Refund';
                                                                }
                                                                if($item['is_opd']=='Y'){
                                                                    echo "OPD";
                                                                }else if($item['is_advance']=='Y'){

                                                                    if($item['advance_status']=='ACTIVE'){
                                                                        echo "IPD Advance Deposit";
                                                                    }else{
                                                                        echo "IPD Advance Refund";
                                                                    }
                                                                }
                                                                else if($item['is_expense']=='Y'){
                                                                    echo "IPD Expense";
                                                                }else if($item['is_settlement']=='Y'){
                                                                    echo "IPD Settlement";

                                                                }else if($item['is_package']=='Y'){
                                                                    echo "Direct Billing (Package)";
                                                                }else{
                                                                    if($item['patient_due_amount_id'] > 0 ){
                                                                        echo "Due Amount";
                                                                    }else{
                                                                        if($item['lab_pharmacy_user_id'] > 0 ){
                                                                            echo "Direct Billing (".$item['lab_pharmacy_type'].")";
                                                                        }else{
                                                                            echo "Direct Billing";
                                                                        }
                                                                    }

                                                                }
                                                                echo ' '.$type;



                                                        ?>
                                                    </td>

                                                    <td><?php echo $item['reason_of_appointment']; ?></td>
                                                    <td><?php echo $item['notes']; ?></td>
                                                    <td><?php


                                                        if($item['settlement_payment_status']=="RECEIVED" && $item['is_settlement'] =='Y'){
                                                            echo 'Paid';
                                                        }else if($item['settlement_payment_status']=="REFUND"  && $item['is_settlement'] =='Y'){
                                                            echo  'Refund';
                                                        }else if($item['is_advance']=='Y'){
                                                            if($item['advance_status']=='ACTIVE'){
                                                                echo 'Paid';
                                                            }else{
                                                                echo  'Refund';
                                                            }
                                                        }else{
                                                            echo ucfirst(strtolower($item['payment_status']));
                                                        }





                                                    ?></td>
                                                    <td><i class="fa fa-inr" aria-hidden="true"></i> <?php

                                                        if($item['advance_status'] == 'INACTIVE' || $item['settlement_payment_status'] =='REFUND' && $item['is_settlement'] =='Y'){
                                                            $totalAmount -= sprintf("%.2f",$item['total_amount']);
                                                            $totalTax -= sprintf("%.2f",$item['total_tax']);
                                                        }else{

                                                            $totalAmount += sprintf("%.2f",$item['total_paid']);
                                                            $totalTax += sprintf("%.2f",$item['total_tax']);
                                                        }


                                                        echo sprintf("%.2f",$item['total_paid']);


                                                    ?>
                                                    </td>
                                                    <td><i class="fa fa-inr" aria-hidden="true"></i> <?php echo sprintf("%.2f",$item['total_tax']); ?></td>
                                                    <td><?php echo $item['biller']; ?></td>
                                                    <td>
                                                        <?php $module = ($item['patient_due_amount_id'] > 0)?'DUE':'IPD';; ?>
                                                        <?php if($item['is_opd'] == 'Y'){ ?>
                                                            <a class="btn btn-success btn-xs" href="<?php echo Router::url('/').'app_admin/print_invoice/'.base64_encode($item['id']); ?>" target="_blank">Receipt</a>
                                                        <?php if($login1['USER_ROLE'] !='RECEPTIONIST' && $login1['USER_ROLE'] !='DOCTOR' && $login1['USER_ROLE'] !='STAFF'){ ?>
                                                            <button type="button" class="btn btn-primary btn-xs editBtn" data-id="<?php echo base64_encode($item['id']); ?>">Edit</button>
                                                            <?php } ?>
                                                        <?php }else{
                                                            if($item['is_advance'] == 'Y'){ ?>
                                                                <a class="btn btn-success btn-xs" href="<?php echo Router::url('/').'app_admin/print_invoice/'.base64_encode($item['id'])."/IAD"; ?>" target="_blank">Receipt</a>
                                                            <?php }else if($item['is_settlement'] == 'Y'){ ?>
                                                                <a class="btn btn-success btn-xs" href="<?php echo Router::url('/').'app_admin/print_invoice_non_opd_settlement/'.base64_encode($item['id']); ?>" target="_blank">Receipt</a>

                                                            <?php }else{   ?>


                                                                    <a class="btn btn-success btn-xs" href="<?php echo Router::url('/').'app_admin/print_invoice/'.base64_encode($item['id'])."/$module"; ?>" target="_blank">Receipt</a>


                                                                    <?php if( $module != "DUE" && $login1['USER_ROLE'] !='RECEPTIONIST' && $login1['USER_ROLE'] !='DOCTOR' && $login1['USER_ROLE'] !='STAFF'  && $item['is_package'] != 'Y'){ ?>
                                                                    <button type="button" class="btn btn-primary btn-xs editBtn" data-id="<?php echo base64_encode($item['id']); ?>">Edit</button>
                                                                    <?php } ?>

                                                            <?php }
                                                        } ?>

                                                        <?php if($module != "DUE" && in_array($login1['USER_ROLE'],array('ADMIN','LAB','PHARMACY')) && $item['is_package'] != 'Y'){ ?>
                                                            <button type="button" class="btn btn-danger btn-xs delete_btn" data-id="<?php echo base64_encode($item['id']); ?>">Delete</button>
                                                        <?php } ?>

                                                    </td>
                                                </tr>
                                                <?php $counter++; } ?>


                                            </tbody>

                                            <tfoot>
                                            <tr>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th><?php echo round($totalCommission,2); ?></th>


                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th>Total</th>
                                                <th><i class="fa fa-inr" aria-hidden="true"></i> <?php echo $totalAmount; ?></th>
                                                <th><i class="fa fa-inr" aria-hidden="true"></i> <?php echo $totalTax; ?></th>
                                                <th></th>
                                                <th></th>
                                            </tr>
                                            </tfoot>
                                        </table>
                                    </div>


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
<div class="modal fade" id="myModalForm" role="dialog"></div>
<div class="modal fade" id="myModalFormSearch" role="dialog"></div>

<script>
    $(document).ready(function () {

        var report_head_length = $("#example1 thead:first tr th").length;

        var options = [];

        var columns = [];

        <?php echo !empty($table_columns)?"var setting = [".$table_columns."];":"var setting = false;"; ?>

        if(setting){
            setting = JSON.parse(setting);
        }




        /* $("#example1 thead:first tr th").each(function (index,value) {
            var visible;
            if(index < report_head_length ){
                if(setting){
                    console.log(setting);

                    var key1 = value.innerHTML;
                    console.log(setting[key1]);
                    if(setting[key1].visibility == "true"){
                        visible = true;
                    }else{
                        visible = false;
                    }
                }else{
                    if($(this).hasClass('show_column')){
                        visible = true;
                    }else{
                        visible = false;
                    }
                }
                var col = {
                    "targets": [ index ],
                    "visible": visible,
                    "searchable": true
                };
                var obj = {label: $(this).text(), title: $(this).text(), value: index, selected: visible};
                options.push(obj);
                columns.push(col);
            }
        }); */

        var table;

        function create_report_table(){
            var report_head_length = $("#example1 thead .show_column").length;
            table = $('#example1').DataTable({
                dom: 'Blfrtip',
                lengthMenu: [
                    [ 10, 25, 50, 100, 150, 200, -1 ],
                    [ '10 rows', '25 rows', '50 rows', '100 rows', '150 rows', '200 rows', 'Show all' ]
                ],
                "columnDefs": columns,
                "language": {
                    "emptyTable": "No Data Found"
                },
                buttons: [
                    {
                        extend: 'copy',
                        header: true,
                        footer: true,
                        messageTop: '<?php echo $reportTitle; ?>',
                        title: '',
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'csv',
                        header: true,
                        footer: true,
                        messageTop: '<?php echo $reportTitle; ?>',
                        title: '',
                        exportOptions: {
                            columns:  [':visible :not(:last-child)']
                        }
                    },
                    {
                        extend: 'excel',
                        header: true,
                        footer: true,
                        messageTop: '<?php echo $reportTitle; ?>',
                        title: '',
                        exportOptions: {
                            columns: ':visible'
                        }


                    },
                    {
                        extend: 'pdf',
                        header: true,
                        footer: true,
                        messageTop: '<?php echo $reportTitle; ?>',
                        title: '',
                        exportOptions: {
                            columns: ':visible'
                        }


                    },
                    {
                        extend: 'print',
                        header: true,
                        footer: true,
                        messageTop: '<?php echo $reportTitle; ?>',
                        title: '',
                        exportOptions: {
                            columns: ':visible'
                        }


                    }
                ]
            });
        }

        create_report_table();

        /*$('#multi-select-demo').multiselect({
            nonSelectedText: 'Select Fields',
            buttonWidth: 300,
            onChange: function(option, checked, select) {
                var column = table.column( option.val() );
                column.visible( ! column.visible() );
                update_table_columns();
            }
        });*/

        //$('#multi-select-demo').multiselect('dataprovider',options);

        //$('#multi-select-demo').show();

        function update_table_columns(){
            if(table){
                var save_column = new Object();
                table.columns().every( function (index) {
                    var visibility = this.visible();
                    save_column["column_"+index] = visibility;
                } );


                $.ajax({
                    type:'POST',
                    url: baseurl+"app_admin/save_billing_report_table_header",
                    data:{columns:save_column},
                    success:function(data){
                    },
                    error: function(data){}
                });
            }

        }

        $("head > title").text("Report_<?php echo date("d-M-Y"); ?>");

        $(".date_from, .date_to").datepicker({clearBtn:true,format: 'dd/mm/yyyy',autoclose:true,orientation: "bottom auto",endDate: new Date()});

        $(".date_from, .date_to").mask("99/99/9999", {placeholder: 'dd/mm/yyyy'});

        $(document).on('click','.editBtn',function(e){


            var $btn = $(this);
            var obj = $(this);
            var id = $(this).attr('data-id');
            $.ajax({
                type: 'POST',
                url: baseurl + "app_admin/getEditMedicalOrder",
                data: {id: id},
                beforeSend: function () {
                    $btn.button('loading').html('Wait..')
                },
                success: function (data) {
                    $btn.button('reset');
                    var html = $(data).filter('#editPaymentModal');
                    $(html).modal('show');

                },
                error: function (data) {
                    $btn.button('reset');
                    alert("Sorry something went wrong on server.");
                }
            });

        });

        $(document).on('click','.delete_btn',function(e){
            var del_obj = $(this);
            var jc = $.confirm({
                title: 'Delete Receipt',
                content: 'Are you sure you want to delete this receipt?',
                type: 'red',
                buttons: {
                    ok: {
                        text: "Yes",
                        btnClass: 'btn-primary',
                        keys: ['enter'],
                        name:"ok",
                        action: function(e){
                            var btn = $(this);
                            var rec_id =  $(del_obj).attr("data-id");
                            $.ajax({
                                url:baseurl+"/app_admin/delete_receipt",
                                type:'POST',
                                data:{
                                    ri:rec_id
                                },
                                beforeSend:function(){
                                    jc.buttons.ok.setText("Wait..");
                                },
                                success:function(res){
                                    var response = JSON.parse(res);
                                    jc.buttons.ok.setText("Yes");
                                    if(response.status==1){
                                        jc.close();
                                        window.location.reload();
                                    }else{

                                        $.alert(response.message);
                                    }

                                },
                                error:function () {
                                    jc.buttons.ok.setText("Yes");
                                    $.alert(response.message);
                                }
                            });
                            return false;
                        }
                    },
                    cancel: function(){
                        console.log('the user clicked cancel');
                    }
                }
            });
        });

    });
</script>
