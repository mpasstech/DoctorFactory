<ul class="top_detail_box_ul">
    <li style="width: 60%;">
        <label>Name :</label>
        <span class="top_name_lbl"><?php echo $data['general_info']['name']; ?></span>
    </li>
    <li style="width: 20%;">
        <label>Age :</label>
        <span class="top_age_lbl"><?php echo $data['general_info']['age']; ?></span>
    </li>
    <li style="width: 20%;">
        <label>Gender :</label>
        <span class="top_gender_lbl"><?php echo !empty($data['general_info']['gender'])?$data['general_info']['gender']:'N/A'; ?></span>
    </li>
</ul>
<div class="detail_inner_box">
    <p class="detail_heading" style="   border-bottom: 1px solid;">Vitals Details</p>
    <ul class="vital_box_ul">
        <?php foreach($data['vitals'] as $key => $value){  $image_name = strtolower(str_replace(array(" ","."),"",$value['name'])).".png"  ?>
        <li>

          <?php  $string = !empty(base64_decode($value['value']))?base64_decode($value['value']):'N/A';      ?>
            <?php if(strlen($string) > 10){ ?>
                <button class="unit_value"  role="button"  data-toggle="popover" data-placement="left" data-trigger="focus" data-html="true" title="<?php echo ($value['name']); ?>" data-content="<?php echo $string?>" > <?php echo mb_strimwidth($string, 0, 10, '...') ?></button>
            <?php }else{ ?>
                 <label class="unit_value"> <?php echo $string; ?> </label>
            <?php } ?>
            <img  src="<?php echo $this->AppAdmin->beforeLodeImage(); ?>" data-src="<?php echo Router::url("/web_prescription/$image_name",true); ?>" class="icon_box" />
            <span class="unit"><?php echo ($value['name']); ?></span>
            <span class="unit"><?php echo !empty($value['unit'])?"(".$value['unit'].")":""; ?></span>
        </li>
        <?php } ?>
    </ul>

    <div class="detail_form_box">
        <p class="detail_heading">Patient Details</p>
        <div style="float: left;display: block;width: 100%;">
            <div class="input_box genral_info_input" style="width: 70%;">
                <div class="short_box">
                    <label class="" >Patient Name</label>
                    <input class="" type="text" id="gen_patient_name" value="<?php echo $data['general_info']['name']; ?>">
                </div>
                <div class="short_box">
                    <label class="" >Patient Age</label>
                    <input class="" type="text" id="gen_patient_age" value="<?php echo ($data['general_info']['age'] !='N/A')?$data['general_info']['age']:''; ?>">
                </div>

                <div class="short_box">
                    <label class="" >Patient Gender</label>
                    <select id="gen_patient_gender">
                        <option value="" ?>Select Gender</option>
                        <option value="MALE" <?php echo ($data['general_info']['gender']=='MALE')?'selected':''; ?>>Male</option>
                        <option value="FEMALE" <?php echo ($data['general_info']['gender']=='FEMALE')?'selected':''; ?>>Female</option>
                    </select>

                </div>

                <label>Medical History</label>
                <input type="text" id="gen_medical_history_input" value="<?php echo $data['general_info']['medical_history']; ?>">
                <label>Patient Address</label>
                <input type="text" id="gen_patient_address" value="<?php echo $data['general_info']['address']; ?>">
                <label>Date Of Birth</label>
                <input type="text" id="gen_dob" value="<?php echo $data['general_info']['dob']; ?>">

                <label>Parents Mobile</label>
                <input style="width: 80%; float: left;" type="text" id="gen_parent_mobile" value="<?php echo $data['general_info']['parents_mobile']; ?>">
                <button style="width: 20%; float: left; margin: 0px 0px;" type="button" id="update_patient_btn">Save</button>


                <ul class="main_buttons">
                    <li  id="prescripion_btn">
                        <img   src="<?php echo $this->AppAdmin->beforeLodeImage(); ?>" data-src="<?php echo Router::url('/web_prescription/prescription.png',true); ?>">
                        <label>Prescription</label>
                    </li>
                    <li  id="history_btn" data-url="<?php echo $data['general_info']['history_url']; ?>" >
                        <img  src="<?php echo $this->AppAdmin->beforeLodeImage(); ?>" data-src="<?php echo Router::url('/web_prescription/history.png',true); ?>">
                        <label>History</label>
                    </li>
                    <li  id="invoice_btn">
                        <img   src="<?php echo $this->AppAdmin->beforeLodeImage(); ?>" data-src="<?php echo Router::url('/web_prescription/invoice.png',true); ?>">
                        <label>Invoice</label>
                    </li>
                    <li  id="folder_list_btn">
                        <img   src="<?php echo $this->AppAdmin->beforeLodeImage(); ?>" data-src="<?php echo Router::url('/web_prescription/folder.png',true); ?>">
                        <label>Folder</label>
                    </li>

                    <li  id="show_certificate_btn">
                        <img  src="<?php echo $this->AppAdmin->beforeLodeImage(); ?>" data-src="<?php echo Router::url('/web_prescription/certificate.png',true); ?>">
                        <label>Certificate</label>
                    </li>
                </ul>

            </div>
            <div class="input_box last_prescription" style="width: 29%; float: left;">
                <label class="last_prescription_lbl">Last Prescription Preview</label>
                <div class="prev_image_container">
                    <?php if(!empty($data['general_info']['last_prescription'])){ ?>
                        <img  class="vew_last_prescription" style="width: 100%;" src="<?php echo $this->AppAdmin->beforeLodeImage(); ?>" data-src="<?php echo $data['general_info']['last_prescription'];?>" alt="Image">
                    <?php }else{ ?>
                        <img  style=" width: 100%;height: 100%;" src="<?php echo $this->AppAdmin->beforeLodeImage(); ?>" data-src="<?php echo Router::url('/img/web_prescription/rx_symbol_placholder.png',true); ?>">
                    <?php } ?>
                </div>

            </div>
        </div>

    </div>



