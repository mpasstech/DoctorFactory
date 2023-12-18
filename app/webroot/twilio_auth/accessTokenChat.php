<?php
// Get the PHP helper library from https://twilio.com/docs/libraries/php
include('./vendor/autoload.php');
include('./config.php');
use Twilio\Jwt\AccessToken;
use Twilio\Jwt\Grants\ChatGrant;

// Required for all Twilio access tokens
$twilioAccountSid = $ACCOUNT_SID;
$twilioApiKey = $API_KEY;
$twilioApiSecret = $API_KEY_SECRET;
// Required for Chat grant
$serviceSid = 'ISa7c451ee76dc4037bc82f7216cd44ac7';
// choose a random username for the connecting user
$identity = isset($_REQUEST['identity'])?$_REQUEST['identity']:'test';

// Create access token, which we will serialize and send to the client
$token = new AccessToken(
    $twilioAccountSid,
    $twilioApiKey,
    $twilioApiSecret,
    3600,
    $identity
);

// Create Chat grant
$chatGrant = new ChatGrant();
$chatGrant->setServiceSid($serviceSid);

// Add grant to token
$token->addGrant($chatGrant);

// render token to string
//echo "<pre>";
//print_r($token);
return $token->toJWT();
?>