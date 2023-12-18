<div class="add_cus_div_app">
    <div class="form-group row">
        <div class="col-md-10 col-md-offset-1 add_appointment">

            <!--span class="input-group-addon app_slot_span"><span class="glyphicon glyphicon-user"></span></span>
            <select name="user_type" id="userType" class="form-control customer_gender">
                <option value="CUSTOMER">Patient</option>
                <option value="CHILDREN">Child</option>
            </select-->
           
            <label><input type="radio" name="add_type" value="CUSTOMER" id="userType" data-cus="<?php echo (0); ?>" data-user="<?php echo (0); ?>">&nbsp;Patient </label>&nbsp;&nbsp;
            <label><input type="radio" name="add_type" value="CHILDREN" id="userType" data-cus="<?php echo (0); ?>" data-user="<?php echo (0); ?>">&nbsp;Children</label>


            <!--span class="input-group-addon app_slot_span">

                    <button class="btn btn-xs btn-success book_btn" data-cus="<?php echo (0); ?>" data-user="<?php echo (0); ?>" type="button">Add</button>

                </span-->
        </div>

    </div>
</div>
    <?php if(!empty($customer_list)){ ?>
    <table class="table">
        <thead>
        <tr>
            <th>#</th>
            <th>Photo</th>
            <th>Name</th>
            <th>UHID</th>
            <th>Patient Type</th>
            <!--th>Book</th-->
            <th>Detail</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($customer_list as $key => $list){
            if(empty($list['profile_photo'])){
                $list['profile_photo'] = Router::url('/thinapp_images/staff.jpg',true);
            }
            ?>
            <tr>
                <td class="td_valign"><?php echo $key+1; ?></td>
                <td><img class="search_cus_img_box" src="<?php echo $list['profile_photo']; ?>"/></td>
                <td class="td_valign"><?php echo $list['name']; ?></td>
                <td class="td_valign"><?php echo empty($list['uhid'])?'-':$list['uhid']; ?></td>
                <td class="td_valign"><?php echo !empty($list['child_id'])?'Child':'Patient'; ?></td>
                <!--td class="td_valign" user-type="<?php echo empty($list['child_id'])?"CUSTOMER":"CHILDREN" ?>"><button class="btn btn-success btn-xs app_book_now_direct" data-cus="<?php echo ($list['id']); ?>" data-user="<?php echo ($list['user_id']); ?>" class="btn btn-success btn-xs" type="button">Book</button></td-->
                <td class="td_valign" user-type="<?php echo empty($list['child_id'])?"CUSTOMER":"CHILDREN" ?>"><button class="btn btn-success btn-xs app_book_now" data-cus="<?php echo ($list['id']); ?>" data-user="<?php echo ($list['user_id']); ?>" class="btn btn-success btn-xs" type="button">Details</button></td>

            </tr>
        <?php } ?>
        </tbody>
    </table>


<?php } ?>
<div class="add_cus_div_app add_cus_div_app_load"></div>
    <style>
        .add_appointment{ text-align: center; }
        .add_appointment h3{ color: #04a6f0; }
        .add_appointment label{ color: #04a6f0;font-size: medium; }
    </style>
<script>


    /* code use for book appointment */
    $(document).off("click", "#userType");
    $(document).on("click","#userType",function(){

            var $btn = $(this);
            var obj = $(this);
            var user_type =  $('#userType:checked').val();

            var number = $(".app_search_customer").val();

            var user = $(this).attr("data-user");
            var customer_id = $(this).attr("data-cus");
            var first_name = $(".customer_first_name").val();
            var gender = $(".customer_gender").val();
            var code = "+91";


            if(!number.match(/^\d{10}$/)){
                $(".app_error_msg").html("Please enter valid 10 digit mobile number.").fadeIn('slow');
            }
            else
            {

                    $.ajax({
                        type:'POST',
                        url: baseurl+"app_admin/get_book_new_appointment_without_token",
                        data:{user_type:user_type,first_name:first_name,gender:gender,mobile:number,code:code,user:user,customer_id:customer_id,type:'NEW'},
                        beforeSend:function(){
                            $btn.attr('readonly',true);
                            $(".customer_loading").show();
                            $(".app_error_msg").fadeOut('slow');
                        },
                        success:function(data){
                            if(data != '')
                            {
                                $(".add_cus_div_app_load").html(data);
                                $btn.attr('readonly',false);
                                $(".customer_loading").hide();
                            }
                            else
                            {
                                $(".app_error_msg").html("Sorry, Couldn't Get Data!").fadeIn('slow');
                                $btn.attr('readonly',false);
                                $(".customer_loading").hide();
                            }

                        },
                        error: function(data){
                            $btn.attr('readonly',false);
                            $(".customer_loading").hide();
                            $(".app_error_msg").html(response.message).fadeIn('slow');;
                        }
                    });

            }



    });



    $(document).off("click", ".app_book_now");
    $(document).on("click", ".app_book_now", function () {
        var $btn = $(this);
        var obj = $(this);
        var user_type =  $(this).closest('td').attr('user-type');
        var user = $(this).attr("data-user");
        var customer_id = $(this).attr("data-cus");
        var first_name = $(".customer_first_name").val();
        var gender = $(".customer_gender").val();
        var number = $(".app_search_customer").val();
        var code = "+91";

        if(!number.match(/^\d{10}$/)){
            $(".app_error_msg").html("Please enter valid 10 digit mobile number.").fadeIn('slow');
        }else if(user == 0 && customer_id == 0 &&  first_name == "" ){
            $(".app_error_msg").html("Please enter customer name.").fadeIn('slow');

        }else{
            if(!user_type){
                user_type = "CUSTOMER";
            }
                $.ajax({
                    type:'POST',
                    url: baseurl+"app_admin/get_book_new_appointment_without_token",
                    data:{user_type:user_type,gender:gender,first_name:first_name,mobile:number,code:code,user:user,customer_id:customer_id},
                    beforeSend:function(){
                        isSearching = 'YES';
                        $btn.attr('readonly',true);
                        $(".customer_loading").show();
                        $(".app_error_msg").fadeOut('slow');
                    },
                    success:function(data){
                        isSearching = 'NO';
                        if(data.indexOf("ERROR_GOT_IN_LOAD") <= 0)
                        {
                            $(".add_cus_div_app_load").html(data);
                            $btn.attr('readonly',false);
                            $(".customer_loading").hide();
                        }
                        else
                        {
                            data = JSON.parse(data);
                            $(".app_error_msg").html(data.message).fadeIn('slow');
                            $btn.attr('readonly',false);
                            $(".customer_loading").hide();
                        }

                    },
                    error: function(data){
                        isSearching = 'NO';
                        $btn.attr('readonly',false);
                        $(".customer_loading").hide();
                        $(".app_error_msg").html(data.message).fadeIn('slow');
                    }
                });

            }


    });
    /* code use for book appointment */



    /*$(document).off("click", ".app_book_now_direct");
    $(document).on("click", ".app_book_now_direct", function () {

        var $btn = $(this);
        var obj = $(this);
        var slot =  $("#search_cus_modal").attr('slot');
        var user_type =  $(this).closest('td').attr('user-type');
        var date = $(".slot_date").val();
        var doctor =  $(".staff_drp").val();
        var address = $(".address_drp").val();
        var user = $(this).attr("data-user");
        var customer_id = $(this).attr("data-cus");
        var first_name = $(".customer_first_name").val();
        var gender = $(".customer_gender").val();
        var number = $(".app_search_customer").val();
        var code = "+91";

        if(!number.match(/^\d{10}$/)){
            $(".app_error_msg").html("Please enter valid 10 digit mobile number.").fadeIn('slow');
        }else if(user == 0 && customer_id == 0 &&  first_name == "" ){
            $(".app_error_msg").html("Please enter customer name.").fadeIn('slow');

        }else{

            if(!user_type){
                user_type = "CUSTOMER";
            }
            if(isSearching == 'NO')
            {
                $.ajax({
                    type:'POST',
                    url: baseurl+"app_admin/web_get_book_new_appointment",
                    data:{user_type:user_type,gender:gender,first_name:first_name,mobile:number,code:code,slot:slot,date:date,doctor:doctor,address:address,user:user,customer_id:customer_id},
                    beforeSend:function(){
                        isSearching == 'YES';
                        $btn.attr('readonly',true);
                        $(".customer_loading").show();
                        $(".app_error_msg").fadeOut('slow');
                    },
                    success:function(data){
                        isSearching == 'NO';
                        var response = JSON.parse(data);
                        if(response.status==1){
                            $btn.attr('readonly',false);
                            $(".customer_loading").hide();
                            $(".slot_date").trigger('changeDate');
                            $("#search_cus_modal").modal("hide");
                        }else{
                            $(".app_error_msg").html(response.message).fadeIn('slow');
                            $btn.attr('readonly',false);
                            $(".customer_loading").hide();
                        }
                    },
                    error: function(data){
                        isSearching == 'NO';
                        $btn.attr('readonly',false);
                        $(".customer_loading").hide();
                        $(".app_error_msg").html(response.message).fadeIn('slow');;
                    }
                });
            }

        }


    })*/




</script>
<style>
    .customer_first_name, .customer_last_name{float: left;width: 70% !important;}
    .customer_gender{float: left; /*width: 30% !important;*/}
    .table-responsive{
        max-height: 205px;
        overflow-y: scroll;
    }
</style>