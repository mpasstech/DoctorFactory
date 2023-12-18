<?php
    $postData = $this->request->data;
    $script = "";

if(empty($postData))
{
       $style = ".amountType{ display: none; }
        .amountFile{ display: none; }
        .amount{ display: none; }
        .quantityType{ display: none; }
        .quantity{ display: none; }
        .lastDate{ display: none; }
        .discountEnable{ display: none; }
        .discountType{ display: none; }
        .discountValue{ display: none; }
        #channelContainer{ display: none; }
        .shareOn{ display: none; }";
}
else
{
        $style = "";
        if(!empty($postData['PaymentItem']['amount_type']))
        {
            $style .= ".amountType{ display: block; }";
            $script .= "$('#PaymentItemAmountType').prop('required',true);";
        }
        else
        {
            $style .= ".amountType{ display: none; }";
        }

        if(!empty($postData['PaymentItem']['amount_file']['tmp_name']))
        {
            $style .= ".amountFile{ display: block; }";
            $script .= "$('#PaymentItemAmountFile').prop('required',true);";
        }
        else
        {
            $style .= ".amountFile{ display: none; }";
        }

        if(!empty($postData['PaymentItem']['amount']))
        {
            $style .= ".amount{ display: block; }";
            $script .= "$('#PaymentItemAmount').prop('required',true);";
        }
        else
        {
            $style .= ".amount{ display: none; }";
        }

        if(!empty($postData['PaymentItem']['quantity_type']))
        {
            $style .= ".quantityType{ display: block; }";
            $script .= "$('[name='data[PaymentItem][quantity_type]']').prop('required',true);";
        }
        else
        {
            $style .= ".quantityType{ display: none; }";
        }

        if($postData['PaymentItem']['quantity_type'] == 'YES')
        {
            $style .= ".maxOrederQuantity{ display: block; }";
        }
        else
        {
            $style .= ".maxOrederQuantity{ display: none; }";
        }

        if(!empty($postData['PaymentItem']['quantity']))
        {
            $style .= ".quantity{ display: block; }";
            $script .= "$('#PaymentItemQuantity').prop('required',true);";
        }
        else
        {
            $style .= ".quantity{ display: none; }";
        }

        if(!empty($postData['PaymentItem']['last_date']))
        {
            $style .= ".lastDate{ display: block; }";
            $script .= "$('#PaymentItemLastDate').prop('required',true);";
        }
        else
        {
            $style .= ".lastDate{ display: none; }";
        }

        if(!empty($postData['PaymentItem']['discount_type']))
        {
            $style .= ".discountType{ display: block; }";
            $script .= "$('#PaymentItemDiscountType').prop('required',true);";
        }
        else
        {
            $style .= ".discountType{ display: none; }";
        }

        if(!empty($postData['PaymentItem']['discount_value']))
        {
            $style .= ".discountValue{ display: block; }";
            $script .= "$('#PaymentItemDiscountValue').prop('required',true);";
        }
        else
        {
            $style .= ".discountValue{ display: none; }";
        }

        if(!empty($postData['PaymentItem']['channel_id']))
        {
            $style .= ".channelContainer{ display: block; }";
            $script .= "$('#ChannelId').prop('required',true);";
        }
        else
        {
            $style .= ".channelContainer{ display: none; }";
        }

        if($postData['PaymentItem']['discount_enable'] == 'YES')
        {
            $style .= ".discountEnable{ display: block; }";
        }
        else
        {
            $style .= ".discountEnable{ display: none; }";
        }

        if($postData['PaymentItem']['payment_type'] == 'BY_FILE')
        {
            $style .= ".shareOn{ display: none; }";
        }

}
?>
<style>
<?php echo $style; ?>
</style>
<script>
    <?php echo $script;?>
</script>

