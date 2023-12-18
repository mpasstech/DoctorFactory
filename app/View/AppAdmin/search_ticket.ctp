<?php
$login = $this->Session->read('Auth.User');
?>

<div class="Inner-banner">
    <div class="container">
        <div class="row">
            <!--  SLIDER IMAGE -->
            <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 inner-page-text"> <h2>Ticket List</h2> </div>
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
                    <?php echo $this->element('app_admin_leftsidebar'); ?>
                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">



                         <div class="Social-login-box">

                            <?php echo $this->element('message'); ?>


                             <?php echo $this->Form->create('Search',array('type'=>'get','url'=>array('controller'=>'app_admin','action' => 'search_ticket_form'),'admin'=>true)); ?>


                             <div class="form-group">
                                 <div class="col-sm-4">

                                     <?php echo $this->Form->input('username', array('type' => 'text', 'placeholder' => 'Insert username', 'label' => 'Search by username', 'class' => 'form-control')); ?>
                                 </div>
                                 <div class="col-sm-4">

                                     <?php echo $this->Form->input('topic', array('type' => 'text', 'placeholder' => 'Insert titel', 'label' => 'Search by Title', 'class' => 'form-control')); ?>
                                 </div>
                                 <div class="col-sm-2">
                                     <?php echo $this->Form->label('&nbsp;'); ?>
                                     <?php echo $this->Form->submit('Search',array('class'=>'Btn-typ3','id'=>'search')); ?>
                                 </div>
                                 <div class="col-sm-2">
                                     <?php echo $this->Form->label('&nbsp;'); ?>
                                     <div class="submit">
                                         <a href="<?php echo $this->Html->url(array('controller'=>'app_admin','action'=>'search_ticket')) ?>"><button type="button" class="Btn-typ3" >Reset</button></a>
                                     </div>
                                 </div>

                             </div>

                             <?php echo $this->Form->end(); ?>



                            <div class="form-group row">
                                <div class="col-sm-12">

                            <div class="table-responsive">
                            <?php if(!empty($searchedTickets)){ ?>
                                <table class="table" >
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Created By</th>
                                        <th>Title</th>
                                        <th>Description</th>
                                        <th>Media</th>
                                        <th>Type</th>
                                        <th>Created</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                        <th>Favourite</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $n = 1;
                                    foreach ($searchedTickets as $value){ ?>
                                        <tr>
                                            <td><?php echo $n++; ?></td>
                                            <td><?php echo $value['CreatedBy']['username']; ?></td>
                                            <td><?php echo $value['Ticket']['title']; ?></td>
                                            <td><?php echo mb_strimwidth($value['Ticket']['description'], 0, 50, '...'); ?></td>
                                            <td><?php if( !empty($value['Ticket']['media']) ){ ?>
                                                    <a href="<?php echo $value['Ticket']['media']; ?>" target="_blank" download><i class="fa fa-download"></i></a>
                                                <?php } ?>
                                            </td>
                                            <td><?php echo $value['Ticket']['type']; ?></td>
                                            <td><?php echo date('d-M-Y',strtotime($value['Ticket']['created'])); ?></td>
                                            <td><?php echo $value['Ticket']['status']; ?></td>
                                            <td class="ticket_list_btn">
                                                <button type="button" id="viewThis" ticket-id="<?php echo $value['Ticket']['id']; ?>" class="btn btn-primary btn-xs" title="View ticket"><i class="fa fa-eye"></i></button>&nbsp;
                                                <button type="button" id="updateStatus" ticket-id="<?php echo $value['Ticket']['id']; ?>" class="btn btn-primary btn-xs" title="Update ticket status"><i class="fa fa-pencil"></i></button>&nbsp;
                                                <button type="button" id="viewHistory" ticket-id="<?php echo $value['Ticket']['id']; ?>" class="btn btn-primary btn-xs" title="View history"><i class="fa fa-list"></i></button>&nbsp;
                                            </td>
                                            <td>
                                                <button type="button" id="changeIsFavourite" ticket-id="<?php echo $value['Ticket']['id']; ?>" class="btn btn-warning btn-xs fav_btn"><?php echo ($value['Ticket']['is_favourite'] == 'Y')?'<i class="fa fa-star"></i>':'<i class="fa fa-star-o"></i>'; ?></button>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                    </tbody>
                                </table>
                                <?php echo $this->element('paginator'); ?>

                        </div>
                            <?php }else{ ?>
                                <div class="no_data">
                                    <h2>No tickets found...</h2>
                                </div>
                            <?php } ?>
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



<div class="modal fade" id="myModalView" role="dialog">

    <div class="modal-dialog modal-sm">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">View Ticket</h4>
            </div>
            <div class="modal-body">

                <div class="table-responsive">
                    <table class="table" id="viewTicketTable">

                    </table>
                </div>

            </div>
        </div>
    </div>

</div>


<div class="modal fade" id="myModalViewHistory" role="dialog">

    <div class="modal-dialog modal-sm">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">View Ticket History</h4>
            </div>
            <div class="modal-body">

                <div class="table-responsive">
                    <table class="table" id="viewTicketHistoryTable">

                    </table>
                </div>

            </div>
        </div>
    </div>

</div>



<div class="modal fade" id="myModal" role="dialog">

    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <?php echo $this->Form->create('Ticket',array( 'class'=>'form','id'=>'form')); ?>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Update Status</h4>
            </div>
            <div class="modal-body">

                <div class="form-group">
                    <?php echo $this->Form->input('comment', array('type'=>'textarea','label'=>false,'required'=>true,'placeholder'=>'Write Your Comment Here','class'=>'form-control ','id'=>'comment','style'=>(array('box-shadow'=>'0px 0px 0px','border-radius'=>'0px','border'=>'0px none')))); ?>
                    <input type="hidden" name="data[Ticket][id]" id="ticketID" >
                    <div class="show_err"></div>
                </div>
                <div class="form-group">
                    <?php
                    $options = array('OPEN'=>'OPEN','INPROGRESS'=>'IN PROGRESS','SOLVED'=>'SOLVED','CANCELLED'=>'CANCELLED');
                    echo $this->Form->input('status', array('type'=>'select','label'=>false,'required'=>true,'options'=>$options,'class'=>'form-control ','id'=>'status')); ?>
                </div>


            </div>
            <div class="modal-footer">
                <?php echo $this->Form->submit('Update',array('class'=>'Btn-typ3','id'=>'reply')); ?>
            </div>
            <?php echo $this->Form->end(); ?>
        </div>
    </div>

</div>




<script>
    $(document).ready(function () {

                $(document).on('click','#viewThis',function (e) {
                        e.preventDefault();

                        var $btn = $(this);
                        var ticketID = $(this).attr('ticket-id');
                            $.ajax({
                                url: baseurl+'/app_admin/view_ticket',
                                data:{ticketID:ticketID},
                                type:'POST',
                                dataType:'html',
                                beforeSend:function () {
                                    var path = baseurl+"images/ajax-loading-small.png";
                                    $btn.button('loading').html("<img src="+path+">");
                                },
                                success: function(result){

                                    $btn.button('reset');
                                    $("#viewTicketTable").html(result);
                                    $("#myModalView").modal('show');
                                },
                                error:function () {
                                    $btn.button('reset');
                                }
                            });
                });

                $(document).on('click','#viewHistory',function (e) {
                    e.preventDefault();
                    var $btn = $(this);
                    var ticketID = $(this).attr('ticket-id');
                    $.ajax({
                        url: baseurl+'/app_admin/view_ticket_history',
                        data:{ticketID:ticketID},
                        type:'POST',
                        dataType:'html',
                        beforeSend:function () {
                            var path = baseurl+"images/ajax-loading-small.png";
                            $btn.button('loading').html("<img src="+path+">");
                        },
                        success: function(result){
                            $("#viewTicketHistoryTable").html(result);
                            $("#myModalViewHistory").modal('show');
                            $btn.button('reset');
                        },
                        error:function () {
                            $btn.button('reset');
                        }
                    });
                });



                $(document).on('click','#updateStatus',function (e) {
                        e.preventDefault();

                        var ticketID = $(this).attr('ticket-id');
                        $('#form').trigger('reset');
                        $("#ticketID").val(ticketID);
                        $("#myModal").modal('show');
                });
        
                $(document).on('submit','#form',function (e) {
                        e.preventDefault();
                        e.stopPropagation();
                        var $btn = $(this).find('input[type=submit]');
                        var sendData = $( this ).serialize();
                        $.ajax({
                            url: baseurl+'/app_admin/update_ticket_status',
                            data:sendData,
                            type:'POST',
                            beforeSend:function () {
                                $btn.button('loading').val('Wait..');
                            },
                            success: function(result){
                                var data = JSON.parse(result);
                                if(data.status == 1)
                                {
                                    $("#myModal").modal('hide');
                                    location.reload();
                                }
                                else
                                {
                                    alert(data.message);
                                }
                                $btn.button('reset');
                            },
                            error:function () {
                                $btn.button('reset');
                            }
                        });
                });

        $(document).on('click','#changeIsFavourite',function (e) {
            e.preventDefault();
            var ticketID = $(this).attr('ticket-id');
            var thisButton = $(this);
            var $btn = $(this);
            $.ajax({
                url: baseurl+'app_admin/change_is_favourite_ticket',
                data:{ticketID:ticketID},
                type:'POST',
                beforeSend:function () {
                    var path = baseurl+"images/ajax-loading-small.png";
                    $btn.button('loading').html("<img src="+path+">");
                },
                success: function(result){
                    $btn.button('reset');
                    var data = JSON.parse(result);
                    if(data.status == 1)
                    {   if(data.text=="Yes"){
                            $(thisButton).html('<i class="fa fa-star"></i>');
                        }else{
                            $(thisButton).html('<i class="fa fa-star-o"></i>');
                        }
                    }
                    else
                    {
                        alert(data.message);
                    }

                },
                error:function () {
                    $btn.button('reset');
                }
            });
        });


    });
</script>