</div>
<style>




    button.unit_value{
        padding: 0px 2px;
        border: 0;
        color: #000;
        font-weight: 600;
        outline: none !important;
        width: 100%;
    }

    .popover-content, .popover-title{
        color: #000;
    }


    #show_image_dialog .modal.fade .modal-dialog {
        -webkit-transform: scale(0.1);
        -moz-transform: scale(0.1);
        -ms-transform: scale(0.1);
        transform: scale(0.1);
        top: 300px;
        opacity: 0;
        -webkit-transition: all 0.3s;
        -moz-transition: all 0.3s;
        transition: all 0.3s;
        height: 900px;
    }
    #show_image_dialog .modal.fade.in .modal-dialog {
        -webkit-transform: scale(1);
        -moz-transform: scale(1);
        -ms-transform: scale(1);
        transform: scale(1);
        -webkit-transform: translate3d(0, -300px, 0);
        transform: translate3d(0, -300px, 0);
        opacity: 1;
    }
    #show_image_dialog .modal-body{
        padding: 0px;
    }
    #show_image_dialog .modal-content{

    }
    #show_image_dialog .modal-body img{
        width: 100%;
        overflow-y: scroll;
        height: 100%;
    }

    .main_buttons li{
        cursor: pointer;
    }
    .width_import{
        width: 100% !important;
        margin: 0px !important;
        padding: 0px !important;

    }
    #showPatientHistory .close{
        position: absolute;
        background: #fff;
        width: 30px;
        height: 30px;
        z-index: 999999999;
        right: -12px;
        color: #000;
        opacity: 1;
        border: 1px solid #fff;
        border-radius: 48px;
        padding: 0px 0px;
        top: -14px;
    }

    ::-webkit-scrollbar {
        width: 5px;
    }

    ::-webkit-scrollbar-track {
        -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
        -moz-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
    }

    ::-webkit-scrollbar-thumb {
        background-color: darkgrey;
        outline: 1px solid slategrey;
    }


