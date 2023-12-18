<!DOCTYPE html>
<html>
<head class="row_content" >
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta charset="UTF-8">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="theme-color" content="#7f0b00">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script>

        var baseUrl = '<?php echo Router::url('/',true); ?>';
    </script>

    <?php echo $this->Html->script(array('jquery.js','loader.js','bootstrap.min.js','angular.min.js','es6-promise.auto.min.js','sweetalert2.min.js','html5gallery.js')); ?>

    <?php echo $this->Html->css(array()); ?>


    <?php echo $this->Html->css(array( 'bootstrap.min.css','doctor.css','font-awesome.min.css','sweetalert2.min.css','bootstrap.min.css' ),array("media"=>'all')); ?>
    <title>Doctor</title>

    <style>

        /**** BASE ****/
        .container-fluid {
            background: #f9f9f9;
        }
        a {
            color: #03a1d1;
            text-decoration: none!important;
        }

        /**** LAYOUT ****/
        .list-inline>li {
            padding: 0 10px 0 0;
        }
        .container-pad {
            padding: 15px 1px;
        }


        /**** MODULE ****/
        .bgc-fff {
            background-color: #fff!important;
        }
        .box-shad {
            -webkit-box-shadow: 1px 1px 0 rgba(0,0,0,.2);
            box-shadow: 1px 1px 0 rgba(0,0,0,.2);
        }
        .brdr {
            border: 1px solid #ededed;
        }

        /* Font changes */
        .fnt-smaller {
            font-size: .9em;
        }
        .fnt-lighter {
            color: #bbb;
        }

        /* Padding - Margins */
        .pad-10 {
            padding: 10px!important;
        }
        .mrg-0 {
            margin: 0!important;
        }
        .btm-mrg-10 {
            margin-bottom: 10px!important;
        }
        .btm-mrg-20 {
            margin-bottom: 20px!important;
        }

        /* Color  */
        .clr-535353 {
            color: #535353;
        }

        .img-responsive{
            width: 120px;
            height: 120px;
            border-radius: 67px;
            margin: 0 auto;

        }

        .container{
            width: 100% !important;
        }

        /**** MEDIA QUERIES ****/
        @media only screen and (max-width: 991px) {
            #property-listings .property-listing {
                padding: 5px!important;
            }
            #property-listings .property-listing a {
                margin: 0;
            }
            #property-listings .property-listing .media-body {
                padding: 10px;
            }
        }

        @media only screen and (min-width: 992px) {
            #property-listings .property-listing img {
                max-width: 180px;
            }
        }







        #search_blog_btn {
            height: 43px !important;
            margin-left: -60px;
      }





        .logo_box{
            background: #fff;
            /* position: absolute; */
            margin-top: 5px;
            height: 82px;
            border-radius: 0px 54px 0px 0px;
            padding: 5px;



            }

            .doctor_list_logo{
                padding: 0px !important;
            }
            header{
                padding: 0px;
                background:#fbbc05;
            }
            .container-pad {
                padding: 2px 1px;
                border-top: 2px solid #0530ff;
            }
            #search-doctor-text{
                width: 60%;
                float: left;
                margin-left: 35%;
            }

            #search_blog_btn {
                height: 44px !important;

            }


            .append_doctor_list .doctor_div_section {
               border: 1px solid #dedede !important;
               box-shadow: none !important;

           }
         .logo_box span{
            display: none;
         }

        header{
            padding: 0px;
            background: #fbbc05;
            position: fixed;
            z-index: 99999;
            width: 100%;
            display: block;
        }
        .left_menu_div{
            position: fixed;
            float: left;
            width: 16.6%;
        }


        @media only screen and (max-width: 600px) {
            .append_doctor_list .doctor_div_section{
                width: 47%;
                float: left;
            }
            .append_doctor_list{
                float: left;
                width: 100%;
                margin-top: 18rem;
            }
            #search-doctor-text{
                font-size: 1.1rem;
            }
            .logo_box{
                width: 100%;
                margin: 0 auto;
                border-radius: 0 !important;
                border-top: 2px solid #fbbc05;
            }
            .left_menu_div{
                position: fixed;
                float: left;
                width: 100% !important;

                z-index: 99999;
                top: 12.5rem;
                border-top: 2px solid #fff;
            }
            .left_menu_div .left_menu{
                float: left;
                width: 25%;
                font-size: 1rem;
                text-align: center;
                height: 65px;
                padding: 6px 0px;
            }
            .left_menu_div .left_menu a{
                width: 100%;
                margin: 0;
                padding: 0;
                text-align: center;
            }
            .doctor_side_head{
                display: none;
            }

            #search-doctor-text {
                width: 92% !important;
                float: left !important;
                margin-left: 0% !important;
            }

            #search_blog_btn {
                height: 43px !important;
                margin-left: -35px !important;
                width: 60px !important;
            }
            .slider_row{
                display: none !important;
            }
            .left_menu_div label img{
                width: 30px;
            }

            .left_menu_div .left_menu a span{
                display: block;
                width: 100%;
                margin: 10px 0px;
            }

            .img-responsive {
                width: 80px;
                height: 80px;
                border-radius: 60px;
                margin: 0 auto;
            }
            .append_doctor_list .doctor_div_section {
                padding: 6px !important;
                margin: 6px 4px;
                height: 188px;
                margin-top: 4px;
            }
            .category_div h3{
                font-size: 16px;
            }

            .no_doctor_available{
                font-size: 1.5rem;
            }

            label.left_menu.active_filter {
                border-bottom: 5px solid rgba(0, 0, 0, 0.41);
                border-right: none !important;
            }
            .doctor_list_logo img{
                height: 40px;
                float: left;
                margin: 0px 10px;
            }
            .logo_box{
                height: 50px!important;
                margin-top: 0px !important;

            }


            .logo_box span{
                color: #2f2424 !important;
                font-size: 2.6rem !important;
                display: block !important;
                float: right;
                padding: 0px 10px;
            }

        }


        @media only screen and (max-width: 320px) {

            .load_list_box{
                width: 100% !important;
            }
            #search_blog_btn {
                height: 35px !important;
                margin-left: -27px !important;
                width: 50px !important;
            }


            .left_menu_div .left_menu {

                font-size: 0.9rem;
                height: 64px;
                padding: 5px 6px;
            }
            .left_menu_div{
                top: 10.5rem;
            }

            .append_doctor_list{
                margin-top: 16rem;
            }

            .img-responsive {
                width: 50px;
                height: 50px;

            }
            .doctor_name_box{
                font-size: 0.6rem;
                height: 28px;
            }
            .append_doctor_list .doctor_div_section{
                height: 140px;
            }
            .box_doc_name_link{
                font-size: 1.2rem;
            }
            #search-doctor-text{
                font-size: 0.9rem;
            }

            .form-control{
                height: 35px !important;
                font-size: 0.9rem;
            }

        }




        @media (min-width: 768px) and (max-width: 1024px) {

            .load_list_box{
                width: 100% !important;
            }

            .append_doctor_list .doctor_div_section{
                width: 32.2%;
                float: left;
            }
            .append_doctor_list{

                margin-top: 28rem;
            }
            #search-doctor-text{
                font-size: 1.1rem;
            }


            .logo_box{
                width: 100%;
                margin: 0 auto;
                border-radius: 0 !important;
                border-top: 8px solid #fbbc05;
            }





            .left_menu_div{
                position: fixed;
                float: left;
                width: 100% !important;

                z-index: 99999;
                top: 18.5rem;
                border-top: 6px solid #fff;
            }
            .left_menu_div .left_menu{
                float: left;
                width: 25%;

                text-align: center;
                font-size: 2rem;
                height: 100px;
                padding: 6px 0px;
            }



            .left_menu_div .left_menu a{
                width: 100%;
                margin: 0;
                padding: 0;
                text-align: center;
            }
            .doctor_side_head{
                display: none;
            }

            #search-doctor-text {
                width: 92% !important;
                float: left !important;
                margin-left: 0% !important;
                font-size: 2.1rem !important;
                height: 60px !important;
            }

            #search_blog_btn {
                height: 60px !important;
                margin-left: -40px !important;
                width: 90px;
            }
            .slider_row{
                display: none !important;
            }
            .left_menu_div label img{
                width: 50px;
            }

            .left_menu_div .left_menu a span{
                display: block;
                width: 100%;
                margin: 10px 0px;
            }

            .img-responsive {
                width: 100px;
                height: 100px;

            }
            .append_doctor_list .doctor_div_section {
                padding: 6px !important;
                margin: 6px 4px;
                height: 230px;
                margin-top: 4px;
            }
            .category_div h3{
                font-size: 25px;
            }
            .btn-sm, .btn-xs{
                font-size: 2rem;
            }

            .no_doctor_available{
                font-size: 2.5rem;
            }
            .box_doc_name_link{
                font-size: 2.2rem;
            }

            .box_app_name {
                font-size: 1.8rem;
                width: 100%;
                display: block;
                float: left;
                margin: 18px 0px;
            }

            .total_downloads li i{
                font-size: 2.5rem;
            }
            .box_app_name{
                font-size: 1.8rem;
            }
            label.left_menu.active_filter {
                border-bottom: 5px solid rgba(0, 0, 0, 0.41);
                border-right: none !important;
            }
            .doctor_list_logo img {
                height: 80px;
                float: left;
                margin: 0px 20px;
            }

            
            .logo_box{
                height: 100px!important;

            }





            .logo_box span{
                color: #2f2424 !important;
                font-size: 4.6rem !important;
                display: block !important;
                float: right;
                padding: 0px 10px;
            }

        }







    </style>
