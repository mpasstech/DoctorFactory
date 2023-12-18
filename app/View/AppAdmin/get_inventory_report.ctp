<?php
$login = $this->Session->read('Auth.User');
?>
<?php  echo $this->Html->css(array('dataTableBundle.css')); ?>
<?php  echo $this->Html->script(array('dataTableBundle.js','jquery.maskedinput-1.2.2-co.min.js','comman.js')); ?>

<?php echo $this->Html->css(array('magicsuggest-min.css')); ?>
<?php echo $this->Html->script(array('magicsuggest-min.js')); ?>


<div class="Home-section-2">

    <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1">
        <?php echo $this->element('inventory_pharma_billing_setting_inner_header'); ?>
    </div>

    <div class="col-lg-11 col-md-11 col-sm-11 col-xs-11">
        <div class="container">
            <div class="row">
                <!--box 1 -->
                <!--box 1 -->


                <div class="middle-block">
                    <!-- Heading -->
                    <h3 class="screen_title">Inventory Report</h3>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 custom_form_box">



                        <?php echo $this->element('message'); ?>


                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 hospital_search_box">

                            <div class="row search_main">
                                <?php echo $this->Form->create('Search',array('type'=>'get','url'=>array('controller'=>'app_admin','action' => 'search_inventory_report'))); ?>
                                <div class="form-group">
                                    <div class="col-md-3">
                                        <?php echo $this->Form->input('medical_product_id', array('type'=>'text', 'label' => 'Product/Service','placeholder'=>'Product/Service', "value"=>"1", 'id'=>'medical_product_id', 'class' => 'form-control')); ?>
                                    </div>

                                    <div class="col-md-3">
                                        <?php echo $this->Form->input('expiry_from', array('type'=>'text', 'label' => 'Expiry From','placeholder'=>'Expiry From', 'class' => 'form-control from_date')); ?>
                                    </div>

                                    <div class="col-md-3">
                                        <?php echo $this->Form->input('expiry_to', array('type'=>'text', 'label' => 'Expiry To','placeholder'=>'Expiry To', 'class' => 'form-control to_date')); ?>
                                    </div>

                                    <div class="col-sm-3 action_btn" >
                                        <div class="input text">
                                            <label style="display: block;">&nbsp;</label>
                                            <?php echo $this->Form->submit('Search',array('div'=>false,'class'=>'btn btn-info','id'=>'search')); ?>
                                            <a class="btn btn-warning resteButton" href="<?php echo $this->Html->url(array('controller'=>'app_admin','action'=>'get_inventory_report')) ?>">Reset</a>

                                        </div>

                                    </div>
                                </div>
                                <?php echo $this->Form->end(); ?>
                            </div>
                        </div>

                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 hospital_table_box">


                            <div class="row">
                                <?php if(!empty($dataToSend)){ ?>
                                    <div class="table-responsive">

                                        <table id="example" class="table">
                                            <thead>
                                            <tr>
                                                <th></th>
                                                <th>#</th>
                                                <th>Name</th>
                                                <th>Expiry</th>
                                                <th>Batch No.</th>
                                                <th>Total Quantity</th>
                                                <th>Sold Quantity</th>
                                                <th>Dead</th>
                                                <th>Remaining Quantity</th>
                                                <th>Created</th>
                                                <th>Purchase Price</th>
                                                <th>MRP</th>
                                                <th>Status</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php foreach ($dataToSend as $key => $list){
                                                ?>
                                                <tr>
                                                    <td <?php if($list[0]['total'] > 1){ ?>class="add_row"<?php } ?> pro-id="<?php echo $list['MedicalProduct']['id']; ?>"  pro-qty-id="<?php echo $list['MedicalProductQuantity']['id']; ?>"><?php if($list[0]['total'] > 1){ ?><i class="fa-plus fa"></i><?php } ?></td>
                                                    <td><?php echo $key+1; ?></td>
                                                    <td><?php echo $list['MedicalProduct']['name']; ?>(<?php echo $list['MedicalProduct']['medicine_form']; ?>)</td>
                                                    <td>
                                                        <?php
                                                            if($list['MedicalProductQuantity']['expiry_date'] != '0000-00-00')
                                                            {
                                                                echo date('d/m/Y',strtotime($list['MedicalProductQuantity']['expiry_date']));
                                                            }
                                                            else
                                                            {
                                                                echo "Not Available";
                                                            }
                                                        ?>
                                                    </td>
                                                    <td><?php echo $list['MedicalProductQuantity']['batch']; ?></td>
                                                    <td><?php echo $list['MedicalProductQuantity']['quantity']; ?></td>
                                                    <td><?php echo $list['MedicalProductQuantity']['sold']; ?></td>
                                                    <td><span class="anchor" data-id="<?php echo $list['MedicalProductQuantity']['id']; ?>"><?php echo $list['MedicalProductQuantity']['dead']; ?></span></td>
                                                    <td><?php echo $list['MedicalProductQuantity']['quantity']-($list['MedicalProductQuantity']['dead']+$list['MedicalProductQuantity']['sold']); ?></td>
                                                    <td><?php echo date('d/m/Y',strtotime($list['MedicalProductQuantity']['created'])); ?></td>
                                                    <td><?php echo $list['MedicalProductQuantity']['purchase_price']; ?></td>
                                                    <td><?php echo $list['MedicalProductQuantity']['mrp']; ?></td>
                                                    <td><?php echo $list['MedicalProductQuantity']['status']; ?></td>
                                                </tr>
                                            <?php } ?>
                                            </tbody>
                                        </table>

                                    </div>
                                <?php }else{ ?>

                            </div>
                            <div class="no_data">
                                <h2>No records found!</h2>
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


<div class="modal fade" id="dead_modal" role="dialog"></div>

<div class="modal fade" id="edit_dead_modal" role="dialog"></div>

<style>
    #example_length {
        width: 32%;
        text-align: right;
    }
    .anchor{
        color: red;
        text-decoration: none;
        text-transform: uppercase;
        cursor: pointer;
    }
    .add_row{cursor:pointer;}
    .remove_row{cursor:pointer;}
