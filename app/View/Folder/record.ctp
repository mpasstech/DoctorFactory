<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <title>Folder</title>
    <?php echo $this->Html->script(array('jquery.js','bootstrap.min.js','dropzone.min.js','jquery-confirm.min.js')); ?>
    <?php echo $this->Html->css(array('bootstrap.min.css','font-awesome.min.css','dropzone.min.css','jquery-confirm.min.css'));
    //$category_list =$this->AppAdmin->getFileCategory();

    ?>
    <style>

        body{
            background:#fff;
        }
        .header{
            background: #0a34f5;
            color: #fff;
            text-align: center;
            padding: 0px 10px;
            border-top: 4px solid #0677e8 !important;
            float: left;
            width: 100%;
            position: fixed;
            z-index: 99999;
        }
        header img{
            height: 100%;
            float: left;
        }
        .header h4{
            font-size: 16px;
            float: left;
            width: 100%;
            display: block;
            padding-right:10px;
            margin: 0;
            text-align: right;

        }
        .image_cover{
            float: left;
            display: block;
            background: #fff;
        //border-radius: 0px 90px 90px 0px;
            width: 100%;
            z-index: 9999;
            margin-left: 0px;
            /* height: 60%; */
            padding: 6% 17%;
            margin-top: 0px;
            margin-bottom: 0px;




        }
        .title_lbl{
            font-weight: 600;
            padding-top:2%;
            padding-bottom: 2%;
        }
        .patient_name{
            display: block;
            padding: 0;
            margin: 0;
        }
        table tr{
            border-bottom: 1px solid #0a34f5 !important;
            padding-top: 12px !important;
        }
        .line_box{
            float: left;
            display: block;
            background: #fff;
            position: absolute;
            height: 100%;
            left: 12%;
            transform: rotate(8deg);
            WIDTH: 20%;
            top: -9%;
            z-index: -1;
        }
        .patient_name span{
            border-top: 3px solid;
            margin-top: 0;
            display: initial;
            padding-top: 5px;
            height: 100%;
            float: right;
            text-align: right;
            width: auto;
            font-weight: 500 !important;
        }

        .col-md-3 {

            padding-right: 0px;
            padding-left: 0px;
        }
        .container{
            float: left;
            display: block;
            /* border: 1px solid; */
            top: 9%;
            position: fixed;
            overflow: scroll;
            height: 99%;
            scroll-behavior: smooth;
            padding:0 4% 8% 4%;
        }
        .file-box {
            text-align: center;
            /*width: 220px;*/
            width: 100%;
        }
        .file-manager h5 {
            text-transform: uppercase;
        }
        .file-manager {
            list-style: none outside none;
            margin: 0;
            padding: 0;
        }
        .folder-list li a {
            color: #666666;
            display: block;
            padding: 5px 0;
        }
        .folder-list li {
            border-bottom: 1px solid #e7eaec;
            display: block;
        }
        .folder-list li i {
            margin-right: 8px;
            color: #3d4d5d;
        }
        .category-list li a {
            color: #666666;
            display: block;
            padding: 5px 0;
        }
        .category-list li {
            display: block;
        }
        .category-list li i {
            margin-right: 8px;
            color: #3d4d5d;
        }
        .tag-list li {
            float: left;
        }
        .tag-list li a {
            font-size: 10px;
            background-color: #f3f3f4;
            padding: 5px 12px;
            color: inherit;
            border-radius: 2px;
            border: 1px solid #e7eaec;
            margin-right: 5px;
            margin-top: 5px;
            display: block;
        }
        .file {
            background-color: #ffffff;

            padding: 0;
            position: relative;
        }
        .file-manager .hr-line-dashed {
            margin: 15px 0;
        }
        .file .icon,
        .file .image {
            min-height: 250px;
            overflow: hidden;
        }
        .file .icon {
            padding: 10% 0;
            text-align: center;
        }
        .row {
            margin-right: 0px !important;
            margin-left: 0px !important;
        }

        .file .icon i {
            font-size: 12rem;
            color: #a1a1a1;
        }

        .fa-file-pdf-o{
            color: #e90606 !important;
        }
        .fa-play{
            color :#556ec4 !important;
        }
        .fa-headphones{
            color: #47b1d8 !important;
        }
        .file .file-name {
            padding: 3px;
            background-color: #1d18189e;
            word-break: break-all;
            position: absolute;
            bottom: 0;
            width: 100%;
            color: #fff;
        }
        .file-name small {
            color: #fff;
        }
        ul.tag-list li {
            list-style: none;
        }
        .corner {
            position: absolute;
            display: inline-block;
            width: 0;
            height: 0;
            line-height: 0;
            border: 0.6em solid transparent;
            border-right: 0.6em solid #f1f1f1;
            border-bottom: 0.6em solid #f1f1f1;
            right: 0em;
            bottom: 0em;
        }

        .ibox.collapsed .ibox-content {
            display: none;
        }
        .ibox.collapsed .fa.fa-chevron-up:before {
            content: "\f078";
        }
        .ibox.collapsed .fa.fa-chevron-down:before {
            content: "\f077";
        }

        a:hover{
            text-decoration:none;
        }

        .memoTex {;
            overflow-y: scroll !important;
        }
        .box_inner{
            margin: 27px auto;
            border: 1px solid #c8c8c8c2;
            padding: 0;
        }
        .img-responsive{
            margin: 0 auto;
            width: 100%;
        }
        tr,td{
            padding: 0px;
        }

        .load_img{
            width: 50%;
            height: 30%;
        }
        .container .row{
            float: left;
            width: 100%;
            display: block;
        }

        .float_btn{
            position: fixed;
            bottom: 14px;
            float: right;
            right: 8px;
            border-radius: 28px;
            height: 50px;
            width: 50px;
            font-size: 21px;
            font-weight: 500;
            z-index: 999;
        }

        .upload_btn_span{
            border: 1px solid;
            padding: 10px;
            border-radius: 4px;
            background: #1b8c1b;
            color: #fff;

        }
    </style>
