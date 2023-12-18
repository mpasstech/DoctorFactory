$(document).ready(function(){

    var db;
    var app_id = $("#body").attr('data-ti');
    var dbname = "patientDB_"+app_id;
    var table_name = "patient_"+app_id;


    $(document).on('click','.tab_btn_div button',function(e){
        var ID = $(this).attr('data-id');

        if(ID=='android_app' || ID=='web_app'){
            return false;
        }

        if(ID=='track_token'){
            var trans = db.transaction(table_name, IDBTransaction.READ_ONLY);
            var objectStore = db.transaction(table_name).objectStore(table_name);
            objectStore.openCursor().onsuccess = function(event) {
                var cursor = event.target.result;
                if (cursor) {
                    var patient_data = cursor.value;
                }else{
                    var ti  = btoa($('body').attr('data-ti'));
                    var dialog = $.confirm({
                        title: 'Track Token',
                        content:"<label>Enter your 10 digit mobile number</label><input type='number' class='form-control' name='mobile' id='mobile_num'>",
                        type: 'red',
                        buttons: {
                            ok: {
                                text: "Show Tracker",
                                btnClass: 'btn-primary confirm_btn',
                                keys: ['enter'],
                                name:"ok",
                                action: function(e){
                                    var $btn2 = $(".confirm_btn_cancel");
                                    var mobile = $("#mobile_num").val();
                                    if(mobile.length==10){
                                        send_tracker_ajax(0,ti,$("#mobile_num").val());
                                        dialog.close();
                                    }else{
                                        return false;
                                    }
                                }
                            },
                            cancel: function(){

                            }
                        }
                    });
                }
            };
            return  false;
        }
        if(ID=='walkin_token'){

            var src = $("#walkin_token").attr('data-src');
            var string = "<h4 id='loading_h3' style='text-align:center;width:100%;'>Please wait..</h4><iframe src="+src+" style='width:100%;height:550px;;'></iframe>";
            var dialog = $.confirm({
                title: '',
                content: string,
                columnClass:'dialog_container',
                type: 'success',
                buttons: {
                    ok: {
                        text: "Go To Dashboard",
                        btnClass: 'btn-primary',
                        keys: ['enter'],
                        name:"ok",
                        action: function(e){
                            dialog.close();
                            return false;
                        }
                    }
                },onOpen:function(){
                    this.$jconfirmBoxContainer.find(".jconfirm-box").closest('.container').css("padding","0px");
                   // this.$jconfirmBoxContainer.find(".jconfirm-content-pane").css("min-height","600px");
                    this.$jconfirmBoxContainer.find(".jconfirm-buttons").addClass('back_btn_position');
                },onContentReady: function () {
                    this.$jconfirmBoxContainer.find("iframe").on("load", function() {
                        $("#loading_h3").hide();
                    });
                }
            });
            return  false;
        }

        $(".tab-content, .add_container").hide();
        $("#"+ID).show();
        $(".tab_btn_div, .table_bottom").hide();
        $(".go_to_dash").show();
        $("#top_sub_title").text($(this).attr('data-title'));

        if(ID=='my_appointment'){
            appointment_offset =0;
            load_my_appointment();
        }

        if(ID=='doctor_apps'){
            $(".go_to_dash").trigger('click');
            window.open(
                'https://www.qutot.com',
                '_self'
            );

        }


        if(ID=='appointment'){
            if($("#addressSlider li").length == 1 ){
                $('#addressSlider li:first').trigger('click');
                $("#back_to_location").hide();
            }
        }

        if(ID=='medical_record'){
            $(".folder_drp_container, #load_more_record").hide();
            var trans = db.transaction(table_name, IDBTransaction.READ_ONLY);
            var objectStore = db.transaction(table_name).objectStore(table_name);
            objectStore.openCursor().onsuccess = function(event) {
                var cursor = event.target.result;
                if (cursor) {
                    var app_id = $("#body").attr('data-ti');
                    var patient_data = cursor.value;
                    if(patient_data){
                        $("body").attr('data-m',btoa(patient_data.mobile));
                        refreshRecordList(0);
                    }
                }
            };
        }

        if(ID=='patient_info'){
            var patient_list_string = "";
            var trans = db.transaction(table_name, IDBTransaction.READ_ONLY);
            var objectStore = db.transaction(table_name).objectStore(table_name);
            var countRequest = objectStore.count();
            countRequest.onsuccess = function() {
                var total_records = countRequest.result;
                var index=0;
                if(total_records > 0){
                    patient_list_string = "<ul>";
                }
                objectStore.openCursor().onsuccess = function(event) {
                    var cursor = event.target.result;
                    if (cursor) {
                        value = cursor.value;
                        patient_list_string += "<li>"+value.patient_name+"<a class='load_info btn btn-xs btn-info'  data-id='"+btoa(value.patient_id)+"'  href='javascript:void(0)'> View Info</a></li>";
                        index++;
                        if(total_records!=index){
                            cursor.continue();
                        }
                    }
                    if(total_records==index){
                        patient_list_string += "</ul>";
                        $("#patient_list").html(patient_list_string);

                    }
                };
            }
        }




    });




    $('.go_to_dash').click(function(){
        $(".go_to_dash, .tab-content").hide();
        $(".tab_btn_div, .add_container, .table_bottom").show();
        $("#top_sub_title").text('Dashboard');
    });



    $(document).on('click','.date_box',function(){
        $('.date_box').removeClass("selected_date");
        $(this).addClass('selected_date');
        $(this).closest("ul").hide();
        $(".append_slots").html("");
        $("#slot_box").show();
        load_doctor_slot();

    });


    function load_doctor_slot(){
        //calculateSlotBoxHeight();
        var address_id = $('#addressSlider .selected_address').attr('data-id');
        var row = $('#row_content').data('key');
        var booking_date = $('.date_box.selected_date').attr('data-date');
        var data = {row:row,ai:address_id,bd:booking_date};
        $.ajax({
            type:'POST',
            url: baseUrl+"qutot/load_doctor_time_slot",
            data:data,
            beforeSend:function(){

                $('.doctor_loader').show();
            },
            success:function(data){
                $('.doctor_loader').hide();

                $(".append_slots").html(data);
            },
            error: function(data){
                $('.doctor_loader').hide();
                alert("Sorry something went wrong on server.");
            }
        });
    }





    $(".carousel").on("touchstart", function(event){
        var xClick = event.originalEvent.touches[0].pageX;
        $(this).one("touchmove", function(event){
            var xMove = event.originalEvent.touches[0].pageX;
            if( Math.floor(xClick - xMove) > 5 ){
                $(this).carousel('next');
            }
            else if( Math.floor(xClick - xMove) < -5 ){
                $(this).carousel('prev');
            }
        });
        $(".carousel").on("touchend", function(){
            $(this).off("touchmove");
        });
    });


    function visibleBookButton(){
        $('#swal-otp, #swal-mobile, #swal-name').val('');
        $('.contain').removeAttr('row-id');
        setTimeout(function () {
            openBookDialog('','',false,'NO','');
        },100);
    }

    $(document).on('click','#emergency_btn',function(){
        var fee = $(this).attr('data-fee');
        var dialog = $.confirm({
            title: 'Emergency Appointment',
            content: 'The charges for emergency appointment will be Rs '+fee+'/-',
            type: 'red',

            buttons: {
                ok: {
                    text: "Next",
                    btnClass: 'btn-primary confirm_btn_cancel',
                    keys: ['enter'],
                    name:"ok",
                    action: function(e){
                        dialog.close();

                        $hours = "<option value=''>Select Hour</option>";
                        $mintures = "<option value=''>Select Minutes</option>";
                        for(i=1;i<=23;i++){
                            $hours += "<option value='"+i+"'>"+i+"</option>";
                        }
                        for(i=0;i<=59;i++){
                            $mintures += "<option value='"+i+"'>"+i+"</option>";
                        }
                        var jc = $.confirm({
                            title: 'Select Approx Time',
                            columnClass:'box_container',
                            content: '' +
                                '<form action="" class="formName">' +
                                '<div class="form-group"><label>Select Hours</label>' +
                                '<select id="select_hours" class="form-control">'+$hours+'</select></div>' +
                                '<div class="form-group"><label>Select Minutes</label>' +
                                '<select id="select_minutes" class="form-control">'+$mintures+'</select>' +
                                '</div>' +
                                '</form>',
                            buttons: {
                                next:{
                                    text: 'Next',
                                    btnClass: 'btn-blue emr_slot_time_btn',
                                    action: function (e) {
                                        var hours = this.$content.find('#select_hours').val();
                                        var minutes = this.$content.find('#select_minutes').val();
                                        if(hours != '' && minutes != ''){
                                            var time = hours+":"+minutes;
                                            jc.close();
                                            openBookDialog('','',false,'YES',time);
                                        }else{
                                            $.alert('Please select hours or minutes');
                                        }

                                        return false;
                                    }
                                },
                                cancel: {
                                    btnClass: 'emr_not_slot_time_btn',
                                    text: 'Instant Appointment', // With spaces and symbols
                                    action: function () {
                                        openBookDialog('','',false,'YES','');
                                    }
                                }
                            },
                            onContentReady: function () {
                                $(".emr_not_slot_time_btn").closest(".jconfirm-buttons").append("<span id='or_span'>OR</span>");
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



    function calculateSlotBoxHeight(){
        var all = $("#slot_box").height();
        var h1 = $("#slot_box .inner_label_header").height();
        var h2 = $("#slot_box .alert_span").height();
        var h3 = $("#slot_box .emergency_row").height();
        var total = all - (h1+h2+h3);
        $("#slot_box .appoinment-slot-holder").height(total);
    }


    function scrollToPosition(element, flag) {
        var ele = $('.wrap').scrollTop();
        var move  = 0;
        if(flag =='top'){
            move = ele +$(element).height() * 5;
        }else{
            move = ele - $(element).height() * 5
            if(move < 0){
                move = 0;
            }

        }
        console.log(move);
        $('.wrap').animate({
            scrollTop: move
        }, 250);


    }

    //Create an Array of posts
    var date_box = $('.date_box');
    var position = 0; //Start Position
    var next = $('#next');
    var prev = $('#prev');

    next.click(function(evt) {
        scrollToPosition(date_box[position += 1],'top');

    });

    prev.click(function(evt) {

        scrollToPosition(date_box[position -= 1],'prev');

    });

    var box_height = $('.appoinment-slot-holder').height();
    if(box_height > 400){
        $(".wrap").height($('.appoinment-slot-holder').height() - 150);
    }


    $(document).on('click','.appointment-slot',function(){
        if($(this).hasClass('available')){
            $(".append_slots").find('.selected-slot').removeClass("selected-slot");
            $(this).addClass('selected-slot');
            visibleBookButton();

        }
    });




    function showForm(okBtn,swal){
        console.log('res');
        var trans = db.transaction(table_name, IDBTransaction.READ_ONLY);
        var objectStore = db.transaction(table_name).objectStore(table_name);
        var countRequest = objectStore.count();
        countRequest.onsuccess = function() {
            $(".recent_box, .second_box, .first_box, .add_new_btn").hide();
            $(".back_btn").attr('data-hide','first_box');
            $(".back_btn").attr('data-show','recent_box');
            $(".first_box, .back_btn").show();
            var total_records = countRequest.result;
            if(total_records==1){
                $('.back_btn').hide();
                $(".add_new_btn").show();
            }
            $(okBtn).text("Next");
            $(okBtn).attr("verified",'no');
            swal.enableConfirmButton();
            return false;
        };
    }
    var t_id =0;


    function setCookie(cname, cvalue, exdays) {
        var d = new Date();
        d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
        var expires = "expires="+d.toUTCString();
        document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
    }


    var paymentWindow=null;
    function openBookDialog(name,mobile,show_otp,emergency_appointment,emergency_time){

        var patient_list =[];
        var address= $('#addressSlider .selected_address').find('.address_text').text();
        var address_id= $('#addressSlider .selected_address').attr('data-id');
        var doctor_name = $('.doctor-name').text();
        var date = $('.date_box.selected_date').attr('data-date');
        var show_date = $('.date_box.selected_date').attr('data-show-date');
        var token = $('.selected-slot .appointment-token').text();
        var time = $('.selected-slot .appointment-time').text();

        var online_appointment = $('.main_container').attr('data-online');
        var offline_appointment = $('.main_container').attr('data-offline');
        var audio_appointment = $('.main_container').attr('data-audio');
        var chat_appointment = $('.main_container').attr('data-chat');

        var showConfirmButton = false;
        if(app_category=='DOCTOR_OPDS' || app_category=='HOSPITAL'){
            var consulting_type_string = '<div class="col-12"><h5 class="inner_heading_lbl">Select Consulting Type</h5></div>';
            if(offline_appointment =='YES'){
                showConfirmButton =true;
                consulting_type_string += '<div class="col-12 consulting_box"><label class="consulting_lbl"><input class="input_box" checked="checked" name="consulting_type"  type="radio"  value="OFFLINE"><i class="fa fa-building-o" aria-hidden="true"></i> On Site Visit</label></div>';
            }if(online_appointment =='YES' && emergency_appointment=='NO'){
                showConfirmButton =true;
                consulting_type_string += '<div class="col-12 consulting_box"><label class="consulting_lbl"><input class="input_box" checked="checked" name="consulting_type"  type="radio"  value="VIDEO"><i class="fa fa-video-camera" aria-hidden="true"></i> Video Consultation</label></div>';
            }if(audio_appointment =='YES'  && emergency_appointment=='NO'){
                showConfirmButton =true;
                consulting_type_string += '<div class="col-12 consulting_box"><label class="consulting_lbl"><input class="input_box"  name="consulting_type"  type="radio"  value="AUDIO"><i class="fa fa-file-audio-o" aria-hidden="true"></i> Voice Consultation</label></div>';
            }if(chat_appointment =='YES'  && emergency_appointment=='NO'){
                showConfirmButton =true;
                consulting_type_string += '<div class="col-12 consulting_box"><label class="consulting_lbl"><input class="input_box"  name="consulting_type"  type="radio"  value="CHAT"><i class="fa fa-comments-o" aria-hidden="true"></i> Chat Consultation</label></div>';
            }

        }else{
            offline_appointment ='YES';
            var consulting_type_string = '<div class="col-12"><h5 class="inner_heading_lbl">Select Token Type</h5></div>';
            if(offline_appointment =='YES'){
                showConfirmButton =true;
                consulting_type_string += '<div class="col-12 consulting_box"><label class="consulting_lbl"><input class="input_box" checked="checked" name="consulting_type"  type="radio"  value="OFFLINE"><i class="fa fa-building-o" aria-hidden="true"></i> On Site Visit</label></div>';
            }
        }

        var single_field = $('body').attr('moq-hospital');
        var html_string = '';
        var title =  'Book Token';
        var cancelButtonText =  'Cancel';
        var show_recent = 'none';
        var show_form = 'flex';
        var patient_list_string ='';
        var trans = db.transaction(table_name, IDBTransaction.READ_ONLY);
        var objectStore = db.transaction(table_name).objectStore(table_name);
        var countRequest = objectStore.count();
        countRequest.onsuccess = function() {
            var total_records = countRequest.result;
            var index=0;
            var lbl = 'User';
            if(total_records > 0){
                patient_list_string = "<h5 class='inner_heading_lbl'>Recent "+lbl+" List</h5><div class='recent_scroll'>";
            }

            objectStore.openCursor().onsuccess = function(event) {

                console.log(single_field);
                var cursor = event.target.result;
                if (cursor) {
                    value = cursor.value;
                    patient_list.push(cursor.value);
                    patient_list_string += "<label style='display:block;width:100%;padding:0.5rem 0px;'><input data-index="+index+" style='margin:5px;' type='radio' name='recent_patient' value ='"+value.patient_id+"'>"+value.patient_name+"</label>";
                    show_recent ='block';
                    show_form ='none';
                    index++;
                    if(single_field=='YES'){
                        total_records = index = 1;
                    }else if(total_records!=index){
                        cursor.continue();
                    }
                }

                if(single_field=='YES' || total_records==index){
                    var field_display = (single_field=='YES')?'none':'block';
                    var num_width = (single_field=='YES')?'12':'6';

                    if(total_records > 0){
                        patient_list_string+="</div>";
                    }
                    var dynamic_lbl = "Tell about your health problem  or symptoms";
                    var display_address = "none";
                    var sym_type = 'text';
                    var patient_label = 'User Name';
                    if(app_category=='DOCTOR_OPD' || app_category=='DOCTOR'){
                        display_address='block';
                    }
                    if(showConfirmButton===true){
                        html_string = '<div class="book_dialog_div row"><span class="token_circle"><span>Token</span>'+token+'</span>' +
                            '<div class="detail_section col-12" style="padding:0px;" ><p class="detail_p"><label class="right_date"><i class="fa fa-calendar"></i> '+show_date+' </label><label class="dialog_time_lable"><i class="fa fa-clock-o"></i>'+time+'</label></p><p>'+address+'</p></div>' +
                            '<div class="recent_box row" style="width:100%;float:left;display:'+show_recent+';"> <div class="col-12">'+patient_list_string+'</div></div>' +
                            '<div class="first_box row" style="width:100%;float:left;display:'+show_form+';"><div class="col-12"><h5 class="inner_heading_lbl">Booking Form</h5></div>' +
                            '<div class="col-6" style="display:'+field_display+';"><label>'+patient_label+'</label><input autocomplete="off" required="required" placeholder="" id="swal-name" class="swal2-input"></div>' +
                            '<div class="col-'+num_width+'" ><label>Mobile Number</label><input type="number" maxlength="10" autocomplete="false"  placeholder="" id="swal-mobile" class="swal2-input"></div>' +
                            '<div class="col-2" style="display:'+field_display+';" ><label>Age</label><input   type="number" min="0" max="99" autocomplete="false" placeholder="" id="swal-age" class="swal2-input"></div>' +
                            '<div class="col-4" style="display:'+field_display+';"><label>DOB</label><input data-provide="datepicker" autocomplete="false" placeholder="DD/MM/YYYY"  type="text"  id="swal-dob" class="swal2-input"></div>' +
                            '<div class="col-6" style="display:'+field_display+';"><label>Select Gender</label><select id="swal-gender" class="swal2-input"><option value="MALE">Male</option><option value="FEMALE">Female</option></select></div>' +
                            '<div class="col-12" style="padding:0px;display:'+display_address+';"><label>Address</label><input  type="text" placeholder="" id="swal-address" class="swal2-input"></div>' +
                            '<div class="col-12" style="padding:0px;display:'+display_address+';"><label>'+dynamic_lbl+'</label><input autocomplete="off"  value="" type="'+sym_type+'" placeholder="" id="swal-reason" class="swal2-input"></div>' +
                            '</div> <div class="second_box row" style="display:none;">' +
                            '<div class="box col-12">'+consulting_type_string+'</div><br><div style="display: none; padding:0px;" class="box otp_div col-12"><label>Enter OTP</label><input  value="" type="number" min="4" max="4" placeholder="" id="swal-otp" class="swal2-input"></div></div>' +
                            '<div class="cus-message"></div>' +
                            '</div>';
                    }else{
                        html_string = '<div class="cus-message">Doctor not accepting Walk-In appointment right now.</div>';
                        title =  'Token Booking';
                        cancelButtonText = 'Close';
                    }
                    var swalBox =  swal({
                        title: title,
                        showCancelButton: true,
                        cancelButtonText: cancelButtonText,
                        confirmButtonText: (total_records > 0)?"Next ":'Next',
                        showConfirmButton: showConfirmButton,
                        showLoaderOnConfirm:false,
                        allowOutsideClick: false,
                        enableCancelButton:true,
                        html:html_string,
                        preConfirm: function () {
                            $('.cus-message').html('');
                            var message ='';
                            var gender = $('#swal-gender').val();
                            var okBtn= $('.swal2-confirm');
                            var name = $('#swal-name').val();
                            var mobile = $('#swal-mobile').val();
                            var age = $('#swal-age').val();
                            var dob = $('#swal-dob').val();
                            var address = $('#swal-address').val();
                            var reason = $('#swal-reason').val();
                            var consulting_type = $("[name='consulting_type']:checked").val();
                            var otp_obj = $('#swal-otp');
                            var otp = $(otp_obj).val();
                            var appendTo;
                            var mobileString = new String( mobile );
                            var otpString = new String( otp );
                            var row_id = $('.contain').attr('row-id');
                            if(single_field=='YES' && name==''){
                                name = mobile;
                            }


                            if(name=='' && single_field=='NO'){
                                if(app_category=='TEMPLE'){
                                    message = 'Please enter user name';
                                }else{
                                    message = 'Please enter patient name';
                                }
                                appendTo = $('#swal-name');

                            }else if(mobile==''){
                                message ='Please enter mobile number';
                                appendTo = $('#swal-mobile');

                            }else if(mobileString.length !=10 && single_field=='NO' ){
                                message ='Please enter 10 digit mobile number';
                                appendTo = $('#swal-mobile');

                            }else if(otpString.length !=4  && row_id && single_field=='NO') {
                                message ='Please enter 4 digit OTP';
                                appendTo = otp_obj;

                            }else if(age=='' && dob =='' && single_field=='NO'){
                                message ='Please enter  age or DOB';
                                appendTo = $('#swal-age');
                                showForm(okBtn,swal);
                            }else{

                                $('.swal2-buttonswrapper').find('.swal2-cancel').removeAttr('disabled');

                                if($(okBtn).text()=="Next "){
                                    showForm(okBtn,swal);
                                }else if($(okBtn).text()=="Next"){
                                    $(okBtn).attr("verified",'no');
                                    $(".recent_box, .second_box, .first_box, .add_new_btn").hide();
                                    $(".back_btn").attr('data-hide','second_box');
                                    $(".back_btn").attr('data-show','first_box');
                                    $(".second_box, .back_btn").show();
                                    $(okBtn).text("Genrate OTP");
                                    swal.enableConfirmButton();
                                    var trans = db.transaction(table_name, IDBTransaction.READ_ONLY);
                                    var objectStore = db.transaction(table_name).objectStore(table_name);
                                    objectStore.openCursor().onsuccess = function(event) {
                                        var cursor = event.target.result;
                                        if (cursor) {
                                            patient_data = cursor.value;
                                            if(patient_data.mobile==mobile){
                                                $(okBtn).text("Book Token");
                                                $(okBtn).attr("verified",'yes');
                                            }else{
                                                $(okBtn).attr("verified",'no');
                                            }
                                        }
                                    };

                                }else{
                                    var doctor_id = $('.main_container').attr('data-di');
                                    var tid = $('.main_container').attr('data-ti');
                                    var verified =$('.swal2-confirm').attr("verified");
                                    if(verified=='yes'){
                                        $.ajax({
                                            url: baseUrl+"doctor/verify_and_book_appointment",
                                            data:{et:emergency_time,ea:emergency_appointment,queue_number:token,address:address,pi:selected_patient,verified:verified,age:age,dob:dob,reason:reason, mobile:mobile,row_id:row_id,t:tid,otp:otp,d_id:doctor_id,gender:gender,slot:time,date:date,address_id:address_id,name:name,consulting_type:consulting_type},
                                            type:'POST',
                                            beforeSend:function () {
                                                var text = (verified=='yes')?'Booking...':'Verify OTP...';
                                                $(okBtn).button('loading').html(text);
                                            },
                                            success: function(result){
                                                $(okBtn).button('reset');
                                                // $('.loading-cus-message').html('');
                                                $('.swal2-buttonswrapper').removeClass('swal2-loading');
                                                $('.swal2-buttonswrapper').find('.swal2-confirm').html('Book Token');
                                                $('.swal2-buttonswrapper').find('.swal2-confirm').removeAttr('disabled');
                                                $('.swal2-buttonswrapper').find('.swal2-cancel').removeAttr('disabled');
                                                result = JSON.parse(result);
                                                if (result.status == 1) {
                                                    if(result.data.patient){
                                                        var newList = result.data.patient;
                                                        pastList = patient_list;
                                                        localStorage.setItem('login_mobile',btoa(mobile));
                                                        updateList(newList,pastList);
                                                    }
                                                    if(result.data.convenience_fee > 0)
                                                    {
                                                        var conFee = result.data.convenience_fee;
                                                        var string = result.data.label;
                                                        var appointmentID = result.data.appointment_id;
                                                        var ks = string.split("\n");
                                                        var label = "";
                                                        if(string!=''){
                                                            var string_array = [];
                                                            $.each(ks, function(k,value){
                                                                if(value.trim() != ''){
                                                                    string_array.push(value);
                                                                }
                                                            });
                                                            $.each(string_array, function(index,value){
                                                                var check_index = index+1;
                                                                if(value.trim() != ''){
                                                                    label += "<li>"+value+"</li>";
                                                                }
                                                            });
                                                            label = "<ul class='success_ul' >"+label+"</ul>";
                                                        }
                                                        swal({
                                                            type:'info',
                                                            title: "Pay For Confirm Token ",
                                                            html: label,
                                                            showCancelButton: false,
                                                            confirmButtonText: 'Pay Now',
                                                            customClass:"success-box",
                                                            showConfirmButton: true,
                                                            allowOutsideClick: false
                                                        }).then(function (result) {
                                                            showDashboardIcon();
                                                            paymentWindow = window.open(baseUrl+'booking_convenience/?rf=qutot&&token='+btoa(appointmentID), "_blank");
                                                            //window.location = baseUrl+'booking_convenience/?token='+btoa(appointmentID);
                                                        }).catch(swal.noop);
                                                    }
                                                    else
                                                    {
                                                        swal({
                                                            type:'success',
                                                            title: "Book Token",
                                                            html: result.message,
                                                            showCancelButton: false,
                                                            confirmButtonText: 'Ok',
                                                            customClass:"success-box",
                                                            showConfirmButton: true
                                                        });
                                                    }

                                                    //load_doctor_slot();
                                                }else{

                                                    $('.cus-message').html(result.message);
                                                }



                                            },error:function () {
                                                $(okBtn).button('reset');
                                            }
                                        });
                                    }else{
                                        sendOTP();
                                    }
                                }
                            }


                            if(message!=''){
                                $('.swal2-buttonswrapper').find('.swal2-confirm').removeAttr('disabled');
                                $('.swal2-buttonswrapper').find('.swal2-cancel').removeAttr('disabled');
                                $('.swal2-buttonswrapper').removeClass('swal2-loading');
                                $('.cus-message').html(message);
                            }

                        }

                    }).then(function (result) {
                        var okBtn= $('.swal2-confirm');
                        if($(okBtn).text()!="Next") {
                            swal(JSON.stringify(result))
                        }
                    }).catch(swal.noop);
                    setTimeout(function () {

                        $("#swal-dob").mask("99/99/9999", {placeholder: 'DD/MM/YYYY'});
                        //$("#swal-mobile").mask("9999999999", {placeholder: '9999999999'});
                        if(single_field=='NO'){
                            $(".swal2-buttonswrapper").prepend("<button style='background:rgb(48, 133, 214);display:none;' data-show='first_box' data-hide='recent_box' class='swal2-styled back_btn'>Back</button>");

                        }


                        if(index > 0){
                            var add_new_label = (app_category=='TEMPLE')?'Add New User':'Add New Patient';
                            if(single_field=='NO'){
                                $(".swal2-buttonswrapper").prepend("<button style='background:rgb(48, 133, 214);' class='swal2-styled add_new_btn'>"+add_new_label+"</button>");
                            }

                            $("input[name='recent_patient']:first-child").attr('checked','checked');
                            $("input[name='recent_patient']:first-child").trigger('change');
                            setTimeout(function () {
                                if(total_records==1){

                                    $('.swal2-buttonswrapper').find('.back_btn').hide();
                                    $('.swal2-buttonswrapper').find('.swal2-confirm').trigger('click');

                                }else if(total_records==0){
                                    $('.add_new_btn').hide();
                                    $('.swal2-confirm').text("Next");
                                }
                            },3);




                        }

                    },100);
                    if(show_otp===true){
                        $('.otp_div').show();
                    }else{
                        $('.otp_div').hide();
                    }
                }
            };
        }
    }


    var selected_patient=0;

    $(document).on('change','input[name="recent_patient"]',function(){
        var val = $(this).val();
        var objectStore = db.transaction(table_name).objectStore(table_name);
        objectStore.openCursor().onsuccess = function(event) {
            var cursor = event.target.result;
            if (cursor) {
                patient_data = cursor.value;
                if(val== patient_data.patient_id){
                    selected_patient = btoa(patient_data.patient_id);
                    $("#swal-name").val(patient_data.patient_name);
                    $("#swal-mobile").val(patient_data.mobile);
                    $("#swal-age").val(patient_data.age);
                    $("#swal-gender").val(patient_data.gender);
                    $("#swal-dob").val(patient_data.dob);
                    $("#swal-address").val(patient_data.address);
                }else{
                    cursor.continue();
                }
            }else{
                $(".back_btn").attr('data-hide','recent_box');
                $(".back_btn").attr('data-show','first_box');
            }
        };
    });


    $(document).on('click','.back_btn',function(){
        var obj = $(this);
        var objectStore = db.transaction(table_name).objectStore(table_name);
        var countRequest = objectStore.count();
        countRequest.onsuccess = function() {
            var total_records = countRequest.result;
            $(".recent_box, .second_box, .first_box, .add_new_btn").hide();
            var hide_cls = $(obj).attr('data-hide');
            var show_cls = $(obj).attr('data-show');

            if(show_cls=='recent_box'){
                $(".back_btn").hide();
                $(".add_new_btn").show();
                $('.swal2-confirm').text("Next ");
            }else if(hide_cls=='recent_box'){
                $(".back_btn").attr('data-hide','first_box');
                $(".back_btn").attr('data-show','recent_box');
                $('.swal2-confirm').text("Next");
            }else if(hide_cls=='second_box'){
                $(".swal2-confirm").text("Next");
                $(".back_btn").attr('data-hide','first_box');
                $(".back_btn").attr('data-show','recent_box');
                if(total_records==1){
                    $('.back_btn').hide();
                }
            }
            $("."+hide_cls).hide();
            $("."+show_cls).show();
        }

    });


    $(document).on('input','#swal-name, #swal-age, #swal-gender, #swal-dob, #swal-address, #swal-reason',function(){
        $('.cus-message').html('');
    });

    $(document).on('click','.add_new_btn',function(){
        selected_patient=0;
        $(".recent_box, .second_box, .first_box").hide();
        $(".back_btn, .first_box").show();
        $(".back_btn").attr('data-hide','first_box');
        $(".back_btn").attr('data-show','recent_box');
        $(this).hide();
        $('.swal2-confirm').text("Next");
        $("#swal-name, #swal-age, #swal-gender, #swal-dob, #swal-address, #swal-reason").val('');

        var trans = db.transaction(table_name, IDBTransaction.READ_ONLY);
        var objectStore = db.transaction(table_name).objectStore(table_name);
        objectStore.openCursor().onsuccess = function(event) {
            var cursor = event.target.result;
            if (cursor) {
                patient_data = cursor.value;
                $("#swal-mobile").val(patient_data.mobile);
            }
        };

    });




    $(document).on('click','.resend_otp',function(){
        sendOTP();
    });




    var timer;
    function sendOTP(){

        var okBtn= $('.swal2-confirm');
        var name = $('#swal-name').val();
        var mobile = $('#swal-mobile').val();
        var otp = $('#swal-otp').val();
        t_id = $(".main_container").attr('data-ti');
        clearInterval(timer);
        $.ajax({
            url: baseUrl+"doctor/send_otp",
            data:{mobile:mobile,t:t_id},
            type:'POST',
            beforeSend:function () {
                $(okBtn).button('loading').html('Sending OTP...');

            },
            success: function(result){
                $(okBtn).button('reset');
                $(okBtn).attr("verified",'yes');
                $('.swal2-buttonswrapper').removeClass('swal2-loading');
                $('.swal2-buttonswrapper').find('.swal2-confirm').removeAttr('disabled');
                $('.swal2-buttonswrapper').find('.swal2-confirm').removeAttr('disabled');
                $('.swal2-buttonswrapper').find('.swal2-cancel').removeAttr('disabled');
                result = JSON.parse(result);
                if (result.status == 1) {
                    $('.otp_div').slideDown(200);
                    $('#swal-otp').focus();
                    $('.contain').attr('row-id',result.row_id);
                    var seconds = 30;
                    $('.swal2-buttonswrapper').find('.swal2-confirm').html('Book Token');
                    $('.swal2-buttonswrapper').find('.swal2-confirm').addClass('book-btn');
                    $('#swal-mobile').prop('disabled','disabled');
                    var link ="Sit back & Relax! Till you get OTP on your mobile number <span class='sec'>"+seconds+"</span> seconds.";
                    var resend = "Didn't get OTP yet? <a href='javascript:void(0);' class='resend_otp' >Resend OTP</a>";
                    $('.cus-message').html(link);
                    timer = setInterval(function () {
                        if(seconds > 0){
                            seconds--;
                            $('.sec').html(seconds);
                        }else{
                            $('.cus-message').html(resend);
                            clearInterval(timer);
                        }
                    },1000);
                }else{
                    $(okBtn).button('reset');
                    $('.cus-message').html(result.message);

                }



            },error:function () {

            }
        });
    }

    var blog_offset =0;
    function load_doctor_blog(search_loader){
        var row = $('#row_content').data('key');
        var channel_id = $('.main_container').attr('data-c');
        var search = $('#search-blog-text').val();
        var data = {row:row,c_id:channel_id,offset:blog_offset,search:search};
        var btn = $("#load_more_blog_btn");
        $.ajax({
            type:'POST',
            url: baseUrl+"doctor/load_doctor_blog",
            data:data,
            beforeSend:function(){
                if(search_loader === true){
                    $('#search_blog_btn').find('i').removeClass('fa-search').addClass('fa fa-spinner fa-spin');
                }else{
                    var src = baseUrl+'/images/doctor_web_loader.gif';
                    var htm = '<img src ='+src+'> Loading post';
                    $(btn).html(htm).attr('disabled','disabled');
                }


            },
            success:function(data){

                if(search_loader === true){
                    $('#search_blog_btn').find('i').removeClass('fa-spinner fa-spin').addClass('fa-search');
                }else{
                    $(btn).html('Load More').removeAttr('disabled');

                }
                if(blog_offset == 0){
                    $(".append_blogs").html(data);
                    $(btn).show();
                }else{
                    console.log('htappendml');
                    $(".append_blogs").append(data);
                }
                blog_offset++;
            },
            error: function(data){
                $(btn).html('Load More').removeAttr('disabled');
                alert("Sorry something went wrong on server.");
            }
        });
    }
    $(document).on('click','#load_more_blog_btn',function(){
        load_doctor_blog(false);
    });

    $(document).on('click','#load_more_record',function(){
        var offset = $(this).attr('data-offset');
        refreshRecordList(offset);
    });





    $(document).on('click','.read_more_btn',function(){
        var mod = $(this).attr('data-id');
        $('#'+mod).modal('show');
    });

    $(document).on('click','#search_blog_btn',function(){
        offset = 0;
        load_doctor_blog(true);
    });

    $(document).on('keyup','#search-blog-text',function(){
        offset = 0;
        if($(this).val()==''){
            load_doctor_blog(true);
        }
    });



    /* appointment load function */

    var appointment_offset =0;
    function load_my_appointment(search_loader){
        var trans = db.transaction(table_name, IDBTransaction.READ_ONLY);
        var objectStore = db.transaction(table_name).objectStore(table_name);
        objectStore.openCursor().onsuccess = function(event) {
            var cursor = event.target.result;
            if (cursor) {
                var app_id = $("#body").attr('data-ti');
                var patient_data = cursor.value;
                if(patient_data){
                    var data = {t:patient_data.thinapp_id,offset:appointment_offset,m:patient_data.mobile};
                    var btn = $("#load_more_appointment_btn");
                    $.ajax({
                        type:'POST',
                        url: baseUrl+"doctor/load_my_appointment",
                        data:data,
                        beforeSend:function(){
                            if(search_loader === true){
                                $('#load_more_appointment_btn').find('i').removeClass('fa-search').addClass('fa fa-spinner fa-spin');
                            }else{
                                var src = baseUrl+'/images/doctor_web_loader.gif';
                                var htm = 'Loading List...';
                                $(btn).html(htm).attr('disabled','disabled');
                            }
                        },
                        success:function(data){
                            if(search_loader === true){
                                $('#load_more_appointment_btn').find('i').removeClass('fa-spinner fa-spin').addClass('fa-search');
                            }else{
                                $(btn).html('Load More').removeAttr('disabled');
                            }
                            if(appointment_offset == 0){
                                $(".append_appointments").html(data);
                                $(btn).show();
                            }else{
                                $(".append_appointments").append(data);
                            }
                            appointment_offset++;
                        },
                        error: function(data){
                            $(btn).html('Load More').removeAttr('disabled');
                            alert("Sorry something went wrong on server.");
                        }
                    });

                }
            }
        };

    }

    $(document).on('click','#load_more_appointment_btn',function(){
        load_my_appointment(false);
    });


    $(document).on('click','.list_cancel_btn',function(e){
        var $btn = $(this);
        var id = $(this).attr('data-id');
        var dialog = $.confirm({
            title: 'Cancel Appointment',
            content: 'Are you sure you want to cancel this appointment?',
            type: 'red',
            buttons: {
                ok: {
                    text: "Yes",
                    btnClass: 'btn-primary confirm_btn_cancel',
                    keys: ['enter'],
                    name:"ok",
                    action: function(e){
                        var $btn2 = $(".confirm_btn_cancel");
                        $.ajax({
                            type: 'POST',
                            url: baseUrl  + "homes/cancel_appointment",
                            data: {id: id},
                            beforeSend: function () {
                                ($btn,$btn2).button('loading').html('Wait..')
                            },
                            success: function (data) {
                                var response = JSON.parse(data);
                                ($btn,$btn2).button('reset');
                                if (response.status == 1) {
                                    $($btn).closest(".card").find(".app_list_status").html("Canceled");
                                    $($btn).closest(".card").find(".list_reschedule_btn").remove();
                                    $($btn).remove();
                                    dialog.close();
                                } else {
                                    $.alert(response.message);
                                    dialog.close();
                                }
                            },
                            error: function (data) {
                                ($btn,$btn2).button('reset');
                                alert("Sorry something went wrong on server.");
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

    setTimeout(function () {
        // load_doctor_blog();
    },1);


    $(document).on('submit','.search_btn_form',function(e){
        e.preventDefault();
    });


    $('body').on('hidden.bs.modal', '.modal', function () {
        $('video').trigger('pause');
    });






    $(document).click(function(event){

        var target = event.target;


        if(!$(target).hasClass('popover-content')){
            $('.popover').hide();
        }



        if($(target).attr('data-url')){
            window.location.href = $(this).attr('data-url');
        }


    });

    /* storage */

    function isSupport() {
        return window.indexedDB;
    }

    //prefixes of implementation that we want to test
    window.indexedDB = window.indexedDB || window.mozIndexedDB ||
        window.webkitIndexedDB || window.msIndexedDB;

    //prefixes of window.IDB objects
    window.IDBTransaction = window.IDBTransaction ||
        window.webkitIDBTransaction || window.msIDBTransaction;
    window.IDBKeyRange = window.IDBKeyRange || window.webkitIDBKeyRange ||
        window.msIDBKeyRange

    if (!window.indexedDB) {
        window.alert("Your browser doesn't support a stable version of IndexedDB.")
    }

    var request = window.indexedDB.open(dbname, 1);
    request.onerror = function(event) {
        console.log("error: ");
    };

    request.onsuccess = function(event) {
        db = request.result;
        addFolderDrp();
        showDashboardIcon();

        setTimeout(function () {
            load_tracker_button();
            load_user_tracker();
        },100);



    };

    request.onupgradeneeded = function(event) {
        var db = event.target.result;
        var objectStore = db.createObjectStore(table_name, {keyPath: "patient_id"});
    }


    function read(key) {
        var transaction = db.transaction([table_name]);
        var objectStore = transaction.objectStore(table_name);
        var request = objectStore.get(key);
        request.onerror = function(event) {
            return false;
        };

        request.onsuccess = function(event) {
            return request.result;
        };
    }


    function updateList(newList,pastList) {


        if(pastList.length > 0){
            $.each(pastList,function (index,value) {
                remove(value.patient_id);
            });
        }


        $.each(newList,function (index,value) {
            addPatient(value);
        });

        showDashboardIcon();
    }


    function addPatient(object) {
        var request = db.transaction([table_name], "readwrite")
            .objectStore(table_name)
            .add(object);
    }

    function remove(key) {
        var request = db.transaction([table_name], "readwrite")
            .objectStore(table_name)
            .delete(key);
    }

    function getCookie(cname) {
        var name = cname + "=";
        var ca = document.cookie.split(';');
        for(var i = 0; i < ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) == ' ') {
                c = c.substring(1);
            }
            if (c.indexOf(name) == 0) {
                return c.substring(name.length, c.length);
            }
        }
        return "";
    }



    function addFolderDrp(){
        var trans = db.transaction(table_name, IDBTransaction.READ_ONLY);
        var objectStore = db.transaction(table_name).objectStore(table_name);
        var countRequest = objectStore.count();
        var option_string ="";
        countRequest.onsuccess = function() {
            var total_records = countRequest.result;
            var index=0;
            objectStore.openCursor().onsuccess = function(event) {
                var cursor = event.target.result;
                if (cursor) {
                    value = cursor.value;
                    option_string += "<option data-m='"+btoa(value.mobile)+"' data-t='"+btoa(value.thinapp_id)+"' value='"+btoa(value.folder_id)+"'>"+value.patient_name+"</option>";
                    cursor.continue();
                }else{
                    $("#folder_drp").html(option_string);
                    updateFolderSelection();
                }
            };

        }
    }


    function showDashboardIcon(){
        var trans = db.transaction(table_name, IDBTransaction.READ_ONLY);
        var objectStore = db.transaction(table_name).objectStore(table_name);
        var countRequest = objectStore.count();
        var option_string ="";
        countRequest.onsuccess = function() {
            var total_records = countRequest.result;
            if(total_records > 0){
                $("#dash_my_appointment").show();
                $("#dash_track_token").hide();
            }else{
                $("#dash_my_appointment").hide();
                $("#dash_track_token").show();

            }
        }
    }



    function updateFolderSelection(){
        var obj = $("#folder_drp option").filter(':selected');
        $("#t").val($(obj).attr('data-t'));
        $("#m").val($(obj).attr('data-m'));
        $("#d").val($(obj).val());
    }

    /* file uploading code here*/



    $(document).on('click','.add_new_file',function(e){
        $("#upload_file").modal('show');
    });
    var myDropzone = new Dropzone("#myId", {
        url: baseUrl+"folder/upload_file",
        autoProcessQueue: false,
        maxFilesize: 25, // MB
        addRemoveLinks:true,
        parallelUploads:5,
        maxFiles:5,
        acceptedFiles: ".jpeg,.jpg,.png"
    });
    $(document).on('click','#upload_pre_btn',function(e){
        var accept_files = myDropzone.getAcceptedFiles().length;
        var reject_files = myDropzone.getRejectedFiles().length;
        var total_files= myDropzone.files.length;
        var folder_id = $("#folder_drp").val();
        var cat_id = '6';
        if(folder_id){
            if(accept_files == 0 && reject_files ==0 ){
                alert('Please upload files to upload.');
            }else if( reject_files > 0 ){
                if(total_files > 10){
                    $.alert('You can upload only 10 files once.');
                }else{
                    $.alert('Please upload validate files.');
                }
            }else{
                myDropzone.processQueue();
            }
        }else{
            $.alert('Medical folder not found');
        }
    });
    myDropzone.on("complete", function(file) {
        myDropzone.removeFile(file);
    });
    myDropzone.on("uploadprogress", function(file, progress, bytesSent) {

    });
    myDropzone.on("queuecomplete", function(file) {
        $("#upload_file").modal("hide");
        refreshRecordList(0);
    });
    myDropzone.on("addedfile", function(file) {
        if (this.files.length) {
            var _i, _len;
            for (_i = 0, _len = this.files.length; _i < _len - 1; _i++) // -1 to exclude current file
            {
                if(this.files[_i].name === file.name && this.files[_i].size === file.size && this.files[_i].lastModifiedDate.toString() === file.lastModifiedDate.toString())
                {
                    this.removeFile(file);
                }
            }
            $("#upload_pre_btn").show();
        }else{
            $("#upload_pre_btn").hide();
        }
    });

    myDropzone.on("removedfile", function(file) {
        if (this.files.length) {
            $("#upload_pre_btn").show();
        }else{
            $("#upload_pre_btn").hide();
        }
    });

    function refreshRecordList(offset){
        var thisButton =$('#load_more_record');
        var last_html = $(thisButton).html();
        var fi = $('#folder_drp').val();
        $.ajax({
            url: baseUrl+"folder/load_records",
            data:{offset:offset,m:$("body").attr('data-m')},
            type:'POST',
            beforeSend:function(){
                var src = baseUrl+'/images/doctor_web_loader.gif';
                var htm = '<img src ='+src+'> Loading Records..';
                if(offset==0){
                    $(".append_medical_records").html("<H5 class='record_loading_lbl'>Loading Records...</h5>");
                }else{
                    $(thisButton).button('loading').html('<i class="fa fa-spinner fa-pulse">');
                }
            },
            success: function(result){
                $(thisButton).html(last_html);
                if(offset==0){
                    $(".append_medical_records").html(result);
                    $("#load_more_record").show();
                }else{
                    $(".append_medical_records").append(result);
                }
                $("#load_more_record").attr('data-offset',parseInt(offset)+1);

            },error:function () {
                $(".append_medical_records").html("");
                $(thisButton).html(last_html);
            }
        });
    }
    $(document).on('click','.refresh_btn',function(e){
        refreshRecordList(0);
    });

    $(document).on('change','#folder_drp',function(e){
        updateFolderSelection();
        refreshRecordList(0);
    });


    $(document).on('click','.load_info',function(){
        var pi = $(this).attr('data-id');
        var okBtn = $(this);
        $("#load_info").remove();
        $.ajax({
            url: baseUrl+"doctor/load_info/",
            type:'POST',
            data:{pi:pi},
            beforeSend:function () {
                $(okBtn).button('loading').html('Loading...');
            },
            success: function(result){
                $(okBtn).button('reset').html('View Info');
                var html = $(result).filter('#load_info');
                $(html).modal('show');

                $('#flags, #allergy').tagsinput();

                $(document).off("click",".tag_ul li");
                $(document).on("click",".tag_ul li",function(e){
                    if($(this).hasClass('selected')){
                        $(this).removeClass('selected');
                    }else{
                        $(this).addClass('selected');
                    }
                });
                $(document).off("click",".bootstrap-tagsinput input");
                $(document).on("click",".bootstrap-tagsinput input",function(e){

                    var id = $(this).closest(".box").attr('data-id');

                    $('#'+id).tagsinput('refresh');
                    var added = $("#"+id).val().split(',');

                    if(id=='flags'){
                        var title = "Add Flags";
                        var flags =['Diabetes','BP','Hypertension','Alcoholic','Smoker'];
                    }else{
                        var title = "Add Allergy";
                        var flags =['Anaphylaxis','Antibiotics','Aspirin','Bloating','Breathing','Bronchial Asthma','Cramping abdominal pain','Diarrhea','Diseases','Dizziness','Egg','Fainting','Fainting  ','Fish','Garlic','Hives','Inflammation','Itching','Itching or hives','Mushrooms','Nausea','Pain killers','Peanut','Peanut butter','Penicillin','Runny nose','Sea food','Shortness of breath','Shortness of breath','Skin disease','Skin rashes','Sneezing','Stomach pain','Sulpha','Swelling','Swelling of the lips','Throat and mouth swelling','Tongue or throat','Trouble breathing ','Urticaria','Vomiting','Vomiting ','Vomiting and diarrhoea','Wheezing'];
                    }

                    var html_string = "<h5>Chose "+id+" here</h5><ul class='tag_ul'>";
                    $.each(flags,function (index,value) {
                        var cls = (added.includes(value))?'selected':'';
                        html_string += "<li class="+cls+" >"+value+"</li>";
                    });
                    var input_tag =[];
                    $.each(added,function (index,value) {
                        if(!flags.includes(value)){
                            input_tag.push(value);
                        }
                    });
                    input_tag = input_tag.join(',');
                    var str = "Please select "+id+" from above list, if you do not find your "+id+" in the above list of selection, please enter your "+id+" to add in your patient info."

                    html_string += "</ul><div class='dialog_tag_box'><div class='input_label'>"+str+"</div><input value='"+input_tag+"'  data-role='tagsinput' type='text' id='input_tag'></div>";
                    var swalBox =  swal({
                        title: title,
                        showCancelButton: true,
                        confirmButtonText:'Done',
                        allowOutsideClick: false,
                        html:html_string,
                    }).then(function (result) {
                        $('#'+id).tagsinput('removeAll');
                        var all_tag =[];
                        $(".tag_ul .selected").each(function (index,value) {
                            all_tag.push($(this).text());
                        });

                        var extra_tag = $('#input_tag').val().split(',');
                        $.each(extra_tag,function (index,value) {
                            all_tag.push(value);
                        });

                        $.each(all_tag,function (index,value) {
                            $('#'+id).tagsinput('add',value);
                            $('#'+id).tagsinput('refresh');
                        });

                        swalBox.close();
                    }).catch(swal.noop);
                    setTimeout(function () {
                        $('#input_tag').tagsinput();
                        $(".bootstrap-tagsinput .tag").css({'backgound':'blue'});
                        $("#swal2-content .bootstrap-tagsinput").css({'width':'100%'});

                    },100);


                });
                $(document).off("click","#save_btn");
                $(document).on("click","#save_btn",function(e){
                    var $btn =  $(this);
                    var data = {
                        flags:$('#flags').val(),
                        allergy:$('#allergy').val(),
                        history:$("#history").val(),
                        height:$("#height").val(),
                        weight:$("#weight").val(),
                        bp_systolic:$("#bp_systolic").val(),
                        temperature:$("#temperature").val(),
                        o_saturation:$("#o_saturation").val(),
                        ac:atob(pi),
                        c:"0"
                    };
                    $.ajax({
                        type:'POST',
                        url: baseUrl+"/homes/save_flag",
                        data:data,
                        beforeSend:function(){
                            $($btn).prop('disabled',true).text('Saving..');
                        },
                        success:function(response){
                            $($btn).prop('disabled',false).text('Save');
                            var response = JSON.parse(response);

                            if(response.status==1){
                                $('#flags, #allergy').tagsinput('destroy');
                                $("#load_info").modal('hide');
                                $("#load_info").remove();
                            }else{
                                alert(response.message);
                            }
                        },
                        error: function(data){
                            $($btn).prop('disabled',false).text('Save');
                            $(".file_error").html("Sorry something went wrong on server.");
                        }
                    });
                });
                $("#bp_systolic").mask("99-999", {placeholder: '00-000'});

            },error:function () {

            }
        });
    });


    function load_tracker_button(){
        const params = new URLSearchParams(window.location.search);
        if(params.has('uh') && params.has('ti')){
            send_tracker_ajax(params.get('uh'),params.get('ti'),'');
        }else{
            var trans = db.transaction(table_name, IDBTransaction.READ_ONLY);
            var objectStore = db.transaction(table_name).objectStore(table_name);
            objectStore.openCursor().onsuccess = function(event) {
                var cursor = event.target.result;
                if (cursor) {
                    var patient_data = cursor.value;
                    var okBtn = $(".next_close_btn");
                    if(patient_data){
                        var lastText = "Close & Next";
                        $.ajax({
                            url: baseUrl+"doctor/tracker_button",
                            type:'POST',
                            data:{m:btoa(patient_data.mobile),ti:btoa(patient_data.thinapp_id),ai:$(".next_close_btn").attr('data-id')},
                            beforeSend:function () {
                                $(okBtn).attr('disabled',true);
                                $(okBtn).button('loading').html('Closing...');
                            },
                            success: function(result){
                                $(okBtn).attr('disabled',false);
                                $(okBtn).button('reset').html(lastText);
                                $(".tracker_row").html(result);

                                if($(".tracker_row").find('tr').length ==0){
                                    load_user_tracker();
                                }


                            },error:function () {
                                $(okBtn).attr('disabled',false);
                                $(okBtn).button('reset').html(lastText);
                            }
                        });
                    }
                }
            };
        }
    }


    function load_user_tracker(){
        const params = new URLSearchParams(window.location.search);
        if(params.has('uh') && params.has('ti')){
            send_tracker_ajax(params.get('uh'),params.get('ti'),'');
        }else{
            var trans = db.transaction(table_name, IDBTransaction.READ_ONLY);
            var objectStore = db.transaction(table_name).objectStore(table_name);
            objectStore.openCursor().onsuccess = function(event) {
                var cursor = event.target.result;
                if (cursor) {
                    var patient_data = cursor.value;
                    if(patient_data){
                        var uhid = btoa(0);
                        if(patient_data.uhid){
                            uhid = btoa(patient_data.uhid);
                        }
                        send_tracker_ajax(uhid,btoa(patient_data.thinapp_id),(patient_data.mobile));
                    }
                }
            };
        }
    }

    function send_tracker_ajax(uhid,thin_app_id,mobile){
        var uhid = (uhid);
        var btn = $(".refresh_tracker_btn");
        var thin_app_id = (thin_app_id);
        var doctor_id = $(".main_container ").attr('data-di');
        $.ajax({
            url: baseUrl+"tracker/track_your_appointment/"+uhid+"/"+thin_app_id+"/1",
            type:'POST',
            data:{m:mobile,di:doctor_id},
            beforeSend:function () {
                btn.button('loading').html('Loading');
            },
            success: function(result){
                btn.button('reset');
                $(".tracker_row").html(result);
                var trans = db.transaction(table_name, IDBTransaction.READ_ONLY);
                var objectStore = db.transaction(table_name).objectStore(table_name);
                objectStore.openCursor().onsuccess = function(event) {
                    var cursor = event.target.result;
                    if (!cursor) {
                        $(".otp_row").show();
                    }
                };
            },error:function () {
                btn.button('reset');
            }
        });
    }



    $(document).on('click','.next_close_btn',function(){
        load_tracker_button();
    });


    $(document).on('click','.refresh_tracker_btn',function(){
        load_user_tracker();
    });


    $(document).on('click','.verify_tracker_number',function(){
        var pm = atob($(this).attr('data-key'));
        var t = btoa($(body).attr('data-ti'));
        var btn = $(this);
        $.ajax({
            url: baseUrl+"doctor/send_otp",
            type:'POST',
            data:{t:t,mobile:pm,send_list:'yes'},
            beforeSend:function () {
                btn.button('loading').html('Generating..');
            },
            success: function(result){
                btn.button('reset');
                result = JSON.parse(result);
                if (result.status == 1) {
                    var row_id =  result.row_id;
                    $(".otp_text_td").html("Enter OTP <input type='number' class='otp_box'>");
                    $(".otp_button_td").find("button").removeClass('verify_tracker_number').addClass('verify_otp_btn');
                    $(".otp_button_td").find("button").html('Verify');
                    $('.verify_otp_btn').attr('data-key', row_id);
                    $('.otp_box').focus();
                    $(body).data('pat_list',result.list);
                }else{
                    $.alert(result.message);
                }
            },error:function () {
                btn.button('reset');
                $.alert('Network issue found.');
            }
        });
    });

    $(document).on('click','.verify_otp_btn',function(){
        var row_id = $(this).attr('data-key');
        var otp = $('.otp_text_td .otp_box').val();
        if(btoa(otp)==row_id){
            var newlist = $(body).data('pat_list');
            if(newlist){
                newlist = JSON.parse(newlist);
                // console.log(newlist);
                updateList(newlist,[]);
                showDashboardIcon();
                $(".otp_row").remove();
                $(body).data('pat_list','');
            }else{
                $.alert('Info not found');
            }
        }else{
            $.alert('Please enter valid OTP');
        }
    });

    function installAppStatus(){
        var trans = db.transaction(table_name, IDBTransaction.READ_ONLY);
        var objectStore = db.transaction(table_name).objectStore(table_name);
        objectStore.openCursor().onsuccess = function(event) {
            var cursor = event.target.result;
            if (cursor) {
                patient_data = cursor.value;
                var mobile = patient_data.mobile;
                var thin_app_id = patient_data.thinapp_id;
                installAjax(mobile,thin_app_id);
            }else{
                if($('.verify_tracker_number').attr('data-key') && $(body).attr('data-ti')){
                    var mobile = atob($('.verify_tracker_number').attr('data-key'));
                    var thin_app_id = atob($(body).attr('data-ti'));
                    installAjax(mobile,thin_app_id);
                }
            }
        };
    }

    function installAjax(mobile,thin_app_id){
        $.ajax({
            url: baseUrl+"doctor/web_app_installed",
            data:{m:btoa(mobile),t:btoa(thin_app_id)},
            type:'POST',
            beforeSend:function () {

            },
            success: function(result){

            },error:function () {

            }
        });
    }

    let deferredPrompt;
    var btnSave = document.getElementById('btnSaveDoctor');
    window.addEventListener('beforeinstallprompt', function(e) {
        console.log('beforeinstallprompt Event fired');
        e.preventDefault();
        // Stash the event so it can be triggered later.
        deferredPrompt = e;
        return false;
    });


    function hideInstallBtn(){
        $("#btnSaveDoctor").hide();
        $("#btnSaveDoctor").removeClass('highlight_install_btn');
        $("#fade_box, .navigation-arrow-up").remove();
    }
    $(document).on('click',"#btnSaveDoctor",function(){
        if(deferredPrompt !== undefined) {
            deferredPrompt.prompt();
            deferredPrompt.userChoice.then(function(choiceResult) {
                console.log(choiceResult.outcome);
                if(choiceResult.outcome == 'dismissed') {
                    console.log('User cancelled home screen install');
                }
                else {
                    installAppStatus();
                    hideInstallBtn();
                    saveInstallLog();
                }
                // We no longer need the prompt.  Clear it up.
                deferredPrompt = null;
            });
        }else{
            var app_name ="<?php echo $doctor_data['app_name']; ?>";
            var ua = navigator.userAgent.toLowerCase();
            if (ua.indexOf('safari') != -1) {
                if (ua.indexOf('chrome') > -1) {
                    alert("Following reasons are that app can not add on the homescreen\n\n1. This web browser does not support this feature. You can try again by updating your browser.\n2. You have already added '"+app_name+"' to homescreen.");
                    hideInstallBtn();
                } else {
                    var img = "<img style='float:left;width:100%;' src='https://s3-ap-south-1.amazonaws.com/mengage/202103041939141957658215.png'>";
                    var ios= "<h6>Following steps are needed to add app '"+app_name+"' on homescreen.</h6>\n"+img;
                    swal({
                        type:'',
                        title:'',
                        html: ios,
                        showCancelButton: false,
                        confirmButtonText: 'Got It',
                        customClass:"success-box",
                        showConfirmButton: true
                    }).then(function (result) {

                    });



                }
            }
        }
    });

    function onLoad(){
        $(".container").removeClass("dynamic_width");
        if (window.matchMedia('(display-mode: standalone)').matches) {
            $("#btnSaveDoctor, .top_back_box").hide();
        }else{

            if(!is_desktop()){
                if(deferredPrompt !== undefined) {
                    $("#btnSaveDoctor").addClass('highlight_install_btn');
                    $("#fade_box").show();
                    $("#btnSaveDoctor").show();
                }
                var ua = navigator.userAgent.toLowerCase();
                if (ua.indexOf('safari') != -1) {
                    if (ua.indexOf('chrome') > -1) {} else {
                        $("#btnSaveDoctor").show();
                    }
                }
            }else{
                $("#btnSaveDoctor").hide();
                $(".container").addClass("dynamic_width");
            }
        }
    }

    setTimeout(function () {
        onLoad();
    },1000);

    function is_desktop(){
        if(/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
            return false;
        }return true;
    }

    function saveInstallLog(){
        var di =$(".main_container").attr('data-di');
        $.ajax({
            url: baseUrl+"doctor/web_app_install_log",
            type:'POST',
            data:{di:di},
            success: function(result){},
            error:function () {}
        });
    }

});

