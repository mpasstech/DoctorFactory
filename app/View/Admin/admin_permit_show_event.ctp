<div class="Inner-banner">
    <div class="container">
        <div class="row">

            <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 inner-page-text"> <h2>Show List</h2> </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 pull-right create-btn-box">
            </div>
        </div>
    </div>
</div>
<section class="Home-section-2">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="container">
            <div class="row">
                <div class="middle-block">
                    <?php echo $this->element('admin_leftsidebar'); ?>
                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                        <div class="Social-login-box payment_bx">

                            <div class="form-group row">
                                <div class="col-sm-12">
                                    <div class="table-responsive">
                                        <?php if(!empty($eventShow)){ ?>

                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Event Title</th>
                                                <th>Title</th>
                                                <th>Start Time</th>
                                                <th>End Time</th>
                                                <th>Status</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php

                                            $num = 1;
                                            foreach ($eventShow as $value){
                                                ?>
                                                <tr>
                                                    <td><?php echo $num++; ?></td>
                                                    <td><?php echo $value['Event']['title']; ?></td>
                                                    <td><?php echo $value['EventShow']['title']; ?></td>
                                                    <td><?php echo date("d-M-Y H:i:s",strtotime($value['EventShow']['start_datetime'])); ?></td>
                                                    <td><?php echo date("d-M-Y H:i:s",strtotime($value['EventShow']['end_datetime'])); ?></td>
                                                    <td>
                                                        <button type="button" id="changeStatus" class="btn btn-primary btn-xs" row-id="<?php echo $value['EventShow']['id']; ?>" ><?php echo $value['EventShow']['status']; ?></button>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                            </tbody>
                                        </table>
                                        <?php echo $this->element('paginator'); ?>
                                    </div>
                                </div>
                            </div>
                            <?php }else{ ?>
                                <div class="no_data">
                                    <h2>No Show Found..!</h2>
                                </div>
                            <?php } ?>
                            <div class="clear"></div>




                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