</head>
<body>

<div class="modal fade" id="upload_file" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">

    <div class="modal-dialog modal-sm" style="margin-top: 70px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Upload Prescription</h4>
            </div>
            <div class="modal-body" style="padding: 0px;">
                <div class="form-group">


                    <div class="col-sm-12">
                        <form id="myId" class="dropzone" style="padding: 0px 0px 50px 0px !important; border: 0 !important;">


                            <input type="hidden" name="m" value="<?php echo base64_encode($mobile); ?>";>
                            <input type="hidden" name="t" value="<?php echo base64_encode($thin_app_id); ?>">
                            <input type="hidden" name="d" value="<?php echo base64_encode($drive_folder_id); ?>">
                            <div class="col-12" style="display: none;">
                                <?php echo $this->Form->input('cat_id',array('type'=>'select','label'=>"Select Category",'options'=>array('6'=>'Prescription'),'class'=>'form-control cat_drp')); ?>
                            </div>

                            <div class="fallback col-12">
                                <input name="file" type="file" multiple placeholder="Click here to browse file" />
                            </div>
                            <div class="dz-message" data-dz-message><span class="upload_btn_span"><i class="fa fa-file-text-o" aria-hidden="true"></i> Upload Prescription</span></div>

                            <div class="col-sm-12" style="position: absolute;bottom: 0; width: 90%;float: left;">
                                <label style="display: block;">&nbsp;</label>
                                <button type="button" style="width: 100%;display: none;" class="btn btn-default" id="upload"><i class="fa fa-upload"></i> Upload</button>
                            </div>


                        </form>
                    </div>


                </div>
            </div>
            <div class="modal-footer">

            </div>
        </div>
    </div>

</div>


<table class="header" style="border: none; width: 100%;">
    <tr>
        <td style="width: 30%;height: 20%;" valign="top">
            <img class="image_cover" src="<?php echo Router::url('/images/logo.png',true); ?>" >
            <div class="line_box">&nbsp;</div>
        </td>

        <td style="width: 70%;" valign="top" align="right">
            <h4 class="title_lbl" >Medical Records</h4>
            <h4 class="patient_name" ><span class="patient_name_title"><?php echo $folder_name; ?></span></h4>
        </td>
    </tr>
</table>

