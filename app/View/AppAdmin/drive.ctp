<?php
$login = $this->Session->read('Auth.User');


?>

<?php echo $this->Html->css(array('file_module.css?'.date('Ymd'),'bootstrap-treeview.min.css')); ?>
<?php echo $this->Html->script(array('bootstrap.tooltip.js','bootstrap-confirmation.js','bootstrap-treeview.min.js'));?>

<div class="Home-section-2">

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="container">
            <div class="row">

                <div class="middle-block">
                    <h3 class="screen_title">Medical Record</h3>
                     <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 custom_form_box">

        <div class="container file_container">
            <div class="row">
               <div class="col-md-12">

                   <?php //echo $this->element('app_admin_inner_tab_drive'); ?>
                   <?php echo $this->element('app_admin_inner_tab_drive_filter'); ?>

                    <?php if($folder_type =="PERSONAL" || $folder_type=="SHARED_FOLDER"){ ?>
                    <div class="row">
                            <?php if(!empty($data)){ ?>
                                <?php foreach ($data as $key  => $file){
                                    $folder_id = $id = $file["DriveFolder"]['id'];
                                    $user_id = $id = $file["DriveFolder"]['user_id'];

                                    $string ="";
                                    $open_query_string = "&&of=";
                                    $color = "#fff";
                                    $path = Router::url('/images/',true);
                                    if($folder_type=="PERSONAL"){
                                        if($file['DriveFolder']['is_instruction_bucket']=="YES"){
                                            $string = "folder_icon_blue.png";
                                            $color = "#1A7CF9";

                                        }else{
                                            $string = "folder_icon.png";
                                            $color = "#FFA500";
                                        }
                                        $open_query_string .='mf';

                                    }else if($folder_type=="SHARED_FOLDER"){
                                        $string = "folder_icon_green.png";
                                        $color = "#28B463";
                                        $open_query_string .='sf';

                                    }
                                    $string = Router::url('/images/'.$string,true);
                                    $string = "background-image :url('$string')";

                                    if(!empty($file["AppointmentCustomer"]['first_name']) ){
                                        $folder_name  = $file["AppointmentCustomer"]['first_name'];
                                        $mobile  = $file["AppointmentCustomer"]['mobile'];
                                    }else if(!empty($file["Children"]['child_name']) ){
                                        $folder_name  = $file["Children"]['child_name'];
                                        $mobile  = $file["Children"]['mobile'];
                                    }else{
                                        $folder_name  = $file["DriveFolder"]['folder_name'];
                                        $mobile  = $file["DriveFolder"]['mobile'];
                                    }

                                ?>
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 row_box">
                                    <div class="folder_container">
                                        <div class="image_box" style="<?php echo $string; ?>" type="folder" data-folder="<?php echo base64_encode($folder_id); ?>" data-id="<?php echo base64_encode($id); ?>" >
                                            <div class="folder_content">
                                                <a href="<?php echo Router::url('/app_admin/drive_data?fi='.base64_encode($folder_id)).$open_query_string; ?>">
                                                <h4 style="color:<?php echo $color; ?>" class="folder_name"> <?php echo mb_strimwidth($folder_name, 0, 150, '...'); ?></h4>
                                                <label style="font-size: 10px; margin: 0px; padding: 0px;" class="folder_mobile"> <?php echo $mobile; ?></label>

                                                <div class="footer_folder">
                                                    <div class="folder_time"><i class="fa fa-clock-o"></i>  <?php echo date('d-m-Y H:i',strtotime($file["DriveFolder"]['created'])); ?></div>

                                                    <div class="sh_cnt">
                                                        <i title="Total Shares" class="fa fa-share"></i> <?php echo $file["DriveFolder"]['share_count']; ?>
                                                        <i title="Total file into folder" class="fa fa-file"></i> <?php echo $file["DriveFolder"]['total_file_count']; ?>
                                                        <i title="Total memo into folder" class="fa fa-file-text"></i> <?php echo $file["DriveFolder"]['total_memo_count']; ?>
                                                    </div>
                                                </div>
                                                </a>
                                                <div style="background-color:<?php echo $color; ?>" class="folder_action_icon">
                                                    <?php if($folder_type =="PERSONAL"){ ?>
                                                    <?php if($login['User']['id'] == $user_id){ ?>


                                                        <a  href="javascript:void(0);" data-href="javascript:void(0);"  class="rename_folder" title="Rename this folder."><i class="fa fa-pencil"></i></a>
                                                        <a  href="javascript:void(0);" data-href="javascript:void(0);"  class="delete_folder"><i class="fa fa-trash" title="Delete this folder."></i></a>
                                                        <?php $is_lock =  $file["DriveFolder"]['allow_add_file']; ?>
                                                        <a  href="javascript:void(0);" data-href="javascript:void(0);"  class="change_permission" data-is="<?php echo $is_lock; ?>" title="<?php echo ($is_lock=='NO')?'File can not be added by shared folder user.':'File can be added by shared folder user.'; ?>" ><i class="fa <?php echo ($is_lock=='YES')?'fa-unlock':'fa-lock'; ?>"> </i></a>
                                                    <?php }}else{ ?>
                                                        <a  href="javascript:void(0);" data-set="<?php echo base64_encode($file["DriveShare"]['id']); ?>"  title="Share this folder." class="unshare_object"><i class="fa fa-link"></i></a>
                                                    <?php }?>

                                                    <?php if($file["DriveFolder"]['allow_add_file']){ ?>
                                                        <a  title="Upload Record" data-name="<?php echo $file["DriveFolder"]['folder_name']; ?>" href="javascript:void(0);" data-id="<?php echo ($file["DriveFolder"]['id']); ?>" class="add_new_file"><i class="fa fa-file"></i></a>
                                                    <?php } ?>
                                                    <a  href="javascript:void(0);" data-id="<?php echo base64_encode($folder_id); ?>" sh-tp="<?php echo base64_encode("FOLDER"); ?>" class="share_object"><i class="fa fa-share-alt"></i></a>
                                                </div>
                                            </div>

                                        </div>

                                    </div>
                                </div>
                                <?php } ?>

                        <?php }else{ ?>
                            <div class="no_data">
                                <h2>No folder available</h2>
                            </div>
                        <?php } ?>
                    </div>
                    <?php } else { ?>
                    <div class="row">
                       <?php if(!empty($data)){ ?>
                           <div class="dashboard_icon_li">
                               <?php foreach ($data as $key  => $file){

                                   $file_type = $file['DriveFile']['file_type'];
                                   $file_name = $file['DriveFile']['file_name'];
                                   $id = $file_id= $file['DriveFile']['id'];
                                   $share_id = $file['DriveShare']['id'];
                                   $folder_id = $file['DriveFile']['drive_folder_id'];
                                   $file_path = $file['DriveFile']['file_path'];
                                   $type = explode(".",$file_path);
                                   $ext = end($type);

                                   ?>
                                   <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 row_box">
                                       <?php if($file['DriveFile']['listing_type']=="OTHER"){ ?>
                                       <div class="file_box_container">
                                           <div class="file_label"><?php echo $file_type; ?></div>
                                           <?php
                                           $string ="";
                                           if($file_type=="IMAGE"){
                                               $string = "background-image :url('$file_path')";
                                           }
                                           ?>
                                           <div class="image_box" style="<?php echo  $string; ?>" type ='file' data-folder="<?php echo base64_encode($folder_id); ?>" data-file="<?php echo base64_encode($file_id); ?>" data-id="<?php echo base64_encode($id); ?>" >
                                               <?php if($file_type=="VIDEO"){  $type = explode(".",$file_path);  ?>

                                                   <video class="video" controls>
                                                       <source src="<?php echo $file_path; ?>" type="video/<?php echo end($type); ?>">
                                                       Your browser does not support the video tag.
                                                   </video>


                                               <?php }else if($file_type=="AUDIO"){ ?>

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

                                               <div class="file_data_content">
                                                   <div class="file-name">
                                                       <?php echo $file['DriveFile']['file_name']; ?>
                                                       <br>
                                                       <small><i class="fa fa-calendar"></i>  <?php echo date('d-m-Y H:i',strtotime($file['DriveFile']['created'])); ?></small>
                                                   </div>


                                                   <div class="action_icon">
                                                       <a href="javascript:void(0);" data-set="<?php echo base64_encode($share_id); ?>"  class="unshare_object"><i class="fa fa-link"></i></a>
                                                       <a href="javascript:void(0);" data-id="<?php echo base64_encode($file_id); ?>" sh-tp="<?php echo base64_encode("FILE"); ?>"  class="share_object"><i class="fa fa-share-alt"></i></a>
                                                       <a href="<?php echo $file_path; ?>" class="file_download" download><i class="fa fa-download"></i></a>
                                                       <a href="<?php echo $file_path; ?>" target="_blank"><i class="fa fa-eye"></i></a>

                                                   </div>

                                               </div>
                                               <div class="file_last_label"><?php echo $file['DriveFile']['file_name']; ?></div>
                                           </div>

                                       </div>


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
                                                       <div class="action_icon">
                                                           <?php if ($is_owner){ ?>
                                                               <a href="javascript:void(0);" data-id="<?php echo base64_encode($file['DriveFile']['id']); ?>"  class="delete_file"><i class="fa fa-trash"></i></a>
                                                           <?php } ?>
                                                           <!--<a href="javascript:void(0);" sh-tp="<?php /*echo base64_encode("FILE"); */?>"  class="share_object"><i class="fa fa-share-alt"></i></a>-->
                                                           <a href="javascript:void(0);" class="view_memo" data-set="<?php echo $file['DriveFile']['memo_text']; ?>" ><i class="fa fa-eye"></i></a>
                                                       </div>
                                                   </div>
                                                   <div class="file_last_label"><?php echo $file['DriveFile']['file_name']; ?></div>
                                               </div>

                                           </div>
                                       <?php } ?>


                                   </div>
                               <?php } ?>

                           </div>


                       <?php }else{ ?>
                           <div class="no_data">
                               <h2>No file found</h2>
                           </div>
                       <?php } ?>


                   </div>
                    <?php }  ?>


                   <?php echo $this->element('paginator'); ?>
                </div>

            </div>
        </div>
    </div>

                </div>
            </div>
        </div>
    </div>

</div>
</div>
<!-- this for hide side bar-->


<style>

    body{
        background: #fff;
    }
    .modal.fade .modal-dialog {
        -webkit-transform: scale(0.1);
        -moz-transform: scale(0.1);
        -ms-transform: scale(0.1);
        transform: scale(0.1);
        top: 300px;
        opacity: 0;
        -webkit-transition: all 0.3s;
        -moz-transition: all 0.3s;
        transition: all 0.3s;
    }

    .modal.fade.in .modal-dialog {
        -webkit-transform: scale(1);
        -moz-transform: scale(1);
        -ms-transform: scale(1);
        transform: scale(1);
        -webkit-transform: translate3d(0, -300px, 0);
        transform: translate3d(0, -300px, 0);
        opacity: 1;
    }

</style>




<script type="text/javascript">
    $(document).ready(function(){

        $(".drive_tap a").removeClass('active');

        var href = 'javascript:void(0);';
         var type = "<?php echo $folder_type; ?>";
        if(type=="SHARED_FOLDER"){
            $("#v_share_folder").addClass('active');
            href = $("#v_share_folder").attr('href');

        }else if(type=="SHARED_FILE"){
            $("#v_share_file").addClass('active');
            href = $("#v_share_file").attr('href');
        }else{
            $("#v_my_folder").addClass('active');
            href = $("#v_my_folder").attr('href');
        }

        $('.resteButton').attr('href',href);



        $(document).on('click','.share_file_link',function(e){
            var parent_obj = $(this).closest(".image_box");
            var id =  parent_obj.attr("data-id");
            var folder_id =  parent_obj.attr("data-folder");
            $("#share_file").modal('show');
            $("#share_file").attr("data-id",id);
            $("#share_file").attr("data-folder",folder_id);

        });




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

        $(document).on('click','.add_more_btn',function(e){
            var len = $(".share_frm .mobile").length;
            var str = '<div class="form-group">' +
                '<input type="text" id="mobile_"+len  placeholder="Mobile number" name="mobile[]" class="mobile form-control" required />' +
                '</div>'
            $(".share_frm").append(str);
            addCountry("#mobile_"+len);
        });


    });
</script>


