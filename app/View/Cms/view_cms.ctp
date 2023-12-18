<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="generator" content="Mobirise v4.3.5, mobirise.com">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="shortcut icon" href="<?php echo LOCAL_PATH.'app/webroot/'; ?>/home/icon/favicon.png" type="image/x-icon">
    <meta name="description" content="MEngage">
    <title><?= $title; ?></title>
    <link rel="stylesheet" href="<?php echo Router::url('/cms_theme/')?>assets/web/assets/mobirise-icons/mobirise-icons.css">
    <link rel="stylesheet" href="<?php echo Router::url('/cms_theme/')?>assets/tether/tether.min.css">
    <link rel="stylesheet" href="<?php echo Router::url('/cms_theme/')?>assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo Router::url('/cms_theme/')?>assets/bootstrap/css/bootstrap-grid.min.css">
    <link rel="stylesheet" href="<?php echo Router::url('/cms_theme/')?>assets/bootstrap/css/bootstrap-reboot.min.css">
    <link rel="stylesheet" href="<?php echo Router::url('/cms_theme/')?>assets/theme/css/style.css">
    <link rel="stylesheet" href="<?php echo Router::url('/cms_theme/')?>assets/mobirise-gallery/style.css">
    <link rel="stylesheet" href="<?php echo Router::url('/cms_theme/')?>assets/mobirise/css/mbr-additional.css" type="text/css">

    <style>
        section{
            background: #fff;
        }
        .about_us_section{
            padding: 15px 0px;
        }
        .about_us_section .card-title{
            text-align: center;
        }
        .about_us_section .card-title span{
            color: #1ba005;
        }
        .about_us_section .description,
        .about_us_section p{
            padding-right: 0px !important;
            padding-left: 0px !important;
            text-align: justify;
        }

        ::-webkit-scrollbar {
            width: 2px !important;
        }

        /* Track */
        ::-webkit-scrollbar-track {
            background: #f1f1f1 !important;
        }

        /* Handle */
        ::-webkit-scrollbar-thumb {
            background: #888 !important;
        }

        /* Handle on hover */
        ::-webkit-scrollbar-thumb:hover {
            background: #555 !important;
        }

        .header11{
            background-size: cover !important;
            padding: 0.8rem 0px !important;
        }


        .cid-qv5AityZTn .nav-tabs .nav-link{
            margin: 0.1rem 0.2rem !important;
            font-size: 0.9rem;
        }
        .tab_menu_link {
            width: auto;
            padding: 4px 2px !important;
            background: none !important;
            border: none !important;
            font-size: 10px;


        }

        .cid-qv5AityZTn .tab-content{
            margin-top: 0.8rem;
        }

        .tab_menu_link.active{
            width: auto;
            padding: 4px 2px !important;
            background: none !important;
            border: none !important;
            border-bottom: 1px solid !important;
            border-radius: 0px !important;
            color: #1ba005 !important;
        }


        .tab_menu_li{
            float: left !important;
            width: auto;
        }
        .cid-qv5AityZTn{
            padding-top: 0px !important;
        }

        .expert_title{
            padding: 4px 0px;
            font-size:1.2rem;
            text-align: center;
        }

        .expert_sub_title{
            font-size: 0.8rem;
            font-weight: 500;
        }
        .cid-qv1kArXNGD{
            padding-top: 0px !important;
        }
        .mbr-gallery .container{
            padding-left: 10px !important;
            padding-right: 10px !important;
        }

        .mbr-gallery .container .raw{
            margin-left: 10px !important;
            margin-right: 10px !important;
        }

        .mbr-gallery-item--p1{
            padding: 0.2rem 0.2rem !important;
        }
        .cid-qv1kArXNGD .mbr-gallery-title{
            padding: 15px 5px !important;
        }
        .gallery_img_description, .gallery_img_description p{
            color: #fff;
            font-size: 0.8rem;
        }
        .gallery_img_sub_title{
            font-size: 0.7rem;

            color: #dddbdb !important;

            display: block;

            position: relative;

            margin-top: 0px;

            margin-left: 0;

            width: 100%;

            padding: 3px 5px;
        }

        .event_title{
            text-align: center;
            margin-bottom: 0px !important;

        }


        .specility_title{
            text-align: center;
            margin-bottom: 0px !important;
            color: #1ba005;
            text-decoration:underline;

        }

        .specility_sub_title{
            text-align: center;
            width: 100%;

            display: block;
            font-size: 0.8rem;
            padding: 0.2rem;
        }

        .event_sub_title{
            text-align: center;
            width: 100%;
            color: #1ba005;
            display: block;
            font-size: 0.8rem;
            padding: 0.2rem;
        }

        .event_container_card{
            width: 100%;
            padding: 0.2rem 0rem;
            border-bottom: 1px solid #ccc;
            margin-bottom: 3rem;
        }

        .read_more_link{
            position: absolute;
            bottom: -25px;
            width: 100%;
            text-align: center;
            right: 0px;
            font-size: 0.8rem;
            background: #fff;
            padding-left: 2px;
            display: none;
        }

        .description_box{
            overflow: hidden;
        }



        .testimonials_image{
            border-radius: 56%;

            width: 100px !important;

            height: 100px !important;
            margin: 0 auto;
        }
        .testimonials_title{
            text-align: center;
            font-size: 1.5rem;
            color: #1ba005;
        }

        .testimonials_description{
            font-family: "Comic Sans MS", cursive, sans-serif;
            padding: 0.8rem 0;
        }

        .cid-qv5Aq4h3k3{
            padding-top: 15px !important;
        }

        .contact_us_detail p{
            text-align: left !important;
            color: #737373;
            font-family: -apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif;
            font-size: 1.1rem;
            font-style: normal !important;

            padding-bottom: 0.2rem;
        }


        .contact_us_detail p a{
            text-decoration: underline;
            color: #737373;

        }


        .contact_us_detail{
            margin-bottom: 0.5rem !important;
            border-bottom: 1px solid #8888883b;
            width: 100%;
        }
        .cid-qv5Aq4h3k3 .mbr-iconfont {

            font-size: 30px;
            padding-right: 0.5rem;
            color: rgb(9, 33, 91);
            font-weight: 400;

        }

        .contact_us_detail .email_margin{
            margin-top: -6px;
        }
        .contact_us_detail .email_margin a{
            text-decoration: none;
        }

        .contact_box{
            margin-bottom: 1rem;
        }
        .career_title{
            margin-bottom: 0px;
            font-size: 1.3rem;
        }
        .our_expert_img{
            width: 60% !important;
            border-radius: 65%;
            margin: 0 auto;
        }
        .expert_sub_title{
            padding: 0.3rem 0;
            display: block;
        }
        .expert_main_box{
            margin-bottom: 3rem;
        }
        .expert_main_box .media-content{
            border-bottom: 1px solid #d9d7d7;
            padding-bottom: 1rem;
        }
    </style>

