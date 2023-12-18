<?php
$login_user = $this->Session->read('Auth.User.User');
$login_role = $this->Session->read('Auth.User.USER_ROLE');
//$folder_list =$this->AppAdmin->getDriveFolderList($login_user['thinapp_id'],$login_user['id']);
$folder_list[] =array('id'=>base64_decode($id),'folder_name'=>$folder_name);
$category_list =$this->AppAdmin->getFileCategory();
?>

<?php if($login_role == 'ADMIN' || ($login_role =='RECEPTIONIST' && $this->AppAdmin->check_user_enable_functionlity($login_user['thinapp_id'],'RECEPTIONIST_CAN_MANAGE_PATIENT_MEDICAL_RECORDS') =='YES' )){ ?>
<div class="progress-bar drive_tap">
    <a id="v_my_folder" href="<?php echo Router::url('/app_admin/drive?ft=pf'); ?>"><i class="fa fa-folder-o"></i> My Folders</a>
   <!-- <a id="v_share_folder" href="<?php /*echo Router::url('/app_admin/drive?ft=sf'); */?>" ><i class="fa fa-folder"></i> Received Folders </a>
    <a id="v_share_file" href="<?php /*echo Router::url('/app_admin/drive?ft=sfile'); */?>" ><i class="fa fa-file"></i> Received Files </a>
    <a id="v_add_folder" class="add_new_folder" href="javascript:void(0);" ><i class="fa fa-plus"></i> Folder </a>
   --> <a id="v_add_file" data-id="<?php echo !empty($this->request->query['fi'])?base64_decode($this->request->query['fi']):'0'; ?>" class="add_new_file" href="javascript:void(0);" ><i class="fa fa-plus"></i> File </a>
</div>
<?php } ?>




