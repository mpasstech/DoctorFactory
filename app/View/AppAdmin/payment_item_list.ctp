<?php
$login = $this->Session->read('Auth.User');
?>
<div class="Inner-banner">
    <div class="container">
        <div class="row">
            <!--  SLIDER IMAGE -->
            <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 inner-page-text"> <h2>Payment List</h2> </div>
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

                            <?php echo $this->Form->create('Search',array('type'=>'get','url'=>array('controller'=>'app_admin','action' => 'search_payment_item_list'),'admin'=>true)); ?>

                                <div class="form-group">
                                    <div class="col-sm-4">
                                        <?php echo $this->Form->input('title', array('type' => 'text', 'placeholder' => 'Title', 'label' => 'Search by title', 'class' => 'form-control')); ?>
                                    </div>
                                    <div class="col-sm-2">
                                        <?php echo $this->Form->label('&nbsp;'); ?>
                                        <?php echo $this->Form->submit('Search',array('class'=>'Btn-typ3','id'=>'search')); ?>
                                    </div>
                                    <div class="col-sm-2">
                                        <?php echo $this->Form->label('&nbsp;'); ?>
                                        <div class="submit">
                                            <a href="<?php echo $this->Html->url(array('controller'=>'app_admin','action'=>'payment_item_list')) ?>"><button type="button" class="Btn-typ3" >Reset</button></a>
                                        </div>
                                    </div>
                                </div>

                            <?php echo $this->Form->end(); ?>
                            <div class="col-sm-4">
                                <?php echo $this->Form->label('&nbsp;'); ?>
                                <div class="submit">
                                    <a href="<?php echo $this->Html->url(array('controller'=>'app_admin','action'=>'add_payment_item')) ?>"><button type="button" class="Btn-typ3" >Add Payment Item</button></a>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-12">

                            <div class="table-responsive">
                            <?php if(!empty($paymentItem)){ ?>
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Image</th>
                                        <th>Title</th>
                                        <th>Description</th>
                                        <th>Orders</th>
                                        <th>Order Status</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($paymentItem as $key => $list){
                                       // pr($paymentItem); die;
                                        if(empty($list['PaymentItem']['image_path'])){
                                            $path =Router::url('/images/channel-icon.png',true);
                                        }else{
                                            $path =$list['PaymentItem']['image_path'];
                                        }
                                        ?>
                                        <tr>
                                            <td><?php echo $key+1; ?>.</td>
                                            <td><img class="staff_image_list" src="<?php echo $path;?>"></td>
                                            <td><?php echo $list['PaymentItem']['title']; ?></td>
                                            <td><?php echo $list['PaymentItem']['description']; ?></td>
                                            <td><?php
                                                echo "<strong style='color: #00aa00'>".$list['PaymentItem']['total_orders']."</strong>";
                                                if($list['PaymentItem']['payment_type'] == 'BY_FILE')
                                                {
                                                    echo '/'.count($list['PaymentFileAmount']);
                                                }
                                                ?></td>
                                            <td>
                                                <?php if($list['PaymentItem']['payment_type'] == 'BY_FILE'){ ?>
                                                    <a href="<?php echo $this->Html->url(array('controller'=>'app_admin','action'=>'list_payment_ietm_file',base64_encode($list['PaymentItem']['id']))) ?>" >
                                                        <button type="button" class="btn btn-primary btn-xs" >View File Amount</button>
                                                    </a>
                                                <?php } ?>
                                                <a href="<?php echo $this->Html->url(array('controller'=>'app_admin','action'=>'list_payment_item_transactions',base64_encode($list['PaymentItem']['id']))) ?>" >
                                                    <button type="button" class="btn btn-primary btn-xs" >View Orders</button>
                                                </a>
                                            </td>
                                            <td>
                                                <div class="action_icon status_td" style="display:flex;">
                                                    <?php
                                                    if($list['PaymentItem']['status']=="ACTIVE"){
                                                    echo $this->Html->link('','javascript:void(0)',
                                                    array('id'=>'statusPaymentItem','data-id'=>base64_encode($list['PaymentItem']['id']),'class' => 'fa fa-check', 'title' => 'This item is active.'));
                                                    }else{
                                                    echo $this->Html->link('','javascript:void(0)',
                                                    array('id'=>'statusPaymentItem','data-id'=>base64_encode($list['PaymentItem']['id']),'class' => 'fa fa-close', 'title' => 'This item is inactive.'));
                                                    }
                                                    ?>
                                                    &nbsp;
                                                    <button type="button" id="viewPaymentItem" class="btn btn-primary btn-xs"  paymentItem-id="<?php echo $list['PaymentItem']['id']; ?>" ><i class="fa fa-eye fa-2x"></i></button>

                                                    <?php if( (int)$list['PaymentItem']['total_orders'] == 0 ){ ?>

                                                    <button type="button" id="editPaymentItem" class="btn btn-primary btn-xs"  paymentItem-id="<?php echo base64_encode($list['PaymentItem']['id']); ?>" ><i class="fa fa-pencil fa-2x"></i></button>
                                                    <button type="button" id="deletePaymentItem" class="btn btn-primary btn-xs"  paymentItem-id="<?php echo base64_encode($list['PaymentItem']['id']); ?>" ><i class="fa fa-trash fa-2x"></i></button>

                                                    <?php } ?>

                                                </div>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                    </tbody>
                                </table>
                                <?php echo $this->element('paginator'); ?>

                        </div>
                            <?php }else{ ?>
                                <div class="no_data">
                                    <h2>You have payment item</h2>
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
                <h4 class="modal-title">Payment Item</h4>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table" id="viewPaymentItemTable">
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="editModal" role="dialog">

    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <form method="POST" id="editPaymentItemForm" enctype="multipart/form-data">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Edit</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <input type="text" class="form-control" id="paymentTitle" name="title" required>
                    <input type="hidden" name="id" id="editID">
                </div>
                <div class="form-group">
                    <textarea id="paymentDescription" class="form-control" name="description" required></textarea>
                </div>
                <div class="form-group">
                    <input type="file" class="file_browse" accept="image/x-png,image/gif,image/jpeg" name="image" >
                </div>
                <div class="file_error"></div>
            </div>
            <div class="modal-footer">
                <?php echo $this->Form->submit('Update',array('class'=>'Btn-typ3','id'=>'reply')); ?>
            </div>
            </form>
        </div>
    </div>

</div>

<script>
    var editPaymentID = '';
    $(document).ready(function(){

        $(".channel_tap a").removeClass('active');
        $("#v_app_staff_list").addClass('active');
        $('[data-toggle="tooltip"]').tooltip();

        $(document).on('click','#statusPaymentItem',function (e) {

            var id = $(this).attr('data-id');
            var thisButton = $(this);
            var $btn = $(this);
            $.ajax({
                url: baseurl+'app_admin/change_payment_item_status',
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

        $(document).on('click','#deletePaymentItem',function (e) {

            var id = $(this).attr('paymentItem-id');
            var thisButton = $(this);
            var $btn = $(this);
            $.ajax({
                url: baseurl+'app_admin/delete_payment_item',
                data:{id:id},
                type:'POST',
                beforeSend:function () {
                    $(thisButton).button('loading').html('<i class="fa fa-spinner fa-pulse fa-2x fa-fw">');
                },
                success: function(result){
                    $btn.button('reset');
                    var data = JSON.parse(result);
                    if(data.status == 1)
                    {
                        location.reload();
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

        $(document).on('click','#viewPaymentItem',function () {
            var paymentItemID = $(this).attr('paymentItem-id');
            var thisButton = $(this);
            $.ajax({
                url: baseurl+'/app_admin/view_payment_item',
                data:{paymentItemID:paymentItemID},
                type:'POST',
                beforeSend:function(){
                    $(thisButton).button('loading').html('<i class="fa fa-spinner fa-pulse fa-2x fa-fw">');
                },
                success: function(result){
                    $(thisButton).button('reset');
                    var result = JSON.parse(result);
                    if(result.status == 1)
                    {
                        $("#viewPaymentItemTable").html(result.html);
                        $("#myModalView").modal('show');
                    }
                    else
                    {
                        alert('Sorry, Could not find payment item!');
                    }
                }
            });
        });

        $(document).on('click','#editPaymentItem',function () {
            editPaymentID = $(this).attr('paymentItem-id');
            var thisButton = $(this);
            $("#editPaymentItemForm")[0].reset();
            $.ajax({
                url: baseurl+'/app_admin/get_edit_payment_item',
                data:{editPaymentID:editPaymentID},
                type:'POST',
                beforeSend:function(){
                    $(thisButton).button('loading').html('<i class="fa fa-spinner fa-pulse fa-2x fa-fw">');
                },
                success: function(result){
                    $(thisButton).button('reset');
                    var result = JSON.parse(result);
                    if(result.status == 1)
                    {
                        $("#paymentTitle").val(result.title);
                        $("#paymentDescription").val(result.description);
                        $("#editID").val(editPaymentID);
                        $("#editModal").modal('show');
                    }
                    else
                    {
                        alert('Sorry, Could not find payment item!');
                    }
                }
            });
        });

        $(document).on('submit','#editPaymentItemForm',function (e) {
            e.preventDefault();
            var formData = new FormData($("#editPaymentItemForm")[0]);

            $.ajax({
                type:'POST',
                url: baseurl+"app_admin/edit_payment_item",
                data:formData,
                cache:false,
                contentType: false,
                processData: false,
                success:function(data){
                    data = JSON.parse(data);

                    if(data.status==1){
                        $("#editModal").modal('hide');
                        location.reload();
                    }else{
                        $(".file_error").html(data.message);
                    }
                },
                error: function(data){
                    $(".file_error").html("Sorry something went wrong on server.");
                }
            });

        });

    });



    $(document).on('change','.file_browse',function(e){
        if($(this).val()){
            readURL(this);
        }
    });

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                var image = new Image();
                image.src = e.target.result;
                image.onload = function() {
                    if(this.width==400 && this.height == 400){
                        return true;
                    }else{

                        alert("Please upload 400 X 400 dimension image only.");
                        input.value = "";
                    }
                };
            }
            reader.readAsDataURL(input.files[0]);
        }
    }


</script>

<style>
     .fa{font-size: 18px;}
     .tooltip-inner {
        max-width: 500px !important;
     }
     .tooltip-inner {background-color: #03a9f5; text-align: justify;}
     .tooltip-arrow { border-bottom-color:#03a9f5; }
    button { margin: 1px; }
</style>






