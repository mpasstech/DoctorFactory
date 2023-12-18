<?php
$login = $this->Session->read('Auth.User');
$folder_list =$this->AppAdmin->getDriveFolderList($login['User']['thinapp_id'],$login['User']['id']);
?>
               <!--     <div class="list-group side_showing_div">
                        <h3>Show</h3>
                        <a focus="ANYTHING" href="<?php /*echo Router::url('/app_admin/drive?t=ANYTHING',true); */?>" class="list-group-item active">
                            <span class="glyphicon glyphicon-picture"></span> All <span class="badge">25</span>
                        </a>
                        <a focus="IMAGE" href="<?php /*echo Router::url('/app_admin/drive?t=IMAGE',true); */?>" class="list-group-item">
                            <span class="glyphicon glyphicon-picture"></span> Images <span class="badge">25</span>
                        </a>
                        <a focus="VIDEO" href="<?php /*echo Router::url('/app_admin/drive?t=VIDEO',true); */?>" class="list-group-item">
                            <span class="glyphicon glyphicon-film"></span> Videos <span class="badge">8</span>
                        </a>
                        <a focus="AUDIO" href="<a href="<?php /*echo Router::url('/app_admin/drive?t=AUDIO',true); */?>" class="list-group-item">
                        <span class="glyphicon glyphicon-music"></span> Audio <span class="badge">50</span>
                        </a>
                        <a focus="DOCUMENT" href="<?php /*echo Router::url('/app_admin/drive?t=DOCUMENT',true); */?>" class="list-group-item">
                            <span class="glyphicon glyphicon-file"></span>Documents <span class="badge">145</span>
                        </a>
                        <a focus="OTHER" href="<?php /*echo Router::url('/app_admin/drive?t=OTHER',true); */?>" class="list-group-item">
                            <span class="glyphicon glyphicon-file"></span> Other <span class="badge">8</span>
                        </a>
                    </div>

<div class="list-group side_showing_div">
     <h3>Folder</h3>
     <div id="tree"></div>
 </div>
-->
<div class="ibox float-e-margins">
    <div class="ibox-content">
        <div class="file-manager">
            <h5>Show:</h5>
            <a href="<?php echo Router::url('/app_admin/drive?t=ANYTHING',true); ?>" class="file-control active">All</a>
            <a href="<?php echo Router::url('/app_admin/drive?t=DOCUMENT',true); ?>" class="file-control">Documents</a>
            <a href="<?php echo Router::url('/app_admin/drive?t=AUDIO',true); ?>" class="file-control">Audio</a>
            <a href="<?php echo Router::url('/app_admin/drive?t=VIDEO',true); ?>" class="file-control">Video</a>
            <a href="<?php echo Router::url('/app_admin/drive?t=IMAGE',true); ?>" class="file-control">Images</a>
            <a href="<?php echo Router::url('/app_admin/drive?t=OTHER',true); ?>" class="file-control">Other</a>
            <div class="hr-line-dashed"></div>

            <!--  <h5>Shared:</h5>

                                <a href="<?php /*echo Router::url('/app_admin/drive?t=s_folder',true); */?>" class="file-control">Share Folder List</a>
                                <a href="<?php /*echo Router::url('/app_admin/drive?t=s_file',true); */?>" class="file-control">Share File List</a>
                                <div class="hr-line-dashed"></div>
