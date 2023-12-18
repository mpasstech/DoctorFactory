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
                    <!--left sidebar-->

                    <h3 class="screen_title">Discharge Templates</h3>
                    <a href="<?php echo Router::url('/app_admin/add_discharge_eye_template/',true); ?>" class="btn btn-xs btn-info list_template_btn">Add Template</a>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">



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
                                                <th>Status</th>
                                                <th>Option</th>
                                            </tr>
                                            </thead>
                                            <tbody>

                                            <?php $counter = 1; foreach($dataList AS $item){ ?>

                                                <tr>
                                                    <td><?php echo $counter; ?>.</td>
                                                    <td><?php echo ucwords( $item['HospitalDischargeTemplate']['name']); ?></td>
                                                    <td><?php echo $item['HospitalDischargeTemplate']['status']; ?></td>
                                                    <td>
                                                        <a href="<?php echo Router::url('/app_admin/edit_discharge_eye_template/'.base64_encode($item['HospitalDischargeTemplate']['id']),true); ?>" class="btn btn-primary btn-xs" data-id="<?php echo $item['HospitalDischargeTemplate']['id']; ?>"  ><i class="fa fa-edit fa-2x"></i> Edit</a>
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
</style>



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
                    title: '',
                    exportOptions: {
                        columns: [0,1,2,3,4,5,6]
                    }

                },
                {
                    extend: 'csv',
                    header: true,
                    footer: true,
                    title: '',
                    exportOptions: {
                        columns: [0,1,2,3,4,5,6]
                    }

                },
                {
                    extend: 'excel',
                    header: true,
                    footer: true,
                    title: '',
                    exportOptions: {
                        columns: [0,1,2,3,4,5,6]
                    }

                },
                {
                    extend: 'pdf',
                    header: true,
                    footer: true,
                    title: '',
                    exportOptions: {
                        columns: [0,1,2,3,4,5,6]
                    }

                },
                {
                    extend: 'print',
                    header: true,
                    footer: true,
                    title: '',
                    exportOptions: {
                        columns: [0,1,2,3,4,5,6]
                    }

                }
            ]
        });

    });
</script>