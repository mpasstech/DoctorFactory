<?php
$login = $this->Session->read('Auth.User.User');
$login1 = $this->Session->read('Auth.User');
//$thinappName = $login1['Thinapp']['name'];

$show_conceive_date = $this->AppAdmin->has_category($login['thinapp_id'],array(17,22,23));

$show_conceive_date = false;



$table_columns = $this->AppAdmin->get_billing_table_column($login['thinapp_id']);



$address_list =$this->AppAdmin->get_app_address($login['thinapp_id']);





$staff_data =array();
if(!empty($login1['AppointmentStaff']['id'])){
    $staff_data = $this->AppAdmin->get_doctor_by_id($login1['AppointmentStaff']['id'],$login1['User']['thinapp_id']);
}


$field_data = $this->AppAdmin->get_app_prescription_fields($login['thinapp_id'],true);

$active_fields =array();
if(!empty($field_data)){
    foreach($field_data as $key => $field){
        if($field['custom_field'] == "YES" && $field['form_status'] =="ACTIVE"){
            $active_fields[] =$field;
        }
    }
}



?>
<?php  echo $this->Html->css(array('dataTableBundle.css','magicsuggest-min.css','bootstrap-multiselect.css')); ?>
<?php  echo $this->Html->script(array('dataTableBundle.js','jquery.maskedinput-1.2.2-co.min.js','comman.js','magicsuggest-min.js','bootstrap-multiselect.js')); ?>




