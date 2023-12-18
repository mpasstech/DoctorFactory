<div class="modal fade"  style="width:750px;margin:2% auto; overflow: hidden;" id="send_sms_modal" >
   <form id="send_sms_form" method="post" enctype="multipart/form-data">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title" style="text-align: center;font-weight: bold;">Send Sms</h4>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-7">
                    <label for="opdCharge">Enter Number ( Add multiple number with (,) )</label>
                    <input  type="text" class="form-control" name="numbers" placeholder="9999999999,8888888888,7777777777">
                </div>
                <div style="padding: 0px;" class="col-md-3">
                    <label>Send To</label><br>
                    <label style="margin-left: 0px;" class="checkbox-inline"><input type="checkbox" value="YES" name="all_patient" />All Patients</label>
                </div>
                <div class="col-md-2">
                    <input style="display: none;" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" type="file" id="file" name="file" />
                    <label>Upload CSV</label><br>
                    <button data-toggle="tooltip" title="Enter 10 digit mobile number into first column without header" type="button" class="btn btn-success upload_btn">Upload CSV</button>
                <label class="show_msg"> </label>
               </div>

            </div>
            <div class="row">
                <div class="col-md-12 box_col">
                    <label for="opdCharge">Enter your message here <span class="str_len"></span></label>
                    <textarea required="required" type="text" rows="5" class="form-control" name="message" />
                    <label style="color: red;">"Upload CSV" Supports only excel and csv file</label>
                </div>

            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn-warning btn modal_dismis" data-dismiss="modal">Cancel</button>
            <button type="reset" class="btn-success btn" >Reset</button>
            <button type="submit" class="btn-info btn send" >Send</button>
        </div>
    </div>
   </form>
    <script>
        $(function() {

            var currentRequest = null;
            $(document).off('input',"textarea");
            $(document).on('input','textarea',function(e){
                var obj = $(this).closest('.box_col').find('.str_len');
                var msg =$(this).val();
                if(msg){
                    currentRequest =  $.ajax({
                        type:'POST',
                        url: baseurl+"app_admin/count_credit",
                        data:{msg:msg},
                        beforeSend:function(){
                            if(currentRequest!= null) {
                                currentRequest.abort();
                            }
                        },
                        success:function(data){
                            var data = JSON.parse(data);
                            var str = data.sms+" SMS Credits For "+data.len+" Characters";
                            $(obj).html(str);
                        },
                    });
                }else{
                    var str = "0 SMS Credits For 0 Characters";
                    $(this).closest('.box_col').find('.str_len').html(str);
                }

            });



            $(document).off("click",".upload_btn");
            $(document).on("click",".upload_btn", function() {
                $("#file").trigger('click');
            });
            $(document).off("submit", "#send_sms_form");
            $(document).on("submit", "#send_sms_form", function(e) {
                e.preventDefault();
               // var formData = $(this).serialize();
                var formData = new FormData($(this)[0]);
                var $btn = $(".send");
                var jc = $.confirm({
                    title: 'Send SMS',
                    content: 'Are you sure you want to send message?',
                    type: 'yellow',
                    buttons: {
                        ok: {
                            text: "Yes",
                            btnClass: 'btn-primary',
                            name:"ok",
                            action: function(e){
                                jc.close();
                                $.ajax({
                                    type:'POST',
                                    url: baseurl+"app_admin/send_sms",
                                    data: formData,
                                    cache: false,
                                    contentType: false,
                                    processData: false,
                                    beforeSend:function(){
                                        $btn.button('loading').html('Sending...');
                                    },
                                    success:function(data){
                                        var response = JSON.parse(data);
                                        $.alert(response.message);
                                        if(response.status==1){
                                            $(".modal_dismis").trigger('click');


                                        }
                                        $btn.button('reset');
                                    },
                                    error: function(data){
                                        $btn.button('reset');
                                        alert("Sorry something went wrong on server.");
                                    }
                                });
                            }
                        },
                        cancel: function(){

                        }
                    }
                });
            });
            $(document).off('change','#file');
            $(document).on('change','#file',function(e){
                if($(this).val()){
                    $(".show_msg").html('File uploaded successfully');
                }else{
                    $(".show_msg").html('');
                }
            });

        });
    </script>
    <style>
        .show_msg{
            position: absolute;

            width: 100%;

            display: block;

            float: left;

            font-size: 9px;
        }

        .box_col label{
            width: 100% !important;
        }
        .box_col label span{
            float: right !important;
        }


    </style>
</div>



