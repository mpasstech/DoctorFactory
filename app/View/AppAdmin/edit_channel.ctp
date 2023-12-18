<?php
$login = $this->Session->read('Auth.User');
?>

<div class="Inner-banner">
    <div class="container">
        <div class="row">
            <!--  SLIDER IMAGE -->
            <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 inner-page-text"> <h2>Edit Channel</h2> </div>
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

                       <!-- --><?php /*echo $this->element('app_admin_inner_tab_channel'); */?>


                        <?php echo $this->Form->create('Channel',array('type'=>'file','method'=>'post','class'=>'form-horizontal','id'=>'sub_frm')); ?>

                        <div class="col-lg-9 col-md-6 col-sm-12 col-xs-7">
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <label>Channel Name</label>
                                    <?php echo $this->Form->input('channel_name',array('type'=>'text','placeholder'=>'Channel name','label'=>false,'id'=>'mobile','class'=>'form-control cnt')); ?>

                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <label>Channel Description</label>
                                    <?php echo $this->Form->input('channel_desc',array('type'=>'textarea','placeholder'=>'Channel description','label'=>false,'id'=>'mobile','class'=>'form-control cnt')); ?>                                </div>
                            </div>




                            <div class="col-sm-12">
                                <div class="form-group">
                                    <div class="col-sm-4">
                                        <?php echo $this->Form->submit('Save',array('class'=>'Btn-typ5','id'=>'signup')); ?>
                                    </div>

                                </div>
                            </div>





                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-12 col-xs-1">

                            <div class="form-group">

                                <label>Channel Image</label>
                                <a class="upload_media upload_img_icon" href="javascript:void(0)"><i class="fa fa-upload"> Upload</i></a>
                                <div class="channle_img_box channel_img edit_img_icon">

                                </div>

                                <div class="col-sm-12">
                                    <?php echo $this->Form->input('file',array("accept"=>"image/x-png,image/gif,image/jpeg",'type'=>'file','label'=>false,'class'=>'form-control hidden_file_browse')) ?>
                                    <?php echo $this->Form->input('image',array('type'=>'hidden','label'=>false,'class'=>'image_box')) ?>
                                    <div class="file_error"></div>
                                    <div class="file_success"></div>
                                </div>

                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <?php echo $this->Form->input('is_searchable',array('value'=>'Y','type'=>'checkbox','label'=>"Is Searchable",'class'=>'')); ?>
                                    </div>
                                    <div class="col-sm-12">
                                        <?php echo $this->Form->input('is_publish_mbroadcast',array('value'=>'Y','type'=>'checkbox','label'=>"Is publish to mobroadcast",'class'=>'')) ?>
                                    </div>
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
        $("#v_add_channel").addClass('active');
        var is_image = $(".image_box").val();
        if(is_image != ""){
            $('.channle_img_box').css('background-image', "url('"+is_image+"')");
        }
       /* $("#v_add_channel").attr("href","javascript:void(0);");
        $("#v_add_channel").html("<i class='fa fa-pencil'> </i> Edit Channel");
*/



        $(document).on('click','.edit_img_icon',function(e){
            $(".hidden_file_browse").trigger("click");
        });


        $(document).on('submit','#sub_frm',function(e){
            if(upload_file==true){
                alert('Please upload channel image');
                return false;
            }

        });

        $(document).on('change','.hidden_file_browse',function(e){
            if($(this).val()){
                $(".upload_media").show();
                readURL(this);
            }else{
                $(".upload_media").hide();
            }
        });


        function readURL(input) {

            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('.channle_img_box').css('background-image', "url('"+e.target.result+"')");
                    upload_file= true;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }


        $(document).on('click','.upload_media',function(e){

            var formData = new FormData($("#sub_frm")[0]);
            var $btn = $(this);
            $.ajax({
                type:'POST',
                url: baseurl+"admin/admin/upload",
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






