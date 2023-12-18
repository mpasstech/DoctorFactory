<?php if($follow_up_master_category_id==$category_id){ ?>
<div id="myTemplateTableContainer">
    <div class="row">
        <div class="col-sm-12" style="padding: 0px 15px;">
        <h4 class="reminder_heading">Set New Reminder</h4>
        </div>
        <div class="col-sm-12">
            <label>Select Date</label>
            <div class="input-group date">
                <input type="text" class="form-control follow_up_date_picker">
                <div class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                </div>
            </div>
        </div>
        <div class="col-sm-12">
            <?php echo $this->Form->input('template_list',array('id'=>'template_drp','type'=>'select','label'=>"Select SMS Template",'options'=>$sms_template_list,'class'=>'form-control')); ?>
        </div>

        <div class="col-sm-12">

            <div class="button_container" style="text-align: center;width: 100%;">
                <?php $category_name =  !empty($category_name)?$category_name:$template_list[0]['category_name']; ?>
                <button type="submit" id="add_follow_up_btn" data-cn="<?php echo $category_name; ?>" data-ci="<?php echo base64_encode($category_id); ?>" class=" btn btn-success btn-xs"><i class="fa fa-plus"></i> Add <?php echo $category_name; ?></button>
            </div>

        </div>

    </div>

    <div class="row">
        <div class="col-sm-12" style="padding: 0px 15px;">
            <h4 class="reminder_heading" >Reminder List</h4>
        </div>
        <div class="col-sm-12" style="overflow-y: scroll;">
          <?php if(!empty($patient_reminder_list)){ ?>
            <table style="width: 100%">
            <?php foreach ($patient_reminder_list as $key => $reminder){ ?>
                <tr class="border_bottom">
                    <td>
                        <a title="Delete Reminder" href="javascript:void(0);" data-ri="<?php echo base64_encode($reminder['id']); ?>" class="delete_reminder"><i class="fa fa-trash"></i></a>
                        <label><i class="fa fa-calendar"></i> Date :- <span><?php echo $reminder['reminder_date']; ?></span></label>
                        <p>
                            <i class="fa fa-envelope"></i> <?php echo $reminder['message']; ?>
                        </p>
                    </td>
                </tr>
            <?php } ?>
            </table>
          <?php }else{ ?>
                <h4>This patient has no reminder.</h4>
          <?php } ?>
        </div>

    </div>


    <style>
        .reminder_heading{
            background: #5a81efd1;
            color: #fff;
            padding: 5px 3px;
        }
        .delete_reminder{
            float: right;
            font-size: 20px;
            color: red;
        }
        .border_bottom{
            border-bottom:1px solid #7a76767a;
        }
    </style>

    <script>
        $(function () {
            $('.follow_up_date_picker').datepicker({
                format: 'dd/mm/yyyy',
                startDate: new Date(),
                setDate: new Date(),
                autoclose:true,
                minDate:0
            }).on('changeDate', function(e) {

            });


            $(document).off('click','.delete_reminder');
            $(document).on('click','.delete_reminder',function(e){
                e.stopPropagation();
                var ri = $(this).attr('data-ri');
                var row = $(this).closest('tr');
                var patient_data = $("#patient_detail_object").data('key');
                patient_data = JSON.parse(patient_data);
                var patient_id = patient_data.general_info.patient_id;
                var patient_type = patient_data.general_info.patient_type;


                var dialog = $.confirm({
                    title: "Delete Reminder",
                    content: 'Are you sure you want to delete this reminder?',
                    type: 'red',
                    buttons: {
                        ok: {
                            text: "Yes",
                            keys: ['enter'],
                            btnClass: 'btn-primary delete_reminder_btn',
                            name:"Yes",
                            action: function(e){
                                var $btn = $(".delete_reminder_btn");
                                $.ajax({
                                    type: 'POST',
                                    url: "<?php echo Router::url('/prescription/delete_patient_reminder',true); ?>",
                                    data: {ri:ri,pi:btoa(patient_id),pt:patient_type},
                                    beforeSend: function () {
                                        $btn.button({loadingText: 'Deleting...'}).button('loading');
                                    },
                                    success: function (data) {
                                        $btn.button('reset');
                                        data = JSON.parse(data);
                                        if(data.status==1){
                                            dialog.close();
                                            $(row).slideUp(600).remove();
                                        }else{
                                            flash("Delete Reminder",data.message, "warning",'center');
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



        })
    </script>

</div>
<?php }else{ ?>
    <input type="text" id="myInputSearch"  placeholder="Search for template.." title="Type in a template">
    <div id="myTemplateTableContainer">
        <table id="myTemplateTable">
            <?php $load_auto_step = false; if(!empty($template_list)){ foreach($template_list as $key => $temp){ ?>
                <tr class="template_tr" data-cn="<?php echo $temp['category_name'];?>" data-ci="<?php echo $temp['category_id'];?>" data-tmp="<?php echo base64_encode($temp['template_array']); ?>">
                    <td style="width: 5%"><?php echo ($key+1)."."; ?></td>
                    <td style="width: 85%;" class="template_name_td"><?php echo !empty($temp['template_name'])?$temp['template_name']:$temp['template_alias_name']; ?></td>
                    <td style="width10%;text-align: center;" >
                        <a title="Delete Template" data-ci="<?php echo base64_encode($temp['category_id']); ?>" data-ti="<?php echo base64_encode($temp['id']); ?>" href="javascript:void(0);" class="delete_template"><i class="fa fa-trash"></i> </a>
                        <a title="Rename Template" data-ci="<?php echo base64_encode($temp['category_id']); ?>" data-ti="<?php echo base64_encode($temp['id']); ?>" href="javascript:void(0);" class="rename_template"><i class="fa fa-edit"></i> </a>
                    </td>
                </tr>
            <?php }}else{ $load_auto_step= true; ?>
                <label style="width: 100%; text-align: center;">No Template List Yet</label>
            <?php } ?>

        </table>
    </div>
    <div class="button_container">
        <?php if($category_id != $prescription_template_master_category_id){ ?>
        <?php $category_name =  !empty($category_name)?$category_name:$template_list[0]['category_name']; ?>
       <!-- <button type="button" id="load_step_btn" data-cn="<?php /*echo $category_name; */?>" data-ci="<?php /*echo base64_encode($category_id); */?>" class=""><i class="fa fa-plus"></i> Add <?php /*echo $category_name; */?></button>
       --> <?php } ?>
    </div>
    <style>
        #myTemplateTableContainer{
            float: left;
            display: block;
            overflow-y: auto;
            width: 100%;
            clear: both;
            border: none;
            padding-left: 15px;


        }
        #load_step_btn{
            padding: 5px;
            width: 30%;
            margin: 0 !important;
        }
        .delete_template, .rename_template{

            padding: 2px 6px;
            position: relative;
            float: right;
        }
        #myInputSearch {
            background-image: url('<?php echo Router::url("/img/web_prescription/search.png",true); ?>');
            background-position: 10px 10px;
            background-repeat: no-repeat;
            width: 98%;
            font-size: 16px;
            padding: 12px 20px 12px 40px;
            border: 1px solid #ddd;
            margin: 3px auto;
            border-radius: 54px;
            display: block;
        }

        #myTemplateTable {
            border-collapse: collapse;
            width: 100%;
            border: none;
            font-size: 18px;
        }

        #myTemplateTable th, #myTemplateTable td {
            text-align: left;
            padding: 2px;
            font-size: 16px;
        }
        #myTemplateTable tr {
            border-bottom: 1px solid #c8c8c838;
            cursor: pointer;
        }
        #myTemplateTable tr.header, #myTemplateTable tr:hover {
            background-color: #f1f1f1;
        }
        .button_container{
            width: 100%;
            text-align: center;
            float: left;
            padding: 5px;
        }

        .template_tr.selected{
            background: #c7e8ff;
        }

    </style>
    <script>
        $(function () {
            var load_auto_step = <?php echo json_encode($step_modal); ?>;
            if(load_auto_step){
                var html = $(load_auto_step).filter('#stepModal').modal('show');
            }
        })
    </script>



<?php } ?>

