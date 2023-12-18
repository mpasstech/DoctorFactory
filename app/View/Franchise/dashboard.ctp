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
                    <h3 class="screen_title">Dashboard</h3>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">


                        <div class="Social-login-box payment_bx">

                            <?php echo $this->element('message'); ?>
                            <div class="form-group">

                                <div class="row">



                                    <div class="col-sm-12">
                                        <h3>Earning</h3>
                                        <div class="col-sm-3">
                                            <div class="panel panel-info">
                                                <div class="panel-heading">
                                                    <div class="row">
                                                        <div class="col-xs-12 text-center">
                                                            <div class="huge"><i class="fa fa-inr"></i><?php echo $today_total; ?></div>
                                                        </div>

                                                        <div class="col-xs-12 text-center lbl_heading">
                                                            Today's Earning
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="panel panel-success">
                                                <div class="panel-heading">
                                                    <div class="row">
                                                        <div class="col-xs-12 text-center">
                                                            <div class="huge"><i class="fa fa-inr"></i><?php echo $week_total; ?></div>
                                                        </div>

                                                        <div class="col-xs-12 text-center lbl_heading">
                                                            Week's Earning
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="panel panel-warning">
                                                <div class="panel-heading">
                                                    <div class="row">

                                                        <div class="col-xs-12 text-center">
                                                            <div class="huge"><i class="fa fa-inr"></i><?php echo $month_total; ?></div>
                                                        </div>
                                                        <div class="col-xs-12 text-center lbl_heading">
                                                            Month's Earning
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="panel panel-danger">
                                                <div class="panel-heading">
                                                    <div class="row">

                                                        <div class="col-xs-12 text-center">
                                                            <div class="huge"><i class="fa fa-inr"></i><?php echo $total; ?></div>
                                                        </div>

                                                        <div class="col-xs-12 text-center lbl_heading">
                                                            Total Earning
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>







                            </div>

                            <div class="form-group">

                                <div class="row table_row">
                                    <div class="col-sm-6">
                                        <h3>Associate App List </h3>
                                        <?php if(!empty($mediator_app_list)){ ?>
                                            <div class="table-responsive">
                                            <?php  foreach ($mediator_app_list as $app_name => $app_list){ ?>
                                                <table id="assocate_table" class="table" style="margin-bottom:0;">
                                                    <thead>
                                                    <tr>
                                                        <th><?php echo $app_name; ?></th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>
                                                                <table id="assocate_table" class="table">
                                                                    <thead>
                                                                    <tr>
                                                                        <th>#</th>
                                                                        <th>Role</th>
                                                                        <th>Percentage</th>

                                                                    </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                    <?php  foreach ($app_list as $key => $list){ ?>
                                                                        <tr>
                                                                            <td><?php echo $key+1; ?></td>
                                                                            <td><?php echo $list['role'] ?></td>
                                                                            <td><?php echo $list['percentage']."%" ?></td>

                                                                        </tr>
                                                                    <?php } ?>
                                                                    </tbody>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                        <?php } ?>
                                            </div>
                                        <?php }else{ ?>
                                            <h2 class="no_data"> Your are not associate with app  </h2>

                                        <?php } ?>

                                    </div>



                                    <div class="col-sm-6">
                                        <h3>Today Earning List </h3>
                                        <?php if(!empty($today_total_app_list)){ ?>
                                            <div class="table-responsive">

                                                <table id="today_collection" class="table">
                                                    <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>App Name</th>
                                                        <th>Total Fee</th>

                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php  foreach ($today_total_app_list as $key => $list){ ?>
                                                        <tr>
                                                            <td><?php echo $key+1; ?></td>
                                                            <td><?php echo $list['app_name'] ?></td>
                                                            <td><?php echo '<i class="fa fa-inr"></i>'. $this->Franchise->splitAfterDecimal($list['total']); ?></td>
                                                        </tr>
                                                    <?php } ?>
                                                    </tbody>
                                                </table>

                                            </div>
                                        <?php }else{ ?>
                                            <h2 class="no_data"> No data found for today  </h2>

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



