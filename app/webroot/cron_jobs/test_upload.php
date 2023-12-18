<?php
date_default_timezone_set("Asia/Kolkata");
include_once "../webservice/Custom.php";
include_once "../webservice/WebServicesFunction_2_3.php";
$response = array();
$response['status'] = 1;
$response['message'] = "Process Complete";
$connection = ConnectionUtil::getConnection();
foreach(glob(LOCAL_PATH.'app/webroot/test_prescription/*.json') as $file_path) {
    if(is_file($file_path)){
        $file_name = explode('/',$file_path);
        $file_name = end($file_name);
        $data = json_decode(file_get_contents($file_path),true);
        $raw_string = @$data['data']['raw_string'];
        $thinapp_id = @$data['data']['thin_app_id'];

        $fields = array(
            'applicationKey' => MYSCRIPT_APPLICATION_KEY,
            'textInput' => $raw_string
        );
        $return_array =array();
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, MYSCRIPT_URL);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, ($fields));
        $return_array[] = json_decode(curl_exec($ch), true);
        curl_close($ch);
        if (isset($return_array[0]['result']['textSegmentResult']['candidates'][0]['label'])) {
            $string = $return_array[0]['result']['textSegmentResult']['candidates'][0]['label'];

            if(!empty($string)){
                $file_date_time = date("Y-m-d H:i:s", filemtime($file_path));
                $sql = "UPDATE  drive_files SET created=?, modified = ? where myscript_string = ? and thinapp_id =?  and created >= '2019-01-01' order by id desc limit 1";
                $stmt = $connection->prepare($sql);
                $stmt->bind_param('ssss', $file_date_time, $file_date_time, $string, $thinapp_id);
                if($stmt->execute()){
                    unlink($file_path);
                }
            }

        }







    }
}

Custom::sendResponse($response);
exit();