-->
            <div class="btn_div_sec">
                <ul class="btn_action_cls">
                    <li><a href="javascript:void(0);" class="folder_btn"><i class="fa fa-folder"></i> Add Folder</a></li>
                    <li><a href="javascript:void(0);" class="upload_btn"><i class="fa fa-file"></i> Add File</a></li>
                </ul>
            </div>


            <div class="hr-line-dashed"></div>
            <h5>Folders</h5>
            <div class="war-messages"></div>
            <?php if(!empty($folder_list)){ ?>
                <ul class="folder-list" style="padding: 0">
                    <?php foreach ($folder_list as $key => $folder ){ ?>
                        <li>
                            <div class="label-name" data-href="<?php echo Router::url('/app_admin/drive?fi=',true).base64_encode($folder['id']); ?>">
                                <a href="<?php echo Router::url('/app_admin/drive?fi=',true).base64_encode($folder['id']); ?>"><div class="folder_div"> <?php echo $folder['folder_name']; ?> </div></a>
                            </div>
                            <div class="edit_btn_div">
                                <a class="edit_btn edit_rename_btn" href="javascript:void(0);" title="Edit folder name"><i  data-set="<?php echo base64_encode($folder['id']); ?>" class="fa fa-pencil btn_rename"></i> </a>
                                <a class="edit_btn delete_folder_btn" href="javascript:void(0);" data-set="<?php echo base64_encode($folder['id']); ?>" title="Edit folder name"><i class="fa fa-trash-o"></i> </a>

                            </div>


                        </li>
                    <?php } ?>
                </ul>
            <?php } ?>
            <!-- <h5 class="tag-title">Tags</h5>
             <ul class="tag-list" style="padding: 0">
                 <li><a href="">Family</a></li>
                 <li><a href="">Work</a></li>
                 <li><a href="">Home</a></li>
                 <li><a href="">Children</a></li>
                 <li><a href="">Holidays</a></li>
                 <li><a href="">Music</a></li>
                 <li><a href="">Photography</a></li>
                 <li><a href="">Film</a></li>
             </ul>-->
            <div class="clearfix"></div>
        </div>
    </div>
</div>


<div class="modal fade" id="folder_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <?php echo $this->Form->create('Folder',array('type'=>'file','method'=>'post','class'=>'form-horizontal folder_frm')); ?>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Create Folder</h4>
            </div>
            <div class="modal-body">
                <div id="number" class="tab-pane fade in active">
                    <div class="form-group">
                        <div class="col-sm-9">
                            <?php echo $this->Form->input('name',array('id'=>'folder_box_name','type'=>'text','placeholder'=>'Folder name','label'=>false,'class'=>'form-control','required'=>false)); ?>
                            <?php if($instruction_book =="YES"){ ?>
                                <?php echo $this->Form->input('book',array('id'=>'insbook','type'=>'checkbox','label'=>'Add folder for instruction book','class'=>'ins_book')); ?>
                            <?php } ?>

                        </div>



                        <div class="col-sm-3 add_fol_btn">
                            <?php echo $this->Form->submit('Save',array('class'=>'btn btn-info btn-sm share_btn')); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12">
                            <?php echo $this->Form->input('description',array('id'=>'description','type'=>'textarea','placeholder'=>'About folder','label'=>false,'class'=>'form-control','required'=>false)); ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer warning_message">

            </div>
            <?php echo $this->Form->end(); ?>
        </div>
    </div>

</div>


<div class="modal fade" id="upload_file" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">

    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Upload Files</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">



                    <?php
                    $list =array();
                    if(!empty($folder_list)){
                        foreach ($folder_list as $key => $folder){
                            $list[$folder['DriveFolders']['id']] =$folder['DriveFolders']['folder_name'];
                        }
                    }

                    ?>

                    <div class="col-sm-12">
                        <form id="myId" class="dropzone">
                            <div class="folder_div">
                                <?php echo $this->Form->input('folder_name',array('type'=>'select','label'=>false,'options'=>$list,'class'=>'form-control cnt')); ?>
                                <button type="button" class="btn btn-info" id="upload"><i class="fa fa-plus"></i> Upload</button>
                            </div>

                            <div class="fallback">
                                <input name="file" type="file" multiple />
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





