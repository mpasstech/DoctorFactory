<html xmlns="http://www.w3.org/1999/xhtml">
<head id ="row_content" class="row_content" >
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="theme-color" content="#7f0b00">
    <meta http-equiv="cache-control" content="no-store"/>
    <meta http-equiv="expires" content="0"/>
    <meta http-equiv="pragma" content="no-cache"/>
    <meta name="author" content="mengage">
    <script>
        var baseUrl = '<?php echo Router::url('/',true); ?>';
    </script>
    <?php echo $this->Html->script(array('jquery-3.5.1.min.js','popper.min.js','bootstrap4.min.js','sweetalert2.min.js')); ?>
    <?php echo $this->Html->css(array( 'bootstrap4.min.css','font-awesome.min.css','sweetalert2.min.css' ),array("media"=>'all')); ?>

    <title>Video End</title>
    <style>
        body{
            font-size: 1.3rem;
            background: #f7f7f7;
        }
        .firs_row{
            border-bottom: 1px solid #dcdbdb;
            padding-bottom: 10px;
            margin-top: 30px;


        }
        .firs_row label{
            text-align: left !important;
        }

        .third_row p{
            font-size: 1.1rem;
            text-align: center;
        }
        .third_row{
            margin-top: 23px;
            border-top: 1px solid #dcdbdb;
            padding-top: 9px;
        }
        .container{
            padding: 0px 0px;
            background: #fff;
            margin: 0px;
            display: block;

            width: 100%;

        }
        .row{
            margin-top: 15px;
            width: 100%;
            margin-right: 0px !important;
            margin-left: 0px !important;
        }
        .swal2-modal{
            padding: 0px 0px 20px 0px !important;
        }
        img{
            width: 80px;
            height: 80px;
            border-radius: 42px;
            position: relative;
            margin: 0;
        }
        .second_row label{
            font-weight: 600;
            text-align: center;
            width: 100%;
            display: block;
        }

        .second_row div{
            text-align: center;
            width: 100%;
            display: block;
        }
        .middle_box{
            border-right: 1px solid;
            border-left: 1px solid;
            border-color: #e4e2e2;
            margin: 0px;
            padding: 0px;
        }

        .success-box .swal2-title{
            font-size: 25px;
            background: #0080ff;
            color: #fff;
            padding: 10px 0px;
        }

    </style>
</head>
<body>



<script>
    $(function () {


        function disableBack() { window.history.forward() }

        window.onload = disableBack();
        window.onpageshow = function(evt) { if (evt.persisted) disableBack() }

        swal({
            type:'success',
            title: "Video Call Ended",
            html: "Thankyou for connect with us",
            showCancelButton: false,
            showConfirmButton: false,
            customClass:"ssuccess-box",
            allowOutsideClick: false
        });



        setTimeout(function () {
            window.close();
        },2000);

    });



</script>

</body>
<html>


