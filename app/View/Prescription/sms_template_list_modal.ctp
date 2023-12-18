<div class="modal fade" id="smsTemplateListModal" role="dialog" tabindex="-1" style="z-index: 9999;">
    <div class="modal-dialog modal-md">
        <form id="add_child_form">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">SMS Template</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <label>SMS Template List</label>
                        <table class="table">
                            <?php if(!empty($data['status']==1)){ foreach($data['data']['list'] as $key => $list){ ?>
                                <tr>
                                    <td width="3%"><?php echo $key+1; ?></td>
                                    <td width="95%"><textarea style="width: 100%;" rows="2" ><?php echo $list['message']; ?></textarea></td>
                                    <td width="2%">
                                        <button type="button" class="template_update_btn btn btn-success btn-xs" data-id="<?php echo base64_encode($list['id']);?>"  title = "Save this template" href="javascript:void(0);"><i class="fa fa-save fa-1x fa-fw"></i></button>
                                        <button type="button" class="template_delete_btn  btn btn-danger btn-xs" data-id="<?php echo base64_encode($list['id']);?>" title="Delete this template" href="javascript:void(0);"><i class="fa fa-trash fa-1x fa-fw"></i></button>
                                    </td>
                                </tr>
                            <?php }}else{ ?>
                                <tr><td colspan="3">No sms template found</td></tr>
                            <?php } ?>
                        </table>
                    </div>
                    <div class="row">
                        <label>Add New SMS Template</label>
                        <textarea id="new_message" style="width: 100%;" rows="3" placeholder="Write your message here..." ></textarea>

                    </div>
               </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-close"></i> Cancel</button>
                    <button type="reset" class="btn btn-warning"><i class="fa fa-refresh"></i> Reset</button>
                    <button type="button" class="btn btn-success save_template_btn"><i class="fa fa-save"></i> Save</button>
                </div>
            </div>
        </form>
    </div>

    <script type="text/javascript">
        $(function () {

            $(document).off('click','.template_update_btn, .template_delete_btn');
            $(document).on('click','.template_update_btn, .template_delete_btn',function(){

                var message = $(this).closest('tr').find('textarea').val();
                var tr = $(this).closest('tr');
                var ti = $(this).attr('data-id');
                var action_type = ($(this).hasClass('template_update_btn'))?'UPDATE':'DELETE';
                var $btn = $(this);
                $($btn).button({loadingText: '<i class="fa fa-spinner fa-pulse fa-1x fa-fw"></i>'});
                $.ajax({
                    url: "<?php echo Router::url('/prescription/manage_sms_template',true);?>",
                    type:'POST',
                    data:{ti:ti,action_type:action_type,message:message},
                    beforeSend:function(){
                        $($btn).button('loading');
                    },
                    success: function(response){
                        $btn.button('reset');
                        response = JSON.parse(response);
                        if(response.status == 1){
                            flash("SMS Template",response.message, "success",'center');
                            var html = $(response.data.li).filter('#smsTemplateListModal');
                            $("#smsTemplateListModal .modal-body").html($(html).find(".modal-body").html());
                        }else{
                            flash("Error",response.message, "danger");
                        }
                    },error:function () {
                        $btn.button('reset');
                        flash("Error",'Something went wrong on server.', "danger");
                    }
                });
            });

            $(document).off('click','.save_template_btn');
            $(document).on('click','.save_template_btn',function(){
                var message = $("#new_message").val();
                var action_type = "ADD";
                var $btn = $(this);
                $.ajax({
                    url: "<?php echo Router::url('/prescription/manage_sms_template',true);?>",
                    type:'POST',
                    data:{action_type:action_type,message:message},
                    beforeSend:function(){
                        $btn.button('loading').text('Saving..');
                    },
                    success: function(response){
                        $btn.button('reset');
                        response = JSON.parse(response);
                        if(response.status == 1){
                            flash("SMS Template",response.message, "success",'center');
                            var html = $(response.data.li).filter('#smsTemplateListModal');
                            $("#smsTemplateListModal .modal-body").html($(html).find(".modal-body").html());
                        }else{
                            flash("Warning",response.message, "warning",'center');
                        }
                    },error:function () {
                        $btn.button('reset');
                        flash("Error",'Something went wrong on server.', "danger",'center');
                    }
                });
            });




        });
    </script>


</div>