<style>

    #rob_table_wrapper{
        overflow-x: auto;
    }


    .option_btn_panel .btn{
        width: 49%;
        float: left;
        height: 22px;
        margin: 1px 1px;
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
    #rob_table_length {
        width: 32%;
        text-align: right;
    }
    #rob_table{
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

    .nav-tabs>li.active>a, .nav-tabs>li.active>a:focus, .nav-tabs>li.active>a:hover {
        color: #fff;
        background-color: #3baeff;
        border: 1px solid #3baeff;
        text-transform: uppercase;

    }
    .tab_menu_ul{
        border-top: 1px solid gray;
        margin-top: 13px;
        float: left;
        width: 100%;
        padding-top: 15px;
    }
    .label_hint{
        position: absolute;
        margin: 0 auto;
        top: -34px;
        width: 100%;
        font-size: 17px;
        text-align: center;

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
                    <h3 class="screen_title">Billing Reports</h3>
                    <?php
                    echo $this->element('billing_inner_header');
                    ?>
                    <div  class="col-lg-12 col-md-12 col-sm-12 col-xs-12 right_box_div">



                        <div class="Social-login-box payment_bx">

                            <div style="background: #d4d1d126;"  class="col-lg-12 col-md-12 col-sm-12 col-xs-12 search_row">
                                <?php echo $this->Form->create('Search',array('type'=>'POST','url'=>array('controller'=>'app_admin','action' => 'search_get_hospital_receipt_reports'),'admin'=>true)); ?>
                                <div class="form-group">

                                    <?php $label_hint = !empty($date_message)?$date_message:'You can search any two month records';  ?>
                                    <label class="label_hint" style="<?php echo !empty($date_message)?'color:red':''; ?>"><?php echo $label_hint; ?></label>

                                    <?php echo $this->Form->input('rf', array('value'=>$report_for,'autocomplete'=>'off', 'type'=>'hidden')); ?>

                                    <?php $col = 7; $offset = 1; $sm = 2; if( $login1['USER_ROLE'] !="ADMIN") {$col = 3; $offset=0; $sm=1; } ?>

                                    <?php if ( $login1['USER_ROLE'] != 'LAB' && $login1['USER_ROLE'] != 'PHARMACY') { ?>
                                        <div class="col-md-1">
                                            <?php echo $this->Form->input('receipt_category', array('type' => 'select','empty'=>'All','options'=>array('OPD'=>'OPD','IPD'=>'IPD','LAB'=>'LAB','PHARMACY'=>'PHARMACY','OTHER'=>'Direct Billing','PACKAGE'=>'Package','DUE_AMOUNT'=>'Due Amount'),'label' => 'By Category', 'class' => 'form-control')); ?>
                                        </div>
                                    <?php } ?>

                                    <div class="col-md-2">
                                        <?php echo $this->Form->input('category', array('type' => 'select','label' => 'Search by Service Category', "empty"=>"All", "options"=>$this->AppAdmin->billing_report_category_list($login['thinapp_id']), 'class' => 'form-control')); ?>
                                    </div>
                                    <div class="col-md-2">
                                        <?php echo $this->Form->input('address', array('type' => 'select','label' => 'Search by Address', "empty"=>"All", "options"=>$address_list, 'class' => 'form-control')); ?>
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





                                    <div class="col-md-1">
                                        <?php


                                        $payment_types = $payment_type_table_header = $this->AppAdmin->billing_report_payment_type_list($login['thinapp_id']);

                                        $payment_types['PAY_BY_USER']='Online payments by patients';

                                        ?>
                                        <?php echo $this->Form->input('payment', array('type' => 'select','empty'=>'All','options'=>$payment_types,'label' => 'By Payment', 'class' => 'form-control')); ?>
                                    </div>
                                    <?php $biller_list =  $this->AppAdmin->billing_report_biller_list($login['thinapp_id']); ?>
                                    <?php if ( $login1['USER_ROLE'] =="ADMIN") { ?>
                                        <div class="col-md-2">

                                            <?php echo $this->Form->input('biller', array('type' => 'select','empty'=>'All','options'=>$biller_list,'label' => 'Search By Biller', 'class' => 'form-control')); ?>
                                        </div>
                                    <?php } ?>

                                    <div class="col-md-1">
                                        <?php echo $this->Form->input('uhid', array('type' => 'text','placeholder'=>'','label' => 'By UHID', 'class' => 'form-control')); ?>
                                    </div>
                                    <div class="col-md-1">
                                        <?php echo $this->Form->input('name', array('type' => 'text','placeholder'=>'','label' => 'Search By Name', 'class' => 'form-control')); ?>
                                    </div>


                                    <div class="col-sm-8" style="padding: 0;" >
                                        <div class="input text">
                                            <label style="display: block;">&nbsp;</label>
                                            <button type="submit" class="btn btn-info"><i class="fa fa-search"></i> Search</button>
                                            <a class="btn btn-warning" href="<?php echo $this->Html->url(array('controller'=>'app_admin','action'=>'get_hospital_receipt_reports')) ?>"><i class="fa fa-refresh"></i> Reset</a>
                                            <?php if ( $login1['USER_ROLE'] == 'LAB' OR $login1['USER_ROLE'] == 'PHARMACY' OR $login1['USER_ROLE'] == 'ADMIN' OR $staff_data['allow_date_wise_report'] == "YES" ) { ?>
                                                <a class="btn btn-danger" href="<?php echo $this->Html->url(array('controller'=>'app_admin','action'=>'get_billing_stats')) ?>"><i class="fa fa-file"></i> Date Wise Reports</a>
                                            <?php } ?>
                                            <?php if ( $login1['USER_ROLE'] == 'LAB' OR $login1['USER_ROLE'] == 'PHARMACY' OR $login1['USER_ROLE'] == 'ADMIN' OR $staff_data['allow_product_wise_report'] == "YES" ) { ?>
                                                <a class="btn btn-success" href="<?php echo $this->Html->url(array('controller'=>'app_admin','action'=>'get_billing_stats?rt=address')) ?>"><i class="fa fa-file"></i> Product Reports</a>
                                            <?php } ?>

                                            <?php if ($login1['USER_ROLE'] == 'ADMIN' || $login1['USER_ROLE'] == 'RECEPTIONIST' ) { ?>
                                                <a class="btn btn-warning" href="<?php echo $this->Html->url(array('controller'=>'app_admin','action'=>'get_billing_stats?rt=refer')) ?>"><i class="fa fa-file"></i> Refer Reports</a>
                                                <a class="btn btn-info" href="<?php echo $this->Html->url(array('controller'=>'app_admin','action'=>'get_billing_stats?rt=due_amount')) ?>"><i class="fa fa-file"></i> Due Amount Reports</a>
                                            <?php }  ?>
                                        </div>

                                    </div>

                                </div>
                                <?php echo $this->Form->end(); ?>
                            </div>

                            <ul class="tab_menu_ul nav nav-tabs">
                                <?php
                                $query_string='';
                                $param_string = $this->request->query;
                                unset($param_string['url']);
                                unset($param_string['rf']);
                                if(!empty($param_string)){
                                    foreach($param_string as $key =>$value){
                                        $query_string[] = $key."=".$value;
                                    }
                                    $query_string = "&".implode("&",$query_string);
                                }

                                ?>
                                <li class="<?php echo ($report_for=='la')?'active':''; ?>"><a data-toggle="tab" data-href="#la" href="<?php echo Router::url('/app_admin/get_hospital_receipt_reports?rf=la'.$query_string,true)?>">Billing Summary By Login Account</a></li>
                                <li class="<?php echo ($report_for=='mw')?'active':''; ?>"><a data-toggle="tab" data-href="#mw" href="<?php echo Router::url('/app_admin/get_hospital_receipt_reports?rf=mw'.$query_string,true)?>">Billing Summary Module Wise</a></li>
                                <li class="<?php echo ($report_for=='scw')?'active':''; ?>"><a data-toggle="tab" data-href="#scw" href="<?php echo Router::url('/app_admin/get_hospital_receipt_reports?rf=scw'.$query_string,true)?>">Billing Summary Service Category Wise</a></li>
                                <li class="<?php echo ($report_for=='rob')?'active':''; ?>"><a data-toggle="tab" data-href="#rob" href="<?php echo Router::url('/app_admin/get_hospital_receipt_reports?rf=rob'.$query_string,true)?>">Receipts of Billing</a></li>
                                <li class="<?php echo ($report_for=='pt')?'active':''; ?>"><a data-toggle="tab" data-href="#pt" href="<?php echo Router::url('/app_admin/get_hospital_receipt_reports?rf=pt'.$query_string,true)?>">Payment Type</a></li>
                            </ul>

                            <div class="tab-content">
                                <div id="la" class="tab-pane fade <?php echo ($report_for=='la')?'in active':''; ?>">
                                    <?php  $visible_index=$refund_list =array(); if ( $login1['USER_ROLE'] != 'LAB' && $login1['USER_ROLE'] != 'PHARMACY') { ?>

                                        <div class="form-group row">
                                            <div class="col-sm-12">


                                                <div class="table table-responsive">
                                                    <h3 class="heading_lable">Billing Summary By Login Account</h3>

                                                    <?php

                                                    $account_array['header'][0] = "#";
                                                    $account_array['header'][1] = "Name";
                                                    $account_array['header'][2] = "Amount";
                                                    $counter=3;
                                                    foreach ($payment_type_table_header as $header_key => $header){
                                                        $account_array['header'][] = "Refund By ".ucfirst($header);
                                                        $visible_index[$counter++] =false;
                                                    }
                                                    $dyn_col = 3 + count($visible_index);
                                                    $account_array['header'][$dyn_col] = "Final Amount";
                                                    $visible_index[$dyn_col] =false;
                                                    $grand_final_total =0;
                                                    $deatialUserDataNewArray=array();
                                                    $biller_counter = 0;
                                                    foreach ($biller_list as $biller_id => $biller_name){
                                                        $biller_id = explode("#",$biller_id);
                                                        $biller_id = $biller_id[0];

                                                        $biller_name = explode("-",$biller_name);
                                                        $biller_name = $biller_name[0];


                                                        $tmp['username']= $biller_name;
                                                        $tmp['total']= 0;
                                                        $tmp['order_ids']= '';
                                                        $tmp['user_id']= $biller_id;
                                                        $deatialUserDataNewArray[$biller_counter] =$tmp;
                                                        foreach($detailUserData as $detail_key => $item){
                                                            if($item['user_id'] == $biller_id){
                                                                $deatialUserDataNewArray[$biller_counter]['total'] =$item['total'];
                                                                $deatialUserDataNewArray[$biller_counter]['order_ids'] =$item['order_ids'];
                                                                break;
                                                            }
                                                        }
                                                        $biller_counter++;
                                                    }


                                                    $order_ids = implode(",",array_column($orderDetails,'id'));
                                                    $row_counter = 0;
                                                    foreach($deatialUserDataNewArray as $detail_key => $item){
                                                        $detail_key = @count($account_array['detail']);

                                                        $account_array['detail'][$detail_key][0] = $row_counter+1;
                                                        $account_array['detail'][$detail_key][1] = $item['username'];
                                                        $account_array['detail'][$detail_key][2] = $item['total'];
                                                        $counter=3;
                                                        $total_refund_amount = 0;

                                                        $user_id = $item['user_id'];
                                                        $from_date = @$this->request->data['Search']['from_date'];
                                                        $to_date = @$this->request->data['Search']['to_date'];

                                                        if(!empty($user_id) && !empty($from_date) && !empty($to_date)){
                                                            $from_date = DateTime::createFromFormat('d/m/Y', $from_date);
                                                            $from_date= $from_date->format('Y-m-d');
                                                            $to_date = DateTime::createFromFormat('d/m/Y', $to_date);
                                                            $to_date= $to_date->format('Y-m-d');
                                                            $address_id = isset($this->request->data['Search']['address'])?$this->request->data['Search']['address']:0;
                                                            $refund_list = $this->AppAdmin->get_total_refund_by_payment_type($user_id,$from_date,$to_date,$address_id,$order_ids);
                                                        }
                                                        if(!empty($item['total']) || !empty($refund_list)) {
                                                            foreach ($payment_type_table_header as $header_key => $header) {
                                                                $header_key = ($header_key == -1) ? 0 : $header_key;
                                                                $payment_type = @$this->request->query['p'];
                                                                $payment_type = !empty($payment_type == -1) ? 0 : $payment_type;
                                                                if ((!empty($refund_list) && isset($refund_list[$header_key])) && (empty($payment_type) || $payment_type = $header_key)) {
                                                                    if ($refund_list[$header_key]['refund_amount'] != 0) {
                                                                        $account_array['detail'][$detail_key][] = $refund_list[$header_key]['refund_amount'];
                                                                        $total_refund_amount += $refund_list[$header_key]['refund_amount'];
                                                                        $visible_index[$counter] = true;
                                                                        $visible_index[$dyn_col] = true;
                                                                    } else {
                                                                        $account_array['detail'][$detail_key][] = 0;
                                                                    }
                                                                } else {
                                                                    $account_array['detail'][$detail_key][] = 0;
                                                                }
                                                                $counter++;
                                                            }

                                                            $total_refund_amount = empty($item['total']) ? 0 : $total_refund_amount;
                                                            $account_array['detail'][$detail_key][$dyn_col] = $item['total'] - $total_refund_amount;

                                                            $grand_final_total += $account_array['detail'][$detail_key][$dyn_col];
                                                            $row_counter++;
                                                        }
                                                    }



                                                    ?>



                                                    <table id="table_la" class="table table-striped table-bordered" style="display: none; width:100%">
                                                        <thead>
                                                        <tr>
                                                            <?php  foreach($account_array['header'] AS $index => $header){ ?>

                                                                <th class="<?php echo 'index_'.$index; ?>"><?php echo $header; ?> </th>

                                                            <?php  } ?>
                                                        </tr>
                                                        </thead>
                                                        <tbody>

                                                        <?php $total_amount = 0; if(!empty($account_array['detail'])){ foreach($account_array['detail'] AS $detail_array){ if(!empty($detail_array[2]) || count($detail_array) > 3){ ?>

                                                            <tr>
                                                                <?php  foreach($detail_array AS $index => $detail){

                                                                    if($index==2){
                                                                        $total_amount += $detail;
                                                                    }

                                                                    ?>
                                                                    <td class="<?php echo 'index_'.$index; ?>" ><?php echo $detail;  ?> </td>
                                                                <?php  } ?>
                                                            </tr>

                                                        <?php } }} ?>
                                                        <tfoot>
                                                        <tr>
                                                            <?php  foreach($account_array['header'] AS $index => $header){ ?>
                                                                <th class="<?php echo 'index_'.$index; ?>"> <?php

                                                                    if($index==2){
                                                                        echo $total_amount;
                                                                    }else if($index ==  $dyn_col){
                                                                        echo $grand_final_total;
                                                                    }

                                                                    ?> </th>
                                                            <?php  } ?>

                                                        </tr>
                                                        </tfoot>
                                                        </tbody>
                                                    </table>

                                                </div>


                                            </div>
                                        </div>
                                        <div class="clear"></div>

                                    <?php } ?>
                                </div>
                                <div id="mw" class="tab-pane fade <?php echo ($report_for=='mw')?'in active':''; ?>">
                                    <div class="form-group row">
                                        <div class="col-sm-12">


                                            <div class="table table-responsive">
                                                <h3 class="heading_lable">Billing Summary Module Wise</h3>
                                                <table id="mw_table" class="table table-striped table-bordered" style="width:100%">
                                                    <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Module Name</th>
                                                        <th>Amount</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>

                                                    <?php $totalCat = 0; $counter = 1; foreach($categoryData AS $item){ ?>

                                                        <tr>
                                                            <td><b><?php echo $counter; ?>.</b></td>
                                                            <td>
                                                                <b><?php
                                                                    echo $item['name'];
                                                                    ?></b>
                                                            </td>
                                                            <td><b><i class="fa fa-inr" aria-hidden="true"></i> <?php $totalCat += $item['total']; echo $item['total']; ?></b></td>

                                                        </tr>
                                                        <?php $counter++; } ?>
                                                    <tfoot>
                                                    <tr>
                                                        <th></th>
                                                        <th>Total</th>
                                                        <th><i class="fa fa-inr" aria-hidden="true"></i> <?php echo $totalCat; ?></th>

                                                    </tr>
                                                    </tfoot>
                                                    </tbody>
                                                </table>

                                            </div>


                                        </div>
                                    </div>
                                    <div class="clear"></div>
                                </div>
                                <div id="scw" class="tab-pane fade <?php echo ($report_for=='scw')?'in active':''; ?>">
                                    <div class="form-group row">
                                        <div class="col-sm-12">


                                            <div class="table table-responsive">
                                                <h3 class="heading_lable">Billing Summary Service Category Wise</h3>
                                                <table id="scw_table" class="table table-striped table-bordered" style="width:100%">
                                                    <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Category</th>
                                                        <th>Amount</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>

                                                    <?php $totalCat = 0; $counter = 1; foreach($serviceData AS $item){ ?>

                                                        <tr>
                                                            <td><b><?php echo $counter; ?>.</b></td>
                                                            <td>
                                                                <b><?php
                                                                    echo $item['name'];
                                                                    ?></b>
                                                            </td>
                                                            <td><b><i class="fa fa-inr" aria-hidden="true"></i> <?php $totalCat += $item['total']; echo $item['total']; ?></b></td>

                                                        </tr>
                                                        <?php $counter++; } ?>
                                                    <tfoot>
                                                    <tr>
                                                        <th></th>
                                                        <th>Total</th>
                                                        <th><i class="fa fa-inr" aria-hidden="true"></i> <?php echo $totalCat; ?></th>

                                                    </tr>
                                                    </tfoot>
                                                    </tbody>
                                                </table>

                                            </div>


                                        </div>
                                    </div>
                                    <div class="clear"></div>
                                </div>
                                <div id="rob" class="tab-pane fade <?php echo ($report_for=='rob')?'in active':''; ?>">
                                    <div class="form-group row">
                                        <div class="col-sm-12">


                                            <div class="table table-responsive">
                                                <h3 class="heading_lable">
                                                    <span style="float: left;margin: 6px;5px;">Receipts of Billing</span>

                                                    <span class="blink_text">Choose Table Column <i class="fa fa-arrow-right"></i> </span>
                                                    <select id="multi-select-demo" multiple="multiple" style="display: none;"></select>
                                                </h3>
                                                <table id="rob_table" class="table table-striped table-bordered" style="width:100%">
                                                    <thead>
                                                    <tr>
                                                        <th class="show_column" data-name="#">#</th>
                                                        <th class="show_column" data-name="UHID">UHID</th>
                                                        <th class="show_column" data-name="Patient Name">Patient Name</th>
                                                        <th class="show_column" data-name="Patient Mobile">Patient Mobile</th>
                                                        <th class="show_column" data-name="Patient Address">Patient Address</th>
                                                        <th class="show_column" data-name="Patient Email">Patient Email</th>

                                                        <th class="show_column" data-name="Patient Age">Patient Age</th>
                                                        <th class="show_column" data-name="Parents Name">Parents Name</th>

                                                        <th class="show_column" data-name="Receipt No">Receipt No.</th>
                                                        <th class="show_column" data-name="Date">Date</th>
                                                        <th class="show_column" data-name="Doctor">Doctor</th>

                                                        <?php if($show_conceive_date===true){ ?>
                                                            <th class="show_column" data-name="Conceive Date">Conceive Date</th>
                                                            <th class="show_column" data-name="Expected Date">Expected Date</th>
                                                        <?php } ?>

                                                        <th class="show_column" data-name="Biller">Biller</th>
                                                        <th class="show_column" data-name="Payment Via">Payment Via</th>
                                                        <th class="show_column" data-name="Description">Description</th>
                                                        <th class="show_column" data-name="Details">Details</th>
                                                        <th class="show_column" data-name="Receipt Type">Receipt Type</th>
                                                        <?php if(!empty($active_fields)){ foreach ($active_fields as $field_key =>$field){ ?>
                                                            <th class="show_column" data-name="<?php echo $field['label']; ?>"><?php echo $field['label']; ?></th>
                                                        <?php }} ?>

                                                        <th class="show_column" data-name="Reason">Reason</th>
                                                        <th class="show_column" data-name="Remark">Remark</th>
                                                        <th class="show_column" data-name="Payment Status">Payment Status</th>

                                                        <th class="show_column" data-name="Total Amount">Total Amount</th>
                                                        <th class="show_column" data-name="Total Tax">Total Tax</th>

                                                        <th class="show_column" data-name="Option">Option</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php $category_wise_payment=array(); $totalAmount = 0; $totalTax = 0; ?>

                                                    <?php $counter = 1; foreach($orderDetails AS $item){  ?>


                                                        <tr>
                                                            <td><?php echo $counter; ?>.</td>
                                                            <td><?php echo $item['uhid']; ?></td>
                                                            <td><?php echo $item['patient_name']; ?></td>
                                                            <td><?php echo $item['patient_mobile']; ?></td>
                                                            <td><?php echo $item['patient_address']; ?></td>
                                                            <td><?php echo $item['email']; ?></td>
                                                            <td>

                                                                <?php
                                                                $ageStr = $this->AppAdmin->getAgeStringFromDob($item['age']);
                                                                if(empty($ageStr)){
                                                                    $ageStr = $item['age'];
                                                                } echo ( $ageStr);
                                                                ?>


                                                            </td>
                                                            <td><?php echo $item['parents_name']; ?></td>

                                                            <td><?php echo $item['unique_id']; ?></td>

                                                            <td><?php echo $item['date']; ?></td>
                                                            <td>
                                                                <?php
                                                                $tmp_doctor = explode(",",$item['doctor_name']);
                                                                if(count($tmp_doctor) > 1){
                                                                    foreach ($tmp_doctor as $key =>$value){
                                                                        $tmp= explode("##",$value);
                                                                        echo $tmp[0];
                                                                        if(!empty($tmp[1])){
                                                                            echo "<br>( ".$tmp[1]." )";
                                                                        }
                                                                        echo "<br>";
                                                                    }

                                                                }else{

                                                                    $tmp= explode("##",$item['doctor_name']);
                                                                    echo $tmp[0];
                                                                    if(!empty($tmp[1])){
                                                                        echo "<br>( ".$tmp[1]." )";
                                                                    }


                                                                }

                                                                ?>

                                                            </td>
                                                            <?php if($show_conceive_date===true){ ?>
                                                                <td><?php
                                                                    if(!empty($item['conceive_date']) && $item['conceive_date']!='0000-00-00'){
                                                                        echo date('d/m/Y',strtotime($item['conceive_date']));
                                                                    }
                                                                    ?></td>
                                                                <td><?php
                                                                    if(!empty($item['expected_date']) && $item['expected_date']!='0000-00-00'){
                                                                        echo date('d/m/Y',strtotime($item['expected_date']));
                                                                    }
                                                                    ?></td>
                                                            <?php } ?>
                                                            <td><?php echo $item['biller']; ?></td>

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
                                                            <?php if(!empty($active_fields)){ foreach ($active_fields as $field_key =>$field){ ?>
                                                                <td><?php echo $item[$field['column']]; ?></td>
                                                            <?php }} ?>
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
                                                                    if($item['booking_validity_attempt'] > 1 && $item['total_amount'] ==0){
                                                                        echo " (Free/-)";
                                                                    }else{
                                                                        echo ucfirst(strtolower($item['payment_status']));
                                                                    }

                                                                }

                                                                ?></td>
                                                            <td><i class="fa fa-inr" aria-hidden="true"></i> <?php

                                                                $allowContinue = ($login['thinapp_id']==318 && $item['settlement_payment_status'] =='REFUND' && $item["refund_payment_type_id"])?false:true;
                                                                if($allowContinue){
                                                                    if(($item['advance_status'] == 'INACTIVE' || $item['settlement_payment_status'] =='REFUND' ) && $item['is_settlement'] =='Y' ){
                                                                        $totalAmount -= sprintf("%.2f",$item['total_amount']);
                                                                        $category_amount  = sprintf("%.2f",$item['total_amount']);

                                                                        $totalTax -= sprintf("%.2f",$item['total_tax']);
                                                                    }else{


                                                                            $totalAmount += sprintf("%.2f",$item['total_amount']);
                                                                            $category_amount = sprintf("%.2f",$item['total_amount']);
                                                                            $totalTax += sprintf("%.2f",$item['total_tax']);



                                                                    }
                                                                }

                                                                $payment_type_name = trim($item['payment_type_name']);

                                                                $category_wise_payment[$payment_type_name] = isset($category_wise_payment[$payment_type_name])?($category_wise_payment[$payment_type_name]+$category_amount):$category_amount;

                                                                echo sprintf("%.2f",$item['total_amount']);


                                                                ?>

                                                            <td><i class="fa fa-inr" aria-hidden="true"></i> <?php echo sprintf("%.2f",$item['total_tax']); ?></td>


                                                            <td>
                                                                <div class="dropdown option_td">
                                                                    <button class="btn btn-default btn-xs dropdown-toggle" type="button" data-toggle="dropdown">Option
                                                                        <span class="caret"></span></button>
                                                                    <ul class="dropdown-menu pull-right option_btn_panel">


                                                                        <?php $module = ($item['patient_due_amount_id'] > 0)?'DUE':'IPD';; ?>
                                                                        <?php if($item['is_opd'] == 'Y'){ ?>
                                                                            <a class="btn btn-success btn-xs" href="<?php echo Router::url('/').'app_admin/print_invoice/'.base64_encode($item['id']); ?>" target="_blank">Receipt</a>

                                                                            <?php if($item['due_amount_settled'] =="NO" && ( $login1['USER_ROLE'] =='ADMIN' || ($login1['USER_ROLE'] =='RECEPTIONIST' && @$staff_data['edit_appointment_payment'] =="YES"))){ ?>
                                                                                <button type="button" class="btn btn-primary btn-xs editBtn" data-id="<?php echo base64_encode($item['id']); ?>">Edit</button>
                                                                            <?php } ?>
                                                                        <?php }else{
                                                                            if($item['is_advance'] == 'Y'){ ?>
                                                                                <a class="btn btn-success btn-xs" href="<?php echo Router::url('/').'app_admin/print_invoice/'.base64_encode($item['id'])."/IAD"; ?>" target="_blank">Receipt</a>
                                                                            <?php }else if($item['is_settlement'] == 'Y'){ ?>
                                                                                <a class="btn btn-success btn-xs" href="<?php echo Router::url('/').'app_admin/print_invoice_non_opd_settlement/'.base64_encode($item['id']); ?>" target="_blank">Receipt</a>

                                                                            <?php }else{   ?>


                                                                                <a class="btn btn-success btn-xs" href="<?php echo Router::url('/').'app_admin/print_invoice/'.base64_encode($item['id'])."/$module"; ?>" target="_blank">Receipt</a>

                                                                                <?php if(in_array($login1['USER_ROLE'],array('LAB','PHARMACY'))){   ?>
                                                                                    <button type="button" class="btn btn-primary btn-xs editBtn" data-id="<?php echo base64_encode($item['id']); ?>">Edit</button>
                                                                                <?php }else{   ?>
                                                                                    <?php if( $item['due_amount_settled'] =="NO" && $module != "DUE" && $item['is_package'] != 'Y'  && ( $login1['USER_ROLE'] =="ADMIN" || ($login1['USER_ROLE'] =='RECEPTIONIST' && @$staff_data['edit_appointment_payment'] =="YES"))){ ?>
                                                                                        <button type="button" class="btn btn-primary btn-xs editBtn" data-id="<?php echo base64_encode($item['id']); ?>">Edit</button>
                                                                                    <?php } ?>
                                                                                <?php }   ?>


                                                                            <?php }
                                                                        } ?>

                                                                        <?php if($module != "DUE" && in_array($login1['USER_ROLE'],array('ADMIN','LAB','PHARMACY')) && $item['is_package'] != 'Y'){ ?>
                                                                            <button type="button" class="btn btn-danger btn-xs delete_btn" data-id="<?php echo base64_encode($item['id']); ?>">Delete</button>
                                                                        <?php } ?>
                                                                    </ul>
                                                                </div>
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

                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <?php if($show_conceive_date===true){ ?>
                                                            <th></th>
                                                            <th></th>
                                                        <?php } ?>
                                                        <th></th>
                                                        <?php if(!empty($active_fields)){ foreach ($active_fields as $field_key =>$field){ ?>
                                                            <th></th>
                                                        <?php }} ?>
                                                        <th>Total</th>
                                                        <th><i class="fa fa-inr" aria-hidden="true"></i> <?php echo $totalAmount; ?></th>
                                                        <th><i class="fa fa-inr" aria-hidden="true"></i> <?php echo $totalTax; ?></th>
                                                        <th></th>

                                                    </tr>
                                                    </tfoot>
                                                </table>
                                            </div>


                                        </div>
                                    </div>
                                    <div class="clear"></div>
                                </div>

                                <div id="pt" class="tab-pane fade <?php echo ($report_for=='pt')?'in active':''; ?>">
                                    <div class="form-group row">
                                        <div class="col-sm-12">


                                            <div class="table table-responsive">
                                                <h3 class="heading_lable">Payment Type</h3>
                                                <table id="payment_cat_table" class="table table-striped table-bordered" style="width:100%">
                                                    <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Payment Via</th>
                                                        <th>Amount</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>

                                                    <?php $totalCat = 0; foreach($paymentCategoryData AS $key => $item){ ?>

                                                        <tr>
                                                            <td><b><?php echo $key+1; ?>.</b></td>
                                                            <td>
                                                                <b><?php
                                                                    echo $item['payment_type_name'];
                                                                    ?></b>
                                                            </td>
                                                            <td><b><i class="fa fa-inr" aria-hidden="true"></i> <?php $totalCat += $item['amount']; echo $item['amount']; ?></b></td>

                                                        </tr>
                                                        <?php $counter++; } ?>
                                                    <tfoot>
                                                    <tr>
                                                        <th></th>
                                                        <th>Total</th>
                                                        <th><i class="fa fa-inr" aria-hidden="true"></i> <?php echo $totalCat; ?></th>

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



        $('a[data-toggle="tab"]').on('show.bs.tab', function (e) {
            var target = $(e.target).attr("href") // activated tab
            window.location.href = target;
            e.preventDefault();
        });




        var report_for = "<?php echo $report_for; ?>";

        //  $('.nav-tabs a[data-href="#rob"]').tab('show');

        //$('.nav-tabs a[href='+'#'+report_for+']').tab('show');
        if(report_for=='la'){
            $('#table_la').DataTable({
                dom: 'Blfrtip',
                lengthMenu: [
                    [ 10, 25, 50, 100, 150, 200, -1 ],
                    [ '10 rows', '25 rows', '50 rows', '100 rows', '150 rows', '200 rows', 'Show all' ]
                ],
                "language": {
                    "emptyTable": "No Data Found"
                },
                buttons: [
                    {
                        extend: 'copy',
                        header: true,
                        footer: true,
                        messageTop: '<?php echo $thinappName; ?>',
                        title: '',
                        exportOptions: {
                            columns: ':visible'
                        }

                    },
                    {
                        extend: 'csv',
                        header: true,
                        footer: true,
                        messageTop: '<?php echo $thinappName; ?>',
                        title: '',
                        exportOptions: {
                            columns: ':visible'
                        }

                    },
                    {
                        extend: 'excel',
                        header: true,
                        footer: true,
                        messageTop: '<?php echo $thinappName; ?>',
                        title: '',
                        exportOptions: {
                            columns: ':visible'
                        }

                    },
                    {
                        extend: 'pdf',
                        header: true,
                        footer: true,
                        messageTop: '<?php echo $thinappName; ?>',
                        title: '',
                        exportOptions: {
                            columns: ':visible'
                        }

                    },
                    {
                        extend: 'print',
                        header: true,
                        footer: true,
                        messageTop: '<?php echo $thinappName; ?>',
                        title: '',
                        exportOptions: {
                            columns: ':visible'
                        }

                    }
                ]
            });

            $("#table_la").slideDown(100);
        }else if(report_for=='rob'){



            var report_head_length = $("#rob_table thead:first tr th").length;
            var options = [];
            var columns = [];
            <?php echo !empty($table_columns)?"var setting = [".$table_columns."];":"var setting = false;"; ?>
            if(setting){
                setting = JSON.parse(setting);

            }
            $("#rob_table thead:first tr th").each(function (index,value) {
                var visible;
                var name = $(this).attr('data-name');
                if(index < report_head_length ){
                    if(setting){
                        if(setting.hasOwnProperty(name)){
                            if(setting[name].visibility == "true"){
                                visible = true;
                            }else{
                                visible = false;
                            }
                        }else{
                            visible =false;
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
                    var obj = {label: $(this).text(), title: $(this).text(), value: name, selected: visible};
                    options.push(obj);
                    columns.push(col);
                }
            });
            var table;
            function create_report_table(){
                var report_head_length = $("#rob_table thead .show_column").length;
                table = $('#rob_table').DataTable({
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
                            messageTop: '<?php echo $thinappName; ?>',
                            title: '',
                            exportOptions: {
                                columns: ':visible'
                            }
                        },
                        {
                            extend: 'csv',
                            header: true,
                            footer: true,
                            messageTop: '<?php echo $thinappName; ?>',
                            title: '',
                            exportOptions: {
                                columns:  [':visible :not(:last-child)']
                            }
                        },
                        {
                            extend: 'excel',
                            header: true,
                            footer: true,
                            messageTop: '<?php echo $thinappName; ?>',
                            title: '',
                            exportOptions: {
                                columns: ':visible'
                            }


                        },
                        {
                            extend: 'pdf',
                            header: true,
                            footer: true,
                            messageTop: '<?php echo $thinappName; ?>',
                            title: '',
                            exportOptions: {
                                columns: ':visible'
                            }


                        },
                        {
                            extend: 'print',
                            header: true,
                            footer: true,
                            messageTop: '<?php echo $thinappName; ?>',
                            title: '',
                            exportOptions: {
                                columns: ':visible'
                            }


                        }
                    ]
                });
                if(getCookie('table_order')){
                    var data = getCookie('table_order').split(',');
                    table.order( [data[0],data[1]] ).draw();
                }

                $('#rob_table').on( 'order.dt', function () {
                    var order = table.order();
                    setCookie('table_order',order);
                } );


            }
            create_report_table();

            $('#multi-select-demo').multiselect({
                nonSelectedText: 'Select Fields',
                buttonWidth: 300,
                onChange: function(option, checked, select) {
                    var col_index = table.column(":contains("+option.val()+")").index();
                    var column = table.column( col_index );
                    column.visible( ! column.visible() );
                    update_table_columns();
                }
            });

            $('#multi-select-demo').multiselect('dataprovider',options);
            $('#multi-select-demo').show();


            var visible_td = '<?php echo json_encode($visible_index); ?>';
            var index_array = JSON.parse(visible_td);

            if(index_array){
                $.each(index_array, function (index, value) {

                    if(value=="false" || value == false){
                        $(".index_"+index).remove();
                    }
                });

            }


        }else{
            var obj;
            if(report_for=='mw'){
                obj = "#mw_table";
            }else if(report_for=='scw'){
                obj = "#scw_table";
            }else if(report_for=='scw'){
                obj = "#payment_cat_table";
            }
            $(obj).DataTable({
                dom: 'Blfrtip',
                lengthMenu: [
                    [ 10, 25, 50, 100, 150, 200, -1 ],
                    [ '10 rows', '25 rows', '50 rows', '100 rows', '150 rows', '200 rows', 'Show all' ]
                ],
                "language": {
                    "emptyTable": "No Data Found"
                },
                buttons: [
                    {
                        extend: 'copy',
                        header: true,
                        footer: true,
                        messageTop: '<?php echo $thinappName; ?>',
                        title: '',
                        exportOptions: {
                            columns: [0,1,2]
                        }

                    },
                    {
                        extend: 'csv',
                        header: true,
                        footer: true,
                        messageTop: '<?php echo $thinappName; ?>',
                        title: '',
                        exportOptions: {
                            columns: [0,1,2]
                        }

                    },
                    {
                        extend: 'excel',
                        header: true,
                        footer: true,
                        messageTop: '<?php echo $thinappName; ?>',
                        title: '',
                        exportOptions: {
                            columns: [0,1,2]
                        }

                    },
                    {
                        extend: 'pdf',
                        header: true,
                        footer: true,
                        messageTop: '<?php echo $thinappName; ?>',
                        title: '',
                        exportOptions: {
                            columns: [0,1,2]
                        }

                    },
                    {
                        extend: 'print',
                        header: true,
                        footer: true,
                        messageTop: '<?php echo $thinappName; ?>',
                        title: '',
                        exportOptions: {
                            columns: [0,1,2]
                        }

                    }
                ]
            });
        }






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












        function update_table_columns(){
            if(table){
                var save_column = new Object();

                $(".multiselect-container input[type='checkbox']").each( function (index) {
                    var visibility = $(this).is(':checked');
                    var col_text =$(this).val();

                    var tmp_obj = {"index":index,"column_name":col_text,"visibility":visibility};
                    save_column[col_text] =tmp_obj;
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


        var date = new Date();
        var last = (new Date(date.getTime() - (45 * 24 * 60 * 60 * 1000)));
        $(".date_from, .date_to").datepicker({clearBtn:true,format: 'dd/mm/yyyy',autoclose:true,  orientation: "bottom auto",endDate: new Date()});

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
