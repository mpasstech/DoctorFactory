<?php
$login = $this->Session->read('Auth.User.User');

$fields = $default_fields;

$availableData = !empty($availableData['SettingWebPrescription'])?$availableData['SettingWebPrescription']:'';
$custom_label= $final_array= $last_save_array = array();
$has_label_param = false;
if(!empty($availableData['fields']) )
{
    $last_save_array = json_decode($availableData['fields'],true);
    $has_label_param = count(array_column($last_save_array,'label')) > 0?true:false;

    if($has_label_param===false){
        $tmp_array = array_merge($last_save_array,$fields);
        $save_array = array_merge($fields,$tmp_array);
    }else{


       foreach($fields as $key =>$value){
           $save_array[$value['column']] =$value;
           if(isset($last_save_array[$value['column']]['label'])){
               $last_save_array[$value['column']]['label'] =$last_save_array[$value['column']]['label'];
           }
       }

        $save_array = array_merge($save_array,$last_save_array);
        $custom_fields = array_filter($last_save_array, function($item) {
            return ($item['custom_field'] == 'YES');
        });



       $custom_field_columns = array_column($custom_fields,'custom_field','column');
       $field_array=array();
       if(!empty($custom_fields)){
           foreach($save_array as $key =>$value){
               if($value['custom_field']=="YES" && $value['column'] == $value['label']){
                   $field_array[$key] = $value;
               }else{
                   $final_array[$value['column']] =$value;
               }
           }


           if(!empty($field_array)){
               foreach($field_array as $key => $value){
                   $final_array[$value['column']] =$value;
               }

           }else{

               foreach($save_array as $key =>$value){
                   if($value['custom_field']=="YES"){
                       unset($final_array[$key]);
                   }
               }

               if(!empty($custom_fields)){
                   $custom_fields = $this->AppAdmin->array_order_by($custom_fields,'column');
                   foreach($custom_fields as $key => $value){
                       $final_array[$value['column']] =$value;
                   }
               }
           }


       }else{
           $final_array =$save_array;
       }





    }
    $column = 1;
    if($has_label_param===false){
        foreach($save_array as $key => $value){
            if(!array_key_exists($key,$fields)){
                $label = "field".$column;
                $tmp = $final_array[$label];
                $tmp['label'] = $key;
                $tmp['status'] = 'ACTIVE';
                $tmp['order'] = $final_array[$label]['order'];
                $tmp['custom_field'] = 'YES';
                $final_array[$label] = $tmp;
                $column++;
            }else{
                $final_array[$key]=$value;
            }
        }
    }


}else{
    $final_array =$fields;
}





?>
<?php  echo $this->Html->css(array('dataTableBundle.css')); ?>
<?php  echo $this->Html->script(array('dataTableBundle.js','jquery.maskedinput-1.2.2-co.min.js','comman.js')); ?>

<style>
    .display_none{
        display: none;
    }
    .WEB_PRESCRIPTION{
        display: none;
    }
</style>


