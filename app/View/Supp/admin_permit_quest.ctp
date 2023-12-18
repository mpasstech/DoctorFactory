<div class="Inner-banner">
    <div class="container">
        <div class="row">

            <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 inner-page-text"> <h2>Permit Quest</h2> </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 pull-right create-btn-box">
            </div>
        </div>
    </div>
</div>
<section class="Home-section-2">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="container">
            <div class="row">
                <div class="middle-block">
                    <?php echo $this->element('support_admin_leftsidebar'); ?>
                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">



                        <div class="progress-bar channel_tap">

                            <a id="v_app_channel_list" href="<?php echo Router::url('/admin/supp/quest'); ?>"><i class="fa fa-list"></i> Quest List</a>

                            <a id="v_add_channel" href="<?php echo Router::url('/admin/supp/add_quest'); ?>" ><i class="fa fa-television"></i> Add Quest</a>

                            <a id="v_add_channel" class='active' href="<?php echo Router::url('/admin/supp/permit_quest'); ?>" ><i class="fa fa-globe"></i> Permit Quest</a>

                        </div>
                        <style>
                            .channel_tap a{ width:33% !important; }
                        </style>




                        <div class="Social-login-box payment_bx">
                            <?php echo $this->Form->create('Search',array('type'=>'get','url'=>array('controller'=>'supp','action' => 'search_permit_quest'),'admin'=>true)); ?>
                            <div class="form-group">
                                <div class="col-sm-4">
                                    <?php echo $this->Form->input('quest_category', array('type' => 'select', 'empty' => 'Please select', 'options'=>$questCategory, 'label' => 'Search by category', 'class' => 'form-control')); ?>
                                </div>

                                <div class="col-sm-2">
                                    <?php echo $this->Form->label('&nbsp;'); ?>
                                    <?php echo $this->Form->submit('Search',array('class'=>'Btn-typ3','id'=>'date')); ?>
                                </div>
                                <div class="col-sm-2">
                                    <?php echo $this->Form->label('&nbsp;'); ?>
                                    <div class="submit">
                                        <a href="<?php echo $this->Html->url(array('controller'=>'supp','action'=>'permit_quest')) ?>"><button type="button" class="Btn-typ3" >Reset</button></a>
                                    </div>
                                </div>
                            </div>
                            <div class="clear"></div>



                            <?php echo $this->Form->end(); ?>
                            <?php echo $this->element('message'); ?>
                            <div class="form-group row">
                                <div class="col-sm-12">
                                    <div class="table-responsive">
                                        <?php if(!empty($quest)){ ?>
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Question</th>
                                                <th>Category</th>
                                                <th>Image</th>
                                                <th>Permit Status</th>
                                                <th>Action</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            $login = $this->Session->read('Auth.User');
                                            $num = 1;
                                            foreach ($quest as $key => $value){
                                                ?>
                                                <tr>
                                                    <td><?php echo $num++; ?></td>
                                                    <td><?php echo $value['Quest']['question']; ?></td>
                                                    <td><?php echo $value['QuestCategory']['name']; ?></td>
                                                    <td>
                                                        <?php if(!empty($value['Quest']['image'])){ ?>
                                                        <img src="<?php echo $value['Quest']['image']; ?>" style="width:150px">
                                                        <?php }else{ ?>
                                                        No Image
                                                        <?php } ?>
                                                    </td>
                                                    <td>
                                                        <button type="button" id="changeQuestPermission" class="btn btn-primary btn-xs"  quest-id="<?php echo $value['Quest']['id']; ?>" ><?php echo $value['Quest']['mbroadcast_publish_status']; ?></button>
                                                    </td>

                                                    <td>
                                                        <div class="action_icon" style="display:flex;">
                                                            <button type="button" id="viewQuest" class="btn btn-primary btn-xs"  quest-id="<?php echo $value['Quest']['id']; ?>" ><i class="fa fa-eye fa-2x"></i></button>
                                                            &nbsp;
                                                            <button type="button" id="viewQuestResponse" class="btn btn-primary btn-xs"  quest-id="<?php echo $value['Quest']['id']; ?>" ><i class="fa fa-cog fa-2x"></i></button>
                                                            &nbsp;
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                            </tbody>
                                        </table>
                                        <?php echo $this->element('paginator'); ?>
                                    </div>
                                </div>
                            </div>
                        <?php }else{ ?>
                            <div class="no_data">
                                <h2>No Quest..!</h2>
                            </div>
                        <?php } ?>
                            <div class="clear"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>



<div class="modal fade" id="myModalView" role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">View Quest</h4>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table" id="viewQuestTable">
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>


<div class="modal fade" id="myModalViewResponse" role="dialog">

    <div class="modal-dialog modal-sm">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Quest Response</h4>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table" id="viewQuestResponseTable">
                        <tr>
                            <th id="questionContainer" colspan="5"></th>
                        </tr>
                        <tr>
                            <td colspan="2" id="likeContainer"></td>
                            <td colspan="3" id="shareContainer"></td>
                        </tr>
                        <tr>
                            <th colspan="5" align="center">Replies</th>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
    $(document).ready(function(){
        $(document).on('click','#changeQuestStatus',function(e){
            var questID = $(this).attr('quest-id');
            var thisButton = $(this);
            $.ajax({
                url: baseurl+'/admin/supp/change_quest_status',
                data:{questID:questID},
                type:'POST',
                beforeSend:function(){
                    $(thisButton).button('loading').html('<i class="fa fa-spinner fa-pulse fa-2x fa-fw">');
                },
                success: function(result){
                    $(thisButton).button('reset');
                    var result = JSON.parse(result);
                    if(result.status == 1)
                    {
                        $(thisButton).text(result.text);
                    }
                    else
                    {
                        alert('Sorry, Could not change status!');
                    }
                }
            });
        });
        $(document).on('click','#viewQuest',function () {
            var questID = $(this).attr('quest-id');
            var thisButton = $(this);
            $.ajax({
                url: baseurl+'/admin/supp/view_quest',
                data:{questID:questID},
                type:'POST',
                beforeSend:function(){
                    $(thisButton).button('loading').html('<i class="fa fa-spinner fa-pulse fa-2x fa-fw">');
                },
                success: function(result){
                    $(thisButton).button('reset');
                    var result = JSON.parse(result);
                    if(result.status == 1)
                    {
                        $("#viewQuestTable").html(result.html);
                        $("#myModalView").modal('show');
                    }
                    else
                    {
                        alert('Sorry, Could not find event!');
                    }
                }
            });
        });
        $(document).on('click','#viewQuestResponse',function () {
            var questID = $(this).attr('quest-id');
            var thisButton = $(this);
            $.ajax({
                url: baseurl+'/admin/supp/view_quest_result',
                data:{questID:questID},
                type:'POST',
                beforeSend:function(){
                    $(thisButton).button('loading').html('<i class="fa fa-spinner fa-pulse fa-2x fa-fw">');
                },
                success: function(result){
                    $(thisButton).button('reset');
                    var result = JSON.parse(result);
                    if(result.status == 1)
                    {
                        var data = result.data;
                        $(".replyContainer").remove();
                        $("#questionContainer").text(data.Quest.question);
                        $("#likeContainer").text('Total Like: '+data.Quest.like_count);
                        $("#shareContainer").text('Total Share: '+data.Quest.share_count);
                        var replyHtml = '';
                        var count = 1;
                        data.QuestReply.forEach(function (reply) {
                            console.log(reply);
                            replyHtml += '<tr class="replyContainer"><td>'+count+'</td><td>'+reply.message+'</td><td>'+reply.User+'</td><td>Total Thanks: '+reply.thank_count+'</td><td><button type="button" id="changeQuestReplyStatus" class="btn btn-primary btn-xs"  questReply-id="'+reply.id+'" >'+reply.status+'</button></td></tr>';
                            count++;
                        });
                        $("#viewQuestResponseTable").append(replyHtml);
                        $("#myModalViewResponse").modal('show');
                    }
                    else
                    {
                        alert('Sorry, Could not find any response!');
                    }
                }
            });
        });




        $(document).on('click','#changeQuestPermission',function(e){
            var questID = $(this).attr('quest-id');
            var thisButton = $(this);
            $.ajax({
                url: baseurl+'/admin/supp/change_quest_mbroadcast_publish_status',
                data:{questID:questID},
                type:'POST',
                beforeSend:function(){
                    $(thisButton).button('loading').html('<i class="fa fa-spinner fa-pulse fa-2x fa-fw">');
                },
                success: function(result){
                    $(thisButton).button('reset');
                    var result = JSON.parse(result);
                    if(result.status == 1)
                    {
                        $(thisButton).text(result.text);
                    }
                    else
                    {
                        alert('Sorry, Could not change permission!');
                    }
                }
            });
        });




    });
</script>