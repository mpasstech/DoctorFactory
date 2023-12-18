<!DOCTYPE html>
<html>
<head id ="row_content" class="row_content" >
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="theme-color" content="#7f0b00">


    <meta http-equiv="cache-control" content="no-store"/>
    <meta http-equiv="expires" content="0"/>
    <meta http-equiv="pragma" content="no-cache"/>


    <meta name="author" content="mengage">

    <?php

    $meta[] = @$doctor['city_name'];
    $meta[] = @$doctor['country_name'];
    $meta[] = @$doctor['state_name'];
    $meta[] = @$doctor['doctor_category'];
    $meta[] = @$doctor['name'];
    $meta[] = @$content['title'];
    $keys = implode(',',$meta);

    echo '<meta name="keywords" content="'.$keys.'">';

    ?>

    <script>

        var baseUrl = '<?php echo Router::url('/',true); ?>';
    </script>


    <?php echo $this->Html->script(array('jquery-3.5.1.min.js','popper.min.js','bootstrap4.min.js','html5gallery.js')); ?>
    <?php echo $this->Html->css(array( 'bootstrap4.min.css','font-awesome.min.css'),array("media"=>'all')); ?>



    <title>Doctor</title>
    <style>

        div[class^="html5gallery-elem-"]{
            box-shadow: none !important;
        }
        .blog-desc h4{
            text-align: center;
            font-size: 30px;
            padding: 0px;
            margin: 0px;
            color: #565656;
            border-bottom: 1px solid #b3adad;
            margin: 15px 0px;

        }
        .blog-desc{
            padding: 0px 20px;

        }
        .blog-desc-message{
            padding-top: 22px;
            margin-bottom: 1rem;
            border-top: 1px solid #eae8e8;

        }
        .left_box_img img{
            width: 70px;
            height: 70px;
            border-radius: 60px;
            border: 1px solid #d7dff1;
            padding: 3px;
            background: #ffffff;
        }
        .left_box_img{
            float: left;
            width: 20%;
        }

        .left_box_detail_box{
            width: 78%;
            float: left;
        }
        .blog-desc-message{
            text-align: justify;
        }

        .header_box{
            float: left;
            width: 100%;
            padding: 4px 10px 0px 10px;
            border-bottom: 1px solid #f0f0f0;
        }


        .left_box_detail_box h3{
            width: 100%;
            text-align: right;
            font-size: 30px;
            color: #4b944a;
            float: left;
            padding: 0px;
            margin: 0px;
        }
        .cat, .location{
            text-align: right;
        }
        .action_icon img{
            width: 40px;
            height: 40px;

        }
        .action_div{
            width: 100%;
            float: left;
            margin-bottom: 30px;
        }
        .action_div small{
            float: left;
            width: 100%;
            font-size: 1rem;
            color: #6d6666;
        }
        .action_div .action_icon{
            float: left;
            width: 80%;
            padding: 0px;
            margin: 0px;
            border: 0px solid;

        }
        .action_icon li{
            float: right;
            list-style: none !important;
            width: 13%;
            text-align: center;
        }

        .action_icon li img{
            float: left;
        }


        .action_icon li span{
            width: 50%;
            display: block;
            text-align: left;
            float: right;
            padding: 7px 0px;
            font-size: 25px;
            color: #6c7077;
        }

        .body_content{
            margin-right: 0px !important;
            margin-left: 0px !important;
        }
        .action_icon_row{
            padding: 15px 0px;
            font-size: 1rem;
            display: flex;
            color: #3897b9
        }

        .date_lbl{
            font-size: 0.9rem;
            font-weight: 600;
        }
        .col-12, .row{
           margin-right: 0px !important;
            margin-left: 0px !important;
        }

    </style>
</head>
<body>

<div class="content">

    <div class="row">
        <div class="col-12">
            <header class="header_box">
                <div class="left_box_img">  <img  src="<?php echo !empty($doctor['profile_photo'])?$doctor['profile_photo']:$doctor['logo']; ?>" /></div>
                <div class="left_box_detail_box">
                    <h3><?php echo $doctor['name']; ?></h3>

                    <?php
                    if(!empty($doctor['doctor_category'])){
                        $tmp[] = $doctor['doctor_category'];
                    }

                    if(!empty($doctor['state_name'])){
                        $tmp[] = $doctor['state_name'];
                    }
                    if(!empty($doctor['city_name'])){
                        $tmp[] = $doctor['city_name'];
                    }
                    $label = "";
                    if(!empty($tmp)){
                        $label = implode(', ',$tmp);
                    }

                    ?>
                    <p class="cat"><?php echo $label; ?></p>
                </div>
            </header>
        </div>
    </div>

  <div class="row">
        <div class="col-12">

            <div class="gallary_box media_box" >
                <?php if($content['message_type'] == 'IMAGE' || $content['message_type'] == 'VIDEO'){ ?>

                    <?php if($content['multiple_image'] == 'YES'){ ?>
                        <div style="display:none;" class="html5gallery" data-responsive="true" data-width="1000" data-height="560" data-thumbwidth="140" data-thumbheight="85" data-showimagetoolbox="always"  data-skin="light" style="display:none;" >
                            <?php foreach($content['message_file_array'] as $img) { ?>
                                <!-- Add images to Gallery -->
                                <a href="<?php echo $img['path']; ?>"><img src="<?php echo $img['path']; ?>" alt="<?php echo $content['title']; ?>"></a>
                            <?php } ?>
                        </div>
                    <?php }else{ ?>

                        <div style="display:none;" class="html5gallery" data-responsive="true" data-width="1000" data-height="560" data-thumbwidth="140" data-thumbheight="85" data-showimagetoolbox="always"  data-skin="light" style="display:none;">
                            <a href="<?php echo $content['message_file_url']; ?>"><img src="<?php echo $content['message_file_url']; ?>" alt="<?php echo $content['title']; ?>"></a>
                        </div>
                    <?php } ?>



                <?php } ?>
            </div>
        </div>
    </div>


    <div class="body_content row">
        <div class="col-12">
            <h4 style="text-align: center; border-top: 1px solid #d7d0d0; <?php echo ($content['message_type'] == 'IMAGE' || $content['message_type'] == 'VIDEO')?'':'border:none;'; ?>" ><?php echo $content['title']; ?></h4>

            <small class="date_lbl"><?php echo $content['created'];  ?> </small>

        </div>

        <div class="col-12">
            <p class="blog-desc-message">
                <?php

                $msg_array = explode('Posted By -',$content['message']);
                $footer = $message = "";
                if(count($msg_array) > 1){
                    $footer = 'Posted By - '.end($msg_array);
                }
                array_pop($msg_array);
                $message = implode(' ',$msg_array);
                echo  $message ;
                echo "<br><br><lable class='post_by_lbl'>$footer</lable>";
                ?> </p>
        </div>
        <div class="col-12">
            <div class="row action_icon_row">
                <div class="col-4">
                    <span><?php echo $content['total_likes']; ?> <i class="fa fa-thumbs-up"></i></span>
                </div>

                <div class="col-4" style="text-align: center;">
                    <span><?php echo $content['total_views']; ?> <i class="fa fa-eye"></i></span>
                </div>

                <div class="col-4" style="text-align: right;">
                    <span><?php echo $content['total_share']; ?> <i class="fa fa-share"></i></span>
                </div>

            </div>

        </div>
    </div>

</div>

</body>


<script>




    $(function () {

        function is_desktop(){
            if(/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
                return false;
            }return true;
        }
        if(is_desktop()){
            $("body").addClass("desktop_layout");
        }

    });

</script>

</html>







