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
    <meta name="keywords" content="<?php echo $thin_app_data['name']; ?>">
    <script type="text/javascript"> var baseUrl = '<?php echo Router::url('/',true); ?>'; </script>
    <?php echo $this->Html->script(array('jquery-3.5.1.min.js','popper.min.js','bootstrap4.min.js','loader.js','es6-promise.auto.min.js')); ?>
    <?php echo $this->Html->css(array( 'bootstrap4.min.css','doctor.css?'.date('his'),'font-awesome.min.css' ),array("media"=>'all')); ?>
    <title><?php echo $thin_app_data['name']; ?></title>

    <style>


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
                    <button type="button" class="back_button" onclick="window.history.back();" class=""><i class="fa fa-arrow-left" aria-hidden="true"></i></button>
                </div>
                <div class="col-2 top_photo_box">
                    <img src="<?php echo $thin_app_data['logo']; ?>"  />
                </div>
                <div class="col-9" style="padding-right: 0px;padding-left: 0px;">
                    <div class="doctor-detail-container" style="padding: 0;" >
                        <h1 class="header_title"><?php echo $thin_app_data['name']; ?></h1>
                        <label>Department List</label>
                    </div>
                </div>
            </div>
        </div>



</header>
    <div class="main_container contain">
        <div class="col-md-12 blog-search">
            <div class="form-group" style="margin-top: 1rem; margin-bottom: 0px;">
                <div class="icon-addon addon-md">
                    <input type="text" placeholder="Search Department..." class="form-control search-input" id="myInput">
                    <label for="email" class="fa fa-search search_icon" rel="tooltip" title="Search Department..."></label>
                </div>
            </div>
        </div>

        <div class="col-md-12">

            <?php if(!empty($department_list)){ foreach($department_list as $key => $list){
                $image = explode('/',$list['image']);
                if( strpos(end($image),'.') !== false){
                    $image = $list['image'];
                }else{
                    $image = Router::url('/img/profile.png',true);
                }

                ?>
            <ul id="department_list">
                    <li>
                        <a href="<?php echo Router::url('/doctor/search_doctor/'.base64_encode($list['id']),true)?>">
                            <div class="row">
                                <div class="col-2" style="padding: 0px;"> <img class="department_image" src="<?php echo $image; ?>"></div>
                                <div class="col-9">
                                    <label>
                                        <h5><?php echo $list['name']; ?></h5>
                                        <span style="display: block; font-size: 0.9rem;"><?php $string = ($list['total_doctor'] > 1)?'s':''; echo $list['total_doctor']." Doctor$string Available   "; ?></span>
                                    </label>
                                </div>
                                <div class="col-1" style="margin: 0px;padding: 0px;">
                                    <i class="fa fa-angle-right right_navigation" aria-hidden="true"></i>
                                </div>
                            </div>
                        </a>
                    </li>

            </ul>
            <?php }}else{ ?>
                <h4>No Department Found</h4>
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
                $("#department_list li").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });


        });

    </script>


</body>
</html>