<div class="Inner-banner">
    <div class="container">
        <div class="row">
            <!--  SLIDER IMAGE -->
            <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 inner-page-text"> <h2>Add Payment Item</h2> </div>
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
                    <!--left sidebar-->

                    <?php echo $this->element('app_admin_leftsidebar'); ?>
                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">

                        <?php
                        //echo $this->element('app_admin_quest_tab');
                         ?>



                        <div class="Social-login-box payment_bx">

                            <?php echo $this->element('message'); ?>

                            <?php echo $this->Form->create('PaymentItem',array('type'=>'file','method'=>'post','class'=>'form-horizontal')); ?>

                            <div class="form-group">
                                <div class="col-sm-12">
                                    <label>Title</label>
                                    <?php echo $this->Form->input('title',array('type'=>'text','placeholder'=>'Title','label'=>false,'class'=>'form-control cnt','required'=>true)); ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-12">
                                    <label>Description</label>
                                    <?php echo $this->Form->input('description',array('type'=>'textarea','row'=>5,'placeholder'=>'Description','label'=>false,'class'=>'form-control cnt','required'=>true)); ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-12">
                                    <label>Payment Type</label>
                                    <?php echo $this->Form->input('payment_type',array('type'=>'select','options'=>array('FREE'=>'Free','AMOUNT'=>'Amount','BY_FILE'=>'By File'),'empty'=>'Select Payment Type','label'=>false,'class'=>'form-control cnt','required'=>true)); ?>
                                </div>
                            </div>

                            <div class="form-group amountType">
                                <div class="col-sm-12">
                                    <label>Amount Type</label>
                                    <?php echo $this->Form->input('amount_type',array('type'=>'select','options'=>array('ADMIN_DEFINED'=>'Admin Defined','USER_DEFINED'=>'User Defined'),'empty'=>'Select Amount Type','label'=>false,'class'=>'form-control cnt')); ?>
                                </div>
                            </div>

                            <div class="form-group amountFile">
                                <div class="col-sm-12">
                                    <label>Amount File</label>
                                    <?php echo $this->Form->input('amount_file',array('type'=>'file','label'=>false,'class'=>'form-control cnt')); ?>
                                </div>
                            </div>

                            <div class="form-group amount">
                                <div class="col-sm-12">
                                    <label>Amount</label>
                                    <?php echo $this->Form->input('amount',array('type'=>'number','placeholder'=>'Amount','label'=>false,'class'=>'form-control cnt')); ?>
                                </div>
                            </div>

                            <div class="form-group askForQuanitiy">
                                <div class="col-sm-12">
                                    <label>Ask For Quanitiy</label>
                                    <?php echo $this->Form->radio('ask_for_quantity',array('YES'=>'Yes','NO'=>'No',),array( 'value' => 'NO','label'=>false,'required'=>true,'legend'=>false )); ?>
                                </div>
                            </div>

                            <div class="form-group quantityType">
                                <div class="col-sm-12">
                                    <label>Quantity Type</label>
                                    <?php echo $this->Form->input('quantity_type',array('type'=>'select','options'=>array('LIMITED'=>'Limited','UNLIMITED'=>'Unlimited'),'empty'=>'Select Quantity Type','label'=>false,'class'=>'form-control cnt')); ?>
                                </div>
                            </div>

                            <div class="form-group quantity">
                                <div class="col-sm-12">
                                    <label>Quantity</label>
                                    <?php echo $this->Form->input('quantity',array('type'=>'number','placeholder'=>'Quantity','label'=>false,'class'=>'form-control cnt')); ?>
                                </div>
                            </div>

                            <div class="form-group maxOrederQuantity">
                                <div class="col-sm-12">
                                    <label>Maximum Order Quantity</label>
                                    <?php echo $this->Form->input('maximum_order_quantity',array('type'=>'number','placeholder'=>'Maximum Order Quantity','label'=>false,'class'=>'form-control cnt','value'=>'1','required'=>true)); ?>
                                </div>
                            </div>

                            <div class="form-group showLastDate">
                                <div class="col-sm-12">
                                    <label>Show Last Date</label>
                                    <?php echo $this->Form->radio('show_last_date',array('YES'=>'Yes','NO'=>'No',),array( 'value' => 'NO','label'=>false,'required'=>true,'legend'=>false )); ?>
                                </div>
                            </div>

                            <div class="form-group lastDate">
                                <div class="col-sm-12">
                                    <label>Last Date</label>
                                    <?php echo $this->Form->input('last_date',array('type'=>'text','label'=>false,'class'=>'form-control cnt')); ?>
                                </div>
                            </div>

                            <div class="form-group questionToAsk">
                                <div class="col-sm-12">
                                    <label>Question To Ask</label>
                                    <?php echo $this->Form->input('question_to_ask',array('type'=>'text','label'=>false,'class'=>'form-control cnt','placeholder'=>'Question To Ask')); ?>
                                </div>
                            </div>

                            <div class="form-group homeDelivery">
                                <div class="col-sm-12">
                                    <label>Home Delivery</label>
                                    <?php echo $this->Form->radio('home_delivery',array('YES'=>'Yes','NO'=>'No',),array( 'value' => 'NO','label'=>false,'required'=>true,'legend'=>false )); ?>
                                </div>
                            </div>

                            <div class="form-group discountEnable">
                                <div class="col-sm-12">
                                    <label>Discount Enable</label>
                                    <?php echo $this->Form->radio('discount_enable',array('YES'=>'Yes','NO'=>'No',),array( 'value' => 'NO','label'=>false,'required'=>true,'legend'=>false )); ?>
                                </div>
                            </div>

                            <div class="form-group discountType">
                                <div class="col-sm-12">
                                    <label>Discount Type</label>
                                    <?php echo $this->Form->input('discount_type',array('type'=>'select','options'=>array('PERCENTAGE'=>'Percentage','FIX_AMOUNT'=>'Fix Amount'),'empty'=>'Select Discount Type','label'=>false,'class'=>'form-control cnt')); ?>
                                </div>
                            </div>

                            <div class="form-group discountValue">
                                <div class="col-sm-12">
                                    <label>Discount Value</label>
                                    <?php echo $this->Form->input('discount_value',array('type'=>'number','placeholder'=>'Discount Value','label'=>false,'class'=>'form-control cnt')); ?>
                                </div>
                            </div>

                            <div class="form-group shareOn">
                                <div class="col-sm-12">
                                    <label>Share On</label>
                                    <?php echo $this->Form->input('share_on',array('type'=>'select','options'=>array('FACTORY'=>'Factory','CHANNEL'=>'Channel'),'label'=>false,'id'=>'share_on','class'=>'form-control cnt','required'=>true)); ?>
                                </div>
                            </div>

                            <div class="form-group" id="channelContainer">
                                <div class="col-sm-12">
                                    <label>Channel For Share</label>
                                    <?php echo $this->Form->input('channel_id',array('type'=>'select','options'=>$channels,'empty'=>'Please Select Channel','id'=>'ChannelId','label'=>false,'class'=>'form-control cnt')); ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-12">
                                    <label>Image</label>
                                    <?php echo $this->Form->input('image_path',array("accept"=>"image/x-png,image/gif,image/jpeg",'type'=>'file','label'=>false,'class'=>'form-control file_browse')); ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-3 pull-right">
                                    <?php echo $this->Form->submit('Add Item',array('class'=>'Btn-typ5','id'=>'addEvent')); ?>
                                </div>
                            </div>

                            <?php echo $this->Form->end(); ?>

                        </div>
                    </div>
                </div>
                <!-- box 1 -->
            </div>
            <!--box 2 -->
        </div>
    </div>