<div class="Home-section-2">

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="container">
            <div class="row">

                <div class="middle-block">
                    <!-- Heading -->
                    <h3 class="screen_title"> Setting Prescription Fields</h3>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 custom_form_box">

                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <form >


                            <div class="table table-responsive">
                                <table id="example" class="table table-striped table-bordered" style="width:100%">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Field</th>
                                            <th width="10%" class="<?php echo $setting_for; ?>">Booking Form Status <input type="checkbox" class="change_form_field"></th>
                                            <th width="10%" class="<?php echo $setting_for; ?>">Input Required</th>
                                            <th width="10%" class="<?php echo $setting_for; ?>">Booking Form Field Order Number</th>
                                            <th width="10%">Prescription Status <input type="checkbox" class="change_prescription_field"></th>
                                            <th width="10%">Prescription Field Order Number</th>
                                         </tr>
                                        </thead>
                                        <tbody>
                                            <?php $count =1;  foreach ($final_array as $key => $value){
                                                $display ="display_block";
                                                $type = "text";
                                                if(@$value['custom_field'] =="YES" && $value['column'] == $value['label']){
                                                    $display ='display_none';
                                                    $type = "hidden";
                                                }
                                                ?>
                                            <tr data-label="<?php echo isset($final_array[$key]['label'])?$final_array[$key]['label']:$key;; ?>" data-column="<?php echo isset($value['column'])?$value['column']:'';; ?>" data-input="<?php echo isset($value['input'])?$value['input']:'NO';; ?>" data-custom="<?php echo isset($value['custom_field'])?$value['custom_field']:'NO';; ?>"  default="<?php echo (isset($default_fields[$key]))?1:0; ?>" class="<?php echo $display; ?>"  >
                                                <td><?php echo $count; ?></td>
                                                <td class="field_name" >

                                                    <?php
                                                        if(@$value['custom_field'] =="YES"){
                                                            echo '<input custom-field="YES" data-column = "'.$key.'" required="required" type="'.$type.'" class="field_value" value="'.$value['label'].'">';
                                                        }else{
                                                            echo isset($final_array[$key]['label'])?$final_array[$key]['label']:$key;
                                                        }
                                                    ?>

                                                </td>
                                                <td class="<?php echo $setting_for; ?>">
                                                    <?php if($value['input'] == "YES"){ ?>
                                                        <select class="form_status form-control">
                                                            <option value ="ACTIVE"   <?php echo @($value['form_status']=="ACTIVE")?'selected':''; ?>> Active</option>
                                                            <option value ="INACTIVE" <?php echo @($value['form_status']=="INACTIVE")?'selected':''; ?>> Inactive</option>
                                                        </select>

                                                    <?php } ?>
                                                </td>
                                                <td class="<?php echo $setting_for; ?>">
                                                    <?php if($value['input'] == "YES"){ ?>
                                                         <input class="required_input" type="checkbox" <?php echo @($value['required']=="YES")?'checked':''; ?> >
                                                    <?php } ?>
                                                </td>
                                                <td class="<?php echo $setting_for; ?>">
                                                    <?php if($value['input'] =="YES"){ ?>
                                                        <input type="number" required="required" class="form_field_order" value="<?php echo @$value['form_field_order']; ?>" >
                                                    <?php } ?>
                                                </td>
                                                <td>
                                                    <select class="status form-control">
                                                        <option value ="ACTIVE"   <?php echo @($value['status']=="ACTIVE")?'selected':''; ?>> Active</option>
                                                        <option value ="INACTIVE" <?php echo @($value['status']=="INACTIVE")?'selected':''; ?>> Inactive</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="number" required="required" class="order_number" value="<?php echo @$value['order']; ?>" >

                                                    <?php if(@$value['custom_field'] =="YES"){ ?>
                                                            <a title="Remove this field" href="javascript:void(0)" class="btn btn-xs btn-danger remove_field"><i class="fa fa-trash"></i></a>
                                                    <?php } ?>


                                                </td>

                                            </tr>
                                            <?php $count++; } ?>
                                        </tbody>
                                    </table>
                                </div>
                           <div class="btn_box">
                               <button type="button" class="btn btn-info add_more_field">Add More Fields</button>
                               <button type="submit" class="btn btn-success save_fields">Save Fields</button>
                           </div>
                            <div class="clear"></div>

                            </form>
                        </div>

                    </div>






                </div>
                <!-- box 1 -->


            </div>
            <!--box 2 -->


        </div>
    </div>
</div>

<style>
    .remove_field{
        float: right;
        padding: 4px 5px;
        border-radius: 0px;
        position: relative;
        margin-top: -24px;
        font-size: 13px;
    }
    .form-control {
        width: 100%;
        height: 27px;
        padding: 0px 2px;
    }
    .field_value{
        width: 100%;
    }
    .btn_box{
        width: 100%;
        float: left;
        text-align: right;
    }
    .order_number, .form_field_order{
        width: 100%;
        padding: 0px 4px;
    }
    .change_form_field, .change_prescription_field{
               float: right;
        margin-top: 0px !important;
    }

    .required_input, .change_form_field, .change_prescription_field{
               width: 20px;
               height: 20px;

    }



    table.dataTable thead th, table.dataTable thead td{
        padding: 2px 4px !important;
    }
</style>
<script>
    $(document).ready(function () {

            $('#example').DataTable({
                "bPaginate": false,
                "bLengthChange": false,
                "bFilter": true,
                "bInfo": false,
                "bAutoWidth": false,
                "ordering": false
            });

        });
</script>

