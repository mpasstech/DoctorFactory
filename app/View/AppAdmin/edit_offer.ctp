<?php
$login = $this->Session->read('Auth.User');
?>

<div class="Inner-banner">
    <div class="container">
        <div class="row">
            <!--  SLIDER IMAGE -->
            <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 inner-page-text"> <h2>Edit Offer</h2> </div>
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
                        <?php echo $this->element('app_admin_inner_tab_offer'); ?>


                        <?php echo $this->Form->create('AppServiceOffer',array('type'=>'file','method'=>'post','class'=>'form-horizontal','id'=>'sub_frm')); ?>

                            <div class="col-lg-9 col-md-6 col-sm-12 col-xs-7">
                               <div class="form-group">
                                    <div class="col-sm-12">
                                        <label>Title</label>
                                        <?php echo $this->Form->input('title',array('type'=>'text','placeholder'=>'','label'=>false,'id'=>'mobile','class'=>'form-control cnt')); ?>

                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <label>Short Description &nbsp;&nbsp;<small style="color:green">Use [PRICE] = actual price and [OFFER] = offer price</small></label>
                                        <?php echo $this->Form->input('short_description',array('maxlength'=>'80','type'=>'text','placeholder'=>'','label'=>false,'class'=>'form-control cnt')); ?>                                </div>
                                </div>


                                <div class="form-group">
                                    <div class="col-sm-6">
                                        <label>Price</label>
                                        <?php echo $this->Form->input('base_amount',array('type'=>'text','placeholder'=>'','label'=>false,'class'=>'form-control cnt')); ?>                                </div>
                                    <div class="col-sm-6">
                                        <label>Offer Price</label>
                                        <?php echo $this->Form->input('offer_amount',array('type'=>'text','placeholder'=>'','label'=>false,'class'=>'form-control cnt')); ?>                                </div>

                                </div>


                                <div class="form-group">
                                    <div class="col-sm-6">
                                        <label>Start Offer</label>
                                        <?php echo $this->Form->input('start',array('type'=>'text','placeholder'=>'','label'=>false,'class'=>'start form-control cnt')); ?>                                </div>
                                    <div class="col-sm-6">
                                        <label>End Offer</label>
                                        <?php echo $this->Form->input('end',array('type'=>'text','placeholder'=>'','label'=>false,'class'=>'end form-control cnt')); ?>                                </div>

                                </div>
                                <div class="form-group">
                                    <div class="col-sm-6">
                                        <label>Status</label>
                                        <?php echo $this->Form->input('status',array('type'=>'select','label'=>false,'options'=>array('ACTIVE'=>'Active','INACTIVE'=>'Inactive'),'class'=>'form-control cnt')); ?>
                                    </div>
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

                                        <label>Offer Image</label>
                                        <a class="upload_media upload_img_icon" href="javascript:void(0)"><i class="fa fa-upload"> Upload</i></a>
                                        <div style="border-radius: 0px !important;" class="channle_img_box channel_img edit_img_icon">

                                        </div>

                                    <div class="col-sm-12">
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
        var upload_file = false;

        var is_image = $(".image_box").val();
        if(is_image != ""){
            $('.channle_img_box').css('background-image', "url('"+is_image+"')");
        }



        var newDate = new Date('<?php echo date("Y-m-d H:i",strtotime($this->request->data['AppServiceOffer']['start']));?>');
        $('.start').datetimepicker({format: 'YYYY-MM-DD HH:mm'});
        $('.start').data("DateTimePicker").date(newDate);

        var newDate = new Date('<?php echo date("Y-m-d H:i",strtotime($this->request->data['AppServiceOffer']['end']));?>');
        $('.end').datetimepicker({format: 'YYYY-MM-DD HH:mm'});
        $('.end').data("DateTimePicker").date(newDate);



        /*
                $(".datetime").datetimepicker(
                    defaultDate:new Date(''),
                    format:"YYYY-MM-dd HH:SS"
                );*/

        /*$(".datetime").datepicker({
            startDate: new Date(),
            autoclose: true
        });
*/
        $(".channel_tap a").removeClass('active');
        $("#v_add_channel").addClass('active');
        var is_image = $(".image_box").val();
        if(is_image != ""){
            $('.channle_img_box').css('background-image', "url('"+is_image+"')");
        }

        $(document).on('click','.edit_img_icon',function(e){
            $(".hidden_file_browse").trigger("click");
        });


        $(document).on('submit','#sub_frm',function(e){

            if($(".image_box").val()==""){
                alert('Please select image');
                return false;
            }
            if(upload_file==true){
                alert('Please upload offer image');
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
                    url: baseurl+"admin/admin/banner_image",
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






