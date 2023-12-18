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
    <meta name="keywords" content="<?php echo $department_data['name']; ?>">
    <script type="text/javascript"> var baseUrl = '<?php echo Router::url('/',true); ?>'; </script>
    <?php echo $this->Html->script(array('jquery-3.5.1.min.js','popper.min.js','bootstrap4.min.js','loader.js','es6-promise.auto.min.js')); ?>
    <?php echo $this->Html->css(array( 'bootstrap4.min.css','doctor.css?'.date('his'),'font-awesome.min.css' ),array("media"=>'all')); ?>
    <title><?php echo $department_data['name']; ?></title>


    <style>


        #doctor_box li{
            list-style: none;
        }
        .button_box{
            margin-top: 20px;
        }
        .book_btn, .book_btn:hover, .book_btn:active{
            float: right;
            border: 1px solid #0fb30f;
            color: #0fb30f;
            padding: 4px 9px;
            border-radius: 23px;
            font-size: 0.9rem;
            outline: none;
            text-decoration: none;
        }
        .icon_div i{
            font-size: 1.1rem !important;
            margin: 3px 1px;
            border: 1px solid;
            border-radius: 21px;
            padding: 6px;
            height: 30px;
            width: 30px;
        }

        .icon_box_container{
            float: left;
            margin: 0 0.1rem;
        }
         .icon_box_container  span{
             font-size:0.6rem;
             width: 100%;
             display: block;
             text-align: center;
         }


        }
        .blog-search{
            margin: 0px !important;
        }

        .blog-search{
            background: #fff;
        }

        body{
            background: #f2f2f2;
        }
        .card-body label{
            width: 100%;
            display: block;;
        }
        .card{
            margin: 15px 0px;
        }
        /* width */
        ::-webkit-scrollbar {
            width: 5px;


        }

        /* Track */
        ::-webkit-scrollbar-track {
            box-shadow: inset 0 0 8px grey;
            border-radius: 50px;
        }

        /* Handle */
        ::-webkit-scrollbar-thumb {
            background: #61A7FD;
            border-radius: 5px;
        }

        /* Handle on hover */
        ::-webkit-scrollbar-thumb:hover {
            background: #fcfcfc;
        }



    </style>


</head>

<body  ng-app="myApp" id="body">
    <header>





    <div class="row block detail_main_box">
        <div class="row top_header_row">
            <div class="col-1 top_photo_box">
                <button type="button" class="back_button" onclick="window.location.href = '<?php echo Router::url("/doctor/department/".base64_encode($department_data['thinapp_id']),true); ?>'" class=""><i class="fa fa-arrow-left" aria-hidden="true"></i></button>
           </div>
            <div class="col-2 top_photo_box">
                <img src="<?php echo $department_data['logo']; ?>"  />
            </div>
            <div class="col-9" style="padding-right: 0px;padding-left: 0px;">
                <div class="doctor-detail-container" style="padding: 0;" >
                    <h1 class="header_title"><?php echo $department_data['app_name']; ?></h1>
                    <label>Doctor List</label>
                </div>
            </div>
        </div>
    </div>

</header>
    <div class="main_container contain">

        <div class="col-md-12 blog-search">
            <h3 style="text-align: center;width: 100%;padding: 10px 0px;"><?php echo $department_data['name']; ?></h3>
            <div class="form-group">
                <div class="icon-addon addon-md">
                    <input type="text" placeholder="Search Doctor..." class="form-control search-input" id="myInput">
                    <label for="email" class="fa fa-search search_icon" rel="tooltip" title="Search Doctor"></label>
                </div>
            </div>
        </div>

        <div class="col-md-12" id="doctor_box">
            <?php if(!empty($doctor_list)){ foreach($doctor_list as $key => $list){

                $image = explode('/',$list['profile_photo']);
                if( strpos(end($image),'.') !== false){
                    $image = $list['profile_photo'];
                }else{
                    $image = Router::url('/img/profile.png',true);
                }

                ?>
                <li>
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-2" style="padding: 0px;">
                                <img class="department_image"  src="<?php echo $image; ?>">
                            </div>
                            <div class="col-10 top_section">
                                <h5><?php echo trim($list['name']); ?></h5>
                                <?php if(!empty($list['sub_title'])){ ?>
                                    <label>Degree : <?php echo $list['sub_title']; ?></label>
                                <?php } ?>

                                <?php if(!empty($list['registration_number'])){ ?>
                                    <label>License No : <?php echo $list['registration_number']; ?></label>
                                <?php } ?>
                            </div>
                            <div class="col-12 button_box">
                                <label style="display: block;width: 100%;">
                                    Consultation Option
                                </label>
                                <div style="display: block;width: 100%;" class="icon_div">

                                    <?php $option=array(); if($list['is_offline_consulting'] =='YES'){ $option[] =true; ?>
                                        <div class="icon_box_container">
                                            <i class="fa fa-building-o"></i>
                                            <span>Hospital</span>
                                        </div>


                                    <?php } ?>

                                    <?php if($list['is_audio_consulting'] =='YES'){ $option[] =true; ?>
                                        <div class="icon_box_container">
                                            <i class="fa fa-phone"></i>
                                            <span>Call</span>
                                        </div>


                                    <?php } ?>

                                    <?php if($list['is_online_consulting'] =='YES'){ $option[] =true; ?>
                                        <div class="icon_box_container">
                                            <i class="fa fa-video-camera"></i>
                                            <span>Video</span>
                                        </div>


                                    <?php } ?>

                                    <?php if($list['is_chat_consulting'] =='YES'){ $option[] =true; ?>
                                        <div class="icon_box_container">
                                            <i class="fa fa-comments-o"></i>
                                            <span>Chat</span>
                                        </div>


                                    <?php } ?>

                                    <?php if(!empty($option)){ ?>
                                        <a href="<?php echo Router::url('/doctor/index/?d=YES&t='.base64_encode($list['id']),true); ?>"  style="float: right;" class="book_btn" >Book Appointment</a>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
                </li>
            <?php }}else{ ?>
                <h4>No Doctor Found</h4>
            <?php } ?>
        </div>
    </div>
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
            $("#myInput").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $("#doctor_box li").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });


        });

    </script>


</body>
</html>