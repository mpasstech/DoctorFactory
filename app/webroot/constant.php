<?php
define('Paypal', serialize(array(
    'Username'=>'yinfluencer_seller_api1.gmail.com',
    'Password'=>'5S6ZHYV4FVYGJG74',
    'Signature'=>'AFcWxV21C7fd0v3bYYYRCpSSRl31Ac1rQRUVoEVnaEIcM2c8p1PtCoCM',
)));
define('CoinDistribution',serialize(array(
    'REGISTER'=>1000,
    'POST'=>10,
    'LIKE'=>5,
    'SUPER_LIKE'=>6,
    'EXCELLENT'=>7,
    'MIND_BLOWING'=>8,
    'WOW'=>9,
    'SHARE'=>10,
)));
define('AWS_BUCKET','mengage');
define('AWS',serialize(array(
        'version' => '2006-03-01',
        'region' => 'ap-south-1',
        'signature_version' => 'v4',
        'credentials' => array(
            'key'    => 'AKIAJ7CRUNJY26LEANDQ',
            'secret' => '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'
        )
    )
));
define('AWS_POOL_ID','ap-south-1:525c1ef8-ed26-45ee-8cd1-e8d8bb3390ce');
define('AWS_END_POINT','s3.ap-south-1.amazonaws.com');
define('AWS_ACCOUNT_ID','259985963249');
define('AWS_ARN_AUTH_ROLE_NAME',"arn:aws:iam::259985963249:role/Cognito_mengageAuth_Role");
define('AWS_ARN_UNAUTH_ROLE_NAME',"arn:aws:iam::259985963249:role/Cognito_mengageUnauth_Role");
define('GAPI_ENCRYPT',"AIzaSyA9tEMyxDqxU1uroSkCTvfa8QeR4ZVE-Ug");

define('APP_KEY','MBROADCAST');
define('PaypalAmount',100);
define('PAGINATION_LIMIT',20);
define('MBROADCAST_APP_ID',1);
define('DEFAULT_ROW_FETCH',50);
define('MAX_LIMIT','1000');
$protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'http://' : 'http://';
define('SITE_PATH',$protocol.$_SERVER['SERVER_NAME'].'/DoctorFactory/');




define('FIREBASE_SERVER_PATH',SITE_PATH.'chat/');
define('FOLDER_PATH',$protocol.$_SERVER['SERVER_NAME'].'/DoctorFactory/folder/');
define('FOLDER_STATIC_PATH',$protocol.$_SERVER['SERVER_NAME'].'/DoctorFactory/folder/folder_share_static/');
define('FILE_STATIC_PATH',$protocol.$_SERVER['SERVER_NAME'].'/DoctorFactory/folder/file_share_static/');
define('LOCAL_PATH',$_SERVER['DOCUMENT_ROOT'].'/DoctorFactory/');
define('APP_PAGINATION_LIMIT',15);
define('WEB_PAGINATION_LIMIT',10);
define('TOTAL_PROMOTIONAL_SMS','1000');
define('TOTAL_TRANSACTIONAL_SMS','1000');
define('SMS_QUEUE','200');
define('MBROADCAST_APP_ADMIN_ID','3');
define('MBROADCAST_APP_NAME','MBRODCAST');
define('GOOGLE_API_KEY','AIzaSyARC7VY-3Jf0oflukR5ehIchc2Wf2jkqss');
define('FIREBASE_SERVER_KEY','AIzaSyCWdw6-oJX6Jkp2UfSHGvuhlDvozj6RQUI');

define('SUPER_ADMIN_EMAIL','engage@mengage.in');
define('SUPER_ADMIN_MOBILE','+917412991122');

