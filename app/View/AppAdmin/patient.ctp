<?php
$login = $this->Session->read('Auth.User');


$country = $this->AppAdmin->getAllCountryList();
$state = $this->AppAdmin->getAllStateList();
$city = $this->AppAdmin->getAllCityList();





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
                    <h3 class="screen_title">All Patient List</h3>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 custom_form_box">

                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 hospital_search_box">
                            <div class="row">
                                <?php echo $this->element('app_admin_inner_tab_patient'); ?>
								<?php echo $this->element('message'); ?>
                            </div>
                            <div class="row search_main">
                                <?php echo $this->Form->create('Search',array('type'=>'get','url'=>array('controller'=>'app_admin','action' => 'search_patient'),'admin'=>true)); ?>
                                <div class="form-group">
                                    <div class="col-md-2">
                                        <?php echo $this->Form->input('uhid', array('type'=>'text','placeholder' => '', 'label' => 'By UHID', 'class' => 'form-control')); ?>
                                    </div>

                                    <div class="col-md-2">
                                        <?php echo $this->Form->input('name', array('type'=>'text','placeholder' => '', 'label' => 'Search By Name', 'class' => 'form-control')); ?>
                                    </div>
                                    <div class="col-md-2">
                                        <?php echo $this->Form->input('mobile', array('type'=>'text','placeholder' => '', 'label' => 'Search By Mobile', 'class' => 'form-control')); ?>
                                    </div>

                                  <!--  <div class="col-md-2">
                                        <?php /*echo $this->Form->input('tag', array('type'=>'text','placeholder' => '', 'label' => 'Search By Tag', 'class' => 'form-control tag')); */?>
                                    </div>
