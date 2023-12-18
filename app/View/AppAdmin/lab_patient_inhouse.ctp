<?php

if(isset($dataToSend['data']))
{
    $dataToSend = $dataToSend['data']['list'];
}
else
{
    $dataToSend = array();
}
$login = $this->Session->read('Auth.User');

$country_list =$this->AppAdmin->countryDropdown(true);
//$doctor_list =$this->AppAdmin->getHospitalDoctorList($login['User']['thinapp_id']);
?>
<?php echo $this->Html->script(array('magicsuggest-min.js')); ?>
<?php echo $this->Html->css(array('magicsuggest-min.css')); ?>
<?php  echo $this->Html->css(array('dataTableBundle.css')); ?>
<?php  echo $this->Html->script(array('dataTableBundle.js','jquery.maskedinput-1.2.2-co.min.js','comman.js')); ?>

<!-- this element user for use upload file to folder methods -->
<?php echo $this->element('app_admin_inner_tab_drive_lab_pharma'); ?>
<!--END-->

<div class="Home-section-2">

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="container">
            <div class="row">
                <!--box 1 -->
                <!--box 1 -->


                <div class="middle-block">
                    <!-- Heading -->
                    <h3 class="screen_title">Patient List</h3>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 custom_form_box">


                        <?php echo $this->element('message'); ?>


                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 hospital_search_box">

                            <div class="row search_main">
                                <?php echo $this->Form->create('Search',array('type'=>'get','url'=>array('controller'=>'app_admin','action' => 'search_lab_patient_inhouse'))); ?>
                                <div class="form-group">



                                    <div class="col-md-1">
                                        <?php echo $this->Form->input('search', array('type'=>'text','placeholder' => '', 'label' => 'Search', 'class' => 'form-control')); ?>
                                    </div>
                                    <div class="col-md-2">
                                        <?php echo $this->Form->input('from_date', array('type'=>'text','placeholder' => '', 'label' => 'From date', 'class' => 'form-control from_date')); ?>
                                    </div>
                                    <div class="col-md-2">
                                        <?php echo $this->Form->input('to_date', array( 'type'=>'text','placeholder' => '', 'label' => 'To date', 'class' => 'form-control to_date')); ?>
                                    </div>
                                    <div class="col-md-1">
                                        <label>&nbsp;</label>
                                        <?php echo $this->Form->input('status_new', array('type' => 'checkbox','label' => 'New')); ?>
                                    </div>
                                    <div class="col-md-1">
                                        <label>&nbsp;</label>
                                        <?php echo $this->Form->input('checked-in', array('type' => 'checkbox','label' => 'Checked-In')); ?>
                                    </div>

                                    <div class="col-md-1">
                                        <label>&nbsp;</label>
                                        <?php echo $this->Form->input('status_billing', array('type' => 'checkbox','label' => 'Billing')); ?>
                                    </div>
                                    <div class="col-md-1">
                                        <label>&nbsp;</label>
                                        <?php echo $this->Form->input('status_closed', array('type' => 'checkbox','label' => 'Closed')); ?>
                                    </div>

                                    <div class="col-sm-3 action_btn" >
                                        <div class="input text">
                                            <label style="display: block;">&nbsp;</label>
                                            <?php echo $this->Form->submit('Search',array('div'=>false,'class'=>'btn btn-info','id'=>'search')); ?>
                                            <a class="btn btn-warning resteButton" href="<?php echo $this->Html->url(array('controller'=>'app_admin','action'=>'lab_patient_inhouse')) ?>">Reset</a>
                                            <a class="btn btn-success morePatient" href="javascript:void(0);">More Patients</a>
                                        </div>
                                    </div>
                                </div>
                                <?php echo $this->Form->end(); ?>
                            </div>
                        </div>

                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 hospital_table_box">


                            <div class="row">
                                <?php if(!empty($dataToSend)){ ?>
                                    <div class="table-responsive">

                                        <table id="example" class="table">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Patient Name</th>
                                                <th>Patient Mobile</th>
                                                <th>Token Number</th>
                                                <th>Appointment Time</th>
                                                <th>Doctor Name</th>
                                                <th>File Status</th>
                                                <th>Options</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            foreach ($dataToSend as $key => $list){
                                                ?>
                                                <tr>
                                                    <td><?php echo $key+1; ?></td>
                                                    <td><?php echo $list['patient_name']; ?></td>
                                                    <td><?php echo $list['patient_mobile']; ?></td>
                                                    <td><?php echo $list['queue_number']; ?></td>
                                                    <td><?php echo $list['date']; ?> <?php echo $list['time']; ?></td>
                                                    <td><?php echo $list['doctor_name']; ?></td>
                                                    <td class="status<?php echo $list['id']; ?>"><?php echo $list['file_status']; ?></td>
                                                    <td>
                                                        <a class="btn btn-xs btn-info add_new_file" href="javascript:void(0);" data-id="<?php echo $list['drive_folder_id']; ?>"><i class="fa fa-upload"></i> Upload</a>
                                                        <button type="button" class="btn btn-xs btn-info files_btn" data='<?php echo json_encode($list['file_list']); ?>'>Files</button>
                                                        <br>
                                                        <form class="formChk" method="POST" >
                                                            <?php
                                                            $appointmentStaffID = isset($list['appointment_staff_id']) ? $list['appointment_staff_id'] : 0;
                                                            $appointmentCustomerID = isset($list['appointment_customer_id']) ? $list['appointment_customer_id'] : 0;
                                                            $childrenID = isset($list['children_id']) ? $list['children_id'] : 0;
                                                            ?>
                                                            <input type="radio" appointmentStaffID="<?php echo $appointmentStaffID; ?>" appointmentCustomerID="<?php echo $appointmentCustomerID; ?>" childrenID="<?php echo $childrenID; ?>" class="radioBtn"<?php if($list['file_status'] == 'NEW'){ ?> checked="checked"<?php } ?> name="status[<?php echo $list['id']; ?>]" appointmentID="<?php echo $list['id']; ?>" value="NEW" id="<?php echo $list['id']; ?>new">
                                                            <label for="<?php echo $list['id']; ?>new">New</label>
                                                            <input type="radio" appointmentStaffID="<?php echo $appointmentStaffID; ?>" appointmentCustomerID="<?php echo $appointmentCustomerID; ?>" childrenID="<?php echo $childrenID; ?>" class="radioBtn" <?php if($list['file_status'] == 'CHECKED-IN'){ ?> checked="checked"<?php } ?> name="status[<?php echo $list['id']; ?>]" appointmentID="<?php echo $list['id']; ?>" value="CHECKED-IN" id="<?php echo $list['id']; ?>checked-in">
                                                            <label for="<?php echo $list['id']; ?>checked-in">Checked-In</label>
                                                            <input type="radio" appointmentStaffID="<?php echo $appointmentStaffID; ?>" appointmentCustomerID="<?php echo $appointmentCustomerID; ?>" childrenID="<?php echo $childrenID; ?>" class="radioBtn" <?php if($list['file_status'] == 'BILLING'){ ?> checked="checked"<?php } ?> name="status[<?php echo $list['id']; ?>]" appointmentID="<?php echo $list['id']; ?>" value="BILLING" id="<?php echo $list['id']; ?>billing">
                                                            <label for="<?php echo $list['id']; ?>billing">Billing</label>
                                                            <input type="radio" appointmentStaffID="<?php echo $appointmentStaffID; ?>" appointmentCustomerID="<?php echo $appointmentCustomerID; ?>" childrenID="<?php echo $childrenID; ?>" class="radioBtn" <?php if($list['file_status'] == 'CLOSED'){ ?> checked="checked"<?php } ?> name="status[<?php echo $list['id']; ?>]" appointmentID="<?php echo $list['id']; ?>" value="CLOSED" id="<?php echo $list['id']; ?>closed">
                                                            <label for="<?php echo $list['id']; ?>closed">Closed</label>

                                                        </form>
                                                    </td>

                                                </tr>
                                            <?php } ?>
                                            </tbody>
                                        </table>

                                    </div>
                                <?php }else{ ?>

                            </div>
                            <div class="no_data">
                                <h2>No OPD found</h2>
                            </div>
                            <?php } ?>
                            <div class="clear"></div>
                        </div>


                    </div>






                </div>
                <!-- box 1 -->


            </div>
            <!--box 2 -->


        </div>
    </div>
