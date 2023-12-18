<style>.td_links button{ margin: 1px; }</style>
<div class="Inner-banner">
    <div class="container">
        <div class="row">

            <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 inner-page-text"> <h2>Doctor Referral</h2> </div>
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
                    
                                                <a id="v_app_channel_list" class='active' href="<?php echo Router::url('list_referral_users'); ?>"><i class="fa fa-list"></i> List Doctor Referral</a>
                    
                                                <a id="v_add_channel"   href="<?php echo Router::url('add_referral_users'); ?>" ><i class="fa fa-television"></i> Add Doctor Referral</a>
                    
                    
                                            </div>
                                            <style>
                                                .channel_tap a{ width:33% !important; }
                                            </style>


                        <div class="Social-login-box payment_bx">
                            <?php echo $this->element('message'); ?>
                            <?php echo $this->Form->create('Search',array('type'=>'get','url'=>array('controller'=>'supp','action' => 'search_referral'),'admin'=>true)); ?>
                            <div class="form-group">
                                <div class="col-sm-8">
                                    <?php echo $this->Form->input('referral', array('type' => 'text', 'placeholder' => 'Insert referral name/mobile/thinapp/status', 'label' => 'Insert referral name/mobile/thinapp', 'class' => 'form-control')); ?>
                                </div>
                                <div class="col-sm-2">
                                    <?php echo $this->Form->label('&nbsp;'); ?>
                                    <?php echo $this->Form->submit('Search',array('class'=>'Btn-typ3','id'=>'search')); ?>
                                </div>
                                <div class="col-sm-2">
                                    <?php echo $this->Form->label('&nbsp;'); ?>
                                    <div class="submit">
                                        <a href="<?php echo $this->Html->url(array('controller'=>'supp','action'=>'list_referral_users')) ?>"><button type="button" class="Btn-typ3" >Reset</button></a>
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
                                        <th>Referred Name</th>
                                        <th>Referred Mobile</th>
                                        <th>Status</th>
										<th>Already Referred</th>
                                        <th>Paid Amount</th>
                                        <th>Referral Points</th>
										<th>User Points</th>
                                        <th>Mobile</th>
                                        <th>Thinapp</th>
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
                                            <td><?php echo $value['DoctorRefferal']['reffered_name']; ?></td>
                                            <td><?php echo $value['DoctorRefferal']['reffered_mobile']; ?></td>
                                            <td><?php echo $value['DoctorRefferal']['status']; ?></td>
											<td><?php echo $value['DoctorRefferal']['is_reffered_already']; ?></td>
                                            <td><?php echo $value['DoctorRefferal']['total_amount']; ?></td>
                                            <td><?php echo $value['DoctorRefferal']['total_refferal_points']; ?></td>
											<td><?php echo $value['DoctorRefferalUser']['refferal_points']; ?></td>
                                            <td><?php echo $value['User']['mobile']; ?></td>
                                            <td><?php echo $value['Thinapp']['name']; ?></td>
                                            <td><?php echo date('d-M-Y H:i:s',strtotime($value['DoctorRefferal']['created'])); ?></td>
                                            <td class="td_links">
                                                <div class="action_icon">
                                                <?php if( !in_array(  $value['DoctorRefferal']['status'] , array('CONVERTED','PAID'))){ ?>
                                                     <button type="button" id="changeStatus" status="CONVERTED" class="btn btn-primary btn-xs" row-id="<?php echo $value['DoctorRefferal']['id']; ?>" >Converted</button>
                                                <?php } ?>
                                                <?php if( !in_array(  $value['DoctorRefferal']['status'] , array('DENIED','PAID'))){ ?>
                                                    <button type="button" id="changeStatus" status="DENIED" class="btn btn-primary btn-xs" row-id="<?php echo $value['DoctorRefferal']['id']; ?>" >Denied</button>
													<button type="button" id="changeStatus" status="CONTACTED" class="btn btn-primary btn-xs" row-id="<?php echo $value['DoctorRefferal']['id']; ?>" >Contacted</button>
                                                
                                                    <button type="button" id="addPayment" class="btn btn-primary btn-xs" row-id="<?php echo $value['DoctorRefferal']['id']; ?>" >Paid</button>
													
                                                <?php } ?>     
                                                     
                                                     <button type="button" id="view" class="btn btn-primary btn-xs" row-id="<?php echo $value['DoctorRefferal']['id']; ?>" >View</button>
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
                                <h2>No Referrals..!</h2>
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
                <h4 class="modal-title">View Referral</h4>
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



