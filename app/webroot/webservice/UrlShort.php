<?php

include_once "ConnectionUtil.php";
include_once "Custom.php";


class UrlShort
{
    protected static $chars = "abcdfghijkmpqrstvwxyz|ABCDFGHIJKLMNPQRSTVWXYZ|0123456789";
    protected static $table = "short_urls";
    protected static $checkUrlExists = false;
    protected static $codeLength = 7;




    public function __construct(){

    }

    public function urlToShortCode($url,$thin_app_id){

        if(empty($url)){
           return false;
        }

        if($this->validateUrlFormat($url) == false){
           return false;
        }

        if(self::$checkUrlExists){
            if (!$this->verifyUrlExists($url)){
                return false;
            }
        }

        $shortCode = $this->urlExistsInDB($url);
        if($shortCode == false){
            $shortCode = $this->createShortCode($url,$thin_app_id);
        }
        return $shortCode;
    }

    protected function validateUrlFormat($url){
        return filter_var($url, FILTER_VALIDATE_URL, FILTER_FLAG_HOST_REQUIRED);
    }

    protected function verifyUrlExists($url){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_setopt($ch,  CURLOPT_RETURNTRANSFER, true);
        curl_exec($ch);
        $response = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return (!empty($response) && $response != 404);
    }

    protected function urlExistsInDB($url){
        $query = "select short_code from ".self::$table." WHERE long_url = '$url' LIMIT 1";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            return mysqli_fetch_assoc($service_message_list)["short_code"];

        }
        return false;
    }

    protected function createShortCode($url,$thin_app_id){
            $url =trim($url);
            $connection = ConnectionUtil::getConnection();
            $query = "select short_code from ".self::$table." WHERE long_url = '$url' LIMIT 1";
            $service_message_list = $connection->query($query);
            if ($service_message_list->num_rows) {
                    return mysqli_fetch_assoc($service_message_list)['short_code'];
            }else {
                $counter = 0;
                CHECK_AGAIN:
                if ($counter < 5) {
                    $shortCode = $this->generateRandomString(self::$codeLength);
                    $query = "select id from " . self::$table . " WHERE short_code = '$shortCode' LIMIT 1";
                    $service_message_list = $connection->query($query);
                    if (!$service_message_list->num_rows) {
                        if ($this->insertUrlInDB($url, $shortCode,$thin_app_id)) {
                            return $shortCode;
                        }
                    } else {
                        $counter++;
                        goto CHECK_AGAIN;
                    }
                }
            }

        return false;

    }

    protected function generateRandomString($length = 6){
        $sets = explode('|', self::$chars);
        $all = '';
        $randString = '';
        foreach($sets as $set){
            $randString .= $set[array_rand(str_split($set))];
            $all .= $set;
        }
        $all = str_split($all);
        for($i = 0; $i < $length - count($sets); $i++){
            $randString .= $all[array_rand($all)];
        }
        $randString = str_shuffle($randString);
        return $randString;
    }

    protected function insertUrlInDB($url, $code,$thin_app_id){
        $url =trim($url);
        $connection = ConnectionUtil::getConnection();

            $sql = "INSERT INTO ".self::$table." (long_url, thinapp_id, short_code, created) VALUES (?, ?, ?, ?)";
            $stmt_user = $connection->prepare($sql);
            $created =Custom::created();
            $stmt_user->bind_param('ssss',  $url, $thin_app_id, $code,$created);
            if ($stmt_user->execute()) {
                return $stmt_user->insert_id;
            }

        return false;
    }

    public function shortCodeToUrl($code, $increment = true){
        if(empty($code)) {
            return false;
        }

        if($this->validateShortCode($code) == false){
            return false;
        }

        $urlRow = $this->getUrlFromDB($code);
        if(empty($urlRow)){
            return false;
        }

        if($increment == true){
            $res = $this->incrementCounter($urlRow["id"]);
            $res = $this->insertShortHits($urlRow["id"],$urlRow["long_url"]);
        }

        return $urlRow["long_url"];
    }

    protected function validateShortCode($code){
        $rawChars = str_replace('|', '', self::$chars);
        return preg_match("|[".$rawChars."]+|", $code);
    }

    protected function getUrlFromDB($code){
        $query = "select id, long_url from ".self::$table." WHERE short_code = '$code' LIMIT 1";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            return mysqli_fetch_assoc($service_message_list);
        }
        return false;
    }

    protected function incrementCounter($id){

        $connection = ConnectionUtil::getConnection();
        $sql = "UPDATE ".self::$table." SET hits = hits + 1 WHERE id = ?";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param('s', $id);
        return $stmt->execute();

    }

	protected function insertShortHits($short_url_id, $long_url){
        $parts = parse_url($long_url);
        parse_str($parts['query'], $query);
        if(!empty($query)){
            if(isset($query['tracker']) && $query['tracker']=='web_tracker_url'){
                $thin_app_id = base64_decode($query['ti']);
                $doctor_id = base64_decode($query['t']);
                $custom_value = base64_decode($query['uh']);
                $module_name = 'WEB_APP_TRACKER';
                $created = date('Y-m-d');
                $query = "select id from short_url_hits WHERE DATE(created) = '$created' and module_name ='$module_name' and custom_value ='$custom_value' and doctor_id = $doctor_id and thinapp_id=$thin_app_id and short_url_id=$short_url_id  LIMIT 1";
                $connection = ConnectionUtil::getConnection();
                $value = $connection->query($query);
                if (!$value->num_rows) {
                    $sql = "INSERT INTO short_url_hits (thinapp_id, doctor_id, short_url_id, module_name, custom_value, created) VALUES (?, ?, ?, ?, ?, ?)";
                    $stmt_user = $connection->prepare($sql);
                    $created = date('Y-m-d H:i:s');
                    $stmt_user->bind_param('ssssss', $thin_app_id, $doctor_id, $short_url_id, $module_name, $custom_value, $created);
                    if ($stmt_user->execute()) {
                        return $stmt_user->insert_id;
                    }
                }
            }
        }
        return true;

    }

}