<?php
include_once "../webservice/WebServicesFunction_2_3.php";
$_SERVER['REQUEST_METHOD'] = 'POST';
WebServicesFunction_2_3::ivr_end_call($_GET);
exit();