define('payment_getway',serialize(array('RAZORPAY'=>'Razorpay','PAYPAL'=>'Paypal')));
define("DEFAULT_COVER_IMAGE","https://s3-eu-west-1.amazonaws.com/mbroadcastdemo/image/201611021721341308243784.jpg");
define("DEFAULT_SMS_API","GUPSHUP");
define("GUPSHUP_TRANSACTIONAL_API_USER_ID","2000148979");
define("GUPSHUP_TRANSACTIONAL_API_USER_PASSWORD","kj5xweMEp");
define("ROUTE_SMS_PROMOTIONAL_API_USERNAME","2000152271");
define("ROUTE_SMS_PROMOTIONAL_API_PASSWORD","OVGYQdmnq");
define("APPOINTMENT_WORKING_START_TIME","08:00 AM");
define("APPOINTMENT_WORKING_END_TIME","08:00 PM");
define("RAXORPAY_LIVE_KEY","rzp_live_kNVgKtPv41Wjlt");
define("RAXORPAY_TEST_KEY","rzp_test_Tk1zBTD5C7eb0I");
define("FIREBASE_KEY","AIzaSyCWdw6-oJX6Jkp2UfSHGvuhlDvozj6RQUI");
define("FIREBASE_KEY_MENGAGE_TECH_PROJECT","AIzaSyDhiop2nZH2gZG3fb8asBnGIAONrEdpNdw");
define("MULTI_CURL_LIMIT","250");
define("DRIVE_PAGINATION_LIMIT","20");
define("DRIVE_FOLDER_DEFAULT_LIMIT","500");
define('STATIC_CATEGORY',serialize(array(
    'Doctor'=>'Doctor'
)));
define("OTP_SMS_API_KEY","08a711d4-7c27-11e7-94da-0200cd936042");
define("SERVICE_ON_MAINTENANCE","NO");
define("MAINTENANCE_MESSAGE","THIS APP IS UNDER MAINTENANCE MEINTENANCE");
define("AWS_DEFAULT_PATH","http://mengage.s3-website.ap-south-1.amazonaws.com/");
define("MESSAGE_CHARGE_RATE","0.23");
define("CLOUD_CHARGE_RATE","200");
define("CHILD_IMAGE","http://mengage.s3-website.ap-south-1.amazonaws.com/2017092116562725384.jpg");
define("CHILD_TIMELINE_MESSAGE","Having a baby is one of the most wonderful things in your life, as well as the hardest thing in your life.");
define("BIRTHDAY_DEFAULT_TEMPLATE","Wish your child many many happy returns of the day happy birthday #CHILD \nBest wishes from #APP");
define('DOCTOR_FACTORY_APP_ID',134);
define('SECURITY_KEY',"bcb04b7e103a0cd8b54763051cef08bc55abe029fdebae5e1d417e2ffb2a00a3");
define('SECURITY_IV',"bcb04b7e103a9568b54763051cef08bc55abe029fdebae5e1d417e2ffb2a00a3");
define('SMS_HORIZON_KEY',"MpuX6oA4y1klZMyI0kUv");
define('SMS_HORIZON_USER',"MEngage");
define('SMS_HORIZON_SENDER_ID',"MENGAZ");

/*test account*/
define('INSTAMOJO_API_KEY',"test_XugMBS9Z2sEC0qhqDektsLwODWV4F15z1Pd");
define('INSTAMOJO_API_SECRET',"test_MXDsRGFURH0jGGIGcdw8mtwZwgW27VbtTCzLRX3SIPTyWNxkwTvDXz5CptT1W4H5bIFNt6Sk5kfXFzL563yAXzMiDBfnj8Pk451ZeuI4sWN6TxUXgkT77JB1LFb");
define('INSTAMOJO_SALT',"b2406d27585a49d7ae781242ad46eef7");
define('INSTAMOJO_AUTH_TOKEN',"test_aa082c1a41b4fc6f3066709dfe7");

/* live account*/
//define('INSTAMOJO_API_KEY',"pdpIq1VXftk28V5vo7osvyTTbUArr2LjcwyMVyew");
//define('INSTAMOJO_API_SECRET',"nfHQXYtU1KIJaKnCkdXIwMMX1Y3bqXOI8cJmqjQJmNgArWkMmgGG87bywCWmq7RYoZNjsx4mgOZszdQJqhGXQfalzsXSt8YRyR8Phh6QdOGiUQcf9DLSZqwjotbA5oID");
//define('INSTAMOJO_SALT',"8823fd9ce25b4f4e9566d8b489355201");
define('INSTAMOJO_PRIVATE_API_KEY',"test_3ed5c9a6802c0d44c18433daa66");
define('INSTAMOJO_PRIVATE_AUTH_TOKEN',"test_aa082c1a41b4fc6f3066709dfe7");

