<html>
<head>
    <title>Appointment Tracker</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <meta name="description" content="" />
    <meta name="keywords" content="" />
    <?php echo $this->Html->css(array('fourth_tracker.css?'.date('Ymd')),array("media"=>'all','fullBase' => true)); ?>
    <style>
        .active_box{
            display: block !important;
        }


        @media (min-width: 1080px) and (min-height: 1920px) and (orientation: portrait){
            .powered_by{
                width: 25%;
            }
            .current_patient_span{
                width: 70% !important;;
            }
            .current_patient_time_span{
                width: 30% !important;
            }

            .current_patient_span span, .current_patient_time_span span{
                font-size :4.5rem !important;
            }

            .doctor_patient_list .patient_box, .doctor_patient_list .time_box{
                font-size: 3.1rem !important;
            }
            .check_in_type_lbl{
                width: 36% !important;
            }
        }
    </style>
</head>
<body >

<!-- Highlights -->


<div id="element" class=" main_section" style="width: 100%;">

    <div class="overlay_box_div" >
        <lable class="break_doctor"></lable>
        <h2 class="emergency">EMERGENCY</h2>
        <h3 class="emer_patient_name"></h3>
    </div>


    <img src="<?php echo Router::url('/images/tracker/large_screen.png',true);?>" id="go-button"></img>


    <select id="screen_size_drp" style="position: absolute;right: 0; padding: 2px 2px; z-index: 99999;">
        <?php for($counter=60;$counter <= 100;){ ?>
            <option value="<?php echo $counter.'%'; ?>"><?php echo $counter."%"; ?></option>
            <?php $counter = $counter+1; } ?>
    </select>

    <div class="shedow_div"></div>
    <div class="append_html">
        <h1 style="text-align: center;"> Please wait... </h1>

    </div>


</div>

</div>


<!-- Scripts -->
<script src="<?php echo Router::url('/js/jquery.js')?>"></script>
<script src="<?php echo Router::url('/js/bootstrap.min.js')?>"></script>
<script>

    $(document).ready(function(){





        load_list();

        var refresh_list_seconds ="<?php echo $refresh_list_seconds; ?>";
        setInterval(function () {
            if(process_running===false){
                load_list();
            }
        },refresh_list_seconds);

        var process_running = false;
        Object.size = function(obj) {
            var size = 0, key;
            for (key in obj) {
                if (obj.hasOwnProperty(key)) size++;
            }
            return size;
        };


        var showloader = false;
        function load_list(){
            var t = "<?php echo base64_encode($thin_app_id); ?>";
            var baseurl ="<?php echo Router::url('/',true);?>";
            $.ajax({
                type:'POST',
                url: baseurl+"tracker/load_pq_tracker",
                data:{t:t},
                beforeSend:function(){
                    process_running = true;
                },
                success:function(data){
                    $(".append_html").html(data);
                    process_running = false;
                },
                error: function(data){
                    process_running = false;
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

            $("#top_address_drp").hide();
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
            $("#top_address_drp").show();
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
                GoInFullscreen($("#element").get(0));
        });

        $(document).on('fullscreenchange webkitfullscreenchange mozfullscreenchange MSFullscreenChange', function() {
            if(IsFullScreenCurrently()) {
                $("#go-button").attr('src',image_path+"small_screen.png");
            }
            else {
                $("#go-button").attr('src',image_path+"large_screen.png");
            }
        });


        blink_interval = setInterval(blink_text, 1000);
        function blink_text() {
            $('.blink').fadeOut(500);
            $('.blink').fadeIn(500);
        }

        /* screen resolution change option */
        function setCookie(cname, cvalue, exdays) {
            var d = new Date();
            d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
            var expires = "expires="+d.toUTCString();
            document.cookie = cname + "=" + cvalue + ";" + expires + ";";
        }
        function getCookie(cname) {
            var name = cname + "=";
            var ca = document.cookie.split(';');
            for(var i = 0; i < ca.length; i++) {
                var c = ca[i];
                while (c.charAt(0) == ' ') {
                    c = c.substring(1);
                }
                if (c.indexOf(name) == 0) {
                    return c.substring(name.length, c.length);
                }
            }
            return "";
        }
        $(document).on("change","#screen_size_drp",function(){
            document.body.style.zoom = $(this).val();
            setCookie("tracker_screen", $(this).val(), 800);
        });

        var screen_size = getCookie("tracker_screen");
        $("#screen_size_drp").val("100%");
        document.body.style.zoom = "100%";
        if(screen_size!=''){
            $("#screen_size_drp").val(screen_size);
            document.body.style.zoom = screen_size;
        }







    });
</script>

</body>
</html>
<div class="footertext">Powered by <img class="logo-mengage" src="https://mengage.in/doctor/images/logo.png">
</div>
<style>
    .footertext img {
        height: 50px;
    }
    .footertext {
        width: 100%;
        position: fixed;
        bottom: 0;
        left: 0;
        text-align: right;
        font-size: 20px;
        font-weight: bold;
    }


    .break_doctor{
        text-align: center;
        /* min-width: 10%; */
        display: block;
        font-size: 2.5rem;
        background: blue;
        color: #fff;
        margin: 0 auto;
        padding: 5px 13px;
        border-radius: 0px 0px 30px 30px;
        width: 40%;
        font-weight: 600;
    }
    .overlay_box_div {
        position: fixed;
        top: 0;
        left: 0;
        height: 100%;
        display: block;
        background: #fff;
        z-index: 99999;
        opacity: 1;
        width: 100%;
        display: none;
    }
    .overlay_box_div .emergency {
        color: red;
        margin: 0px;
        margin-top: 5%;
        font-size: 12rem;
        width: 100%;
        text-align: center;
        opacity: 1;
    }
    .overlay_box_div .emer_patient_name {
        text-align: center;
        width: 100%;
        font-size: 7rem;
        margin-top: 2%;
    }
    .overlay_box_div .emer_patient_name {
        text-align: center;
        width: 100%;
        font-size: 7rem;
        margin-top: 2%;
    }

</style>
