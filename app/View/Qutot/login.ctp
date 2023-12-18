<!DOCTYPE html>
<!--[if IE 8 ]><html class="ie" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="en-US"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="en-US"><!--<![endif]-->
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="author" content="mengage">
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />

    <meta name="description" content="QuToT, QuToT xyz, QuToT xyz jaipur, QuToT, Virtual Queue Automation">
    <meta name="keywords" content="QuToT">
    <meta name="author" content="mengage.in">

    <meta name="apple-mobile-web-app-status-bar" content="#547DBF" />
    <meta name="theme-color" content="#547DBF" />


    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script>
        var baseUrl = '<?php echo Router::url('/',true); ?>';

    </script>

    <?php echo $this->Html->css(array('jquery.typeahead.css')); ?>
    <?php echo $this->Html->script(array('popper.min.js','jquery.js','bootstrap4.5.3.min.js','jquery.typeahead.js','firebase-app.js','firebase-messaging.js')); ?>

    <?php echo $this->Html->css(array( 'bootstrap4.min.css','font-awesome.min.css','bootstrap.min.css' ),array("media"=>'all')); ?>
    <title>QuToT</title>

    <style>

        .login_form{
            width: 100%;
        }
        *{
            /*border: 1px solid red;*/
        }



        .sub_heading{
            font-size: 4rem;
            text-align: center;
            width: 100%;
        }

        .sub_main_heading{
            font-size: 4.3rem;
            width: 100%;
        }

        body{
            background: linear-gradient(#fff, #f4f9ff);
            background-repeat: no-repeat;
            min-height: 720px;
        }
        #logo{
            text-align: center;
        }
        .desclaimer p{
            font-size: 9px;
        }
        .doctor_lbl a{
            color: #547DBF;
            padding: 0.7rem;
            border: 1px solid;
            border-radius: 30px;
            margin: 0.3rem 0;
            text-decoration: none;
            cursor: pointer;
        }

        .dynamic_width{
            width: 50%;
        }

        ::placeholder { /* Chrome, Firefox, Opera, Safari 10.1+ */
            color: #8a8a8a;
            opacity: 1; /* Firefox */
        }

        :-ms-input-placeholder { /* Internet Explorer 10-11 */
            color: #8a8a8a;
        }

        ::-ms-input-placeholder { /* Microsoft Edge */
            color: #8a8a8a;
        }


        .container{
            margin: 1% auto;
        }


        .main_grid div[class*="col-"]{
            margin: 0;
            padding: 0;
        }

        .large_heading{
            text-align: center;
            width: 100%;
            display: block;
            color: #547DBF;

            font-weight: 500;
            font-size: 15.5rem;
            font-family: emoji;


        }
        .large_heading span{
            font-size: 6rem;
            font-weight: 600;
        }





        .search_box_container .dropdown-menu{
            width: 100%;
            border-radius: 0px 0px 5px 5px;
        }
        .search_box_container .dropdown-item{
            font-size: 1.8rem;
            border-bottom: 1px solid #eee;
            padding: 1%;
        }

        .search_box_container .dropdown-item:focus{
            outline: none;
        }




        .row{
            margin-right: 0px !important;
            margin-left: 0px !important;
        }



        .second_pera p{
            font-size: 2rem;
        }



        @media only screen and (min-width: 320px) and (max-width: 430px) {
            .search_box {
                width: 100%;
                padding: 2% 2% 2% 11% !important;
                font-size: 1.8rem !important;
                border: 2px solid !important;

            }

            .typeahead__item li label{
                font-size: 1.5rem !important;
            }

            .fa-search{
                font-size: 2rem !important;
            }

            .typeahead__item .app_name{
                font-size: 1.3rem !important;
            }

            .bottom_sub_heading{
                font-size: 1.5rem;
            }

            .qutot_now_btn {
                font-size: 2rem !important;
                padding: 2% !important;
            }

            .btn-search{
                font-size: 1rem;
            }
            .large_heading{
                font-size: 5.5rem;
                margin:6%;
            }

            .sub_heading {
                font-size: 2rem;
            }
            .sub_main_heading {
                font-size: 2.8rem;
            }
            .second_pera p{
                font-size: 1rem;
            }
            .main_container{
                width: 100% !important;
            }

            .large_heading span{
                font-size: 3rem;
            }


            .whats_app, .whats_app:hover{
                font-size: 3.5rem;
                height: 50px;
                width: 50px;
            }
            .whats_app_row{
                margin-top: 4%;
            }
            .doctor_lbl{
                font-size: 1.7rem;
            }
            .doctor_lbl a{
                display: block;;
            }
            .info{
                font-size: 1.6rem !important;
            }
            .last_img{
                display: none;
            }
            .search_box_container .dropdown-item .category_name{
                font-size: 1rem !important;
            }
            .search_box_container .dropdown-item .city_name, .search_box_container .dropdown-item .app_name{
                font-size: 1rem;
            }
            .search_box_container .dropdown-item li:first-child, .search_box_container .dropdown-item li:last-child{
                font-size: 1.2rem;
            }
        }


        @media (min-width: 481px) and (max-width: 767px) and (orientation: portrait){
            .search_box{
                font-size: 1.8rem !important;
            }
            .btn-search{
                font-size: 1.8rem;
            }

            .last_img {
                width: 35%;
                bottom: 15%;
                left: 9%;
            }

        }

        @media (min-width: 481px) and (max-width: 767px) and (orientation: landscape){
            .search_box{
                font-size: 1.8rem !important;
            }
            .btn-search{
                font-size: 1.8rem;
            }

            .last_img {
                width: 37%;
                bottom: -20%;
                left: 1%;
            }
            .whats_app_row{
                margin-top: 10%;
            }
            #header{
                margin-bottom: 20%;
            }

        }

        @media (min-width: 768px) and (max-width: 1024px) and (orientation: landscape) {
            .search_box{
                font-size: 1.8rem !important;
            }
            #header{
                margin-bottom: 20%;
            }
            .last_img {
                width: 40%;
                /* display: block; */
                bottom: 17%;
                position: absolute;
                left: 4%;
                opacity: 0.8;
                z-index: -99999;
                transform: rotate(351deg);
            }
            .whats_app_row{
                margin-top: 17%;
            }

        }
        @media (min-width: 768px) and (max-width: 1024px) and (orientation: portrait) {

            .last_img{
                display: none;
            }

            #header{
                margin-bottom: 20%;
            }

        }



        .fa-search{
            margin: 2% 2%;
            position: absolute;
            font-size: 4rem;
            height: 100%;

            z-index: 999999;
        }

        body{
            border-top: 3px solid blue;
        }

        .main_container{

            width: 70%;
            display: table;
            margin: 0px auto;
            /* text-align: center; */
            padding: 2% 2%;
            position: relative;
        }
        .qutot_now_btn{
            font-size: 3.5rem;
            padding: 1%;
        }

        .typeahead__cancel-button{
            display: none;
        }

        .background_box{
            background-size: cover !important;
            background-repeat: no-repeat !important;
            min-height: 360px !important;
            opacity: 0.5;
            width: 100%;
            height: 100%;
            position: absolute;
            background: transparent;
            z-index: -99;

        }
        form{
            width: 100%;
        }
    </style>