</div>


<script>
    $(document).ready(function(){

        $('#PaymentItemPaymentType').change(function(){
            var paymentType = $(this).val();
            if(paymentType == 'AMOUNT')
            {
                $(".amountType").show();
                $(".amountFile").hide();
                $(".amount").hide();
                $(".shareOn").show();
                $(".discountEnable").hide();
                $(".discountType").hide();
                $(".discountValue").hide();
                $("#channelContainer").hide();

                $("#PaymentItemAmountFile").val('');
                $("#PaymentItemAmountFile").prop('required',false);
                $("#PaymentItemAmountType").val('');
                $("#PaymentItemAmountType").prop('required',true);
                $("#PaymentItemAmount").val('');
                $("#PaymentItemAmount").prop('required',false);

                $("#share_on").val('FACTORY');
                $("#share_on").prop('required',true);
                $("#ChannelId").val('');
                $("#ChannelId").prop('required',false);

                $("#PaymentItemDiscountEnableNO").prop("checked", true);
                $("#PaymentItemDiscountType").val('');
                $("#PaymentItemDiscountValue").val('');
                $("#PaymentItemDiscountType").prop('required',false);
                $("#PaymentItemDiscountValue").prop('required',false);

            }
            else if(paymentType == 'BY_FILE')
            {
                $(".amountType").hide();
                $(".amount").hide();
                $(".amountFile").show();
                $(".shareOn").hide();
                $("#channelContainer").hide();
                $(".discountEnable").hide();
                $(".discountType").hide();
                $(".discountValue").hide();
                $(".amount").hide();

                $("#PaymentItemAmountFile").val('');
                $("#PaymentItemAmountFile").prop('required',true);
                $("#PaymentItemAmountType").val('');
                $("#PaymentItemAmountType").prop('required',false);
                $("#PaymentItemAmount").val('');
                $("#PaymentItemAmount").prop('required',false);

                $("#share_on").val('FACTORY');
                $("#share_on").prop('required',true);
                $("#ChannelId").val('');
                $("#ChannelId").prop('required',false);

                $("#PaymentItemDiscountEnableNO").prop("checked", true);
                $("#PaymentItemDiscountType").val('');
                $("#PaymentItemDiscountValue").val('');
                $("#PaymentItemDiscountType").prop('required',false);
                $("#PaymentItemDiscountValue").prop('required',false);

                $("#PaymentItemAmount").val('');
                $("#PaymentItemAmount").prop('required',false);
            }
            else if(paymentType == 'FREE')
            {
                $(".amountFile").hide();
                $(".amountType").hide();
                $(".amount").hide();
                $(".shareOn").show();
                $(".discountEnable").hide();
                $(".discountType").hide();
                $(".discountValue").hide();
                $(".amount").hide();
                $("#channelContainer").hide();

                $("#PaymentItemAmountFile").val(null);
                $("#PaymentItemAmountFile").prop('required',false);
                $("#PaymentItemAmountType").val('');
                $("#PaymentItemAmountType").prop('required',false);
                $("#PaymentItemAmount").val('');
                $("#PaymentItemAmount").prop('required',false);
                $("#share_on").val('FACTORY');
                $("#share_on").prop('required',true);
                $("#ChannelId").val(null);
                $("#ChannelId").prop('required',false);

                $("#PaymentItemDiscountEnableNO").prop("checked", true);
                $("#PaymentItemDiscountType").val('');
                $("#PaymentItemDiscountValue").val('');
                $("#PaymentItemDiscountType").prop('required',false);
                $("#PaymentItemDiscountValue").prop('required',false);
                $("#PaymentItemAmount").val('');
                $("#PaymentItemAmount").prop('required',false);
            }
            else
            {
                $(".amountFile").hide();
                $(".amountType").hide();
                $(".amount").hide();
                $(".shareOn").hide();
                $(".discountEnable").hide();
                $(".discountType").hide();
                $(".discountValue").hide();
                $(".amount").hide();
                $("#channelContainer").hide();

                $("#PaymentItemAmountFile").val(null);
                $("#PaymentItemAmountFile").prop('required',false);
                $("#PaymentItemAmountType").val('');
                $("#PaymentItemAmountType").prop('required',false);
                $("#PaymentItemAmount").val('');
                $("#PaymentItemAmount").prop('required',false);
                $("#share_on").val('FACTORY');
                $("#share_on").prop('required',true);
                $("#ChannelId").val(null);
                $("#ChannelId").prop('required',false);

                $("#PaymentItemDiscountEnableNO").prop("checked", true);
                $("#PaymentItemDiscountType").val('');
                $("#PaymentItemDiscountValue").val('');
                $("#PaymentItemDiscountType").prop('required',false);
                $("#PaymentItemDiscountValue").prop('required',false);
                $("#PaymentItemAmount").val('');
                $("#PaymentItemAmount").prop('required',false);
            }
        });

        $('#PaymentItemAmountType').change(function(){
            var amountType = $(this).val();
            if(amountType == 'ADMIN_DEFINED')
            {
                $(".amount").show();
                $(".discountEnable").show();

                $("#PaymentItemDiscountEnableNO").prop("checked", true);
                $("#PaymentItemDiscountType").val('');
                $("#PaymentItemDiscountValue").val('');

                $("#PaymentItemDiscountType").prop('required',false);
                $("#PaymentItemDiscountValue").prop('required',false);

                $("#PaymentItemAmount").val('');
                $("#PaymentItemAmount").prop('required',true);
            }
            else
            {

                $(".discountEnable").hide();
                $(".discountType").hide();
                $(".discountValue").hide();

                $("#PaymentItemDiscountEnableNO").prop("checked", true);
                $("#PaymentItemDiscountType").val('');
                $("#PaymentItemDiscountValue").val('');

                $("#PaymentItemDiscountType").prop('required',false);
                $("#PaymentItemDiscountValue").prop('required',false);

                $(".amount").hide();
                $("#PaymentItemAmount").val('');
                $("#PaymentItemAmount").prop('required',false);
            }
        });

        $("[name='data[PaymentItem][ask_for_quantity]']").change(function (){
            var askForQuantity = $(this).val();
            if(askForQuantity == 'YES')
            {
                $(".quantityType").show();
                $(".maxOrederQuantity").show();
                $("#PaymentItemMaximumOrderQuantity").val();
                $("#PaymentItemQuantityType").val('');
                $("#PaymentItemQuantityType").prop('required',true);
            }
            else
            {
                $(".quantityType").hide();
                $(".maxOrederQuantity").hide();
                $("#PaymentItemMaximumOrderQuantity").val('1');
                $("#PaymentItemQuantityType").val('');
                $("#PaymentItemQuantityType").prop('required',false);
                $("#PaymentItemQuantity").val('');
                $("#PaymentItemQuantity").prop('required',false);
            }
        });

        $("[name='data[PaymentItem][quantity_type]']").change(function(){
            var quantityType = $(this).val();
            if(quantityType == 'LIMITED')
            {
                $(".quantity").show();
                $("#PaymentItemQuantity").prop('required',true);
            }
            else
            {
                $(".quantity").hide();
                $("#PaymentItemQuantity").val('');
                $("#PaymentItemQuantity").prop('required',false);
            }
        });

        $("[name='data[PaymentItem][show_last_date]']").change(function () {
            var showLastDate = $(this).val();
            if(showLastDate == 'YES')
            {
                $(".lastDate").show();
                $("#PaymentItemLastDate").prop('required',true);
            }
            else
            {
                $(".lastDate").hide();
                $("#PaymentItemLastDate").val('');
                $("#PaymentItemLastDate").prop('required',false);
            }
        });

        $("[name='data[PaymentItem][discount_enable]']").change(function () {
            var discountEnable = $(this).val();
            if(discountEnable == 'YES')
            {
                $(".discountType,.discountValue").show();
                $("#PaymentItemDiscountType").val('PERCENTAGE');
                $("#PaymentItemDiscountValue").val('');
                $("#PaymentItemDiscountValue").prop('required',true);
            }
            else
            {
                $(".discountType,.discountValue").hide();
                $("#PaymentItemDiscountValue").val('');
                $("#PaymentItemDiscountValue").prop('required',false);
                $("#PaymentItemDiscountType").val('');
            }
        });

        $('#share_on').change(function(){
            var shareOn = $(this).val();
            if(shareOn == 'CHANNEL')
            {
                $("#channelContainer").show();
                $("#ChannelId").prop('required',true);
            }
            else
            {
                $("#channelContainer").hide();
                $("#ChannelId").val('');
                $("#ChannelId").prop('required',false);
            }
        });

        $('#PaymentItemLastDate').datepicker({
            startDate: new Date(),
            autoclose: true,
            format: 'yyyy-mm-dd'
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




    });
</script>

<style>
    .bootstrap-tagsinput > input{
        width: 100%!important;
       /* padding-top: 5px !important; */
    }
    #channelContainer{
        display: none;
    }
</style>