
<div class="Inner-banner">
    <div class="container">
        <div class="row">
            <!--  SLIDER IMAGE -->
            <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 inner-page-text"> <h2>My Account</h2> </div>
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
                    <?php echo $this->element('app_admin_leftsidebar'); ?>
                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">

                        <?php echo $this->element('app_admin_inner_tab_header'); ?>

                        <div class="Social-login-box payment_bx">

                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <?php echo $this->element('message'); ?>
                        <?php echo $this->Form->create('Leads',array('type'=>'file')); ?>
                        <?php echo $this->Form->input('customer_id',array('type'=>'hidden'));?>


                                <?php

                                $path = Router::url('/img/android_icon.png',true);
                                $lead = $post['Leads'];
                                if(isset($lead['app_logo']) && !empty($lead['app_logo'])){
                                    $path1 = WWW_ROOT . 'uploads' . DS . 'app' . DS . $lead['app_logo'];
                                    if(file_exists($path1)){
                                        $path = Router::url('/uploads/app/',true).$lead['app_logo'];
                                    }
                                }

                                ?>
                              <div class="form-group">
                                <div class="col-sm-12 app_info_logo"  >
                                   <img width="80px" height="80px" src="<?php echo $path; ?>" />
                                </div>
                            </div>


                                <!-- <div class="form-group">
                            <div class="col-sm-12">
                                <label>App Id</label>
                                <?php /*echo $this->Form->input('app_id',array('type'=>'text','placeholder'=>'App Id','label'=>false,'class'=>'form-control cnt','id'=>'text','required'=>'required'));*/?>
                            </div>
                        </div>-->



                        <div class="form-group">
                            <div class="col-sm-12">
                                <label>App Name</label>
                                <?php echo $this->Form->input('app_name',array('type'=>'text','placeholder'=>'App Name','label'=>false,'class'=>'form-control cnt','id'=>'text'));?>
                            </div>
                        </div>


                        <div class="form-group">
                            <div class="col-sm-12">
                                <label>App Description</label>
                                <?php echo $this->Form->input('app_description',array('type'=>'textarea','placeholder'=>'App Description','label'=>false,'class'=>'form-control cnt','id'=>'text'));?>
                            </div>
                        </div>


                        <div class="form-group">
                            <div class="col-sm-12">
                                <label>Upload App Logo</label>
                                <?php echo $this->Form->input('app_logo',array("accept"=>"image/gif, image/jpeg, image/png",'type'=>'file','placeholder'=>'','label'=>false,'class'=>'form-control cnt','id'=>'UserImage','required'=>empty($post['Leads']['app_logo'])?true:false));?>

                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-12">
                                <label>App Theme</label>

                                <img style="display: none;" class="loading_theme" src="<?php echo Router::url('/img/9.gif',true);?>">
                                <?php $theme = $this->AppAdmin->getThemeDropDown(); ?>
                                <?php echo $this->Form->input('app_theme',array('type'=>'select','options'=>$theme,'empty'=>'Select Theme','placeholder'=>'','label'=>false,'class'=>'form-control cnt theme_drp','id'=>'text'));?>
                            </div>
                        </div>


                        <div class="form-group">
                            <div class="col-sm-6 col-md-offset-3">
                                <?php echo $this->Form->submit('Submit',array('class'=>'Btn-typ5','id'=>'signup')); ?>
                            </div>
                        </div>







                        <?php echo $this->Form->end();?>
                            </div>

                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">

                                <div class="APP-preview-box">
                                    <div class="app-preivew">
                                       <?php $name = $this->AppAdmin->get_theme_image($post['Leads']['app_theme']); ?>
                                        <img src="<?php echo Router::url('/uploads/theme/'.$name,true); ?>" alt="theme" />
                                    </div>
                                </div>

                            </div>

                            <div class="clear"></div>
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
    $(document).ready(function (){
        $(".app_inner_tab a").removeClass('active');
        $("#v_app_info").addClass('active');

        $(document).on('change','.theme_drp',function () {
             /*  var id = $(this).val();
               $.ajax({
                    url:baseurl+"/app_admin/get_theme",
                    type:'POST',
                    data:{id:id},
                    beforeSend:function(){

                        $(".loading_theme").show();
                    },
                    success:function(res){
                            $(".app-preivew img").attr('src',baseurl+"uploads/theme/"+res);
                            $(".loading_theme").hide();
                    },
                    error:function () {
                        $(".loading_theme").hide();
                    }
                })*/
        })




    });
</script>