<script type="text/javascript">
    $(document).ready(function(){

        var tree = [
            {
                text: "My Folder",
                nodes: [
                    {
                        text: "Child 2"
                    }
                ]
            },
            {
                text: "Shared Foder",
                nodes: [
                    {
                        text: "Child 2"
                    }
                ]
            },
            {
                text: "Shared File 2"
            }


        ];

        /* $('#tree').treeview({
         data: tree,         // data is not optional
         levels: 5
         });

         */

        $(".drive_tap a").removeClass('active');
        var url = window.location.href;

        var pieces = url.split("?t=");
        if(pieces.length > 1 ){
            pieces = pieces[1].split("&&");
            if(pieces[0]){
                $(".side_showing_div a").removeClass('active');
                $(".side_showing_div a[focus='"+pieces[0]+"']").addClass('active');
            }
        }




        $(document).on('click','.folder_btn', function(e){
            $("#folder_modal").modal('show').find("input[type=text], textarea").val("");
            $(".warning_message").html("");
        });


       


        $(document).on('click','.upload_btn',function(e){
            $("#upload_file").modal('show');
        });



        var myDropzone = new Dropzone("#myId", {
            url: "<?php echo Router::url('/app_admin/upload_file')?>",
            autoProcessQueue: false,
            maxFilesize: 25, // MB
            addRemoveLinks:true,
            parallelUploads:10

        });

        $(document).on('click','#upload',function(e){
            var accept_files = myDropzone.getAcceptedFiles().length;
            var reject_files = myDropzone.getRejectedFiles().length;
            var folder = $("#folder_name").val();

            if(folder != ""){
                if(accept_files == 0 && reject_files ==0 ){
                    $.alert('Please upload files to upload.');
                }else if( reject_files > 0 ){
                    $.alert('Please upload validate files.');
                }else{
                    myDropzone.processQueue();
                }
            }else{

            }


        });
        myDropzone.on("complete", function(file) {
            // myDropzone.removeFile(file);
        });
        myDropzone.on("uploadprogress", function(file, progress, bytesSent) {

        });

        myDropzone.on("queuecomplete", function(file) {
            window.location.reload();
        });


        $(document).on('submit','.folder_frm',function(e){

            var name = $("#folder_box_name").val();
            var description = $("#description").val();
            var insbook = $("#insbook").is(":checked");
            if(name==""){
                $("#folder_box_name").css('border-color','red');
            }else{
                $("#folder_box_name").css('border-color','#ccc');

                var btn = $(".share_btn");
                var parent_obj = $(this).closest(".image_box");
                var id =  parent_obj.attr("data-id");
                $.ajax({
                    url:baseurl+"/app_admin/add_folder",
                    type:'POST',
                    data:{
                        name:name,
                        description:description,
                        ins_book:insbook
                    },
                    beforeSend:function(){
                        btn.button('loading').val('Wait..');
                    },
                    success:function(res){
                        var response = JSON.parse(res);
                        if(response.status==1){

                            $("#folder_modal").modal('hide');
                            window.location.reload();
                        }else{
                            btn.button('reset');
                        }

                        $(".warning_message").html(response.message);
                    },
                    error:function () {
                        btn.button('reset');
                    }
                });




            }
            return false;
        });

        
        
        
        $(document).on('submit','.sub_frm',function(e){
            var ret = [];
            $(".number_div input").each(function () {
                if(/^[0-9]{4,13}$/.test($(this).val())){
                    $(this).css('border-color','#ccc');
                }else{
                    $(this).css('border-color','red');
                    ret.push(0);
                }
            });
            if($.inArray(0,ret) == -1){

                var mobile = [];

                console.log($(".mobile").length);

                $(".mobile").each(function (index, value) {



                    var countryData = $(this).intlTelInput("getSelectedCountryData");
                    var val = $(this).val();
                    mobile.push("+"+countryData.dialCode+""+ val);
                    //$(this).val();
                });



                /* send request */
                var btn = $(".share_btn");
                var parent_obj = $("#share_file");
                var id =  parent_obj.attr("data-id");
                var folder_id =  parent_obj.attr("data-folder");
                $.ajax({
                    url:baseurl+"/app_admin/share_file",
                    type:'POST',
                    data:{
                        file_id:id,
                        folder_id:folder_id,
                        mobile:mobile
                    },
                    beforeSend:function(){
                        btn.button('loading').val('Wait..');
                    },
                    success:function(res){
                        var response = JSON.parse(res);
                        if(response.status==1){
                            $("#share_file").modal('hide');
                            window.location.reload();
                        }else{
                            btn.button('reset');
                        }
                        $(".warning_message_file").html(response.message);

                    },
                    error:function () {
                        btn.button('reset');
                    }
                });
                return false;
                /* send request */

            }

        });

        $(document).on('click','.add_more',function(e){
            var len =$(".mobile").length-1;
            var str = '<div class="col-sm-12 number_div"><div class="input text"><input type="text" name="mobile[]" class="form-control mobile" required="required"></div><a href="javascript:void(0);" class="delete_number" style="display: block;"><i class="fa fa-trash-o" aria-hidden="true"></i></a></div>';
            $(".group_div").append(str);
            addCountry("#mobile_"+len);
        });

        $(document).on("keyup blur",".mobile", function() {
            if($(this).intlTelInput("isValidNumber")){
                $(this).css('border-color',"#ccc");

            }else{
                $(this).css('border-color',"red");
            }
        });

        $(document).on('click','.delete_number',function(e){
            var len = $(".number_div").length;
            if(len >1 ){
                $(this).closest(".number_div").remove();
            }
        })

        $(document).on('click','.btn_rename',function(e){

            var folder_id = $(this).attr("data-set");
            var obj = $(this).closest("li");
            var val = $(obj).find(".folder_div").text();
            var str = "<input  class='folder_name' type='text' value='"+val+"'>";
            $(obj).find(".folder_div").html(str);
            $(obj).find('.label-name a').attr("href","javascript:void(0);");
            $(this).closest('a').html("<i data-set='"+folder_id+"' class='fa fa-save save_name'></i>");

        })

        $(document).on('click','.save_name',function(e){

            var btn = $(this);
            var folder_id = $(this).attr("data-set");
            var obj = $(this).closest("li");
            var folder_name = $(obj).find(".folder_name").val();
            var folder_div = $(obj).find(".folder_div");
            var link = $(obj).find('.label-name').attr("data-href");
            var link_obj = $(obj).find('.label-name a');
            var icon_obj = $(obj).find('.edit_rename_btn');

            if(folder_name){
                $.ajax({
                    url:baseurl+"/app_admin/rename_folder",
                    type:'POST',
                    data:{
                        data_set:folder_id,
                        folder_name:folder_name
                    },
                    beforeSend:function(){
                        $(icon_obj).html("<i data-set='"+folder_id+"' class='fa fa-spinner'></i>");
                    },
                    success:function(res){

                        var response = JSON.parse(res);
                        if(response.status==1){
                            $(folder_div).html(folder_name);
                            $(icon_obj).html("<i data-set='"+folder_id+"' class='fa fa-pencil btn_rename'></i>");
                            $(link_obj).attr('href',link);
                            $(".war-messages").html("");
                        }else{
                            $(icon_obj).html("<i data-set='"+folder_id+"' class='fa fa-save save_name'></i>");
                            $(".war-messages").html(response.message);
                        }
                    },
                    error:function () {
                        btn.button('reset');
                    }
                });
            }

        })

        $(document).on('click','.btn_delete',function(e){

            var btn = $(this);
            var folder_id = $(this).attr("data-set");
            var icon_obj = $(obj).find('.edit_rename_btn');
            $.ajax({
                url:baseurl+"/app_admin/rename_folder",
                type:'POST',
                data:{
                    data_set:folder_id,
                    folder_name:folder_name
                },
                beforeSend:function(){
                    $(icon_obj).html("<i data-set='"+folder_id+"' class='fa fa-spinner'></i>");
                },
                success:function(res){

                    var response = JSON.parse(res);
                    if(response.status==1){
                        $(folder_div).html(folder_name);
                        $(icon_obj).html("<i data-set='"+folder_id+"' class='fa fa-pencil btn_rename'></i>");
                        $(link_obj).attr('href',link);
                        $(".war-messages").html("");
                    }else{
                        $(icon_obj).html("<i data-set='"+folder_id+"' class='fa fa-save save_name'></i>");
                        $(".war-messages").html(response.message);
                    }
                },
                error:function () {
                    btn.button('reset');
                }
            });

        })

        $(document).on("click", ".delete_folder_btn", function(){
            var folder_id = $(this).attr("data-set");
            var obj = $(this);

            if(confirm("Are you sure you want to delete this folder?")){
                $.ajax({
                    url:baseurl+"/app_admin/delete_folder",
                    type:'POST',
                    data:{
                        data_set:folder_id
                    },
                    beforeSend:function(){
                        $(obj).html("<i class='fa fa-spinner'></i>");
                    },
                    success:function(res){

                        var response = JSON.parse(res);
                        if(response.status==1){
                            $(obj).closest("li").slideUp(1000);
                        }else{
                            $(obj).html("<i data-set='"+folder_id+"' class='fa fa-save btn_delete'></i>");
                            $(".war-messages").html(response.message);
                        }

                    },
                    error:function () {
                        btn.button('reset');
                    }
                });
            }
        });


    });
</script>