</style>
<script>
    $(function () {

        $('[data-toggle="popover"]').popover();


        $("#patient_detail_object").data('key','<?php echo json_encode($data); ?>');



        $(document).off('click','.vew_last_prescription');
        $(document).on('click','.vew_last_prescription',function(){
            var url = $(this).attr('src');
            var html = "<img src="+url+">";
            var patient_data = $("#patient_detail_object").data('key');
            patient_data = JSON.parse(patient_data);
            console.log(patient_data);
            var prescription_html = patient_data.general_info.prescription_html;
            if(prescription_html){
                html = atob(prescription_html);
            }
            html =  '<div class="modal fade" id="show_image_dialog" role="dialog" style="overflow: scroll !important;"><div class="modal-dialog modal-md" style="width: 210mm; height: auto;"><div class="modal-content" style="float: left;"><button type="button" class="close" data-dismiss="modal">&times;</button><div class="modal-body">'+html+'</div></div></div></div>';
            $(html).filter("#show_image_dialog").modal("show");
        });

        $(document).off('click','#invoice_btn');
        $(document).on('click','#invoice_btn',function(){
            var $btn = $(this).find('label');
            var patient_data = $("#patient_detail_object").data('key');
            patient_data = JSON.parse(patient_data);
            var patient_id = patient_data.general_info.patient_id;
            var patient_type = btoa(patient_data.general_info.patient_type);
            $.ajax({
                url: "<?php echo Router::url('/prescription/payment_modal',true);?>",
                type:'POST',
                data:{pi:btoa(patient_id),pt:patient_type},
                beforeSend:function(){
                    $($btn).button({loadingText: 'Wait...'}).button('loading');
                },
                success: function(result){
                    $btn.button('reset');
                    var html = $(result).filter('#paymentModal');
                    $(html).modal('show');
                },error:function () {
                    $btn.button('reset');
                }
            });
        });

        $(document).off('click','#history_btn');
        $(document).on('click','#history_btn',function(){
            var $btn = $(this).find('label');
            var url = $(this).attr('data-url');
            var iframe = '<iframe id="ifram" width="100%" height="600px;" src='+url+'></iframe>';
            var  html =  '<div class="modal fade" tabindex="-1" id="showPatientHistory" role="dialog" style="overflow: scroll !important;"><div class="modal-dialog modal-md"><div class="modal-content"><button type="button" class="close" data-dismiss="modal">&times;</button><div style="padding:0px;" class="modal-body">'+iframe+'</div></div></div></div>';
            $(html).filter("#showPatientHistory").modal('show');

        });


        $(document).off('click','#folder_list_btn');
        $(document).on('click','#folder_list_btn',function(){
            var $btn = $(this).find('label');
            var patient_data = $("#patient_detail_object").data('key');
            patient_data = JSON.parse(patient_data);
            var folder_id = btoa(patient_data.general_info.folder_id);
            $.ajax({
                url: "<?php echo Router::url('/prescription/prescription_list_modal',true);?>",
                type:'POST',
                data:{fi:folder_id},
                beforeSend:function(){
                    $($btn).button({loadingText: 'Wait...'}).button('loading');
                },
                success: function(result){
                    $btn.button('reset');
                    var html = $(result).filter('#prescriptionListModal');
                    $(html).modal('show');
                },error:function () {
                    $btn.button('reset');
                }
            });

        });



        $(document).off('click','#show_certificate_btn');
        $(document).on('click','#show_certificate_btn',function(){
            var $btn = $(this).find('label');

            $.ajax({
                url: "<?php echo Router::url('/prescription/load_certificate',true);?>",
                type:'POST',

                beforeSend:function(){
                    $($btn).button({loadingText: 'Wait...'}).button('loading');
                },
                success: function(result){
                    $btn.button('reset');
                    var html = $(result).filter('#loadCertificateModal');
                    $(html).modal('show');
                },error:function () {
                    $btn.button('reset');
                }
            });

        });



        $('#gen_dob').datepicker({
            format: 'dd-mm-yyyy',
            setDate: new Date(),
            autoclose:true
        });


            $(document).off('click','#update_patient_btn');
            $(document).on('click','#update_patient_btn',function(){
                var name  = $("#gen_patient_name").val();
                var age  = $("#gen_patient_age").val();
                var gender  = $("#gen_patient_gender").val();
                var dob  = $("#gen_dob").val();
                var history  = $("#gen_medical_history_input").val();
                var address  = $("#gen_patient_address").val();
                var parent_mobile  = $("#gen_parent_mobile").val();
                var rf  = $(".patient_list_ul").attr('data-rf');
                var pt  = "<?php echo $patient_type; ?>";
                var pi  = "<?php echo base64_encode($patient_id); ?>";
                var pm  = "<?php echo $data['general_info']['mobile']; ?>";
                var $btn = $(this);
                $.ajax({
                    url: "<?php echo Router::url('/prescription/update_patient_detail',true);?>",
                    type:'POST',
                    data:{rf:rf,pm:pm,dob:dob,pt:pt,pi:pi,name:name,age:age,gender:gender,history:history,address:address,parent_mobile:parent_mobile},
                    beforeSend:function(){
                        $btn.button('loading').text('Wait..');
                    },
                    success: function(response){
                        $btn.button('reset');
                        response = JSON.parse(response);
                        if(response.status == 1){
                            flash("Update",response.message, "success",'center');
                            var html = $(response.data.update).filter('.patient_list_li').html();
                            $(".patient_list_li.active_li").html(html);
                            $(".top_name_lbl").html(name);
                            $(".top_age_lbl").html(age);
                            $(".top_gender_lbl").html(gender);
                        }else{
                            flash("Update",response.message, "warning",'center');
                        }
                    },error:function () {
                        $btn.button('reset');
                        flash("Update","Sorry something went wrong on server.", "danger",'center');
                    }
                });
            });

            $(document).off('click','#prescripion_btn');
            $(document).on('click','#prescripion_btn',function(){
                var pt  = "<?php echo $patient_type; ?>";
                var pi  = "<?php echo base64_encode($patient_id); ?>";
                var ai  = btoa($("#top_address_drp").val());

                var patient_data = $("#patient_detail_object").data('key');
                patient_data = JSON.parse(patient_data);
                var appointment_id = btoa(patient_data.general_info.appointment_id);
                var $btn = $(this).find('label');
                $.ajax({
                url: "<?php echo Router::url('/prescription/prescription_window',true);?>",
                type:'POST',
                cache:true,
                data:{pt:pt,pi:pi,ai:ai,app_id:appointment_id},
                beforeSend:function(){
                    $btn.button('loading').text('Wait..');
                },
                success: function(response){
                    $btn.button('reset');
                    $("#main_window").hide();
                    $("#prescription_window").html(response).show();



                },error:function () {
                    $btn.button('reset');
                    flash("Update","Sorry something went wrong on server.", "danger",'center');
                }
            });
        });






        $.each($(".column_2 img"), function(){
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

    });
</script>



