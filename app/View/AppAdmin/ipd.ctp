<?php
$login = $this->Session->read('Auth.User');
$doctor_list =$this->AppAdmin->getHospitalDoctorList($login['User']['thinapp_id']);
$list_type = @$this->request->query['lt'];
?>
<?php  echo $this->Html->css(array('dataTableBundle.css')); ?>
<?php  echo $this->Html->script(array('dataTableBundle.js','jquery.maskedinput-1.2.2-co.min.js','comman.js')); ?>


<div class="Home-section-2">

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="container">
            <div class="row">
                <!--box 1 -->
                <!--box 1 -->



                <div class="middle-block">
                    <!-- Heading -->


                    <h3 class="screen_title">

                        <?php
                        if($list_type=='td'){
                            echo "To Discharge Patient List";
                        }else if($list_type=='d'){
                            echo "Discharged Patient List";
                        }else{
                            echo "Admitted Patient List";
                        }

                        ?>

                    </h3>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 custom_form_box">

                        <?php echo $this->element('message'); ?>


                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 hospital_search_box">
                            <div class="row">
                                <?php echo $this->element('app_admin_inventory_tab'); ?>
                            </div>
                            <div class="row search_main">

                                <?php echo $this->Form->create('Search',array('type'=>'get','url'=>array('controller'=>'app_admin','action' => 'search_ipd'))); ?>
                                <input type="hidden" name="list_type" value="<?php echo $list_type; ?>">
                                <div class="form-group">
                                    <div class="col-md-1">
                                        <?php echo $this->Form->input('uhid', array('type'=>'text','placeholder' => '', 'label' => 'UHID', 'class' => 'form-control')); ?>
                                    </div>

                                    <div class="col-md-1">
                                        <?php echo $this->Form->input('ipd_unique_id', array('type'=>'text','placeholder' => '', 'label' => 'IPD ID', 'class' => 'form-control')); ?>
                                    </div>

                                    <div class="col-md-2">
                                        <?php echo $this->Form->input('name', array('type'=>'text','placeholder' => '', 'label' => 'Patient Name', 'class' => 'form-control')); ?>
                                    </div>

                                    <div class="col-md-2">
                                        <?php echo $this->Form->input('mobile', array('type'=>'text','placeholder' => '', 'label' => 'Patient Mobile', 'class' => 'form-control')); ?>
                                    </div>

                                    <div class="col-md-2">
                                        <?php echo $this->Form->input('consultant',array('empty'=>'Select Consultant','type'=>'select','label'=>'Consultant','options'=>$doctor_list,'class'=>'form-control')); ?>
                                    </div>

                                    <div class="col-md-1">
                                        <?php echo $this->Form->input('from_date', array('autocomplete'=>'off','type'=>'text','placeholder' => '', 'label' => 'From date', 'class' => 'form-control from_date')); ?>
                                    </div>
                                    <div class="col-md-1">
                                        <?php echo $this->Form->input('to_date', array( 'autocomplete'=>'off','type'=>'text','placeholder' => '', 'label' => 'To date', 'class' => 'form-control to_date')); ?>
                                    </div>


                                    <div class="col-sm-2 action_btn" >
                                        <div class="input text">
                                            <label style="display: block;">&nbsp;</label>
                                            <?php echo $this->Form->submit('Search',array('div'=>false,'class'=>'btn btn-info btn-xs','id'=>'search')); ?>
                                            <a class="btn btn-warning btn-xs resteButton" href="<?php echo $this->Html->url(array('controller'=>'app_admin','action'=>"ipd?lt=$list_type")) ?>">Reset</a>
                                            <a class="btn btn-success btn-xs"  href="<?php echo $this->Html->url(array('controller'=>'app_admin','action'=>'add_ipd_patient')) ?>"><i class="fa fa-plus"></i> Patient </a>
                                        </div>
                                    </div>

                                </div>
                                <?php echo $this->Form->end(); ?>
                            </div>
                        </div>

                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 hospital_table_box">


                            <div class="row">
                                <?php if(!empty($ipd_list)){ ?>


                                    <table id="example" class="table">
                                    <thead>
                                    <tr>
                                        <th>#</th>

                                        <th>UHID</th>
                                        <th>IPD ID</th>
                                        <th>Patient Name</th>
                                        <th>Mobile</th>
                                        <th>Age</th>
                                        <th>Consultant</th>
                                        <th>Ward</th>
                                        <th>Remark</th>
                                        <th>Address</th>
                                        <th>Status</th>
                                        <th>To Discharge</th>
                                        <th>Admit Date</th>
                                        <?php if($list_type == 'd'){ ?>
                                            <th>Discharge Date</th>
                                        <?php } ?>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php


                                    foreach ($ipd_list as $key => $list){

                                        $patient_type = 'CU';
                                        $object = "AppointmentCustomer";
                                        $patient_id = base64_encode($list['AppointmentCustomer']['id']);
                                        if(empty($patient_id)){
                                            $patient_type = 'CH';
                                            $patient_id = base64_encode($list['Children']['id']);
                                            $object = "Children";
                                        }

                                        ?>
                                        <tr>
                                            <td><?php echo $key+1; ?></td>
                                            <td><?php

                                                echo $list['AppointmentCustomer']['uhid'].$list['Children']['uhid'];

                                             ?></td>
                                            <td><?php echo $list['HospitalIpd']['ipd_unique_id']; ?></td>

                                           <td><?php  echo $list['AppointmentCustomer']['first_name'].$list['Children']['child_name']; ?></td>
                                            <td><?php echo $list['AppointmentCustomer']['mobile'].$list['Children']['mobile']; ?></td>
                                            <td><?php


                                                $dob = $list[$object]['dob'];
                                                $dob =  ($dob!='1970-01-01' && $dob!='0001-11-30')?$dob:'';
                                                if(!empty($list[$object]['age'])){
                                                    echo @$list[$object]['age'];
                                                }else{
                                                    if(!empty($dob)){
                                                        echo $res = Custom::get_age_from_dob($dob);
                                                    }
                                                }



                                                ?></td>
                                            <td><?php echo $list['AppointmentStaff']['name'];?></td>
                                            <td>
                                                <span class="ward_name"><?php echo $list['HospitalServiceCategory']['name'];?></span>
                                            </td>
                                            <td title="<?php echo $list['HospitalIpd']['remark']; ?>"><?php echo mb_strimwidth($list['HospitalIpd']['remark'], 0, 40, '...') ;?></td>
                                            <td><?php echo isset($list['AppointmentCustomer']['address'])?$list['AppointmentCustomer']['address']:$list['Children']['address']; ?></td>
                                          
                                            <td><?php echo $this->AppAdmin->showIpdStatus($list['HospitalIpd']['admit_status']); ?></td>
                                            <td><?php if($list['HospitalIpd']['to_discharge_date']!='0000-00-00') echo date('d-m-Y',strtotime($list['HospitalIpd']['to_discharge_date']));?></td>
                                            <td><?php echo date('d-m-Y H:i',strtotime($list['HospitalIpd']['admit_date']));?></td>
                                            <?php if($list_type == 'd'){ ?>
                                                <td><?php echo date('d-m-Y H:i',strtotime($list['HospitalDischarge']['discharge_date']));?></td>
                                            <?php } ?>
                                             <td class="action_btn">



                                                 <div class="dropdown">
                                                     <button class="btn btn-default btn-xs dropdown-toggle" type="button" data-toggle="dropdown">Option
                                                         <span class="caret"></span></button>
                                                     <ul class="dropdown-menu pull-right">

                                                         <?php

                                                         $discharge_id = @trim($list['HospitalDischarge']['id']);
                                                         $discharge_id = !empty($discharge_id)?'/'.base64_encode($discharge_id):'';

                                                         ?>




                                                         <?php  if( $list['AppointmentCustomer']['uhid'].$list['Children']['uhid'] != '' ){ ?>

                                                             <?php if($list['HospitalIpd']['hospital_ipd_settlement_id'] == 0 ){ ?>
                                                                 <li><a href="<?php echo Router::url('/app_admin/add_hospital_ipd_receipt/'.base64_encode($list['AppointmentCustomer']['uhid'].$list['Children']['uhid']).'/'.base64_encode($list['HospitalIpd']['id']),true); ?>" target="_blank" class="receipt_btn" ><i class="fa fa-plus"></i> Add Expense </a></li>
                                                             <?php } ?>
                                                             <li><a href="<?php echo Router::url('/app_admin/hospital_patient_invoice_list/'.base64_encode($list['AppointmentCustomer']['uhid'].$list['Children']['uhid']),true); ?>" target="_blank" class="receipt_btn" ><i class="fa fa-list"></i> Receipts </a></li>
                                                         <?php }?>

                                                         <li><a target="_blank" href="<?php echo Router::url('/app_admin/discharge_patient/',true).base64_encode($list['HospitalIpd']['id']).$discharge_id; ?>">
                                                             <?php if($list_type =='d'){ ?>
                                                                 <i class="fa fa-eye"></i> Discharge Report
                                                             <?php }else{ ?>
                                                                 <i class="fa fa-sign-out"></i> Discharge Now
                                                             <?php } ?>

                                                         </a></li>


                                                         <?php if(!empty($list['HospitalIpd']['admit_status']) && $list_type !='d'){ ?>


                                                             <li><a href="javascript:void(0);"  class="to_discharge_btn" data-id="<?php echo base64_encode($list['HospitalIpd']['id']); ?>"><i class="fa fa-calendar"></i> Schedule Discharge Date</a></li>
                                                         <?php } ?>
                                                         <?php if($login['USER_ROLE'] !='RECEPTIONIST' && $login['USER_ROLE'] !='DOCTOR' && $login['USER_ROLE'] !='STAFF'){ ?>
                                                             <?php if($patient_type=="CU" && $list_type !='d'){ ?>
                                                                 <li><a target="_blank"  href="<?php echo Router::url('/app_admin/edit_ipd_patient/',true).base64_encode($list['HospitalIpd']['id']); ?>"><i class="fa fa-edit"></i> Edit Patient Detail</a></li>
                                                             <?php } ?>
                                                         <?php } ?>
                                                         <li><a class="deposit_btn add_deposit" href="javascript:void(0);" data-ipd="<?php echo base64_encode($list['HospitalIpd']['id']); ?>"><i class="fa fa-rupee"></i> Deposit Amount</a></li>
                                                         <li><a class="deposit_btn deposit_list_btn" href="javascript:void(0);" data-ipd="<?php echo base64_encode($list['HospitalIpd']['id']); ?>"><i class="fa fa-rupee"></i> Deposit List</a></li>
                                                         <li><a class="deposit_btn expense_list_btn" href="javascript:void(0);" data-ipd="<?php echo base64_encode($list['HospitalIpd']['id']); ?>"><i class="fa fa-rupee"></i> Expense List</a></li>


                                                         <?php if($list['HospitalIpd']['admit_status'] == 'ADMIT' ){ ?>
                                                             <li><a href="javascript:void(0);" data-id="<?php echo base64_encode($list['HospitalIpd']['id']); ?>"  type="button" class="delete_admit_patient"><i class="fa fa-trash"></i> Delete Admit Patient </a></li>
                                                         <?php } ?>

                                                         <li><a href="javascript:void(0);"  type="button" class="print_slip_btn"><i class="fa fa-print"></i> Print Slip </a></li>



                                                         <?php if($list['HospitalIpd']['hospital_ipd_settlement_id'] == 0 ){ ?>
                                                             <li><a class="deposit_btn final_settlement" href="javascript:void(0);" data-ipd="<?php echo base64_encode($list['HospitalIpd']['id']); ?>"><i class="fa fa-rupee"></i> Final Settlement</a></li>
                                                         <?php }else{  ?>

                                                             <li><a href="javascript:void(0);"  data-type="settlement"  type="button" data-id="<?php echo base64_encode($list['HospitalIpd']['id']); ?>" class="load_all_receipt btn-xs"><i class="fa fa-history"></i> Settlement History </a></li>

                                                         <?php }  ?>

                                                         <?php if($list_type == 'td' || $list_type == 'a' || $list_type == ''){ ?>
                                                             <li><a class="update_ward_btn" href="javascript:void(0);" ipd-id="<?php echo base64_encode($list['HospitalIpd']['id']);?>"><i class="fa fa-edit"></i> Update Ward</a> </li>
                                                             <li><a class="history_ward_btn btn-xs" href="javascript:void(0);" ipd-id="<?php echo base64_encode($list['HospitalIpd']['id']);?>"><i class="fa fa-list"></i> Ward History </a></li>


                                                         <?php } ?>



                                                     </ul>
                                                 </div>







                                            </td>

                                        </tr>

                                    <?php } ?>
                                    </tbody>
                                </table>

                           <?php }else{ ?>

                            </div>
                            <div class="no_data">


                                <h2>
                                    <?php
                                    if($list_type=='td'){

                                        echo "No To Discharge Patient Yet";

                                    }else if($list_type=='d'){
                                        echo "No Discharged Patient Yet";

                                    }else{
                                        echo "No Admitted Patient Yet";
                                    }

                                    ?>

                                </h2>
                            </div>
                        <?php } ?>
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

