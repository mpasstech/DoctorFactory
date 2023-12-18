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

                            <?php echo $this->Form->create('Search',array('type'=>'get','url'=>array('controller'=>'supp','action' => 'search_redeem_list'),'admin'=>true)); ?>
                            <div class="form-group">
                                <div class="col-sm-4">

                                    <?php echo $this->Form->input('username', array('type' => 'text', 'placeholder' => 'Insert username', 'label' => 'Search by username', 'class' => 'form-control')); ?>
                                </div>
                                <!--div class="col-sm-4">

                                    <?php echo $this->Form->input('appName', array('type' => 'text', 'placeholder' => 'Insert app name', 'label' => 'Search by app name', 'class' => 'form-control')); ?>
                                </div-->
                                <div class="col-sm-2">
                                    <?php echo $this->Form->label('&nbsp;'); ?>
                                    <?php echo $this->Form->submit('Search',array('class'=>'Btn-typ3','id'=>'search')); ?>
                                </div>
                                <div class="col-sm-2">
                                    <?php echo $this->Form->label('&nbsp;'); ?>
                                    <div class="submit">
                                        <a href="<?php echo $this->Html->url(array('controller'=>'supp','action'=>'redeem_request_list')) ?>"><button type="button" class="Btn-typ3" >Reset</button></a>
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
                                        <th>User Name</th>
                                        <th>Mobile</th>
                                        <!--th>Thinapp</th-->
                                        <th>Total Coins</th>
                                        <th>Redeem Coins</th>
                                        <th>Amount</th>
                                        <th>Coin Rate</th>
                                        <th>Created</th>
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
                                            <td><?php echo $value['RedeemBy']['username']; ?></td>
                                            <td><?php echo $value['RedeemBy']['mobile']; ?></td>
                                            <!--td><?php echo $value['Thinapp']['name']; ?></td-->
                                            <td><?php echo $value['Gullak']['total_coins']; ?>&nbsp;<i class='fa fa-money'></i></td>
                                            <td><?php echo $value['CoinRedeem']['coins']; ?>&nbsp;<i class='fa fa-money'></i></td>
                                            <td><?php echo $value['CoinRedeem']['amount']; ?>&nbsp;<i class='fa fa-inr'></i></td>
                                            <td><?php echo $value['CoinRedeem']['coin_rate']; ?>/100</td>
                                            <td><?php echo date("d-M-Y H:i:s",strtotime($value['CoinRedeem']['created'])); ?></td>
                                            <td>
                                                <?php if($value['CoinRedeem']['status'] == 'INPROGRESS'){ ?>
                                                    Accepted By <?php echo $value['SupportAdmin']['username']; ?>
                                                <?php }
                                                else if($value['CoinRedeem']['status'] == 'CLOSE')
                                                { ?>
                                                    Closed By <?php echo $value['SupportAdmin']['username'];
                                                }
                                                else if($value['CoinRedeem']['status'] == 'CANCELLED')
                                                { ?>
                                                    Cancelled By <?php echo $value['SupportAdmin']['username'];
                                                }
                                                else
                                                { ?>
                                                    <button type="button" id="acceptThis" redeem-id="<?php echo $value['CoinRedeem']['id']; ?>" class="btn btn-primary btn-xs">Accept</button>
                                                <?php }
                                                if($value['CoinRedeem']['supp_admin_id'] == $login['id'] && $value['CoinRedeem']['status'] == 'INPROGRESS')
                                                { ?>
                                                    <button type="button" id="closeThis" redeem-id="<?php echo $value['CoinRedeem']['id']; ?>" class="btn btn-primary btn-xs">Close</button>
                                                    <button type="button" id="cancelThis" redeem-id="<?php echo $value['CoinRedeem']['id']; ?>" class="btn btn-primary btn-xs">Cancel</button>
                                                <?php } ?>
                                            </td>

                                            <td>
                                                <?php
                                                        echo $this->Html->link('', "javascript:void(0)",
                                                        array('class' => 'fa fa-eye','view','title' => 'View Reply', 'redeem-id' => $value['CoinRedeem']['id']));
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
                                <h2>No redeem request..!</h2>
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
                        <h4 class="modal-title">View Redeem Request</h4>
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

        $(document).on('click','.fa-eye',function(e){
            var redeemID = $(this).attr('redeem-id');
            $.ajax({
                url: baseurl+'/admin/supp/view_redeem_request',
                data:{redeemID:redeemID},
                type:'POST',
                dataType:'html',
                success: function(result){
                    $("#viewReplyTable").html(result);
                    $("#myModalView").modal('show');
                }
            });
        });


        $(document).on('click','#acceptThis',function(e) {
            var conf = confirm("Do you want to accept this task?");
            if(conf == false){ return false; }
            var redeemID = $(this).attr('redeem-id');
            var thisButton = $(this);

            $.ajax({
                url: baseurl+'/admin/supp/accept_redeem_request',
                data:{redeemID:redeemID},
                type:'POST',
                beforeSend:function () {
                  thisButton.button('loading').html('<i class="fa fa-spinner fa-pulse fa-2x fa-fw">');
                },
                success: function(result){
                    $(thisButton).button('reset');
                    var data = JSON.parse(result);
                    if(data.status == 1)
                    {
                        $(thisButton).parent().html(data.message);
                        $(thisButton).remove();
                    }
                    else
                    {
                        alert(data.message);
                    }
                    thisButton.button('reset');
                }
            });
        });

        $(document).on('click','#closeThis',function(e) {
            var conf = confirm("Do you want to pay and close this task?");
            if(conf == false){ return false; }
            var redeemID = $(this).attr('redeem-id');
            var thisButton = $(this);
            $.ajax({
                url: baseurl+'/admin/supp/close_redeem_request',
                data:{redeemID:redeemID},
                type:'POST',
                beforeSend:function () {
                    thisButton.button('loading').html('<i class="fa fa-spinner fa-pulse fa-2x fa-fw">');
                },
                success: function(result){
                    $(thisButton).button('reset');
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

        $(document).on('click','#cancelThis',function(e) {
            var conf = confirm("Do you want to cancel this request task?");
            if(conf == false){ return false; }
            var redeemID = $(this).attr('redeem-id');
            var thisButton = $(this);
            $.ajax({
                url: baseurl+'/admin/supp/cancel_redeem_request',
                data:{redeemID:redeemID},
                type:'POST',
                beforeSend:function () {
                    thisButton.button('loading').html('<i class="fa fa-spinner fa-pulse fa-2x fa-fw">');
                },
                success: function(result){
                    $(thisButton).button('reset');
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