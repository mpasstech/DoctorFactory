<?php
$login = $this->Session->read('Auth.User');
?>



<div class="Home-section-2">

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="container">
            <div class="row">
                <!--box 1 -->
                <!--box 1 -->
                <div class="middle-block">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <?php echo $this->element('app_admin_inner_tab_cms'); ?>

                        <div class="Social-login-box payment_bx">

                            <?php echo $this->element('message'); ?>

                            <?php echo $this->Form->create('CmsPage',array('type'=>'file','method'=>'post','class'=>'form-horizontal','novalidate')); ?>

                            <div class="form-group">
                                <div class="col-sm-5">
                                    <label>Icon Title</label>
                                    <?php echo $this->Form->input('title',array('type'=>'text','placeholder'=>'Icon Title','label'=>false,'id'=>'mobile','class'=>'form-control cnt')); ?>

                                </div>

                                <div class="col-sm-3">
                                    <label>Page Load Type</label>
                                    <?php echo $this->Form->input('request_load_type',array('type'=>'select','label'=>false,'options'=>array('CONTENT'=>'Content','URL'=>'URL'),'class'=>'form-control link_type')); ?>
                                </div>

                                <div class="col-sm-2">
                                    <?php echo $this->Form->input('dashboard_icon_url',array('type'=>'select','label'=>"CMS Type",'options'=>$this->AppAdmin->get_dashboard_icon(),'class'=>'form-control icon_dropdown')); ?>
                                </div>

                                <div class="col-sm-1">
                                    <label style="display: block;">Icon</label>
                                    <img style="display:none; width: 60px; height: 60px;" src="" class="show_icon">
                                </div>

                            </div>

                            <div class="form-group link_div" style="display:none;">
                                <div class="col-sm-12">
                                    <label>Enter link URL</label>
                                    <?php echo $this->Form->input('url',array('type'=>'text','placeholder'=>'https://www.example.com','label'=>false,'class'=>'form-control link_input')); ?>
                                </div>
                            </div>

                            <div class="form-group content_div" style="display:none;">
                                <div class="col-sm-12">
                                    <label>Content</label>
                                    <?php echo $this->Form->input('description',array('type'=>'textarea','placeholder'=>'Channel description','label'=>false,'id'=>'editor1','class'=>'form-control content_input')); ?>
                                </div>
                            </div>



                            <div class="form-group">
                                <div class="col-sm-12" style="text-align: right;">

                                    <button type="button" onclick="window.history.back();" class="btn btn-info">Back</button>
                                    <button type="button" onclick="window.location.reload();" class="btn-warning btn" >Reset</button>
                                    <button type="submit" class="btn-success btn" >Save</button>

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





<script>
    $(document).ready(function(){
        $(".channel_tap a").removeClass('active');
        $("#v_app_cms_list").addClass('active');
        $(document).on("change",".link_type",function(){

            console.log($(this).val());
            if($(this).val()=="URL"){
                $(".content_div").val('').hide();
                $(".link_div").show();
                $(".link_input").attr('required','required');
                $(".content_input").removeAttr('required');
            }else{
                $(".content_div").show();
                $(".link_div").val('').hide();
                $(".content_input").attr('required','required');
                $(".link_input").removeAttr('required');
            }
        });

        $(document).on('reset','form',function(){
            $(".link_type").trigger("change");
        });

        $(document).on("change",".icon_dropdown",function(){
            $(".show_icon").attr('src',$(this).val()).show();
        });

        $(".link_type, .icon_dropdown").trigger("change");
        CKEDITOR.replace( 'editor1' );
    });
</script>




