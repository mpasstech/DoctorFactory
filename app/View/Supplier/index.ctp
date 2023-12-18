<?php  echo $this->Html->css(array('dataTableBundle.css')); ?>
<?php  echo $this->Html->script(array('dataTableBundle.js','jquery.maskedinput-1.2.2-co.min.js','comman.js')); ?>

<?php
$login = $this->Session->read('Auth.User');
?>

<div class="Home-section-2">

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="container">
            <div class="row">
                <!--box 1 -->
                <!--box 1 -->
                <div class="middle-block">
                    <!-- Heading -->
                    <!--left sidebar-->

                    <?php // echo $this->element('admin_leftsidebar'); ?>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                        <div class="progress-bar channel_tap">

                            <a id="v_add_channel" style="width:100%;" href="#"><i class="fa fa-television"></i> List Order</a>


                        </div>


                        <div class="Social-login-box payment_bx">

                            <?php echo $this->element('message'); ?>



                            <div class="form-group row">
                                <div class="col-sm-12">


                                    <div class="table table-responsive">
                                        <table id="example" class="table table-striped table-bordered" style="width:100%">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Order Number</th>
                                                <th>Patient Name</th>
                                                <th>Date</th>
                                                <th>Expected Delivery Date</th>
                                                <th>Status</th>
                                                <th>Option</th>
                                            </tr>
                                            </thead>
                                            <tbody>

                                            <?php $counter = 1; $total_amount = 0; $total = 0; foreach($orderList AS $item){ ?>

                                                <tr>
                                                    <td><?php echo $counter; ?>.</td>
                                                    <td><?php echo $item['SupplierOrder']['order_no']; ?></td>
                                                    <td><?php echo $item['SupplierOrder']['patient_name']; ?></td>
                                                    <td><?php echo date("d/m/Y",strtotime($item['SupplierOrder']['date'])); ?></td>
                                                    <td><?php echo date("d/m/Y",strtotime($item['SupplierOrder']['expected_delivery_date'])); ?></td>
                                                    <td class="status_cls">
                                                        <span id="<?php echo $item['SupplierOrder']['id']; ?>"><?php echo $item['SupplierOrder']['status_from_supplire']; ?></span><br>
                                                        <button type="button" id="viewStatusHistory" class="viewStatusHistory btn btn-primary btn-xs" data-thinapp-id="<?php echo $item['SupplierOrder']['thinapp_id']; ?>"  data-id="<?php echo $item['SupplierOrder']['id']; ?>"  ><i class="fa fa-eye fa-xx"></i>&nbsp;Status Description</button>
                                                    </td>
                                                    <td>
                                                        <br>
                                                        <a href="<?php echo Router::url('/supplier/get_order_details/'.base64_encode($item['SupplierOrder']['id']),true); ?>" target="_blank" class="btn btn-primary btn-xs"  ><i class="fa fa-eye fa-xx"></i>&nbsp;View Placed Order</a>
                                                    </td>
                                                </tr>
                                                <?php $counter++; } ?>
                                            </tbody>
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

<style>
    .status_cls {
        text-align: center;
    }
    #example_length {
        width: 32%;
        text-align: right;
    }
    .modal-footer {

        border-top: none;

    }
</style>

<div class="modal fade" id="editModal" role="dialog">

    <div class="modal-dialog modal-sm">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Status</h4>
            </div>
            <div class="modal-body">
                <div class="table-responsive history">

                </div>
            </div>
            <div class="modal-footer">

            </div>

        </div>
    </div>

</div>


<script>
    $(document).ready(function () {

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
                    footer: true,
                    messageTop: '',
                    title: '',
                    exportOptions: {
                        columns: [0,1,2,3,4,5,6]
                    }

                },
                {
                    extend: 'csv',
                    header: true,
                    footer: true,
                    messageTop: '',
                    title: '',
                    exportOptions: {
                        columns: [0,1,2,3,4,5,6]
                    }

                },
                {
                    extend: 'excel',
                    header: true,
                    footer: true,
                    messageTop: '',
                    title: '',
                    exportOptions: {
                        columns: [0,1,2,3,4,5,6]
                    }

                },
                {
                    extend: 'pdf',
                    header: true,
                    footer: true,
                    messageTop: '',
                    title: '',
                    exportOptions: {
                        columns: [0,1,2,3,4,5,6]
                    }

                },
                {
                    extend: 'print',
                    header: true,
                    footer: true,
                    messageTop: '',
                    title: '',
                    exportOptions: {
                        columns: [0,1,2,3,4,5,6]
                    }

                }
            ]
        });

        $(".viewStatusHistory").click(function(){
            var $btn = $(this);
            var orderID = $(this).attr("data-id");
            var thinappID = $(this).attr("data-thinapp-id");
            $.ajax({
                url: baseurl+'/supplier/get_order_status_history',
                data:{orderID:orderID,thinappID:thinappID},
                type:'POST',
                beforeSend:function(){
                    $btn.button('loading').html('<i class="fa fa-spinner fa-pulse fa-2x fa-fw">');
                },
                success: function(result){
                    $btn.button('reset');
                    $(".history").html(result);
                    $("#editModal").modal("show");
                }
            });
        });

    });
</script>