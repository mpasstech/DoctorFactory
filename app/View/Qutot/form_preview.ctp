<html>
<head>
    <title>Token Booking</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <meta name="mobile-web-app-capable" content="yes">
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" href="<?php echo Router::url('/css/moq_css.css?'.date('his'),true)?>" />
    <meta name="apple-mobile-web-app-status-bar" content="#5D54FF" />
    <meta name="theme-color" content="#5D54FF" />
    <?php echo $this->Html->script(array('jquery-3.5.1.min.js','popper.min.js','bootstrap4.min.js','jquery-confirm.min.js')); ?>
    <?php echo $this->Html->css(array( 'bootstrap4.min.css','font-awesome.min.css','jquery-confirm.min.css'),array("media"=>'all')); ?>

    <style>
        .buton_box button{
            margin: 1px !important;
            font-size: 0.9rem !important;
        }
        .btn_btt{
            float: left;
        }
        .info_msg{
            color: #0f5a1f;
            font-size: 0.7rem;
            text-align: center;
            width: 100%;
            display: block;
            padding: 2px 0px;
            font-weight: 600;
        }

        .status_icon{
            border: 1px solid;
            color: green;
            padding: 3px 5px;
            display: block;
            border-radius: 20px;
            font-size: 0.6rem;
            font-weight: 600;
            margin: 0 auto;
            width: 80%;
            background: transparent;
        }
        #btnSave{
            position: fixed;
            left: 0;
            bottom: 0;
            color: #5D54FF;
            background: #fff;
            border-radius: 0px;
            outline: none;
            padding: 5px 2px;
            width: 98%;
            margin: 2% 1%;
            border: none;
            border-top: 2px solid;

        }

        #btnSave img{
            width: 25px;
            height: 25px;
            display: inline-flex;
            margin-top: -3px;
            position: absolute;
            left: 23px;

        }

        .jconfirm-content{
            float: left;

        }
        .jconfirm-content ul{
            margin: 0;
            padding: 0;
        }
        .jconfirm-content ul li{
            float: left;
            width: 19%;
            list-style: none;
            font-size: 0.7rem;
            text-align: center;
            border: 1px solid #cfcfcf;
            margin: 0.2rem 0.1rem;
        }
        .BOOKED{
            background: red;
            color: #fff;
        }
        .selected_time{
            background: #1426ffd9;
            color: #fff;
        }
    .main_box{
        float: left;
        width: 100%;
        display: block;
        margin: 0 !important;
        padding: 0;

    }

     #list_append{
         text-align: center;
     }

        .box_heading{
            font-size: 1.5rem;
            width: 100%;
            text-align: center;
            border-bottom: 1px solid rgb(210 210 210);
            padding-bottom: 7px;
            margin: 8px 0.5rem;
        }
        .button span{
            display: block;
            width: 100%;
            text-align: center;
        }
        .counter_container{
            width: 100%;
            padding: 0.5rem;
        }
        .counter_container .jconfirm-content{
            float: left;
            display: block;
            width: 100%;
        }
        .counter_container label{
            float: left;
            display: block;
            width: 100%;
            padding: 0.3rem;
            border: 1px solid #d2cdcd;
            border-radius: 14px;
        }
        .counter_container label input{
            float: left;
            width: 15px;
            height: 15px;
            border: 0px solid #000;
            margin: 0.3rem 0.6rem;
        }

        #profile_sec{
            min-height: 350px;


        }

        .whats_btn, .start_video_call, .start_audio_call{
            float: left;
        }
    </style>
</head>
<?php

$background = Router::url('/opd_form/css/backgound.png',true);
$background = "background: url('$background');";
 if(empty($data)){
     $background ='overflow:hidden;background:none;';
 }

 $single_field = 'YES';

?>

<body  style="display: none; <?php echo $background; ?>;">

<?php if(!empty($data)){ ?>
<header>
    <h3 style="text-align: center;">
        <img id="logo_image" src="<?php echo $data[0]['profile_photo']; ?>" alt="Logo Image" />
        Daily Token
        <i id="load_setting"  class="fa fa-gears" style="float:right;"></i>
    </h3>
</header>
<?php } ?>
<div class="container-fluid">
    <div class="main_box">
        <div id="token_list_sec" style="display: blockk;margin-left: 0;margin-right: 0;" class="row section_box">
            <h1 class="top_heading">Token List </h1>

            <div class="col-12" style="padding: 0;">
                <table id="customers" class="table table-bordered">
                    <thead>
                    <tr>
                        <th>S.no</th>
                        <th style="width: 35% !important;">Name <br> Mobile</th>
                        <th>Token</th>
                        <th>Status</th>
                        <th style="width: 20% !important;">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php for($counter=1;$counter<5;$counter++) { ?>
                        <tr>
                            <td><?php echo $counter; ?></td>
                            <td>
                                Patient <?php echo $counter; ?>
                                <br>
                                <br>
                                9999999999
                            </td>
                            <td style="text-align: center;"><?php echo rand(1,99); ?></td>
                            <td>
                                <?php if($counter/2==0){ ?>
                                    <span class="status_icon">Video</span>
                                <?php }else if($counter%2==0){ ?>
                                    <span class="status_icon" style="color: #ffc107;" >Audio</span>
                                <?php } ?>
                                <span class="status_td">Booked</span>
                            </td>
                            <td class="btn_td">
                                    <a  target="_blank" class="btn btn-success whats_btn" href="javascript:void(0);" ><i class="fa fa-whatsapp" aria-hidden="true"></i></a>

                                <?php if(($counter/2)==0){ ?>
                                    <button class="btn btn-success start_video_call"  ><i class="fa fa-video-camera" aria-hidden="true"></i></button>
                                <?php }else if($counter%2==0){ ?>
                                    <button type="button" class="btn btn-xs btn-warning start_audio_btn"><i class="fa fa-phone"></i></button>
                                <?php } ?>
                                    <div class="btn-group dropleft">
                                        <button type="button" class="drop_action_btn btn btn-success" >
                                            More
                                        </button>
                                    </div>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
            <div class="col-12 buton_box">
                <button  class="button btn_btt btn btn-xs" id="refresh_token_btn"><i class="fa fa-refresh" aria-hidden="true"></i> Refresh Token</button>
                <button  class="button  btn_btt btn btn-xs" id="back_token_btn"><i class="fa fa-ticket" aria-hidden="true"></i> Book Token</button>
            </div>
        </div>
        </div>
    </div>
</div>
</body>

<script>
    $( document ).ready(function() {


    });
</script>
</html>