<div class="modal fade" id="amount_deposit" role="dialog" keyboard="true">

</div>


<div class="modal fade" id="all_receipt" role="dialog" keyboard="true">

</div>



<div  class="inline"></div>

<style>

    .dropdown-menu{
        width: 400px !important;
    }

    .dropdown-menu li{
        width: 50% !important;
        float: left !important;
    }



    .print_slip th{
        padding: 0px 10px 0px 0px;

    }

    .print_slip td{
        padding: 0px 10px 0px 0px;

    }

    #example_length {
        width: 32%;
        text-align: right;
    }
    .modal_div{
        width: 85%;
    }
    .search_main [class*="col-"]{
        padding-right: 0px !important;
    }
    .search_main [class*="col-"] input[type='text']{
        padding: 6px 6px;
    }
    .search_main .btn-xs {
        padding: 7px 10px;
        margin: 3px auto;
    }

</style>


<script>
    var parentTr = "";
    $(document).ready(function(){
        $('.print_slip_btn').click(function(){

            var divToPrint= $("#example").find("thead").html();
            var print_raw  = $(this).closest("tr");
            var append = "<table>";
            var header_row =''; 
            var row = '';
            $("#example").find("thead tr th").each(function (index, value) {
                if(index > 0 && index  < 11 && index != 9 && index != 7 ){
                    header_row += $('<th />').append($(this).clone()).html();
                    row += $('<td />').append($(print_raw).children().eq(index).clone()).html();
                }
            });
            header_row = "<thead>"+header_row+"</thead>";
            row = "<tbody>"+row+"</tbody>";
            append += header_row + row + "</table>";


            var newWin=window.open('','Print-Window');
            newWin.document.open();
            newWin.document.write('<html><head><style> th, td{border:1px solid;padding:0px 5px;font-size:15px; min-width:100px !important; text-align: left;}</style> </head><body onload="window.print()">'+append+'</body></html>');
            newWin.document.close();
           setTimeout(function(){newWin.close();},10);

        });

        $(".channel_tap a").removeClass('active');

        var selected = 'admit_tab';
        var tab = "<?php echo @$this->request->query['lt']; ?>";
        console.log(tab);
        if(tab =='a'){
            selected = 'admit_tab';
        }else if(tab=='td'){
            selected = 'to_discharge_tab';
        }else if(tab=='d'){
            selected='discharge_tab';
        }

        $("#"+selected).addClass('active');

        var columns = [];
        var total_head = $("#example thead th").length;



        $('#example').DataTable({
            dom: 'Blfrtip',
            lengthMenu: [
                [ 10, 25, 50, 100, 150, 200, -1 ],
                [ '10 rows', '25 rows', '50 rows', '100 rows', '150 rows', '200 rows', 'Show all' ]
            ],
            "language": {
                "emptyTable": "No Data Found Related To Search"
            },
            buttons: [
                {
                    extend: 'copy',
                    header: true,
                    messageTop: '<?php echo $reportTitle; ?>',
                    title: '',
                    exportOptions: {
                        columns: 'th:not(:last-child)'
                    }

                },
                {
                    extend: 'csv',
                    header: true,
                    messageTop: '<?php echo $reportTitle; ?>',
                    title: '',
                    exportOptions: {
                        columns: 'th:not(:last-child)'
                    }

                },
                {
                    extend: 'excel',
                    header: true,
                    messageTop: '<?php echo $reportTitle; ?>',
                    title: '',
                    exportOptions: {
                        columns: 'th:not(:last-child)'
                    }

                },
                {
                    extend: 'pdf',
                    header: true,
                    messageTop: '<?php echo $reportTitle; ?>',
                    title: '',
                    exportOptions: {
                        columns: 'th:not(:last-child)'
                    }

                },
                {
                    extend: 'print',
                    header: true,
                    messageTop: '<?php echo $reportTitle; ?>',
                    title: '',
                    exportOptions: {
                        columns: 'th:not(:last-child)'
                    }

                }
            ]
        });

        $(".from_date, .to_date").datepicker({clearBtn:true,format: 'dd/mm/yyyy',autoclose:true,orientation: "bottom auto",endDate: new Date()});






        $(document).on('click','.to_discharge_btn',function () {

            var id = $(this).attr('data-id');
           var dialog =  $.confirm({
                title: 'Add Discharge Date',
                content: '' +
                '<form action="" class="formName">' +
                '<div class="form-group">' +
                '<label>Select Discharge Date</label>' +
                '<input type="text"  class="name form-control to_discharge" required />' +
                '</div>' +
                '</form>',
                buttons: {
                    formSubmit: {
                        text: 'Save',
                        btnClass: 'btn-blue',
                        action: function () {
                            var date = this.$content.find('.name').val();

                            if(date){
                                $.ajax({
                                    type: 'POST',
                                    url: baseurl + "app_admin/update_to_discharge_date",
                                    data: {id: id,date:date},
                                    beforeSend: function () {
                                        //$btn.button('loading').html('Wait..')
                                    },
                                    success: function (data) {
                                        data = JSON.parse(data);
                                        if(data.status==1){
                                            $.alert(data.message);
                                            dialog.close();
                                            window.location.reload();
                                        }else{
                                            $.alert(data.message);
                                            return false;
                                        }

                                    },
                                    error: function (data) {

                                        $.alert("Sorry something went wrong on server.");
                                    }
                                });

                                return false;
                            }else{
                                $.alert('Please select date.');
                            }

                        }
                    },
                    cancel: function () {
                        //close
                    },
                },
                onContentReady: function () {
                    // bind to events
                    $(".to_discharge").datepicker({clearBtn:true,format: 'dd/mm/yyyy',autoclose:true,orientation: "top auto",setDate:new Date()});
                    var jc = this;
                    this.$content.find('form').on('submit', function (e) {
                        // if the user submits the form by pressing enter in the field.
                        e.preventDefault();
                        jc.$$formSubmit.trigger('click'); // reference the button and click it
                    });
                }
            });


        });



        $(document).on('click','.deposit_btn',function(e){

            if($(this).hasClass('add_deposit'))
            {
                var openTab = "#add";
            }
            else if($(this).hasClass('deposit_list_btn'))
            {
                var openTab = "#list";
            }
            else if($(this).hasClass('expense_list_btn'))
            {
                var openTab = "#expense";
            }
            else
            {
                var openTab = "#settlement";
            }


            var $btn = $(this);
            var ipd_id = $(this).attr('data-ipd');
            if(ipd_id){
                $.ajax({
                    type:'POST',
                    url: baseurl+"app_admin/deposit_amount_modal",
                    data:{ipd_id:ipd_id},
                    beforeSend:function(){
                        $btn.button('loading').html('<i class="fa fa-spinner fa-pulse fa-1x fa-fw"></i>  Please Wait..');
                    },
                    success:function(data){
                        $btn.button('reset');
                        $("#amount_deposit").modal('show').html(data);

                        /*setTimeout(function(){*/
                            $('[href="'+openTab+'"]').trigger('click');
                        /*},500)*/


                    },
                    error: function(data){
                        $btn.button('reset');
                        $.alert("Sorry something went wrong on server.");
                    }
                });
            }

        });


        $('#amount_deposit').on('shown.bs.modal', function (e) {

            $(".amount").focus();
        });


        $('#all_receipt').on('hide.bs.modal', function (e) {
           $(this).html('');
        });


        $(document).on('click','.load_all_receipt',function(e){
            var ipd_id = $(this).attr('data-id');
            var receipt_for = $(this).attr('data-type');
            var $btn_loading = $(this);
            if(ipd_id){
                $.ajax({
                    type:'POST',
                    url: baseurl+"app_admin/ipd_load_all_receipt",
                    data:{ipd_id:ipd_id,receipt_for:receipt_for},
                    beforeSend:function(){
                        $btn_loading.button('loading').html('<i class="fa fa-spinner fa-pulse fa-1x fa-fw"></i>  Please Wait..');
                    },
                    success:function(data){
                        $btn_loading.button('reset');
                        $("#all_receipt").modal('show').html(data);
                    },
                    error: function(data){
                        $btn_loading.button('reset');
                        $.alert("Sorry something went wrong on server.");
                    }
                });
            }

        });




        $(document).on('click','.delete_admit_patient',function(){

            var ipd_id = $(this).attr('data-id');
            var parent_row = $(this).closest('tr');
            var $btn_loading = $(this);
            var jc = $.confirm({
                title: "Delete Admit Patient",
                content: 'Are you sure you want to delete this admit patient?',
                type: 'red',
                buttons: {
                    ok: {
                        text: "Yes",
                        btnClass: 'btn-primary',
                        keys: ['enter'],
                        name:"ok",
                        action: function(e){

                            if(ipd_id){
                                $.ajax({
                                    type:'POST',
                                    url: baseurl+"app_admin/delete_admit_patient",
                                    data:{ipd_id:ipd_id},
                                    beforeSend:function(){
                                        $btn_loading.button('loading').html('<i class="fa fa-spinner fa-pulse fa-1x fa-fw"></i>  Please Wait..');
                                        jc.buttons.ok.setText("Wait..");
                                    },
                                    success:function(data){
                                        $btn_loading.button('reset');
                                        var data = JSON.parse(data);
                                        jc.buttons.ok.setText("Yes");
                                        if(data.status == 1){
                                            jc.close();
                                            $(parent_row).slideUp(300);
                                        }else{
                                            $.alert(data.message);
                                        }
                                    },
                                    error: function(data){
                                        $btn_loading.button('reset');
                                        $.alert("Sorry something went wrong on server.");
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



        });




        $("head > title").text('IPD');


        $(document).on("click",".history_ward_btn",function(){
            var ipd_id = $(this).attr('ipd-id');
            var $btn_loading = $(this);

            $.ajax({
                type:'POST',
                url: baseurl+"app_admin/get_ward_history",
                data:{ipd_id:ipd_id},
                beforeSend:function(){
                    $btn_loading.button('loading').html('<i class="fa fa-spinner fa-pulse fa-1x fa-fw"></i>  Please Wait..');
                },
                success:function(data){
                    $btn_loading.button('reset');

                    //$("#ward_history").modal('show');
                    //$(".ward_tistory_table_container").html(data);
                    var html = $(data).filter('#ward_history');
                    $(html).modal('show');

                },
                error: function(data){
                    $btn_loading.button('reset');
                    $.alert("Sorry something went wrong on server.");
                }
            });



        });


        $(document).on("click",".update_ward_btn",function(){
            var ipd_id = $(this).attr('ipd-id');
            var $btn_loading = $(this);

            parentTr = $(this).parents("tr");

            $.ajax({
                type:'POST',
                url: baseurl+"app_admin/get_update_ward",
                data:{ipd_id:ipd_id},
                beforeSend:function(){
                    $btn_loading.button('loading').html('<i class="fa fa-spinner fa-pulse fa-1x fa-fw"></i>  Please Wait..');
                },
                success:function(data){
                    $btn_loading.button('reset');
                    var html = $(data).filter('#update_ward_modal');
                    $(html).modal('show');
                },
                error: function(data){
                    $btn_loading.button('reset');
                    $.alert("Sorry something went wrong on server.");
                }
            });
        });



    });
</script>




