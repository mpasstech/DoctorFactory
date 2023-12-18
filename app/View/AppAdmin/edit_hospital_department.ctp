<?php
$login = $this->Session->read('Auth.User');
?>

<style>
    .channle_img_box{
        width: 100% !important;
    }
</style>

<div class="Home-section-2">

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="container">

            <div class="row">

                <div class="middle-block">
                    <h3 class="screen_title"> Edit Deparment</h3>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 custom_form_box">

                    <?php echo $this->element('hospital_department_inner_tab'); ?>



                            <div class="tab-content">
                                <?php echo $this->element('message'); ?>


                                <?php echo $this->Form->create('AppointmentCategory',array('type'=>'file','method'=>'post','class'=>'form-horizontal sub_frm','id'=>'sub_frm')); ?>
                                <div class="col-sm-12">


                                    <div class="col-sm-2">

                                        <div class="form-group">

                                            <label>Upload Department Image</label>
                                            <?php
                                                    $path  = $this->request->data['AppointmentCategory']['image'];
                                                    $css = "";
                                                    if(!empty($path)){
                                                        $css = "background:url('$path')";
                                                    }
                                            ?>
                                            <a class="upload_media upload_img_icon" href="javascript:void(0)"><i class="fa fa-upload"> Upload</i></a>
                                            <div class="channle_img_box channel_img edit_img_icon" style="background-repeat:no-repeat;background-size:cover !important;<?php echo $css; ?>">

                                            </div>

                                            <div class="col-sm-12">
                                                <?php echo $this->Form->input('file',array("accept"=>"image/x-png,image/gif,image/jpeg",'type'=>'file','label'=>false,'class'=>'form-control hidden_file_browse')) ?>
                                                <?php echo $this->Form->input('image',array('type'=>'hidden','label'=>false,'class'=>'image_box','value'=>@$this->request->data['AppointmentCategory']['image'])) ?>
                                                <div class="file_error"></div>
                                                <div class="file_success"></div>
                                            </div>

                                        </div>

                                    </div>

                                    <div class="col-sm-4">

                                        <div class="col-sm-12">
                                            <?php echo $this->Form->input('name',array('type'=>'text','placeholder'=>'','label'=>'Enter Department Name','class'=>'form-control cnt','required'=>'required')); ?>

                                        </div>
                                        <div class="col-sm-4">
                                            <?php echo $this->Form->label('&nbsp;'); ?>
                                            <?php echo $this->Form->submit('Add',array('class'=>'btn btn-info col-sm-12')); ?>

                                        </div>

                                    </div>



                                    <?php echo $this->Form->end(); ?>



                                </div>

                            </div>
                    </div>






                </div>



            </div>


        </div>
    </div>
</div>




<script>
    $(document).ready(function(){


        $(document).on('click','.edit_img_icon',function(e){
            $(".hidden_file_browse").trigger("click");
        });


        $(document).on('submit','#sub_frm',function(e){
            if(upload_file==true){
                $.alert('Please upload profile image');
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
                url: baseurl+"app_admin/upload_department_image",
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

        $(".channel_tap a").removeClass('active');
        $("#add_cat_tab").addClass('active');




        $(document).on('click','.upload_media',function(e){
            $("#myModal").modal('show');
        })
        $(document).on('click','.add_more',function(e){
            $(".group_div").append($(".clone_div").html());
            $(".group_div .number_div:last").find(".delete_number").show();
        })



        $(document).on('submit','.sub_frm',function(e){
            var ret = [];
            $(".mobile_div input").each(function () {

                if(/^[0-9]{4,13}$/.test($(this).val())){
                    $(this).css('border-color','#ccc');
                }else{
                    $(this).css('border-color','red');
                    ret.push(0);
                }
            });

            if($.inArray(0,ret) == -1){
                return true;
            }
            return false;

        })

        $(document).on('submit','.sub_frm_2',function(e){
                var countryData = $("#mobile_2").intlTelInput("getSelectedCountryData");
                $("#mobile_2").val("+"+countryData.dialCode);
        });




        $(document).on('click','.delete_number',function(e){

            var len = $(".number_div").length;
            if(len >1 ){
                $(this).closest(".number_div").remove();
            }

        })






    });
</script>



