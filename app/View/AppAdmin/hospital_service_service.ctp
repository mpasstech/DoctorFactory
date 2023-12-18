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
                    <h3 class="screen_title"> Service & Package</h3>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 custom_form_box">
                        <?php echo $this->element('message'); ?>


                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                            <?php echo $this->element('hospital_service_inner_tab_service'); ?>

                            <?php echo $this->Form->create('Search',array('type'=>'get','url'=>array('controller'=>'app_admin','action' => 'search_hos_ser_service'))); ?>
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
                                        <a class="btn btn-warning" href="<?php echo $this->Html->url(array('controller'=>'app_admin','action'=>'hospital_service_service')) ?>">Reset</a>

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
                                            <th>Price</th>
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
                                                    <a href="<?php echo Router::url('/app_admin/edit_hospital_service_service/',true).base64_encode($list['MedicalProduct']['id']);?>" class="btn btn-primary btn-xs">Edit</a>
                                                    <?php if( $list['MedicalProduct']['is_package'] != 1){ ?>
                                                        <a href="<?php echo Router::url('/app_admin/list_hospital_service_inventory_service/',true).base64_encode($list['MedicalProduct']['id']);?>" class="btn btn-primary btn-xs">Detail</a>
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





