<?php
date_default_timezone_set("Asia/Kolkata");
include_once "../webservice/WebServicesFunction_2_3.php";
$response = array();
$_SERVER['REQUEST_METHOD'] = 'POST';
if(isset($_GET["play_voice"])){
    echo "https://mengage.in/doctor/queue_tracker_voices/ed2f8cf0be79e7536aca062a6987c71d.wav";
}else{
    WebServicesFunction_2_3::custom_ivr($_GET);
}
exit();


