<?php
$login = $this->Session->read('Auth.User');
?>




<div class="Home-section-2" >

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="container">
            <div class="row">
                <!--box 1 -->
                <!--box 1 -->
                <div class="middle-block">
                    <div class="col-lg-8">
                        <?php echo $this->element('app_admin_inner_tab_cms'); ?>
                        <div class="Social-login-box payment_bx manage_cms_box">

                            <?php echo $this->element('message'); ?>

                            <?php echo $this->Form->create('CmsPage',array('type'=>'file','method'=>'post','class'=>'form-horizontal save_template_form','enctype'=>'multipart/form-data')); ?>

                            <input type="hidden" name="dashboard_icon_url" class="form-control dashboard_icon_url" >

                            <div class="form-group">
                                <div class="col-sm-10">
                                    <lable>CMS Type</lable>
                                    <select class="form-control icon_dropdown" >
                                        <option  value="0">Please Select Type</option>
                                        <?php $option_list = $this->AppAdmin->get_dashboard_icon(); ?>
                                        <?php foreach($option_list as $key => $value){ ?>

                                            <option data-icon="<?php echo $value['url']?>" value="<?php echo $value['id']?>"><?php echo $value['title']?></option>
                                        <?php } ?>

                                    </select>


                                </div>
                                <div class="col-sm-2" style="margin: 10px 0px;">
                                    <img style="display:none; background: #fff; position: absolute; width: 60px; height: 60px;" src="<?php echo Router::url('/images/loading_tran.gif',true); ?>" class="loader_icon">
                                    <img style="display:none; width: 60px; height: 60px;" src="" class="show_icon">
                                </div>





                            </div>




                            <div class="form-group" id="content_body_div">



                            </div>





                            <?php echo $this->Form->end(); ?>


                        </div>
                    </div>
                    <div class="col-lg-4 mobile_box" >
                        <img src="<?php echo Router::url('/cms_theme/assets/images/mobile_emulator.png',true);?>" style="width:100%;height: 100%;" />

                            <iframe class="prev_iframe" style="display: <?php echo !empty($cms_id)?'block':'none';?>" allowfullscreen="" frameborder="0"  src="<?php echo Router::url('/cms/view_cms/',true).base64_encode($cms_id); ?>"></iframe>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<style>


    .collapse{
        float: left !important;
        margin-left: 13% !important;
    }
    .mobile_box{
        position: fixed;
        float: right;
        right: 0px;
        height: 633px;
        width: 506px;
        top: 0px;
        z-index: 9999;

    }
    .channel_tap a{
        width: 50% !important;
    }

    .manage_cms_box .form-group{
        margin-bottom: 0px;
    }


    .manage_cms_box input,
    .manage_cms_box select{
        width: 100%;
        height: 34px;
        padding: 3px 7px;
        margin-bottom: 0px;
    }

    .prev_iframe{
        position: absolute;
        top: 70px;
        width: 375px;
        left: 65px;
        height: 449px;
        border: unset;
    }

</style>

<script>
    $(document).ready(function(){



        $(".channel_tap a").removeClass('active');
        $("#v_add_cms").addClass('active');

        $(document).on("change",".icon_dropdown",function(){


            if($(this).val() > 0){
                $(".dashboard_icon_url").val($(".icon_dropdown option:selected").attr('data-icon'));
                $(".show_icon").attr('src',$(".icon_dropdown option:selected").attr('data-icon')).show();

                var page_category_id =  btoa($(this).val());
                $.ajax({
                    type:'POST',
                    url: baseurl+"cms/cms_body",
                    data:{pci:page_category_id},
                    beforeSend:function(){
                        $(".loader_icon").show();
                    },
                    success:function(data){
                        $(".loader_icon").hide();
                        $("#content_body_div").html(data);
                    },
                    error: function(data){
                        $(".loader_icon").hide();
                        $.alert("Sorry something went wrong on server.");
                    }
                });
            }else{
                $("#content_body_div").html("");
                $(".dashboard_icon_url").val("");
            }

        });
        var page_category_id = "<?php echo $page_category_id; ?>";
        $(".icon_dropdown").val(page_category_id);


        $(".icon_dropdown").trigger("change");



    });



</script>




