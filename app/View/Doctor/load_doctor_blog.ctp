


<?php

if(!empty($post_data)){

    foreach($post_data as $key => $val) {
        ?>
        <div class="blog_box">
            <div class="col-2 blog-img">

                <?php if($val['message_type'] == 'IMAGE' && !empty($val['message_file_url'])){ ?>
                    <img src="<?php echo $val['message_file_url']; ?>" />
                <?php }else{ ?>

                    <img src="<?php echo $doctor_data['logo']; ?>" />

                <?php } ?>

            </div>
            <div class="col-10 blog-desc desc_blog_box">
                <h4><?php echo $val['title']; ?></h4>
                <small><?php echo $val['created'];  ?> </small>
                <p class="blog-desc">
                    <?php

                    $msg_array = explode('Posted By -',$val['message']);
                    $footer = $message = "";
                    if(count($msg_array) > 1){
                        $footer = 'Posted By - '.end($msg_array);
                    }
                    array_pop($msg_array);
                    $message = implode(' ',$msg_array);
                    echo  mb_strimwidth($message, 0, 350, "...") ;
                    echo "<a target='_blank' class='read_more_btn' data-id='modal_".$val['message_id']."' href='".Router::url('/doctor/blog/',true).base64_encode($doctor_data['doctor_id']).'/'.base64_encode($doctor_data['thin_app_id']).'/'.base64_encode($val['channel_id']).'/'.base64_encode($val['id'])."'>&nbsp;Read More</a>";
                    echo "<br><lable class='post_by_lbl'>$footer</lable>";
                    ?> </p>
                <div class="row blog-btn-container">
                    <div  class="col-4">
                        <span><?php echo $val['total_likes']; ?> <i class="fa fa-thumbs-up"></i></span>
                    </div>
                    <div class="col-4">
                        <span><?php echo $val['total_views']; ?> <i class="fa fa-eye"></i></span>
                    </div>
                    <div class="col-4">
                        <span><?php echo $val['total_share']; ?> <i class="fa fa-share"></i></span>
                    </div>
                </div>
            </div>
        </div>

    <?php }}else{
        if($offset==0){
            $message = "Doctor will soon post blog here.";
        }else{ ?>

        <?php $message="Sorry, Doctor has no more blog post.";
        }
    ?>
    <h2 class="doc_not_available"><?php echo $message; ?></h2>


    <script>$(".load_more").hide();</script>


<?php  } ?>
<style>

    div[class^="html5gallery-elem-"]{
        box-shadow: none !important;
    }

    .blog-desc label{
        color: #686df7;
        font-weight: 100;
        font-style: normal;
        font-size: 13px;
    } .blog-desc p{
              margin: 0 0 4px;
    }
    .blog-img{
        text-align: center;
    }


</style>