</div>



<div class="modal fade" id="file_list_container" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">

    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Files</h4>
            </div>
            <div class="modal-body modalData">

            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>

</div>


<style>
    #example_length {
        width: 32%;
        text-align: right;
    }
    .file_box_container {

        border: 1px solid #ccc;
        border: 3px double rgba(204, 204, 204, 0.74) !important;
        display: block;
        float: left;
        padding: 0;
        position: relative;
        width: 100%;
        margin: 12px 0px;

    }
    .file_label {

        background-color: yellow;
        position: absolute;
        text-align: center;
        box-shadow: 3px 3px 7px -5px;
        font-weight: 600;
        z-index: 99;
        padding-left: 5px;
        padding-right: 5px;
    }
    .dashboard_icon_li .image_box {

        background-size: cover;
        display: block;
        float: left;
        height: 250px;
        width: 100%;

    }
    .file_box_container .image_box .icon {

        margin: 25% auto;
        text-align: center;
        width: 100%;
        float: left;

    }
    .file_box_container .icon .fa {

        font-size: 150px;

    }
</style>





<div class="modal fade" id="myModalAddPatient" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" style="color:#FFF;">Add New Patient</h4>
            </div>

            <div class="modal-body">
                <?php echo $this->Form->create('AppointmentCustomer',array('type'=>'file','method'=>'post','class'=>'form-horizontal','id'=>'formAddNewPatient')); ?>
                <div class="form-group">
                    <div class="col-sm-6">
                        <label>Name<span class="red">*</span></label>
                        <?php echo $this->Form->input('first_name',array('type'=>'text',"maxlength"=>"30",'id'=>'focusInput','list'=>'nameList','placeholder'=>'Name','label'=>false,'class'=>'form-control cnt','required'=>true)); ?>
                        <?php echo $this->Form->input('patient_type',array('type'=>'hidden','value'=>'CUSTOMER','label'=>false,'required'=>true)); ?>
                    </div>

                    <div class="col-sm-6">
                        <label>Mobile<span class="red">*</span></label>
                        <?php echo $this->Form->input('mobile',array('type'=>'text', "onkeyup"=>"if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')",'id'=>'mobile',"maxlength"=>"10","minlength"=>"10",'placeholder'=>"9999999999",'label'=>false,'class'=>'form-control cnt', 'required'=>"required")); ?>

                    </div>

                </div>

                <div class="form-group">
                    <div class="col-sm-6">
                        <label>Age</label><br>
                        <input max="150" min="0" placeholder="Year" class="form-control cnt ageInput" id="ageYear" type="number">
                        <input max="12" min="0" placeholder="Month" class="form-control cnt ageInput" id="ageMonth" type="number">
                        <input max="31" min="0" placeholder="Day" class="form-control cnt ageInput" id="ageDay" type="number">
                        <?php echo $this->Form->input('age',array('type'=>'hidden',"maxlength"=>"50",'placeholder'=>'Age','label'=>false,'class'=>'form-control cnt')); ?>

                    </div>
                    <div class="col-sm-6">
                        <label>Date Of Birth</label>
                        <?php echo $this->Form->input('dob',array('type'=>'text', 'pattern'=>"\d{1,2}/\d{1,2}/\d{4}","title"=>"Please enter valid DD/MM/YYYY formate date",'placeholder'=>'DD/MM/YYYY','label'=>false,'class'=>'form-control cnt')); ?>

                    </div>


                </div>

                <div class="form-group">
                    <div class="col-sm-6">
                        <label>Gender</label>
                        <?php echo $this->Form->input('gender',array('type'=>'select','options'=>array('MALE'=>'Male','FEMALE'=>'Female'),'label'=>false,'class'=>'form-control cnt')); ?>

                    </div>
                    <div class="col-sm-6">
                        <label>Email</label>
                        <?php echo $this->Form->input('email',array('type'=>'email',"maxlength"=>"50",'placeholder'=>'Email','label'=>false,'class'=>'form-control cnt')); ?>

                    </div>

                </div>

                <div class="form-group">
                    <div class="col-sm-6">
                        <label>Address</label>
                        <?php echo $this->Form->input('address',array('type'=>'text',"maxlength"=>"100",'placeholder'=>'Address','list'=>'addressList','label'=>false,'class'=>'form-control cnt')); ?>

                    </div>
                    <div class="col-sm-6">
                        <label>Marital Status</label>
                        <?php echo $this->Form->input('marital_status',array('type'=>'select',"options"=>array("MARRIED"=>"Married","UNMARRIED"=>"Unmarried"),'label'=>false,'class'=>'form-control cnt')); ?>

                    </div>

                </div>

                <div class="form-group">
                    <div class="col-sm-6">
                        <label>Blood Group</label>
                        <?php echo $this->Form->input('blood_group',array('type'=>'select','options'=>array('N/A'=>'N/A','O+'=>'O+','A+'=>'A+','B+'=>'B+','AB+'=>'AB+','O-'=>'O-','A-'=>'A-','B-'=>'B-','AB-'=>'AB-'),'label'=>false,'class'=>'form-control cnt')); ?>

                    </div>
                    <div class="col-sm-6">
                        <label>Relation</label>
                        <div class="parent_holder">
                            <?php echo $this->Form->input('relation_prefix',array('type'=>'select','options'=>array('S/O'=>'S/O','D/O'=>'D/O','W/O'=>'W/O','C/O'=>'C/O'),'label'=>false,'class'=>'form-control cnt relation_prefix')); ?>
                            <?php echo $this->Form->input('parents_name',array('type'=>'text',"maxlength"=>"50",'placeholder'=>"Name",'label'=>false,'class'=>'form-control cnt parent_name_input')); ?>
                        </div>
                    </div>

                </div>
                <div class="form-group">
                    <div class="col-sm-6">
                        <label>Country</label>
                        <?php echo $this->Form->input('country_id',array('type'=>'text','label'=>false,'class'=>'form-control country')); ?>
                    </div>
                    <div class="col-sm-6">
                        <?php $state_list =array();?>
                        <label>State <i class="fa fa-spinner fa-spin state_spin" style="display:none;"></i> </label>
                        <?php echo $this->Form->input('state_id',array('type'=>'text','label'=>false,'class'=>'form-control state')); ?>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-6">
                        <?php $city_list =array();?>
                        <label>City <i class="fa fa-spinner fa-spin city_spin" style="display:none;"></i></label>
                        <?php echo $this->Form->input('city_id',array('type'=>'text','label'=>false,'class'=>'form-control city')); ?>
                    </div>
                </div>


                <div class="form-group">
                    <div class="col-sm-6 col-sm-offset-3">
                        <label>&nbsp;</label>
                        <?php echo $this->Form->submit('Save',array('class'=>'Btn-typ5','id'=>"submitAddPatientBtn")); ?>
                    </div>
                </div>
                <?php echo $this->Form->end(); ?>
            </div>

        </div>
    </div>
