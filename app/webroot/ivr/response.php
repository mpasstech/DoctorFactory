<?php
header('Content-type: text/plain');
include_once "../webservice/WebservicesFunction.php";
include_once "../webservice/Custom.php";
$call_sid = isset($_GET['CallSid']) ? $_GET['CallSid'] : 0;
$file_name = array('call_sid_'.$call_sid,'input_'.$call_sid);
echo WebservicesFunction::readJson('call_sid_'.$call_sid,'ivr');
Custom::send_process_to_background();
WebservicesFunction::deleteJson($file_name,'ivr');
exit();


