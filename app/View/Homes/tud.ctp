<html>
<head>

    <meta charset="utf-8" />
    <title>Token Update Device</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <meta name="mobile-web-app-capable" content="yes">
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">


    <?php echo $this->Html->script(array('jquery-3.5.1.min.js','bootstrap4.min.js','jquery-confirm.min.js')); ?>
    <?php echo $this->Html->css(array( 'bootstrap4.min.css','font-awesome.min.css','jquery-confirm.min.css'),array("media"=>'all')); ?>


    <style>
        .btn_container{
            margin: 0;
            padding: 0;
        }
        .btn_container li{
            list-style: none;
            float: left;
            width: 33%;
            padding-top: 10px;
            text-align: center;
        }
        .btn_container label{
            display: block;
            text-align: center;
        }

        .display{
            width: 92%;
            text-align: center;
            background: #000;
            margin: 0 auto;
            display: block;
            height: 110px;
            border-radius: 9px;
            border: 5px solid #cfcfcf7a;
        }



        .tud_cover{
            text-align: center;
            background: #c1bcbc5e;
            height: 280px;
            width: 100%;
            float: left;
            border-left: 14px solid #68686882;
            border-bottom: 15px solid #9593935e;
            margin: 44px 0;
            border-radius: 5px 7px 7px 7px;
            box-shadow: 0px 2px 0px #999;
        }

        .display_text{
            color: red;
            font-weight: 600;
            font-size: 5rem;
        }

        header{
            background: blue;
            color: #fff;
            text-align: center;
            width:100%;
            float: left;

            top: 0;
            left: 0;
            font-size: 2rem;
            font-weight: 600;
            padding: 1rem 0;
        }


        .button {
            text-align: center;
            cursor: pointer;
            outline: none !important;
            color: #fff;
            box-shadow:2px 5px 1px #999;
            width: 35px;
            height: 35px;
            border-radius: 64%;
            border: none;
            border-right: 3px solid #60606059;
            border-bottom: 3px solid #60606059;

        }



        .button:active {
            background-color: #e7e7e7;
            outline: none !important;
            box-shadow: 2px 5px 1px #666;
            transform: translateY(4px);
        }


        .btn3d {
            position: relative;
            top: -6px;
            border: 0;
            transition: all 40ms linear;
            margin-top: 10px;
            margin-bottom: 10px;
            margin-left: 2px;
            margin-right: 2px;
            width: 35px;
            height: 35px;
            border-radius: 35px;
        }

        .btn3d:active:focus,
        .btn3d:focus:hover,
        .btn3d:focus {
            -moz-outline-style: none;
            outline: medium none;
        }

        .btn3d:active,
        .btn3d.active {
            top: 2px;
        }

        .btn3d.btn-white {
            color: #666666;
            box-shadow: 0 0 0 1px #ebebeb inset, 0 0 0 2px rgba(255, 255, 255, 0.10) inset, 0 8px 0 0 #f5f5f5, 0 8px 8px 1px rgba(0, 0, 0, .2);
            background-color: #fff;
        }

        .btn3d.btn-white:active,
        .btn3d.btn-white.active {
            color: #666666;
            box-shadow: 0 0 0 1px #ebebeb inset, 0 0 0 1px rgba(255, 255, 255, 0.15) inset, 0 1px 3px 1px rgba(0, 0, 0, .1);
            background-color: #fff;
        }

        .btn3d.btn-default {
            color: #666666;
            box-shadow: 0 0 0 1px #ebebeb inset, 0 0 0 2px rgba(255, 255, 255, 0.10) inset, 0 8px 0 0 #BEBEBE, 0 8px 8px 1px rgba(0, 0, 0, .2);
            background-color: #f9f9f9;
        }

        .btn3d.btn-default:active,
        .btn3d.btn-default.active {
            color: #666666;
            box-shadow: 0 0 0 1px #ebebeb inset, 0 0 0 1px rgba(255, 255, 255, 0.15) inset, 0 1px 3px 1px rgba(0, 0, 0, .1);
            background-color: #f9f9f9;
        }

    </style>


</head>

<body >
<header>
    Digital Tud
</header>
<div class="container">

    <div class="raw">
        <div class="col-sm-12 col-lg-12 col-md-12">
            <div class="tud_cover">

               <table style="width: 100%;">
                   <tr><td>

                           <ul class="btn_container">



                               <li>
                                   <button data-type="prev" class="btn3d btn btn-default btn-lg" type="button"></button>
                                   <label>Prev</label>
                               </li>
                               <li>
                                   <button data-type="close" class="btn3d btn btn-default btn-lg" type="button"></button>
                                   <label>Close</label>
                               </li>

                               <li>
                                   <button data-type="next" class="btn3d btn btn-default btn-lg" type="button"></button>
                                   <label>Next</label>
                               </li>
                           </ul></td></tr>
                   <tr>
                       <td>
                           <div class="display">
                               <label class="display_text">1</label>
                           </div>
                       </td>
                   </tr>

               </table>




            </div>
        </div>
    </div>
</div>
</body>

<script>
    $(function () {
        var baseUrl = "<?php echo Router::url('/',true); ?>";
        $(document).on("click",".btn_container button",function(){
            var number = parseInt($(".display_text").text());
            var type = $(this).attr('data-type');
            var audioElement = document.createElement('audio');
            if(type=='next'){
                $(".display_text").html(number+1);
                audioElement.setAttribute('src', baseUrl+'voice_anouncement/next.mp3');
            }else if(type=='prev'){
                if(number > 1){
                    $(".display_text").html(number-1);
                    audioElement.setAttribute('src', baseUrl+'voice_anouncement/prev.mp3');
                }else{
                    $(".display_text").html(number);
                    
                }
            }else if(type=='close'){
                $(".display_text").html(0);
                audioElement.setAttribute('src', baseUrl+'voice_anouncement/close.mp3');
            }


            audioElement.play();


        });

    });
</script>







</html>


