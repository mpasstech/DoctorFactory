<?php
ignore_user_abort(true);
set_time_limit(0);
date_default_timezone_set("Asia/Kolkata");
include_once "../webservice/Custom.php";
$response = array();
$connection = ConnectionUtil::getConnection();
$apkUrlArray = array();
$query = "select GROUP_CONCAT(' ',CONCAT_WS(' ',amv.vac_name,amv.vac_dose_name)) as all_dose_name, c.parents_mobile as pat_mobile, pu.firebase_token as pat_token, u.firebase_token, c.thinapp_id, c.id as child_id, cv.id as vac_id, cv.reschedule_date,IF(cv.reschedule_date IS NOT NULL || cv.reschedule_date !='',cv.reschedule_date,cv.vac_date ) as vac_date, u.id as patient_id, u.username as patient_name, c.child_name,c.user_id,c.mobile from childrens as c join users as createdBy on createdBy.id = c.child_add_by_id and createdBy.role_id = 5 left join users as u on u.thinapp_id =c.thinapp_id and c.mobile= u.mobile and (u.role_id = 5 OR u.role_id = 1)  left join users as pu on pu.thinapp_id =c.thinapp_id and c.parents_mobile= pu.mobile and (pu.role_id = 5 OR pu.role_id = 1) join child_vaccinations as cv on c.id = cv.children_id join app_master_vaccinations as amv on cv.app_master_vaccination_id = amv.id JOIN app_enable_functionalities AS aef ON aef.thinapp_id = c.thinapp_id AND aef.app_functionality_type_id = 23 where (  DATE(CURDATE() + INTERVAL 1 DAY) = DATE(IFNULL(cv.reschedule_date, cv.vac_date)) OR  DATE(IFNULL(cv.reschedule_date, cv.vac_date)) = DATE(NOW()) OR DATEDIFF(DATE(IFNULL(cv.reschedule_date, cv.vac_date)),DATE(NOW())) =7 ) AND (IF(c.gender = 'MALE',amv.visible_for_male, amv.visible_for_female) ='YES') AND amv.status ='ACTIVE' and cv.status ='PENDING' AND c.status ='ACTIVE' and  c.has_vaccination='YES'  group by CONCAT(c.mobile,c.thinapp_id,c.id,vac_date)";
$subscriber = $connection->query($query);
if ($subscriber->num_rows) {
    $message_data =array();
    $menu_list = mysqli_fetch_all($subscriber, MYSQLI_ASSOC);
    $counter=0;
    foreach($menu_list as $key => $child_vac_data){
        try {
            if (!empty($child_vac_data)) {
                $thin_app_id = $child_vac_data['thinapp_id'];
                if(!array_key_exists($thin_app_id,$apkUrlArray)){
                    $app_data = Custom::getThinAppData($thin_app_id);
                    $apkUrlArray[$thin_app_id] = $app_data['apk_url'];
                }

                $child_id = $child_vac_data['child_id'];
                $vac_id = $child_vac_data['vac_id'];
                $vaccination_list_array = explode(',', $child_vac_data['all_dose_name']);

                $vaccination = '';
                $dos_cnt = count($vaccination_list_array);
                if ($dos_cnt > 1) {
                    $vaccination = $vaccination_list_array[0] . ',  +' . ($dos_cnt-1);
                }else {
                    $vaccination = @$vaccination_list_array[0];
                }
                $date = $child_vac_data['vac_date'];
                $label_date = date('Y-m-d', strtotime($date));
                $date = date('d/m/Y', strtotime($date));
                $day_type = "WEEK";
                $label = Custom::get_date_label($label_date);

                if ($label == "Yesterday") {
                    $message = "Hi " . trim($child_vac_data['child_name']) . ", You have missed the vaccine $vaccination on $label, $date. If taken, Congrats! You are protected.";
                } else {
                    if ($label == "Today") {
                        $day_type = "TODAY";
                    }
                    $message = "Vaccine " . $vaccination . " for child " . trim($child_vac_data['child_name']) . " is due on $label " . $date;
                }



                $rec_user_id = $child_vac_data['patient_id'];
                $firebase_token = $child_vac_data['firebase_token'];
                $pat_token = $child_vac_data['pat_token'];
                $rec_mobile = $child_vac_data['mobile'];
                $pat_mobile = $child_vac_data['pat_mobile'];
                $option = array(
                    'thinapp_id' => $thin_app_id,
                    'channel_id' => 0,
                    'role' => "USER",
                    'flag' => 'VACCINATION',
                    'title' => mb_strimwidth($vaccination, 0, 300, '...'),
                    'module_title' => mb_strimwidth($vaccination, 0, 300, '...'),
                    'message' => mb_strimwidth($message, 0, 300, '...'),
                    'description' => "",
                    'chat_reference' => '',
                    'module_type' => 'VACCINATION',
                    'module_type_id' => $vac_id,
                    'child_id' => $child_id,
                    'firebase_reference' => "",
                    'day_type' => $day_type
                );

                if (!empty($pat_token) && !empty($firebase_token)) {
                    Custom::send_notification_via_token($option, array($firebase_token, $child_vac_data['pat_token']), $thin_app_id);
                } else if (!empty($firebase_token)) {
                    Custom::send_notification_via_token($option, array($firebase_token), $thin_app_id);
                }

                $message .= "\nDownload App: " . $apkUrlArray[$thin_app_id];
                $message .= "\n- Sent by MEngage";

                if (!empty($rec_mobile)) {
                    $message_data[$counter]['mobile'] = $rec_mobile;
                    $message_data[$counter]['message'] = $message;
                    Custom::send_single_sms($rec_mobile, $message, $thin_app_id,false,false);
                    $counter++;
                }
                if (!empty($pat_mobile)) {
                    $message_data[$counter]['mobile'] = $pat_mobile;
                    $message_data[$counter]['message'] = $message;
                    Custom::send_single_sms($pat_mobile, $message, $thin_app_id,false,false);
                    $counter++;
                }


            }
        }catch(Exception $e){
            echo "<pre>";
            echo $e->getMessage();
        }
    }
    $response['status'] = 1;
    $response['message'] = "Notification and sms Send";
    $response['data'] = $message_data;
    Custom::sendResponse($response);

} else {
    $response['status'] = 0;
    $response['message'] = "No vaccination list found";
}
Custom::sendResponse($response);
exit();


