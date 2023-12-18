<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <title>Folder</title>
    <?php echo $this->Html->script(array('jquery.js','bootstrap.min.js','sweetalert2.min.js','dropzone.min.js')); ?>
    <?php echo $this->Html->css(array('sweetalert2.min.css','bootstrap.min.css','font-awesome.min.css','dropzone.min.css')); ?>
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
            width: 100%;
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
            outline: none;
        }

        .refresh_btn{
            position: fixed;
            bottom: 14px;
            float: right;
            right: 65px;
            border-radius: 28px;
            height: 50px;
            width: 50px;
            font-size: 21px;
            font-weight: 500;
            z-index: 999;
            outline: none;
        }

    </style>
</head>
<body>

    <table class="header" style="border: none; width: 100%;">
        <tr>
            <td style="width: 30%;height: 20%;" valign="top">
                <img class="image_cover" src="<?php echo Router::url('/images/logo.png',true); ?>" >
                <div class="line_box">&nbsp;</div>
            </td>

            <td style="width:<?php !empty($web_app_url)?'55%':'70%'; ?>;" valign="top" align="right">
                <h4 class="title_lbl" >Medical Records</h4>
                <h4 class="patient_name" ><span class="patient_name_title"><?php echo $folder_name; ?></span></h4>
            </td>

            <?php if(!empty($web_app_url)){  ?>
                <td style="width: 15%;background: #0a34f5; border-left: 1px solid #fff;">
                   <a class="btn" style="color: #fff;font-size: 1.5rem;" href="<?php echo $web_app_url; ?>"><i class="fa fa-home"></i><br> Home</a>
                </td>
            <?php }  ?>
        </tr>
    </table>

<div class="container">

</div>

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
                                <input type="hidden" name="m" value="<?php echo ($share_with_mobile); ?>";>
                                <input type="hidden" name="t" value="<?php echo ($thin_app_id); ?>">
                                <input type="hidden" name="d" value="<?php echo base64_encode($drive_folder_id); ?>">
                                <div class="col-12" style="display: none;">
                                    <?php echo $this->Form->input('cat_id',array('type'=>'select','label'=>"Select Category",'options'=>array('6'=>'Prescription'),'class'=>'form-control cat_drp')); ?>
                                </div>

                                <div class="fallback col-12">
                                    <input name="file" type="file" multiple placeholder="Click here to browse file" />
                                </div>
                                <div class="dz-message" data-dz-message><span>Click here to upload prescription</span></div>

                                <div class="col-sm-12" style="position: absolute;bottom: 0; width: 90%;float: left;">
                                    <label style="display: block;">&nbsp;</label>
                                    <button type="button" style="width: 100%;" class="btn btn-default" id="upload"><i class="fa fa-upload"></i> Upload</button>
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
<button style="display: none;" type="button" class="btn btn-success float_btn add_new_file"><i class="fa fa-plus"></i> </button>
<button style="display: none;" type="button" class="btn btn-info refresh_btn"><i class="fa fa-refresh"></i> </button>

