<?php
$login = $this->Session->read('Auth.User');
?>

<div class="Inner-banner">
    <div class="container">
        <div class="row">
            <!--  SLIDER IMAGE -->
            <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 inner-page-text"> <h2>Add Ticket</h2> </div>
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

                    <?php echo $this->element('admin_leftsidebar'); ?>
                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">

                        <?php echo $this->element('admin_inner_tab_ticket'); ?>
                        <div class="Social-login-box payment_bx">

                            <?php echo $this->element('message'); ?>

                            <?php echo $this->Form->create('EvenTicket',array('type'=>'file','method'=>'post','class'=>'form-horizontal')); ?>

                            <div id="addMoreContainer">
                                    <div class="borderDiv" >
                                        
                                        <div class="form-group">
                                            <div class="col-sm-12">
                                                <label>Show</label>
                                                <?php echo $this->Form->input('data.0.event_show_id',array('type'=>'select','options'=>$eventShowArr,'empty'=>'Please Select','label'=>false,'class'=>'form-control cnt','required'=>true)); ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-12">
                                                <label>Title</label>
                                                <?php echo $this->Form->input('data.0.title',array('type'=>'text','placeholder'=>'Title','label'=>false,'class'=>'form-control cnt','required'=>true)); ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-12">
                                                <label>Sub Title</label>
                                                <?php echo $this->Form->input('data.0.sub_title',array('type'=>'text','placeholder'=>'Sub Title','label'=>false,'class'=>'form-control cnt','required'=>true)); ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-12">
                                                <label>Amount</label>
                                                <?php echo $this->Form->input('data.0.amount',array('type'=>'text','placeholder'=>'Amount','label'=>false,'class'=>'form-control cnt','required'=>true)); ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-12">
                                                <label>Total Count</label>
                                                <?php echo $this->Form->input('data.0.total_count',array('type'=>'text','placeholder'=>'Total Count','label'=>false,'class'=>'form-control cnt','required'=>true)); ?>
                                            </div>
                                        </div>
                                    </div>
                            </div>
                            <div>
                                <button type="button" id="addMore" class="btn btn-primary btn-xs" ><i class="fa fa-plus"></i></button>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-3 pull-right">
                                    <?php echo $this->Form->submit('Submit',array('class'=>'Btn-typ5')); ?>
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
    $(document).ready(function () {
        var options='<option value="">Please Select</option>';
        <?php
            foreach($eventShowArr AS $key => $value){ ?>
                options = options+'<option value="<?php echo $key; ?>"><?php echo $value; ?></option>';
        <?php } ?>
        $('#addMore').click(function(){
           var length = $('.borderDiv').length;
            var addHtml = '<div class="borderDiv"><div class="removeContainer"><button class="btn btn-primary btn-xs" id="removeThis" type="button"><i class="fa fa-times"></i></button></div><div class="form-group"><div class="col-sm-12"><label>Show</label><div class="input select"><select id="data0EventShowId" required="required" class="form-control cnt" name="data[data]['+length+'][event_show_id]">'+options+'</select></div></div></div><div class="form-group"><div class="col-sm-12"><label>Title</label><div class="input text"><input type="text" id="data0Title" required="required" class="form-control cnt" placeholder="Title" name="data[data]['+length+'][title]"></div></div></div><div class="form-group"><div class="col-sm-12"><label>Sub Title</label><div class="input text"><input type="text" id="data0SubTitle" required="required" class="form-control cnt" placeholder="Sub Title" name="data[data]['+length+'][sub_title]"></div></div></div><div class="form-group"><div class="col-sm-12"><label>Amount</label><div class="input text"><input type="text" id="data0Amount" required="required" class="form-control cnt" placeholder="Amount" name="data[data]['+length+'][amount]"></div></div></div><div class="form-group"><div class="col-sm-12"><label>Total Count</label><div class="input text"><input type="text" id="data0TotalCount" required="required" class="form-control cnt" placeholder="Total Count" name="data[data]['+length+'][total_count]"></div></div></div></div>';

            $('#addMoreContainer').append(addHtml);
        });

        $(document).on('click','#removeThis',function () {
            $(this).parent().parent().remove();
        });


    });
</script>

<style>
    .borderDiv {
        border: 2px solid royalblue;
        border-radius: 15px;
        margin: 5px;
        padding: 5px;
    }
    .removeContainer{ text-align: right; }
</style>