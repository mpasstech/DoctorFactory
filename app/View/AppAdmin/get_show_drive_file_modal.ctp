<div class="modal-dialog modal-md">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">File</h4>
        </div>
        <div class="modal-body">





            <div class="row">


                        <?php
                            $file_type = $file['DriveFile']['file_type'];
                            $file_name = $file['DriveFile']['file_name'];
                            $id = $file['DriveFile']['id'];
                            $folder_id = $file['DriveFile']['drive_folder_id'];
                            $file_path = $file['DriveFile']['file_path'];
                            $type = explode(".",$file_path);
                            $ext = end($type);
                        ?>
                        <div class="col-sm-12 row_box">

                            <?php if($file['DriveFile']['listing_type']=="OTHER"){ ?>

                                <a href="<?php echo $file_path; ?>" title="View file" target="_blank">
                                    <div class="file_box_container">
                                        <div class="file_label"><?php echo $file_type; ?></div>
                                        <?php
                                        $string ="";
                                        if($file_type=="IMAGE"){
                                            $string = "background-image :url('$file_path')";
                                        }
                                        ?>
                                        <div class="image_box" data-folder="<?php echo base64_encode($folder_id); ?>" data-id="<?php echo ($id); ?>" >
                                            <?php if($file_type=="VIDEO"){  $type = explode(".",$file_path);  ?>

                                                <video class="video">
                                                    <source src="<?php echo $file_path; ?>" type="video/<?php echo end($type); ?>">
                                                    Your browser does not support the video tag.
                                                </video>


                                            <?php }
                                            else if($file_type=="IMAGE"){ ?>
                                                <img src="<?php echo $file_path; ?>" class="file_img">
                                            <?php }
                                            else if($file_type=="AUDIO"){ ?>
                                                <div class="icon">
                                                    <i class="fa fa-music"></i>
                                                </div>
                                            <?php }else if($file_type=="PDF"){ ?>
                                                <div class="icon">
                                                    <i class="fa fa-file-pdf-o"></i>
                                                </div>
                                            <?php }else if($file_type=="DOCUMENT" || $file_type=="OTHER"){ ?>
                                                <div class="icon">
                                                    <i class="fa fa-file-o"></i>
                                                </div>
                                            <?php } ?>


                                        </div>
                                        <div class="file_last_label"><?php echo $file['DriveFile']['file_name']; ?></div>

                                    </div>
                                </a>

                            <?php }else{ ?>
                                <div class="file_box_container">
                                    <div class="file_label"><?php echo $file['DriveFile']['listing_type']; ?></div>
                                    <?php
                                    $string = "";

                                    ?>
                                    <div class="image_box" style="<?php echo  $string; ?>" data-folder="<?php echo base64_encode($folder_id); ?>" data-id="<?php echo base64_encode($id); ?>" >
                                        <div class="icon">
                                            <i style="color:<?php echo $file['DriveFile']['memo_label']; ?>" class="fa fa-file-text"></i>
                                        </div>
                                        <div class="file_data_content">
                                            <div class="file-name">

                                                <?php echo mb_strimwidth($file['DriveFile']['memo_text'], 0, 200, '...'); ?>
                                                <br>
                                                <small><i class="fa fa-calendar"></i>  <?php echo date('d-m-Y H:i',strtotime($file['DriveFile']['created'])); ?></small>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="file_last_label"><?php echo $file['DriveFile']['memo_text']; ?></div>

                                </div>
                            <?php } ?>


                        </div>


            </div>


            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>
<style>
    .file_img {

        width: 100%;

    }
</style>