<?php
$login = $this->Session->read('Auth.User');

?>

<?php echo $this->Html->css(array('file_module.css','bootstrap-treeview.min.css')); ?>
<?php echo $this->Html->script(array('bootstrap.tooltip.js','bootstrap-confirmation.js','bootstrap-treeview.min.js'));?>

<div class="Inner-banner">
    <div class="container">
        <div class="row">
            <!--  SLIDER IMAGE -->
            <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 inner-page-text"> <h2>My Drive</h2> </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 pull-right create-btn-box">
            </div>
        </div>
    </div>
</div>


<div class="Home-section-2">

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="container file_container">
            <div class="row">
                <div class="col-md-3">
                    <?php echo $this->element('document_left_side_bar'); ?>
                </div>
                <div class="col-md-9">
                    <div class="row search_row">
                        <div class="col-xs-12 col-xs-offset-0">



                            <?php echo $this->element('message'); ?>
                            <?php echo $this->Form->create('Search',array('type'=>'post','url'=>array('controller'=>'app_admin','action' => 'search_folder',"?"=>array("fi"=>$id)),'admin'=>true)); ?>

                            <div class="input-group">
                                <div class="input-group-btn search-panel">
                                    <button type="button" class="btn btn-default dropdown-toggle search_btn" data-toggle="dropdown">
                                        <span id="search_concept">Filter by</span> <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu" role="menu">
                                        <li><a href="#">All</a></li>
                                        <li><a href="#PUBLIC">Public</a></li>
                                        <li><a href="#PRIVATE">Private</a></li>

                                    </ul>
                                </div>
                                <input type="hidden" name="type" value="<?php echo @$this->request->query['t']; ?>" id="search_param">
                                <input type="text" class="form-control large_search_box"  value="<?php echo @$this->request->query['n']; ?>" name="name" placeholder="Search file">
                <span class="input-group-btn">
                    <button class="btn btn-default search_btn" type="Submit"><span class="glyphicon glyphicon-search"></span></button>
                </span>
                            </div>
                            <?php echo $this->Form->end(); ?>

                        </div>
                    </div>
                    <div class="row">


                            <?php if(!empty($folder_list)){ ?>
                            <div class="dashboard_icon_li">
                                <?php foreach ($folder_list as $key  => $file){ ?>
                                <div class="col-sm-4 row_box">
                                    <div class="file_box_container">
                                        <?php
                                            $file_path = Router::url('/thinapp_images/folder-icon.png',true);
                                            $string = "background-image :url('$file_path')";
                                        ?>
                                        <div class="image_box" style="<?php echo  $string; ?>"  data-id="<?php echo base64_encode($file['DriveFolder']['id']); ?>" >

                                            <div class="file_data_content">
                                                <div class="file-name">
                                                    <?php echo $file['DriveFolder']['folder_name']; ?>
                                                    <br>
                                                    <small><i class="fa fa-calendar"></i>  <?php echo date('Y M d',strtotime($file['DriveFolder']['created'])); ?></small>
                                                </div>
                                                <div class="action_icon">
                                                    <a href="javascript:void(0);" data-href="javascript:void(0);"  class="delete_file"><i class="fa fa-trash"></i></a>
                                                    <a href="javascript:void(0);" class="share_file_link"><i class="fa fa-share-alt"></i></a>
                                                    <a href="<?php echo $file_path; ?>" class="file_download" download><i class="fa fa-download"></i></a>
                                                    <a href="<?php echo $file_path; ?>" target="_blank"><i class="fa fa-eye"></i></a>

                                                </div>

                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <?php } ?>

                            </div>
                            <?php echo $this->element('paginator'); ?>

                        <?php }else{ ?>
                            <div class="no_data">
                                <h2>No file found</h2>
                            </div>
                        <?php } ?>





                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<style>

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


<div class="modal fade" id="share_file" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">

    <div class="modal-dialog modal-sm cus_mod">
        <div class="modal-content">
            <?php echo $this->Form->create('Subscriber',array('type'=>'file','method'=>'post','class'=>'form-horizontal sub_frm')); ?>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Share Files</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <div class="col-sm-3 sub_ac_btn">
                        <?php echo $this->Form->submit('Share',array('class'=>'btn btn-info btn-sm share_btn')); ?>
                    </div>
                    <div class="col-sm-3 sub_ac_btn">
                        <?php echo $this->Form->submit('Add More',array('class'=>'btn btn-info btn-sm add_more','type'=>'button')); ?>

                    </div>
                </div>

                <div id="number" class="tab-pane fade in active">
                    <div class="form-group group_div">
                        <div class="clone_div">
                            <div class="col-sm-12 number_div">
                                <div class="input text">
                                    <input type="text" name="mobile[]" class="form-control mobile" required="required">
                                </div>
                                <a href="javascript:void(0);" class="delete_number" style="display: none;"><i class="fa fa-trash-o" aria-hidden="true"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="modal-footer warning_message_file">

            </div>
            <?php echo $this->Form->end(); ?>
        </div>
    </div>

</div>


<script type="text/javascript">
    $(document).ready(function(){



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






        $('.delete_file').confirmation({
            popout : true,
            placement : 'bottom',
            singleton : true,
            onConfirm : function(){

                var btn = $(this);
                var parent_obj = $(this).closest(".image_box");
                var hide_obj = $(this).closest(".row_box");
                var id =  parent_obj.attr("data-id");
                $.ajax({
                    url:baseurl+"/app_admin/delete_file",
                    type:'POST',
                    data:{
                        data_id:id
                    },
                    beforeSend:function(){
                        btn.button('loading').val('...');
                    },
                    success:function(res){
                        var response = JSON.parse(res);
                        if(response.status==1){
                            $(hide_obj).fadeOut('slow');
                        }else{
                            btn.button('reset');
                        }
                    },
                    error:function () {
                        btn.button('reset');
                    }
                });


            },
            onCancel : function(){
                $(this).trigger('toggle');
            }
        });




        var concept = $('#search_param').val();
        if(concept!=""){
            $('#search_concept').text(concept);
        }
        $('.search-panel .dropdown-menu').find('a').click(function(e) {
            e.preventDefault();
            var param = $(this).attr("href").replace("#","");
            var concept = $(this).text();
            $('.search-panel span#search_concept').text(concept);
            $('.input-group #search_param').val(param);
        });


        $(document).on('click','.share_file_link',function(e){
            var parent_obj = $(this).closest(".image_box");
            var id =  parent_obj.attr("data-id");
            var folder_id =  parent_obj.attr("data-folder");
            $("#share_file").modal('show');
            $("#share_file").attr("data-id",id);
            $("#share_file").attr("data-folder",folder_id);

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
        addCountry("#mobile_0");



        $(document).on('mouseover', '.file_box_container', function(e){
            $(this).find(".file_data_content").slideDown('fast');
        });

        $(document).on('mouseleave', '.file_box_container', function(e){
            $(this).find(".file_data_content").slideUp('fast');
        });


        $(this).find(".file_data_content").slideUp('fast');

    });
</script>




