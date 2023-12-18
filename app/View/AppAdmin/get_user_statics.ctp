<?php
$login = $this->Session->read('Auth.User');
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
                    <h3 class="screen_title">Appointment And Download Stats</h3>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">


                        <div class="Social-login-box payment_bx">

                            <?php echo $this->element('message'); ?>
                            <div class="form-group">
							
								<div class="row">



                                    <div class="col-sm-6">
                                        <h3>Download And Appointment Stats</h3>
                                        <div class="col-sm-4">
                                            <div class="panel panel-info">
                                                <div class="panel-heading">
                                                    <div class="row">
                                                        <div class="col-xs-2">
                                                            <i class="fa fa-users fa-2x"></i>
                                                        </div>
                                                        <div class="col-xs-8 text-right">
                                                            <div class="huge"><?php echo $all_counts["today_downloads"]; ?></div>
                                                        </div>

                                                        <div class="col-xs-12 text-center lbl_heading">
                                                            Today's Downloads
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="panel panel-success">
                                                <div class="panel-heading">
                                                    <div class="row">
                                                        <div class="col-xs-2">
                                                            <i class="fa fa-users fa-2x"></i>
                                                        </div>
                                                        <div class="col-xs-8 text-right">
                                                            <div class="huge"><?php echo $all_counts["week_downloads"]; ?></div>
                                                        </div>

                                                        <div class="col-xs-12 text-center lbl_heading">
                                                            Week's Downloads
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="col-sm-4">
                                            <div class="panel panel-warning">
                                                <div class="panel-heading">
                                                    <div class="row">
                                                        <div class="col-xs-2">
                                                            <i class="fa fa-users fa-2x"></i>
                                                        </div>
                                                        <div class="col-xs-8 text-right">
                                                            <div class="huge"><?php echo $all_counts["month_downloads"]; ?></div>
                                                        </div>

                                                        <div class="col-xs-12 text-center lbl_heading">
                                                            Month's Downloads
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="panel panel-danger">
                                                <div class="panel-heading">
                                                    <div class="row">
                                                        <div class="col-xs-2">
                                                            <i class="fa fa-users fa-2x"></i>
                                                        </div>
                                                        <div class="col-xs-8 text-right">
                                                            <div class="huge"><?php echo $all_counts["today_appointment"]; ?></div>
                                                        </div>

                                                        <div class="col-xs-12 text-center lbl_heading">
                                                            Today's Appointments
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>



                                        <div class="col-sm-4">
                                            <div class="panel panel-success">
                                                <div class="panel-heading">
                                                    <div class="row">
                                                        <div class="col-xs-2">
                                                            <i class="fa fa-users fa-2x"></i>
                                                        </div>
                                                        <div class="col-xs-8 text-right">
                                                            <div class="huge"><?php echo $all_counts["week_appointment"]; ?></div>
                                                        </div>

                                                        <div class="col-xs-12 text-center lbl_heading">
                                                            Week's Appointments
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="panel panel-info">
                                                <div class="panel-heading">
                                                    <div class="row">
                                                        <div class="col-xs-2">
                                                            <i class="fa fa-users fa-2x"></i>
                                                        </div>
                                                        <div class="col-xs-8 text-right">
                                                            <div class="huge"><?php echo $all_counts["month_appointment"]; ?></div>
                                                        </div>

                                                        <div class="col-xs-12 text-center lbl_heading">
                                                            Month's Appointments
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="col-sm-6">



                                        <div class="row">
                                            <h3>Download And Appointment Search Stats</h3>
                                            <div class="col-sm-6">
                                                <div class="panel panel-primary">
                                                    <div class="panel-heading">
                                                        <div class="row">
                                                            <div class="col-xs-6">
                                                                <i class="fa fa-users fa-2x"></i>
                                                            </div>
                                                            <div class="col-xs-6 text-right">
                                                                <div class="huge"><?php echo !empty($appointment_list)?count($appointment_list):0; ?></div>
                                                            </div>

                                                            <div class="col-xs-12 text-center lbl_heading">
                                                               Total Appointment
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-6">
                                                <div class="panel panel-primary">
                                                    <div class="panel-heading">
                                                        <div class="row">
                                                            <div class="col-xs-6">
                                                                <i class="fa fa-users fa-2x"></i>
                                                            </div>
                                                            <div class="col-xs-6 text-right">
                                                                <div class="huge"><?php echo !empty($download_user_list)?count($download_user_list):0; ?></div>
                                                            </div>

                                                            <div class="col-xs-12 text-center lbl_heading">
                                                                Total Downloads
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="row search_main">
                                            <?php echo $this->Form->create('Search',array('type'=>'get','url'=>array('controller'=>'app_admin','action' => 'search_get_user_statics'))); ?>

                                            <div class="form-group">
                                                <div class="col-md-4">
                                                    <?php echo $this->Form->input('from_date', array('autocomplete'=>'off','type'=>'text','placeholder' => '', 'label' => 'From date', 'class' => 'form-control from_date')); ?>
                                                </div>
                                                <div class="col-md-4">
                                                    <?php echo $this->Form->input('to_date', array( 'autocomplete'=>'off','type'=>'text','placeholder' => '', 'label' => 'To date', 'class' => 'form-control to_date')); ?>
                                                </div>


                                                <div class="col-sm-4 action_btn" >
                                                    <div class="input text">
                                                        <label style="display: block;">&nbsp;</label>
                                                        <?php echo $this->Form->submit('Search',array('div'=>false,'class'=>'btn btn-info','id'=>'search')); ?>
                                                        <a class="btn btn-warning resteButton" href="<?php echo $this->Html->url(array('controller'=>'app_admin','action'=>"get_user_statics")) ?>">Reset</a>

                                                    </div>

                                                </div>

                                            </div>
                                            <?php echo $this->Form->end(); ?>
                                        </div>



                                    </div>



									
									
								</div>




								

									
								</div>

                            <div class="form-group">

                                <div class="row table_row">
                                    <div class="col-sm-6">
                                        <h3>Total Appointment List </h3>
                                            <?php if(!empty($appointment_list)){ ?>
                                                <div class="table-responsive">

                                                    <table id="appointment_table" class="table">
                                                        <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Patient Name</th>
                                                            <th>Patient Mobile</th>
                                                            <th>Doctor Name</th>

                                                            <th>Appointment Date</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <?php  foreach ($appointment_list as $key => $list){ ?>
                                                            <tr>
                                                                <td><?php echo $key+1; ?></td>
                                                                <td><?php echo $list['patient_name'] ?></td>
                                                                <td><?php echo $list['patient_mobile'] ?></td>
                                                                <td><?php echo $list['doctor_name'] ?></td>

                                                                <td><?php echo date('d-m-Y H:i',strtotime($list['appointment_datetime']));?></td>

                                                            </tr>
                                                        <?php } ?>
                                                        </tbody>
                                                    </table>

                                                </div>
                                            <?php }else{ ?>
                                            <h2 class="no_data"> No appointment on search result  </h2>

                                        <?php } ?>

                                    </div>



                                    <div class="col-sm-6">
                                        <h3>Total Download List </h3>
                                        <?php if(!empty($download_user_list)){ ?>
                                            <div class="table-responsive">

                                                <table id="user_table" class="table">
                                                    <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Patient Name</th>
                                                        <th>Patient Mobile</th>
                                                        <th>Appointment Date</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php  foreach ($download_user_list as $key => $list){ ?>
                                                        <tr>
                                                            <td><?php echo $key+1; ?></td>
                                                            <td><?php echo @$list['username'] ?></td>
                                                            <td><?php echo @$list['mobile'] ?></td>
                                                            <td><?php echo @date('d-m-Y H:i',strtotime($list['created']));?></td>
                                                        </tr>
                                                    <?php } ?>
                                                    </tbody>
                                                </table>

                                            </div>
                                        <?php }else{ ?>
                                            <h2 class="no_data"> No download users on search result  </h2>

                                        <?php } ?>

                                    </div>
                                </div>


                            </div>
                            </div>
                        </div>
                            
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
.searchBtn{ margin-top: 28px; }
    .huge{
        font-size: 40px;
    }
    .no_data{
        text-align: center;
        font-size: 20px;
    }

