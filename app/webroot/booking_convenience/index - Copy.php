<?php
error_reporting(1);

ini_set('display_errors', 0);
$baseUrl = "https://mengage.in/doctor/";
$survey_data = array(
    'step_1'=>array(
        'question'=>"Symptoms",
        'subtitle'=>"<ul><li>Severe, constant chest pain or pressure</li><li>Extreme difficulty breathing</li><li>Severe, constant lightheadedness</li><li>Serious disorientation or unresponsiveness</li></ul>",
        'option'=>array(
            array('text'=>"I’m experiencing at least one of these symptoms",'single'=>true,'report'=>'report_1'),
            array('text'=> "I do not have any of these symptoms",'single'=>true)
        ),
        'type'=>'radio',
        'next'=>'2',
        'prev'=>'0'
    ),
    'step_2'=>array(
        'question'=>"How old are you?",
        'option'=>array(
            array('text'=>"Under 18",'single'=>true,'report'=>'report_2'),
            array('text'=>"Between 18 and 64",'single'=>true),
            array('text'=>"65 or older",'single'=>true)
        ),
        'type'=>'radio',
        'next'=>'3',
        'prev'=>'1'
    ),
    'step_3'=>array(
        'question'=>"Are you experiencing any of these symptoms?",
        'subtitle'=>"Select all that apply",
        'option'=>array(
            array('text'=> "Fever, chills, or sweating",'single'=>false),
            array('text'=> "Difficulty breathing (not severe)",'single'=>false),
            array('text'=> "New or worsening cough",'single'=>false),
            array('text'=> "Aching throughout the body",'single'=>false),
            array('text'=> "Vomiting or diarrhea",'single'=>false),
            array('text'=> "None of the above",'single'=>true)

        ),
        'type'=>'checkbox',
        'next'=>'4',
        'prev'=>'2'
    ),
    'step_4'=>array(
        'question'=>"Do any of these apply to you?",
        'subtitle'=>"Select all that apply",
        'option'=>array(

            array('text'=> "Moderate to severe asthma or chronic lung disease",'single'=>false),
            array('text'=> "Cancer treatment or medicines causing immune suppression",'single'=>false),
            array('text'=> "Inherited immune system deficiencies or HIV",'single'=>false),
            array('text'=> "Serious heart conditions, such as heart failure or prior heart attack",'single'=>false),
            array('text'=> "Diabetes with complications",'single'=>false),
            array('text'=> "Kidney failure that needs dialysis",'single'=>false),
            array('text'=> "Cirrhosis of the liver",'single'=>false),
            array('text'=> "Diseases or conditions that make it harder to cough",'single'=>false),
            array('text'=> "Extreme obesity",'single'=>false),
            array('text'=> "Pregnancy",'single'=>false),
            array('text'=> "None of the above",'single'=>true),
        ),
        'type'=>'checkbox',
        'next'=>'5',
        'prev'=>'3'
    ),
    'step_5'=>array(
        'question'=>"In the last 14 days, have you traveled internationally?",
        'option'=>array(
            array('text'=>  "I have traveled internationally",'single'=>true),
            array('text'=>  "I have not traveled internationally",'single'=>true)

        ),
        'type'=>'radio',
        'next'=>'6',
        'prev'=>'4'
    ),
    'step_6'=>array(
        'question'=>"In the last 14 days, have you been in an area where COVID‑19 is widespread?",
        'subtitle'=>"Select all that apply",
        'option'=>array(
            array('text'=> "I live in an area where COVID‑19 is widespread",'single'=>false),
            array('text'=> "I have visited an area where COVID‑19 is widespread",'single'=>false),
            array('text'=> "I don’t know",'single'=>true),
            array('text'=> "None of the above",'single'=>true),
        ),
        'type'=>'checkbox',
        'next'=>'7',
        'prev'=>'5'
    ),
    'step_7'=>array(
        'question'=>"In the last 14 days, what is your exposure to others who are known to have COVID‑19?",
        'subtitle'=>"Select all that apply",
        'option'=>array(
            array('text'=> "I live with someone who has COVID‑19",'single'=>false),
            array('text'=> "I’ve had close contact with someone who has COVID‑19",'desc'=>"I was within 6 feet of someone who’s sick, or I was exposed to a cough or sneeze." ,'single'=>false),
            array('text'=>  "I’ve been near someone who has COVID‑19",'desc'=>"I was at least 6 feet away and was not exposed to a sneeze or cough." ,'single'=>false),
            array('text'=> "I’ve had no exposure",'desc'=>"I have not been in contact with someone who has COVID‑19." ,'single'=>true),
            array('text'=> "I don't know",'single'=>true),
        ),
        'type'=>'checkbox',
        'next'=>'8',
        'prev'=>'6'
    ),
    'step_8'=>array(
        'question'=>"Do you live in a care facility?",
        'subtitle'=>"This includes nursing homes or assisted living facilities.",
        'option'=>array(
            array('text'=> "I live in a long-term care facility",'single'=>true),
            array('text'=> "No, I don’t live in a long-term care facility",'single'=>true)
        ),
        'type'=>'radio',
        'next'=>'9',
        'prev'=>'7'
    )
);



?>
<html>

<head>
    <title>Payment</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <script type="text/javascript" src="../js/jquery.js"></script>
    <script type="text/javascript" src="../js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <style>

        .t_and_c_heading{
            text-align: center;
            padding: 0;
            margin: 0;
            color: blue;
            text-decoration: underline;
        }
        .t_and_c_body{
            padding: 2px 5px;
            margin: 0;
            font-size: 15px;
            border: 1px solid #eaeaea;
            overflow-y: scroll;
            height: 80%;
        }
        .accept-btn-holder button{
            background: green;
            color: #fff;
            padding: 7px 20px;
            border-radius: 19px;
            border: none;
            font-size: 15px;
            outline: none;
        }
        .accept-btn-holder{
            width: 100%;
            float: left;
            display: block;
            text-align: center;
        }


        .action_btn, .continue{
            padding: 5px 60px;
            font-size: 22px;
            background: #2196F3;
            border-color: #2196F3;
        }
        h1, h2{
            text-align: center;
        }
        .container{
            padding: 0px 15px;
        }
        p{
            font-size: 20px;
            text-align: center;
        }
        li{
            font-weight: 500;
        }



        .option {
            display: block;
            position: relative;
            padding-left: 34px;
            margin-bottom: 12px;
            cursor: pointer;
            font-size: 17px;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            float: left;
            width: 100%;
            font-weight: 500;
        }

        /* Hide the browser's default checkbox */
        .option input {
            position: absolute;
            opacity: 0;
            cursor: pointer;
            height: 0;
            width: 0;
        }

        /* Create a custom checkbox */
        .checkmark {
            position: absolute;
            top: 0;
            left: 0;
            height: 25px;
            width: 25px;
            background-color: #eee;
        }

        /* On mouse-over, add a grey background color */
        .option:hover input ~ .checkmark {
            background-color: #ccc;
        }

        /* When the checkbox is checked, add a blue background */
        .option input:checked ~ .checkmark {
            background-color: #2196F3;
        }

        /* Create the checkmark/indicator (hidden when not checked) */
        .checkmark:after {
            content: "";
            position: absolute;
            display: none;
        }

        /* Show the checkmark when checked */
        .option input:checked ~ .checkmark:after {
            display: block;
        }

        /* Style the checkmark/indicator */
        .option .checkmark:after {
            left: 9px;
            top: 5px;
            width: 5px;
            height: 10px;
            border: solid white;
            border-width: 0 3px 3px 0;
            -webkit-transform: rotate(45deg);
            -ms-transform: rotate(45deg);
            transform: rotate(45deg);
        }



        .sub_title{
            font-size: 18px;
            padding: 10px 0px;
        }
        .inner_sub_title{
            font-size: 12px;
            display: block;
        }

        .top_nav{
            padding: 0px;
            margin: 10px 0px 20px 0px;
            display: block;
            width: 100%;
            float: left;
            border-bottom: 1px solid #e2e2e2;
        }
        .top_nav li{
            float: left;
            width: 50%;
            padding: 5px;
            list-style: none;

        }
        .top_nav .prev{
            text-align: left;
        }
        .top_nav .cancel, .top_nav .done {
            text-align: right;
        }
        .prev span{
            background: #5ecb55;
        }
        .cancel span, .done span{
            background: #ff4c4c;
        }
        .top_nav li span{
            padding: 4px 18px;
            color: #fff;
            border-radius: 26px;
            border: none;
            font-size: 15px;
        }


        #loader {
            border: 5px solid #f3f3f3;
            border-radius: 50%;
            border-top: 5px solid green;
            width: 70px;
            height: 70px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            100% {
                transform: rotate(360deg);
            }
        }

        .center {
            position: absolute;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            margin: auto;
            z-index:9999999;
        }

    </style>
    <script>
        document.onreadystatechange = function() {
            if (document.readyState !== "complete") {
                document.querySelector("body").style.visibility = "hidden";
                document.querySelector("#loader").style.visibility = "visible";
            } else {
                document.querySelector("#loader").style.display = "none";
               document.querySelector("body").style.visibility = "visible";
            }
        };
    </script>

</head>
<body>
<div id="loader" class="center"></div>
<?php

        include '../webservice/ConnectionUtil.php';
        $connection = ConnectionUtil::getConnection();
        $baseUrl = "https://mengage.in/doctor/";
        $postUrl = "https://www.cashfree.com/checkout/post/submit";
        $secretKey = "7c09d72fe98e2c7df4384772a8bfb5237f2ff85e";
        $appId = "278553e2c5d1803aa8ce3586955872";


        $orderCurrency = 'INR';
        $appointmentID = base64_decode(trim($_GET['token']));
        $sql = "SELECT t.booking_convenience_fee_online_consutlting_terms_condition as online_t_c, acss.booking_validity_attempt, service.service_amount AS consulting_fee, staff.is_online_consulting, t.booking_convenience_fee_terms_condition, acss.thinapp_id,acss.`status`,acss.children_id,acss.appointment_datetime,acss.appointment_customer_id,acss.booking_date, t.booking_convenience_fee, acss.is_paid_booking_convenience_fee,  IFNULL(ac.first_name,c.child_name ) AS `name`, IFNULL(ac.mobile,c.mobile) AS mobile, IFNULL(ac.email, c.email ) AS email FROM appointment_customer_staff_services AS acss JOIN thinapps AS t ON t.id = acss.thinapp_id JOIN appointment_staffs AS staff ON staff.id = acss.appointment_staff_id JOIN appointment_services AS service ON service.id = acss.appointment_service_id LEFT JOIN appointment_customers AS ac ON ac.id = acss.appointment_customer_id LEFT JOIN childrens AS c ON c.id = acss.children_id WHERE acss.id = $appointmentID LIMIT 1";
        $list = $connection->query($sql);
        $appointmentData = mysqli_fetch_assoc($list);
        if(@$appointmentData['thinapp_id']==134){
            $secretKey = "899895a9f04308d36584adaa04a7b52d880c02ae"; //test
            $appId = "1461824bd90be36c6c495fd3081641"; //test
            $postUrl = "https://test.cashfree.com/billpay/checkout/post/submit";
            $baseUrl = "https://localhost/php_factory/";
        }

        $RETURN_URL = $baseUrl."booking_convenience/result.php";
        $NOTIFY_URL = $baseUrl."booking_convenience/result.php";

        $show_survey_form = true;
        if($appointmentData['is_online_consulting']=='NO'){
            $show_survey_form = true;
        }

?>

