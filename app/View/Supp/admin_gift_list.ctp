<div class="Inner-banner">
    <div class="container">
        <div class="row">

            <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 inner-page-text"> <h2>Gift List</h2> </div>
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
                            <a href="<?php echo Router::url('/admin/supp/gift_list'); ?>"  class="active" ><i class="fa fa-list"></i> Gift List</a>
                            <a href="<?php echo Router::url('/admin/supp/gift_redeem_list'); ?>"><i class="fa fa-list"></i> Redeem</a>
                            <a href="<?php echo Router::url('/admin/supp/add_gift'); ?>" ><i class="fa fa-list"></i> Add Gift</a>
                        </div>


                        <div class="Social-login-box payment_bx">
                            <?php echo $this->element('message'); ?>
                            <?php echo $this->Form->create('Search',array('type'=>'get','url'=>array('controller'=>'supp','action' => 'search_gift_list'),'admin'=>true)); ?>
                            <div class="form-group">
                                <div class="col-sm-4">
                                    <?php echo $this->Form->input('gift_name', array('type' => 'text', 'placeholder' => 'Insert gift name', 'label' => 'Search by gift name', 'class' => 'form-control')); ?>
                                </div>
                                <div class="col-sm-2">
                                    <?php echo $this->Form->label('&nbsp;'); ?>
                                    <?php echo $this->Form->submit('Search',array('class'=>'Btn-typ3','id'=>'search')); ?>
                                </div>
                                <div class="col-sm-2">
                                    <?php echo $this->Form->label('&nbsp;'); ?>
                                    <div class="submit">
                                        <a href="<?php echo $this->Html->url(array('controller'=>'supp','action'=>'gift_list')) ?>"><button type="button" class="Btn-typ3" >Reset</button></a>
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
                                        <th>Gift Name</th>
                                        <th>Description</th>
                                        <th>End Time</th>
                                        <th>Created</th>
                                        <th>Quantity</th>
                                        <th>Redeems</th>
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
                                            <td><?php echo $value['Gift']['gift_name']; ?></td>
                                            <td><?php echo $value['Gift']['gift_description']; ?></td>
                                            <td><?php echo date('d-M-Y H:i:s',strtotime($value['Gift']['end_datetime'])); ?></td>
                                            <td><?php echo date('d-M-Y H:i:s',strtotime($value['Gift']['created'])); ?></td>
                                            <td><?php echo $value['Gift']['quantity']; ?></td>
                                            <td><?php echo $value['Gift']['redeem_count']; ?></td>
                                            <td class="td_links">
                                                <div class="action_icon" style="display:flex;">
                                                    <a href="<?php echo $this->Html->url(array('controller'=>'supp','action'=>'edit_gift',base64_encode($value['Gift']['id']))); ?>" >
                                                        <button type="button" class="btn btn-primary btn-xs"><i class="fa fa-edit fa-2x"></i></button>
                                                    </a>
                                                    &nbsp;<button type="button" id="ViewGift" class="btn btn-primary btn-xs" row-id="<?php echo $value['Gift']['id']; ?>" ><i class="fa fa-eye fa-2x"></i></button>
                                                    &nbsp;<button type="button" id="changeStatus" class="btn btn-primary btn-xs" row-id="<?php echo $value['Gift']['id']; ?>" ><?php echo $value['Gift']['status']; ?></button>
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
                                <h2>No Gift..!</h2>
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
                <h4 class="modal-title">View Gift</h4>
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




<script>
    $(document).ready(function () {

            $(document).on('click','#changeStatus',function(e){
                var rowID = $(this).attr('row-id');
                var thisButton = $(this);
                $.ajax({
                    url: baseurl+'/admin/supp/change_gift_status',
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
                    url: baseurl+'/admin/supp/view_gift',
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


    });
</script>