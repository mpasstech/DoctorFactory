<?php
$login = $this->Session->read('Auth.User');
?>

<?php  echo $this->Html->css(array('dataTableBundle.css')); ?>
<?php  echo $this->Html->script(array('dataTableBundle.js','jquery.maskedinput-1.2.2-co.min.js','comman.js')); ?>

<style>
    .dashboard_icon_li li {

        text-align: center;
        width: 18%;

    }
    .search_main{
        margin-top: 28px;
        padding: 0px 0px;
        border-bottom: 1px solid;
    }

    .search_main input{
      padding: 6px !important;
    }

    .search_main .form-group div{
        margin-left: 1px !important;
        margin-right: 1px !important;
        padding:0px;
    }
    .action_btn{

        position: absolute;
        float: right;
        right: -35px;
    }
</style>


<div class="Home-section-2">

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="container">
            <?php // echo $this->element('billing_inner_header'); ?>

            <div class="row">
                <!--box 1 -->
                <!--box 1 -->
                <div class="middle-block">
                    <!-- Heading -->
                    <h3 class="screen_title">Patient List</h3>
                    <?php echo $this->element('billing_inner_header'); ?>

                    <div class="col-lg-12 right_box_div">


                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 search_main">
                                <?php echo $this->Form->create('Search',array('type'=>'get','url'=>array('controller'=>'app_admin','action' => 'search_hospital_patient'),'admin'=>true)); ?>
                                    <div class="form-group">
                                        <div class="col-md-1">
                                            <?php echo $this->Form->input('uhid', array('type'=>'text','placeholder' => '', 'label' => 'By UHID', 'class' => 'form-control')); ?>
                                        </div>

                                        <div class="col-md-2">
                                            <?php echo $this->Form->input('name', array('type'=>'text','placeholder' => '', 'label' => 'Search By Name', 'class' => 'form-control')); ?>
                                        </div>
                                        <div class="col-md-2">
                                            <?php echo $this->Form->input('mobile', array('type'=>'text','placeholder' => '', 'label' => 'Search By Mobile', 'class' => 'form-control')); ?>
                                        </div>
                                            <div class="col-md-1">
                                                <?php echo $this->Form->input('from_date', array('type'=>'text','placeholder' => '', 'label' => 'From Date', 'class' => 'from_date form-control')); ?>
                                            </div>
                                            <div class="col-md-1">
                                                <?php echo $this->Form->input('to_date', array('type'=>'text','placeholder' => '', 'label' => 'To Date', 'class' => 'to_date form-control')); ?>
                                            </div>
                                           <div class="col-md-1">
                                               <?php echo $this->Form->input('search_for', array('type' => 'select','empty'=>'All','options'=>array('YES'=>'Admit','NO'=>'Non Admit'),'label' => 'Search For', 'class' => 'form-control')); ?>
                                           </div>

                                        <div class="col-md-2">
                                            <?php echo $this->Form->input('address', array('type'=>'text','placeholder' => '', 'label' => 'Search By Address', 'class' => 'form-control')); ?>
                                        </div>


                                        <div class="col-sm-2 action_btn" >
                                            <div class="input text">
                                                <label style="display: block;">&nbsp;</label>
                                                <?php echo $this->Form->submit('Search',array('div'=>false,'class'=>'btn btn-info','id'=>'search')); ?>
                                                <a class="btn btn-warning" href="<?php echo $this->Html->url(array('controller'=>'app_admin','action'=>'hospital_patient_list')) ?>">Reset</a>

                                            </div>

                                        </div>
                                    </div>
                            <?php echo $this->Form->end(); ?>
                    </div>

                        <div class="Social-login-box payment_bx">
                            <div class="form-group row">

                                <div class="col-sm-12">


                                    <div class="table table-responsive">
                                        <table id="example" class="table table-striped table-bordered" style="width:100%">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Patient Name</th>
                                                <th>UHID</th>
                                                <th>Age</th>
                                                <th>Gender</th>
                                                <th>Address</th>
                                                <th>Mobile</th>
                                                <th>Date</th>
                                                <!--th>Status</th-->
                                                <th >Option</th>
                                            </tr>
                                            </thead>
                                            <tbody>

                                            <?php $counter=0; foreach($data AS $item){

                                                $product_count = count($item['MedicalProductOrder']);
                                                $patient_name = array_key_exists('AppointmentCustomer',$item)?$item['AppointmentCustomer']['first_name']:$item['Children']['child_name'];
                                                $patient_type = array_key_exists('AppointmentCustomer',$item)?'CUSTOMER':'CHILDREN';
                                                $item = array_key_exists('AppointmentCustomer',$item)?$item['AppointmentCustomer']:$item['Children'];
                                                $address = array_key_exists('patient_address',$item)?$item['patient_address']:$item['address'];


                                                ?>

                                            <tr class="table_row">
                                                <td><?php echo ++$counter; ?>.</td>
                                                <td class="table_pat_name"><?php echo $patient_name; ?></td>
                                                <td><?php echo $item['uhid']; ?></td>
                                                <td><?php

                                                    if($patient_type == "CHILDREN"){
                                                        echo $this->AppAdmin->getAgeStringFromDob($item['dob']);

                                                    }else{
                                                        if(!empty($item['dob']) && $item['dob'] != '1970-01-01'){
                                                            echo $this->AppAdmin->getAgeStringFromDob($item['dob']);
                                                        }else{
                                                            echo $item['age'];

                                                        }
                                                    }


                                                    ?></td>
                                                <td class="table_pat_gender"><?php echo $item['gender']; ?></td>
                                                <td class="table_pat_address"><?php echo trim($address); ?></td>
                                                <td class="table_pat_mobile"><?php echo $item['mobile']; ?></td>
                                                <td class=""><?php echo date('d-m-Y',strtotime($item['created'])); ?></td>
                                                <!--td>
                                                    <button type="button" class="btn btn-success btn-xs admit_btn" data-type= '<?php echo $patient_type; ?>' data-status = "<?php echo $item['is_admit']; ?>" data-id="<?php echo base64_encode($item['id']); ?>"><?php echo ($item['is_admit']=='YES')?'Admit':'Non Admit'; ?></button>

                                                </td-->
                                                <td>
                                                    <?php if($login['USER_ROLE'] !='RECEPTIONIST' && $login['USER_ROLE'] !='DOCTOR' && $login['USER_ROLE'] !='STAFF' && $login['USER_ROLE'] !='LAB' && $login['USER_ROLE'] !='PHARMACY'){ ?>

                                                    <button type="button" class="btn btn-warning btn-xs edit_btn" data-type= '<?php echo $patient_type; ?>' data-id="<?php echo base64_encode($item['id']); ?>"><i class="fa fa-edit"></i> Edit </button>
                                                    <?php } ?>
                                                    <?php  if($item['uhid'] != '' && $product_count > 0 ){ ?>
                                                        <a href="<?php echo Router::url('/app_admin/add_hospital_receipt_search?u='.base64_encode($item['uhid']),true); ?>" target="_blank" class="btn btn-info btn-xs receipt_btn" ><i class="fa fa-edit"></i> Add Receipt </a>

                                                        <a href="<?php echo Router::url('/app_admin/hospital_patient_invoice_list/'.base64_encode($item['uhid']),true); ?>" target="_blank" class="btn btn-success btn-xs receipt_btn" ><i class="fa fa-list"></i> Receipt </a>
                                                        <a href="<?php echo Router::url('/app_admin/get_patient_history/'.base64_encode($item['thinapp_id']).'/'.base64_encode($item['uhid']),true); ?>" target="_blank" class="btn btn-info btn-xs receipt_btn history_btn" ><i class="fa fa-list"></i> History </a>
                                                    <?php }?>

                                                </td>
                                            </tr>
                                            <?php  } ?>

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
<div class="modal fade" id="patient_edit_modal" role="dialog"></div>
<div class="modal fade" id="patient_history_modal" role="dialog">

    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">History</h4>
            </div>
            <div class="modal-body" id="modal_body">
                <iframe id="ifram" src="https://www.w3schools.com"></iframe>
            </div>
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
    #ifram {

        width: 100%;
        border: none;
        height: 90%;

    }



   #patient_history_modal > .modal-dialog.modal-sm {

        height: 90%;

    }
    .modal-content {

        height: 100%;

    }
    #modal_body {

        height: 100%;

    }
    .modal-header {
        background: #03a9f5;
        color: #FFFFFF;
    }

