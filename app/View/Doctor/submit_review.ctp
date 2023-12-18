<html>
<head id ="row_content" class="row_content" >
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <meta name="mobile-web-app-capable" content="yes">
    <meta http-equiv="cache-control" content="no-store"/>
    <meta http-equiv="expires" content="0"/>
    <meta http-equiv="pragma" content="no-cache"/>
    <meta name="author" content="mengage">
      <?php echo $this->Html->script(array('jquery-3.5.1.min.js','popper.min.js','bootstrap4.min.js','sweetalert2.min.js','star.js','flash.js')); ?>
    <?php echo $this->Html->css(array( 'bootstrap4.min.css','font-awesome.min.css','doctor.css?'.date('his'),'sweetalert2.min.css'),array("media"=>'all')); ?>
    <title><?php echo $doctor_data['doctor_name']; ?></title>
    <style>



        #error_message{
            font-size: 1rem;
            display: block;
            width: 100%;
            text-align: center;
            color: red;
        }


        .star_span{
            font-size: 3rem;
            cursor: pointer;
            color: grey;
        }

        .checked {
            color: orange;
        }

        header{
            position: fixed;
            width: 100%;
            z-index: 99999999999999;
            display: grid;
        }

        #dashboard_box{
            margin-top: 17%;
            float: left;
            width: 100%;
            display: block;
        }


        .banner_lbl{
            text-align: center;
            font-size: 0.6rem;
            text-align: center;
            width: 100%;
            font-weight: 600;
            text-decoration: underline;
            text-transform: uppercase;
            color: green;
        }

        .navigation-arrow-left {
            animation: slide1 1s ease-in-out infinite;
            color: #1bb518;
            font-size: 1.5rem;
            padding: 1rem 0;
            float: left;

        }
        @keyframes slide1 {
            0%,
            100% {
                transform: translate(0, 0);
            }

            50% {
                transform: translate(5px, 0px);
            }
        }

        .upload_btn_span{
            border: 1px solid;
            padding: 10px;
            border-radius: 4px;
            background: #1b8c1b;
            color: #fff;

        }


        .upload_btn_span{
            border: 1px solid;
            padding: 10px;
            border-radius: 4px;
            background: #1b8c1b;
            color: #fff;

        }

        .append_health_tip, .bottom_ul{
            margin: 0;
            padding: 0;
            width: 97%;
            float: left;
        }
        .append_health_tip li table td{
            padding: 5px !important;
        }
        .append_health_tip li{
            list-style: none;
            width: 100%;
            padding: 2px;
            float: left;

            margin: 10px 5px;
        }

        .bottom_ul li{
            list-style: none;
            width: 30%;
            float: left;
            padding: 0;
            text-align: center;
        }



        .login_box{
            padding: 40px 30px;
        }
        .login_box h3, .login_box p{
            text-align: center;
        }
        .login_box label{
            font-weight: 600;
            padding: 5px 0px;
        }
        .login_box p{
            border-bottom: 3px solid #013ec442;
            padding: 8px;
            font-size: 0.8rem;
        }


        .loader_image{
            float: right;
            position: relative;
            right: 10px;
            width: 20px;
            height: 20px;
            top: -30px;
        }
        .login_screen_img{
            display: block;
            margin: 0 auto;
            width: 120px;
            height: 120px;
            border: 1px solid #f1f1f1;
            border-radius: 58px;
            padding: 4px;
        }

    </style>



</head>

<body  id="body">
<header>
    <div class="row block detail_main_box" >
        <div class="row top_header_row">
            <div class="col-2 top_photo_box">
                <img src="<?php echo $doctor_data['logo']; ?>" />
            </div>
            <div class="col-10" style="">
                <div class="doctor-detail-container" >
                    <h1 style="font-size: 2.1rem;" class="doctor-name"><?php echo $doctor_data['app_name']; ?></h1>

                </div>
            </div>
        </div>
    </div>
</header>

<div id="dashboard_box" style="margin-top: 7%; padding: 38px 10px;" class="login_box">
    <div class="row">
        <div class="col-12">
            <h4 style="text-align: center;width: 100%; font-weight: 500;">Review For <span style="font-size: 1.8rem;font-weight: 600; display: block;color: #3cb40a;width: 100%;"><?php echo $doctor_data['doctor_name']; ?></span></h4>
            <img class="login_screen_img" src="<?php echo !empty($doctor_data['profile_photo'])?$doctor_data['profile_photo']:$doctor_data['logo']; ?>" >
            <p><?php echo $doctor_data['category_name']; ?></p>
            <div class="form-group">
                <div class="rating" style="text-align: center;">
                    <?php for($stars =1; $stars <=5; $stars++){ ?>
                        <span data-number="<?php echo $stars; ?>" class="star_span fa fa-star <?php echo ($stars <= $doctor_data['total_star'])?'checked':''; ?>"></span>
                    <?php } ?>
                </div>
            </div>

            <?php if($doctor_data['review_given']=='NO'){ ?>

                <div class="form-group">
                    <label>Write us your review</label>
                    <textarea class="form-control" id="review" rows="5"><?php echo @$doctor_data['review']; ?></textarea>
                </div>


                <div class="form-group">
                    <h4 id="error_message"></h4>
                    <button style="width: 80%;margin: 0 auto;display: block;" id="sub_btn" type="button" class="btn btn-success">Submit</button>
                </div>
            <?php }else{ ?>

                <div class="form-group">
                    <label>Your Review</label>
                    <p class="form-control" style="text-align: left;font-size: 0.9rem;border: none;" ><?php echo @$doctor_data['review']; ?></p>
                </div>


                <div class="form-group">
                    <p>Review posted on <i class="fa fa-calendar"></i> <?php echo date("d M, y H:i",strtotime($doctor_data['review_datetime'])); ?> </p>
                </div>
            <?php } ?>
        </div>


    </div>
</div>



    <script>


        function clearMessage(){
            $("#error_message").html('');
        }

        $(function () {
            var active_number = 0;
            $(document).on("click",".star_span",function () {
                    active_number= parseInt($(this).attr('data-number'));
                    clearMessage();
                    $(this).closest(".rating").find(".star_span").removeClass('checked');
                    $(".star_span").each(function (index, value) {
                        var active= parseInt($(this).attr('data-number'));
                        if(active <= active_number){
                            $(this).addClass("checked");
                        }
                    });
            });



            $('#review').bind('input propertychange', function() {

                clearMessage();
            });





            $(document).on("click","#sub_btn",function () {

                var $btn = $(this);
                var review = $("#review").val();
                if(active_number==0){
                    flashMessage('error',"Please give star rating.");
                }else if(review==""){
                    flashMessage('error',"Please enter your review.");
                }else{

                    var di = "<?php echo $doctor_id; ?>";
                    var m = "<?php echo $mobile; ?>";
                    var un = "<?php echo $user_name; ?>";
                    $.ajax({
                        type:'POST',
                        url: "<?php echo Router::url('/',true); ?>doctor/save_doctor_review",
                        beforeSend:function(){
                            $btn.button('loading').html('Please wait....');
                        },
                        data:{di:di,m:m,un:un,star:active_number,review:review},
                        success:function(response){
                            response = JSON.parse(response);
                            if(response.status==1){

                                $btn.hide();
                                flashMessage('success',response.message);
                                setTimeout(function () {
                                    window.location.reload();
                                },1000)
                            }else{
                                $btn.button('reset');
                                flashMessage('error',response.message);
                            }
                        },
                        error: function(data){

                            $btn.button('reset');
                        }




                    });

                }
            });




        });
    </script>

</body>
</html>

