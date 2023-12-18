<div class="Inner-banner">
    <div class="container">
        <div class="row">

            <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 inner-page-text"> <h2><?php echo ucfirst($quest_type); ?> List</h2> </div>
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
                    <?php echo $this->element('app_admin_leftsidebar'); ?>
                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">

                        <?php echo $this->element('app_admin_quest_tab'); ?>

                        <div class="Social-login-box payment_bx">
                            <?php echo $this->Form->create('Search',array('type'=>'get','url'=>array('controller'=>'app_admin','action' => 'search_quest'),'admin'=>true)); ?>
                            <div class="form-group">
                                <div class="col-sm-4">
                                    <?php echo $this->Form->input('title', array('type' => 'text','label' => 'Search by title', 'class' => 'form-control')); ?>
                                </div>

                                <div class="col-sm-4">
                                    <?php echo $this->Form->input('qt', array('type' => 'hidden','value'=>$this->request->query['qt'])); ?>
                                    <?php echo $this->Form->input('quest_category', array('type' => 'select', 'empty' => 'Please select', 'options'=>$questCategory, 'label' => 'Search by category', 'class' => 'form-control')); ?>
                                </div>

                                <div class="col-sm-2">
                                    <?php echo $this->Form->label('&nbsp;'); ?>
                                    <?php echo $this->Form->submit('Search',array('class'=>'Btn-typ3','id'=>'date')); ?>
                                </div>
                                <div class="col-sm-2">
                                    <?php echo $this->Form->label('&nbsp;'); ?>
                                    <div class="submit">
                                        <a href="<?php echo $this->Html->url(array('controller'=>'app_admin','action'=>'quest',"?"=>array('qt'=>$this->request->query['qt']))) ?>"><button type="button" class="Btn-typ3" >Reset</button></a>
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
                                                <th>Title</th>
                                                <th>Creaded By</th>
                                                <th>Category</th>
                                                <th>Image</th>
                                                <th>Status</th>
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
                                                    <td><?php echo $value['User']['mobile']; ?></td>
                                                    <td><?php echo $value['QuestCategory']['name']; ?></td>
                                                    <td>
                                                        <?php if(!empty($value['Quest']['image'])){ ?>
                                                        <img src="<?php echo $value['Quest']['image']; ?>" style="width:150px">
                                                        <?php }else{ ?>
                                                        No Image
                                                        <?php } ?>
                                                    </td>
                                                    <td>
                                                        <button type="button" id="changeQuestStatus" class="btn btn-primary btn-xs"  quest-id="<?php echo $value['Quest']['id']; ?>" ><?php echo $value['Quest']['status']; ?></button>
                                                    </td>

                                                    <td>
                                                        <div class="action_icon" style="display:flex;">
                                                            <button type="button" id="viewQuest" class="btn btn-primary"  quest-id="<?php echo $value['Quest']['id']; ?>" ><i class="fa fa-eye"></i></button>
                                                            &nbsp;
                                                            <button type="button" id="viewQuestResponse" class="btn btn-primary"  quest-id="<?php echo $value['Quest']['id']; ?>" ><i class="fa fa-cog"></i></button>

                                                            <?php if($value['Quest']['user_id']==$login['User']['id']){ ?>
                                                                &nbsp;
                                                                <a href="<?php echo $this->Html->url(array('controller'=>'app_admin','action'=>'edit_quest',base64_encode($value['Quest']['id']),"?"=>array('qt'=>base64_encode($quest_type)))) ?>" >
                                                                    <button type="button" id="editEvent" class="btn btn-primary" ><i class="fa fa-edit"></i></button>
                                                                </a>
                                                                &nbsp;
                                                            <button type="button"  class="btn btn-primary delete_quest"  quest-id="<?php echo base64_encode($value['Quest']['id']); ?>" ><i class="fa fa-trash"></i></button>
                                                            <?php } ?>

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
                                <h2>No <?php echo ucfirst($quest_type); ?> ..!</h2>
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
                url: baseurl+'/app_admin/change_quest_status',
                data:{questID:questID},
                type:'POST',
                beforeSend:function(){
                    $(thisButton).button('loading').html('<i class="fa fa-spinner fa-pulse">');
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
                url: baseurl+'/app_admin/view_quest',
                data:{questID:questID},
                type:'POST',
                beforeSend:function(){
                    $(thisButton).button('loading').html('<i class="fa fa-spinner fa-pulse">');
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
                url: baseurl+'/app_admin/view_quest_result',
                data:{questID:questID},
                type:'POST',
                beforeSend:function(){
                    $(thisButton).button('loading').html('<i class="fa fa-spinner fa-pulse">');
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





        $(document).on('click','.delete_quest',function(e){
            var quest_id = $(this).attr('quest-id');
            if(confirm("Are you sure you want to delete ?")){
                window.location.href = baseurl+"/app_admin/delete_quest/"+quest_id;
            }
        });


        $(document).on('click','#changeQuestReplyStatus',function(e){
            var questReplyID = $(this).attr('questreply-id');
            var thisButton = $(this);
            $.ajax({
                url: baseurl+'/app_admin/change_quest_reply_status',
                data:{questReplyID:questReplyID},
                type:'POST',
                beforeSend:function(){
                    $(thisButton).button('loading').html('<i class="fa fa-spinner fa-pulse">');
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
    });
</script>