</head>
<body>



<?php
$final_array =array();
$read_more_box_height = "300px";
if(!empty($page_data)){


    $category_array = array_column($page_data,'category_name');
    $category_label = !array_filter($category_array)?"":'Other';
    foreach($page_data as $key => $value){
        $value['category_name'] = !empty($value['category_name'])?$value['category_name']:$category_label;
        $final_array['category_list'][$value['category_name']] = $value['category_name'];
        $final_array['expert_list'][$value['category_name']][] = array(
            'title'=>$value['title'],
            'sub_title'=>$value['sub_title'],
            'description'=>$value['description'],
            'media'=>$value['media'],
            'media_type'=>$value['media_type'],
            'field1'=>$value['field1'],
            'field2'=>$value['field2']
        );
    }
    foreach ($final_array as $key =>$value){
        ksort( $final_array[$key]);
    }

}


?>


<?php if($page_category_id==1 || $page_category_id==18){ ?>
    <section class="header11" style="background: url('<?php echo Router::url('/cms_theme/')?>/assets/images/about_us_bg.png');">
        <div class="container align-left">
            <div class="media-container-column mbr-white col-md-12">
                <h1 class="mbr-section-title py-3 mbr-fonts-style display-1"><?php echo $title; ?></h1>
            </div>
        </div>
    </section>
    <section class="about_us_section">
        <div class="container">
            <div class="media-container-row">
                <div class="card col-12 col-md-12 col-lg-12" style="padding-right: 0px; padding-left: 0px;">
                    <div class="card-wrapper">
                        <div class="card-box">
                            <h4 class="card-title pb-3 mbr-fonts-style display-8">

                                <?php

                                $temp = explode(" ",$page_data[0]['title']);
                                echo "<span>".$temp[0]."</span> ";
                                unset($temp[0]);
                                echo implode(' ',$temp);

                                ?>


                            </h4>
                            <p class="mbr-text mbr-fonts-style display-7 description">

                                <?php echo $page_data[0]['description']; ?>

                            </p>
                        </div>

                        <?php if(!empty($page_data[0]['media']) && $page_data[0]['media_type']=="IMAGE"){ ?>
                            <div class="card-img">
                                <img src="<?php echo $page_data[0]['media']?>" alt="About Us" title="" media-simple="true">
                            </div>
                        <?php } ?>


                    </div>
                </div>
            </div>
        </div>
    </section>
<?php }else if($page_category_id==7){ ?>
    <section class="header11" style="background: url('<?php echo Router::url('/cms_theme/')?>/assets/images/our_expertise_bg.png');">
        <div class="container align-left">
            <div class="media-container-column mbr-white col-md-12">
                <h1 class="mbr-section-title py-3 mbr-fonts-style display-1"><?php echo $title; ?></h1>
            </div>
        </div>
    </section>
    <section class="tabs3 cid-qv5AityZTn" style="background: #fff;" id="tabs3-2k" data-rv-view="10877">

        <?php if(!empty($final_array)){


            ?>
            <div class="container-fluid">
                <div class="row tabcont">
                    <ul class="nav nav-tabs" role="tablist">
                        <?php foreach ($final_array['category_list'] as $key => $category){ ?>
                            <li class="nav-item mbr-fonts-style tab_menu_li">
                                <a class="nav-link active display-7 tab_menu_link" role="tab" data-toggle="tab" href="#tabs3-2k_tab<?=strtoupper($key); ?>" aria-expanded="true">
                                    <?=$category; ?>
                                </a>
                            </li>
                        <?php } ?>

                    </ul>
                </div>
            </div>

            <div class="container">
                <div class="row">
                    <div class="tab-content" >
                        <?php foreach ($final_array['expert_list'] as $key => $expert){ ?>
                            <div id="<?=strtoupper($key); ?>" class="tab-pane in <?php echo ($key==0)?'active':''; ?> mbr-table" role="tabpanel">

                                <?php foreach ($expert as $exp_key => $exp_data){ ?>

                                    <div class="media-container-row expert_main_box">
                                        <div class="card col-12 col-md-12 col-lg-12">

                                                <?php if(!empty($exp_data['media'])){ ?>
                                                    <div class="mbr-figure" style="width: 60%;">
                                                        <img class="our_expert_img" src="<?php echo $exp_data['media']; ?>" alt="<?php echo $exp_data['title']; ?>" media-simple="true">
                                                    </div>
                                                <?php } ?>
                                                <div class="media-content">
                                                    <div class="mbr-section-text">
                                                        <h3 class="expert_title"><?php echo $exp_data['title']; ?>
                                                            <strong class="expert_sub_title">  <?php echo $exp_data['sub_title']; ?></strong>
                                                        </h3>

                                                        <div class="card-box description description_box expert_description" style="height: <?php echo $read_more_box_height; ?>">
                                                            <a href='javascript:void(0);' class='read_more_link'>Read More </a>
                                                            <?php echo $exp_data['description']; ?>
                                                        </div>


                                                    </div>
                                                </div>

                                        </div>
                                    </div>

                                <?php } ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        <?php } ?>



    </section>
<?php }else if($page_category_id==6){ ?>
    <section class="header11" style="background: url('<?php echo Router::url('/cms_theme/')?>/assets/images/gallery_bg.png');">
        <div class="container align-left">
            <div class="media-container-column mbr-white col-md-12">
                <h1 class="mbr-section-title py-3 mbr-fonts-style display-1"><?php echo $title; ?></h1>
            </div>
        </div>
    </section>
    <section class="tabs3 cid-qv5AityZTn" style="background: #fff;" id="tabs3-2k" data-rv-view="10877">

        <?php if(!empty($final_array)){



            ?>
            <div class="container-fluid">
                <div class="row tabcont">
                    <ul class="nav nav-tabs" role="tablist">
                        <?php $counter=0; foreach ($final_array['category_list'] as $key => $category){  ?>
                            <li class="nav-item mbr-fonts-style tab_menu_li">
                                <a data-tab-id="<?="TAB_".$counter; ?>" class="nav-link <?php ($counter==0)?'active':''; ?> display-7 tab_menu_link"  href="javascript:void(0);" aria-expanded="true">
                                    <?=$category; ?>
                                </a>
                            </li>
                            <?php $counter++; } ?>

                    </ul>
                </div>
            </div>

            <div class="container">
                <div class="row gallery_tab_panel">
                    <?php $counter=0;  foreach ($final_array['expert_list'] as $key => $expert){ ?>
                        <div id="<?="TAB_".$counter; ?>" style="display: <?php echo ($counter==0)?'block':'none'; ?>" class="mbr-table tab_content_box" >
                            <section class="mbr-gallery mbr-slider-carousel cid-qv1kArXNGD" id="gallery1-<?php echo $counter; ?>" data-rv-view="10172">
                                <div class="container">
                                    <div>
                                        <div class="mbr-gallery-row">
                                            <div class="mbr-gallery-layout-default">
                                                <div>
                                                    <div>

                                                        <?php foreach ($expert as $exp_key => $exp_data){ ?>

                                                            <div class="mbr-gallery-item mbr-gallery-item--p1"
                                                                 data-video-url="false" data-tags="Awesome">
                                                                <div href="#lb-gallery1-<?php echo $counter; ?>" data-slide-to="<?php echo $exp_key; ?>" data-toggle="modal">
                                                                    <?php if($exp_data['media_type']=='IMAGE'){ ?>
                                                                        <img style="width: 100%;" src="<?php echo $exp_data['media']; ?>" alt="<?php echo $exp_data['title']; ?>">
                                                                    <?php }else if($exp_data['media_type']=='VIDEO'){ ?>
                                                                        <video controls controlsList="nodownload" style="width: 100%;">
                                                                            <source src="<?php echo $exp_data['media']; ?>" type="video/<?php $tmp = explode(".",$exp_data['media']); echo end($tmp); ?>">
                                                                            Your browser does not support the video tag.
                                                                        </video>
                                                                    <?php } ?>

                                                                    <span class="icon-focus"></span>
                                                                    <span class="mbr-gallery-title mbr-fonts-style display-7"><?php echo $exp_data['title']; ?></span>
                                                                    <span class="gallery_img_sub_title"><?php echo $exp_data['sub_title']; ?></span>
                                                                </div>
                                                            </div>

                                                        <?php } ?>


                                                    </div>
                                                </div>
                                                <div class="clearfix"></div>
                                            </div>
                                        </div><!-- Lightbox -->
                                        <div data-app-prevent-settings=""
                                             class="mbr-slider modal fade carousel slide" tabindex="-1"
                                             data-keyboard="true" data-interval="false" id="lb-gallery1-<?php echo $counter; ?>">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-body">
                                                        <div class="carousel-inner">
                                                            <?php foreach ($expert as $exp_key => $exp_data){ ?>
                                                                <div class="carousel-item <?php echo ($exp_key==0)?'active':''; ?>">

                                                                    <?php if($exp_data['media_type']=='IMAGE'){ ?>
                                                                        <img  style="width: 100%;" src="<?php echo $exp_data['media']; ?>" alt="<?php echo $exp_data['title']; ?>">
                                                                    <?php }else if($exp_data['media_type']=='VIDEO'){ ?>
                                                                        <video controls controlsList="nodownload" style="width: 100%;">
                                                                            <source src="<?php echo $exp_data['media']; ?>" type="video/<?php $tmp = explode(".",$exp_data['media']); echo end($tmp); ?>">
                                                                            Your browser does not support the video tag.
                                                                        </video>
                                                                    <?php } ?>


                                                                    <div class="gallery_img_description"><?php echo $exp_data['description']; ?></div>
                                                                </div>
                                                            <?php } ?>
                                                        </div>
                                                        <a class="carousel-control carousel-control-prev"
                                                           role="button" data-slide="prev"
                                                           href="#lb-gallery1-<?php echo $counter; ?>"><span
                                                                    class="mbri-left mbr-iconfont"
                                                                    aria-hidden="true"></span><span class="sr-only">Previous</span></a>
                                                        <a
                                                                class="carousel-control carousel-control-next"
                                                                role="button" data-slide="next"
                                                                href="#lb-gallery1-<?php echo $counter; ?>"><span
                                                                    class="mbri-right mbr-iconfont"
                                                                    aria-hidden="true"></span><span class="sr-only">Next</span></a>
                                                        <a
                                                                class="close" href="#" role="button"
                                                                data-dismiss="modal"><span
                                                                    class="sr-only">Close</span></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </section>
                        </div>
                        <?php $counter++; } ?>
                </div>
            </div>
        <?php } ?>

    </section>
<?php }else if($page_category_id==9){ ?>
    <section class="header11" style="background: url('<?php echo Router::url('/cms_theme/')?>/assets/images/events_bg.png');">
        <div class="container align-left">
            <div class="media-container-column mbr-white col-md-12">
                <h1 class="mbr-section-title py-3 mbr-fonts-style display-1"><?php echo $title; ?></h1>
            </div>
        </div>
    </section>
    <section class="tabs3 cid-qv5AityZTn" style="background: #fff;" id="tabs3-2k" data-rv-view="10877">

        <?php if(!empty($final_array)){


            ?>
            <div class="container-fluid">
                <div class="row tabcont">
                    <ul class="nav nav-tabs" role="tablist">
                        <?php foreach ($final_array['category_list'] as $key => $category){ ?>
                            <li class="nav-item mbr-fonts-style tab_menu_li">
                                <a class="nav-link active display-7 tab_menu_link" role="tab" data-toggle="tab" href="#tabs3-2k_tab<?=strtoupper($key); ?>" aria-expanded="true">
                                    <?=$category; ?>
                                </a>
                            </li>
                        <?php } ?>

                    </ul>
                </div>
            </div>

            <div class="container">
                <div class="row">
                    <div class="tab-content" >
                        <?php foreach ($final_array['expert_list'] as $key => $expert){ ?>
                            <div id="<?=strtoupper($key); ?>" class="tab-pane in <?php echo ($key==0)?'active':''; ?> mbr-table" role="tabpanel">
                                <?php foreach ($expert as $exp_key => $exp_data){ ?>
                                    <div class="container">
                                        <div class="media-container-row event_container_card">
                                            <div class="card col-12 col-md-12 col-lg-12" style="padding-right: 0px; padding-left: 0px;">
                                                <div class="card-wrapper">
                                                    <h4 class="card-title  mbr-fonts-style display-8 event_title"><?php echo $exp_data['title']; ?></h4>
                                                    <span class="event_sub_title"><?php echo $exp_data['sub_title']; ?></span>

                                                    <?php if(!empty($exp_data['media'])){ ?>
                                                        <div class="card-img">
                                                            <?php if($exp_data['media_type']=="IMAGE"){ ?>
                                                                <img src="<?php echo $exp_data['media']?>" alt="About Us" title="" media-simple="true">
                                                            <?php }else if($exp_data['media_type']=="VIDEO"){ ?>
                                                                <video controls controlsList="nodownload" style="width: 100%;">
                                                                    <source src="<?php echo $exp_data['media']; ?>" type="video/<?php $tmp = explode(".",$exp_data['media']); echo end($tmp); ?>">
                                                                    Your browser does not support the video tag.
                                                                </video>
                                                            <?php } ?>

                                                        </div>

                                                    <?php } ?>
                                                    <div class="card-box description description_box" style="height: <?php echo $read_more_box_height; ?>">
                                                        <a href='javascript:void(0);' class='read_more_link'>Read More </a>
                                                        <?php echo $exp_data['description']; ?>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                <?php } ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        <?php } ?>



    </section>
<?php }else if($page_category_id==15){ ?>
    <section class="header11" style="background: url('<?php echo Router::url('/cms_theme/')?>/assets/images/specialities_bg.png');">
        <div class="container align-left">
            <div class="media-container-column mbr-white col-md-12">
                <h1 class="mbr-section-title py-3 mbr-fonts-style display-1"><?php echo $title; ?></h1>
            </div>
        </div>
    </section>
    <section class="tabs3 cid-qv5AityZTn" style="background: #fff;" id="tabs3-2k" data-rv-view="10877">

        <?php if(!empty($final_array)){
            ?>
            <div class="container">
                <?php foreach ($final_array['expert_list'] as $key => $expert){ ?>
                    <?php foreach ($expert as $exp_key => $exp_data){ ?>
                        <div class="media-container-row event_container_card">
                            <div class="card col-12 col-md-12 col-lg-12" style="padding-right: 0px; padding-left: 0px;">
                                <div class="card-wrapper">
                                    <h4 class="card-title  mbr-fonts-style display-8 specility_title"><?php echo $exp_data['title']; ?></h4>
                                    <span class="specility_sub_title"><?php echo $exp_data['sub_title']; ?></span>

                                    <?php if(!empty($exp_data['media'])){ ?>
                                        <div class="card-img">
                                            <?php if($exp_data['media_type']=="IMAGE"){ ?>
                                                <img src="<?php echo $exp_data['media']?>" alt="About Us" title="" media-simple="true">
                                            <?php }else if($exp_data['media_type']=="VIDEO"){ ?>
                                                <video controls controlsList="nodownload" style="width: 100%;">
                                                    <source src="<?php echo $exp_data['media']; ?>" type="video/<?php $tmp = explode(".",$exp_data['media']); echo end($tmp); ?>">
                                                    Your browser does not support the video tag.
                                                </video>
                                            <?php } ?>

                                        </div>

                                    <?php } ?>
                                    <div class="card-box description description_box" style="height: <?php echo $read_more_box_height; ?>">
                                        <a href='javascript:void(0);' class='read_more_link'>Read More </a>
                                        <?php echo $exp_data['description']; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                <?php } ?>
            </div>
        <?php } ?>
    </section>
<?php }else if($page_category_id==8){ ?>
    <section class="header11" style="background: url('<?php echo Router::url('/cms_theme/')?>/assets/images/testimonial_bg.png');">
        <div class="container align-left">
            <div class="media-container-column mbr-white col-md-12">
                <h1 class="mbr-section-title py-3 mbr-fonts-style display-1"><?php echo $title; ?></h1>
            </div>
        </div>
    </section>
    <section class="tabs3 cid-qv5AityZTn" style="background: #fff;" id="tabs3-2k" data-rv-view="10877">

        <?php if(!empty($final_array)){


            ?>
            <div class="container-fluid">
                <div class="row tabcont">
                    <ul class="nav nav-tabs" role="tablist">
                        <?php foreach ($final_array['category_list'] as $key => $category){ ?>
                            <li class="nav-item mbr-fonts-style tab_menu_li">
                                <a class="nav-link active display-7 tab_menu_link" role="tab" data-toggle="tab" href="#tabs3-2k_tab<?=strtoupper($key); ?>" aria-expanded="true">
                                    <?=$category; ?>
                                </a>
                            </li>
                        <?php } ?>

                    </ul>
                </div>
            </div>

            <div class="container">
                <div class="row">
                    <div class="tab-content" >
                        <?php foreach ($final_array['expert_list'] as $key => $expert){ ?>
                            <div id="<?=strtoupper($key); ?>" class="tab-pane in active mbr-table" role="tabpanel">

                                <?php foreach ($expert as $exp_key => $exp_data){ ?>

                                    <div class="media-container-row">
                                        <div class="col-12 col-md-12">
                                            <div class="media-container-row">
                                                <?php if(!empty($exp_data['media'])){ ?>
                                                    <div class="mbr-figure" style="width: 60%;">
                                                        <img class="testimonials_image" src="<?php echo $exp_data['media']; ?>" alt="<?php echo $exp_data['title']; ?>" media-simple="true">
                                                    </div>
                                                <?php } ?>
                                                <div class="media-content">
                                                    <div class="mbr-section-text">
                                                        <h3 class="testimonials_title"><?php echo $exp_data['title']; ?></h3>
                                                        <p class="mbr-text mb-0 mbr-fonts-style display-7">
                                                            <strong class="expert_sub_title">  <?php echo $exp_data['sub_title']; ?></strong>
                                                        </p>
                                                        <div class="mbr-text mb-0 mbr-fonts-style display-7 testimonials_description">
                                                            <span class="testimonials_cott_left">"</span>
                                                            <?php echo $exp_data['description']; ?>
                                                            <span class="testimonials_cott_right">"</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                <?php } ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        <?php } ?>



    </section>
<?php }else if($page_category_id==4){ ?>
    <section class="header11" style="background: url('<?php echo Router::url('/cms_theme/')?>/assets/images/contact_us_bg.png');">
        <div class="container align-left">
            <div class="media-container-column mbr-white col-md-12">
                <h1 class="mbr-section-title py-3 mbr-fonts-style display-1"><?php echo $title; ?></h1>
            </div>
        </div>
    </section>
    <section class="mbr-section form4 cid-qv5Aq4h3k3" id="form4-2y" data-rv-view="9854">




        <div class="container">
            <div class="row">
                <?php foreach ($final_array['expert_list'][key($final_array['expert_list'])] as $exp_key => $exp_data){ ?>
                    <div class="col-md-6">
                        <div class="icon-contacts contact_box">
                            <h5 class="align-left mbr-fonts-style display-2">
                                WE ARE HERE
                            </h5>

                            <?php if(!empty($exp_data['description'])){ ?>
                                <div class="icon-block contact_us_detail">
                                        <span class="icon-block__icon">
                                            <span class="mbri-pin mbr-iconfont" media-simple="true"></span>
                                        </span>
                                    <p class="icon-block__title align-left mbr-fonts-style display-15">
                                        <?php echo $exp_data['description']; ?>
                                    </p>
                                </div>
                            <?php  } ?>

                            <?php if(!empty($exp_data['sub_title'])){ ?>
                                <div class="icon-block contact_us_detail">
                                    <span class="icon-block__icon">
                                        <span class="mbri-letter mbr-iconfont" media-simple="true"></span>
                                    </span>
                                    <p class="icon-block__title align-left mbr-fonts-style display-15 email_margin">
                                        <a href="javascript:void(0);" class="__cf_email__" data-cfemail="a1d8ced4d3c4ccc0c8cde1ccc0c8cd8fc2cecc"><?php echo $exp_data['sub_title']; ?></a>
                                    </p>
                                </div>
                            <?php  } ?>

                            <?php if(!empty($exp_data['title'])){ ?>
                                <div class="icon-block contact_us_detail">
                                    <span class="icon-block__icon">
                                        <span class="mbri-mobile2 mbr-iconfont" media-simple="true"></span>
                                    </span>
                                    <p class="icon-block__title align-left mbr-fonts-style display-15">
                                        <?php echo $exp_data['title']; ?>
                                    </p>
                                </div>
                            <?php  } ?>

                            <?php if(!empty($exp_data['field2'])){ ?>
                                <div class="icon-block contact_us_detail">
                                    <span class="icon-block__icon">
                                        <span class="mbri-link mbr-iconfont" media-simple="true"></span>
                                    </span>
                                    <p class="icon-block__title align-left mbr-fonts-style display-15">
                                        <a target="_blank" href='<?php echo $exp_data['field2']; ?>'><?php echo $exp_data['field2']; ?></a>
                                    </p>
                                </div>
                            <?php  } ?>

                        </div>
                    </div>
                    <?php if(!empty($exp_data['field1'])){ ?>
                        <div class="col-md-6">
                            <div class="google-map">
                                <iframe frameborder="0" style="border:0" src="https://www.google.com/maps/embed/v1/place?key=<?php echo $google_api_key; ?>&amp;q=place_id:<?php echo $exp_data['field1']; ?>" allowfullscreen=""></iframe>
                            </div>
                        </div>
                    <?php }} ?>

            </div>
        </div>
    </section>
<?php }else if($page_category_id==2){ ?>
    <section class="header11" style="background: url('<?php echo Router::url('/cms_theme/')?>/assets/images/careers_bg.png');">
        <div class="container align-left">
            <div class="media-container-column mbr-white col-md-12">
                <h1 class="mbr-section-title py-3 mbr-fonts-style display-1"><?php echo $title; ?></h1>
            </div>
        </div>
    </section>
    <section class="tabs3 cid-qv5AityZTn" style="background: #fff;" id="tabs3-2k" data-rv-view="10877">

        <?php if(!empty($final_array)){


            ?>
            <div class="container-fluid">
                <div class="row tabcont">
                    <ul class="nav nav-tabs" role="tablist">
                        <?php foreach ($final_array['category_list'] as $key => $category){ ?>
                            <li class="nav-item mbr-fonts-style tab_menu_li">
                                <a class="nav-link active display-7 tab_menu_link" role="tab" data-toggle="tab" href="#tabs3-2k_tab<?=strtoupper($key); ?>" aria-expanded="true">
                                    <?=$category; ?>
                                </a>
                            </li>
                        <?php } ?>

                    </ul>
                </div>
            </div>

            <div class="container">
                <div class="row">
                    <div class="tab-content" >
                        <?php foreach ($final_array['expert_list'] as $key => $expert){ ?>
                            <div id="<?=strtoupper($key); ?>" class="tab-pane in active mbr-table" role="tabpanel">
                                <?php foreach ($expert as $exp_key => $exp_data){ ?>
                                    <div class="container">
                                        <div class="media-container-row career_container_card">
                                            <div class="card col-12 col-md-12 col-lg-12" style="padding-right: 0px; padding-left: 0px;">
                                                <div class="card-wrapper">
                                                    <h4 class="card-title  mbr-fonts-style display-8 career_title"><?php echo $exp_data['title']; ?></h4>
                                                    <span class="event_sub_title"><?php echo $exp_data['sub_title']; ?></span>

                                                    <div class="card-box description description_box" style="height: <?php echo $read_more_box_height; ?>">
                                                        <a href='javascript:void(0);' class='read_more_link'>Read More </a>
                                                        <?php echo $exp_data['description']; ?>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                <?php } ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        <?php } ?>



    </section>
<?php } ?>






<script src="<?php echo Router::url('/cms_theme/')?>assets/web/assets/jquery/jquery.min.js"></script>
<script src="<?php echo Router::url('/cms_theme/')?>assets/popper/popper.min.js"></script>
<script src="<?php echo Router::url('/cms_theme/')?>assets/tether/tether.min.js"></script>
<script src="<?php echo Router::url('/cms_theme/')?>assets/bootstrap/js/bootstrap.min.js"></script>
<script src="<?php echo Router::url('/cms_theme/')?>assets/smooth-scroll/smooth-scroll.js"></script>

<script src="<?php echo Router::url('/cms_theme/')?>assets/touch-swipe/jquery.touch-swipe.min.js"></script>
<script src="<?php echo Router::url('/cms_theme/')?>assets/theme/js/script.js"></script>
<script src="<?php echo Router::url('/cms_theme/')?>assets/mobirise-gallery/script.js"></script>

<script>
    $(function () {
        $(document).on("click",".tab_menu_link",function(){
            $(".tab_menu_link").removeClass('active');
            $(this).addClass('active');
            $(".gallery_tab_panel .tab_content_box").hide();
            $("#"+$(this).attr('data-tab-id')).show();
            showReadLinkd();
        });


        function showReadLinkd(){
            $(".description_box, .description_box:hidden").each(function () {
                if ($(this).prop('scrollHeight') > $(this).prop('clientHeight')){
                    $(this).find('.read_more_link').css('display', 'block');
                }
            });

        }

        showReadLinkd();

        $(document).on("click",".read_more_link",function(){

            var box_height = '<?php echo $read_more_box_height; ?>';
            var more_content = $(this).closest('.description_box');
            console.log(box_height);
            if($(this).hasClass('read_less')){
                $(more_content).animate({height:box_height});
                $([document.documentElement, document.body]).animate({
                    scrollTop: $(more_content).offset().top
                }, 100);
                $(this).html("Read More").addClass('read_more').removeClass('read_less');
            }else{
                $(more_content).animate({height:'100%'},1000);
                $(this).html("Read Less").addClass('read_less').removeClass('read_more');
            }

        });


    })
</script>

</body>
</html>