<?php
$login_user = $this->Session->read('Auth.User.User');
$login_role = $this->Session->read('Auth.User.USER_ROLE');
$category_list =$this->AppAdmin->getFileCategory();
?>





<div class="modal fade" id="upload_file" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">

    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Upload Files</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">



                    <div class="col-sm-12">
                        <form id="myId" class="dropzone">
                            <div class="fallback">
                                <input name="file" type="file" multiple placeholder="Drag and drop file here Or Click to browse file" />
                            </div>
                            <div class="folder_div">
                                <div class="col-sm-4  col-sm-offset-4">
                                    <?php echo $this->Form->input('cat_id',array('type'=>'select','label'=>"Select Category",'options'=>$category_list,'class'=>'form-control cat_drp')); ?>
                                    <?php echo $this->Form->input('folder_name',array('type'=>'hidden','label'=>false,'options'=>$category_list,'id'=>'folder_name')); ?>

                                </div>
                                <div class="col-sm-2">
                                    <label style="display: block;">&nbsp;</label>
                                    <button type="button" class="btn btn-default" id="upload"><i class="fa fa-upload"></i> Upload</button>
                                </div>

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

<script>
    $(function () {



        var folder_id = 0;



        $(document).on('click','.add_new_file',function(e){
            folder = ($(this).attr('data-id'));
            if(folder != 0 && folder !=''){
                $("#folder_name").val(folder);
            }

            $("#upload_file").modal('show');


        });



        $(document).on('mouseover', '.file_box_container', function(e){
            $(this).find(".file_data_content").slideDown('fast');
        });

        $(document).on('mouseleave', '.file_box_container', function(e){
            $(this).find(".file_data_content").slideUp('fast');
        });


        $(this).find(".file_data_content").slideUp('fast');


        var myDropzone = new Dropzone("#myId", {
            url: "<?php echo Router::url('/app_admin/upload_file')?>",
            autoProcessQueue: false,
            maxFilesize: 25, // MB
            addRemoveLinks:true,
            parallelUploads:10,
            maxFiles:10

        });

        $(document).on('click','#upload',function(e){
            var accept_files = myDropzone.getAcceptedFiles().length;
            var reject_files = myDropzone.getRejectedFiles().length;
            var total_files= myDropzone.files.length;
            var cat_id = $(".cat_drp").val();

            if(folder){
                if(accept_files == 0 && reject_files ==0 ){
                    alert('Please select files to upload.');
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
                $.alert('Please select folder.');
            }


        });
        myDropzone.on("complete", function(file) {
             //myDropzone.removeFile(file);
        });
        myDropzone.on("uploadprogress", function(file, progress, bytesSent) {

        });

        myDropzone.on("queuecomplete", function(file) {
            $("#upload_file").modal("hide");
            $(".folder_name").val('');
            window.location.reload();
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

        $(".dz-message span").html("Drag and drop file here OR <a href='javascript:void(0);'> Click to browse</a>");

    })
</script>
<style>
    .jconfirm-title{width: 100% !important;;}
    .dropzone .dz-preview .dz-error-message {
        top: 150px!important;
    }
    .folder_div {

        margin-top: 55px;

    }
    .modal-footer {

        border-top: unset !important;

    }
    .modal-title {

        text-align: center;

    }
    .modal-header {

        background-color: #03A9F5;

    }
</style>


