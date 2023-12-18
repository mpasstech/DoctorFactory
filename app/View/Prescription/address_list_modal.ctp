<div class="modal fade" id="addressListModal" tabindex="-1" style="z-index: 9999;">


    <div class="modal-dialog modal-md" style="width: 60%;">
        <form id="add_child_form">
            <div class="modal-content" >
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Clinic List</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <table class="table clinic_table">
                            <tr>
                                <th>#</th>
                                <th width="20%;">Clinic Name</th>
                                <th width="40%;">Address</th>
                                <th>Pincode</th>

                                <th>State</th>
                                <th>City</th>
                                <th>Action</th>
                            </tr>
                            <?php if(!empty($data['data']['address_list'])){ foreach($data['data']['address_list'] as $key => $list){ ?>
                                <tr>
                                    <td><?php echo $key+1; ?></td>
                                    <td><?php echo $list['clinic_name']; ?></td>
                                    <td><?php echo $list['address']; ?></td>
                                    <td><?php echo $list['pincode']; ?></td>
                                    <td><?php echo $list['state_name']; ?></td>
                                    <td><?php echo $list['city_name']; ?></td>
                                    <td>
                                        <button type="button" class="address_update_btn btn btn-info btn-xs" data-id="<?php echo base64_encode($list['id']);?>"  title = "Edit this address" href="javascript:void(0);"><i class="fa fa-edit fa-1x fa-fw"></i></button>
                                        <button type="button" class="address_delete_btn  btn btn-danger btn-xs" data-id="<?php echo base64_encode($list['id']);?>" title="Delete this address" href="javascript:void(0);"><i class="fa fa-trash fa-1x fa-fw"></i></button>
                                    </td>
                                </tr>
                            <?php }}else{ ?>
                                <tr><td colspan="6">No clinic list found</td></tr>
                            <?php } ?>
                        </table>
                    </div>

               </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-success add_new_address_btn"><i class="fa fa-plus"></i> Add New Address</button>
                </div>
            </div>
        </form>
    </div>

    <style>
        .clinic_table td, .clinic_table th{
            border: none;
        }
        .clinic_table tr{
            border-bottom: 1px solid #dfdfdf;
        }
    </style>
    <script type="text/javascript">
        $(function () {

            $('button').button({loadingText: '<i class="fa fa-spinner fa-pulse fa-1x fa-fw"></i>'});

            $(document).off('click','.add_new_address_btn, .address_update_btn');
            $(document).on('click','.add_new_address_btn, .address_update_btn',function(){
                var $btn = $(this);
                $.ajax({
                    url: "<?php echo Router::url('/prescription/address_manage_modal',true);?>",
                    type:'POST',
                    data:{ai:$(this).attr('data-id')},
                    beforeSend:function(){
                        $btn.button('loading');
                    },
                    success: function(response){
                        $btn.button('reset');
                        //$("#doctorListModal").modal('hide')
                        var html = $(response).filter('#addressManageModal');
                        $(html).modal('show');
                    },error:function () {
                        $btn.button('reset');
                        flash("Error",'Something went wrong on server.', "danger",'center');
                    }
                });
            });     $(document).off('click','.add_new_doctor_btn');

            $(document).off('click','.address_delete_btn');
            $(document).on("click",".address_delete_btn",function(){
                var $btn = $(this);
                var tr = $(this).closest('tr');
                var ai = $(this).attr('data-id');
                var jc = $.confirm({
                    title: "Delete Address",
                    content: 'Are you sure you want to delete this address?',
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
                                    url: "<?php echo Router::url('/prescription/manage_address',true);?>",
                                    data: {at:'DELETE',ai: ai},
                                    beforeSend: function () {
                                        $btn.button('loading').html('Wait..')
                                    },
                                    success: function (response) {
                                        $btn.button('reset');
                                        response = JSON.parse(response);
                                        if(response.status == 1){
                                            flash("Address",response.message, "success",'center');
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