<div class="modal fade" id="upload_file" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">

    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Upload Files</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">

                    <?php
                    $list = $cat_list= array();
                    if(!empty($folder_list)){
                        foreach ($folder_list as $key => $folder){
                            $list[$folder['id']] =$folder['folder_name'];
                        }
                    }

                    ?>

                    <div class="col-sm-12">
                        <form id="myId" class="dropzone">
                            <div class="folder_div">
                                <div class="col-sm-6 folder_list_div">

                                <?php echo $this->Form->input('folder_name',array('type'=>'select','label'=>"Select Folder",'options'=>$list,'class'=>'form-control folder_drp')); ?>
                                </div>
                                <div class="col-sm-4">
                                    <?php echo $this->Form->input('cat_id',array('type'=>'select','label'=>"Select Category",'options'=>$category_list,'class'=>'form-control cat_drp')); ?>
                                 </div>
                                <div class="col-sm-2">
                                    <label style="display: block;">&nbsp;</label>
                                    <button type="button" class="btn btn-default" id="upload"><i class="fa fa-upload"></i> Upload</button>
                                </div>

                            </div>

                            <div class="fallback">
                                <input name="file" type="file" multiple placeholder="Drag and drop file here Or Click to browse file" />
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



        var folder_id = "<?php echo @$this->request->query['fi']; ?>";
        if(folder_id!=""){
            $(".folder_drp").val(atob(folder_id));
        }



        $(document).on('click','.add_new_folder', function(e){

            var insbook = "<?php echo @$instruction_book; ?>";
            var ins_str ="";
            if(insbook=="YES"){
                ins_str = '<label><input type="checkbox" id="insbook" /> Add folder for instruction book</label>';
            }


            var jc = $.confirm({
                title: 'Add Folder',
                content: '' +
                '<form action="" class="formName">' +
                '<div class="form-group">' +
                '<input type="text" id="folder_box_name" placeholder="Enter folder name" class="form-control" required />' +
                '<input type="textrea" row="3" id="description" placeholder="Enter description" class="txtArea form-control"  />' +
                ins_str+
                '</div>' +
                '</form>',
                buttons: {
                    formSubmit: {
                        text: 'Add',
                        btnClass: 'btn-blue',
                        action: function (e) {
                            var name = this.$content.find('#folder_box_name').val();
                            var description = this.$content.find('#description').val();
                            var insbook = this.$content.find('#insbook').is(":checked");
                            if(!name){
                                $.alert('Please enter folder name.');
                                return false;
                            }else{
                                $.ajax({
                                    url:baseurl+"/app_admin/add_folder",
                                    type:'POST',
                                    data:{
                                        name:name,
                                        description:description,
                                        ins_book:insbook
                                    },
                                    beforeSend:function(){
                                        jc.buttons.formSubmit.setText("Wait..");
                                    },
                                    success:function(res){
                                        var response = JSON.parse(res);
                                        jc.buttons.formSubmit.setText("Add");
                                        if(response.status==1){
                                            jc.close();
                                            window.location.reload();
                                        }else{
                                            $.alert(response.message);
                                        }
                                    },
                                    error:function () {
                                        jc.buttons.ok.setText("Add");
                                        $.alert(response.message);

                                    }
                                });
                                return false;
                            }
                        }
                    },
                    cancel: function () {
                        //close
                    },
                },
                onContentReady: function () {
                    // bind to events
                    var jc = this;
                    this.$content.find('form').on('submit', function (e) {
                        // if the user submits the form by pressing enter in the field.
                        e.preventDefault();
                        jc.$$formSubmit.trigger('click'); // reference the button and click it
                    });
                }
            });


        });


        $(document).on('click','.add_new_file',function(e){

            var folder = ($(this).attr('data-id'));
            var f_name = ($(this).attr('data-name'));
            if(folder !=0 && folder !=''){
                $(".folder_drp").val(folder);

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



        $(document).on('click','.delete_folder',function(e){
            var del_obj = $(this);
            var jc = $.confirm({
                title: 'Delete Folder',
                content: 'Are you sure you want to delete this folder?',
                type: 'red',
                buttons: {
                    ok: {
                        text: "Yes",
                        btnClass: 'btn-primary',
                        keys: ['enter'],
                        name:"ok",
                        action: function(e){
                            var btn = $(this);
                            var parent_obj = $(del_obj).closest(".image_box");
                            var hide_obj = $(del_obj).closest(".row_box");
                            var folder_id =  parent_obj.attr("data-id");
                            $.ajax({
                                url:baseurl+"/app_admin/delete_folder",
                                type:'POST',
                                data:{
                                    data_set:folder_id
                                },
                                beforeSend:function(){
                                    jc.buttons.ok.setText("Wait..");
                                },
                                success:function(res){
                                    var response = JSON.parse(res);
                                    jc.buttons.ok.setText("Yes");
                                    if(response.status==1){
                                        jc.close();
                                        $(hide_obj).fadeOut('slow');
                                    }else{
                                        $.alert(response.message);
                                    }

                                },
                                error:function () {
                                    jc.buttons.ok.setText("Yes");
                                    $.alert(response.message);
                                }
                            });
                            return false;
                        }
                    },
                    cancel: function(){
                        console.log('the user clicked cancel');
                    }
                }
            });
        });


        $(document).on('click','.unshare_object',function(e){
            var del_obj = $(this);
            var parent_obj = $(del_obj).closest(".image_box");
            var hide_obj = $(del_obj).closest(".row_box");
            var data_share_id =  $(this).attr("data-set");

            var jc = $.confirm({
                title: 'Unshare',
                content: 'Are you sure you want to unshare this?',
                type: 'red',
                buttons: {
                    ok: {
                        text: "Yes",
                        btnClass: 'btn-primary',
                        keys: ['enter'],
                        action: function(e){

                            $.ajax({
                                url:baseurl+"/app_admin/delete_share",
                                type:'POST',
                                data:{
                                    share_id:data_share_id
                                },
                                beforeSend:function(){
                                    jc.buttons.ok.setText("Wait..");
                                },
                                success:function(res){
                                    var response = JSON.parse(res);
                                    jc.buttons.ok.setText("Yes");
                                    if(response.status==1){
                                        jc.close();
                                        $(hide_obj).fadeOut('slow');
                                    }else{
                                        $.alert(response.message);
                                    }

                                },
                                error:function () {
                                    jc.buttons.ok.setText("Yes");
                                    $.alert(response.message);
                                }
                            });
                            return false;
                        }
                    },
                    cancel: function(){
                        console.log('the user clicked cancel');
                    }
                }
            });
        });


        $(document).on('click','.rename_folder',function(e){
            var del_obj = $(this);
            var btn = $(this);

            var folder_id =  $(this).closest(".image_box").attr("data-folder");
            var name_obj = $(del_obj).closest(".folder_container").find(".folder_name");
            var folder_name  = name_obj.text();
            var jc = $.confirm({
                title: 'Rename Folder',
                content: '' +
                '<form action="" class="formName">' +
                '<div class="form-group">' +
                '<label>Enter Folder Name</label>' +
                '<input type="text" value="'+folder_name+'"  placeholder="Folder name" class="name form-control" required />' +
                '</div>' +
                '</form>',
                buttons: {
                    formSubmit: {
                        text: 'Rename',
                        btnClass: 'btn-blue',
                        action: function (e) {

                            var name = this.$content.find('.name').val();
                            if(!name){
                                $.alert('Please enter a folder name');
                                return false;
                            }else{
                                $.ajax({
                                    url:baseurl+"/app_admin/rename_folder",
                                    type:'POST',
                                    data:{
                                        data_set:folder_id,
                                        folder_name:name
                                    },
                                    beforeSend:function(){
                                        jc.buttons.formSubmit.setText("Wait..");
                                    },
                                    success:function(res){
                                        var response = JSON.parse(res);
                                        jc.buttons.formSubmit.setText("Rename");
                                        if(response.status==1){
                                            name_obj.text(name);
                                            jc.close();
                                           
                                        }else{
                                            $.alert(response.message);
                                        }

                                    },
                                    error:function () {
                                        jc.buttons.formSubmit.setText("Rename");
                                        $.alert(response.message);
                                    }
                                });
                            }
                            return false;
                        }
                    },
                    cancel: function () {
                        //close
                    },
                },
                onContentReady: function () {
                    // bind to events
                    var jc = this;
                    this.$content.find('form').on('submit', function (e) {
                        // if the user submits the form by pressing enter in the field.
                        e.preventDefault();
                        jc.$$formSubmit.trigger('click'); // reference the button and click it
                    });
                }
            });
        });



        $(document).on('click','.change_permission',function(e){
            var del_obj = $(this);

            var jc = $.confirm({
                title: 'Change Permission',
                content: 'Are you sure you want to change permission for this folder?',
                type: 'red',
                buttons: {
                    ok: {
                        text: "Yes",
                        btnClass: 'btn-primary',
                        keys: ['enter'],
                        name:"ok",
                        action: function(e){
                            var btn = $(this);
                            var parent_obj = $(del_obj).closest(".image_box");
                            var hide_obj = $(del_obj).closest(".row_box");
                            var folder_id =  parent_obj.attr("data-id");
                            var current_obj = parent_obj.find('.change_permission');
                            var is_permission =  current_obj.attr("data-is");
                            $.ajax({
                                url:baseurl+"/app_admin/change_folder_permission",
                                type:'POST',
                                data:{
                                    data_set:folder_id,
                                    data_is:is_permission
                                },
                                beforeSend:function(){
                                    jc.buttons.ok.setText("Wait..");
                                },
                                success:function(res){
                                    var response = JSON.parse(res);
                                    jc.buttons.ok.setText("Yes");
                                    if(response.status==1){
                                        jc.close();
                                        if(is_permission=="YES"){
                                            current_obj.attr("data-is","NO");
                                            current_obj.attr("title","File can not be added by folder shared user.");
                                            current_obj.find('.fa').removeClass("fa-lock, fa-unlock").addClass("fa-lock");
                                        }else{
                                            current_obj.attr("data-is","YES");
                                            current_obj.attr("title","File can be added by folder shared user.");
                                            current_obj.find('.fa').removeClass("fa-lock, fa-unlock").addClass("fa-unlock");
                                        }

                                    }else{
                                        $.alert(response.message);
                                    }

                                },
                                error:function () {
                                    jc.buttons.ok.setText("Yes");
                                    $.alert(response.message);
                                }
                            });
                            return false;
                        }
                    },
                    cancel: function(){
                        console.log('the user clicked cancel');
                    }
                }
            });
        });




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
            var folder = $("#folder_name").val();
            var cat_id = $(".cat_drp").val();

            if(folder){
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
            var folder_id =  $(".folder_drp").val();

            $.ajax({
                type:'POST',
                url: baseurl+"app_admin/add_file_notify",
                data:{f_id:btoa(folder_id)},
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



        $(document).on('click','.add_more_btn',function(e){
            var len = $(".share_frm .mobile").length;
            var str = '<div class="form-group">' +
                '<input type="text" id="mobile_"+len  placeholder="Mobile number" name="mobile[]" class="mobile form-control" required />' +
                '</div>'
            $(".share_frm").append(str);
            addCountry("#mobile_"+len);
        });


        $(document).on('click','.view_memo', function(e){
            var text = $(this).attr("data-set");
            $.alert(text).css("backgound","red");
        });

        $(document).on('click','.share_object',function(e){
            var parent_obj = $(this).closest(".image_box");
            var object_id =  $(this).attr("data-id");
            var obj_type =  atob($(this).attr("sh-tp"));

            var jc = $.confirm({
                title: '<div style="width: 100%;">Share Folder <small style="float:right;"><button type="button" class="btn btn-xs btn-info add_more_btn">Add More</button><small></div>',
                content: '' +
                '<form action="" class="formName share_frm">' +
                '<div class="form-group">' +
                '<input type="text" id="mobile_0"  placeholder="Mobile number" name="mobile[]" class="mobile form-control" required />' +
                '</div>' +
                '</form>',
                buttons: {
                    formSubmit: {
                        text: 'Share',
                        btnClass: 'btn-blue',
                        action: function (e) {
                            var ret = [];
                            this.$content.find('.mobile').each(function () {
                                if(/^[0-9]{4,13}$/.test($(this).val())){
                                    $(this).css('border-color','#ccc');
                                }else{
                                    $(this).css('border-color','red');
                                    ret.push(0);
                                }
                            });
                            if($.inArray(0,ret) == -1){
                                var mobile = [];

                                $(".mobile").each(function (index, value) {
                                    var countryData = $(this).intlTelInput("getSelectedCountryData");
                                    var val = $(this).val();
                                    mobile.push("+"+countryData.dialCode+""+ val);
                                });

                                $.ajax({
                                    url:baseurl+"/app_admin/share_file",
                                    type:'POST',
                                    data:{
                                        object_id:object_id,
                                        mobile:mobile,
                                        object_type:obj_type
                                    },
                                    beforeSend:function(){
                                        jc.buttons.formSubmit.setText("Wait..");
                                    },
                                    success:function(res){
                                        var response = JSON.parse(res);
                                        jc.buttons.formSubmit.setText("Share");
                                        if(response.status==1){
                                            jc.close();
                                            window.location.reload();
                                        }else{
                                            $.alert(response.message);
                                        }
                                    },
                                    error:function () {
                                        $.alert(response.message);
                                        jc.buttons.formSubmit.setText("Share");
                                    }
                                });

                            }else{
                                $.alert('Please enter valid mobile number');
                            }
                            return false;
                        }
                    },
                    cancel: function () {
                        //close
                    },
                },
                onContentReady: function () {

                    var jc = this;
                    addCountry("#mobile_0");
                    this.$content.find('form').on('submit', function (e) {
                        // if the user submits the form by pressing enter in the field.
                        e.preventDefault();
                        jc.$$formSubmit.trigger('click'); // reference the button and click it
                    });
                }
            });
        });

        var country_code="";
        function addCountry(obj){
            if(country_code==""){
                $.get("https://ipinfo.io", function(response) {
                    country_code =  response.country;
                    $('.mobile').intlTelInput({
                        allowExtensions: true,
                        autoFormat: false,
                        autoHideDialCode: false,
                        autoPlaceholder:  false,
                        initialCountry: country_code,
                        ipinfoToken: "yolo",
                        nationalMode: true,
                        numberType: "MOBILE",
                        //onlyCountries: ['us', 'gb', 'ch', 'ca', 'do'],
                        //preferredCountries: ['cn', 'jp'],
                        preventInvalidNumbers: true,
                        utilsScript: "<?php echo Router::url('/js/utils.js',true); ?>"
                    });
                }, "jsonp");
            }else{
                $('.mobile').intlTelInput({
                    allowExtensions: true,
                    autoFormat: false,
                    autoHideDialCode: false,
                    autoPlaceholder:  false,
                    initialCountry: country_code,
                    ipinfoToken: "yolo",
                    nationalMode: true,
                    numberType: "MOBILE",
                    //onlyCountries: ['us', 'gb', 'ch', 'ca', 'do'],
                    //preferredCountries: ['cn', 'jp'],
                    preventInvalidNumbers: true,
                    utilsScript: "<?php echo Router::url('/js/utils.js',true); ?>"
                });
            }

        }

        $(".dz-message span").html("Drag and drop file here OR <a href='javascript:void(0);'> Click to browse</a>");

    })
</script>
<style>
    .jconfirm-title{width: 100% !important;;}
    .dropzone .dz-preview .dz-error-message {
        top: 150px!important;
    }
</style>


