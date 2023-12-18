<?php
$channelId = '';
$channelName = '';
$channelDescription = '';
$channelImg = '';
$userId = '';
$is_subscribe = 0;
if (!empty($channelData)) {
    $channelId = $channelData[0]['id'];
    $channelName = $channelData[0]['channel_name'];
    $channelDesc = $channelData[0]['channel_desc'];
    $channelImg = $channelData[0]['image'];
    $userId = $channelData[0]['user_id'];
    $is_subscribe = $channelData[0]['is_subscribe'];
}
?>
<style>
    #replyajaxuploader { display:none;}
    #replyajaxuploader img {height: 20px;
                            width: 20px;
                            margin-left: 150px;
                            margin-top: 10px;
                            position: relative;}
    #videoajaxloader{ display:none;}
    #videoajaxloader img {height: 20px;
                          width: 20px;
                          margin-left: 150px;
                          margin-top: 10px;
                          position: relative;}
    #imageajaxloader{display:none;}
    #imageajaxloader img{
        height: 20px;
        width: 20px;
        margin-left: 150px;
        margin-top: 10px;
        position: relative;}
    #ajaxloader{display:none;}
    #ajaxloader img{height: 20px;
                    width: 20px;
                    margin-left: 150px;
                    margin-top: 10px;
                    position: relative;}
    </style>

    <div class="middle-section">
    <div class="container">

        <!--/LEFT-BOX-->
        <div class="left-box">

            <!--/heading-->
            <div class="top-heading">
                <div class="heading1"><h1>Channels</h1>
                <a href="javascript:;" title="Add new channel"><button  type="button" class="Add-btn pull-right" id="add_channel"><i class="fa fa-plus-circle"></i></button></a>
</div>
                            <div class="heading2"><h1> <a href="#" id="index_page"> Pages </a></h1>
                            <a href="javascript:;" title="Add new page" style="color:#fff !important;"><button  type="button" class="Add-btn pull-right" id="add_page"><i class="fa fa-plus-circle"></i></button></a>

