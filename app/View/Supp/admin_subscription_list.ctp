<?php  $login = $this->Session->read('Auth.User'); ?>
<?php
echo $this->Html->css(array('select2.min.css'));
echo $this->Html->script(array('select2.min.js'));
?>
<div class="Inner-banner">
    <div class="container">
        <div class="row">
            <!--  SLIDER IMAGE -->
            <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 inner-page-text"> <h2>Subscripiton App List</h2> </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 pull-right create-btn-box">

            </div>
        </div>
    </div>
</div>
<section class="Home-section-2">

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="container">
            <div class="row">
                <div class="middle-block">


                    <?php if($this->SupportAdmin->isMainSupportAdmin()){ ?>
                    <?php echo $this->element('support_admin_leftsidebar'); ?>
                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                    <?php }else{ ?>
                        <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10 col-sm-offset-1">

                    <?php } ?>



                        <div class="Social-login-box payment_bx">

                            <input type="file" class="hidden_file_browse">

                            <?php echo $this->Form->create('Search',array('type'=>'get','url'=>array('controller'=>'supp','action' => 'search_subscription_list'),'admin'=>true)); ?>
                            <div class="form-group">
                                <div class="col-sm-4">
                                    <?php echo $this->Form->input('name', array('type' => 'text', 'placeholder' => 'Insert name', 'label' => 'Search by name', 'class' => 'form-control')); ?>
                                </div>
                                <!--<div class="col-sm-4">

                                    <?php /*echo $this->Form->input('email', array('type' => 'text', 'placeholder' => 'Insert email', 'label' => 'Search by email', 'class' => 'form-control')); */?>
                                </div>-->
                                <div class="col-sm-2">
                                    <?php echo $this->Form->label('&nbsp;'); ?>
                                    <?php echo $this->Form->submit('Search',array('class'=>'Btn-typ3','id'=>'search')); ?>
                                </div>
                                <div class="col-sm-2">
                                    <?php echo $this->Form->label('&nbsp;'); ?>
                                    <div class="submit">
                                        <a href="<?php echo $this->Html->url(array('controller'=>'supp','action'=>'subscription_list')) ?>"><button type="button" class="Btn-typ3" >Reset</button></a>
                                    </div>
                                </div>

                            </div>

                            <?php echo $this->Form->end(); ?>

                            <div class="form-group row">
                                <div class="col-sm-12">
                        <div class="table-responsive">
                            <table class="table table-responsive ">

                                <thead>
                                <tr >
                                    <th>#</th>
                                    <th>Logo</th>
                                    <th width="300">Name</th>
                                    <?php if($this->SupportAdmin->isMainSupportAdmin()){ ?>
                                        <th>Phone</th>
                                    <?php } ?>
                                    <th width="120">Start Date</th>
                                    <th width="120">End Date</th>
                                    <th width="120">Last 6 Month Appointments</th>
                                    <th width="120">Days Left</th>
                                    <th>Action</th>
                                </tr>
                                </thead>

                                <tbody>

                                <?php
                                if(isset($data) && !empty($data))
                                {
                                    foreach($data as $key => $val)
                                    {

                                        ?>
                                        <tr>
                                            <td><?php echo $key+1; ?></td>
                                            <td>
                                                <div class="upload_layer">
                                                <img class="logo_img" data-id="<?php  echo $val['Thinapp']['id']; ?>" src="<?php  echo $val['Thinapp']['logo']; ?>" >
                                                    <i class="fa fa-upload"></i>
                                                </div>
                                            </td>
                                            <td>
                                                <?php echo $val['Thinapp']['name']; ?>

                                            </td>
                                           <?php if($this->SupportAdmin->isMainSupportAdmin()){ ?>
                                            <td><?php echo $val['Thinapp']['phone']; ?></td>
                                             <?php } ?>
                                            <td><?php echo date('d-m-Y',strtotime($val['Thinapp']['subscription_start_date'])); ?></td>
                                            <td><?php echo date('d-m-Y',strtotime($val['Thinapp']['end_date'])); ?></td>
                                            <td><?php echo $val[0]['total_appointment']; ?></td>
                                            <td><?php echo $val[0]['total_days']; ?></td>
                                            <td class="td_links">
                                                <div style="display:flex;">
                                                  &nbsp;<button title="<?php echo ($val['Thinapp']['is_published']=='NO')?'This app is not published yet':'This app is published on play store'; ?>" type="button"  class="setting action_icon btn btn-<?php echo ($val['Thinapp']['is_published']=='YES')?'success':'danger'; ?> btn-xs setting_btn_<?php echo $val['Thinapp']['id']; ?>"  row-id="<?php echo $val['Thinapp']['id']; ?>" ><i class="fa fa-cog"></i> Update Subscription</button>
                                                </div>
                                            </td>
                                        </tr>

                                    <?php }  ?>
                                <?php }  ?>

                                </tbody>
                            </table>
                            <?php echo $this->element('paginator'); ?>
                        </div>


                        <div class="clear"></div>
                    </div>
                    </div>



                </div>
            </div>
        </div>
    </div>
