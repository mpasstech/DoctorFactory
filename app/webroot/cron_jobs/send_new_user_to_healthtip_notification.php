<?php
date_default_timezone_set("Asia/Kolkata");
include_once "../webservice/Custom.php";
    $response = array();
    $connection = ConnectionUtil::getConnection();
    $query = "select  u.id,u.thinapp_id,u.firebase_token, u.sent_healthtip_id from users as u join app_enable_functionalities as aef on aef.thinapp_id = u.thinapp_id join app_functionality_types as aft on aft.id = aef.app_functionality_type_id and aft.label_key = 'HEALTH_TIP' where u.status = 'Y' and u.is_verified='Y' and u.app_installed_status = 'INSTALLED' and healthip_send = 'NO' and DATE(u.created) > '2018-04-01' AND (DATE(u.send_healthtip_date) ='0000-00-00' OR DATE(u.send_healthtip_date) = DATE(subdate(NOW(), 2)))";
    $subscriber = $connection->query($query);
    if ($subscriber->num_rows) {

        $menu_list = mysqli_fetch_all($subscriber, MYSQLI_ASSOC);
        $counter=0;
        $update= $health_tip_sent_id = array();
        foreach($menu_list as $key => $user_data){

            $condition = " status = 'ACTIVE' and view_count > 0 ";
            if(!empty($user_data['sent_healthtip_id'])){
                $health_tip_sent_id = explode(",",$user_data['sent_healthtip_id']);
                $condition .= " and id NOT IN (" .'"'.implode('","', $health_tip_sent_id).'"'. ")" ;
            }

            $query ="select id,category, thinapp_id,title, image, view_count from cms_doc_dashboards where $condition ORDER BY view_count desc limit 1";
            $view_data = $connection->query($query);
            if ($view_data->num_rows) {
                $view_data = mysqli_fetch_assoc($view_data);
                $health_tip_id = $view_data['id'];
                $flag = strtoupper($view_data['category']);
                $thin_app_id =$user_data['thinapp_id'];
                $option = array(
                    'thinapp_id' => $thin_app_id,
                    'channel_id' => 0,
                    'role' => "USER",
                    'flag' => $flag,
                    'title' => mb_strimwidth($view_data['title'], 0, 100, '...'),
                    'file_path_url' => $view_data['image'],
                    'type' => 'IMAGE',
                    'message' => mb_strimwidth($view_data['title'], 0, 100, '...'),
                    'description' => "",
                    'chat_reference' => '',
                    'module_type' => $flag,
                    'module_type_id' => $health_tip_id,
                    'firebase_reference' => ""
                );
                $result = Custom::send_notification_via_token($option,array($user_data['firebase_token']),$thin_app_id);

                if(@$result[0]['success'] == 1 ){
                    $created = Custom::created();
                    $healthip_send = "NO";
                    $health_tip_sent_id[] = $health_tip_id;
                    if(count($health_tip_sent_id) >= 5){
                        $healthip_send = "YES";
                    }
                    $id_string = implode(",",$health_tip_sent_id);
                    $sql = "UPDATE users set healthip_send =?, sent_healthtip_id=?, send_healthtip_date =?  where id = ?";
                    $stmt = $connection->prepare($sql);
                    $stmt->bind_param('ssss', $healthip_send, $id_string, $created, $user_data['id']);
                    if ($stmt->execute()) {
                        $update[] =  "Notification send to ".$user_data['id'];
                    } else {
                        $update[] = "Notification could not send to ".$user_data['id'];
                    }

                }else{
                    $update[] = "Notification could not send to ".$user_data['id'];
                }
            }

        }

        $response['status'] = 1;
        $response['message'] = "Request Completed";
        $response['result'] = $update;
        echo "<pre>";
        print_r($response);
        exit();


    } else {
        $response['status'] = 0;
        $response['message'] = "No user found to send healthtip.";
    }
    echo Custom::sendResponse($response);
    exit();