define('INSTAMOJO_URL',"http://mengage.in/doctor/staging_site/instamojo/handle_redirect.php");
define('MENGAGE_APP_ID',459);
define('MAIN_SUPPORT_ADMIN_MOBILE','+917412991122');
define("MENGAGE_AWS_DEFAULT_PATH","https://s3-ap-south-1.amazonaws.com/mengage/");
define("MYSCRIPT_URL","https://cloud.myscript.com/api/v3.0/recognition/rest/text/doSimpleRecognition.json");
//define("MYSCRIPT_APPLICATION_KEY","85ba5a63-1b76-40e5-a13d-97e8ad88e531");
define("MYSCRIPT_APPLICATION_KEY","922e895c-452e-4126-b260-ed0d413d4797");
define("MENGAGE_API_ACCESS_MODE","PROD");
define("JIRA__USERNAME","munnasaini281@gmail.com");
define("JIRA__PASSWORD","Jira@4321");
define('SUPPORT_EMAIL','mahendra@mengage.in');
define('SUPPORT_MOBILE','8955004048');
define('SUPPORT_ADDRESS','228, Okay Plus Spaces, Near Apex Circle, Malviya Industrial Area Jaipur Rajasthan, India, 302017');
define('SUPPORT_WHATS_APP_NUMBER','+91-8955004049');
define('MY_SCRIPT_KEY_ARRAY',"85ba5a63-1b76-40e5-a13d-97e8ad88e531,922e895c-452e-4126-b260-ed0d413d4797,2c29e84d-9f81-4ee5-a36d-3270ffbe2934,1165887b-f29e-45bd-8022-534e39e01e0b,9c7a3104-cf77-4116-bd2c-2ae399c38ae4,cb6ffeb9-5bbe-4c21-9ead-1d585ef5d909,5e7c464e-d4f7-43b8-b2c4-438904fc3042,06ce995a-72af-4b0c-91ad-b56514a05e84");
define('MENGAGE_CLINIC','640');
define('KIOSK_THINAPP_ID','640');
define('MASTER_SYMPTOMS_CATEGORY_ID',1);
define('MASTER_DIAGNOSIS_CATEGORY_ID',2);
define('MEDICINE_MASTER_CATEGORY_ID',3);
define('MASTER_INVESTIGATION_CATEGORY_ID',4);
define('VACCINATION_MASTER_CATEGORY_ID',5);
define('PRESCRIPTION_SETTING_MASTER_CATEGORY_ID',10);
define('MASTER_VITAL_OTHER_NOTES',9);
define('MASTER_FLAGS',1699);
define('MASTER_ALLERGY',1700);
define('PRESCRIPTION_TEMPLATE_MASTER_CATEGORY_ID',9);
define('MASTER_MEDICINE_NAME_STEP_ID',4);
define('VITALS_MASTER_CATEGORY_ID',206);
define('FOLLOW_UP_MASTER_CATEGORY_ID',209);
define('INTERNAL_NOTES_MASTER_CATEGORY_ID',205);
define('ADVICE_MASTER_CATEGORY_ID',208);
define('CLINIC_FINDING_MASTER_CATEGORY_ID',204);
define('DEFAULT_GOOGLE_PAY',1001);
define('DEFAULT_PHONE_PE',1002);
define('DEFAULT_PAYTM',1003);
define('DEFAULT_NEFT',1004);
define('DEFAULT_DEBIT_CARD',1005);
define('DEFAULT_CREDIT_CARD',1006);
define('SHORT_URL','https://localhost/DoctorFactory/l/');
define('TWILIO_VIDEO_RATE','.80');
define('TELEMEDICINE_CONVENCE_RATE','15');
define('TELEMEDICINE_GST_RATE','18');
define('TELEMEDICINE_PAYMENT_GETWAY_RATE','3');
define('TWILIO_SID','ACc8400e4cd159ba80be171f2540d9e7db');
define('TWILIO_TOKEN','d7ab657910568de721fda42939aa2008');
define('CASHFREE_SECERET','7c09d72fe98e2c7df4384772a8bfb5237f2ff85e');
define('CASHFREE_APPID','278553e2c5d1803aa8ce3586955872');
define('CASHFREE_SECERET_TEST','899895a9f04308d36584adaa04a7b52d880c02ae');
define('CASHFREE_APPID_TEST','1461824bd90be36c6c495fd3081641');
define('WEB_VIDEO_CALL_URL','http://localhost:8080/quickstart/');
define('TEST_PAYMENT_MODE_APP',"134,788,796");
define('SEND_ALERT_MOBILES',"+918890720687,+918955060430,+919829016936");
define('OPRATIIONAL_NUMBER',"+918955060430");
define('SEND_ALERT_EMAILS',"tech@mpasscheckin.com");
define('PAYTM_MID',"AoXQMr59427367918442");
define('PAYTM_KEY',"!YNce16faF_pwX!o");
define('DELIVERY_CHARGES',"10");
define('CK_BIRLA_APP_ID',"288");
define('QUTOT_LOGO',"https://www.qutot.com/img/qutot_text.png");
define('QUTOT_EMAIL','info@qutot.com');
define('NODE_SERVER_URL','http://localhost:3000/');
define('MENGAGE_EMAIL','engage@mengage.in');
define('MENGAGE_PASSWORD','Jupiter@2020');
define('SMTP_HOST','smtppro.zoho.in');
define('NOTIFIY_THINAPPS',"134,730,726,728,497,651,318,552,815,713,182,776");
define('AUTO_ASSIGN_TOKEN_MINUTES',"15");
define('TOKEN_SOCKET_URL',"https://mngz.in:8000");
define('EHCC_APP_ID','15');
define('CUSTOMIZE_APPS',"134");

define('PHONE_PAY_MERCHANT_ID','M1P24OKI1SCN');
define('PHONE_PAY_SECRATE_KEY','c3e95f3c-e411-48a5-bb14-5f46d7ba6543');
define('PHONE_PAY_API_URL','https://api.phonepe.com/apis/hermes/pg/v1/pay');
define('PHONE_PAY_API_REFUND_URL','https://api.phonepe.com/apis/hermes/pg/v1/refund');


define('PHONE_PAY_MERCHANT_ID_TEST','PGTESTPAYUAT91');
define('PHONE_PAY_SECRATE_KEY_TEST','05992a0b-5254-4f37-86fb-e23bb79ea7e7');
define('PHONE_PAY_API_URL_TEST','https://api-preprod.phonepe.com/apis/merchant-simulator/pg/v1/pay');
define('PHONE_PAY_API_REFUND_URL_TEST','https://api-preprod.phonepe.com/apis/merchant-simulator/pg/v1/refund');



