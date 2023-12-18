<div class="modal-dialog modal-lg">
    <div class="modal-content form-horizontal">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h5 class="modal-title">Add Appointment</h5>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <div class="col-md-6">
                    <div class="note_message">Note: Enter 9999999999 If Mobile Number Is Not Available.</div>
                    <div class="input-group">
                        <span class="input-group-addon app_slot_span"><!--<span class="glyphicon glyphicon-phone"></span>--> +91</span>
                        <input type="text" class="form-control app_search_customer" id="app_search_customer_without_token" list="mobileDataList" placeholder="Enter Customer Mobile Number" >
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="note_message">&nbsp;</div>
                    <div class="input-group">
                        <span class="input-group-addon app_slot_span">UHID</span>
                        <input type="text" class="form-control app_search_customer app_search_customer_uhid" id="app_search_customer_uhid_without_token" placeholder="Enter Customer UHID" >
                    </div>
                </div>

                <div class="col-md-12">
                    <span class="input-group-addon app_slot_span slot_err_msg"><span class="fa fa-spinner fa-spin customer_loading"></span></span>
                    <div class="app_error_msg"></div>
                </div>
                <datalist id="mobileDataList">
                    <?php foreach ($appointmentMobiles AS $key => $value) { ?>
                        <option value="<?php echo str_replace('+91','',$value); ?>" ><?php echo str_replace('+91','',$value); ?></option>
                    <?php } ?>
                </datalist>
            </div>
            <div class="form-group">
                <div class="col-sm-12">
                    <div id="load_customer_div"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    .note_message {
        text-align: center;
        font-size: 11px;
        color: red;
        line-height: 16px;
        margin-bottom: 15px;
    }
    .slot_err_msg {
        background: none;
        border: none;
        border-right-width: medium;
        border-right-style: none;
        border-right-color: currentcolor;
    }

</style>
<script>

     $(".customer_loading").hide();
     /* code user for search customer modal*/

     $(document).off('input',"#app_search_customer_without_token");
     $(document).on('input',"#app_search_customer_without_token", function(e){

         var in_process =false;
         /*var charCode = (e.which) ? e.which : e.keyCode;
         if ((charCode >= 48 && charCode <= 57) || (charCode >= 96 && charCode <= 105) || charCode == 105) { */
             var number = $(this).val()
             if( number.match(/^\d{10}$/)){
                 var $btn = $(this);
                 number = "+91"+number;
                 $(".file_error").fadeOut('slow');

                     $.ajax({
                         type:'POST',
                         url: baseurl+"app_admin/load_search_customer_list_without_token",
                         data:{mobile:number},
                         beforeSend:function(){
                             isSearching = 'YES';
                             $btn.attr('readonly',true);
                             $(".customer_loading").show();
                         },
                         success:function(data){
                             $("#search_cus_modal_without_token  #load_customer_div").html(data);

                             if($('#search_cus_modal_without_token #load_customer_div > table').length == 0 ) {
                                 $("#search_cus_modal_without_token #load_customer_div").find("input[value='CUSTOMER']").prop( "checked", true ).delay(2000).trigger('click');
                             }

                             $btn.attr('readonly',false);
                             $(".app_error_msg").fadeOut('slow');
                             $(".customer_loading").hide();
                             setTimeout(function(){
                                 $("#search_cus_modal_without_token #load_customer_div").find("button").first().focus();
                             },1000);
                         },
                         error: function(data){
                             $btn.attr('readonly',false);
                             $(".customer_loading").hide();
                             $(".app_error_msg").html("Sorry something went wrong on server.").fadeIn('slow');
                         }
                     });




             }else{
                 $(".app_error_msg").html("Please enter valid 10 digit mobile number.").fadeIn('slow');
                 $("#search_cus_modal_without_token #load_customer_div").html('');
             }
         /*}*/
     });

     $(document).off('blur',"#app_search_customer_uhid_without_token");
     $(document).on('blur',"#app_search_customer_uhid_without_token", function(e){

         var in_process =false;
         /*var charCode = (e.which) ? e.which : e.keyCode;
          if ((charCode >= 48 && charCode <= 57) || (charCode >= 96 && charCode <= 105) || charCode == 105) { */
         var UHID = $(this).val()
         if( UHID.length > 6){
             var $btn = $(this);
             $(".file_error").fadeOut('slow');

                 $.ajax({
                     type:'POST',
                     url: baseurl+"app_admin/load_search_customer_list_uhid_without_token",
                     data:{UHID:UHID},
                     beforeSend:function(){
                         $btn.attr('readonly',true);
                         $(".customer_loading").show();
                     },
                     success:function(data){
                         $("#search_cus_modal_without_token  #load_customer_div").html(data);

                         if($('#search_cus_modal_without_token #load_customer_div > table').length == 0 ) {
                             $("#search_cus_modal_without_token  #load_customer_div").find("input[value='CUSTOMER']").prop( "checked", true ).delay(2000).trigger('click');
                         }

                         $btn.attr('readonly',false);
                         $(".app_error_msg").fadeOut('slow');
                         $(".customer_loading").hide();
                         setTimeout(function(){
                             $("#search_cus_modal_without_token  #load_customer_div").find("button").first().focus();
                         },1000);
                     },
                     error: function(data){
                         $btn.attr('readonly',false);
                         $(".customer_loading").hide();
                         $(".app_error_msg").html("Sorry something went wrong on server.").fadeIn('slow');
                     }
                 });



         }else{
             $(".app_error_msg").html("Please enter valid UHID.").fadeIn('slow');
             $("#search_cus_modal_without_token  #load_customer_div").html('');
         }
         /*}*/
     });

     $(document).off('keyup',"#app_search_customer_uhid_without_token");
     $(document).on('keyup',"#app_search_customer_uhid_without_token", function(e){
         var code = (e.keyCode ? e.keyCode : e.which);
         if (code == 13) {
             $("#app_search_customer_uhid").trigger("blur");
         }
     });

     $("#search_cus_modal_without_token #app_search_customer_without_token").focus();
</script>