.panel-heading{
    padding: 10px 4px;
}
.panel-heading .text-right{
    right: 17px;
    padding: 0px;
    text-align: right;

    position: absolute;
}
    .table_row{
        border-top: 2px solid #afabab;
    }

    td, th{
        padding: 3px 2px !important;
    }
    .lbl_heading{
        font-size: 16px;
        padding: 20px 0px 0px 0px;
        margin: 0px;
        width: 100%;
        font-weight: 600;
    }
    .dt-buttons button{
        padding: 2px 2px !important;
    }
</style>
<script>



            $('#appointment_table').DataTable({
                dom: 'Blfrtip',
                lengthMenu: [
                    [ 10, 25, 50, 100, 150, 200, -1 ],
                    [ '10 rows', '25 rows', '50 rows', '100 rows', '150 rows', '200 rows', 'Show all' ]
                ],
                "language": {
                    "emptyTable": "No Data Found Related To Search"
                },
                "oLanguage": { "sSearch": "" },
                buttons: [
                    {
                        extend: 'copy',
                        header: true,
                        messageTop: '<?php echo $reportTitle; ?>',
                        title: '',
                        exportOptions: {
                            columns: [0,1,2,3,4]
                        }

                    },
                    {
                        extend: 'csv',
                        header: true,
                        messageTop: '<?php echo $reportTitle; ?>',
                        title: '',
                        exportOptions: {
                            columns: [0,1,2,3,4]
                        }

                    },
                    {
                        extend: 'excel',
                        header: true,
                        messageTop: '<?php echo $reportTitle; ?>',
                        title: '',
                        exportOptions: {
                            columns: [0,1,2,3,4]
                        }

                    },
                    {
                        extend: 'pdf',
                        header: true,
                        messageTop: '<?php echo $reportTitle; ?>',
                        title: '',
                        exportOptions: {
                            columns: [0,1,2,3,4]
                        }

                    },
                    {
                        extend: 'print',
                        header: true,
                        messageTop: '<?php echo $reportTitle; ?>',
                        title: '',
                        exportOptions: {
                            columns: [0,1,2,3,4]
                        }

                    }
                ]
            });





            $('#user_table').DataTable({
                dom: 'Blfrtip',
                lengthMenu: [
                    [ 10, 25, 50, 100, 150, 200, -1 ],
                    [ '10 rows', '25 rows', '50 rows', '100 rows', '150 rows', '200 rows', 'Show all' ]
                ],
                "language": {
                    "emptyTable": "No Data Found Related To Search"
                },
                "oLanguage": { "sSearch": "" },
                buttons: [
                    {
                        extend: 'copy',
                        header: true,
                        messageTop: '<?php echo $reportTitle; ?>',
                        title: '',
                        exportOptions: {
                            columns: [0,1,2,3]
                        }

                    },
                    {
                        extend: 'csv',
                        header: true,
                        messageTop: '<?php echo $reportTitle; ?>',
                        title: '',
                        exportOptions: {
                            columns: [0,1,2,3]
                        }

                    },
                    {
                        extend: 'excel',
                        header: true,
                        messageTop: '<?php echo $reportTitle; ?>',
                        title: '',
                        exportOptions: {
                            columns: [0,1,2,3]
                        }

                    },
                    {
                        extend: 'pdf',
                        header: true,
                        messageTop: '<?php echo $reportTitle; ?>',
                        title: '',
                        exportOptions: {
                            columns: [0,1,2,3]
                        }

                    },
                    {
                        extend: 'print',
                        header: true,
                        messageTop: '<?php echo $reportTitle; ?>',
                        title: '',
                        exportOptions: {
                            columns: [0,1,2,3]
                        }

                    }
                ]
            });

			$(document).ready(function(){
                $(".from_date, .to_date").datepicker({clearBtn:true,format: 'dd/mm/yyyy',autoclose:true,orientation: "bottom auto",endDate: new Date()});

            });
</script>