</section>


<div class="modal fade" id="addThinAppMobal" role="dialog">

    <div class="modal-dialog modal-sm">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Edit Thin App</h4>
            </div>
            <form id="editThinAppForm" method="POST">

            <div class="modal-body">
                <div class="table-responsive">

                        <div class="form-group">
                            <div class="col-sm-12">
                                <input type="email" name="email" id="emailHolder" placeholder="Please enter email" class="form-control cnt" required>
                                <input type="hidden" name="id" id="idHolder" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <input type="text" name="phone" id="phoneHolder" placeholder="Please enter phone number" class="form-control cnt" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <input type="text" name="address" id="addressHolder"  placeholder="Please enter address" class="form-control cnt" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <input type="text" name="apk_url" id="apkUrlHolder"  placeholder="Please enter apk URL" class="form-control cnt" required>
                            </div>
                        </div>
                </div>
            </div>
            <div class="modal-footer">

                <div class="form-group">
                    <div class="col-sm-12">
                        <button type="submit" name="submitForm" class="form-control" id="thisBtn" >Edit Thin App</button>
                    </div>
                </div>

            </div>

            </form>

        </div>
    </div>

</div>





<div class="modal fade" id="myModalView" role="dialog">

    <div class="modal-dialog modal-sm">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">View Lead</h4>
            </div>
            <div class="modal-body">

                <div class="table-responsive">
                    <table class="table" id="viewLeadTable">

                    </table>
                </div>

            </div>
        </div>
    </div>

</div>



<div class="modal fade" id="permission_modal" role="dialog"></div>
<div class="modal fade" id="setting_modal" role="dialog"></div>



