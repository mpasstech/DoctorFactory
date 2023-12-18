<div class="Inner-banner">
    <div class="container">
        <div class="row">
       
            <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 inner-page-text"> <h2>Theme List</h2> </div>
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
                                <?php if(!empty($theme)){ ?>
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Background Color</th>
                                        <th>Font Size</th>
                                        <!--th>Theme Image</th-->
                                        <th>Theme Desc</th>
                                        <th>Status</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php

									$num = 1;
									foreach ($theme as $value){
                                        ?>
                                        <tr>
                                            <td><?php echo $num++; ?></td>
                                            <td><?php echo $value['AppTheme']['theme_name']; ?></td>
                                            <td><?php echo $value['AppTheme']['bg_color']; ?></td>
                                            <td><?php echo $value['AppTheme']['font_size']; ?></td>
                                            <!--td><?php echo $value['AppTheme']['theme_image']; ?></td-->
                                            <td><?php echo $value['AppTheme']['theme_desc']; ?></td>
                                            <td>
                                                <button type="button" id="changeStatus" class="btn btn-primary btn-xs" row-id="<?php echo $value['AppTheme']['id']; ?>" ><?php echo ($value['AppTheme']['theme_status'] == 1)?'ACTIVE':'INACTIVE'; ?></button>
                                            </td>
										</tr>
                                    <?php } ?>
                                    </tbody>
                                </table>
                                <?php // echo $this->element('paginator'); ?>
                                    </div>
                                </div>
                            </div>
                        <?php }else{ ?>
                            <div class="no_data">
                                <h2>No Theme Found..!</h2>
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

            /*    $(document).on('click','#changeStatus',function(e){
                    var rowID = $(this).attr('row-id');
                    var thisButton = $(this);
                    $.ajax({
                        url: baseurl+'/admin/admin/change_user_status',
                        data:{rowID:rowID},
                        type:'POST',
                        success: function(result){
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
                }); */
    });
</script>