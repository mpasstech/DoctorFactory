<?php
$login = $this->Session->read('Auth.User');
$thin_app_id = $login['User']['thinapp_id'];
$department_list = $this->AppAdmin->get_all_department_list($thin_app_id);
?>

<section class="content contact">
    <div class="block-header">
        <div class="row">
            <div class="col-lg-7 col-md-6 col-sm-12">
                <h2>Doctor
                    <small class="text-muted">Hospital Doctor List</small>
                </h2>
            </div>
            <div class="col-lg-5 col-md-6 col-sm-12">
                <ul class="breadcrumb float-md-right">
                    <li class="breadcrumb-item"><a href="index.html"><i class="zmdi zmdi-home"></i> Home</a></li>
                    <li class="breadcrumb-item"><a href="javascript:void(0);">Appointment</a></li>
                    <li class="breadcrumb-item active">Book Appointment</li>
                </ul>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card action_bar">
                    <div class="body">
                        <div class="row clearfix">

                            <div class="col-lg-12 col-md-12 col-12">
                                <div class="input-group search">
                                    <input type="text" class="form-control search_box" placeholder="Search...">
                                    <span class="input-group-addon search_btn">
                                        <i class="zmdi zmdi-search"></i>
                                    </span>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>






        <?php if(!empty($department_list)) { ?>
        <div class="row clearfix">
            <div class="col-md-12 col-lg-12">
                <div class="card">
                    <div class="header">
                        <h2><strong>Department</strong> List</h2>
                    </div>
                    <div class="body table-responsive list_body">
                        <ul class="new_friend_list list-unstyled row table-responsive">
                            <li class="col-lg-1 col-md-4 col-sm-4 col-4">
                                <a href="javascript:void(0)" class="department_click active" data-id="0">
                                    <div class="img_wrapper">
                                        <i  class="fa fa-group img-thumbnail" ></i>
                                    </div>

                                    <h6 class="users_name"> All </h6>
                                    
                                </a>
                            </li>
                            <?php foreach($department_list as $key =>$list){ ?>

                            <li class="col-lg-1 col-md-4 col-sm-4 col-4">
                                <a href="javascript:void(0);" class="department_click" data-id="<?php echo $list['AppointmentCategory']['id']; ?>">
                                    <div class="img_wrapper">
                                    <?php if(!empty($list['AppointmentCategory']['image'])){ ?>

                                      <img src="<?php echo $list['AppointmentCategory']['image']; ?>" class="img-thumbnail" alt="User Image">

                                    <?php } else{ ?>

                                        <i  class="fa fa-group img-thumbnail" ></i>

                                    <?php } ?>

                                    </div>

                                    <h6 class="users_name"> <?php echo $list['AppointmentCategory']['name'];?> </h6>

                                </a>
                            </li>
                            <?php } ?>

                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <?php } ?>


        <div class="row clearfix append_list">

        </div>


    </div>
</section>

<script>




    $(document).ready(function(){


        var doctor_id = 0;


        function load_list(){
            var search =  $(".search_box").val();
            var department = $(".department_click.active").attr('data-id');
            var loading;
            $.ajax({
                url:baseurl+"/app_admin/doctor_list_ajax",
                type:'POST',
                data:{
                    search:search,
                    department:department

                },
                beforeSend:function(){
                   /* loading = $.notify("Please wait, Your list is loading...", {
                        type: 'danger',
                        allow_dismiss: true,
                        delay:100
                    });*/
                    $('.page-loader-wrapper').fadeIn();

                },
                success:function(res){
                    $('.page-loader-wrapper').fadeOut();
                    $(".append_list").html(res);

                },
                error:function () {

                    $('.page-loader-wrapper').fadeOut();

                    $.notify("Sorry something went wrong on server.", {
                        type: 'danger',
                        allow_dismiss: true
                    });
                }
            })
        }

        load_list();



        $(document).on('click','.search_btn',function(e){
            load_list();
        })



        $(document).on('click','.upload_media',function(e){


            var formData = new FormData($("#sub_frm")[0]);
            var filename = $('.browse_file').val().split('\\').pop();
            var $btn = $(this).find('i');
            $.ajax({
                type:'POST',
                url: baseurl+"app_admin/upload_media",
                data:formData,
                cache:false,
                contentType: false,
                processData: false,
                beforeSend:function(){
                    $(".file_error, .file_success").html("");
                    $btn.button('loading').html('').attr('class',"fa fa-circle-o-notch fa-spin");

                    var notify = $.notify('<strong>Uploading</strong> Do not close this page...', {
                        type: 'warning',
                        allow_dismiss: false,
                        showProgressbar: true
                    });



                },
                success:function(data){
                    data = JSON.parse(data);
                    $btn.button('loading').html('').attr('class',"fa fa-upload");
                    $btn.button('reset');
                    if(data.status==1){
                        $(".image_box").val(data.url);
                        $(".original_file").val(filename);
                        $(".channel_img").attr("src",data.url);

                        notify.update('message', '<strong>Uploaded</strong> '+data.message);
                        upload_file= false;
                    }else{
                        notify.update('message', '<strong>Uploaded</strong> '+data.message);

                    }
                },
                error: function(data){
                    $btn.button('loading').html('').attr('class',"fa fa-upload");
                    notify.update('message', '<strong>Error</strong> Sorry something went wrong on server.');

                }
            });




        })

        $(document).on('click','.department_click',function(e){


            $(".department_click").removeClass('active');
            $(this).addClass('active');
            load_list();
        })

        $(document).on('click','.load_slot',function(e){


            doctor_id =  $(this).attr('data-id');
            var thisButton =  $(this);
            $.ajax({
                url:baseurl+"/app_admin/ajax_load_doctor_slots",
                type:'POST',
                data:{doctor_id:doctor_id},
                beforeSend:function(){
                    $(thisButton).button('loading').html('<i class="fa fa-spinner fa-pulse">');

                },
                success:function(res){
                    $(thisButton).button('reset');
                    $('.append_list').fadeOut(800).html(res).fadeIn(800);
                    changeAddress();

                },
                error:function () {


                    $.notify("Sorry something went wrong on server.", {
                        type: 'danger',
                        allow_dismiss: true
                    });
                }
            })



        })


        $(document).on('change','.address_drp',function(e){
            changeAddress();
        });

        function changeAddress(){
            var address_id = $('.address_drp').val();
            var obj = $('.address_drp');
            if(doctor_id && address_id ){
                $.ajax({
                    type:'POST',
                    url: baseurl+"app_admin/load_book_appointment_modal",
                    data:{address:btoa(address_id),doctor:(doctor_id)},
                    beforeSend:function(){
                        loading(obj,true,"Loading appointment slot...");
                    },
                    success:function(data){
                        $(".append_appointment").html(data);
                        loading(obj,false);
                    },
                    error: function(data){
                        loading(obj,false);
                        alert("Sorry something went wrong on server.");
                    }
                });
            }
        }


    });
</script>


<style>
   .new_friend_list{
      /* overflow-x: scroll;*/
       display: -webkit-inline-box;
       width: 100%;
   }
    .search_btn{
        cursor:pointer;
    }
    .img_wrapper, .img-thumbnail, .img-thumbnail img{
        width: 60px;
        height: 60px;
    }
   .img_wrapper i{
      font-size: 46px;
       color:#f8f8f8;
   }
    .list_body li .active .img_wrapper .img-thumbnail{
        border: 2px solid rgba(162, 151, 151, 0.76) !important;

    }
    .selectpicker{display: block !important;}
    .fa-pulse{
        position: unset !important;
    }
</style>






