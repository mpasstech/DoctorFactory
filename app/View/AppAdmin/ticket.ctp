<?php
$login = $this->Session->read('Auth.User');
?>

<div class="Inner-banner">
    <div class="container">
        <div class="row">
            <!--  SLIDER IMAGE -->
            <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 inner-page-text"> <h2>Tickets</h2> </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 pull-right create-btn-box">
            </div>
        </div>
    </div>
</div>

<div class="Home-section-2">

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="container">
            <div class="row">
                <!--box 1 -->
                <!--box 1 -->
                <div class="middle-block">
                    <!-- Heading -->
                    <!--left sidebar-->

<!--                    --><?php /*echo $this->element('app_admin_leftsidebar'); */?>
                    <!--left sidebar-->

                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                        <div class="Social-login-box dashboard_box">



                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <h3>Tickets</h3>
                                        <ul class="dashboard_icon_li">

                                               <!-- <li>
                                                    <a href="javascript:void(0)" id="addTicket">
                                                    <div class="content_div">
                                                        <div class="dash_img">
                                                            <img src="<?php /*echo Router::url('/thinapp_images/poll-oneline-icon.png')*/?>">
                                                        </div>
                                                        New Ticket
                                                    </div>
                                                    </a>
                                                </li>-->

                                                <li>
                                                    <a href="<?php echo Router::url('/app_admin/list_tickets',true); ?>">
                                                        <div class="content_div">
                                                            <div class="dash_img">
                                                                <img src="<?php echo Router::url('/thinapp_images/ticket-icon.png')?>">
                                                            </div>
                                                            View Tickets
                                                        </div>
                                                    </a>
                                                </li>

                                            <li>
                                                <a href="javascript:void(0)" id="viewFavouriteTickets">
                                                    <div class="content_div">
                                                        <div class="dash_img">
                                                            <img src="<?php echo Router::url('/thinapp_images/fav-ticket.png')?>">
                                                        </div>
                                                        Favourite Tickets
                                                    </div>
                                                </a>
                                            </li>

                                            <li id="viewRecentTickets">
                                                <a href="javascript:void(0)">
                                                    <div class="content_div">
                                                        <div class="dash_img">
                                                            <img src="<?php echo Router::url('/thinapp_images/recent-images.png')?>">
                                                        </div>
                                                        Recent
                                                    </div>
                                                </a>
                                            </li>

                                            <li>
                                                <a href="<?php echo Router::url('/app_admin/ticket_report',true); ?>">
                                                    <div class="content_div">
                                                        <div class="dash_img">
                                                            <img src="<?php echo Router::url('/thinapp_images/ticket_report.png')?>">
                                                        </div>
                                                        Report
                                                    </div>
                                                </a>
                                            </li>
                                            </ul>
                                        <div class="clear"></div>


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



<div class="modal fade" id="myModal" role="dialog">

    <div class="modal-dialog modal-sm">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Recent Tickets</h4>
            </div>
            <div class="modal-body">

                <div class="table-responsive">
                    <table class="table" >
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Created By</th>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Created</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        $n = 1;
                        foreach ($recentTickets as $value){ ?>
                            <tr>
                                <td><?php echo $n++; ?></td>
                                <td><?php echo $value['CreatedBy']['username']; ?></td>
                                <td><?php echo $value['Ticket']['title']; ?></td>
                                <td><?php echo mb_strimwidth($value['Ticket']['description'], 0, 50, '...'); ?></td>
                                <td><?php echo $value['Ticket']['type']; ?></td>
                                <td><?php echo $value['Ticket']['status']; ?></td>
                                <td><?php echo date('d-M-Y',strtotime($value['Ticket']['created'])); ?></td>
                            </tr>
                        <?php } $value=''; ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>



<div class="modal fade" id="myModalFavourite" role="dialog">

    <div class="modal-dialog modal-sm">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Favourite Tickets</h4>
            </div>
            <div class="modal-body">

                <div class="table-responsive">
                    <table class="table" >
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Created By</th>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Created</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $n = 1;
                        foreach ($favouriteTickets as $value){ ?>
                            <tr>
                                <td><?php echo $n++; ?></td>
                                <td><?php echo $value['CreatedBy']['username']; ?></td>
                                <td><?php echo $value['Ticket']['title']; ?></td>
                                <td><?php echo mb_strimwidth($value['Ticket']['description'], 0, 50, '...'); ?></td>
                                <td><?php echo $value['Ticket']['type']; ?></td>
                                <td><?php echo $value['Ticket']['status']; ?></td>
                                <td><?php echo date('d-M-Y',strtotime($value['Ticket']['created'])); ?></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>



<div class="modal fade" id="myModalForm" role="dialog">

    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <?php echo $this->Form->create('Ticket',array( 'class'=>'form','id'=>'form')); ?>

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add Ticket</h4>
            </div>
            <div class="modal-body">

                <div class="form-group">
                    <?php echo $this->Form->input('title', array('type'=>'text','label'=>false,'required'=>true,'placeholder'=>'Write Ticket Title','class'=>'form-control ',)); ?>
                    <div class="show_err"></div>
                </div>
                <div class="form-group">
                    <?php echo $this->Form->input('description', array('type'=>'textarea','label'=>false,'required'=>true,'placeholder'=>'Write Ticket Title','class'=>'form-control ','style'=>(array('box-shadow'=>'0px 0px 0px','border-radius'=>'0px','border'=>'0px none')))); ?>
                    <div class="show_err"></div>
                </div>
                <div class="form-group">
                    <?php
                    $option = array('QUESTION'=>'QUESTION','INCIDENT'=>'INCIDENT','PROBLEM'=>'PROBLEM','TASK'=>'TASK');
                    echo $this->Form->input('type', array('type'=>'select','options'=>$option,'label'=>false,'required'=>true,'empty'=>'Select Ticket Type','class'=>'form-control ')); ?>
                    <div class="show_err"></div>
                </div>


            </div>
            <div class="modal-footer">
                <?php echo $this->Form->submit('Add Ticket',array('class'=>'Btn-typ3','id'=>'addTicketSubmit')); ?>
            </div>
            <?php echo $this->Form->end(); ?>
        </div>
    </div>

</div>









<script>
    $(document).ready(function () {
        $(document).on('click',"#viewRecentTickets",function (e) {
            e.preventDefault();
            $("#myModal").modal('show');
        });

        $(document).on('click',"#viewFavouriteTickets",function (e) {
            e.preventDefault();
            $("#myModalFavourite").modal('show');
        });


        $(document).on('click','#addTicket',function(e){
            $("#form").trigger('reset');
            $("#myModalForm").modal('show');
        });

        $(document).on('submit','#form',function(e){
            e.preventDefault();
            e.stopPropagation();
            var sendData = $( this ).serialize();

            $.ajax({
                url: baseurl+'app_admin/add_ticket',
                data:sendData,
                type:'POST',
                success: function(result){
                    var data = JSON.parse(result);

                    if(data.status == 1)
                    {
                        $("#myModalForm").modal('hide');
                        location.reload();
                    }
                    else
                    {
                        alert(data.message);
                    }
                }
            });
        });


    });
</script>