</style>
<script>
    $(document).ready(function () {

        var table_obj ;

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
                        columns: [1,2,3,4,5,6,7,8]
                    }

                },
                {
                    extend: 'csv',
                    header: true,
                    messageTop: '<?php echo $reportTitle; ?>',
                    title: '',
                    exportOptions: {
                        columns: [1,2,3,4,5,6,7,8]
                    }

                },
                {
                    extend: 'excel',
                    header: true,
                    messageTop: '<?php echo $reportTitle; ?>',
                    title: '',
                    exportOptions: {
                        columns: [1,2,3,4,5,6,7,8]
                    }

                },
                {
                    extend: 'pdf',
                    header: true,
                    messageTop: '<?php echo $reportTitle; ?>',
                    title: '',
                    exportOptions: {
                        columns: [1,2,3,4,5,6,7,8]
                    }

                },
                {
                    extend: 'print',
                    header: true,
                    messageTop: '<?php echo $reportTitle; ?>',
                    title: '',
                    exportOptions: {
                        columns: [1,2,3,4,5,6,7,8]
                    }

                }
            ]
        });

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
                        $("#myModalForm").html(data);
                        $("#myModalForm").modal("show");
                        setTimeout(function(){
                            $("#opdCharge").focus();
                        },1500);

                    },
                    error: function (data) {
                        $btn.button('reset');
                        alert("Sorry something went wrong on server.");
                    }
                });

        });

        $(document).on('change','input[list]',function () {
            var optionFound = false,
                datalist = this.list;
            var selected;
            // Determine whether an option exists with the current value of the input.
            for (var j = 0; j < datalist.options.length; j++) {
                if (this.value == datalist.options[j].value) {
                    optionFound = true;
                    selected = datalist.options[j];
                    break;
                }
            }
            // use the setCustomValidity function of the Validation API
            // to provide an user feedback if the value does not exist in the datalist
            if (optionFound) {
                this.setCustomValidity('');
                var productID = $(selected).attr('productID');
                var productPrice = $(selected).attr('productPrice');

                $(this).next('input[name="productID[]"]').val(productID);
                $(this).parent().nextAll().find('input[name="price[]"]').val(productPrice);

                var quantity = $(this).parent().nextAll().find('input[name="quantity[]"]').val();
                var discount = $(this).parent().nextAll().find('input[name="discount[]"]').val();
                var discountType = $(this).parent().nextAll().find('select[name="discountType[]"]').val();

                var amount = ( parseInt(productPrice) * parseInt(quantity) );
                if(discountType == 'PERCENTAGE')
                {
                    var discountVal = amount*(parseInt(discount)/100);
                }
                else
                {
                    var discountVal = parseInt(discount);
                }
                if(discountVal > amount)
                {
                    discountVal = amount;
                }
                var totalAmount = Math.round(amount - discountVal);

                $(this).parent().nextAll().find('input[name="amount[]"]').val(totalAmount);




            } else {
                this.setCustomValidity('Please select a valid value.');
            }
        });

        $(document).on('click','.removeRow',function () {
            $(this).parents('.addedRow').remove();
        });

        $(document).on('click','#addMore',function () {
            var htmlToAppend = '<div class="row addedRow"><div class="input number col-md-3"><label for="opdCharge">Product</label><input type="text" name="productName[]" class="form-control" placeholder="Product" list="text_editors" required="true"><input type="hidden" name="productID[]" required="true"></div><div class="input number col-md-2"><label for="opdCharge">Quantity</label><input name="quantity[]" class="form-control" min="1" value="1" placeholder="Quantity" required="true" type="number"></div><div class="input number col-md-2"><label for="opdCharge">Discount</label><input name="discount[]" class="form-control" min="0" value="0" placeholder="Discount" type="number"></div><div class="input number col-md-2"><label for="opdCharge">Discount Type</label><select name="discountType[]" class="form-control"><option value="PERCENTAGE">Percentage</option><option value="AMOUNT">Amount</option></select></div><div class="input number col-md-1"><label for="opdCharge">Price</label><input type="text" readonly="readonly" name="price[]" class="form-control" value="0" placeholder="Price"></div><div class="input number col-md-1"><label for="opdCharge">Amount</label><input type="text" readonly="readonly" name="amount[]" class="form-control" value="0" placeholder="Amount"></div><div class="input col-md-1"><button type="button" class="close removeRow"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button></div></div>';
            $(".addProductAppend").append(htmlToAppend).find('input[name="productName[]"]').last().focus();
        });

        $(document).on('keyup keypress blur change','#opdCharge',function () {
            $("#amountOPD").val($(this).val());
            $("#priceOPD").val($(this).val());
        });

        $(document).on('keyup keypress blur change',"input[name='productID[]'], input[name='quantity[]'], input[name='discount[]'], select[name='discountType[]']",function () {
            $("input[list]").trigger('change');
        });

        $(document).on('submit','#formPay',function(e){
            e.preventDefault();
            var data = $( this ).serialize();


            var $btn = $("#addPaySubmit");
            $.ajax({
                type:'POST',
                url: baseurl+"app_admin/web_edit_order",
                data:data,
                beforeSend:function(){
                    $btn.attr('disabled',true);
                },
                success:function(data){
                    console.log(data);
                    var response = JSON.parse(data)
                    if(response.status == 1)
                    {
                        $btn.attr('disabled',false);
                        $("#myModalForm").modal("hide");
                        if($(".doctor_drp").length > 0){
                            $("#address").trigger('change');
                        }else{
                            $(".slot_date").trigger('changeDate');
                        }

                        var ID = $("#idContainer").val();
                        var win = window.open(baseurl+"app_admin/print_invoice/"+btoa(ID), '_blank');
                        if (win) {
                            win.focus();
                            location.reload();
                        } else {
                            alert('Please allow popups for this website');
                        }
                    }
                    else
                    {
                        $btn.attr('disabled',false);
                        alert("Sorry something went wrong on server.");
                    }


                },
                error: function(data){
                    $btn.attr('disabled',false);
                    alert("Sorry something went wrong on server.");
                }
            });

        });

        $("head > title").text("Report_<?php echo date("d-M-Y"); ?>");

        $(".from_date, .to_date").datepicker({clearBtn:true,format: 'dd/mm/yyyy',autoclose:true,orientation: "bottom auto",endDate: new Date()});

        $("#SearchMonth1").datepicker({
        format: 'mm/yyyy',
        viewMode: "months",
        minViewMode: "months",
        autoclose:true,
        orientation: "bottom auto",
        endDate: new Date()
    });

        $(".resteButton").click(function(e){
        e.preventDefault();
        $("#SearchUhid").val("");
        $("#SearchName").val("");
        $("#SearchMobile").val("");
        $("#SearchDate").val("");
        $("#SearchMonth1").val("");
        $("#SearchSearchFor").val("");
        $("#SearchAddress").val("");
    });

        $("#SearchMonth1").mask("99/9999", {placeholder: 'mm/yyyy'});

        $(document).on('click','.admit_btn',function(e){
            var pat_id = $(this).attr('data-id');
            var pat_type = $(this).attr('data-type');
            var status = $(this).attr('data-status');

            var display ='';
            if(status == "YES"){
                status = 'NO';
                display ='Non Admit';
            }else{
                status = 'YES';
                display = 'Admit';
            }

            var thisButton = $(this);
            $.ajax({
                url: baseurl+'/app_admin/hospital_change_admit_status',
                data:{pat_id:pat_id,pat_type:pat_type,status:status},
                type:'POST',
                beforeSend:function(){
                    $(thisButton).button('loading').html('Updating...');
                },
                success: function(result){
                    $(thisButton).button('reset');
                    var result = JSON.parse(result);
                    if(result.status == 1)
                    {
                        $(thisButton).text(display);
                        thisButton.attr('data-status',status);
                    }
                    else
                    {
                        thisButton.removeClass('btn-success').addClass('btn-danger',status);
                        alert('Sorry, Could not change status!');
                    }
                }
            });
        });

        var table_obj = $("#patient_edit_modal").attr('data-object');

        $(document).on('click','.edit_btn',function(e){
            var pat_id = $(this).attr('data-id');
            var pat_type = $(this).attr('data-type');


            var thisButton = $(this);
            $.ajax({
                url: baseurl+'/app_admin/hospital_load_patient_detail',
                data:{pat_id:pat_id,pat_type:pat_type},
                type:'POST',
                beforeSend:function(){
                    $(thisButton).button('loading').html('Loading...');
                },
                success: function(result){
                    $(thisButton).button('reset');
                    setObject(thisButton);
                    $("#patient_edit_modal").html(result).modal('show');
                }
            });
        });

        $(document).on('click','.history_btn',function(e){
            e.preventDefault();
            var url = $(this).attr('href');
            $("#ifram").attr("src",url);
            $("#patient_history_modal").modal('show');
            /*var thisButton = $(this);
            $.ajax({
                url: url,
                type:'GET',
                beforeSend:function(){
                    $(thisButton).button('loading').html('Loading...');
                },
                success: function(result){
                    $(thisButton).button('reset');
                    $("#modal_body").html(result);

                }
            });*/


        })

    });




</script>