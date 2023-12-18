<?php
date_default_timezone_set("Asia/Kolkata");
include_once "../webservice/Custom.php";
include_once "../webservice/WebServicesFunction_2_3.php";
$response = array();
$response['status'] = 1;
$response['message'] = "Process Complete";
foreach(glob(LOCAL_PATH.'app/webroot/myscript_pending_request/*.json') as $file_path) {
    if(is_file($file_path)){
        $file_name = explode('/',$file_path);
        $file_name = end($file_name);
        $data = json_decode(file_get_contents($file_path),true);
        $file_date_time = date("Y-m-d H:i:s", filemtime($file_path));
        if(!empty($data)){
            $_SERVER['REQUEST_METHOD'] = 'POST';
            $file_data = isset($data['data'])?$data['data']:$data;
            $file_date_time = (isset($data['data']['created']) && !empty($data['data']['created']))?$data['data']['created']:$file_date_time;
            $myscript_response = isset($data['myscript_response'])?$data['myscript_response']:array();
            if( empty($myscript_response) || @$myscript_response['result']['error'] == 'RecognitionApiTimedOutException' || @$myscript_response['result']['error'] != 'NoInkFoundException' ||  @$myscript_response['result']['error'] == 'AccountCounterThresholdReachedException' || !empty($myscript_response['instanceId']) ){
                $res = json_decode(WebServicesFunction_2_3::save_barcode_prescription($file_data, $file_name,false, $file_date_time),true);
                if($res['status']==1){
                    $response[$file_name] ='SUCCESS';
                }else{
                    $response[$file_name] ='FAIL';
                }
            }else{
                $response[$file_name] ='INVALID INK DATA';
            }

        }
    }
}

Custom::sendResponse($response);
exit();