</div>

<style>
    #AppointmentCustomerRelationPrefix {
        font-size: 16px;
        padding: 0;
    }
    #ageMonth {
        padding: 0;
    }
    .paymentTypeSelect {

    }


    .span_left {
        float: right;
    }
    .close.removeRowAdd {
        margin-top: 27px;
    }
    .address_col{
        margin-top: 10px;
    }
    .ageInput {
        width: 30%;
        display: inline-block;
    }

    .red{color:red;font-size: 15px;}
    .relation_prefix {
        width: 25%;
    }
    .parent_name_input {
        width: 75%;
    }
    .parent_holder .input {
        display: contents;
    }
    .parent_holder {
        display: flex;
    }
    .ms-ctn .ms-sel-ctn {

        margin-left: unset;
        max-height: 26px;
        min-height: 26px;

    }
    #formPaySearch input, #formPaySearch select, #formPay input, #formPay select, #formPayAdd input, #formPayAdd select {

        padding: unset;
        width: 100%;

    }
    .ms-res-ctn .ms-res-item {

        line-height: unset;
        text-align: left;
        padding: 9px 5px;
        color: #666;
        cursor: pointer;

    }
    .ms-helper{ display:none !important; }
    .ms-sel-ctn .ms-sel-item{ color: #000000 !important;min-height: 24px; }


    .ms-close-btn {
        top: 0;
        position: absolute;
        right: 33px;
    }

    .referrer_lbl{
        float: right!important;
        font-size: 13px !important;
        margin: 3px!important;
        color: red;
        text-decoration: underline;
    }
    #show_referrer{
        width: 5% !important;
        float: right !important;
        margin: 2px 3px !important;
        font-size: 14px !important;

    }

    .due_amount_span{
        float: left;
        text-align: left;
        width: 50%;
    }
    .total_due_span{
        float: right;
        text-align: right;
        width: 50%;
    }


