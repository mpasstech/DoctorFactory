<div class="combo_box" style="width: 65%;">
    <ul class="top_bar">
        <li style="padding: 6px 7px; text-align: center !important; width:32%;background: #069505 !important;" id="go_to_home">
            <button type="button" class="home_button" title="Go To Home"><i class="fa fa-home" style="font-size: 19px;"></i> Go To Home</button>
        </li>
        <li style="width:32%;">
            <label>Name :</label>
            <span id="pat_name_lbl">Ajay Sharma</span>
        </li>

        <li style="width:16%;">
            <label>Gender :</label>
            <span id="pat_gender_lbl">MALE</span>
        </li>

        <li style="width:20%;">
            <label>Age :</label>
            <span id="pat_age_lbl">15 Year</span>
        </li>
    </ul>
    <?php

    $color_icon[MASTER_SYMPTOMS_CATEGORY_ID] = '#495FE4';
    $color_icon[MASTER_DIAGNOSIS_CATEGORY_ID] = '#A989B7';
    $color_icon[MEDICINE_MASTER_CATEGORY_ID] = '#C9273E';
    $color_icon[MASTER_INVESTIGATION_CATEGORY_ID] = '#51BAF3';
    $color_icon[CLINIC_FINDING_MASTER_CATEGORY_ID] = '#4AA027';
    $color_icon[INTERNAL_NOTES_MASTER_CATEGORY_ID] = '#FF4F12';
    $color_icon[ADVICE_MASTER_CATEGORY_ID] = '#FF0000';
    $color_icon[MASTER_FLAGS] = '#BADA8E';
    $color_icon[MASTER_ALLERGY] = '#1E7BBF';

    ?>
    <div class="pre_category_box" style="width: 32%;">
        <ul>
            <?php if(!empty($data)){foreach($data['list'] as $key =>$value){  if($value['id'] != $vaccination_master_category_id){ ?>
                <li> <div data-cn="<?php echo $value['name']; ?>" class="category_li<?php ($value['id']==$category_id)?' active':''; ?>" data-ci="<?php echo base64_encode($value['id']); ?>"><img src="<?php echo $this->AppAdmin->beforeLodeImage(); ?>" data-src="<?php echo $value['icon_path']; ?>"> <?php echo $value['name']; ?></div>    <?php if(array_key_exists($value['id'],$color_icon)){ $color = $color_icon[$value['id']]; ?><button type="button" data-cn="<?php echo $value['name']; ?>" data-ci="<?php echo base64_encode($value['id']); ?>" class="load_step_btn" title="<?php echo "Add More ".$value['name']; ?>" style="color:<?=$color; ?>;" ><i class="fa fa-plus"></i></button><?php } ?> </li>
            <?php }}} ?>

        </ul>
    </div>

    <div class="pre_template_box" style="width: 68%; display: block; height: 100%;">
        <?php echo $template_html; ?>
    </div>


    <div class="vital_box">
        <?php if(!empty($vitals)){ ?>
            <form>
                <table cellpadding="0" cellspacing="5" style="width: 100%; height: 100%;">
                    <?php foreach ($vitals as $key =>$vital){ $image_name = strtolower(str_replace(array(" ","."),"",$vital['name'])).".png" ?>
                        <tr style="" master-vital="<?php echo $vital['vital_id']; ?>" >
                            <td width="10%;">
                                <img style="width: 30px;height: 30px;"  src="<?php echo $this->AppAdmin->beforeLodeImage(); ?>" data-src="<?php echo Router::url("/web_prescription/$image_name",true); ?>" class="icon_box" />
                            </td>
                            <td>
                                <label style="width: 100%; margin-bottom: 0px; text-align: left;"><?php echo $vital['name']; ?></label>
                            </td>
                            <td>
                                <input data-mvi="<?php echo $vital['vital_id']; ?>" data-n="<?php echo $vital['name']; ?>" data-u="<?php echo $vital['unit']; ?>" class="vital_input" type="text" id="<?php echo $vital['name']; ?>" maxlength="<?php echo ($vital['vital_id']!=$master_vital_other_notes)?"6":"600"; ?>" >
                            </td>
                            <td><?php echo $vital['unit']; ?></td>
                        </tr>
                    <?php } ?>
                    <tr style="padding: 10px 0px;">
                        <td style="padding: 0px !important; text-align: center;" colspan="4">
                            <button type="reset" id="vital_reset_btn" class=" btn btn-info"><i class="fa fa-refresh"></i> Reset</button>
                            <button type="button" id="save_vital_btn" data-cn="Vital Information" data-ci="<?php echo base64_encode($vitals_master_category_id); ?>" class=" btn btn-success"><i class="fa fa-plus"></i> Add Vitals</button>
                        </td>
                    </tr>
                </table>
            </form>

        <?php } ?>
    </div>

</div>
<?php
$login_data = $this->Session->read('Auth.User');
?>
<div class="pre_preview_box" data-pre="<?php echo $prescription_id; ?>" style="width: 35%;">

    <div class="preview_container" style="padding: 0px; width: 100%; height: 100%; float: left; background: #fff;">
        <ul class="action_btn_ul">
            <li><button title="Preview this prescription" type="button" id="preview_bookmark" class=""><i class="fa fa-eye"></i> Preview</button></li>
            <li><button title="Save as template this prescription" type="button" id="save_bookmark" class=""><i class="fa fa-bookmark"></i> Bookmark</button></li>
            <li><button title="Print and print this prescription" type="button" id="print_prescription_btn" class=""><i class="fa fa-print"></i> Save & Print</button></li>
            <li><button title="Save this prescription" type="button" id="save_prescription_btn" class=""><i class="fa fa-save"></i>  Save</button></li>
        </ul>
        <div id="prescription_body" style="width: 100%;padding: 0px;position: relative; overflow-y: auto;overflow-x: hidden;">
            <img src="<?php echo Router::url('/web_prescription/rx.png',true);?>" style="width: 30px;height: 30px;margin: 2px 0px;" >

        </div>


    </div>
</div>
<div class="footer_bar" id="pre_window_footer_bar"><div style="width: 65%; display: block;float: left;"><div class="span_left" style="width: 21%; border-right: "></div> <div style="text-align: center; width: 69%; float: right;display: block; height: 100%;"> Help Desk Number : +917412991122 10:00 AM To 07:00 PM </div> </div> <div class="span_right" style="width: 35%;"><a href="https://www.mengage.in" target="_blank">Powered By MEngage</a></div></div>

<style>


    .vital_box{
        width: 68%;
        float: left;
        padding: 1px 0px;
        border: 1px solid #14b3ff;
        height: 100%;
    }

    .vital_box td{
        padding: 12px 0px;
    }

    #label_box li{
        float: left;
        list-style: none;
        font-weight: 600;
        padding: 5px 0px;
        margin-right: 8px;
        min-width: 15%;
    }


    .prescription_button_container{
        position: relative;
        float: right;
        width: 17%;
        margin-top: -146px !important;
    }
    .prescription_button_container button{
        font-size: 13px;
        padding: 2px;
        margin: 2px 0px;
        width: 100%;
    }
    .prescription_button_container button i{
        display: block;
        width: auto;
        align-self: center;
    }
    #prescription_body td, #prescription_body th{
        padding: 0 6px;
        text-align: left;
        max-width: 200px;
        font-size: 10px;

    }
    .vital_input{
        width: 98%;
        margin: 2px;
    }
    .vital_box th{
        text-align: center;
        font-size: 18px;
        background: #14b3ff;
        color: #fff;
        padding: 5px 0px;
    }

    .vital_box td{
        font-size: 16px;
        font-weight: 500;
        text-align: center;
    }
    #save_vital_btn{
        margin: 10px 0px;
    }
    .cat_box h4{
        background: #d5d5d5;
        padding: 3px 2px;
        font-size: 16px;
    }

    .pre_category_box .active{
        background: #d8c8c8 !important;
    }
