<div class="modal fade" id="stepModal"  role="dialog"  >



    <?php

   echo $this->Html->css(array('lightslider.css'),array("media"=>'all','fullBase' => true));
    echo $this->Html->script(array('lightslider.js'),array('fullBase' => true));

    ?>

    <?php  echo $this->Html->script(array('advance_prescription.js'),array('fullBase' => true)); ?>
    <div class="modal-dialog" style="width: auto;margin-top: 10px;" >
        <div class="modal-content" style="">
            <ul class="modal-body step_container" id="lightSlider" style="width: 100%!important;">
                    <?php if(!empty($steps_list)){ ?>
                        <?php foreach ($steps_list as $key =>$step){ ?>
                        <li data-slide-index="<?php echo $key; ?>" class="main_step_box" data-cn="<?php echo $category_name; ?>" data-cmi="<?php echo $step['master_category_id']; ?>" data-st="<?php echo $step['step_title']; ?>" data-si="<?php echo $step['step_id']; ?>">


                            <?php $add_tag_class = ($step['tab_master_prescription_step_id'] == $master_medicine_name_step_id)?'add_medicine_btn':'add_new_tag'; ?>
                            <h4 class="step_title" ><?php echo $step['step_title']; ?>   <span class="<?php echo $add_tag_class; ?>" data-ci="<?php echo base64_encode($category_id); ?>" data-si="<?php echo base64_encode($step['step_id']); ?>"><i class="fa fa-plus"></i></span> </h4>
                            <input type="text" class="tag_search_box" tabindex="<?php echo $key+1; ?>"  placeholder="Search for <?php echo $step['step_title']; ?>.." title="Type in a <?php echo $step['step_title']; ?>">

                                    <ul id="tag_list_box_<?php echo $step['step_id']; ?>" class="tag_container">
                                        <?php if(!empty($step['tag_list'])){ ?>
                                        <script>

                                            $("#tag_list_box_<?php echo $step['step_id']; ?>").data('key','<?php echo json_encode($step['tag_list']); ?>');
                                        </script>
                                        <?php foreach ($step['tag_list']as $tag_key =>$tag){
                                                echo  $this->AppAdmin->create_tag_layout($tag,$step['step_id']);
                                            }?>
                                        <?php } ?>
                                    </ul>

                        </li>
                    <?php } ?>
                    <?php } ?>
            </ul>

            <div class="btn_container">
                <?php if($step['master_category_id']==$internal_notes_master_category_id){ ?>
                    <button type="button" class="btn btn-warning" data-show="YES" id="save_as_template_btn"> Save & Print</button>
                    <button type="button" class="btn btn-warning" data-show="NO" id="save_as_template_btn"> Save</button>
                <?php }else{ ?>
                    <button type="button" class="btn btn-warning" id="save_as_template_btn"> Save As Template</button>
                    <button type="button" class="btn btn-warning" id="add_more_btn"> Add More  <span id="total_template">0</span></button>
                    <button type="button" class="btn btn-warning" id="done_btn" data-cn="<?php echo $category_name; ?>" data-ci="<?php echo base64_encode($category_id); ?>"> Done</button>
                <?php } ?>

            </div>
        </div>
    </div>

    <style>




        .company_lbl{
            font-size: 8px;
            display: block;
            color: blue;
            text-transform: uppercase;
            font-weight: 600;
        }
        .counter_ul{
            padding: 0px;
            margin: 0px;
            list-style: none;
        }
        .select_count{
            float: left;
            list-style: none;
            padding: 6px;
            /* background: red; */
            color: #1b42ff;
            text-align: center;
            width: 35px;
            height: 35px;
            border-radius: 31px;
            background: transparent;
            border: 1px solid;
            margin: 2px;
            cursor: pointer;
        }
        .select_count:hover{
            background: #1b42ff;
            color: #fff;

        }

        .tag_box .dropdown-toggle{
            box-shadow: none;
        }
        .tag_container .tag_box button{
            outline: none !important;
            border-radius: 0px;
            background: transparent;
            border: none;
            padding: 0px;
            margin: 0px;
        }
        #total_template{
            position: absolute;
            padding: 2px;
            background: #1e8d0a;
            border-radius: 36px;
            width: 20px;
            height: 20px;
            text-align: center;
            margin-top: -15px;
            font-size: 13px;
        }
        .btn_container button{
            border-radius: 34px;
            margin: 3px 2px;
        }
        .btn_container{
            width: 100%;
            display: block;
            background: #fff;
            text-align: center;
            margin: 0 auto;

        }
        .tag_search_box {
            background-image: url('<?php echo Router::url("/img/web_prescription/search.png",true); ?>');
            background-position: 10px 5px;
            background-repeat: no-repeat;
            width: 99%;
            font-size: 16px;
            padding: 5px 10px 5px 37px;
            border: 1px solid #ddd;
            margin: 5px 0px;
            border-radius: 51px;
            outline: none;
        }
        .main_step_box{
            float: left;
            width: 100%;
            display: block;
            border: 1px solid #1fbc1f;
            margin-right: 0px !important;
            margin: 0px 2px;
            height: 550px;
            background: #fff;


        }
        .step_container{
            width: 100%;
            float: left;
            background: #ffffffab;
            text-align: center;
            padding: 0px;
            margin: 0px;
        }
        .tag_container{
            padding: 0px;
            margin: 0px;
            list-style: none;
            float: left;
            height: 471px;
            overflow-y: auto;
            width: 100%;
        }
        .step_title{
            background: #1fbc1f;
            color: #fff;
            padding: 10px 5px;
            margin: 0px;
            text-align: center;
        }
        .add_new_tag, .add_medicine_btn{
            float: right;
        }
        .tag_container .tag_box{
            list-style: none;
            float: left;
            margin: 3px 2px;
            border: 1px solid #1fbc1f;
            padding: 3px 9px;
            border-radius: 20px;
            font-size: 15px;
            cursor: pointer;
            word-break: break-all;
        }
        .tag_container .tag_box.active, .tag_container .tag_box.active span{
            background: #1b42ff;
            border-color: #1b42ff;
            color:#fff;
        }

        .close_dialog_btn{
            position: absolute;
            top: 0;
            right: 0px;
            background: red;
            color: #fff;
            font-size: 14px;
            padding: 4px;
            width: 30px;
            height: 30px;
        }
    </style>

    <script type="text/javascript">
        $(function () {



            var slider = $("#lightSlider").lightSlider({
                useCSS: false
            });

            setTimeout(function () {
                $(".tag_search_box:first").focus();
            },10);


            var internal_notes_master_category_id = "<?php echo $internal_notes_master_category_id; ?>";

            $(document).off('input','.tag_search_box');
            $(document).on('input','.tag_search_box',function(){
                var  filter, txtValue;
                filter = $(this).val().toUpperCase();
                $(this).closest('.main_step_box').find('.tag_container .tag_box').each(function( index ) {
                    txtValue = $(this).find(".tag_button_class").text();
                    console.log(txtValue);
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });




            function setBoxHeight(){

                var width = $(window).width();
                var width = parseInt(width/3);
                var total_box = $(".main_step_box").length;
                if(total_box > 3){
                    total_box = 3;
                }else if(total_box == 0){
                    total_box =1;
                }
                total_box = (total_box==0)?1:total_box;
                var dialog_width =parseInt((width*total_box)-(total_box *13));
                $("#stepModal .modal-dialog").width(dialog_width);
                $(".main_step_box").width(width-20);


            }


            $(window).off('resize');
            $(window).on('resize', function(){
                setBoxHeight();
            });

            setTimeout(function () {
                setBoxHeight();
            },50);



            $(document).off('click','.tag_button_class');
            $(document).on('click','.tag_button_class',function(){


                var obj = $(this).closest('li');



                var tag_name = $.trim($(obj).find(".tag_button_class").attr('data-tn').toUpperCase());
                if($(this).closest('li').hasClass('active')){
                    var tag_name = $.trim($(obj).find(".tag_button_class").text().toUpperCase());
                    $(this).closest('li').removeClass('active');
                    if( tag_name== "DAYS"  || tag_name == "HOURS" || tag_name== "WEEKS" || tag_name== "YEAR"){
                        $(obj).find(".tag_button_class").attr('data-tn',$(obj).find(".tag_button_class").text());
                    }
                }else{
                    $(obj).addClass('active');
                    var string = "";
                    var length =0;
                    if( tag_name== "DAYS"  || tag_name == "HOURS" || tag_name== "WEEKS" || tag_name== "YEAR"){
                        if (tag_name == "HOURS") {
                            length = 12;
                        } else {
                            length = 30
                        }
                        for (counter = 1; counter <= length; counter++) {
                            string += "<li class='select_count'>" + counter + "</li>";
                        }
                        var dialog = $.confirm({
                            title: "Select Number <li class='select_count close_dialog_btn'>X</li>",
                            content: '<ul class="counter_ul">'+ string +'</ul>',
                            buttons:false,
                            closeIcon:false,
                            onContentReady: function () {
                                $(document).off("click",".select_count");
                                $(document).on("click",".select_count",function(){
                                    if(!$(this).hasClass('close_dialog_btn')){
                                        var number = $.trim($(this).text());
                                        var text = $.trim($(obj).find(".tag_button_class").text());
                                        $(obj).find(".tag_button_class").attr('data-tn',number+" "+text);
                                    }else{
                                        $(obj).find(".tag_button_class").closest(".tag_box ").removeClass("active");
                                    }
                                    dialog.close();
                                });
                            }
                        });

                    }
                }

                var current_selectd_index =slider.getCurrentSlideCount();
                var slide_index =$(this).closest('.main_step_box').attr("data-slide-index");
                slider.goToSlide(slide_index-1);

            });

            function setACookie(cookie_name,cookie_value,exdays=365){
                cookie_value = JSON.stringify(cookie_value);
                var d = new Date();
                d.setTime(d.getTime() + (exdays*1000*60*60*24));
                var expires = "expires=" + d.toGMTString();
                window.document.cookie = cookie_name+"="+cookie_value+"; "+expires;

            }
            function readCookie(cookie_name) {
                var data=getCookie(cookie_name);
                if (data != "") {
                    return JSON.parse(data);
                } else {
                    return false;
                }
            }
            function getCookie(cname) {
                var name = cname + "=";
                var cArr = window.document.cookie.split(';');
                for(var i=0; i<cArr.length; i++) {
                    var c = cArr[i].trim();
                    if (c.indexOf(name) == 0)
                        return c.substring(name.length, c.length);
                }
                return "";
            }



            var final_template_array=[];
            $(document).off('click','#done_btn');
            $(document).on('click','#done_btn',function(){
                var return_data = create_template_array(this);
                var category_id = atob($(this).attr('data-ci'));
                var category_name = ($(this).attr('data-cn'));
                if(return_data !==false){
                    var prescription_id =$(".pre_preview_box").attr('data-pre');
                    var temp_obj = getTemplateArray();
                    if(!temp_obj){
                        temp_obj = new Object();
                    }

                    var temp_inner =new Object();
                    temp_inner['category_id'] = category_id;
                    temp_inner['category_name'] = category_name;
                    temp_inner['data'] = final_template_array;
                    temp_obj["category_"+category_id] = temp_inner;
                    setTemplateArray(temp_obj);

                    $(".main_step_box .tag_container li").removeClass('active');
                    create_prescription_data(final_template_array,category_id,category_name,"STEP");
                    $("#stepModal").modal('hide');
                    $("#myTemplateTable .template_tr").removeClass("selected");

                }



            });




            $(document).off('click','#add_more_btn');
            $(document).on('click','#add_more_btn',function(){
                var return_data = create_template_array(this);
                if(return_data !==false){
                    $(".main_step_box .tag_container li").removeClass('active');
                    slider.goToSlide(0);
                }
            });

           function create_template_array(obj){
              var template_array = [];
              var id = $(obj).attr('id');
              var allow_add_template = true;
              var total_selected_tag = $(".main_step_box .tag_container li.active").length;
              if(id=="add_more_btn" && total_selected_tag ==0){
                  allow_add_template = false;
              }else if(id=="done_btn" && total_selected_tag ==0 && final_template_array.length ==0 ){
                  allow_add_template = false;
              }
              if(allow_add_template ===true){
                  $(".main_step_box").each(function () {
                      var selected_tag = $(this).find(".tag_container li.active").length;
                      if(selected_tag>0){
                          var template_obj= {};
                          var tag_array=[];
                          var step_name = step_id = "";
                          template_obj['s_id']=$(this).attr('data-si');
                          template_obj['step_id']= step_id = $(this).attr('data-si');
                          template_obj['step_title']=step_name = $(this).attr('data-st');
                          template_obj['category_name']=step_name = $(this).attr('data-cn');
                          template_obj['category_id']=step_name = $(this).attr('data-cmi');
                          $(this).find(".tag_container .active .tag_button_class").each(function () {
                              var tag_obj= {};
                              tag_obj['tag_id']=$(this).attr('data-ti');
                              tag_obj['tag_title']=$(this).attr('data-tn');
                              tag_obj['template_id']=0;
                              tag_array.push(tag_obj);
                          });
                          template_obj['selected_tag']=tag_array;
                          template_array.push(template_obj);
                      }

                  });

                  if(template_array.length > 0){

                     final_template_array.push(template_array);
                      $("#total_template").html(final_template_array.length);
                  }
                  return final_template_array;
              }else{
                  flash("Select Tag","Please select at least one tag", "warning",'center');
                  return false;
              }
          }




          function save_template(dialog,btn,obj,btn_type,master_category_id,click_object){
              var return_data = create_template_array(this);
              if(return_data !==false){
                  var data_array = JSON.stringify({'template_array':final_template_array});
                  var template_name = obj.$content.find('#template_name_box').val();
                  var category_id = "<?php echo ($category_id); ?>";
                  var category_name = "<?php echo $category_name; ?>";
                  var patient_data = $("#patient_detail_object").data('key');
                  patient_data = JSON.parse(patient_data);
                  var patient_id = patient_data.general_info.patient_id;
                  var patient_type = patient_data.general_info.patient_type;
                  $.ajax({
                      type: 'POST',
                      url: "<?php echo Router::url('/prescription/save_template',true); ?>",
                      data: {da:data_array,tn:template_name,ci:btoa(category_id),pi:btoa(patient_id),pt:patient_type,mci:btoa(master_category_id)},
                      beforeSend: function () {
                          btn.button({loadingText: 'Saving...'}).button('loading');
                      },
                      success: function (data) {
                          btn.button('reset');
                          data = JSON.parse(data);
                          if(data.status==1){
                              dialog.close();
                              if(btn_type=='SAVE'){
                                  $(".main_step_box .tag_container li").removeClass('active');
                                  setTemplateArray("");
                              }else if(btn_type=='SAVE_DONE'){
                                  $(".main_step_box .tag_container li").removeClass('active');
                                  $("#stepModal").modal('hide');
                                  create_prescription_data(final_template_array,category_id,category_name,"STEP");
                              }
                              $(".pre_template_box").html(data.template_list);
                          }else{
                              $.alert(data.message);
                              setTemplateArray("");
                              updateTemplateCount();
                          }

                      },
                      error: function (data) {
                          btn.button('reset');
                          $.alert("Sorry something went wrong on server.");
                          setTemplateArray("");
                          updateTemplateCount();
                      }
                  });
              }
          }

            $(document).off('click','.add_new_tag');
            $(document).on('click','.add_new_tag',function(e){
                var title_lbl = $(this).closest('.main_step_box').attr('data-st');
                var cat_master_id = $(this).closest('.main_step_box').attr('data-cmi');
                var si = atob($(this).attr('data-si'));
                var ms;
                var new_tag_array=[];
                var string = "<input type='text' id='add_new_tag_box' class='form-control'>";
                var title  ='Add New '+title_lbl;
                var label  ="Enter "+title_lbl;
                var enter_label ='';
                if(cat_master_id == internal_notes_master_category_id){
                    title  ="Add Notes";
                    label  ="Enter Notes";
                    string = "<textarea  rows='3' style='height: auto;' id='add_new_tag_box' class='form-control' ></textarea>";
                }else{
                    enter_label = '<span style="font-size: 12px;">(Press enter after adding tag)</span>';
                }


                var dialog = $.confirm({
                    title: title,
                    content: '' +
                    '<div class="form-group">' +
                    '<label>'+label+'</label> '+enter_label+'<br>' +
                    string +
                    '</div>',
                    buttons: {
                        save: {
                            text: 'Save',
                            btnClass: 'btn-blue save_tag_btn',
                            action: function () {
                                var btn = $(".save_tag_btn");
                                var tag_name = this.$content.find('#add_new_tag_box').val();
                                if(cat_master_id != internal_notes_master_category_id){
                                     tag_name = new_tag_array.join('@#@#');
                                     if(tag_name ==""){
                                         tag_name = this.$content.find('#add_new_tag_box').val();
                                         new_tag_array.push(tag_name);
                                     }

                                }else{
                                    new_tag_array.push(tag_name);
                                }


                                $.ajax({
                                    type: 'POST',
                                    url: "<?php echo Router::url('/prescription/manage_tag',true); ?>",
                                    data: {tn:tag_name,si:btoa(si),'at':'ADD',cmi:cat_master_id},
                                    beforeSend: function () {
                                        btn.button({loadingText: 'Saving...'}).button('loading');
                                    },
                                    success: function (data) {
                                        btn.button('reset');
                                        data = JSON.parse(data);
                                        if(data.status==1){
                                            dialog.close();
                                            $("#tag_list_box_"+si).append(data.html);

                                        }else{
                                            $.alert(data.message);
                                        }
                                    },
                                    error: function (data) {
                                        btn.button('reset');
                                        $.alert("Sorry something went wrong on server.");
                                        setTemplateArray("");
                                        updateTemplateCount();
                                    }
                                });
                                return false;
                            }
                        },
                        cancel: function () {
                            //close
                        },
                    },
                    onContentReady: function () {
                        var tags_string = $("#tag_list_box_"+si).data('key');
                        if(cat_master_id != internal_notes_master_category_id){
                            ms = $("#add_new_tag_box").tagSuggest({
                                data: JSON.parse(tags_string),
                                sortOrder: 'tag_name',
                                displayField: 'tag_name',
                                valueField: 'tag_name',
                                allowFreeEntries:true,
                                required: true,
                                maxDropHeight: 0,
                                allowDuplicates:false,
                                resultAsString: true,
                                maxSelection: 15,
                                noSuggestionText: 'You can enter only 15 Tags at once',
                                name: si
                            });
                            $(ms).on('selectionchange', function(){
                                new_tag_array = this.getValue();
                            });
                        }

                    }
                });

            });

            $(document).off('click','.add_medicine_btn, .edit_medicine_btn');
            $(document).on('click','.add_medicine_btn, .edit_medicine_btn',function(e){
                var $btn = $(this);
                var si = $(this).attr('data-si');
                var ti = $(this).attr('data-ti');


                $.ajax({
                    url: "<?php echo Router::url('/prescription/manage_medicine_modal',true);?>",
                    type:'POST',
                    data:{si:si,ti:ti},
                    beforeSend:function(){
                       // $btn.button('loading').text('Wait..');
                    },
                    success: function(result){
                        $btn.button('reset');
                        var html = $(result).filter('#manageMedicineModal');
                        $(html).modal('show');
                    },error:function () {
                        $btn.button('reset');
                    }
                });
            });





            $(document).off('click','#save_as_template_btn');
            $(document).on('click','#save_as_template_btn',function(e){

                var click_object = $(this);
                var total_selected_tag = $(".main_step_box .tag_container li.active").length;
                var cat_master_id = $(this).closest(".step_container").find('.main_step_box').attr('data-cmi');
                if(total_selected_tag > 0 || final_template_array.length > 0){

                    if(cat_master_id == internal_notes_master_category_id) {
                        var btn = $(this);
                        var data_show = $(this).attr('data-show');


                        var return_data = create_template_array(this);
                        if (return_data !== false) {
                            var data_array = JSON.stringify({'template_array': final_template_array});
                            var template_name = "";
                            var category_id = "<?php echo($category_id); ?>";
                            var category_name = "<?php echo $category_name; ?>";
                            var patient_data = $("#patient_detail_object").data('key');
                            patient_data = JSON.parse(patient_data);
                            var patient_id = patient_data.general_info.patient_id;
                            var patient_type = patient_data.general_info.patient_type;
                            $.ajax({
                                type: 'POST',
                                url: "<?php echo Router::url('/prescription/save_template', true); ?>",
                                data: {
                                    da: data_array,
                                    tn: template_name,
                                    ci: btoa(category_id),
                                    pi: btoa(patient_id),
                                    pt: patient_type,
                                    mci: btoa(cat_master_id)
                                },
                                beforeSend: function () {
                                    btn.button({loadingText: 'Saving...'}).button('loading');
                                },
                                success: function (data) {
                                    btn.button('reset');
                                    data = JSON.parse(data);
                                    if (data.status == 1) {
                                        $(".main_step_box .tag_container li").removeClass('active');
                                        $("#stepModal").modal('hide');
                                        if(data_show=="YES"){
                                            create_prescription_data(final_template_array, category_id, category_name, "STEP");
                                        }

                                        $(".pre_template_box").html(data.template_list);

                                    } else {
                                        $.alert(data.message);
                                        setTemplateArray("");
                                        updateTemplateCount();
                                    }
                                },
                                error: function (data) {
                                    btn.button('reset');
                                    $.alert("Sorry something went wrong on server.");
                                    setTemplateArray("");
                                    updateTemplateCount();
                                }
                            });

                        } else {
                            flash("Select Tag", "Please select at least one tag", "warning", 'center');
                        }
                    }else {
                        var string = "<input type='text' id='template_name_box' class='form-control'>";
                        var data_array = JSON.stringify({'template_array': final_template_array});
                        var dialog = $.confirm({
                            title: 'Save Template',
                            content: '' +
                            '<div class="form-group">' +
                            '<label>Template Name</label><br>' +
                            string +
                            '</div>',
                            buttons: {
                                save: {
                                    text: 'Save',
                                    btnClass: 'btn-blue save_template_btn',
                                    action: function () {
                                        var $btn = $(".save_template_btn");
                                        save_template(dialog, $btn, this, 'SAVE', cat_master_id, click_object);
                                        return false;
                                    }
                                },
                                save_done: {
                                    text: 'Save & Done',
                                    btnClass: 'btn-green save_done_template_btn',
                                    action: function () {
                                        var $btn = $(".save_done_template_btn");
                                        save_template(dialog, $btn, this, 'SAVE_DONE', cat_master_id, click_object);
                                        return false;
                                    }
                                },
                                cancel: function () {
                                    //close
                                },
                            },
                            onContentReady: function () {

                            }
                        });
                    }

                }else{
                    flash("Select Tag","Please select at least one tag", "warning",'center');
                }
            });






            $(document).off("click",".delete_tag");
            $(document).on("click",".delete_tag",function (e) {
                var current_lit = $(this).closest('.tag_box');
                var tag_id = $(this).attr('data-ti');
                var step_id = $(this).attr('data-si');
                $(this).confirmation({
                    rootSelector: '[data-toggle=confirmation]',
                    title:'Delete this tag?',
                    popout:true,
                    singleton:true,
                    container: 'body',
                    onConfirm:function(){
                        $(this).confirmation('show');
                        var btn  = $("[data-apply='confirmation']");
                        $.ajax({
                            type: 'POST',
                            url: "<?php echo Router::url('/prescription/manage_tag',true); ?>",
                            data: {ti:(tag_id),si:step_id,at:'DELETE'},
                            beforeSend: function () {
                                btn.button({loadingText: 'Deleting...'}).button('loading');
                            },
                            success: function (data) {
                                btn.button('reset');
                                data = JSON.parse(data);
                                if(data.status==1){
                                    $(current_lit).remove();
                                    $(this).confirmation('hide');
                                }else{
                                    $.alert(data.message);
                                }
                            },
                            error: function (data) {
                                btn.button('reset');
                                $.alert("Sorry something went wrong on server.");

                            }
                        });
                    },
                    onCancel:function () {
                        $(this).confirmation('hide');
                    }
                });
                $(this).confirmation('show');
            });





            $(document).off('click','.edit_tag');
            $(document).on('click','.edit_tag',function(e){


                var title = $(this).closest('.main_step_box').attr('data-st');

                var tag_id = $(this).attr('data-ti');
                var composition = $(this).closest('.tag_box').find('.tag_button_class .company_lbl').clone();
                var step_id = $(this).attr('data-si');
                var tag_name = $(this).attr('data-tn');
                var tag_name_object = $(this);



                var replace_text = $(this).closest('.tag_box').find(".tag_button_class");
                var string = "<input type='text' id='edit_tag_box' value='"+tag_name+"' class='form-control'>";

                var dialog = $.confirm({
                    title: 'Edit '+title,
                    content: '' +
                    '<div class="form-group">' +
                    '<label>Enter '+title+'</label>' +
                    string +
                    '</div>',
                    buttons: {
                        save: {
                            text: 'Save',
                            btnClass: 'btn-blue edit_tag_btn',
                            action: function () {
                                var btn = $(".edit_tag_btn");
                                var tag_name = this.$content.find('#edit_tag_box').val();
                                $.ajax({
                                    type: 'POST',
                                    url: "<?php echo Router::url('/prescription/manage_tag',true); ?>",
                                    data: {tn:tag_name,si:step_id,ti:tag_id,'at':'UPDATE'},
                                    beforeSend: function () {
                                        btn.button({loadingText: 'Saving...'}).button('loading');
                                    },
                                    success: function (data) {
                                        btn.button('reset');
                                        data = JSON.parse(data);
                                        if(data.status==1){
                                            dialog.close();
                                            var span = "<span style='display: block;font-size: 7px;'>"+$(composition).text()+"</span>";
                                            $(tag_name_object).attr('data-tn',tag_name);
                                            $(replace_text).html(tag_name).attr('data-tn',tag_name+span);
                                            $(replace_text).append(composition);

                                        }else{
                                            $.alert(data.message);
                                        }
                                    },
                                    error: function (data) {
                                        btn.button('reset');
                                        $.alert("Sorry something went wrong on server.");
                                    }
                                });
                                return false;
                            }
                        },
                        cancel: function () {
                            //close
                        },
                    },
                    onContentReady: function () {

                    }
                });

            });





        });
    </script>


</div>
