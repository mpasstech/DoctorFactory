<?php
date_default_timezone_set("Asia/Kolkata");
include_once "../webservice/WebServicesFunction_2_3.php";
//namespace ConnectionUtil;
//error_reporting(E_ALL);
//ini_set('display_errors', 1);
$response = array();
$_SERVER['REQUEST_METHOD'] = 'POST';
WebServicesFunction_2_3::ivr_response($_GET);
exit();


