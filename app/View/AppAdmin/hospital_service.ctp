<?php
$login = $this->Session->read('Auth.User.User');
$category = $this->AppAdmin->getHospitalServiceCategoryArray($login['thinapp_id']);

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
     td{
        word-break: break-word;
    }
</style>



<div class="Home-section-2">

    <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1">
        <?php echo $this->element('inventory_pharma_billing_setting_inner_header'); ?>
    </div>

    <div class="col-lg-11 col-md-11 col-sm-11 col-xs-11">
        <div class="container">

            <div class="row">

                <div class="middle-block">
                    <!-- Heading -->
                    <h3 class="screen_title"> Service/Product</h3>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 custom_form_box">
                        <?php echo $this->element('message'); ?>


                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                            <?php echo $this->element('hospital_service_inner_tab'); ?>

                            <?php echo $this->Form->create('Search',array('type'=>'get','url'=>array('controller'=>'app_admin','action' => 'search_hos_ser'))); ?>
                            <div class="form-group">
                                <div class="col-sm-4">
                                    <?php echo $this->Form->input('name', array('type' => 'text', 'placeholder' => 'Search by name', 'label' => 'Search by Service/Product Name', 'class' => 'form-control')); ?>
                                </div>

                                <div class="col-sm-4">

                                    <?php echo $this->Form->input('category', array('type' => 'select','empty'=>'All','options'=>$category,'label' => 'Search by Category', 'class' => 'form-control')); ?>
                                </div>

                                <div class="col-sm-4" style="padding: 30px 0px;">
                                    <div class="input text">
                                        <label>&nbsp;</label>
                                        <?php echo $this->Form->submit('Search',array('div'=>false,'class'=>'btn btn-info','id'=>'search')); ?>
                                        <a class="btn btn-warning" href="<?php echo $this->Html->url(array('controller'=>'app_admin','action'=>'hospital_service')) ?>">Reset</a>

                                    </div>

                                </div>

                            </div>

                            <?php echo $this->Form->end(); ?>

                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">





                            <div class="table table-responsive">
                                <table id="example" class="table table-striped table-bordered" style="width:100%">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Service/Product Name</th>
                                            <th>Tax - Rate(%)</th>
                                            <th>Category</th>
                                            <th style="width: 300px !important;">Price</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                         </tr>
                                        </thead>
                                        <tbody>
                                        <?php foreach ($data as $key => $list){
                                            ?>
                                            <tr>
                                                <td><?php echo $key+1; ?></td>
                                                <td><?php echo $list['MedicalProduct']['name']; ?></td>
                                                <td><?php echo @$list['HospitalServiceCategory']['HospitalTaxRate']['name'].' - '.@$list['HospitalServiceCategory']['HospitalTaxRate']['rate'].'%'; ?></td>
                                                <td><?php echo $list['HospitalServiceCategory']['name']; ?></td>
                                                <td><?php
                                                    if(!empty($list['MedicalProductQuantity'])){
                                                        $priceArr = array();
                                                        foreach($list['MedicalProductQuantity'] AS $price)
                                                        {
                                                            $priceArr[] = $price['mrp'];
                                                        }
                                                        echo implode(',',$priceArr);
                                                    }
                                                    else
                                                    {
                                                        echo $list['MedicalProduct']['price'];
                                                    }
                                                    ?></td>
                                                <td><?php echo $list['MedicalProduct']['status']; ?></td>
                                                <td>
                                                    <a href="<?php echo Router::url('/app_admin/edit_hospital_service/',true).base64_encode($list['MedicalProduct']['id']);?>" class="btn btn-primary btn-xs">Edit</a>
                                                    <?php if( $list['MedicalProduct']['is_package'] != 1){ ?>
                                                        <a href="<?php echo Router::url('/app_admin/list_hospital_service_inventory/',true).base64_encode($list['MedicalProduct']['id']);?>" class="btn btn-primary btn-xs">Detail</a>
                                                    <?php } ?>


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


            $(document).on('change', '#p_type_id', function (e) {
                $('#p_type_name').val($('#p_type_id option:selected').text());
            });

            $('#example').DataTable({
                dom: 'Blfrtip',
                lengthMenu: [
                    [10, 25, 50, 100, 150, 200, -1],
                    ['10 rows', '25 rows', '50 rows', '100 rows', '150 rows', '200 rows', 'Show all']
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

<script>
    $(document).ready(function(){

        $(".channel_tap a").removeClass('active');
        $("#v_app_subscriber_list").addClass('active');


        $(document).on('click','#editCustomer',function(){


            var ID = $(this).attr('customer-id');
            var thisButton = $(this);
            $.ajax({
                url: baseurl+'/app_admin/get_customer_by_id',
                data:{ID:ID},
                type:'POST',
                beforeSend:function(){
                    $(thisButton).button('loading').html('<i class="fa fa-spinner fa-pulse fa-2x fa-fw">');
                },
                success: function(result){

                    $(thisButton).button('reset');
                    var result = JSON.parse(result);
                    if(result.status == 1)
                    {
                        $("#mobileHolder").val(result.data.mobile);
                        $("#firstNameHolder").val(result.data.first_name);
                        $("#genderHolder").val(result.data.gender);
                        $("#IDholder").val(result.data.id);
                        $("#editModal").modal('show');
                    }
                    else
                    {
                        alert('Sorry, Could not get data!');
                    }

                }
            });

        });



        $(document).on('submit','#editForm',function (e) {
            e.stopPropagation();
            e.preventDefault();
            var dataToPost = $(this).serialize();
            var thisButton = $('#editBtn');
            $.ajax({
                url: baseurl+'/app_admin/edit_customer',
                data:dataToPost,
                type:'POST',
                beforeSend:function(){
                    $(thisButton).button('loading').html('<i class="fa fa-spinner fa-pulse fa-2x fa-fw">');
                },
                success: function(result){
                    $(thisButton).button('reset');
                    var result = JSON.parse(result);
                    if(result.status == 1)
                    {
                        $("#editModal").modal('hide');
                        $("#editForm").trigger('reset');
                        location.reload();
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