</head>
<body >
<div class="container-fluid" >
    <header>

        <div class="row too doctor_list_logo" >
            <div class="col-md-2 logo_box">
                <img class="doctor_list_logo" src="<?php echo Router::url('/images/logo.png',true); ?>" >
                <span>
                    Our Doctors
                </span>
            </div>
            <div class="col-md-10 search_box_div">
                <div class="form-group">
                    <div class="search_btn_form icon-addon addon-md">
                        <input type="text" placeholder="Search Doctor/Hospital name, city, doctor category" class="form-control search-input" id="search-doctor-text">
                        <button type="button"  id="search_blog_btn"  rel="tooltip" title="Search Doctor..."><i class="fa fa-search"></i> </button>
                    </div>
                </div>
            </div>
        </div>
    </header>



    <?php

    $color =array('#689F38','#ED3B3B','#EF6C00','#FBBC05','#039BE5','#536DFE','#658092');

    ?>
    <div class="container container-pad" id="property-listings">


        <div class="row main_row_box" >

            <div class="col-md-2 left_side_bar">
                <h3 class="doctor_side_head"><img src="<?php echo Router::url('/images/category.png',true); ?>" > Category</h3>
                <div class="left_menu_div">
                    <label class="left_menu" data-f="TOP_FOLLOWERS" style="background: <?php echo $color[3]; ?>"><a href="javascript:void(0);" ><img src="<?php echo Router::url('/images/top_followers.png',true); ?>" > <span>Top Follower</span></a></label>
                    <label class="left_menu" data-f="TOP_DOWNLOADS" style="background: <?php echo $color[4]; ?>"><a href="javascript:void(0);" ><img src="<?php echo Router::url('/images/top_download.png',true); ?>" > <span>Top Download</span></a></label>
                    <label class="left_menu" data-f="AVAILABLE"  style="background: <?php echo $color[0]; ?>"><a href="javascript:void(0);" ><img src="<?php echo Router::url('/images/available.png',true); ?>" > <span>Available</span></a></label>
                    <label class="left_menu" data-f="NEW_DOCTORS"  style="background: <?php echo $color[1]; ?>"><a href="javascript:void(0);" ><img src="<?php echo Router::url('/images/new_doctor.png',true); ?>" > <span>New Doctor</span></a></label>

                </div>

            </div>
            <div class="col-md-10 load_list_box">

                <div class="row slider_row">
                    <div id="myCarousel" data-interval="false" class="carousel slide" data-ride="carousel">

                        <?php
                            $cat_filter=array();
                            $category = $this->AppAdmin->getDoctorCategoryList();

                            foreach($category as $id => $name ){
                                $cat_filter[$name] = $id;
                            }
                            array_unshift($category,'All');

                        $chunk_array = array_chunk($category,7);

                        ?>


                    <div class="carousel-inner">

                        <?php foreach($chunk_array as $key => $cat_array ){ ?>

                        <div data-index="<?php echo $key; ?>" class="item <?php echo ($key==0)?'active':''; ?>">

                            <?php $counter = 0; foreach($cat_array as $cat_id => $cat_name ){ ?>
                                  <label data-cat = "<?php echo isset($cat_filter[$cat_name])?$cat_filter[$cat_name]:0; ?>" class="category_menu <?php echo (@$cat_filter[$cat_name]==0)?'active_category':''; ?>" style="background: <?php echo $color[$counter++]; ?>"><a  href="javascript:void(0);" ><?php echo $cat_name; ?></a></label>
                            <?php } ?>
                        </div>
                        <?php } ?>

                    </div>

                    <!-- Left and right controls -->
                    <a class="left carousel-control" href="#myCarousel" data-slide="prev">
                        <span class="fa fa-angle-left"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="right carousel-control" href="#myCarousel" data-slide="next">
                        <span class="fa fa-angle-right"></span>
                        <span class="sr-only">Next</span>
                    </a>
                </div>
                </div>
                <div class="row append_doctor_list">

                </div>
            </div>



        </div>
        <!-- End row -->
    </div><!-- End container -->
