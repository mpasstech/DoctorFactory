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