-->

                                    <div class="col-md-2">
                                        <?php echo $this->Form->input('country', array('type'=>'text','placeholder' => '', 'label' => 'Search By Country', 'class' => 'form-control country')); ?>
                                    </div>

                                    <div class="col-md-2">
                                        <?php echo $this->Form->input('state', array('type'=>'text','placeholder' => '', 'label' => 'Search By State', 'class' => 'form-control state')); ?>
                                    </div>

                                    <div class="col-md-2">
                                        <?php echo $this->Form->input('city', array('type'=>'text','placeholder' => '', 'label' => 'Search By City', 'class' => 'form-control city')); ?>
                                    </div>



                                    <div class="col-sm-2 action_btn" >
                                        <div class="input text">
                                            <label style="display: block;">&nbsp;</label>
                                            <?php echo $this->Form->submit('Search',array('div'=>false,'class'=>'btn btn-info','id'=>'search')); ?>
                                            <a class="btn btn-warning" href="<?php echo $this->Html->url(array('controller'=>'app_admin','action'=>'patient')) ?>">Reset</a>

                                        </div>

                                    </div>
                                </div>
                                <?php echo $this->Form->end(); ?>
                            </div>
                        </div>

                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 hospital_search_box">
                            <div class="form-group row">

                                <div class="col-sm-12">


                                    <div class="table table-responsive">
                                        <table id="example" class="table table-striped table-bordered" style="width:100%">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Patient Name</th>
                                                <th>Mobile</th>
                                                <th>MEngage UHID</th>
                                                <th>UHID</th>
                                                <th>Age</th>
                                                <th>Gender</th>
                                                <th>Address</th>
                                                <th>City</th>
                                                <th>Medical History</th>
                                                <th >Option</th>
                                            </tr>
                                            </thead>
                                            <tbody>

                                            <?php $counter=0; foreach($data_list AS $item){

                                                $product_count = 0;


                                                ?>

                                                <tr class="table_row">
                                                    <td><?php echo ++$counter; ?>.</td>
                                                    <td class="table_pat_name"><?php echo $item['patient_name']; ?></td>
                                                    <td class="table_pat_mobile" ><?php echo $item['mobile']; ?></td>
                                                    <td><?php echo $item['uhid']; ?></td>
                                                    <td><?php echo $item['third_party_uhid']; ?></td>
                                                    <td><?php echo $item['age']; ?></td>
                                                    <td class="table_pat_gender"><?php echo $item['gender']; ?></td>
                                                    <td title="<?php echo $item['address']; ?>"><?php echo mb_strimwidth($item['address'], 0, 100, '...') ;?></td>
                                                    <td ><?php echo $item['city_name']; ?></td>
                                                    <td ><?php echo $item['medical_history']; ?></td>

                                                    <td style="display: flex;">
                                                        <?php echo html_entity_decode($item['action_btn']); ?>



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
        <div class="modal-content" style="width: 500px;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">History</h4>
            </div>
            <div class="modal-body" id="modal_body" style="padding: 0px;">
                <iframe id="ifram" src="https://www.w3schools.com"></iframe>
            </div>
        </div>
    </div>
</div>


<style>
    #ifram {

        width: 100%;
        border: none;
        height: 90%;

    }
    .form-control {

        height: 35px;

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

<style>

    .col-md-2{
        width: 11%;
    }
    .col-md-2 .form-control {
        width: 100%;
        height: 30px;
    }

    .action_btn .btn {

        padding: 3px 12px !important;
    }


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
</style>
<script>
    $(document).ready(function () {


        $(document).on("click",".delete_patient",function(){
            if(confirm("Do you want to delete this patient?"))
            {
                var patientType = $(this).attr("data-pt");
                var patientID = $(this).attr("data-pi");




                var $btn = $(this);
                var obj = $(this);
                $.ajax({
                    type: 'POST',
                    url: baseurl + "app_admin/inactive_patient_status",
                    data: {patientType: patientType,patientID:patientID},
                    beforeSend: function () {
                        $btn.button('loading').html('Wait..')
                    },
                    success: function (data) {
                        $btn.button('reset');

                        data = JSON.parse(data)

                        if(data.status == '0')
                        {
                            alert("Sorry something went wrong on server.");
                        }
                        else
                        {
                            $(obj).closest('tr').remove();
                        }

                    },
                    error: function (data) {
                        $btn.button('reset');
                        alert("Sorry something went wrong on server.");
                    }
                });
            }
        });

        var table_obj ;
        var total = "<?php echo $total_record; ?>";



        $(document).on('click','.history_btn',function(e){
            e.preventDefault();
            var url = $(this).attr('href');
            $("#ifram").attr("src",url);
            $("#patient_history_modal").modal('show');
        });


        $( ".country" ).autocomplete({
            source: (<?php echo json_encode($country); ?>),
            delay: 100,
            classes: {
                "ui-autocomplete": "highlight"
            },
            select: function( event, ui ) {
                $('.country').val(ui.item.label);
                return false;
            }
        });


        $( ".state" ).autocomplete({
            source: (<?php echo json_encode($state); ?>),
            delay: 100,
            classes: {
                "ui-autocomplete": "highlight"
            },
            select: function( event, ui ) {
                $('.state').val(ui.item.label);
                return false;
            }
        });


        $( ".city" ).autocomplete({
            source: (<?php echo json_encode($city); ?>),
            delay: 100,
            classes: {
                "ui-autocomplete": "highlight"
            },
            select: function( event, ui ) {
                $('.city').val(ui.item.label);
                return false;
            }
        });



        $(".channel_tap a").removeClass('active');
        $("#v_app_subscriber_list").addClass('active');

        $('#example').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "data": function(){
                    $('#example').DataTable().ajax.url(
                        baseurl + "app_admin/patient/"+total+"/?"+window.location.search.substring(1)
                    );
                }
            },
            "deferLoading": '<?php echo $total_record; ?>',
            dom: 'Blfrtip',
            lengthMenu: [
                [ 10, 25, 50, 100, 300, 500, 700, 1000 ],
                [ '10 rows', '25 rows', '50 rows', '100 rows', '300 rows', '500 rows', '700 rows', '1000 rows']
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
                        columns: [0,1,2,3,4,5,6,7,8,9]
                    }

                },
                {
                    extend: 'csv',
                    header: true,
                    messageTop: '<?php echo $reportTitle; ?>',
                    title: '',
                    exportOptions: {
                        columns: [0,1,2,3,4,5,6,7,8,9]
                    }

                },
                {
                    extend: 'excel',
                    header: true,
                    messageTop: '<?php echo $reportTitle; ?>',
                    title: '',
                    exportOptions: {
                        columns: [0,1,2,3,4,5,6,7,8,9]
                    }

                },
                {
                    extend: 'pdf',
                    header: true,
                    messageTop: '<?php echo $reportTitle; ?>',
                    title: '',
                    exportOptions: {
                        columns: [0,1,2,3,4,5,6,7,8,9]
                    }

                },
                {
                    extend: 'print',
                    header: true,
                    messageTop: '<?php echo $reportTitle; ?>',
                    title: '',
                    exportOptions: {
                        columns: [0,1,2,3,4,5,6,7,8,9]
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

        $("head > title").text('My Patient');
    });




</script>
<style>
    .col-md-1, .col-md-2{
        padding: 0px 3px !important;
    }
    .form-control{
        padding: 6px;
    }
</style>