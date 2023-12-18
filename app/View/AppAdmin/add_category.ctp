<?php
$login = $this->Session->read('Auth.User');
?>

<div class="Inner-banner">
    <div class="container">
        <div class="row">
            <!--  SLIDER IMAGE -->
            <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 inner-page-text"> <h2>Create Channel</h2> </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 pull-right create-btn-box">
            </div>
        </div>
    </div>
</div>

<div class="Home-section-2">

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="container">
            <div class="row">
                <!--box 1 -->
                <!--box 1 -->
                <div class="middle-block">
                    <!-- Heading -->
                    <!--left sidebar-->

                    <!--<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                        <div class="Myaccount-left">
                            <div class="User-info-box">

                                <?php
/*                                $path =Router::url('/images/channel-icon.png',true);
                                */?>
                                <div class="user-img"><img class="channel_img" src="<?php /*echo $path; */?>" alt="user" /></div>

                            </div>
                            <div class="Myaccount-links">
                                <p><a class="upload_media" href="javascript:void(0);" ><i class="fa-image fa"> </i>&nbsp; Add Channel Image</a></p>
                            </div>

                        </div>

                    </div>-->
                    <!--left sidebar-->

                    <?php echo $this->element('app_admin_leftsidebar'); ?>

                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12 custom_form_box">

                        <?php echo $this->element('message'); ?>
                        <?php echo $this->element('app_admin_inner_tab_service'); ?>


                        <?php echo $this->Form->create('ServiceMenuCategory',array('type'=>'file','method'=>'post','class'=>'form-horizontal','id'=>'sub_frm')); ?>

                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-1">

                                <div class="form-group">
                                    <div class="col-sm-8">
                                        <label>Category Name</label>
                                        <?php echo $this->Form->input('name',array('type'=>'text','placeholder'=>'Channel name','label'=>false,'id'=>'mobile','class'=>'form-control cnt')); ?>
                                    </div>
                                    <div class="col-sm-4">
                                        <label>&nbsp;</label>
                                        <?php echo $this->Form->submit('Save',array('class'=>'Btn-typ5','id'=>'signup')); ?>
                                    </div>

                                </div>
                                <div class="form-group">
                                    <div class="col-sm-7">
                                        <label>Category Image</label>
                                        <a class="upload_media upload_img_icon" href="javascript:void(0)"><i class="fa fa-upload"> Upload</i></a>
                                        <div class="channle_img_box channel_img edit_img_icon">

                                        </div>
                                        <?php echo $this->Form->input('file',array("accept"=>"image/x-png,image/gif,image/jpeg",'type'=>'file','label'=>false,'class'=>'form-control hidden_file_browse')) ?>
                                        <?php echo $this->Form->input('image',array('type'=>'hidden','label'=>false,'class'=>'image_box')) ?>
                                        <div class="file_error"></div>
                                        <div class="file_success"></div>
                                    </div>
                                </div>





                            </div>
                        </div>
                    <?php echo $this->Form->end(); ?>
                </div>
                <!-- box 1 -->


            </div>
            <!--box 2 -->

        </div>
    </div>
</div>




<script>
    $(document).ready(function(){

        var upload = false;
        var upload_file =false;
        $(".channel_tap a").removeClass('active');
        $("#v_add_category").addClass('active');
        var is_image = $(".image_box").val();
        if(is_image != ""){
            $('.channle_img_box').css('background-image', "url('"+is_image+"')");
        }

        $(document).on('click','.edit_img_icon',function(e){
            $(".hidden_file_browse").trigger("click");
        });


        $(document).on('submit','#sub_frm',function(e){
            if(upload_file==true){
                alert('Please upload category image');
                return false;
            }

        });

        $(document).on('change','.hidden_file_browse',function(e){
            if($(this).val()){
                readURL(this);
            }else{
                $(".upload_media").hide();
            }
        });


        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    var image = new Image();
                    image.src = e.target.result;
                    image.onload = function() {
                        if(this.width==200 && this.height == 200){
                            $(".upload_media").show();
                            $(".file_error").html("");
                            $('.channle_img_box').css('background-image', "url('"+this.src+"')");
                            upload_file= true;
                        }else{
                            $(".upload_media").hide();
                            $(".file_error").html("Please upload 200 X 200 dimension image only.");
                        }
                    };
                }
                reader.readAsDataURL(input.files[0]);
            }
        }


        $(document).on('click','.upload_media',function(e){

                var formData = new FormData($("#sub_frm")[0]);
                var $btn = $(this);
                $.ajax({
                    type:'POST',
                    url: baseurl+"admin/admin/category_image",
                    data:formData,
                    cache:false,
                    contentType: false,
                    processData: false,
                    beforeSend:function(){
                        $(".file_error, .file_success").html("");
                        $('.channle_img_box').html("<img src='"+img_loader+"'>");
                    },
                    success:function(data){
                        data = JSON.parse(data);

                        //$btn.button('reset');
                        $('.channle_img_box').html("");
                        if(data.status==1){
                            $(".image_box").val(data.url);
                            //$(".channel_img").attr("src",data.url);
                            upload_file =false;
                            $(".file_success").html(data.message);
                        }else{
                            $(".file_error").html(data.message);
                        }
                    },
                    error: function(data){
                        //$btn.button('reset');
                        $('.channle_img_box').css('background-image', "url('"+oldImg+"')");
                        $(".file_error").html("Sorry something went wrong on server.");
                    }
                });

        })
    });
</script>