<div class="modal fade" id="addPaymentView" role="dialog">

    <div class="modal-dialog modal-sm">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add Payment</h4>
            </div>
                        <form id="addPaymentForm" method="POST">
                        
                                    <div class="modal-body">
                                        <div class="table-responsive">
                        
                                                <div class="form-group">
                                                    <div class="col-sm-12">
                                                        <input type="number" name="amount" id="paymentInput" placeholder="Please enter amount" class="form-control cnt" required>
                                                        <input type="hidden" name="id" id="idHolder" required>
                                                    </div>
                                                </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                        
                                        <div class="form-group">
                                            <div class="col-sm-12">
                                                <button type="submit" name="submitForm" class="form-control" id="thisBtn" >Add Payment</button>
                                            </div>
                                        </div>
                        
                                    </div>
            
                        </form>
        </div>
    </div>

</div>



<div class="modal fade" id="changeStatusModal" role="dialog">

    <div class="modal-dialog modal-sm">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Change Status</h4>
            </div>
                        <form id="changeStatusForm" method="POST">
                        
                                    <div class="modal-body">
                                        <div class="table-responsive">
                        
                                                <div class="form-group">
                                                    <div class="col-sm-12">
                                                        <textarea name="comment" id="commentText" placeholder="Please enter comment" class="form-control cnt" required></textarea>
														<input type="hidden" name="statusType" id="statusType" required>
														<input type="hidden" name="statusID" id="statusID" required>
                                                    </div>
                                                </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                        
                                        <div class="form-group">
                                            <div class="col-sm-12">
                                                <button type="submit" name="submitForm" class="form-control" id="changeStatusBtn" >Change Status</button>
                                            </div>
                                        </div>
                        
                                    </div>
            
                        </form>
        </div>
    </div>

</div>



<script>
    $(document).ready(function () {

            /*$(document).on('click','#changeStatus',function(e){
                var rowID = $(this).attr('row-id');
                var status = $(this).attr('status');
                var thisButton = $(this);
                $.ajax({
                    url: baseurl+'/admin/supp/change_referral_status',
                    data:{rowID:rowID,status:status},
                    type:'POST',
                    beforeSend:function(){
                        $(thisButton).button('loading').html('<i class="fa fa-spinner fa-pulse fa-2x fa-fw">');
                    },
                    success: function(result){
                        $(thisButton).button('reset');
                        var result = JSON.parse(result);
                        if(result.status == 1)
                        {
                            location.reload(true)
                        }
                        else
                        {
                            alert('Sorry, Could not change status!');
                        }
                    }
                });
            }); */
			
			
			
			$(document).on('click','#changeStatus',function(e){
                var rowID = $(this).attr('row-id');
                var statusType = $(this).attr('status');
				$("#changeStatusModal").modal('show');
				$("#statusType").val(statusType);
				$("#statusID").val(rowID);
			});	
			
			
			$(document).on("submit","#changeStatusForm",function(e){
				e.stopPropagation();
                e.preventDefault();
				
				
				var status = $("#statusType").val();
				var rowID = $("#statusID").val();
				var commentText = $("#commentText").val();
                var thisButton = $("#changeStatusBtn");
                $.ajax({
                    url: baseurl+'/admin/supp/change_referral_status',
                    data:{rowID:rowID,status:status,comment:commentText},
                    type:'POST',
                    beforeSend:function(){
                        $(thisButton).button('loading').html('<i class="fa fa-spinner fa-pulse fa-2x fa-fw">');
                    },
                    success: function(result){
						$("#changeStatusModal").modal('hide');
                        $(thisButton).button('reset');
                        var result = JSON.parse(result);
                        if(result.status == 1)
                        {
                            location.reload(true)
                        }
                        else
                        {
                            alert('Sorry, Could not change status!');
                        }
                    }
                });
				
				
				
			});
			
			
			
			

            $(document).on('click','#view',function(e){
                var rowID = $(this).attr('row-id');
                var thisButton = $(this);
                $.ajax({
                    url: baseurl+'/admin/supp/view_referral',
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

            $(document).on('click','#addPayment',function(e){
                var rowID = $(this).attr('row-id');
                $("#idHolder").val(rowID);
                $("#paymentInput").val();
                $("#addPaymentView").modal('show');
            });

            $(document).on('submit','#addPaymentForm',function(e){
                e.stopPropagation();
                e.preventDefault();
                var amount = $('#paymentInput').val();
                var ID = $('#idHolder').val();
                var dataToSend = {amount:amount, id:ID};
                var thisButton = $(this);
                $.ajax({
                    url: baseurl+'/admin/supp/change_referral_payment',
                    data:dataToSend,
                    type:'POST',
                    beforeSend:function(){
                        $(thisButton).button('loading').html('<i class="fa fa-spinner fa-pulse fa-2x fa-fw">');
                    },
                    success: function(result){
                        $(thisButton).button('reset');
                        var result = JSON.parse(result);
                        if(result.status == 1)
                        {
                            location.reload(true)
                        }
                        else
                        {
                            alert('Sorry, Could not add payment!');
                        }
                    }
                });

            });

    });
</script>