</div>

</body>
<script type="text/javascript">
    $(function () {

        var off =0;
        var loading_ajax = false;
        var show_all = false;
        var category_id = 0;
        function load_doctor(search_loader=false){
            var search = $("#search-doctor-text").val();
            var data_cat = category_id;
            if(category_id == 0){
                 data_cat = $(".active_category").attr('data-cat');
            }else{
                show_all =true;
            }

            var filter = $(".active_filter").attr('data-f');

            var data = {search:search,category_id:data_cat,filter:filter,show_all:show_all};
            if( loading_ajax ===false) {
                $.ajax({
                    type: 'POST',
                    url: baseUrl + "doctor/load_doctor_list",
                    data: data,
                    beforeSend: function () {
                        loading_ajax = true;

                        if(search_loader === true){
                            $('#search_blog_btn i').removeClass('fa-search').addClass('fa fa-spinner fa-spin');
                        }
                    },
                    success: function (data) {

                        $(".append_doctor_list").html('');
                        if(search_loader === true){
                            $('#search_blog_btn i').removeClass('fa-spinner fa-spin').addClass('fa-search');
                        }

                        $(".append_doctor_list").html(data).hide();
                        $(".append_doctor_list").fadeIn(1500);

                        if(show_all===true){
                            $('.show_all').hide();
                        }else{
                            $('.show_all').show();
                        }

                        loading_ajax = false;
                        off++;
                    },
                    error: function (data) {
                        if(search_loader === true){
                            $('#search_blog_btn i').removeClass('fa-spinner fa-spin').addClass('fa-search');
                        }
                        loading_ajax = false;
                    }
                });
            }

        }

        $(document).on('click','#search_blog_btn',function (e) {
            off =0;
            load_doctor(true);
        });

        $(document).on('keypress','#search-doctor-text',function (e) {
            if(e.keyCode == 13){
                off =0;
                load_doctor(true);
            }

        });

        $(document).on('click','.category_menu',function (e) {

            category_id =$(this).attr('data-cat');
            $(".category_menu").removeClass('active_category');
            $(".left_menu").removeClass('active_filter');

            $(this).addClass('active_category');
            if($(this).attr('data-cat') == 0){
                $(".left_menu").removeClass('active_filter');
                show_all = false;
            }

            load_doctor();


        });

        $(document).on('click','.left_menu',function (e) {
            $(".left_menu").removeClass('active_filter');
            $(this).addClass('active_filter');
            load_doctor();


        });


        $(document).on('click','.show_all',function (e) {

            category_id =$(this).attr('data-c');
            $(".category_menu").removeClass('active_category');
            $("label[data-cat="+category_id+"]").addClass('active_category');


            $("#myCarousel .item").removeClass('active');
            $("label[data-cat="+category_id+"]").closest('.item').addClass('active');

            load_doctor();


        });



        $(document).scroll(function(e){

            // grab the scroll amount and the window height
            var scrollAmount = $(window).scrollTop();
            var documentHeight = $(document).height();

            // calculate the percentage the user has scrolled down the page
            var scrollPercent = (scrollAmount / documentHeight) * 100;
            if(scrollPercent > 50) {
               // load_doctor();
            }



        });


        $(document).off('click','.doctor_div_section');
        $(document).on('click','.doctor_div_section',function(){
            window.open($(this).attr('data-url'), '_blank');
        });

        load_doctor();
        $('.carousel').carousel();
    })
</script>


</html>