<div class="container" style="width: 100%;display: block;float: left;">
    <?php


    $n = 1;
    if(!empty($list_data)){

        foreach ($list_data as $file) {
            if ($n == 1) { ?><div class="row"><?php }


            if ($file['file_type'] == 'IMAGE') { ?>
                <div class="col-md-3 box_inner">
                    <div class="file-box">
                        <div class="file"><a href="<?php echo $file["file_path"]; ?>" download>
                                <span class="corner"></span>
                                <div class="image"><img alt="image" class="img-responsive load_img"  src="<?php echo $this->AppAdmin->beforeLodeImage(); ?>" data-src="<?php echo $file["file_path"]; ?>"></div>
                                <div class="file-name"><?php echo str_replace(array("'", "\"", "&quot;"), "", htmlspecialchars($file["file_name"])); ?>
                                    <br>
                                    <small><i class="fa fa-calendar"></i>
                                        Date: <?php echo date("M d,Y", strtotime($file['created'])); ?>
                                    </small>
                                    <br>
                                    <small>Added
                                        By: <?php echo str_replace(array("'", "\"", "&quot;"), "", htmlspecialchars($file["username"])); ?></small>
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
                        <div class="col-md-3 box_inner">
                            <div class="file-box">
                                <div class="file"><a href="javascript:void(0)">
                                        <div class="image memoTex"><p
                                                    style="color: <?php echo ($file['DriveFile']['memo_label'] == 'YELLOW') ? "#eddd0d" : $file['DriveFile']['memo_label']; ?>;"><?php echo str_replace(array("'", "\"", "&quot;"), "", htmlspecialchars($file['memo_text'])); ?></p>
                                        </div>
                                        <div class="file-name"><br>
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
                    <div class="col-md-3">
                        <div class="file-box box_inner">
                            <div class="file"><a href="<?php echo $file["file_path"]; ?>" download>

                                    <span class="corner"></span>
                                    <div class="icon">
                                        <i class="fa <?php echo $icon; ?>"></i>
                                    </div>
                                    <div class="file-name"><?php echo str_replace(array("'", "\"", "&quot;"), "", htmlspecialchars($file["file_name"])); ?>
                                        <br>
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

        } ?>



    <?php }else{ ?>
        <label style="text-align: center;width: 100%; display: block; padding: 10px; font-size: 15px;">No medical record found</label>
    <?php } ?>

    <button type="button" class="btn btn-success float_btn add_new_file"><i class="fa fa-plus"></i> </button>
</div>

<script>
    $(document).ready(function () {

        var baseurl = "<?php echo Router::url('/',true); ?>";
        $.each($(".load_img"), function(){
            var this_image = this;
            var src = $(this_image).attr('src') || '' ;
            var lsrc = $(this_image).attr('data-src') || '' ;
            if(lsrc.length > 0){
                var img = new Image();
                img.src = lsrc;
                $(img).load(function() {
                    this_image.src = this.src;
                    $(this_image).removeClass('load_img');
                });
            }
        });


        $(document).on('mouseover', '.file_box_container', function(e){
            $(this).find(".file_data_content").slideDown('fast');
        });

        $(document).on('mouseleave', '.file_box_container', function(e){
            $(this).find(".file_data_content").slideUp('fast');
        });


        $(this).find(".file_data_content").slideUp('fast');

        var folder_id = "<?php echo $drive_folder_id; ?>";

        $(document).on('click','.add_new_file',function(e){

            var folder = ($(this).attr('data-id'));
            var f_name = ($(this).attr('data-name'));
            if(folder !=0 && folder !=''){
                $(".folder_drp").val(folder);

            }

            $("#upload_file").modal('show');


        });

        var myDropzone = new Dropzone("#myId", {
            url: "<?php echo Router::url('/folder/upload_file')?>",
            autoProcessQueue: false,
            maxFilesize: 25, // MB
            addRemoveLinks:true,
            parallelUploads:5,
            maxFiles:5,
            acceptedFiles: ".jpeg,.jpg,.png"

        });

        $(document).on('click','#upload',function(e){
            var accept_files = myDropzone.getAcceptedFiles().length;
            var reject_files = myDropzone.getRejectedFiles().length;
            var total_files= myDropzone.files.length;
            var folder = $("#folder_name").val();
            var cat_id = $(".cat_drp").val();

            if(folder_id){
                if(accept_files == 0 && reject_files ==0 ){
                    alert('Please upload files to upload.');
                }else if( reject_files > 0 ){
                    if(total_files > 10){
                        $.alert('You can upload only 10 files once.');
                    }else{
                        $.alert('Please upload validate files.');
                    }
                }else{
                    myDropzone.processQueue();
                }
            }else{
                $.alert('Medical folder not found');
            }


        });
        myDropzone.on("complete", function(file) {
            //myDropzone.removeFile(file);
        });
        myDropzone.on("uploadprogress", function(file, progress, bytesSent) {

        });

        myDropzone.on("queuecomplete", function(file) {
            $("#upload_file").modal("hide");

            var m = "<?php echo base64_encode($mobile); ?>";
            var t = "<?php echo base64_encode($thin_app_id); ?>";


            $.ajax({
                type:'POST',
                url: baseurl+"folder/add_file_notify",
                data:{f_id:btoa(folder_id),t:t,m:m},
                success:function () {
                    window.location.reload();
                }
            });
        });

        myDropzone.on("addedfile", function(file) {
            if (this.files.length) {
                var _i, _len;
                for (_i = 0, _len = this.files.length; _i < _len - 1; _i++) // -1 to exclude current file
                {
                    if(this.files[_i].name === file.name && this.files[_i].size === file.size && this.files[_i].lastModifiedDate.toString() === file.lastModifiedDate.toString())
                    {
                        this.removeFile(file);
                    }
                }
                $("#upload").show();
            }else{
                $("#upload").hide();
            }
        });

        myDropzone.on("removedfile", function(file) {
            if (this.files.length) {
                $("#upload").show();
            }else{
                $("#upload").hide();
            }
        });





    });
</script>
</body>
</html>