<div class="Inner-banner">
    <div class="container">
        <div class="row">

            <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 inner-page-text"> <h2>Show List</h2> </div>
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
                        <div class="Social-login-box payment_bx">

                            <div class="form-group row">
                                <div class="col-sm-12">
                                    <div class="table-responsive">
                                        <?php if(!empty($eventShow)){ ?>

                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Event Title</th>
                                                <th>Title</th>
                                                <th>Start Time</th>
                                                <th>End Time</th>
                                                <th>Status</th>
                                                <th>Option</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php

                                            $num = 1;
                                            foreach ($eventShow as $value){
                                                ?>
                                                <tr>
                                                    <td><?php echo $num++; ?></td>
                                                    <td><?php echo $value['Event']['title']; ?></td>
                                                    <td><?php echo $value['EventShow']['title']; ?></td>
                                                    <td><?php echo date("d-M-Y H:i:s",strtotime($value['EventShow']['start_datetime'])); ?></td>
                                                    <td><?php echo date("d-M-Y H:i:s",strtotime($value['EventShow']['end_datetime'])); ?></td>
                                                    <td>
                                                        <button type="button" id="changeStatus" class="btn btn-primary btn-xs" row-id="<?php echo $value['EventShow']['id']; ?>" ><?php echo $value['EventShow']['status']; ?></button>
                                                    </td>
                                                    <td>
                                                        <button type="button" id="edit" class="btn btn-primary btn-xs" row-id="<?php echo $value['EventShow']['id']; ?>" ><i class="fa fa-edit fa-2x"></i></button>
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
                                    <h2>No Show Found..!</h2>
                                </div>
                            <?php } ?>
                            <div class="clear"></div>

                            <div class="form-group row">
                                <div class="col-sm-12">
                                    <button type="button" id="addFormButton" class="btn btn-primary btn-xs">
                                        Add Show
                                    </button>
                                </div>

                                <div class="clear"></div>
                                <?php echo $this->element('message'); ?>
                                <div class="clear"></div>

                                <div class="col-sm-12" id="addForm" style="display: <?php echo isset($this->request->data['EventShow'])?'block':'none'; ?>">

                                    <?php echo $this->Form->create('EventShow',array('type'=>'file','method'=>'post','class'=>'form-horizontal')); ?>

                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <label>Show Title</label>
                                            <?php echo $this->Form->input('title',array('type'=>'text','placeholder'=>'Show Title','label'=>false,'class'=>'form-control cnt','required'=>true)); ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <label>Show Start Date</label>
                                            <?php echo $this->Form->input('start_date',array('type'=>'text','placeholder'=>'Show Start Date','label'=>false,'id'=>'start_date','class'=>'form-control cnt','required'=>true)); ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <label>Show Start Time</label>
                                            <?php echo $this->Form->input('start_time',array('type'=>'text','placeholder'=>'Show Start Time','label'=>false,'id'=>'start_time','class'=>'form-control cnt','required'=>true)); ?>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <label>Show End Date</label>
                                            <?php echo $this->Form->input('end_date',array('type'=>'text','placeholder'=>'Show End Date','label'=>false,'id'=>'end_date','class'=>'form-control cnt','required'=>true)); ?>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <label>Show End Time</label>
                                            <?php echo $this->Form->input('end_time',array('type'=>'text','placeholder'=>'Show End Time','label'=>false,'id'=>'end_time','class'=>'form-control cnt','required'=>true)); ?>
                                        </div>
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>




<div class="modal fade" id="editModal" role="dialog">

    <div class="modal-dialog modal-sm">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Edit Show</h4>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <form id="editForm" method="POST">

                        <div class="form-group">
                            <div class="col-sm-12">
                                <input type="text" name="title" id="titleHolder" placeholder="Please enter title" class="form-control cnt" required>
                                <input type="hidden" name="id" id="IDholder">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-12">
                                <input type="text" name="start_date" id="startDateHolder" placeholder="Please enter start date" class="form-control cnt" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <input type="text" name="start_time" id="startTimeHolder" placeholder="Please enter start time" class="form-control cnt" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-12">
                                <input type="text" name="end_date" id="endDateHolder" placeholder="Please enter end date" class="form-control cnt" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-12">
                                <input type="text" name="end_time" id="endTimeHolder" placeholder="Please enter end time" class="form-control cnt" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-12">
                                <button type="submit" id="editBtn" name="submitForm" class="form-control" >Edit</button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
            <div class="modal-footer">

            </div>

        </div>
    </div>

</div>




<script>
    $(document).ready(function(){
        $(document).on('click','#addFormButton',function () {
            $("#addForm").toggle();
        });

        $(document).on('click','#changeStatus',function(e){
            var ID = $(this).attr('row-id');
            var thisButton = $(this);
            $.ajax({
                url: baseurl+'/admin/admin/change_event_show_status',
                data:{ID:ID},
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

        $(document).ready(function(){

            var t = "<?php echo $event['Event']['start_datetime']; ?>".split(/[- :]/);
            var startDefaultDate = new Date(Date.UTC(t[0], t[1]-1, t[2], t[3], t[4], t[5]));
            var d = "<?php echo $event['Event']['end_datetime']; ?>".split(/[- :]/);
            var endDefaultDate = new Date(Date.UTC(d[0], d[1]-1, d[2], d[3], d[4], d[5]));

            var startSDate = startDefaultDate;
            $('#start_date').datepicker({
                startDate: startDefaultDate,
                endDate: endDefaultDate,
                autoclose: true,
                format: 'yyyy-mm-dd'
            }).on('changeDate', function(selected){
                    startSDate = new Date(selected.date.valueOf());
                    startSDate.setDate(startSDate.getDate(new Date(selected.date.valueOf())));
                    $('#end_date').datepicker('setStartDate', startSDate).datepicker('setMinDate', startSDate);
                    $('#end_date').val('');
                });
            $('#end_date').datepicker({
                    startDate: startDefaultDate,
                    endDate: endDefaultDate,
                    minDate: startSDate,
                    autoclose: true,
                    format: 'yyyy-mm-dd'
                });



            var startSDateEdit = startDefaultDate;
            $('#startDateHolder').datepicker({
                startDate: startDefaultDate,
                endDate: endDefaultDate,
                autoclose: true,
                format: 'yyyy-mm-dd'
            }).on('changeDate', function(selected){
                startSDateEdit = new Date(selected.date.valueOf());
                startSDateEdit.setDate(startSDateEdit.getDate(new Date(selected.date.valueOf())));
                $('#endDateHolder').datepicker('setStartDate', startSDateEdit).datepicker('setMinDate', startSDateEdit);
                $('#endDateHolder').val('');
            });
            $('#endDateHolder').datepicker({
                startDate: startDefaultDate,
                endDate: endDefaultDate,
                minDate: startSDateEdit,
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





        $(document).on('click','#edit',function(e){
            var ID = $(this).attr('row-id');
            var thisButton = $(this);
            $.ajax({
                url: baseurl+'admin/admin/get_event_show_edit',
                data:{ID:ID},
                type:'POST',
                beforeSend:function(){
                    $(thisButton).button('loading').html('<i class="fa fa-spinner fa-pulse fa-2x fa-fw">');
                },
                success: function(result){
                    $(thisButton).button('reset');
                    var result = JSON.parse(result);
                    if(result.status == 1)
                    {
                        var startDateTime = result.data.start_datetime.split(' ');
                        var endDateTime = result.data.end_datetime.split(' ');
                        
                        $("#startDateHolder").val(startDateTime[0]);
                        $("#startTimeHolder").val(startDateTime[1]);
                        $("#endDateHolder").val(endDateTime[0]);
                        $("#endTimeHolder").val(endDateTime[1]);
                        $("#titleHolder").val(result.data.title);
                        $("#IDholder").val(result.data.id);
                        $("#editModal").modal('show');
                    }
                    else
                    {
                        alert('Sorry, Could not get data!');
                    }
                }
            });
        });

        $(document).on('submit','#editForm',function (e) {
            e.stopPropagation();
            e.preventDefault();
            var dataToPost = $(this).serialize();
            var thisButton = $('#editBtn');
            $.ajax({
                url: baseurl+'admin/admin/edit_event_show',
                data:dataToPost,
                type:'POST',
                beforeSend:function(){
                    $(thisButton).button('loading').html('<i class="fa fa-spinner fa-pulse fa-2x fa-fw">');
                },
                success: function(result){
                    $(thisButton).button('reset');
                    var result = JSON.parse(result);
                    if(result.status == 1)
                    {
                        $("#editModal").modal('hide');
                        $("#editForm").trigger('reset');
                        location.reload();
                    }
                    else
                    {
                        alert(result.message);
                    }
                }
            });
        });

        $(document).ready(function () {
            $('#endTimeHolder').timepicker({'timeFormat': 'H:i:s'});
            $('#startTimeHolder').timepicker({'timeFormat': 'H:i:s'});
            $('#endTimeHolder').keydown(function () { return false; });
            $('#startTimeHolder').keydown(function () { return false; });
        });



    });
</script>