<script>
    $(document).ready(function(){



        $(document).on('click','.user_permission',function(e){
            var rowID = $(this).attr('row-id');
            var thisButton = $(this);
            $.ajax({
                url: baseurl+'/admin/supp/load_permission',
                data:{rowID:rowID},
                type:'POST',
                beforeSend:function(){
                    $(thisButton).button('loading').html('<i class="fa fa-spinner fa-pulse">');
                },
                success: function(result){
                    $(thisButton).button('reset');
                    $("#permission_modal").html(result).modal('show');
                }
            });
        });


        $(document).on('click','.convence_btn',function(e){
            $("#booking_convenience_modal").remove();
            var rowID = $(this).attr('row-id');
            var thisButton = $(this);
            $.ajax({
                url: baseurl+'/admin/supp/booking_convenience_modal',
                data:{rowID:rowID},
                type:'POST',
                beforeSend:function(){
                    $(thisButton).button('loading').html('<i class="fa fa-spinner fa-pulse">');
                },
                success: function(result){
                    $(thisButton).button('reset');
                    var html = $(result).filter('#booking_convenience_modal');
                    $(html).modal('show');

                }
            });
        });



        $(document).on('click','.setting',function(e){
            var rowID = $(this).attr('row-id');
            var thisButton = $(this);
            $.ajax({
                url: baseurl+'/admin/supp/load_subscription',
                data:{rowID:rowID},
                type:'POST',
                beforeSend:function(){
                    $(thisButton).button('loading').html('<i class="fa fa-spinner fa-pulse">');
                },
                success: function(result){
                    $(thisButton).button('reset');
                    $("#setting_modal").html(result).modal('show');
                }
            });
        });


        $(document).on('click','#view',function(e){
            var rowID = $(this).attr('row-id');
            var thisButton = $(this);
            $.ajax({
                url: baseurl+'/admin/supp/view_thinapp',
                data:{rowID:rowID},
                type:'POST',
                beforeSend:function(){
                    $(thisButton).button('loading').html('<i class="fa fa-spinner fa-pulse">');
                },
                success: function(result){
                    $(thisButton).button('reset');
                        $("#viewLeadTable").html(result);
                        $("#myModalView").modal('show');
                }
            });
        });

        $(document).on('click','#changeStatus',function(e){
            var rowID = $(this).attr('row-id');

            var thisButton = $(this);
            $.ajax({
                url: baseurl+'/admin/supp/change_thinapp_status',
                data:{rowID:rowID},
                type:'POST',
                beforeSend:function(){
                    $(thisButton).button('loading').html('<i class="fa fa-spinner fa-pulse">');
                },
                success: function(result){
                    $(thisButton).button('reset');
                    var result = JSON.parse(result);
                    if(result.status == 1)
                    {
                        $(thisButton).text(result.text);
                    }
                    else
                    {
                        alert('Sorry, Could not change status!');
                    }
                }
            });
        });



        $(document).on('blur','.version_name',function(e){
            var version = $(this).val();
            var rowID = $(this).attr('row-id');
            var last_value = $(this).attr('last-value');
            var thisButton = $(this);
            if(version != last_value && version != "")
            {
                $.ajax({
                    url: baseurl + '/admin/supp/change_thinapp_version',
                    data: {version: version, rowID: rowID},
                    type: 'POST',
                    beforeSend: function () {
                        $(thisButton).closest('td').append('<i class="fa fa-spinner fa-pulse ver_load">');
                    },
                    success: function (result) {
                        $(thisButton).closest('td').find(".ver_load").remove();
                        var result = JSON.parse(result);
                        if (result.status == 1) {
                            $(thisButton).attr('last-value',version);
                            $(thisButton).css('border-color', "green");
                        }
                        else {
                            $(thisButton).css('border-color', "red");
                        }
                    }
                });
            }else{
                $(this).val(last_value);
            }
        });

        $(document).on('blur','.doctor_count',function(e){
            var version = $(this).val();
            var rowID = $(this).attr('row-thinapp-id');
            var last_value = $(this).attr('last-value');
            var thisButton = $(this);
            if(version != last_value && version != "")
            {
                $.ajax({
                    url: baseurl + '/admin/supp/change_thinapp_allowed_doctor_count',
                    data: {version: version, rowID: rowID},
                    type: 'POST',
                    beforeSend: function () {
                        $(thisButton).closest('td').append('<i class="fa fa-spinner fa-pulse ver_load">');
                    },
                    success: function (result) {
                        $(thisButton).closest('td').find(".ver_load").remove();
                        var result = JSON.parse(result);
                        if (result.status == 1) {
                            $(thisButton).attr('last-value',version);
                            $(thisButton).css('border-color', "green");
                        }
                        else {
                            $(thisButton).css('border-color', "red");
                        }
                    }
                });
            }else{
                $(this).val(last_value);
            }
        });

        $(document).on('change','.lead_status',function(e){
            var status = $(this).val();
            var rowID = $(this).attr('row-id');
            var thisButton = $(this);
                $.ajax({
                    url: baseurl + '/admin/supp/change_lead_status',
                    data: {status: status, rowID: rowID},
                    type: 'POST',
                    beforeSend: function () {
                        $(thisButton).closest('td').append('<i class="fa fa-spinner fa-pulse ver_load">');
                    },
                    success: function (result) {
                        $(thisButton).closest('td').find(".ver_load").remove();
                        var result = JSON.parse(result);
                        if (result.status == 1) {
                            $(thisButton).css('border-color', "green");
                        }
                        else {
                            $(thisButton).css('border-color', "red");
                        }
                    }
                });

        });

        $(document).on('change','.category_status',function(e){
            var status = $(this).val();
            var rowID = $(this).attr('row-id');
            var thisButton = $(this);
                $.ajax({
                    url: baseurl + '/admin/supp/change_category',
                    data: {status: status, rowID: rowID},
                    type: 'POST',
                    beforeSend: function () {
                        $(thisButton).closest('td').append('<i class="fa fa-spinner fa-pulse ver_load">');
                    },
                    success: function (result) {
                        $(thisButton).closest('td').find(".ver_load").remove();
                        var result = JSON.parse(result);
                        if (result.status == 1) {
                            $(thisButton).css('border-color', "green");
                        }
                        else {
                            $(thisButton).css('border-color', "red");
                        }
                    }
                });

        });


        $(document).on('click','#edit',function(e){
            var rowID = $(this).attr('row-id');
            var thisButton = $(this);
            $.ajax({
                url: baseurl+'/admin/supp/get_edit_thinapp',
                data:{rowID:rowID},
                type:'POST',
                beforeSend:function(){
                    $(thisButton).button('loading').html('<i class="fa fa-spinner fa-pulse">');
                },
                success: function(result){
                    $(thisButton).button('reset');
                    var result = JSON.parse(result);
                    if(result.status == 1)
                    {
                        $("#apkUrlHolder").val(result.data.apk_url);
                        $("#emailHolder").val(result.data.email);
                        $("#phoneHolder").val(result.data.phone);
                        $("#addressHolder").val(result.data.address);
                        $("#idHolder").val(result.data.id);
                        $("#addThinAppMobal").modal('show');
                    }
                    else
                    {
                        alert('Sorry, Could not edit.');
                    }
                }
            });
        });


        $(document).on('submit','#editThinAppForm',function(e){
            e.stopPropagation();
            e.preventDefault();
           var dataToPost = $(this).serialize();
            var thisButton = $("#thisBtn");
            $.ajax({
                url: baseurl+'/admin/supp/edit_thinapp',
                data:dataToPost,
                type:'POST',
                beforeSend:function(){
                    $(thisButton).button('loading').html('<i class="fa fa-spinner fa-pulse">');
                },
                success: function(result){
                    $(thisButton).button('reset');
                    var result = JSON.parse(result);

                    if(result.status == 1)
                    {
                        $("#addThinAppMobal").modal('hide');
                     //   userID
                        $("#editThinAppForm").trigger('reset');
                        location.reload();
                    }
                    else
                    {
                        alert(result.message);
                    }
                }
            });


        });

        $('#datePick').datepicker({startDate:new Date(),format: 'yyyy-mm-dd'});




        /* work for modal of loading permission change status*/

        $(document).on('click','.action_btn',function(e){
            var text = $(this).text().trim();
            var data_fun_id = $(this).attr('data-fun-id');

            var fun_type_id = $(this).attr('fun-type-id');
            var data_app_id = $(this).attr('data-app-id');
            var hasClass22 = $(this).closest('.parent_div').hasClass('com_22');
            var has22_text = $('.com_22').find('button').text().trim();
            var has40_text = $('.com_40').find('button').text().trim();
            var hasClass40 = $(this).closest('.parent_div').hasClass('com_40');
            var save_data =true;
            var message = "";
            if(hasClass22){
                if(has40_text == "ACTIVE"){
                  save_data = false;
                    message = " Inactive new quick appointment to active quick appointment. ";
                }
            }
            if(hasClass40){
                if(has22_text == "ACTIVE"){
                    save_data = false;
                    message = " Inactive quick appointment to active new quick appointment.";
                }
            }
            if(save_data){
                var thisButton = $(this);
                $.ajax({
                    url: baseurl+'/admin/supp/change_app_permission',
                    data:{data_fun_id:data_fun_id,fun_type_id:fun_type_id,data_app_id:data_app_id},
                    type:'POST',
                    beforeSend:function(){
                        $(thisButton).button('loading').html('<i class="fa fa-spinner fa-pulse ">');
                    },
                    success: function(result){
                        $(thisButton).button('reset');
                        var result = JSON.parse(result);

                        if(result.status == 1)
                        {
                            $(thisButton).closest('td').html(result.html_string);

                            if(result.html_string.indexOf('INACTIVE') != -1){
                                $(".booking_convenience_fee_form").hide();
                            }
                            else
                            {
                                $(".booking_convenience_fee_form").show();
                            }

                        }
                        else
                        {
                            alert(result.message);
                        }
                    }
                });
            }else{
                $.alert(message);
            }

        });

        var change_image_obj;
        $(document).on('click','.logo_img',function(e){
            change_image_obj = $(this);
            $('.hidden_file_browse').trigger('click');
        });

        $(document).on('change','.hidden_file_browse',function(e){
            if($(this).val()){
               // $(change_image_obj).next('i').show();
                readURL(this);
            }else{
              //  $(change_image_obj).next('i').hide();
            }
        });


        function readURL(input) {

            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $(change_image_obj).attr('src', e.target.result);
                    upload_file= true;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }


    });
</script>


<style>

    .link_tr, .link_tr td{
        padding: 0 !important;
        margin: 0 !important;
    }
    /* Important part */
    .modal-dialog{
        overflow-y: initial !important
    }
    .modal-body{
        height: 500px;
        overflow-y: auto;
    }

    .ver_load {
        position: absolute;
        margin: 0 auto;
        /* float: left; */
        margin-top: -31px;
        margin-left: 48px;
    }
    .lead_status {
        padding: 0;
        height: 23px;

    }
    .Social-login-box{
        padding: 17px 4px !important;
    }
    .logo_img{
        border-radius: 50px;
        border: 1px solid #f8f8f8;
        width: 50px;
        height: 50px;
    }
    .upload_layer i{
        width: 50px;
        height: 50px;
        position: relative;
        background: rgba(0, 0, 0, 0.28);
        top: -52px;
        border-radius: 50px;
        margin: 0 auto;
        text-align: center;
        padding-top: 13px;
        color: #ffffffa8;
        font-size: 1.3rem;
        display: none;
    }
</style>
