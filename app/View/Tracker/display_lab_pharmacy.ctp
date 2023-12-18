<?php
$login = $this->Session->read('Auth.User');
?>
<html>
<head>
    <title>Appointment Tracker</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <?php echo $this->Html->css(array('tracker.css')); ?>
    <style>
        /* html, body {margin: 0; height: 100%; overflow: hidden}*/
        .stat {
            margin-top: 100px;
            font-size: xx-large;
            font-weight: bold;
        }
        .mu-hero-overlay {
            height: 100%;

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
        }
        #go-button span{
            font-size: 9px;
            color: #fff;
            width: 100%;
            display: block;
            text-align: center;
        }

        @media only screen and (max-width: 500px) {
            .mu-logo{
                font-size: 18px;
            }
            #mu-event-counter, .mu-event-counter-area{
                width: 100%;
            }
            .mu-event-counter-block{
                width: 170px;
                height: 170px;
                margin: 0 auto;
            }
        }



        @media (min-width: 1281px) {



        }


        @media (min-width: 1025px) and (max-width: 1280px) {



        }



        @media (min-width: 768px) and (max-width: 1024px) {



        }



        @media (min-width: 768px) and (max-width: 1024px) and (orientation: landscape) {



        }


        @media (min-width: 481px) and (max-width: 767px) {




        }


        @media (min-width: 1000px) and (max-width: 1200px) and (orientation: landscape) {

            body {
                background: red !important;
            }


        }

        @media (min-width: 1000px) and (max-width: 1200px) and (min-height: 1500px)  and (max-height: 2000px) {

            .mu-event-counter-area{
                margin-top: 30%;
            }
            .container {

                width: 100%;

            }
            #go-button {
                display: none;
            }
            .mu-event-counter-block {
                width: 80% !important;
                height: 650px;
                margin: 0 auto;
            }
            .mu-event-counter-block{
                margin: 0 11%;
            }
            #mu-event-counter{
                width: 100%;
            }
            .mu-hero-featured-content {

                display: inline;
                float: right;
                margin-top: -400px;
                text-align: center;
                width: 100%;
                padding-top: 30px;

            }
            .mu-event-counter-block span {

                display: block;
                font-size: 300px;
                font-weight: 700;
                padding-top: 300px;
                line-height: 40px;

            }
            .mu-hero-featured-content {
                margin:  0;
            }


        }

        @media (max-width: 480px){
            .mu-event-counter-block span {
                font-size: 65px !important;
            }

            .mu-event-counter-area, .mu-hero-featured-content {
                width: 100%;
            }
        }

        @media (max-width: 360px){

            .mu-hero-featured-area {
                padding: 64px 0;
            }
        }


        @media (min-width: 320px) and (max-width: 480px) {

            .mu-logo{
                font-size: 18px;
            }
            #mu-event-counter, .mu-event-counter-area{
                width: 100%;
                margin: 8px auto;
            }
            .mu-event-counter-block{
                width: 170px;
                height: 170px;
                margin-left: 21%;

                padding-top: 50px;
                line-height: 20px;
            }
        }
        }


    </style>
</head>
<body   >


<!-- Highlights -->
<div>


    <header id="mu-hero" class="" role="banner">

        <div id="go-button">
            <img src="<?php echo Router::url('/images/tracker/large_screen.png',true);?>" ></img>
            <span>Maximize</span>
        </div>

        <div class="mu-hero-overlay" >
            <div class="container">
                <div class="mu-hero-area append_html">

                    <h1 style="text-align: center;"> Please wait... </h1>

                </div>
            </div>

        </div>

    </header>



</div>

</div>


<!-- Scripts -->
<script src="<?php echo Router::url('/js/jquery.js')?>"></script>
<script src="<?php echo Router::url('/js/bootstrap.min.js')?>"></script>
<script>
    $(document).ready(function(){
        load_list();
        var change_div = 10000;
        var process_running = false;
        setInterval(function () {
            if(process_running===false){
                load_list(false);

            }
        },change_div);


        var image_path = "<?php echo Router::url('/images/tracker/',true);?>";

        function load_list(showloader=false){

            var t = "<?php echo @($thinappID); ?>";
            var n = "<?php echo @($app_data['name']); ?>";
            var l = "<?php echo @($labUserId); ?>";
            var baseurl ="<?php echo Router::url('/',true);?>";
            $.ajax({
                type:'POST',
                url: baseurl+"tracker/load_tracker_lab_pharmacy",
                data:{
                    t:t,l:l,app_name:n
                },

                beforeSend:function(){

                    if(showloader===true){
                    }
                    process_running = true;

                },
                success:function(data){

                    var x = new Date()
                    var x1=x.toUTCString();
                    var hour = (x.getHours() <10)?"0"+x.getHours():x.getHours();
                    var minute = (x.getMinutes() <10)?"0"+x.getMinutes():x.getMinutes();
                    var sec = (x.getSeconds() <10)?"0"+x.getSeconds():x.getSeconds();
                    console.log(hour+":"+minute+":"+sec);

                    $(".loader_div").hide();

                    $(".append_html").html(data);


                    process_running = false;


                },
                error: function(data){
                    $(".loader_div").hide();
                    if(showloader===true){
                        // $.alert("Sorry something went wrong on server.");
                    }
                    process_running =false;

                }
            });

        }

        /* Get into full screen */
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

        /* Get out of full screen */
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

        /* Is currently in full screen or not */
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
                GoInFullscreen($("#mu-hero").get(0));
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

    });
</script>





</body>
</html>