<div class="Inner-banner">
    <div class="container">
        <div class="row">
       
            <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 inner-page-text"> <h2>Ticket List</h2> </div>
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
                                <?php if(!empty($eventTicket)){ ?>

                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Title</th>
                                        <th>Show</th>
                                        <th>Title</th>
                                        <th>Sub Title</th>
                                        <th>Amount</th>
                                        <th>Total Count</th>
                                        <th>Available Count</th>
                                        <th>Status</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php

									$num = 1;
									foreach ($eventTicket as $value){

                                        ?>
                                        <tr>
                                            <td><?php echo $num++; ?></td>
                                            <td><?php echo $value['Event']['title']; ?></td>
                                            <td>
                                                <?php echo date("d-M-Y H:i:s",strtotime($value['EventShow']['start_datetime'])); ?> - <?php echo date("d-M-Y H:i:s",strtotime($value['EventShow']['end_datetime'])); ?>
                                            </td>
                                            <td><?php echo $value['EventTicket']['title']; ?></td>
                                            <td>
                                                <?php echo $value['EventTicket']['sub_title']; ?>
                                            </td>
                                            <td>
                                                <?php echo $value['EventTicket']['amount']; ?>
                                            </td>
                                            <td>
                                                <?php echo $value['EventTicket']['total_count']; ?>
                                            </td>
                                            <td>
                                                <?php echo $value['EventTicket']['available_count']; ?>
                                            </td>
                                            <td>
                                                <button type="button" id="changeStatus" class="btn btn-primary btn-xs" row-id="<?php echo $value['EventTicket']['id']; ?>" ><?php echo $value['EventTicket']['status']; ?></button>
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
                                <h2>No Ticket Found..!</h2>
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