<div class="container-fluid">

    <div id="survey_screen" class="row" style="display: <?php echo ($show_survey_form)?'block':'none'; ?>">
        <header style="text-align: center;">
            <img class="girl" src="data:image/png;base64,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">
            <img class="corona" style="display:none; height: 100px; width: 100px;" src="data:image/png;base64,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">
        </header>
        <div class="container">
            <form id="form">


                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 display_content" id="step_0">
                    <h1>COVID-19 Screening Tool</h1>
                    <p >You’ll answer a few questions about symptoms, travel, and contact you’ve had with others.</p>
                    <p ><button type="button" class="btn btn-info action_btn" data-next="step_1">Start Screening</button></p>
                </div>
                <?php foreach ($survey_data as $step_id => $content){ ?>
                    <div style="display: none;" class="col-xs-12 col-sm-12 col-md-12 col-lg-12 display_content" id="<?php echo $step_id; ?>">
                        <ul class="top_nav">
                            <li class="prev" data-prev="<?php echo "step_".$content['prev'];?>"><span>Prev</span></li>
                            <li class="cancel"><span>Cancel</span></li>
                        </ul>

                        <h2 class="question" ><?php echo $content['question']; ?></h2>
                        <?php if(isset($content['subtitle'])){ ?>
                            <label class="sub_title"><?php echo $content['subtitle']; ?></label>
                        <?php } ?>
                        <?php foreach ($content['option'] as $key => $option){
                            $value =  $option['text'];
                            $message =  isset($option['desc'])?$option['desc']:'';
                            ?>
                            <label class="option"><?php echo $value; ?>
                                <input class="input_box" data-report="<?php echo isset($option['report'])?$option['report']:''; ?>" data-single="<?php echo ($option['single'])?'YES':'NO'; ?>" name="<?php echo ($content['type']=='checkbox')?$step_id.'[]':$step_id; ?>";  type="<?php echo ($content['type']=='checkbox')?'checkbox':'radio'; ?>";    value="<?php echo $value; ?>">
                                <span class="checkmark"></span>
                                <?php if(!empty($message)){ ?>
                                    <span class="inner_sub_title"><?php echo $message; ?></span>
                                <?php } ?>
                            </label>

                        <?php } ?>
                        <p ><button type="button" class="btn btn-info action_btn click_able_btn" disabled  data-next="<?php echo "step_".$content['next']; ?>">Next</button></p>
                    </div>
                <?php } ?>



                <div style="display: none;" class="col-xs-12 col-sm-12 col-md-12 col-lg-12 display_content" id="report_1">
                    <ul class="top_nav">
                        <li class="prev" data-prev="step_1"><span>Prev</span></li>

                    </ul>
                    <img style="width: 50px; height: 50px;  margin: 0 auto;display: block;" class="call" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAQAAAAEACAYAAABccqhmAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAA2hpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMy1jMDExIDY2LjE0NTY2MSwgMjAxMi8wMi8wNi0xNDo1NjoyNyAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wTU09Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9tbS8iIHhtbG5zOnN0UmVmPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvc1R5cGUvUmVzb3VyY2VSZWYjIiB4bWxuczp4bXA9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8iIHhtcE1NOk9yaWdpbmFsRG9jdW1lbnRJRD0ieG1wLmRpZDpFQjI2NjI5NkNCMjA2ODExODA4MzkzMTE5OTlDNUQyNCIgeG1wTU06RG9jdW1lbnRJRD0ieG1wLmRpZDpGNDFCRkU3RUUxRjIxMUU3OUFBNkQxNEQ3MzkyRDZERCIgeG1wTU06SW5zdGFuY2VJRD0ieG1wLmlpZDpDRTEyQTU5NkUxOUYxMUU3OUFBNkQxNEQ3MzkyRDZERCIgeG1wOkNyZWF0b3JUb29sPSJBZG9iZSBQaG90b3Nob3AgQ1M2IChNYWNpbnRvc2gpIj4gPHhtcE1NOkRlcml2ZWRGcm9tIHN0UmVmOmluc3RhbmNlSUQ9InhtcC5paWQ6RDc0RUUzMjExRjIwNjgxMTgyMkFCQ0Y1MzNBODc4REIiIHN0UmVmOmRvY3VtZW50SUQ9InhtcC5kaWQ6RUIyNjYyOTZDQjIwNjgxMTgwODM5MzExOTk5QzVEMjQiLz4gPC9yZGY6RGVzY3JpcHRpb24+IDwvcmRmOlJERj4gPC94OnhtcG1ldGE+IDw/eHBhY2tldCBlbmQ9InIiPz4RhYFXAAB6mklEQVR42ux9B4AkVZ3+96qqc0/OszMbZvOSFlgQ2CWIgLAqIsiZs6dy3hnPdIL69zz1hJOknllBTwUERLIoQZJk2IXdZQObw+TYscL713tV3VOdqqs6TNidWpqZ6VDdXe993+/7hfd7BHPHYXtIooTQ4pCgErUWFEGA+PW7vQCV2MP6jZhPpfpN1W+yfkvqtzgERKFgbGL7hDZ3JQ/fg8xdgll4CICvzSf523xtga7AfDEoddWuqm3TFG1hcEGw0d8ZaFETaovX720I9QQCCtRaShEwwD95UEozTkuR/jtJBS0GhYxFd0RjmqIMC36xP74/3h/bHRuCRHZObJro06Lqvvj+2J54b/JQsjehYo4q5ghg7qjs4W31ILAwtKx2RXhpYF7w2PCy8BJfi29ZoDO4QAqLnZ5GjwiNQPAaQ6nEVKhxFUQg0FQN8oQMQcc1IYaZt2KUUIMEKMlDAuwx/QmeWok/TjQK4hMgBgT+sJrQnyDqkmEoqcoReX98f2KP3J/cOrEtsj1xIL4hsjmyNb47ti3ZJ88N4hwBzB2ODHtYQHhVeF7N8po1dSvrTggtDR8fXhQ61t8WWOAJe3RQC9B0cKtRBUpUBVUo1KTKR9FqzS2W3KLwC/2V/dpMM65lPDvrPPqfglcAkXQCCgrQVQJEP4FCNWgTChK9id3R3bEN0a2R5ydejTwf3RJ9Lro5sl+dUOcGe44A5g5mQWtPqF1Yf1LD2obVDevqVtWdHJwfPM5b6xEJFaBEdLDrQFJ1q65pWg5yC0l4Qsjk38x6G//j9xgPkfwkQI0zcBIwZQGlGj8PJwKaSQI0+/OkHiOGmyLoakEKSSBh4z5lTFF0h4Gpg6fHXxp7bOyZscfHXxzfBWVuKswRwJEi6Rd4/fXHN5zefEbzOQ1HN5xVu7L2JH+dn2i6pFZGZCi6ZNcUyuV3riVHGuAc5GQS7FYQpsiCgVpjANblexrglKYBnnF29n6UnZeRhMD/Zm5E6n24G6ArEIFMkoDGzq+mzg3+XgViCvw5RNJdCF3lSPUSBB+BPCJTXRk8M/7y+MOjj47+dfzFsUcTuxPxuVkyRwCH1RFY7q9tfn3r+S1ntFzYtLrpnGBHsE0URCjDCpLDSR3wWnokNKsE50BHJhAZgDXK/XtVVflrVUVN/87Ar6oKf5w9T7UQQAqolAX8UxY+9T8ySQSMAFLvxwIIgv5Z+WfQeUEUdfDqQBZEov8uQvDoN0YMog5u/SclBvD5Z9Q/CycFmse90J/IzsPJoF5/nf7ceG+8d/zFib+OPDb855FHRu6NbYmNz82eOQKYnZa+xxdqOadlffvr2y5sPrHpgmBHuInECJIDSSgxxQBjlu/OQMfAxMBGicZBrOjAVmV2UyDrN+bzc8Bz0Ot/axaAmzLdShZpIOeMeCYYqQWkTO4T82/Ncl6NmsRBaFp9CIwERIMAPB4Jok4Iov5TYjevxO8novGmnBA0lX/efK4MiyF4Wjxg+YrkocTg2PPj9w4/MvTnkQdH7onviEfmZtUcAczswwe0vKXlzI7zOy5tPb3t0tqu2laiT9tkXxJyXE5fbWoFvGgCnllrHdRKMolkUtF/sptsgF6/P2XR0wNnUQVptOYJ3Nmk+vIG9fIFAwudz3AxVH6aFBGlZhX7XpJOBpLHA8knwev1wOPzQvTqBCGJBkGZSoaarkP63MwV8RNOBiREED8Y7xt9YvSWofsGbxm8a/ARGqdzc22OAGaQxD860N558bxLO8/r/EDTMU0nSqqkWzAdyNGkATLLVU5ZS3a3ogNbTshIJBL8JwM8A76qGGDivr5g3LJHKhfYdtF7uyxBmSSArAwCnXyOalp8UCP4KOjA93BC8MLn1wnB7+XkwEiCBSoVReaEkD6vEZGEENSJpE0CPBQTr0SeG/7r0A0Dfxq4Jboxemhu9s0RwLQdjW9pfF33pfM/3HFGx3vDTeGgNqgiPpAw/Hgy6ceLaSuvQdZBnozpoI/roI8nDdArRlqMSWXuAqSi+IWi7CWQgFMlkPueLkmA5n8+CznygKTKSIHy9xElgasCj04GvqBfJwWdEHSlwAiS1zCocoYrwl2rJg/EJgnycCI68ujob/tv6//l0J1DT83NxjkCmJqjFuh8V8dF3Zcs+Jf2Ne3neokPiX1xQ+ILuZaeBefYY4mYrghiCQ58RZf1DAECs+6ikBHJzxf5L4cIpkUJ2JBA+v5UloLFODSDMFkMwef3wRvyIhAMcGJIkQF3g1JpTvZ03d3ydfmg6Qwx/tzYA/1/GvhR/x/6/6SOztUYzBFANY46oOsj8z/c885F/9pydMvxZERA/FBMn7xq+iqmQM/kuwH6BOLROP/JQc99Y5EDvxDwZgoJFCIAJyRgdQWy3zubBKzvwa4bD3pSFV7JA2/AB3+I3QLwBbxcRSlmPMR4kQqBCLp74OWZhMjmyAt9t/T9sO9Xfb9QRuaKC+YIoLzrQlPA7/7o/E8sfvfiz7Yc1bKM9lLE+mM8T5+KhKeCWorux8ciccTZLRbn0Xt2P3s8w8rTQkEsWhYRpP3mKpNAJVVAIdJi6UtVUTgpiJKEgE4CgXAA/rAfXr/EX8syI6nUJhsOqdEDqV1CZEtkq04EV/f+ovfHFiIgedl2bqLPHXmBX8ssfveHlrxn8Rdbj25fQXs1DnyNGHKV+eoM2EziJ6IJxCZiSESMYB4jB7YSj5iWXiuwSiY/ERRXBG7UgFvrPZ0kYD2/9TyqapABjwH4vJwEgrUhHjeQpElVwLlApfA1eyHqRDC+ZWLLwE1939MVwa/miGCOAJwBXz/a/6XjkqUfWXpF+6qO49AHHfhRDvxU2o4VwLBgXlwHfXQ8xgmA5biNnHjhS1oOEVSKBMoNDE4XCaSCgCxmICsyH4dgKIhAbRChmqBODB7dfdAM1UWNOIHYLBmK4NXIS7039P7noR8dvLXQmM8RwBzwUffW+lOXf3L5t+afPv9soV9ArG8S+FzG6xY9EU8iNsaAH+V+PicFScgpyS0k4e3IoFwimE4lMFUEMPm3qlt+w0Xw+r0I19cgrKsCFjfg1ZA6ERhVk2xFpQSxzYPRJ0Ye7P3JocsH/zT45BwRzBFAeuC9x/naln1uyTeXXLzkY4FYALE9MchEMYDvEXmOmgXyomNRbvHZ5Ern8219+uJkkE0EU0UCs9EV0Ap8F0VVOBl4vD6EdTVQU1+LQMjPny/LMn9/trjK1+0Fa4syeOfgTw9ce+BrkRcjvUc6CZAjGfiscq/7S/MvW/Xxld9prGuqS2yPI6kkeTovbfF1v35iNIIYA74uMSXzfluw2xBCpYjAjUswk0igGgSQOi8rOmLZFuaiherCqGuo5RkE9pnY/by4StSHfbEfSkQeO/SLvq8cuGrfj7SYdsSqAXIEflc+wOELak4++oqjvz//hPlrtd0a4mMJUFHjEWeWpmN+fWTEAD4LMHEl4FLmFyOEfK8tTgSF1UDWat1pJ4GpVAHWczIiYDEa5prV1dahprEG/nDAWFuhyDxQKIYl+BZ5Mb5x4on9397/2eF7hp7ON0/mCOBws/oNwJJvLPmvle9f+R+hRBixfazzlcItPitVlXWpP6EDPzoatQW+G+tuRwQVJ4EpVAKVdgUqoQKs78U+R4oImFtQ31QHfyAAWVd5rLCIvdw7T5eBIYL+3/V9e/83931VHpKPKDVAjpDvxweydn3Nacd+Z/WPu1Z0HZPcKiMZT4JI4CvXmA8ZGYpwq89+lyQpr9SnLuZEoedWhgimjwRmgwqwvh+T/gk5AY/Hi/rGOtQ21fKSY1aGzZdi+wgCy/yI7Yht3HvFvk8M3T34xJGiBshhDn5j4PxAz3cWffPoDx9zRSgSRnRfFKqowqNPAjZRI7qPPzE0waP6xvp2d5elqOUvgwjcqAFaUKoXJwArgEp1BUpVAdUmAP4YW16tg53FAvwBP+qb67lrwNQdW4DFHvN0+iDWEvT9tv8/9351z9fUqHrYqwFyuIPf9zr/kuOuOe7Xi47vWattUxGLx3kDTUn0IB6JYWxwDPGJuNHUwiNUZJjtCCF/dR+dUhLItNDVVwHWc0ylCqAFOhQxhcdcgJraGjS0NiBQG+DEwHotsManTA1MbIw8vvsLuz80/uTYtsOZBMhh+n34QLV8pu0DJ37l+J81kiZPdHfK6nt5Vdm4DvyJkQkuD1njipKATksng3KJoFpKoJIkMB2xADsVkH09eR2HSFDXVK8TQT2P9yQTss4QFL75PrZzgrzvqv3/fPDqAzccri4BOSzBXwss/+HyHx39T8deJu4WEB/VLbzPyNvHxuIYHRjhK/OY78+71VRoOO0IgTpe9DMzSaBaAcFSVYDmcPGTHQHw+1SKZDIJf9CPxvZGhOvDRrYgrkCsEeFb7MPAnwZ/vOvfdl5mlhMfViRADivgM8l/onfh6v89/pbFxy5ek9wsI0llSD6RF++M9o/q/v7E5AKejJMUvxSuAoC0ckQwnSQwlSpgKt2AbAhzF0BTeWygsa2Rq0I5ZmQE/KsCiL4aeXbnv+26dOKZ8V2HkxoghxP4699Vv/7E/1lzU7u3PRzdGYXm1Xh/uthYFKN9I7wBB4v+Ok3rVSQAWAYRTAUJTJcKcBMLqKYbYP2TnS+RjPOeBC2dLQjXhXlcQEko8C/ilYUTu7+y+50Dv+u/+3AhAfFwAX/HFR2fW3vN6TfUDdd5oweiIAG+VpcDf7R3xPD1fR5Hlt7dh8j8l/O48TEKvs5OhViJKvv5hEOF5jzP9rPm+xxZ74G0Nc7dO4Dk7y6aPm+x1+e+b+5z83VKyD6v9RzU0WenDq+PUfrNsgLjw+PcFQjWBHnbsmR/ki308ra8r+XdkDA+9sjYk5aPTGY7iGbj5yYwW9su/e3Snxz39tUfE3aIiCfi8PhF3o9vWAd+IhLnco7kn/3VcfiLBQCpOzVgFxdwogSq5wqUlxacESog65Kl2rmx2ABTA8FwGG1dbQgEAkjGE4CHwLfch+E7h3667f3bPm6+pYB0B8M5Apg6y98G6ehfH33XUWcf80Z1s+7Dwcjjs2KeER38jMEzIvykSl+3hNr/fC+htrX+pZBAafGAUlyB6YoFuKkMdOQGZJ0jkUzwQHF7ZzvqGut4LElWFYSOCmLs8bH7t39kx5vlQ0lltroDZJZ+VioultrW3HHiX3oWLjk2sSUB6lO59BvRJT8r6uGr9aRp8nCom9RfkThA2UqgcvGA6VABUx0MzCEBfdYl5SRv/NLc0ozmjmZ+NwsQ+lf4keiVN2y7+NXzYttivQU8mDkCqDT4Pau9S0/6w5qHF7Qs6kzsjOv+PtX9NhVDB4Z4Oy4e6HNQzVdOPMBRRoDSqpKAs8Bg/nNVRQVY3q4UFVBNN6BkAoBRRcjLiXX3sramDu3d7UZcYCIJX48P8ph6YPt7t50VeWFi22wjATLbwB88P3jCyb88+ZFOcV44sTcGBAgHPQM/k2cs0FdpsJdNCLQCbb4ckEDhisGZpwKmOyXoxg2wPpfFmLxeLzq6OxCqDSExkYC3y6tbJTKx82OvnTF879ALs4kEZhMB0NBF4XWn/OqUh9omWqVEf5wv4mD+/vChYaP/vkfKQZJT4BNrT++CINfKIwNagaafLkjAjStQLCBYzVjAdLgBdgSQcx2t5yBshWGS717U1tWO+uY6yFGZtx8TGwV558d3nj14++BjsyUmQGbJZ6M1l4TOOOXXax9pHmiCPJIE9VKM9k1gdHDE2KSS+fvWffZsvpoTsDsDuuaeCFyqgXKVQDESqKYKqIYbYD3vVLoB1ueyjBKLC7DeAq3tbTwuwGoFaC2Bb54XO//5tTMH/jjw99mgBMTZAP7QxTVnnfKr0x5uYeAfk6FJVLf6IxgfGjM2oBSEDODnzcfroC/0WOkf0pr/pwUfz7wztzAgN/9v81hWHjzv/ZYagaLfwUVtAM3zuJ0tcf7ayWdk5vBJATOaWxNQKO9Pc74XdWQKi10/XjMgiBgZHQaVwVcWss5Cqk4Eze9q/lDyteQj0U3RXZYzk5kOtBkJ/vBbw6ed8ptTH28e0Fl2XIZKVAwdHOb9+dhyXhB7i+/U2gtFUoQaddMHQCtJDThVAtOlAorX+FdPBcykOIDxXlr6NbFEFA31jehY0GFseBrSdCXgw2sfeW2t7g48MZOVAJmh4OdFPoFzg6tPveW059qGWwRFt/wyFAzuH0I8mtDBL9nKfTvgCxWqByhGCo6IYMpJwH0soNJuQLFg4FRlA9zEAWxJQD/Ylm/hmhp0LOqAQAm0MODr8Gjb37X9xOG/DL+IyWKhGUUCM5EAOPg9K6Ulpzy09qWuZGdQGVag6Dqrf/8gkrFkRnFPrkSuPvDdkEG5JFBOULCYCiglI1BOMLCUmoCZGgfIJgBOAok4/MEAunq6dN9a5LtKiXVidMu5m4+LvBLdjhlYMUhm2Ofg4Bd7xKY19560qae2p1Xtk5HQwT+wb9BczCO5svpOQV+p1YBOiaCYS1AOCbhxBaZLBVQjG6A53BrNVVmwCwJgUygWj/Ky4e6eBRCJANKif/ok7Xv1za8eFdsRG5hpSoDMNPCjFZ4THzpx49L2Zcu1vSoS+r+BvYN8y61C4HcL/EoFAm03/iiFCBwqgWq5AtOlAmaqG6A5IO2iJKDPTaFLgDqqvbrprE1HywO8bHjGkICAmXEY4Nf/v/K3Kx9Y2r10Od2n6dBPGpafgd9XPvirmwXI/QxO1EfGa4ldxL/A/Q6yAk65v1hGYHLSHBnNpIVS4KHD2ef3IxaLYe9ru/n25Zo+l6VWafmSG3v+Qoyt47SZor6FGQD89Kq+hTcu+tVRa48+Ezspt/yD+wd52yYOfloc/IVA5wT4xME/J68vRkZGOlJwTAKFAFqIBEqZyKRgCs8dGTohFMHhewlFr7dQEUVXlQrRDBLYw0lA3iEjfGr49Ut+veSXaYExA5YSizMA/Eb/vq+0fPGkT570Bf92Xxr81oCfE/C7Ab4bcNuRhEIVfmN72vNPRYS86/opyTfxaFGkuwZiAQJx2zugMFic5vXzoMKR5s33PPt6gJxrXeQ9besBbNy8fGNht5SbtZyL6yTAlqbXNtVBHdIQXhs6Xp+tkfHHxp6YCW64OI3gT/2koYvDF5zyg1N/XbezFklVl/0HBvmWXKWC3wnwy7poRERcjWN3ZDdi+k92PlmV0R/vxXByGGEpDI/gyZk8mZOzcKMLOxJwowKKFweV4gY4afhRelGQFZw034Qhxamkkk1CSmn5br2P7S8xER3nlYI19TXQRjU0XFx/Xnxz/KnYlth2THOhEJlm8LN0X9cpD526syveJSnjCvoPDfD6fo+5qKcU8Jci84oZslRsSRIk7Ivs4xP10kWX4qz2s9ARaueP7x7fhb/s/yvu2HMHfIIXncFOyFQuGiAsFNDKjqQVjdiXlBEoPxg4FdmAaqYDK5oJQP6dn1lgsLm5BR1dHdBCFEKdoG45d/PC6KbovukMCpLpBD9CwHEPr968ctHKFfSghoGBAYwNjJutu/JZscqC35Uapgb4t49vQ5u/Hdeeeh1Obj0p71MfOfAIvvj0lzGYGMDC8ELImpxzte0aWRTKDBTN27vICLhNCVYiGzArCCALgpUiAPZ+yUQSbe3taGprAukQIR9MbNl09qaV6oSK6SIBYZrAz7/g/OsX3Lj06KUryEFgZHQE4wz8rH1XFcGfKsV35bqmwD+2Da3eNvzh7N8XBD87zuw8kz/HR7zojfZyl4F/Fpr/8zsNCjoJRBaLBZQ8eBXIBuTL2OR3A4Q8w+D8u1cWIILt+whEcHj9BHi9Phw8dACjw6OgB1T4lwZWLLx+0Y2WmTbl7oA4xQSQjuU0fKTufWu+ctI3QjuCGIuMYUj3+9mKvtTCHrtFMMXAb9eg0200NyX7t49uRYuvHbecezMW1Cwo+tIGXz2W1y7Hz7f+Ao2+JkhEzKFC6jQoWES9uIkF2AUDnSuowl54cSBShyaucBygmC9PHX1W6vgzu40DFFqMRYgRJJ4Ym0AwHIQUkRA6I3ScOqDujDw/8RKmISsgTiH4U4pDk47yLDjpdyc90jLQhthEFP37+7lMZB1ZUcz6E/sJ76YzbzZhZPyjxk8WzNsxtkMHf5sO/lswv2a+4y/dU9eDuBLDrXtvw/xgd+akIHAeFHQQEHRqtZ0GA4tbW6dAzv6c5RNAsUBgoUxARvfhEjMBpQQCrYckSpDVJKLjUdTU1kJMCqh7S93bRu8dvUHuk4ctrsCUEIEwxeBXIQErfrzirg7SieRoAv0HB4xOPl4pPR620r8E8BcDva3lH9uOFm8Lt/zza7pdf/krTrwCa5vW6SSyfdIVsLxHpdYnVLzducOioFJdjkrVA5TidkznwWIGzBVQZBmH9h2EPKaAyAQLf7ToTsHDv6tqwSU5HAggw+/v+Gb7d5eetPRo7AcGBwbSbbudrLZ1W97rtB9/Ydm/Hc3eZtzs0vJnSCwd9D9cey0UTcFIYpT7lCSfD5BnwlYiFmB/fUiViWSWVgxW+2Pr4+7xezE+PoaB3n4o+xUEV4eO7v7m/O/miQfMahcgQ/r7z/CftOa6k2+o31+PocEhjPUbDT2IIBSX/rAvdbVrquEGOOw9PKIHr43uQLOv1bT888u6CE2BZp1IWvCrHb/CvEBnphuTxxUoNRbgRi24cQPs4gDFNgAp3X3IFfHFNg0pJw4wVS5ABvhEFg8Yh9fng0/xIXxOzbrIkxN3JXYnDkyVKyBMEfhVBIFVV6+8tS3WionhCYz0DfPW3U6lo1Al8LP35zfzcWb5d+iWv8nHLP9NZYM/dbx/xfvwngXvxsaRDZCIlDMHC2UF3Fhkp1a+muRSiopw+hnpDFUUTjMBud9b4PtYHDpwELHhKIQxYP7/zL9VDIlT5gpU2wWYlP6Xd/7PomN6uuX9Mgb7+nkyV5AER76bG+lPiDPJnwJ+tuzfMbINTVz231wx8KeOq9Zeifm+BbyQqJgrUAyR1XQDnIA1r29dwWlKSgCVXaZoqmMoTg+P5IGmY6HvYC8S+xIIrQgt6Lq863+myhUQqgj81E/Nd5Lv6FWfXPU53+4A+gf7kYwmzA6+cF337qawpyjwc8DPLH8rbqoC+NlR76vHNWuvQW+0DzE1xjMNGZ+jVItLKj14pOKvI0fGAkILsBxCSx931mZ8bGIMA/0DSO6Q0fqx1s/VvK72KGSuGiSzhQAy/H7215L/XvKHVq1N9/lHzUaenmmdqHnBb8p+I88/v2oT48yuM/CFY/4dG4ZeMsjIQgICHLgBpATLn7eIR6jgJCJT+jr380SYGjCXcfh9fgwODGK8bwyiLKL72103mZvbaNV0BcQqEUB6mW/DZQ3/cvy/nPhBbAF6Dx3ioGNtvLMndKHgn1Pfv5j1L2z5vRz8LOBXSZ/f7ji983Q8vu8JbB7djJZAS1YPeudBqnIJsFKBQDdeTHbgzr6QqDKLggqdy82nLmdVoKNrooOdNRSNxWMIaEHUnFLXqgwq/RPPTDxTTVddqAL404E/0k7qV35x1dXBvUEM6tKfNfawFvuAVGcdd1FgZIB/G5q9OvjP+cOUgD91XH/6tRCpiKH4UEELM9Ny2OVNialXGMXORcgMur6s3NwrIRGLY6hvEMquJDo/33G1t9NbjyoGBIUqkQo3aV2Xd103r6PLO75vDBOjE3x9dDWWObiqiLOCf8QE/7l/QPcUgp8di+uX4Duv+za2jG2GyhaU0MkJSqh7UnOa+XAfoS9NWk9lUO3wIEozHuDzYmh4CKN7RuFr9Xu7vtp1nfmoVg28ihUdB+PGrb/vRO8xJ1x90o+DOwPoPXCIyxue9itS3FIo91+q/C8s+3Xw6z7/TedOreW3Hsc0HYP9owfx0KGHMC/YkbmnPbGRqaSyPQ3LWzjkvCTY7nX5P4OzWoBC12k21ALkcwVUTUEykUCQBlF3Zt1xo38buy15INlbDZddqCD4Uz/52sZFl/f8pBnNfN++ZDyZKf2dfjib3n6uLU8G+HeYFX43Txv4U8f3TvsuFgUWYu/EfgjUEvibVbvMV8myl7Aq8LBQAV4fItEIhg8MQ1AEdH113k/MR1VUOCtQSUmRbvEVelPovCVvWnJq4tU4X+YrFpH+ZfV0Iw5oKSPaz5b0Mst/ky77u6d9vGu8Nbj+9OsxEB9AVIkai5BSqoeWOhCVLQiaKldt7sgkgcGhAYy8MoLG8xtPbXxL43mYTBZX7EoKFQS+kLL+PZ9bfH1wJIyhgWFQVZf+wlSle/K4CRl5fsPy//7c30+75bcep3Wcii8f9yW8OPJCTt8AUkJDz2pcS6dkQuZQXhnfXBShqioG+gYgDyjo+HTH9RYVIFSKCCodVKC1l9a+c9FZi5ZFt05gYnwiYxefKTcHWXn+VpbqO+/mGQX+1PHFE76AN7a9EVtHX+ULiOZcgTkV4PP5MTY2isFNA6hfV7+s6R3N70SFw+jlEkCm9dfnbc+nFn/X2+vF8NBwxSx/SVbOvEwe3effOfYamn0t3PJ3h7tn7JgzV8BLfNwdyEgNUuJ8NFwNvjAHtJmuBHTjNawr6dj+ODr+teO75r4CFVMBlXQBaM3ba96z4LSFCyK69Y/F4hClaWo6bCmrHYoPYn5wAW4656YZafmtx4LaBbjylCuxdWwLZE3J+k5z0rpqxmIGHyx1Ho1GMLhZVwEn1y1ourT5PZWMBQgVAD678dna84nF35AOihgeHple8Ft+3zO+Gxf3XDLjwZ863r7kEvzz0o/jxeEXM9uIzR1H7MFWDI7oijqyN4L2j7V9w4S9koXBaXUBaM1bw29bsHbBkgnd+ieTSZ7zn1ZLQI1gGvOnE0piVg34d0/9LlaFV2GXTl6iNfU7Fw84Ig+2YjCeiGPglQHUnVq3pOmiprdVSgUIZQI/bf0XXbboa9KAiNGR0ZJy/pW2/qzdNLux3Dqlsws5IU8QPzz9egwnRjAhT/D95udI4Ag+WJmwx4ORkRFED0TR8bGOr5mPlK0CynUBeNcS/9rAuq4zuldPvBpBUkmmO/tW7vuXUGdmgp4pAVlWZt2Yn9x+Mr52/BV4YeQF83u4dH8cHPn6189+rByeDMlc6mQygcFNg6hdV7u67vTadeaIC1PtAlgZh+f953+060v+mA9jI2POff9KW2Wa53fmAkBCJB6ZlYP+2dWfwVs6LsQ21lAU4mRNwJwKOGJVAMuuxcfiaPtIx5fMR9RyVEA5LgBP/UmLpZ756xe+ObI5hoTOUJW2/qWwP//JftUIRCJhLDE2a8f9+2uvhF/wI6JG+HqKbJKjc2xwxBysOCgpJzCwcQANb6x/c2BpoAeZKcGqKwAr0/CZ1/be9stCoRDGByYKWv8pd8HN3XzY+3qJF0PRkVlrNRfV9eCfFr0duyd2c9FeajrQbRzEjljszjXb4i2z7ZAkD0b7RqB5NbS8p+0yy4wvSQWU6gKwQ0YQ3gUXz/9QclsS8USMR/5L8tfLQGfOfKOZ+9X5iA/DsUHEEolZO+hndpyl87wR1DTIzb6X4ExVBXPcUBkVwDICQ5uH0PzWpg+JIdHLsZiJzaoQgJVduKmvvaD2ooalDU3j+yZgVihV9NAKbYxpZ50sCoCXU+rXZzAyiL6J/lk76GxDErY5iaIqIEcQimiejTfJnMvDlfbQ7iF4FklNjRc0XmTFpFsVUKoC4GH17ku6P4oBglg06jj4V3xdtVbCRJl0SrLxwbb2GogN83X3s/XwS154BQmaZl4bqubEAsoDGp0BYJ8Dths3gAW2xw6NoeWS5o+adyvVVADWNchG8G+htLB1Xdu50e1RaETLO4DTJUUNFUBMWhQRUSawd3jvrB3wiXgcsXjCaGdF80vpvPdVoEmFHS7tyHoqx54ehunM4sAVMPTqMIKnhs719wQWIjcYSCpJAMgOMjS/tfkdgdoAIkOR0st+y2B9uz3drX/yBhJExc6h3bN2sHcfOIChkQmjmWpVd5CnU3JOt8ShVfBzWc+Vz8WYLQerDhwfGoUSUNByYfM78mG0WgTAAw4dF3S+S94nI6nIjtaAF8N6po+vFY0D5J1m+fePRtDnx46hHbN2sB975VlI1AuBGlKZFTdR81/pQNBcwnhmSXSNuvnsWpmkoc24a8FbhykqRneNof6N9e8y75arQQBWVuENP33H+o5tOLrhuOieKASJTNtkKUYIqcdrAzXYObILo6PRWQf+vb2HcOeGv6KzsVn/QkJGgDOfGsgr+6vsX2vW+osSrfHc4f5gynt05wikld7jwqtrjsNk41DHSqCkLEDr+ta3ebwexMfjJS36oZlF+5VVAeYnJZbn+z0B7I/sw9be7bNukL/6f1chSifgD0igGklfLuuGIpWGUak1AFXy9kpUCNNLLlNRZs2CgbGJKGQxjqb1jW/LxmklXYAM+d90Ruvb5AMyVKhFJ0cGqGnlJ2jByWopnpf0fyPqCF45tGlWgf//3fx9/G3DE1iwoFm/0gqsc0or4bpUUh3kldUVxFwp/nlZ9SRT6E5VmmhG94yhZm3NRaW4AYJD4Kdr/73LvavqltccF9sfAzHlfynRZicXvJAKsD0XsfzNSICnBwl8foKXDrw8a8D/zT9ejR/c83v0zO+AVjOhX/nSlgWXI/9pieXG7jMNzp4/Y2sApvFjMRUwtncc4hLxuPDK8Crkrg0glYoB8Oc2n9N8vifkRWIiAVGoTO4fDiW+3TkL/U1Mf6C+phYv929EdDw548H/n3+8BtfdcyNWti8GbRuD6kmyfZbS9Z5akdXBxa6hVkA/TIUfX+mU3ZGYAsyIA7DKQN0NUHwKGt7QcL4F1xWNAaRX/jW+ruE8dVDR56OadyIVY3O3BslOBRSfrOakJgRhfwg7xnbhhd0zWwUwy3/tvTfgqNYVQOso5JphEMVjxDVI6Z3BKiH/3auJWRrkm4Ufe6JvAqGTQ+eZf6qVcAGyZURSaCbN4ZU16+L74zmlv2W7AQ5VgFYkQMVfa+E+SlR+RgkeRIVRPL372RkN/mt0y39U63LQlhEkGvpANCl3NEz3hpYAcDvrXxZR0/KVQzVqAI4EhcCWCY/tHYO4TFrnY5te6Fh16gY4dQH48+pOqT/d3xoIJUfdN/0oFAx0mxFgdzvKCvCvbZhNnjHTT9FQF8DTe5/FTJwTKdl/TJsO/uZRA/zUq38N3c0SKIi1wzKpTjDLsXJwW0NQYYtarAagULzocCkCynEDdFc8NhKD1qiFanWMZrkBFVMAaDip4QwmLmRVtrUo1cg7u60eSysBC2Dqa2uwoX8DNu/aNePAf+09uuxv02W/Dv54fZ+OeY/RtlvI9OgyXABSgtKyGaNKkEclAoBTDU57xem+CGg6sgLsmiWSMYTXhM7IEwdwrQBIFglwxIePqlmr9KsFV/4VcwPKUQHFWD4D8FmvJ0wu6xbUJ3rQr/Xi71ufnDngv5WB/0as0i0/mnXZX9cHUQc/ISIoi7GmrD+xDinNO6SZ15dWdGLmPx915UiXI8dJBbJK1Ti0GaIkBElEpDcKzwrPWvOuZLbTWI4CkD3dngWB+f7ViYE4l//WCVFMBVQqP5s9gQoGBTNiAJaCOd2fbmgI4O+vPTZjwM9k/1Fty0CY7K/r17Ht0T+zyIv+eO1vSgEIlbP+U+n/V7Mepyr+/SyNW3oFD6L9EZAusjq4MLgAmfUAZcUA+HNqjqlZ4230eNSIUpYP6lYFFBv0fCRgJQJCLFZT/6+xIYzne5/Dph2vTTv4WcBvZesyw+evYz6/pANfBN8OQDTAz9UWMaqAK2X9ncp/O/9fK2Ol4VQFAI+kgxmHRCQJtY56QseETnKKb8GB78+nW/jomjUsIKVZ1qIXT/mVMYA2CqIYCWQwuZUA9JskShhGP+7d8NC0Dda3UpZfBz+aRpHk4GepPgF8R+ws8Ocs8iQOr3+FrH8l0n/UYayomP9f6iKgI4FMWMxIVuPwr/KfaIdlNwqAL/5hvwSXh45XR1Sz70gRC1OJWECpJGABSqYaIDwOQFQRza0h/HXrQ1DkqfffeJ7ftPxM9ifr+3m0n5t9BnjRuOrc709J/9QoWaw/Bw9xZv1LCf7RMkBc6cNNBaCTNQClBhlnegNWtjgoNhyDd4nn+DQsi2DcLgiYsjsJEiS1/nm+Y+ShpD4xBVcTgDpo61VJEsggAov1t1YG1teHsGFoAx566ekptvzX4rp7U+AfSfv83E/RgU8kalp/YrgB2eAX3MRLaJHgn8OKS4fBP+pQTVRK/tMiBqcSoJ5t+yYwoxEbjAGd5FhvrbdOvyuOXA3pSgHwytNgT2CZr9HTqUTkIr6d5tgnLXVQ8pFANhGkmmemN4okph/NOhfpQGKddUhNHHe9cN/U+fx/1H3+u2/gRT6wBPwY2JnlZ4A3An/FwW/NeGTGTuDa95+qgF0lDo3OtM+jzSjSYC6AMqGA1msdgUX+5cjYJre0GAB/PLgstEoKe6DKmmXC5Qd4sQvgRAUUCwo6kafc5hOalRkw3ACqCuhor8Xfdj6C3QcPVXVQxqLj+PL/fQc/vP//eLSfgZ/5/OCynxiA1y0/NS0/W17B4gBamrvygL8IybqxiJWX/9Xz/0sJLrry/w+DMAFV9Ksd0OBb6l9hwbjjGADJQwTwLdBPJpA8L3cT8acVJ4FiLoE1FjD5jSjvYOQLeNCn7cOtT95b1QGJJuO49an70OBrgNAyjpgZ8ONdlCQT/FmWXzOHjDiQ/Y4VGc3dOqnYUu2pkP/l+v+FFFChFOHhVAGY16Lr1kOmCqQuaXk2jvNgvKgC4JfU3+lbqk5omNyZyr6yTCt48StBAsVWAWq8SenklyE5sQBmXVlzjfbOGtyx4R7ecLNaR3t9C372z1eiL7ADw4E9uovvNUw8A78wKfvTll/ItPzUkXpyY/1LqBqssPWvpP9flrWscAXgjAgEChLi4wkI7cIyy6C4zgKkd/4lEhF9Hb7F6kShrsP2rkApy3vtScCZGtDM6EA2pZGUddVE1DR4sWlsI257/IGqDspZx5yMz1/4Ybzc/4qxhFokpo9P+edJW35SOfC7yftPhfUvV/7P+f/OA4HKRFInAHGxIPH1+gpsioEEG+vPMwBSg9jhafEuVMfVkhmxFGtlSwIFZlkhf1Yz4wHpgKAJPPYVG9s8uOmpP1Xd//uP07+K9Uvegk0jm+CRzAU+ohEAZFdaI5myn5Z4rZ1I/3Ktf6X882Lfq9T0X0n5/8OoTCDJjHUTXeBr8rYzDMOmFqCYAqDeNl+XGBAaNEWzuU7FA4Ju4wGOSaCQGiCpXgDgUX9KUmXBxmMsI8BUQFN7CP849BTufvqRqg/MTy78Mep89TgQO2i0UjfVSKrs14nlr4T0L8X6VzP4V2qcwP3CoyOjeQhVVGg+rVFq8XZluQBFFYA1SMB/ert0Aqj1QEsVzRTM2VNXA1YxErBRA+kJSHL1DSMD/qgOvFALxQ2P3FL1gekId+An63+CPbG9iKmxHE62rlsorpBKk/6FLlvxLkKVkf+lAFNzENyrpP9fLtFMeyBQFkBrAc88qTsfpgsRQPbKIf6YFJA6+QiQ0nw8p36RvaXLnaxO1EA6KGjsFZ7RI4D73uymSmibF8Lf9zyGvz5f/VWC5/Wci2+c8nU8P/wc99cmc/2Zw1OK5Xci/YtWaeaR0lrRmo7KkIcb+U8LatHy5X8pvvxMKhoi+sRW2T6SQXRacE7yzTS7LAA//Iv9nWmrWXQg3bsC1LbSr7gaKEwENKN7jvFVKbf4Ro0AjDoBRgYeikCzip8+8LspGaD/eN1X8LaFl2Dj8AZ4BIm7I3AQ8Ksk+Itv1OJ+hSfNY6k1FwVhpUb/q1n+6zQAOKMUANs0hKUCezydhXDtNAjI/NLOfPVElFaGBIoxezESKOwWZKkCkhkpTWkcTgKqgPbuMB7c9Xfc9+zULBX+xTk/RVewG7vGdvLUjROQVGqJtRPr79yKlhc4nG3R/9lyqLwsXusohOtCLoD1d37pPS3eZi2qFrUi5SoBtyRQSA0UjhEYZcAZKUEzLchWOVKPiro2gh/dd+OUDFCDvxG/fsOvMJAcwmhihNcr0Mk+3K4CfqVa/2LSv1TrXwp5WYnGjfzXinxutz79bM3/57gAMRmkWWg2y+EpChQD2bkAmv5/n7fN26jGtIpZHzfugJ0fbDfpsnsCWL8Z38nYdA9oqskG/7YCWrsDeHzfP3DLY1OzRuDk9pPxv2t/gFdGXoasyem1C3ay3/Z6Vlj6a2UWFRWV9C6ktNvgn+P+f4epwlBjKsQ2sUkQBJ8RALN3AfLEyXlr4Rqq0kbbBiAuVUAxd6BUNVDINchLHAK1FAYZcQHGBpoko2meBz+4+wYklanZP+ADKz+Iz6z6LJ4desZQAaAWFwa2pFgO+EtdQuzU96+E9a+G/C/Xos/UAqC8JCDLjQzDyN0sJI15oYD/z7+X4BMCRBJqqDkSBVN3DknAaUyAFqn0KyT1CxFBdm+A1E7CJJUVEAwiYHUBzfMkbBzagJ/ed/OUDdTV676PC9rW47mhZ3n78kLfuVKW34n011wEfEtRD6Wm/pzI/1Kt/+Eg/9NTnu0jKaGG+EgAk32wc+IAdoVAqrfDG5ZaxJAaV4uyOS2hPqAYCThRA66IwPreZs49HRfgBTgCFFHGvJ4Q/vf+3+LQcP+UDdiN59yAhf5F2DG+DSIRbb93qZV+pcnn8pYQu3EdyQwH3Gyy/lpCg9AshDztnjBsNgqxywKogl8IE68QZAqAFph05QQF85GAWzXghAjM9gDpb5jeOTiVHRUmq/GgehBqpeije3HlbT+fsgFrDjTjD+f+AePyOHqjfRCN1kvp3gapa+UU/KVaf81R4NG99acFrHKplX+l7P5Lj6Qeg0wBeLSg4CeMABQUyQLkWzKo6eo4CLMJmNMLWy4JOA2CFapJKLZ9OEUq+KdlNQ01MwIsgqp/noVL6/C7J/+EZ7ZtmLIxO75lNX5z1m/x2vh2TMgTtgadOtws1a30Lw4cFwHFEjuMuK38q0Tvv8NJ/luuhah/jxDyZwFIIRfAQgZC0Drm1EWZaSkxATs1UMglsFMEdoNI+I5BGncFNNMNoGbXIKJ5INQm4G1M4hu/u25KB+3inrfhypOvwstDG6FpWjowmAHYHMtfeb/fXRlxab6/m9RfSQE/alN7cpiLAdYdSDO+d7CAkS/oAlgukubPmTwOXQHqMHhUihqg1Ln1p9n/KM2iOqNBCFKlwYKxWpA9rbMngMd3Po2fP3DTlA7ev6/+PC5b+S94bvDZzLUXOd+9MrK/GtK/VN+/UsG/I9n/T19Xyq+I1w7jxUqBBWKKB80lCYA6UwJOScAtEVAnm4jCSAkavQLNmoDURhxUhOKLontxCFfe9jPsHzo0pYP3ozN+iDfPezOeGXoaEpGMCZhh7N2D35nVpDYOVHmBv1J9/3Kt/5Eo/1OkRTUqOlUAuQ2/KBEyZbp7JWBPAs5dArdEUJAMsjwhXnxjWZabqhHgQUFNRKhDwQgGcPlvvj/lA/j78/6A4+tOxAvDz8EjePLq73wLpwpZfurScubv/1i+9K+G9XcV/KPugTTbrD+fw9yiUbsVvwWzAOkLpdHcTjuVIwH3aqBgANCc4E7IIKNZ6KReynAHjCujqwAhiQXLw7j92b/gtn/cP6VjGPaEcMf5t6Pd047tY9vgIR7b2UsdL34rX7a7tf6F5lAFA14lWX8nc2u2xgD4VcnYTy5/FiCfNMioFsq1ziXEBKg7EnCqBkohg4xzkElSSJUKp68KKw5SPdBqxtE2348rfns1BsYHp3Qgu2u68ac33oGoEsX+yG6dBKT8Fr3Acmmnlt+J31/NwN90Wf/Z1vvfJZvBwgD5ME5s5YHjzSOcxARQbE0/daQGyiGDDDBYVwcSkqZDvkbfGgsQDH+gYT5wKH4IX7nxyikfx+Nbjscd5/wZ+2OH0B8byCgUsiPX3KxJdcFfqoIot+FHqdbfCfhnpfxPf047bOePAWSzBaEFV/bRskmgFDVQbBCKBQAzSAGZ1j/dLkzQLEVCDGESklIUPStrcNMT9+OWJ++Z8sE8b/65+P1Zv8fO8Z0YTYzpJCDkXC77GojKgL8w8NwF/ojLDlKlWH+bOOZhLf/t1DwclgIXvDClkoB7l6A4ERRSBDk+v5OBtbQKMwKDlq25RcprA7S6cXQuCODLv74K+wYPTvlIvnPpO/GD036EbSNbEZFj+kcU05erlIAfLamaTnPsxzuJ+juR/o6t/2EazS89DlCJ59DKkIB7l8A5ERQjg3yEkBsEtOwhkMoKiIYLYLQSJ/zz1s1XMa6N4tM//+a0DOonj7kM31zzTWwZ2oyklpxcQUjdyX5761dc+rsN/GVeapddjmkFOvuW4PvPZvlfLkmQycGfrCIslQTcugTlEIETMsghhWyXILVc2PydmkqAcFcggoUrg7jvpcdxzd2/mJZBu2LNFfj8sf+Olwc2QtXUdAzDDfir7fdXKvDn1JUpV84fjmpBK+L/FyKA9BP5IiCNpcaKd/LRimzT5YYECg9IYSJwQgYFCYFkqQBYAoCWDTz4Dr6aD3LNMHqW1uNbN/0ET21/cVoG96rTrsTHV30CGwc2gGruFueUA35HYKpgwY9T37/STT9mvfVnKW2GX9V5EDDnCVTVNE1VMdlVqDgJ2PpkLuIC9gE9mndUnaT/tAL/Mqw/LCnCVHpQ0Iy4AC+wEOHtmECojuDjP7oc47HxaRnjH5/5v3jv0vdhQ/9L5uAR+5StrRpzZvm1gulFd9K/kr6/W2uuHQGxAl7ZqlKOYdjsEGwXAxA1VUsaCoDkThLb1X2ZJFAsLuCUCAq7Bi7SfwUvGM28Kun9kSfXCcBUAQIkqGISbUtF7BrYh0/+7OvTNtC/OedGvL3nUoMEaOYM0Bz3XXRe5lsMcOVIf6fWP1fRlN70w6n1n3W+vT5hOQFoVIZNxa89Acg0psk5usA1CRSLCxRyC+zafBVWBUXWADgkhnRFoGWjdC1dJmz0DUgERrB4VR1uefwBXH/vr6ZtsG9548140/w3Y8PAS2YFmDnRaXngr5TfXwqRlCr9q+X7z7rgn27QNEYAOoZhs6TfngCSakxNqhoIKUir1Laev3hcwM4tKI0IssnASbrK+BzcGaDmLd1FWEuvDeC7+bKUIM8MEJ4aVBoGsXhxIy7/7XV4dMvT0zbef77gDqzvfhMnAeYKpIggOz4wHeCfNul/hFp//tXZ8Mv6F5ZpNIsAqB0BWB+UaJxGtZgaJaJgKw3tGngUiws4cQucEAEtmgNySAj5CiYFZKwR0ITUJRX5+3rmjaGhPoAPXf9lHBzpnTbJ9+f1d+CC7vU8MFiszXglwV+K31/1wN+RbP1h7DhNYzp+45wAJDcKIHVVJEQQkUeUKHwkz0Qp3IjCbVzAzi0otQuwO0KwuVGzlbigTboDovm3rgYE6oEqxdGyDBieGMf7r/38tA06KxG+e/1deGP3BZwE2N9G45OsBg1lgt8OiE47/Dqp95+z/mUcXv0SjCDKMGwhAOrUBUjtpaXbfy0CQbOxxPZ72BVTA6USQTEyyEcIrqoCLVJKSKcGqdk2jPDgYLo+QPMi6h/EolVBPLZpA/7tF1+bPubXP9s9OgkwJfBy/0ZjkWPKhaOVAX+5pb6VyPnPWf9ikpArgAmGYaT3xXMZA9BvUTWhDBeTYijau995gNAJEZRCBk6IId+SYd4OVTD2EtRSbcPEVJmw/hj/nehiwIdEXS9WrmzGT+/7I354/6+nNQJ8l04C6+evxyv9rxjbJJK0L1B18OdaVmfgr1jg70ha8Wd3JLQRhuFSg4BMNkSUfnmISjR38GyUQCEScKIGnCgCN2TglBSKxgNIVjxANPwsIhKjZJjbPAFySx8WL2rFl274H9z/0iPTSAIEd7/pbryt52Js7tsEazrXip/sa14K+Cvt91db+ufdnOQwK/ulbF4OUma8I05jAFanPlX/m5AH5WF2wYi5+iwXoM7jAk7UgBtFUMxFKEYKpRADKwjSLJkBnilgCoFlB6hHVwQKPN2jaG2oxweu+wJe2bt1WifCbeffincufze29G+BqlpqQmjudS4V/OX6/U7Gv5rS/7Dy/U1jRYkKOoQhhmFY9vrMBqlQRM+r6rh6SJVVY9IXGKBcIBVL7dmrASeKoJgqcOrvFyIG634C1ianvFRYnGwdxhRASg3wIiHNC9kzgfrFSd6L4dKrPomB8aFpnQ+/P+f/8JGjPoqt/a9CUWWjSIQzAKka+N34/XbS326tfzWl/2y2/ixFzep36DhljSzVwldoMjhQcPWNclAngLjKJ7p9hV9xNeCUCAqRQSFV4LgVmNtgYLYrQCa5lFl+tp2YUSJsqgGWHeDrBfyIBwfRtcKLg4ODePtVl0FW5WmdFD8/62f49PGfwfaB7bpfF+EkYFwgUhXLXwm/v5KLfY4Y6w8zNpXQKewgPZQXjJb7hELATwUClVGlV47qVkMSHAXw7NSAU7fArSpwog5KDgbaEIdGzP0FzE5CvKW4aLgEgupFrOYgFq2owZObN+K913162ufFNWuvxuUnX449g7sxmhw1lYAGQisHfjdy3o3fX1T6z1n/yfFg/n9MV+3jWi9yi4AyrpSYG+KCaLkpUGhT8MzQewINfs4qkwECknFekme9QU4BYdZz8r+G5GWk3OdO5uqJ3fbFRc5fakAw/Z7EYL0cYtMEnQcI5MAoOoNdePi5lzAQGcAFx79+WifH2fPORp2/Hrdu/SNUQUWtt85oH01pVjO40sBvV+dvl+/PfS4qKv2PhMBfekyCIuReGcodyv9Cxn79Lp/pCqgwVglrKfDkI4AUCUj8viQk/1r/u4Pzgj6253g6kpwDzNJIwC0RVJIMyg20GAqaTv6Z4oRUCJW3F6JQA+No93bjrqeegChpOGPlKdM6QU5tOwU99T34/dbfY0wbQZO3eRIMxDn4s0dTKxILKghwO79f/ytJk5Cp7JjAKUrZtap012LGeQBB3fDsUsbU+9UfYDILoOQjAMlGSGkmc/TKh+S9RKSrUjLROggaT36RjIEkltgipdkkQHOmTuqiW8FrlaPZg26dIEL2ZkZ5GJw463xWGglQs1+AsU7YaCDCMwIGjRJVhCbqbNx+EKuS3bjidz9AQ6gOn3zjB6d1krx/2fs58N/8lzdhi7IZK+tW8Q5DViVQquV34/fnk/6s8/GQMoRoJGpMWY95rTVzGuvXNRyqRY0YgqIpc9Y/a06yOaj10n36X30mhrVCekkq4E1Ry+MDif3xXaqmreIdczWalwSsYMwmgnQjXodEUCoZ5COEYn5o/mso2L+O5nkPYTJVIOjynxcLpQpvFB8vF5a6erFUJ4HP/PI7qA/V4z3rLprWufKmhevx1Fufwnn3nYfNQ5uwvHEFB5TRHcnZ4p5ywJ99fdl1p7ql3zeiz10/8PbFl+L1HWdhce0ShKQQ3zl5y+gW/HXfA7hn7z2YUMfQUdupc4QKatMb8ogI/KXmv44PhY3YAbpL/5PVATTrt2SBQGBGjTDN63oDMWW/9lpSluGRPKBJmgHKQmogNcDZasAJERRSBYXe16k6cB4tLjJBiCWFxlW+WW8vGL9rojb5NKYC2PlkH2RvFN6FQ5ivduCDP/wywv4g3rrmvGmdMCe3nYwX3vY8zr9nPV7t34KlzUs5nAop4apYfv1uiXh0qz+AyGgUFy+/GFccfwVWN6/OeeoFOB+fPeYzeGDfX/HFp76IF/e+AF9DAI1SA3cVQCqzO/CsPSR9tiUVRgA7YZQBExt8QyjoTE/eROWgvCM5KoNtTFNKmW++TEH+bIGLTT8dpAC1jLxB7q2cg9VFpP8J2uRnFaiZDTCXDpt1ApCYW+BF0j+CwMIJdNa24B3XfAb3b3hk2ufMotoePHvxM3h919nY1reNqwCBiDnXtpLgp5jc65AtWjokH0BkJIqvn/J13HrurXnBbz3O7ToHL1zyPP51zaeQGIvh4MQBeNn2aelNHo8868/HSCcAdUzXRIfUHWYsLx+mLUayICenb0EcotuSvYl0KtAJCTir93dPBIWCNG5TgE4Ioih5kCwXwKwKtIZUaaqVmGCkZwQaQDI0gLqeBJr9jbjkqk/ioVeemPaJU+OtwYMX/g0fPOqD2NO/G2PJUR2YUlrJuAF/LugKzwVm+XvlQ5BHFPz36d/DN9Z8w9Xnvn7dtbj9zX9C2BfCwf6D/NyiSV5OwX84rRVgQ6b269+nl24Hd6Ts18GLWfrbmgpM/fToTkVCOFZ8R7inNkgT1DaCb5Xm+dKFdpH6wgFeYuOvu5P5JacB7VwGS+CMx6qI9TuSyb0GUpdAE3V3YBS1oSDUYT9ufOw2nHnU69Dd1DntE+iiRRdBEAXcve1uRIRxNHqbdOCoGcNQDPxF0310EvyHGPiHZHzvzKvwxdX/XtJnXtGwAh9b/jFsGd+Cl/a8hIgYQZ23DmrW586rCA6nhUJM/AQo5I3yoPZ37RozZErMn+yLWrMAGQSQukwZm2KZj7P9xQdJFzmv5rjwfH4qWgSElglPbYjAeerQGRmUSgqVHoTsazAZ6TBTIpr+CNVJwDeCulAN5GEPfvP4bTh95UkzggTO7DwTyxqX4Zbtt2AoOYTWQIsOptSGIBWQ/Sb4+3TwJ4eT+O+zriwZ/Kkj6AniXUvfhYZQI+7bfR8mohOoCdRichlb4f0iDpdDlEQokgL5UfV5ukn7KVfvBuhTKcAUARSMAWRr8FRfgDH5NeWVRDzJfYx8hRk0c48qR25BMdcgv5p30Puv1JLfMkGf+X5mOzGzMpCYbcYpqxiUDJZjfQQmavehdbEAj+bH+u98GE9sfW5GTKZ3L303nrzoH2jxNWPrwHajdJj71/ljO67Bn+xFYkgH/5nfKxv81uPTx34Kz1/yPI5pORZ9vb0YUYf4++WbBofdMmH9aybjCuhObRPDLDL7AOQFTfZqQOvvmuWnoO1VN8QHEpxlDJBqRSu08m/n5a7E1zkZOCeEqvyzRp+JRcYQo0x4ssaSxQQ008kSIVE/xuv2oW2JQQJv0kng0S3PzIj5dErb6/Dy21/Bus51eK13JxKaPOlfU1Ie+IcT+B63/F+o+Odmm6lu+KeX8K8n/xvUUQ19Y73wCr6MaXI49ghgQWdVd6e0fdoGE9taFpZzsF4sC5B6YS0G8HJ8b0xTzaWwkwDVikbtnRJBPstSPGjonhCqLv/NyBlbI6CZjUQ4IZBU/wDCB4stHmLSQOBKYC86lojw0wAu+PaH8NeNj82ISdUabMWjFz2Kjx77URwc3I9DiYPwsGg7v9zENfgHkv0c/Mzyf6GClj9/gPA63PaW2xH0+9DX36vrX8UIbB6OWwYSM+28V7/gg3iFYzYTw3mBkS8GkF0SLPJoooZ+2oX1NSvDLcZOGbnAyxdgK1bmW4ny3tLjeuXECqgDErCUCPOFQ6niIDL5mPkg2+gz7h9AfaAeyrAXv/j7zTihZxWWdSyaEfPrwoUXojHYiDt33IlheRjN/lT5MLUEB4tb/vhwXPf5ddl//Bem5HOvbFiBT6y8DJvGNuHlPS8jJkZ5xoMFNkm5U2Am+f8egZdLy08qW+hL9GoYGQDNzv/P5wLQAqaUkUC/sl15LhFPQPAK+aV8AbfAjSKwUwXFlIH7Rh+0jFvxU2dcUL50GDwGALO1OI8JiEbpsKb/Lmg+TNTtRfNigiZvIy668hP441P3zJhJ9qljPoUHL3wIzb5WvNa/w9h+ihhxgeLgn7T81ZD9dkeTvwl3rr8T15xzDWuVjf6hfq4EWJEaOVzUgFf3/xNJaDvoswyrMIr88k1axw1BrG4AjwPQPdrT0f5YhkXOC1jqrCSz8E6/hQt1ipFB+aRQoh7IbiJivZLpLceR7iTEqwVNfUXSDUVE/SLr7kDdbjQsUtHqb8Gl3/9X/Oqhm2bMPHv9vLPwyqUbcfaCN2Bv715ensuKhgidLMLL7/PHDPAf/4Vp++yfPvbTeP7SF3BM27EY6h3CiDoCSXdnDhcSUAZ0I7+XPm3x/7U84EchF6CYG+BBFHG6DO+r6Q6L6cUZsE/1OXUL7NwDJ27ClK4EdOpdWPPPlkYiqWtASdZnpimaFJH0D6EmGEIg1oTfPPpHhAMBnLZ8zYz4aiFPCB9Y/n42HfDga3/DqDaquwQtPO+eXeTDfH4m+3nAbxrBnzo6gu247KjLMKQN4+mdTyGmxFDrr53VLgHLzDEFltigS4CH8R19EFK7ASnIXAWYI2FFm6mbXQ8Q0F+2H0307MDRwS6PX79LpgCK+fPlE0HxeEFhST8dpEBzXS1LoIZMVmebS2+JWR+QjgkYrYeR9A0h4PehVmnHzY/drqtXGW84et2MmXisHJel2/605zYMjg6iLlhnBmRohs//3bOmXvYXOy6YfwFOaD8Bf95/O0YHxyAFRXiI11Cvs4wERB2LiXgc6iPqs9iK68wAoJLH/9fsgoDFFAArCOrV/dVlnhM8pwVrgtBkzWLoSicCt2RAHakD935+oXPQvIFUh/EBi1mZPA+z/poJeqNSMCN1mHqpJkH2j8CrD3Aj5uOOJ+/BvtFeXLjm3Bkz+VY1rMQHlnwITw8+g1f2vwzNq6HWU4u+xCHD53/9lfjSDAN/6lhevxyfWHEZNo1vwit7XkFM1NWAd/apAcEjIDoQAf0L/QMGwIJGDeDRDvsAYCECQAEFYCwsiOtu6gr6znBL2Kh/16wvzAVqIdleCSIoBPPSrX6JAb8Ch2ZGPiixnIdkXSZiXbhC00swYQbXmBJgJCD5VbSKi/HA83/Hs7tewttOPh8eUZoRE7DOV4ePrPgwEvrkeGjXQxiJjkCJqdPu8zs5glKQFz2x5dn377qf9yAIBUKT82GGkwBf/KPo8n9rHHiIXKkPwkEYPQAKEUBewGcQCqzrAIyb1zwpu08T3y4+2H5Re4+vxg8tquScheY5rVCw/l+wcaOLpf9KGx2hgqPqZlVhigf4WgHNiJynA1Cq0WsBfE93avytmL+zHgxCDGKkDoH+hdi0cyvWrDoGf/r3n2FeY/uMmpD37L4Xl9x7ET6vA/9br/vWrJLSLwy8iPc/+H68vHcj0KT/JzVDocl87R9mjvwPCYiMxJC8K/EabsPZJnYZ4Fk78KRJBDLyrAMoRADWBUFSFgGw2uJdOI5cV//xuo83ztMvUNRo1SSQfPaUuAJgOWRQLilULR5AC/evI3SSBBj2iarTCd9+SP+p6N+Yk4FBBOwJGolDjIURHtBJYM8+dLTU4fbP/QQnLVk9o75zRI7wQOFsPT712Kdx/XPX8RnfWtOGhBYzhm4GEoEYFDG6bwTaL/FTvET/Tb9rIYzdgKwEoBRSAcV6ZWWnAlVOAnvog7G9MVDd+jP/g1tCmg+01JXVzFdHMPlBqMMNQKijXgHVAnvR97ZMIo0Yy4S1VA4t1VlYJ0IimXUDomakCdkehZofqj+CsdatWLmoA4NDMaz7f+/ETU/cOaMm5WwGPzuuW3ct7njrHdz09Y2wMuLApIKbSb6/V4AyoUDbpxuRvfRvmFz8oxXy+fPJ/ULA1/L8ZCdvwjD+Ib8q74xF4xB8lpoAmksExIxy56wntyv8MYnACRmUSgrVuBWPMGTuO2hVLan24tRcPMSTBea+g3xrNsnQZEQ3S5o3idGWV7FoUR1qST3e+f1P4Fu3Xou5o3IHq3zc/vbtXAX0j/dCIt4ZRQLcXugEEI/q6mQrdtIh/INjM9PSF1oL4FgBaFkKQDPdgkPadnr3+EgUWjLXkchHBFYyyKcIiqmCYn3hp3wFoKOQos1nsqb/LduPs5+8VzNbLyARrgTAqwWN+7gSgJ8Hf0abt6Jhvor5DYtxxY3fxnt/8GkoqjKH3godi+sW46H1D3MhPa6MZ8ZypntusTmR0CCPKSA7hbsZJk1salkKwBY4oh3JIDcbkIoNsFKDBF2kvDvYGIRYI0JTtNz+feaNkPwnBty0/s5+Bi3b3y+nRqAckqE0b02G+YksWYHUGoJU4QCx+KGaEUlJBAbg83rQIvXg4RcfwT0bHsbZR69FY7h+DsEVOBbWLMQLQy9i06FX4Pf5Mxq/TGeGQAxIPM0qv6oTwKP4T93j7zfjdElkRv9V2KS0xCLgL1QTUKO/4XatAW+QFno6/PV+XhNQKJ9ejAiKkYFTQqgUMVQ2LmC7GCtTCVkXC6UTgiSLBFIVhEbHUUIlJAPDoN4E5vmWYuOuHfjNP27FsvZFWDlvyRyCK3CwHom3v3o7PH6Jlz3PBBJgsbeJvgjwDJ7HJvrfpvxXkJv+s514goMAYPbyYNX82hPYQW8bHxjjTQhFn2h5Yf5a/UKugZ174CRmYOc2OHEfKgl2t++b+k7UmhIUzN2YeS9BwzXQhJQLQCeXFEuGayBqAcg1Qxhv24aVi7qgRkVc/L2P4PI/fG8OvRVSAczsJTXZWAE5zekAwSdAHpVBBzUIu8htHIsGJvOV/NpaHbsgIJCbBUjdGMO0YifulV9To7HhOKjH+dr+FBHYxQnsCKGUDr/5wFnpm1Ow5/vc1CrvYQCfpEbI7ChE+CIiYhBDeiERixMIENUQZF8Ew81b0NbtRXf9YvzX767C+u+8HweH++ZQXI7cNnPc6QVPWanc6bD+iaE4sBsRupPey7E42fcvOwZQcCGQGwWQfWLGNCH9LbeQHfTOibFxaBMaJwHqssmHnSpwogwKgWsmHG4+U/p7WmeUYNY1cOAbaUGeIWDAZ23GzcyAxncl1iDSAG8/Pt60DWL7CJZ3H4t7n3oYJ/7Hm3DHM3+ZQ3KJx/6+Pt5gy2gmQoxuQtOkAti6f5b6k8d58O9OKtMtHIuZ6T8NBRb/uCEA5FEBatYtqL/9zYmDCcj9suEGUJte/jZLee1UQT5lUIwUXLX3LhPYpZzb9ruY7r6WrrQ24gDGPgNmVyHeSwCmG8B31uAxYEI8/BZr0M1D62tYuXApRkaSuOi/P4Qv/ua/5tBcwnHrIw/w0JqQCsNMV8Mp9t66/I/36ta/V//9VXoLJnP/1pujGgC7IGC+WF0qEEgw2Ta8FjFsQjM5i7Zinr/WZ0xELd9JnHf8yQkAEmeRynzBRKfsVsFWIHmAnu9zoqAiynkfYlRPUjK5ZiAdGuU1BJgMDBp/gGgeqL5xJPxs889Wnambcd9Td+Ivm5/EmsXHor2+ZQ7ZDo77n3sUX7nxCnhWCpC8Gu/mDLNuI93Waar2o/Xob5SkiByIApvI03QD/bYp/2VLADDvLsClEkChbEDqxqojRhg7Kj3yep/kg6dFn3gJzeVin2Kr8YqTgR0plEIMJQ1QHrA7UhQFQJ9ZK2DpIyDAnISGWeCP0clYAkn1H1QlKGICcZYqFCW0+5fgxZ1b8MsnbkbI48Wpy9bMIdzmeOa1DXj9Fe8GqZNQuzBptsgWjQ1fUt2QpogA2BCLYRGRPVGofSqEx8Tv0BH6gv5QHXJLfh2rACcEgCIkUIdRbBK6cBGtJfWBOr9RuKLZ1+UXS+s5IQOnpGBHDJW8uQW73eiklw7ndBs29yIkWkZ0h89Fcy2FQQiGGyZQiV/LRHAQmjeOdl831JiIOx6/GU/tegUnLV6N5pqGObRng3/HRpx8+VtAowTtqwKI1B2AqPp5Fob7AlainQL7wqy/7u8jujfKVuPsok/Q/9DvbrRY/uyaf61SLkA+ArAuGGLFBwf1WRdQFyqv98IPb5sHbAehSYAWWdVXIhm4dRmmPAhI3bkPdo1EsnsKEGMn0vRENPYbMUuIU6QhpAqL2EYkHr4RCXMJwlItmn2L8Myrz+BXT9yOAFcDJ86h3jye3bFBB/+FQERA9+JWjHZuNdqe8Zs5wYRJAqi2wExZ/9jOGNQh3fo/KV5N++mDJgFkr/hztRbAaQzA+tNaFJT6PYwBbEIP3gEfDfkadKb0CMby1hxwlkcGpRACLUM5uAG321gBdRaoNasCc3sKEKOlUDrkyoFvSAFztIzXaaarwJqOUjGpq4E+/aeKNl8P4hEVf378Jvx9+4s4pnsFOhpaj2jwP/faRpykW37oln9BdydGurZA9cUhakGzmatBrFNJAKJX4AY1sjPCCn776UP08xxzxoRIWf7s4h9HxS9ugoCF3ADRjETuJlSolXuUdR5ZgqfdAyRonl4B5ZJBYYiTKQgAlhoApnBeEUjtWo6nWnBbawhSZEZI7pZs3FdFuoJQ0NjGHqx6cEhXBGOo8dSiyd+DF7a/hJ/rbkEykcC6lSdBEsQjDvzM8nPwxwQsmNeJoa7N0IJjvMYC5opMIgppDTwVBMAX/YQERLfp1n9ChfiU+AN6iN5lBv+SBeS/42nqZpQLuQGpW0jnpk1kKd5FBRrw1nlAAjpzKYUDgk6JwJ4M7CE9/T0BnY2F07UFqS5DJKvjMPunmSsNiTlzSIoQiDUuYFxvUfODSgldDfRzNdDq64Yg+3H/07fj1hceREttE47qXnYEWf4N3OdHTLf8nZ0Y7twENTAGSa0x6i0kY4en9C5PFgKgVQQ/CRAo4yqiO3XffwjD9AH6WX0Qg+ZTCsl/xxPPTQzALhhIYBQj7CKKEFSXqOukqAfeeR4euCgGSOqSDMolherfUDnAU5r/zBbjo7GAYKrDsOkGGHEBCtXar5vNXWFSDRhLDAUk/YOQ/eMIiEG0BBdj26G9uOXRm/Di3lexorMH7Ye5W8Bk/5qvGuCfr4N/pH0TlACz/LW8sIpXW4rEMuOpsVeiQKsr/9kWEn4JkU0RvtZGfFy6lvZqKesfLyf4Vw4BAPkXCRHul/TRF7EUl2qCFvYGvBDrJdCk1RVwk/JzufX3DG3gVsrKQY0Wf5WRCaBZpGDsB2n0HDBIgP/k1kswXmMqAoM0BH0e+6BKcciBAWhiAs3eVt01aMfTm5/ELx6/HcPjw1i98CiE/aHDDvxW2c/AP9ymgz84DkkLc6tPUyXXAsnMAAjVlf888BcSkejTVdreBMgIerW/aJ8BW4g3WY6fTQCqWy/VrQsA5E8FEkssYA+JEqir1DeQEQHeTk86WOU2yk/LJISpJIhylgdnW/miqUEUbjKab5NS3mzE/Iw0nTkwlYJgxgk0Nts9UP0jSARGdKVA0OpfCKJ48OAzd+G3T90DRVGwZvFxkGZIM9KKBfxSsp+BPzCqg7+G91tg1l9IWX+zHwOvAZiCACB7fzYGExuivNJT/JvwHTrIu/6kfP8Ecvv+W61/1WIAdmTA/q7XfZVnhYXkLapPaxR1CmUBQS2hmVvilZ7yyxHbZOZa/WI+PKVlpgazz5m9J2G6Ws1sQopUizbBqCoULOognUEAzxQwaZsMDkD2TsAn+NEW7MHA+Bju+8ft+OPzf+Pf4MRFx+oTVJi14M+0/PMs4K/lnZioZIAwBf40CZBM+U+rZf1Z0c+OKJQhGaSf7NAe5pH/VhPsyazov3UhkLsMQ2meCT8ECylYU4JsU8JBOkiGtRPoW4R+nUhbvRD8hH/M1BbzTiL8TsBNq6AUKgr4Qj58maC3kkk2RRPL4ySlTkhm2XAqRUjT0SbjuqVjCToRqJ4IkqEB/WcCdVI9moILsbN/P+554o+448WH+blXLzraAMRhYflr+VoLDn5zubUh/82OTKlWbUIeNFQ48KdFNER0688ia+Qu4csYpxth9PtPILf0N7VOB9V0AexiAVYlwI5GjOMpoUFYq7Qr8wXdFfB1eXnXIGSlqaqx04/zOgBSMWCXky50Wg+ggTpyE7jUt2QJBJD0VmSUWJqKpAkhJWnZ48T8G3yfQgEeKLpbkAwMQhNl1Hsa0RiYj1d7d+PuJ27D7c8/CFmRsWreUvg8vllk+Q3wD+WAn6bBbyy6oqb0N8DPyY5UjwB4fYFfQOSlCF91KGwVH6fPaV/XH5lvAX/Zvn85CiAb+PmyAqyVuEwP0E30BPo+bZTy7Yt8LV5e0GBbG1D0ilYuzTeVdQClAL6gpc93TpoV/LXWpxIjYJjPRUhXtpngt9YNpCwd0bz83AmdBGQdLCxt2ORrQUOgG9t69+K+J2/Dzc8+gOGJESxs6UJ9qG7Gy34O/tbNUIIG+NPBPg8xV12aP83dnCfBn9nDodLWXwpLSOyJIfFa0tDSt+NjuuAfAduaLzPtV3Lqr1IugJ0KMGIBSbxEFLFFO0Z3BvZTeNokEB9xVBtQKiHMpI1CqbvmLK4sfSboCxcO0ayAYapmYHL0yGRNO0mRhQH+tJrg9wu8doA1KpFZfMA/Bk1Q0Cg1oSHYjQPD/Xjw6Ttxw1P3YPvBnWgI12FB87yZJ/tZhV+XafmDk5afg19CLvjNoN9UgJ9ZfjWuYuK5KG/wJTwi/Zzu1H5usf7JSlr/cgRMdgowewMRv4WxKPmI8LAQFtoCtQHUnlgDJaZkhCuICx9SqDKwSZ4WCRTVbSnmpi+Bk85DefUCzeVMCpoRUWUige1ExEq4J3cpMu5n9cSE3a+CP6bqQ8s2KpGSYXgjzfBEG+BJhjAUG8XYyC4gGMZZq9binaddiLeedO60Lj/ODPh15PH5LbJfmFQARpdmWnXwGxObQgp6MPb0GJRRHR8JoRe/0M4yscZwxXYniaPwhh8aptgFyJcNyHYLWOL4IOlFn3aK9mYc0ieRn8DX6oOWyFMbQNz795W39JUW/KVbeMeW3g742SOWXUGYsaTVjBNgcoER0kuPjfiBmmpRpnO+SH06aJK6WzDIAaVICfglPxp1ReAXw3hlz0bc9cTt+N3T92HL/h08fdjd3DmlaURm+U9mRT5xwst7c8AvGtH+/JZ/asDPpX+NhNiuOJLbkjzWT24ln8c4fV5/uMUEfnbkv2zrX24II9vvT6kAn8lYfvPGft8pnCP8jp6MN3oHvKhdV8N9HTWq2TZVI2VGl4VpdAO0stuGO6Ur6ozT8v1uuU9lO+KmUjRaShEYwURNo2llkFID/HfVeB5/jMpQhChExQcp3sAVgTdeA0H/eyA2hOjYPn2GeLFq4VE499gzcf7qM3HmqlMQ8PqravnZqj6q205e299awPJPM/iFoMjbfI3/fZyDX3xWvE/9q/oe/eFFJvDjFhKwZgG0cqx/JQjAavk9Jgl4La6Az3QFJnStUUs+QR4UBaFW8npRt7bWSAsm4bizIqlCuqkUkqhGz0G3nYtdA7/AY6nvkt64FFoGEfBdj8zeZIwQNNMV4I9pZJIYVHNlok4kGolC08/hS9RBijVAjOmEnwhyIjkU6YOm3+ALY9X8FVi34mTdVTgFpy47AQtbu6sU8OswfH7/GCRaY1j9lM+fLfuFqQn4pa+5h/Amn6OPj/JaGf3qjuGn9PVU5wMYK/5iJuhT0j/V919G5q4/U04A+WIBYlYsIHVjrsBrZD55Lz6Eq8W9Ivw9AYSPDXHmYxOuVClPyOwrRimlTbmrSkPqhMS0vDM7RQRMEaTORXSga1yspYiAcDBDVXmMwJiGlBNCWiHo31Ejif/P3pUGSXJU55eZ1d0zOzM7M7s7O3ugPaSVxCGhA4lTEOYyhgBLRmGbP9jGFo7AOGzCEYQdgcOEHXaEf9gR/mGbHxBhc5iQBFiS5cCABRiEJJC0OtAKIWkl7S3tzF6zc/VRmc/5Mqt6cnKyqmvu7lHXqjXd1d1V1VX1fe97R77Ud2oNRKMHSqQKqps1EfTr1736qxLGZs6CJDLgAoa374Yb9l4FbzlwLVy//2q4eu9r4cCOfUuW/Td87sPzwd9mlj9FT9QvYPLJKWgcbphpPfmX+WfUMfUf+t1L9WPaAb/r+8vl+v4rSQCuEog8V6DiBAVp3VH+Xv5lfAd8MDoqoO/GfujZUzHzCrhHsly/vp1IYTlzEqw06OcBf8H32fyAJzozGyd2hojauANJsHCOEFJlMOceGNWAc7MdKz5r3AkKGhIRiNoAiHovRHGPIZHJxhRcnB7Xt/mUNh+9MLx1FA6M7IXX7b4MXr/7gFYHe+ByTQgUTNw+uC0zjvDgcwfhps/fqmU/gz27d8L5EUr1XWivgF9CtGKzgJljs1D9WdWIff6Q+B/1ffm7YGf4rTtBv5on/WNYRupvJQnAdwWEQwJlLx7QaxiNgWC3sR+KQTHCJhgM3jQI+rlpK553NKuZ1lsMYazmJCOLHk+wXNAv+G2YGROl92hSDJYA2qiARB0YkKt0nWq6BGbq82Y8QRm3gHw+xRtmPW/0GUIQpArqm/TrMghV1ptSMN2YgYu1ScDaRW3rGiZ+UOobgtGBYUMAW/Xzwb4B2NyrCUW/R0FL+t7dB78LY+MTsHfH6DzZPxfwg3W3/Mbv7+cgJyRcJL9/SK+bYuP4JXw3YNJyfy7qX3esv1/7v+wo9UoTAINwWjB9kE9zFEbYe9kf4lf5GQGiEsHwTUPmAmAVF3VEnTYOYFlgXyToiwK/ZcoQ5hOAOe+KzRFDkxDQdkkygUKWuAQwl15MWygl79stxVReZJ7zWANcE4Ko95m+BFwrAyIErkqJS4FQ10QwI6swG1cBY40JqTFBk6Gm7ore7cDAPtgyuAnObX9ag39Sg78/2/KvE/gp30/n5MJPJuw9v10fxhfZx9W4GeyzF+xMPyHpH8P8jj9tQQA+CXBPBZQ9V4Aeh/m14m/UR9Wn+GFh+gYM3zgEcV3/thiXdVTtSArLnq14hUG/mCCichRPGhy0E2OAQwYKJAEbk5bw6II/dQMgqSVIlEZKHGYfiZvA6vrqSftZqdVAvMlkFSiTwGXFjEzksqS3YWZKAW6+N6femBT6a1WY2vl80+efF/AT833/9ZD9FPSj+TMmfjYB8qT+rQe0xbxHfEE+Lv8KzKtmxD8U+IthEf3+1poAICcgmLoBKQnQDzjNbuF3s2vgRnaYQe/rNkH/1X1mEAQqXPkjXGGiWLUpyJewWQVqxY/bBf6CHqUJQlQ6JRo2+5SZuEDqGoQUglUHLAkuJmMWTNNCZchBpQg0hGANHsUlzOA7FZmyZG7+lkx7MztBKjPVi1w/b2zSwOqZ0O9pyx8pM4diWwT8CBh6v9Tea+qpaag/rTF9ud71U/wRvFvdot8eTbCTgr/qgD8U+IN2IoCQCuAOAfhBQeobcE5rhCF2G3yHD4oteErLt+v6oe+yPmhMNtbmiNtHIixpWXXg5xwnutNjNdOHCYLSgCHOTykaciAV4KQYm8ogzSTgHAGkVlOl20jufcUcI8jmplE3gNa3GseeZEgv2r+CrWvAL13KAyWYOjwN1cc0tncZoX8evsQ+oFUv1fpTh9+ZQNAvJQC10tYfYOmVgEVIhUH2eAE6+CH9U06wl+BpdQPeyoRm76MNiDYLqGyrmJzoosDONi7QXcCj828lVYqx5q0+zyABHy64qlRCbYcdz40pmBtjwOaak6TzGyYmwlQccsoSpC23ko67ZkJUbptvcGh2NKLW3ARcATRVd2SmQePapnBmRy5yamZqhvOmrbzAjOZbT8tPC93X1RM1mH101g7q7dWH8lX2BziNh8DSwawD+LxS3xUtTxWrAH6E8IBJP1hIy4j+2Y/ycV5Tb5bv4lUG1ZM1iLZEUBouBUcOtoXWWQVwrwTglwp6LDRAySEIr+9Aus42IeVzfQUC7WNZAn4L/LS9FjTBT2SQrkcBHinMrcNkinRIRusR4IE69jZLe5mZQBWSIb22oQeuycAe3++nMt/aeB2mH562eliLffEN8bfquLodbL7flft5jT5XPAW12grAXcc9EkjXb8Gz+D0eiz3qOvkG6h1Ak42WR8omTyrrqq0afKz24oJ+NWMSahHpzExXI4MIUkVgW5G5qsACNp3x2LQrSfrsWQtvwW7G4CeRJEMUpimHsu9pEHOeBPaaj0QdmPwTtdJKJks1CiA0nn/twC/6I4gnGjD10JSF71697j5xp3xc/jXYUt8YwuP8s9J9uBpgXS0C8McJuKnB9G9vcgIm2C38drwe38xf4CZKMPz2YX0CGcRTcgEJcOAdD/S1DkKuCOiLRLoXHGwgVtAM9qWuPJtLMzrr7efQxgLybn+XZBIfv9m4cx3BL6dimHhgEmBar7hMH8OT/GF1t/oY2Dn9KjBX6uvm+10iWFKvv/UWxkWyAmUvKHiBhpixj/N7YB/sY0c0kw9wGHzbZhCbtBKYVi3HDLQrKagVUm5LzTyoRRYvrcTxZl4qJ4Mw73PI5t3irEka9miYQyJ5d5ztbcitimhOnrr24Od9+p6dkXDxwYtgqvr36fXH2RH8Ct6sf0NsYmDzg36+C7AqUf+19IxDBUJZA4bS8QIn9bM97DZ2Dxtim/EYmCpBSwJcsykWHjhUZFkOYahV7hGwHMCvF+iXSgbN2gKfEGCh3bNEoBwvEpqv0ckINIEPvPl6zcBPVX4zyoJ/Amw7j4vsIvsS3Iw1uquBOqWE6vzdgT4rWvCzXgTguwLCcwV8EiB34Bgb5tfjJ9SdrMwiOKGVwCCHobcNguhbeRJol2W5dQVqiSXKa0Fki3ITnKBqJim02HAW8NfM55+O4eJDCfhfY2Ad83/nv6XOq8cSOpjNAL8b/FtV6b+aQcAsEsgKDvrrt0EVn4YX2S/gGryZDTDAMwqqY1WIRksQDQg7bLLDA4MrVUhUKH2XE2xcl4UFAoiBLpNuViF9zLfy8x8k81kzyBjoPbna4B+wPr8Bv/7PJPfova/x2/Q9fH8S9PMLfPyUn9vkY1XBvxYEADnAz7r0tIxogfQIO8aP4rXqg1zLfzyH0Hi5AaURTQJDEai6arFTtmFAHgL8UjIFS80wrBUZLMgoQAtS4DCPHBbcRS7w1yDPH1/Q4H9w0or7HVbn8q+LP8FT6l4wIcCgr1/3wK/WCvxrTQBZwMcMpTACF/EBfoqPqWvV+3mPJgEtqWqnaiCGhakToPnSlj10eAnfX2sALSZf33bWfpmEECIH5Blk4QN+DYBvxvRrVUp5fpPqI/tO03dQ/9Q7xJ+ro/KOBPz1DPBLT/YjLHOMf7sRQJ4KwAwFkMYNtuF5/BF/hZ9X16j30OQiqKVV/WTdBFrKWg00iyQ3SKmAb+GXC/qOAf5iyKEVWaxRHIOmDRMa/LMnqjDz8Iy9F0cs+MU3or9UL8ivJOCPM2R/XntvWAsSEGt8+fKCj6H36fi2avn/Q36an1NvVO9hNJRo2pIAavxXdlSSDradRwIrBfYNDfp25KF0VJ92TWcOz9rafnDA/03+Ofm8/HICfpkh/f1SX1xr8K8nAUALt8Bdb5XAWU0CL4sxvFq9DzYxI7UapxpmwsqenT3mU8YlaNPg4EqDvQv69QM/jefnZQaTT01B/VDd5rRGLJr4N7Xsf1591QN/yOr7fv+qpvvagQCKkkCIFHiiBH7Ej/FjeBX+mpkzrc5AnpZQn6xDebQEQl8YCg5iwX/Lmxqs+H5WEpxLGR/QXVZQ9vcJQKlg8tEpkC9p7NKcl9sSS3U7/1P1krrDAX8dWkf81XpI/1aAXKt4QFokFMHCTkJuyXD6nN5/ke3kH8CPqX+FXijxs9z0EGDDHDZfPwCl4ciUDm+ouMA65Oq7y3zgU+CRGnjG52OYPDgFeF4bEK1EcSuSjW+w2/kf4cvqu2AH98Se7Pdz/SkBxIGg34ZWACESKKIE0oAhN2JrCg/y5/jDcADejyPYQ/3ncApNXIB8MDMHYTpfKus8sK+WeuguS/f3oz7bwHP6kVmAGb2yT99YI0gF7Bf419nv4xj+OLH8DU/21zMs/5rm+9tJAUDAx3ebioaqBX1FQH+PsV62H36TfQH3q8vYuCYBisXECJXLK9D/2n6zVarHbkcS6Fr1DpH8m4T5O/XMtJ21hzQo1atq2c+O8BfgG/gpnMWXYP78fX7Qr8gQ33VherHe5xgW9g9oRVDpd7ZpPj0BT+K9bAt/PV6Ke0DavH78Sgz183XbV2AwAkzHVLH1AfpqxgS6yypZfYFmHH88qSX/I5MQH4uNuiTZT+Dnh/gDeLv6pL4Hx/WnaUaTqmP98yL+bQP+diAAnwRYwROSjgihNkpT8Eu8i4MYxivxavsmAzWhTNEQpQqpetB0HY5VMvfd2v3rLp0Hforyix47V9/0wWnACe3vk+SnntaDNGuv+Lr6jvpsch9SL7/ZAuD3e/mvO/jbiQDyiCFvofcHDF8fxXv5OTGlSeBdZnAxzVijT318QquB2TqUt5ZB9EU2VYiwMXsLdpflWX0T6LNl5pM/n4T6M3V7n/Tq/w3bsQX8XvF36hH5j2Am8IbNsLCbjw/+tFRtRWby2YgEkEUCRU8SnVAqDxrEMfwBe5E/AfvgHbAd+8xloHbQYwj103VgFWarByn9F2OXBLrLfKvfy03fPrL66rSyA9Qp4kQ5/rNsnN3JP40vqv8E27u/5Mj+ekHZv27pvnYngCzrXkQBuL+FMgSH2FPse3xYXIb7cY+5BNQSqorQONGAxmwM0XBk6rdhHWMD3aU9gJ+W88pZZdp1156uWbiSr99j7Tw/JO6HO/HT2hV4GmyaT0E4xx+S/aFpvLoKYAnugA94zCCMbfoCnsVn8B5eixgeUDeaCxkzowbUmILaWM2QQmlrCViJA8ZdX/3VuFCnKaGv/+xR7es/oa3+K8r2pqJ+gkNoR/N9P/oXdZ/8vL6nCMi7YH7brqxCHwnh+v62u9HaUQEsxh3ADJeAfLMSnlDf5Uf4L3A3Xgc79bpaItxqNjbQuNgwg4pKQ5HtNCO7bsGrweqLMjfNZRrnGzD15BTUn6vbO2mTdRlJ8rMxOMHv4p9Vz8ivgQ30DTiSv5Hj78ctwI/tDrZ2Oi53jFfaJNpvLebXDLiv6TPH9LdG+a+KP1NvkjcbEphMNk+NGvWnyvvL0HNpBaK+yLRyovgAdolgwwGfLDsN4KGOPdUXa1B/qW7h25dc7AE0FSb8oPgv9b/yHzSEx8Dm92MP6PXAa7eVVxr0a2vwtzMBhEjAn3w0RAQ+CaQzEtHMK+fFFeKj8n3yL7RfNwRnk8tEl21Wb3iQQUWTQM+eHuBaFqoZaabA7hJB5wOf2oZTQQ9lgKrHqlDT4KfUninoiZI7i2L6Z+GCuE/8vXxO3gW2a+8wLGzZHWrmkQI/b/JObGeQQZsTgT+zkICFVYOu5Xf/pgRByzHWwy5l7+Z/rK6THzaXa4LZuyS5zHyEQ8/+Hqjsqpi2UqQImIIuEXQg8Kl+nyw+TT1GNSHVl6qgxtWcWaCLOojmDuGPi//GH6p/xiq+mFh98ACeNZa/lc+P7Q6uTiAAaEECUQ4RlBwiqBie106AOCA+on5FfQZ34W6jD2oJEczaS8ZHOfTu7YXyjrIlgllp5rzvEkEHAF8giF5hgF9/pQ6zR2dtWs/k9BPgV9A05man2En+f/yf5GF5b+Lnb02sfhyw/I0cf78jfP5OJAD/OLkXF3BJIPL8/7L32lUDxyk2IG4SvydvlJ8wmYJz6aXTm561AUExKqC8twy923tM5kDNdl2Dtpb6BHx9fWbHqlA/Vgf5SjLdeC+bm0h0iw3niUfEv8mfyH/XsD0NtpzXtfpxQOq7g3qyBva4gzywk4DVScfqxwQ4LBxSHFICkecW0JQN42w7eyt7J/+kulK+c55b4BAB365dg0t6oDxaBl7mIKv6mje6RNAWwT3qztMjTAUfFXxVj1dNunce8F25/6y4H+9XX8Qx/CnYMp8BT+7HOZbfHcqrAj5/x4C/0wgg5BK4aiAKuARZrkHkPV6hCyau5B9Sb8Pb8DV4mZmzZdojAtrRNg6VXWXjGtD4cFVHO5Nxd1nzhVdsZx7q/0BSv3aqblrIm8UFfh+aFB87wV7gD7EvyWfVt5N7ZgfML9iJc6S+L/ljz+pjJwG/kwkgFBfgLVyCUkAFlLzP0EU8ST6geJO4Rd6gfge24whM6TUzzs1Us/0HKWsQ7YigZ0ePqSyk7kKkCsg96C6reOGFtfaIaJpzVF+pmtGfJqpPV73iXKtNaAfwjLFx8Sj/ijwo705iQLuTe8aV8yHAN1pI/o6I9G9EAsgKDvokIDJAHyIAkagDCgO+zCpsH3+TuFVeG/82bIVB4yzMsrmZZtJR39qyiJEIKloRlLaVbPAp1jdnTUJ3MODKXemoIkwen4KxjTMNqGmLL8djO7texYnsEPB70Yr6szAhnojuUAflt7CGR/SanUkY0G3H7RNA6LmE7EYeHQv+TieAvOAgz1ADWYrAJYH0NUH+DNvELufX89+Qb5S3GiKYdlwDltwiVbtXNqxVAZHBSMVMXkKxAso9m0lMumSw6CtL58/UZOjzR5Nu1MZrEGvQUzsucz57kiuVBm5J6vclwP+5+JZ6TN2FM/g82K59A54lLwL+vCh/RwX7NioB5AUHXSKIAkHCUJxABOIDpoiI9WkiuEZ8RL1B/jqO4qitKExuvFQVpCUjVEIwzLUiKEO0Tf/dXLY3ciyN7VGqywZBn56m86asa2SLdhoX6xBrn75xpg7qvIJmKXfFsfYssfZ6HTvNxvjT4h71pLwXpw3wqZBnKODnywz/3g/yxRnAx40A/o1CAEVcAp6jBkJ/hfeXHheNbSmxveJq/j51Fd6Cu9UBs0d6J3aIQCVkIK3g5EMcoq0RlLaUoDxQNsOSyYc1HYwlvnrVAUtSd2U71ZeW6abDc+NcA+KzMagLyjpkIgE9d4AfoR3xQZs5yQ/zQ+xu+ZS6Dxp4FObG6sceoGUG4BsBgsgq6cWNAPyNSACt1IDIcQtCJOCqBvc75ARQG6gRcYV4B74BP6T2q5vMQJLpJILgqgLpeJxUSrBZgBgSJnBY2lwC1suMOiAiIKu3oQkhBbz+vfTX/N5ZNIOyKKAnL0iQF6V1qdKIjPCsPXnwJPO178+PiJ+wQ/Bt+Zx8IL0mybu+dI89uR8CfZzj628oq7+RCSBPDbgtyDmExxREOTEBnwgI1lRAUmKj/Fr2Wv4evEK+F0dwp7k9KHtQZ/Onp05Hkcd2C9Rmig9wEIPCkAF1o6GmFBTsIoVgCo5oYJLqQFKgn04z9erfQmBnSRMWGncfT9mRmHJCgprUJECDspJzYkDPXaiRW5BE82mb4+xl9pz4Pv5S/QBPqycSIG9PNEIW8GUG4P0a/nR0iB/hx40G/I1OAK2IgLdQAyF3oOR83v0+gE0tzbAy28Mv5W/BK+Bdaq98Owzqm7KRKIOGRwbp8ON6cltRo6Je25yC9TMzMtESgjCgSEGkpDQlroYcqPe5WudLyGmCFWvR6TkXokle9Nsoak+ApxF41LpdTkpj9c15YamVD5ybUhLQo7M+ATV+VDwIz8OP8QX1U6zjcbCDd7fCnM5yJXsckP+NHOBnde3ZsMB/NREAaxEg9ImgFSFwTxG42yEH4Ax9lg/zK9ml7C24H96qdsvrtAUT5nabSW4/f8GkTVlaX0ZTX1eYdRG0KuB93PwlQmA9miAqkQVd2d8ONMlBf8MEHA1RLLZkkVlgU2COOhs3Qe5vpm73F9c0wKt2zITSVp4ma6G/BHby7dPfZM+mB/h0KcHcmPwpkPykeJy9BD/FF/Fn6rx6Njlz22xkpXmmlGfxVY7Ezwrw5QX6sEsAnWXlixJASA3wAAmUAu6CyCAB7qiCiSQ82Me3siuoqgD3wQ1qp7wKNidx7NkkWJhVSOjXnDHjcBjrSNOl05FQV5u0Bp6VkkIZep4eBY2Bj4SR4ynoFARcCjLg6anSZEFuh6SsRdItCaW15oZcNLTSMRFm3gV6XVVW5TRw7lgjTyctUA+JcO9NXl/Ulv5lcYgdgUfxiHpMncVnE/1EAb1B56wo76/MAXlovcqx+i74Q517MWddlwDWWd6DB24IxAD81yloWcC6h9SACPwVASJJH5gQwaQhgyG+D3az18Ml7CrcKa/EYdxlAODOIStzfjUGbn+Y/2tYmRs5bn68Jgx67RJAs105zj+TzCMApCxFI81sMPN6QUd7n/7y7qg0sFeZ00vsPDvFXhbPwnE8BCfxF+qCOpLopP4E+AwWdtUNgV/mEICEcP2+36MfM/z/UBZA5RBClwDWAfy+VfdBzjPA72cKuBcoDAUCQ6/97/HAAxOLNmn2J2CEj/J9bBe7AnfAFTii9uAQjjQtojsUpehwA5L6btBwqY2o3bPTfJ0h3bOsuzsUK1E87AIbZ+P8KHsFnsdT+Jw6rY4mnXcwAX2/A3r/4fv5CrJTfb6PryA7r68CRBAiBJc8YCPECjqdALj33AeeyAC7CBCGrwiyXAORoQKiDHeAZ+wDkhuS8gWU+KqwCLbCNr6LbWeXsBHYrbbhJTCIo9iPfc08uB/GWs8u8+7ZcvMoSR0Em2LTMMFO8zPsOI5r+z6Gx+GMOoWxCZqS3ulJAB85dIUtgK8CVl1mZABUIFagMvaDOe+pjG0AdPBAoE4ngFCDEJ5hwXkOEPPWhQAdBYKHeQ/eYv8uIdQTMqgm6/pYL9sCW9k2NsxG2RCMwjBswX41ojYbUuhrzpns2qM445YuEs7K01KR52ylc+DWYJpf1GCf4uPsPJzDC3Aaz+Fp/ewMzuK5RPWk8zf0wFzoMmR13SOWAbkvc4AuM76jMuS+yiAB6e3fTxFmjQnoEsA6WH/WAqDRMohAZJCBrwpCpCNyvs8ySMAntjR/XXNuPgJPP6N/PdpHHuIDGlJDbIBtgn4cxJImhn4oYwU2gcBN+tNCr4v0XsUCSQ+BMNacnZOswaSmpFg/m2E17ZdPQV2v00BnEziJM5qmLsAFNYlVmMApnE7UTN0JAVacoClm+Ng+KP3gXB74ZUHQF7HsmLO/rDiC7BLA+hMA90CYVcQTZVh1FgApb+FWiAxSEDlkESKUUGrS3TcEgpkuSNx8NpuXqeAa+ly7FWUWsR5GFBDp17ReMIo+sOb+KSZoAICot6XMo8FiiLGKMdaxoV/X9KPu7Q8DmRHhHGOrRx4gZQCMKgPwKgP8KsPyK1jYyCO0r1bZhKxOQN1+AGss//NG/EUZabsQqFkLhSACz0ULpcBakECrYCXLyXK45OCeG8wQ/8q7SdHLA/jb5RnOAAukwDDnOQb8ZcyxvFl+f956mWPZW1n+PKsfGi3oDxHuqCag/hJBZy95WYCQ1fZlOvMAyXLAybygIwuAwB8mKjLi9+7nufM3fS+0X/ezkGFt/ICo+53Fkj060XiWE+3GnKi4ypH9rSR4HpgxcJzgxVPcc6ICoUs3icpyrpFyrqPKIOWOXTqdAPIkpUwutPQulMgAn5uqUwFCQOcG4BlhsryH8DIQImcbrWIDWTdhXlEUZHw2RCSYc64h428RqY9LtM5FrXuWusAC+8zy+dVGsfavphhAqHgnKzAXsvp5QbpWn8lTEjwnEMgLqo9WRNCqMrIosUILsC8W+EsBPxZ83WqfIWKQOVH/VjEAv6CoGwNo8yxAXkqQtbC4ecDjgWMpqhCgQGqwaLYgSw24PjsrcN1D0hoL+vlFimiKWGUsYN3z4gp5Lgi02Jfrergg72YB2tz3z6sDaFWQE4q6F7WyPIOQfJ+0FdEUURlFSQByFMFi3as8mQ8FUnutLLRaxGeyYi2tynSzjjFvfzInM6Agu1FIlwDWSQUA5Jfg5knrIkDKet3qM6HgVFbKr5WagGUe91JjLNAC+FnyH3LADTC/pFYW2CYUUCNFgpKtiKGVe9KtBGyj485KXy0lkFbEeuZZ1RAptHrOIbvFedHj5YFt84LBv6L+P+QATxUkhawmG2oRAMcCMQrICVaqJQQvu2MBOowEQqMB+RLAXQToi31dhGj8VBZfhDKBFpkBd50bF3D9/VYgKhILAAinRdUiwI0FMxS4iOeYo25gEUSWRSbdLMA6EwEswVLnnQf/fSwA8KztYA5AWcH3oIBqgYLPl6oCYBEEUBTc/mdZC0AVTVuuNHHgIvbfJYAO+H1shb7HlnlsbImExmDxiiXveLEgGeQRQVFZ7q9jiwQSFlxX5P0i32PQ7QnYXVbo/LHATcWWSBSLdVGWGghciuUsCjq2DEvanWShSwDda9di3XJKgYta2i4QuwTQXTbgNe4CewMv/y/AACp7+iFYjXgYAAAAAElFTkSuQmCC">
                    <h1>You Should Call</h1>
                    <h2 style="color: #4351e8;">01123978046</h2>
                    <p >Based on your reported symptoms, you should seek care immediately.</p>

                    <p ><button type="button" class="btn btn-info continue">Continue</button></p>

                </div>

                <div style="display: none;" class="col-xs-12 col-sm-12 col-md-12 col-lg-12 display_content" id="report_2">
                    <ul class="top_nav">
                        <li class="prev" data-prev="step_2"><span>Prev</span></li>

                    </ul>
                    <h1>This tool is intended for people who are at least 18 years old</h1>
                    <p >Visit the CDC site to get information about COVID‑19 and younger people.</p>
                    <p ><button type="button" class="btn btn-info continue">Continue</button></p>
                </div>
                <button type="reset" id="reset_btn" style="display: none;"></button>


                <div style="display: none;" class="col-xs-12 col-sm-12 col-md-12 col-lg-12 display_content" id="report_5">

                    <h1>Report Done</h1>
                    <p >this is final report</p>
                    <p class="ans_div"></p>
                </div>

            </form>


        </div>
    </div>

    <div id="t_and_c_screen" style="display: <?php echo ($show_survey_form)?'none':'block'; ?>" class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <?php


        $allowPay = false;
        if($appointmentData['status'] == 'CANCELED')
        {
            $currDateTime = date("Y-m-d H:i:00");
            $appointmentDatetime = $appointmentData['appointment_datetime'];

            $select = "SELECT COUNT(`id`) as `total` FROM `appointment_customer_staff_services` WHERE `children_id` = '".$appointmentData['children_id']."' AND `appointment_customer_id` = '".$appointmentData['appointment_customer_id']."' AND `status` IN ('NEW', 'CONFIRM', 'RESCHEDULE') AND `booking_date` = '".$appointmentData['booking_date']."' LIMIT 1";
            $totalRS = $connection->query($select);
            $totalData = mysqli_fetch_assoc($totalRS);
            if(strtotime($currDateTime) > strtotime($appointmentDatetime))
            {
                echo "<h1>Your token has been canceled.</h1>";
            }
            else if($totalData['total'] > 1)
            {
                echo "<h1>You have already booked a token.</h1>";
            }
            else
            {
                $allowPay = true;
            }



        }
        else
        {
            $allowPay = true;
        }


        if($appointmentData['is_paid_booking_convenience_fee'] == 'NOT_APPLICABLE'){ ?>
            <h1>You Don't have to pay any fees.</h1>
        <?php }
        else if($appointmentData['is_paid_booking_convenience_fee'] == 'YES'){ ?>
            <h1>You have already paid fees.</h1>
        <?php }
        else if($appointmentData['is_paid_booking_convenience_fee'] == 'NO' && $allowPay == true)
        {


            $thinappID = $appointmentData['thinapp_id'];
            $orderNote = "Appointment Booking Convenience Fee";

            $orderId = $appointmentID.'-'.mt_rand(001,99999) ;
            $customerName = trim($appointmentData['name']);
            $customerPhone = trim($appointmentData['mobile']);
            $customerEmail = trim(!empty($appointmentData['email'])?$appointmentData['email']:'mbroadcast.100cflab@gmail.com');
            $tc = $appointmentData['booking_convenience_fee_terms_condition'];

            $is_online_consulting = $appointmentData['is_online_consulting'];
            $consulting_fee = $appointmentData['consulting_fee'];
            $orderAmount = trim($appointmentData['booking_convenience_fee']);
            $convence_for = "OFFLINE";
            if($is_online_consulting=="YES"){
                $tc = $appointmentData['online_t_c'];
                $convence_for = "ONLINE";
                if($appointmentData['booking_validity_attempt'] == 1){
                    $orderAmount = $consulting_fee + $appointmentData['booking_convenience_fee'];
                }
            }

            $currDate = date('Y-m-d H:i:s');
            $query = "INSERT INTO booking_convenience_orders (thinapp_id, appointment_customer_staff_service_id, order_id, amount, appointment_customer_id, children_id, status, convence_for, created, modified) VALUES(?,?,?,?,?,?,?,?,?,?)";
            $connection = ConnectionUtil::getConnection();
            $stmt = $connection->prepare($query);
            $status = 'ACTIVE';
            $stmt->bind_param('ssssssssss', $thinappID, $appointmentID,$orderId, $orderAmount, $appointmentData['appointment_customer_id'],$appointmentData['children_id'], $status,$convence_for, $currDate, $currDate);
            $stmt->execute();




            $postData = array(
                "appId" => $appId,
                "orderId" => $orderId,
                "orderAmount" => $orderAmount,
                "orderCurrency" => $orderCurrency,
                "orderNote" => $orderNote,
                "customerName" => $customerName,
                "customerPhone" => $customerPhone,
                "customerEmail" => $customerEmail,
                "returnUrl" => $RETURN_URL,
                "notifyUrl" => $NOTIFY_URL,
            );
            // get secret key from your config
            ksort($postData);
            $signatureData = "";
            foreach ($postData as $key => $value){
                $signatureData .= $key.$value;
            }
            $signature = hash_hmac('sha256', $signatureData, $secretKey,true);
            $signature = base64_encode($signature);
            ?>

            <form style="display:none;" id="redirectForm" method="post" action="<?php echo $postUrl; ?>">
                <input type="hidden" name="appId" value="<?php echo $appId; ?>"/>
                <input type="hidden" name="orderId" value="<?php echo $orderId; ?>"/>
                <input type="hidden" name="orderAmount" value="<?php echo $orderAmount; ?>"/>
                <input type="hidden" name="orderCurrency" value="<?php echo $orderCurrency; ?>"/>
                <input type="hidden" name="orderNote" value="<?php echo $orderNote; ?>"/>
                <input type="hidden" name="customerName" value="<?php echo $customerName; ?>"/>
                <input type="hidden" name="customerEmail" value="<?php echo $customerEmail; ?>"/>
                <input type="hidden" name="customerPhone" value="<?php echo $customerPhone; ?>"/>
                <input type="hidden" name="returnUrl" value="<?php echo $RETURN_URL; ?>"/>
                <input type="hidden" name="notifyUrl" value="<?php echo $NOTIFY_URL; ?>"/>
                <input type="hidden" name="signature" value="<?php echo $signature; ?>"/>
            </form>




            <div class="terms-block">
                <div class="terms" style="white-space: pre-line">
                    <h3 class="t_and_c_heading">Terms And Conditions</h3>
                    <div class="t_and_c_body">
                        <?php if($tc != ''){
                            echo $tc;
                        } ?>
                    </div>
                </div>
                <div class="accept-btn-holder">
                    <button type="button"  class="accept-btn" >ACCEPT T&C</button>
                </div>
            </div>

        <?php }
        else if($allowPay == false){ ?>
            <h1>Something Went Wrong!</h1>
        <?php } ?>
    </div>