<script>
    $(document).ready(function(){


        function checkDuplicates(class_name) {
            var $elems = $('.'+class_name);
            var values = [];
            var isDuplicated = 0;
            $elems.each(function () {
                var check_class = (class_name=="form_field_order")?'.form_status':'.status';

                if($(this).closest('tr').find(check_class).val() =="ACTIVE"){
                    if(!this.value) return true;
                    if(values.indexOf(this.value) !== -1) {
                        isDuplicated = this.value;
                        return false;
                    }
                    values.push(this.value);
                }

            });

            return isDuplicated;

        }


        $(document).on('submit','form',function(e){
             e.preventDefault();
             var field = $(this).attr('field');
             var status = $(this).attr('status');
             var thisButton = $(".save_fields");
             var save_array = new Object();

            var prescription_order_duplicate = checkDuplicates('order_number');
            var form_order_duplicate = checkDuplicates('form_field_order');
            if (prescription_order_duplicate > 0 ) {
                alert(prescription_order_duplicate+' is duplicate number into prescription field order number column');
            }else if (form_order_duplicate > 0 ) {
                alert(form_order_duplicate+' is duplicate number into booking form field order number column');
            }else{
                $("#example tbody tr").each(function (index, value) {

                    if($(this).hasClass("display_block")){
                        var column = $(this).attr("data-column");
                        var custom_field = $(this).attr("data-custom");
                        var input = $(this).attr("data-input");

                        var label = $(this).find('.field_value').val();
                        var form_field_order = ($(this).find('.form_field_order').val())?$(this).find('.form_field_order').val():'0';

                        var form_status = ($(this).find('.form_status').val())?$(this).find('.form_status').val():'INACTIVE';

                        custom_field = (custom_field)?custom_field:'NO';
                        label = (label)?label:$(this).attr("data-label");

                        var required = ($(this).find('.required_input').is(":checked") && form_status =="ACTIVE")?"YES":'NO';


                        var tmp = {'required':required, 'input':input,'status':$(this).find('.status').val(),'order':$(this).find('.order_number').val(),'column':column,'label':label,'custom_field':custom_field,'form_status':form_status,'form_field_order':form_field_order};
                        save_array[column] = tmp;
                    }


                });


                var setting_for = "<?php echo $setting_for; ?>";
                $.ajax({
                    url: baseurl+'/app_admin/update_prescription_setting_field_status',
                    data:{save_fields:JSON.stringify(save_array),setting_for:setting_for},
                    type:'POST',
                    beforeSend:function(){
                        $(thisButton).button('loading').html('Saving...');
                    },
                    success: function(result){
                        window.location.reload();
                    }
                });
            }




     });




        $(document).on('change','.change_form_field',function(){
            if($(this).is(":checked")){
                $(".form_status").val('ACTIVE');
            }else{
                $(".form_status").val('INACTIVE');
            }

        });

        $(document).on('change','.change_prescription_field',function(){
            if($(this).is(":checked")){
                $(".status").val('ACTIVE');
            }else{
                $(".status").val('INACTIVE');
            }
        });

        function hideAddMoreButton(){
            var total =  $(".display_block").find("[custom-field='YES']").length;
            if(total < 6 ){
                $(".add_more_field").show();
            }else{
                $(".add_more_field").hide();
            }
        }

        $(document).on('click','.remove_field',function(){
            $(this).closest('tr').addClass('display_none').removeClass('display_block')
            $(this).closest('tr').find('.field_value, .order_number').val('');
            $(this).closest('tr').find('.field_value, .order_number').attr("novalidate","novalidate");
            $(this).closest('tr').find('.field_value, .order_number').attr("type","hidden");
            hideAddMoreButton();

        });

        $(document).on('click','.add_more_field',function(){
            var obj = $(".display_none:first");
            $(obj).removeClass('display_none').addClass('display_block');
            $(obj).closest('tr').find('.field_value, .order_number').val('');
            $(obj).closest('tr').find('.field_value, .order_number').removeAttr('novalidate');
            $(obj).closest('tr').find('.field_value, .order_number').attr("type","text");
            $(obj).closest('tr').find('.status').val('ACTIVE');
            $(obj).closest('tr').find('.status, .form_status').val('ACTIVE');
            var max=1;
            $(".order_number").each(function () {
                var current = parseInt($(this).val());
                max = (current > max)?current:max;
            });
            $(obj).closest('tr').find('.order_number').val(parseInt(max)+1);
            hideAddMoreButton();

        });

        hideAddMoreButton();

    });
</script>





