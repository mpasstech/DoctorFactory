<?php
$login = $this->Session->read('Auth.User');
?>
<style>
    .green {
        background: lime none repeat scroll 0 0;
    }
</style>
<div class="Inner-banner">
    <div class="container">
        <div class="row">
            <!--  SLIDER IMAGE -->
            <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 inner-page-text"> <h2>Order List</h2> </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 pull-right create-btn-box">
            </div>
        </div>
    </div>
</div>

<section class="Home-section-2">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="container">
            <div class="row">
                <!--box 1 -->
                <!--box 1 -->
                <div class="middle-block">
                    <!-- Heading -->
                    <?php echo $this->element('app_admin_leftsidebar'); ?>
                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                        <div class="Social-login-box payment_bx">
                            <?php echo $this->element('message'); ?>
                            <?php echo $this->Form->create('Search',array('type'=>'get','url'=>array('controller'=>'app_admin','action' => 'search_payment_item_list_transactions'),'admin'=>true)); ?>
                                    <div class="form-group">
                                            <div class="col-sm-4">
                                                <?php echo $this->Form->input('payment_item_title', array('type'=>'text','placeholder' => 'Title', 'label' => 'Search by title', 'class' => 'form-control')); ?>
                                            </div>
                                            <div class="col-sm-4">
                                                <?php echo $this->Form->input('mobile', array('type' => 'text', 'placeholder' => 'Mobile', 'label' => 'Search by mobile', 'class' => 'form-control')); ?>
                                            </div>
                                            <div class="col-sm-4">
                                                <?php echo $this->Form->input('unique_id', array('type' => 'text', 'placeholder' => 'Unique ID', 'label' => 'Search by unique ID', 'class' => 'form-control')); ?>
                                            </div>
                                            <div class="col-sm-4">
                                                <?php echo $this->Form->input('redeem_time', array('type' => 'text', 'placeholder' => 'Redeem time', 'label' => 'Search by redeem date', 'class' => 'form-control')); ?>
                                            </div>
                                            <div class="col-sm-4">
                                                <?php echo $this->Form->input('transaction_status', array('type' => 'select', 'empty' => 'Please select', 'label' => 'Search by status', 'class' => 'form-control', 'options'=>array('SUCCESS'=>'SUCCESS','FAILED'=>'FAILED','SUSPECT'=>'SUSPECT',))); ?>
                                            </div>
                                            <div class="col-sm-2">
                                                <?php echo $this->Form->label('&nbsp;'); ?>
                                                <?php echo $this->Form->submit('Search',array('class'=>'Btn-typ3','id'=>'search')); ?>
                                            </div>
                                            <div class="col-sm-2">
                                                <?php echo $this->Form->label('&nbsp;'); ?>
                                                <div class="submit">
                                                    <a href="<?php echo $this->Html->url(array('controller'=>'app_admin','action'=>'list_payment_item_transactions')) ?>"><button type="button" class="Btn-typ3" >Reset</button></a>
                                                </div>
                                            </div>
                                    </div>
                            <?php echo $this->Form->end(); ?>
                            <div class="col-sm-2">
                                <?php echo $this->Form->label('&nbsp;'); ?>
                                <div class="submit">
                                    <a href="<?php echo $this->Html->url(array("controller" => "app_admin",
                                        "action" => "export_list_payment_item_transactions",
                                        $idForSearch,
                                        "?" => $searchToExport,)); ?>"><button type="button" class="Btn-typ3" >Export</button></a>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-12">
                                    <div class="table-responsive">
                                        <?php if(!empty($appPaymentTransactions)){ ?>
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Item Title</th>
                                                <th>Mobile</th>
                                                <th>Quantity</th>
                                                <th>Total Amount</th>
                                                <th>Unique ID</th>
                                                <th>Status</th>
                                                <th>Payment Status</th>
                                                <th>Redeem Status</th>
                                                <td>View</td>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php foreach ($appPaymentTransactions as $key => $list){
                                                ?>
                                                <tr class="<?php echo ($list['AppPaymentTransaction']['redeem_status']=="YES")?'green':''; ?>">
                                                    <td><?php echo $key+1; ?>.</td>
                                                    <td><?php echo $list['PaymentItem']['title']; ?></td>
                                                    <td><?php echo $list['AppPaymentTransaction']['mobile']; ?></td>
                                                    <td><?php echo $list['AppPaymentTransaction']['quantity']; ?></td>
                                                    <td><?php echo $list['AppPaymentTransaction']['total_amount']; ?></td>
                                                    <td><strong><?php echo $list['AppPaymentTransaction']['unique_id']; ?></strong></td>
                                                    <td>
                                                        <?php
                                                            if($list['AppPaymentTransaction']['status']=="ACTIVE"){
                                                                echo $this->Html->link('','javascript:void(0)',
                                                                    array('id'=>'AppPaymentTransactionStatus','data-id'=>base64_encode($list['AppPaymentTransaction']['id']),'class' => 'fa fa-check', 'title' => 'This order is active.'));
                                                            }else{
                                                                echo $this->Html->link('','javascript:void(0)',
                                                                    array('id'=>'AppPaymentTransactionStatus','data-id'=>base64_encode($list['AppPaymentTransaction']['id']),'class' => 'fa fa-close', 'title' => 'This order is inactive.'));
                                                            }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $list['AppPaymentTransaction']['transaction_status']; ?>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        if($list['AppPaymentTransaction']['redeem_status']=="YES"){
                                                            echo $this->Html->link('','javascript:void(0)',
                                                                array('id'=>'AppPaymentTransactionRedeemStatus','data-id'=>base64_encode($list['AppPaymentTransaction']['id']),'class' => 'fa fa-check', 'title' => 'This order is redeemed.'));
                                                        }else if($list['AppPaymentTransaction']['redeem_status']=="NO"){
                                                            echo $this->Html->link('','javascript:void(0)',
                                                                array('id'=>'AppPaymentTransactionRedeemStatus','data-id'=>base64_encode($list['AppPaymentTransaction']['id']),'class' => 'fa fa-close', 'title' => 'This item is not redeemed.'));
                                                        }else{
                                                            echo "EXPIRED";
                                                        }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <button type="button" id="viewAppPaymentTransaction" class="btn btn-primary btn-xs"  AppPaymentTransaction-id="<?php echo $list['AppPaymentTransaction']['id']; ?>" ><i class="fa fa-eye fa-2x"></i></button>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                            </tbody>
                                        </table>
                                        <?php echo $this->element('paginator'); ?>
                                    </div>
                                    <?php }else{ ?>
                                        <div class="no_data">
                                            <h2>You have no payment transaction</h2>
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
</section>

<div class="modal fade" id="myModalView" role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Payment Item Order</h4>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table" id="viewAppPaymentTransactionTable">
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    $(document).ready(function () {

        $(document).on('click','#AppPaymentTransactionStatus',function (e) {
            var id = $(this).attr('data-id');
            var thisButton = $(this);
            var $btn = $(this);
            $.ajax({
                url: baseurl+'app_admin/change_app_payment_transaction_status',
                data:{id:id},
                type:'POST',
                beforeSend:function () {
                    var path = baseurl+"images/ajax-loading-small.png";
                    $(thisButton).removeClass("fa fa-check, fa fa-close");
                    $btn.button('loading').html('').attr('class',"fa fa-circle-o-notch fa-spin");
                },
                success: function(result){
                    $btn.button('reset');
                    var data = JSON.parse(result);
                    if(data.status == 1)
                    {
                        if(data.text=="ACTIVE")
                        {
                            $(thisButton).attr('class',"fa fa-check");
                        }
                        else
                        {
                            $(thisButton).attr('class',"fa fa-close");
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

        $(document).on('click','#AppPaymentTransactionRedeemStatus',function (e) {
            var id = $(this).attr('data-id');
            var thisButton = $(this);
            var $btn = $(this);
            $.ajax({
                url: baseurl+'app_admin/change_app_payment_transaction_redeem_status',
                data:{id:id},
                type:'POST',
                beforeSend:function () {
                    var path = baseurl+"images/ajax-loading-small.png";
                    $(thisButton).removeClass("fa fa-check, fa fa-close");
                    $btn.button('loading').html('').attr('class',"fa fa-circle-o-notch fa-spin");
                },
                success: function(result){
                    $btn.button('reset');
                    var data = JSON.parse(result);
                    if(data.status == 1)
                    {
                        $(thisButton).attr('class',"fa fa-check");
                        $(thisButton).parents('tr').css('background','lime none repeat scroll 0 0');
                    }
                    else if(data.status == 0)
                    {
                        $(thisButton).attr('class',"fa fa-check");
                        alert(data.message);
                    }
                    else
                    {
                        $(thisButton).attr('class',"fa fa-close");
                        alert(data.message);
                    }
                },
                error:function () {
                    $btn.button('reset');
                }
            });
        });

        $('#SearchRedeemTime').datepicker({
                autoclose: true,
                format: 'yyyy-mm-dd'
            });

        $(document).on('click','#viewAppPaymentTransaction',function () {
            var appPaymentTransactionID = $(this).attr('AppPaymentTransaction-id');
            var thisButton = $(this);
            $.ajax({
                url: baseurl+'/app_admin/view_payment_item_order',
                data:{appPaymentTransactionID:appPaymentTransactionID},
                type:'POST',
                beforeSend:function(){
                    $(thisButton).button('loading').html('<i class="fa fa-spinner fa-pulse fa-2x fa-fw">');
                },
                success: function(result){
                    $(thisButton).button('reset');
                    var result = JSON.parse(result);
                    if(result.status == 1)
                    {
                        $("#viewAppPaymentTransactionTable").html(result.html);
                        $("#myModalView").modal('show');
                    }
                    else
                    {
                        alert('Sorry, Could not find payment item!');
                    }
                }
            });
        });

    });
</script>

<style>
    .fa{font-size: 18px;}
    .tooltip-inner {
        max-width: 500px !important;
    }
    .tooltip-inner {background-color: #03a9f5; text-align: justify;}
    .tooltip-arrow { border-bottom-color:#03a9f5; }
</style>