</div>


</body>

<script>
    $(function () {


        var app_id =  "<?php echo @base64_encode($appointmentID); ?>";
        var baseurl =  "<?php echo $baseUrl; ?>";
        var show_survey_form =  "<?php echo ($show_survey_form)?'YES':'NO'; ?>";
        function termAndCondition(){
                $("#t_and_c_screen").show();
                $("#survey_screen").hide();

            if(show_survey_form=='YES'){
                var res = JSON.stringify(getAnswer());
                $.ajax({
                    url: baseurl+'/tracker/addSurveyData',
                    data:{ai:app_id,res:res},
                    type:'POST',
                    success: function(result){}
                });
            }

        }


        $(document).on("click",".accept-btn",function () {
                $(this).button('loading').html('Please Wait..');
                $("#redirectForm").submit();
        });

        $(document).on("click",".continue",function () {
            termAndCondition();
        })

        $(document).on("click",".action_btn",function () {
            $(".display_content").hide();
            var total_checked =  $(this).closest(".display_content").find(":checked").length;
            var id = $(this).attr('data-next');
            if(total_checked==1){
                var report_id = $(this).closest(".display_content").find(":checked").attr('data-report');
                if(report_id && report_id!=''){
                    id = report_id;
                }
            }
            if(id=="step_9"){
                id = "report_5";
            }
            $("#"+id).show();
            showHeader(id);
            if(id=='report_5'){
                var result = getAnswer();
                $(".ans_div").html(JSON.stringify(result));
                termAndCondition();
            }
        });

        function getAnswer(){
            var final = [];
            var obj = {};
            $(".display_content").each(function () {
                var question = $(this).find('.question').text();
                if(question){
                    var answer_array = [];
                    $(this).find("input").each(function (indec, value) {
                        if($(this).is(":checked")){
                            answer_array.push($(this).val());
                        }
                    })
                    obj = {'question':question,'ans':answer_array};
                    final.push(obj);
                }
            });
            return final;
        }

        $(document).on("click","input",function () {
            var total_checked =  $(this).closest(".display_content").find(":checked").length;
            var single_checked =  $(this).attr("data-single");
            var current_value = $(this).val();
            var btn = $(this).closest(".display_content").find('.action_btn');
            if(total_checked > 0){
                $(btn).attr('disabled',false);
            }else{
                $(btn).attr('disabled',true);
            }
            if(single_checked=='YES'){
                $(this).closest(".display_content").find('.input_box').each(function (index,value) {
                    if(current_value  != $(this).val() ){
                        $(this).attr('checked',false);
                    }
                });
            }else{
                $(this).closest(".display_content").find('.input_box').each(function (index,value) {
                    if($(this).attr('data-single')=='YES'){
                        $(this).attr('checked',false);
                    }
                });
            }
        });

        $(document).on("click",".prev",function () {
            $(".display_content").hide();
            var id = $(this).attr('data-prev');
            $("#"+id).show();
            showHeader(id);
        });

        $(document).on("click",".done",function () {
            $(".display_content").hide();
            var id = "report_5";
            $("#"+id).show();
            showHeader(id);

        });

        $(document).on("click",".cancel",function () {
            $('#reset_btn').trigger('click');
            $(".click_able_btn").attr('disabled',true);
            $(".display_content").hide();
            $("#step_0").show();
            showHeader("step_0");
        });

        function showHeader(id){
            if(id!='step_0'){
                $('.girl').hide();
                $('.corona').show();
            }else{
                $('.girl').show();
                $('.corona').hide();
            }
        }

    })
</script>


</html>