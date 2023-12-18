<div class="Inner-banner">
    <div class="container">
        <div class="row">
       
            <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 inner-page-text"> <h2>User List</h2> </div>
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
                            <?php echo $this->Form->create('Search',array('type'=>'get','url'=>array('controller'=>'admin','action' => 'search_list_user'),'admin'=>true)); ?>
                            <div class="form-group">
                                <div class="col-sm-4">
                                    <?php echo $this->Form->input('name', array('type' => 'text', 'placeholder' => 'Insert username', 'label' => 'Search by username', 'class' => 'form-control')); ?>
                                </div>
                                <div class="col-sm-4">
                                    <?php echo $this->Form->input('email', array('type' => 'text', 'placeholder' => 'Insert email', 'label' => 'Search by email', 'class' => 'form-control')); ?>
                                </div>
                                <div class="col-sm-2">
                                    <?php echo $this->Form->label('&nbsp;'); ?>
                                    <?php echo $this->Form->submit('Search',array('class'=>'Btn-typ3','id'=>'search')); ?>
                                </div>
                                <div class="col-sm-2">
                                    <?php echo $this->Form->label('&nbsp;'); ?>
                                    <div class="submit">
                                        <a href="<?php echo $this->Html->url(array('controller'=>'admin','action'=>'list_user')) ?>"><button type="button" class="Btn-typ3" >Reset</button></a>
                                    </div>
                                </div>
                            </div>
                            <div class="clear"></div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <button type="button" class='Btn-typ3' id="addUser" style="float: right">Add New User</button>
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
                                        <th>Username</th>
                                        <th>Email</th>
                                        <th>Mobile</th>
                                        <th>Created On</th>
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
                                            <td><?php echo $value['User']['username']; ?></td>
                                            <td><?php echo $value['User']['email']; ?></td>
                                            <td><?php echo $value['User']['mobile']; ?></td>
                                            <td><?php echo date("d-M-Y H:i:s",strtotime($value['User']['created'])); ?></td>
                                            <td>
                                                <button type="button" id="changeStatus" class="btn btn-primary btn-xs" row-id="<?php echo $value['User']['id']; ?>" ><?php echo ($value['User']['status'] == 'Y')?'ACTIVE':'INACTIVE'; ?></button>
                                            </td>
											<td>
                                                <button type="button" id="edit" class="btn btn-primary btn-xs"  row-id="<?php echo $value['User']['id']; ?>" ><i class="fa fa-edit fa-2x"></i></button>
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
                                <h2>No User..!</h2>
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





<div class="modal fade" id="editUserModal" role="dialog">

    <div class="modal-dialog modal-sm">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Edit User</h4>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <form id="editUserForm" method="POST">

                        <div class="form-group">
                            <div class="col-sm-12">
                                <input type="text" name="username" id="usernameHolder" placeholder="Please enter Username" class="form-control cnt">
                                <input type="hidden" name="id" id="userIDholder">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <input type="email" name="email" id="emailHolder" placeholder="Please enter email" class="form-control cnt" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <input type="text" name="mobile" id="mobileHolder" placeholder="Please enter phone number" class="form-control cnt" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-12">
                                <button type="submit" id="editBtn" name="submitForm" class="form-control" >Edit User</button>
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




<div class="modal fade" id="addUserModal" role="dialog">

    <div class="modal-dialog modal-sm">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add User</h4>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <form id="addUserForm" method="POST">

                        <div class="form-group">
                            <div class="col-sm-12">
                                <input type="text" name="username" placeholder="Please enter Username" class="form-control cnt">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <input type="email" name="email" placeholder="Please enter email" class="form-control cnt" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <input type="text" name="mobile" placeholder="Please enter phone number" class="form-control cnt" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <input type="password" name="password" placeholder="Please enter password" id="pass" class="form-control cnt" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-12">
                                <input type="password" name="conf_password" placeholder="Please enter password again" id="confPass" class="form-control cnt" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-12">
                                <select class="form-control cnt" name="role_id" required>
                                <option value="">Please select</option>
                                <option value="2">Super Admin</option>
                                <option value="4">Support Admin</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-12">
                                <button type="submit" id="addBtn" name="submitForm" class="form-control" >Add User</button>
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

                $(document).on('click','#changeStatus',function(e){
                    var rowID = $(this).attr('row-id');
                    var thisButton = $(this);
                    $.ajax({
                        url: baseurl+'/admin/admin/change_user_status',
                        data:{rowID:rowID},
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
                    var rowID = $(this).attr('row-id');
                    var thisButton = $(this);
                    $.ajax({
                        url: baseurl+'/admin/admin/get_edit_user',
                        data:{rowID:rowID},
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
                                $("#emailHolder").val(result.data.email);
                                $("#userIDholder").val(result.data.id);
                                $("#usernameHolder").val(result.data.username);
                                $("#editUserModal").modal('show');
                            }
                            else
                            {
                                alert('Sorry, Could not edit.');
                            }
                        }
                    });
                });


                $(document).on('submit','#editUserForm',function(e){
                    e.stopPropagation();
                    e.preventDefault();
                    var dataToPost = $(this).serialize();
                    var thisButton = $('#editBtn');
                    $.ajax({
                        url: baseurl+'/admin/admin/edit_user',
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
                                $("#editUserModal").modal('hide');
                                $("#editUserForm").trigger('reset');
                                location.reload();
                            }
                            else
                            {
                                alert(result.message);
                            }
                        }
                    });
                });


                $(document).on('click','#addUser',function (e) {
                    $("#addUserForm").trigger('reset');
                    $("#addUserModal").modal('show');
                });

                $(document).on('submit','#addUserForm',function (e) {
                            e.stopPropagation();
                            e.preventDefault();
                            if($('#pass').val() != $('#confPass').val())
                            {
                                alert('Confirm password does not match');
                                return false;
                            }
                             var thisButton = $('#addBtn');
                            var dataToPost = $(this).serialize();
                            $.ajax({
                                url: baseurl+'/admin/admin/add_user',
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
                                        $("#addUserModal").modal('hide');
                                        $("#addUserForm").trigger('reset');
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