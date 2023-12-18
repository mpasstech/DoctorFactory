<div class="modal fade" id="doctorListModal" role="dialog" style="z-index: 9999;">
    <div class="modal-dialog modal-md">
        <form id="add_child_form">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Doctor List</h4>
                </div>
                <div class="modal-body">
                    <div class="row container_list">
                        <table class="table">
                            <tr>
                                <th>#</th>
                                <th>Doctor Name</th>
                                <th>Education</th>
                                <th>Mobile</th>
                                <th>Action</th>
                            </tr>
                            <?php if(!empty($data['data']['staff_list'])){ foreach($data['data']['staff_list'] as $key => $list){ ?>
                                <tr>
                                    <td><?php echo $key+1; ?></td>
                                    <td><?php echo $list['name']; ?></td>
                                    <td><?php echo $list['sub_title']; ?></td>
                                    <td><?php echo $list['mobile']; ?></td>
                                    <td>
                                        <button type="button" class="doctor_update_btn btn btn-info btn-xs" data-id="<?php echo base64_encode($list['staff_id']);?>"  title = "Edit this doctor" href="javascript:void(0);"><i class="fa fa-edit fa-1x fa-fw"></i></button>
                                        <button type="button" class="doctor_delete_btn  btn btn-danger btn-xs" data-id="<?php echo base64_encode($list['staff_id']);?>" title="Delete this doctor" href="javascript:void(0);"><i class="fa fa-trash fa-1x fa-fw"></i></button>
                                    </td>
                                </tr>
                            <?php }}else{ ?>
                                <tr><td colspan="5">No doctor list found</td></tr>
                            <?php } ?>
                        </table>
                    </div>

               </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-success add_new_doctor_btn"><i class="fa fa-plus"></i> Add New Doctor</button>
                </div>
            </div>
        </form>
    </div>

    <script type="text/javascript">
        $(function () {

            $('button').button({loadingText: '<i class="fa fa-spinner fa-pulse fa-1x fa-fw"></i>'});


            $(document).off('click','.add_new_doctor_btn, .doctor_update_btn');
            $(document).on('click','.add_new_doctor_btn, .doctor_update_btn',function(){
                var $btn = $(this);
                $.ajax({
                    url: "<?php echo Router::url('/prescription/doctor_manage_modal',true);?>",
                    type:'POST',
                    data:{di:$(this).attr('data-id')},
                    beforeSend:function(){
                        $btn.button('loading');
                    },
                    success: function(response){
                        $btn.button('reset');
                        //$("#doctorListModal").modal('hide')
                        var html = $(response).filter('#doctorManageModal');
                        $(html).modal('show');
                    },error:function () {
                        $btn.button('reset');
                        flash("Error",'Something went wrong on server.', "danger",'center');
                    }
                });
            });     $(document).off('click','.add_new_doctor_btn');

            $(document).off('click','.doctor_delete_btn');
            $(document).on("click",".doctor_delete_btn",function(){
                var $btn = $(this);
                var tr = $(this).closest('tr');
                var di = $(this).attr('data-id');
                var jc = $.confirm({
                    title: "Delete Doctor",
                    content: 'Are you sure you want to delete this doctor?',
                    type: 'red',
                    buttons: {
                        ok: {
                            text: "Yes",
                            btnClass: 'btn-primary',
                            keys: ['enter'],
                            name:"ok",
                            action: function(e){
                                $.ajax({
                                    type: 'POST',
                                    url: "<?php echo Router::url('/prescription/manage_doctor',true);?>",
                                    data: {at:'DELETE',di: di},
                                    beforeSend: function () {
                                        $btn.button('loading').html('Wait..')
                                    },
                                    success: function (response) {
                                        $btn.button('reset');
                                        response = JSON.parse(response);
                                        if(response.status == 1){
                                            flash("Doctor",response.message, "success",'center');
                                            $(tr).hide();
                                            jc.close();
                                        }else{
                                            flash("Warning",response.message, "warning",'center');
                                        }

                                    },
                                    error: function (data) {
                                        $btn.button('reset');
                                        flash("Error",'Something went wrong on server.', "danger",'center');
                                    }
                                });
                                return false;
                            }
                        },
                        cancel: function(){

                        }
                    }
                });

            });

        });
    </script>


</div>