</style>



<script>
    $(document).ready(function(){




        $(document).on('click','.morePatient',function(e){
            var $btn = $(this);
            var obj = $(this);
            $.ajax({
                type: 'POST',
                url: baseurl + "app_admin/lab_more_patient",
                beforeSend: function () {
                    $btn.button('loading').html('Wait..')
                },
                success: function (data) {
                    $btn.button('reset');
                    var html = $(data).filter('#lab_more_patient');
                    $(html).modal('show');
                },
                error: function (data) {
                    $btn.button('reset');
                    alert("Sorry something went wrong on server.");
                }
            });

        });

        $(document).on('submit','#search_form',function(e){
            e.preventDefault();
            var $btn = $("#search_btn");
            var obj = $(this);
            var dataToSend = $(this).serialize();
            $.ajax({
                type: 'POST',
                data:dataToSend,
                url: baseurl + "app_admin/lab_more_patient",
                beforeSend: function () {
                    $btn.button('loading').html('Wait..');
                    $("#lab_more_patient").modal("hide");
                },
                success: function (data) {
                    $btn.button('reset');
                    var html = $(data).filter('#lab_more_patient');
                    $(html).modal('show');
                },
                error: function (data) {
                    $btn.button('reset');
                    alert("Sorry something went wrong on server.");
                }
            });

        });





        /* $(document).on('click','.chk',function(e){
            e.preventDefault();
            var ID = $(this).attr("id");
            $('input:checkbox').prop('checked',false);
            setTimeout(function(){
                $(".formChk").hide();
                var appointmentID = $("#"+ID).attr("value");


                $.ajax({
                    url: baseurl+'/app_admin/lab_update_traker_token',
                    data:{appointmentID:appointmentID},
                    type:'POST',
                    success: (result)=>{
                        var result = JSON.parse(result);
                        if(result.status == 1)
                        {
                            $("#"+ID).prop('checked',true);
                            $("#"+ID).siblings('form').show();
                        }
                        else
                        {
                            alert(result.message);
                        }
                    }
                });
            },300);

        }); */

        $(document).on('click','.radioBtn',function(e){
            e.preventDefault();
            var ID = $(this).attr("id");
            var appointmentID = $(this).attr("appointmentID");
            var file_status = $(this).attr("value");
            var appointmentStaffID=$(this).attr("appointmentStaffID");
            var appointmentCustomerID=$(this).attr("appointmentCustomerID");
            var childrenID=$(this).attr("childrenID");
            $.ajax({
                        url: baseurl+'/app_admin/lab_update_file_status',
                        data:{appointmentID:appointmentID,file_status:file_status,appointmentStaffID:appointmentStaffID,appointmentCustomerID:appointmentCustomerID,childrenID:childrenID},
                        type:'POST',
                        success: (result)=>{
                            var result = JSON.parse(result);
                            if(result.status == 1)
                            {
                                $("#"+ID).siblings('input[type="radio"]').removeAttr('checked');
                                $("#"+ID).prop('checked',true);
                                $(".status"+appointmentID).text(file_status);
                            }
                            else
                            {
                                alert(result.message);
                            }
                        }
            });
        });

        $(".channel_tap a").removeClass('active');
        $("#opd_tab").addClass('active');

        $('#example').DataTable({
            paging: false,
            "language": {
                "emptyTable": "No Data Found Related To Search"
            }
        });

        $(".from_date, .to_date").datepicker({clearBtn:true,format: 'dd/mm/yyyy',autoclose:true,orientation: "bottom auto",endDate: new Date()});

        $("head > title").text('opd');
        $(document).on('click',".row_box",function(){
            var url = $(this).attr('url');
            window.open(url, '_blank');
        });


        $(document).on("click",".files_btn",function(){
            var data = $(this).attr('data');
            data = JSON.parse(data);

            var htmlToShow = '<div class="row">';
            if(data.length > 0)
            {
                htmlToShow += '<div class="dashboard_icon_li">';
                data.forEach(function(item, index){


                        var file_type = item.file_type;
                        var file_path = item.file_path;
                        var type = item.file_type;

                        htmlToShow += '<div class="col-sm-3 row_box" url="'+file_path+'">';

                                    htmlToShow += '<div class="file_box_container">';
                                    htmlToShow += '<div class="file_label">'+file_type+'</div>';
                                    var string ="";
                                    if(file_type=="IMAGE"){
                                        string = "background-image :url('"+file_path+"')";
                                    }
                                    htmlToShow += '<div class="image_box" style="'+string+'" >';

                                    if(file_type=="VIDEO"){
                                        var file_ext = file_pathfilename.split('.').pop();

                                        htmlToShow += '<video class="video">';
                                        htmlToShow += '<source src="'+file_path+'" type="video/'+file_ext+'">';
                                        htmlToShow += 'Your browser does not support the video tag.';
                                        htmlToShow += '</video>';


                                    }
                                    else if(file_type=="AUDIO"){
                                        htmlToShow += '<div class="icon">';
                                        htmlToShow += '<i class="fa fa-music"></i>';
                                        htmlToShow += '</div>';
                                    }
                                    else if(file_type=="PDF"){
                                        htmlToShow += '<div class="icon">';
                                        htmlToShow += '<i class="fa fa-file-pdf-o"></i>';
                                        htmlToShow += '</div>';
                                    }
                                    else if(file_type=="DOCUMENT" || file_type=="OTHER"){
                                        htmlToShow += '<div class="icon">';
                                        htmlToShow += '<i class="fa fa-file-o"></i>';
                                        htmlToShow += '</div>';
                                    }



                                    htmlToShow += '</div>';

                                    htmlToShow += '</div>';


                    htmlToShow += '</div>';

                });

                htmlToShow += '</div>';
            }
            else
            {
                htmlToShow += '<div class="no_data">'+
                '<h2>No file found</h2>'
                +'</div>';
            }


            htmlToShow += '</div>';

            $(".modalData").html(htmlToShow);
            $("#file_list_container").modal('show');
        });

    });
