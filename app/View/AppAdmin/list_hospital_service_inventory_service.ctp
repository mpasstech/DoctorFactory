<?php
$login = $this->Session->read('Auth.User.User');
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

    <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1">
        <?php echo $this->element('inventory_service_billing_setting_inner_header'); ?>
    </div>

    <div class="col-lg-11 col-md-11 col-sm-11 col-xs-11">
        <div class="container">
            <div class="row">

                <div class="middle-block">
                    <!-- Heading -->
                    <h3 class="screen_title"> Detail (<?php echo $medicalProData['MedicalProduct']['name']; ?>)</h3>
                    <a style="float: right;" href="<?php echo Router::url('/app_admin/add_product_quantity_service/',true).base64_encode($medicalProductID); ?>" class="btn btn-primary">Add</a>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 custom_form_box">
                        <?php echo $this->element('message'); ?>


                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                            <div class="table table-responsive">
                                <table id="example" class="table table-striped table-bordered" style="width:100%">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Purchase Price</th>
                                            <th>MRP</th>
                                            <th>Expiry Date</th>
                                            <th>Quantity</th>
                                            <th>Sold</th>
                                            <th>Status</th>
                                            <th>Created</th>
                                            <th>Options</th>
                                         </tr>
                                        </thead>
                                        <tbody>
                                        <?php foreach ($inventoryData as $key => $list){
                                            ?>
                                            <tr>
                                                <td><?php echo $key+1; ?></td>
                                                <td><?php echo $list['MedicalProductQuantity']['purchase_price']; ?></td>
                                                <td><?php echo $list['MedicalProductQuantity']['mrp']; ?></td>
                                                <td><?php echo ($list['MedicalProductQuantity']['expiry_date'] == '0000-00-00')?'Not Available':date('d M Y',strtotime($list['MedicalProductQuantity']['expiry_date'])); ?></td>
                                                <td><?php echo $list['MedicalProductQuantity']['quantity']; ?></td>
                                                <td><?php echo $list['MedicalProductQuantity']['sold']; ?></td>
                                                <td><?php echo $list['MedicalProductQuantity']['status']; ?></td>
                                                <td><?php echo ($list['MedicalProductQuantity']['created'] == '0000-00-00')?'Not Available':date('d M Y',strtotime($list['MedicalProductQuantity']['created'])); ?></td>
                                                <td>
                                                    <a href="<?php echo Router::url('/app_admin/edit_product_quantity_service/',true).base64_encode($list['MedicalProductQuantity']['id']).'/'.base64_encode($list['MedicalProductQuantity']['medical_product_id']); ?>" class="btn btn-primary btn-xs">Edit</a>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                        </tbody>
                                    </table>
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





<div class="modal fade" id="editModal" role="dialog">

    <div class="modal-dialog modal-sm">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Edit Customer</h4>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <form id="editForm" method="POST">

                        <div class="form-group">
                            <div class="col-sm-12">
                                <input type="text" name="mobile" id="mobileHolder" placeholder="Please enter mobile" class="form-control cnt" required>
                                <input type="hidden" name="id" id="IDholder">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <input type="text" name="first_name" id="firstNameHolder" placeholder="Please enter name" class="form-control cnt" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <select name="gender" id="genderHolder" class="form-control cnt" required>
                                    <option value="MALE">Male</option>
                                    <option value="FEMALE">Female</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-12">
                                <button type="submit" id="editBtn" name="submitForm" class="form-control" >Edit</button>
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
                    [10, 25, 50, 100, 150, 200, -1],
                    ['10 rows', '25 rows', '50 rows', '100 rows', '150 rows', '200 rows', 'Show all']
                ],
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
                            columns: [0, 1, 2, 3, 4, 5, 6]
                        }

                    },
                    {
                        extend: 'csv',
                        header: true,
                        footer: true,
                        messageTop: '<?php echo $reportTitle; ?>',
                        title: '',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6]
                        }

                    },
                    {
                        extend: 'excel',
                        header: true,
                        footer: true,
                        messageTop: '<?php echo $reportTitle; ?>',
                        title: '',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6]
                        }

                    },
                    {
                        extend: 'pdf',
                        header: true,
                        footer: true,
                        messageTop: '<?php echo $reportTitle; ?>',
                        title: '',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6]
                        }

                    },
                    {
                        extend: 'print',
                        header: true,
                        footer: true,
                        messageTop: '<?php echo $reportTitle; ?>',
                        title: '',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6]
                        }

                    }
                ]
            });

        });
</script>





