<?php
$login = $this->Session->read('Auth.User');
?>

<div class="Inner-banner">
    <div class="container">
        <div class="row">
            <!--  SLIDER IMAGE -->
            <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 inner-page-text"> <h2>Edit Member </h2> </div>
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


                    <!--left sidebar-->

                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <?php echo $this->element('app_admin_inner_tab_staff'); ?>


                        <div class="Social-login-box payment_bx">

                            <?php echo $this->element('message'); ?>

                            <?php
                            $path = Router::url('/images/channel-icon.png');
                            if(!empty($post['AppStaff']['image'])){
                                $path =  $post['AppStaff']['image'];
                            }
                            ?>

                            <div class="staff_img_div"><img src="<?php echo $path; ?>"> </div>

                            <?php echo $this->Form->create('AppStaff',array('type'=>'file','method'=>'post','class'=>'form-horizontal','id'=>'sub_frm')); ?>


                            <div class="form-group">
                                <div class="col-sm-6">
                                    <label>Full Name</label>
                                    <?php echo $this->Form->input('fullname',array('type'=>'text','placeholder'=>'Full name','label'=>false,'class'=>'form-control cnt')); ?>

                                </div>
                                <div class="col-sm-6">
                                    <label>Email</label>
                                    <?php echo $this->Form->input('email',array('type'=>'email','placeholder'=>'Email','label'=>false,'class'=>'form-control cnt')); ?>

                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-6">
                                    <label>Mobile Number</label>
                                    <?php echo $this->Form->input('mobile',array('id'=>'mobile','type'=>'text','placeholder'=>'Enter mobile number','label'=>false,'class'=>'form-control cnt')); ?>    </div>
                                <div class="col-sm-6">
                                    <label>Alternate Mobile Number</label>
                                    <?php echo $this->Form->input('alt_mobile',array('type'=>'number','placeholder'=>'Alternate Mobile Number','label'=>false,'class'=>'form-control cnt')); ?>
                                </div>
                            </div>


                            <div class="form-group">
                                <div class="col-sm-6">
                                    <label>Designation</label>
                                    <?php echo $this->Form->input('designation',array('type'=>'text','placeholder'=>'Designation','label'=>false,'class'=>'form-control cnt')); ?>
                                </div>
                                <div class="col-sm-6">
                                    <label>Address</label>
                                    <?php echo $this->Form->input('address',array('type'=>'text','placeholder'=>'Address','label'=>false,'class'=>'form-control cnt')); ?>
                                </div>
                            </div>


                            <div class="form-group">
                                <div class="col-sm-6">
                                    <label>Upload photo</label>
                                    <?php echo $this->Form->input('image',array('type'=>'file','label'=>false,'class'=>'form-control','required'=>false)) ?>
                                </div>
                                <div class="col-sm-6">
                                    <label>Facebook</label>
                                    <?php echo $this->Form->input('facebook',array('type'=>'text','placeholder'=>'Facebook','label'=>false,'class'=>'form-control cnt')); ?>
                                </div>
                            </div>


                            <div class="form-group">
                                <div class="col-sm-6">
                                    <label>Twitter</label>
                                    <?php echo $this->Form->input('twitter',array('type'=>'text','placeholder'=>'Twitter','label'=>false,'class'=>'form-control cnt')); ?>
                                </div>
                                <div class="col-sm-6">
                                    <label>Linkedin</label>
                                    <?php echo $this->Form->input('linkedin',array('type'=>'text','placeholder'=>'Linkedin','label'=>false,'class'=>'form-control cnt')); ?>
                                </div>
                            </div>


                            <div class="form-group">
                                <div class="col-sm-12">
                                    <label>About this staff</label>
                                    <?php echo $this->Form->input('description',array('type'=>'textarea','placeholder'=>'About staff','label'=>false,'class'=>'form-control cnt')); ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-3 pull-right">
                                    <?php echo $this->Form->submit('Save',array('class'=>'Btn-typ5','id'=>'signup')); ?>
                                </div>
                            </div>



                            <?php echo $this->Form->end(); ?>


                        </div>



                    </div>






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
        $(".channel_tap a").removeClass('active');
        $("#v_add_staff").addClass('active');

        $("#mobile").on("countrychange", function(e, countryData) {
            $("#mobile").trigger("keyup");
        });

        $(document).on("keyup blur","#mobile", function() {
            //console.log($("#phone").intlTelInput("getNumber",intlTelInputUtils.numberFormat.E164));
            if($(this).intlTelInput("isValidNumber")){
                $(this).css('border-color',"#ccc");
                var mob = $("#mobile").intlTelInput("getNumber",intlTelInputUtils.numberFormat.E164);
                $("#mobile_2").val(mob);
            }else{
                $(this).css('border-color',"red");
                $("#mobile_2").val('');
            }
        });


        $(document).on("submit","#sub_frm", function() {
            //console.log($("#phone").intlTelInput("getNumber",intlTelInputUtils.numberFormat.E164));
            if($("#mobile").intlTelInput("isValidNumber")){
                var mob = $("#mobile").intlTelInput("getNumber",intlTelInputUtils.numberFormat.E164);
                $("#mobile").val(mob);
            }else{
                alert("Enter valid mobile number");
                return false;
            }
        });


        $.get("https://ipinfo.io", function(response) {
            //console.log(response.country);
            //console.log(response.city);
            $("#mobile").intlTelInput({
                allowExtensions: true,
                autoFormat: false,
                autoHideDialCode: false,
                autoPlaceholder:  false,
                initialCountry: response.country,
                ipinfoToken: "yolo",
                nationalMode: true,
                numberType: "MOBILE",
                //onlyCountries: ['us', 'gb', 'ch', 'ca', 'do'],
                //preferredCountries: ['cn', 'jp'],
                preventInvalidNumbers: true,
                utilsScript: "<?php echo Router::url('/js/utils.js',true); ?>"
            });
        }, "jsonp");


    });
</script>






