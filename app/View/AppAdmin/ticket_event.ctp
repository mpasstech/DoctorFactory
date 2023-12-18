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
                        <?php echo $this->element('admin_inner_tab_ticket'); ?>
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
                                        <th>Option</th>
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
                                            <td>
                                                <button type="button" id="editEvent" class="btn btn-primary btn-xs" row-id="<?php echo $value['EventTicket']['id']; ?>" ><i class="fa fa-edit fa-2x"></i></button>
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



<div class="modal fade" id="editTicketModal" role="dialog">

    <div class="modal-dialog modal-sm">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Edit Ticket</h4>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <form id="editTicketForm" method="POST">

                        <div class="form-group">
                            <div class="col-sm-12">
                                <input type="text" name="title" id="titleHolder" placeholder="Please enter Title" class="form-control cnt">
                                <input type="hidden" name="id" id="ticketIDholder">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <input type="text" name="sub_title" id="subTitleHolder" placeholder="Please enter sub title" class="form-control cnt" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <input type="text" name="amount" id="amountHolder" placeholder="Please enter amount" class="form-control cnt" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-12">
                                <button type="submit" id="editBtn" name="submitForm" class="form-control" >Edit Ticket</button>
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
    $(document).ready(function(){
        $(document).on('click','#changeStatus',function(e){
            var ID = $(this).attr('row-id');
            var thisButton = $(this);
            $.ajax({
                url: baseurl+'/app_admin/change_event_ticket_status',
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
                        $(thisButton).text(result.text);
                    }
                    else
                    {
                        alert('Sorry, Could not change status!');
                    }
                }
            });
        });

        $(document).on('click','#editEvent',function(){
            var ID = $(this).attr('row-id');
            var thisButton = $(this);
            $.ajax({
                url: baseurl+'/app_admin/get_event_edit',
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
                        $("#titleHolder").val(result.data.title);
                        $("#subTitleHolder").val(result.data.sub_title);
                        $("#ticketIDholder").val(result.data.id);
                        $("#amountHolder").val(result.data.amount);
                        $("#editTicketModal").modal('show');
                    }
                    else
                    {
                        alert('Sorry, Could not get event!');
                    }
                }
            });
        });

        $(document).on('submit','#editTicketForm',function (e) {
            e.stopPropagation();
            e.preventDefault();
            var dataToPost = $(this).serialize();
            var thisButton = $('#editBtn');
            $.ajax({
                url: baseurl+'/app_admin/edit_event_ticket',
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
                        $("#editTicketModal").modal('hide');
                        $("#editTicketForm").trigger('reset');
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