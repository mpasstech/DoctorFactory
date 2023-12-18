<?php
/*
 * Creates an access token with VoiceGrant using your Twilio credentials.
 */

include('vendor/autoload.php');
include('config.php');
use Twilio\Rest\Client;


class getRecordingList{

        public static function getList($recordingSid){
            $twilio = new Client("ACc8400e4cd159ba80be171f2540d9e7db", "d7ab657910568de721fda42939aa2008");
            return $twilio->video->v1->recordings($recordingSid)->fetch();
        }

}

