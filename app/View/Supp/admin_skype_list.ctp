<div class="Inner-banner">
    <div class="container">
        <div class="row">

            <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 inner-page-text"> <h2>Skype List</h2> </div>
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

                   <?php echo $this->element('support_admin_leftsidebar'); ?>
                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">



                        <div class="Social-login-box payment_bx">

                            <?php echo $this->Form->create('Search',array('type'=>'get','url'=>array('controller'=>'supp','action' => 'search_skype_list'),'admin'=>true)); ?>
                            <div class="form-group">
                                <div class="col-sm-4">

                                    <?php echo $this->Form->input('name', array('type' => 'text', 'placeholder' => 'Insert name', 'label' => 'Search by name', 'class' => 'form-control')); ?>
                                </div>
                                <div class="col-sm-4">

                                    <?php echo $this->Form->input('email', array('type' => 'text', 'placeholder' => 'Insert email', 'label' => 'Search by email', 'class' => 'form-control')); ?>
                                </div>
                                <div class="col-sm-2">
                                    <?php echo $this->Form->label('&nbsp;'); ?>
                                    <?php echo $this->Form->submit('Search',array('class'=>'Btn-typ3','id'=>'search')); ?>
                                </div>
                                <div class="col-sm-2">
                                    <?php echo $this->Form->label('&nbsp;'); ?>
                                    <div class="submit">
                                        <a href="<?php echo $this->Html->url(array('controller'=>'supp','action'=>'skype_list')) ?>"><button type="button" class="Btn-typ3" >Reset</button></a>
                                    </div>
                                </div>

                            </div>

                            <?php echo $this->Form->end(); ?>



                            <?php echo $this->element('message'); ?>
                            <div class="form-group row">
                                <div class="col-sm-12">
                            <div class="table-responsive">
                                <?php if(!empty($data)){ ?>
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                    <!--    <th>Message</th>-->
                                        <th>On Date</th>
                                        <th>On Time</th>
                                        <th>Skype</th>
                                        <th>Created On</th>
                                        <th>Status</th>
										<th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $login = $this->Session->read('Auth.User');
									$num = 1;
									foreach ($data as $key => $value){
                                       
                                        ?>
                                        <tr>
                                            <td><?php echo $num++; ?></td>
                                            <td><?php echo $value['AppEnquiry']['name']; ?></td>
                                            <td><?php echo $value['AppEnquiry']['email']; ?></td>
                                            <td><?php echo $value['AppEnquiry']['phone']; ?></td>
                                          <!--  <td><?php /*echo mb_strimwidth($value['AppEnquiry']['message'], 0, 50, '...'); */?></td>-->
                                            <td><?php echo $value['AppEnquiry']['on_date']; ?></td>
                                            <td><?php echo $value['AppEnquiry']['on_time']; ?></td>
                                            <td><?php echo $value['AppEnquiry']['skype_id']; ?></td>
                                            <td><?php echo date("d-M-Y H:i:s",strtotime($value['AppEnquiry']['created'])); ?></td>
                                            <td>
                                                <?php if($value['AppEnquiry']['status'] == 'INPROGRESS'){ ?>
                                                    Accepted By <?php echo $value['Support']['username']; ?>
                                                <?php }
                                                else if($value['AppEnquiry']['status'] == 'COMPLETE')
                                                { ?>
                                                    Closed By <?php echo $value['Support']['username'];
                                                }
                                                else
                                                { ?>
                                                    <button type="button" id="acceptThis" enquiry-id="<?php echo $value['AppEnquiry']['id']; ?>" class="btn btn-primary btn-xs">Accept</button>
                                                <?php }
                                                if($value['AppEnquiry']['supp_admin_id'] == $login['id'] && $value['AppEnquiry']['status'] == 'INPROGRESS')
                                                { ?>
                                                    <button type="button" id="closeThis" enquiry-id="<?php echo $value['AppEnquiry']['id']; ?>" class="btn btn-primary btn-xs">Close</button>
                                                <?php } ?>
                                            </td>

                                            <td>

                                                <?php
                                                if($value['AppEnquiry']['supp_admin_id'] == $login['id']) {
                                                    echo $this->Html->link('', "javascript:void(0)",
                                                        array('class' => 'fa fa-reply', 'reply', 'title' => 'Reply By Email', 'row-id' => $value['AppEnquiry']['id']));
                                                }
                                                ?>

                                                <?php
                                                echo $this->Html->link('', "javascript:void(0)",
                                                    array('class' => 'fa fa-eye','view','title' => 'View Reply', 'row-id' => $value['AppEnquiry']['id']));

                                                ?>



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
                                <h2>No skype request..!</h2>
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


<div class="modal fade" id="myModal" role="dialog">

    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <?php echo $this->Form->create('AppEnquiryReply',array( 'class'=>'form','id'=>'form')); ?>

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Reply</h4>
            </div>
            <div class="modal-body">

                <div class="form-group">
                    <?php echo $this->Form->input('reply', array('type'=>'textarea','label'=>false,'required'=>true,'placeholder'=>'Write Your Message here','class'=>'form-control','id'=>'reply','style'=>(array('box-shadow'=>'0px 0px 0px','border-radius'=>'0px','border'=>'0px none')))); ?>
                    <input type="hidden" name="data[AppEnquiryReply][rowID]" id="rowID" >
                    <div class="show_err"></div>
                </div>

            </div>
            <div class="modal-footer">
                <?php echo $this->Form->button('Reply',array('type'=>'submit','class'=>'Btn-typ3','id'=>'replyBtn')); ?>
            </div>
            <?php echo $this->Form->end(); ?>
        </div>
    </div>

</div>




<div class="modal fade" id="myModalView" role="dialog">

    <div class="modal-dialog modal-sm">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">View Reply</h4>
            </div>
            <div class="modal-body">

                <div class="table-responsive">
                    <table class="table" id="viewReplyTable">

                    </table>
                </div>

            </div>
        </div>
    </div>

</div>









<script>
    $(document).ready(function(){
        $(document).on('click','.fa-reply',function(e){
            var rowID = $(this).attr('row-id');
            $("#rowID").val(rowID);
            $("#reply").val('');
            $(".show_err").html('');
            $("#myModal").modal('show');
        });
        $(document).on('submit','#form',function(e){
            e.preventDefault();
            e.stopPropagation();
            var sendData = $( this ).serialize();
            var thisBtn = $("#replyBtn");
            $.ajax({
                url: baseurl+'/admin/supp/reply_enquiry_message',
                data:sendData,
                type:'POST',
                beforeSend:function () {
                    $( thisBtn ).button('loading').html('<i class="fa fa-spinner fa-pulse fa-2x fa-fw">');
                },
                success: function(result){
                    $( thisBtn ).button('reset');
                    var data = JSON.parse(result);
                    if(data.status == 1)
                    {
                        $("#myModal").modal('hide')
                    }
                    else
                    {
                        $(".show_err").html(data.message);
                    }

                }
            });

        });
        $(document).on('click','#acceptThis',function(e) {
            var conf = confirm("Do you want to accept this task?");
            if(conf == false){ return false; }
            var rowID = $(this).attr('enquiry-id');
            var thisButton = $(this);

            $.ajax({
                url: baseurl+'/admin/supp/accept_inquiry',
                data:{rowID:rowID},
                type:'POST',
                beforeSend:function () {
                    $( thisBtn ).button('loading').html('<i class="fa fa-spinner fa-pulse fa-2x fa-fw">');
                },
                success: function(result){
                    thisButton.button('reset');
                    var data = JSON.parse(result);
                    if(data.status == 1)
                    {
                        $(thisButton).parent().html(data.message).next().prepend(data.icon);
                        $(thisButton).remove();
                    }
                    else
                    {
                        alert(data.message);
                    }

                }
            });
        });

        $(document).on('click','.fa-eye',function(e){


            var rowID = $(this).attr('row-id');

            $.ajax({
                url: baseurl+'/admin/supp/view_inquiry',
                data:{rowID:rowID},
                type:'POST',
                dataType:'html',
                success: function(result){
                    $("#viewReplyTable").html(result);
                    $("#myModalView").modal('show');
                }
            });


        });

        $(document).on('click','#closeThis',function(e) {
            var conf = confirm("Do you want to close this task?");
            if(conf == false){ return false; }
            var rowID = $(this).attr('enquiry-id');
            var thisButton = $(this);
            $.ajax({
                url: baseurl+'/admin/supp/close_inquiry',
                data:{rowID:rowID},
                type:'POST',
                beforeSend:function () {
                    $( thisBtn ).button('loading').html('<i class="fa fa-spinner fa-pulse fa-2x fa-fw">');
                },
                success: function(result){
                    thisButton.button('reset');
                    var data = JSON.parse(result);
                    if(data.status == 1)
                    {
                        $(thisButton).parent().html(data.message);
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