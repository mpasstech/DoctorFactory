<style>.td_links button{ margin: 1px; }</style>
<div class="Inner-banner">
    <div class="container">
        <div class="row">

            <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 inner-page-text"> <h2>Contest List</h2> </div>
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
                        <div class="progress-bar channel_tap">
                            <a href="<?php echo Router::url('/admin/supp/list_contest'); ?>"  class="active" ><i class="fa fa-list"></i> Contest List</a>
                            <a href="<?php echo Router::url('/admin/supp/add_contest'); ?>" ><i class="fa fa-list"></i> Add Contest</a>
                        </div>


                        <div class="Social-login-box payment_bx">
                            <?php echo $this->element('message'); ?>
                            <?php echo $this->Form->create('Search',array('type'=>'get','url'=>array('controller'=>'supp','action' => 'search_contest'),'admin'=>true)); ?>
                            <div class="form-group">
                                <div class="col-sm-4">
                                    <?php echo $this->Form->input('contest', array('type' => 'text', 'placeholder' => 'Insert contest title', 'label' => 'Search by contest title', 'class' => 'form-control')); ?>
                                </div>
                                <div class="col-sm-2">
                                    <?php echo $this->Form->label('&nbsp;'); ?>
                                    <?php echo $this->Form->submit('Search',array('class'=>'Btn-typ3','id'=>'search')); ?>
                                </div>
                                <div class="col-sm-2">
                                    <?php echo $this->Form->label('&nbsp;'); ?>
                                    <div class="submit">
                                        <a href="<?php echo $this->Html->url(array('controller'=>'supp','action'=>'list_contest')) ?>"><button type="button" class="Btn-typ3" >Reset</button></a>
                                    </div>
                                </div>
                            </div>
                            <?php echo $this->Form->end(); ?>




                            <div class="form-group row">
                                <div class="col-sm-12">
                            <div class="table-responsive">
                                <?php if(!empty($data)){ ?>
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Title</th>
                                        <th>Start Time</th>
                                        <th>End Time</th>
                                        <th>Contest Type</th>
                                        <th>Responses</th>
                                        <th>Views</th>
                                        <th>Created</th>
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
                                            <td><?php echo $value['Contest']['title']; ?></td>
                                            <td><?php echo date('d-M-Y H:i:s',strtotime($value['Contest']['start_time'])); ?></td>
                                            <td><?php echo date('d-M-Y H:i:s',strtotime($value['Contest']['end_time'])); ?></td>
                                            <td><?php echo $value['Contest']['contest_type']; ?></td>
                                            <td><?php echo $value['Contest']['response_count']; ?></td>
                                            <td><?php echo $value['Contest']['view_count']; ?></td>
                                            <td><?php echo date('d-M-Y H:i:s',strtotime($value['Contest']['created'])); ?></td>
                                            <td class="td_links">
                                                <div class="action_icon">
                                                    <a href="<?php echo $this->Html->url(array('controller'=>'supp','action'=>'edit_contest',base64_encode($value['Contest']['id']))); ?>" >
                                                        <button type="button" class="btn btn-primary btn-xs"><i class="fa fa-edit fa-2x"></i></button>
                                                    </a>
                                                     <button type="button" id="ViewGift" class="btn btn-primary btn-xs" row-id="<?php echo $value['Contest']['id']; ?>" ><i class="fa fa-eye fa-2x"></i></button>
                                                     <button type="button" id="changeStatus" class="btn btn-primary btn-xs" row-id="<?php echo $value['Contest']['id']; ?>" ><?php echo $value['Contest']['status']; ?></button>
                                                    <?php if($value['Contest']['contest_type'] == "MULTIPLE_CHOICE"){ ?>
                                                     
                                                    <a href="<?php echo $this->Html->url(array('controller'=>'supp','action'=>'edit_contest_que',base64_encode($value['Contest']['id']))); ?>" >
                                                        <button type="button" class="btn btn-primary btn-xs"><i class="fa fa-question-circle-o fa-2x"></i></button>
                                                    </a>
                                                    <?php } ?>
                                                     <button type="button" id="viewResponse" class="btn btn-primary btn-xs" row-id="<?php echo $value['Contest']['id']; ?>" >RESPONSE</button>
													 <a href="<?php echo $this->Html->url(array('controller'=>'supp','action'=>'update_contest_winner',base64_encode($value['Contest']['id']))); ?>" >
                                                        <button type="button" class="btn btn-primary btn-xs"><i class="fa fa-trophy fa-2x"></i></button>
                                                    </a>
                                                </div>
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
                                <h2>No Contest..!</h2>
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




<div class="modal fade" id="myModalView" role="dialog">

    <div class="modal-dialog modal-sm">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">View Contest</h4>
            </div>
            <div class="modal-body">

                <div class="table-responsive">
                    <table class="table" id="viewLeadTable">

                    </table>
                </div>

            </div>
        </div>
    </div>

</div>

<div class="modal fade" id="myContestView" role="dialog">

    <div class="modal-dialog modal-sm">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">View Response</h4>
            </div>
            <div class="modal-body">

                <div class="table-responsive">
                    <table class="table" id="viewResponseTable">

                    </table>
                </div>

            </div>
        </div>
    </div>

</div>

<script>
    $(document).ready(function () {

            $(document).on('click','#changeStatus',function(e){
                var rowID = $(this).attr('row-id');
                var thisButton = $(this);
                $.ajax({
                    url: baseurl+'/admin/supp/change_contest_status',
                    data:{rowID:rowID},
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

            $(document).on('click','#ViewGift',function(e){
                var rowID = $(this).attr('row-id');
                var thisButton = $(this);
                $.ajax({
                    url: baseurl+'/admin/supp/view_contest',
                    data:{rowID:rowID},
                    type:'POST',
                    beforeSend:function(){
                        $(thisButton).button('loading').html('<i class="fa fa-spinner fa-pulse fa-2x fa-fw">');
                    },
                    success: function(result){
                        $(thisButton).button('reset');
                        $("#viewLeadTable").html(result);
                        $("#myModalView").modal('show');
                    }
                });
            });

            $(document).on('click','#viewResponse',function(e){
                var rowID = $(this).attr('row-id');
                var thisButton = $(this);
                $.ajax({
                    url: baseurl+'/admin/supp/view_response',
                    data:{rowID:rowID},
                    type:'POST',
                    beforeSend:function(){
                        $(thisButton).button('loading').html('<i class="fa fa-spinner fa-pulse fa-2x fa-fw">');
                    },
                    success: function(result){
                        $(thisButton).button('reset');
                        $("#viewResponseTable").html(result);
                        $("#myContestView").modal('show');
                    }
                });
            });


    });
</script>