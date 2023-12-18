<div class="Inner-banner">
    <div class="container">
        <div class="row">

            <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 inner-page-text"> <h2>Permit Sell Item</h2> </div>
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



                        <div class="progress-bar channel_tap">

                            <a id="v_app_channel_list" href="<?php echo Router::url('/admin/admin/sell'); ?>"><i class="fa fa-list"></i> Sell Item List</a>
                            <a id="v_add_channel" class='active' href="<?php echo Router::url('/admin/admin/permit_sell'); ?>" ><i class="fa fa-globe"></i> Permit Sell Item</a>

                        </div>




                        <div class="Social-login-box payment_bx">
                            <?php echo $this->Form->create('Search',array('type'=>'get','url'=>array('controller'=>'admin','action' => 'search_permit_sell'),'admin'=>true)); ?>
                            <div class="form-group">
                                <div class="col-sm-4">
                                    <?php echo $this->Form->input('sell_category', array('type' => 'select', 'empty' => 'Please select', 'options'=>$sellItemCategory, 'label' => 'Search by category', 'class' => 'form-control')); ?>
                                </div>
                                <div class="col-sm-2">
                                    <?php echo $this->Form->label('&nbsp;'); ?>
                                    <?php echo $this->Form->submit('Search',array('class'=>'Btn-typ3','id'=>'date')); ?>
                                </div>

                                <div class="col-sm-2">
                                    <?php echo $this->Form->label('&nbsp;'); ?>
                                    <div class="submit">
                                        <a href="<?php echo $this->Html->url(array('controller'=>'admin','action'=>'permit_sell')) ?>"><button type="button" class="Btn-typ3" >Reset</button></a>
                                    </div>
                                </div>
                            </div>
                            <div class="clear"></div>

                            <?php echo $this->Form->end(); ?>
                            <?php echo $this->element('message'); ?>
                                        <div class="form-group row">
                                            <div class="col-sm-12">
                                                <div class="table-responsive">
                                                    <?php if(!empty($sellItems)){ ?>
                                                    <table class="table">
                                                        <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Item Name</th>
                                                            <th>Price</th>
                                                            <th>Category</th>
                                                            <th>User</th>
                                                            <th>Status</th>
                                                            <th>Permit Status</th>
                                                            <th>View</th>
                                                            <th>Images</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <?php
                                                        $login = $this->Session->read('Auth.User');
                                                        $num = 1;
                                                        foreach ($sellItems as $key => $value){
                                                            ?>
                                                            <tr>
                                                                <td><?php echo $num++; ?></td>
                                                                <td><?php echo $value['SellItem']['item_name']; ?></td>
                                                                <td><?php echo $value['SellItem']['price']; ?></td>
                                                                <td><?php echo $value['SellItemCategory']['name']; ?></td>
                                                                <td><?php echo $value['User']['mobile']; ?></td>
                                                                <td>
                                                                    <?php echo $value['SellItem']['status']; ?>
                                                                </td>
                                                                <td>
                                                                    <button type="button" id="changeSellPermission" class="btn btn-primary btn-xs"  sell-id="<?php echo $value['SellItem']['id']; ?>" ><?php echo $value['SellItem']['mbroadcast_publish_status']; ?></button>
                                                                </td>
                                                                <td>
                                                                    <button type="button" id="viewSell" class="btn btn-primary btn-xs"  sell-id="<?php echo $value['SellItem']['id']; ?>" >VIEW</button>
                                                                </td>
                                                                <td>
                                                                    <a href="<?php echo $this->Html->url(array('controller'=>'admin','action'=>'permit_sell_images',base64_encode($value['SellItem']['id']))) ?>" >
                                                                        <button type="button" id="editSell" class="btn btn-primary btn-xs" >IMAGE</button>
                                                                    </a>
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
                                <h2>No Sell Item..!</h2>
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
                <h4 class="modal-title">View Sell Item</h4>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table" id="viewSellTable">
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    $(document).ready(function(){

        $(document).on('click','#viewSell',function () {
            var sellID = $(this).attr('sell-id');
            var thisButton = $(this);
            $.ajax({
                url: baseurl+'/admin/admin/view_sell',
                data:{sellID:sellID},
                type:'POST',
                beforeSend:function(){
                    $(thisButton).button('loading').html('<i class="fa fa-spinner fa-pulse fa-2x fa-fw">');
                },
                success: function(result){
                    $(thisButton).button('reset');
                    var result = JSON.parse(result);
                    if(result.status == 1)
                    {
                        $("#viewSellTable").html(result.html);
                        $("#myModalView").modal('show');
                    }
                    else
                    {
                        alert('Sorry, Could not find sell!');
                    }
                }
            });
        });




        $(document).on('click','#changeSellPermission',function(e){
            var sellID = $(this).attr('sell-id');
            var thisButton = $(this);
            $.ajax({
                url: baseurl+'/admin/admin/change_sell_mbroadcast_publish_status',
                data:{sellID:sellID},
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
                        alert('Sorry, Could not change permission!');
                    }
                }
            });
        });




    });
</script>