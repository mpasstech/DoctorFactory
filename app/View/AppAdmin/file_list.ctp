<?php
$login = $this->Session->read('Auth.User');
?>

<div class="Inner-banner">
    <div class="container">
        <div class="row">
            <!--  SLIDER IMAGE -->
            <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 inner-page-text"> <h2>My Document</h2> </div>
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

                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="Social-login-box payment_bx">
                            <?php echo $this->element('message'); ?>
                            <?php echo $this->Form->create('Search',array('type'=>'post','url'=>array('controller'=>'app_admin','action' => 'search_file_list',"?"=>array("fi"=>$id)),'admin'=>true)); ?>
                            <div class="form-group">
                                <div class="col-sm-4">
                                    <?php echo $this->Form->input('name', array('type' => 'text', 'label' => 'Search by Name', 'class' => 'form-control')); ?>
                                </div>
                                <div class="col-sm-4">

                                    <?php
                                    $option = array(
                                        'IMAGE'=>'Image',
                                        'VIDEO'=>'Video',
                                        'AUDIO'=>'Audio',
                                        'PDF'=>'Pdf',
                                        'DOCUMENT'=>'Document',
                                        'APK'=>'Apk',
                                        'OTHER'=>'Other'
                                    );

                                    ?>

                                    <?php echo $this->Form->input('type', array('type' => 'select','empty'=>'All','options'=>$option,'label' => 'Search by Type', 'class' => 'form-control')); ?>
                                </div>

                                <div class="col-sm-4">
                                <div class="col-sm-4">
                                    <?php echo $this->Form->label('&nbsp;'); ?>
                                     <?php echo $this->Form->submit('Search',array('class'=>'Btn-typ3','id'=>'search')); ?>
                                </div>
                                <div class="col-sm-4">
                                    <?php echo $this->Form->label('&nbsp;'); ?>
                                    <a href="<?php echo Router::url('/app_admin/file_list?fi=',true).$id?>" class="Btn-typ3">Reset</a>

                                </div>
                                </div>
                            </div>
                            <?php echo $this->Form->end(); ?>

                            <div class="form-group row">
                                <div class="col-sm-12">

                            <div class="table-responsive">
                            <?php if(!empty($file_list)){ ?>
                                <ul class="dashboard_icon_li">
                                    <?php foreach ($file_list as $key  => $file){

                                        $file_type = $file['DriveFile']['file_type'];
                                        $file_name = $file['DriveFile']['file_name'];
                                        $id = $file['DriveFile']['id'];
                                        $file_path = $file['DriveFile']['file_path'];
                                        $type = explode(".",$file_path);
                                        $ext = end($type);

                                        ?>
                                        <li>
                                            <div class="content_file" data-id="<?php echo json_encode($id)?>">
                                                <div class="file_div">
                                                    <?php if($file_type=="IMAGE"){ ?>
                                                        <img src="<?php echo $file_path; ?>">
                                                    <?php }else if($file_type=="VIDEO"){?>
                                                        <video  controls>
                                                            <source src="<?php echo $file_path; ?>" type="<?php echo "video/".$ext; ?>">
                                                            Your browser does not support HTML5 video.
                                                        </video>
                                                    <?php }else if($file_type=="AUDIO"){ ?>
                                                        <audio  controls>
                                                            <source src="<?php echo $file_path; ?>" type="<?php echo "video/".$ext; ?>">
                                                            Your browser does not support HTML5 video.
                                                        </audio>
                                                    <?php }else if($file_type=="PDF"){ ?>
                                                        <a href="<?php echo $file_path; ?>" target="_blank">
                                                            <img src="<?php echo Router::url('/thinapp_images/pdf.jpg')?>">
                                                        </a>
                                                       <!-- <embed src="<?php /*echo $file_path; */?>" />-->
                                                    <?php } ?>

                                                </div>
                                                <label class="name"><?php echo $file['DriveFile']['file_name']; ?></label>
                                                <div class="action_icon">
                                                    <a href="javascript:void(0);"><i class="fa fa-trash"></i></a>
                                                    <a href="javascript:void(0);"><i class="fa fa-share-alt"></i></a>
                                                    <a href="<?php echo $file_path; ?>" class="file_download" download><i class="fa fa-download"></i></a>
                                                    <a href="<?php echo $file_path; ?>" target="_blank"><i class="fa fa-eye"></i></a>

                                                </div>

                                                <div class="confirm_icon">
                                                    <label>Aure u sure</label>
                                                    <div class="action_sure">
                                                        <a href="javascript:void(0);"><i class="fa fa-check"></i></a>
                                                        <a href="javascript:void(0);"><i class="fa fa-close"></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    <?php } ?>
                                </ul>
                                <?php echo $this->element('paginator'); ?>
                        </div>
                            <?php }else{ ?>
                                <div class="no_data">
                                    <h2>No file found.</h2>
                                </div>
                            <?php } ?>

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



