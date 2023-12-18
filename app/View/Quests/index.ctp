<style>
    a{cursor:pointer}
	.headBar {
		background-color: #0288d1;
	}
	.heading{
	color: #FFF;
	}
	h6 a{ 
	color:#B4E1F7 
	}
	.mainContainer{
		margin-top: 15px;
	}
	.bodyForColor{
		background: rgba(0, 0, 0, 0) linear-gradient(white, #F7FF78) repeat scroll 0 0;
	}
	.btn{
		background-color: rgb(255, 202, 40) !important;
	color: rgb(255, 255, 255) !important;
	}
	
</style>
<div class="container">
    <div class="row headBar">
        <div class="col-md-12 text-center heading">
            <h2><?php echo $questData['Thinapp']['name']; ?> Quest</h2>
            <h6>Created by <a><?php echo ($questData['Quest']['post_as_anonymous'] == 'NO')?substr($questData['User']['mobile'], 0, 7) . str_repeat("*", strlen($questData['User']['mobile'])-7):'Anonymous'; ?></a> </h6>
            <br>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 col-lg-offset-2 col-md-8 col-md-offset-2 col-sm-8 col-sm-offset-2 mainContainer">

            <div class="twt-wrapper">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <?php echo $questData['Quest']['question']; ?>
                    </div>
                    <div class="panel-body bodyForColor">
                        <form method="post" id="replyForm">
                            <textarea rows="3" required id="message" name="message" placeholder="Enter here reply..." class="form-control"></textarea>
                            <br>
                            <a class="pull-left" id="questLike" >
                                <?php if($isLiked > 0){ ?>
                                    <i class="fa fa-thumbs-up" aria-hidden="true"></i>
                                <?php }else{ ?>
                                    <i class="fa fa-thumbs-o-up" aria-hidden="true"></i>
                                <?php } ?>

                                &nbsp;<?php echo $questData['Quest']['like_count']; ?> Likes
                            </a>
                            <button type="submit" id="replyBtn" class="btn btn-primary btn-sm pull-right" >Reply</button>
                        </form>
                        <div class="clearfix"></div>
                        <hr>
                        <ul class="media-list">
                            <?php
                            $n = 0;
                            foreach($questReply as $reply)
                            { ?>
                                <li class="media">

                                    <div class="media-body">
                                        <span class="text-muted pull-right">
                                            <small class="text-muted"><?php echo $this->Custom->timeElapsedString(strtotime($reply['created'])); ?></small>
                                        <br>
                                       <a id="replyThank" row-id="<?php echo $reply['id']; ?>" >
                                           <?php
                                           if($reply['is_thanked'] === true)
                                           { ?>
                                               <i class="fa fa-thumbs-up" aria-hidden="true"></i>
                                           <?php }
                                           else{ ?>
                                               <i class="fa fa-thumbs-o-up" aria-hidden="true"></i>
                                           <?php }
                                           ?>
                                           &nbsp;<?php echo $reply['thank_count'] ?> Thanks
                                       </a>
                                        </span>
                                        <strong class="text-success">@ <?php echo substr($reply['mobile'], 0, 7) . str_repeat("*", strlen($reply['mobile'])-7); ?></strong>
                                        <p>
                                            <?php echo $reply['message']; ?>
                                        </p>
                                    </div>
                                </li>
                            <?php $n++; } ?>


                        </ul>
                        <span class="text-danger"><?php echo $n; ?> replies</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function () {
            $(document).on('submit','#replyForm',function (e) {
                e.stopPropagation();
                e.preventDefault();
                var dataToPost = {};
                dataToPost.type = 'addReply';
                dataToPost.message = $('#message').val();
                dataToPost.subscriberID = <?php echo $subscriberID; ?>;
                dataToPost.questID = <?php echo $questID; ?>;
                var thisButton = $('#replyBtn');
                $.ajax({
                    url: baseurl+'quests',
                    data:dataToPost,
                    type:'POST',
                    beforeSend:function(){
                        $(thisButton).button('loading').html('<i class="fa fa-spinner fa-pulse fa-2x fa-fw">');
                    },
                    success: function(result){
                        $(thisButton).button('reset');
                        var result = JSON.parse(result);
                        if(result.status == 1)
                        {

                            location.reload();
                        }
                        else
                        {
                            $("#replyForm").trigger('reset');
                            alert(result.message);
                        }
                    }
                });
            });

            $(document).on('click','#replyThank',function (e) {
                e.preventDefault();
                var dataToPost = {};
                dataToPost.type = 'addThank';
                dataToPost.questReplyID = $(this).attr('row-id');
                dataToPost.subscriberID = <?php echo $subscriberID; ?>;
                dataToPost.questID = <?php echo $questID; ?>;
                var thisButton = $(this);
                $.ajax({
                    url: baseurl+'quests',
                    data:dataToPost,
                    type:'POST',
                    beforeSend:function(){
                        $(thisButton).button('loading').html('<i class="fa fa-spinner fa-pulse fa-2x fa-fw">');
                    },
                    success: function(result){

                        var result = JSON.parse(result);
                        if(result.status == 1)
                        {
                            $("#replyForm").trigger('reset');
                            location.reload();
                        }
                        else
                        {
                            $(thisButton).button('reset');
                            alert(result.message);
                        }
                    }
                });
            });

        $(document).on('click','#questLike',function (e) {
            e.preventDefault();
            var dataToPost = {};
            dataToPost.type = 'questLike';
            dataToPost.subscriberID = <?php echo $subscriberID; ?>;
            dataToPost.questID = <?php echo $questID; ?>;
            var thisButton = $(this);
            $.ajax({
                url: baseurl+'quests',
                data:dataToPost,
                type:'POST',
                beforeSend:function(){
                    $(thisButton).button('loading').html('<i class="fa fa-spinner fa-pulse fa-2x fa-fw">');
                },
                success: function(result){

                    var result = JSON.parse(result);
                    if(result.status == 1)
                    {
                        $("#replyForm").trigger('reset');
                        location.reload();
                    }
                    else
                    {
                        $(thisButton).button('reset');
                        alert(result.message);
                    }
                }
            });
        });
    });
</script>