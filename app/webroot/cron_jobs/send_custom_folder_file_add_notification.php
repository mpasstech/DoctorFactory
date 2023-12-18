<?php
ignore_user_abort(true);
set_time_limit(0);
date_default_timezone_set("Asia/Kolkata");
include_once "../webservice/Custom.php";

function getFileCount($url){
    $html = file_get_contents($url);
    $count = preg_match_all('/<a href="([^"]+)">[^<]*<\/a>/i', $html, $files);
    $fileCount = 0;
    $file1 = "";
    for ($i = 0; $i < $count; ++$i) {
        $file = $files[1][$i];
        if(preg_match('/^([-\.\w]+)$/', $file) > 0)
        {
            $fileCount++;
            if($file1 == "")
            {
                $file1 = $file;
            }
        }
    }
    return array("count"=>$fileCount,"first_file"=>$file1);
}

$url = "http://goyalhospital.org/doc";
$thinappID = 717;
$html = file_get_contents($url);
$count = preg_match_all('/<a href="([^"]+)">[^<]*<\/a>/i', $html, $files);
$connection = ConnectionUtil::getConnection();
for ($i = 0; $i < $count; ++$i) {
    $folder = $files[1][$i];
    $created =Custom::created();
    if(preg_match("/^[6-9][0-9]{9}[\/]$/", $folder) > 0)
    {
        $mobile = "+91".substr($folder, 0, 10);
        $userSql = "SELECT `id`,`firebase_token`,`app_installed_status` FROM `users` WHERE `mobile` = '".$mobile."' AND `thinapp_id` = '".$thinappID."' LIMIT 1";
        $userSql = $connection->query($userSql);
        if($userSql->num_rows)
        {

            $userData = mysqli_fetch_assoc($userSql);
            $userID = $userData['id'];
            $firebaseToken = $userData['firebase_token'];
            $appInstalledStatus = $userData['app_installed_status'];
            $customeFolderFileCountSql = "SELECT `previous_file_count` FROM `custome_folder_file_count` WHERE `mobile` = '".$mobile."' AND `thinapp_id` = '".$thinappID."' LIMIT 1";
            $customeFolderFileCountSql = $connection->query($customeFolderFileCountSql);
            if($customeFolderFileCountSql->num_rows)
            {
                $customeFolderFileCountData = mysqli_fetch_assoc($customeFolderFileCountSql);
                $currFileCount = $customeFolderFileCountData['previous_file_count'];
                $newFileCount = getFileCount($url.'/'.$folder."?C=M;O=D");
                $firstFile = $url.'/'.$folder.$newFileCount["first_file"];
                $newFileCount = $newFileCount["count"];
                if($currFileCount != $newFileCount)
                {
                    $updateSql = "UPDATE `custome_folder_file_count` SET modified = '$created', `current_file_count` = '".$newFileCount."',`previous_file_count` = '".$newFileCount."',`user_id` = '".$userID."' WHERE `mobile` = '".$mobile."' AND `thinapp_id` = '".$thinappID."'";
                    $connection->query($updateSql);
                }

                if($currFileCount < $newFileCount)
                {
                    if (!empty($firebaseToken) && ($appInstalledStatus == 'INSTALLED')) {
                        $option = array(
                            'thinapp_id' => $thinappID,
                            'channel_id' => 0,
                            'role' => "USER",
                            'flag' => 'FILE_ADD',
                            'title' => "New file added to folder ",
                            'message' => "New file added to folder ",
                            'description' => "New file added to folder ",
                            'chat_reference' => '',
                            'module_type' => 'DOCUMENT',
                            'module_type_id' => 0,
                            'firebase_reference' => ""
                        );
                        Custom::send_notification_via_token($option, array($firebaseToken), $thinappID);
                    }
                    $message = "New report has been updated by Goyal Hospital \n";//Click to view:- ".$firstFile;
                    Custom::send_single_sms($mobile,$message,$thinappID);

                }
            }
            else
            {
                $currentFileCount = getFileCount($url.'/'.$folder."?C=M;O=D");
                $firstFile = $url.'/'.$folder.$currentFileCount["first_file"];
                $currentFileCount = $currentFileCount["count"];
                $previousFileCount = 0;

                $insertSql = "INSERT INTO `custome_folder_file_count` (`created`, `thinapp_id`,`mobile`,`user_id`,`current_file_count`,`previous_file_count`) VALUES ('".$created."','".$thinappID."','".$mobile."','".$userID."','".$currentFileCount."','".$previousFileCount."')";
                $connection->query($insertSql);
                if (!empty($firebaseToken) && ($appInstalledStatus == 'INSTALLED')) {
                    $option = array(
                        'thinapp_id' => $thinappID,
                        'channel_id' => 0,
                        'role' => "USER",
                        'flag' => 'FILE_ADD',
                        'title' => "New file added to folder ",
                        'message' => "New file added to folder ",
                        'description' => "New file added to folder ",
                        'chat_reference' => '',
                        'module_type' => 'DOCUMENT',
                        'module_type_id' => -1,
                        'firebase_reference' => ""
                    );
                    Custom::send_notification_via_token($option, array($firebaseToken), $thinappID);
                }
                $message = "New report has been updated by Goyal Hospital \n";//Click to view:- ".$firstFile;
                Custom::send_single_sms($mobile,$message,$thinappID);

            }
        }
        else
        {

            $customeFolderFileCountSql = "SELECT `previous_file_count` FROM `custome_folder_file_count` WHERE `mobile` = '".$mobile."' AND `thinapp_id` = '".$thinappID."' LIMIT 1";
            $customeFolderFileCountSql = $connection->query($customeFolderFileCountSql);
            if($customeFolderFileCountSql->num_rows)
            {
                $customeFolderFileCountData = mysqli_fetch_assoc($customeFolderFileCountSql);
                $currFileCount = $customeFolderFileCountData['previous_file_count'];
                $newFileCount = getFileCount($url.'/'.$folder."?C=M;O=D");
                $firstFile = $url.'/'.$folder.$newFileCount["first_file"];
                $newFileCount = $newFileCount["count"];
                if($currFileCount != $newFileCount)
                {
                    $updateSql = "UPDATE `custome_folder_file_count` SET modified = '$created',  `current_file_count` = '".$newFileCount."',`previous_file_count` = '".$newFileCount."' WHERE `mobile` = '".$mobile."' AND `thinapp_id` = '".$thinappID."'";
                    $connection->query($updateSql);
                }

                if($currFileCount < $newFileCount)
                {

                    $message = "New report has been updated by Goyal Hospital \n";//Click to view:- ".$firstFile;
                    Custom::send_single_sms($mobile,$message,$thinappID);
                }
            }
            else
            {
                $userID = 0;
                $currentFileCount = getFileCount($url.'/'.$folder."?C=M;O=D");
                $firstFile = $url.'/'.$folder.$currentFileCount["first_file"];
                $currentFileCount = $currentFileCount["count"];
                $previousFileCount = 0;
                $insertSql = "INSERT INTO `custome_folder_file_count` (`created`, `thinapp_id`,`mobile`,`user_id`,`current_file_count`,`previous_file_count`) VALUES ('".$created."','".$thinappID."','".$mobile."','".$userID."','".$currentFileCount."','".$previousFileCount."')";
                $connection->query($insertSql);
                $message = "New report has been updated by Goyal Hospital \n";//Click to view:- ".$firstFile;
                Custom::send_single_sms($mobile,$message,$thinappID);
            }

        }
    }
}
?>