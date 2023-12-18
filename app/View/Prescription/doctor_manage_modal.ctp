<div class="modal fade" id="doctorManageModal" role="dialog" tabindex="-1" style="z-index: 9999;">
    <div class="modal-dialog modal-sm">
        <form id="doctor_manage_form">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><?php echo empty($data)?'Add Doctor':'Edit Doctor'; ?></h4>
                    <input type="hidden" name="at" value="<?php echo empty($data)?'ADD':'UPDATE'; ?>">
                    <?php if(!empty($data)){ ?>
                        <input type="hidden" name="di" value="<?php echo base64_encode($data['id']); ?>">
                    <?php } ?>

                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group">
                            <div class="col-sm-12">
                                <label style="width: 100%;">Doctor Name </label>
                                <input type="text" value="<?php echo @$data['name']; ?>"  name="doctor_name" maxlength="20" minlength="3" oninvalid = 'this.setCustomValidity("Please enter  doctor name")' oninput = 'setCustomValidity("")' required="required" class="form-control">
                            </div>
                            <div class="col-sm-12">
                                <label style="width: 100%;">Doctor Mobile </label>
                                <input type="text" value="<?php echo @$data['mobile']; ?>" data-masked-input="9999999999"  maxlength="10" minlength="10" name="doctor_mobile" class="form-control" oninvalid = 'this.setCustomValidity("Please enter  10 digit mobile number")' oninput = 'setCustomValidity("")' required="required">
                            </div>

                            <div class="col-sm-12">
                                <label style="width: 100%;">Education </label>
                                <input type="text"  value="<?php echo @$data['sub_title']; ?>" maxlength="50" minlength="1" name="education" class="form-control" >
                            </div>
                        </div>
                    </div>
               </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-xs" data-dismiss="modal"><i class="fa fa-close"></i> Cancel</button>
                    <button type="reset" class="btn btn-warning btn-xs"><i class="fa fa-refresh"></i> Reset</button>
                    <button type="submit" class="btn btn-success btn-xs save_doctor_btn"><i class="fa fa-save"></i> Save</button>
                </div>
            </div>
        </form>
    </div>

    <script type="text/javascript">
        $(function () {


            $(document).off('submit','#doctor_manage_form');
            $(document).on('submit','#doctor_manage_form',function(e){
                e.preventDefault();
                var $btn = $(this).find('.save_doctor_btn');
                $.ajax({
                    url: "<?php echo Router::url('/prescription/manage_doctor',true);?>",
                    type:'POST',
                    data:$(this).serialize(),
                    beforeSend:function(){
                        $btn.button('loading').text('Saving..');
                    },
                    success: function(response){
                        $btn.button('reset');
                        response = JSON.parse(response);
                        if(response.status == 1){
                            flash("Doctor",response.message, "success",'center');
                            $("#doctorManageModal").modal('hide');
                            var html = $('.container_list', $(response.data.update));
                            $("#doctorListModal .container_list").html(html);
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
