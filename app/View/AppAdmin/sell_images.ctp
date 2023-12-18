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
                   <?php echo $this->element('app_admin_leftsidebar'); ?>
                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                        <div class="Social-login-box payment_bx">

                            <?php echo $this->element('message'); ?>
                            <div class="form-group row">
                                <div class="col-sm-12">
                                    <div class="table-responsive">
                                <?php if(!empty($sellImages)){ ?>
                                        <form method="post">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Item Name</th>
                                        <th>Image</th>
                                        <th>Is cover image</th>
                                        <th>Status</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php

									$num = 1;
									foreach ($sellImages['SellImage'] as $value){
                                        ?>
                                        <tr>
                                            <td><?php echo $num++; ?></td>
                                            <td><?php echo $sellImages['SellItem']['item_name']; ?></td>
                                            <td>
                                                <a href="<?php echo $value['path']; ?>">
                                                <img src="<?php echo $value['path']; ?>" style="width: 150px;">
                                                </a>
                                            </td>
                                            <td>
                                                    <input type="radio" name="is_cover_image" value="<?php echo $value['id']; ?>" <?php echo ($value['is_cover_image'] == 'YES')?'checked':''; ?> >

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
                                                <div class="col-sm-6">
                                                    <label>Upload Media</label>
                                                    <div class="input file"><input type="file" id="MessageFile" class="form-control" name="data[SellImage][file]"></div>
                                                    <div class="file_error"></div>
                                                    <input type="hidden" name="data[SellImage][sell_item_id]" value="<?php echo $sellImages['SellItem']['id']; ?>">
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
                                <h2>No Image Found..!</h2>
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
                    var sellImageID = $(this).attr('row-id');
                    var thisButton = $(this);
                    $.ajax({
                        url: baseurl+'/app_admin/change_sell_image_status',
                        data:{sellImageID:sellImageID},
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
                var imageID = $(this).val();
                $.ajax({
                        url: baseurl+'/app_admin/update_sell_image_cover',
                        data:{imageID:imageID,sellItemID:<?php echo $sellImages['SellItem']['id']; ?>},
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
                        url: baseurl+"app_admin/upload_sell_image",
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