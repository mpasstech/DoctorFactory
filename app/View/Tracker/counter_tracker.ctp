<html>
<head>
    <title>Appointment Tracker</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <script src="<?php echo Router::url('/js/jquery.js')?>"></script>
    <script src="<?php echo Router::url('/js/bootstrap4.min.js')?>"></script>
    <?php echo $this->Html->css(array('bootstrap4.min.css','font-awesome.min.css'),array("media"=>'all','fullBase' => true)); ?>
    <style>



        .main_box{
            display: block;
            float: left;
            width: 100%;
        }

        .container {
            background: #fff;
            margin: 0 auto;
            width: 100%;
        }

        body{
            background: #fff;
            overflow: hidden;
        }
        #go-button{position: absolute;
            position: absolute;
            position: absolute;
            top: 29px;
            float: right;
            z-index: 9999999;
            right: 0;
            height: 40px;
            width: 45px;
            cursor: pointer;

        }
        #go-button img{
            border: 1px solid;
            border-radius: 6px 0px 0px 6px;
            width: 50px;
            height: 50px;
        }
        #go-button span{
            font-size: 9px;
            color: #fff;
            width: 100%;
            display: block;
            text-align: center;
        }

        .logo{
            width: 40%;
            display: block;
            background: #fff;
            border-radius: 50%;
        }
        .app_title{
            text-align: center;
            width: 100%;
            padding: 1.6rem;
        }


        .head_row{
            padding: 0.5rem;
            background: linear-gradient(#2b00d6, #8465ff);
            border-bottom: 10px solid #10ff00;
            color: #fff;
        }

    </style>

</head>
<body style="background: #fff;">
<div class="main_container" style="background: #fff;">
    <div id="go-button">
        <img src="<?php echo Router::url('/images/tracker/large_screen.png',true);?>" ></img>
        <span>Maximize</span>
    </div>
    <div class="row head_row">
        <div class="col-3">
            <img class="logo" src="<?php echo $thin_app_data['logo']; ?>">
        </div>
        <div class="col-6">
            <h1 style="font-size: 3.5rem;" class="app_title"><?php echo $thin_app_data['name']; ?></h1>
        </div>

        <div class="col-3">
            <h3 class="app_title">Token Tracker</h3>
        </div>
    </div>
    <div class="col-12 append_tracker_box">
        <h1 style="text-align: center;"> Please Wait... </h1>
    </div>

</div>

<script>
    $(document).ready(function(){

        var refresh_list_seconds = "8000";
        var image_path = "<?php echo Router::url('/images/tracker/',true);?>";

        var ajaxReq;
        function load_list(){
            var t = "<?php echo base64_encode($thin_app_id); ?>";
            var baseurl ="<?php echo Router::url('/',true);?>";
           ajaxReq =  $.ajax({
                type:'POST',
                url: baseurl+"tracker/load_counter_tracker",
                data:{t:t},
                beforeSend:function(){
                    if(ajaxReq !=null){
                        ajaxReq.abort();
                    }
                },
                success:function(data){
                    $(".append_tracker_box").html(data);
                },
                error: function(data){

                }
            });
        }
        load_list();

        setInterval(function(){load_list();},refresh_list_seconds);


        function resize(){
            $('.container').height($(document).height());
        }

        function GoInFullscreen(element) {
            if(element.requestFullscreen)
                element.requestFullscreen();
            else if(element.mozRequestFullScreen)
                element.mozRequestFullScreen();
            else if(element.webkitRequestFullscreen)
                element.webkitRequestFullscreen();
            else if(element.msRequestFullscreen)
                element.msRequestFullscreen();

        }
        function GoOutFullscreen() {
            if(document.exitFullscreen)
                document.exitFullscreen();
            else if(document.mozCancelFullScreen)
                document.mozCancelFullScreen();
            else if(document.webkitExitFullscreen)
                document.webkitExitFullscreen();
            else if(document.msExitFullscreen)
                document.msExitFullscreen();

        }
        function IsFullScreenCurrently() {
            var full_screen_element = document.fullscreenElement || document.webkitFullscreenElement || document.mozFullScreenElement || document.msFullscreenElement || null;

            // If no element is in full-screen
            if(full_screen_element === null)
                return false;
            else
                return true;
        }
        $("#go-button").on('click', function() {

            if(IsFullScreenCurrently())
                GoOutFullscreen();
            else
                GoInFullscreen($(".main_container").get(0));
        });
        $(document).on('fullscreenchange webkitfullscreenchange mozfullscreenchange MSFullscreenChange', function() {
            if(IsFullScreenCurrently()) {
                $("#go-button").attr('src',image_path+"small_screen.png");
                $("#go-button span").html('Minimize');

            }
            else {
                $("#go-button").attr('src',image_path+"large_screen.png");
                $("#go-button span").html('Maximize');

            }
        });

        $(window).on('resize', function(){
            resize();
        });

        resize();

    });
</script>



</body>
</html>