
<div class="modal-dialog modal-sm">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Edit Patient</h4>
        </div>
        <div class="modal-body" style="height:230px;">

            <div class="form-group">
                <div class="col-sm-8">
                    <label>Patient Name</label>
                    <input type="text" class="form-control pat_name"  value="<?php echo @$data['first_name'].@$data['child_name']; ?>">
                </div>
                <div class="col-sm-4">
                    <label>Mobile</label>
                    <input type="text" class="form-control mobile" value="<?php echo @$data['mobile']; ?>">
                </div>
                <div class="col-sm-3">
                    <label>Gender</label>
                    <select class="form-control gender">
                        <option <?php echo ($data['gender']=='MALE')?'selected = selected':''; ?> value="MALE">Male</option>
                        <option <?php echo ($data['gender']=='FEMALE')?'selected=selected':''; ?> value="FEMALE">Female</option>
                    </select>
                </div>


                <div class="col-sm-8">
                    <label>Address</label>
                    <input type="text" class="form-control address" value="<?php echo @$data['address']; ?>">
                </div>


            </div>
        </div>
        <div class="modal-footer">
            <label class="res_message"></label>
            <button class="update_sub btn  btn-info save_btn" >Update</button>
        </div>
    </div>
</div>
<style>
    .res_message{
        float: left;
    }
    .modal-footer{text-align: center}
</style>
<script>
    $(function () {

        $(document).off('click','.save_btn');
        $(document).on('click','.save_btn',function(e){

            var thin_app_id = "<?php echo $login['thinapp_id']; ?>";
            var user_id = "<?php echo $login['id']; ?>";
            var mobile = "<?php echo $login['mobile']; ?>";
            var app_key = "MB";
            var pat_id = "<?php echo $patient_id; ?>";
            var pat_type = "<?php echo $patient_type; ?>";
            var folder_id = "<?php echo $folder_id; ?>";
            var pat_name = $('.pat_name').val();
            var patient_mobile = $(".mobile").val();
            var address = $(".address").val();
            var gender = $(".gender").val();


            var thisButton = $(this);
            $.ajax({
                url: baseurl+'/app_admin/hospital_edit_patient',

                data:{
                    thin_app_id:thin_app_id,
                    user_id:user_id,
                    mobile:mobile,
                    patient_id:pat_id,
                    patient_type:pat_type,
                    patient_mobile:patient_mobile,
                    folder_id:folder_id,
                    gender:gender,
                    patient_name:pat_name,
                    patient_address:address

                },
                type:'POST',
                beforeSend:function(){
                    $(thisButton).button('loading').html('Loading...');
                },
                success: function(result){
                    $(thisButton).button('reset');
                    var result = JSON.parse(result);
                    if(result.status == 1)
                    {
                        $('.res_message').html(result.message);
                        var table_obj = getObject();
                        table_obj = $(table_obj).closest(".table_row");
                        $(table_obj).find('.table_pat_name').html(pat_name);
                        $(table_obj).find('.table_pat_mobile').html(patient_mobile);
                        $(table_obj).find('.table_pat_gender').html(gender);
                        $(table_obj).find('.table_pat_address').html(address);
                        $("#patient_edit_modal").html('').modal('hide');
                    }
                    else
                    {
                        $('.res_message').html(result.message);
                    }
                }
            });
        });


        $('.datepicker').datepicker({
            setDate: new Date(),
            autoclose: true,
            format: 'dd-mm-yyyy'
        });
    })
</script>
