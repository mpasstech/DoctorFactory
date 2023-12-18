<div class="Inner-banner">
    <div class="container">
        <div class="row">

            <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 inner-page-text"> <h2>Edit Contest</h2> </div>
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
                            <a href="<?php echo Router::url('/admin/supp/list_contest'); ?>"   ><i class="fa fa-list"></i> Contest List</a>
                            <a href="<?php echo Router::url('/admin/supp/add_contest'); ?>" class="active" ><i class="fa fa-list"></i> Add Contest</a>
                        </div>


                        <div class="Social-login-box payment_bx">

                            <?php echo $this->element('message'); ?>

                            <?php echo $this->Form->create('Contest',array('type'=>'file','method'=>'post','class'=>'form-horizontal')); ?>


                            <div class="form-group">
                                <div class="col-sm-12">
                                    <label>Title</label>
                                    <?php echo $this->Form->input('title',array('type'=>'text','placeholder'=>'Contest title','label'=>false,'class'=>'form-control cnt','required'=>true)); ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-12">
                                    <label>Description</label>
                                    <?php echo $this->Form->input('description',array('type'=>'textarea','placeholder'=>'Description','id'=>'editor1','label'=>false,'class'=>'form-control cnt','required'=>true)); ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-12">
                                    <label>Contest Type</label>
                                    <?php echo $this->Form->input('contest_type',array('type'=>'select','empty'=>'Please select','options'=>array('TEXT'=>'Text', 'MULTIPLE_CHOICE'=>'Multiple Choice'),'label'=>false,'class'=>'form-control cnt','required'=>true)); ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-12">
                                    <label>Start Date</label>
                                    <?php echo $this->Form->input('start_date',array('type'=>'text','placeholder'=>'Start Date','label'=>false,'id'=>'start_date','class'=>'form-control cnt','required'=>true)); ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-12">
                                    <label>Start Time</label>
                                    <?php echo $this->Form->input('start_time',array('type'=>'text','placeholder'=>'Start Time','label'=>false,'id'=>'start_time','class'=>'form-control cnt','required'=>true)); ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-12">
                                    <label>End Date</label>
                                    <?php echo $this->Form->input('end_date',array('type'=>'text','placeholder'=>'End Date','label'=>false,'id'=>'end_date','class'=>'form-control cnt','required'=>true)); ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-12">
                                    <label>End Time</label>
                                    <?php echo $this->Form->input('end_time',array('type'=>'text','placeholder'=>'End Time','label'=>false,'id'=>'end_time','class'=>'form-control cnt','required'=>true)); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <label>Allow Maximum Time Limit</label>
                                    <?php echo $this->Form->input('allow_maximum_time_limit',array('type'=>'select','options'=>array('NO'=>'No', 'YES'=>'Yes'),'label'=>false,'class'=>'form-control cnt','required'=>true)); ?>
                                </div>
                            </div>

                            <!--div class="form-group" id="maxTime" style="display: <?php echo ($this->request->data['Contest']['allow_maximum_time_limit'] == 'YES')?'block':'none'; ?>;">
                                <div class="col-sm-12">
                                    <label>Maximum Time Limit</label>
                                    <?php echo $this->Form->input('maximum_time_limit',array('type'=>'number','placeholder'=>'Maximum time limit','label'=>false,'class'=>'form-control cnt')); ?>
                                </div>
                            </div-->

                            <div class="form-group">
                                <div class="col-sm-12">
                                    <label>Image</label>
                                    <?php echo $this->Form->input('image',array('type'=>'file','accept'=>'image/*','label'=>false,'class'=>'form-control cnt')); ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-3 pull-right">
                                    <?php echo $this->Form->submit('Edit Contest',array('class'=>'Btn-typ5','id'=>'addContest')); ?>
                                </div>
                            </div>
                            <?php echo $this->Form->end(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>




<script>
    $(document).ready(function(){
        var startSDate = new Date();
        $('#start_date').datepicker({
            startDate: new Date(),
            autoclose: true,
            format: 'yyyy-mm-dd'
        })
            .on('changeDate', function(selected){
                startSDate = new Date(selected.date.valueOf());
                startSDate.setDate(startSDate.getDate(new Date(selected.date.valueOf())));
                $('#end_date').datepicker('setStartDate', startSDate).datepicker('setMinDate', startSDate);
                $('#end_date').val('');
            });
        $('#end_date')
            .datepicker({
                startDate: startSDate,
                minDate: startSDate,
                autoclose: true,
                format: 'yyyy-mm-dd'
            });
    });

    $(document).ready(function () {
        $('#end_time').timepicker({'timeFormat': 'H:i:s'});
        $('#start_time').timepicker({'timeFormat': 'H:i:s'});
        $('#end_time').keydown(function () { return false; });
        $('#start_time').keydown(function () { return false; });
    });

    $(document).on('change','#ContestAllowMaximumTimeLimit',function () {
        if($("#ContestAllowMaximumTimeLimit").val() == 'YES')
        {
            $("#maxTime").show();
            $("#ContestMaximumTimeLimit").attr("required",true);
            $("#ContestMaximumTimeLimit").val("10");
        }
        else
        {
            $("#maxTime").hide();
            $("#ContestMaximumTimeLimit").val("10");
        }

    });

</script>

<script>
    $(document).ready(function(){
        CKEDITOR.replace( 'editor1');
        CKEDITOR.instances.editor1.on( 'key', function() {
            var str = CKEDITOR.instances.editor1.getData();

            if (str.length > 1000) {
                CKEDITOR.instances.editor1.setData(str.substring(0, 1000));
            }
        });
    });
</script>