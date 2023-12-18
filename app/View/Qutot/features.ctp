<!DOCTYPE html>
<!--[if IE 8 ]><html class="ie" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="en-US"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="en-US"><!--<![endif]-->
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="author" content="QuToT">
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />






    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script>
        var baseUrl = '<?php echo Router::url('/',true); ?>';

    </script>

    <?php echo $this->Html->css(array('jquery.typeahead.css')); ?>
    <?php echo $this->Html->script(array('popper.min.js','jquery.js','bootstrap4.5.3.min.js','jquery.typeahead.js','firebase-app.js','firebase-messaging.js')); ?>
    <?php echo $this->Html->css(array( 'bootstrap4.min.css','font-awesome.min.css','bootstrap.min.css','qutot.css?'.date('ymdhis') ),array("media"=>'all')); ?>
    <title>Features</title>
    <style>
        .top_heading h1, .top_heading p{
            width: 100%;
            text-align: center;
        }

        .card img{
            width: 100%;
            border-radius: 0px !important;
            height: 100%;
        }
        .feat_heading{
            text-align: center;
            width: 100%;
            color: #ff8d00;
        }
        .card{
            box-shadow: 1px 3px 9px #cccaca;
        }
        .col-12{
            margin-bottom: 35px;
        }
        .card p{
            font-size: 1.7rem;
        }
        .card-body{
            padding: 0.9rem 0.9rem;

        }
        .card-title{
            font-weight: 600;
        }
        .back_arrow{
            float: left;
            padding: 0px 1rem;
            position: absolute;
            left: 0;
            color: orange;
        }
    </style>
</head>
<body>


<div class="main_container">

    <div class="row top_heading">
        <h1 class="large_heading qutot_font"><a href="<?php echo Router::url('/',true); ?>"><i class="fa fa-long-arrow-left back_arrow"></i></a> QueueToT</h1>
        <p class="sub_heading">One of it's kind worlds first ever Virtual Queue Token Tracker. </p>
    </div>

    <div class="row" style="float: left;">
        <h2 class="feat_heading">Features</h2>
        <div class="col-12">
            <div class="card">
                <img src="<?php echo Router::url('/qutot_row/features/virtual_queue_management.png')?>" class="card-img-top" alt="...">
                <div class="card-body">
                    <h3 class="card-title">Virtual Queue Management System:</h3>
                    <p class="card-text">Allows Queuing virtually from the comfort of home while being in the Queue same time.</p>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card">
                <img src="<?php echo Router::url('/qutot_row/features/single_press.jpg',true)?>" class="card-img-top" alt="...">
                <div class="card-body">
                    <h3 class="card-title">Single Step Etoken Management and live Tracking of Token:</h3>
                    <p class="card-text">Extremely simple method of self token generation and live tracking by user.</p>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card">
                <img src="<?php echo Router::url('/qutot_row/features/vending.jpg',true)?>" class="card-img-top" alt="...">
                <div class="card-body">
                    <h3 class="card-title">E-token Dispensing Vending Device:</h3>
                    <p class="card-text">A  wall mountable extremely simple self etoken generation by users.</p>
                </div>
            </div>
        </div>


        <div class="col-12">
            <div class="card">
                <img src="<?php echo Router::url('/qutot_row/features/easy_integration.jpg',true)?>" class="card-img-top" alt="...">
                <div class="card-body">
                    <h3 class="card-title">Ease of Integration of Queue System:</h3>
                    <p class="card-text">Helps seamless and easy integration with existing IT System of clients </p>
                </div>
            </div>
        </div>


        <div class="col-12">
            <div class="card">
                <img src="<?php echo Router::url('/qutot_row/features/easy_implementation.jpg',true)?>" class="card-img-top" alt="...">
                <div class="card-body">
                    <h3 class="card-title">Ease of implementation:</h3>
                    <p class="card-text">Extremely simple to configure and define counter across multiple domains in matter of few minutes </p>
                </div>
            </div>
        </div>


        <div class="col-12">
            <div class="card">
                <img src="<?php echo Router::url('/qutot_row/features/multiple_way_token_booking.png',true)?>" class="card-img-top" alt="...">
                <div class="card-body">
                    <h3 class="card-title">Supports multiple channels of booking token:</h3>
                    <p class="card-text">A complete 24/7 Queue Automation Solution reduce staff salaries of Reception or assistant for generating tokens and managing queues through wallk-in, online app, IVR, etc. </p>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card">
                <img src="<?php echo Router::url('/qutot_row/features/happy_customer.jpg',true)?>" class="card-img-top" alt="...">
                <div class="card-body">
                    <h3 class="card-title">Enhanced Customer Experience:</h3>
                    <p class="card-text">Leaves cool First Impression on users mind. Gives ambience of Airport Lounge waiting with Live TV Queue Tracker Screen.</p>
                </div>
            </div>
        </div>



    </div>


</div>




</body>



<script type="text/javascript">
    $(function () {



    });
</script>

</html>