<div class="modal fade" id="myModal" role="dialog">

    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <?php echo $this->Session->flash('success'); ?>
            <?php echo $this->Session->flash('error'); ?>

            <?php echo $this->Form->create('Message',array('method'=>'post','class'=>'form-horizontal msg_frm','id'=>'sub_frm')); ?>

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Post Message</h4>
            </div>
            <div class="modal-body">



                <div class="form-group">
                    <div class="col-sm-12">
                        <label>My Post here</label>
                        <?php echo $this->Form->input('message',array('type'=>'textarea','placeholder'=>'Write your message here','label'=>false,'id'=>'mobile','class'=>'form-control cnt msg_box')); ?>
                    </div>
                </div>



                <div class="form-group">

                    <div class="col-sm-3">
                        <label>Messag Type</label>
                        <?php $type_array = array(
                            'TEXT'=>'TEXT',
                            'IMAGE'=>'IMAGE',
                            'VIDEO'=>'VIDEO',
                            'AUDIO'=>'AUDIO'


                        );?>
                        <?php echo $this->Form->input('message_type',array('type'=>'select','label'=>false,'options'=>$type_array,'class'=>'form-control cnt')); ?>
                    </div>

                    <div class="col-sm-6">
                        <label>Upload Media</label>
                        <?php echo $this->Form->input('file',array('type'=>'file','label'=>false,'class'=>'form-control')) ?>
                        <?php echo $this->Form->input('message_file_url',array('type'=>'hidden','label'=>false,'class'=>'image_box')) ?>
                        <div class="file_error"></div>
                        <div class="file_success"></div>

                    </div>

                    <div class="col-sm-2">
                        <label>&nbsp;</label>
                        <?php echo $this->Form->submit('Upload',array('type'=>'button','class'=>'upload_media btn btn-success')); ?>

                    </div>


                </div>

            </div>
            <div class="modal-footer">
                <div class="show_msg_text"></div>
                <?php echo $this->Form->submit('Post Message',array('class'=>'Btn-typ3','type'=>'submit')); ?>
            </div>
            <?php echo $this->Form->end(); ?>
        </div>
    </div>

</div>





<script>
    $(document).ready(function(){


        $(".channel_tap a").removeClass('active');
        $("#v_app_channel_list").addClass('active');
        $(document).on('submit','.msg_frm',function(e){
            e.preventDefault();


            if($(".msg_box").val()==""){
                $(".msg_box").css('border-color','red');
            }else{

                var btn = $(this).find('[type=submit]');

                var id =  $("#myModal").attr("data-channel");
                $.ajax({
                    url:baseurl+"/app_admin/add_message_ajax",
                    type:'POST',
                    data:{
                        chn_id:id,
                        Message:$(".msg_frm").serialize()
                    },
                    beforeSend:function(){
                        btn.button('loading').val('Message sending...');
                    },
                    success:function(res){
                      var response = JSON.parse(res);
                        if(response.status==1){
                            $(".show_msg_text").html(response.message).css('color','green');
                            var inter = setInterval(function(){
                                $("#myModal").modal("hide");
                                clearInterval(inter);
                            },1500);
                        }else{
                            $(".show_msg_text").html(response.message).css('color','red');
                        }
                        btn.button('reset');
                    },
                    error:function () {
                        btn.button('reset');
                        $(".show_msg_text").html("Sorry something went wrong on server.").css('color','red');
                    }
                })
            }

        })
         $(document).on('click','.post_message',function(e){

        $(".show_msg_text").html('');;
        $(".msg_box").val('');;
        $("#myModal").modal('show').attr("data-channel",$(this).attr('data-channel'));

    })



    });
</script>








