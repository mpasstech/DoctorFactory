<?php
$login = $this->Session->read('Auth.User');
$category_list = $this->AppAdmin->getFileCategory();
?>


<?php echo $this->Html->css(array('file_module.css','bootstrap-treeview.min.css')); ?>
<?php echo $this->Html->script(array('bootstrap.tooltip.js','bootstrap-confirmation.js','bootstrap-treeview.min.js'));?>






<div class="Home-section-2">


    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="container">
            <div class="row">

                <div class="middle-block">
                    <h3 class="screen_title">Medical Record for <?php echo $folder_name; ?></h3>
                    <div class="custom_form_box col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="container file_container">
            <div class="row">

                <div class="col-md-12">
                    <?php echo $this->element('app_admin_inner_tab_drive'); ?>

                    <div class="row search_row">
                        <div class="col-xs-8 col-xs-offset-2">
                            <?php echo $this->element('message'); ?>
                            <?php echo $this->Form->create('Search',array('type'=>'post','url'=>array('controller'=>'app_admin','action' => 'search_drive_data',"?"=>array("fi"=>$id)),'admin'=>true)); ?>

                            <div class="input-group">
                                <div class="input-group-btn search-panel file_type_group">
                                    <button type="button" class="btn btn-default dropdown-toggle search_btn" data-toggle="dropdown">
                                        <span id="search_concept">Filter by</span> <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu" role="menu">
                                        <li><a href="#MEMO">Memo</a></li>
                                        <li><a href="#IMAGE">Image</a></li>
                                        <li><a href="#VIDEO">Video</a></li>
                                        <li><a href="#AUDIO">Audio</a></li>
                                        <li><a href="#PDF">Pdf</a></li>
                                        <li><a href="#DOCUMENT">Document</a></li>
                                        <li><a href="#APK">Apk</a></li>
                                        <li><a href="#OTHER">Other</a></li>
                                        <li><a href="#ANYTHING">Anything</a></li>
                                    </ul>

                                    <!-- this is category filter -->
                                    </div>

                                    <div class="input-group-btn search-panel cat_type_group">
                                    <button type="button" class="btn btn-default dropdown-toggle search_btn" data-toggle="dropdown">
                                        <span id="search_cat">All Category</span> <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu" >
                                        <li><a href="#0">All Category</a></li>
                                        <?php foreach ($category_list as $key => $value){ ?>
                                        <li><a href="<?php echo "#".$key; ?>"><?php echo $value; ?></a></li>
                                        <?php } ?>
                                    </ul>
                                </div>
                                <input type="hidden" name="type" value="<?php echo @$this->request->query['t']; ?>" id="search_param">
                                <input type="hidden" name="cat_type" value="<?php echo @$this->request->query['ct']; ?>" id="cat_param">
                                <input type="text" class="form-control large_search_box"  value="<?php echo @$this->request->query['n']; ?>" name="name" placeholder="Search file">
                <span class="input-group-btn">
                    <button class="btn btn-default search_btn" type="Submit"><span class="glyphicon glyphicon-search"></span></button>
                    <?php $param = !empty($this->request->query['fi'])?"?fi=".$this->request->query['fi']:""; ?>
                    <button class="btn btn-default reset_btn search_btn" type="button" data-ref ="<?php echo Router::url('/app_admin/drive_data',true).$param ?>"><span class="fa fa-undo"></span></button>
                </span>
                            </div>
                            <?php echo $this->Form->end(); ?>

                        </div>
                    </div>
                    <div class="row">


                            <?php if(!empty($file_list)){ ?>
                            <div class="dashboard_icon_li">
                                <?php foreach ($file_list as $key  => $file){

                                    $file_type = $file['DriveFile']['file_type'];
                                    $file_name = $file['DriveFile']['file_name'];
                                    $id =$file_id = $file['DriveFile']['id'];
                                    $folder_id = $file['DriveFile']['drive_folder_id'];
                                    $file_path = $file['DriveFile']['file_path'];
                                    $type = explode(".",$file_path);
                                    $ext = end($type);

                                    ?>
                                <div class="col-sm-3 row_box">

                                   <?php if($file['DriveFile']['listing_type']=="OTHER"){ ?>
                                    <div class="file_box_container">
                                        <div class="file_label"><?php echo $file_type; ?></div>
                                        <?php
                                        $string ="";
                                        if($file_type=="IMAGE"){
                                            $string = "background-image :url('$file_path')";
                                        }
                                        ?>
                                        <div class="image_box" style="<?php echo  $string; ?>" data-folder="<?php echo base64_encode($folder_id); ?>" data-id="<?php echo ($id); ?>" >
                                            <?php if($file_type=="VIDEO"){  $type = explode(".",$file_path);  ?>

                                                <video class="video">
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
                                                    <?php if ($is_owner){ ?>
                                                        <a href="javascript:void(0);" data-id="<?php echo base64_encode($file['DriveFile']['id']); ?>" title="Delete file"  class="delete_file"><i class="fa fa-trash"></i></a>
                                                    <?php } ?>
                                                    <a href="javascript:void(0);" data-id="<?php echo base64_encode($file_id); ?>" sh-tp="<?php echo base64_encode("FILE"); ?>"  title="Share file" class="share_object"><i class="fa fa-share-alt"></i></a>
                                                    <a href="<?php echo $file_path; ?>" class="file_download" title="Download file" download><i class="fa fa-download"></i></a>

                                                 <?php    if($file_type=="IMAGE"){ ?>
                                                     <a  href="javascript:void(0);" data-url="<?php echo $file_path; ?>" class="view_large_image" title="View file"><i class="fa fa-eye"></i></a>

                                                 <?php    }else{ ?>
                                                     <a   href="<?php echo $file_path; ?>"  title="View file"><i class="fa fa-eye"></i></a>

                                                 <?php    } ?>




                                                </div>

                                            </div>

                                        </div>
                                        <div class="file_last_label"><?php echo $file['DriveFile']['file_name']; ?></div>

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
                                                           <a href="javascript:void(0);" data-id="<?php echo base64_encode($file['DriveFile']['id']); ?>" title="Delete file"  class="delete_file"><i class="fa fa-trash"></i></a>
                                                       <?php } ?>
                                                       <!--<a href="javascript:void(0);" sh-tp="<?php /*echo base64_encode("FILE"); */?>"  class="share_object"><i class="fa fa-share-alt"></i></a>-->
                                                       <a href="javascript:void(0);" class="view_memo" data-set="<?php echo $file['DriveFile']['memo_text']; ?>" title="View file" ><i class="fa fa-eye"></i></a>
                                                   </div>
                                               </div>

                                           </div>
                                           <div class="file_last_label"><?php echo mb_strimwidth($file['DriveFile']['memo_text'], 0, 20, '...'); ?></div>

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



<div class="modal fade" id="show_image_dialog" role="dialog" style="overflow: scroll !important;">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
              <div class="modal-body">

              </div>
        </div>
    </div>
</div>

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
        height: 900px;
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


    #show_image_dialog .modal-body{
        padding: 0px;
    }

    #show_image_dialog .modal-content{

    }
    #show_image_dialog .modal-body img{
        width: 100%;
        overflow-y: scroll;
        height: 100%;
    }



    #show_image_dialog .close{
        position: absolute;
        background: #fff;
        width: 30px;
        height: 30px;
        z-index: 999999999;
        right: -12px;
        color: #000;
        opacity: 1;
        border: 1px solid #fff;
        border-radius: 48px;
        padding: 0px 0px;
        top: -14px;
    }