</div>
            </div>

            <div class="left-bar" style="cursor:pointer;">
                <?php if (isset($channelData) && !empty($channelData)) { ?>
                    <?php foreach ($channelData as $channel) { ?>
                        <!--/Channel-Box-->
                        <div class="Channel-Box active" onclick="channelInfo('<?php echo $channel['id'] ?>', '<?php echo $channel['is_subscribe'] ?>')">

                            <div class="Channel-left-box pull-left">
                                <?php if (!empty($channel['image'])) { ?>
                                    <p><img src="<?php echo $channel['image']; ?>"/></p>
                                <?php } else { ?>
                                    <p>D</p>
                                <?php } ?>

                                <?php
                                if ($channel['is_subscribe'] == 0) {
                                    echo $this->Html->image('admin.png', array('alt' => 'MEngage', 'class' => 'admin', 'align' => 'absmiddle'));
                                }
                                ?>
                            </div>

                            <div class="Channel-right-box pull-right">
                                <?php $data = htmlspecialchars($channel['channel_name']); ?>
                                <h2><?php echo $data; ?></h2>
                                <?php
                                $text = substr($channel['channel_desc'], 0, 25);
                                $suffix = '...';
                                if (strlen($text) == 25) {
                                    ?>
                                    <p><?php echo $text . $suffix; ?></p>
                                <?php } else { ?>
                                    <p><?php echo $text; ?></p>
                                <?php } ?>
                                    <?php if($channel['is_public'] =='Y'){ echo 'Public'; }

                                     else if($channel['is_public'] =='P'){ echo 'Protected'; }
                                      else {
                                      echo 'Private';
                                      }?>
                            </div>

                        </div>
                        <!--/Channel-Box-END-->
                    <?php } ?>
                <?php } ?>
            </div>
           <br/>
            <?php
           if($PageShow == 1)
           {
           ?>

           <?php
           }
           ?>
        </div><!--/LEFT-BOX-END-->
        <?php echo $this->Session->flash("front_error"); ?>
        <?php echo $this->Session->flash("front_success"); ?>

        <!--/RIGHT-BOX-->
        <div class="right-box">
        <div class="top-box"><!--/TOP-BOX-->

            <div class="rigth-top-box">
                <script>
                    function viewMessageSummary(msgid, channelid)
                    {
                        var loading_htm = '<div class="load_content"><?php echo $this->Html->image('loader.gif', array('width' => '20px', 'height' => '20px')); ?><div>';
                        $(".right-box").html(loading_htm);

                        $.ajax({
                            'url': '<?php echo SITE_URL . "channels/viewmessagesummary"; ?>',
                            'data': {'channelid': channelid, 'msgid': msgid},
                            'type': 'post',
                            'dataType': 'html',
                            success: function(response) {
                                //alert(response);
                                //$(".right-box").html(response);
                                //if(response=="Channel deleted successfully")
                                //{
                                $(".right-box").html(response);
                                //}
                            }
                        });
                    }

                    function addsubscribe(channelId)
                    {
                        var loading_htm = '<div class="load_content"><?php echo $this->Html->image('loader.gif'); ?><div>';
                        $(".right-box").html(loading_htm);
                        $.ajax({
                            'url': '<?php echo SITE_URL . "channels/addsubscribe"; ?>',
                            'data': {'channelId': channelId},
                            'type': 'post',
                            'dataType': 'html',
                            success: function(response) {
                                //alert(response);
                                //$(".right-box").html(response);
                                //if(response=="Channel deleted successfully")
                                //{
                                $(".right-box").html(response);
                                //}
                            }
                        });
                    }

                    function unapprovedmessage(channelId)
                    {
                        var loading_htm = '<div class="load_content"><?php echo $this->Html->image('loader.gif'); ?><div>';
                        $(".right-box").html(loading_htm);
                        $.ajax({
                            'url': '<?php echo SITE_URL . "channels/unapprovedmessage"; ?>',
                            'data': {'channelId': channelId},
                            'type': 'post',
                            'dataType': 'html',
                            success: function(response) {
                                //alert(response);
                                //$(".right-box").html(response);
                                //if(response=="Channel deleted successfully")
                                //{
                                $(".right-box").html(response);
                                //}
                            }
                        });
                    }

                    function viewHistory(channelId)
                    {
                        var loading_htm = '<div class="load_content"><?php echo $this->Html->image('loader.gif'); ?><div>';
                        $(".right-box").html(loading_htm);
                        $.ajax({
                            'url': '<?php echo SITE_URL . "channels/viewchannelhistory"; ?>',
                            'data': {'channelId': channelId},
                            'type': 'post',
                            'dataType': 'html',
                            success: function(response) {
                                //alert(response);
                                //$(".right-box").html(response);
                                //if(response=="Channel deleted successfully")
                                //{
                                $(".right-box").html(response);
                                //}
                            }
                        });
                    }

                    function editchannel(channelId)
                    {
                        $.ajax({
                            'url': '<?php echo SITE_URL . "channels/editchannel"; ?>',
                            'data': {'channelId': channelId},
                            'type': 'post',
                            'dataType': 'html',
                            success: function(response) {
                                //alert(response);
                                //$(".right-box").html(response);
                                //if(response=="Channel deleted successfully")
                                //{
                                $(".right-box").html(response);
                                //}
                            }
                        });
                    }

                    function deletechannel(channelId)
                    {
                        var r = confirm("Are you sure?");
                        if (r == true) {
                            $.ajax({
                                'url': '<?php echo SITE_URL . "channels/channel_delete"; ?>',
                                'data': {'channel_id': channelId},
                                'type': 'post',
                                'dataType': 'html',
                                success: function(response) {
                                    //alert(response);
                                    //$(".right-box").html(response);
                                    //if(response=="Channel deleted successfully")
                                    //{
                                    location.reload(true);
                                    //}
                                }
                            });
                        } else {
                            //x = "You pressed Cancel!";
                        }


                    }
                </script>

                <div class="rigth-top-box1">
                    <input type="hidden" value="<?php echo $channelId; ?>" name="channel_id" id="channel_id" />
                    <?php $data = htmlspecialchars(@$channelName); ?>
                    <h2><?php echo $data; ?></h2>
                    <p><?php echo @$channelDesc; ?></p>
                </div>
                <?php if ($is_subscribe == 0) { ?>
                    <div class="edit-Subscribers">
                        <p>
                            <i class="fa fa-pencil" style="color:#fff;font-size: 11px;"></i><a href="javascript:;" onClick="editchannel('<?php echo $channelId; ?>')">Edit</a> &nbsp;
                            <i class="fa fa-trash" style="color:#fff;font-size: 11px;"></i><a href="javascript:;" onClick="deletechannel('<?php echo $channelId; ?>')">Delete</a> &nbsp;
                            <i class="fa fa-users" style="color:#fff;font-size: 11px;"></i><a href="javascript:;" onclick="addsubscribe('<?php echo $channelData[0]['id']; ?>')">Subscribers</a>
                            <i class="fa fa-users" style="color:#fff;font-size: 11px;"></i><a href="javascript:;" onclick="unapprovedmessage('<?php echo $channelData[0]['id']; ?>')">Unapproved Messages</a>
                        </p>
                    </div>
                <?php } ?>
            </div>

        </div><!--/TOP-BOX-END-->

        <?php if ($is_subscribe == 0) { ?>

            <div class="tab-block"><!--/TAB-BLOCK-->
                <?php echo $this->Html->image('arrow3.png', array('alt' => 'MEngage', 'style' => 'float:right; height:62px;')); ?>
                <?php echo $this->Html->image('arrow4.png', array('alt' => 'MEngage', 'style' => 'float:left; height:62px;')); ?>

                <ul class="nav nav-tabs">
                    <li class="active"><a data-toggle="tab" id="img1" href="#sectionA">Text</a></li>
                    <li><a data-toggle="tab" id="img7" href="#sectionB">Image</a></li>
                    <li><a data-toggle="tab" id="img8" href="#sectionC">Video</a></li>
                    <li><a data-toggle="tab" id="img4" href="#sectionD">Reply</a></li>
                    <!--<li><a data-toggle="tab" id="img5" href="#sectionE">Yes/No</a></li>-->
                </ul>

                <div class="tab-content"><!--/TAB-CONTENT-->

                    <div id="sectionA" class="tab-pane fade active in">
                        <div class="isa_successmsg" style="display: none;"></div>
                        <div class="isa_warningmsg" style="display: none;"></div>
                        <div class="isa_errormsg" style="display: none;"></div>
                        <textarea name="message" id="message" cols="10" class="cnt" placeholder="Enter Your Message" rows="3" style="resize:none;"></textarea>

                        <div class="button-box">

                            <div class="button-box2">
                                <div class="SEnd-btn5 pull-right">
                                    <label class="margin0">
                                        <?php echo $this->Form->checkbox('is_default', array('class' => 'margin4', 'id' => 'is_default')); ?>Make Default</label>
                                </div>
                            </div>

                            <div class="SEND-BTN">
                                <span id="ajaxloader"><?php echo $this->Html->image('ajaxloader.gif', array('alt' => 'MEngage')); ?></span>
                                <button type="button" class="SEnd-btn3 pull-right" id="send_message">Send</button>
                            </div>

                        </div>
                    </div>

                    <div id="sectionB" class="tab-pane fade">
                        <?php echo $this->Form->create('Message', array('url' => array('controller' => 'messages', 'action' => 'post_image'), 'id' => 'upload_img_form', 'enctype' => 'multipart-form/data')) ?>
                        <div class="isa_info" style="display: none;">Uploading...<?php echo $this->Html->image('9.gif'); ?></div>
                        <div class="isa_success" style="display: none;"></div>
                        <div class="isa_warning" style="display: none;"></div>
                        <div class="isa_error" style="display: none;"></div>

                        <div class="form-groups">
                            <div class="form-group" style="margin-top:10px; margin-bottom:5px !important; float:left; width:100%;">
                                <label class="control-label1 col-sm-2" for="email">Attach Image :</label>

                                <div class="col-sm-8" style="padding:0px;">

                                    <span style="display:none;"><?php echo $this->Form->file('image', array('id' => 'img_upload')); ?></span>
                                    <?php echo $this->Form->hidden('channel_id', array('value' => $channelId)); ?>

                                    <input class="form-control imghight" id="uploadfiles" placeholder="" type="text" style="width:110%;">
                                </div>

                                <div
                                    class="col-sm-2" style="padding:0px; text-align:right;"><?php echo $this->Html->image('browse-icon.png', array('alt' => 'MEngage', 'id' => 'upload')); ?>
                                </div>
                            </div>

                            <div class="form-group" style="margin-bottom: 5px;">
                                <label class="control-label1 col-sm-2" for="email">Title :</label>
                                <div class="col-sm-10" style="padding:0px;">
                                    <?php echo $this->Form->input('title', array('type' => 'text', 'label' => false, 'placeholder' => '', 'id' => 'imagetitle', 'class' => 'form-control imghight')); ?>
                                </div>
                            </div>

                        </div>

                        <div class="button-box">
                            <div class="button-box2">
                                <div class="SEnd-btn5 pull-right">
                                    <label class="margin0">
                                        <?php echo $this->Form->checkbox('is_default', array('class' => 'margin4')); ?>Make Default
                                    </label>
                                </div>
                            </div>

                            <div class="SEND-BTN">
                                <span id="imageajaxloader"><?php echo $this->Html->image('ajaxloader.gif', array('alt' => 'MEngage')); ?></span>
                                <button type="button" class="SEnd-btn3 pull-right" id="imageupload">Send</button>

                            </div>

                        </div>
                        <?php echo $this->Form->end(); ?>
                    </div>


                    <div id="sectionC" class="tab-pane fade">

                        <div class="isa_successvideo" style="display: none;"></div>
                        <div class="isa_warningvideo" style="display: none;"></div>
                        <div class="isa_errorvideo" style="display: none;"></div>

                        <div class="form-groups">
                            <div class="form-group" style="margin-top:10px; margin-bottom:5px !important; float:left; width:100%;">
                                <label class="control-label1 col-sm-2" for="email">Attach Video :</label>
                                <div class="col-sm-8" style="padding:0px;">
                                    <?php echo $this->Form->create('Message', array('url' => array('controller' => 'messages', 'action' => 'post_image'), 'id' => 'upload_video', 'enctype' => 'multipart-form/data')) ?>
                                    <span style="display:none;"><?php echo $this->Form->file('image', array('id' => 'video_upload')); ?></span>
                                    <?php echo $this->Form->hidden('channel_id', array('value' => $channelId)); ?>

                                    <input class="form-control imghight" id="videouploadid" placeholder="" type="text" style="width:110%;">
                                </div>

                                <div
                                    class="col-sm-2" style="padding:0px; text-align:right;"><?php echo $this->Html->image('browse-icon.png', array('alt' => 'MEngage', 'id' => 'videoupload')); ?>
                                </div>

                            </div>

                            <div class="form-group" style="margin-bottom: 5px;">
                                <label class="control-label1 col-sm-2" for="email">Title :</label>
                                <div class="col-sm-10" style="padding:0px;">
                                    <?php echo $this->Form->input('title', array('type' => 'text', 'label' => false, 'placeholder' => '', 'id' => 'videotitle', 'class' => 'form-control imghight')); ?>
                                </div>

                            </div>
                        </div>

                        <div class="button-box">
                            <div class="button-box2">
                                <div class="SEnd-btn5 pull-right">
                                    <label class="margin0">
                                        <?php echo $this->Form->checkbox('is_default', array('class' => 'margin4')); ?>Make Default</label>
                                </div>
                            </div>

                            <div class="SEND-BTN">
                                <span id="videoajaxloader"><?php echo $this->Html->image('ajaxloader.gif', array('alt' => 'MEngage')); ?></span>
                                <button type="button" class="SEnd-btn3 pull-right" id="vupload">Send</button>
                            </div>
                            <?php echo $this->Form->end(); ?>
                        </div>

                    </div>


                    <div id="sectionD" class="tab-pane fade">

                        <div class="isa_successreply" style="display: none;"></div>
                        <div class="isa_warningreply" style="display: none;"></div>
                        <div class="isa_errorreply" style="display: none;"></div>

                        <div class="form-groups">
                            <div class="form-group" style="margin-bottom: 5px !important; margin-top:10px;">
                                <label class="control-label1 col-sm-2" for="email">Question :</label>
                                <div class="col-sm-10" style="padding:0px;">
                                    <?php echo $this->Form->create('Message', array('url' => array('controller' => 'messages', 'action' => 'post_reply'), 'id' => 'r_upload', 'enctype' => 'multipart-form/data')) ?>
                                    <?php echo $this->Form->input('title', array('type' => 'text', 'label' => false, 'placeholder' => '', 'id' => 'replytitle', 'class' => 'form-control imghight')); ?>
                                </div>
                            </div>

                            <div class="form-group" style="margin-top:0px; margin-bottom:5px; float:left; width:100%;">
                                <label class="control-label1 col-sm-2" for="email">Add <br>Media :</label>
                                <div class="col-sm-8" style="padding:0px;">
                                    <span style="display:none;"><?php echo $this->Form->file('image', array('id' => 'reply')); ?></span>
                                    <?php echo $this->Form->hidden('channel_id', array('value' => $channelId)); ?>
                                    <input class="form-control imghight" id="replyimg" placeholder="" type="text" style="width:110%;">
                                </div>
                                <div
                                    class="col-sm-2" style="padding:0px; text-align:right;"><?php echo $this->Html->image('browse-icon.png', array('alt' => 'MEngage', 'id' => 'replyupload')); ?>
                                </div>
                            </div>
                        </div>

                        <div class="button-box">
                            <div class="button-box1">
                                <div class="checkbox left0">
                                    <?php
                                    $options = array('1' => 'Single', '0' => 'Multiple');
                                    $attributes = array('legend' => false);
                                    echo $this->Form->radio('is_single', $options, $attributes, array('class' => 'radiopadd'));
                                    ?>
                                </div>

                                <div class="SEnd-btn55 pull-right">
                                    <label class="margin044">
                                        <?php echo $this->Form->checkbox('is_default', array('class' => 'margin4')); ?>Make Default</label>
                                </div>
                            </div>

                            <div class="SEND-BTN">
                                <span id="replyajaxuploader"><?php echo $this->Html->image('ajaxloader.gif', array('alt' => 'MEngage')); ?></span>
                                <button type="button" class="SEnd-btn3 pull-right" id="replysubmit">Send</button>
                            </div>
                            <?php echo $this->Form->end(); ?>
                        </div>

                    </div>


                </div><!--/TAB-CONTENT-END-->

            </div><!--/TAB-BLOCK-END-->
        <?php } ?>

        <div class="msg-box2">

            <div class="all-msg2">
                <p>View history<span class="total_msg"><?php echo count($messges); ?></span></p>
            </div>
            <form id="DeleteMessageForm">
                <?php
                if(count($messges) > 0)
                {
                    ?>
                    <span id="SelectAllBtn">
                    <input type="checkbox" id="SelectAll"> Select All

                    <a id="DelteAll" >Delete Selected</a>
                        </span>
                <?php
                }
                ?>
                <div class="msd-discretion">
                    <?php
                    if (count($messges) > 0) {
                        $i = 1000;
                        foreach ($messges as $msg) {
                            ?>
                            <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                                <div class="panel panel-default" id="Div<?php echo $msg['Message']['id']; ?>">

                                    <input type="checkbox" value="<?php echo $msg['Message']['id'];?>" >

                                    <!--/ACCORDION-->
                                    <div class="panel-heading collapsed" style="background:#F3FDEB ;" role="tab" id="headingone" data-toggle="collapse" data-parent="#accordion<?php echo $i; ?>" href="#collapseone<?php echo $i; ?>" aria-expanded="false" aria-controls="collapseone">
                                        <h4 class="panel-title">
                                            <a style=" color:#000 !important;">
                                                <?php
                                                if ($msg['Message']['action_type'] == 1) {
                                                    echo '<i class="fa fa-envelope-o" style="color:#6ad316;"></i>&nbsp;';
                                                    echo "<label class=".$msg['Message']['id'].">".ucfirst($msg['Message']['message'])."</label>";
                                                } else {
                                                    echo $this->Html->image('image4.png', array('alt' => 'MEngage'));
                                                    echo "Reply";
                                                };
                                                ?>
                                                <span><?php echo date('d-M-Y h:i A', strtotime($msg['Message']['created'])); ?></span>
                                            </a>
                                        </h4>
                                    </div>
                                    <!--/CONTENT-->
                                    <?php if ($msg['Message']['action_type'] == 1) { ?>
                                        <div aria-expanded="false" style="height: 0px;" id="collapseone<?php echo $i; ?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingone">
                                            <div class="panel-body">
                                                <div class="SLP_categoryBoxText" style="width: 60%; float:left;">
                                                    <p style="font-size:14px; color:#6d6d6d; margin:0px;">
                                                        <?php if ($msg['Message']['type'] == 'image') { ?>
                                                            <img src="<?php echo $msg['Message']['image']; ?>" height="50px" width="50px"/>
                                                            <br/><br/>
                                                        <?php } ?>
                                                        <?php if ($msg['Message']['type'] == 'video') { ?>
                                                            <video width="300" height="200" controls>
                                                                <source src="<?php echo $msg['Message']['image']; ?>" type="video/mp4">
                                                            </video>
                                                            <br/><br/>
                                                        <?php } ?>
                                                        <?php
                                                        $text = $msg['Message']['message'];
                                                        $reg_exUrl = "/((((http|https|ftp|ftps)\:\/\/)|www\.)[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,4}(\/\S*)?)/";
                                                        $R_msg = preg_replace($reg_exUrl, "<a href='$1' target='_blank'>$1</a>", $text);
                                                        ?>

                                                        <textarea style="width:100%" id="<?php echo $msg['Message']['id']; ?>"><?php echo $R_msg; ?></textarea>
                                                        <a href="#" onclick="updatemessagetext('<?php echo $msg['Message']['id']; ?>')" style="margin-bottom:0px; text-align:center" class="SEnd-btn3 pull-right">Update Message
                                                        </a>
                                                        <a href="#" onclick="deletemeaasge('<?php echo $msg['Message']['id']; ?>')" style="margin-bottom:0px; text-align:center" class="SEnd-btn3 pull-right">Delete Message
                                                        </a>
                                                    </p>

                                                </div>
                                                <?php if ($userId != $msg['Message']['user_id']) { ?>

                                                <?php } else { ?>

                                                    <div class="button-box">
                                                        <div class="button-box2">
                                                            <div class="SEnd-btn5 pull-right">
                                                                <label class="margin04">
                                                                    <?php
                                                                    if ($msg['Message']['is_default'] == 'Y') {
                                                                        echo $this->Form->checkbox('is_default', array('checked' => 'checked', 'class' => 'margin4', 'id' => 'is_default'));
                                                                    } else {
                                                                        echo $this->Form->checkbox('is_default', array('class' => 'margin4', 'id' => 'is_default'));
                                                                    }
                                                                    ?>
                                                                    Make Default
                                                                </label>
                                                            </div>
                                                            <a class="update-link" href="javascript:;" onclick="updatemessage('<?php echo $msg['Message']['id']; ?>')">Update</a>

                                                        </div>
                                                        <div class="SEND-BTN">
                                                            <a href="javascript:;" onclick="viewMessageSummary('<?php echo $msg['Message']['id']; ?>', '<?php echo $msg['Message']['channel_id']; ?>')"><button type="button" class="SEnd-btn3 pull-right" style="margin-bottom:0px;">View Report</button></a>
                                                        </div>

                                                    </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    <?php } else { ?>

                                        <div aria-expanded="false" style="height: 0px;" id="collapseone<?php echo $i; ?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingone">
                                            <div class="panel-body">
                                                <div class="SLP_categoryBoxText" style="width:60%; float:left;">
                                                    <p style="font-size:14px; color:#6d6d6d; padding-left: 0px; margin:0px"><strong>Q.</strong>
                                                        <?php
                                                        $text = $msg['Message']['message'];
                                                        $reg_exUrl = "/((((http|https|ftp|ftps)\:\/\/)|www\.)[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,4}(\/\S*)?)/";
                                                        echo preg_replace($reg_exUrl, "<a href='$1' target='_blank'>$1</a>", $text);
                                                        //echo ucfirst($msg['messages']['message']);
                                                        ?>
                                                    </p>
                                                    <?php if ($msg['Message']['type'] == 'image') { ?>
                                                        <img src="<?php echo $msg['Message']['image']; ?>" height="50px" width="50px"/>
                                                        <br/><br/>
                                                    <?php } ?>
                                                    <?php if ($msg['Message']['type'] == 'video') { ?>
                                                        <video width="300" height="200" controls>
                                                            <source src="<?php echo $msg['Message']['image']; ?>" type="video/mp4">
                                                        </video>
                                                        <br/><br/>
                                                    <?php } ?>
                                                    <!--<div class="msg-boxes">
                                                      <textarea name="" cols="10" class="cnt" style="padding-left:0px; padding-top:5px;resize:none;width:100%; " placeholder="" rows="5"></textarea>
                                                    </div>-->
                                                </div>
                                                <?php if ($userId != $msg['Message']['user_id']) { ?>
                                                    <div class="button-box">
                                                        <div class="SEND-BTN">
                                                            <a href="javascript:;" onclick="sendreplyresponse('<?php echo $msg['Message']['id']; ?>', '<?php echo $msg['Message']['channel_id']; ?>')"><button type="button" class="SEnd-btn3 pull-right"> Response</button></a>
                                                        </div>

                                                    </div>

                                                <?php } else { ?>
                                                    <div class="button-box">
                                                        <div class="button-box2">
                                                            <div class="SEnd-btn5 pull-right">
                                                                <label class="margin04">
                                                                    <?php
                                                                    if ($msg['Message']['is_default'] == 'Y') {
                                                                        echo $this->Form->checkbox('is_default', array('checked' => 'checked', 'class' => 'margin4', 'id' => 'is_default'));
                                                                    } else {
                                                                        echo $this->Form->checkbox('is_default', array('class' => 'margin4', 'id' => 'is_default'));
                                                                    }
                                                                    ?>
                                                                    Make Default
                                                                </label>
                                                            </div>
                                                            <div class="SEnd-btn55 pull-right">
                                                                <label class="margin0">
                                                                    <?php
                                                                    if ($msg['Message']['is_stop_accepting_msg'] == 'Y') {
                                                                        echo $this->Form->checkbox('is_stop_accepting_msg', array('checked' => 'checked', 'class' => 'margin4', 'id' => 'is_stop_accepting_msg'));
                                                                    } else {
                                                                        echo $this->Form->checkbox('is_stop_accepting_msg', array('class' => 'margin4', 'id' => 'is_stop_accepting_msg'));
                                                                    }
                                                                    ?>
                                                                    Stop Accepting Messages
                                                                </label>
                                                            </div>

                                                            <a class="update-link" href="javascript:;" onclick="updatemessage('<?php echo $msg['Message']['id']; ?>')">Update</a>
                                                        </div>
                                                        <div class="SEND-BTN">
                                                            <a href="javascript:;" onclick="viewMessageSummary('<?php echo $msg['Message']['id']; ?>', '<?php echo $msg['Message']['channel_id']; ?>')"><button type="button" class="SEnd-btn3 pull-right" style="margin-bottom:0px;">View Report</button></a>
                                                            <a href="javascript:;" onclick="viewreplysummary('<?php echo $msg['Message']['id']; ?>', '<?php echo $msg['Message']['channel_id']; ?>');"><button type="button" class="SEnd-btn3 pull-right">View Response</button></a>
                                                        </div>

                                                    </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                            <?php
                            $i++;
                        }
                    }
                    ?>
            </form>
        </div>
        </div>
    </div><!--/RIGHT-BOX-END-->

    </div>
</div>

<script>
    $("#upload").on('click', function() {

        $('#img_upload').trigger('click');
        $("#uploadfiles").val($('#img_upload').val());
        return false;
    });
    $("#img_upload").change(function() {
        $("#uploadfiles").val($('#img_upload').val());
    });
    $("#imageupload").on('click', function() {

        var imgVal = $('#img_upload').val();
        var imagetitle = $("#imagetitle").val();
        $("#uploadfiles").val(imgVal);

        if ($("#uploadfiles").val() == "") {
            alert("Please upload image.");
            return false;
        }
        if (imagetitle == "") {
            alert("Please choose image title.");
            return false;
        }
        $("#imageajaxloader").show();
        //$('.isa_info').slideDown();
        $('#upload_img').attr('disabled', 'disabled');
        $('#upload_img_form').ajaxForm({
            success: function(resp) {
                $("#imageajaxloader").hide();
                //console.log(resp);
                $("#uploadfiles").val('');
                $("#imagetitle").val('');
                if (resp != '') {

                    if (resp == "NO_CREDIT") {
                        $('#upload_img').removeAttr('disabled');
                        $('.isa_info').hide();
                        $('.isa_error').html("You do not have sufficient credits to send messages.");
                        $('.isa_error').show();
                        setTimeout(function() {
                            $('.isa_error').slideUp();
                        }, 1500);
                        return false;
                    }

                    var haveMsg = $('#haveMsg').val();
                    $('#upload_img').removeAttr('disabled');
                    $('.isa_info').hide();
                    $('.isa_hide').show();
                    $('.isa_success').html('Message sent successfully.');
                    $('.isa_success').show();
                    $('#upload_img').val('');
                    $('#MessageTitle').val('');
                    $('#img_upload').val('');
                    setTimeout(function() {
                        $('.isa_success').slideUp();
                    }, 2000);
                } else {
                    $('#upload_img').removeAttr('disabled');
                    $('.isa_info').hide();
                    $('.isa_hide').show();
                    $('.isa_error').html('Invalid file format!');
                    $('.isa_error').show();
                    setTimeout(function() {
                        $('.isa_error').slideUp();
                    }, 2000);
                }
            }
        }).submit();


    });


    $("#videoupload").on('click', function() {

        $('#video_upload').trigger('click');
        $("#videouploadid").val($('#video_upload').val());
        $("#uploadfiles").val($('#video_upload').val());
        return false;
    });
    $("#video_upload").change(function() {
        $("#videouploadid").val($('#video_upload').val());
    });
    $("#vupload").on('click', function() {
        var imgVal = $('#video_upload').val();
        var msgtitle = $("#videotitle").val();

        $("#videouploadid").val(imgVal);
        if ($("#videouploadid").val() == "") {
            alert("Please upload video.");
            return false;
        }
        if (msgtitle == "") {
            alert("Please enter video title");
            return false;
        }
        $('#upload_img').attr('disabled', 'disabled');
        $("#videoajaxloader").show();
        $("#upload_video").ajaxForm({
            success: function(resp) {
                $("#videoajaxloader").hide();
                $("#videouploadid").val('');
                $("#videotitle").val('');
                if (resp != '') {

                    if (resp == "NO_CREDIT") {
                        $('#upload_img').removeAttr('disabled');
                        $('.isa_info').hide();
                        $('.isa_errorvideo').html("You do not have sufficient credits to send messages.");
                        $('.isa_errorvideo').show();
                        setTimeout(function() {
                            $('.isa_errorvideo').slideUp();
                        }, 1500);
                        return false;
                    }

                    var haveMsg = $('#haveMsg').val();
                    $('#upload_img').removeAttr('disabled');
                    $('.isa_info').hide();
                    $('.isa_hide').show();
                    $('.isa_successvideo').html('Message sent successfully.');
                    $('.isa_successvideo').show();
                    $('#upload_img').val('');
                    $('#MessageTitle').val('');
                    $('#img_upload').val('');
                    setTimeout(function() {
                        $('.isa_successvideo').slideUp();
                    }, 2000);
                } else {
                    $('#upload_img').removeAttr('disabled');
                    $('.isa_info').hide();
                    $('.isa_hide').show();
                    $('.isa_errorvideo').html('Invalid file format!');
                    $('.isa_errorvideo').show();
                    setTimeout(function() {
                        $('.isa_errorvideo').slideUp();
                    }, 2000);
                }

            }
        }).submit();


    });


    $("#replyupload").on('click', function() {

        $('#reply').trigger('click');
        $("#replyimg").val($('#reply').val());
        $("#uploadfiles").val($('#reply').val());

        return false;
    });
    $("#reply").change(function() {
        $("#replyimg").val($('#reply').val());
    });

    $("#replysubmit").on('click', function() {

        var imgVal = $('#reply').val();
        var msgtitle = $("#replytitle").val();


        //$("#replyimg").val(imgVal);
        if (msgtitle == "") {
            alert("Please enter reply title");
            return false;
        }

        $('#upload_rply').attr('disabled', 'disabled');
        $("#replyajaxuploader").show();
        $("#r_upload").ajaxForm({
            success: function(resp) {

                $("#replyajaxuploader").hide();
                $("#replyimg").val('');
                $("#replytitle").val('');
                //alert(resp);return false;
                if (resp != '') {
                    $("#replytitle").val('');
                    $("#replyimg").val('');
                    if (resp == "NO_CREDIT") {
                        $('#upload_img').removeAttr('disabled');
                        $('.isa_info').hide();
                        $('.isa_errorreply').html("You do not have sufficient credits to send messages.");
                        $('.isa_errorreply').show();
                        setTimeout(function() {
                            $('.isa_errorreply').slideUp();
                        }, 1500);
                        return false;
                    }

                    var haveMsg = $('#haveMsg').val();
                    $('#upload_rply').removeAttr('disabled');
                    $('.isa_info').hide();
                    $('.isa_hide').show();
                    $('.isa_successreply').html('Message sent successfully.');
                    $('.isa_successreply').show();
                    $('#upload_rply').val('');
                    $('#MessageTitle').val('');
                    $('#rply_upload').val('');
                    setTimeout(function() {
                        $('.isa_successreply').slideUp();
                    }, 2000);
                } else {
                    $('#upload_rply').removeAttr('disabled');
                    $('.isa_info').hide();
                    $('.isa_hide').show();
                    $('.isa_errorreply').html('Invalid file format!');
                    $('.isa_errorreply').show();
                    setTimeout(function() {
                        $('.isa_errorreply').slideUp();
                    }, 2000);
                }

            }
        }).submit();


    });


    $("#send_message").on('click', function() {
        var message = $("#message").val();

        var channel_id = $('#channel_id').val();
        var is_default = $("#is_default").val();

        if (message !== '') {
            $("#ajaxloader").show();
            $.ajax({
                'url': "<?php echo SITE_URL; ?>messages/post",
                'data': {"channel_id": '<?php echo $channel_id; ?>', "message": message, "type": 'text', "is_default": is_default},
                'type': 'post',
                'dataType': 'html',
                success: function(response) {

                    $("#ajaxloader").hide();
                    $("#message").val("");
                    var haveMsg = $('#haveMsg').val();
                    if (response == "NO_CREDIT") {

                        $('.isa_errormsg').html("You do not have sufficient credits to send messages.");
                        $('.isa_errormsg').show();
                        setTimeout(function() {
                            $('.isa_errormsg').slideUp();
                        }, 2000);
                        return;
                    }
                    $('#MessageTitle').val('');

                    $('.isa_error').hide();
                    $('.isa_successmsg').html('Message sent successfully.');
                    $('.isa_successmsg').slideDown();
                    setTimeout(function() {
                        $('.isa_successmsg').slideUp();
                    }, 2000);

                }
            });
        }
        else {
            alert('Enter Message First.');
            $('#message').focus();
            return false;
        }
    });


    $("#add_channel").on('click', function() {
        //alert("hello");return false;
        var loading_htm = '<div class="load_content"><?php echo $this->Html->image('loader.gif'); ?><div>';
        $(".right-box").html(loading_htm);

        $.ajax({
            'url': "<?php echo SITE_URL . "channels/add"; ?>",
            'data': {},
            'type': 'post',
            'dataType': 'html',
            success: function(response) {
                $(".right-box").html(response);
            }

        });
    });


    $("#index_page").on('click', function() {
        //alert("hello");return false;
        var loading_htm = '<div class="load_content"><?php echo $this->Html->image('loader.gif'); ?><div>';
        $(".right-box").html(loading_htm);

        $.ajax({
            'url': "<?php echo SITE_URL . "CmsPages/index"; ?>",
            'data': {},
            'type': 'post',
            'dataType': 'html',
            success: function(response) {
                $(".right-box").html(response);
            }

        });
    });

    $("#add_page").on('click', function() {
        //alert("hello");return false;
        var loading_htm = '<div class="load_content"><?php echo $this->Html->image('loader.gif'); ?><div>';
        $(".right-box").html(loading_htm);

        $.ajax({
            'url': "<?php echo SITE_URL . "CmsPages/add"; ?>",
            'data': {},
            'type': 'post',
            'dataType': 'html',
            success: function(response) {
                $(".right-box").html(response);

                 CKEDITOR.replace("data[CmsPage][description]",{toolbar : 'Advanced', uiColor : '#9AB8F3'});
            }

        });
    });


    function channelInfo(channelId, is_subscribe) {

        var loading_htm = '<div class="load_content"><?php echo $this->Html->image('loader.gif'); ?><div>';
        $(".right-box").html(loading_htm);
        $.ajax({
			'url': '<?php echo SITE_URL . "channels/channelinfo"; ?>',
            'data': {'channelId': channelId, 'is_subscribe': is_subscribe},
            'type': 'post',
            'dataType': 'html',
            success: function(response) {
				//alert(response);
                $(".right-box").html(response);
            }
        });
    }

    function viewreplysummary(msgid, channelid) {
        var loading_htm = '<div class="load_content"><?php echo $this->Html->image('loader.gif'); ?><div>';
        $(".right-box").html(loading_htm);
        $.ajax({
            'url': '<?php echo SITE_URL . "channels/viewreplysummary"; ?>',
            'data': {'msgid': msgid, 'channelid': channelid},
            'type': 'post',
            'dataType': 'html',
            success: function(response) {
                //alert(response);
                $(".right-box").html(response);
            }
        });
    }

    function sendreplyresponse(msgid, channelid) {
        var loading_htm = '<div class="load_content"><?php echo $this->Html->image('loader.gif'); ?><div>';
        $(".right-box").html(loading_htm);
        $.ajax({
            'url': '<?php echo SITE_URL . "channels/sendreplyresponse"; ?>',
            'data': {'msgid': msgid, 'channelid': channelid},
            'type': 'post',
            'dataType': 'html',
            success: function(response) {
                $(".right-box").html(response);
            }
        });
    }

    function updatemessage(msgid) {

        var is_default = $("#is_default").val();
        var is_stop_accepting_msg = $("#is_stop_accepting_msg").val();

        if (is_default) {

            $.ajax({
                'url': '<?php echo SITE_URL . "messages/update_message"; ?>',
                'data': {'message_id': msgid, 'is_default': is_default, 'is_stop_accepting_msg': is_stop_accepting_msg},
                'type': 'post',
                'dataType': 'html',
                success: function(response) {
                    location.reload(true);
                }

            });
        }
        else {
            alert("Please Select Checkbox");
            $('#is_default').focus();
            return false;
        }
    }

    function updatemessagetext(msgid) {

        var msg = $("#"+msgid).val();


        if (msg) {

            $.ajax({
                'url': '<?php echo SITE_URL . "messages/update_message_text"; ?>',
                'data': {'message_id': msgid, 'msg': msg},
                'type': 'post',
                'dataType': 'json',
                success: function(response) {

                   $('.'+msgid).html(response.msg);
                   // location.reload(true);
                }

            });
        }
        else {
            alert("Please Select Checkbox");
            $('#is_default').focus();
            return false;
        }
    }

     function deletemeaasge(msgid) {

        var count = $('.total_msg').html();
        var count = count-1;

        if (confirm("Are your sure to delete this message")) {

            $.ajax({
                'url': '<?php echo SITE_URL . "messages/delete_message"; ?>',
                'data': {'message_id': msgid},
                'type': 'post',
                'dataType': 'html',
                success: function(response) {

                    $("#Div"+msgid).hide('slow');
                    $('.total_msg').html(count);
                }

            });
        }

    }

    $(document).ready(function (){

            $('#SelectAll').change(function() {


            var checkboxes = $(this).closest('form').find(':checkbox');
            if($(this).is(':checked')) {
                 checkboxes.prop('checked', true);
            } else {
                 checkboxes.prop('checked', false);
            }
            });

            $("#DelteAll").click(function(){

                var allVals = [];
                $('#DeleteMessageForm :checked').each(function() {
                allVals.push($(this).val());
                });

                var removeItem = 1;
                allVals = jQuery.grep(allVals, function(value) {
                return value != removeItem;
                });

                var removeItem = 'on';
                allVals = jQuery.grep(allVals, function(value) {
                return value != removeItem;
                });

               if(allVals.length == 0)
               {
                alert("Plaese check atleast 1 checkbox.");
               }
               else
               {
                        $.ajax({
                         'url': '<?php echo SITE_URL . "messages/delete_all_message"; ?>',
                         'data': {'AllIds': allVals},
                         'type': 'post',
                         'dataType': 'html',
                         success: function(response) {
                          totalLength =allVals.length;
                          var count = $('.total_msg').html();
                          count = count-totalLength;
                          $('.total_msg').html(count);

                            var RemoveString ='';
                            $.each( allVals, function( key, value ) {
                             RemoveString += "#Div"+value+",";
                            });
                            var RemoveString = RemoveString.substring(1, RemoveString.length-1);
                            //alert(RemoveString);
                            $("#"+RemoveString).hide('slow');
                            if(count == 0)
                            {
                                $('#SelectAllBtn').hide('slow');
                            }
                         }

                     });
               }
            });

    });

</script>
<script src="http://malsup.github.com/jquery.form.js"></script>

<?php
echo $this->Html->script(array('bootstrap.min', 'jquery'));
