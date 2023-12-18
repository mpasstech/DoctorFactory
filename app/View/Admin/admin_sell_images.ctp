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
                                <?php if(!empty($sellImages)){ ?>

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
                                                <?php echo $value['is_cover_image']; ?>
                                            </td>
                                            <td>
                                                <button type="button" id="changeStatus" class="btn btn-primary btn-xs" row-id="<?php echo $value['id']; ?>" ><?php echo $value['status']; ?></button>
                                            </td>
										</tr>
                                    <?php } ?>
                                    </tbody>
                                </table>

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
                        url: baseurl+'/admin/admin/change_sell_image_status',
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
    });
</script>