<?php

date_default_timezone_set("Asia/Kolkata");
include_once "../webservice/Custom.php";
$current_time = date("h:i a");
$sunrise = "00:10 am";
$sunset = "05:00 am";
$date1 = DateTime::createFromFormat('h:i a', $current_time);
$date2 = DateTime::createFromFormat('h:i a', $sunrise);
$date3 = DateTime::createFromFormat('h:i a', $sunset);
if ($date1 > $date2 && $date1 < $date3)
{
    $files = glob(LOCAL_PATH.'app/webroot/cache/fortis/*'); // get all file names
    foreach($files as $file){ // iterate files
        if(is_file($file)) {
            unlink($file); // delete file
        }
    }
    $response['message'] = "Total ".count($files)." files has been deleted";
}else{
    $response['message'] = "Current time is not valid for delete cache file $current_time. Please run this job between $sunrise - $sunset";
}
Custom::sendResponse($response);
exit();


