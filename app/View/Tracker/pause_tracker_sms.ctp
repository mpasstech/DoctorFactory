<?php if(!empty($patient_data)){ foreach ($patient_data as $key =>$value){ ?>
    <li><input type="radio" class="radio_btn" name="live_mssage" <?php echo ($break_data['patient_name']==$value['msg'])?'checked':''; ?>  > <input style="width: 82%;" type="text" value="<?php echo $value['msg']; ?>" class="custom_message_input"> <button type="button" class="btn btn-xs btn-danger delete_sms_li"><i class="fa fa-trash"></i> </button> </li>
<?php }} ?>
<li><input type="radio" class="radio_btn" name="live_mssage"> <input style="width: 82%;" type="text" value="" class="custom_message_input"> </li>


<script>
    $(function () {

        $(document).off("focus",".custom_message_input");
        $(document).on("focus",".custom_message_input",function(e){
           $(this).closest("li").find("input[type='radio']").prop("checked",true);

        });

        $(document).off("mousedown",".delete_sms_li");
        $(document).on("mousedown",".delete_sms_li",function(e){
            $(this).closest("li").remove();
        });

    })
</script>