<?php
$login = $this->Session->read('Auth.User.User');
$doctor_list = $this->AppAdmin->getHospitalDoctorList($login['thinapp_id']);

?>
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
                        <?php echo $this->element('app_admin_supplires'); ?>


                        <div class="Social-login-box payment_bx">

                            <?php echo $this->element('message'); ?>



                            <div class="form-group row">
                                <div class="col-sm-12">


                                    <div class="table table-responsive">
                                        <table id="example" class="table table-striped table-bordered" style="width:100%">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Name</th>
                                                <th>Tel</th>
                                                <th>Mobile</th>
                                                <th>WhatsApp</th>
                                                <th>Email</th>
                                                <th>Address</th>
                                                <th>Status</th>
                                                <th>Option</th>
                                            </tr>
                                            </thead>
                                            <tbody>

                                            <?php $counter = 1; $total_amount = 0; $total = 0; foreach($supplierData AS $item){ ?>

                                                <tr>
                                                    <td><?php echo $counter; ?>.</td>
                                                    <td><?php echo ucwords( $item['SupplierHospital']['name']); ?></td>
                                                    <td><?php echo $item['SupplierHospital']['tel']; ?></td>
                                                    <td><?php echo $item['SupplierHospital']['mobile']; ?></td>
                                                    <td><?php echo $item['SupplierHospital']['whatsapp_mobile']; ?></td>
                                                    <td><?php echo $item['SupplierHospital']['email']; ?></td>
                                                    <td><?php echo $item['SupplierHospital']['address']; ?></td>
                                                    <td><?php echo $item['SupplierHospital']['status']; ?></td>
                                                    <td>
                                                        <button type="button" id="editSupplier" class="editSupplier btn btn-primary btn-xs" data-id="<?php echo $item['SupplierHospital']['id']; ?>"  ><i class="fa fa-edit fa-2x"></i></button>
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
                <h4 class="modal-title">Edit Supplier</h4>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <form id="editForm" method="POST">

                        <div class="form-group">
                            <div class="col-sm-12">
                                <input type="text" name="name" id="nameHolder" placeholder="Supplier Name*" class="form-control cnt" required>
                                <input type="hidden" name="id" id="IDholder">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <input type="text" name="tel" id="telHolder" placeholder="Supplier Telephone" class="form-control cnt" >
                            </div>
                        </div>
                        <!--div class="form-group">
                            <div class="col-sm-12">
                                <input type="text" name="mobile" id="mobileHolder" placeholder="Supplier Mobile*" class="form-control cnt" required>
                            </div>
                        </div-->
                        <div class="form-group">
                            <div class="col-sm-12">
                                <input type="text" name="whatsapp_mobile" id="whatsappMobileHolder" placeholder="Supplier Whatsapp" class="form-control cnt" >
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <input type="email" name="email" id="emailHolder" placeholder="Supplier Email" class="form-control cnt" >
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <input type="text" name="address" id="addressHolder" placeholder="Supplier Address" class="form-control cnt" >
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <select name="status" id="statusHolder" class="form-control cnt" required>
                                    <option value="ACTIVE">Active</option>
                                    <option value="INACTIVE">Inactive</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-12">
                                <button type="submit" id="editBtn" name="submitForm" class="form-control btn btn-primary" >Edit</button>
                            </div>
                        </div>

                    </form>
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
                    messageTop: '<?php echo $reportTitle; ?>',
                    title: '',
                    exportOptions: {
                        columns: [0,1,2,3,4,5,6]
                    }

                },
                {
                    extend: 'csv',
                    header: true,
                    footer: true,
                    messageTop: '<?php echo $reportTitle; ?>',
                    title: '',
                    exportOptions: {
                        columns: [0,1,2,3,4,5,6]
                    }

                },
                {
                    extend: 'excel',
                    header: true,
                    footer: true,
                    messageTop: '<?php echo $reportTitle; ?>',
                    title: '',
                    exportOptions: {
                        columns: [0,1,2,3,4,5,6]
                    }

                },
                {
                    extend: 'pdf',
                    header: true,
                    footer: true,
                    messageTop: '<?php echo $reportTitle; ?>',
                    title: '',
                    exportOptions: {
                        columns: [0,1,2,3,4,5,6]
                    }

                },
                {
                    extend: 'print',
                    header: true,
                    footer: true,
                    messageTop: '<?php echo $reportTitle; ?>',
                    title: '',
                    exportOptions: {
                        columns: [0,1,2,3,4,5,6]
                    }

                }
            ]
        });

        $(".editSupplier").click(function(){
            var $btn = $(this);
            var supplierID = $(this).attr("data-id");
            $.ajax({
                url: baseurl+'/app_admin/get_edit_supplier',
                data:{supplierID:supplierID},
                type:'POST',
                beforeSend:function(){
                    $btn.button('loading').html('<i class="fa fa-spinner fa-pulse fa-2x fa-fw">');
                },
                success: function(result){
                    $btn.button('reset');
                    var result = JSON.parse(result);
                    if(result.status == 1)
                    {

                        $("#nameHolder").val(result.data.SupplierHospital.name);
                        $("#IDholder").val(result.data.SupplierHospital.id);
                        $("#telHolder").val(result.data.SupplierHospital.tel);
                        //$("#mobileHolder").val(result.data.DentalSupplier.mobile);
                        $("#whatsappMobileHolder").val(result.data.SupplierHospital.whatsapp_mobile);
                        $("#emailHolder").val(result.data.SupplierHospital.email);
                        $("#addressHolder").val(result.data.SupplierHospital.address);
                        $("#statusHolder").val(result.data.SupplierHospital.status);
                        $("#editModal").modal('show');

                    }
                    else
                    {
                        alert(result.message);
                    }
                }
            });
        });

        $("#editForm").submit(function(e){
            e.preventDefault();
            var dataTosend = $(this).serialize();

            $.ajax({
                url: baseurl+'/app_admin/edit_supplier',
                data:dataTosend,
                type:'POST',
                beforeSend:function(){
                    $("#editBtn").button('loading').html('<i class="fa fa-spinner fa-pulse fa-2x fa-fw">');
                },
                success: function(result){
                    $("#editBtn").button('reset');
                    var result = JSON.parse(result);
                    if(result.status == 1)
                    {

                        $('#editForm')[0].reset();
                        $("#editModal").modal('hide');
                        window.location.reload();
                    }
                    else
                    {
                        alert(result.message);
                    }
                }
            });

        });
    });
</script>