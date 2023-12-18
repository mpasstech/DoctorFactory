<?php
$login = $this->Session->read('Auth.User');
$country_list =$this->AppAdmin->countryDropdown();
?>



<div class="Home-section-2">

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="container">
            <div class="row">
                <!--box 1 -->
                <!--box 1 -->
                <div class="middle-block">

                    <h3 class="screen_title">Profile</h3>
                    <?php //echo $this->element('app_admin_leftsidebar'); ?>

                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 custom_form_box">

                        <?php echo $this->element('message'); ?>


                        <?php echo $this->Form->create('User',array('type'=>'file','method'=>'post','class'=>'form-horizontal','id'=>'sub_frm')); ?>

                        <div class="col-lg-9 col-md-6 col-sm-12 col-xs-7">
                            <div class="form-group">
                                <div class="col-sm-6">
                                    <label>Name</label>
                                    <?php echo $this->Form->input('username',array('required'=>'required','oninvalid'=>"this.setCustomValidity('Please enter  name.')",'oninput'=>"setCustomValidity('')" ,'type'=>'text','placeholder'=>'Username','label'=>false,'id'=>'mobile','class'=>'form-control')); ?>
                                </div>
                                <div class="col-sm-6">
                                    <label>Email</label>
                                    <?php echo $this->Form->input('email',array('required'=>'required','oninvalid'=>"this.setCustomValidity('Please enter email.')",'oninput'=>"setCustomValidity('')" ,'type'=>'email','placeholder'=>'Email','label'=>false,'id'=>'mobile','class'=>'form-control cnt')); ?>
                                </div>

                            </div>

                            <div class="form-group">
                                <div class="col-sm-6">
                                    <label>Mobile</label>
                                    <?php echo $this->Form->input('mobile',array('type'=>'text','placeholder'=>'Address goes here','label'=>false,'class'=>'form-control cnt','readonly'=>'readonly')); ?>
                                </div>
                                <div class="col-sm-6">

                                    <label>Education</label>
                                    <?php echo $this->Form->input('education',array('type'=>'text','placeholder'=>'Education goes here','label'=>false,'class'=>'form-control cnt')); ?>

                                </div>



                            </div>


                            <div class="form-group">

                                <div class="col-sm-6">
                                    <label>Fees</label>
                                    <?php echo $this->Form->input('fees',array('type'=>'text','label'=>false,'class'=>'form-control')); ?>
                                </div>

                                <div class="col-sm-6">
                                    <label>Category</label>
                                    <?php $type_array = $this->AppAdmin->getDepartmentList("Doctor"); ?>
                                    <?php echo $this->Form->input('department_category_id',array('required'=>'required','oninvalid'=>"this.setCustomValidity('Please select category.')",'oninput'=>"setCustomValidity('')" ,'empty'=>'Select Category','type'=>'select','label'=>false,'options'=>$type_array,'class'=>'form-control message_type cnt')); ?>
                                </div>

                            </div>



                            <div class="form-group">
                                <div class="col-sm-6">
                                    <label>Country</label>
                                    <?php echo $this->Form->input('country_id',array('required'=>'required','oninvalid'=>"this.setCustomValidity('Please select country.')",'oninput'=>"setCustomValidity('')" ,'type'=>'select','label'=>false,'empty'=>'Select Country','options'=>$country_list,'class'=>'form-control country')); ?>
                                </div>
                            <div class="col-sm-6">
                                <?php $state_list =$this->AppAdmin->stateDropdown($this->request->data['User']['country_id']);?>
                                <label>State <i class="fa fa-spinner fa-spin state_spin" style="display:none;"></i> </label>
                                <?php echo $this->Form->input('state_id',array('required'=>'required','oninvalid'=>"this.setCustomValidity('Please select state.')",'oninput'=>"setCustomValidity('')" ,'type'=>'select','label'=>false,'empty'=>'Select State','options'=>$state_list,'class'=>'form-control state')); ?>
                            </div>


                            </div>


                            <div class="form-group">

                                <div class="col-sm-6">
                                    <?php $city_list =$this->AppAdmin->cityDropdown($this->request->data['User']['state_id']);?>
                                    <label>City <i class="fa fa-spinner fa-spin city_spin" style="display:none;"></i></label>
                                    <?php echo $this->Form->input('city_id',array('required'=>'required','oninvalid'=>"this.setCustomValidity('Please select city.')",'oninput'=>"setCustomValidity('')" ,'type'=>'select','label'=>false,'empty'=>'Select City','options'=>$city_list,'class'=>'form-control city')); ?>
                                </div>

                                <?php $exp = explode('.',$this->request->data['User']['experience']);?>
                                <div class="col-sm-3">
                                    <?php $year =array();for($counter=0;$counter<=150;$counter++)$year[]=$counter; ?>
                                    <label>Total Year Exp.</label>
                                    <?php echo $this->Form->input('year',array('required'=>'required','oninvalid'=>"this.setCustomValidity('Please select year experience.')",'oninput'=>"setCustomValidity('')" ,'value'=>@$exp[0],'type'=>'select','label'=>false,'empty'=>'Select Year','options'=>$year,'class'=>'form-control year')); ?>
                                </div>
                                <div class="col-sm-3">
                                    <label>Total Month Exp.</label>
                                    <?php $month =array();for($counter=0;$counter<=12;$counter++)$month[]=$counter; ?>
                                    <?php echo $this->Form->input('month',array('required'=>'required','oninvalid'=>"this.setCustomValidity('Please select month experience.')",'oninput'=>"setCustomValidity('')" ,'value'=>@$exp[1],'type'=>'select','label'=>false,'empty'=>'Select Month','options'=>$month,'class'=>'form-control month')); ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-6">
                                    <label>Where you Working ?</label>
                                    <?php echo $this->Form->input('current_working',array('type'=>'text','placeholder'=>'Answer goes here','label'=>false,'class'=>'form-control')); ?>
                                </div>
                                <div class="col-sm-6">
                                    <label>Registration Number</label>
                                    <?php echo $this->Form->input('registration_number',array('type'=>'text','label'=>false,'class'=>'form-control')); ?>
                                </div>

                            </div>





                            <div class="form-group">
                                <div class="col-sm-12">

                                    <label>Address</label>
                                    <?php echo $this->Form->input('address',array('type'=>'text','placeholder'=>'Address goes here','label'=>false,'class'=>'form-control cnt')); ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-12">
                                    <label>About Me</label>
                                    <?php echo $this->Form->input('about_user',array('row'=>'10','type'=>'textarea','placeholder'=>'About user','label'=>false,'class'=>'form-control cnt')); ?>                                </div>
                            </div>

                            <div class="col-sm-12">
                                <div class="form-group">
                                    <div class="col-sm-4">
                                        <?php echo $this->Form->submit('Update',array('class'=>'Btn-typ5','id'=>'signup')); ?>
                                    </div>

                                </div>
                            </div>





                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-12 col-xs-1">

                            <div class="form-group">

                                <label>Profile Image</label>
                                <a class="upload_media upload_img_icon" href="javascript:void(0)"><i class="fa fa-upload"> Upload</i></a>
                                <div class="channle_img_box channel_img edit_img_icon">

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

        $(document).on('change','.country',function(e){
            var country_id =$(this).val();
            if(country_id){
                $.ajax({
                    type:'POST',
                    url: baseurl+"app_admin/get_state_list",
                    data:{country_id:country_id},
                    beforeSend:function(){

                        $('.state_spin').show();
                        $('.city, .state').attr('disabled',true).html('');
                    },
                    success:function(data){
                        $('.state_spin').hide();
                        $('.state').html(data);;
                        $('.city, .state').attr('disabled',false);
                       // $(".state").trigger('change');
                    },
                    error: function(data){
                    }
                });
            }

        });


        $(document).on('change','.state',function(e){
            var state_id =$(this).val();
            if(state_id){
                $.ajax({
                    type:'POST',
                    url: baseurl+"app_admin/get_city_list",
                    data:{state_id:state_id},
                    beforeSend:function(){
                        $('.city_spin').show();
                        $('.city').attr('disabled',true).html('');
                    },
                    success:function(data){
                        $('.city_spin').hide();
                        $('.city').attr('disabled',false).html(data);
                    },
                    error: function(data){
                    }
                });
            }

        });


        if($(".country").val()==""){
           // $(".country").trigger('change');
        }


        $(document).on('click','.upload_media',function(e){

            var formData = new FormData($("#sub_frm")[0]);
            var $btn = $(this);
            $.ajax({
                type:'POST',
                url: baseurl+"admin/admin/image_upload",
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