</style>


<script>
    $(document).ready(function(){


        $('#example').DataTable({
            "order": [[ 1, "asc" ]],
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
                        columns: [1,2,3,4,5,6,7,8,9,10,11]
                    }

                },
                {
                    extend: 'csv',
                    header: true,
                    messageTop: '<?php echo $reportTitle; ?>',
                    title: '',
                    exportOptions: {
                        columns: [1,2,3,4,5,6,7,8,9,10,11]
                    }

                },
                {
                    extend: 'excel',
                    header: true,
                    messageTop: '<?php echo $reportTitle; ?>',
                    title: '',
                    exportOptions: {
                        columns: [1,2,3,4,5,6,7,8,9,10,11]
                    }

                },
                {
                    extend: 'pdf',
                    header: true,
                    messageTop: '<?php echo $reportTitle; ?>',
                    title: '',
                    exportOptions: {
                        columns: [1,2,3,4,5,6,7,8,9,10,11]
                    }

                },
                {
                    extend: 'print',
                    header: true,
                    messageTop: '<?php echo $reportTitle; ?>',
                    title: '',
                    exportOptions: {
                        columns: [1,2,3,4,5,6,7,8,9,10,11]
                    }

                }
            ]
        });

        $(".from_date, .to_date").datepicker({clearBtn:true,format: 'dd/mm/yyyy',autoclose:true,orientation: "bottom auto"});



        $("head > title").text('Inventory Report');













    });
    $(document).on('click','.anchor',function(){
        var qtyID = $(this).attr('data-id');
        var btn = $(this);

        $.ajax({
            url:baseurl+"/app_admin/get_medical_product_dead_list",
            type:'POST',
            data:{
                qty_id:qtyID
            },
            success:function(res){

                $('#dead_modal').html(res);
                $('#dead_modal').modal('show');

            },
            error:function () {
            }
        });


    });
    var ms = $('#medical_product_id').magicSuggest({
        allowFreeEntries:false,
        allowDuplicates:false,
        data:<?php echo json_encode($productList,true); ?>,
        maxDropHeight: 345,
        maxSelection: 1,
        required: true,
        noSuggestionText: 'No result matching the term {{query}}',
        useTabKey: true
    });
    $(ms).on('blur', function(c,e){
        var selecteion = e.getSelection();
        if(!selecteion[0])
        {
            e.empty();
        }
    });
    var proID1 = 0;
    $(document).on("click",".add_row",function(){
        var qtyID = $(this).attr('pro-qty-id');
        var proID = $(this).attr('pro-id');
        if(proID1 == proID){ return false; }else{ proID1 = proID; }
        var $this = $(this);
        $.ajax({
            url:baseurl+"/app_admin/get_medical_product_qty_list",
            type:'POST',
            data:{
                qtyID:qtyID,
                proID:proID,
                type:'pharma'
            },
            success:function(res){
                $($this).closest('tr').after(res);
                $($this).removeClass("add_row");
                $($this).addClass("remove_row");
                $($this).html("<i class='fa-minus fa'></i>");
            },
            error:function () {
            }
        });
    });
    $(document).on("click",".remove_row",function(){
        proID1 = 0;
        $('.added_row').remove();
        $(".remove_row").removeClass("remove_row").html("<i class='fa-plus fa'></i>").addClass("add_row");
    });
</script>