</style>



<script type="text/javascript">
    $(document).ready(function(){




        $(document).on('click','.view_large_image',function(){
            var url = $(this).attr('data-url');
            var html = "<img src="+url+">";
           $("#show_image_dialog .modal-body").html(html);
            $("#show_image_dialog").modal('show');

        });



        $(".drive_tap a").removeClass('active');
        var type = "<?php echo @$this->request->query['of']; ?>";
        if(type=="mf"){
            $("#v_my_folder").addClass('active');
        }else if(type=="sf"){
            $("#v_share_folder").addClass('active');
        }
        $(document).on("click", ".reset_btn", function(){
            window.location.href = $(this).attr("data-ref");
        });



        var concept = $('#search_param').val();
        if(concept!=""){
            $('#search_concept').text(concept);
        }

        var cat = $('#cat_param').val();
        if(cat!=""){
            var txt = $(".cat_type_group li").find("[href='#"+cat+"']").text();
            $('#search_cat').text(txt);
        }


        $('.file_type_group').find('a').click(function(e) {
            e.preventDefault();
            var param = $(this).attr("href").replace("#","");
            var concept = $(this).text();

            $('.file_type_group span#search_concept').text(concept);
            $('#search_param').val(param);
        });



        $('.cat_type_group').find('a').click(function(e) {
            e.preventDefault();
            var param = $(this).attr("href").replace("#","");
            var concept = $(this).text();
            $('.cat_type_group span#search_cat').text(concept);
            $('#cat_param').val(param);
        });


        $(document).on('click','.share_file_link',function(e){
            var parent_obj = $(this).closest(".image_box");
            var id =  parent_obj.attr("data-id");
            var folder_id =  parent_obj.attr("data-folder");
            $("#share_file").modal('show');
            $("#share_file").attr("data-id",id);
            $("#share_file").attr("data-folder",folder_id);

        });



        $(document).on('click','.delete_file',function(e){
            var del_obj = $(this);
            var jc = $.confirm({
                title: 'Delete File',
                content: 'Are you sure you want to delete this file?',
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
                            var file_id =  parent_obj.attr("data-id");
                            $.ajax({
                                url:baseurl+"/app_admin/delete_file",
                                type:'POST',
                                data:{
                                    data_id:file_id
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


        $(document).on('mouseover', '.file_box_container', function(e){
            $(this).find(".file_data_content").slideDown('fast');
        });

        $(document).on('mouseleave', '.file_box_container', function(e){
            $(this).find(".file_data_content").slideUp('fast');
        });


        $(this).find(".file_data_content").slideUp('fast');

    });
</script>




