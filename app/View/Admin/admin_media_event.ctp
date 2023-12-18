<div class="Inner-banner">
    <div class="container">
        <div class="row">
       
            <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 inner-page-text"> <h2>Media List</h2> </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 pull-right create-btn-box">
            </div>
        </div>
    </div>
</div>
<section class="Home-section-2">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="container">
            <div class="row">
                <div class="middle-block">
                   <?php echo $this->element('admin_leftsidebar'); ?>
                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                        <div class="Social-login-box payment_bx">

                            <?php echo $this->element('message'); ?>
                            <div class="form-group row">
                                <div class="col-sm-12">
                                    <div class="table-responsive">
                                <?php if(!empty($eventMedia)){ ?>

                        <form method="post">

                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Event title</th>
                                        <th>Media</th>
                                        <th>Type</th>
                                        <th>Is cover image</th>
                                        <th>Status</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php

									$num = 1;
									foreach ($eventMedia['EventMedia'] as $value){
                                        ?>
                                        <tr>
                                            <td><?php echo $num++; ?></td>
                                            <td><?php echo $eventMedia['Event']['title']; ?></td>
                                            <td>
                                                <?php if($value['media_type'] == 'IMAGE'){ ?>
                                                <a href="<?php echo $value['media_path']; ?>">
                                                <img src="<?php echo $value['media_path']; ?>" style="width: 150px;">
                                                </a>
                                                <?php }else{ ?>
                                                    <video width="150" controls>
                                                        <source src="<?php echo $value['media_path']; ?>" type="video/mp4">
                                                        <source src="<?php echo $value['media_path']; ?>" type="video/wmv">
                                                        Your browser does not support HTML5 video.
                                                    </video>
                                                <?php } ?>
                                            </td>
                                            <td><?php echo $value['media_type']; ?></td>
                                            <td>
                                                <?php if($value['media_type'] == 'IMAGE'){?>
                                                    <input type="radio" name="is_cover_image" value="<?php echo $value['id']; ?>" <?php echo ($value['is_cover_image'] == 'YES')?'checked':''; ?> >
                                                <?php } ?>
                                            </td>
                                            <td>
                                                <button type="button" id="changeStatus" class="btn btn-primary btn-xs" row-id="<?php echo $value['id']; ?>" ><?php echo $value['status']; ?></button>
                                            </td>
										</tr>
                                    <?php } ?>
                                    </tbody>
                                </table>
                        </form>

<form method="post" id="sub_frm" enctype="multipart/form-data">

    <div class="form-group">

        <div class="col-sm-3">
            <label>Media Type</label>
            <div class="input select">
                <select id="MessageMessageType" class="form-control cnt" name="data[Message][message_type]">
                    <option value="IMAGE">IMAGE</option>
                    <option value="VIDEO">VIDEO</option>
                </select>
                <input type="hidden" name="data[Message][event_id]" value="<?php echo $eventMedia['Event']['id']; ?>">
            </div>
        </div>

        <div class="col-sm-6">
            <label>Upload Media</label>
            <div class="input file"><input type="file" id="MessageFile" class="form-control" name="data[Message][file]"></div>                        <input type="hidden" id="MessageMessageFileUrl" class="image_box" name="data[Message][message_file_url]">                        <div class="file_error"></div>
            <div class="file_success"></div>
        </div>

        <div class="col-sm-2">
            <label>&nbsp;</label>
            <div class="submit"><input type="button" value="Upload" class="upload_media btn btn-success"></div>
        </div>

    </div>

</form>



                                    </div>
                                </div>
                            </div>
                        <?php }else{ ?>
                            <div class="no_data">
                                <h2>No Media Found..!</h2>
                            </div>
                        <?php } ?>
                            <div class="clear"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>



<script>
    $(document).ready(function(){
                $(document).on('click','#changeStatus',function(e){
                    var eventMediaID = $(this).attr('row-id');
                    var thisButton = $(this);
                    $.ajax({
                        url: baseurl+'/admin/admin/change_event_media_status',
                        data:{eventMediaID:eventMediaID},
                        type:'POST',
                        beforeSend:function(){
                            $(thisButton).button('loading').html('<i class="fa fa-spinner fa-pulse fa-2x fa-fw">');
                        },
                        success: function(result){
                            $(thisButton).button('reset');
                            var result = JSON.parse(result);
                            if(result.status == 1)
                            {
                                $(thisButton).text(result.text);
                            }
                            else
                            {
                                alert('Sorry, Could not change status!');
                            }


                        }
                    });
                });

                $(document).on('click','[name="is_cover_image"]',function(e){
                    var eventMediaID = $(this).val();
                    $.ajax({
                        url: baseurl+'/admin/admin/update_event_media_cover',
                        data:{eventMediaID:eventMediaID,eventID:<?php echo $eventMedia['Event']['id']; ?>},
                        type:'POST',
                        success: function(result){
                            var result = JSON.parse(result);
                            if(result.status != 1)
                            {
                                alert('Sorry, Could not update!');
                            }
                        }
                    });
                });




        $(document).on('click','.upload_media',function(e){
            var formData = new FormData($("#sub_frm")[0]);
            var $btn = $(this);
            $.ajax({
                type:'POST',
                url: baseurl+"admin/admin/upload_event_media",
                data:formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend:function(){
                    $(".file_error, .file_success").html("");
                    $btn.button('loading').html('<i class="fa fa-spinner fa-pulse fa-2x fa-fw">');
                },
                success:function(data){
                    data = JSON.parse(data);
                    $btn.button('reset');

                    if(data.status==1){
                        $(".file_success").html(data.message);
                        location.reload();
                    }else{
                        $(".file_error").html(data.message);
                    }
                },
                error: function(data){
                    $btn.button('reset');
                    $(".file_error").html("Sorry something went wrong on server.");
                }
            });
        });




    });
</script>