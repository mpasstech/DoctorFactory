<?php
$login = $this->Session->read('Auth.User');
?>

<div class="Inner-banner">
    <div class="container">
        <div class="row">
            <!--  SLIDER IMAGE -->
            <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 inner-page-text"> <h2>My Channel</h2> </div>
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
                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12 custom_form_box">

                        <?php echo $this->element('message'); ?>
                       <!-- <?php /*echo $this->element('app_admin_inner_tab_channel_filter'); */?>

                        --><?php /*echo $this->element('app_admin_inner_tab_channel'); */?>


                            <div class="form-group row">
                                <div class="col-sm-12">

                            <div class="table table-responsive">
                            <?php if(!empty($channel)){ ?>
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Icon</th>
                                        <th>Channel Name</th>

                                        <th>Channle Type</th>
                                        <th>Description</th>
                                        <th>Subscribes</th>
                                        <th>Status</th>
                                        <th>Action</th>

                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($channel as $key => $list){
                                        if(empty($list['Channel']['image'])){
                                            $list['Channel']['image'] =Router::url('/images/channel-icon.png',true);
                                        }
                                        ?>
                                    <tr>
                                        <td><?php echo $key+1; ?></td>
                                        <td><img class="channel_icon_list" src="<?php echo $list['Channel']['image'];?>"></td>
                                        <td><?php echo $list['Channel']['channel_name']; ?></td>
                                        <td><?php echo ucfirst($list['Channel']['channel_status']); ?></td>
                                        <td><?php echo $list['Channel']['channel_desc']; ?></td>
                                        <td><?php echo $this->Custom->total_subscribe($list['Channel']['id']); ?></td>
                                        <td><?php echo ($list['Channel']['status']=="Y")?"Active":"Inactive"; ?></td>
                                        <td>
                                            <div class="action_icon">
                                                <?php

                                                if($list['Channel']['status']=="Y") {
                                                    echo $this->Html->link('', 'javascript:void(0);',
                                                        array('data-channel' => base64_encode($list['Channel']['id']), 'class' => 'fa fa-envelope post_message', 'title' => 'Post new message'));
                                                }
                                                ?>
                                            </div>

                                            <div class="action_icon">
                                            <?php

                                            echo $this->Html->link('', Router::url('/app_admin/edit_channel/',true).base64_encode($list['Channel']['id']),
                                                array('class' => 'fa fa-edit', 'title' => 'View Channel'));

                                            ?>
                                            </div>
                                                <div class="action_icon">
                                            <?php
                                            echo $this->Html->link('', Router::url('/app_admin/view_channel/',true).base64_encode($list['Channel']['id']),
                                                array('class' => 'fa fa-eye', 'title' => 'View Channel'));


                                            ?>
                                                </div>

                                        </td>
                                    </tr>
                                    <?php } ?>
                                    </tbody>
                                </table>
                                <?php echo $this->element('paginator'); ?>

                        </div>
                            <?php }else{ ?>
                                <div class="no_data">
                                    <h2>You have no channels</h2>
                                </div>
                            <?php } ?>

                                </div>

                            </div>
                            <div class="clear"></div>



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
                        <?php echo $this->Form->input('file',array('type'=>'file','label'=>false,'class'=>'form-control browse_file')) ?>
                        <?php echo $this->Form->input('message_file_url',array('type'=>'hidden','label'=>false,'class'=>'image_box')) ?>
                        <?php echo $this->Form->input('original_filename',array('type'=>'hidden','label'=>false,'class'=>'original_file')) ?>
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


        var upload_file =false;
        $(document).on('change','.browse_file',function(e){
            if($(this).val()){
                readURL(this);
            }else{
                upload_file = false;
            }
        });

        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    upload_file= true;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }


        $(document).on('submit','.msg_frm',function(e){
            e.preventDefault();

            if(upload_file==true){
                alert('Please upload file');
                return false;
            }

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
        $(document).on('click','.upload_media',function(e){


                var formData = new FormData($("#sub_frm")[0]);
                var filename = $('.browse_file').val().split('\\').pop();
                var $btn = $(this);
                $.ajax({
                    type:'POST',
                    url: baseurl+"app_admin/upload_media",
                    data:formData,
                    cache:false,
                    contentType: false,
                    processData: false,
                    beforeSend:function(){
                        $(".file_error, .file_success").html("");
                        $btn.button('loading').val('Wait..')
                    },
                    success:function(data){
                        data = JSON.parse(data);
                        $btn.button('reset');
                        if(data.status==1){
                            $(".image_box").val(data.url);
                            $(".original_file").val(filename);
                            $(".channel_img").attr("src",data.url);
                            $(".file_success").html(data.message);
                            upload_file= false;
                        }else{
                            $(".file_error").html(data.message);
                        }
                    },
                    error: function(data){
                        $btn.button('reset');
                        $(".file_error").html("Sorry something went wrong on server.");
                    }
                });




        })


        /*serach box script start*/

        var concept = $('#search_param').val();
        if(concept!=""){
            $('#search_concept').text(concept);
        }
        $('.search-panel .dropdown-menu').find('a').click(function(e) {
            e.preventDefault();
            var param = $(this).attr("href").replace("#","");
            var concept = $(this).text();
            $('.search-panel span#search_concept').text(concept);
            $('.input-group #search_param').val(param);
        });

        /*serach box script end*/


    });
</script>








