<?php
$login = $this->Session->read('Auth.User.User');
$login1 = $this->Session->read('Auth.User');


?>
<?php  echo $this->Html->css(array('dataTableBundle.css')); ?>
<?php  echo $this->Html->script(array('dataTableBundle.js','jquery.maskedinput-1.2.2-co.min.js','comman.js')); ?>


<style>

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

                    <?php if($report_type=='address'){ ?>
                        <h3 class="screen_title">Product Report</h3>
                    <?php }else if($report_type=='refer'){ ?>
                        <h3 class="screen_title">Refer Report</h3>
                    <?php }else if($report_type=='due_amount'){ ?>
                        <h3 class="screen_title">Due Amount Report</h3>
                    <?php }else{ ?>
                        <h3 class="screen_title">Date Wise Report</h3>
                    <?php } ?>

                    <?php if($report_type=='date'){ echo $this->element('billing_inner_header'); ?>
                    <div class="col-lg-12 right_box_div">

                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 search_row">
                            <?php echo $this->Form->create('Search',array('type'=>'POST','url'=>array('controller'=>'app_admin','action' => 'search_get_billing_stats'),'admin'=>true)); ?>
                            <div class="form-group">

                                <div class="col-md-2" >
                                    <?php echo $this->Form->input('from_date', array('type'=>'text','placeholder' => 'Date From', 'label' => 'Date From', 'class' => 'form-control date_from')); ?>
                                </div>
                                <div class="col-md-2" >
                                    <?php echo $this->Form->input('to_date', array('type'=>'text','placeholder' => 'Date To', 'label' => 'Date To', 'class' => 'form-control date_to')); ?>
                                </div>


                                <?php if ( $login1['USER_ROLE'] =="ADMIN") { ?>
                                    <div class="col-md-2">
                                        <?php echo $this->Form->input('module', array('type' => 'select','empty'=>'All','options'=>array('OPD'=>'OPD','IPD'=>'IPD','LAB'=>'LAB','PHARMACY'=>'PHARMACY','OTHER'=>'Direct Billing'),'label' => 'By Category', 'class' => 'form-control')); ?>
                                    </div>
                                <?php } ?>

                                <div class="col-md-2">
                                    <?php echo $this->Form->input('category', array('type' => 'select','label' => 'Search by Service Category', "empty"=>"All", "options"=>$this->AppAdmin->billing_report_category_list($login['thinapp_id']), 'class' => 'form-control')); ?>
                                </div>

                                <div class="col-md-2">
                                    <?php echo $this->Form->input('service', array('type' => 'select','empty'=>'All','options'=>$this->AppAdmin->billing_report_service_list($login['thinapp_id']),'label' => 'Search By Product', 'class' => 'form-control')); ?>
                                </div>



                                <div class="col-md-2">
                                    <?php echo $this->Form->input('type', array('type' => 'select','options'=>array('DATE'=>'Date','MONTH'=>'Month','YEAR'=>'Year'),'label' => 'Search Date Type', 'class' => 'form-control')); ?>
                                </div>
								
                                <?php if ( $login1['USER_ROLE'] != 'LAB' && $login1['USER_ROLE'] != 'PHARMACY') { ?>
                                    <div class="col-md-2">
                                        <?php echo $this->Form->input('doctor', array('type' => 'select','empty'=>'All','options'=>$this->AppAdmin->getHospitalDoctorList($login['thinapp_id']),'label' => 'Search By Doctor', 'class' => 'form-control')); ?>
                                    </div>
                                <?php } ?>



                                <div class="col-sm-3 action_btn" style="width: 19%; padding: 4px 5px;" >
                                    <div class="input text">
                                        <label style="display: block;">&nbsp;</label>
                                        <button type="button" onclick="window.history.back();" class="btn btn-info">Back</button>
                                        <button type="submit" class="btn btn-success">Search</button>
                                        <a class="btn btn-warning" href="<?php echo $this->Html->url(array('controller'=>'app_admin','action'=>'get_billing_stats')) ?>">Reset</a>
                                    </div>

                                </div>

                            </div>
                            <?php echo $this->Form->end(); ?>
                        </div>

                        <div class="Social-login-box payment_bx">

                            <div class="form-group row">
                                <div class="col-sm-12">


                                    <div class="table table-responsive">
                                        <table id="data_table" class="table table-striped table-bordered" style="width:100%">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Date</th>
                                                <th>Amount</th>
                                                <?php if(!empty($payment_type_name)){ foreach ($payment_type_name as $type){ echo "<th>".$type."</th>"; } }?>


                                            </tr>
                                            </thead>
                                            <tbody>

                                            <?php $gTotal = 0; foreach($list AS $key => $total){ ?>
                                                <tr>
                                                    <td><b><?php echo $key+1; ?>.</b></td>
                                                    <td>
                                                        <b><?php echo $total['title']; ?></b>
                                                    </td>
                                                    <td><b><i class="fa fa-inr" aria-hidden="true"></i> <?php echo $total['total']; $gTotal = $gTotal+$total['total']; ?></b></td>
                                                    <?php if(!empty($payment_type_name)){ foreach ($payment_type_name as $type){ ?>
                                                        <td>
                                                            <?php
                                                                if(array_key_exists($type,$total['sub_amount'])){
                                                                    echo $total['sub_amount'][$type]['amount'];
                                                                }
                                                            ?>
                                                        </td>

                                                    <?php } }?>

                                                </tr>
                                            <?php } ?>

                                            <tfoot>
                                            <tr>
                                                <th></th>
                                                <th>Total</th>
                                                <th><i class="fa fa-inr" aria-hidden="true"></i> <?php echo $gTotal; ?></th>
                                                <?php if(!empty($payment_type_name)){ foreach ($payment_type_name as $type){ echo "<th></th>"; } }?>


                                            </tr>
                                            </tfoot>
                                            </tbody>
                                        </table>

                                    </div>


                                </div>
                            </div>
                            <div class="clear"></div>

                        </div>



                    </div>
                    <?php }else if($report_type=='address'){ echo $this->element('billing_inner_header'); ?>

                        <div class="col-lg-12">

                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 search_row">
                                <?php echo $this->Form->create('Search',array('type'=>'POST','url'=>array('controller'=>'app_admin','action' => 'search_get_billing_stats'),'admin'=>true)); ?>
                                <div class="form-group">

                                    <div class="col-md-1" >
                                        <?php echo $this->Form->input('from_date', array('type'=>'text','placeholder' => 'Date From', 'label' => 'Date From', 'class' => 'form-control date_from')); ?>
                                    </div>
                                    <div class="col-md-1" >
                                        <?php echo $this->Form->input('to_date', array('type'=>'text','placeholder' => 'Date To', 'label' => 'Date To', 'class' => 'form-control date_to')); ?>
                                    </div>
                                    <div class="col-md-2">
                                        <?php echo $this->Form->input('product', array('type' => 'select','empty'=>'All','options'=>$this->AppAdmin->billing_report_service_list($login['thinapp_id']),'label' => 'Select Product', 'class' => 'form-control')); ?>
                                    </div>
                                    <div class="col-md-2">
                                        <?php echo $this->Form->input('address', array('type' => 'select','empty'=>'All','options'=>$this->AppAdmin->get_app_address($login['thinapp_id']),'label' => 'Select Address', 'class' => 'form-control')); ?>
                                        <?php echo $this->Form->input('rt', array('type' => 'hidden','value'=>@$this->request->query['rt'])); ?>
                                    </div>
                                    <div class="col-md-2">
                                        <?php echo $this->Form->input('doctor', array('type' => 'select','empty'=>'All','options'=>$this->AppAdmin->getHospitalDoctorList($login['thinapp_id']),'label' => 'Search By Doctor', 'class' => 'form-control')); ?>
                                    </div>

                                    <?php if ( $login1['USER_ROLE'] =="ADMIN") { ?>
                                    <div class="col-md-2">
                                        <?php echo $this->Form->input('biller', array('type' => 'select','empty'=>'All','options'=>$this->AppAdmin->billing_report_biller_list($login['thinapp_id']),'label' => 'Search By Biller', 'class' => 'form-control')); ?>
                                    </div>
                                    <?php } ?>


                                    <div class="col-sm-3 action_btn" >
                                        <div class="input text">
                                            <label style="display: block;">&nbsp;</label>
                                            <button type="button" onclick="window.history.back();" class="btn btn-info">Back</button>

                                            <button type="submit" class="btn btn-success">Search</button>
                                            <a class="btn btn-warning" href="<?php echo $this->Html->url(array('controller'=>'app_admin','action'=>'get_billing_stats')) ?>">Reset</a>
                                        </div>
                                    </div>

                                </div>
                                <?php echo $this->Form->end(); ?>
                            </div>

                            <div class="Social-login-box payment_bx">

                                <div class="form-group row">
                                    <div class="col-sm-12">


                                        <div class="table table-responsive">
                                            <table id="data_table" class="table table-striped table-bordered" style="width:100%">
                                                <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Product Name</th>
                                                    <th>Amount</th>
                                                </tr>

                                                </thead>

                                                <tbody>

                                                <?php $gTotal = 0; $address_count =1;  foreach($list AS $address => $list_data){ ?>


                                                    <tr>
                                                        <th style="-webkit-print-color-adjust: exact;background-color: #6b7782 !important; color: #fff;"><?php echo $address_count++; ?></th>
                                                        <th  style="-webkit-print-color-adjust: exact; background-color: #6b7782 !important; color: #fff; text-align: center;" ><?php echo !empty($address)?$address:'No address for the following products'; ?></th>
                                                        <th style="-webkit-print-color-adjust: exact; background-color: #6b7782 !important; color: #fff;"></th>
                                                    </tr>


                                                    <?php  foreach($list_data AS $key => $inner){ ?>


                                                    <tr>
                                                        <td><b><?php echo $key+1; ?>.</b></td>
                                                        <td>
                                                            <b><?php echo $inner['product_name']; ?></b>
                                                        </td>
                                                        <td><b><i class="fa fa-inr" aria-hidden="true"></i> <?php echo $inner['total']; $gTotal = $gTotal+$inner['total']; ?></b></td>
                                                    </tr>


                                                <?php } ?>


                                                <?php } ?>


                                                    </tbody>
                                                <tfoot>

                                                <tr>
                                                    <th  style="background-color: #6b7782; color: #fff;"></th>
                                                    <th  style="background-color: #6b7782; color: #fff;">Total</th>
                                                    <th  style="background-color: #6b7782; color: #fff;"><i class="fa fa-inr" aria-hidden="true"></i> <?php echo $gTotal; ?></th>

                                                </tr>
                                                </tfoot>

                                            </table>

                                        </div>


                                    </div>
                                </div>
                                <div class="clear"></div>

                            </div>



                        </div>

                    <?php } else if($report_type=='refer'){ echo $this->element('billing_inner_header'); ?>

                        <div class="col-lg-12">

                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 search_row">
                                <?php echo $this->Form->create('Search',array('type'=>'POST','url'=>array('controller'=>'app_admin','action' => 'search_get_billing_stats'),'admin'=>true)); ?>
                                <div class="form-group">

                                    <div class="col-md-1">
                                        <?php echo $this->Form->input('module', array('type' => 'select','empty'=>'All','options'=>array('OPD'=>'OPD','IPD'=>'IPD'),'label' => 'By Category', 'class' => 'form-control')); ?>
                                    </div>

                                    <div class="col-md-1" >
                                        <?php echo $this->Form->input('from_date', array('type'=>'text','placeholder' => 'Date From', 'label' => 'Date From', 'class' => 'form-control date_from')); ?>
                                    </div>
                                    <div class="col-md-1" >
                                        <?php echo $this->Form->input('to_date', array('type'=>'text','placeholder' => 'Date To', 'label' => 'Date To', 'class' => 'form-control date_to')); ?>
                                    </div>
                                    <div class="col-md-2">
                                        <?php echo $this->Form->input('address', array('type' => 'select','empty'=>'All','options'=>$this->AppAdmin->get_app_address($login['thinapp_id']),'label' => 'Select Address', 'class' => 'form-control')); ?>
                                        <?php echo $this->Form->input('rt', array('type' => 'hidden','value'=>@$this->request->query['rt'])); ?>
                                    </div>
                                    <div class="col-md-1" >
                                        <?php echo $this->Form->input('uhid', array('type'=>'text','placeholder' => '', 'label' => 'Search By UHID', 'class' => 'form-control')); ?>
                                    </div>
                                    <div class="col-md-2" >
                                        <?php echo $this->Form->input('name', array('type'=>'text','placeholder' => '', 'label' => 'Search By Patient Name', 'class' => 'form-control')); ?>
                                    </div>

                                    <div class="col-md-2" >
                                        <?php echo $this->Form->input('refer', array('type'=>'text','placeholder' => '', 'label' => 'Search By Refer', 'class' => 'form-control')); ?>
                                    </div>



                                    <div class="col-sm-2 action_btn" style="width: 24% !important;" >
                                        <div class="input text">
                                            <label style="display: block;">&nbsp;</label>
                                            <button type="button" onclick="window.history.back();" class="btn btn-info">Back</button>

                                            <button type="submit" class="btn btn-success">Search</button>
                                            <a class="btn btn-warning" href="<?php echo $this->Html->url(array('controller'=>'app_admin','action'=>'get_billing_stats?rt=refer')) ?>">Reset</a>
                                        </div>
                                    </div>

                                </div>
                                <?php echo $this->Form->end(); ?>
                            </div>

                            <div class="Social-login-box payment_bx">

                                <div class="form-group row">
                                    <div class="col-sm-12">


                                        <div class="table table-responsive">
                                            <table id="data_table" class="table table-striped table-bordered" style="width:100%">
                                                <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Patient UHID</th>
                                                    <th>Patient Name</th>
                                                    <th>Patient Mobile</th>
                                                    <th>Refer by Name</th>
                                                    <th>Refer by Mobile</th>
                                                    <th>Date</th>

                                                </tr>

                                                </thead>

                                                <tbody>

                                                <?php if(!empty($list)){ foreach($list AS $key => $data){ ?>


                                                    <tr>
                                                        <td><b><?php echo $key+1; ?>.</b></td>
                                                        <td><b><?php echo $data['uhid']; ?></b></td>
                                                        <td><b><?php echo $data['patient_name']; ?></b></td>
                                                        <td><b><?php echo $data['patient_mobile']; ?></b></td>
                                                        <td><b><?php echo $data['referred_by']; ?></b></td>
                                                        <td><b><?php echo $data['referred_by_mobile']; ?></b></td>
                                                        <td><b><?php echo date('d-m-Y',strtotime($data['created'])); ?></b></td>
                                                    </tr>


                                                <?php }} ?>


                                                </tbody>


                                            </table>

                                        </div>


                                    </div>
                                </div>
                                <div class="clear"></div>

                            </div>



                        </div>

                    <?php } else if($report_type=='due_amount'){ echo $this->element('billing_inner_header'); ?>

                        <div class="col-lg-12">

                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 search_row">
                                <?php echo $this->Form->create('Search',array('type'=>'POST','url'=>array('controller'=>'app_admin','action' => 'search_get_billing_stats'),'admin'=>true)); ?>
                                <div class="form-group">

                                    <div class="col-md-2" >
                                        <?php echo $this->Form->input('uhid', array('type'=>'text','placeholder' => '', 'label' => 'Search By UHID', 'class' => 'form-control')); ?>
                                    </div>
                                    <div class="col-md-2" >
                                        <?php echo $this->Form->input('name', array('type'=>'text','placeholder' => '', 'label' => 'Search By Patient Name', 'class' => 'form-control')); ?>
                                    </div>


                                    <div class="col-md-2" >
                                        <?php echo $this->Form->input('from_date', array('type'=>'text','placeholder' => 'Date From', 'label' => 'Date From', 'class' => 'form-control date_from')); ?>
                                    </div>
                                    <div class="col-md-2" >
                                        <?php echo $this->Form->input('to_date', array('type'=>'text','placeholder' => 'Date To', 'label' => 'Date To', 'class' => 'form-control date_to')); ?>
                                    </div>
                                    <div class="col-md-2">
                                        <?php echo $this->Form->input('payment_status', array('type' => 'select','empty'=>'All','options'=>array('DUE'=>'Due','PAID'=>'Paid'),'label' => 'Payment Status', 'class' => 'form-control')); ?>
                                        <?php echo $this->Form->input('rt', array('type' => 'hidden','value'=>@$this->request->query['rt'])); ?>
                                    </div>




                                    <div class="col-sm-2 action_btn" style="width: 24% !important;" >
                                        <div class="input text">
                                            <label style="display: block;">&nbsp;</label>
                                            <button type="button" onclick="window.history.back();" class="btn btn-info">Back</button>

                                            <button type="submit" class="btn btn-success">Search</button>
                                            <a class="btn btn-warning" href="<?php echo $this->Html->url(array('controller'=>'app_admin','action'=>'get_billing_stats?rt=due_amount')) ?>">Reset</a>
                                        </div>
                                    </div>

                                </div>
                                <?php echo $this->Form->end(); ?>
                            </div>

                            <div class="Social-login-box payment_bx">

                                <div class="form-group row">
                                    <div class="col-sm-12">


                                        <div class="table table-responsive">
                                            <table id="data_table" class="table table-striped table-bordered" style="width:100%">
                                                <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Patient UHID</th>
                                                    <th>Patient Name</th>
                                                    <th>Patient Mobile</th>
                                                    <th>Amount</th>
                                                    <th>Status</th>
                                                    <th>Date</th>
                                                    <th class="action_btn">Action</th>

                                                </tr>

                                                </thead>

                                                <tbody>

                                                <?php if(!empty($list)){ foreach($list AS $key => $data){ ?>


                                                    <tr>
                                                        <td><b><?php echo $key+1; ?>.</b></td>
                                                        <td><b><?php echo $data['uhid']; ?></b></td>
                                                        <td><b><?php echo $data['patient_name']; ?></b></td>
                                                        <td><b><?php echo $data['patient_mobile']; ?></b></td>
                                                        <td><b><?php echo $data['amount']; ?></b></td>
                                                        <td><b class="status_box"><?php echo $data['payment_status']; ?></b></td>
                                                        <td><b><?php echo date('d-m-Y',strtotime($data['created'])); ?></b></td>
                                                        <td>
                                                            <?php if($data['payment_status']=="DUE"){ ?>
                                                                  <a href="javascript:void(0);" class="btn btn-info btn-xs pay_due_amount_btn" data-id="<?php echo base64_encode($data['id']); ?>" ><i class="fa fa-inr"></i> Pay</a>
                                                            <?php }else{ if(!empty($data['order_id'])){ ?>
                                                                <a href="javascript:void(0);" class="btn btn-info btn-xs undo_btn" data-id="<?php echo base64_encode($data['id']); ?>" ><i class="fa fa-arrow-left"></i> Revert Payment</a>
                                                                <a href="<?php echo Router::url('/app_admin/print_invoice/',true).base64_encode($data['order_id']).'/DUE'?>" target="_blank" class="btn btn-success btn-xs" ><i class="fa fa-file-o"></i> Receipt</a>
                                                            <?php }} ?>
                                                        </td>
                                                    </tr>


                                                <?php }} ?>


                                                </tbody>


                                            </table>

                                        </div>


                                    </div>
                                </div>
                                <div class="clear"></div>

                            </div>



                        </div>

                    <?php } ?>


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


        var columns = [];
        $("#data_table thead:first tr th").each(function (index,value) {
            if(!$(this).hasClass("action_btn")){
                columns.push(index);
            }
        });

        $('#data_table').DataTable({
            dom: 'Blfrtip',
            lengthMenu: [
                [ 10, 25, 50, 100, 150, 200, -1 ],
                [ '10 rows', '25 rows', '50 rows', '100 rows', '150 rows', '200 rows', 'Show all' ]
            ],
            "aaSorting": [],
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
                        columns: columns
                    }

                },
                {
                    extend: 'csv',
                    header: true,
                    footer: true,
                    messageTop: '<?php echo $reportTitle; ?>',
                    title: '',
                    exportOptions: {
                        columns: columns
                    }

                },
                {
                    extend: 'excel',
                    header: true,
                    footer: true,
                    messageTop: '<?php echo $reportTitle; ?>',
                    title: '',
                    exportOptions: {
                        columns: columns
                    }

                },
                {
                    extend: 'pdf',
                    header: true,
                    footer: true,
                    messageTop: '<?php echo $reportTitle; ?>',
                    title: '',
                    exportOptions: {
                        columns: columns
                    }

                },
                {
                    extend: 'print',
                    header: true,
                    footer: true,
                    messageTop: '<?php echo $reportTitle; ?>',
                    title: '',
                    exportOptions: {
                        columns: columns
                    }

                }
            ]
        });

        var date = new Date();
        var last = (new Date(date.getTime() - (370 * 24 * 60 * 60 * 1000)));
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
