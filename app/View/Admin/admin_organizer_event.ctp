<div class="Inner-banner">
    <div class="container">
        <div class="row">
       
            <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 inner-page-text"> <h2>Organizer List</h2> </div>
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
                                <?php if(!empty($eventOrganizer)){ ?>

                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Event title</th>
                                        <th>Image</th>
                                        <th>Name</th>
                                        <th>Mobile</th>
                                        <th>Status</th>
                                        <th>Option</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php

									$num = 1;
									foreach ($eventOrganizer as $value){
                                        ?>
                                        <tr>
                                            <td><?php echo $num++; ?></td>
                                            <td><?php echo $value['Event']['title']; ?></td>
                                            <td>
                                                <img src="<?php echo $value['EventOrganizer']['image']; ?>" style="width: 150px;">
                                            </td>
                                            <td><?php echo $value['EventOrganizer']['name']; ?></td>
                                            <td>
                                                <?php echo $value['EventOrganizer']['mobile']; ?>
                                            </td>
                                            <td>
                                                <button type="button" id="changeStatus" class="btn btn-primary btn-xs" row-id="<?php echo $value['EventOrganizer']['id']; ?>" ><?php echo $value['EventOrganizer']['status']; ?></button>
                                            </td>
                                            <td>
                                                <button type="button" id="edit" class="btn btn-primary btn-xs" row-id="<?php echo $value['EventOrganizer']['id']; ?>" ><i class="fa fa-edit fa-2x"></i></button>
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
                                <h2>No organizer Found..!</h2>
                            </div>
                        <?php } ?>
                            <div class="clear"></div>

                            <div class="form-group row">
                                <div class="col-sm-12">
                                    <button type="button" id="addFormButton" class="btn btn-primary btn-xs">
                                        Add Organizer
                                    </button>
                                </div>

                                <div class="clear"></div>
                                    <?php echo $this->element('message'); ?>
                                <div class="clear"></div>

                                <div class="col-sm-12" id="addForm" style="display: <?php echo isset($this->request->data['EventOrganizer'])?'block':'none'; ?>">

                                    <?php echo $this->Form->create('EventOrganizer',array('type'=>'file','method'=>'post','class'=>'form-horizontal')); ?>

                                        <div class="form-group">
                                            <div class="col-sm-12">
                                                <label>Organizer Name</label>
                                                <?php echo $this->Form->input('name',array('type'=>'text','placeholder'=>'Organizer name','label'=>false,'class'=>'form-control cnt','required'=>true)); ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-12">
                                                <label>Organizer Mobile</label>
                                                <?php echo $this->Form->input('mobile',array('type'=>'text','placeholder'=>'Organizer Mobile','label'=>false,'class'=>'form-control cnt','required'=>true)); ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-12">
                                                <label>Organizer Image</label>
                                                <?php echo $this->Form->input('image',array('type'=>'file','label'=>false,'class'=>'form-control cnt')); ?>
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
                <h4 class="modal-title">Edit Organizer</h4>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <form id="editForm" method="POST">

                        <div class="form-group">
                            <div class="col-sm-12">
                                <input type="text" name="mobile" id="mobileHolder" placeholder="Please enter mobile" class="form-control cnt" required>
                                <input type="hidden" name="id" id="IDholder">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <input type="text" name="name" id="nameHolder" placeholder="Please enter name" class="form-control cnt" required>
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
                url: baseurl+'/admin/admin/change_event_organizer_status',
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

        $(document).on('click','#edit',function(e){
            var ID = $(this).attr('row-id');
            var thisButton = $(this);
            $.ajax({
                url: baseurl+'/admin/admin/get_event_organizer_edit',
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
                        $("#mobileHolder").val(result.data.mobile);
                        $("#nameHolder").val(result.data.name);
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
                url: baseurl+'/admin/admin/edit_event_organizer',
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



    });
</script>