<script>
        $(document).ready(function () {
            var baseurl = "<?php echo Router::url('/',true); ?>";
            function warningDialog(message){
                swal({
                    title:'Warning',
                    type: 'warning',
                    text: message,
                    showCancelButton: false,
                    showConfirmButton: false
                });
            }
            var param = "<?php echo $send_param; ?>";
            var $dialog = "<?php echo $dialog; ?>";
            var ds = "<?php echo $drive_share_id; ?>";
            var direct_load = "<?php echo $direct_load; ?>";
            var m = "<?php echo $share_with_mobile; ?>";
            var t = "<?php echo $thin_app_id; ?>";
            if($dialog=='OTP' && direct_load =='NO'){
                swal({
                    title: '<?php echo $title; ?>',
                    text: '<?php echo $message; ?>',
                    showCancelButton: false,
                    confirmButtonText: 'Generate',
                    showLoaderOnConfirm: true,
                    preConfirm: function (otp) {
                        return new Promise(function (resolve, reject) {
                            $.ajax({
                                url: "<?php echo SITE_PATH;?>folder/send_otp",
                                data:{t:t,m:m,ds:ds},
                                type:'POST',
                                success: function(result){
                                    result = JSON.parse(result);
                                    if (result.status == 1) {
                                        resolve();
                                    } else {
                                        reject(result.message);
                                    }
                                },error:function () {
                                    reject(result.message);
                                }
                            });
                        })
                    },
                    allowOutsideClick: false
                }).then(function (otp) {
                    /* verify otp screen open*/
                    swal({
                        type:'success',
                        title: 'OTP Sent',
                        text: 'Please enter OTP to show medical record',
                        input: 'number',
                        showCancelButton: false,
                        confirmButtonText: 'Verify',
                        showLoaderOnConfirm: true,
                        preConfirm: function (otp) {
                            return new Promise(function (resolve, reject) {
                                $.ajax({
                                    url: "<?php echo SITE_PATH;?>folder/verify_share/verify_share",
                                    data:{param:param,otp:otp,ds:ds},
                                    type:'POST',
                                    success: function(result){
                                        result = JSON.parse(result);
                                        if (result.status == 1) {
                                            $('body').attr('data-o',otp);
                                            resolve();
                                            $(".container").html(result.html);
                                        } else {
                                            reject(result.message);
                                        }
                                    },error:function () {
                                        reject(result.message);
                                    }
                                });
                            });
                        },
                        allowOutsideClick: false
                    }).then(function (otp) {
                        swal({
                            type: 'success',
                            title: 'Success',
                            text: 'OTP Verified Successfully',
                            showCancelButton: false,
                            showConfirmButton: false
                        });
                        var inter = setInterval(function () {
                            swal.close();
                            $(".login-page").show();
                            clearInterval(inter);
                        },1000);
                        /* verify otp screen end*/
                    });
                });
            }else{
                if(direct_load =='YES'){
                    $.ajax({
                        url: "<?php echo SITE_PATH;?>folder/verify_share/verify_share",
                        data:{param:param,ds:ds},
                        type:'POST',
                        success: function(result){
                            result = JSON.parse(result);
                            if (result.status == 1) {
                                $(".container").html(result.html);
                            } else {
                                warningDialog(result.message)
                            }
                        },error:function () {
                            warningDialog(result.message)
                        }
                    });
                }else{
                    warningDialog('<?php echo $message; ?>');
                }
            }
            /* verify code */



            var folder_id = "<?php echo $drive_folder_id; ?>";

            $(document).on('click','.add_new_file',function(e){
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
                var folder = folder_id;
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
                myDropzone.removeFile(file);
            });
            myDropzone.on("uploadprogress", function(file, progress, bytesSent) {

            });

            myDropzone.on("queuecomplete", function(file) {
                $("#upload_file").modal("hide");
                refreshList();
                return false;

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
                }
            });

            function refreshList(){
                var thisButton =$('.refresh_btn');
                var param = "<?php echo $send_param; ?>";
                var ds = "<?php echo $drive_share_id; ?>";
                var otp = $('body').attr('data-o');
                $.ajax({
                    url: "<?php echo SITE_PATH;?>folder/verify_share/verify_share",
                    data:{param:param,otp:otp,ds:ds},
                    type:'POST',
                    beforeSend:function(){
                        $(thisButton).button('loading').html('<i class="fa fa-spinner fa-pulse">');
                    },
                    success: function(result){
                        $(thisButton).button('reset');
                        result = JSON.parse(result);
                        if (result.status == 1) {
                            $('body').attr('data-o',otp);
                            $(".container").html(result.html);
                        }
                    },error:function () {
                        $(thisButton).button('reset');
                    }
                });

            }

            $(document).on('click','.refresh_btn',function(e){
                refreshList();
            });

        });
    </script>
</body>
</html>