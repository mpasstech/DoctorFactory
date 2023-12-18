<div class="modal fade" id="switchModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-md" style="width: 35%;" >
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Switch Patient</h4>
            </div>
            <div class="modal-body" style="padding: 0px; max-height: 350px; overflow-y: auto;">
                    <?php if(!empty($patient_list)){ ?>
                        <table style="margin-bottom: 0px;" class="table table-hover switch_patient_table">
                            <thead>
                            <tr>
                                <th>Patient Name</th>
                                <th>UHID</th>
                                <th>Type</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($patient_list as $key =>$value){ ?>
                                <tr>
                                    <td><label> <input class="switch_patient_radio" <?php echo ($value['patient_id']==$patient_id && $value['patient_type']==$patient_type)?'checked':''; ?> type="radio" name="switch_patient_id" value="<?php echo base64_encode($value['patient_id'].'##'.$value['patient_type'])?>"> <span class="switch_patient_name"><?php echo $value['patient_name']; ?></span></label></td>
                                    <td><?php echo $value['uhid']; ?></td>
                                    <td><?php echo $value['patient_type']; ?></td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    <?php } ?>
            </div>
            <div class="modal-footer" style="padding: 6px 5px; margin-top: 0px; ">
                <button type="button" class="btn btn-xs btn-danger" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-xs btn-success save_switch">Save</button>
            </div>
        </div>
    </div>

    <style>
        .switch_patient_table td{
            padding: 3px 4px;
        }
        #switchModal .modal-header{
            padding: 3px;
        }

    </style>
    <script type="text/javascript">
        $(function () {

            $(document).off('click','.switch_patient_table td');
            $(document).on('click','.switch_patient_table td',function () {
                $(this).find('input[type=radio]').prop('checked',true);
            });

            $(document).off('click','.save_switch');
            $(document).on('click','.save_switch',function(){
                var $btn =$(this);
                var selected = $(".switch_patient_radio:checked").val();
                var n = $(".switch_patient_radio:checked").closest('tr').find(".switch_patient_name").text();
                var ai = "<?php echo $appointment_id; ?>";
                $.ajax({
                    type:'POST',
                    url: baseurl+"app_admin/save_switch",
                    data:{selected:selected,ai:ai,n:n},
                    beforeSend:function(){
                        $btn.button('loading').html('<i class="fa fa-spinner fa-pulse fa-1x fa-fw"></i>  Wait..');
                    },
                    success:function(response){
                        $btn.button('reset');
                        var response = JSON.parse(response);
                        if(response.status==1){
                            $("#switchModal").modal("hide");
                            $("#appointment_date").trigger('changeDate');

                        }else{
                            $.alert(response.message);
                        }
                    },
                    error: function(){
                        $btn.button('reset');
                        $.alert("Sorry something went wrong on server.");
                    }
                });
            });



        });
    </script>


</div>
