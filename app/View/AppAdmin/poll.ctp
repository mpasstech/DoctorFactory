<?php
$login = $this->Session->read('Auth.User');


?>

<div class="Inner-banner">
    <div class="container">
        <div class="row">
            <!--  SLIDER IMAGE -->
            <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 inner-page-text"> <h2>Poll</h2> </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 pull-right create-btn-box">
            </div>
        </div>
    </div>
</div>

<div class="Home-section-2">

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="container">
            <div class="row">
                <!--box 1 -->
                <!--box 1 -->
                <div class="middle-block">
                    <!-- Heading -->
                    <!--left sidebar-->

                   <!-- --><?php /*echo $this->element('app_admin_leftsidebar'); */?>
                    <!--left sidebar-->

                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                        <div class="Social-login-box dashboard_box">
                            <?php echo $this->element('message'); ?>


                            <?php
                            $login = $this->Session->read('Auth.User');


                            $list = $this->Custom->getActionType();
                            ?>


                            <?php if(count($list) > 0){ ?>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                      <!--  <h3>Poll</h3>-->
                                        <ul class="dashboard_icon_li">
                                            <?php foreach ($list as $key => $qur){  ?>
                                                <li>

                                                    <a data-id="<?php echo $qur['ActionType']['id']; ?>" class="poll_link" href="javascript:void(0)">
                                                    <div class="content_div">
                                                        <div class="dash_img">
                                                            <img src="<?php echo Router::url('/thinapp_images/').$qur['ActionType']['icon']; ?>">
                                                        </div>

                                                        <span class="poll_name"><?php echo $qur['ActionType']['name'];?></span>
                                                    </div>
                                                    </a>
                                                </li>
                                            <?php } ?>

                                        </ul>

                                    </div>
                                </div>
                                <div class="clear"></div>
                            <?php } ?>


                        </div>



                    </div>






                </div>
                <!-- box 1 -->


            </div>
            <!--box 2 -->

        </div>
    </div>
</div>





<div class="modal fade" id="question_modal" role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <?php echo $this->Form->create('ActionQuestion',array('url'=>array('controller'=>'app_admin','action'=>'poll'),'class'=>'form','id'=>'question_form')); ?>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <img src="" class="question_logo" />
                <h4 class="modal-title">Poll for <span class="poll_h_name"></span></h4>
            </div>
            <div class="modal-body">
                <div class="form-group">

                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <label>Enter your question here</label>
                        <?php echo $this->Form->input('action_type_id', array('type'=>'hidden','label'=>false,'required'=>false,'placeholder'=>'Enter your question here','id'=>'type_box')); ?>
                        <?php echo $this->Form->input('question_text', array('type'=>'text','label'=>false,'required'=>false,'placeholder'=>'Enter your question here','class'=>'form-control','id'=>'question')); ?>
                        <div class="que_error"></div>
                    </div>

                </div>

                <div class="form-group">

                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <label class="man_label">Result as: </label>
                        <?php echo $this->Form->input('poll_publish', array('options'=>array('PRIVATE'=>'Private','PUBLIC'=>'Public'),'type'=>'select','label'=>false,'required'=>false,'class'=>'form-control')); ?>
                    </div>

                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <label class="man_label">Share On </label>
                        <?php echo $this->Form->input('share_on', array('options'=>array('CHANNEL'=>'Channel','POLLFACTORY'=>'Poll Factory'),'type'=>'select','label'=>false,'required'=>false,'class'=>'form-control')); ?>
                    </div>

                </div>


                <div class="form-group">
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <label class="man_label">Response till </label>
                        <?php echo $this->Form->input('poll_duration', array('options'=>$this->AppAdmin->getPollDurationOption(),'type'=>'select','label'=>false,'required'=>false,'class'=>'form-control')); ?>
                    </div>

                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <label>Channel for post poll</label>
                        <?php $type_array = $this->AppAdmin->getChannelList($login['User']['id'],$login['User']['thinapp_id']); ?>
                        <?php echo $this->Form->input('channel_id',array('type'=>'select','label'=>false,'options'=>$type_array,'class'=>'form-control cnt')); ?>
                    </div>



                </div>

                <div class="form-group">
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 mandatory">
                        <label class="man_label">Is this question is mandatory?</label>
                        <label class="radio-inline"><input type="radio" value="YES" name="data[ActionQuestion][is_mandatory]" checked='checked'>Yes</label>
                        <label class="radio-inline"><input type="radio" value="NO" name="data[ActionQuestion][is_mandatory]">No</label>
                    </div>

                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 mbroadcast">
                        <label class="man_label">Shared on mbroadcast?</label>
                        <label class="radio-inline"><input type="radio" value="YES" name="data[ActionQuestion][share_on_mbroadcast]" >Yes</label>
                        <label class="radio-inline"><input type="radio" value="NO" name="data[ActionQuestion][share_on_mbroadcast]" checked='checked'>No</label>
                    </div>

                </div>



                <div class="form-group add_option_div">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <label>Enter your option and press enter to add</label>
                        <?php echo $this->Form->input('option', array('type'=>'text','label'=>false,'class'=>'form-control','id'=>'option')); ?>
                        <div class="opt_error"></div>
                    </div>
                </div>

                <div class="clear"></div>


            </div>
            <div class="modal-footer">
                <?php echo $this->Form->submit('Create Question',array('id'=>'sub_btn','type'=>'button','class'=>'Btn-typ3')); ?>
            </div>
            <?php echo $this->Form->end(); ?>
        </div>
    </div>

</div>





<script>
    $(document).ready(function(){



        $('#option').tagsinput({
            maxTags: 10,
            maxChars: 15,
            trimValue: true
        });


        $(document).on('click','.poll_link',function(e){
            var name = $(this).closest("li").find(".poll_name").html();
            var path = $(this).find("img").attr('src');
            var type = $(this).attr('data-id');
            $("#type_box").val(type);

            $(".poll_h_name").html(name);
            $(".question_logo").attr('src',path);
            if(type == 3 || type == 1 || type == 6 || type == 7 || type == 8 ){
                $(".add_option_div").show();
            }else{
                $(".add_option_div").hide();
            }

            $(".opt_error, .que_error").html('');


            $("#question, #option").val('');
            $("#question_modal").modal('show');
        })

        $(document).on('click','#sub_btn',function(e){


            var type = parseInt($("#type_box").val());
            if($("#question").val()==""){

                $(".que_error").html("Please enter your question.");

            }else{

                $(".que_error").html("");

                if(type == 1 || type == 6 || type == 7 || type == 8 ){
                    if($("#option").val()==""){
                        $(".opt_error").html("Please enter option for this question.");

                    }else{
                        var option_len  = $("#option").val().split(",");
                        option_len = cleanArray(option_len);
                        if(option_len.length > 1){

                            $(".que_error, .opt_error").html("");
                            $(".bootstrap-tagsinput").css('border-color','#ccc');
                            $("#question_form").submit();
                        }else{
                            $(".opt_error").html("Please enter minimum 2 option for this question.");

                        }
                    }
                }else{
                    $("#question_form").submit();
                }

            }


        })


        function cleanArray(actual) {
            var newArray = new Array();
            for (var i = 0; i < actual.length; i++) {
                if (actual[i].trim()) {
                    newArray.push(actual[i]);
                }
            }
            return newArray;
        }



    });
</script>