</script>
<script>
    $(document).on("click","#add_new_patient_btn",function(){
        $("#lab_more_patient").modal("hide");
        $("#myModalAddPatient").modal("show");
        setTimeout(function(){ $("#focusInput").focus(); },1500);
    });

    $(document).on("input","#ageYear,#ageMonth,#ageDay",function(e){
        var year = $("#ageYear").val();
        var month = $("#ageMonth").val();
        var day = $("#ageDay").val();
        var ageStr = "";
        if(year > 0)
        {
            ageStr += year+"Years ";
        }
        if(month > 0)
        {
            ageStr += month+"Months ";
        }
        if(day > 0)
        {
            ageStr += day+"Days";
        }
        $("#AppointmentCustomerAge").val(ageStr);
    });

    function getMorePatient(){
        $.ajax({
            type: 'POST',
            url: baseurl + "app_admin/lab_more_patient",
            beforeSend: function () {

            },
            success: function (data) {

                var html = $(data).filter('#lab_more_patient');
                $(html).modal('show');
            },
            error: function (data) {

                alert("Sorry something went wrong on server.");
            }
        });
    }

    $(document).on("submit","#formAddNewPatient",function(e){
        e.preventDefault();
        var data = $( this ).serialize();
        var $btn = $("#submitAddPatientBtn");
        $("#lab_more_patient").modal("hide");

        $.ajax({
            type:'POST',
            url: baseurl+"app_admin/add_patient_before_receipt",
            data:data,
            beforeSend:function(){
                $btn.attr('disabled',true);
            },
            success:function(data){
                $btn.attr('disabled',false);
                var response = JSON.parse(data);
                if(response.status == 1)
                {
                    $('#formAddNewPatient')[0].reset();
                    $("#myModalAddPatient").modal("hide");
                    getMorePatient();
                }
                else
                {
                    alert("Sorry something went wrong on server.");
                }


            },
            error: function(data){
                $btn.attr('disabled',false);
                alert("Sorry something went wrong on server.");
            }
        });
    });

    $("#AppointmentCustomerDob").datepicker({clearBtn:true,dateFormat: 'dd/mm/yy',autoclose:true,orientation: "bottom auto"});

    $("#AppointmentCustomerDob").mask("99/99/9999", {placeholder: 'dd/mm/yyyy'});

    function addMagicsuggest(html){
        var msc = $(html).find(".country").magicSuggest({
            allowFreeEntries:false,
            allowDuplicates:false,
            data:<?php echo json_encode($country_list,true); ?>,
            maxDropHeight: 345,
            maxSelection: 1,
            required: false,
            noSuggestionText: '',
            useTabKey: true,
            selectFirst :true
        });

        var ms = $(html).find(".state").magicSuggest({
            allowFreeEntries:false,
            allowDuplicates:false,
            maxDropHeight: 345,
            maxSelection: 1,
            required: false,
            noSuggestionText: '',
            useTabKey: true,
            selectFirst :true
        });

        var mscity = $(html).find(".city").magicSuggest({
            allowFreeEntries:true,
            allowDuplicates:false,
            maxDropHeight: 345,
            maxSelection: 1,
            required: false,
            noSuggestionText: '',
            useTabKey: true,
            selectFirst :true
        });

        $(msc).on('selectionchange', function(e,m){
            var $this = this;
            var IdArr = this.getSelection();
            if(IdArr[0])
            {
                var country_id =IdArr[0].id;
                if(country_id){
                    $.ajax({
                        type:'POST',
                        url: baseurl+"app_admin/get_state_list_json",
                        data:{country_id:country_id},
                        beforeSend:function(){

                            //$('.state_spin').show();
                            $($($this)[0]['container'][0]).parents(".col-sm-2").siblings(".col-sm-2").find(".state_spin").show();
                            $('.city, .state').attr('disabled',true).html('');
                        },
                        success:function(data){
                            $($($this)[0]['container'][0]).parents(".col-sm-2").siblings(".col-sm-2").find(".state_spin").hide();
                            ms.setData(JSON.parse(data));
                            $('.city, .state').attr('disabled',false);
                        },
                        error: function(data){
                        }
                    });
                }
            }

        });

        $(ms).on('selectionchange', function(e,m) {
            var $this = this;
            var IdArr = this.getSelection();
            if (IdArr[0]) {
                var state_id =IdArr[0].id;
                if(state_id){
                    $.ajax({
                        type:'POST',
                        url: baseurl+"app_admin/get_city_list_json",
                        data:{state_id:state_id},
                        beforeSend:function(){
                            $($($this)[0]['container'][0]).parents(".col-sm-2").siblings(".col-sm-2").find(".city_spin").show();
                            $('.city').attr('disabled',true).html('');
                        },
                        success:function(data){
                            $($($this)[0]['container'][0]).parents(".col-sm-2").siblings(".col-sm-2").find(".city_spin").hide();
                            mscity.setData(JSON.parse(data));
                        },
                        error: function(data){
                        }
                    });
                }
            }
        });
    }

    addMagicsuggest($(document));
    $(document).on('show.bs.modal', '#upload_file', function () {
        $("#lab_more_patient").modal("hide");
    });
    $(document).on('show.bs.modal', '#file_list_container', function () {
        $("#lab_more_patient").modal("hide");
    });
</script>