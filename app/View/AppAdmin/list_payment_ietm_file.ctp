<?php
$login = $this->Session->read('Auth.User');
?>
<div class="Inner-banner">
    <div class="container">
        <div class="row">
            <!--  SLIDER IMAGE -->
            <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 inner-page-text"> <h2>Payment File List</h2> </div>
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
                                <?php echo $this->Form->create('Search',array('type'=>'get','url'=>array('controller'=>'app_admin','action' => 'search_list_payment_ietm_file',$idForSearch),'admin'=>true)); ?>
                                <div class="form-group">
                                    <div class="col-sm-4">
                                        <?php echo $this->Form->input('mobile', array('type' => 'text', 'placeholder' => 'Mobile', 'label' => 'Search by mobile', 'class' => 'form-control')); ?>
                                    </div>
                                    <div class="col-sm-2">
                                        <?php echo $this->Form->label('&nbsp;'); ?>
                                        <?php echo $this->Form->submit('Search',array('class'=>'Btn-typ3','id'=>'search')); ?>
                                    </div>
                                    <div class="col-sm-2">
                                        <?php echo $this->Form->label('&nbsp;'); ?>
                                        <div class="submit">
                                            <a href="<?php echo $this->Html->url(array('controller'=>'app_admin','action'=>'list_payment_ietm_file',$idForSearch)) ?>"><button type="button" class="Btn-typ3" >Reset</button></a>
                                        </div>
                                    </div>
                                </div>
                            <?php echo $this->Form->end(); ?>

                            <div class="col-sm-2">
                                <?php echo $this->Form->label('&nbsp;'); ?>
                                <div class="submit">
                                    <a href="<?php echo $this->Html->url(array("controller" => "app_admin",
                                        "action" => "export_payment_ietm_file",
                                        $idForSearch,
                                        "?" => $searchToExport,)); ?>"><button type="button" class="Btn-typ3" >Export</button></a>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-12">

                                    <div class="table-responsive">
                                        <?php if(!empty($paymentFileAmount)){ ?>
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Mobile</th>
                                                <th>Amount</th>
                                                <th>Status</th>
                                                <th>Payment Status</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php foreach ($paymentFileAmount as $key => $list){
                                                ?>
                                                <tr>
                                                    <td><?php echo $key+1; ?>.</td>
                                                    <td><?php echo $list['PaymentFileAmount']['mobile']; ?></td>
                                                    <td><?php echo $list['PaymentFileAmount']['amount']; ?></td>
                                                    <td>
                                                        <?php
                                                            if($list['PaymentFileAmount']['status']=="ACTIVE"){
                                                                echo $this->Html->link('','javascript:void(0)',
                                                                    array('id'=>'paymentItemStatus','data-id'=>base64_encode($list['PaymentFileAmount']['id']),'class' => 'fa fa-check', 'title' => 'This item is active.'));
                                                            }else{
                                                                echo $this->Html->link('','javascript:void(0)',
                                                                    array('id'=>'paymentItemStatus','data-id'=>base64_encode($list['PaymentFileAmount']['id']),'class' => 'fa fa-close', 'title' => 'This item is inactive.'));
                                                            }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $list['PaymentFileAmount']['payment_status']; ?>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                            </tbody>
                                        </table>
                                        <?php echo $this->element('paginator'); ?>

                                    </div>
                                    <?php }else{ ?>
                                        <div class="no_data">
                                            <h2>You have no payment file item</h2>
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


<script>
    $(document).on('click','#paymentItemStatus',function (e) {
        var id = $(this).attr('data-id');
        var thisButton = $(this);
        var $btn = $(this);
        $.ajax({
            url: baseurl+'app_admin/change_payment_file_status',
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
</script>

<style>
    .fa{font-size: 18px;}
    .tooltip-inner {
        max-width: 500px !important;
    }
    .tooltip-inner {background-color: #03a9f5; text-align: justify;}
    .tooltip-arrow { border-bottom-color:#03a9f5; }
</style>