<?php
$n = 1;
if(!empty($list_data)){
foreach ($list_data as $file) {
    if ($n == 1) { ?><div class="row"><?php }
    if ($file['file_type'] == 'IMAGE') { ?>
        <div class="col-12 box_inner">
            <div class="file-box">
                <div class="file"><a href="<?php echo $file["file_path"]; ?>" target="_blank" download>
                        <span class="corner"></span>
                        <div class="image"><img alt="image" onload="loadImag(this);" class="img-responsive load_img"  src="<?php echo $this->AppAdmin->beforeLodeImage(); ?>" data-src="<?php echo $file["file_path"]; ?>"></div>
                        <div class="file-name">
                           <!-- --><?php /*echo str_replace(array("'", "\"", "&quot;"), "", htmlspecialchars($file["file_name"])); */?>
                            <p style="margin-bottom: 0.1rem;">
                                <?php echo $file['app_name'].' - '.$file['patient_name']; ?>
                            </p>
                            <small><i class="fa fa-calendar"></i>
                                Date: <?php echo date("M d,Y", strtotime($file['created'])); ?>
                            </small>
                            <br>
                            <small>Added By: <?php echo str_replace(array("'", "\"", "&quot;"), "", htmlspecialchars($file["username"])); ?></small>
                        </div>
                    </a></div>
            </div>
        </div>
    <?php } else {
        $icon = "";
        if ($file['file_type'] == 'DOCUMENT') {
            $icon = "fa-file";
        } else if ($file['file_type'] == 'PDF') {
            $icon = "fa-file-pdf-o";
        } else if ($file['file_type'] == 'VIDEO') {
            $icon = "fa-play";
        } else if ($file['file_type'] == 'AUDIO') {
            $icon = "fa-headphones";
        } else if ($file['file_type'] == 'APK') {
            $icon = "fa-android";
        } else {
            if ($file['listing_type'] == 'MEMO') {
                ?>
                <div class="col-12 box_inner">
                    <div class="file-box">
                        <div class="file"><a href="javascript:void(0)">
                                <div class="image memoTex"><p
                                            style="color: <?php echo ($file['DriveFile']['memo_label'] == 'YELLOW') ? "#eddd0d" : $file['DriveFile']['memo_label']; ?>;"><?php echo str_replace(array("'", "\"", "&quot;"), "", htmlspecialchars($file['memo_text'])); ?></p>
                                </div>
                                <div class="file-name">
                                    <p style="margin-bottom: 0.1rem;">
                                        <?php echo $file['app_name'].' - '.$file['patient_name']; ?>
                                    </p>
                                    <small>
                                        Added: <?php echo date("M d,Y", strtotime($file['created'])); ?></small>
                                    <br>
                                    <small>Created
                                        By: <?php echo str_replace(array("'", "\"", "&quot;"), "", htmlspecialchars($file["username"])); ?></small>
                                </div>
                            </a></div>
                    </div>
                </div>
            <?php } else {
                $icon = "fa-file";
            }
        }
        if ($icon != "") {
            ?>
            <div class="col-12">
                <div class="file-box box_inner">
                    <div class="file"><a href="<?php echo $file["file_path"]; ?>" download>

                            <span class="corner"></span>
                            <div class="icon">
                                <i class="fa <?php echo $icon; ?>"></i>
                            </div>
                            <div class="file-name"><?php echo str_replace(array("'", "\"", "&quot;"), "", htmlspecialchars($file["file_name"])); ?>
                                <p style="margin-bottom: 0.1rem;">
                                    <?php echo $file['app_name'].' - '.$file['patient_name']; ?>
                                </p>
                                <small>
                                    Added: <?php echo date("M d,Y", strtotime($file['created'])); ?></small>
                                <br>
                                <small>Created
                                    By: <?php echo date("M d,Y", strtotime($file["username"])); ?></small>
                            </div>
                        </a></div>
                </div>
            </div>
        <?php }
    }
    if ($n == 4) {
        $n = 1; ?> </div> <?php } else {
        $n++;
    }

} }else{ ?>
    <label style="padding:10px;text-align: center;width: 100%; display: block;">No medical record found</label>
    <script>$("#load_more_record").hide();</script>
<?php } ?>
<script>

    function loadImag(obj){
        var img_src = $(obj).attr('data-src');
        $(obj).attr('src',img_src);
        $(obj).removeClass('load_img');
    }


</script>