</style>
<script>
    $(function () {





        var internal_notes_master_category_id = "<?php echo $internal_notes_master_category_id; ?>";
        var prescription_template_master_category_id = "<?php echo $prescription_template_master_category_id; ?>";
        var prescription_setting_master_category_id = "<?php echo $prescription_setting_master_category_id; ?>";
        var vitals_master_category_id = "<?php echo $vitals_master_category_id; ?>";

        function myFunction() {
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById("myInputSearch");
            filter = input.value.toUpperCase();
            table = document.getElementById("myTemplateTable");
            tr = table.getElementsByTagName("tr");
            for (i = 0; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td")[1];
                if (td) {
                    txtValue = td.textContent || td.innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
        }


        $(document).off('input','#myInputSearch');
        $(document).on('input','#myInputSearch',function(){
            myFunction();
        });


        $(document).off('click','#go_to_home');
        $(document).on('click','#go_to_home',function(){
            $("#main_window").show();
            $("#prescription_window").hide();
        });

        $(document).off('click','.load_step_btn');
        $(document).on('click','.load_step_btn',function(){
            var $btn = $(this);
            var cat_id = $(this).attr('data-ci');
            var cat_name = $(this).attr('data-cn');
            $.ajax({
                url: "<?php echo Router::url('/prescription/step_modal',true);?>",
                type:'POST',
                data:{ci:cat_id,cn:cat_name},
                beforeSend:function(){
                    $btn.button({loadingText: '<i class="fa fa-circle-o-notch fa-spin"></i>'}).button('loading');
                },
                success: function(result){
                    $btn.button('reset');
                    var html = $(result).filter('#stepModal');
                    $(html).modal('show');
                },error:function () {
                    $btn.button('reset');
                }
            });
        });


        var currentRequest=null;
        $(document).off('click','.category_li');
        $(document).on('click','.category_li',function(){

            if($(this).closest('li').hasClass("active")){
                $(".category_li").closest('li').removeClass('active');
                $(this).closest('li').addClass('active');
                $(this).closest('li').find(".load_step_btn").trigger("click");
            }else{
                $(".category_li").closest('li').removeClass('active');
                $(this).closest('li').addClass('active');

                var patient_data = $("#patient_detail_object").data('key');
                patient_data = JSON.parse(patient_data);
                var patient_id = patient_data.general_info.patient_id;
                var patient_type = patient_data.general_info.patient_type;
                var $btn = $(this);
                var category_id = $(this).attr('data-ci');
                var category_name = $(this).attr('data-cn');

                if(atob(category_id) == vitals_master_category_id){
                    $(".vital_box").show();
                    $(".pre_template_box").hide();
                }else if(atob(category_id) == prescription_setting_master_category_id){
                    var url = "<?php echo Router::url('/app_admin/setting_print_prescription/web',true);?>";
                    window.open(url, '_blank');
                }else{
                    currentRequest = $.ajax({
                        url: "<?php echo Router::url('/prescription/templates_list',true);?>",
                        type:'POST',
                        cache:true,
                        data:{ci:category_id,pi:btoa(patient_id),pt:patient_type,cn:category_name},
                        beforeSend:function(){
                            if(currentRequest != null) {
                                currentRequest.abort();
                            }
                            //$btn.button({loadingText: 'Wait...'}).button('loading');
                        },
                        success: function(result){
                            $btn.button('reset');
                            $(".vital_box").hide();
                            $(".pre_template_box").html(result).show();

                        },error:function () {
                            $btn.button('reset');
                        }
                    });
                }

            }

        });









        function resizePrescriptionWindow(){
            var height = parseInt($(window).height());



            var footer  = parseInt($("#pre_window_footer_bar").outerHeight());
            var top_bar  = parseInt($(".top_bar").outerHeight());
            $(".pre_preview_box, .combo_box").height(height-footer);
            var pre_template_box  = parseInt($(".pre_template_box").outerHeight());

            var myInputSearch  = parseInt($("#myInputSearch").outerHeight());


            $('#prescription_window').height(parseInt(height));


            $('#myTemplateTableContainer').height(height - myInputSearch -top_bar);


            $(".pre_preview_box, .combo_box").height(height-footer);

            var pre_preview_box  = parseInt($(".pre_preview_box").outerHeight());
            var action_btn_ul  = parseInt($(".action_btn_ul").outerHeight());

            $('#prescription_body').height(pre_preview_box -action_btn_ul);

            $("#clone_html_div").show();

        }
        setTimeout(function(){
            resizePrescriptionWindow();
        },50);

        $(window).off('resize');
        $(window).on('resize', function(){
            resizePrescriptionWindow();
        });


        $(document).off('click','.template_tr');
        $(document).on('click','.template_tr',function(){
            var category_id = $(this).attr('data-ci');
            var category_name = $(this).attr('data-cn');
            var template = JSON.parse(atob($(this).attr('data-tmp')));
            var template_list = template.template_array;
            if(category_id == prescription_template_master_category_id){
                $("#prescription_body").html('');
                $.each(template_list,function(index, value){
                    category_id = value[Object.keys(value)[0]].category_id;
                    category_name = value[Object.keys(value)[0]].category_name;
                    var temp_array = [];
                    temp_array.push(value);
                    create_prescription_data(temp_array,category_id,category_name,"TEMPLATE");
                });
            }else{
                create_prescription_data(template_list,category_id,category_name,"TEMPLATE");
            }


        });

        $(document).off('click','.delete_template');
        $(document).on('click','.delete_template',function(e){
            e.stopPropagation();
            var ti = $(this).attr('data-ti');
            var ci = $(this).attr('data-ci');
            var patient_data = $("#patient_detail_object").data('key');
            patient_data = JSON.parse(patient_data);
            var patient_id = patient_data.general_info.patient_id;
            var patient_type = patient_data.general_info.patient_type;

            var dialog = $.confirm({
                title: "Delete Template",
                content: 'Are you sure you want to delete this template?',
                type: 'red',
                buttons: {
                    ok: {
                        text: "Yes",
                        keys: ['enter'],
                        btnClass: 'btn-primary delete_tmp_btn',
                        name:"Yes",
                        action: function(e){
                            var $btn = $(".delete_tmp_btn");
                            $.ajax({
                                type: 'POST',
                                url: "<?php echo Router::url('/prescription/manage_template',true); ?>",
                                data: {at:'DELETE',ti:ti,ci:(ci),pi:btoa(patient_id),pt:patient_type},
                                beforeSend: function () {
                                    $btn.button({loadingText: 'Deleting...'}).button('loading');
                                },
                                success: function (data) {
                                    $btn.button('reset');
                                    data = JSON.parse(data);
                                    if(data.status==1){
                                        dialog.close();
                                        $(".pre_template_box").html(data.template_list);
                                        setTimeout(function(){
                                            resizePrescriptionWindow();
                                        },10);

                                    }else{
                                        flash("Delete Template",data.message, "warning",'center');
                                    }
                                },
                                error: function (data) {
                                    $btn.button('reset');
                                    flash("Error","Sorry something went wrong on server.", "danger",'center');
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








        $(document).off('click','.rename_template');
        $(document).on('click','.rename_template',function(e){
            e.stopPropagation();
            var ti = $(this).attr('data-ti');
            var ci = $(this).attr('data-ci');
            var last_name = $(this).closest('tr').find('.template_name_td').text();
            var string = "<input type='text' id='template_name_box' class='form-control' value='"+last_name+"'>";
            var title  ="Rename Template";
            var label  ="Enter Template Name";
            if(atob(ci) == internal_notes_master_category_id){
                title  ="Edit Notes";
                label  ="Enter Notes";
                string = "<textarea  rows='3' style='height: auto;' id='template_name_box' class='form-control' >"+last_name+"</textarea>";
            }
            var dialog = $.confirm({
                title: title,
                content: '' +
                '<div class="form-group">' +
                '<label>'+label+'</label><br>' +
                string +
                '</div>',
                buttons: {
                    save: {
                        text: 'Save',
                        keys: ['enter'],
                        btnClass: 'btn-blue rename_template_btn',
                        action: function () {
                            var $btn = $(".rename_template_btn");
                            var template_name = this.$content.find('#template_name_box').val();

                            var patient_data = $("#patient_detail_object").data('key');
                            patient_data = JSON.parse(patient_data);
                            var patient_id = patient_data.general_info.patient_id;
                            var patient_type = patient_data.general_info.patient_type;

                            $.ajax({
                                type: 'POST',
                                url: "<?php echo Router::url('/prescription/manage_template',true); ?>",
                                data: {at:'UPDATE',ti:ti,ci:(ci),tn:template_name,pi:btoa(patient_id),pt:patient_type},
                                beforeSend: function () {
                                    $btn.button({loadingText: 'Saving...'}).button('loading');
                                },
                                success: function (data) {
                                    $btn.button('reset');
                                    data = JSON.parse(data);
                                    if(data.status==1){
                                        dialog.close();
                                        $(".pre_template_box").html(data.template_list);
                                        resizePrescriptionWindow();
                                    }else{
                                        flash("Rename Template",data.message, "warning",'center');
                                    }
                                },
                                error: function (data) {
                                    $btn.button('reset');
                                    flash("Error","Sorry something went wrong on server.", "danger",'center');
                                }
                            });
                            return false;
                        }
                    },
                    cancel: function () {
                        //close
                    }
                },
                onContentReady: function () {

                }
            });



        });



        $(document).off('click','#save_vital_btn');
        $(document).on('click','#save_vital_btn',function(e){

            var string_array = [];
            var category_id = atob($(this).attr('data-ci'));
            var save_data_array = [];
            var category_name = "Vital Information";
            $(".vital_box .vital_input").each(function (index, value) {
                var name = $(this).attr('data-n');
                var unit = $(this).attr('data-u');
                var id = $(this).attr('data-mvi');
                var value = $(this).val();
                if(value!=''){
                    var unit = (unit!='')?" ("+unit+")":'';
                    string_array.push(id+"##@@##"+name+":"+value+""+unit);
                }
                save_data_array.push({'vital_id':id,'value':value});
            });

            if(string_array.length > 0){


                var other_notes_tr = "";
                var string_td = "";
                var string_th = "";
                var total_td = 0;
                $(string_array).each(function (index, string) {
                    var name_value_string_array = string.split(":");
                    var name_id_string_array = name_value_string_array[0].split("##@@##");
                    if (name_id_string_array[0] == "<?php echo $master_vital_other_notes; ?>" ){
                        other_notes_tr = name_value_string_array[1];
                    }else{
                        total_td++;
                        string_th += "<th>" + name_id_string_array[1] + "</th>";
                        string_td += "<td>" + name_value_string_array[1] + "</td>";
                    }
                });

                var container_length = $("#prescription_body .category_box_"+category_id).length;
                var container_object = $("#prescription_body .category_box_"+category_id);
                var string ="";
                var id = "data_raw_"+category_id+"_0";

                if(other_notes_tr!=''){
                    var notes = other_notes_tr;
                    other_notes_tr = "<tr class='data_raw' id='"+id+"' class='category_"+category_id+"' onclick='change_color(this,true);'><th colspan='"+total_td+"'>Other Notes</th></tr>";
                    other_notes_tr += "<tr class='data_raw' id='"+id+"' class='category_"+category_id+"' onclick='change_color(this,true);'><td colspan='"+total_td+"'>"+notes+"</td></tr>";
                }
                string += "<div  data-i='"+category_id+"' class='cat_box category_box_"+category_id+"'>";
                string += "<h4 class='category_title_"+category_id+"'>"+category_name+"</h4>";
                string += "<table style='width: 100%;' class='category_"+category_id+"' data-cn='"+category_name+"' data-ci='"+category_id+"'>";
                string += "<tr class='data_raw' id='"+id+"' class='category_"+category_id+"' onclick='change_color(this,true);'>"+string_th+"</tr>";
                string += "<tr class='data_raw' id='"+id+"' class='category_"+category_id+"' onclick='change_color(this,true);'>"+string_td+"</tr>";
                string += other_notes_tr+"</table>";

                if(container_length == 1){
                    $(container_object).html(string);
                }else{
                    $("#prescription_body .category_box_"+category_id).remove();
                    $("#prescription_body").append(string);
                }
                $("#"+id).data('key',JSON.stringify(save_data_array));
                $("#"+id).attr('data-key',$("#"+id).data('key'));
                var row_object = '.cat_box .category_'+category_id+' tr';
                $(".category_"+category_id).confirmation({
                    rootSelector: '[data-toggle=confirmation]',
                    title:'Delete this tag?',
                    popout:true,
                    singleton:true,
                    container: 'body',
                    onConfirm:function(){
                        $(".category_box_"+category_id).remove();
                        $(this).confirmation('hide');
                    },
                    onCancel:function () {
                        $(this).confirmation('hide');
                        change_color(this,false);
                    }
                });
                $("#vital_reset_btn").trigger('click');
            }else{
                flash("Vitals","Please enter vital values", "warning",'center');
            }
        });


        $(document).off('click','#add_follow_up_btn');
        $(document).on('click','#add_follow_up_btn',function(e){

            var string_array = [];
            var category_id = atob($(this).attr('data-ci'));
            var save_data_array =[];

            var category_name = $(this).attr('data-cn');
            var date = $(".follow_up_date_picker").val();

            var message_obj = $("#myTemplateTableContainer #template_drp");
            var message_id = $("#myTemplateTableContainer #template_drp").val();
            var message = $("#myTemplateTableContainer #template_drp option:selected").text();
            if(date && message_id){
                string_array.push(date);
                save_data_array.push({'date':date,'message':message});

                var container_length = $("#prescription_body .category_box_"+category_id).length;
                var container_object = $("#prescription_body .category_box_"+category_id);
                var string ="";
                var id = "data_raw_"+category_id+"_0";
                string += "<div data-i='"+category_id+"' class='cat_box category_box_"+category_id+"'>";
                string += "<h4 class='category_title_"+category_id+"'>"+category_name+"</h4>";
                string += "<table style='width: 100%;' class='category_"+category_id+"' data-cn='"+category_name+"' data-ci='"+category_id+"'>";
                string += "<tr class='data_raw' id='"+id+"' data-mi='"+message_id+"' data-d='"+date+"' onclick='change_color(this,true);'><td>"+string_array.join(", ")+"</td></tr></table>";
                if(container_length == 1){
                    $(container_object).html(string);
                }else{
                    $("#prescription_body .category_box_"+category_id).remove();
                    $("#prescription_body").append(string);
                }
                $("#"+id).data('key',JSON.stringify(save_data_array));
                $("#"+id).attr('data-key',$("#"+id).data('key'));
                var row_object = '.cat_box .category_'+category_id+' tr';
                $("#"+id).confirmation({
                    rootSelector: '[data-toggle=confirmation]',
                    title:'Delete this tag?',
                    popout:true,
                    singleton:true,
                    container: 'body',
                    onConfirm:function(){
                        $(".category_box_"+category_id).remove();
                        $(this).confirmation('hide');
                    },
                    onCancel:function () {
                        $(this).confirmation('hide');
                        change_color(this,false);
                    }
                });
                $(message_obj).val('');
                $(".follow_up_date_picker").val('');


            }else{
                var message = "";
                if(date==""){
                    message = "Please enter followup date";
                }else{
                    message = "Please select SMS template";
                }
                flash("Reminder",message, "warning",'center');
            }
        });








        var patient_data = ($("#patient_detail_object").data('key'))?$("#patient_detail_object").data('key'):$("#patient_detail_object").data('key').key;

        patient_data = JSON.parse(patient_data);

        $("#pat_name_lbl").html(patient_data.general_info.name);
        $("#pat_age_lbl").html(patient_data.general_info.age);
        $("#pat_gender_lbl").html(patient_data.general_info.gender);


		var final_template_array=[];
        function create_template_array_default(key,category_id,category_name,notes){
                if(notes !=''){
                    final_template_array=[];
                    var data = $('body').data(key);
                    var input_tag_array = notes.split('###');
                    if(data){
                        data = JSON.parse(data);

                        var template_array = [];
                        $.each(data,function (index ,value) {
                            var tag_string = input_tag_array[index];
                            if(tag_string){
                                var preTag = tag_string.split(',');
                                var template_obj= {};
                                var tag_array=[];
                                var step_name = step_id = "";
                                template_obj['s_id']=value.step_id;
                                template_obj['step_id']= value.step_id;
                                template_obj['step_title']=step_name =value.step_title;
                                template_obj['category_name']=category_name;
                                template_obj['category_id']=category_id;
                                var tag_name_array=[];
                                $.each(value.tag_list,function (ind ,tag) {
                                    var enterTag= (preTag.includes(tag.tag_name))?true:false;
                                    var tag_obj= {};
                                    if(enterTag){
                                        tag_obj['tag_title']=tag.tag_name;
                                        tag_obj['template_id']=0;
                                        tag_obj['tag_id']=tag.tag_id;
                                        tag_array.push(tag_obj);
                                    }
                                    tag_name_array.push(tag.tag_name);
                                });
                                /* custom tag added by user */
                                $.each(preTag,function (ind ,tag) {
                                    var enterTag= (!tag_name_array.includes(tag))?true:false;
                                    var tag_obj= {};
                                    if(enterTag){
                                        tag_obj['tag_title']=tag;
                                        tag_obj['template_id']=0;
                                        tag_obj['tag_id']=0;
                                        tag_array.push(tag_obj);
                                    }

                                });
                                if(tag_array.length > 0){
                                    template_obj['selected_tag']=tag_array;
                                    template_array.push(template_obj);
                                }
                            }
                        });
                        if(template_array.length > 0){
                            final_template_array.push(template_array);
                            $("#total_template").html(final_template_array.length);
                        }
                    }
                }

                return final_template_array;
        }
        function loadDefaultValue(key,category_id,category_name,notes){
            if(notes!=''){
                var return_data = create_template_array_default(key,category_id,category_name,notes);
                if(return_data !==false){
                    var temp_obj = getTemplateArray();
                    if(!temp_obj){
                        temp_obj = new Object();
                    }
                    var temp_inner =new Object();
                    temp_inner['category_id'] = category_id;
                    temp_inner['category_name'] = category_name;
                    temp_inner['data'] = final_template_array;
                    temp_obj["category_"+category_id] = temp_inner;
                    setTemplateArray(temp_obj);
                    $(".main_step_box .tag_container li").removeClass('active');
                    create_prescription_data(final_template_array,category_id,category_name,"STEP");
                    $("#myTemplateTable .template_tr").removeClass("selected");

                }
            }
        }

        var master_category_id = "<?php echo MASTER_SYMPTOMS_CATEGORY_ID; ?>";
        var master_flag_id = "<?php echo MASTER_FLAGS; ?>";
        var master_allergy_id = "<?php echo MASTER_ALLERGY; ?>";

        if(<?php echo $symptoms_steps_list; ?>){
            $('body').data('symptoms','<?php echo $symptoms_steps_list; ?>');
            var notes = "<?php echo @$prescription_setting['notes']; ?>";
            loadDefaultValue('symptoms',master_category_id,'Symptomps',notes);
        }

        if(<?php echo $flag_list; ?>){
            $('body').data('flags','<?php echo $flag_list; ?>');
            var notes = "<?php echo @$prescription_setting['flag']; ?>";
            loadDefaultValue('flags',master_flag_id,'Flags',notes);
        }

        if(<?php echo $allergy_list; ?>){
            $('body').data('allergy','<?php echo $allergy_list; ?>');
            var notes = "<?php echo @$prescription_setting['allergy']; ?>";
            loadDefaultValue('allergy',master_allergy_id,'Allergy',notes);
        }
        




    })
</script>


<?php
$left = $tb_left = '0%';
$top = $tb_top= '0%';
$width = $tb_width= '60%';
$height = '8%';
$fontSize = $tb_text_font_size= $tb_label_font_size= $tb_heading_font_size= '13px';
$header_rotate = "0";
$barcode = 'NO';
$background_img = "";
$header_box_border = $tag_box_border = "2px solid";
$default_fields = array(
    'uhid'=>'20181215',
    'patient_name'=>'John Doe',
    'parents_name'=>'Madelyn Duke',
    'parents_mobile'=>'9999999999',
    'age'=>'28 Years',
    'email'=>'testmail@gmail.com',
    'gender'=>'Male',
    'address'=>'222, test nagar jaipur',
    'queue_number'=>'51',
    'appointment_datetime'=>date('d-m-Y'),
    'receipt_datetime'=>date('d-m-Y H:i'),
    'slot_time'=>date('h:i A'),
    'dob'=>date('d-m-Y'),
    'weight'=>'12',
    'blood_group'=>'O+',
    'mobile'=>'+919999999999',
    'amount'=>'<i class="fa fa-inr" aria-hidden="true"></i> 500',
    'patient_height'=>'5',
    'bp_systolic'=>'2',
    'bp_diastolic'=>'5',
    'marital_status'=>'Married',
    'bmi'=>'9',
    'bmi_status'=>'Good',
    'notes'=>'Remark',
    'reason_of_appointment'=>'Fever',
    'third_party_uhid'=>'D3567',
    'referred_by'=>'Refer by',
    'referred_by_mobile'=>'9999999999',
    'doctor_name'=>'Dr. Mahendra Saini',
    'temperature'=>'12',
    'o_saturation'=>'4',
    'city_name'=>'Jaipur',
    'country_id'=>'India',
    'city_id'=>'Jaipur',
    'state_id'=>'Rajasthan',
    'service_validity_time'=>'2 Day(s)',
    'doctor name'=>'Dr Joey',
    'token time'=>'12 Sep 2019 03:46 PM',
    'head_circumference'=>'98 CM',
    'receipt_datetime'=>'17 Mar 2019 03:15 PM',
    'field1'=>'field1',
    'field2'=>'field2',
    'field3'=>'field3',
    'field4'=>'field4',
    'field5'=>'field5',
    'field6'=>'field6',

);

if (!empty($prescription_setting)) {
    $data = $prescription_setting;
    $left = $data['left'];
    $top = $data['top'];
    $width = $data['width'];
    $height = $data['height'];
    $fontSize = $data['font_size'];
    $barcode = $data['barcode'];
    $tb_left = $data['tb_left'];
    $tb_top = $data['tb_top'];
    $tb_width = $data['tb_width'];
    $header_rotate = $data['header_rotate'];

    $tb_text_font_size = !empty($data['tb_text_font_size'])?$data['tb_text_font_size']:$tb_text_font_size;
    $tb_label_font_size = !empty($data['tb_label_font_size'])?$data['tb_label_font_size']:$tb_label_font_size;
    $tb_heading_font_size = !empty($data['tb_heading_font_size'])?$data['tb_heading_font_size']:$tb_heading_font_size;
    $header_box_border = !empty($data['header_box_border'])?$data['header_box_border']:$header_box_border;
    $tag_box_border = !empty($data['tag_box_border'])?$data['tag_box_border']:$tag_box_border;
    $prescription = $data['prescription'];
    $prescription_base64 = $data['prescription_base64'];

    if(!empty($prescription_base64)){
        $background_img = "background-image:url('".$prescription_base64."');";
    }

    if(!empty($data['fields']))
    {
        $fields = json_decode($data['fields'],true);
        uasort($fields, function($item1, $item2){
            if ($item1['order'] == $item2['order']) return 0;
            return $item1['order'] < $item2['order'] ? -1 : 1;
        });
    }
}

if(empty($fields)){
    $fields = $default_fields;
}


?>
<style media="print">

    @page {
        size: A4;
        margin: 0;
    }
    @media print {
        html, body {
            width: 210mm;
            /*height: 297mm;*/
        }

        #tag_box .background_color {
            background-color: #0000001f !important;
            -webkit-print-color-adjust: exact;

        }
        #tag_box td, #tag_box th{
            text-align: left;
        }

        #label_box li{
            float: left;
            list-style: none;
            font-weight: 600;
            padding: 5px 3px;
            margin-right: 8px;
            min-width: 10%;
        }



    }
</style>

<style>
    thead {
        display: table-header-group !important;
    }

    tfoot { display: table-footer-group !important; }

</style>
<div style="display: none;" id="clone_html_div">

    <?php if(empty($prescription_setting['setting_id'])){ ?>
        <div id="printPrescriptionBox"  style="background-color:#fff;float: left; width:210mm;  background-size: cover !important;;display:block; padding: 4px;">
            <table cellspacing="0" cellpadding="2"  style="width: 100%;"  >
                <thead>
                <tr>
                    <th>
                        <?php if($data['barcode'] =='YES' && $data['barcode_on_prescription'] =="YES"){ ?>
                            <div><img id="barcode"/></div>
                        <?php } ?>
                        <table cellspacing="2" cellpadding="0" style=" width: 100%;padding: 0px; border-bottom: 3px solid #5681A4;">
                            <tr>
                                <td colspan="2"><label style="padding:10px 0px;text-align: center;width: 100%;display: block; color:#5681A4; font-weight: 600; font-size: 33px;"><?php echo !empty($prescription_setting['clinic_name'])?$prescription_setting['clinic_name']:$login_data['Thinapp']['name']; ?></label></td>
                            </tr>
                            <tr>
                                <td colspan="2" style="" >
                                    <label style=" display: block;font-weight: 500; font-size: 25px;"><?php echo $prescription_setting['doctor_name']; ?> <span style="font-size:17px; font-weight: 500;"><?php echo  $doctor_data['sub_title']; ?></span></label>
                                </td>
                            </tr>
                            <tr>
                                <td valign="top"  style="width:50%;">
                                    <span style="font-size:17px; font-weight: 500;"><?php echo $prescription_setting['clinic_address']; ?></span>
                                </td>
                                <td style="width:50%;text-align: right; ">
                                    <span style="display: block;font-weight: 500; font-size: 17px;"><?php echo $prescription_setting['category_name']; ?></span>
                                    <span style="display: block;font-weight: 500; font-size: 17px;"><?php echo $doctor_data['mobile']; ?></span>
                                </td>
                            </tr>
                        </table>
                        <?php if($data['field_layout_type'] == "ORDER_LIST"){ ?>
                            <div class="child" id="label_box"  style=" float:left; transform: rotate(<?php echo $header_rotate.'deg'; ?>); border:<?php echo $header_box_border; ?>; left: <?php echo $left; ?>; font-size: <?php echo $fontSize; ?>; top: <?php echo $top; ?>; width: <?php echo $width; ?>; height: auto;" >
                                <?php if(empty($fields)){ ?>

                                    <li><span>UHID: </span>
                                        <span><?php echo $data['uhid']; ?></span>
                                    </li>
                                    <li>
                                        <span>Name:  </span>
                                        <span><?php echo $data['patient_name']; ?> </span>
                                    </li>

                                    <li>
                                        <span><?php echo (!empty($data['relation_prefix']) && !empty($data['parents_name']))?$data['relation_prefix']:"Parents Name:"; ?></span>
                                        <span> <?php echo $data['parents_name']; ?></span>
                                    </li>

                                    <li>
                                        <span>Age: </span>
                                        <span><?php
                                            $ageStr = $this->AppAdmin->getAgeStringFromDob($data['age'],false,true);
                                            if(empty($ageStr)){
                                                $ageStr = $data['age'];
                                            } ?>
                                            <?php echo ( $ageStr); ?></span>
                                    </li>
                                    <li>
                                        <span>Gender: </span>
                                        <span><?php echo ucfirst(strtolower($data['gender'])); ?></span>
                                    </li>
                                    <li>
                                        <span>Address: </span>
                                        <span><?php echo $data['patient_address']; ?></span>
                                    </li>

                                    <li>
                                        <span>Token No: </span>
                                        <span><?php echo @$this->AppAdmin->create_queue_number($data); ?></span>
                                    </li>
                                    <li>
                                        <span>Date:</span>
                                        <span><?php echo date("d-m-Y");?></span>
                                    </li>
                                    <li>
                                        <span> Weight(KG): </span>
                                        <span> ____ </span>
                                    </li>

                                <?php }else{ ?>
                                    <?php $keys = 1; $size = 1; $totalSize = sizeof($fields); foreach ($fields AS $key => $field){
                                        $custom_condition = isset($field['custom_field'])?true:false;
                                        ?>
                                        <?php if($custom_condition===false){ ?>
                                            <?php if( $field['status'] == 'ACTIVE'){ ?>

                                                <?php if($key == 'uhid'){ ?>
                                                    <li>
                                      <span>  <span>UHID:</span>
                                        <span>   <?php echo $data['uhid']; ?></span>
                                                    </li>
                                                <?php }else if($key == 'name'){ ?>
                                                    <li>
                                                        <span>   Name:</span>
                                                        <span>    <?php echo $data['patient_name']; ?></span>
                                                    </li>
                                                <?php }else if($key == 'parents'){ ?>
                                                    <?php $relationPrefix = $data['relation_prefix']; ?>
                                                    <li>
                                                        <span>    <?php echo (!empty($data['relation_prefix']) && !empty($data['parents_name']))?$data['relation_prefix']:"Parents Name:"; ?></span>
                                                        <span>    <?php echo $data['parents_name']; ?></span>
                                                    </li>
                                                <?php }else if($key == 'age'){ ?>

                                                    <li>
                                                        <span>     Age: </span>
                                                        <?php
                                                        $ageStr = $this->AppAdmin->getAgeStringFromDob($data['age'],false,true);
                                                        if(empty($ageStr)){
                                                            $ageStr = $data['age'];
                                                        }
                                                        ?>
                                                        <span> <?php echo ( $ageStr); ?></span>
                                                    </li>
                                                <?php }else if($key == 'gender'){ ?>
                                                    <li>
                                                        <span> Gender:</span>
                                                        <span><?php echo ucfirst(strtolower($data['gender'])); ?></span>
                                                    </li>
                                                <?php }else if($key == 'address'){ ?>
                                                    <li>
                                                        <span>  Address:</span>
                                                        <span> <?php echo $data['patient_address']; ?></span>
                                                    </li>
                                                <?php }else if($key == 'token_no'){ ?>
                                                    <li>
                                                        <span>  Token No:</span>
                                                        <span> <?php echo @$this->AppAdmin->create_queue_number($data); ?></span>
                                                    </li>
                                                <?php }else if($key == 'date'){ ?>
                                                    <li>
                                                        <span> Date:</span>
                                                        <span> <?php echo !empty($data['appointment_datetime'])?date("d-m-Y",strtotime($data['appointment_datetime'])):date('d-m-Y');?></span>
                                                    </li>
                                                <?php }else if($key == 'weight'){ ?>
                                                    <li>
                                                        <span>  Weight(KG):</span>
                                                        <span>  <?php echo $data['weight']; ?></span>
                                                    </li>
                                                <?php }else if($key == 'mobile'){ ?>
                                                    <li>
                                                        <span>  Mobile:</span>
                                                        <span>  <?php echo $data['mobile']; ?></span>
                                                    </li>
                                                <?php }else if($key == 'OPD Fee'){ ?>
                                                    <li>
                                                        <span> OPD Fee:</span>
                                                        <span> <i class="fa fa-inr" aria-hidden="true"></i> <?php echo !empty($data['amount'])?$data['amount']:0; ?></span>
                                                    </li>
                                                <?php }else if($key == 'height'){ ?>
                                                    <li>
                                                        <span> Height(CM):</span>
                                                        <span> <?php echo $data['patient_height']; ?></span>
                                                    </li>
                                                <?php }else if($key == 'BP Systolic'){ ?>
                                                    <li>
                                                        <span> BP Systolic:</span>
                                                        <span> <?php echo $data['bp_systolic']; ?></span>
                                                    </li>
                                                <?php }else if($key == 'head circumference'){ ?>
                                                    <li>
                                                        <span> Head Circumference:</span>
                                                        <span> <?php echo $data['head_circumference']; ?></span>
                                                    </li>

                                                <?php }else if($key == 'BP Diastolic'){ ?>
                                                    <li>
                                                        <span> BP Diastolic:</span>
                                                        <span> <?php echo $data['bp_diasystolic']; ?></span>
                                                    </li>
                                                <?php }else if($key == 'BMI'){ ?>
                                                    <li>
                                                        <span> BMI:</span>
                                                        <span>
                                                <?php if( isset($data['weight']) && !empty($data['weight']) && isset($data['height']) && !empty($data['height']) && empty($data['bmi']) ){
                                                    echo round( ($data['weight'] / (($data['height']/100) * ($data['height']/100))),3 );
                                                }
                                                else
                                                {
                                                    echo $data['bmi'];
                                                } ?>

                                            </span>
                                                    </li>
                                                <?php }else if($key == 'BMI Status'){ ?>
                                                    <li>
                                                        <span> BMI Status:</span>
                                                        <span> <?php echo $data['bmi_status']; ?></span>
                                                    </li>
                                                <?php }else if($key == 'temperature'){ ?>
                                                    <li>
                                                        <span> Temperature:</span>
                                                        <span> <?php echo $data['temperature']; ?></span>
                                                    </li>
                                                <?php }else if($key == 'O.Saturation'){ ?>
                                                    <li>
                                                        <span>O.Saturation:</span>
                                                        <span><?php echo $data['o_saturation']; ?></span>
                                                    </li>
                                                <?php }else if($key == 'validity time'){ ?>
                                                    <li>
                                                        <span>     Validity Time:</span>
                                                        <span> <?php echo !empty($data['service_validity_time'])?$data['service_validity_time']:''; ?>Day(s)</span>
                                                    </li>
                                                <?php }else if($key == 'doctor name'){ ?>
                                                    <li>
                                                        <span>Doctor Name:</span>
                                                        <span> <?php echo $data['doctor_name'];?></span>
                                                    </li>
                                                <?php }else if($key == 'token time'){ ?>
                                                    <li>
                                                        <span>Token Time:</span>
                                                        <span><?php echo date('d M Y h:i A', strtotime($data['appointment_datetime']));?></span>
                                                    </li>
                                                <?php }else if($key == 'receipt datetime'){ ?>
                                                    <li>
                                                        <span>Receipt Time:</span>
                                                        <span><?php echo !empty($data['receipt_datetime'])?date('d M Y h:i A', strtotime($data['receipt_datetime'])):'';?></span>
                                                    </li>
                                                <?php }else{ ?>
                                                    <li>
                                                        <span><?php echo (isset($field['custom_field']) && !empty($field['custom_field']=="YES"))?$field['label']:$key; ?>:</span>
                                                        <span><?php echo (isset($field['custom_field']) && !empty($field['custom_field']=="YES"))?$data[$field['column']]:isset($data[$key])?$data[$key]:'___'; ?></span>
                                                    </li>
                                                <?php } ?>

                                            <?php } ?>

                                        <?php }else{ ?>
                                            <?php if( $field['status'] == 'ACTIVE'){ ?>

                                                <?php
                                                $label = $field['label'];
                                                if($field['column']=="bmi")
                                                {
                                                    $value = $this->AppAdmin->calculateBmi($data['patient_id'],$data['patient_type']);
                                                }else if($field['column']=="age"){
                                                    $value = $this->AppAdmin->getAgeStringFromDob($data[$field['column']],false,true);
                                                    if(empty($value)){
                                                        $value = $data[$field['column']];
                                                    }
                                                }else if($field['column']=="country_id"){
                                                    $value = "";
                                                    if(!empty($data[$field['column']])){
                                                        $name = $this->AppAdmin->get_localization_by_id($data[$field['column']],'COUNTRY');
                                                        if(!empty($name)){
                                                            $value = $name;
                                                        }
                                                    }

                                                }else if($field['column']=="state_id"){
                                                    $value = "";
                                                    if(!empty($data[$field['column']])){
                                                        $name = $this->AppAdmin->get_localization_by_id($data[$field['column']],'STATE');
                                                        if(!empty($name)){
                                                            $value = $name;
                                                        }
                                                    }

                                                }else if($field['column']=="city_id"){
                                                    $value = $data['city_name'];
                                                    if(!empty($data[$field['column']])){
                                                        $name = $this->AppAdmin->get_localization_by_id($data[$field['column']],'CITY');
                                                        if(!empty($name)){
                                                            $value = $name;
                                                        }
                                                    }

                                                }else if($field['column']=="parents_name"){
                                                    $label = (!empty($data['relation_prefix']) && !empty($data[$field['column']]))?$data['relation_prefix']:"Parents Name";
                                                    $value = @$data[$field['column']];
                                                }else if($field['column']=="gender" || $field['column']=="marital_status"){
                                                    $value = @ucfirst(strtolower($data[$field['column']]));
                                                }else{
                                                    $value = @$data[$field['column']];
                                                }
                                                ?>

                                                <?php if(($field['column']=="conceive_date" || $field['column']=="expected_date")){ ?>
                                                    <?php if(($data['gender']=="FEMALE")){ ?>
                                                        <li>
                                                            <span><?php echo ucfirst($label); ?>:</span>
                                                            <span ><?php echo ($value != "" && $value != '00-00-0000')?$value:''; ?></span>
                                                        </li>

                                                    <?php } ?>
                                                <?php }else{ ?>

                                                    <?php $value=  ($value == '00-00-0000' || empty($value))?'':$value; ?>

                                                    <li>
                                                        <span><?php echo ucfirst($label); ?>:</span>
                                                        <span ><?php echo $value; ?></span>
                                                    </li>

                                                <?php } ?>

                                            <?php } ?>
                                        <?php  } ?>
                                        <?php $size++; } ?>
                                <?php } ?>
                            </div>
                        <?php }else{ ?>
                            <?php if(empty($fields)){ ?>
                                <table class="child" id="label_box"  style="font-size: 15px; width: 100%;" >
                                    <tr>
                                        <th align="left" width="16.666%">UHID: </th>
                                        <th align="left" width="16.666%"><?php echo $data['uhid']; ?></th>
                                        <th align="left" width="16.666%">Name: </th>
                                        <th align="left" width="16.666%"><?php echo mb_strimwidth(ucfirst($data['patient_name']),0,40); ?></th>
                                        <th align="left" width="16.666%"><?php echo (!empty($data['relation_prefix']) && !empty($data['parents_name']))?$data['relation_prefix']:"Parents Name:"; ?>  </th>
                                        <th align="left" width="16.666%"><?php echo mb_strimwidth(ucfirst($data['parents_name']),0,40); ?></th>
                                    </tr>
                                    <tr>
                                        <th align="left" width="16.666%">Age: </th>
                                        <?php
                                        $ageStr = $this->AppAdmin->getAgeStringFromDob($data['age']);
                                        if(empty($ageStr)){
                                            $ageStr = $data['age'];
                                        } ?>
                                        <th align="left" width="16.666%"><?php echo ( $ageStr); ?></th>
                                        <th align="left" width="16.666%">Gender: </th>
                                        <th align="left" width="16.666%"><?php echo ucfirst($data['gender']); ?></th>
                                        <th align="left" width="16.666%">Address: </th>
                                        <th align="left" width="16.666%"><?php echo mb_strimwidth($data['patient_address'],0,40); ?></th>
                                    </tr>
                                    <tr>

                                        <th align="left" width="16.666%">Token No: </th>
                                        <th align="left" width="16.666%"><?php echo @$this->AppAdmin->create_queue_number($data); ?></th>
                                        <th align="left" width="16.666%">Date: </th>
                                        <th align="left" width="16.666%"><?php echo date("d-m-Y");?></th>
                                        <th align="left" width="16.666%">Weight(KG): </th>
                                        <th align="left" width="16.666%">____</th>
                                    </tr>
                                </table>
                            <?php }else{ ?>
                                <table cellpadding="2" class="child" id="label_box"  style="transform: rotate(<?php echo $header_rotate.'deg'; ?>); border:<?php echo $header_box_border; ?>; margin-left: <?php echo $left; ?>; font-size: <?php echo $fontSize; ?>; margin-top: <?php echo $top; ?>; width: <?php echo $width; ?>; height: <?php echo $height; ?>;" >
                                    <?php $keys = 1; $size = 1; $totalSize = sizeof($fields); foreach ($fields AS $key => $field){
                                        $custom_condition = isset($field['custom_field'])?true:false;
                                        ?>
                                        <?php if($custom_condition===false){ ?>
                                            <?php if( $field['status'] == 'ACTIVE'){ ?>
                                                <?php if($keys == 1){ ?> <tr> <?php } ?>
                                                <?php if($key == 'uhid'){ ?>
                                                    <th align="left" width="16.666%">UHID: </th>
                                                    <th align="left" width="16.666%"><?php echo $data['uhid']; ?></th>
                                                <?php }else if($key == 'name'){ ?>
                                                    <th align="left" width="16.666%">Name: </th>
                                                    <th align="left" width="16.666%"><?php echo mb_strimwidth(ucfirst($data['patient_name']),0,40); ?></th>
                                                <?php }else if($key == 'parents'){ ?>
                                                    <?php $relationPrefix = $data['relation_prefix']; ?>
                                                    <th align="left" width="16.666%"><?php echo (!empty($data['relation_prefix']) && !empty($data['parents_name']))?$data['relation_prefix']:"Parents Name:"; ?> </th>
                                                    <th align="left" width="16.666%"><?php echo mb_strimwidth(ucfirst($data['parents_name']),0,40); ?></th>
                                                <?php }else if($key == 'age'){ ?>

                                                    <th align="left" width="16.666%">Age: </th>


                                                    <?php
                                                    $ageStr = $this->AppAdmin->getAgeStringFromDob($data['age']);
                                                    if(empty($ageStr)){
                                                        $ageStr = $data['age'];
                                                    }
                                                    ?>


                                                    <th align="left" width="16.666%"><?php echo ( $ageStr); ?></th>
                                                <?php }else if($key == 'gender'){ ?>
                                                    <th align="left" width="16.666%">Gender: </th>
                                                    <th align="left" width="16.666%"><?php echo ucfirst($data['gender']); ?></th>
                                                <?php }else if($key == 'address'){ ?>
                                                    <th align="left" width="16.666%">Address: </th>

                                                    <th align="left" width="16.666%"><?php echo mb_strimwidth($data['patient_address'],0,40); ?></th>
                                                <?php }else if($key == 'token_no'){ ?>
                                                    <th align="left" width="16.666%">Token No: </th>
                                                    <th align="left" width="16.666%"><?php echo @$this->AppAdmin->create_queue_number($data); ?></th>
                                                <?php }else if($key == 'date'){ ?>
                                                    <th align="left" width="16.666%">Date: </th>
                                                    <th align="left" width="16.666%"><?php echo !empty($data['appointment_datetime'])?date("d-m-Y",strtotime($data['appointment_datetime'])):date('d-m-Y');?></th>
                                                <?php }else if($key == 'weight'){ ?>
                                                    <th align="left" width="16.666%">Weight(KG): </th>
                                                    <th align="left" width="16.666%"><?php echo $data['weight']; ?></th>
                                                <?php }else if($key == 'mobile'){ ?>
                                                    <th align="left" width="16.666%">Mobile: </th>
                                                    <th align="left" width="16.666%"><?php echo $data['mobile']; ?></th>
                                                <?php }else if($key == 'OPD Fee'){ ?>
                                                    <th align="left" width="16.666%">OPD Fee: </th>
                                                    <th align="left" width="16.666%"><i class="fa fa-inr" aria-hidden="true"></i> <?php echo !empty($data['amount'])?$data['amount']:0; ?></th>
                                                <?php }else if($key == 'height'){ ?>
                                                    <th align="left" width="16.666%">Height: </th>
                                                    <th align="left" width="16.666%"><?php echo $data['patient_height']; ?></th>
                                                <?php }else if($key == 'BP Systolic'){ ?>
                                                    <th align="left" width="16.666%">BP Systolic: </th>
                                                    <th align="left" width="16.666%"><?php echo $data['bp_systolic']; ?></th>
                                                <?php }else if($key == 'head circumference'){ ?>
                                                    <th align="left" width="16.666%">Head Circumference: </th>
                                                    <th align="left" width="16.666%"><?php echo $data['head_circumference']; ?></th>

                                                <?php }else if($key == 'BP Diastolic'){ ?>
                                                    <th align="left" width="16.666%">BP Diastolic: </th>
                                                    <th align="left" width="16.666%"><?php echo $data['bp_diasystolic']; ?></th>
                                                <?php }else if($key == 'BMI'){ ?>
                                                    <th align="left" width="16.666%">BMI: </th>
                                                    <th align="left" width="16.666%">
                                                        <?php if(!empty($data['weight']) && isset($data['weight']) && !empty($data['patient_height']) && isset($data['patient_height']) && empty($data['bmi']) ){
                                                            echo round( ($data['weight'] / (($data['patient_height']/100) * ($data['patient_height']/100))),3 );
                                                        }
                                                        else
                                                        {
                                                            echo $data['bmi'];
                                                        } ?>

                                                    </th>
                                                <?php }else if($key == 'BMI Status'){ ?>
                                                    <th align="left" width="16.666%">BMI Status: </th>
                                                    <th align="left" width="16.666%"><?php echo $data['bmi_status']; ?></th>
                                                <?php }else if($key == 'temperature'){ ?>
                                                    <th align="left" width="16.666%">Temperature: </th>
                                                    <th align="left" width="16.666%"><?php echo $data['temperature']; ?></th>
                                                <?php }else if($key == 'O.Saturation'){ ?>
                                                    <th align="left" width="16.666%">O.Saturation: </th>
                                                    <th align="left" width="16.666%"><?php echo $data['o_saturation']; ?></th>
                                                <?php }else if($key == 'validity time'){ ?>
                                                    <th align="left" width="16.666%">Validity Time: </th>
                                                    <th align="left" width="16.666%"><?php echo !empty($data['service_validity_time'])?$data['service_validity_time']:''; ?>Day(s)</th>
                                                <?php }else if($key == 'doctor name'){ ?>
                                                    <th align="left" width="16.666%">Doctor Name: </th>
                                                    <th align="left" width="16.666%"><?php echo $data['doctor_name'];?></th>
                                                <?php }else if($key == 'token time'){ ?>
                                                    <th align="left" width="16.666%">Token Time: </th>
                                                    <th align="left" width="16.666%"><?php echo date('d M Y h:i A', strtotime($data['appointment_datetime']));?></th>
                                                <?php }else if($key == 'receipt datetime'){ ?>
                                                    <th align="left" width="16.666%">Receipt Time: </th>
                                                    <th align="left" width="16.666%"><?php echo !empty($data['receipt_datetime'])?date('d M Y h:i A', strtotime($data['receipt_datetime'])):'';?></th>
                                                <?php }else{ ?>
                                                    <th align="left" width="16.666%"><?php echo (isset($field['custom_field']) && !empty($field['custom_field']=="YES"))?$field['label']:$key; ?>: </th>
                                                    <th align="left" width="16.666%"><?php echo (isset($field['custom_field']) && !empty($field['custom_field']=="YES"))?$data[$field['column']]:isset($data[$key])?$data[$key]:'___'; ?></th>
                                                <?php } ?>
                                                <?php if($keys == 3 || $size == $totalSize){ ?> </tr> <?php $keys = 0; }  $keys++; ?>
                                            <?php } ?>

                                        <?php }else{ ?>
                                            <?php if( $field['status'] == 'ACTIVE'){ ?>
                                                <?php if($keys == 1){ ?> <tr> <?php } ?>
                                                <?php
                                                $label = $field['label'];
                                                if($field['column']=="bmi")
                                                {
                                                    $value = $this->AppAdmin->calculateBmi($data['patient_id'],$data['patient_type']);
                                                }else if($field['column']=="age"){
                                                    $value = $this->AppAdmin->getAgeStringFromDob($data[$field['column']]);
                                                    if(empty($value)){
                                                        $value = $data[$field['column']];
                                                    }
                                                }else if($field['column']=="country_id"){
                                                    $value = "";
                                                    if(!empty($data[$field['column']])){
                                                        $name = $this->AppAdmin->get_localization_by_id($data[$field['column']],'COUNTRY');
                                                        if(!empty($name)){
                                                            $value = $name;
                                                        }
                                                    }

                                                }else if($field['column']=="state_id"){
                                                    $value = "";
                                                    if(!empty($data[$field['column']])){
                                                        $name = $this->AppAdmin->get_localization_by_id($data[$field['column']],'STATE');
                                                        if(!empty($name)){
                                                            $value = $name;
                                                        }
                                                    }

                                                }else if($field['column']=="city_id"){
                                                    $value = $data['city_name'];
                                                    if(!empty($data[$field['column']])){
                                                        $name = $this->AppAdmin->get_localization_by_id($data[$field['column']],'CITY');
                                                        if(!empty($name)){
                                                            $value = $name;
                                                        }
                                                    }

                                                }else if($field['column']=="parents_name"){
                                                    $label = (!empty($data['relation_prefix']) && !empty($data[$field['column']]))?$data['relation_prefix']:"Parents Name";
                                                    $value = @$data[$field['column']];
                                                }else{
                                                    $value = @$data[$field['column']];
                                                }
                                                ?>

                                                <?php if(($field['column']=="conceive_date" || $field['column']=="expected_date")){ ?>
                                                    <?php if(($data['gender']=="FEMALE")){ ?>
                                                        <th align="left" width="16.666%"><?php echo ucfirst($label); ?>: </th>
                                                        <th align="left" width="16.666%"><?php echo ($value != "" && $value != '00-00-0000')?$value:''; ?></th>
                                                        <?php if($keys == 3 || $size == $totalSize){ ?> </tr> <?php $keys = 0; }  $keys++; ?>
                                                    <?php } ?>
                                                <?php }else{ ?>
                                                    <?php $value=  ($value == '00-00-0000' || empty($value))?'':$value; ?>
                                                    <th align="left" width="16.666%"><?php echo ucfirst($label); ?>: </th>
                                                    <th align="left" width="16.666%"><?php echo ucfirst($value); ?></th>
                                                    <?php if($keys == 3 || $size == $totalSize){ ?> </tr> <?php $keys = 0; }  $keys++; ?>
                                                <?php } ?>


                                            <?php } ?>
                                        <?php  } ?>
                                        <?php $size++; } ?>
                                </table>
                            <?php } ?>

                        <?php } ?>

                    </th>
                </tr>
                <tr>
                    <td>
                        <img style="margin: 5px 0px;" src="<?php echo Router::url('/web_prescription/rx.png',true);?>" >
                    </td>
                </tr>
                </thead>
                <tbody cellspacing="2" cellpadding="0" class="tag_box" id="tag_box"   style="font-weight: 500; width: 100%;border: none;">

                </tbody>
                <tfoot>
                <tr><td></td></tr>
                </tfoot>
            </table>
        </div>
    <?php }else{ ?>
        <div id="printPrescriptionBox" class="printPrescriptionBox" style="background-color:#fff;float: left; width:210mm; height:297mm; background-size: cover !important;;display:block;" >
            <div  id="printThisPrescription">
                <?php if($data['barcode'] =='YES' && $data['barcode_on_prescription'] =="YES"){ ?>
                    <div><img id="barcode"/></div>
                <?php } ?>

                <?php if($data['patient_detail_box']=='YES'){ if($data['field_layout_type'] == "ORDER_LIST"){ ?>
                    <div class="child" id="label_box"  style=" position: relative; float:left; transform: rotate(<?php echo $header_rotate.'deg'; ?>); border:<?php echo $header_box_border; ?>; left: <?php echo $left; ?>; font-size: <?php echo $fontSize; ?>; top: <?php echo $top; ?>; width: <?php echo $width; ?>; height: auto;" >
                        <?php $keys = 1; $size = 1; $totalSize = sizeof($fields); foreach ($fields AS $key => $field){
                            $custom_condition = isset($field['custom_field'])?true:false;
                            ?>
                            <?php if( $field['status'] == 'ACTIVE'){ ?>

                                <?php
                                $label = $field['label'];
                                if($field['column']=="age"){
                                    $value = $this->AppAdmin->getAgeStringFromDob($data[$field['column']],false,true);
                                    if(empty($value)){
                                        $value = $data[$field['column']];
                                    }
                                }else if($field['column']=="country_id"){
                                    $value = "";
                                    if(!empty($data[$field['column']])){
                                        $name = $this->AppAdmin->get_localization_by_id($data[$field['column']],'COUNTRY');
                                        if(!empty($name)){
                                            $value = $name;
                                        }
                                    }

                                }else if($field['column']=="state_id"){
                                    $value = "";
                                    if(!empty($data[$field['column']])){
                                        $name = $this->AppAdmin->get_localization_by_id($data[$field['column']],'STATE');
                                        if(!empty($name)){
                                            $value = $name;
                                        }
                                    }

                                }else if($field['column']=="city_id"){
                                    $value = $data['city_name'];
                                    if(!empty($data[$field['column']])){
                                        $name = $this->AppAdmin->get_localization_by_id($data[$field['column']],'CITY');
                                        if(!empty($name)){
                                            $value = $name;
                                        }
                                    }

                                }else if($field['column']=="parents_name"){
                                    $label = (!empty($data['relation_prefix']) && !empty($data[$field['column']]))?$data['relation_prefix']:"Parents Name";
                                    $value = @$data[$field['column']];
                                }else if($field['column']=="gender" || $field['column']=="marital_status"){
                                    $value = @ucfirst(strtolower($data[$field['column']]));
                                }else{
                                    $value = @$data[$field['column']];
                                }
                                ?>

                                <?php if(($field['column']=="conceive_date" || $field['column']=="expected_date")){ ?>
                                    <?php if(($data['gender']=="FEMALE")){ ?>
                                        <li>
                                            <span><?php echo ucfirst($label); ?>:</span>
                                            <span ><?php echo ($value != "" && $value != '00-00-0000')?$value:''; ?></span>
                                        </li>

                                    <?php } ?>
                                <?php }else{ ?>

                                    <?php
                                        $value=  ($value == '00-00-0000' || empty($value))?'':$value;
                                        if(ucfirst($label)=='Date' && empty($value)){
                                            $value = date('d-m-Y');
                                        }
                                    ?>

                                    <li>
                                        <span><?php echo ucfirst($label); ?>:</span>
                                        <span ><?php echo $value; ?></span>
                                    </li>

                                <?php } ?>

                            <?php } ?>
                            <?php $size++; } ?>
                    </div>
                <?php }else{ ?>
                    <table class="child" id="label_box"  style="position:relative;transform: rotate(<?php echo $header_rotate.'deg'; ?>); border:<?php echo $header_box_border; ?>; left: <?php echo $left; ?>; font-size: <?php echo $fontSize; ?>; top: <?php echo $top; ?>; width: <?php echo $width; ?>; height: <?php echo $height; ?>;" >
                        <?php $keys = 1; $size = 1; $totalSize = sizeof($fields); foreach ($fields AS $key => $field){
                            $custom_condition = isset($field['custom_field'])?true:false;
                            ?>
                            <?php if( $field['status'] == 'ACTIVE'){ ?>
                                <?php if($keys == 1){ ?> <tr> <?php } ?>
                                <?php
                                $label = $field['label'];
                                if($field['column']=="age"){
                                    $value = $this->AppAdmin->getAgeStringFromDob($data[$field['column']]);
                                    if(empty($value)){
                                        $value = $data[$field['column']];
                                    }
                                }else if($field['column']=="country_id"){
                                    $value = "";
                                    if(!empty($data[$field['column']])){
                                        $name = $this->AppAdmin->get_localization_by_id($data[$field['column']],'COUNTRY');
                                        if(!empty($name)){
                                            $value = $name;
                                        }
                                    }

                                }else if($field['column']=="state_id"){
                                    $value = "";
                                    if(!empty($data[$field['column']])){
                                        $name = $this->AppAdmin->get_localization_by_id($data[$field['column']],'STATE');
                                        if(!empty($name)){
                                            $value = $name;
                                        }
                                    }

                                }else if($field['column']=="city_id"){
                                    $value = $data['city_name'];
                                    if(!empty($data[$field['column']])){
                                        $name = $this->AppAdmin->get_localization_by_id($data[$field['column']],'CITY');
                                        if(!empty($name)){
                                            $value = $name;
                                        }
                                    }

                                }else if($field['column']=="parents_name"){
                                    $label = (!empty($data['relation_prefix']) && !empty($data[$field['column']]))?$data['relation_prefix']:"Parents Name";
                                    $value = @$data[$field['column']];
                                }else{
                                    $value = @$data[$field['column']];
                                }
                                ?>

                                <?php if(($field['column']=="conceive_date" || $field['column']=="expected_date")){ ?>
                                    <?php if(($data['gender']=="FEMALE")){ ?>
                                        <th align="left" width="16.666%"><?php echo ucfirst($label); ?>: </th>
                                        <th align="left" width="16.666%"><?php echo ($value != "" && $value != '00-00-0000')?$value:''; ?></th>
                                        <?php if($keys == 3 || $size == $totalSize){ ?> </tr> <?php $keys = 0; }  $keys++; ?>
                                    <?php } ?>
                                <?php }else{ ?>
                                     <?php
                                        $value=  ($value == '00-00-0000' || empty($value))?'':$value;
                                        if(ucfirst($label)=='Date' && empty($value)){
                                            $value = date('d-m-Y');
                                        }
                                    ?>
                                    <th align="left" width="16.666%"><?php echo ucfirst($label); ?>: </th>
                                    <th align="left" width="16.666%"><?php echo ucfirst($value); ?></th>
                                    <?php if($keys == 3 || $size == $totalSize){ ?> </tr> <?php $keys = 0; }  $keys++; ?>
                                <?php } ?>


                            <?php } ?>
                            <?php $size++; } ?>
                    </table>
                <?php }} ?>


            </div>
            <table class="tag_box" id="tag_box"   style="position:relative;border:<?php echo $tag_box_border; ?>; left: <?php echo $tb_left; ?>; font-size: <?php echo $tb_text_font_size; ?>; top: <?php echo $tb_top; ?>; width: <?php echo $tb_width; ?>; height: auto;" >
            </table>
        </div>
    <?php } ?>
</div>


<script>
    $(function () {



        var max_th_count = $("#label_box tr:first-child th").length;
        var last_row_th = $("#label_box tr:last-child th").length;
        if(last_row_th < max_th_count){
            for(var i=0; i < (max_th_count-last_row_th); i++){
                $("#label_box tr:last-child").append("<th></th>");
            }
        }

        function update_print_prescription(){

            var string ="";
            $("#prescription_body table[class^='category_']").each(function (index, value) {
                var h4 = $(this).closest('.cat_box').find("h4:first-child").clone();
                var table = $(this).clone();
                string += "<tr style='background: #dbdbdb6e;-webkit-print-color-adjust: exact;' class='background_color' ><td class='tag_heading_td'>"+$(h4).html()+"</td></tr>";
                string += "<tr><td>"+$('<div>').append(table).html()+"</td></tr>";
            });
            $("#tag_box").html(string);
            $("#tag_box th").css('font-size','<?php echo $tb_label_font_size; ?>');
            $("#tag_box td").css('font-size','<?php echo $tb_text_font_size; ?>');
            $("#tag_box .tag_heading_td").css('font-size','<?php echo $tb_heading_font_size; ?>');
            $("#tag_box td, #tag_box th").css('padding','2px 4px');

        }
        $(document).off('click','#print_prescription_btn');
        $(document).on('click','#print_prescription_btn',function (e) {
            var $btn = $(this);
            save_prescription($btn,true);
        });
        function print_prescription(){
            $('#printPrescriptionBox').printThis({              // show the iframe for debugging
                importCSS: true,            // import page CSS
                importStyle: true,         // import style tags
                pageTitle: "",              // add title to print page
                removeInline: false,        // remove all inline styles from print elements
                printDelay: 0,            // variable print delay; depending on complexity a higher value may be necessary
                header: null,               // prefix to html
                footer: null,               // postfix to html
                base: false ,               // preserve the BASE tag, or accept a string for the URL
                formValues: true,           // preserve input/form values
                canvas: false,              // copy canvas elements (experimental)
                doctypeString: " ",       // enter a different doctype for older markup
                removeScripts: false,       // remove script tags from print content
                copyTagClasses: false       // copy classes from the html & body tag
            });
        }


        function create_final_array(){
            var final_template_array = [];
            var custom_data_array = [];
            var follow_up_master_category_id ="<?php echo $follow_up_master_category_id; ?>";
            var vitals_master_category_id ="<?php echo $vitals_master_category_id; ?>";
            $("#prescription_body .cat_box").each(function (index,value) {
                var category_id = $(this).attr('data-i');
                var category_name = $(this).find('.category_title_'+category_id).text();
                $(this).find("tr").each(function (table_index,table_value) {
                    if($(this).data('key')){
                        final_template_array.push(JSON.parse($(this).data('key')));
                        if(category_id == vitals_master_category_id){
                            custom_data_array.push({'vitals':$(this).attr('data-key')});
                        }else if(category_id == follow_up_master_category_id){
                            custom_data_array.push({'follow_up':$(this).attr('data-key')});
                        }

                    }
                });
            });

            var return_array = [];
            return_array[0] = final_template_array;
            return_array[1] = custom_data_array;
            return return_array;


        }


        $(document).off('click','#save_prescription_btn');
        $(document).on('click','#save_prescription_btn',function(e){
            var $btn = $(this);
            save_prescription($btn,false);

        });





        function save_prescription($btn,print_dialog){
            $($btn).button({loadingText: '<i class="fa fa-circle-o-notch fa-spin"></i> Saving...'}).button('loading');
            setTimeout(function () {
                var return_array = create_final_array();
                var final_template_array = return_array[0];
                var custom_data_array=return_array[1];
                if(final_template_array.length > 0){
                    update_print_prescription();
                    //return false;

                    var patient_data = $("#patient_detail_object").data('key');
                    patient_data = JSON.parse(patient_data);
                    var patient_id = patient_data.general_info.patient_id;
                    var patient_type = patient_data.general_info.patient_type;
                    var folder_id = patient_data.general_info.folder_id;
                    var appointment_id = patient_data.general_info.appointment_id;
                    if(appointment_id > 0){
                        appointment_id  =btoa(appointment_id);
                    }
                    var node = document.getElementById('printPrescriptionBox');
                    domtoimage.toPng(node,{qualitiy:1}).then(function(baseEncodeImage) {
                        //var baseEncodeImage = canvas.toDataURL("image/png");
                        $('#clone_html_div').makeCssInline();
                        var prescription_html =  $("#clone_html_div").html();
                        $.ajax({
                            type:'POST',
                            url: baseUrl+"prescription/save_prescription",
                            data:{ai:appointment_id,ph:prescription_html,cd:JSON.stringify(custom_data_array),pi:btoa(patient_id),pt:patient_type, pre_base64:baseEncodeImage,fi:btoa(folder_id),ps:JSON.stringify(final_template_array)},
                            beforeSend:function(){
                                //$($btn).button({loadingText: '<i class="fa fa-spinner fa-pulse fa-1x fa-fw"></i> Saving..'}).button('loading');
                            },
                            success:function(response){
                                response = JSON.parse(response);
                                $btn.button('reset');
                                if(response.status == 1){

                                    if(print_dialog===true){
                                        print_prescription();
                                    }

                                    setTimeout(function () {
                                        flash("Prescription Save",response.message, "success",'center');
                                        $("#go_to_home").trigger('click');
                                        if(response.hasOwnProperty('li') && $(".list_ul").attr('data-rf') =='APPOINTMENT'){
                                            $( ".selected_patient" ).next(".patient_list_li").find("a").trigger('click');
                                            //$( ".selected_patient" ).replaceWith( response.li ).trigger('click');
                                        }else{
                                            $(".patient_list_li .active_li" ).trigger('click');
                                        }


                                        //$(".last_prescription img").attr("src",baseEncodeImage);
                                        //$(".last_prescription img").addClass("vew_last_prescription");
                                    },500);




                                }else{
                                    flash("Error",response.message, "danger",'center');
                                }


                            },
                            error: function(data){
                                $btn.button('reset');
                                alert("Sorry something went wrong on server.");
                            }
                        });
                    }).catch(function (error) {
                        console.error('oops, something went wrong!', error);
                    });
                }else{
                    $btn.button('reset');
                    flash("Warning","Empty prescription can not save", "warning",'center');
                }
            },30);
        }


        $(document).off('click','#save_bookmark');
        $(document).on('click','#save_bookmark',function(e){
            var prescription_template_master_category_id = "<?php echo $prescription_template_master_category_id; ?>";
            var click_object = $(this);
            var return_array = create_final_array();
            var final_template_array = return_array[0];
            var custom_data_array=return_array[1];

            if(final_template_array.length > 0){
                var string = "<input type='text' id='template_name_box' class='form-control'>";
                var data_array = JSON.stringify({'template_array': final_template_array});
                var dialog = $.confirm({
                    title: 'Save Bookmark',
                    content: '' +
                    '<div class="form-group">' +
                    '<label>Bookmark Name</label><br>' +
                    string +
                    '</div>',
                    buttons: {
                        save: {
                            text: 'Save',
                            btnClass: 'btn-blue save_bookmark_template_btn',
                            action: function () {
                                var $btn = $(".save_bookmark_template_btn");
                                var data_array = JSON.stringify({'template_array':final_template_array});
                                var template_name = this.$content.find('#template_name_box').val();
                                var category_id = prescription_template_master_category_id;
                                var category_name = 'Prescription Template'
                                var patient_data = $("#patient_detail_object").data('key');
                                patient_data = JSON.parse(patient_data);
                                var patient_id = patient_data.general_info.patient_id;
                                var patient_type = patient_data.general_info.patient_type;
                                $.ajax({
                                    type: 'POST',
                                    url: "<?php echo Router::url('/prescription/save_template',true); ?>",
                                    data: {da:data_array,tn:template_name,ci:btoa(category_id),pi:btoa(patient_id),pt:patient_type,mci:btoa(category_id)},
                                    beforeSend: function () {
                                        $($btn).button({loadingText: '<i class="fa fa-circle-o-notch fa-spin"></i> Saving..'}).button('loading');
                                    },
                                    success: function (data) {
                                        $btn.button('reset');
                                        data = JSON.parse(data);
                                        if(data.status==1){
                                            dialog.close();
                                            flash("Bookmark",data.message, "success",'center');
                                        }else{
                                            flash("Bookmark",data.message, "warning",'center');
                                        }
                                    },
                                    error: function (data) {
                                        $btn.button('reset');
                                        flash("Error","Sorry something went wrong on server.", "danger",'center');
                                    }
                                });
                                return false;
                            }
                        },
                        cancel: function () {
                            //close
                        },
                    },
                    onContentReady: function () {

                    }
                });
            }else{
                flash("Warning","Empty prescription can not bookmark", "warning",'center');
            }
        });

        setTimeout(function () {
            var background = "<?php echo !empty($prescription_base64)?$prescription_base64:''; ?>";
            if(background!=''){
                $("#printPrescriptionBox").css("background-image","url("+background+")").show();
            }
        },500);


        $.each($("#prescription_window img"), function(){
            var this_image = this;
            var src = $(this_image).attr('src') || '' ;
            var lsrc = $(this_image).attr('data-src') || '' ;
            if(lsrc.length > 0){
                var img = new Image();
                img.src = lsrc;
                $(img).load(function() {
                    this_image.src = this.src;
                });
            }
        });



        $(document).off('click','#preview_bookmark');
        $(document).on('click','#preview_bookmark',function(){
            update_print_prescription();

            var html = $("#clone_html_div").html();
            html =  '<div class="modal fade" id="show_prev_dialog" role="dialog" tabindex="-1" style="overflow: scroll !important;"><div class="modal-dialog modal-md" style="width:210mm;"><div class="modal-content" style="float: left;"><button type="button" class="close" data-dismiss="modal">&times;</button><div class="modal-body">'+html+'</div></div></div></div>';
            $(html).filter("#show_prev_dialog").modal("show");
        });

    })
</script>