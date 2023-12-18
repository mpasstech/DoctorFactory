<?php
date_default_timezone_set("Asia/Kolkata");
include_once "../webservice/Custom.php";
$thin_app_id = $_REQUEST['ti'];
$doctor_id = $_REQUEST['di'];
$ivr_number = $_REQUEST['n'];
if(!empty($thin_app_id) && !empty($doctor_id)){
    Custom::setup_ivr_data($thin_app_id, $doctor_id,$ivr_number);
}else{
    echo "Invalid Data";die;
}