</head>
<body>


<div class="main_container">

    <div class="row">
        <h1 class="large_heading">QueueToT</h1>
        <p class="sub_heading">Login Your Dashboard here</p>
    </div>

    <div class="row ">
        <?php echo $this->Form->create('User',array('method'=>'post','id'=>'login_form','class'=>'contact-form')); ?>
        <div class="col-12">
            <label>Username</label>
            <?php echo $this->Form->input('mobile',array("autocomplete"=>"off",'id'=>'mobile','type'=>'text','placeholder'=>'Enter username','label'=>false,'class'=>'form-control cnt')); ?>
       </div>

        <div class="col-12">
            <label>Password</label>
            <?php echo $this->Form->input('password',array("autocomplete"=>"off",'id'=>'password','type'=>'password','placeholder'=>'Enter your password','label'=>false,'class'=>'form-control cnt')); ?>
        </div>
            <div class="col-12" style="text-align: center;">
                <button type="submit" class="btn btn-success">Login</button>
            </div>
        <?php echo $this->Form->end(); ?>
    </div>
    <div class="row">
        <div class="col-12" style="text-align: center;width: 100%;margin: 15px;">
            <?php echo $this->element('message'); ?>
        </div>
    </div>
</div>




</body>



<script type="text/javascript">
    $(function () {
            var baseUrl = "<?php echo Router::url('/',true); ?>";
          /*  $(document).on('submit','.login_form',function(e){
                e.preventDefault();
                $.ajax({
                    type:'POST',
                    url: baseUrl+"qutot/login",
                    data:{username:$("#username").val(),password:$("#password").val()},
                    beforeSend:function(){

                    },
                    success:function(result){
                        result = JSON.parse(result);
                        if (result.status == 1) {
                            window.location.href = baseUrl+"qutot/dashboard";
                        }else{
                            alert(result.message);
                        }
                    },
                    error: function(data){

                        alert("Sorry something went wrong on server.");
                    }
                });
            });
*/

    });
</script>

</html>