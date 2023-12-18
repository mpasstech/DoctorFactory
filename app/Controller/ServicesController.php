<?php
App::uses('Controller', 'Controller');
App::uses('CakeEmail', 'Network/Email');
date_default_timezone_set("Asia/Kolkata");
ini_set('memory_limit', '-1');
App::uses('WebservicesFunction', WWW_ROOT . 'webservice' . DS . 'WebservicesFunction.php');
App::uses('WebServicesFunction_2_3', WWW_ROOT . 'webservice' . DS . 'WebServicesFunction_2_3.php');

class ServicesController extends AppController
{

    public $name = "services";
    public $uses = array("LostFoundComment", "AppEnableFunctionality", "AppFunctionalityType", "UserFunctionalityType", "UserEnabledFunPermission", 'SellItem', 'SellItemCategory', 'SellImage', 'Conference', 'EventAgenda', 'EventOrganizer', 'EventSpeaker', 'EventTicket', 'EventTicketSell', 'EventShow', 'EventPost', 'EventResponse', 'EventCategory', 'Event', 'EventMedia', 'ChannelLoseObject', 'LoseObject', 'AppStaff', 'TicketComment', 'Ticket', 'Gullak', 'AppEnquiry', 'QuestionChannel', 'ActionType', 'QuestionChoice', 'ActionQuestion', 'ChannelMessage', 'MessageStatic', 'MessageAction', 'Collaborator', 'CmsPage', 'User', 'Like', 'Transaction', 'Thinapp', 'Channel', 'Message', 'MessageQueue', 'Subscriber', 'Setting', 'ActionType', 'ActionResponse', 'SubscriberMessages', 'Quest', 'QuestLike', 'QuestThank', 'QuestShare', 'QuestReply', 'QuestReplyThank', 'QuestCategory', 'SellWishlist', 'SellLike', 'TicketComment', 'AppUserStatic','DriveShare','DriveFile','DriveFolder');
    public $components = array('Custom');


    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow();
        $this->layout = false;
        $this->autoRender = false;

    }

    /* this function updated by mahendra*/
    public function resend_code()
    {


        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        if ($this->request->is('post')) {
            $response = array();
            $mobile = $data['mobile'];
            $app_key = $data['app_key'];
            $thin_app_id = $data['thin_app_id'];

            if ($mobile != '' && $app_key != '' && $thin_app_id != '') {
                if (($result = $this->Custom->CheckIsValidApp($app_key, $thin_app_id)) && ($result == 1)) {
                    $userdata = $this->User->find("first", array("conditions" => array("User.mobile" => $mobile)));
                    if (!empty($userdata)) {

                        $option = array(
                            'username' => $userdata['User']['username'],
                            'mobile' => $mobile,
                            'verification' => $userdata['User']['verification_code'],
                            'thinapp_id' => $thin_app_id
                        );
                        $sms_resp = $this->Custom->send_otp($option);

                        if ($sms_resp->response->status == 'success') {
                            $response['status'] = 1;
                            $response['message'] = "Verification code resent successfully";
                        } else {
                            $response['status'] = 0;
                            $response['message'] = "Message API Error : " . $sms_resp->response->details;
                        }
                    } else {
                        $response['status'] = 0;
                        $response['message'] = "Mobile number not found";
                    }
                } else {
                    $response['status'] = 0;
                    $response['message'] = $this->Custom->getResponseMessage($result);
                }
            } else {
                $response['status'] = 0;
                $response['message'] = "Request not found";
            }
            echo json_encode($response);
            exit;
        }
    }


    /* this function created by mahendra */
    public function signup_revised()
    {
        $request = file_get_contents("php://input");
        $data = json_decode($request, true);

        if ($this->request->is('post')) {
            $response = array();
            //  $username = $data['username'];
            $mobile = $data['mobile'];
            $device_token = $data['device_token'];
            $device_type = $data['device_type'];
            $firebase_token = $data['firebase_token'];
            $device_unique_id = $data['device_unique_id'];
            $app_key = $data['app_key'];
            $thin_app_id = $data['thin_app_id'];

            if ($mobile != '' && $device_token != '' && $device_type != '' && $app_key != '' && $firebase_token != '') {
                if (($result = $this->Custom->CheckIsValidApp($app_key, $thin_app_id)) && ($result == 1)) {

                    $total_sms = $this->Custom->getTotalRemainingSms($thin_app_id, "T");
                    if ($total_sms > 0) {
                        $datasource = $this->User->getDataSource();
                        try {
                            $datasource->begin();


                            $thin_app_data = $this->Thinapp->find('first', array(
                                'conditions' => array(
                                    'Thinapp.id' => $thin_app_id,
                                ),
                                'contain' => false,
                                'fields' => array("Thinapp.website_url", "Thinapp.facebook_url")
                            ));


                            $admin_firebase_email = $this->User->find("first", array(
                                "conditions" => array(
                                    "User.role_id" => 5,
                                    "User.thinapp_id" => $thin_app_id
                                ),
                                'contain' => false,
                                "fields" => array("User.firebase_chat_email")
                            ));

                            $userdata = $this->User->find("first", array(
                                "conditions" => array(
                                    "User.mobile" => $mobile,
                                    "User.thinapp_id" => $thin_app_id
                                ),
                                'contain' => false,
                                "fields" => array("User.id", "User.username", "User.mobile", "User.role_id", "User.firebase_chat_email", "User.firebase_chat_password", 'User.firebase_token')
                            ));

                            if (empty($userdata)) {

                                /* this condition check app admin signup first time or not user
                                              can not register berfor app admin login issue of default channel
                                        */
                                if ($this->Custom->is_app_admin_login_first_time($thin_app_id)) {
                                    $res = $this->Custom->create_firebase_email_password($mobile, $thin_app_id);
                                    $uniqeEmail = $res['email'];
                                    $uniqePassword = $res['password'];
                                    $username = "mBroadcast";
                                    $verification_code = $this->Custom->getRandomString(4);
                                    $this->User->create();
                                    $this->User->set('role_id', 1);
                                    $this->User->set('username', $username);
                                    $this->User->set('mobile', $mobile);
                                    //$this->User->set('image', SITE_URL . "img/profile/profile_default.png");
                                    $this->User->set('verification_code', $verification_code);

                                    $this->User->set('firebase_chat_email', $uniqeEmail);
                                    $this->User->set('firebase_chat_password', $uniqePassword);

                                    $this->User->set('device_type', $device_type);
                                    $this->User->set('device_token', $device_token);
                                    //$this->User->set('credit', 200);
                                    $this->User->set('app_key', $this->Custom->getRandomAppKey(8));
                                    $this->User->set('thinapp_id', $thin_app_id);
                                    $this->User->set('firebase_token', $firebase_token);
                                    $this->User->set('device_unique_id', $device_unique_id);

                                    if ($this->User->save()) {
                                        $last_id = $this->User->getLastInsertId();


                                        $userdata = $this->User->find("first", array(
                                            "conditions" => array(
                                                "User.id" => $last_id,
                                                "User.thinapp_id" => $thin_app_id
                                            ),
                                            'contain' => false,
                                            "fields" => array("User.id", "User.username", "User.mobile", "User.role_id", 'User.firebase_chat_email', 'User.firebase_chat_password')
                                        ));


                                        $response['status'] = 1;
                                        $response['message'] = "Signup successfully";
                                        $response['data']['is_exist'] = 0;
                                        $response['data']['user_id'] = $last_id;
                                        $response['data']['mbroadcast_app_id'] = MBROADCAST_APP_ID;
                                        $response['data']['username'] = $userdata['User']['username'];
                                        $response['data']['role_id'] = $userdata['User']['role_id'];
                                        $response['data']['firebase_chat_email'] = $userdata['User']['firebase_chat_email'];
                                        $response['data']['firebase_chat_password'] = $userdata['User']['firebase_chat_password'];
                                        $response['data']['website_url'] = $thin_app_data['Thinapp']['website_url'];
                                        $response['data']['facebook_url'] = $thin_app_data['Thinapp']['facebook_url'];
                                        $response['data']['mobile'] = $mobile;
                                        $response['data']['admin_firebase_chat_email'] = $admin_firebase_email['User']['firebase_chat_email'];;

                                        /* this code update app_user_id into subscriber table when app downnlad and install because app admin subscribe me for some channel */
                                        //$this->Subscriber->updateAll(array('app_user_id' => $last_id), array('AND' => array("Subscriber.mobile" => $mobile, "Subscriber.app_id" => $thin_app_id)));
                                        /* this code create gullak for user */
                                        if ($this->Custom->updateCoins('REGISTER', $last_id, $last_id, 0, $thin_app_id, 0)) {
                                            $option = array(
                                                'username' => $username,
                                                'mobile' => $mobile,
                                                'verification' => $verification_code,
                                                'thinapp_id' => $thin_app_id
                                            );
                                            $this->Custom->send_otp($option);
                                            $this->SubscribeDefaultChannelsSignIn($this->User->getLastInsertId(), $mobile, $thin_app_id);
                                            $datasource->commit();


                                        } else {
                                            $response['status'] = 0;
                                            $response['message'] = "Sorry user could not register";
                                        }

                                    } else {
                                        $response['status'] = 0;
                                        $response['message'] = "An error occured! Please try again later." . debug($this->User->validationErrors);
                                    }
                                } else {
                                    $response['status'] = 0;
                                    $response['message'] = "Sorry you cannot register right now. Please try letter";
                                }
                            } else {

                                $is_active = $this->User->find("first", array(
                                    "conditions" => array(
                                        "User.mobile" => $mobile,
                                        "User.is_verified" => 'Y',
                                        "User.thinapp_id" => $thin_app_id
                                    ),
                                    'contain' => false
                                ));

                                if (!empty($is_active)) {
                                    $verification_code = $this->Custom->getRandomString(4);
                                    //$verification_code = '2222';
                                    $this->User->set('id', $is_active['User']['id']);
                                    //$this->User->set('username', $username);
                                    $this->User->set('verification_code', $verification_code);
                                    $this->User->set('device_token', $device_token);
                                    $this->User->set('firebase_token', $firebase_token);
                                    $this->User->set('is_verified', 'N');
                                    if ($this->User->save()) {
                                        $response['status'] = 1;
                                        $response['message'] = "Login successfully";
                                        $response['data']['is_exist'] = 1;
                                        $response['data']['user_id'] = $userdata['User']['id'];
                                        $response['data']['mbroadcast_app_id'] = MBROADCAST_APP_ID;
                                        $response['data']['role_id'] = $userdata['User']['role_id'];
                                        $response['data']['firebase_chat_email'] = $userdata['User']['firebase_chat_email'];
                                        $response['data']['firebase_chat_password'] = $userdata['User']['firebase_chat_password'];
                                        $response['data']['website_url'] = $thin_app_data['Thinapp']['website_url'];
                                        $response['data']['facebook_url'] = $thin_app_data['Thinapp']['facebook_url'];

                                        $response['data']['admin_firebase_chat_email'] = $admin_firebase_email['User']['firebase_chat_email'];;
                                        if (isset($userdata['User']['username'])) {
                                            $response['data']['username'] = $userdata['User']['username'];
                                        }
                                        $username = $userdata['User']['username'];
                                        $response['data']['mobile'] = $userdata['User']['mobile'];

                                        $option = array(
                                            'username' => $username,
                                            'mobile' => $mobile,
                                            'verification' => $verification_code,
                                            'thinapp_id' => $thin_app_id
                                        );
                                        $this->Custom->send_otp($option);
                                        $this->SubscribeDefaultChannelsSignIn($userdata['User']['id'], $userdata['User']['mobile'], $thin_app_id);

                                    } else {
                                        $response['status'] = 0;
                                        $response['message'] = "Sorry user could not login";

                                    }


                                } else {
                                    $is_active = $this->User->find("first", array(
                                        "conditions" => array(
                                            "User.mobile" => $mobile,
                                            "User.is_verified" => 'N',
                                            "User.thinapp_id" => $thin_app_id
                                        ),
                                        'contain' => false

                                    ));

                                    if (!empty($is_active)) {

                                        $verification_code = $this->Custom->getRandomString(4);
                                        $this->User->set('id', $is_active['User']['id']);
                                        $this->User->set('verification_code', $verification_code);
                                        $this->User->set('firebase_token', $firebase_token);
                                        $this->User->set('device_token', $device_token);
                                        if ($this->User->save()) {
                                            $username = $is_active['User']['username'];
                                            $response['status'] = 1;
                                            $response['data']['is_exist'] = 1;
                                            $response['message'] = "Signup successfully";
                                            $response['data']['user_id'] = $is_active['User']['id'];
                                            $response['data']['mbroadcast_app_id'] = MBROADCAST_APP_ID;
                                            $response['data']['username'] = $username;
                                            $response['data']['mobile'] = $mobile;
                                            $response['data']['image'] = $is_active['User']['image'];
                                            $response['data']['email'] = $is_active['User']['email'];
                                            $response['data']['role_id'] = $is_active['User']['role_id'];
                                            $response['data']['firebase_chat_email'] = $is_active['User']['firebase_chat_email'];
                                            $response['data']['firebase_chat_password'] = $is_active['User']['firebase_chat_password'];
                                            $response['data']['website_url'] = $thin_app_data['Thinapp']['website_url'];
                                            $response['data']['facebook_url'] = $thin_app_data['Thinapp']['facebook_url'];

                                            $response['data']['admin_firebase_chat_email'] = $admin_firebase_email['User']['firebase_chat_email'];;
                                            $option = array(
                                                'username' => $username,
                                                'mobile' => $mobile,
                                                'verification' => $verification_code,
                                                'thinapp_id' => $thin_app_id
                                            );
                                            $this->Custom->send_otp($option);

                                            $this->SubscribeDefaultChannelsSignIn($is_active['User']['id'], $is_active['User']['mobile'], $thin_app_id);

                                        } else {
                                            $response['status'] = 0;
                                            $response['message'] = "Sorry user could not login";
                                        }

                                    }

                                }
                                $datasource->commit();
                            }


                        } catch (Exception $e) {

                            // echo $e->getMessage();die;
                            $datasource->rollback();
                            $response['status'] = 0;
                            $response['message'] = "Sorry something went wrong on server";
                        }
                    } else {
                        $response['status'] = 0;
                        $response['message'] = "System error found. Please try later";
                    }


                } else {
                    $response['status'] = 0;
                    $response['message'] = $this->Custom->getResponseMessage($result);
                }

            } else {
                $response['status'] = 0;
                $response['message'] = "Request not found";
            }
            echo json_encode($response);
            exit;
        }
    }


    /* this function created by mahendra */
    public function verify_account()
    {

        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        if ($this->request->is('post')) {
            $response = array();
            $user_id = $data['user_id'];
            $code = $data['code'];
            $app_key = $data['app_key'];
            $thin_app_id = $data['thin_app_id'];


            if ($user_id != '' && $code != '' && $app_key != '') {
                if (($result = $this->Custom->CheckIsValidApp($app_key, $thin_app_id)) && ($result == 1)) {
                    $userdata = $this->User->find("first", array(
                        "conditions" => array(
                            "User.id" => $user_id,
                            "User.verification_code" => $code
                        ),
                        'contain' => false,

                        'fields' => array('User.id')
                    ));
                    if (!empty($userdata)) {
                        $this->User->id = $user_id;
                        if ($this->User->saveField('is_verified', 'Y')) {
                            $response['status'] = 1;
                            $response['message'] = "Verification successful";
                        } else {
                            $response['status'] = 0;
                            $response['message'] = "Failed";
                        }
                    } else {
                        $response['status'] = 0;
                        $response['message'] = "Wrong verification code";
                    }
                } else {
                    $response['status'] = 0;
                    $response['message'] = $this->Custom->getResponseMessage($result);
                }
            } else {
                $response['status'] = 0;
                $response['message'] = "Request not found";
            }
            echo json_encode($response);
            exit;
        }
    }


    /* this function created by mahendra*/
    public function refresh_subscriber_topic_login()
    {

        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        if ($this->request->is('post')) {
            $response = array();
            $user_id = $data['user_id'];
            $mobile = $data['mobile'];
            $thin_app_id = $data['thin_app_id'];
            $app_key = $data['app_key'];

            if ($user_id != '' && $app_key != '' && $thin_app_id != '' && $mobile != '') {
                if (($result = $this->Custom->CheckIsValidApp($app_key, $thin_app_id)) && ($result == 1)) {
                    $this->Custom->refresh_firebase_token($user_id, $mobile);
                    $response['status'] = 1;
                    $response['message'] = "Token refresh successfully";
                } else {
                    $response['status'] = 0;
                    $response['message'] = $this->Custom->getResponseMessage($result);
                }
            } else {
                $response['status'] = 0;
                $response['message'] = "Invalid request parameter";
            }
            echo json_encode($response);
            exit;
        }
    }


    /* api updated by mahendra this function use for subscribe users with multiple numbers*/
    public function add_subscriber_revised()
    {


        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        if ($this->request->is('post')) {
            $response = array();
            $channel_id = $data['channel_id'];
            $user_id = $data['user_id'];
            $app_key = $data['app_key'];
            $thin_app_id = $data['thin_app_id'];

            //PR($data);die;

            if (isset($channel_id) && isset($app_key) && isset($user_id)) {
                if (($result = $this->Custom->CheckIsValidApp($app_key, $thin_app_id)) && ($result == 1)) {

                    if (isset($data['contacts'])) {

                        $channel = $this->Channel->find('first', array(
                            'conditions' => array(
                                'Channel.id' => $channel_id
                            ),
                            'contain' => false,

                        ));
                        if (!empty($channel)) {
                             $total_sms = Custom::get_total_sms_thinapp($thin_app_id,'T');
                             $app_data = $this->Custom->getThinAppData($thin_app_id);
                            $app_name = $app_data['name'];

                            if ($total_sms >= count($data['contacts'])) {
                                $list_array = array();
                                $total_subscribed_nubmber = 0;
                                $already_subscribed = 0;
                                $invalid_mobile = 0;
                                foreach ($data['contacts'] as $key => $contact_data) {
                                    $mobile = $this->Custom->create_mobile_number($contact_data['mobile']);
                                    if ($mobile) {

                                        $is_user_exist = 0;
                                        $user = $this->User->find("first", array("contain" => false, "conditions" => array("User.mobile" => $mobile, "User.thinapp_id" => $thin_app_id), 'fields' => array('id', 'device_token', 'device_type')));
                                        if (!empty($user) && isset($user)) {
                                            $is_user_exist = $user['User']['id'];
                                        }
                                        $subscribers = $this->Subscriber->find('first', array(
                                            'conditions' => array(
                                                'Subscriber.channel_id' => $channel_id,
                                                'Subscriber.mobile' => $mobile,
                                                'Subscriber.app_id' => $channel['Channel']['app_id']
                                            ),
                                            'fields' => array('Subscriber.id', 'Subscriber.status')
                                        ));


                                        if (empty($subscribers)) {
                                            /* this code add default public channel to new user login or signup time*/
                                            $this->Subscriber->create();
                                            $this->Subscriber->set('channel_id', $channel['Channel']['id']);
                                            $this->Subscriber->set('user_id', $channel['Channel']['user_id']);
                                            $this->Subscriber->set('app_user_id', $is_user_exist);
                                            $this->Subscriber->set('app_id', $thin_app_id);
                                            $this->Subscriber->set('mobile', $mobile);
                                            $this->Subscriber->set('status', 'SUBSCRIBED');
                                            if ($this->Subscriber->save()) {
                                                $total_subscribed_nubmber++;
                                                $last_inser_id = $this->Subscriber->getLastInsertId();
                                                $list_array[$key]['subscriber_id'] = $last_inser_id;
                                                $list_array[$key]['channel_id'] = $channel_id;
                                                $list_array[$key]['channel_name'] = $channel['Channel']['channel_name'];
                                                $list_array[$key]['channel_image'] = $channel['Channel']['image'];
                                                if ($is_user_exist != 0) {
                                                    $list_array[$key]['username'] = $user['User']['username'];
                                                    $list_array[$key]['user_image'] = $user['User']['image'];
                                                    $list_array[$key]['user_id'] = $is_user_exist;
                                                }

                                                $message = $app_name . ' invited you to subscribe channel ' . $channel['Channel']['channel_name'] . '. Click to download ' . $app_data['apk_url'];
                                                if ($is_user_exist == 0) {
                                                    $option = array(
                                                        'mobile' => $mobile,
                                                        'message' => urlencode($message),
                                                        'thinapp_id' => $thin_app_id,
                                                        'sender_id' => $user_id
                                                    );
                                                    $this->Custom->send_message_system($option);

                                                } else {

                                                    $user_firebase_token = $this->Custom->getUserFirebaseToken($is_user_exist);
                                                    $this->Custom->add_subscriber_to_topic($channel['Channel']['app_id'], $channel['Channel']['topic_name'], $user_firebase_token);
                                                    $message = 'You are subscribed to channel ' . $channel['Channel']['channel_name'] . ' by ' . $app_name;
                                                    $option = array(
                                                        'thinapp_id' => $thin_app_id,
                                                        'user_id' => $is_user_exist,
                                                        'data' => array(
                                                            'thinapp_id' => $thin_app_id,
                                                            'channel_id' => $channel_id,
                                                            'role' => "USER",
                                                            'flag' => 'SUBSCRIBE',
                                                            'title' => "New Subscriber Request",
                                                            'message' => mb_strimwidth("Subscriber - " . $message, 0, 80, '...'),
                                                            'description' => mb_strimwidth($message, 0, 100, '...'),
                                                            'chat_reference' => '',
                                                            'module_type' => 'SUBSCRIBE',
                                                            'module_type_id' => 0,
                                                            'firebase_reference' => ""
                                                        )
                                                    );
                                                    $this->Custom->send_notification_to_device($option);
                                                    WebservicesFunction::deleteJson(array('get_subscriber_list_app' . $thin_app_id . '_user' . $is_user_exist),'subscriber');


                                                }


                                            }
                                        } else {


                                            if ($subscribers['Subscriber']['status'] == "SUBSCRIBED") {
                                                $already_subscribed++;
                                            } else {

                                                $this->Subscriber->id = $subscribers['Subscriber']['id'];
                                                $this->Subscriber->set('status', 'SUBSCRIBED');
                                                if ($this->Subscriber->save()) {
                                                    if ($is_user_exist == 0) {
                                                        $message = $app_name . ' invited you to subscribe channel ' . $channel['Channel']['channel_name'] . '. Click to download ' . $app_data['apk_url'];
                                                        $option = array(
                                                            'mobile' => $mobile,
                                                            'message' => urlencode($message),
                                                            'thinapp_id' => $thin_app_id,
                                                            'sender_id' => $user_id
                                                        );
                                                        $this->Custom->send_message_system($option);
                                                    } else {

                                                        $user_firebase_token = $this->Custom->getUserFirebaseToken($is_user_exist);
                                                        $this->Custom->add_subscriber_to_topic($channel['Channel']['app_id'], $channel['Channel']['topic_name'], $user_firebase_token);
                                                        $message = 'You are subscribed to channel ' . $channel['Channel']['channel_name'] . ' by ' . $app_name;
                                                        $option = array(
                                                            'thinapp_id' => $thin_app_id,
                                                            'user_id' => $is_user_exist,
                                                            'data' => array(
                                                                'thinapp_id' => $thin_app_id,
                                                                'channel_id' => $channel_id,
                                                                'role' => "USER",
                                                                'flag' => 'SUBSCRIBE',
                                                                'title' => "New Subscriber Request",
                                                                'message' => mb_strimwidth("Subscriber - " . $message, 0, 80, '...'),
                                                                'description' => mb_strimwidth($message, 0, 100, '...'),
                                                                'chat_reference' => '',
                                                                'module_type' => 'SUBSCRIBE',
                                                                'module_type_id' => 0,
                                                                'firebase_reference' => ""
                                                            )
                                                        );
                                                        $this->Custom->send_notification_to_device($option);
                                                        WebservicesFunction::deleteJson(array('get_subscriber_list_app' . $thin_app_id . '_user' . $is_user_exist),'subscriber');

                                                    }

                                                }
                                            }
                                        }
                                    } else {
                                        $invalid_mobile++;
                                    }

                                }
                                if ($total_subscribed_nubmber == count($data['contacts'])) {
                                    $response['status'] = 1;
                                    $response['message'] = "Subscribers added successfully";
                                    $response['details'] = $list_array;
                                } else if ($invalid_mobile == count($data['contacts'])) {
                                    $response['status'] = 0;
                                    $response['message'] = "All numbers are invalid";
                                } else if ($already_subscribed == count($data['contacts'])) {
                                    $response['status'] = 0;
                                    $response['message'] = "All numbers are alreday subscribed";
                                } else {
                                    $response['status'] = 1;
                                    $response['message'] = "Few subscribers added successfully";
                                    $response['details'] = $list_array;
                                }
                            } else {
                                $response['status'] = 0;
                                $response['message'] = "System error found. Please try later";
                            }

                        } else {
                            $response['status'] = 0;
                            $response['message'] = "Sorry channel not found";
                        }

                    } else {
                        $response['status'] = 0;
                        $response['message'] = "Not any subscriber details found";
                    }
                } else {
                    $response['status'] = 0;
                    $response['message'] = $this->Custom->getResponseMessage($result);
                }
            } else {
                $response['status'] = 0;
                $response['message'] = "Invalid request parameter";
            }
            echo json_encode($response);
            exit;
        }
    }


        
    
    
    /* api updated by mahendra this api subbscribe and unsubscribe onley single persone with plus button and minus button  */
    public function update_subscription_status_revised()
    {


        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        if ($this->request->is('post')) {
            $response = array();
            $channel_id = $data['channel_id'];
            $app_user_id = $data['user_id'];
            $mobile = $data['mobile'];
            $status = $data['status'];
            $app_key = $data['app_key'];
            $thin_app_id = $data['thin_app_id'];

            if (isset($mobile) && isset($channel_id) && isset($app_key)) {
                if (($result = $this->Custom->CheckIsValidApp($app_key, $thin_app_id)) && ($result == 1)) {
                    $channel = $this->Channel->find('first', array(
                        'conditions' => array(
                            'Channel.id' => $channel_id,
                            'Channel.status' => 'Y'
                        ),
                        'contain' => false

                    ));
                    if (!empty($channel)) {

                        $subscribers = $this->Subscriber->find('first', array(
                            'conditions' => array(
                                'Subscriber.channel_id' => $channel_id,
                                'Subscriber.app_user_id' => $app_user_id,
                                'Subscriber.app_id' => $channel['Channel']['app_id']
                            ),
                            'fields' => array('Subscriber.id')
                        ));

                        if (empty($subscribers)) {
                            /* this code add default public channel to new user login or signup time*/
                            $this->Subscriber->create();
                            $this->Subscriber->set('channel_id', $channel['Channel']['id']);
                            $this->Subscriber->set('user_id', $channel['Channel']['user_id']);
                            $this->Subscriber->set('app_user_id', $app_user_id);
                            $this->Subscriber->set('app_id', $thin_app_id);
                            $this->Subscriber->set('mobile', $mobile);
                            $this->Subscriber->set('status', 'SUBSCRIBED');
                            if ($this->Subscriber->save()) {
                                $response_data[] = WebservicesFunction::fun_get_subscriber_list($thin_app_id, $app_user_id, PAGINATION_LIMIT, 0);
                                WebservicesFunction::createJson('get_subscriber_list_app' . $thin_app_id . "_user" . $app_user_id, $response_data, 'CREATE','subscriber');
                                $user_firebase_token = $this->Custom->getUserFirebaseToken($app_user_id);
                                $this->Custom->add_subscriber_to_topic($channel['Channel']['app_id'], $channel['Channel']['topic_name'], $user_firebase_token);
                                $response['status'] = 1;
                                $response['message'] = "Subscriber add successfully";
                            } else {
                                $response['status'] = 0;
                                $response['message'] = "Sorry subscriber could not add";
                            }

                        } else {
                            $this->Subscriber->id = $subscribers['Subscriber']['id'];
                            $this->Subscriber->set('status', $status);
                            if ($this->Subscriber->save()) {
                                $response['status'] = 1;
                                if ($status == "SUBSCRIBED") {
                                    $user_firebase_token = $this->Custom->getUserFirebaseToken($app_user_id);
                                    $this->Custom->add_subscriber_to_topic($channel['Channel']['app_id'], $channel['Channel']['topic_name'], $user_firebase_token);
                                    $response['message'] = "Subscribered  successfully";
                                } else {
                                    $user_firebase_token = $this->Custom->getUserFirebaseToken($app_user_id);
                                    $this->Custom->remove_subscriber_from_topic($channel['Channel']['app_id'], $channel['Channel']['topic_name'], $user_firebase_token);
                                    $response['message'] = "Unsubscribered successfully";
                                }
                                $response_data[] = WebservicesFunction::fun_get_subscriber_list($thin_app_id, $app_user_id, PAGINATION_LIMIT, 0);
                                WebservicesFunction::createJson('get_subscriber_list_app' . $thin_app_id . "_user" . $app_user_id, $response_data, 'CREATE','subscriber');

                            } else {
                                $response['status'] = 0;
                                $response['message'] = "Sorry subscriber could not update";
                            }
                        }

                    } else {
                        $response['status'] = 0;
                        $response['message'] = "Sorry channel not found";
                    }

                } else {
                    $response['status'] = 0;
                    $response['message'] = $this->Custom->getResponseMessage($result);
                }
            } else {
                $response['status'] = 0;
                $response['message'] = "Invalid request parameter";
            }

            echo json_encode($response);
            exit;
        }
    }


    /* created by mahendra */
    public function delete_subscriber()
    {
        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        if ($this->request->is('post')) {
            $response = array();
            $subscriber_id = $data['subscriber_id'];
            $app_user_id = $data['user_id'];
            $app_key = $data['app_key'];
            $thin_app_id = $data['thin_app_id'];
            if (isset($subscriber_id) && isset($app_key) && isset($app_user_id)) {
                if (($result = $this->Custom->CheckIsValidApp($app_key, $thin_app_id)) && ($result == 1)) {
                    $subscribers = $this->Subscriber->find('first', array(
                        'conditions' => array(
                            'Subscriber.id' => $subscriber_id,
                        ),
                        'fields' => array('Subscriber.id', 'Subscriber.app_user_id', 'Channel.app_id', 'Channel.topic_name'),
                        'Contain' => array('Channel'),

                    ));
                    if (!empty($subscribers)) {
                        $this->Subscriber->id = $subscribers['Subscriber']['id'];
                        $this->Subscriber->set('status', "UNSUBSCRIBED");
                        if ($this->Subscriber->save()) {


                            $response_data[] = WebservicesFunction::fun_get_subscriber_list($thin_app_id, $subscribers['Subscriber']['app_user_id'], PAGINATION_LIMIT, 0);
                            WebservicesFunction::createJson('get_subscriber_list_app' . $thin_app_id . "_user" . $subscribers['Subscriber']['app_user_id'], $response_data, 'CREATE','subscriber');


                            $user_firebase_token = $this->Custom->getUserFirebaseToken($subscribers['Subscriber']['app_user_id']);
                            if (!empty($user_firebase_token)) {
                                $this->Custom->remove_subscriber_from_topic($subscribers['Channel']['app_id'], $subscribers['Channel']['topic_name'], $user_firebase_token);
                            }
                            $response['status'] = 1;
                            $response['message'] = "Subscriber deleted successfully";
                        } else {
                            $response['status'] = 0;
                            $response['message'] = "Sorry subscriber could not delete";
                        }

                    } else {
                        $response['status'] = 0;
                        $response['message'] = "Sorry invalid subscriber";
                    }
                } else {
                    $response['status'] = 0;
                    $response['message'] = $this->Custom->getResponseMessage($result);
                }
            } else {
                $response['status'] = 0;
                $response['message'] = "Invalid request parameter";
            }

            echo json_encode($response);
            exit;
        }
    }

    /* created by mahendra */
    public function channel_delete()
    {

        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        if ($this->request->is('post')) {
            $response = array();
            $channel_id = $data['channel_id'];
            $app_user_id = $data['user_id'];
            $app_key = $data['app_key'];
            $thin_app_id = $data['thin_app_id'];

            if (isset($channel_id) && isset($app_key) && isset($app_user_id)) {
                if (($result = $this->Custom->CheckIsValidApp($app_key, $thin_app_id)) && ($result == 1)) {
               
                    $channel_data = $this->Channel->find('first', array(
                        'conditions' => array(
                            'Channel.id' => $channel_id,
                        ),
                        'fields' => array('Channel.id', 'Channel.user_id'),
                        'Contain' => false
                    ));
                    if (!empty($channel_data)) {
                        if ($channel_data['Channel']['user_id'] == $app_user_id) {
                            $this->Channel->set('id', $channel_id);
                            $this->Channel->set('status', "N");
                            if ($this->Channel->save()) {

                                WebservicesFunction::delete_channel_subscriber_cache($channel_id);

                                $response['status'] = 1;
                                $response['message'] = "Channel deleted successfully";
                                /************UPDATED BY VISHWAJEET**********/
                                $fileName = 'get_my_channel_list_app' . $thin_app_id . '_user' . $app_user_id;
                                $file_path = 'cache/' . $fileName . '.json';
                                if (file_exists($file_path)) {
                                    unlink($file_path);
                                }

                                $collaboratorList = $this->Collaborator->find('all', array(
                                    "conditions" => array(
                                        "Collaborator.channel_id" => $channel_id,
                                        "Collaborator.status !=" => 'CANCELED'
                                    ),
                                    'fields' => array('user_id', 'thinapp_id'),
                                    'contain' => false
                                ));


                                foreach ($collaboratorList as $collaborator) {
                                    $fileName = 'get_my_channel_list_app' . $collaborator['Collaborator']['thinapp_id'] . '_user' . $collaborator['Collaborator']['user_id'];
                                    $file_path = 'cache/' . $fileName . '.json';
                                    if (file_exists($file_path)) {
                                        unlink($file_path);
                                    }
                                }

                                /************UPDATED BY VISHWAJEET END**********/
                            } else {
                                $response['status'] = 0;
                                $response['message'] = "Sorry channel could not deleted";
                            }

                        } else {
                            $response['status'] = 0;
                            $response['message'] = "Sorry you are not authorized user";
                        }
                    } else {
                        $response['status'] = 0;
                        $response['message'] = "Channel could not found";
                    }
                } else {
                    $response['status'] = 0;
                    $response['message'] = $this->Custom->getResponseMessage($result);
                }
            } else {
                $response['status'] = 0;
                $response['message'] = "Invalid request parameter";
            }

            echo json_encode($response);
            exit;
        }
    }


    /* this function created by mahendra */
    public function add_channel()
    {

        $request = file_get_contents("php://input");
        $data = json_decode($request, true);


        if ($this->request->is('post')) {
            $response = array();
            $user_id = $data['user_id'];
            $channel_name = $data['channel_name'];
            $channel_desc = $data['channel_desc'];
            $allow_sms = $data['allow_sms'];
            $thin_app_id = $data['thin_app_id'];
            $channel_image = $data['image_path'];
            $app_key = $data['app_key'];
            $is_searchable = $data['is_searchable'];
            $is_publish_mbroadcast = $data['is_publish_mbroadcast'];
            $record_id = $data['record_id'];

            if ($user_id != '' && $channel_name != '' && $app_key != '' && $allow_sms != '' && $is_searchable != '' && $is_publish_mbroadcast != '') {
                if (($result = $this->Custom->CheckIsValidApp($app_key, $thin_app_id)) && ($result == 1)) {

                    $datasource = $this->Channel->getDataSource();
                    try {
                        $datasource->begin();
                        $chdata = array();

                        $channel_data = $this->Channel->find("first", array(
                            "conditions" => array(
                                "Channel.channel_name" => trim($channel_name),
                                "Channel.app_id" => $thin_app_id
                            ),
                            "contain" => false
                        ));

                        $user_data = $this->User->find("first", array(
                            "conditions" => array(
                                "User.id" => $user_id
                            ),
                            "contain" => false
                        ));

                        $mbroadcast_app_id = MBROADCAST_APP_ID;
                        if ($user_data['User']['role_id'] == 5 || $thin_app_id == $mbroadcast_app_id) {
                            if (empty($channel_data)) {
                                // $channel_image = SITE_URL . "img/channel/default/channel_default_img.png";
                                $this->Channel->create();
                                $this->Channel->set('user_id', $user_id);
                                $this->Channel->set('channel_name', $channel_name);
                                $this->Channel->set('channel_desc', $channel_desc);
                                $this->Channel->set('image', $channel_image);
                                $this->Channel->set('allow_sms', $allow_sms);
                                $this->Channel->set('app_id', $thin_app_id);
                                $this->Channel->set('is_searchable', $is_searchable);
                                $this->Channel->set('is_publish_mbroadcast', $is_publish_mbroadcast);
                                if ($this->Channel->save()) {

                                    $last_inser_id = $this->Channel->getLastInsertId();
                                    $this->Channel->id = $last_inser_id;
                                    $topic_name = $this->Custom->create_topic_name($last_inser_id);
                                    $this->Channel->set('topic_name', $topic_name);
                                    $this->Channel->save();
                                    /*this topic autometically add when one subscriber add to this channel*/
                                    //$this->Custom->create_topic($thin_app_id,$last_inser_id,$topic_name);

                                    $response['status'] = 1;
                                    $response['message'] = "Channel added Successfully";

                                    /* subscrie app admin user for its private channel*/
                                    $this->Subscriber->create();
                                    $this->Subscriber->set('channel_id', $last_inser_id);
                                    $this->Subscriber->set('user_id', $user_id);
                                    $this->Subscriber->set('app_user_id', $user_id);
                                    $this->Subscriber->set('name', $user_data['User']['username']);
                                    $this->Subscriber->set('mobile', $user_data['User']['mobile']);
                                    $this->Subscriber->set('app_id', $thin_app_id);
                                    $this->Subscriber->set('status', 'SUBSCRIBED');
                                    $this->Subscriber->save();


                                    //include(WWW_ROOT.'webservice'.DS.'WebservicesFunction.php');
                                    $response_data[] = WebservicesFunction::fun_get_subscriber_list($thin_app_id, $user_id, PAGINATION_LIMIT, 0);
                                    WebservicesFunction::createJson('get_subscriber_list_app' . $thin_app_id . "_user" . $user_id, $response_data, 'CREATE','subscriber');


                                    $channel_data = $this->Channel->find("first", array("conditions" => array("Channel.id" => $last_inser_id)));
                                    if (isset($channel_data) && !empty($channel_data)) {
                                        $response['data']['user_id'] = $channel_data['Channel']['user_id'];
                                        $response['data']['channel_id'] = $channel_data['Channel']['id'];
                                        $response['data']['channel_name'] = $channel_data['Channel']['channel_name'];
                                        $response['data']['channel_desc'] = $channel_data['Channel']['channel_desc'];
                                        $response['data']['channel_image'] = $channel_data['Channel']['image'];
                                        $response['data']['record_id'] = $record_id;
                                        $response['data']['pending_status'] = "1";
                                        $response['data']['topic_name'] = $topic_name;
                                        $response['data']['modified'] = $channel_data['Channel']['modified'];
                                        $response['data']['added_on'] = $channel_data['Channel']['created'];
                                        /************UPDATED BY VISHWAJEET**********/
                                        $fileName = 'get_my_channel_list_app' . $thin_app_id . '_user' . $channel_data['Channel']['user_id'];
                                        $file_path = 'cache/' . $fileName . '.json';
                                        if (file_exists($file_path)) {
                                            unlink($file_path);
                                        }
                                        /************UPDATED BY VISHWAJEET**********/
                                        $datasource->commit();

                                    }
                                } else {
                                    $response['status'] = 0;
                                    $response['message'] = "An error occured! Please try again later";
                                }
                            } else {
                                $response['status'] = 0;
                                $response['message'] = "Channel already registered with same name";
                            }
                        } else {
                            $response['status'] = 0;
                            $response['message'] = "Sorry you can not create channel";
                        }


                    } catch (Exception $e) {
                        $datasource->rollback();
                        $response['status'] = 0;
                        $response['message'] = "Sorry channel could not add";
                    }
                } else {
                    $response['status'] = 0;
                    $response['message'] = $this->Custom->getResponseMessage($result);
                }
            } else {
                $response['status'] = 0;
                $response['message'] = "Invalid request parameter";
            }
            echo json_encode($response);
            exit;
        }
    }


    /* this function created by mahendra */
    public function SubscribeDefaultChannelsSignIn($app_user_id = null, $mobile = null, $thin_app_id = null)
    {

        /* this coded add  app deftautl channel at first time signup */

        $users_data = $this->User->find('first', array(
            'conditions' => array(
                'User.mobile' => $mobile,
                'User.thinapp_id' => $thin_app_id,
            ),

            'contain' => array(
                'Thinapp' => array(
                    'fields' => array('id', 'app_id', 'name', 'user_id')
                )
            )
        ));

        if (!empty($users_data)) {

            /* check this user is thinapp admin or user if not empty it mean this is thin app admin*/
            if (!empty($users_data['Thinapp']) && $users_data['User']['id'] == $users_data['Thinapp']['user_id']) {
                $channel = $this->Channel->find('first', array(
                    'conditions' => array(
                        'Channel.user_id' => $users_data['User']['id'],
                        'Channel.channel_status' => 'DEFAULT',
                        'Channel.app_id' => $thin_app_id
                    ),

                    'contain' => false,
                ));
                if (empty($channel)) {
                    $datasource = $this->Channel->getDataSource();
                    try {
                        $datasource->begin();
                        $this->Channel->create();
                        $this->Channel->create();
                        $this->Channel->set('user_id', $users_data['User']['id']);
                        $this->Channel->set('channel_name', $users_data['Thinapp']['name']);
                        $this->Channel->set('channel_desc', "This is your first default  channel");
                        $this->Channel->set('app_id', $thin_app_id);
                        $this->Channel->set('is_searchable', 'Y');
                        $this->Channel->set('is_publish_mbroadcast', 'Y');
                        $this->Channel->set('channel_status', 'DEFAULT');
                        if ($this->Channel->save()) {
                            $last_inser_id = $this->Channel->getLastInsertId();
                            $topic_name = $this->Custom->create_topic_name($last_inser_id);
                            $this->Channel->id = $last_inser_id;
                            $this->Channel->set('topic_name', $topic_name);
                            $this->Channel->save();

                            /*create topic on firebase*/
                            /* topic will create autometic when subscrite to this topic*/
                            // $this->Custom->create_topic($thin_app_id,$last_inser_id,$topic_name);

                            /* subscrie app admin user for its private channel*/
                            $this->Subscriber->create();
                            $this->Subscriber->set('channel_id', $last_inser_id);
                            $this->Subscriber->set('user_id', $users_data['User']['id']);
                            $this->Subscriber->set('app_user_id', $users_data['User']['id']);
                            $this->Subscriber->set('name', $users_data['User']['username']);
                            $this->Subscriber->set('mobile', $users_data['User']['mobile']);
                            $this->Subscriber->set('app_id', $thin_app_id);
                            $this->Subscriber->set('status', 'SUBSCRIBED');
                            $this->Subscriber->save();


                            $response_data[] = WebservicesFunction::fun_get_subscriber_list($thin_app_id, $app_user_id, PAGINATION_LIMIT, 0);
                            WebservicesFunction::createJson('get_subscriber_list_app' . $thin_app_id . "_user" . $app_user_id, $response_data, 'CREATE','subscriber');

                            //$this->Custom->add_subscriber_to_topic($thin_app_id,$topic_name,$users_data['User']['firebase_token']);
                            $datasource->commit();
                        }
                    } catch (Exception $e) {
                        $datasource->rollback();
                    }
                }


            } else {

                /* add user to default cahnnel of app */
                /* this cod add  public channel to app */

                $channel = $this->Channel->find('first', array(
                    'conditions' => array(
                        'Channel.channel_status' => 'DEFAULT',
                        'Channel.app_id' => $thin_app_id
                    ),

                    'fields' => array('Channel.id', 'Channel.topic_name', 'Channel.user_id', 'Channel.app_id'),
                    'contain' => false,
                ));
                if (!empty($channel)) {
                    $subscribers = $this->Subscriber->find('first', array(
                        'conditions' => array(
                            'Subscriber.channel_id' => $channel['Channel']['id'],
                            'Subscriber.app_user_id' => $app_user_id,
                            'Subscriber.app_id' => $thin_app_id

                        ),

                        'contain' => false,
                        'fields' => array('Subscriber.id')
                    ));
                    if (empty($subscribers)) {
                        /* this code add default public channel to new user login or signup time*/
                        $this->Subscriber->create();
                        $this->Subscriber->set('channel_id', $channel['Channel']['id']);
                        $this->Subscriber->set('user_id', $channel['Channel']['user_id']);
                        $this->Subscriber->set('app_user_id', $app_user_id);
                        $this->Subscriber->set('app_id', $thin_app_id);
                        $this->Subscriber->set('mobile', $mobile);
                        $this->Subscriber->set('status', 'SUBSCRIBED');
                        $subs = $this->Subscriber->save();


                        $response_data[] = WebservicesFunction::fun_get_subscriber_list($thin_app_id, $app_user_id, PAGINATION_LIMIT, 0);
                        WebservicesFunction::createJson('get_subscriber_list_app' . $thin_app_id . "_user" . $app_user_id, $response_data, 'CREATE','subscriber');


                        //$this->Custom->add_subscriber_to_topic($thin_app_id,$channel['Channel']['topic_name'],$users_data['User']['firebase_token']);
                    }
                }


            }

            /* this cod add  public channel to app  start*/


            $chdata = $this->Channel->find('all', array(
                'conditions' => array(
                    'Channel.channel_status' => 'PUBLIC',
                    'Channel.status' => 'Y'
                ),

                'contain' => false,
                'fields' => array('Channel.id', 'Channel.app_id', 'Channel.topic_name', 'Channel.user_id')
            ));
            if (!empty($chdata) && isset($chdata)) {
                foreach ($chdata as $key => $channel_data) {

                    $subscribers = $this->Subscriber->find('first', array(
                        'conditions' => array(
                            'Subscriber.channel_id' => $channel_data['Channel']['id'],
                            'Subscriber.app_user_id' => $app_user_id,
                            'Subscriber.app_id' => $channel_data['Channel']['app_id'],
                        ),

                        'contain' => false,
                        'fields' => array('Subscriber.id')
                    ));
                    if (empty($subscribers)) {
                        /* this code add default public channel to new user login or signup time*/
                        $this->Subscriber->create();
                        $this->Subscriber->set('channel_id', $channel_data['Channel']['id']);
                        $this->Subscriber->set('user_id', $channel_data['Channel']['user_id']);
                        $this->Subscriber->set('app_user_id', $app_user_id);
                        $this->Subscriber->set('app_id', $channel_data['Channel']['app_id']);
                        $this->Subscriber->set('mobile', $mobile);
                        $this->Subscriber->set('status', 'SUBSCRIBED');
                        $subs = $this->Subscriber->save();
                        $response_data[] = WebservicesFunction::fun_get_subscriber_list($thin_app_id, $app_user_id, PAGINATION_LIMIT, 0);
                        WebservicesFunction::createJson('get_subscriber_list_app' . $thin_app_id . "_user" . $app_user_id, $response_data, 'CREATE','subscriber');

                        //$this->Custom->add_subscriber_to_topic($channel_data['Channel']['app_id'],$channel_data['Channel']['topic_name'],$users_data['User']['firebase_token']);

                    }

                }

            }
            /* this cod add  public channel to app  start*/

            /* this cod add  update subscriber user_id */
            $this->Subscriber->updateAll(
                array('Subscriber.app_user_id' => $app_user_id),
                array(
                    'Subscriber.app_user_id' => 0,
                    'Subscriber.mobile' => $mobile,
                    'Subscriber.app_id' => $thin_app_id,
                    'Subscriber.status' => 'SUBSCRIBED'
                )
            );
            /* this cod add  public channel to app  start*/

            /*update id to colobrator table */
            $this->Collaborator->updateAll(
                array('Collaborator.user_id' => $users_data['User']['id']),
                array(
                    'Collaborator.mobile' => $mobile,
                    'Collaborator.thinapp_id' => $thin_app_id
                )
            );


        }

    }


    /* api created by mahendra */
    public function add_message_revised()
    {


        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        if ($this->request->is('post')) {
            $response = array();
            $user_id = $data['user_id'];
            $channel_id = $data['channel_id'];
            $message = $data['message'];
            $message_type = $data['message_type'];
            $message_file_url = $data['message_file_url'];
            $short_url = $data['short_url'];
            $thumb_url = $data['thumb_url'];
            $thin_app_id = $data['thin_app_id'];
            $app_key = $data['app_key'];

            if ($user_id != '' && $channel_id != '' && $app_key != '') {
                if (($result = $this->Custom->CheckIsValidApp($app_key, $thin_app_id)) && ($result == 1)) {


                    $send_message = true;
                    $is_thinapp_owner = $this->Custom->getChannelOwner($channel_id);
                    if ($is_thinapp_owner != $user_id) {
                        $is_colobrator = $this->Custom->is_collobrator_for_channel($user_id, $channel_id, $thin_app_id);
                        if (!$is_colobrator) {
                            $send_message = false;
                        }
                    }

                    if ($send_message === true) {
                         $total_sms = Custom::get_total_sms_thinapp($thin_app_id,'T');
                        $total_sub = $this->Custom->totalSmsSubscriber($channel_id, $thin_app_id);
                        if ($total_sms >= $total_sub) {
                            $datasource = $this->Message->getDataSource();
                            try {

                                if (empty($message) && $message_type == "TEXT") {
                                    $response['status'] = 0;
                                    $response['message'] = "Please enter message";
                                } else {

                                    $datasource->begin();
                                    $this->Message->create();
                                    $this->Message->set('owner_user_id', $user_id);
                                    $this->Message->set('channel_id', $channel_id);
                                    if (!empty($message)) {
                                        $this->Message->set('message', $message);
                                    }
                                    $this->Message->set('message_type', $message_type);
                                    $this->Message->set('message_file_url', $message_file_url);
                                    $this->Message->set('short_url', $short_url);
                                    $this->Message->set('thumb_url', $thumb_url);
                                    $this->Message->set('thinapp_id', $thin_app_id);
                                    if ($this->Message->save()) {
                                        $message_id = $this->Message->getLastInsertId();
                                        /* add messge to channel Message table*/
                                        $this->ChannelMessage->create();
                                        $this->ChannelMessage->set('message_id', $message_id);
                                        $this->ChannelMessage->set('channel_id', $channel_id);

                                        /* add row to message sttice table*/
                                        $this->MessageStatic->create();
                                        $this->MessageStatic->set('message_id', $message_id);

                                        if ($this->ChannelMessage->save() && $this->MessageStatic->save()) {
                                            /* this code update coins for this user */
                                            $this->Custom->updateCoins('POST', $user_id, $user_id, $message_id, $thin_app_id, 0);
                                            $app_name = "MBROADCAST";
                                            $channel_name = $this->Custom->getChannelName($channel_id);

                                            if (empty(trim($message))) {
                                                $message = $channel_name . " - New Message";
                                            } else {
                                                $message = $channel_name . " - " . $message;
                                                if ($message_type != "TEXT") {
                                                    $message = $channel_name . " - New Message";
                                                }
                                            }

                                            /* send notification to channel subscriber*/
                                            $sendArray = array(
                                                'channel_id' => $channel_id,
                                                'thinapp_id' => $thin_app_id,
                                                'flag' => 'NEWPOST',
                                                'title' => strtoupper($app_name),
                                                'message' => mb_strimwidth($message, 0, 50, '...'),
                                                'description' => '',
                                                'chat_reference' => '',
                                                'type' => $message_type,
                                                'file_path_url'=>$message_file_url,
                                                'module_type' => 'MESSAGE',
                                                'module_type_id' => $message_id,
                                                'firebase_reference' => ""
                                            );
                                            $this->Custom->send_topic_notification($sendArray);
                                            /* ADD MESSAGE CODE FOR UNREGISTER USERS*/


                                            $user_role = $this->Custom->get_user_role_id($user_id);
                                            $is_permission = $this->Custom->check_user_permission($thin_app_id, 'POST_SEND_NOTIFICATION_VIA_SMS');
                                            $is_collaborator = $this->Custom->is_collobrator($user_id, $channel_id, $thin_app_id);
                                            if ($user_role == 5 || $is_permission == "YES" || $is_collaborator == 'YES') {
                                                if (empty(trim($message))) {
                                                    $message = $channel_name . " - New Message";
                                                } else {
                                                    $message = $channel_name . " - " . $message. " ";
                                                    if ($message_type != "TEXT") {
                                                        $message = $channel_name . " - New Message ". $message_file_url." " ;
                                                    }
                                                }
                                                $message = mb_strimwidth($message, 0, 150, ' for more ');
                                                $this->Custom->sendBulkSms($channel_id, $thin_app_id, $message, $message_id, $user_id);
                                            }


                                            $datasource->commit();
                                            $response['status'] = 1;
                                            $response['message'] = "Message add successfully";


                                        } else {
                                            $datasource->rollback();
                                            $response['status'] = 0;
                                            $response['message'] = "Sorry message could not post";
                                        }

                                    } else {
                                        $datasource->rollback();
                                        $response['status'] = 0;
                                        $response['message'] = "Sorry message could not post";
                                    }

                                }


                            } catch (Exception $e) {
                                $datasource->rollback();
                                $response['status'] = 0;
                                $response['message'] = "Sorry message could not add";
                            }
                        } else {
                            $response['status'] = 0;
                            $response['message'] = "System error found. Please try later";
                        }
                    } else {
                        $response['status'] = 0;
                        $response['message'] = "Sorry, you are not authorized to post";
                    }


                } else {
                    $response['status'] = 0;
                    $response['message'] = $this->Custom->getResponseMessage($result);
                }
            } else {
                $response['status'] = 0;
                $response['message'] = "Invalid request parameter";
            }
            echo json_encode($response);
            exit;
        }
    }


    public function delete_message()
    {
        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        if ($this->request->is('post')) {
            $app_key = $data['app_key'];
            $user_id = $data['user_id'];
            $thin_app_id = $data['thin_app_id'];
            $message_id = $data['message_id'];
            $response = array();
            $delete_file = false;
            if ($user_id != '' && $app_key != '' && $thin_app_id != '' && $message_id != '') {
                if (($result = $this->Custom->CheckIsValidApp($app_key, $thin_app_id)) && ($result == 1)) {

                    $message = $this->Message->find('first', array(
                        'conditions' => array(
                            'Message.id' => $message_id,
                            'Message.thinapp_id' => $thin_app_id,
                            'Message.status' => 'Y'
                        ),
                        'contain' => false,
                        'fields' => array('Message.id','Message.message_file_url','Message.multiple_image')
                    ));
                    if ($message) {
                        $datasource = $this->Message->getDataSource();
                        try {

                            $datasource->begin();
                            $q = $this->Message->delete(array('Message.id' => $message_id));
                            $cm = $this->ChannelMessage->deleteAll(array(
                                'ChannelMessage.message_id' => $message_id,
                                'ChannelMessage.post_type_status IN' => array('POST', 'BROADCAST')

                            ));

                            $cm = $this->MessageAction->deleteAll(array(
                                'MessageAction.message_id' => $message_id,
                                'MessageAction.list_message_type IN' => array('POST', 'BROADCAST')
                            ));
                            $cm = $this->MessageStatic->deleteAll(array(
                                'MessageStatic.message_id' => $message_id,
                                'MessageStatic.list_message_type IN' => array('POST', 'BROADCAST')
                            ));

                            if ($q) {
                                $datasource->commit();
                                $delete_file =true;
                                $response['status'] = 1;
                                $response['message'] = "Message deleted successfully";
                            } else {
                                $response['status'] = 0;
                                $response['message'] = "Sorry unable to delete message";
                            }

                        } catch (Exception $e) {
                            $datasource->rollback();
                        }

                    } else {
                        $response['status'] = 0;
                        $response['message'] = "Sorry message  not found";
                    }

                } else {
                    $response['status'] = 0;
                    $response['message'] = $this->Custom->getResponseMessage($result);
                }
            } else {
                $response['status'] = 0;
                $response['message'] = "Invalid request parameter";
            }

            echo json_encode($response);
            if($delete_file === true){

                ignore_user_abort(true);
                if(!empty($message)){
                    if($message['Message']['multiple_image']=='YES'){
                        $file_array = Custom::getMessageMultiImageData($message['Message']['message_file_url']);
                        $key_array =array();
                        if(!empty($file_array)){
                            foreach ($file_array as $key => $file){
                                $file_name = explode("/", $file['path']);
                                $key_array[] = end($file_name);
                            }
                            Custom::deleteMultipleFileToAws($key_array);
                        }
                    }else{
                        $file_name = explode("/", $message['Message']['message_file_url']);
                        $file_key = end($file_name);
                        Custom::deleteFileToAws($file_key);
                    }

                }

            }
            exit;
        }
    }


    /* this function  created by mahendra */
    public function get_channel_messages_list()
    {


        $request = file_get_contents("php://input");
        $data = json_decode($request, true);

        if ($this->request->is('post')) {

            $response = array();
            $channel_id = $data['channel_id'];
            $user_id = $data['user_id'];
            $thin_app_id = $data['thin_app_id'];
            $app_key = $data['app_key'];
            $offset = $data['offset'];
            if ($channel_id != "" && $app_key != "" && $thin_app_id != "") {
                if (($result = $this->Custom->CheckIsValidApp($app_key, $thin_app_id)) && ($result == 1)) {
                    $limit = APP_PAGINATION_LIMIT;

                    $message_list = $this->ChannelMessage->find('all', array(
                        'recursive' => 2,
                        'conditions' => array(
                            'ChannelMessage.channel_id' => $channel_id,
                            'ChannelMessage.status' => 'ACTIVE'
                        ),
                        'contain' => array('Message', 'Message.MessageStatic', 'Event', 'Event.CoverImage', 'Event.EventStatic', 'Poll', 'Poll.PollStatic', 'LoseFound', 'LoseFound.LoseFoundStatic', 'Conference', 'Conference.ConferenceStatic', 'Buy', 'Buy.BuyStatic', 'Quest', 'Quest.QuestStatic', 'Rent', 'Rent.RentStatic', 'Borrow', 'Borrow.BorrowStatic', 'Sell', 'Sell.SellStatic', 'Sell.CoverImage'),
                        //'fields'=>array('Message.*'),
                        'order' => array('ChannelMessage.created DESC'),
                        'offset' => $offset * $limit,
                        'limit' => $limit
                    ));

                    //pr($message_list);die;
                    if (!empty($message_list)) {
                        $response['status'] = 1;
                        $response['message'] = "Message list found";
                        $counter = 0;
                        foreach ($message_list as $key => $list) {
                            $message_type = $list['ChannelMessage']['post_type_status'];
                            $data = array();
                            $data = $this->Custom->channel_message_list_param($message_type, $list, $channel_id, $thin_app_id, $user_id);
                            $response['data']['message'][$counter++] = $data;
                        }


                    } else {
                        $response['status'] = 0;
                        $response['message'] = "No message list found";
                    }


                    /* creadentiona perams */

                    $response['permission']['is_owner'] = ($this->Custom->getChannelOwner($channel_id) == $user_id) ? "YES" : "NO";
                    $is_collobrator = $this->Custom->is_collobrator_for_channel($user_id, $channel_id, $thin_app_id);
                    if (!empty($is_collobrator)) {
                        $response['permission']['is_collobrator'] = 'YES';
                        $response['permission']['coll_role'] = $is_collobrator['role'];
                    } else {
                        $response['permission']['is_collobrator'] = 'NO';
                        $response['permission']['coll_role'] = "";
                    }

                    /*========*/


                } else {
                    $response['status'] = 0;
                    $response['message'] = $this->Custom->getResponseMessage($result);
                }
            } else {
                $response['status'] = 0;
                $response['message'] = "Invalid ApiKey";
            }
            echo json_encode($response);
            exit;

        }

    }


    /* this function  created by mahendra */
    public function get_messages_static_data()
    {

        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        if ($this->request->is('post')) {

            $response = array();
            $message_id = $data['message_id'];
            $list_message_type = $data['list_message_type'];
            $user_id = $data['user_id'];
            $thin_app_id = $data['thin_app_id'];
            $app_key = $data['app_key'];

            if ($message_id != 0 && $app_key != "" && $list_message_type != "") {
                if (($result = $this->Custom->CheckIsValidApp($app_key, $thin_app_id)) && ($result == 1)) {
                    $message_list = $this->MessageStatic->find('first', array(
                        'conditions' => array(
                            'MessageStatic.message_id' => $message_id,
                            'MessageStatic.list_message_type' => $list_message_type
                        ),
                        'fields' => array("MessageStatic.*"),
                        'contain' => false
                    ));

                    if (!empty($message_list)) {
                        $response['status'] = 1;
                        $response['message'] = "Message static found";
                        $response['data']['message']['total_likes'] = $message_list['MessageStatic']['total_likes'];
                        $response['data']['message']['total_super_like'] = $message_list['MessageStatic']['total_super_like'];
                        $response['data']['message']['total_excellent_like'] = $message_list['MessageStatic']['total_excellent_like'];
                        $response['data']['message']['total_mindblowing_like'] = $message_list['MessageStatic']['total_mindblowing_like'];
                        $response['data']['message']['total_wow_like'] = $message_list['MessageStatic']['total_wow_like'];
                        $response['data']['message']['total_fb_share'] = $message_list['MessageStatic']['total_fb_share'];
                        $response['data']['message']['total_twitter_share'] = $message_list['MessageStatic']['total_twitter_share'];
                        $response['data']['message']['total_gplus_share'] = $message_list['MessageStatic']['total_gplus_share'];
                        $response['data']['message']['total_whatsapp_share'] = $message_list['MessageStatic']['total_whatsapp_share'];
                        $response['data']['message']['total_other_share'] = $message_list['MessageStatic']['total_other_share'];
                        $response['data']['message']['total_broadcast_share'] = $message_list['MessageStatic']['total_broadcast_share'];
                    } else {
                        $response['status'] = 0;
                        $response['message'] = "Sorry message not found";
                    }
                } else {
                    $response['status'] = 0;
                    $response['message'] = $this->Custom->getResponseMessage($result);
                }
            } else {
                $response['status'] = 0;
                $response['message'] = "Invalid ApiKey";
            }
            echo json_encode($response);
            exit;
        }

    }


    /* api created by mahendra */
    /* this function use for share or broadcast message into other channels */
    public function broadcast_message()
    {
        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        if ($this->request->is('post')) {

            $response = array();
            $user_id = $data['user_id'];
            $channel_id = $data['action_at_channel_id'];
            $message_id = $data['message_id'];
            $thin_app_id = $data['thin_app_id'];
            $app_key = $data['app_key'];

            if ($user_id != '' && $channel_id != '' && $app_key != '' && $message_id != '') {
                if (($result = $this->Custom->CheckIsValidApp($app_key, $thin_app_id)) && ($result == 1)) {

                    $is_broadcast = $this->ChannelMessage->find('count', array(
                        'conditions' => array(
                            'ChannelMessage.broadcast_by' => $user_id,
                            'ChannelMessage.message_id' => $message_id,
                            'ChannelMessage.channel_id' => $channel_id,
                            'ChannelMessage.post_type_status' => 'BROADCAST'
                        ),
                        'contain' => false
                    ));
                    if (empty($is_broadcast)) {
                        $datasource = $this->Message->getDataSource();
                        try {
                            $datasource->begin();
                            $this->MessageAction->create();
                            $this->MessageAction->set('action_at_channel_id', $channel_id);
                            $this->MessageAction->set('action_by', $user_id);
                            $this->MessageAction->set('message_id', $message_id);
                            if ($this->MessageAction->save()) {
                                /* add messge to channel Message table*/
                                $this->ChannelMessage->create();
                                $this->ChannelMessage->set('message_id', $message_id);
                                $this->ChannelMessage->set('channel_id', $channel_id);
                                $this->ChannelMessage->set('post_type_status', 'BROADCAST');
                                $this->ChannelMessage->set('broadcast_by', $user_id);
                                $this->ChannelMessage->save();
                                /* add row to message sttice table*/
                                $this->MessageStatic->updateAll(array(
                                    'MessageStatic.total_broadcast_share' => 'MessageStatic.total_broadcast_share + 1'),
                                    array('MessageStatic.message_id' => $message_id)
                                );
                            }
                            $datasource->commit();
                            $response['status'] = 1;
                            $response['message'] = "Message broadcast successfully";

                        } catch (Exception $e) {
                            $datasource->rollback();
                            $response['status'] = 0;
                            $response['message'] = "Sorry message could not broadcast";
                        }
                    } else {
                        $response['status'] = 0;
                        $response['message'] = "You have already broadcast this message in this channel";
                    }


                } else {
                    $response['status'] = 0;
                    $response['message'] = $this->Custom->getResponseMessage($result);
                }
            } else {
                $response['status'] = 0;
                $response['message'] = "Invalid request parameter";
            }
            echo json_encode($response);
            exit;
        }
    }

    /* update by mahendra */
    public function get_subscriber_list()
    {

        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        if ($this->request->is('post')) {
            $app_key = $data['app_key'];
            $mobile = $data['mobile'];
            $user_id = $data['user_id'];
            $thin_app_id = $data['thin_app_id'];
            $response = array();
            if ($user_id != '' && $app_key != '' && $mobile != '') {
                if (($result = $this->Custom->CheckIsValidApp($app_key, $thin_app_id)) && ($result == 1)) {

                    $channel = $this->Subscriber->find('all', array(
                        "conditions" => array(
                            "Subscriber.app_user_id" => $user_id,
                            "Subscriber.status" => 'SUBSCRIBED',
                            "Subscriber.mobile" => $mobile,
                            "Channel.status" => 'Y'
                        ),

                        'contain' => array(
                            'Channel' => array(
                                'fields' => array('Channel.*')
                            )
                        ),
                        'order' => 'Subscriber.is_favourite asc'
                    ));

                    $channel_array = array();
                    if (!empty($channel)) {

                        foreach ($channel as $key => $channel_data) {
                            $channels_arr['user_id'] = $channel_data['Channel']['user_id'];
                            $channels_arr['mbroadcast_app_id'] = MBROADCAST_APP_ID;
                            $channels_arr['channel_id'] = $channel_data['Channel']['id'];
                            $channels_arr['channel_name'] = $channel_data['Channel']['channel_name'];
                            $channels_arr['channel_image'] = $this->Custom->GetChannelImage($channel_data['Channel']['id']);
                            $channels_arr['channel_desc'] = $channel_data['Channel']['channel_desc'];
                            $channels_arr['added_on'] = $channel_data['Channel']['created'];
                            $channels_arr['topic_name'] = $channel_data['Channel']['topic_name'];
                            $channels_arr['modified'] = $channel_data['Channel']['modified'];
                            $channels_arr['is_searchable'] = $channel_data['Channel']['is_searchable'];
                            $channels_arr['is_publish_mbroadcast'] = $channel_data['Channel']['is_publish_mbroadcast'];
                            $channels_arr['pending_status'] = "1";
                            $channels_arr['subscriber_count'] = $this->Custom->total_subscribe($channel_data['Channel']['id']);
                            $channels_arr['is_subscribe'] = $this->Custom->is_subscribe($channel_data['Channel']['id'], $mobile);
                            $channels_arr['channel_status'] = $channel_data['Channel']['channel_status'];
                            $channels_arr['is_favourite'] = $this->Custom->checkFavourite($channel_data['Channel']['id'], $mobile);
                            $channels_arr['is_owner'] = ($channel_data['Channel']['user_id'] == $user_id) ? "YES" : "NO";
                            if ($channels_arr['is_favourite'] == 'Y') {
                                //$channel_array.unshift($channels_arr);
                                array_unshift($channel_array, $channels_arr);
                            } else {
                                array_push($channel_array, $channels_arr);
                            }

                            /*if (!empty($channel_data['Subscriber'])) {
                                pr($channel_data);
                                $channels_arr[$key]['is_mute'] = $channel_data['Subscriber']['is_mute'];
                                $channels_arr[$key]['is_push'] = $channel_data['Subscriber']['is_push'];
                                $channels_arr[$key]['subscriber_id'] = $channel_data['Subscriber']['id'];
                            }*/

                        }
                        $response['status'] = 1;
                        $response['message'] = "Subscribed channels list found";
                        $response['data']['channels'] = $channel_array;
                    } else {
                        $response['status'] = 0;
                        $response['message'] = "No subscribed channel not found";
                    }
                } else {
                    $response['status'] = 0;
                    $response['message'] = $this->Custom->getResponseMessage($result);
                }
            } else {
                $response['status'] = 0;
                $response['message'] = "Invalid request parameter";
            }

            echo json_encode($response);
            exit;
        }
    }


    /* update by mahendra */
    public function get_my_channel_list()
    {

        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        if ($this->request->is('post')) {
            $app_key = $data['app_key'];
            $mobile = $data['mobile'];
            $user_id = $data['user_id'];
            $thin_app_id = $data['thin_app_id'];
            $role_id = $data['role_id'];
            $response = array();
            if ($user_id != '' && $app_key != '' && $mobile != '' && $role_id != '') {
                if (($result = $this->Custom->CheckIsValidApp($app_key, $thin_app_id)) && ($result == 1)) {

                    $mbroadcast_app_id = MBROADCAST_APP_ID;
                    if ($role_id == 5 || $thin_app_id == $mbroadcast_app_id) {
                        $channel = $this->Channel->find('all', array(
                            'conditions' => array(
                                'Channel.user_id' => $user_id,
                                'Channel.status' => 'Y'
                            ),

                            'contain' => false,
                            'order' => array("Channel.id" => "DESC")
                        ));
                        $channel_array = array();
                        if (!empty($channel)) {

                            foreach ($channel as $key => $channel_data) {
                                $channels_arr['user_id'] = $channel_data['Channel']['user_id'];
                                $channels_arr['mbroadcast_app_id'] = MBROADCAST_APP_ID;
                                $channels_arr['channel_id'] = $channel_data['Channel']['id'];
                                $channels_arr['channel_name'] = $channel_data['Channel']['channel_name'];
                                $channels_arr['channel_image'] = $this->Custom->GetChannelImage($channel_data['Channel']['id']);
                                $channels_arr['channel_desc'] = $channel_data['Channel']['channel_desc'];
                                $channels_arr['added_on'] = $channel_data['Channel']['created'];
                                $channels_arr['topic_name'] = $channel_data['Channel']['topic_name'];
                                $channels_arr['modified'] = $channel_data['Channel']['modified'];
                                $channels_arr['is_searchable'] = $channel_data['Channel']['is_searchable'];
                                $channels_arr['is_publish_mbroadcast'] = $channel_data['Channel']['is_publish_mbroadcast'];
                                $channels_arr['pending_status'] = "1";
                                $channels_arr['subscriber_count'] = $this->Custom->total_subscribe($channel_data['Channel']['id']);
                                $channels_arr['is_subscribe'] = $this->Custom->is_subscribe($channel_data['Channel']['id'], $mobile);
                                $channels_arr['channel_status'] = $channel_data['Channel']['channel_status'];
                                $channels_arr['is_favourite'] = $this->Custom->checkFavourite($channel_data['Channel']['id'], $mobile);
                                if ($channels_arr['is_favourite'] == 'Y') {
                                    //$channel_array.unshift($channels_arr);
                                    array_unshift($channel_array, $channels_arr);
                                } else {
                                    array_push($channel_array, $channels_arr);
                                }


                                /*if (!empty($channel_data['Subscriber'])) {
                                    pr($channel_data);
                                    $channels_arr[$key]['is_mute'] = $channel_data['Subscriber']['is_mute'];
                                    $channels_arr[$key]['is_push'] = $channel_data['Subscriber']['is_push'];
                                    $channels_arr[$key]['subscriber_id'] = $channel_data['Subscriber']['id'];
                                }*/

                            }
                            $response['status'] = 1;
                            $response['message'] = "Channels list found";
                            $response['data']['channels'] = $channel_array;
                        } else {
                            $response['status'] = 0;
                            $response['message'] = "Sorry you have no channel!";
                        }
                    } else {
                        /* this channel list show list if this user colobroator for this */


                        $collaborator_channel_list = $this->Collaborator->find('list', array(
                            'conditions' => array(
                                'Collaborator.user_id' => $user_id,
                                'Collaborator.thinapp_id' => $thin_app_id,
                                'Collaborator.status' => 'ACTIVE'
                            ),

                            'contain' => false,
                            'fields' => array('Collaborator.channel_id')
                        ));

                        if (!empty($collaborator_channel_list)) {

                            $channel = $this->Channel->find('all', array(
                                'conditions' => array(
                                    'Channel.id' => $collaborator_channel_list,
                                    'Channel.status' => 'Y'
                                ),

                                'contain' => false
                            ));
                            $channel_array = array();
                            if (!empty($channel)) {

                                foreach ($channel as $key => $channel_data) {
                                    $channels_arr['user_id'] = $channel_data['Channel']['user_id'];
                                    $channels_arr['channel_id'] = $channel_data['Channel']['id'];
                                    $channels_arr['channel_name'] = $channel_data['Channel']['channel_name'];
                                    $channels_arr['channel_image'] = $this->Custom->GetChannelImage($channel_data['Channel']['id']);
                                    $channels_arr['channel_desc'] = $channel_data['Channel']['channel_desc'];
                                    $channels_arr['added_on'] = $channel_data['Channel']['created'];
                                    $channels_arr['topic_name'] = $channel_data['Channel']['topic_name'];
                                    $channels_arr['modified'] = $channel_data['Channel']['modified'];
                                    $channels_arr['is_searchable'] = $channel_data['Channel']['is_searchable'];
                                    $channels_arr['is_publish_mbroadcast'] = $channel_data['Channel']['is_publish_mbroadcast'];
                                    $channels_arr['pending_status'] = "1";
                                    $channels_arr['subscriber_count'] = $this->Custom->total_subscribe($channel_data['Channel']['id']);
                                    $channels_arr['is_subscribe'] = $this->Custom->is_subscribe($channel_data['Channel']['id'], $mobile);
                                    $channels_arr['channel_status'] = $channel_data['Channel']['channel_status'];
                                    $channels_arr['is_favourite'] = $this->Custom->checkFavourite($channel_data['Channel']['id'], $mobile);

                                    $is_colobrator = $this->Custom->is_collobrator_for_channel($user_id, $channel_data['Channel']['id'], $thin_app_id);
                                    if (!empty($is_colobrator)) {
                                        $channels_arr['is_collobrator'] = 'Y';
                                        $channels_arr['coll_role'] = $is_colobrator['role'];
                                    } else {
                                        $channels_arr['is_collobrator'] = 'N';
                                        $channels_arr['coll_role'] = $is_colobrator;
                                    }

                                    if ($channels_arr['is_favourite'] == 'Y') {
                                        //$channel_array.unshift($channels_arr);
                                        array_unshift($channel_array, $channels_arr);
                                    } else {

                                        array_push($channel_array, $channels_arr);

                                    }


                                    /*if (!empty($channel_data['Subscriber'])) {
                                        pr($channel_data);
                                        $channels_arr[$key]['is_mute'] = $channel_data['Subscriber']['is_mute'];
                                        $channels_arr[$key]['is_push'] = $channel_data['Subscriber']['is_push'];
                                        $channels_arr[$key]['subscriber_id'] = $channel_data['Subscriber']['id'];
                                    }*/

                                }
                                $response['status'] = 1;
                                $response['message'] = "Channels list found";
                                $response['data']['channels'] = $channel_array;
                            } else {
                                $response['status'] = 0;
                                $response['message'] = "Sorry you have no channel!";
                            }

                        } else {


                        }


                    }


                } else {
                    $response['status'] = 0;
                    $response['message'] = $this->Custom->getResponseMessage($result);
                }
            } else {
                $response['status'] = 0;
                $response['message'] = "Invalid request parameter";
            }

            echo json_encode($response);
            exit;
        }
    }

    /* update by mahendra */
    public function get_channel_dropdown_list()
    {

        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        if ($this->request->is('post')) {
            $app_key = $data['app_key'];
            $mobile = $data['mobile'];
            $user_id = $data['user_id'];
            $thin_app_id = $data['thin_app_id'];
            $response = array();
            if ($user_id != '' && $app_key != '' && $mobile != '') {
                if (($result = $this->Custom->CheckIsValidApp($app_key, $thin_app_id)) && ($result == 1)) {


                    $mbroadcast_app_id = MBROADCAST_APP_ID;
                    if ($thin_app_id == $mbroadcast_app_id) {
                        $channel = $this->Channel->find('all', array(
                            'conditions' => array(
                                'OR' => array(
                                    'Channel.user_id' => $user_id,
                                    //'Channel.channel_status'=>'PUBLIC',
                                ),
                                'Channel.app_id' => $thin_app_id,
                                'Channel.status' => 'Y'
                            ),
                            'contain' => false,
                            'fields' => array('Channel.id', 'Channel.channel_name')
                        ));
                    } else {

                        $channel = $this->Channel->find('all', array(
                            'conditions' => array(
                                'Channel.user_id' => $user_id,
                                'Channel.app_id' => $thin_app_id,
                                'Channel.status' => 'Y'
                            ),
                            'contain' => false,
                            'fields' => array('Channel.id', 'Channel.channel_name')
                        ));

                    }

                    $channel_array = array();
                    if (!empty($channel)) {
                        foreach ($channel as $key => $channel_data) {
                            $channels_arr['channel_id'] = $channel_data['Channel']['id'];
                            $channels_arr['channel_name'] = $channel_data['Channel']['channel_name'];
                            array_push($channel_array, $channels_arr);
                        }
                        $response['status'] = 1;
                        $response['message'] = "Channels list found";
                        $response['data']['channels'] = $channel_array;
                    } else {
                        $response['status'] = 0;
                        $response['message'] = "Sorry you have no channel!";
                    }
                } else {
                    $response['status'] = 0;
                    $response['message'] = $this->Custom->getResponseMessage($result);
                }
            } else {
                $response['status'] = 0;
                $response['message'] = "Invalid request parameter";
            }

            echo json_encode($response);
            exit;
        }
    }

    /* update by mahendra */
    public function get_channel_for_losefound_list()
    {
        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        if ($this->request->is('post')) {
            $app_key = $data['app_key'];
            $mobile = $data['mobile'];
            $user_id = $data['user_id'];
            $thin_app_id = $data['thin_app_id'];
            $response = array();
            if ($user_id != '' && $app_key != '' && $mobile != '') {
                if (($result = $this->Custom->CheckIsValidApp($app_key, $thin_app_id)) && ($result == 1)) {
                    $mbroadcast_app_id = MBROADCAST_APP_ID;
                    if ($thin_app_id == $mbroadcast_app_id) {
                        $channel = $this->Channel->find('all', array(
                            'conditions' => array(
                                'OR' => array(
                                    'Channel.user_id' => $user_id,
                                    //'Channel.channel_status'=>'PUBLIC',
                                ),
                                'Channel.app_id' => $thin_app_id,
                                'Channel.status' => 'Y'
                            ),
                            'contain' => false,
                            'fields' => array('Channel.id', 'Channel.channel_name')
                        ));
                    } else {

                        $channel = $this->Channel->find('all', array(
                            'conditions' => array(
                                'Channel.user_id' => $user_id,
                                'Channel.app_id' => $thin_app_id,
                                'Channel.status' => 'Y'
                            ),
                            'contain' => false,
                            'fields' => array('Channel.id', 'Channel.channel_name')
                        ));

                    }
                    $channel_array = array();
                    if (!empty($channel)) {
                        foreach ($channel as $key => $channel_data) {
                            $channels_arr['channel_id'] = $channel_data['Channel']['id'];
                            $channels_arr['channel_name'] = $channel_data['Channel']['channel_name'];
                            array_push($channel_array, $channels_arr);
                        }
                        $response['status'] = 1;
                        $response['message'] = "Channels list found";
                        $response['data']['channels'] = $channel_array;
                    } else {
                        $response['status'] = 0;
                        $response['message'] = "Sorry you have no channel!";
                    }
                } else {
                    $response['status'] = 0;
                    $response['message'] = $this->Custom->getResponseMessage($result);
                }
            } else {
                $response['status'] = 0;
                $response['message'] = "Invalid request parameter";
            }

            echo json_encode($response);
            exit;
        }
    }


    /* update by mahendra */
    public function get_channel_factory_list()
    {

        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        if ($this->request->is('post')) {
            $app_key = $data['app_key'];
            $mobile = $data['mobile'];
            $user_id = $data['user_id'];
            $thin_app_id = $data['thin_app_id'];
            $response = array();
            if ($user_id != '' && $app_key != '' && $mobile != '') {
                if (($result = $this->Custom->CheckIsValidApp($app_key, $thin_app_id)) && ($result == 1)) {
                    $channel = $this->Channel->find('all', array(
                        "conditions" => array(
                            "OR" => array(
                                "AND" => array(
                                    "Channel.is_searchable" => "Y",
                                    "Channel.app_id" => $thin_app_id
                                ),
                                "Channel.channel_status" => "PUBLIC",
                            ),
                            "Channel.status" => "Y"
                        ),
                        'contain' => false

                    ));

                    $channel_array = array();
                    if (!empty($channel)) {

                        foreach ($channel as $key => $channel_data) {
                            if (!$this->Custom->is_subscribe($channel_data['Channel']['id'], $mobile)) {
                                $channels_arr['user_id'] = $channel_data['Channel']['user_id'];
                                $channels_arr['mbroadcast_app_id'] = MBROADCAST_APP_ID;
                                $channels_arr['channel_id'] = $channel_data['Channel']['id'];
                                $channels_arr['channel_name'] = $channel_data['Channel']['channel_name'];
                                $channels_arr['channel_image'] = $this->Custom->GetChannelImage($channel_data['Channel']['id']);
                                $channels_arr['channel_desc'] = $channel_data['Channel']['channel_desc'];
                                $channels_arr['added_on'] = $channel_data['Channel']['created'];
                                $channels_arr['topic_name'] = $channel_data['Channel']['topic_name'];
                                $channels_arr['modified'] = $channel_data['Channel']['modified'];
                                $channels_arr['is_searchable'] = $channel_data['Channel']['is_searchable'];
                                $channels_arr['is_publish_mbroadcast'] = $channel_data['Channel']['is_publish_mbroadcast'];
                                $channels_arr['pending_status'] = "1";
                                $channels_arr['subscriber_count'] = $this->Custom->total_subscribe($channel_data['Channel']['id']);
                                $channels_arr['is_subscribe'] = 0;
                                $channels_arr['channel_status'] = $channel_data['Channel']['channel_status'];
                                $channels_arr['is_favourite'] = $this->Custom->checkFavourite($channel_data['Channel']['id'], $mobile);
                                if ($channels_arr['is_favourite'] == 'Y') {
                                    //$channel_array.unshift($channels_arr);
                                    array_unshift($channel_array, $channels_arr);
                                } else {
                                    array_push($channel_array, $channels_arr);
                                }
                            }
                        }
                        $response['status'] = 1;
                        $response['message'] = "Channels list found";
                        $response['data']['channels'] = $channel_array;
                    } else {
                        $response['status'] = 0;
                        $response['message'] = "Channels not found";
                    }
                } else {
                    $response['status'] = 0;
                    $response['message'] = $this->Custom->getResponseMessage($result);
                }
            } else {
                $response['status'] = 0;
                $response['message'] = "Invalid request parameter";
            }

            echo json_encode($response);
            exit;
        }
    }


    /* created by mahendra */
    public function update_subscription_favourite()
    {


        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        if ($this->request->is('post')) {
            $response = array();
            $channel_id = $data['channel_id'];
            $user_id = $data['user_id'];
            $mobile = $data['mobile'];
            $status = $data['status'];
            $app_key = $data['app_key'];
            $thin_app_id = $data['thin_app_id'];

            if (isset($mobile) && isset($channel_id) && isset($app_key)) {
                if (($result = $this->Custom->CheckIsValidApp($app_key, $thin_app_id)) && ($result == 1)) {


                    $channel = $this->Channel->find('first', array(
                        'conditions' => array(
                            'Channel.id' => $channel_id,
                            'Channel.status' => 'Y'
                        ),

                        'contain' => false
                    ));
                    if (!empty($channel)) {

                        $chdata = $this->Subscriber->find('first', array(
                            'conditions' => array(
                                "Subscriber.mobile" => $mobile,
                                "Subscriber.channel_id" => $channel_id,
                                'Subscriber.app_id' => $channel['Channel']['app_id']
                            ),

                            'fields' => array('Subscriber.id', 'Subscriber.app_user_id', 'Channel.channel_name'),
                            'contain' => array('Channel')
                        ));

                        if (!empty($chdata)) {
                            $subscriber_id = $chdata['Subscriber']["id"];
                            $this->Subscriber->id = $subscriber_id;
                            if ($this->Subscriber->saveField('is_favourite', $status)) {

                                $sub_user = $chdata['Subscriber']['app_user_id'];
                                WebservicesFunction::deleteJson(array('get_subscriber_list_app' . $thin_app_id . '_user' . $sub_user),'subscriber');


                                $response['status'] = 1;
                                if ($status == "Y") {
                                    $message = $chdata['Channel']['channel_name'] . " channel has been added to your favourite list";
                                    $response['message'] = $message;
                                } else if ($status == "N") {
                                    $message = $chdata['Channel']['channel_name'] . " channel has been removed from your favourite list";
                                    $response['message'] = $message;
                                } else {
                                    $response['message'] = "Ignored successfully";
                                }
                            }
                        } else {
                            $response['status'] = 0;
                            $response['message'] = "Subscriber not found";
                        }
                    } else {
                        $response['status'] = 0;
                        $response['message'] = "Sorry channel not found";
                    }

                } else {
                    $response['status'] = 0;
                    $response['message'] = $this->Custom->getResponseMessage($result);
                }
            } else {
                $response['status'] = 0;
                $response['message'] = "Invalid request parameter";
            }

            echo json_encode($response);
            exit;
        }
    }


    /* created by mahendra */
    public function send_collaborator_request()
    {
        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        if ($this->request->is('post')) {
            $response = array();
            $user_id = $data['user_id'];
            $channel_id = $data['channel_id'];
            $col_mobile = $data['col_mobile'];
            $thin_app_id = $data['thin_app_id'];
            $app_key = $data['app_key'];

            if (isset($col_mobile) && isset($channel_id) && isset($app_key)) {
                if (($result = $this->Custom->CheckIsValidApp($app_key, $thin_app_id)) && ($result == 1)) {


                    // $col_mobile = $this->Custom->check_mobile_number($col_mobile); // this is new one for validate mobile
                    $col_mobile = $this->Custom->create_mobile_number($col_mobile);// this is old one
                    if ($col_mobile) {

                        $col_user_data = $this->User->find("first", array(
                            "conditions" => array(
                                "User.mobile" => $col_mobile,
                                "User.thinapp_id" => $thin_app_id
                            ),

                            'fields' => array('id', 'device_token', 'device_type', 'username'),
                            'contain' => false
                        ));

                        /* this condition check app admin has messages */

                        $total_sms = $this->Custom->getTotalRemainingSms($thin_app_id, "T");
                        if ($total_sms > 0) {
                            
                            $login_user_data = $this->User->find("first", array(
                                "conditions" => array(
                                    "User.id" => $user_id
                                ),
                                'fields' => array('username', 'mobile'),

                                'contain' => false
                            ));
                            
                            $this->Channel->unBindModel(array('hasMany' => array('Message', 'Subscriber', 'MessageQueue')));
                            $channel_data = $this->Channel->find("first", array(
                                "conditions" => array(
                                    "Channel.id" => $channel_id
                                ),
                                'fields' => array('channel_name', 'image'),

                                'contain' => false
                            ));
                            
                            $collaborator = $this->Collaborator->find("first", array(
                                "conditions" => array(
                                    "Collaborator.mobile" => $col_mobile,
                                    "Collaborator.thinapp_id" => $thin_app_id,
                                    "Collaborator.channel_id" => $channel_id
                                ),
                                'fields' => array('id', 'status'),
                                "contain" => false
                            ));
                            
                            if ($login_user_data['User']['mobile'] != $col_mobile) {


                                if (empty($collaborator) || ($collaborator['Collaborator']['status'] == 'CANCELED')) {

                                    $datasource = $this->Collaborator->getDataSource();
                                    try {
                                        $datasource->begin();


                                        $this->Collaborator->create();
                                        if (!empty($col_user_data)) {
                                            $this->Collaborator->set('user_id', $col_user_data['User']['id']);
                                        }
                                        $this->Collaborator->set('channel_id', $channel_id);
                                        $this->Collaborator->set('mobile', $col_mobile);
                                        $this->Collaborator->set('role', 'USER');
                                        $this->Collaborator->set('collaborator_added_by', $user_id);
                                        $this->Collaborator->set('thinapp_id', $thin_app_id);
                                        if ($this->Collaborator->save()) {
                                            $collaborator_id = $this->Collaborator->getLastInsertId();
                                            $datasource->commit();
                                            if (!empty($col_user_data)) {

                                                $message = "you are inviated as collaborator";
                                                $message = $channel_data['Channel']['channel_name'] . ' Request - ' . $message;
                                                $option = array(
                                                    'thinapp_id' => $thin_app_id,
                                                    'collaborator_id' => $collaborator_id,
                                                    'user_id' => $col_user_data['User']['id'],
                                                    'data' => array(
                                                        'thinapp_id' => $thin_app_id,
                                                        'channel_id' => $channel_id,
                                                        'role' => "USER",
                                                        'flag' => 'COLL',
                                                        'title' => mb_strimwidth($message, 0, 100, '...'),
                                                        'message' => mb_strimwidth($message, 0, 100, '...'),
                                                        'description' => "",
                                                        'chat_reference' => '',
                                                        'module_type' => 'COLL',
                                                        'module_type_id' => $collaborator_id,
                                                        'firebase_reference' => ""
                                                    )
                                                );
                                                $this->Custom->send_notification_to_device($option);

                                            } else {
                                                $site_url = $this->Custom->getThinAppUrl($thin_app_id);
                                                $message = "you are invited as collaborator";
                                                $message = $channel_data['Channel']['channel_name'] . ' Request - ' . $message . ' for more download app ' . $site_url;
                                                $option = array(
                                                    'mobile' => $col_mobile,
                                                    'message' => urlencode($message),
                                                    'thinapp_id' => $thin_app_id,
                                                    'sender_id' => $user_id
                                                );
                                                $this->Custom->send_message_system($option);

                                            }

                                            $response['status'] = 1;
                                            $response['message'] = "Collaborator request send successfully";
                                            $col_list = $this->Collaborator->find('all', array(
                                                "conditions" => array(
                                                    "Collaborator.thinapp_id" => $thin_app_id
                                                ),
                                                'fields' => array('id', 'mobile', 'status', 'role'),
                                                'order' => array('id' => 'desc')
                                            ));
                                            $list_array = array();
                                            foreach ($col_list as $key => $col) {
                                                $list_array[$key]['col_id'] = $col['Collaborator']['id'];
                                                $list_array[$key]['col_mobile'] = $col['Collaborator']['mobile'];
                                                $list_array[$key]['col_status'] = $col['Collaborator']['status'];
                                                $list_array[$key]['col_role'] = $col['Collaborator']['role'];
                                            }

                                            $response['data']['collaborator'] = $list_array;


                                        } else {
                                            $response['status'] = 0;
                                            $response['message'] = "Sorry collaborator request not send";

                                        }


                                    } catch (Exception $e) {
                                        $datasource->rollback();
                                        $response['status'] = 0;
                                        $response['message'] = "Sorry something went wrong on server";
                                    }

                                } else {
                                    $response['status'] = 0;
                                    $response['message'] = "Already collaborator current status is :" . $collaborator['Collaborator']['status'];

                                }
                            } else {
                                $response['status'] = 0;
                                $response['message'] = "Sorry you can not send collaborator request this number";
                            }
                        } else {
                            $response['status'] = 0;
                            $response['message'] = "System error found. Please try later";
                        }

                    } else {

                        $response['status'] = 0;
                        $response['message'] = "Sorry invalid collaborator mobile number";
                    }


                } else {
                    $response['status'] = 0;
                    $response['message'] = $this->Custom->getResponseMessage($result);
                }
            } else {
                $response['status'] = 0;
                $response['message'] = "Invalid request parameter";
            }

            echo json_encode($response);
            exit;
        }
    }




    /* created by mahendra */
    public function get_collaborator_list()
    {


        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        if ($this->request->is('post')) {
            $response = array();
            $user_id = $data['user_id'];
            $thin_app_id = $data['thin_app_id'];
            $channel_id = $data['channel_id'];
            $app_key = $data['app_key'];


            if (isset($user_id) && isset($thin_app_id) && isset($app_key)) {
                if (($result = $this->Custom->CheckIsValidApp($app_key, $thin_app_id)) && ($result == 1)) {
                    $col_list = $this->Collaborator->find('all', array(
                        "conditions" => array(
                            "Collaborator.thinapp_id" => $thin_app_id,
                            "Collaborator.collaborator_added_by" => $user_id,
                            "Collaborator.channel_id" => $channel_id,
                            "Collaborator.status !=" => 'CANCELED'
                        ),
                        'fields' => array('id', 'mobile', 'status', 'role'),
                        'order' => array('id' => 'desc'),
                        'contain' => false


                    ));
                    if (!empty($col_list)) {
                        $response['status'] = 1;
                        $response['message'] = "Collaborator list found";
                        $list_array = array();
                        foreach ($col_list as $key => $col) {
                            $list_array[$key]['col_id'] = $col['Collaborator']['id'];
                            $list_array[$key]['col_mobile'] = $col['Collaborator']['mobile'];
                            $list_array[$key]['col_status'] = $col['Collaborator']['status'];
                            $list_array[$key]['col_role'] = $col['Collaborator']['role'];
                        }
                        $response['data']['collaborator'] = $list_array;
                    } else {
                        $response['status'] = 0;
                        $response['message'] = "Sorry no collaborator list found";
                    }
                } else {
                    $response['status'] = 0;
                    $response['message'] = $this->Custom->getResponseMessage($result);
                }
            } else {
                $response['status'] = 0;
                $response['message'] = "Invalid request parameter";
            }

            echo json_encode($response);
            exit;
        }
    }


    /* created by mahendra */
    public function user_as_collaborator_list()
    {

        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        if ($this->request->is('post')) {
            $response = array();
            $user_id = $data['user_id'];
            $thin_app_id = $data['thin_app_id'];
            $app_key = $data['app_key'];

            if (isset($user_id) && isset($thin_app_id) && isset($app_key)) {
                if (($result = $this->Custom->CheckIsValidApp($app_key, $thin_app_id)) && ($result == 1)) {
                    $col_list = $this->Collaborator->find('all', array(
                        "conditions" => array(
                            "Collaborator.thinapp_id" => $thin_app_id,
                            "Collaborator.user_id" => $user_id,
                            "Collaborator.status !=" => 'CANCELED'
                        ),
                        'fields' => array('id', 'mobile', 'status', 'role', 'Channel.channel_name'),
                        'contains' => array(
                            "Channel" => array(
                                'fields' => array('channel_name'),
                            )
                        ),

                        'order' => array('id' => 'desc')

                    ));
                    if (!empty($col_list)) {
                        $response['status'] = 1;
                        $response['message'] = "Collaborator list found";
                        $list_array = array();
                        foreach ($col_list as $key => $col) {
                            $list_array[$key]['col_id'] = $col['Collaborator']['id'];
                            $list_array[$key]['col_mobile'] = $col['Collaborator']['mobile'];
                            $list_array[$key]['col_status'] = $col['Collaborator']['status'];
                            $list_array[$key]['col_role'] = $col['Collaborator']['role'];
                            $list_array[$key]['channel_name'] = $col['Channel']['channel_name'];

                        }
                        $response['data']['collaborator'] = $list_array;
                    } else {
                        $response['status'] = 0;
                        $response['message'] = "Yor are not inviated as a collaborator yet!";
                    }
                } else {
                    $response['status'] = 0;
                    $response['message'] = $this->Custom->getResponseMessage($result);
                }
            } else {
                $response['status'] = 0;
                $response['message'] = "Invalid request parameter";
            }

            echo json_encode($response);
            exit;
        }
    }


    /* created by mahendra */
    public function accept_collaborator_request()
    {


        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        if ($this->request->is('post')) {
            $response = array();

            $user_id = $data['user_id'];
            $col_id = $data['col_id'];
            $thin_app_id = $data['thin_app_id'];
            $app_key = $data['app_key'];
            $status = $data['status'];

            if (isset($thin_app_id) && isset($col_id) && isset($user_id) && isset($app_key)) {
                if (($result = $this->Custom->CheckIsValidApp($app_key, $thin_app_id)) && ($result == 1)) {

                    $collaborator = $this->Collaborator->find("first", array(
                        "conditions" => array(
                            "Collaborator.id" => $col_id,
                            "Collaborator.thinapp_id" => $thin_app_id
                        ),
                        'fields' => array(
                            'Collaborator.id', 'Collaborator.status', 'Collaborator.collaborator_added_by', 'User.id', 'User.mobile',
                            'Channel.channel_name', 'Channel.image', 'Channel.id', 'Channel.app_id', 'Channel.topic_name', "User.username"
                        ),
                        "contain" => array("Channel", "User")
                    ));

                    if (!empty($collaborator)) {
                        $this->Collaborator->set('id', $col_id);
                        $this->Collaborator->set('status', $status);
                        $this->Collaborator->set('user_id', $user_id);
                        if ($this->Collaborator->save()) {

                            $response['status'] = 1;
                            $message = "";
                            if ($status == "ACTIVE") {

                                $subscriber = $this->Subscriber->find("first", array(
                                    "conditions" => array(
                                        "Subscriber.app_id" => $thin_app_id,
                                        "Subscriber.app_user_id" => $collaborator['User']['id'],
                                        "Subscriber.channel_id" => $collaborator['Channel']['id']
                                    ),

                                    'fields' => array('Subscriber.id'),
                                    "contain" => false
                                ));
                                if (empty($subscriber)) {
                                    $this->Subscriber->create();
                                    $this->Subscriber->set('channel_id', $collaborator['Channel']['id']);
                                    $this->Subscriber->set('user_id', $user_id);
                                    $this->Subscriber->set('app_id', $thin_app_id);
                                    $this->Subscriber->set('mobile', $collaborator['User']['mobile']);
                                    $this->Subscriber->set('app_user_id', $collaborator['User']['id']);
                                    $this->Subscriber->set('name', $collaborator['User']['username']);
                                    $this->Subscriber->set('status', 'SUBSCRIBED');
                                    if ($this->Subscriber->save()) {
                                        $user_firebase_token = $this->Custom->getUserFirebaseToken($collaborator['User']['id']);
                                        $this->Custom->add_subscriber_to_topic($collaborator['Channel']['app_id'], $collaborator['Channel']['topic_name'], $user_firebase_token);
                                        $response_data[] = WebservicesFunction::fun_get_subscriber_list($thin_app_id, $collaborator['User']['id'], PAGINATION_LIMIT, 0);
                                        WebservicesFunction::createJson('get_subscriber_list_app' . $thin_app_id . "_user" . $collaborator['User']['id'], $response_data, 'CREATE','subscriber');

                                    }
                                }
                              
                              
                              
                                $message = "Collaborator requested accepted by " . $collaborator['User']['mobile'] . " for channel " . $collaborator['Channel']['channel_name'];
                                $response['message'] = "Collaborator request  accepted successfully";
                                /************UPDATED BY VISHWAJEET**********/
                                $file_name = 'get_my_channel_list_app' . $thin_app_id . '_user' . $user_id;
                                WebservicesFunction::deleteJson(array($file_name));

                                /************UPDATED BY VISHWAJEET**********/
                            } else {

                                $message = "Collaborator requested decline by " . $collaborator['User']['mobile'] . " for channel " . $collaborator['Channel']['channel_name'];
                                $response['message'] = "Collaborator request  decline successfully";
                            }


                            $option = array(
                                'thinapp_id' => $thin_app_id,
                                'collaborator_id' => $col_id,
                                'user_id' => $collaborator['Collaborator']['collaborator_added_by'],
                                'data' => array(
                                    'thinapp_id' => $thin_app_id,
                                    'channel_id' => $collaborator['Channel']['id'],
                                    'role' => "ADMIN",
                                    'flag' => 'COLL',
                                    'title' => mb_strimwidth($message, 0, 100, '...'),
                                    'message' => mb_strimwidth($message, 0, 100, '...'),
                                    'description' => "",
                                    'chat_reference' => '',
                                    'module_type' => 'COLL',
                                    'module_type_id' => $col_id,
                                    'firebase_reference' => ""
                                )
                            );
                            $this->Custom->send_notification_to_device($option);


                        } else {
                            $response['status'] = 0;
                            $response['message'] = "Sorry collaborator request not accepted";
                        }
                    } else {
                        $response['status'] = 0;
                        $response['message'] = "Sorry collaborator request not found";
                    }

                } else {
                    $response['status'] = 0;
                    $response['message'] = $this->Custom->getResponseMessage($result);
                }
            } else {
                $response['status'] = 0;
                $response['message'] = "Invalid request parameter";
            }

            echo json_encode($response);
            exit;
        }
    }



    /* created by mahendra */
    public function change_collaborator_role()
    {


        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        if ($this->request->is('post')) {
            $response = array();

            $user_id = $data['user_id'];
            $col_id = $data['col_id'];
            $role = $data['role'];

            $thin_app_id = $data['thin_app_id'];
            $app_key = $data['app_key'];

            if (isset($thin_app_id) && isset($col_id) && isset($user_id) && isset($app_key)) {
                if (($result = $this->Custom->CheckIsValidApp($app_key, $thin_app_id)) && ($result == 1)) {

                    $collaborator = $this->Collaborator->find("first", array(
                        "conditions" => array(
                            "Collaborator.id" => $col_id,
                            "Collaborator.thinapp_id" => $thin_app_id
                        ),
                        'fields' => array(
                            'Collaborator.user_id', 'Collaborator.id', 'Collaborator.status', 'Collaborator.collaborator_added_by',
                            'Channel.channel_name', 'Channel.image', 'Channel.id'
                        )
                    ));

                    if (!empty($collaborator)) {

                        $this->Collaborator->id = $col_id;
                        if ($this->Collaborator->saveField('role', strtoupper($role))) {
                            $response['status'] = 1;
                            $response['message'] = "Collaborator role changed successfully";
                            /************UPDATED BY VISHWAJEET**********/
                            $collUserID = $collaborator['Collaborator']['user_id'];
                            $fileName = 'get_my_channel_list_app' . $thin_app_id . '_user' . $collUserID;
                            $file_path = 'cache/' . $fileName . '.json';
                            if (file_exists($file_path)) {
                                unlink($file_path);
                            }
                            /************UPDATED BY VISHWAJEET**********/
                        } else {
                            $response['status'] = 0;
                            $response['message'] = "Sorry collaborator role could not changed";
                        }
                    } else {
                        $response['status'] = 0;
                        $response['message'] = "Sorry collaborator request not found";
                    }

                } else {
                    $response['status'] = 0;
                    $response['message'] = $this->Custom->getResponseMessage($result);
                }
            } else {
                $response['status'] = 0;
                $response['message'] = "Invalid request parameter";
            }

            echo json_encode($response);
            exit;
        }
    }


    /* created by mahendra */
    public function cancel_collaborator_request()
    {


        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        if ($this->request->is('post')) {
            $response = array();
            $user_id = $data['user_id'];
            $col_id = $data['col_id'];
            $thin_app_id = $data['thin_app_id'];
            $app_key = $data['app_key'];
            if (isset($thin_app_id) && isset($col_id) && isset($user_id) && isset($app_key)) {
                if (($result = $this->Custom->CheckIsValidApp($app_key, $thin_app_id)) && ($result == 1)) {


                    $collaborator = $this->Collaborator->find("first", array(
                        "conditions" => array(
                            "Collaborator.id" => $col_id,
                            "Collaborator.thinapp_id" => $thin_app_id
                        ),

                        'fields' => array(
                            'Collaborator.user_id', 'Collaborator.id', 'Collaborator.status', 'Collaborator.collaborator_added_by',
                            'Channel.channel_name', 'Channel.image', 'Channel.id'
                        )
                    ));
                    if (!empty($collaborator)) {
                        $this->Collaborator->set('id', $col_id);
                        $this->Collaborator->set('status', "CANCELED");
                        if ($this->Collaborator->save()) {

                            $message = "you are not authorized as collaborator";
                            $message = $collaborator['Channel']['channel_name'] . ' Request - ' . $message;
                            $option = array(
                                'thinapp_id' => $thin_app_id,
                                'collaborator_id' => $col_id,
                                'user_id' => $collaborator['Collaborator']['user_id'],
                                'data' => array(
                                    'thinapp_id' => $thin_app_id,
                                    'channel_id' => $collaborator['Channel']['id'],
                                    'role' => "USER",
                                    'flag' => 'COLL',
                                    'title' => mb_strimwidth($message, 0, 100, '...'),
                                    'message' => mb_strimwidth($message, 0, 100, '...'),
                                    'description' => "",
                                    'chat_reference' => '',
                                    'module_type' => 'COLL',
                                    'module_type_id' => $col_id,
                                    'firebase_reference' => ""
                                )
                            );
                            $this->Custom->send_notification_to_device($option);


                            $response['status'] = 1;
                            $response['message'] = "Collaborator request remove successfully";
                            /************UPDATED BY VISHWAJEET**********/
                            $collUserID = $collaborator['Collaborator']['user_id'];
                            $fileName = 'get_my_channel_list_app' . $thin_app_id . '_user' . $collUserID;
                            $file_path = 'cache/' . $fileName . '.json';
                            if (file_exists($file_path)) {
                                unlink($file_path);
                            }
                            /************UPDATED BY VISHWAJEET**********/
                        } else {
                            $response['status'] = 0;
                            $response['message'] = "Sorry collaborator role could not remove";
                        }
                    } else {
                        $response['status'] = 0;
                        $response['message'] = "Sorry collaborator request not found";
                    }

                } else {
                    $response['status'] = 0;
                    $response['message'] = $this->Custom->getResponseMessage($result);
                }
            } else {
                $response['status'] = 0;
                $response['message'] = "Invalid request parameter";
            }

            echo json_encode($response);
            exit;
        }
    }


    /* api created by mahendra */

    public function add_message_action()
    {

        $request = file_get_contents("php://input");
        $data = json_decode($request, true);

        if ($this->request->is('post')) {

            $response = array();
            $user_id = $data['user_id'];
            $message_id = $data['message_id'];
            $like = $data['like'];
            $share_fb = $data['share_fb'];
            $share_twitter = $data['share_twitter'];
            $share_gplus = $data['share_gplus'];
            $share_whatsaap = $data['share_whatsaap'];
            $share_others = $data['share_others'];
            $like_type = $data['like_type'];
            $thin_app_id = $data['thin_app_id'];
            $app_key = $data['app_key'];
            $list_message_type = $data['list_message_type'];

            if ($user_id != '' && $app_key != '' && $message_id != '' && $list_message_type != '') {
                if (($result = $this->Custom->CheckIsValidApp($app_key, $thin_app_id)) && ($result == 1)) {

                    $datasource = $this->MessageAction->getDataSource();
                    try {
                        $datasource->begin();
                        $message_owner = 0;
                        if ($list_message_type == 'POST' || $list_message_type == 'BROADCAST') {
                            $message = $this->Message->find('first', array(
                                'conditions' => array(
                                    'Message.id' => $message_id
                                ),
                                'fields' => array('Message.owner_user_id'),
                                'contain' => false
                            ));
                            $message_owner = $message['Message']['owner_user_id'];
                        }

                        if (!empty($like)) {
                            /* this block chek and update likes start*/
                            $like_data = $this->MessageAction->find('first', array(
                                'conditions' => array(
                                    'MessageAction.action_by' => $user_id,
                                    'MessageAction.message_id' => $message_id,
                                    'MessageAction.like' => 'Y',
                                    'MessageAction.list_message_type' => $list_message_type
                                ),

                                'contain' => false
                            ));

                            if (empty($like_data)) {
                                $this->MessageAction->create();
                                $this->MessageAction->set('like', 'Y');
                                $this->MessageAction->set('action_by', $user_id);
                                $this->MessageAction->set('message_id', $message_id);
                                $this->MessageAction->set('like_type', $like_type);
                                $this->MessageAction->set('list_message_type', $list_message_type);
                                if ($this->MessageAction->save()) {
                                    /* this code update creadit coins for user */
                                    if ($like_type == 'LIKE') {
                                        $this->MessageStatic->updateAll(array(
                                            'MessageStatic.total_likes' => 'MessageStatic.total_likes + 1'),
                                            array(
                                                'MessageStatic.message_id' => $message_id,
                                                'MessageStatic.list_message_type' => $list_message_type
                                            )
                                        );

                                        if ($message_owner != $user_id && ($list_message_type == 'POST' || $list_message_type == 'BROADCAST')) {
                                            $this->Custom->updateCoins('LIKE', $message_owner, $user_id, $message_id, $thin_app_id, 0);
                                        }

                                    } else if ($like_type == 'SUPERLIKE') {

                                        $this->MessageStatic->updateAll(array(
                                            'MessageStatic.total_super_like' => 'MessageStatic.total_super_like + 1'),
                                            array(
                                                'MessageStatic.message_id' => $message_id,
                                                'MessageStatic.list_message_type' => $list_message_type
                                            )
                                        );
                                        if ($message_owner != $user_id && ($list_message_type == 'POST' || $list_message_type == 'BROADCAST')) {
                                            $this->Custom->updateCoins('SUPER_LIKE', $message_owner, $user_id, $message_id, $thin_app_id, 0);
                                        }
                                    } else if ($like_type == 'EXCELLENT') {

                                        $this->MessageStatic->updateAll(array(
                                            'MessageStatic.total_excellent_like' => 'MessageStatic.total_excellent_like + 1'),
                                            array(
                                                'MessageStatic.message_id' => $message_id,
                                                'MessageStatic.list_message_type' => $list_message_type
                                            )
                                        );
                                        if ($message_owner != $user_id && ($list_message_type == 'POST' || $list_message_type == 'BROADCAST')) {
                                            $this->Custom->updateCoins('EXCELLENT', $message_owner, $user_id, $message_id, $thin_app_id, 0);
                                        }

                                    } else if ($like_type == 'MINDBLOWING') {

                                        $this->MessageStatic->updateAll(array(
                                            'MessageStatic.total_mindblowing_like' => 'MessageStatic.total_mindblowing_like + 1'),
                                            array(
                                                'MessageStatic.message_id' => $message_id,
                                                'MessageStatic.list_message_type' => $list_message_type
                                            )
                                        );
                                        if ($message_owner != $user_id && ($list_message_type == 'POST' || $list_message_type == 'BROADCAST')) {
                                            $this->Custom->updateCoins('MIND_BLOWING', $message_owner, $user_id, $message_id, $thin_app_id, 0);
                                        }

                                    } else if ($like_type == 'WOW') {

                                        $this->MessageStatic->updateAll(array(
                                            'MessageStatic.total_wow_like' => 'MessageStatic.total_wow_like + 1'),
                                            array(
                                                'MessageStatic.message_id' => $message_id,
                                                'MessageStatic.list_message_type' => $list_message_type
                                            )
                                        );
                                        if ($message_owner != $user_id && ($list_message_type == 'POST' || $list_message_type == 'BROADCAST')) {
                                            $this->Custom->updateCoins('WOW', $message_owner, $user_id, $message_id, $thin_app_id, 0);
                                        }
                                    }
                                    $datasource->commit();
                                    $response['status'] = 1;
                                    $response['message'] = "Message liked successfully";
                                    $response['data'] = $this->Custom->getTotalStatikLike($message_id);

                                } else {
                                    $response['status'] = 0;
                                    $response['message'] = "Sorry message could not liked";
                                }
                            } else {
                                $response['status'] = 0;
                                $response['message'] = "You are already liked this post";
                            }

                            /* this block chek and update likes end*/
                        } else {
                            $this->MessageAction->create();
                            $this->MessageAction->set('action_by', $user_id);
                            $this->MessageAction->set('message_id', $message_id);
                            $this->MessageAction->set('list_message_type', $list_message_type);

                            if (!empty($share_fb)) {
                                $this->MessageAction->set('share_fb', $share_fb);

                                if ($list_message_type == 'POST' || $list_message_type == 'BROADCAST') {
                                    /* this code update creadit coins for user */
                                    $this->Custom->updateCoins('SHARE', $message_owner, $user_id, $message_id, $thin_app_id, 'FACEBOOK');
                                }
                            }
                            if (!empty($share_twitter)) {
                                if ($list_message_type == 'POST' || $list_message_type == 'BROADCAST') {
                                    $this->MessageAction->set('share_twitter', $share_twitter);
                                    /* this code update creadit coins for user */
                                    $this->Custom->updateCoins('SHARE', $message_owner, $user_id, $message_id, $thin_app_id, 'TWITTER');
                                }

                            }
                            if (!empty($share_gplus)) {
                                if ($list_message_type == 'POST' || $list_message_type == 'BROADCAST') {
                                    $this->MessageAction->set('share_gplus', $share_gplus);
                                    /* this code update creadit coins for user */
                                    $this->Custom->updateCoins('SHARE', $message_owner, $user_id, $message_id, $thin_app_id, 'GPLUS');
                                }

                            }
                            if (!empty($share_whatsaap)) {
                                if ($list_message_type == 'POST' || $list_message_type == 'BROADCAST') {
                                    $this->MessageAction->set('share_whatsaap', $share_whatsaap);
                                    /* this code update creadit coins for user */
                                    $this->Custom->updateCoins('SHARE', $message_owner, $user_id, $message_id, $thin_app_id, 'WHATSAPP');
                                }

                            }
                            if (!empty($share_others)) {
                                if ($list_message_type == 'POST' || $list_message_type == 'BROADCAST') {
                                    $this->MessageAction->set('share_others', $share_others);
                                    /* this code update creadit coins for user */
                                    $this->Custom->updateCoins('SHARE', $message_owner, $user_id, $message_id, $thin_app_id, 'OTHER');
                                }

                            }
                            if ($this->MessageAction->save()) {
                                $conditonarray = array();
                                if (!empty($share_fb)) {
                                    $conditonarray['MessageStatic.total_fb_share'] = "MessageStatic.total_fb_share + 1";
                                } else if (!empty($share_twitter)) {
                                    $conditonarray['MessageStatic.total_twitter_share'] = "MessageStatic.total_twitter_share + 1";
                                } else if (!empty($share_gplus)) {
                                    $conditonarray['MessageStatic.total_gplus_share'] = "MessageStatic.total_gplus_share + 1";
                                } else if (!empty($share_whatsaap)) {
                                    $conditonarray['MessageStatic.total_whatsapp_share'] = "MessageStatic.total_whatsapp_share + 1";
                                } else {
                                    $conditonarray['MessageStatic.total_other_share'] = "MessageStatic.total_other_share + 1";
                                }
                                $result = $this->MessageStatic->updateAll($conditonarray,
                                    array(
                                        'MessageStatic.message_id' => $message_id,
                                        'MessageStatic.list_message_type' => $list_message_type
                                    )
                                );
                                if ($result) {
                                    $datasource->commit();
                                    $response['status'] = 1;
                                    $response['message'] = "Message shared successfully";
                                    $response['data'] = $this->Custom->getTotalStatikLike($message_id);
                                } else {
                                    $response['status'] = 0;
                                    $response['message'] = "Sorry message could not shared";
                                }
                            } else {
                                $response['status'] = 0;
                                $response['message'] = "Sorry message could not shared";
                            }
                        }


                    } catch (Exception $e) {
                        $datasource->rollback();
                        $this->Session->setFlash(__('Sorry question could not add.'), 'default', array(), 'error');
                    }

                } else {
                    $response['status'] = 0;
                    $response['message'] = $this->Custom->getResponseMessage($result);
                }

            } else {
                $response['status'] = 0;
                $response['message'] = "Invalid request parameter";
            }

            echo json_encode($response);
            exit;
        }
    }


    
    /* created by mahendra */
    public function get_poll_type_list()
    {


        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        if ($this->request->is('post')) {
            $response = array();
            $user_id = $data['user_id'];
            $thin_app_id = $data['thin_app_id'];
            $app_key = $data['app_key'];

            if (isset($user_id) && isset($thin_app_id) && isset($app_key)) {
                if (($result = $this->Custom->CheckIsValidApp($app_key, $thin_app_id)) && ($result == 1)) {
                    $col_list = $this->ActionType->find('all', array(
                        'contain' => false
                    ));
                    if (!empty($col_list)) {
                        $response['status'] = 1;
                        $response['message'] = "Poll list found";
                        $list_array = array();
                        foreach ($col_list as $key => $col) {

                            $list_array[$key]['id'] = $col['ActionType']['id'];
                            $list_array[$key]['name'] = $col['ActionType']['name'];
                            $list_array[$key]['image'] = Router::url("/thinapp_images/", true) . $col["ActionType"]['icon'];
                        }
                        $response['data']['poll_list'] = $list_array;
                    } else {
                        $response['status'] = 0;
                        $response['message'] = "Sorry no poll list found";
                    }
                } else {
                    $response['status'] = 0;
                    $response['message'] = $this->Custom->getResponseMessage($result);
                }
            } else {
                $response['status'] = 0;
                $response['message'] = "Invalid request parameter";
            }

            echo json_encode($response);
            exit;
        }
    }


    /* created by mahendra */
    public function get_admin_subscriber_list()
    {

        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        if ($this->request->is('post')) {
            $response = array();
            $user_id = $data['user_id'];
            $thin_app_id = $data['thin_app_id'];
            $channel_id = $data['channel_id'];
            $app_key = $data['app_key'];

            if (isset($user_id) && isset($thin_app_id) && isset($app_key) && isset($channel_id)) {

                $channel_owner = $this->Custom->getChannelOwner($channel_id);
                if (($result = $this->Custom->CheckIsValidApp($app_key, $thin_app_id)) && ($result == 1)) {

                    $col_list = $this->Subscriber->find('all', array(
                        'conditions' => array(
                            "Subscriber.channel_id" => $channel_id,
                            "Subscriber.app_id" => $thin_app_id,
                            "Subscriber.status" => 'SUBSCRIBED'
                        ),

                        'contain' => false
                    ));

                    if (!empty($col_list)) {
                        $response['status'] = 1;
                        $response['is_owner'] = ($channel_owner == $user_id) ? "YES" : "NO";
                        $response['message'] = "Subscriber list found";
                        $list_array = array();
                        foreach ($col_list as $key => $col) {
                            $list_array[$key]['id'] = $col['Subscriber']['id'];
                            $list_array[$key]['mobile'] = $col['Subscriber']['mobile'];
                        }
                        $response['data']['subscriber_list'] = $list_array;
                    } else {
                        $response['is_owner'] = ($channel_owner == $user_id) ? "YES" : "NO";
                        $response['status'] = 0;
                        $response['message'] = "Sorry no subscriber list found";
                    }
                } else {
                    $response['is_owner'] = ($channel_owner == $user_id) ? "YES" : "NO";
                    $response['status'] = 0;
                    $response['message'] = $this->Custom->getResponseMessage($result);
                }
            } else {
                $response['is_owner'] = "N";
                $response['status'] = 0;
                $response['message'] = "Invalid request parameter";
            }

            echo json_encode($response);
            exit;
        }
    }


    /* api created by mahendra */

    public function create_poll()
    {

        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        if ($this->request->is('post')) {

            $response = array();
            $user_id = $data['user_id'];
            $thin_app_id = $data['thin_app_id'];
            $app_key = $data['app_key'];
            $question = $data['question'];
            $is_mandatory = $data['is_mandatory'];
            $question_option = $data['option'];
            $share_on_mbroadcast = $data['share_on_mbroadcast'];
            $poll_publish = $data['poll_publish'];
            $poll_duration = $data['poll_duration'];
            $action_type_id = $data['action_type_id'];
            $share_on = $data['share_on'];

            $flag = true;
            $post_data = array();
            if ($user_id != '' && $app_key != '' && $action_type_id != '' && $poll_duration != '') {
                if (($result = $this->Custom->CheckIsValidApp($app_key, $thin_app_id)) && ($result == 1)) {

                    if (empty($question)) {
                        $response['status'] = 0;
                        $response['message'] = "Please enter your question";
                        $flag = false;
                    } else if (($action_type_id == 3 || $action_type_id == 1 || $action_type_id == 7 || $action_type_id == 8 || $action_type_id == 6) && empty($question_option)) {
                        $response['status'] = 0;
                        $response['message'] = "Please add your choices";
                        $flag = false;
                    } else if (($action_type_id == 3 || $action_type_id == 1 || $action_type_id == 7 || $action_type_id == 8 || $action_type_id == 6) && !empty($question_option)) {
                        $optionArray = explode("\n", $question_option);
                        $optionArray = array_filter($optionArray, create_function('$value', 'return trim($value) != "";'));
                        if (count($optionArray) <= 1) {
                            $response['status'] = 0;
                            $response['message'] = "Please add minimum two choices";
                            $flag = false;
                        }
                    }
                    if ($flag === true) {

                        $datasource = $this->ActionQuestion->getDataSource();
                        try {

                            $datasource->begin();
                            $post_data['user_id'] = $user_id;
                            $post_data['thinapp_id'] = $thin_app_id;
                            $post_data['poll_publish'] = $poll_publish;
                            $post_data['is_mandatory'] = $is_mandatory;
                            $post_data['poll_duration'] = $poll_duration;
                            $post_data['share_on_mbroadcast'] = $share_on_mbroadcast;
                            $post_data['question_text'] = $question;;
                            $post_data['action_type_id'] = $action_type_id;;


                            if ($share_on == "CHANNEL") {
                                $post_data['share_on'] = $share_on;
                            }

                            $current_date = date('Y-m-d H:i:s');
                            $end_time = date('Y-m-d H:i:s', strtotime($current_date . "+" . $poll_duration));
                            $post_data['end_time'] = $end_time;;


                            if ($this->ActionQuestion->save($post_data)) {
                                $question_id = $this->ActionQuestion->getLastInsertId();
                                $optionArray = array();
                                if (!empty($question_option)) {
                                    $optionArray = explode("\n", $question_option);
                                    $optionArray = array_filter($optionArray, create_function('$value', 'return trim($value) != "";'));
                                }
                                $option = $this->Custom->createOpitonArray($action_type_id, $question_id, $thin_app_id, $optionArray);
                                if ($this->QuestionChoice->saveAll($option)) {
                                    $datasource->commit();
                                    $response['status'] = 1;
                                    $response['message'] = "Poll add  successfully";
                                    $response['data']['question_id'] = $question_id;
                                } else {
                                    $response['status'] = 0;
                                    $response['message'] = "Sorry poll could not add";
                                }
                            } else {
                                $response['status'] = 0;
                                $response['message'] = "Sorry poll could not add";
                            }
                        } catch (Exception $e) {
                            $datasource->rollback();
                            $this->Session->setFlash(__('Sorry question could not add.'), 'default', array(), 'error');
                        }
                    }

                } else {
                    $response['status'] = 0;
                    $response['message'] = $this->Custom->getResponseMessage($result);
                }
            } else {
                $response['status'] = 0;
                $response['message'] = "Invalid request parameter";
            }
            echo json_encode($response);
            exit;
        }
    }


    /* api created by mahendra */

    public function share_poll()
    {

        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        if ($this->request->is('post')) {

            $response = array();
            $user_id = $data['user_id'];
            $thin_app_id = $data['thin_app_id'];
            $app_key = $data['app_key'];
            $question_id = $data['question_id'];
            $channel_id = $data['channel_id'];
            //$share_on = $data['share_on'];
            //$action_type_id = $data['action_type_id'];
            if ($user_id != '' && $app_key != '' && $question_id != '' && $channel_id != '') {
                if (($result = $this->Custom->CheckIsValidApp($app_key, $thin_app_id)) && ($result == 1)) {

                    $datasource = $this->ChannelMessage->getDataSource();
                    try {
                        $already_post = $this->ChannelMessage->find('count', array(
                            "conditions" => array(
                                "ChannelMessage.channel_id" => $channel_id,
                                "ChannelMessage.message_id" => $question_id,
                                "ChannelMessage.post_type_status" => 'POLL'
                            ),
                            'contain' => false
                        ));
                        if ($already_post == 0) {
                            $datasource->begin();

                            $add_channel = $this->ActionQuestion->updateAll(array(
                                'ActionQuestion.channel_id' => $channel_id),
                                array('ActionQuestion.id' => $question_id)
                            );
                            
                            $this->ChannelMessage->create();
                            $this->ChannelMessage->set('channel_id', $channel_id);
                            $this->ChannelMessage->set('message_id', $question_id);
                            $this->ChannelMessage->set('post_type_status', 'POLL');
                            
                            $this->MessageStatic->create();
                            $this->MessageStatic->set('message_id', $question_id);
                            $this->MessageStatic->set('list_message_type', 'POLL');
                            
                            if ($add_channel && $this->ChannelMessage->save() && $this->MessageStatic->save()) {
                                $total_par = $this->Custom->totalSubscriberForChannel($channel_id);;
                                $this->ActionQuestion->id = $question_id;
                                $this->ActionQuestion->saveField('participates_count', $total_par);
                                $datasource->commit();


                                $question_text = $this->ActionQuestion->find('first', array(
                                    "conditions" => array(
                                        "ActionQuestion.id" => $question_id,
                                    ),
                                    'contain' => false,
                                    'fields' => array('ActionQuestion.question_text')
                                ));

                                $sendArray = array(
                                    'thinapp_id' => $thin_app_id,
                                    'channel_id' => $channel_id,
                                    'question_id' => $question_id,
                                    //  'action_type'=>$action_type_id,
                                    'flag' => 'POLL',
                                    'title' => mb_strimwidth("Poll - " . $question_text['ActionQuestion']['question_text'], 0, 50, '...'),
                                    'message' => mb_strimwidth("Poll - " . $question_text['ActionQuestion']['question_text'], 0, 50, '...'),
                                    'description' => '',
                                    'chat_reference' => '',
                                    'module_type' => 'POLL',
                                    'module_type_id' => $question_id,
                                    'firebase_reference' => ""
                                );
                                $this->Custom->send_topic_notification($sendArray);

                                $user_role = $this->Custom->get_user_role_id($user_id);
                                $is_permission = $this->Custom->check_user_permission($thin_app_id, 'POLL_SEND_NOTIFICATION_VIA_SMS');
                                if ($user_role == 5 || $is_permission == "YES") {
                                    /* this function send message to subscriber user only*/
                                    $this->Custom->sendPollMessage($channel_id, $question_id, $thin_app_id, $user_id);
                                }
                                $response['status'] = 1;
                                $response['message'] = "Poll shared successfully";
                            } else {
                                $response['status'] = 0;
                                $response['message'] = "Sorry poll could not shared";
                            }
                        } else {
                            $response['status'] = 0;
                            $response['message'] = "Sorry poll already shared into this channel";
                        }
                    } catch (Exception $e) {
                        $datasource->rollback();
                        $response['status'] = 0;
                        $response['message'] = "Sorry poll could not shared";
                    }

                } else {
                    $response['status'] = 0;
                    $response['message'] = $this->Custom->getResponseMessage($result);
                }
            } else {
                $response['status'] = 0;
                $response['message'] = "Invalid request parameter";
            }
            echo json_encode($response);
            exit;
        }
    }

    /* api created by mahendra */

    public function create_poll_question_screen()
    {

        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        if ($this->request->is('post')) {

            $user_id = $data['user_id'];
            $thin_app_id = $data['thin_app_id'];
            $app_key = $data['app_key'];
            $question_id = $data['question_id'];

            if ($user_id != '' && $app_key != '' && $question_id != '') {
                if (($result = $this->Custom->CheckIsValidApp($app_key, $thin_app_id)) && ($result == 1)) {

                    $question_data = $this->ActionQuestion->find('first', array(
                        "conditions" => array(
                            "ActionQuestion.id" => $question_id,
                            "ActionQuestion.thinapp_id" => $thin_app_id
                        ),

                        'fields' => array("ActionQuestion.*", "ActionType.name", "ActionType.id"),
                        'contain' => array("QuestionChoice", "ActionType")
                    ));

                    if (!empty($question_data)) {
                        $response['status'] = 1;
                        $response['message'] = "Poll found";
                        $response['question_id'] = $question_data['ActionQuestion']['id'];
                        $response['question'] = $question_data['ActionQuestion']['question_text'];
                        $response['poll_publish'] = $question_data['ActionQuestion']['poll_publish'];
                        $response['action_name'] = $question_data['ActionType']['name'];
                        $response['action_type_id'] = $question_data['ActionType']['id'];
                        $response['option'] = $question_data['QuestionChoice'];

                    } else {
                        $response['status'] = 0;
                        $response['message'] = "Sorry no poll found";
                    }

                } else {
                    $response['status'] = 0;
                    $response['message'] = $this->Custom->getResponseMessage($result);
                }
            } else {
                $response['status'] = 0;
                $response['message'] = "Invalid request parameter";
            }
            echo json_encode($response);
            exit;
        }
    }


    public function get_poll_list()
    {

        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        if ($this->request->is('post')) {

            $user_id = $data['user_id'];
            $thin_app_id = $data['thin_app_id'];
            $app_key = $data['app_key'];
            $role_id = $data['role_id'];

            if ($user_id != '' && $app_key != '' && $thin_app_id != '') {
                if (($result = $this->Custom->CheckIsValidApp($app_key, $thin_app_id)) && ($result == 1)) {


                    $condition = array();
                    if ($role_id == 5) {
                        $condition["ActionQuestion.thinapp_id"] = $thin_app_id;
                    } else {
                        $condition["ActionQuestion.thinapp_id"] = $thin_app_id;
                        $condition["ActionQuestion.share_on"] = "POLLFACTORY";
                    }


                    $condition["ActionQuestion.end_time >"] = date("Y-m-d H:i:s");
                    $poll_list = $this->ActionQuestion->find('all', array(
                        "conditions" => $condition,
                        'fields' => array("ActionQuestion.*", "ActionType.*"),

                        'contain' => array("ActionType"),
                        'order' => array('ActionQuestion.id' => 'desc')
                    ));


                    //pr($poll_list);die;
                    if (!empty($poll_list)) {
                        $response['status'] = 1;
                        $response['message'] = "Poll list found";
                        foreach ($poll_list as $key => $list) {
                            $response['data']['poll'][$key]['id'] = $list['ActionQuestion']['id'];
                            $response['data']['poll'][$key]['question_text'] = $list['ActionQuestion']['question_text'];
                            $response['data']['poll'][$key]['action_type_id'] = $list['ActionQuestion']['action_type_id'];
                            $response['data']['poll'][$key]['poll_publish'] = $list['ActionQuestion']['poll_publish'];
                            $response['data']['poll'][$key]['share_on_mbroadcast'] = $list['ActionQuestion']['share_on_mbroadcast'];
                            $response['data']['poll'][$key]['poll_duration'] = $list['ActionQuestion']['poll_duration'];
                            $left_days = explode(" ", $list['ActionQuestion']['poll_duration']);
                            $response['data']['poll'][$key]['left_time_string'] = ucwords(strtolower($left_days[1]));
                            $response['data']['poll'][$key]['left_time_int'] = $left_days[0];
                            $response['data']['poll'][$key]['participates_count'] = $list['ActionQuestion']['participates_count'];
                            $response['data']['poll'][$key]['response_count'] = $list['ActionQuestion']['response_count'];
                            $response['data']['poll'][$key]['end_time'] = date('d-m-Y H:i', strtotime($list['ActionQuestion']['end_time']));
                            $response['data']['poll'][$key]['action_type'] = $list['ActionType']['name'];
                        }
                    } else {
                        $response['status'] = 0;
                        $response['message'] = "Sorry! you don't have any poll yet";
                    }

                } else {
                    $response['status'] = 0;
                    $response['message'] = $this->Custom->getResponseMessage($result);
                }
            } else {
                $response['status'] = 0;
                $response['message'] = "Invalid request parameter";
            }
            echo json_encode($response);
            exit;
        }
    }


    public function submit_poll_response()
    {


        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        if ($this->request->is('post')) {

            $user_id = $data['user_id'];
            $thin_app_id = $data['thin_app_id'];
            $app_key = $data['app_key'];
            $question_id = $data['question_id'];
            $channel_id = $data['channel_id'];
            $mobile = $data['mobile'];
            $action_type_id = $data['action_type_id'];
            $option = $data['option'];

            if ($user_id != '' && $app_key != '' && $question_id != '' && $mobile != '' && $action_type_id != '') {
                if (($result = $this->Custom->CheckIsValidApp($app_key, $thin_app_id)) && ($result == 1)) {
                    $response = array();

                    if (!empty($option)) {

                        $now = date('Y-m-d H:i:s');
                        $question = $this->ActionQuestion->find("first", array(
                                "conditions" => array(
                                    "ActionQuestion.id" => $question_id,
                                    "ActionQuestion.end_time >=" => $now,
                                ),

                                "contain" => array('ActionType'),
                            )
                        );

                        if (!empty($question)) {

                            /* this result submit for poll result with channel*/
                            if ($channel_id != 0) {
                                $subscriber = $this->Subscriber->find("first", array(
                                    "conditions" => array(
                                        "Subscriber.app_user_id" => $user_id,
                                        "Subscriber.channel_id" => $channel_id,
                                        "Subscriber.app_id" => $thin_app_id,
                                    ),

                                    "fields" => array("Subscriber.id", "Subscriber.mobile", "Subscriber.app_user_id"),
                                    "contain" => false
                                ));
                                if (!empty($subscriber)) {
                                    $subscriber_id = $subscriber["Subscriber"]['id'];
                                    $countAns = $this->ActionResponse->find("count", array(
                                        "conditions" => array(
                                            "ActionResponse.user_id" => $user_id,
                                            "ActionResponse.action_question_id" => $question_id,
                                        ),

                                        "contain" => false
                                    ));


                                    if ($countAns > 0) {
                                        $response['status'] = 0;
                                        $response['message'] = "You have already used this poll";

                                    } else if (empty($question)) {

                                        $response['status'] = 0;
                                        $response['message'] = "Sorry, Poll is either not found or expired";

                                    } else {


                                        $post = $this->request->data;

                                        if ($question['ActionType']['name'] == 'SHORT ANSWER') {
                                            $this->ActionResponse->create();
                                            $inData = array();
                                            $inData['action_question_id'] = $question_id;
                                            $inData['mobile_number'] = $subscriber['Subscriber']['mobile'];
                                            $inData['user_id'] = $subscriber['Subscriber']['app_user_id'];
                                            $inData['subscriber_id'] = $subscriber['Subscriber']['id'];
                                            $inData['action_type_id'] = $question['ActionType']['id'];
                                            $inData['question_choice_id'] = key($option);
                                            $inData['user_input_values'] = $option[key($option)];

                                            if ($this->ActionResponse->save($inData)) {
                                                $this->ActionQuestion->id = $question['ActionQuestion']['id'];
                                                $this->ActionQuestion->saveField('response_count', ($question['ActionQuestion']['response_count'] + 1));
                                                $response['status'] = 1;
                                                $response['message'] = 'Your answer posted successfully';

                                            } else {
                                                $response['status'] = 0;
                                                $response['message'] = 'Sorry your answer could not be post';
                                            }
                                        }

                                        if ($question['ActionType']['name'] == 'LONG ANSWER') {


                                            $this->ActionResponse->create();
                                            $inData = array();
                                            $inData['action_question_id'] = $question_id;
                                            $inData['mobile_number'] = $subscriber['Subscriber']['mobile'];
                                            $inData['user_id'] = $subscriber['Subscriber']['app_user_id'];
                                            $inData['subscriber_id'] = $subscriber['Subscriber']['id'];
                                            $inData['action_type_id'] = $question['ActionType']['id'];
                                            $inData['question_choice_id'] = key($option);
                                            $inData['user_input_values'] = $option[key($option)];

                                            if ($this->ActionResponse->save($inData)) {
                                                $this->ActionQuestion->id = $question['ActionQuestion']['id'];

                                                $this->ActionQuestion->saveField('response_count', ($question['ActionQuestion']['response_count'] + 1));

                                                $response['status'] = 1;
                                                $response['message'] = 'Your answer posted successfully';

                                            } else {
                                                $response['status'] = 0;
                                                $response['message'] = 'Sorry your answer could not be post';
                                            }

                                        }

                                        if ($question['ActionType']['name'] == 'DROPDOWN') {

                                            $this->ActionResponse->create();
                                            $inData = array();
                                            $inData['action_question_id'] = $question_id;
                                            $inData['mobile_number'] = $subscriber['Subscriber']['mobile'];
                                            $inData['user_id'] = $subscriber['Subscriber']['app_user_id'];
                                            $inData['subscriber_id'] = $subscriber['Subscriber']['id'];
                                            $inData['action_type_id'] = $question['ActionType']['id'];
                                            $inData['question_choice_id'] = key($option);
                                            $inData['user_input_values'] = $option[key($option)];

                                            if ($this->ActionResponse->save($inData)) {
                                                $this->ActionQuestion->id = $question['ActionQuestion']['id'];

                                                $this->ActionQuestion->saveField('response_count', ($question['ActionQuestion']['response_count'] + 1));

                                                $response['status'] = 1;
                                                $response['message'] = 'Your answer posted successfully';

                                            } else {
                                                $response['status'] = 0;
                                                $response['message'] = 'Sorry your answer could not be post';
                                            }

                                        }

                                        if ($question['ActionType']['name'] == 'YES/NO') {


                                            $this->ActionResponse->create();
                                            $inData = array();
                                            $inData['action_question_id'] = $question_id;
                                            $inData['mobile_number'] = $subscriber['Subscriber']['mobile'];
                                            $inData['user_id'] = $subscriber['Subscriber']['app_user_id'];
                                            $inData['subscriber_id'] = $subscriber['Subscriber']['id'];
                                            $inData['action_type_id'] = $question['ActionType']['id'];
                                            $inData['question_choice_id'] = key($option);
                                            $inData['user_input_values'] = $option[key($option)];

                                            if ($this->ActionResponse->save($inData)) {
                                                $this->ActionQuestion->id = $question['ActionQuestion']['id'];

                                                $this->ActionQuestion->saveField('response_count', ($question['ActionQuestion']['response_count'] + 1));

                                                $response['status'] = 1;
                                                $response['message'] = 'Your answer posted successfully';

                                            } else {
                                                $response['status'] = 0;
                                                $response['message'] = 'Sorry your answer could not be post';
                                            }

                                        }

                                        if ($question['ActionType']['name'] == 'DATE') {

                                            $this->ActionResponse->create();
                                            $inData = array();
                                            $inData['action_question_id'] = $question_id;
                                            $inData['mobile_number'] = $subscriber['Subscriber']['mobile'];
                                            $inData['user_id'] = $subscriber['Subscriber']['app_user_id'];
                                            $inData['subscriber_id'] = $subscriber['Subscriber']['id'];
                                            $inData['action_type_id'] = $question['ActionType']['id'];
                                            $inData['question_choice_id'] = key($option);
                                            $inData['user_input_values'] = $option[key($option)];

                                            if ($this->ActionResponse->save($inData)) {
                                                $this->ActionQuestion->id = $question['ActionQuestion']['id'];

                                                $this->ActionQuestion->saveField('response_count', ($question['ActionQuestion']['response_count'] + 1));

                                                $response['status'] = 1;
                                                $response['message'] = 'Your answer posted successfully';

                                            } else {
                                                $response['status'] = 0;
                                                $response['message'] = 'Sorry your answer could not be post';
                                            }

                                        }

                                        if ($question['ActionType']['name'] == 'RATING') {


                                            $this->ActionResponse->create();
                                            $inData = array();
                                            $inData['action_question_id'] = $question_id;
                                            $inData['mobile_number'] = $subscriber['Subscriber']['mobile'];
                                            $inData['user_id'] = $subscriber['Subscriber']['app_user_id'];
                                            $inData['subscriber_id'] = $subscriber['Subscriber']['id'];
                                            $inData['action_type_id'] = $question['ActionType']['id'];
                                            $inData['question_choice_id'] = key($option);
                                            $inData['user_input_values'] = $option[key($option)];

                                            if ($this->ActionResponse->save($inData)) {
                                                $this->ActionQuestion->id = $question['ActionQuestion']['id'];

                                                $this->ActionQuestion->saveField('response_count', ($question['ActionQuestion']['response_count'] + 1));

                                                $response['status'] = 1;
                                                $response['message'] = 'Your answer posted successfully';

                                            } else {
                                                $response['status'] = 0;
                                                $response['message'] = 'Sorry your answer could not be post';
                                            }

                                        }

                                        if ($question['ActionType']['name'] == 'MULTIPLE INPUTS') {


                                            $this->ActionResponse->create();
                                            $inData = array();
                                            $n = 0;
                                            foreach ($option as $choiceID => $inputValue) {
                                                $inData[$n]['action_question_id'] = $question_id;
                                                $inData[$n]['mobile_number'] = $subscriber['Subscriber']['mobile'];
                                                $inData[$n]['user_id'] = $subscriber['Subscriber']['app_user_id'];
                                                $inData[$n]['subscriber_id'] = $subscriber['Subscriber']['id'];
                                                $inData[$n]['action_type_id'] = $question['ActionType']['id'];
                                                $inData[$n]['question_choice_id'] = $choiceID;
                                                $inData[$n]['user_input_values'] = $inputValue;
                                                $n++;
                                            }

                                            if ($this->ActionResponse->saveAll($inData)) {
                                                $this->ActionQuestion->id = $question['ActionQuestion']['id'];

                                                $this->ActionQuestion->saveField('response_count', ($question['ActionQuestion']['response_count'] + 1));

                                                $response['status'] = 1;
                                                $response['message'] = 'Your answer posted successfully';

                                            } else {
                                                $response['status'] = 0;
                                                $response['message'] = 'Sorry your answer could not be post';
                                            }

                                        }

                                        if ($question['ActionType']['name'] == 'MULTIPLE CHOICES') {

                                            $this->ActionResponse->create();
                                            $inData = array();
                                            $n = 0;
                                            foreach ($option as $choiceID => $inputValue) {
                                                $inData[$n]['action_question_id'] = $question_id;
                                                $inData[$n]['mobile_number'] = $subscriber['Subscriber']['mobile'];
                                                $inData[$n]['user_id'] = $subscriber['Subscriber']['app_user_id'];
                                                $inData[$n]['subscriber_id'] = $subscriber['Subscriber']['id'];
                                                $inData[$n]['action_type_id'] = $question['ActionType']['id'];
                                                $inData[$n]['question_choice_id'] = $choiceID;
                                                $inData[$n]['user_input_values'] = $inputValue;
                                                $n++;
                                            }

                                            if ($this->ActionResponse->saveAll($inData)) {
                                                $this->ActionQuestion->id = $question['ActionQuestion']['id'];

                                                $this->ActionQuestion->saveField('response_count', ($question['ActionQuestion']['response_count'] + 1));

                                                $response['status'] = 1;
                                                $response['message'] = 'Your answer posted successfully';

                                            } else {
                                                $response['status'] = 0;
                                                $response['message'] = 'Sorry your answer could not be post';
                                            }

                                        }

                                        if ($question['ActionType']['name'] == 'SCALING') {

                                            $this->ActionResponse->create();
                                            $inData = array();
                                            $inData['action_question_id'] = $question_id;
                                            $inData['mobile_number'] = $subscriber['Subscriber']['mobile'];
                                            $inData['user_id'] = $subscriber['Subscriber']['app_user_id'];
                                            $inData['subscriber_id'] = $subscriber['Subscriber']['id'];
                                            $inData['action_type_id'] = $question['ActionType']['id'];
                                            $inData['question_choice_id'] = key($option);
                                            $inData['user_input_values'] = $option[key($option)];

                                            if ($this->ActionResponse->save($inData)) {
                                                $this->ActionQuestion->id = $question['ActionQuestion']['id'];

                                                $this->ActionQuestion->saveField('response_count', ($question['ActionQuestion']['response_count'] + 1));

                                                $response['status'] = 1;
                                                $response['message'] = 'Your answer posted successfully';

                                            } else {
                                                $response['status'] = 0;
                                                $response['message'] = 'Sorry your answer could not be post';
                                            }

                                        }

                                        if ($question['ActionType']['name'] == 'RANKING') {


                                            $this->ActionResponse->create();
                                            $inData = array();
                                            $n = 0;
                                            foreach ($option as $choiceID => $inputValue) {
                                                $inData[$n]['action_question_id'] = $question_id;
                                                $inData[$n]['mobile_number'] = $subscriber['Subscriber']['mobile'];
                                                $inData[$n]['user_id'] = $subscriber['Subscriber']['app_user_id'];
                                                $inData[$n]['subscriber_id'] = $subscriber['Subscriber']['id'];
                                                $inData[$n]['action_type_id'] = $question['ActionType']['id'];
                                                $inData[$n]['question_choice_id'] = $choiceID;
                                                $inData[$n]['user_input_values'] = $inputValue;
                                                $n++;
                                            }

                                            if ($this->ActionResponse->saveAll($inData)) {
                                                $this->ActionQuestion->id = $question['ActionQuestion']['id'];
                                                $this->ActionQuestion->saveField('response_count', ($question['ActionQuestion']['response_count'] + 1));
                                                $response['status'] = 1;
                                                $response['message'] = 'Your answer posted successfully';

                                            } else {
                                                $response['status'] = 0;
                                                $response['message'] = 'Sorry your answer could not be post';
                                            }

                                        }


                                    }
                                } else {
                                    $response['status'] = 0;
                                    $response['message'] = "You are not subscribe for this channel";
                                }
                            } else {

                                /* this result submit for poll factory response*/
                                $countAns = $this->ActionResponse->find("count", array(
                                    "conditions" => array(
                                        "ActionResponse.user_id" => $user_id,
                                        "ActionResponse.action_question_id" => $question_id,
                                    ),

                                    "contain" => false
                                ));


                                if ($countAns > 0) {
                                    $response['status'] = 0;
                                    $response['message'] = "You have already used this poll";

                                } else if (empty($question)) {

                                    $response['status'] = 0;
                                    $response['message'] = "Sorry, Poll is either not found or expired";

                                } else {


                                    $post = $this->request->data;

                                    if ($question['ActionType']['name'] == 'SHORT ANSWER') {
                                        $this->ActionResponse->create();
                                        $inData = array();
                                        $inData['action_question_id'] = $question_id;
                                        $inData['mobile_number'] = $mobile;
                                        $inData['user_id'] = $user_id;

                                        $inData['action_type_id'] = $question['ActionType']['id'];
                                        $inData['question_choice_id'] = key($option);
                                        $inData['user_input_values'] = $option[key($option)];

                                        if ($this->ActionResponse->save($inData)) {
                                            $this->ActionQuestion->id = $question['ActionQuestion']['id'];
                                            $this->ActionQuestion->saveField('response_count', ($question['ActionQuestion']['response_count'] + 1));
                                            $response['status'] = 1;
                                            $response['message'] = 'Your answer posted successfully';

                                        } else {
                                            $response['status'] = 0;
                                            $response['message'] = 'Sorry your answer could not be post';
                                        }
                                    }

                                    if ($question['ActionType']['name'] == 'LONG ANSWER') {


                                        $this->ActionResponse->create();
                                        $inData = array();
                                        $inData['action_question_id'] = $question_id;
                                        $inData['mobile_number'] = $mobile;
                                        $inData['user_id'] = $user_id;
                                        $inData['action_type_id'] = $question['ActionType']['id'];
                                        $inData['question_choice_id'] = key($option);
                                        $inData['user_input_values'] = $option[key($option)];

                                        if ($this->ActionResponse->save($inData)) {
                                            $this->ActionQuestion->id = $question['ActionQuestion']['id'];

                                            $this->ActionQuestion->saveField('response_count', ($question['ActionQuestion']['response_count'] + 1));

                                            $response['status'] = 1;
                                            $response['message'] = 'Your answer posted successfully';

                                        } else {
                                            $response['status'] = 0;
                                            $response['message'] = 'Sorry your answer could not be post';
                                        }

                                    }

                                    if ($question['ActionType']['name'] == 'DROPDOWN') {

                                        $this->ActionResponse->create();
                                        $inData = array();
                                        $inData['action_question_id'] = $question_id;
                                        $inData['mobile_number'] = $mobile;
                                        $inData['user_id'] = $user_id;
                                        $inData['action_type_id'] = $question['ActionType']['id'];
                                        $inData['question_choice_id'] = key($option);
                                        $inData['user_input_values'] = $option[key($option)];

                                        if ($this->ActionResponse->save($inData)) {
                                            $this->ActionQuestion->id = $question['ActionQuestion']['id'];

                                            $this->ActionQuestion->saveField('response_count', ($question['ActionQuestion']['response_count'] + 1));

                                            $response['status'] = 1;
                                            $response['message'] = 'Your answer posted successfully';

                                        } else {
                                            $response['status'] = 0;
                                            $response['message'] = 'Sorry your answer could not be post';
                                        }

                                    }

                                    if ($question['ActionType']['name'] == 'YES/NO') {


                                        $this->ActionResponse->create();
                                        $inData = array();
                                        $inData['action_question_id'] = $question_id;
                                        $inData['mobile_number'] = $mobile;
                                        $inData['user_id'] = $user_id;
                                        $inData['action_type_id'] = $question['ActionType']['id'];
                                        $inData['question_choice_id'] = key($option);
                                        $inData['user_input_values'] = $option[key($option)];

                                        if ($this->ActionResponse->save($inData)) {
                                            $this->ActionQuestion->id = $question['ActionQuestion']['id'];

                                            $this->ActionQuestion->saveField('response_count', ($question['ActionQuestion']['response_count'] + 1));

                                            $response['status'] = 1;
                                            $response['message'] = 'Your answer posted successfully';

                                        } else {
                                            $response['status'] = 0;
                                            $response['message'] = 'Sorry your answer could not be post';
                                        }

                                    }

                                    if ($question['ActionType']['name'] == 'DATE') {

                                        $this->ActionResponse->create();
                                        $inData = array();
                                        $inData['action_question_id'] = $question_id;
                                        $inData['mobile_number'] = $mobile;
                                        $inData['user_id'] = $user_id;
                                        $inData['action_type_id'] = $question['ActionType']['id'];
                                        $inData['question_choice_id'] = key($option);
                                        $inData['user_input_values'] = $option[key($option)];

                                        if ($this->ActionResponse->save($inData)) {
                                            $this->ActionQuestion->id = $question['ActionQuestion']['id'];

                                            $this->ActionQuestion->saveField('response_count', ($question['ActionQuestion']['response_count'] + 1));

                                            $response['status'] = 1;
                                            $response['message'] = 'Your answer posted successfully';

                                        } else {
                                            $response['status'] = 0;
                                            $response['message'] = 'Sorry your answer could not be post';
                                        }

                                    }

                                    if ($question['ActionType']['name'] == 'RATING') {


                                        $this->ActionResponse->create();
                                        $inData = array();
                                        $inData['action_question_id'] = $question_id;
                                        $inData['mobile_number'] = $mobile;
                                        $inData['user_id'] = $user_id;
                                        $inData['action_type_id'] = $question['ActionType']['id'];
                                        $inData['question_choice_id'] = key($option);
                                        $inData['user_input_values'] = $option[key($option)];

                                        if ($this->ActionResponse->save($inData)) {
                                            $this->ActionQuestion->id = $question['ActionQuestion']['id'];

                                            $this->ActionQuestion->saveField('response_count', ($question['ActionQuestion']['response_count'] + 1));

                                            $response['status'] = 1;
                                            $response['message'] = 'Your answer posted successfully';

                                        } else {
                                            $response['status'] = 0;
                                            $response['message'] = 'Sorry your answer could not be post';
                                        }

                                    }

                                    if ($question['ActionType']['name'] == 'MULTIPLE INPUTS') {


                                        $this->ActionResponse->create();
                                        $inData = array();
                                        $n = 0;
                                        foreach ($option as $choiceID => $inputValue) {
                                            $inData[$n]['action_question_id'] = $question_id;
                                            $inData[$n]['mobile_number'] = $mobile;
                                            $inData[$n]['user_id'] = $user_id;
                                            $inData[$n]['action_type_id'] = $question['ActionType']['id'];
                                            $inData[$n]['question_choice_id'] = $choiceID;
                                            $inData[$n]['user_input_values'] = $inputValue;
                                            $n++;
                                        }

                                        if ($this->ActionResponse->saveAll($inData)) {
                                            $this->ActionQuestion->id = $question['ActionQuestion']['id'];

                                            $this->ActionQuestion->saveField('response_count', ($question['ActionQuestion']['response_count'] + 1));

                                            $response['status'] = 1;
                                            $response['message'] = 'Your answer posted successfully';

                                        } else {
                                            $response['status'] = 0;
                                            $response['message'] = 'Sorry your answer could not be post';
                                        }

                                    }

                                    if ($question['ActionType']['name'] == 'MULTIPLE CHOICES') {

                                        $this->ActionResponse->create();
                                        $inData = array();
                                        $n = 0;
                                        foreach ($option as $choiceID => $inputValue) {
                                            $inData[$n]['action_question_id'] = $question_id;
                                            $inData[$n]['mobile_number'] = $mobile;
                                            $inData[$n]['user_id'] = $user_id;
                                            $inData[$n]['action_type_id'] = $question['ActionType']['id'];
                                            $inData[$n]['question_choice_id'] = $choiceID;
                                            $inData[$n]['user_input_values'] = $inputValue;
                                            $n++;
                                        }

                                        if ($this->ActionResponse->saveAll($inData)) {
                                            $this->ActionQuestion->id = $question['ActionQuestion']['id'];

                                            $this->ActionQuestion->saveField('response_count', ($question['ActionQuestion']['response_count'] + 1));

                                            $response['status'] = 1;
                                            $response['message'] = 'Your answer posted successfully';

                                        } else {
                                            $response['status'] = 0;
                                            $response['message'] = 'Sorry your answer could not be post';
                                        }

                                    }

                                    if ($question['ActionType']['name'] == 'SCALING') {

                                        $this->ActionResponse->create();
                                        $inData = array();
                                        $inData['action_question_id'] = $question_id;
                                        $inData['mobile_number'] = $mobile;
                                        $inData['user_id'] = $user_id;
                                        $inData['action_type_id'] = $question['ActionType']['id'];
                                        $inData['question_choice_id'] = key($option);
                                        $inData['user_input_values'] = $option[key($option)];

                                        if ($this->ActionResponse->save($inData)) {
                                            $this->ActionQuestion->id = $question['ActionQuestion']['id'];

                                            $this->ActionQuestion->saveField('response_count', ($question['ActionQuestion']['response_count'] + 1));

                                            $response['status'] = 1;
                                            $response['message'] = 'Your answer posted successfully';

                                        } else {
                                            $response['status'] = 0;
                                            $response['message'] = 'Sorry your answer could not be post';
                                        }

                                    }

                                    if ($question['ActionType']['name'] == 'RANKING') {


                                        $this->ActionResponse->create();
                                        $inData = array();
                                        $n = 0;
                                        foreach ($option as $choiceID => $inputValue) {
                                            $inData[$n]['action_question_id'] = $question_id;
                                            $inData[$n]['mobile_number'] = $mobile;
                                            $inData[$n]['user_id'] = $user_id;
                                            $inData[$n]['action_type_id'] = $question['ActionType']['id'];
                                            $inData[$n]['question_choice_id'] = $choiceID;
                                            $inData[$n]['user_input_values'] = $inputValue;
                                            $n++;
                                        }

                                        if ($this->ActionResponse->saveAll($inData)) {
                                            $this->ActionQuestion->id = $question['ActionQuestion']['id'];
                                            $this->ActionQuestion->saveField('response_count', ($question['ActionQuestion']['response_count'] + 1));
                                            $response['status'] = 1;
                                            $response['message'] = 'Your answer posted successfully';

                                        } else {
                                            $response['status'] = 0;
                                            $response['message'] = 'Sorry your answer could not be post';
                                        }

                                    }
                                }
                            }


                        } else {
                            $response['status'] = 0;
                            $response['message'] = "This poll is expired.";
                        }
                    } else {
                        $response['status'] = 0;
                        $response['message'] = "Please give you answer";
                    }

                } else {
                    $response['status'] = 0;
                    $response['message'] = $this->Custom->getResponseMessage($result);
                }
            } else {
                $response['status'] = 0;
                $response['message'] = "Invalid request parameter";
            }
            echo json_encode($response);
            exit;
        }


    }


    public function get_poll_result()
    {

        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        if ($this->request->is('post')) {
            $user_id = $data['user_id'];
            $thin_app_id = $data['thin_app_id'];
            $app_key = $data['app_key'];
            $question_id = $data['question_id'];

            if ($user_id != '' && $app_key != '' && $question_id != '') {
                if (($result = $this->Custom->CheckIsValidApp($app_key, $thin_app_id)) && ($result == 1)) {
                    $response = array();
                    $response['status'] = 1;
                    $response['message'] = "Poll result";
                    $result = $this->Custom->getPollChart($question_id);

                    if (!empty($result['responseCount'])) {
                        $response['status'] = 1;
                        $response['message'] = "Response found";
                        $response['data']['participates_count'] = $result['questionVal']['participates_count'];
                        $response['data']['response_count'] = $result['questionVal']['response_count'];
                        $response['data']['question_text'] = $result['questionVal']['question_text'];
                        $response['data']['end_time'] = date('d-m-Y H:i', strtotime($result['questionVal']['end_time']));
                        $response['data']['poll_duration'] = $result['questionVal']['poll_duration'];
                        $left_days = explode(" ", $result['questionVal']['poll_duration']);
                        $response['data']['left_time_string'] = ucwords(strtolower($left_days[1]));
                        $response['data']['left_time_int'] = $left_days[0];
                        $response['data']['action_type'] = $result['actionType']['name'];
                        $res = array();
                        if (in_array($result['actionType']["name"], array('MULTIPLE INPUTS', 'SHORT ANSWER', 'LONG ANSWER', 'DATE'))) {
                            foreach ($result['actionChoice'] as $key => $option) {
                                $number = substr($key, 0, 7) . str_repeat("*", strlen($key) - 7);
                                $res[$number] = $option;
                            }
                        } else {
                            foreach ($result['actionChoice'] as $key => $option) {
                                if (array_key_exists($key, $result['responseCount'])) {
                                    $res[$option] = $result['responseCount'][$key];
                                }
                            }
                        }
                        $response['data']['result'] = array($res);
                    } else {
                        $response['status'] = 0;
                        $response['message'] = "No chart data available!";
                    }

                } else {
                    $response['status'] = 0;
                    $response['message'] = $this->Custom->getResponseMessage($result);
                }
            } else {
                $response['status'] = 0;
                $response['message'] = "Invalid request parameter";
            }
            echo json_encode($response);
            exit;
        }


    }
    
    
    
    
    
    
    

    /* api created by mahendra */
    public function cms_pages()
    {
        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        if ($this->request->is('post')) {
            $app_key = $data['app_key'];
            $user_id = $data['user_id'];
            $thin_app_id = $data['thin_app_id'];
            $response = array();
            if ($user_id != '' && $app_key != '' && $thin_app_id != '') {


                if (($result = $this->Custom->CheckIsValidApp($app_key, $thin_app_id)) && ($result == 1)) {
                    $cmspages = $this->CmsPage->find('all', array(
                        'conditions' => array(
                            //  'CmsPage.user_id' =>$user_id,
                            'CmsPage.thinapp_id' => $thin_app_id,
                            'CmsPage.status' => 'ACTIVE'
                        ),
                        'contain' => false,
                        'fields' => array('CmsPage.id', 'CmsPage.title', 'CmsPage.description')
                    ));
                    if (!empty($cmspages)) {
                        $response['status'] = 1;
                        $response['message'] = "Pages Found";
                        foreach ($cmspages as $key => $page) {
                            $response['data']['pages'][$key]['id'] = $page['CmsPage']['id'];
                            $response['data']['pages'][$key]['title'] = $page['CmsPage']['title'];
                            $response['data']['pages'][$key]['description'] = ($page['CmsPage']['description']);
                        }

                    } else {
                        $response['status'] = 0;
                        $response['message'] = "Pages Not Found";
                    }
                } else {
                    $response['status'] = 0;
                    $response['message'] = $this->Custom->getResponseMessage($result);
                }

            } else {
                $response['status'] = 0;
                $response['message'] = "Invalid request parameter";
            }

            echo json_encode($response);
            exit;
        }

    }


    /* api created by mahendra */
    public function facebook_token()
    {

        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        if ($this->request->is('post')) {
            $app_key = $data['app_key'];
            $user_id = $data['user_id'];
            $thin_app_id = $data['thin_app_id'];
            $response = array();
            if ($user_id != '' && $app_key != '' && $thin_app_id) {
                if (($result = $this->Custom->CheckIsValidApp($app_key, $thin_app_id)) && ($result == 1)) {
                    $thin_app_data = $this->Thinapp->find('first', array(
                        'conditions' => array(
                            'Thinapp.id' => $thin_app_id
                        ),
                        'contain' => false,

                        'fields' => array('Thinapp.id', 'Thinapp.facebook_id', 'Thinapp.facebook_url')
                    ));
                    if (!empty($thin_app_data)) {
                        $response['status'] = 1;
                        $response['message'] = "Token Found";
                        $response['facebook_url'] = $thin_app_data["Thinapp"]["facebook_url"];

                    } else {
                        $response['status'] = 0;
                        $response['message'] = "Token Not Found";
                    }
                } else {
                    $response['status'] = 0;
                    $response['message'] = $this->Custom->getResponseMessage($result);
                }
            } else {
                $response['status'] = 0;
                $response['message'] = "Invalid request parameter";
            }

            echo json_encode($response);
            exit;
        }

    }
    
    

    /* api created by mahendra */
    public function twitter_username()
    {

        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        if ($this->request->is('post')) {
            $app_key = $data['app_key'];
            $user_id = $data['user_id'];
            $thin_app_id = $data['thin_app_id'];
            $response = array();
            if ($user_id != '' && $app_key != '' && $thin_app_id) {
                if (($result = $this->Custom->CheckIsValidApp($app_key, $thin_app_id)) && ($result == 1)) {
                    $thin_app_data = $this->Thinapp->find('first', array(
                        'conditions' => array(
                            'Thinapp.id' => $thin_app_id
                        ),
                        'contain' => false,

                        'fields' => array('Thinapp.twitter_usename')
                    ));
                    if (!empty($thin_app_data) && !empty($thin_app_data["Thinapp"]["twitter_usename"])) {
                        $response['status'] = 1;
                        $response['message'] = "Username Found";
                        $response['twitter_usename'] = $thin_app_data["Thinapp"]["twitter_usename"];
                    } else {
                        $response['status'] = 0;
                        $response['message'] = "Username Not Found";
                    }
                } else {
                    $response['status'] = 0;
                    $response['message'] = $this->Custom->getResponseMessage($result);
                }
            } else {
                $response['status'] = 0;
                $response['message'] = "Invalid request parameter";
            }

            echo json_encode($response);
            exit;
        }

    }


    /* api created by mahendra */
    public function staff_list()
    {

        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        if ($this->request->is('post')) {
            $app_key = $data['app_key'];
            $user_id = $data['user_id'];
            $thin_app_id = $data['thin_app_id'];
            $response = array();
            if ($user_id != '' && $app_key != '' && $thin_app_id) {
                if (($result = $this->Custom->CheckIsValidApp($app_key, $thin_app_id)) && ($result == 1)) {
                    $cmspages = $this->AppStaff->find('all', array(
                        'conditions' => array(
                            'AppStaff.thinapp_id' => $thin_app_id,
                            'AppStaff.status' => 'Y'
                        ),
                        'contain' => false,
                        'fields' => array('AppStaff.id', 'AppStaff.mobile', 'AppStaff.image', 'AppStaff.fullname', 'AppStaff.designation')
                    ));


                    if (!empty($cmspages)) {

                        $counter = 0;
                        foreach ($cmspages as $key => $page) {
                            $is_app_user_id = $this->Custom->is_app_user($thin_app_id, $page['AppStaff']['mobile']);
                            if (!empty($is_app_user_id)) {

                                    $response['data']['staff_list'][$counter]['id'] = $page['AppStaff']['id'];
                                    $response['data']['staff_list'][$counter]['mobile'] = $page['AppStaff']['mobile'];
                                    $response['data']['staff_list'][$counter]['image'] = "";
                                    if (!empty($page['AppStaff']['image'])) {
                                        $response['data']['staff_list'][$counter]['image'] = $page['AppStaff']['image'];
                                    }
                                    $response['data']['staff_list'][$counter]['name'] = $page['AppStaff']['fullname'];
                                    $response['data']['staff_list'][$counter]['designation'] = $page['AppStaff']['designation'];
                                    $response['data']['staff_list'][$counter]['firebase_token'] = $is_app_user_id['firebase_token'];
                                    $response['data']['staff_list'][$counter]['enable_chat'] = "YES";
                                    //$response['data']['staff_list'][$counter]['staff_user_id']  =$is_app_user_id['id'];
                                    $counter++;

                            } else {
                                $response['data']['staff_list'][$counter]['id'] = $page['AppStaff']['id'];
                                $response['data']['staff_list'][$counter]['mobile'] = $page['AppStaff']['mobile'];
                                $response['data']['staff_list'][$counter]['image'] = "";
                                if (!empty($page['AppStaff']['image'])) {
                                    $response['data']['staff_list'][$counter]['image'] = $page['AppStaff']['image'];
                                }
                                $response['data']['staff_list'][$counter]['name'] = $page['AppStaff']['fullname'];
                                $response['data']['staff_list'][$counter]['designation'] = $page['AppStaff']['designation'];
                                $response['data']['staff_list'][$counter]['firebase_token'] = $is_app_user_id['firebase_token'];
                                $response['data']['staff_list'][$counter]['enable_chat'] = "NO";
                                //$response['data']['staff_list'][$counter]['staff_user_id']  =$is_app_user_id['id'];
                                $counter++;
                            }
                        }

                        if ($counter > 0) {
                            $response['status'] = 1;
                            $response['message'] = "Staff list Found";
                        } else {
                            $response['status'] = 0;
                            $response['message'] = "Staff Not Found";
                        }

                    } else {
                        $response['status'] = 0;
                        $response['message'] = "Staff Not Found";
                    }

                } else {
                    $response['status'] = 0;
                    $response['message'] = $this->Custom->getResponseMessage($result);
                }
            } else {
                $response['status'] = 0;
                $response['message'] = "Invalid request parameter";
            }

            echo json_encode($response);
            exit;
        }

    }


    /*api created by mahendra*/


    public function send_chat_notification()
    {

        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        if ($this->request->is('post')) {
            $response = array();
            $user_id = $data['user_id'];
            $thin_app_id = $data['thin_app_id'];
            $app_key = $data['app_key'];
            $firebase_token = $data['firebase_token'];
            $firebase_reference = $data['firebase_reference'];
            $from_number = $data['from_number'];
            $to_number = $data['to_number'];
            $message = $data['message'];

            $user = $this->User->find('first', array(
                'conditions' => array(
                    'User.thinapp_id' => $thin_app_id,
                    'User.mobile' => $to_number
                ),
                'contain' => false,
                'fields' => array('User.firebase_token')
            ));
            if (!empty($user)) {
                $firebase_token = $user['User']['firebase_token'];
                if ($user_id != '' && $app_key != '' && $thin_app_id != '' && $firebase_token != "") {
                    if (empty($message)) {
                        $message = "You have received a new message";
                    }
                    $send_array = array(
                        'title' => 'New Message',
                        'thinapp_id' => $thin_app_id,
                        'message' => mb_strimwidth("Chat - " . $message, 0, 50, '...'),
                        'flag' => 'FCHAT',
                        'firebase_reference' => $firebase_reference,
                        'firebase_token' => $firebase_token,
                        'from_number' => $from_number,
                        'to_number' => $to_number,
                        'description' => '',
                        'chat_reference' => '',
                        'module_type' => 'CHAT',
                        'module_type_id' => 0,

                    );
                    $server_key = $this->Custom->getThinAppFirebaseKey($thin_app_id);
                    $path_to_firebase_cm = 'https://fcm.googleapis.com/fcm/send';
                    $fields = array(
                        'to' => $firebase_token,
                        'data' => $send_array
                    );
                    $headers = array(
                        'Authorization:key=' . $server_key,
                        'Content-Type:application/json'
                    );
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $path_to_firebase_cm);
                    curl_setopt($ch, CURLOPT_POST, true);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
                    $result = curl_exec($ch);
                    $result = json_decode($result, true);
                    curl_close($ch);
                    if ($result["success"] == 1) {
                        $response['status'] = 1;
                        $response['message'] = "Notification send";
                        $response['message_id'] = $result["results"]['0']["message_id"];
                    } else {
                        $response['status'] = 0;
                        $response['message'] = "Notification could not send";
                    }

                } else {
                    $response['status'] = 0;
                    $response['message'] = "Invalid request parameter";
                }
            } else {
                $response['status'] = 0;
                $response['message'] = "Invalid request parameter";
            }
            echo json_encode($response);
            exit;
        }
    }

    

    /* api created by mahendra */

    public function redeem()
    {

        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        if ($this->request->is('post')) {

            $response = array();
            $user_id = $data['user_id'];
            $thin_app_id = $data['thin_app_id'];
            $app_key = $data['app_key'];
            $coins = $data['coins'];

            if ($user_id != '' && $app_key != '' && $thin_app_id != '' && $coins != "") {
                if (($result = $this->Custom->CheckIsValidApp($app_key, $thin_app_id)) && ($result == 1)) {
                    $min_coin = unserialize(CoinDistribution)['REGISTER'];
                    if ($coins >= $min_coin) {


                        $user_total_coins = $this->Custom->totalUserCoins($user_id);

                        if ($user_total_coins >= $min_coin) {
                            if ($coins <= $user_total_coins) {
                                $is_redeem_statu = $this->Custom->redeemCoins($user_id, $coins);
                                if ($is_redeem_statu) {
                                    $response['status'] = 1;
                                    $response['message'] = "Redeem request post successfully";
                                } else {
                                    $response['status'] = 0;
                                    $response['message'] = "Sorry redeem request could not proceed";
                                }
                            } else {
                                $response['status'] = 0;
                                $response['message'] = "You can redeem maximum " . $user_total_coins . " coins";

                            }
                        } else {
                            $response['status'] = 0;
                            $response['message'] = "You have insufficient coins";
                        }


                    } else {
                        $response['status'] = 0;
                        $response['message'] = "Please redeem minimum " . $min_coin . " coins";
                    }

                } else {
                    $response['status'] = 0;
                    $response['message'] = $this->Custom->getResponseMessage($result);
                }
            } else {
                $response['status'] = 0;
                $response['message'] = "Invalid request parameter";
            }
            echo json_encode($response);
            exit;
        }
    }


    /* api created by mahendra */

    public function transfer_coin()
    {

        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        if ($this->request->is('post')) {

            $response = array();
            $user_id = $data['user_id'];
            $transfer_to_mobile = $data['transfer_to_mobile'];
            $thin_app_id = $data['thin_app_id'];
            $app_key = $data['app_key'];
            $coins = $data['coins'];

            if ($user_id != '' && $app_key != '' && $thin_app_id != '' && $coins != "" && $transfer_to_mobile != '') {
                if (($result = $this->Custom->CheckIsValidApp($app_key, $thin_app_id)) && ($result == 1)) {

                    $validate_mobile = $this->Custom->check_mobile_number($transfer_to_mobile);
                    if ($validate_mobile) {
                        $transfer_to_mobile = $validate_mobile;
                         $total_sms = Custom::get_total_sms_thinapp($thin_app_id,'T');
                        if ($total_sms > 0) {

                            $is_transfer_user = $this->Custom->getUserbyApp($transfer_to_mobile, $thin_app_id);
                            if (!empty($is_transfer_user)) {
                                $min_coin = unserialize(CoinDistribution)['REGISTER'];
                                if ($coins >= $min_coin) {
                                    $user_total_coins = $this->Custom->totalUserCoins($user_id);
                                    if ($user_total_coins > $min_coin) {
                                        if ($coins <= $user_total_coins) {
                                            $is_redeem_status = $this->Custom->transferCoins($user_id, $is_transfer_user['id'], $coins);
                                            if ($is_redeem_status) {
                                                $response['status'] = 1;
                                                $response['message'] = "Coins transferred  successfully";
                                            } else {
                                                $response['status'] = 0;
                                                $response['message'] = "Sorry coins could not transferred";
                                            }
                                        } else {
                                            $response['status'] = 0;
                                            $response['message'] = "You can redeem maximum " . $user_total_coins . " coins";
                                        }

                                    } else {
                                        $response['status'] = 0;
                                        $response['message'] = "You have insufficient coins";
                                    }

                                } else {
                                    $response['status'] = 0;
                                    $response['message'] = "Please redeem minimum " . $min_coin . " coins";
                                }
                            } else {
                                $response['status'] = 0;
                                $response['message'] = "Sorry you can not transfer coin to this user";
                            }

                        } else {
                            $response['status'] = 0;
                            $response['message'] = "System error found. Please try later";
                        }
                    } else {
                        $response['status'] = 0;
                        $response['message'] = "Sorry invalid mobile number";
                    }


                } else {
                    $response['status'] = 0;
                    $response['message'] = $this->Custom->getResponseMessage($result);
                }
            } else {
                $response['status'] = 0;
                $response['message'] = "Invalid request parameter";
            }
            echo json_encode($response);
            exit;
        }
    }


    /* api created by mahendra */

    public function contact_us()
    {

        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        if ($this->request->is('post')) {

            $response = array();
            $user_id = $data['user_id'];
            $thin_app_id = $data['thin_app_id'];
            $app_key = $data['app_key'];
            $message = $data['message'];
            $mobile = $data['mobile'];

            if ($user_id != '' && $app_key != '' && $thin_app_id != '' && $message != "") {
                if (($result = $this->Custom->CheckIsValidApp($app_key, $thin_app_id)) && ($result == 1)) {
                    $this->AppEnquiry->create();
                    $this->AppEnquiry->set('message', $message);
                    $this->AppEnquiry->set('enquiry_type', 'CONTACT');
                    $this->AppEnquiry->set('enquiry_from', 'APP');
                    $this->AppEnquiry->set('phone', $mobile);
                    $this->AppEnquiry->set('thinapp_id', $thin_app_id);
                    if ($this->AppEnquiry->save()) {
                        $response['status'] = 1;
                        $response['message'] = "Your query post successfully";
                    } else {
                        $response['status'] = 0;
                        $response['message'] = "Sorry query could not post";
                    }
                } else {
                    $response['status'] = 0;
                    $response['message'] = $this->Custom->getResponseMessage($result);
                }
            } else {
                $response['status'] = 0;
                $response['message'] = "Invalid request parameter";
            }
            echo json_encode($response);
            exit;
        }
    }


    /* api created by mahendra */

    public function get_credits()
    {

        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        if ($this->request->is('post')) {

            $response = array();
            $user_id = $data['user_id'];
            $thin_app_id = $data['thin_app_id'];
            $app_key = $data['app_key'];
            if ($user_id != '' && $app_key != '' && $thin_app_id != '') {
                if (($result = $this->Custom->CheckIsValidApp($app_key, $thin_app_id)) && ($result == 1)) {
                    $coin = $this->Gullak->find('first', array(
                        "conditions" => array(
                            "Gullak.user_id" => $user_id,
                        ),

                        'fields' => array("Gullak.total_coins"),
                        'contain' => false
                    ));
                    if (!empty($coin)) {
                        $response['status'] = 1;
                        $response['message'] = "Gullak found";
                        $response['data']['total_coins'] = $coin['Gullak']['total_coins'];
                    } else {
                        $response['status'] = 0;
                        $response['message'] = "Sorry this user have no gullak";
                    }
                } else {
                    $response['status'] = 0;
                    $response['message'] = $this->Custom->getResponseMessage($result);
                }
            } else {
                $response['status'] = 0;
                $response['message'] = "Invalid request parameter";
            }
            echo json_encode($response);
            exit;
        }
    }





    /* function add by mahendra*/
    /* this function created by mahendra */
    public function add_ticket()
    {

        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        if ($this->request->is('post')) {
            $response = array();
            $user_id = $data['user_id'];
            $thin_app_id = $data['thin_app_id'];
            $app_key = $data['app_key'];
            $title = $data['title'];
            $description = $data['description'];
            $media = $data['media'];
            $query_type = $data['ticket_type'];
            $type = $data['type'];

            if ($user_id != '' && $title != '' && $app_key != '' && $description != '' && $type != '') {
                if (($result = $this->Custom->CheckIsValidApp($app_key, $thin_app_id)) && ($result == 1)) {
                    $datasource = $this->Ticket->getDataSource();
                    try {
                        $datasource->begin();
                        $this->Ticket->create();
                        $this->Ticket->set('app_id', $thin_app_id);
                        $this->Ticket->set('created_by_user_id', $user_id);
                        $this->Ticket->set('title', mb_strimwidth($title, 0, 20, '...'));
                        $this->Ticket->set('description', $description);
                        $this->Ticket->set('media', $media);
                        $this->Ticket->set('query_type', $query_type);
                        $this->Ticket->set('type', $type);
                        if ($this->Ticket->save()) {
                            $last_inser_id = $this->Ticket->getLastInsertId();
                            $this->TicketComment->create();
                            $this->TicketComment->set('ticket_id', $last_inser_id);
                            $this->TicketComment->set('ticket_status', "OPEN");
                            if ($this->TicketComment->save()) {
                                $datasource->commit();
                                $response['status'] = 1;
                                $response['message'] = "Ticket create successfully";
                                $message = "Ticket Open  - " . $description;
                                $option = array(
                                    'thinapp_id' => $thin_app_id,
                                    'quest_id' => $last_inser_id,
                                    'user_id' => $this->Custom->getThinAppAdminId($thin_app_id),
                                    'data' => array(
                                        'thinapp_id' => $thin_app_id,
                                        'channel_id' => 0,
                                        'flag' => 'TICKET',
                                        'title' => "Ticket Update",
                                        'message' => mb_strimwidth($message, 0, 50, '...'),
                                        'description' => "",
                                        'chat_reference' => '',
                                        'module_type' => 'TICKET',
                                        'module_type_id' => $last_inser_id,
                                        'firebase_reference' => ""
                                    )
                                );
                                $this->Custom->send_notification_to_device($option);

                            } else {

                                $datasource->rollback();
                                $response['status'] = 0;
                                $response['message'] = "Sorry ticket could not created";
                            }

                        } else {
                            $response['status'] = 0;
                            $response['message'] = "Sorry ticket could not created";
                        }
                    } catch (Exception $e) {
                        $datasource->rollback();
                        $response['status'] = 0;
                        $response['message'] = "Sorry ticket could not created";
                    }
                } else {
                    $response['status'] = 0;
                    $response['message'] = $this->Custom->getResponseMessage($result);
                }
            } else {
                $response['status'] = 0;
                $response['message'] = "Invalid request parameter";
            }
            echo json_encode($response);
            exit;
        }
    }

    /* funcation add by mahendra */
    public function get_ticket_process_count()
    {

        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        if ($this->request->is('post')) {
            $app_key = $data['app_key'];
            $user_id = $data['user_id'];
            $thin_app_id = $data['thin_app_id'];
            $response = array();
            if ($user_id != '' && $app_key != '' && $thin_app_id != '') {
                if (($result = $this->Custom->CheckIsValidApp($app_key, $thin_app_id)) && ($result == 1)) {
                    $response['status'] = 1;
                    $response['message'] = "Total count found";

                    $condition = "tickets.app_id = " . $thin_app_id;
                    $status_list = $this->Custom->get_enum_values('Ticket', 'tickets', 'status');
                    $type_list = $this->Custom->get_enum_values('Ticket', 'tickets', 'type');
                    $final_array = array_merge($status_list, $type_list);

                    $thin_app_admin = $this->Custom->getThinAppAdminId($thin_app_id);
                    if ($thin_app_admin != $user_id) {
                        $condition .= " AND created_by_user_id = " . $user_id;
                    }

                    $query = "SELECT status as title, COUNT(*) as cnt FROM   tickets  WHERE " . $condition . " GROUP  BY tickets.status UNION ALL SELECT type as title, COUNT(*) as cnt FROM   tickets  WHERE " . $condition . " GROUP  BY tickets.type";
                    $lost_count = $this->Ticket->query($query, array('contain' => false));
                    $count_array = array();
                    foreach ($lost_count as $key => $value) {
                        $count_array[$value[0]['title']] = $value[0]['cnt'];
                    }
                    foreach ($final_array as $key => $value) {
                        if (isset($count_array[$value])) {
                            $response['data']['tickets_counts'][$value] = $count_array[$value];
                        } else {
                            $response['data']['tickets_counts'][$value] = 0;
                        }
                    }

                } else {
                    $response['status'] = 0;
                    $response['message'] = $this->Custom->getResponseMessage($result);
                }
            } else {
                $response['status'] = 0;
                $response['message'] = "Invalid request parameter";
            }

            echo json_encode($response);
            exit;
        }
    }


    public function get_ticket_list()
    {
        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        if ($this->request->is('post')) {

            $app_key = $data['app_key'];
            $user_id = $data['user_id'];
            $thin_app_id = $data['thin_app_id'];
            $type = $data['type'];
            $status = $data['status'];
            $search = $data['search'];
            $offset = $data['offset'];
            $limit = APP_PAGINATION_LIMIT;

            $response = array();
            if ($user_id != '' && $app_key != '' && $thin_app_id != '' && $type != '' && $status != '') {
                if (($result = $this->Custom->CheckIsValidApp($app_key, $thin_app_id)) && ($result == 1)) {

                    $extra_condition_array['Ticket.app_id'] = $thin_app_id;
                    $thin_app_admin = $this->Custom->getThinAppAdminId($thin_app_id);
                    $is_app_owner = "YES";
                    if ($thin_app_admin != $user_id) {
                        $extra_condition_array['Ticket.created_by_user_id'] = $user_id;
                        $is_app_owner = "NO";
                    }

                    if ($type != "TYPE") {
                        $extra_condition_array['Ticket.type'] = $type;
                    }
                    if ($status != "STATUS") {
                        if ($status == "FAVOURITE") {
                            $extra_condition_array['Ticket.is_favourite'] = 'Y';
                        } else {
                            $extra_condition_array['Ticket.status'] = $status;
                        }

                    }
                    if (!empty($search)) {
                        $extra_condition_array['Ticket.description like'] = '%' . $search . '%';
                    }

                    $ticekt_list = $this->Ticket->find('all', array(
                        'conditions' => $extra_condition_array,
                        'contain' => array('CreatedBy'),
                        'order' => array("Ticket.id" => "DESC"),
                        'fields' => array('CreatedBy.mobile', 'Ticket.*'),
                        'offset' => $offset * $limit,
                        'limit' => $limit
                    ));

                    $channel_array = array();
                    if (!empty($ticekt_list)) {
                        foreach ($ticekt_list as $key => $quest_data) {
                            $channels_arr['id'] = $quest_data['Ticket']['id'];
                            $channels_arr['title'] = $quest_data['Ticket']['title'];
                            $channels_arr['description'] = mb_strimwidth($quest_data['Ticket']['description'], 0, 150, '...');
                            $channels_arr['image'] = $quest_data['Ticket']['media'];
                            $channels_arr['is_favourite'] = ($quest_data['Ticket']['is_favourite'] == 'Y') ? "YES" : "NO";
                            $channels_arr['type'] = $quest_data['Ticket']['type'];
                            $channels_arr['status'] = $quest_data['Ticket']['status'];
                            $channels_arr['date'] = date("d M y H:i", strtotime($quest_data['Ticket']['created']));
                            $channels_arr['created_by'] = $this->Custom->hide_number($quest_data['CreatedBy']['mobile']);
                            $channels_arr['is_app_owner'] = $is_app_owner;


                            $admin_id = $this->Custom->getThinAppAdminId($thin_app_id);
                            if ($quest_data['Ticket']['created_by_user_id'] == $user_id || $admin_id == $user_id) {
                                $channels_arr['is_owner'] = "YES";
                            } else {
                                $channels_arr['is_owner'] = "NO";
                            }
                            array_push($channel_array, $channels_arr);
                        }
                        $response['status'] = 1;
                        $response['message'] = "Complain list found";
                        $response['data']['ticket_list'] = $channel_array;

                    } else {
                        $response['status'] = 0;
                        $response['message'] = "There is no complain";
                    }
                } else {
                    $response['status'] = 0;
                    $response['message'] = $this->Custom->getResponseMessage($result);
                }
            } else {
                $response['status'] = 0;
                $response['message'] = "Invalid request parameter";
            }

            echo json_encode($response);
            exit;
        }
    }


    public function get_ticket_detail()
    {
        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        if ($this->request->is('post')) {

            $app_key = $data['app_key'];
            $user_id = $data['user_id'];
            $thin_app_id = $data['thin_app_id'];
            $ticket_id = $data['ticket_id'];

            $response = array();
            if ($user_id != '' && $app_key != '' && $thin_app_id != '' && $ticket_id != '') {
                if (($result = $this->Custom->CheckIsValidApp($app_key, $thin_app_id)) && ($result == 1)) {

                    $ticket_data = $this->Ticket->find('first', array(
                        'conditions' => array(
                            'Ticket.app_id' => $thin_app_id,
                            'Ticket.id' => $ticket_id,
                        ),
                        'contain' => array('CreatedBy'),
                        'order' => array("Ticket.id" => "DESC"),
                        'fields' => array('CreatedBy.mobile', 'Ticket.*')
                    ));
                    if (!empty($ticket_data)) {
                        $channels_arr['id'] = $ticket_data['Ticket']['id'];
                        $channels_arr['title'] = $ticket_data['Ticket']['title'];
                        $channels_arr['description'] = $ticket_data['Ticket']['description'];
                        $channels_arr['image'] = $ticket_data['Ticket']['media'];
                        $channels_arr['is_favourite'] = ($ticket_data['Ticket']['is_favourite'] == 'Y') ? "YES" : "NO";
                        $channels_arr['type'] = $ticket_data['Ticket']['type'];
                        $channels_arr['status'] = $ticket_data['Ticket']['status'];
                        $channels_arr['latitude'] = $ticket_data['Ticket']['latitude'];
                        $channels_arr['longitude'] = $ticket_data['Ticket']['longitude'];
                        $channels_arr['created'] = $this->Custom->timeElapsedString(($ticket_data['Ticket']['created'])) . " ago";

                        $response['status'] = 1;
                        $response['message'] = "Ticket detial found";
                        $response['data']['ticket_detail'] = $channels_arr;
                    } else {
                        $response['status'] = 0;
                        $response['message'] = "There is no ticket";
                    }
                } else {
                    $response['status'] = 0;
                    $response['message'] = $this->Custom->getResponseMessage($result);
                }
            } else {
                $response['status'] = 0;
                $response['message'] = "Invalid request parameter";
            }

            echo json_encode($response);
            exit;
        }
    }


    public function update_ticket_status()
    {
        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        if ($this->request->is('post')) {

            $app_key = $data['app_key'];
            $user_id = $data['user_id'];
            $thin_app_id = $data['thin_app_id'];
            $ticket_id = $data['ticket_id'];
            $status = $data['status'];
            $comment = $data['comment'];
            $media_path = $data['media_path'];


            $response = array();
            if ($user_id != '' && $app_key != '' && $thin_app_id != '' && $ticket_id != '' && $status != '') {
                if (($result = $this->Custom->CheckIsValidApp($app_key, $thin_app_id)) && ($result == 1)) {

                    $ticket_data = $this->Ticket->find('first', array(
                        'conditions' => array(
                            'Ticket.app_id' => $thin_app_id,
                            'Ticket.id' => $ticket_id,
                        ),
                        'contain' => false,
                        'fields' => array('Ticket.created_by_user_id', 'Ticket.description')
                    ));


                    if (!empty($ticket_data)) {

                        $reply_status = $this->Ticket->updateAll(array(
                            'Ticket.status' => "'" . $status . "'"),
                            array('Ticket.id' => $ticket_id)
                        );
                        $this->TicketComment->create();
                        $this->TicketComment->set('ticket_id', $ticket_id);
                        $this->TicketComment->set('ticket_status', $status);
                        $this->TicketComment->set('app_admin_id', $user_id);
                        $this->TicketComment->set('comment', $comment);
                        $this->TicketComment->set('media_path', $media_path);

                        if ($reply_status && $this->TicketComment->save()) {
                            $message = "Ticket " . ucfirst($status) . " - " . $ticket_data['Ticket']['description'];
                            if (!empty($comment)) {
                                $message = "Ticket " . ucfirst($status) . " - " . $comment;
                            }
                            $option = array(
                                'thinapp_id' => $thin_app_id,
                                'quest_id' => $ticket_id,
                                'user_id' => $ticket_data['Ticket']['created_by_user_id'],
                                'data' => array(
                                    'thinapp_id' => $thin_app_id,
                                    'channel_id' => 0,
                                    'flag' => 'TICKET',
                                    'title' => "Ticket Update",
                                    'message' => mb_strimwidth($message, 0, 50, '...'),
                                    'description' => "",
                                    'chat_reference' => '',
                                    'module_type' => 'TICKET',
                                    'module_type_id' => $ticket_id,
                                    'firebase_reference' => ""
                                )
                            );
                            $this->Custom->send_notification_to_device($option);
                            $response['status'] = 1;
                            $response['message'] = "Status update successfully";

                        } else {
                            $response['status'] = 0;
                            $response['message'] = "Sorry status could not update";
                        }

                    } else {
                        $response['status'] = 0;
                        $response['message'] = "There is no ticket";
                    }
                } else {
                    $response['status'] = 0;
                    $response['message'] = $this->Custom->getResponseMessage($result);
                }
            } else {
                $response['status'] = 0;
                $response['message'] = "Invalid request parameter";
            }

            echo json_encode($response);
            exit;
        }
    }


    public function delete_ticket()
    {
        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        if ($this->request->is('post')) {

            $app_key = $data['app_key'];
            $user_id = $data['user_id'];
            $thin_app_id = $data['thin_app_id'];
            $ticket_id = $data['ticket_id'];
            $response = array();
            if ($user_id != '' && $app_key != '' && $thin_app_id != '' && $ticket_id != '') {
                if (($result = $this->Custom->CheckIsValidApp($app_key, $thin_app_id)) && ($result == 1)) {

                    $ticket_data = $this->Ticket->find('first', array(
                        'conditions' => array(
                            'Ticket.app_id' => $thin_app_id,
                            'Ticket.id' => $ticket_id,
                        ),
                        'contain' => false,
                        'fields' => array('Ticket.created_by_user_id')
                    ));
                    if (!empty($ticket_data)) {
                        $del_ticket = $this->Ticket->delete(array('Ticket.id' => $ticket_id));
                        $ql = $this->TicketComment->deleteAll(array('TicketComment.ticket_id' => $ticket_id));
                        if ($del_ticket) {
                            /*$message = "Ticket ".ucfirst($status)." - ".$ticket_data['Ticket']['description'];*/
                            /* $option = array(
                                'thinapp_id'=>$thin_app_id,
                                'quest_id'=>$ticket_id,
                                'user_id'=>$ticket_data['Ticket']['user_id'],
                                'data'=>array(
                                    'thinapp_id'=>$thin_app_id,
                                    'channel_id'=>0,
                                    'flag'=>'TICKET',
                                    'title'=> "Ticket Update",
                                    'message' => mb_strimwidth($message, 0, 50, '...'),
                                    'description' => "",
                                    'chat_reference'=>'',
                                    'module_type'=>'TICKET',
                                    'module_type_id'=>$ticket_id,
                                    'firebase_reference'=>""
                                )
                            );
                            $this->Custom->send_notification_to_device($option);*/
                            $response['status'] = 1;
                            $response['message'] = "Ticket delete successfully";

                        } else {
                            $response['status'] = 0;
                            $response['message'] = "Sorry ticket could not delete";

                        }

                    } else {
                        $response['status'] = 0;
                        $response['message'] = "There is no ticket";
                    }
                } else {
                    $response['status'] = 0;
                    $response['message'] = $this->Custom->getResponseMessage($result);
                }
            } else {
                $response['status'] = 0;
                $response['message'] = "Invalid request parameter";
            }

            echo json_encode($response);
            exit;
        }
    }



    /* function add by mahendra*/

    /* api created by mahendra */

    public function get_lose_found_count()
    {

        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        if ($this->request->is('post')) {
            $app_key = $data['app_key'];
            $user_id = $data['user_id'];
            $thin_app_id = $data['thin_app_id'];
            $response = array();
            if ($user_id != '' && $app_key != '' && $thin_app_id != '') {
                if (($result = $this->Custom->CheckIsValidApp($app_key, $thin_app_id)) && ($result == 1)) {
                    $response['status'] = 1;
                    $response['message'] = "Total count found";
                    $lose_condition["LoseObject.thinapp_id"] = $thin_app_id;
                    $lose_condition["LoseObject.status"] = 'Y';
                    $condition = "thinapp_id = " . $thin_app_id . " AND status ='Y'";
                    $query = "SELECT lost_status,COUNT(*) as cnt FROM   lose_objects WHERE " . $condition . " GROUP  BY lose_objects.lost_status ORDER  BY lose_objects.lost_status ASC";
                    //$lost_count = $this->LoseObject->query($query);
                    $lost_count = $this->LoseObject->query($query, array('contain' => false));

                    $response['data']['lost_count'] = (isset($lost_count[0][0]['cnt'])) ? $lost_count[0][0]['cnt'] : 0;
                    $response['data']['found_count'] = (isset($lost_count[1][0]['cnt'])) ? $lost_count[1][0]['cnt'] : 0;
                    $response['data']['complete_count'] = (isset($lost_count[2][0]['cnt'])) ? $lost_count[2][0]['cnt'] : 0;

                } else {
                    $response['status'] = 0;
                    $response['message'] = $this->Custom->getResponseMessage($result);
                }
            } else {
                $response['status'] = 0;
                $response['message'] = "Invalid request parameter";
            }

            echo json_encode($response);
            exit;
        }
    }


    public function add_lose_object()
    {
        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        if ($this->request->is('post')) {
            $response = array();
            $thin_app_id = $data['thin_app_id'];
            $app_key = $data['app_key'];
            $user_id = $data['user_id'];
            $title = $data['title'];
            $description = $data['description'];
            $image_url = $data['image_url'];
            $lost_from_address = $data['lost_from_address'];
            $contact = $data['contact'];
            $reward_price = (isset($data['reward_price']) && !empty($data['reward_price'])) ? $data['reward_price'] : 0;
            $date_time = $data['date_time'];
            $shared_on = $data['share_on'];
            $channel_id = $data['channel_id'];
            $type = $data['type'];

            if ($user_id != '' && $title != '' && $description != '' && $contact != '') {
                if (($result = $this->Custom->CheckIsValidApp($app_key, $thin_app_id)) && ($result == 1)) {
                     $total_sms = Custom::get_total_sms_thinapp($thin_app_id,'T');
                    $total_sub = $this->Custom->totalSmsSubscriber($channel_id, $thin_app_id);
                    if (($total_sms >= $total_sub && $shared_on == 'CHANNEL') || $shared_on == 'FACTORY') {
                        $datasource = $this->LoseObject->getDataSource();
                        try {

                            $date_time = date('Y-m-d H:i:s', strtotime($date_time));
                            $current_datetime = date('Y-m-d H:i:s');
                            if (strtotime($date_time) > strtotime($current_datetime)) {
                                $date_time = $current_datetime;
                            }

                            $datasource->begin();
                            $this->LoseObject->create();
                            $this->LoseObject->set('user_id', $user_id);
                            $this->LoseObject->set('thinapp_id', $thin_app_id);
                            $this->LoseObject->set('title', $title);
                            $this->LoseObject->set('description', $description);
                            $this->LoseObject->set('image_url', $image_url);
                            $this->LoseObject->set('lost_from_address', $lost_from_address);
                            $this->LoseObject->set('contact', $contact);
                            $this->LoseObject->set('reward_price', $reward_price);
                            $this->LoseObject->set('date_time', $date_time);
                            $this->LoseObject->set('lost_status', $type);
                            if ($shared_on == "CHANNEL") {
                                $this->LoseObject->set('shared_on', 'CHANNEL');
                            }
                            if ($this->LoseObject->save()) {
                                if ($shared_on == "CHANNEL") {
                                    $lose_object_id = $this->LoseObject->getLastInsertId();
                                    $this->ChannelMessage->create();
                                    $this->ChannelMessage->set('message_id', $lose_object_id);
                                    $this->ChannelMessage->set('channel_id', $channel_id);
                                    $this->ChannelMessage->set('post_type_status', 'LOSS_FOUND');
                                    $this->MessageStatic->create();
                                    $this->MessageStatic->set('message_id', $lose_object_id);
                                    $this->MessageStatic->set('list_message_type', 'LOSS_FOUND');
                                    if ($this->ChannelMessage->save() && $this->MessageStatic->save()) {
                                        $datasource->commit();
                                        /* send notification to channel subscriber*/
                                        $app_name = "Mbroadcast";
                                        $message = $description;
                                        $sendArray = array(
                                            'thinapp_id' => $thin_app_id,
                                            'channel_id' => $channel_id,
                                            'lose_object_id' => $lose_object_id,
                                            'flag' => 'LOSS_FOUND',
                                            'title' => $title,
                                            'message' => mb_strimwidth($type . " - " . $message, 0, 50, '...'),
                                            'description' => mb_strimwidth($type . " - " . $description, 0, 100, '...'),
                                            'chat_reference' => '',
                                            'module_type' => 'LOSS_FOUND',
                                            'module_type_id' => $lose_object_id,
                                            'firebase_reference' => ""
                                        );
                                        $this->Custom->send_topic_notification($sendArray);

                                        /* ADD MESSAGE CODE FOR UNREGISTER USERS*/

                                        $user_role = $this->Custom->get_user_role_id($user_id);
                                        $is_permission = $this->Custom->check_user_permission($thin_app_id, 'LOST_FOUND_SEND_NOTIFICATION_VIA_SMS');
                                        if ($user_role == 5 || $is_permission == "YES") {
                                            $this->Custom->sendBulkSms($channel_id, $thin_app_id, $message, $lose_object_id, $user_id);
                                        }
                                        $response['status'] = 1;
                                        $response['message'] = "Request add successfully";

                                    } else {
                                        $datasource->rollback();
                                        $response['status'] = 0;
                                        $response['message'] = "Sorry request could not post";
                                    }

                                } else {

                                    $datasource->commit();
                                    $response['status'] = 1;
                                    $response['message'] = "Request add successfully";

                                }
                            } else {

                                $response['status'] = 0;
                                $response['message'] = "Sorry request could not post";
                            }


                        } catch (Exception $e) {
                            $datasource->rollback();
                            $response['status'] = 0;
                            $response['message'] = "Sorry message could not add";
                        }
                    } else {
                        $response['status'] = 0;
                        $response['message'] = "System error found. Please try later";
                    }

                } else {
                    $response['status'] = 0;
                    $response['message'] = $this->Custom->getResponseMessage($result);
                }
            } else {
                $response['status'] = 0;
                $response['message'] = "Invalid request parameter";
            }
            echo json_encode($response);
            exit;
        }
    }


    /* update by mahendra */
    public function get_lose_object_list()
    {

        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        if ($this->request->is('post')) {
            $app_key = $data['app_key'];
            $user_id = $data['user_id'];
            $thin_app_id = $data['thin_app_id'];
            $type = $data['type'];
            $search_title = $data['search_title'];

            $response = array();
            if ($user_id != '' && $app_key != '' && $thin_app_id != '') {
                if (($result = $this->Custom->CheckIsValidApp($app_key, $thin_app_id)) && ($result == 1)) {
                    $condition["LoseObject.thinapp_id"] = $thin_app_id;
                    $condition["LoseObject.status"] = 'Y';
                    $condition["LoseObject.title like"] = '%' . $search_title . '%';
                    if (!empty($type) && $type != 'ALL') {
                        $condition["LoseObject.lost_status"] = strtoupper($type);

                    }
                    $channel = $this->LoseObject->find('all', array(
                        'conditions' => $condition,
                        'contain' => array('User'),
                        'fields' => array('LoseObject.*', 'User.firebase_token', 'User.mobile'),
                        'order' => array("LoseObject.id" => "DESC")
                    ));

                    $channel_array = array();
                    if (!empty($channel)) {
                        foreach ($channel as $key => $channel_data) {
                            $channels_arr['id'] = $channel_data['LoseObject']['id'];
                            $channels_arr['user_id'] = $channel_data['LoseObject']['user_id'];
                            $channels_arr['title'] = $channel_data['LoseObject']['title'];
                            $channels_arr['description'] = mb_strimwidth($channel_data['LoseObject']['description'], 0, 80, '...');
                            $channels_arr['image_url'] = $channel_data['LoseObject']['image_url'];
                            $channels_arr['lost_from_address'] = $channel_data['LoseObject']['lost_from_address'];
                            $channels_arr['lost_status'] = ucfirst(strtolower($channel_data['LoseObject']['lost_status']));
                            $channels_arr['is_owner'] = ($channel_data['LoseObject']['user_id'] == $user_id) ? "YES" : "NO";
                            $channels_arr['firebase_token'] = $channel_data['User']['firebase_token'];
                            $channels_arr['created_by'] = $channel_data['User']['mobile'];
                            // $channels_arr['contact'] = $channel_data['LoseObject']['contact'];
                            // $channels_arr['reward_price'] = $channel_data['LoseObject']['reward_price'];
                            $channels_arr['date_time'] = $channel_data['LoseObject']['date_time'];
                            $channels_arr['created'] = $this->Custom->timeElapsedString(($channel_data['LoseObject']['created'])) . " ago";
                            array_push($channel_array, $channels_arr);
                        }
                        $response['status'] = 1;
                        $response['message'] = "Khoya paya list found";
                        $response['data']['lose_found'] = $channel_array;
                    } else {
                        $response['status'] = 0;
                        $response['message'] = "List not found";
                    }
                } else {
                    $response['status'] = 0;
                    $response['message'] = $this->Custom->getResponseMessage($result);
                }
            } else {
                $response['status'] = 0;
                $response['message'] = "Invalid request parameter";
            }

            echo json_encode($response);
            exit;
        }
    }

    /* update by mahendra */
    public function get_lose_found_detail()
    {

        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        if ($this->request->is('post')) {
            $app_key = $data['app_key'];
            $lose_object_id = $data['lose_object_id'];
            $user_id = $data['user_id'];
            $thin_app_id = $data['thin_app_id'];
            $response = array();
            if ($user_id != '' && $app_key != '' && $lose_object_id != '') {
                if (($result = $this->Custom->CheckIsValidApp($app_key, $thin_app_id)) && ($result == 1)) {
                    $lose_object = $this->LoseObject->find('first', array(
                        'conditions' => array(
                            'LoseObject.id' => $lose_object_id
                        ),
                        'contain' => array('User'),
                        'fields' => array('LoseObject.*', 'User.firebase_token', 'User.mobile')
                    ));

                    if (!empty($lose_object)) {

                        $channels_arr['id'] = $lose_object['LoseObject']['id'];
                        $channels_arr['user_id'] = $lose_object['LoseObject']['user_id'];
                        $channels_arr['title'] = $lose_object['LoseObject']['title'];
                        $channels_arr['description'] = $lose_object['LoseObject']['description'];
                        $channels_arr['image_url'] = $lose_object['LoseObject']['image_url'];
                        $channels_arr['lost_from_address'] = $lose_object['LoseObject']['lost_from_address'];
                        $channels_arr['contact'] = $lose_object['LoseObject']['contact'];
                        $channels_arr['reward_price'] = $lose_object['LoseObject']['reward_price'];
                        $channels_arr['date_time'] = $this->Custom->timeElapsedString(($lose_object['LoseObject']['date_time'])) . " ago";
                        $channels_arr['created'] = $this->Custom->timeElapsedString(($lose_object['LoseObject']['created'])) . " ago";
                        $channels_arr['lost_status'] = $lose_object['LoseObject']['lost_status'];
                        $channels_arr['object_founder_mobile'] = $lose_object['LoseObject']['object_founder_mobile'];
                        $channels_arr['object_founder_email'] = $lose_object['LoseObject']['object_founder_email'];
                        $channels_arr['object_founder_address'] = $lose_object['LoseObject']['object_founder_address'];
                        $channels_arr['firebase_token'] = $lose_object['User']['firebase_token'];
                        $channels_arr['created_by'] = $lose_object['User']['mobile'];
                        $channels_arr['is_owner'] = ($lose_object['LoseObject']['user_id'] == $user_id) ? "YES" : "NO";
                        $response['status'] = 1;
                        $response['message'] = "Lose detail found";
                        $response['data']['lose_detail'] = $channels_arr;

                    } else {
                        $response['status'] = 0;
                        $response['message'] = "Sorry you have no lose detail!";
                    }
                } else {
                    $response['status'] = 0;
                    $response['message'] = $this->Custom->getResponseMessage($result);
                }
            } else {
                $response['status'] = 0;
                $response['message'] = "Invalid request parameter";
            }

            echo json_encode($response);
            exit;
        }
    }


    public function delete_lose_object()
    {
        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        if ($this->request->is('post')) {
            $app_key = $data['app_key'];
            $user_id = $data['user_id'];
            $thin_app_id = $data['thin_app_id'];
            $lose_found_id = $data['lose_found_id'];
            //$type = $data['type'];

            $response = array();
            if ($user_id != '' && $app_key != '' && $thin_app_id != '' && $lose_found_id != '') {
                if (($result = $this->Custom->CheckIsValidApp($app_key, $thin_app_id)) && ($result == 1)) {
                    $quest = $this->LoseObject->find('first', array(
                        'conditions' => array(
                            'LoseObject.id' => $lose_found_id,
                            'LoseObject.thinapp_id' => $thin_app_id,
                            'LoseObject.status' => 'Y'
                        ),
                        'contain' => false
                    ));
                    if ($quest) {
                        $datasource = $this->LoseObject->getDataSource();
                        try {
                            $datasource->begin();
                            $q = $this->LoseObject->delete(array('LoseObject.id' => $lose_found_id));
                            $ql = $this->LostFoundComment->deleteAll(array('LostFoundComment.lose_object_id' => $lose_found_id));
                            $cm = $this->ChannelMessage->deleteAll(array(
                                'ChannelMessage.message_id' => $lose_found_id,
                                'ChannelMessage.post_type_status' => 'LOSS_FOUND'

                            ));
                            $cm = $this->MessageAction->deleteAll(array(
                                'MessageAction.message_id' => $lose_found_id,
                                'MessageAction.list_message_type' => 'LOSS_FOUND'
                            ));
                            $cm = $this->MessageStatic->deleteAll(array(
                                'MessageStatic.message_id' => $lose_found_id,
                                'MessageStatic.list_message_type' => 'LOSS_FOUND'
                            ));

                            if ($q) {
                                $datasource->commit();
                                $response['status'] = 1;
                                $response['message'] = "Record deleted successfully";
                            } else {
                                $response['status'] = 0;
                                $response['message'] = "Sorry unable to delete";
                            }


                        } catch (Exception $e) {
                            $datasource->rollback();
                        }


                    } else {
                        $response['status'] = 0;
                        $response['message'] = "Sorry record  not found";
                    }


                } else {
                    $response['status'] = 0;
                    $response['message'] = $this->Custom->getResponseMessage($result);
                }
            } else {
                $response['status'] = 0;
                $response['message'] = "Invalid request parameter";
            }

            echo json_encode($response);
            exit;
        }
    }

    public function get_lose_found_comments()
    {

        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        if ($this->request->is('post')) {
            $app_key = $data['app_key'];
            $lose_object_id = $data['lose_object_id'];
            $user_id = $data['user_id'];
            $thin_app_id = $data['thin_app_id'];
            $response = array();
            if ($user_id != '' && $app_key != '' && $lose_object_id != '') {
                if (($result = $this->Custom->CheckIsValidApp($app_key, $thin_app_id)) && ($result == 1)) {

                    $lose_object_list = $this->LostFoundComment->find('all', array(
                        'conditions' => array(
                            'LostFoundComment.lose_object_id' => $lose_object_id
                        ),
                        'contain' => array('User'),
                        'fields' => array('LostFoundComment.*', 'User.mobile'),
                    ));

                    $channel_array = array();
                    if (!empty($lose_object_list)) {
                        foreach ($lose_object_list as $key => $lose_object) {
                            $channels_arr['id'] = $lose_object['LostFoundComment']['id'];
                            $channels_arr['created_by'] = $lose_object['User']['mobile'];
                            $channels_arr['comment'] = $lose_object['LostFoundComment']['comment'];
                            $channels_arr['is_owner'] = ($lose_object['LostFoundComment']['user_id'] == $user_id) ? "YES" : "NO";
                            $channels_arr['time'] = $this->Custom->timeElapsedString(($lose_object['LostFoundComment']['created']));
                            array_push($channel_array, $channels_arr);
                        }
                        $response['status'] = 1;
                        $response['message'] = "Commnet list found";
                        $response['data']['comments'] = $channel_array;
                    } else {
                        $response['status'] = 0;
                        $response['message'] = "There is no comment";
                    }
                } else {
                    $response['status'] = 0;
                    $response['message'] = $this->Custom->getResponseMessage($result);
                }
            } else {
                $response['status'] = 0;
                $response['message'] = "Invalid request parameter";
            }

            echo json_encode($response);
            exit;
        }
    }

    public function add_lose_found_comment()
    {
        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        if ($this->request->is('post')) {
            $response = array();
            $thin_app_id = $data['thin_app_id'];
            $app_key = $data['app_key'];
            $user_id = $data['user_id'];
            $lose_object_id = $data['lose_object_id'];
            $comment = $data['comment'];
            if ($user_id != '' && $lose_object_id != '' && $comment != '') {
                if (($result = $this->Custom->CheckIsValidApp($app_key, $thin_app_id)) && ($result == 1)) {
                    $this->LostFoundComment->create();
                    $this->LostFoundComment->set('lose_object_id', $lose_object_id);
                    $this->LostFoundComment->set('thinapp_id', $thin_app_id);
                    $this->LostFoundComment->set('user_id', $user_id);
                    $this->LostFoundComment->set('comment', $comment);
                    if ($this->LostFoundComment->save()) {

                        $lose_object_list = $this->LoseObject->find('first', array(
                            'conditions' => array(
                                'LoseObject.id' => $lose_object_id
                            ),
                            'contain' => false,
                            'fields' => array('LoseObject.user_id', 'LoseObject.lost_status'),
                        ));

                        $option = array(
                            'thinapp_id' => $thin_app_id,
                            'quest_id' => $lose_object_id,
                            'user_id' => $lose_object_list['LoseObject']['user_id'],
                            'data' => array(
                                'thinapp_id' => $thin_app_id,
                                'channel_id' => 0,
                                'flag' => 'LOSS_FOUND',
                                'title' => "LOSS & FOUND Comment",
                                'message' => mb_strimwidth($lose_object_list['LoseObject']['lost_status'] . " Comment - " . $comment, 0, 50, '...'),
                                'description' => "",
                                'chat_reference' => '',
                                'module_type' => 'LOSS_FOUND',
                                'module_type_id' => $lose_object_id,
                                'firebase_reference' => ""
                            )
                        );
                        $this->Custom->send_notification_to_device($option);


                        $response['status'] = 1;
                        $response['message'] = "Comment add successfully";
                    } else {
                        $response['status'] = 0;
                        $response['message'] = "Sorry comment could not add";
                    }
                } else {
                    $response['status'] = 0;
                    $response['message'] = $this->Custom->getResponseMessage($result);
                }
            } else {
                $response['status'] = 0;
                $response['message'] = "Invalid request parameter";
            }
            echo json_encode($response);
            exit;
        }
    }

    public function object_found()
    {
        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        if ($this->request->is('post')) {

            $response = array();
            $thin_app_id = $data['thin_app_id'];
            $app_key = $data['app_key'];
            $user_id = $data['user_id'];
            $object_id = $data['object_id'];
            $founder_address = $data['founder_address'];
            $founder_mobile = $data['founder_mobile'];
            $founder_email = $data['founder_email'];

            if ($user_id != '' && $object_id != '' && $founder_mobile != '' && $app_key != '') {
                if (($result = $this->Custom->CheckIsValidApp($app_key, $thin_app_id)) && ($result == 1)) {
                    $lose_object = $this->LoseObject->find('first', array(
                        'conditions' => array("LoseObject.id" => $object_id),
                        'contain' => false,

                    ));
                    if ((!empty($lose_object))) {
                        if ($lose_object['LoseObject']['lost_status'] != 'COMPLETED') {
                            $datasource = $this->LoseObject->getDataSource();
                            try {

                                $datasource->begin();
                                $this->LoseObject->id = $object_id;
                                $this->LoseObject->set('lost_status', 'COMPLETED');
                                $this->LoseObject->set('object_founder_mobile', $founder_mobile);
                                $this->LoseObject->set('object_founder_email', $founder_email);
                                $this->LoseObject->set('object_founder_address', $founder_address);
                                $this->LoseObject->set('object_founder_id', $user_id);
                                if ($this->LoseObject->save()) {
                                    $datasource->commit();
                                    $response['status'] = 1;
                                    $response['message'] = "Request update successfully";
                                    $message = "Your " . $lose_object['LoseObject']['user_id'] . " found";
                                    $option = array(
                                        'thinapp_id' => $thin_app_id,
                                        'user_id' => $lose_object['LoseObject']['user_id'],
                                        'data' => array(
                                            'title' => 'LOSE & FOUND',
                                            'message' => $message,
                                            'flag' => 'LOSSFOUND'
                                        )
                                    );
                                    $this->Custom->send_notification_to_device($option);

                                } else {
                                    $response['status'] = 0;
                                    $response['message'] = "Sorry request could not update";
                                }

                            } catch (Exception $e) {
                                $datasource->rollback();
                                $response['status'] = 0;
                                $response['message'] = "Sorry request could not update";
                            }
                        } else {
                            $response['status'] = 0;
                            $response['message'] = "Request already completed";
                        }


                    } else {
                        $response['status'] = 0;
                        $response['message'] = "Sorry record not found";
                    }

                } else {
                    $response['status'] = 0;
                    $response['message'] = $this->Custom->getResponseMessage($result);
                }
            } else {
                $response['status'] = 0;
                $response['message'] = "Invalid request parameter";
            }
            echo json_encode($response);
            exit;
        }
    }

    public function delete_lose_found_comment()
    {
        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        if ($this->request->is('post')) {
            $app_key = $data['app_key'];
            $user_id = $data['user_id'];
            $thin_app_id = $data['thin_app_id'];
            $comment_id = $data['comment_id'];
            //$type = $data['type'];
            $response = array();
            if ($user_id != '' && $app_key != '' && $thin_app_id != '' && $comment_id != '') {
                if (($result = $this->Custom->CheckIsValidApp($app_key, $thin_app_id)) && ($result == 1)) {
                    $comment = $this->LostFoundComment->deleteAll(array('LostFoundComment.id' => $comment_id));
                    if ($comment) {
                        $response['status'] = 1;
                        $response['message'] = "Comment deleted successfullyl";
                    } else {
                        $response['status'] = 0;
                        $response['message'] = "Sorry comment could not deleted";
                    }
                } else {
                    $response['status'] = 0;
                    $response['message'] = $this->Custom->getResponseMessage($result);
                }
            } else {
                $response['status'] = 0;
                $response['message'] = "Invalid request parameter";
            }
            echo json_encode($response);
            exit;
        }
    }


    /* update by mahendra */
    public function show_facebook_page($thin_app_id = null)
    {
        $this->layout = false;
        $this->autoRender = true;
        $thin_app_data = $this->Thinapp->find('first', array(
            "conditions" => array(
                "Thinapp.id" => $thin_app_id,
            ),
            'fields' => array("Thinapp.facebook_url"),
            'contain' => false,

        ));
        if (!empty($thin_app_data)) {
            $url = $thin_app_data['Thinapp']['facebook_url'];
            $this->set(compact('url'));
        }
    }


    /* update by mahendra */
    public function getAppStatus()
    {
        $this->autoRender = false;
        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        if ($this->request->is('post')) {
            $thin_app_id = $data['thin_app_id'];
            $app_key = $data['app_key'];
            if ($app_key != '' && $thin_app_id != '') {


                $statusArr = $this->Thinapp->find('first', array(
                    "conditions" => array(
                        "Thinapp.id" => $thin_app_id
                    ),
                    'fields' => array("Thinapp.status"),
                    'contain' => false
                ));
                if (!empty($statusArr)) {
                    if ($statusArr['Thinapp']['status'] == 'ACTIVE') {
                        $response['status'] = 1;
                        $response['data']['status'] = $statusArr['Thinapp']['status'];
                        $response['message'] = "App is active";

                    } else {
                        $response['status'] = 0;
                        $response['data']['status'] = $statusArr['Thinapp']['status'];
                        $response['message'] = "Your app is inactive";
                    }
                } else {
                    $response['status'] = 0;
                    $response['message'] = "App is not found";
                }
            } else {
                $response['status'] = 0;
                $response['message'] = "Invalid request parameter";
            }
            echo json_encode($response);
            exit;
        }
    }

    /* event goes to here*/
    /* update by mahednra*/


    public function check_app_version()
    {
        $this->autoRender = false;
        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        if ($this->request->is('post')) {
            $thin_app_id = $data['thin_app_id'];
            $app_key = $data['app_key'];
            $version_name = $data['version_name'];
            if ($app_key != '' && $thin_app_id != '') {
                $statusArr = $this->Thinapp->find('first', array(
                    "conditions" => array(
                        "Thinapp.id" => $thin_app_id
                    ),
                    'fields' => array("Thinapp.version_name"),
                    'contain' => false
                ));
                if (!empty($statusArr)) {
                    if ($statusArr['Thinapp']['version_name'] == $version_name) {
                        $response['status'] = 1;
                        $response['data']['status'] = 1;
                        $response['message'] = "App is up to date";
                    } else {
                        $response['status'] = 1;
                        $response['data']['status'] = 0;
                        $response['message'] = "Your app is not updated";
                    }
                } else {
                    $response['status'] = 0;
                    $response['data']['status'] = 0;
                    $response['message'] = "Invalid app id";
                }
            } else {
                $response['status'] = 0;
                $response['message'] = "Invalid request parameter";
            }
            echo json_encode($response);
            exit;
        }
    }


    public function get_event_category_list()
    {

        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        if ($this->request->is('post')) {
            $app_key = $data['app_key'];
            $user_id = $data['user_id'];
            $thin_app_id = $data['thin_app_id'];
            $response = array();
            if ($user_id != '' && $app_key != '' && $thin_app_id != '') {
                if (($result = $this->Custom->CheckIsValidApp($app_key, $thin_app_id)) && ($result == 1)) {
                    $category_list = $this->EventCategory->find('all', array(
                        'conditions' => array(
                            'EventCategory.status' => 'ACTIVE'
                        ),
                        'contain' => false,
                        'fields' => array('EventCategory.id', 'EventCategory.title'),
                        'order' => array('EventCategory.title' => 'asc')
                    ));
                    if (!empty($category_list)) {
                        $response['status'] = 1;
                        $response['message'] = "Category list found";
                        $cat_array = array();
                        foreach ($category_list as $key => $cat) {
                            $cat_array[$key]['category_id'] = $cat['EventCategory']['id'];
                            $cat_array[$key]['category_title'] = $cat['EventCategory']['title'];
                        }
                        $response['data']['event_category'] = $cat_array;
                    } else {
                        $response['status'] = 0;
                        $response['message'] = "Category list not found";
                    }
                } else {
                    $response['status'] = 0;
                    $response['message'] = $this->Custom->getResponseMessage($result);
                }
            } else {
                $response['status'] = 0;
                $response['message'] = "Invalid request parameter";
            }

            echo json_encode($response);
            exit;
        }
    }

    /* update by mahendra */
    public function get_channel_for_event()
    {
        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        if ($this->request->is('post')) {
            $app_key = $data['app_key'];
            $user_id = $data['user_id'];
            $thin_app_id = $data['thin_app_id'];
            $response = array();
            if ($user_id != '' && $app_key != '' && $thin_app_id != '') {
                if (($result = $this->Custom->CheckIsValidApp($app_key, $thin_app_id)) && ($result == 1)) {

                    $mbroadcast_app_id = MBROADCAST_APP_ID;
                    if ($thin_app_id == $mbroadcast_app_id) {
                        $channel = $this->Channel->find('all', array(
                            'conditions' => array(
                                'OR' => array(
                                    'Channel.user_id' => $user_id,
                                    //'Channel.channel_status'=>'PUBLIC',
                                ),
                                'Channel.app_id' => $thin_app_id,
                                'Channel.status' => 'Y'
                            ),
                            'contain' => false,
                            'fields' => array('Channel.id', 'Channel.channel_name')
                        ));
                    } else {

                        $channel = $this->Channel->find('all', array(
                            'conditions' => array(
                                'Channel.user_id' => $user_id,
                                'Channel.app_id' => $thin_app_id,
                                'Channel.status' => 'Y'
                            ),
                            'contain' => false,
                            'fields' => array('Channel.id', 'Channel.channel_name')
                        ));

                    }

                    $channel_array = array();
                    if (!empty($channel)) {
                        foreach ($channel as $key => $channel_data) {
                            $channels_arr['channel_id'] = $channel_data['Channel']['id'];
                            $channels_arr['channel_name'] = $channel_data['Channel']['channel_name'];
                            array_push($channel_array, $channels_arr);
                        }
                        $response['status'] = 1;
                        $response['message'] = "Channels list found";
                        $response['data']['channels'] = $channel_array;
                    } else {
                        $response['status'] = 0;
                        $response['message'] = "Sorry you have no channel!";
                    }
                } else {
                    $response['status'] = 0;
                    $response['message'] = $this->Custom->getResponseMessage($result);
                }
            } else {
                $response['status'] = 0;
                $response['message'] = "Invalid request parameter";
            }

            echo json_encode($response);
            exit;
        }
    }

    public function add_event()
    {
        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        if ($this->request->is('post')) {
            $response = array();
            $thin_app_id = $data['thin_app_id'];
            $app_key = $data['app_key'];
            $user_id = $data['user_id'];
            $event_category_id = $data['event_category_id'];
            $title = $data['title'];
            $description = $data['description'];
            $tags = $data['tags'];
            $address = $data['address'];
            $venue = $data['venue'];
            $latitude = $data['latitude'];
            $longitude = $data['longitude'];
            $contact_phone = $data['contact_phone'];
            $start_datetime = $data['start_datetime'];
            $end_datetime = $data['end_datetime'];
            $share_on = $data['share_on'];
            $channel_id = $data['channel_id'];
            $show_on_mbroadcast = $data['show_on_mbroadcast'];
            $cover_image = $data['cover_image'];
            $media_array = array();//$data['media_array'];

            if ($user_id != '' && $thin_app_id != '' && $description != '' && $title != '') {
                if (($result = $this->Custom->CheckIsValidApp($app_key, $thin_app_id)) && ($result == 1)) {
                     $total_sms = Custom::get_total_sms_thinapp($thin_app_id,'T');
                    $total_sub = $this->Custom->totalSmsSubscriber($channel_id, $thin_app_id);
                    if (($total_sms >= $total_sub && $share_on == 'CHANNEL') || $share_on == 'EVENT_FACTORY') {
                        $datasource = $this->Event->getDataSource();
                        try {

                            $start_datetime = date('Y-m-d H:i:s', strtotime($start_datetime));
                            $end_datetime = date('Y-m-d H:i:s', strtotime($end_datetime));
                            $current_datetime = date('Y-m-d H:i:s');

                            if (strtotime($start_datetime) < strtotime($current_datetime)) {
                                $response['status'] = 0;
                                $response['message'] = "Start time must be upcoming time";
                            } else if (strtotime($end_datetime) < strtotime($start_datetime)) {
                                $response['status'] = 0;
                                $response['message'] = "End time must be grater than start time";
                            } else {

                                $datasource->begin();
                                $this->Event->create();
                                $this->Event->set('user_id', $user_id);
                                $this->Event->set('thinapp_id', $thin_app_id);
                                $this->Event->set('tags', $tags);
                                $this->Event->set('title', $title);
                                $this->Event->set('description', $description);
                                $this->Event->set('address', $address);
                                $this->Event->set('venue', $venue);
                                $this->Event->set('latitude', $latitude);
                                $this->Event->set('longitude', $longitude);
                                $this->Event->set('contact_phone', $contact_phone);
                                $this->Event->set('event_category_id', $event_category_id);
                                $this->Event->set('start_datetime', $start_datetime);
                                $this->Event->set('end_datetime', $end_datetime);
                                $this->Event->set('show_on_mbroadcast', $show_on_mbroadcast);
                                $this->Event->set('status', "ACTIVE");
                                $this->Event->set('publish_status', "PUBLISHED");
                                if ($share_on == "CHANNEL") {
                                    $this->Event->set('share_on', 'CHANNEL');
                                    $this->Event->set('channel_id', $channel_id);
                                }
                                if ($this->Event->save()) {
                                    $event_id = $this->Event->getLastInsertId();
                                    /*add cover image to event*/
                                    if (empty($cover_image)) {
                                        $cover_image = DEFAULT_COVER_IMAGE;
                                    }
                                    $this->EventMedia->create();
                                    $this->EventMedia->set('media_path', $cover_image);
                                    $this->EventMedia->set('media_type', 'IMAGE');
                                    $this->EventMedia->set('event_id', $event_id);
                                    $this->EventMedia->set('is_cover_image', 'YES');

                                    $save_cover = $this->EventMedia->save();
                                    $save_media = true;
                                    if (!empty($media_array)) {
                                        $save_media = array();
                                        $counter = 0;
                                        /* this code add oter media to event*/
                                        foreach ($media_array as $key => $value) {
                                            $save_media[$counter]['event_id'] = $event_id;
                                            $save_media[$counter]['media_path'] = $value["filename"];
                                            $save_media[$counter++]['media_type'] = "IMAGE";
                                        }
                                        $save_media = $this->EventMedia->saveAll($save_media);
                                    }

                                    if ($save_cover && $save_media) {
                                        if ($share_on == "CHANNEL") {
                                            $this->ChannelMessage->create();
                                            $this->ChannelMessage->set('message_id', $event_id);
                                            $this->ChannelMessage->set('channel_id', $channel_id);
                                            $this->ChannelMessage->set('post_type_status', 'EVENT');
                                            $this->MessageStatic->create();
                                            $this->MessageStatic->set('message_id', $event_id);
                                            $this->MessageStatic->set('list_message_type', 'EVENT');
                                            if ($this->ChannelMessage->save() && $this->MessageStatic->save()) {
                                                $datasource->commit();
                                                $response['status'] = 1;
                                                $response['message'] = "Event add successfully";
                                                /* send notification to channel subscriber*/

                                                $message = $description;
                                                $sendArray = array(
                                                    'thinapp_id' => $thin_app_id,
                                                    'channel_id' => $channel_id,
                                                    'event_id' => $event_id,
                                                    'flag' => 'NEW_EVENT',
                                                    'title' => mb_strimwidth("Event - " . $title, 0, 150, '...'),
                                                    'message' => mb_strimwidth("Event - " . $title, 0, 80, '...'),
                                                    'description' => mb_strimwidth($description, 0, 100, '...'),
                                                    'chat_reference' => '',
                                                    'module_type' => 'EVENT',
                                                    'module_type_id' => $event_id,
                                                    'firebase_reference' => ""
                                                );
                                                $this->Custom->send_topic_notification($sendArray);
                                                /* ADD MESSAGE CODE FOR UNREGISTER USERS*/

                                                $user_role = $this->Custom->get_user_role_id($user_id);
                                                $is_permission = $this->Custom->check_user_permission($thin_app_id, 'EVENT_SEND_NOTIFICATION_VIA_SMS');
                                                if ($user_role == 5 || $is_permission == "YES") {
                                                    $message = $description;
                                                    $this->Custom->sendBulkSms($channel_id, $thin_app_id, $message, $event_id, $user_id);
                                                }


                                            } else {
                                                $datasource->rollback();
                                                $response['status'] = 0;
                                                $response['message'] = "Sorry event could not post";
                                            }
                                        } else {
                                            $datasource->commit();
                                            $response['status'] = 1;
                                            $response['message'] = "Event add successfully";
                                        }
                                    } else {
                                        $datasource->rollback();
                                        $response['status'] = 0;
                                        $response['message'] = "Sorry event could not post";
                                    }

                                } else {
                                    $response['status'] = 0;
                                    $response['message'] = "Sorry request could not post";
                                }

                            }


                        } catch (Exception $e) {
                            $datasource->rollback();
                            $response['status'] = 0;
                            $response['message'] = "Sorry message could not add";
                        }
                    } else {
                        $response['status'] = 0;
                        $response['message'] = "System error found. Please try later";
                    }

                } else {
                    $response['status'] = 0;
                    $response['message'] = $this->Custom->getResponseMessage($result);
                }
            } else {
                $response['status'] = 0;
                $response['message'] = "Invalid request parameter";
            }
            echo json_encode($response);
            exit;
        }
    }


    /* created by mahendra */
    public function get_event_list()
    {
        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        if ($this->request->is('post')) {

            $app_key = $data['app_key'];
            $user_id = $data['user_id'];
            $thin_app_id = $data['thin_app_id'];
            $category_id = $data['category_id'];
            $trending = $data['trending'];
            $recommended = $data['recommended'];
            $today = $data['today'];
            $tomorrow = $data['tomorrow'];
            $upcoming = $data['upcoming'];

            $offset = $data['offset'];
            $limit = APP_PAGINATION_LIMIT;

            $response = array();
            if ($tomorrow != '' && $user_id != '' && $app_key != '' && $thin_app_id != '' && $trending != '' && $recommended != '' && $today != '') {
                if (($result = $this->Custom->CheckIsValidApp($app_key, $thin_app_id)) && ($result == 1)) {

                    $mbroadcast_app_id = MBROADCAST_APP_ID;
                    $condition = array();
                    if ($category_id > 0) {
                        $condition['Event.event_category_id'] = $category_id;
                    }
                    if ($trending == "YES") {
                        /* MOST POPULAR EVENT*/
                        $event_ids = $this->Event->find('list', array(
                            'conditions' => array(
                                'Event.response_count >' => 0,
                                'Event.thinapp_id' => $thin_app_id,
                                'Event.status' => 'ACTIVE'
                            ),
                            'contain' => false,
                            'fields' => array('Event.id'),
                            'offset' => $offset * $limit,
                            'limit' => $limit
                        ));

                        if (!empty($event_ids)) {
                            if (count($event_ids) == 1) {
                                $condition['Event.id'] = $event_ids;
                            } else {
                                $condition['Event.id IN'] = $event_ids;
                            }
                        } else {
                            $condition['Event.id'] = 0;
                        }

                    }
                    if ($recommended == "YES") {
                        /* USER LIKED EVENTS*/
                        $event_ids = $this->MessageAction->find('list', array(
                            'conditions' => array(
                                'MessageAction.action_by' => $user_id,
                                'MessageAction.list_message_type' => 'EVENT'
                            ),
                            'contain' => false,
                            'fields' => array('MessageAction.message_id'),
                            'offset' => $offset * $limit,
                            'limit' => $limit
                        ));
                        if (!empty($event_ids)) {
                            if (count($event_ids) == 1) {
                                $condition['Event.id'] = $event_ids;
                            } else {
                                $condition['Event.id IN'] = $event_ids;
                            }
                        } else {
                            $condition['Event.id'] = 0;
                        }
                    }

                    if ($upcoming == "YES") {
                        $condition['Event.start_datetime >'] = date('Y-m-d H:i:s');
                    }

                    if ($today == "YES") {
                        $condition['DATE(Event.start_datetime)'] = date('Y-m-d');
                    }
                    if ($tomorrow == "YES") {
                        $condition['DATE(Event.start_datetime)'] = date('Y-m-d', strtotime(date('Y-m-d') . ' +1 day'));
                    }


                    if ($trending == "YES") {
                        $order_by['order'] = array("Event.response_count" => "DESC");
                    } else {
                        $order_by['order'] = array("Event.id" => "DESC");
                    }


                    if ($thin_app_id == $mbroadcast_app_id) {

                        $event = $this->Event->find('all', array(
                            'conditions' => array(
                                'OR' => array(
                                    array(
                                        'Event.thinapp_id' => $thin_app_id,
                                        'Event.status' => 'ACTIVE',
                                        'Event.publish_status' => 'PUBLISHED',
                                        $condition
                                    ),
                                    array(
                                        'Event.show_on_mbroadcast' => 'YES',
                                        'Event.mbroadcast_publish_status' => 'APPROVED',
                                        'Event.status' => 'ACTIVE',
                                        'Event.publish_status' => 'PUBLISHED',
                                        $condition
                                    )
                                )
                            ),

                            'contain' => array('CoverImage'),
                            $order_by,
                            'fields' => array('CoverImage.media_path', 'Event.user_id', 'Event.id', 'Event.title', 'Event.description', 'Event.venue', 'Event.start_datetime', 'Event.end_datetime'),
                            'offset' => $offset * $limit,
                            'limit' => $limit

                        ));

                    } else {
                        $event = $this->Event->find('all', array(
                            'conditions' => array(
                                'Event.thinapp_id' => $thin_app_id,
                                'Event.status' => 'ACTIVE',
                                'Event.publish_status' => 'PUBLISHED',
                                $condition
                            ),
                            'contain' => array('CoverImage'),
                            $order_by,
                            'fields' => array('CoverImage.media_path', 'Event.user_id', 'Event.id', 'Event.title', 'Event.description', 'Event.venue', 'Event.start_datetime', 'Event.end_datetime'),
                            'offset' => $offset * $limit,
                            'limit' => $limit

                        ));
                    }

                    //pr($event);die;
                    $channel_array = array();

                    if (!empty($event)) {
                        foreach ($event as $key => $event_data) {
                            $channels_arr['id'] = $event_data['Event']['id'];
                            $channels_arr['cover_image'] = $event_data['CoverImage']['media_path'];
                            $channels_arr['title'] = $event_data['Event']['title'];
                            $channels_arr['description'] = mb_strimwidth($event_data['Event']['description'], 0, 80, '...');
                            $channels_arr['venue'] = $event_data['Event']['venue'];
                            $channels_arr['from_date_time'] = date("H:i", strtotime($event_data['Event']['start_datetime']));
                            $channels_arr['to_date_time'] = date("H:i", strtotime($event_data['Event']['end_datetime']));
                            $channels_arr['is_owner'] = ($event_data['Event']['user_id'] == $user_id) ? "YES" : "NO";
                            $channels_arr['is_like'] = !empty($this->Custom->check_messasge_liked($event_data['Event']['id'], $user_id, 'EVENT')) ? "YES" : "NO";
                            $day = date('d', strtotime($event_data['Event']['start_datetime']));
                            $month = date('M', strtotime($event_data['Event']['start_datetime']));
                            $channels_arr['start_date_month'] = $day . "##" . $month;
                            $channels_arr['people_interested'] = $this->Custom->total_people_intrested($event_data['Event']['id']);
                            array_push($channel_array, $channels_arr);
                        }
                        $response['status'] = 1;
                        $response['message'] = "Event list found";
                        $response['data']['event_list'] = $channel_array;
                        $response['data']['response_question'] = "Would you like to participate this event ?";
                        $response['data']['response_options'] = implode(",", $this->Custom->get_event_enum_values());
                    } else {
                        $response['status'] = 0;
                        $response['message'] = "There is no event";
                    }
                } else {
                    $response['status'] = 0;
                    $response['message'] = $this->Custom->getResponseMessage($result);
                }
            } else {
                $response['status'] = 0;
                $response['message'] = "Invalid request parameter";
            }

            echo json_encode($response);
            exit;
        }
    }


    /* created by mahendra */
    public function delete_event()
    {
        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        if ($this->request->is('post')) {
            $app_key = $data['app_key'];
            $user_id = $data['user_id'];
            $thin_app_id = $data['thin_app_id'];
            $event_id = $data['event_id'];

            $response = array();
            if ($user_id != '' && $app_key != '' && $thin_app_id != '' && $event_id != '') {
                if (($result = $this->Custom->CheckIsValidApp($app_key, $thin_app_id)) && ($result == 1)) {
                    $event = $this->Event->find('first', array(
                        'conditions' => array(
                            'Event.id' => $event_id,
                            'Event.thinapp_id' => $thin_app_id,
                            'Event.status' => 'ACTIVE'
                        ),
                        'contain' => false,
                        'fields' => array('Event.share_on', 'Event.channel_id')
                    ));
                    if ($event) {
                        $datasource = $this->Event->getDataSource();
                        try {
                            $datasource->begin();

                            $q = $this->Event->delete(array('Event.id' => $event_id));
                            $ql = $this->EventAgenda->deleteAll(array('EventAgenda.event_id' => $event_id));
                            $qr = $this->EventMedia->deleteAll(array('EventMedia.event_id' => $event_id));
                            $qrt = $this->EventOrganizer->deleteAll(array('EventOrganizer.event_id' => $event_id));
                            $qrt = $this->EventPost->deleteAll(array('EventPost.event_id' => $event_id));
                            $qrt = $this->EventResponse->deleteAll(array('EventResponse.event_id' => $event_id));
                            $qrt = $this->EventShow->deleteAll(array('EventShow.event_id' => $event_id));
                            $qrt = $this->EventSpeaker->deleteAll(array('EventSpeaker.event_id' => $event_id));
                            $qrt = $this->EventTicket->deleteAll(array('EventTicket.event_id' => $event_id));
                            $qrt = $this->EventTicketSell->deleteAll(array('EventTicketSell.event_id' => $event_id));

                            $cm = $this->ChannelMessage->deleteAll(array(
                                'ChannelMessage.message_id' => $event_id,
                                'ChannelMessage.post_type_status' => 'EVENT'
                            ));
                            $cm = $this->MessageAction->deleteAll(array(
                                'MessageAction.message_id' => $event_id,
                                'MessageAction.list_message_type' => 'EVENT'
                            ));
                            $cm = $this->MessageStatic->deleteAll(array(
                                'MessageStatic.message_id' => $event_id,
                                'MessageStatic.list_message_type' => 'EVENT'
                            ));

                            if ($q) {
                                $datasource->commit();
                                $response['status'] = 1;
                                $response['message'] = "Your event deleted successfully";
                            } else {
                                $response['status'] = 0;
                                $response['message'] = "Sorry unable to delete event";
                            }


                        } catch (Exception $e) {
                            $datasource->rollback();
                        }


                    } else {
                        $response['status'] = 0;
                        $response['message'] = "Sorry quest  not found";
                    }


                } else {
                    $response['status'] = 0;
                    $response['message'] = $this->Custom->getResponseMessage($result);
                }
            } else {
                $response['status'] = 0;
                $response['message'] = "Invalid request parameter";
            }

            echo json_encode($response);
            exit;
        }
    }


    /* created by mahendra */
    public function share_event()
    {
        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        if ($this->request->is('post')) {
            $response = array();
            $thin_app_id = $data['thin_app_id'];
            $app_key = $data['app_key'];
            $user_id = $data['user_id'];
            $event_id = $data['event_id'];
            $share_on = $data['share_on'];
            if ($user_id != '' && $thin_app_id != '' && $event_id != '') {
                if (($result = $this->Custom->CheckIsValidApp($app_key, $thin_app_id)) && ($result == 1)) {
                    $this->EventResponse->create();
                    $this->EventResponse->set('user_id', $user_id);
                    $this->EventResponse->set('response_type', 'SHARE');
                    $this->EventResponse->set('event_id', $event_id);
                    $this->EventResponse->set('share_on', $share_on);
                    if ($this->EventResponse->save()) {
                        $response['status'] = 1;
                        $response['message'] = "Participate response add successfully";
                    } else {
                        $response['status'] = 0;
                        $response['message'] = "Sorry participate response could not add";
                    }
                } else {
                    $response['status'] = 0;
                    $response['message'] = $this->Custom->getResponseMessage($result);
                }
            } else {
                $response['status'] = 0;
                $response['message'] = "Invalid request parameter";
            }
            echo json_encode($response);
            exit;
        }
    }

    /* created by mahendra */
    public function submit_event_response()
    {
        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        if ($this->request->is('post')) {
            $response = array();
            $thin_app_id = $data['thin_app_id'];
            $app_key = $data['app_key'];
            $user_id = $data['user_id'];
            $event_id = $data['event_id'];
            $response_type = strtoupper($data['response_type']);
            if ($user_id != '' && $thin_app_id != '' && $event_id != '' && $response_type != '') {
                if (($result = $this->Custom->CheckIsValidApp($app_key, $thin_app_id)) && ($result == 1)) {

                    if ($response_type != 'SHARE') {
                        $event_res = $this->EventResponse->find('first', array(
                            'conditions' => array(
                                'EventResponse.user_id' => $user_id,
                                'EventResponse.event_id' => $event_id
                            ),
                            'contain' => false,

                            'fields' => array('EventResponse.id', 'EventResponse.user_id')
                        ));

                        if (empty($event_res)) {
                            $this->EventResponse->create();
                            $this->EventResponse->set('user_id', $user_id);
                            $this->EventResponse->set('response_type', $response_type);
                            $this->EventResponse->set('event_id', $event_id);
                            if ($response_type != "NO") {
                                $response_count = $this->Event->updateAll(array(
                                    'Event.response_count' => 'Event.response_count + 1',
                                    'Event.participates_count' => 'Event.participates_count + 1',
                                ),
                                    array('Event.id' => $event_id)
                                );
                            } else {
                                $response_count = $this->Event->updateAll(array(
                                    'Event.response_count' => 'Event.response_count + 1'
                                ),
                                    array('Event.id' => $event_id)
                                );
                            }
                            if ($this->EventResponse->save()) {
                                $response['status'] = 1;
                                $response['message'] = "Thanks for your interest";
                            } else {
                                $response['status'] = 0;
                                $response['message'] = "Sorry your response could not save";
                            }
                        } else {
                            $this->EventResponse->id = $event_res['EventResponse']['id'];
                            $this->EventResponse->set('response_type', $response_type);
                            if ($response_type != "NO") {
                                $response_count = $this->Event->updateAll(array(
                                    'Event.participates_count' => 'Event.participates_count + 1',
                                ),
                                    array('Event.id' => $event_id)
                                );
                            }

                            if ($this->EventResponse->save()) {
                                $response['status'] = 1;
                                $response['message'] = "Your interest updated successfully";
                            } else {
                                $response['status'] = 0;
                                $response['message'] = "Sorry your interest could not update";
                            }
                        }
                    } else {
                        $response['status'] = 0;
                        $response['message'] = "Invalid response type parameter";
                    }

                } else {
                    $response['status'] = 0;
                    $response['message'] = $this->Custom->getResponseMessage($result);
                }
            } else {
                $response['status'] = 0;
                $response['message'] = "Invalid request parameter";
            }
            echo json_encode($response);
            exit;
        }
    }

    /* created by mahendra */
    public function event_detail()
    {
        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        if ($this->request->is('post')) {
            $app_key = $data['app_key'];
            $user_id = $data['user_id'];
            $thin_app_id = $data['thin_app_id'];
            $event_id = $data['event_id'];

            $response = array();
            if ($user_id != '' && $app_key != '' && $thin_app_id != '' && $event_id) {
                if (($result = $this->Custom->CheckIsValidApp($app_key, $thin_app_id)) && ($result == 1)) {
                    $event_data = $this->Event->find('first', array(
                        'conditions' => array(
                            'Event.thinapp_id' => $thin_app_id,
                            'Event.status' => 'ACTIVE',
                            'Event.id' => $event_id
                        ),

                        'contain' => array('CoverImage', 'EventAgenda', 'EventSpeaker', 'EventOrganizer'),
                        //'fields'=>array('CoverImage.media_path','Event.id','Event.title','Event.description','Event.venue','Event.start_datetime','Event.end_datetime')
                    ));
                    //   pr($event_data);die;
                    $event_arr = array();
                    if (!empty($event_data)) {
                        $event_arr['id'] = $event_data['Event']['id'];
                        $event_arr['cover_image'] = $event_data['CoverImage']['media_path'];
                        $event_arr['title'] = $event_data['Event']['title'];
                        $event_arr['short_description'] = mb_strimwidth($event_data['Event']['description'], 0, 50, '...');
                        $event_arr['description'] = $event_data['Event']['description'];
                        $event_arr['venue'] = $event_data['Event']['venue'];
                        $event_arr['address'] = $event_data['Event']['address'];
                        $event_arr['latitude'] = $event_data['Event']['latitude'];
                        $event_arr['longitude'] = $event_data['Event']['longitude'];

                        /* agenda start form here*/
                        $event_arr['enable_agenda'] = $event_data['Event']['enable_agenda'];
                        $event_arr['agenda_count'] = count($event_data['EventAgenda']);
                        $event_arr['agenda_list'] = array();
                        if ($event_data['Event']['enable_agenda'] == 'YES') {
                            if (!empty($event_data['EventAgenda'])) {
                                foreach ($event_data['EventAgenda'] as $key => $list) {
                                    if ($list['status'] == "ACTIVE") {
                                        $event['title'] = $list['title'];
                                        $event['description'] = $list['description'];
                                        $event['start_datetime'] = date("d M H:i A", strtotime($list['start_datetime']));
                                        $event['end_datetime'] = date("d M H:i A", strtotime($list['end_datetime']));
                                        $event_arr['agenda_list'][] = $event;
                                    }
                                }
                            } else {
                                $key = 0;
                                $event_arr['agenda_list'][$key]['title'] = '';
                                $event_arr['agenda_list'][$key]['description'] = '';
                                $event_arr['agenda_list'][$key]['start_datetime'] = '';
                                $event_arr['agenda_list'][$key]['end_datetime'] = '';
                            }

                        } else {
                            $key = 0;
                            $event_arr['agenda_list'][$key]['title'] = '';
                            $event_arr['agenda_list'][$key]['description'] = '';
                            $event_arr['agenda_list'][$key]['start_datetime'] = '';
                            $event_arr['agenda_list'][$key]['end_datetime'] = '';
                        }

                        /* orgnizer start from here*/
                        $event_arr['enable_organizer'] = $event_data['Event']['enable_organizer'];
                        $event_arr['organizer_count'] = count($event_data['EventOrganizer']);
                        $event_arr['organizer_list'] = array();
                        if ($event_data['Event']['enable_organizer'] == 'YES') {
                            if (!empty($event_data['EventOrganizer'])) {
                                foreach ($event_data['EventOrganizer'] as $key => $list) {
                                    if ($list['status'] == "ACTIVE") {
                                        $ev['name'] = $list['name'];
                                        $ev['mobile'] = $list['mobile'];
                                        $ev['image'] = $list['image'];
                                        $event_arr['organizer_list'][] = $ev;
                                    }
                                }
                            } else {
                                $key = 0;
                                $event_arr['organizer_list'][$key]['name'] = '';
                                $event_arr['organizer_list'][$key]['mobile'] = '';
                                $event_arr['organizer_list'][$key]['image'] = '';
                            }
                        } else {
                            $key = 0;
                            $event_arr['organizer_list'][$key]['name'] = '';
                            $event_arr['organizer_list'][$key]['mobile'] = '';
                            $event_arr['organizer_list'][$key]['image'] = '';
                        }


                        /* speaker start from here*/
                        $event_arr['enable_speaker'] = $event_data['Event']['enable_speaker'];
                        $event_arr['speaker_count'] = count($event_data['EventSpeaker']);
                        $event_arr['speaker_list'] = array();
                        if ($event_data['Event']['enable_speaker'] == 'YES') {
                            if (!empty($event_data['EventSpeaker'])) {
                                foreach ($event_data['EventSpeaker'] as $key => $list) {
                                    if ($list['status'] == "ACTIVE") {
                                        $eve['name'] = $list['name'];
                                        $eve['mobile'] = $list['mobile'];
                                        $eve['image'] = $list['image'];
                                        $eve['designation'] = $list['designation'];
                                        $event_arr['speaker_list'][] = $eve;
                                    }
                                }
                            } else {
                                $key = 0;
                                $event_arr['speaker_list'][$key]['name'] = '';
                                $event_arr['speaker_list'][$key]['mobile'] = '';
                                $event_arr['speaker_list'][$key]['image'] = '';
                                $event_arr['speaker_list'][$key]['designation'] = '';
                            }
                        } else {
                            $key = 0;
                            $event_arr['speaker_list'][$key]['name'] = '';
                            $event_arr['speaker_list'][$key]['mobile'] = '';
                            $event_arr['speaker_list'][$key]['image'] = '';
                            $event_arr['speaker_list'][$key]['designation'] = '';
                        }

                        $event_arr['enable_show'] = $event_data['Event']['enable_show'];
                        $event_arr['enable_ticket'] = $event_data['Event']['enable_speaker'];
                        $event_arr['from_date_time'] = date("d M H:i A", strtotime($event_data['Event']['start_datetime']));
                        $event_arr['to_date_time'] = date("d M H:i A", strtotime($event_data['Event']['end_datetime']));
                        $event_arr['people_interested'] = $this->Custom->total_people_intrested($event_id);
                        $response['status'] = 1;
                        $response['message'] = "Event  found";
                        $response['data']['event_list'] = $event_arr;
                    } else {
                        $response['status'] = 0;
                        $response['message'] = "Sorry event not found";
                    }
                } else {
                    $response['status'] = 0;
                    $response['message'] = $this->Custom->getResponseMessage($result);
                }
            } else {
                $response['status'] = 0;
                $response['message'] = "Invalid request parameter";
            }

            echo json_encode($response);
            exit;
        }
    }

    public function add_event_post()
    {
        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        if ($this->request->is('post')) {
            $response = array();
            $thin_app_id = $data['thin_app_id'];
            $app_key = $data['app_key'];
            $user_id = $data['user_id'];
            $event_id = $data['event_id'];
            $message = $data['message'];
            if ($user_id != '' && $thin_app_id != '' && $event_id != '' && $message != '') {
                if (($result = $this->Custom->CheckIsValidApp($app_key, $thin_app_id)) && ($result == 1)) {


                    $this->EventPost->create();
                    $this->EventPost->set('user_id', $user_id);
                    $this->EventPost->set('message', $message);
                    $this->EventPost->set('event_id', $event_id);
                    if ($this->EventPost->save()) {


                        $event = $this->Event->find('first', array(
                            'conditions' => array(
                                'Event.id' => $event_id,
                            ),
                            'contain' => false,
                            'fields' => array('Event.title', 'Event.user_id', 'Event.channel_id')
                        ));
                        if (!empty($event)) {

                            $option = array(
                                'thinapp_id' => $thin_app_id,
                                'quest_id' => $event_id,
                                'user_id' => $event['Event']['user_id'],
                                'data' => array(
                                    'thinapp_id' => $thin_app_id,
                                    'channel_id' => $event['Event']['channel_id'],
                                    'event_id' => $event_id,
                                    'flag' => 'NEW_EVENT',
                                    'title' => "Event Post",
                                    'message' => mb_strimwidth($event['Event']['title'] . " - " . $message, 0, 50, '...'),
                                    'description' => "",
                                    'chat_reference' => '',
                                    'module_type' => 'EVENT',
                                    'module_type_id' => $event_id,
                                    'firebase_reference' => ""
                                )
                            );
                            $this->Custom->send_notification_to_device($option);

                        }

                        $response['status'] = 1;
                        $response['message'] = "Post  added successfully";
                    } else {
                        $response['status'] = 0;
                        $response['message'] = "Sorry post  could not add";
                    }
                } else {
                    $response['status'] = 0;
                    $response['message'] = $this->Custom->getResponseMessage($result);
                }
            } else {
                $response['status'] = 0;
                $response['message'] = "Invalid request parameter";
            }
            echo json_encode($response);
            exit;
        }
    }

    /* created by mahendra */
    public function get_event_post_list()
    {
        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        if ($this->request->is('post')) {
            $app_key = $data['app_key'];
            $user_id = $data['user_id'];
            $thin_app_id = $data['thin_app_id'];
            $event_id = $data['event_id'];
            $offset = $data['offset'];
            $limit = APP_PAGINATION_LIMIT;
            $response = array();
            if ($user_id != '' && $app_key != '' && $thin_app_id != '') {
                if (($result = $this->Custom->CheckIsValidApp($app_key, $thin_app_id)) && ($result == 1)) {
                    $post_list = $this->EventPost->find('all', array(
                        'conditions' => array(
                            'EventPost.status' => 'ACTIVE',
                            'EventPost.event_id' => $event_id
                        ),
                        'contain' => array('CreatedBy'),
                        'fields' => array('EventPost.message', 'EventPost.created', 'CreatedBy.mobile'),
                        'order' => array("EventPost.id" => "DESC"),
                        'offset' => $limit * $offset,
                        'limit' => $limit
                    ));

                    //pr($post_list);die;
                    $channel_array = array();
                    if (!empty($post_list)) {
                        foreach ($post_list as $key => $event_data) {
                            $channels_arr['date'] = date("d M H:i", strtotime($event_data['EventPost']['created']));
                            $channels_arr['message'] = $event_data['EventPost']['message'];
                            $channels_arr['post_by'] = $event_data['CreatedBy']['mobile'];
                            array_push($channel_array, $channels_arr);
                        }
                        $response['status'] = 1;
                        $response['message'] = "Event post list found";
                        $response['data']['event_post_list'] = $channel_array;
                    } else {
                        $response['status'] = 0;
                        $response['message'] = "There is no event post";
                    }
                } else {
                    $response['status'] = 0;
                    $response['message'] = $this->Custom->getResponseMessage($result);
                }
            } else {
                $response['status'] = 0;
                $response['message'] = "Invalid request parameter";
            }

            echo json_encode($response);
            exit;
        }
    }



    /* text confrence start from here*/
    /* this function created by mahendra */
    public function add_conference()
    {

        $request = file_get_contents("php://input");
        $data = json_decode($request, true);

        if ($this->request->is('post')) {
            $response = array();
            $user_id = $data['user_id'];
            $thin_app_id = $data['thin_app_id'];
            $app_key = $data['app_key'];
            $title = $data['title'];
            $description = $data['description'];
            $channel_id = $data['channel_id'];
            $start_datetime = $data['start_datetime'];
            $duration = strtoupper($data['duration']);
            $background =array();
            $type = $data['type'];
            if ($user_id != '' && $title != '' && $app_key != '' && $type != '' && $channel_id != '' && $start_datetime != '' && $duration != '') {
                if (($result = $this->Custom->CheckIsValidApp($app_key, $thin_app_id)) && ($result == 1)) {
                     $total_sms = Custom::get_total_sms_thinapp($thin_app_id,'T');
                    if ($total_sms > 0) {
                        $datasource = $this->Ticket->getDataSource();
                        try {
                            $datasource->begin();
                            $this->Conference->create();
                            $this->Conference->set('thinapp_id', $thin_app_id);
                            $this->Conference->set('user_id', $user_id);
                            $this->Conference->set('title', $title);
                            $this->Conference->set('description', $description);
                            $this->Conference->set('channel_id', $channel_id);
                            $this->Conference->set('type', $type);
                            $this->Conference->set('duration', $duration);
                            $start_datetime = date('Y-m-d H:i:s', strtotime($start_datetime));;
                            $this->Conference->set('start_datetime', $start_datetime);
                            $end_datetime = date('Y-m-d H:i:s', strtotime($start_datetime . "+" . $duration));
                            $this->Conference->set('end_datetime', $end_datetime);
                            if ($this->Conference->save()) {
                                $conference_id = $this->Conference->getLastInsertId();
                                $this->ChannelMessage->create();
                                $this->ChannelMessage->set('channel_id', $channel_id);
                                $this->ChannelMessage->set('message_id', $conference_id);
                                $this->ChannelMessage->set('post_type_status', 'CONFERENCE');
                                $this->MessageStatic->create();
                                $this->MessageStatic->set('message_id', $conference_id);
                                $this->MessageStatic->set('list_message_type', 'CONFERENCE');
                                if ($this->ChannelMessage->save() && $this->MessageStatic->save()) {
                                    $datasource->commit();
                                    $response['status'] = 1;
                                    $response['message'] = "Conference add successfully";

                                    $total_invitations = $this->Custom->total_subscribe($channel_id);
                                    $app_name = "MBROADCAST";
                                    /* send notification to channel subscriber*/
                                    $message = "You have received a new conference";

                                    $start_date = date("d M", strtotime($start_datetime));
                                    $end_date = date("d M", strtotime($end_datetime));

                                    $start_time = date("h:i", strtotime($start_datetime));
                                    $end_time = date("h:i", strtotime($end_datetime));
                                    if ($type == 'TEXT') {
                                        $module_type = "TEXT_CONFERENCE";
                                    } else {
                                        $module_type = "OTHER_CONFERENCE";
                                    }
                                    $sendArray = array(
                                        'thinapp_id' => $thin_app_id,
                                        'channel_id' => $channel_id,
                                        'conference_name' => mb_strimwidth($title, 0, 20, '...'),
                                        'description' => mb_strimwidth($description, 0, 50, '...'),
                                        'type' => $type,
                                        'conference_id' => $conference_id,
                                        'start_datetime' => $start_datetime,
                                        'start_endtime' => $end_datetime,
                                        'start_date' => $start_date,
                                        'end_date' => $end_date,
                                        'start_time' => $start_time,
                                        'end_time' => $end_time,
                                        'total_invitation' => $total_invitations,
                                        'flag' => 'NEW_CONFERENCE',
                                        'title' => $title,
                                        'message' => mb_strimwidth("Conference - " . $title, 0, 100, '...'),
                                        'description' => '',
                                        'chat_reference' => '',
                                        'module_type' => $module_type,
                                        'module_type_id' => $conference_id,
                                        'firebase_reference' => ""
                                    );
                                    $background['notification']['data'] =$sendArray;
                                    $background['notification']['channel_id'] =$channel_id;

                                    //Custom::send_topic_notification($sendArray,null,$thin_app_id);
                                    //$this->Custom->send_topic_notification($sendArray);
                                    $user_role = $this->Custom->get_user_role_id($user_id);
                                    $is_permission = $this->Custom->check_user_permission($thin_app_id, 'TEXT_CONFERENCE_SEND_NOTIFICATION_VIA_SMS');
                                    if ($user_role == 5 || $is_permission == "YES") {
                                        /* ADD MESSAGE CODE FOR UNREGISTER USERS*/
                                        if ($type == 'TEXT') {
                                            $background['sms']['channel_id'] =$channel_id;
                                            $background['sms']['conference_id'] =$conference_id;
                                            $background['sms']['user_id'] =$user_id;
                                            $background['sms']['title'] =$title;
                                            //$this->Custom->sendConferenceMessage($channel_id, $conference_id, $thin_app_id, $user_id, $title);
                                        }
                                   }

                                } else {
                                    $datasource->rollback();
                                    $response['status'] = 0;
                                    $response['message'] = "Sorry conference could not created";
                                }
                            } else {
                                $response['status'] = 0;
                                $response['message'] = "Sorry conference could not created";
                            }
                        } catch (Exception $e) {
                            $datasource->rollback();
                            $response['status'] = 0;
                            $response['message'] = "Sorry conference could not created";
                        }
                    } else {
                        $response['status'] = 0;
                        $response['message'] = "System error found. Please try later";
                    }
                } else {
                    $response['status'] = 0;
                    $response['message'] = $this->Custom->getResponseMessage($result);
                }
            } else {
                $response['status'] = 0;
                $response['message'] = "Invalid request parameter";
            }
            Custom::sendResponse($response);
            if (!empty($background)) {
                Custom::send_process_to_background();
                if (isset($background['notification'])) {
                    $option = $background['notification']['data'];
                    Custom::send_topic_notification($option, null,$thin_app_id);
                }
                if (isset($background['sms'])) {
                    $channel_id =$background['sms']['channel_id'];
                    $conference_id =$background['sms']['conference_id'];
                    $user_id =$background['sms']['user_id'];
                    $title =$background['sms']['title'];
                    $this->Custom->sendConferenceMessage($channel_id, $conference_id, $thin_app_id, $user_id, $title);
                }
            }
            exit;
        }
    }


    public function get_conference_list()
    {
        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        if ($this->request->is('post')) {
            $app_key = $data['app_key'];
            $user_id = $data['user_id'];
            $thin_app_id = $data['thin_app_id'];
            $response = array();
            if ($user_id != '' && $app_key != '') {
                if (($result = $this->Custom->CheckIsValidApp($app_key, $thin_app_id)) && ($result == 1)) {

                    $user_channel_id = $this->Subscriber->find('list', array(
                        'conditions' => array(
                            'Subscriber.app_user_id' => $user_id,
                            'Subscriber.status' => "SUBSCRIBED",
                            'Channel.status' => "Y"
                        ),
                        'contain' => array('Channel'),
                        'fields' => array('Channel.id'),
                        'group' => array('Channel.id')
                    ));
                    //$user_channel_id = array_values($user_channel_id);
                    //pr($user_channel_id);die;
                    $conference_list = $this->Conference->find('all', array(
                        'conditions' => array(
                            'Conference.channel_id' => $user_channel_id,
                            'Conference.thinapp_id' => $thin_app_id
                        ),
                        'contain' => false,
                        'order' => array('Conference.end_datetime' => 'DESC')
                    ));
                    $conference_array = array();
                    if (!empty($conference_list)) {
                        foreach ($conference_list as $key => $conference_data) {
                            $conference_arr['conference_id'] = $conference_data['Conference']['id'];
                            $conference_arr['title'] = $conference_data['Conference']['title'];
                            $conference_arr['conference_name'] = $conference_data['Conference']['title'];
                            $conference_arr['description'] = mb_strimwidth($conference_data['Conference']['description'], 0, 50, '...');
                            $conference_arr['type'] = $conference_data['Conference']['type'];
                            $start_date = date("d M", strtotime($conference_data['Conference']['start_datetime']));
                            $end_date = date("d M", strtotime($conference_data['Conference']['end_datetime']));
                            $start_time = date("H:i", strtotime($conference_data['Conference']['start_datetime']));
                            $end_time = date("H:i", strtotime($conference_data['Conference']['end_datetime']));
                            $conference_arr['start_date'] = $start_date;
                            $conference_arr['end_date'] = $end_date;
                            $conference_arr['start_time'] = $start_time;
                            $conference_arr['end_time'] = $end_time;
                            $conference_arr['start_datetime'] = $conference_data['Conference']['start_datetime'];
                            $conference_arr['end_datetime'] = $conference_data['Conference']['end_datetime'];
                            $conference_arr['total_invitation'] = $this->Custom->total_subscribe($conference_data['Conference']['channel_id']);;

                            array_push($conference_array, $conference_arr);
                        }
                        $response['status'] = 1;
                        $response['message'] = "Conference list found";
                        $response['data']['conference_list'] = $conference_array;
                    } else {
                        $response['status'] = 0;
                        $response['message'] = "Sorry you have no conference !";
                    }
                } else {
                    $response['status'] = 0;
                    $response['message'] = $this->Custom->getResponseMessage($result);
                }
            } else {
                $response['status'] = 0;
                $response['message'] = "Invalid request parameter";
            }

            echo json_encode($response);
            exit;
        }
    }

    public function get_conference_detail()
    {
        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        if ($this->request->is('post')) {
            $app_key = $data['app_key'];
            $user_id = $data['user_id'];
            $thin_app_id = $data['thin_app_id'];
            $conference_id = $data['conference_id'];

            $response = array();
            if ($user_id != '' && $app_key != '' && $thin_app_id != '' && $conference_id != '') {
                if (($result = $this->Custom->CheckIsValidApp($app_key, $thin_app_id)) && ($result == 1)) {

                    $conference_data = $this->Conference->find('first', array(
                        'conditions' => array(
                            'Conference.id' => $conference_id,
                            'Conference.thinapp_id' => $thin_app_id
                        ),
                        'contain' => false,
                        'order' => array('Conference.end_datetime' => 'DESC')
                    ));

                    if (!empty($conference_data)) {
                        $conference_arr['conference_id'] = $conference_data['Conference']['id'];
                        $conference_arr['title'] = $conference_data['Conference']['title'];
                        $conference_arr['conference_name'] = $conference_data['Conference']['title'];
                        $conference_arr['description'] = mb_strimwidth($conference_data['Conference']['description'], 0, 50, '...');
                        $conference_arr['type'] = $conference_data['Conference']['type'];
                        $start_date = date("d M", strtotime($conference_data['Conference']['start_datetime']));
                        $end_date = date("d M", strtotime($conference_data['Conference']['end_datetime']));
                        $start_time = date("H:i", strtotime($conference_data['Conference']['start_datetime']));
                        $end_time = date("H:i", strtotime($conference_data['Conference']['end_datetime']));
                        $conference_arr['start_date'] = $start_date;
                        $conference_arr['end_date'] = $end_date;
                        $conference_arr['start_time'] = $start_time;
                        $conference_arr['end_time'] = $end_time;
                        $conference_arr['start_datetime'] = $conference_data['Conference']['start_datetime'];
                        $conference_arr['end_datetime'] = $conference_data['Conference']['end_datetime'];
                        $conference_arr['minutes'] = $conference_data['Conference']['minutes'];
                        $conference_arr['is_owner'] = ($conference_data['Conference']['user_id'] == $user_id) ? "YES" : "NO";
                        $conference_arr['total_invitation'] = $this->Custom->total_subscribe($conference_data['Conference']['channel_id']);;

                        $response['status'] = 1;
                        $response['data']['conference_detail'] = $conference_arr;
                        $response['message'] = "Conference detail found";
                    } else {
                        $response['status'] = 0;
                        $response['message'] = "Sorry conference not found";
                    }
                } else {
                    $response['status'] = 0;
                    $response['message'] = $this->Custom->getResponseMessage($result);
                }
            } else {
                $response['status'] = 0;
                $response['message'] = "Invalid request parameter";
            }

            echo json_encode($response);
            exit;
        }
    }


    public function add_conference_minutes()
    {
        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        if ($this->request->is('post')) {
            $app_key = $data['app_key'];
            $user_id = $data['user_id'];
            $thin_app_id = $data['thin_app_id'];
            $conference_id = $data['conference_id'];
            $minutes = $data['minutes'];

            $response = array();
            if ($user_id != '' && $app_key != '' && $thin_app_id != '' && $conference_id != '') {
                if (($result = $this->Custom->CheckIsValidApp($app_key, $thin_app_id)) && ($result == 1)) {

                    $conference_data = $this->Conference->find('first', array(
                        'conditions' => array(
                            'Conference.id' => $conference_id,
                            'Conference.thinapp_id' => $thin_app_id
                        ),
                        'contain' => false
                    ));
                    if (!empty($conference_data)) {

                        $reply_status = $this->Conference->updateAll(array(
                            'Conference.minutes' => "'" . $minutes . "'"),
                            array('Conference.id' => $conference_id)
                        );
                        if ($reply_status) {
                            $conference_data = $conference_data['Conference'];

                            $start_date = date("d M", strtotime($conference_data['start_datetime']));
                            $end_date = date("d M", strtotime($conference_data['end_datetime']));
                            $start_time = date("H:i", strtotime($conference_data['start_datetime']));
                            $end_time = date("H:i", strtotime($conference_data['end_datetime']));

                            $sendArray = array(
                                'thinapp_id' => $thin_app_id,
                                'channel_id' => $conference_data['channel_id'],
                                'conference_name' => mb_strimwidth($conference_data['title'], 0, 20, '...'),
                                'description' => mb_strimwidth($conference_data['description'], 0, 50, '...'),
                                'type' => $conference_data['type'],
                                'conference_id' => $conference_id,
                                'start_datetime' => $conference_data['start_datetime'],
                                'start_endtime' => $conference_data['end_datetime'],
                                'start_date' => $start_date,
                                'end_date' => $end_date,
                                'start_time' => $start_time,
                                'end_time' => $end_time,
                                'total_invitation' => $this->Custom->total_subscribe($conference_data['channel_id']),
                                'flag' => 'NEW_CONFERENCE',
                                'title' => $conference_data['title'],
                                'message' => mb_strimwidth("Conference M.O.M - " . $conference_data['title'], 0, 100, '...'),
                                'description' => '',
                                'chat_reference' => '',
                                'module_type' => 'CONFERENCE',
                                'module_type_id' => $conference_id,
                                'firebase_reference' => ""
                            );
                            $this->Custom->send_topic_notification($sendArray);

                            $user_role = $this->Custom->get_user_role_id($user_id);
                            $is_permission = $this->Custom->check_user_permission($thin_app_id, 'TEXT_CONFERENCE_SEND_NOTIFICATION_VIA_SMS');
                            if ($user_role == 5 || $is_permission == "YES") {
                                /* ADD MESSAGE CODE FOR UNREGISTER USERS*/
                                if ($conference_data['type'] == 'TEXT') {
                                    $this->Custom->sendConferenceMessage($conference_data['channel_id'], $conference_id, $thin_app_id, $user_id, $conference_data['title']);
                                }

                            }

                            $response['status'] = 1;
                            $response['message'] = "Conference minutes add successfully";
                        } else {
                            $response['status'] = 1;
                            $response['message'] = "Sorry minutes could not add";
                        }

                    } else {
                        $response['status'] = 0;
                        $response['message'] = "Sorry conference not found";
                    }
                } else {
                    $response['status'] = 0;
                    $response['message'] = $this->Custom->getResponseMessage($result);
                }
            } else {
                $response['status'] = 0;
                $response['message'] = "Invalid request parameter";
            }

            echo json_encode($response);
            exit;
        }
    }


    public function get_channel_for_conference_list()
    {
        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        if ($this->request->is('post')) {
            $app_key = $data['app_key'];
            $user_id = $data['user_id'];
            $thin_app_id = $data['thin_app_id'];
            $response = array();
            if ($user_id != '' && $app_key != '') {
                if (($result = $this->Custom->CheckIsValidApp($app_key, $thin_app_id)) && ($result == 1)) {


                    $mbroadcast_app_id = MBROADCAST_APP_ID;
                    if ($thin_app_id == $mbroadcast_app_id) {
                        $channel = $this->Channel->find('all', array(
                            'conditions' => array(
                                'OR' => array(
                                    'Channel.user_id' => $user_id,
                                    //'Channel.channel_status'=>'PUBLIC',
                                ),
                                'Channel.app_id' => $thin_app_id,
                                'Channel.status' => 'Y'
                            ),
                            'contain' => false,
                            'fields' => array('Channel.id', 'Channel.channel_name')
                        ));
                    } else {

                        $channel = $this->Channel->find('all', array(
                            'conditions' => array(
                                'Channel.user_id' => $user_id,
                                'Channel.app_id' => $thin_app_id,
                                'Channel.status' => 'Y'
                            ),
                            'contain' => false,
                            'fields' => array('Channel.id', 'Channel.channel_name')
                        ));

                    }

                    $channel_array = array();
                    if (!empty($channel)) {
                        foreach ($channel as $key => $channel_data) {
                            $channels_arr['channel_id'] = $channel_data['Channel']['id'];
                            $channels_arr['channel_name'] = $channel_data['Channel']['channel_name'];
                            array_push($channel_array, $channels_arr);
                        }
                        $response['status'] = 1;
                        $response['message'] = "Channels list found";
                        $response['data']['channels'] = $channel_array;
                    } else {
                        $response['status'] = 0;
                        $response['message'] = "Sorry you have no channel!";
                    }
                } else {
                    $response['status'] = 0;
                    $response['message'] = $this->Custom->getResponseMessage($result);
                }
            } else {
                $response['status'] = 0;
                $response['message'] = "Invalid request parameter";
            }

            echo json_encode($response);
            exit;
        }
    }


    /**********QUEST START HERE**********/

    public function get_quest_category_and_channel_list()
    {

        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        if ($this->request->is('post')) {
            $app_key = $data['app_key'];
            $user_id = $data['user_id'];
            $thin_app_id = $data['thin_app_id'];
            $response = array();
            /*get quest category*/
            $category_list = $this->QuestCategory->find('all', array(
                'conditions' => array(
                    'QuestCategory.status' => 'ACTIVE'
                ),
                'contain' => false,
                'fields' => array('QuestCategory.id', 'QuestCategory.name')
            ));
            if (!empty($category_list)) {
                $response['status'] = 1;
                $response['message'] = "Category list found";
                $cat_array = array();
                foreach ($category_list as $key => $cat) {
                    $cat_array[$key]['category_id'] = $cat['QuestCategory']['id'];
                    $cat_array[$key]['category_title'] = $cat['QuestCategory']['name'];
                }
                $response['data']['quest']['quest_category_list'] = $cat_array;
                $channel = $this->Channel->find('all', array(
                    'conditions' => array(
                        'Channel.user_id' => $user_id,
                        'Channel.app_id' => $thin_app_id,
                        'Channel.status' => 'Y'
                    ),
                    'contain' => false,
                    'fields' => array('Channel.id', 'Channel.channel_name')
                ));
                $response['data']['quest']['channel_list'] = array();
                $channel_array = array();
                if (!empty($channel)) {
                    foreach ($channel as $key => $channel_data) {
                        $channels_arr['channel_id'] = $channel_data['Channel']['id'];
                        $channels_arr['channel_name'] = $channel_data['Channel']['channel_name'];
                        array_push($channel_array, $channels_arr);
                    }
                    $response['data']['quest']['channel_list'] = $channel_array;
                }

            } else {
                $response['status'] = 0;
                $response['message'] = "Category list not found";
            }

            /*get sale category*/
            $category_list = $this->SellItemCategory->find('all', array(
                'conditions' => array(
                    'SellItemCategory.status' => 'ACTIVE'
                ),
                'contain' => false,
                'fields' => array('SellItemCategory.id', 'SellItemCategory.name')
            ));

            if (!empty($category_list)) {
                $response['status'] = 1;
                $response['message'] = "Category list found";
                $cat_array = array();
                foreach ($category_list as $key => $cat) {
                    $cat_array[$key]['category_id'] = $cat['SellItemCategory']['id'];
                    $cat_array[$key]['category_title'] = $cat['SellItemCategory']['name'];
                }
                $response['data']['quest']['sell_category_list'] = $cat_array;

            } else {
                $response['status'] = 0;
                $response['message'] = "Category list not found";
            }


            echo json_encode($response);
            exit;
        }
    }

    public function add_quest()
    {
        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        if ($this->request->is('post')) {
            $response = array();
            $thin_app_id = $data['thin_app_id'];
            $app_key = $data['app_key'];
            $user_id = $data['user_id'];
            $quest_category_id = $data['quest_category_id'];
            $question = $data['question'];
            $image = $data['image'];
            $location = $data['location'];
            $description = $data['description'];
            $share_on = $data['share_on'];
            $channel_id = $data['channel_id'];
            $show_on_mbroadcast = $data['show_on_mbroadcast'];
            $enable_chat = $data['enable_chat'];
            $type = $data['type'];

            if ($user_id != '' && $thin_app_id != '' && $question != '' && $type != '') {

                if (($result = $this->Custom->CheckIsValidApp($app_key, $thin_app_id)) && ($result == 1)) {
                     $total_sms = Custom::get_total_sms_thinapp($thin_app_id,'T');
                    $total_sub = $this->Custom->totalSmsSubscriber($channel_id, $thin_app_id);
                    if (($total_sms >= $total_sub && $share_on == 'CHANNEL') || $share_on == 'QUEST_FACTORY') {
                        $datasource = $this->Quest->getDataSource();
                        try {
                            $datasource->begin();
                            $this->Quest->create();
                            $this->Quest->set('user_id', $user_id);
                            $this->Quest->set('thinapp_id', $thin_app_id);
                            $this->Quest->set('quest_category_id', $quest_category_id);
                            $this->Quest->set('question', $question);
                            $this->Quest->set('image', $image);
                            $this->Quest->set('location', $location);
                            $this->Quest->set('description', $description);
                            $this->Quest->set('share_on', $share_on);
                            $this->Quest->set('channel_id', $channel_id);
                            $this->Quest->set('show_on_mbroadcast', $show_on_mbroadcast);
                            $this->Quest->set('mbroadcast_publish_status', 'APPROVED');
                            $this->Quest->set('factory_publish_status', 'APPROVED');
                            $this->Quest->set('show_on_mbroadcast', $show_on_mbroadcast);
                            $this->Quest->set('enable_chat', $enable_chat);
                            $this->Quest->set('type', $type);
                            if ($this->Quest->save()) {
                                $questID = $this->Quest->getLastInsertId();
                                if ($share_on == "CHANNEL") {
                                    $this->ChannelMessage->create();
                                    $this->ChannelMessage->set('message_id', $questID);
                                    $this->ChannelMessage->set('channel_id', $channel_id);
                                    $this->ChannelMessage->set('post_type_status', $type);
                                    $this->MessageStatic->create();
                                    $this->MessageStatic->set('message_id', $questID);
                                    $this->MessageStatic->set('list_message_type', $type);
                                    if ($this->ChannelMessage->save() && $this->MessageStatic->save()) {
                                        $datasource->commit();
                                        $response['status'] = 1;
                                        $response['message'] = ucfirst($type) . " added successfully";
                                        /* send notification to channel subscriber*/
                                        $app_name = "Mbroadcast";
                                        $message = $question;
                                        $sendArray = array(
                                            'thinapp_id' => $thin_app_id,
                                            'channel_id' => $channel_id,
                                            'quest_id' => $questID,
                                            'flag' => 'NEW_QUEST',
                                            'title' => strtoupper($app_name),
                                            'message' => mb_strimwidth($type . " - " . $message, 0, 50, '...'),
                                            'description' => mb_strimwidth($description, 0, 100, '...'),
                                            'chat_reference' => '',
                                            'module_type' => $type,
                                            'module_type_id' => $questID,
                                            'firebase_reference' => ""
                                        );
                                        $this->Custom->send_topic_notification($sendArray);

                                        $user_role = $this->Custom->get_user_role_id($user_id);
                                        $is_permission = $this->Custom->check_user_permission($thin_app_id, 'QUEST_BUY_SELL_SEND_NOTIFICATION_VIA_SMS');
                                        if ($user_role == 5 || $is_permission == "YES") {
                                            /* ADD MESSAGE CODE FOR UNREGISTER USERS*/
                                            if ($type == "QUEST") {
                                                /* create new custom function for quet*/
                                                $this->Custom->sendQuestMessage($channel_id, $questID, $thin_app_id, $user_id);
                                            } else {
                                                $this->Custom->sendBulkSms($channel_id, $thin_app_id, $message, $questID, $user_id);
                                            }
                                        }


                                    } else {
                                        $datasource->rollback();
                                        $response['status'] = 0;
                                        $response['message'] = "Sorry," . ucfirst($type) . " could be not posted";
                                    }
                                } else {
                                    $datasource->commit();
                                    $response['status'] = 1;
                                    $response['message'] = ucfirst($type) . " added successfully";
                                }
                            } else {
                                $response['status'] = 0;
                                $response['message'] = "Sorry," . ucfirst($type) . " could be not posted";
                            }
                        } catch (Exception $e) {
                            $datasource->rollback();
                            $response['status'] = 0;
                            $response['message'] = "Sorry,Quest could be not posted";
                        }

                    } else {
                        $response['status'] = 0;
                        $response['message'] = "System error found. Please try later";
                    }

                } else {
                    $response['status'] = 0;
                    $response['message'] = $this->Custom->getResponseMessage($result);
                }
            } else {
                $response['status'] = 0;
                $response['message'] = "Invalid request parameter";
            }
            echo json_encode($response);
            exit;
        }
    }


    /* created by mahendra */
    public function get_quest_list()
    {
        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        if ($this->request->is('post')) {
            $app_key = $data['app_key'];
            $user_id = $data['user_id'];
            $thin_app_id = $data['thin_app_id'];
            $type = $data['type'];
            $filter_type = $data['filter_type'];
            $filter_parameter = $data['filter_parameter'];
            $offset = 0;//$data['offset'];
            $limit = APP_PAGINATION_LIMIT;

            $response = array();
            if ($user_id != '' && $app_key != '' && $thin_app_id != '' && $type != '' && $filter_type != '') {
                if (($result = $this->Custom->CheckIsValidApp($app_key, $thin_app_id)) && ($result == 1)) {
                    $mbroadcast_app_id = MBROADCAST_APP_ID;


                    $extra_condition_array = array();
                    if ($filter_type != "NO") {
                        if ($filter_type == "LIKE") {
                            $quest_ids = $this->QuestLike->find('list', array(
                                'conditions' => array(
                                    'QuestLike.user_id' => $user_id
                                ),
                                'contain' => false,
                                'fields' => array('QuestLike.quest_id'),
                                'offset' => $offset * $limit,
                                'limit' => $limit
                            ));
                            if (!empty($quest_ids)) {
                                if (count($quest_ids) == 1) {
                                    $extra_condition_array['Quest.id'] = $quest_ids;
                                } else {
                                    $extra_condition_array['Quest.id IN'] = $quest_ids;
                                }
                            } else {
                                $extra_condition_array['Quest.id'] = 0;
                            }
                        } else if ($filter_type == "CHAT") {
                            $extra_condition_array['Quest.enable_chat'] = 'YES';
                        } else if ($filter_type == "FILTER" && !empty($filter_parameter)) {
                            $extra_condition_array['Quest.quest_category_id'] = $filter_parameter;
                        }
                    }


                    if ($thin_app_id == $mbroadcast_app_id) {
                        $quest = $this->Quest->find('all', array(
                            'conditions' => array(
                                'OR' => array(
                                    array(
                                        'Quest.thinapp_id' => $thin_app_id,
                                        'Quest.mbroadcast_publish_status' => 'APPROVED',
                                        'Quest.status' => 'ACTIVE',
                                        'Quest.type' => $type,
                                        $extra_condition_array
                                    ),
                                    array(
                                        'Quest.show_on_mbroadcast' => 'YES',
                                        'Quest.mbroadcast_publish_status' => 'APPROVED',
                                        'Quest.status' => 'ACTIVE',
                                        'Quest.type' => $type,
                                        $extra_condition_array
                                    )
                                )
                            ),
                            'contain' => array('QuestCategory', 'User'),
                            'order' => array("Quest.id" => "DESC"),
                            'fields' => array('User.mobile', 'QuestCategory.name', 'Quest.type', 'Quest.user_id', 'Quest.location', 'Quest.created', 'Quest.id', 'Quest.question', 'Quest.reply_count', 'Quest.image', 'Quest.like_count', 'Quest.share_count', 'Quest.enable_chat')
                        ));

                    } else {
                        $quest = $this->Quest->find('all', array(
                            'conditions' => array(
                                'OR' => array(
                                    array(
                                        'Quest.thinapp_id' => $thin_app_id,
                                        'Quest.show_on_mbroadcast' => 'YES',
                                        'Quest.mbroadcast_publish_status' => 'APPROVED',
                                        'Quest.status' => 'ACTIVE',
                                        'Quest.type' => $type,
                                        $extra_condition_array

                                    ),
                                    array(
                                        'Quest.thinapp_id' => $thin_app_id,
                                        'Quest.status' => 'ACTIVE',
                                        'Quest.factory_publish_status' => 'APPROVED',
                                        'Quest.type' => $type,
                                        $extra_condition_array
                                    )
                                )
                            ),
                            'contain' => array('QuestCategory', 'User'),
                            'order' => array("Quest.id" => "DESC"),
                            'fields' => array('User.mobile', 'QuestCategory.name', 'Quest.type', 'Quest.user_id', 'Quest.location', 'Quest.created', 'Quest.id', 'Quest.question', 'Quest.reply_count', 'Quest.image', 'Quest.like_count', 'Quest.share_count', 'Quest.enable_chat'),
                            'offset' => $offset * $limit,
                            'limit' => $limit
                        ));
                    }

                    $channel_array = array();
                    if (!empty($quest)) {
                        foreach ($quest as $key => $quest_data) {
                            $channels_arr['id'] = $quest_data['Quest']['id'];
                            $channels_arr['category_name'] = $quest_data['QuestCategory']['name'];
                            $channels_arr['question'] = $quest_data['Quest']['question'];
                            $channels_arr['image'] = $quest_data['Quest']['image'];
                            $channels_arr['like_count'] = $quest_data['Quest']['like_count'];
                            $channels_arr['share_count'] = $quest_data['Quest']['share_count'];
                            $channels_arr['enable_chat'] = $quest_data['Quest']['enable_chat'];
                            $channels_arr['location'] = $quest_data['Quest']['location'];
                            $channels_arr['date'] = date("d M", strtotime($quest_data['Quest']['created']));
                            $channels_arr['time'] = $this->Custom->timeElapsedString(($quest_data['Quest']['created'])) . " ago";
                            $channels_arr['created_by'] = $this->Custom->hide_number($quest_data['User']['mobile']);
                            $channels_arr['reply_count'] = $quest_data['Quest']['reply_count'];
                            $channels_arr['type'] = $quest_data['Quest']['type'];
                            $channels_arr['is_owner'] = ($quest_data['Quest']['user_id'] == $user_id) ? "YES" : "NO";
                            //$channels_arr['is_like'] = $this->Custom->isQuestLike($user_id,$quest_data['Quest']['id']);
                            array_push($channel_array, $channels_arr);
                        }
                        $response['status'] = 1;
                        $response['message'] = "Quest list found";
                        $response['data']['quest_list'] = $channel_array;

                    } else {
                        $response['status'] = 0;
                        $response['message'] = "No data found";
                    }
                } else {
                    $response['status'] = 0;
                    $response['message'] = $this->Custom->getResponseMessage($result);
                }
            } else {
                $response['status'] = 0;
                $response['message'] = "Invalid request parameter";
            }

            echo json_encode($response);
            exit;
        }
    }


    public function get_quest_detail()
    {
        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        if ($this->request->is('post')) {
            $app_key = $data['app_key'];
            $user_id = $data['user_id'];
            $thin_app_id = $data['thin_app_id'];
            $quest_id = $data['quest_id'];
            $response = array();
            if ($user_id != '' && $app_key != '' && $thin_app_id != '' && $quest_id != '') {
                if (($result = $this->Custom->CheckIsValidApp($app_key, $thin_app_id)) && ($result == 1)) {

                    $quest = $this->Quest->find('first', array(
                        'conditions' => array(
                            'Quest.id' => $quest_id,
                            'Quest.thinapp_id' => $thin_app_id,
                            'Quest.status' => 'ACTIVE'
                        ),
                        'contain' => array('QuestCategory', 'QuestReply', 'User', 'QuestReply.AppUser'),
                        'fields' => array('User.mobile', 'User.firebase_token', 'QuestCategory.name', 'Quest.*')
                    ));

                    //pr($quest);die;
                    $channel_array = array();
                    if (!empty($quest)) {
                        $data_array = array();
                        $data_array['id'] = $quest['Quest']['id'];
                        $data_array['question'] = $quest['Quest']['question'];
                        $data_array['like_count'] = $quest['Quest']['like_count'];
                        $data_array['share_count'] = $quest['Quest']['share_count'];
                        $data_array['enable_chat'] = $quest['Quest']['enable_chat'];
                        $data_array['location'] = $quest['Quest']['location'];
                        $data_array['latitude'] = $quest['Quest']['latitude'];
                        $data_array['longitude'] = $quest['Quest']['longitude'];
                        $data_array['description'] = $quest['Quest']['description'];
                        $data_array['enable_chat'] = $quest['Quest']['enable_chat'];
                        $data_array['created_by'] = $this->Custom->hide_number($quest['User']['mobile']);
                        $data_array['firebase_token'] = $quest['User']['firebase_token'];
                        $data_array['time'] = $this->Custom->timeElapsedString(($quest['Quest']['created'])) . " ago";
                        $data_array['subscriber_id'] = $this->Custom->getSubscriberId($user_id, $quest['Quest']['channel_id']);
                        $data_array['isLike'] = $this->Custom->isQuestLike($user_id, $quest['Quest']['id']);
                        $data_array['is_owner'] = ($quest['Quest']['user_id'] == $user_id) ? "YES" : "NO";

                        $response['data']['quest_detail'] = $data_array;
                        $reply_count = count($quest['QuestReply']);
                        $response['data']['quest_detail']['quest_reply'] = array();
                        $response['data']['quest_detail']['reply_count'] = $reply_count;
                        if ($reply_count > 0) {
                            foreach ($quest['QuestReply'] as $key => $quest_data) {
                                $channels_arr['reply_id'] = $quest_data['id'];
                                $channels_arr['message'] = $quest_data['message'];
                                $channels_arr['created_by'] = $this->Custom->hide_number($quest_data['AppUser']['mobile']);
                                $channels_arr['is_owner'] = ($quest_data['AppUser']['id'] == $user_id) ? "YES" : "NO";
                                $channels_arr['time'] = $this->Custom->timeElapsedString(($quest_data['created']));
                                $channels_arr['is_thank'] = $this->Custom->is_thank($user_id, $quest_data['id']);
                                $channels_arr['thank_count'] = $quest_data['thank_count'];
                                array_push($channel_array, $channels_arr);
                            }
                        } else {
                            $channels_arr['reply_id'] = 0;
                            $channels_arr['message'] = "";
                            $channels_arr['thank_count'] = 0;
                            array_push($channel_array, $channels_arr);
                        }
                        $response['status'] = 1;
                        $response['message'] = "Quest data found";
                        $response['data']['quest_detail']['quest_reply'] = $channel_array;
                    } else {
                        $response['status'] = 0;
                        $response['message'] = "Sorry quest not found";
                    }
                } else {
                    $response['status'] = 0;
                    $response['message'] = $this->Custom->getResponseMessage($result);
                }
            } else {
                $response['status'] = 0;
                $response['message'] = "Invalid request parameter";
            }

            echo json_encode($response);
            exit;
        }
    }

    public function delete_quest()
    {
        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        if ($this->request->is('post')) {
            $app_key = $data['app_key'];
            $user_id = $data['user_id'];
            $thin_app_id = $data['thin_app_id'];
            $quest_id = $data['quest_id'];
            //$type = $data['type'];

            $response = array();
            if ($user_id != '' && $app_key != '' && $thin_app_id != '' && $quest_id != '') {
                if (($result = $this->Custom->CheckIsValidApp($app_key, $thin_app_id)) && ($result == 1)) {
                    $quest = $this->Quest->find('first', array(
                        'conditions' => array(
                            'Quest.id' => $quest_id,
                            'Quest.thinapp_id' => $thin_app_id,
                            'Quest.status' => 'ACTIVE'
                        ),
                        'contain' => false,
                        'fields' => array('Quest.type', 'Quest.share_on', 'Quest.channel_id')
                    ));
                    if ($quest) {
                        $datasource = $this->Quest->getDataSource();
                        try {
                            $datasource->begin();
                            $q = $this->Quest->delete(array('Quest.id' => $quest_id));
                            $ql = $this->QuestLike->deleteAll(array('QuestLike.quest_id' => $quest_id));
                            $qr = $this->QuestReply->deleteAll(array('QuestReply.quest_id' => $quest_id));
                            $qrt = $this->QuestReplyThank->deleteAll(array('QuestReplyThank.quest_id' => $quest_id));
                            $qs = $this->QuestShare->deleteAll(array('QuestShare.quest_id' => $quest_id));
                            $cm = $this->ChannelMessage->deleteAll(array(
                                'ChannelMessage.message_id' => $quest_id,
                                'ChannelMessage.post_type_status' => $quest['Quest']['type']
                            ));
                            $cm = $this->MessageAction->deleteAll(array(
                                'MessageAction.message_id' => $quest_id,
                                'MessageAction.list_message_type' => $quest['Quest']['type']
                            ));
                            $cm = $this->MessageStatic->deleteAll(array(
                                'MessageStatic.message_id' => $quest_id,
                                'MessageStatic.list_message_type' => $quest['Quest']['type']
                            ));

                            if ($q) {
                                $datasource->commit();
                                $response['status'] = 1;
                                $response['message'] = "Your quest deleted successfully";
                            } else {
                                $response['status'] = 0;
                                $response['message'] = "Sorry unable to delete";
                            }

                            /*  if($quest['Quest']['share_on']=='CHANNEL'){
                                $delete_from_channel = $this->ChannelMessage->updateAll(array('ChannelMessage.status' =>"'INACTIVE'"),array(
                                    'ChannelMessage.message_id' => $quest_id,
                                    'ChannelMessage.channel_id' => $quest['Quest']['channel_id'],
                                    'ChannelMessage.post_type_status' => $type
                                ));
                                $del_status = $this->Quest->updateAll(array('Quest.status' =>"'INACTIVE'"),array('Quest.id' => $quest_id));
                                if($del_status && $delete_from_channel){
                                    $datasource->commit();
                                    $response['status'] = 1;
                                    $response['message'] = "Your quest deleted successfully";
                                }else{
                                    $datasource->commit();
                                    $response['status'] = 0;
                                    $response['message'] = "Sorry unable to delete";
                                }
                            }else{
                                $del_status = $this->Quest->updateAll(array('Quest.status' =>"'INACTIVE'"),array('Quest.id' => $quest_id));
                                if($del_status){
                                    $datasource->commit();
                                    $response['status'] = 1;
                                    $response['message'] = "Your quest deleted successfully";
                                }else{
                                    $response['status'] = 0;
                                    $response['message'] = "Sorry unable to delete";
                                }
                            }*/

                        } catch (Exception $e) {
                            $datasource->rollback();
                        }


                    } else {
                        $response['status'] = 0;
                        $response['message'] = "Sorry quest  not found";
                    }


                } else {
                    $response['status'] = 0;
                    $response['message'] = $this->Custom->getResponseMessage($result);
                }
            } else {
                $response['status'] = 0;
                $response['message'] = "Invalid request parameter";
            }

            echo json_encode($response);
            exit;
        }
    }


    public function delete_quest_reply()
    {
        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        if ($this->request->is('post')) {
            $app_key = $data['app_key'];
            $user_id = $data['user_id'];
            $thin_app_id = $data['thin_app_id'];
            $quest_id = $data['quest_id'];
            $quest_reply_id = $data['quest_reply_id'];
            $response = array();
            if ($user_id != '' && $app_key != '' && $thin_app_id != '' && $quest_reply_id != '' && $quest_id != '') {
                if (($result = $this->Custom->CheckIsValidApp($app_key, $thin_app_id)) && ($result == 1)) {
                    $del_status = $this->QuestReply->delete($quest_reply_id);
                    $reply_count_status = $this->Quest->updateAll(array('Quest.reply_count' => 'Quest.reply_count - 1'), array('Quest.id' => $quest_id));
                    if ($del_status == true && $reply_count_status == true) {
                        $response['status'] = 1;
                        $response['message'] = "Your reply deleted successfully";
                    } else {
                        $response['status'] = 0;
                        $response['message'] = "Sorry unable to delete";
                    }
                } else {
                    $response['status'] = 0;
                    $response['message'] = $this->Custom->getResponseMessage($result);
                }
            } else {
                $response['status'] = 0;
                $response['message'] = "Invalid request parameter";
            }

            echo json_encode($response);
            exit;
        }
    }


    public function add_quest_reply()
    {
        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        if ($this->request->is('post')) {

            $app_key = $data['app_key'];
            $user_id = $data['user_id'];
            $subscriber_id = $data['subscriber_id'];
            $thin_app_id = $data['thin_app_id'];
            $quest_id = $data['quest_id'];
            $message = $data['message'];

            $response = array();
            if ($user_id != '' && $app_key != '' && $thin_app_id != '' && $quest_id != '' && $message != '' && $subscriber_id != "") {
                if (($result = $this->Custom->CheckIsValidApp($app_key, $thin_app_id)) && ($result == 1)) {

                    $quest = $this->Quest->find('first', array(
                        'conditions' => array(
                            'Quest.id' => $quest_id,
                            'Quest.thinapp_id' => $thin_app_id,
                            'Quest.status' => 'ACTIVE'
                        ),
                        'contain' => false
                    ));
                    if (!empty($quest)) {
                        $datasource = $this->QuestReply->getDataSource();
                        try {
                            $datasource->begin();

                            $this->QuestReply->create();
                            $this->QuestReply->set('user_id', $user_id);
                            $this->QuestReply->set('quest_id', $quest_id);
                            $this->QuestReply->set('message', $message);
                            $this->QuestReply->set('subscriber_id', $subscriber_id);
                            $reply_count_status = $this->Quest->updateAll(array(
                                'Quest.reply_count' => 'Quest.reply_count + 1'),
                                array('Quest.id' => $quest_id)
                            );
                            if ($this->QuestReply->save() && $reply_count_status) {
                                $datasource->commit();
                                $message = $quest['Quest']['type'] . ' Comment - ' . $message;
                                $option = array(
                                    'thinapp_id' => $thin_app_id,
                                    'quest_id' => $quest_id,
                                    'user_id' => $quest['Quest']['user_id'],
                                    'data' => array(
                                        'thinapp_id' => $thin_app_id,
                                        'channel_id' => $quest['Quest']['channel_id'],
                                        'flag' => 'NEW_QUEST',
                                        'title' => "Quest Reply",
                                        'message' => mb_strimwidth($message, 0, 50, '...'),
                                        'description' => "",
                                        'chat_reference' => '',
                                        'module_type' => $quest['Quest']['type'],
                                        'module_type_id' => $quest_id,
                                        'firebase_reference' => ""
                                    )
                                );
                                $this->Custom->send_notification_to_device($option);


                                $response['status'] = 1;
                                $response['message'] = "Reply post successfully";
                            } else {
                                $response['status'] = 1;
                                $response['message'] = "Sorry reply could not post";
                            }
                        } catch (Exception $e) {
                            // echo $e->getMessage();die;
                            $datasource->rollback();
                            $response['status'] = 0;
                            $response['message'] = "Sorry something went wrong on server";
                        }
                    } else {
                        $response['status'] = 0;
                        $response['message'] = "Sorry quest not found";
                    }
                } else {
                    $response['status'] = 0;
                    $response['message'] = $this->Custom->getResponseMessage($result);
                }
            } else {
                $response['status'] = 0;
                $response['message'] = "Invalid request parameter";
            }

            echo json_encode($response);
            exit;
        }
    }


    public function add_quest_reply_thank()
    {
        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        if ($this->request->is('post')) {

            $app_key = $data['app_key'];
            $user_id = $data['user_id'];
            $subscriber_id = $data['subscriber_id'];
            $thin_app_id = $data['thin_app_id'];
            $quest_id = $data['quest_id'];
            $quest_reply_id = $data['quest_reply_id'];

            $response = array();
            if ($user_id != '' && $app_key != '' && $thin_app_id != '' && $quest_id != '' && $quest_reply_id != '' && $subscriber_id != "") {
                if (($result = $this->Custom->CheckIsValidApp($app_key, $thin_app_id)) && ($result == 1)) {
                    $quest = $this->Quest->find('count', array(
                        'conditions' => array(
                            'Quest.id' => $quest_id,
                            'Quest.thinapp_id' => $thin_app_id,
                            'Quest.status' => 'ACTIVE'
                        ),
                        'contain' => false
                    ));
                    if (!empty($quest)) {
                        $datasource = $this->QuestReplyThank->getDataSource();
                        try {
                            $datasource->begin();
                            $quest_replay_data = $this->QuestReplyThank->find('first', array(
                                'conditions' => array(
                                    'QuestReplyThank.user_id' => $user_id,
                                    'QuestReplyThank.quest_id' => $quest_id,
                                    'QuestReplyThank.quest_reply_id' => $quest_reply_id
                                ),
                                'contain' => false
                            ));
                            if (empty($quest_replay_data)) {
                                $this->QuestReplyThank->create();
                                $this->QuestReplyThank->set('user_id', $user_id);
                                $this->QuestReplyThank->set('quest_id', $quest_id);
                                $this->QuestReplyThank->set('quest_reply_id', $quest_reply_id);
                                $this->QuestReplyThank->set('subscriber_id', $subscriber_id);
                                $reply_thanks_status = $this->QuestReply->updateAll(array(
                                    'QuestReply.thank_count' => 'QuestReply.thank_count + 1'),
                                    array('QuestReply.id' => $quest_reply_id)
                                );
                                if ($this->QuestReplyThank->save() && $reply_thanks_status) {
                                    $datasource->commit();
                                    $response['status'] = 1;
                                    $response['message'] = "Thanks add successfully";
                                } else {
                                    $response['status'] = 1;
                                    $response['message'] = "Sorry thanks could not add";
                                }
                            } else {
                                $is_delete = $this->QuestReplyThank->delete($quest_replay_data['QuestReplyThank']['id']);
                                $is_less_count = $this->QuestReply->updateAll(array('QuestReply.thank_count' => "QuestReply.thank_count - 1"), array('QuestReply.id' => $quest_reply_id));
                                if ($is_delete && $is_less_count) {
                                    $datasource->commit();
                                    $response['status'] = 1;
                                    $response['message'] = "Action update successfully";
                                } else {
                                    $response['status'] = 1;
                                    $response['message'] = "Sorry action could not update";
                                }
                            }
                        } catch (Exception $e) {
                            // echo $e->getMessage();die;
                            $datasource->rollback();
                            $response['status'] = 0;
                            $response['message'] = "Sorry something went wrong on server";
                        }
                    } else {
                        $response['status'] = 0;
                        $response['message'] = "Sorry quest not found";
                    }
                } else {
                    $response['status'] = 0;
                    $response['message'] = $this->Custom->getResponseMessage($result);
                }
            } else {
                $response['status'] = 0;
                $response['message'] = "Invalid request parameter";
            }

            echo json_encode($response);
            exit;
        }
    }

    public function quest_like()
    {
        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        if ($this->request->is('post')) {

            $app_key = $data['app_key'];
            $user_id = $data['user_id'];
            $subscriber_id = $data['subscriber_id'];
            $thin_app_id = $data['thin_app_id'];
            $quest_id = $data['quest_id'];

            $response = array();
            if ($user_id != '' && $app_key != '' && $thin_app_id != '' && $quest_id != '' && $subscriber_id != "") {
                if (($result = $this->Custom->CheckIsValidApp($app_key, $thin_app_id)) && ($result == 1)) {

                    $datasource = $this->QuestReplyThank->getDataSource();
                    try {
                        $datasource->begin();
                        $quest_like_data = $this->QuestLike->find('first', array(
                            'conditions' => array(
                                'QuestLike.user_id' => $user_id,
                                'QuestLike.quest_id' => $quest_id
                            ),
                            'contain' => false
                        ));
                        if (empty($quest_like_data)) {
                            $this->QuestLike->create();
                            $this->QuestLike->set('user_id', $user_id);
                            $this->QuestLike->set('quest_id', $quest_id);
                            $this->QuestLike->set('subscriber_id', $subscriber_id);
                            $reply_thanks_status = $this->Quest->updateAll(array(
                                'Quest.like_count' => 'Quest.like_count + 1'),
                                array('Quest.id' => $quest_id)
                            );
                            if ($this->QuestLike->save() && $reply_thanks_status) {
                                $datasource->commit();
                                $response['status'] = 1;
                                $response['message'] = "Quest liked successfully";
                            } else {
                                $response['status'] = 1;
                                $response['message'] = "Sorry quest could not liked";
                            }
                        } else {

                            $is_delete = $this->QuestLike->delete($quest_like_data['QuestLike']['id']);
                            $is_less_count = $this->Quest->updateAll(array('Quest.like_count' => "Quest.like_count - 1"), array('Quest.id' => $quest_id));
                            if ($is_delete && $is_less_count) {
                                $datasource->commit();
                                $response['status'] = 1;
                                $response['message'] = "Quest unliked successfully";
                            } else {
                                $response['status'] = 1;
                                $response['message'] = "Sorry quest could not unliked";
                            }
                        }
                    } catch (Exception $e) {
                        // echo $e->getMessage();die;
                        $datasource->rollback();
                        $response['status'] = 0;
                        $response['message'] = "Sorry something went wrong on server";
                    }

                } else {
                    $response['status'] = 0;
                    $response['message'] = $this->Custom->getResponseMessage($result);
                }
            } else {
                $response['status'] = 0;
                $response['message'] = "Invalid request parameter";
            }

            echo json_encode($response);
            exit;
        }
    }

    public function share_quest_on_social()
    {
        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        if ($this->request->is('post')) {

            $app_key = $data['app_key'];
            $user_id = $data['user_id'];
            $subscriber_id = $data['subscriber_id'];
            $thin_app_id = $data['thin_app_id'];
            $quest_id = $data['quest_id'];
            $share_at = $data['share_at'];

            $response = array();
            if ($user_id != '' && $app_key != '' && $thin_app_id != '' && $quest_id != '' && $share_at != '' && $subscriber_id != "") {
                if (($result = $this->Custom->CheckIsValidApp($app_key, $thin_app_id)) && ($result == 1)) {

                    $quest = $this->Quest->find('count', array(
                        'conditions' => array(
                            'Quest.id' => $quest_id,
                            'Quest.thinapp_id' => $thin_app_id,
                            'Quest.status' => 'ACTIVE'
                        ),
                        'contain' => false
                    ));
                    if (!empty($quest)) {
                        $datasource = $this->QuestShare->getDataSource();
                        try {
                            $datasource->begin();
                            $this->QuestShare->create();
                            $this->QuestShare->set('user_id', $user_id);
                            $this->QuestShare->set('quest_id', $quest_id);
                            $this->QuestShare->set('share_at', $share_at);
                            $this->QuestShare->set('subscriber_id', $subscriber_id);
                            $reply_count_status = $this->Quest->updateAll(array(
                                'Quest.share_count' => 'Quest.share_count + 1'),
                                array('Quest.id' => $quest_id)
                            );
                            if ($this->QuestShare->save() && $reply_count_status) {
                                $datasource->commit();
                                $response['status'] = 1;
                                $response['message'] = "Quest shared successfully";
                            } else {
                                $response['status'] = 1;
                                $response['message'] = "Sorry quest could not shared";
                            }
                        } catch (Exception $e) {
                            // echo $e->getMessage();die;
                            $datasource->rollback();
                            $response['status'] = 0;
                            $response['message'] = "Sorry something went wrong on server";
                        }
                    } else {
                        $response['status'] = 0;
                        $response['message'] = "Sorry quest not found";
                    }
                } else {
                    $response['status'] = 0;
                    $response['message'] = $this->Custom->getResponseMessage($result);
                }
            } else {
                $response['status'] = 0;
                $response['message'] = "Invalid request parameter";
            }

            echo json_encode($response);
            exit;
        }
    }

    /*Sale start from here */

    public function get_sell_category_list()
    {

        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        if ($this->request->is('post')) {
            $app_key = $data['app_key'];
            $user_id = $data['user_id'];
            $thin_app_id = $data['thin_app_id'];
            $response = array();
            $category_list = $this->SellItemCategory->find('all', array(
                'conditions' => array(
                    'SellItemCategory.status' => 'ACTIVE'
                ),
                'contain' => false,
                'fields' => array('SellItemCategory.id', 'SellItemCategory.name')
            ));

            if (!empty($category_list)) {
                $response['status'] = 1;
                $response['message'] = "Category list found";
                $cat_array = array();
                foreach ($category_list as $key => $cat) {
                    $cat_array[$key]['category_id'] = $cat['SellItemCategory']['id'];
                    $cat_array[$key]['category_title'] = $cat['SellItemCategory']['name'];
                }
                $response['data']['sell_category_list'] = $cat_array;

            } else {
                $response['status'] = 0;
                $response['message'] = "Category list not found";
            }
            echo json_encode($response);
            exit;
        }
    }

    public function add_sell()
    {


        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        if ($this->request->is('post')) {
            $response = array();
            $thin_app_id = $data['thin_app_id'];
            $app_key = $data['app_key'];
            $user_id = $data['user_id'];
            $sell_item_category_id = $data['sell_item_category_id'];
            $item_name = $data['item_name'];
            $description = $data['description'];
            $price = $data['price'];
            $negotiable = $data['negotiable'];
            $show_my_phone_number = $data['show_my_phone_number'];
            $pick_up_location = $data['pick_up_location'];
            $latitude = $data['latitude'];
            $longitude = $data['longitude'];
            $share_on = $data['share_on'];
            $channel_id = $data['channel_id'];
            $show_on_mbroadcast = $data['show_on_mbroadcast'];
            $enable_chat = 'YES';//$data['enable_chat'];
            $image_path_array = $data['image_path_array'];

            if ($user_id != '' && $thin_app_id != '' && $item_name != '' && $price != '' && $pick_up_location != '' && !empty($image_path_array)) {

                if (($result = $this->Custom->CheckIsValidApp($app_key, $thin_app_id)) && ($result == 1)) {
                     $total_sms = Custom::get_total_sms_thinapp($thin_app_id,'T');
                    $total_sub = $this->Custom->totalSmsSubscriber($channel_id, $thin_app_id);
                    if (($total_sms >= $total_sub && $share_on == 'CHANNEL') || $share_on == 'SELL_FACTORY') {
                        $datasource = $this->SellItem->getDataSource();
                        $latitude = empty($latitude) ? 26.9124336 : $latitude;
                        $longitude = empty($longitude) ? 75.7872709 : $longitude;
                        $pick_up_location = empty($pick_up_location) ? "Jaipur" : $pick_up_location;
                        try {
                            $datasource->begin();
                            $this->SellItem->create();
                            $this->SellItem->set('user_id', $user_id);
                            $this->SellItem->set('thinapp_id', $thin_app_id);
                            $this->SellItem->set('sell_item_category_id', $sell_item_category_id);
                            $this->SellItem->set('item_name', $item_name);
                            $this->SellItem->set('description', $description);
                            $this->SellItem->set('price', $price);
                            $this->SellItem->set('negotiable', $negotiable);
                            $this->SellItem->set('show_my_phone_number', $show_my_phone_number);
                            $this->SellItem->set('pick_up_location', $pick_up_location);
                            $this->SellItem->set('latitude', $latitude);
                            $this->SellItem->set('longitude', $longitude);
                            $this->SellItem->set('share_on', $share_on);
                            $this->SellItem->set('channel_id', $channel_id);
                            $this->SellItem->set('show_on_mbroadcast', $show_on_mbroadcast);
                            $this->SellItem->set('mbroadcast_publish_status', 'APPROVED');
                            $this->SellItem->set('factory_publish_status', 'APPROVED');

                            $this->SellItem->set('enable_chat', $enable_chat);
                            if ($this->SellItem->save()) {
                                $sell_id = $this->SellItem->getLastInsertId();
                                if (!empty($image_path_array)) {
                                    $save_media = array();
                                    $counter = 0;
                                    if (is_array($image_path_array)) {
                                        /* this code work for first version of app  media to event*/
                                        foreach ($image_path_array as $key => $value) {
                                            $save_media[$counter]['sell_item_id'] = $sell_id;
                                            $save_media[$counter]['path'] = $value["filename"];
                                            $save_media[$counter++]['is_cover_image'] = ($key == 0) ? "YES" : "NO";
                                        }
                                        $this->SellImage->saveAll($save_media);
                                    } else {
                                        /* this code work for second version of app */
                                        $save_media['sell_item_id'] = $sell_id;
                                        $save_media['path'] = $image_path_array;
                                        $save_media['is_cover_image'] = "YES";
                                        $this->SellImage->saveAll($save_media);
                                    }

                                }


                                if ($share_on == "CHANNEL") {
                                    $this->ChannelMessage->create();
                                    $this->ChannelMessage->set('message_id', $sell_id);
                                    $this->ChannelMessage->set('channel_id', $channel_id);
                                    $this->ChannelMessage->set('post_type_status', 'SELL');
                                    $this->MessageStatic->create();
                                    $this->MessageStatic->set('message_id', $sell_id);
                                    $this->MessageStatic->set('list_message_type', 'SELL');
                                    if ($this->ChannelMessage->save() && $this->MessageStatic->save()) {
                                        $datasource->commit();
                                        $response['status'] = 1;
                                        $response['message'] = "Sell added successfully";
                                        /* send notification to channel subscriber*/
                                        $app_name = "Mbroadcast";
                                        $message = $description;
                                        $sendArray = array(
                                            'thinapp_id' => $thin_app_id,
                                            'channel_id' => $channel_id,
                                            'sell_id' => $sell_id,
                                            'thinapp_id' => $thin_app_id,
                                            'flag' => 'NEW_QUEST',
                                            'title' => strtoupper($app_name),
                                            'message' => mb_strimwidth("Sell - " . $message, 0, 50, '...'),
                                            'description' => mb_strimwidth($description, 0, 100, '...'),
                                            'chat_reference' => '',
                                            'module_type' => 'SELL',
                                            'module_type_id' => $sell_id,
                                            'firebase_reference' => ""
                                        );
                                        $this->Custom->send_topic_notification($sendArray);
                                        /* ADD MESSAGE CODE FOR UNREGISTER USERS*/

                                        $user_role = $this->Custom->get_user_role_id($user_id);
                                        $is_permission = $this->Custom->check_user_permission($thin_app_id, 'QUEST_BUY_SELL_SEND_NOTIFICATION_VIA_SMS');
                                        if ($user_role == 5 || $is_permission == "YES") {
                                            $message = $item_name . " is going to sale for buy";
                                            $this->Custom->sendBulkSms($channel_id, $thin_app_id, $message, $sell_id, $user_id);
                                        }


                                    } else {
                                        $datasource->rollback();
                                        $response['status'] = 0;
                                        $response['message'] = "Sorry,Sell could be not add";
                                    }
                                } else {
                                    $datasource->commit();
                                    $response['status'] = 1;
                                    $response['message'] = "Sell added successfully";
                                }
                            } else {
                                $response['status'] = 0;
                                $response['message'] = "Sorry,Sell could be not add";
                            }
                        } catch (Exception $e) {
                            $datasource->rollback();
                            $response['status'] = 0;
                            $response['message'] = "Sorry,Sell could be not add";
                        }

                    } else {
                        $response['status'] = 0;
                        $response['message'] = "System error found. Please try later";
                    }

                } else {
                    $response['status'] = 0;
                    $response['message'] = $this->Custom->getResponseMessage($result);
                }
            } else {
                $response['status'] = 0;
                $response['message'] = "Invalid request parameter";
            }
            echo json_encode($response);
            exit;
        }
    }

    public function get_sell_list()
    {
        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        if ($this->request->is('post')) {
            $app_key = $data['app_key'];
            $user_id = $data['user_id'];
            $thin_app_id = $data['thin_app_id'];
            $filter_type = $data['filter_type'];
            $filter_parameter = $data['filter_parameter'];
            $offset = $data['offset'];
            $limit = APP_PAGINATION_LIMIT;

            $response = array();
            if ($user_id != '' && $app_key != '' && $thin_app_id != '') {
                if (($result = $this->Custom->CheckIsValidApp($app_key, $thin_app_id)) && ($result == 1)) {

                    $extra_condition_array = array();
                    if ($filter_type != "NO") {
                        if ($filter_type == "LIKE") {
                            $quest_ids = $this->SellLike->find('list', array(
                                'conditions' => array(
                                    'SellLike.user_id' => $user_id
                                ),
                                'contain' => false,
                                'fields' => array('SellLike.sell_item_id'),
                                'offset' => $offset * $limit,
                                'limit' => $limit
                            ));

                            if (!empty($quest_ids)) {
                                if (count($quest_ids) == 1) {
                                    $extra_condition_array['SellItem.id'] = $quest_ids;
                                } else {
                                    $extra_condition_array['SellItem.id IN'] = $quest_ids;
                                }
                            } else {
                                $extra_condition_array['SellItem.id'] = 0;
                            }
                        } else if ($filter_type == "FILTER" && !empty($filter_parameter)) {
                            $extra_condition_array['SellItem.sell_item_category_id'] = $filter_parameter;
                        }


                    }


                    $mbroadcast_app_id = MBROADCAST_APP_ID;
                    if ($thin_app_id == $mbroadcast_app_id) {
                        $sell = $this->SellItem->find('all', array(
                            'conditions' => array(
                                'OR' => array(
                                    array(
                                        'SellItem.show_on_mbroadcast' => 'YES',
                                        'SellItem.mbroadcast_publish_status' => 'APPROVED',
                                        'SellItem.status' => 'ACTIVE',
                                    ),
                                    'SellItem.thinapp_id' => $thin_app_id
                                ), $extra_condition_array
                            ),
                            'contain' => array('SellItemCategory', 'CoverImage', 'User'),
                            'order' => array("SellItem.id" => "DESC"),
                            'fields' => array('CoverImage.path', 'User.mobile', 'SellItemCategory.name', 'SellItem.description', 'SellItem.user_id', 'SellItem.created', 'SellItem.id', 'SellItem.item_name', 'SellItem.price', 'SellItem.pick_up_location'),
                            'offset' => $offset * $limit,
                            'limit' => $limit
                        ));

                    } else {
                        $sell = $this->SellItem->find('all', array(
                            'conditions' => array(
                                'OR' => array(
                                    array(
                                        'SellItem.thinapp_id' => $thin_app_id,
                                        'SellItem.show_on_mbroadcast' => 'YES',
                                        'SellItem.mbroadcast_publish_status' => 'APPROVED',
                                        'SellItem.status' => 'ACTIVE',
                                    ),
                                    array(
                                        'SellItem.thinapp_id' => $thin_app_id,
                                        'SellItem.status' => 'ACTIVE',
                                        'SellItem.factory_publish_status' => 'APPROVED'
                                    )
                                ), $extra_condition_array
                            ),
                            'contain' => array('SellItemCategory', 'CoverImage', 'User'),
                            'order' => array("SellItem.id" => "DESC"),
                            'fields' => array('CoverImage.path', 'User.mobile', 'SellItemCategory.name', 'SellItem.description', 'SellItem.user_id', 'SellItem.created', 'SellItem.id', 'SellItem.item_name', 'SellItem.price', 'SellItem.pick_up_location'),
                            'offset' => $offset * $limit,
                            'limit' => $limit
                        ));
                    }


                    $channel_array = array();
                    if (!empty($sell)) {
                        foreach ($sell as $key => $quest_data) {
                            $channels_arr['id'] = $quest_data['SellItem']['id'];
                            $channels_arr['item_name'] = $quest_data['SellItem']['item_name'];
                            $channels_arr['description'] = $quest_data['SellItem']['description'];
                            $channels_arr['category_name'] = $quest_data['SellItemCategory']['name'];
                            $channels_arr['image'] = $quest_data['CoverImage']['path'];
                            $channels_arr['price'] = $quest_data['SellItem']['price'];
                            $channels_arr['location'] = $quest_data['SellItem']['pick_up_location'];
                            $channels_arr['created_by'] = $this->Custom->hide_number($quest_data['User']['mobile']);
                            $channels_arr['time'] = $this->Custom->timeElapsedString(($quest_data['SellItem']['created'])) . " ago";
                            $channels_arr['is_like'] = $this->Custom->isSellLike($user_id, $quest_data['SellItem']['id']);
                            $channels_arr['is_owner'] = ($quest_data['SellItem']['user_id'] == $user_id) ? "YES" : "NO";
                            $channels_arr['type'] = 'SELL';
                            array_push($channel_array, $channels_arr);
                        }
                        $response['status'] = 1;
                        $response['message'] = "Sell list found";
                        $response['data']['sell_list'] = $channel_array;

                    } else {
                        $response['status'] = 0;
                        $response['message'] = "There is no sell";
                    }
                } else {
                    $response['status'] = 0;
                    $response['message'] = $this->Custom->getResponseMessage($result);
                }
            } else {
                $response['status'] = 0;
                $response['message'] = "Invalid request parameter";
            }

            echo json_encode($response);
            exit;
        }
    }

    public function sell_like()
    {
        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        if ($this->request->is('post')) {

            $app_key = $data['app_key'];
            $user_id = $data['user_id'];
            $thin_app_id = $data['thin_app_id'];
            $sell_item_id = $data['sell_item_id'];

            $response = array();
            if ($user_id != '' && $app_key != '' && $thin_app_id != '' && $sell_item_id != '') {
                if (($result = $this->Custom->CheckIsValidApp($app_key, $thin_app_id)) && ($result == 1)) {

                    $datasource = $this->SellLike->getDataSource();
                    try {
                        $datasource->begin();
                        $quest_like_data = $this->SellLike->find('first', array(
                            'conditions' => array(
                                'SellLike.user_id' => $user_id,
                                'SellLike.sell_item_id' => $sell_item_id
                            ),
                            'contain' => false
                        ));
                        if (empty($quest_like_data)) {
                            $this->SellLike->create();
                            $this->SellLike->set('user_id', $user_id);
                            $this->SellLike->set('sell_item_id', $sell_item_id);
                            $reply_thanks_status = $this->SellItem->updateAll(array(
                                'SellItem.like_count' => 'SellItem.like_count + 1'),
                                array('SellItem.id' => $sell_item_id)
                            );
                            if ($this->SellLike->save() && $reply_thanks_status) {
                                $datasource->commit();
                                $response['status'] = 1;
                                $response['message'] = "Sell liked successfully";
                            } else {
                                $response['status'] = 1;
                                $response['message'] = "Sorry sell could not liked";
                            }
                        } else {

                            $is_delete = $this->SellLike->delete($quest_like_data['SellLike']['id']);
                            $is_less_count = $this->SellItem->updateAll(array('SellItem.like_count' => "SellItem.like_count - 1"), array('SellItem.id' => $sell_item_id));
                            if ($is_delete && $is_less_count) {
                                $datasource->commit();
                                $response['status'] = 1;
                                $response['message'] = "Sell unliked successfully";
                            } else {
                                $response['status'] = 1;
                                $response['message'] = "Sorry sell could not unliked";
                            }
                        }
                    } catch (Exception $e) {
                        // echo $e->getMessage();die;
                        $datasource->rollback();
                        $response['status'] = 0;
                        $response['message'] = "Sorry something went wrong on server";
                    }

                } else {
                    $response['status'] = 0;
                    $response['message'] = $this->Custom->getResponseMessage($result);
                }
            } else {
                $response['status'] = 0;
                $response['message'] = "Invalid request parameter";
            }

            echo json_encode($response);
            exit;
        }
    }

    public function get_sell_detail()
    {
        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        if ($this->request->is('post')) {
            $app_key = $data['app_key'];
            $user_id = $data['user_id'];
            $thin_app_id = $data['thin_app_id'];
            $sell_id = $data['sell_id'];
            $response = array();
            if ($user_id != '' && $app_key != '' && $thin_app_id != '' && $sell_id != '') {
                if (($result = $this->Custom->CheckIsValidApp($app_key, $thin_app_id)) && ($result == 1)) {
                    $sell_data = $this->SellItem->find('first', array(
                        'conditions' => array(
                            'SellItem.id' => $sell_id,
                            'SellItem.thinapp_id' => $thin_app_id,
                            'SellItem.status' => 'ACTIVE'
                        ),
                        'contain' => array('SellItemCategory', 'SellImage', 'User'),
                        'fields' => array('User.mobile', 'User.firebase_token', 'SellItemCategory.name', 'SellItem.*')
                    ));


                    $channels_arr = array();
                    if (!empty($sell_data)) {
                        $channels_arr['id'] = $sell_data['SellItem']['id'];
                        $channels_arr['item_name'] = $sell_data['SellItem']['item_name'];
                        $channels_arr['description'] = $sell_data['SellItem']['description'];
                        $channels_arr['category_name'] = $sell_data['SellItemCategory']['name'];

                        $channels_arr['price'] = $sell_data['SellItem']['price'];
                        $channels_arr['location'] = $sell_data['SellItem']['pick_up_location'];
                        $channels_arr['latitude'] = $sell_data['SellItem']['latitude'];
                        $channels_arr['longitude'] = $sell_data['SellItem']['longitude'];
                        $channels_arr['created_by'] = $this->Custom->hide_number($sell_data['User']['mobile']);
                        $channels_arr['firebase_token'] = $sell_data['User']['firebase_token'];
                        $channels_arr['enable_chat'] = "YES";//$sell_data['SellItem']['enable_chat'];
                        $channels_arr['negotiable'] = $sell_data['SellItem']['negotiable'];
                        $channels_arr['time'] = $this->Custom->timeElapsedString(($sell_data['SellItem']['created'])) . " ago";
                        $channels_arr['date'] = date('d M Y H:i', strtotime($sell_data['SellItem']['created']));
                        $channels_arr['is_like'] = $this->Custom->isSellLike($user_id, $sell_id);
                        $channels_arr['is_owner'] = ($sell_data['SellItem']['user_id'] == $user_id) ? "YES" : "NO";

                        foreach ($sell_data['SellImage'] as $key => $path) {
                            $channels_arr['image'][$key] = $path['path'];
                        }
                        $response['status'] = 1;
                        $response['message'] = "Sell data found";
                        $response['data']['sell_detail'] = $channels_arr;
                    } else {
                        $response['status'] = 0;
                        $response['message'] = "Sorry sell not found";
                    }
                } else {
                    $response['status'] = 0;
                    $response['message'] = $this->Custom->getResponseMessage($result);
                }
            } else {
                $response['status'] = 0;
                $response['message'] = "Invalid request parameter";
            }

            echo json_encode($response);
            exit;
        }
    }

    public function delete_sell()
    {
        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        if ($this->request->is('post')) {
            $app_key = $data['app_key'];
            $user_id = $data['user_id'];
            $thin_app_id = $data['thin_app_id'];
            $sell_id = $data['sell_id'];
            $response = array();
            if ($user_id != '' && $app_key != '' && $thin_app_id != '' && $sell_id != '') {
                if (($result = $this->Custom->CheckIsValidApp($app_key, $thin_app_id)) && ($result == 1)) {

                    $sell = $this->SellItem->find('first', array(
                        'conditions' => array(
                            'SellItem.id' => $sell_id,
                            'SellItem.thinapp_id' => $thin_app_id,
                            'SellItem.status' => 'ACTIVE'
                        ),
                        'contain' => false,
                        'fields' => array('SellItem.share_on', 'SellItem.channel_id')
                    ));
                    if ($sell) {
                        $datasource = $this->SellItem->getDataSource();
                        try {
                            $datasource->begin();

                            $q = $this->SellItem->delete(array('SellItem.id' => $sell_id));
                            $ql = $this->SellImage->deleteAll(array('SellImage.sell_item_id' => $sell_id));
                            $qr = $this->SellWishlist->deleteAll(array('SellWishlist.sell_item_id' => $sell_id));
                            $cm = $this->ChannelMessage->deleteAll(array(
                                'ChannelMessage.message_id' => $sell_id,
                                'ChannelMessage.post_type_status' => 'SELL'
                            ));

                            $cm = $this->MessageAction->deleteAll(array(
                                'MessageAction.message_id' => $sell_id,
                                'MessageAction.list_message_type' => 'SELL'
                            ));
                            $cm = $this->MessageStatic->deleteAll(array(
                                'MessageStatic.message_id' => $sell_id,
                                'MessageStatic.list_message_type' => 'SELL'
                            ));

                            if ($q) {
                                $datasource->commit();
                                $response['status'] = 1;
                                $response['message'] = "Your sell deleted successfully";
                            } else {
                                $response['status'] = 0;
                                $response['message'] = "Sorry unable to delete";
                            }

                        } catch (Exception $e) {
                            $datasource->rollback();
                        }

                    } else {
                        $response['status'] = 0;
                        $response['message'] = "Sorry sell  not found";
                    }

                } else {
                    $response['status'] = 0;
                    $response['message'] = $this->Custom->getResponseMessage($result);
                }
            } else {
                $response['status'] = 0;
                $response['message'] = "Invalid request parameter";
            }

            echo json_encode($response);
            exit;
        }
    }

    /* created by mahendra */
    public function get_app_function_list()
    {

        $this->autoRender = false;
        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        if ($this->request->is('post')) {
            $thin_app_id = $data['thin_app_id'];
            $app_key = $data['app_key'];
            $user_id = $data['user_id'];
            if ($app_key != '' && $thin_app_id != '') {
                $fun_type_data = $this->AppFunctionalityType->find('all', array(
                    'recursive' => 3,
                    "conditions" => array(
                        "AppFunctionalityType.status" => 'Y',
                    ),
                    'contain' => array('AppEnableFunctionality')
                ));

                //pr($fun_type_data);die;

                if (!empty($fun_type_data)) {

                    $features = array();
                    foreach ($fun_type_data as $key => $value) {
                        $features[$key]['name'] = $value['AppFunctionalityType']['label_value'];
                        $features[$key]['is_enabled'] = "NO";
                        if (isset($value['AppEnableFunctionality']['id']) && !empty($value['AppEnableFunctionality']['id'])) {
                            $features[$key]['is_enabled'] = "YES";
                            foreach ($value['AppEnableFunctionality']['UserEnabledFunPermission'] as $key2 => $user_per) {
                                $features[$key]['user_permission'][$key2]['permission_id'] = $user_per['id'];
                                $features[$key]['user_permission'][$key2]['allow'] = $user_per['permission'];
                                $features[$key]['user_permission'][$key2]['label_text'] = $user_per['UserFunctionalityType']['label_text'];
                            }
                        } else {
                            $features[$key]['user_permission'][0]['permission_id'] = 0;
                            $features[$key]['user_permission'][0]['allow'] = '';
                            $features[$key]['user_permission'][0]['label_text'] = '';

                        }
                    }

                    $response['status'] = 1;
                    $response['data']['features'] = $features;
                    $response['message'] = "App features list found";
                } else {
                    $response['status'] = 0;
                    $response['message'] = "App is not found";
                }

            } else {
                $response['status'] = 0;
                $response['message'] = "Invalid request parameter";
            }
            echo json_encode($response);
            exit;
        }
    }

    public function change_user_permission()
    {


        $this->autoRender = false;
        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        if ($this->request->is('post')) {
            $thin_app_id = $data['thin_app_id'];
            $app_key = $data['app_key'];
            $user_permission_id = $data['user_permission_id'];
            $status = $data['status'];
            if ($app_key != '' && $thin_app_id != '' && $user_permission_id != '' && $status != '' && ($status == "YES" || $status == "NO")) {
                $is_permission_data = $this->UserEnabledFunPermission->find('first', array(
                    "conditions" => array(
                        "UserEnabledFunPermission.id" => $user_permission_id,
                    ),
                    'contain' => false
                ));
                if (!empty($is_permission_data)) {
                    $is_update = $this->UserEnabledFunPermission->updateAll(
                        array(
                            'UserEnabledFunPermission.permission' => $status
                        ),
                        array(
                            'UserEnabledFunPermission.id' => $user_permission_id,
                        )
                    );
                    if ($is_update == true) {
                        $response['status'] = 1;
                        $response['message'] = "Permission changed successfully";
                    } else {
                        $response['status'] = 0;
                        $response['message'] = "Sorry permission could not changed";
                    }
                } else {
                    $response['status'] = 0;
                    $response['message'] = "Invalid user permission";
                }

            } else {
                $response['status'] = 0;
                $response['message'] = "Invalid request parameter";
            }
            echo json_encode($response);
            exit;
        }
    }

    public function get_app_enabled_functionality()
    {

        $this->autoRender = false;
        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        if ($this->request->is('post')) {
            $thin_app_id = $data['thin_app_id'];
            $app_key = $data['app_key'];
            $user_id = $data['user_id'];
            if ($app_key != '' && $thin_app_id != '') {

                /* work for app functionlary*/
                $app_fun_type_data = $this->AppFunctionalityType->find('list', array(
                    "conditions" => array(
                        "AppFunctionalityType.status" => 'Y',
                    ),
                    'contain' => false,
                    'fields' => array('AppFunctionalityType.id', 'AppFunctionalityType.label_key')
                ));


                /* work for user functionlary*/
                $fun_type_data = $this->UserFunctionalityType->find('list', array(
                    "conditions" => array(
                        "UserFunctionalityType.status" => 'Y',
                    ),
                    'contain' => false,
                    'fields' => array('UserFunctionalityType.id', 'UserFunctionalityType.label_key')
                ));


                if (!empty($app_fun_type_data) && !empty($fun_type_data)) {
                    /* work for app functionlary*/
                    $app_enable_fun = $this->AppEnableFunctionality->find('list', array(
                        "conditions" => array(
                            "AppEnableFunctionality.thinapp_id" => $thin_app_id,
                        ),
                        'contain' => false,
                        'fields' => array('AppEnableFunctionality.app_functionality_type_id')
                    ));

                    /* work for user functionlary*/
                    $enable_fun = $this->UserEnabledFunPermission->find('list', array(
                        "conditions" => array(
                            "UserEnabledFunPermission.thinapp_id" => $thin_app_id,
                        ),
                        'contain' => false,
                        'fields' => array('UserEnabledFunPermission.user_functionality_type_id', 'UserEnabledFunPermission.permission')
                    ));


                    $features = array();
                    /* work for app functionlary*/
                    foreach ($app_fun_type_data as $key => $value) {
                        /* MUST CHANGE ***********************************CHANGE THIS YES PARAMETER TO NO */
                        $features[$value] = (in_array($key, $app_enable_fun)) ? 'YES' : "NO";
                    }

                    /* work for user functionlary*/
                    foreach ($fun_type_data as $key => $value) {
                        /* MUST CHANGE ***********************************CHANGE THIS YES PARAMETER TO NO */
                        $features[$value] = (array_key_exists($key, $enable_fun)) ? $enable_fun[$key] : "NO";
                    }

                    $response['status'] = 1;
                    $response['data']['features'] = $features;
                    $response['message'] = "App features list found";

                } else {
                    $response['status'] = 0;
                    $response['message'] = "App is not found";
                }


            } else {
                $response['status'] = 0;
                $response['message'] = "Invalid request parameter";
            }


            echo json_encode($response);
            exit;
        }
    }

    public function add_user_log()
    {

        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        if ($this->request->is('post')) {
            $response = array();
            $user_id = $data['user_id'];
            $thin_app_id = $data['thin_app_id'];
            $app_key = $data['app_key'];
            $device_id = $data['device_id'];

            if ($user_id != '' && $device_id != '' && $app_key != '' && $thin_app_id != '') {
                if (($result = $this->Custom->CheckIsValidApp($app_key, $thin_app_id)) && ($result == 1)) {
                    $this->AppUserStatic->create();
                    $this->AppUserStatic->set('user_id', $user_id);
                    $this->AppUserStatic->set('thin_app_id', $thin_app_id);
                    $this->AppUserStatic->set('device_id', $device_id);
                    if ($this->AppUserStatic->save()) {
                        $response['status'] = 1;
                        $response['message'] = "Success";
                    } else {
                        $response['status'] = 0;
                        $response['message'] = "Fail";
                    }
                } else {
                    $response['status'] = 0;
                    $response['message'] = $this->Custom->getResponseMessage($result);
                }
            } else {
                $response['status'] = 0;
                $response['message'] = "Invalid request parameter";
            }
            echo json_encode($response);
            exit;
        }
    }


    /*==========================================APPOINTMENT START====================================================*/
    /* appointment section start from here */


     /* this function add or update appointment staff */
    public function appointment_edit_staff_profile($data=null)
    {

        $return =true;
        if(empty($data)){
            $return = false;
            $request = file_get_contents("php://input");
            $data = json_decode($request, true);

        }
        if ($this->request->is('post')) {
            $datasource = $this->AppointmentStaff->getDataSource();
            try {
                $response = array();
                $thin_app_id = isset($data['thin_app_id']) ? $data['thin_app_id'] : "";
                $app_key = isset($data['app_key']) ? $data['app_key'] : "";
                $user_id = isset($data['user_id']) ? $data['user_id'] : "";
                $staff_id = isset($data['staff_id']) ? $data['staff_id'] : "";
                $name = isset($data['name']) ? $data['name'] : "";
                $sub_title = isset($data['sub_title']) ? $data['sub_title'] : "";
                $profile_photo = isset($data['image']) ? $data['image'] : "";
                $mobile = isset($data['mobile']) ? Custom::create_mobile_number($data['mobile']) : "";
                $country_code = isset($data['country_code']) ? $data['country_code'] : "+91";
                $description = isset($data['description']) ? $data['description'] : "";
                $enable_chat = isset($data['enable_chat']) ? $data['enable_chat'] : "NO";
                $show_mobile = isset($data['show_mobile']) ? $data['show_mobile'] : "";
                $show_fees = isset($data['show_fees']) ? $data['show_fees'] : "";
                $payment_mode = isset($data['payment_mode']) ? $data['payment_mode'] : "BOTH";
                $experience = isset($data['experience']) ? $data['experience'] : "";
                $show_appointment_time = isset($data['show_appointment_time']) ? $data['show_appointment_time'] : "YES";
                $show_appointment_token = isset($data['show_appointment_token']) ? $data['show_appointment_token'] : "YES";
                $allow_emergency_appointment = isset($data['allow_emergency_appointment']) ? $data['allow_emergency_appointment'] : "NO";
                $emergency_appointment_fee = isset($data['emergency_appointment_fee']) ? $data['emergency_appointment_fee'] : 0;
                $is_online_consulting = isset($data['is_online_consulting']) ? $data['is_online_consulting'] : "NO";
                $is_offline_consulting = isset($data['is_offline_consulting']) ? $data['is_offline_consulting'] : "YES";
            	$is_chat_consulting = isset($data['is_chat_consulting']) ? $data['is_chat_consulting'] : "NO";
                $is_audio_consulting = isset($data['is_audio_consulting']) ? $data['is_audio_consulting'] : "NO";

				if($thin_app_id==618){
                    $show_mobile = "NO";
                    $allow_emergency_appointment = "NO";
                    $emergency_appointment_fee = 0;
                    $is_online_consulting = "NO";
                    $is_offline_consulting = "YES";
                    $is_chat_consulting = "NO";
                    $is_audio_consulting = "NO";
                }
            
                if (empty($name)) {
                    $response['status'] = 0;
                    $response['message'] = 'Please enter doctor name';
                } else if (empty($sub_title)) {
                    $response['status'] = 0;
                    $response['message'] = 'Please enter education';
                } else if (empty($staff_id)) {
                    $response['status'] = 0;
                    $response['message'] = 'Please enter doctor id';
                } else if (empty($mobile)) {
                    $response['status'] = 0;
                    $response['message'] = 'Please enter valid mobile number';
                }else if ($payment_mode != "BOTH" && $payment_mode != "CASH" && $payment_mode != "ONLINE") {
                    $response['status'] = 0;
                    $response['message'] = 'Please select payment mode';
                } else {

                    $datasource->begin();
                    if (($result = $this->Custom->CheckIsValidApp($app_key, $thin_app_id)) && ($result == 1)) {

                        $user_profile = $this->AppointmentStaff->find('first', array(
                            "conditions" => array(
                                "AppointmentStaff.id" => $staff_id,
                                "AppointmentStaff.thinapp_id" => $thin_app_id,
                                "AppointmentStaff.status" => "ACTIVE",
                            ),
                            'contain' => false
                        ));
                        if (!empty($user_profile)) {

                            $duplicate_mobile = $this->AppointmentStaff->find('first', array(
                                "conditions" => array(
                                    "AppointmentStaff.mobile" => $mobile,
                                    "AppointmentStaff.thinapp_id" => $thin_app_id,
                                    "AppointmentStaff.status" => "ACTIVE",

                                ),
                                'fields' => array("AppointmentStaff.id"),
                                'contain' => false
                            ));

                            if (count($duplicate_mobile) == 0 || $duplicate_mobile['AppointmentStaff']['id'] == $staff_id) {
                                $this->AppointmentStaff->set('id', $staff_id);
                                $this->AppointmentStaff->set('name', $name);
                                $this->AppointmentStaff->set('sub_title', $sub_title);
                                $profile_photo = Custom::check_image_path_string($profile_photo);

                                if (!empty($profile_photo)) {
                                    $this->AppointmentStaff->set('profile_photo', $profile_photo);
                                }
                                if(!empty($show_mobile)){
                                    $this->AppointmentStaff->set('show_mobile', $show_mobile);
                                }
                                if(!empty($show_fees)){
                                    $this->AppointmentStaff->set('show_fees', $show_fees);
                                }

                                $this->AppointmentStaff->set('experience', $experience);
                                $this->AppointmentStaff->set('mobile', $mobile);
                                $this->AppointmentStaff->set('country_code', $country_code);
                                $this->AppointmentStaff->set('description', $description);
                                $this->AppointmentStaff->set('enable_chat', $enable_chat);
                                $this->AppointmentStaff->set('payment_mode', $payment_mode);
                                $this->AppointmentStaff->set('show_appointment_time', $show_appointment_time);
                                $this->AppointmentStaff->set('show_appointment_token', $show_appointment_token);
                                $this->AppointmentStaff->set('allow_emergency_appointment', $allow_emergency_appointment);
                                $this->AppointmentStaff->set('emergency_appointment_fee', $emergency_appointment_fee);
                                $this->AppointmentStaff->set('is_online_consulting', $is_online_consulting);
                                $this->AppointmentStaff->set('is_offline_consulting', $is_offline_consulting);
                            	$this->AppointmentStaff->set('is_audio_consulting', $is_audio_consulting);
                                $this->AppointmentStaff->set('is_chat_consulting', $is_chat_consulting);
                                if ($this->AppointmentStaff->save()) {
                                    $datasource->commit();
                                    $response['status'] = 1;
                                    $response['message'] = "Profile saved successfully";
                                    Custom::delete_doctor_cache($staff_id);
                                } else {
                                    $response['status'] = 0;
                                    $response['message'] = "Sorry profile could not save";
                                }
                            } else {
                                $response['status'] = 0;
                                $response['message'] = "Please use other mobile number";
                            }

                        } else {
                            $response['status'] = 0;
                            $response['message'] = "Sorry doctor not found";
                        }
                    } else {
                        $response['status'] = 0;
                        $response['message'] = $this->Custom->getResponseMessage($result);
                    }
                }
            } catch (Exception $e) {
                $datasource->rollback();
                $response['status'] = 0;
                $response['message'] = $e->getMessage();
            }

            if($return===true){
                return json_encode($response);
            }else{
                echo json_encode($response);
                exit;
            }


        } else {
            exit();
        }
    }

    /* this function add or update appointment staff */
    public function appointment_add_staff_profile($data=null)
    {

        $return = true;
        if(empty($data)){
            $return = false;
            $request = file_get_contents("php://input");
            $data = json_decode($request, true);
        }

        if ($this->request->is('post')) {
            $datasource = $this->AppointmentStaff->getDataSource();
            try {
                $response = array();
                $thin_app_id = isset($data['thin_app_id']) ? $data['thin_app_id'] : "";
                $app_key = isset($data['app_key']) ? $data['app_key'] : "";
                $user_id = isset($data['user_id']) ? $data['user_id'] : "";
                $name = isset($data['name']) ? $data['name'] : "";
                $sub_title = isset($data['sub_title']) ? $data['sub_title'] : "";
                $profile_photo = isset($data['profile_photo']) ? $data['profile_photo'] : "";
                $mobile = isset($data['mobile']) ? Custom::create_mobile_number($data['mobile']) : "";
                $address = isset($data['address']) ? $data['address'] : "";
                $country_code = isset($data['country_code']) ? $data['country_code'] : "+91";
                $description = isset($data['description']) ? $data['description'] : "";
                $enable_chat = isset($data['enable_chat']) ? $data['enable_chat'] : "NO";
                $payment_mode = isset($data['payment_mode']) ? $data['payment_mode'] : "BOTH";
                $experience = isset($data['experience']) ? $data['experience'] : "";
                $show_mobile = isset($data['show_mobile']) ? $data['show_mobile'] : "";
                $show_fees = isset($data['show_fees']) ? $data['show_fees'] : "";
                $show_appointment_time = isset($data['show_appointment_time']) ? $data['show_appointment_time'] : "YES";
                $is_online_consulting = isset($data['is_online_consulting']) ? $data['is_online_consulting'] : "NO";
                $is_offline_consulting = isset($data['is_offline_consulting']) ? $data['is_offline_consulting'] : "YES";
                $is_chat_consulting = isset($data['is_chat_consulting']) ? $data['is_chat_consulting'] : "NO";
                $is_audio_consulting = isset($data['is_audio_consulting']) ? $data['is_audio_consulting'] : "NO";

                if (empty($name)) {
                    $response['status'] = 0;
                    $response['message'] = 'Please enter name';
                } else if (empty($sub_title)) {
                    $response['status'] = 0;
                    $response['message'] = 'Please enter education';
                }  else if (empty($mobile)) {
                    $response['status'] = 0;
                    $response['message'] = 'Please enter valid mobile number';
                }else if ($payment_mode != "BOTH" && $payment_mode != "CASH" && $payment_mode != "ONLINE") {
                    $response['status'] = 0;
                    $response['message'] = 'Please select payment mode';
                } else {


                    $datasource->begin();
                    if (($result = $this->Custom->CheckIsValidApp($app_key, $thin_app_id)) && ($result == 1)) {
                        $working_days = $this->AppointmentDayTime->find('all', array(
                            "conditions" => array(
                                "AppointmentDayTime.status" => "ACTIVE"
                            ),
                            'contain' => false
                        ));
                        if (!empty($working_days)) {

                            $user_profile = $this->AppointmentStaff->find('first', array(
                                "conditions" => array(
                                    "AppointmentStaff.mobile" => $mobile,
                                    "AppointmentStaff.thinapp_id" => $thin_app_id,
                                ),
                                'contain' => false,
                                'order' => array('AppointmentStaff.id'=>'desc')
                            ));
                            if (empty($user_profile)) {

                                $is_allowed_add_doc = Custom::is_allowed_add_doc($thin_app_id);
                                if ($is_allowed_add_doc > 0) {
                                    $response['status'] = 0;
                                    $response['message'] = "You have opted $is_allowed_add_doc doctor's package,Kindly inactive existing doctor to add new";
                                }else {

                                    $register_staff = Custom::get_mobile_register_staff($mobile,$thin_app_id);
                                    if(empty($register_staff)){
                                        $app_user_id = Custom::create_user($thin_app_id, $mobile, $name);
                                        $password = md5(substr($mobile, -10));
                                        $this->AppointmentStaff->create();
                                        $this->AppointmentStaff->set('user_id', $app_user_id);
                                        $this->AppointmentStaff->set('thinapp_id', $thin_app_id);
                                        $this->AppointmentStaff->set('experience', $experience);
                                        $this->AppointmentStaff->set('name', $name);
                                        $this->AppointmentStaff->set('sub_title', $sub_title);
                                        $profile_photo = Custom::check_image_path_string($profile_photo);
                                        if (!empty($profile_photo)) {
                                            $this->AppointmentStaff->set('profile_photo', $profile_photo);
                                        }
                                        if (!empty($show_mobile)) {
                                            $this->AppointmentStaff->set('show_mobile', $show_mobile);
                                        }
                                        if (!empty($show_fees)) {
                                            $this->AppointmentStaff->set('show_fees', $show_fees);
                                        }

                                        $this->AppointmentStaff->set('address', $address);
                                        $this->AppointmentStaff->set('mobile', $mobile);
                                        $this->AppointmentStaff->set('password', $password);
                                        $this->AppointmentStaff->set('country_code', $country_code);
                                        $this->AppointmentStaff->set('description', $description);
                                        $this->AppointmentStaff->set('enable_chat', $enable_chat);
                                        $this->AppointmentStaff->set('payment_mode', $payment_mode);
                                        $this->AppointmentStaff->set('show_appointment_time', $show_appointment_time);
                                        $this->AppointmentStaff->set('is_online_consulting', $is_online_consulting);
                                        $this->AppointmentStaff->set('is_offline_consulting', $is_offline_consulting);
                                        $this->AppointmentStaff->set('is_audio_consulting', $is_audio_consulting);
                                        $this->AppointmentStaff->set('is_chat_consulting', $is_chat_consulting);
                                        if ($this->AppointmentStaff->save()) {

                                            $app_staff_id = $this->AppointmentStaff->getLastInsertId();
                                            $staff_hours = array();
                                            foreach ($working_days as $key => $days) {
                                                $staff_hours[$key]['thinapp_id'] = $thin_app_id;
                                                $staff_hours[$key]['user_id'] = $user_id;
                                                $staff_hours[$key]['appointment_staff_id'] = $app_staff_id;
                                                $staff_hours[$key]['appointment_day_time_id'] = $days['AppointmentDayTime']['id'];
                                                $staff_hours[$key]['time_from'] = APPOINTMENT_WORKING_START_TIME;
                                                $staff_hours[$key]['time_to'] = APPOINTMENT_WORKING_END_TIME;
                                            }
                                            if ($this->AppointmentStaffHour->saveAll($staff_hours)) {
                                                $datasource->commit();
                                                $response['status'] = 1;
                                                $response['message'] = "Profile saved successfully";
                                                $response['data']['staff_id'] = $app_staff_id;
                                            } else {
                                                $response['status'] = 0;
                                                $response['message'] = "Sorry profile could not save";
                                            }
                                            //$response['message'] = "Profile add successfully";
                                        } else {
                                            $response['status'] = 0;
                                            $response['message'] = "Sorry profile could not save";
                                        }
                                    }else{
                                        $status = $register_staff['status'];
                                        $staff_type = strtolower($register_staff['staff_type']);
                                        $message = "This mobile number already register as $staff_type. ";
                                        if($status=='INACTIVE'){
                                            $message .= "You can active this mobile number from web panel";
                                        }
                                        $response['status'] = 0;
                                        $response['message'] = $message;
                                    }


                                }
                            } else {

                                $this->AppointmentStaff->set('id', $user_profile['AppointmentStaff']['id']);
                                $this->AppointmentStaff->set('status', 'ACTIVE');
                                $this->AppointmentStaff->set('experience', $experience);
                                $this->AppointmentStaff->set('name', $name);
                                $this->AppointmentStaff->set('sub_title', $sub_title);
                                $profile_photo = Custom::check_image_path_string($profile_photo);
                                if (!empty($profile_photo)) {
                                    $this->AppointmentStaff->set('profile_photo', $profile_photo);
                                }
                                if(!empty($show_mobile)){
                                    $this->AppointmentStaff->set('show_mobile', $show_mobile);
                                }
                                if(!empty($show_fees)){
                                    $this->AppointmentStaff->set('show_fees', $show_fees);
                                }

                                $this->AppointmentStaff->set('address', $address);
                                $this->AppointmentStaff->set('description', $description);
                                $this->AppointmentStaff->set('enable_chat', $enable_chat);
                                $this->AppointmentStaff->set('payment_mode', $payment_mode);
                                $this->AppointmentStaff->set('is_online_consulting', $is_online_consulting);
                                $this->AppointmentStaff->set('is_offline_consulting', $is_offline_consulting);
                                if ($this->AppointmentStaff->save()) {
                                    $datasource->commit();
                                    $response['status'] = 1;
                                    $response['message'] = "Profile saved successfully";
                                    $response['data']['staff_id'] = $user_profile['AppointmentStaff']['id'];
                                    $file_name = Custom::encrypt_decrypt('encrypt',"doctor_".$user_profile['AppointmentStaff']['id']);
                                    WebservicesFunction::deleteJson(array($file_name), 'doctor');

                                }else {
                                    $response['status'] = 0;
                                    $response['message'] = "Sorry profile could not save";
                                }


                             }
                        } else {
                            $response['status'] = 0;
                            $response['message'] = "Sorry no working day available";
                        }
                    } else {
                        $response['status'] = 0;
                        $response['message'] = $this->Custom->getResponseMessage($result);
                    }


                }
            } catch (Exception $e) {
                $datasource->rollback();
                $response['status'] = 0;
                $response['message'] = $e->getMessage();
            }

            if($return === true){
                return json_encode($response);
            }else{
                echo json_encode($response);
            }
            exit;

        } else {
            exit();
        }
    }



    /* this function add appointment service */
    public function add_appointment_service()
    {
        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        if ($this->request->is('post')) {

            $datasource = $this->AppointmentService->getDataSource();
            try {
                $datasource->begin();
                $response = array();
                $thin_app_id = isset($data['thin_app_id']) ? $data['thin_app_id'] : "";
                $app_key = isset($data['app_key']) ? $data['app_key'] : "";
                $user_id = isset($data['user_id']) ? $data['user_id'] : "";
                $name = isset($data['name']) ? trim($data['name']) : "";
                $service_amount = isset($data['service_amount']) ? $data['service_amount'] : "";
                $service_image = isset($data['service_image']) ? $data['service_image'] : "";
                $service_slot_duration = isset($data['service_slot_duration']) ? $data['service_slot_duration'] : "";
                $is_online_service = isset($data['is_online_service']) ? $data['is_online_service'] : "";
                $online_service_cost = isset($data['online_service_cost']) ? $data['online_service_cost'] : "";
                $video_consulting_amount = isset($data['video_consulting_amount']) ? $data['video_consulting_amount'] : "0";
                $audio_consulting_amount = isset($data['audio_consulting_amount']) ? $data['audio_consulting_amount'] : "0";
                $chat_consulting_amount = isset($data['chat_consulting_amount']) ? $data['chat_consulting_amount'] : "0";

                $service_validity_time = isset($data['service_validity_time']) ? empty($data['service_validity_time'])?0:$data['service_validity_time']: 0;

                if (empty($thin_app_id)) {
                    $response['status'] = 0;
                    $response['message'] = 'Invalid thinapp id';
                } else if (empty($app_key)) {
                    $response['status'] = 0;
                    $response['message'] = 'Invalid app key';
                } else if (empty($name)) {
                    $response['status'] = 0;
                    $response['message'] = 'Please enter service name';
                } else if (empty($service_amount) && $service_amount != 0) {
                    $response['status'] = 0;
                    $response['message'] = 'Please enter service amount';
                } else if (empty($service_slot_duration)) {
                    $response['status'] = 0;
                    $response['message'] = 'Please select service duration';
                } else if (empty($is_online_service)) {
                    $response['status'] = 0;
                    $response['message'] = 'Is online service value cannot null';
                } else if (empty($online_service_cost) && $online_service_cost != 0) {
                    $response['status'] = 0;
                    $response['message'] = 'Please enter online service cost';
                } else {
                    $user_profile = $this->AppointmentService->find('count', array(
                        "conditions" => array(
                            "AppointmentService.user_id" => $user_id,
                            "AppointmentService.thinapp_id" => $thin_app_id,
                            "AppointmentService.name" => trim($name),
                            "AppointmentService.status" => 'ACTIVE',

                        ),
                        'contain' => false
                    ));
                    if ($user_profile == 0) {
                        if (($result = $this->Custom->CheckIsValidApp($app_key, $thin_app_id)) && ($result == 1)) {

                            $this->AppointmentService->create();
                            $this->AppointmentService->set('user_id', $user_id);
                            $this->AppointmentService->set('thinapp_id', $thin_app_id);
                            $this->AppointmentService->set('name', $name);
                            $this->AppointmentService->set('service_amount', $service_amount);
                            $service_image = Custom::check_image_path_string($service_image);
                            if(!empty($service_image)) {
                                $this->AppointmentService->set('service_image', $service_image);
                            }

                            $this->AppointmentService->set('service_slot_duration', $service_slot_duration);
                            $this->AppointmentService->set('is_online_service', $is_online_service);
                            $this->AppointmentService->set('online_service_cost', $online_service_cost);
                            $this->AppointmentService->set('service_validity_time', $service_validity_time);
                            $this->AppointmentService->set('video_consulting_amount', $video_consulting_amount);
                            $this->AppointmentService->set('audio_consulting_amount', $audio_consulting_amount);
                            $this->AppointmentService->set('chat_consulting_amount', $chat_consulting_amount);


                            if ($this->AppointmentService->save()) {
                                $service_id = $this->AppointmentService->getLastInsertId();
                                $datasource->commit();
                                $response['status'] = 1;
                                $response['message'] = "Service add successfully";
                                $response['data']['service_id'] = $service_id;

                            } else {
                                $response['status'] = 0;
                                $response['message'] = "Sorry service could not add";
                            }
                        } else {
                            $response['status'] = 0;
                            $response['message'] = $this->Custom->getResponseMessage($result);
                        }
                    } else {
                        $response['status'] = 0;
                        $response['message'] = "Service already exists";
                    }
                }
            } catch (Exception $e) {
                $datasource->rollback();
                $response['status'] = 0;
                $response['message'] = $e->getMessage();
            }

            echo json_encode($response);
            exit;

        } else {
            exit();
        }
    }

    /* this function add appointment service */
    public function add_appointment_staff_to_service()
    {
        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        if ($this->request->is('post')) {

            $datasource = $this->AppointmentStaffService->getDataSource();
            try {
                $response = array();
                $thin_app_id = isset($data['thin_app_id']) ? $data['thin_app_id'] : "";
                $app_key = isset($data['app_key']) ? $data['app_key'] : "";
                $user_id = isset($data['user_id']) ? $data['user_id'] : "";
                $service_id = isset($data['service_id']) ? $data['service_id'] : "";
                $staff_ids_array = isset($data['staff_ids_array']) ? $data['staff_ids_array'] : "";
                if (empty($thin_app_id)) {
                    $response['status'] = 0;
                    $response['message'] = 'Invalid thinapp id';
                } else if (empty($app_key)) {
                    $response['status'] = 0;
                    $response['message'] = 'Invalid app key';
                } else if (empty($service_id)) {
                    $response['status'] = 0;
                    $response['message'] = 'Please enter service id';
                } else if (empty($staff_ids_array)) {
                    $response['status'] = 0;
                    $response['message'] = 'Please enter staff';
                } else {
                    $datasource->begin();
                    if (($result = $this->Custom->CheckIsValidApp($app_key, $thin_app_id)) && ($result == 1)) {
                        $save_array = array();
                        if (!empty($staff_ids_array)) {


                            $del_result = $this->AppointmentStaffService->deleteAll(array(
                                'AppointmentStaffService.appointment_service_id' => $service_id,
                                'AppointmentStaffService.thinapp_id' => $thin_app_id
                            ));


                            foreach ($staff_ids_array as $key => $id) {
                                    $save_array[$key]['thinapp_id'] = $thin_app_id;
                                    $save_array[$key]['appointment_service_id'] = $service_id;
                                    $save_array[$key]['appointment_staff_id'] = $id;
                            }
                            if ($this->AppointmentStaffService->saveAll($save_array)) {
                                $datasource->commit();
                                $response['status'] = 1;
                                $response['message'] = "Staff add to service successfully";
                            	 Custom::delete_hospital_cache($thin_app_id);
                            } else {
                                $response['status'] = 0;
                                $response['message'] = "Sorry staff could not add to service";
                            }
                        } else {
                            $response['status'] = 0;
                            $response['message'] = "Please select at least one staff memeber";
                        }

                    } else {
                        $response['status'] = 0;
                        $response['message'] = $this->Custom->getResponseMessage($result);
                    }
                }
            } catch (Exception $e) {
                $datasource->rollback();
                $response['status'] = 0;
                $response['message'] = $e->getMessage();
            }

            echo json_encode($response);
            exit;

        } else {
            exit();
        }
    }


    /* this function add appointment service */
    public function edit_appointment_service()
    {
        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        if ($this->request->is('post')) {
            try {

                $response = array();
                $thin_app_id = isset($data['thin_app_id']) ? $data['thin_app_id'] : "";
                $app_key = isset($data['app_key']) ? $data['app_key'] : "";
                if (($result = $this->Custom->CheckIsValidApp($app_key, $thin_app_id)) && ($result == 1)) {
                    $user_id = isset($data['user_id']) ? $data['user_id'] : "";
                    $service_id = isset($data['service_id']) ? $data['service_id'] : "";
                    $name = isset($data['name']) ? trim($data['name']) : "";
                    $service_amount = isset($data['service_amount']) ? $data['service_amount'] : "";
                    $service_slot_duration = isset($data['service_slot_duration']) ? $data['service_slot_duration'] : "";
                    $is_online_service = isset($data['is_online_service']) ? $data['is_online_service'] : "";
                    $online_service_cost = isset($data['online_service_cost']) ? $data['online_service_cost'] : "";
                    $service_validity_time = isset($data['service_validity_time']) ? $data['service_validity_time'] : 0;
                    $service_image = isset($data['service_image']) ? $data['service_image'] : 0;
                    if (empty($thin_app_id)) {
                        $response['status'] = 0;
                        $response['message'] = 'Invalid thinapp id';
                    } else if (empty($app_key)) {
                        $response['status'] = 0;
                        $response['message'] = 'Invalid app key';
                    } else if (empty($service_id)) {
                        $response['status'] = 0;
                        $response['message'] = 'Enter service id';
                    } else if (empty($name)) {
                        $response['status'] = 0;
                        $response['message'] = 'Please enter service name';
                    } else if (empty($service_amount)) {
                        $response['status'] = 0;
                        $response['message'] = 'Please enter service amount';
                    } else if (empty($service_slot_duration)) {
                        $response['status'] = 0;
                        $response['message'] = 'Please select service duration';
                    } else if (empty($is_online_service)) {
                        $response['status'] = 0;
                        $response['message'] = 'Is online service value cannot null';
                    } else if (empty($online_service_cost)) {
                        $response['status'] = 0;
                        $response['message'] = 'Please enter online service cost';
                    } else {

                        $is_service = $this->AppointmentService->find('count', array(
                            "conditions" => array(
                                "AppointmentService.user_id" => $user_id,
                                "AppointmentService.thinapp_id" => $thin_app_id,
                                "AppointmentService.id" => $service_id
                            ),
                            'contain' => false
                        ));
                        if ($is_service == 1) {
                            $this->AppointmentService->set('id', $service_id);
                            $this->AppointmentService->set('name', $name);
                            $this->AppointmentService->set('service_amount', $service_amount);
                            $this->AppointmentService->set('service_slot_duration', $service_slot_duration);
                            $this->AppointmentService->set('is_online_service', $is_online_service);
                            if(!empty($service_image)){

                                $this->AppointmentService->set('service_image', $service_image);
                            }
                            $this->AppointmentService->set('online_service_cost', $online_service_cost);
                            $this->AppointmentService->set('service_validity_time', $service_validity_time);
                            if ($this->AppointmentService->save()) {
                                $response['status'] = 1;
                                $response['message'] = "Service update successfully";
                            	$res = Custom::deleteDoctorCacheViaServiceId($service_id);
                            } else {
                                $response['status'] = 0;
                                $response['message'] = "Sorry service could not update";
                            }

                        } else {
                            $response['status'] = 0;
                            $response['message'] = "Service not found";
                        }
                    }
                } else {
                    $response['status'] = 0;
                    $response['message'] = $this->Custom->getResponseMessage($result);
                }
            } catch (Exception $e) {
                $response['status'] = 0;
                $response['message'] = $e->getMessage();
            }

            echo json_encode($response);
            exit;

        } else {
            exit();
        }
    }

    /* this function add appointment service */
    public function single_edit_appointment_service()
    {
        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        if ($this->request->is('post')) {
            try {
                $response = array();
                $thin_app_id = isset($data['thin_app_id']) ? $data['thin_app_id'] : "";
                $app_key = isset($data['app_key']) ? $data['app_key'] : "";
                if (($result = $this->Custom->CheckIsValidApp($app_key, $thin_app_id)) && ($result == 1)) {
                    $user_id = isset($data['user_id']) ? $data['user_id'] : "";
                    $service_id = isset($data['service_id']) ? $data['service_id'] : "";
                    $field_name = isset($data['field_name']) ? $data['field_name'] : "";
                    $field_value = isset($data['field_value']) ? $data['field_value'] : "";

                    if (empty($thin_app_id)) {
                        $response['status'] = 0;
                        $response['message'] = 'Invalid thinapp id';
                    } else if (empty($app_key)) {
                        $response['status'] = 0;
                        $response['message'] = 'Invalid app key';
                    } else if (empty($service_id)) {
                        $response['status'] = 0;
                        $response['message'] = 'Enter service id';
                    } else if (empty($user_id)) {
                        $response['status'] = 0;
                        $response['message'] = 'Please enter service name';
                    } else if (empty($field_name)) {
                        $response['status'] = 0;
                        $response['message'] = 'Please enter field name';
                    } else if (empty($field_value) && $field_value != 0) {
                        $response['status'] = 0;
                        $response['message'] = 'Please enter value name';
                    } else {

                        $is_service = $this->AppointmentService->find('first', array(
                            "conditions" => array(
                                "AppointmentService.user_id" => $user_id,
                                "AppointmentService.thinapp_id" => $thin_app_id,
                                "AppointmentService.id" => $service_id
                            ),
                            'contain' => false
                        ));
                        if (count($is_service) == 1) {
                            $is_duplicate = false;
                            if (($result = $this->Custom->CheckIsValidApp($app_key, $thin_app_id)) && ($result == 1)) {

                                if ($field_name == 'name') {
                                    $duplicate = $this->AppointmentService->find('count', array(
                                        "conditions" => array(
                                            "AppointmentService.user_id" => $user_id,
                                            "AppointmentService.thinapp_id" => $thin_app_id,
                                            "AppointmentService.name" => trim($field_value),
                                            "AppointmentService.status" => 'ACTIVE',
                                            "AppointmentService.id !=" => $service_id
                                        ),
                                        'contain' => false
                                    ));
                                    if ($duplicate > 0) {
                                        $is_duplicate = true;
                                    }
                                }
                                if (!$is_duplicate) {
                                    $this->AppointmentService->set('id', $service_id);
                                    $this->AppointmentService->set($field_name, $field_value);
                                    if ($this->AppointmentService->save()) {
                                        $response['status'] = 1;
                                        $response['message'] = "Service update successfully";
                                        $response['data']['field_name'] = $field_name;
                                        $response['data']['field_value'] = $field_value;
                                    	$res = Custom::deleteDoctorCacheViaServiceId($service_id);

                                    } else {
                                        $response['status'] = 0;
                                        $response['message'] = "Sorry service could not update";
                                    }
                                } else {
                                    $response['status'] = 0;
                                    $response['message'] = "Service already exists";
                                }


                            } else {
                                $response['status'] = 0;
                                $response['message'] = $this->Custom->getResponseMessage($result);
                            }
                        } else {
                            $response['status'] = 0;
                            $response['message'] = "Service not found";
                        }
                    }
                } else {
                    $response['status'] = 0;
                    $response['message'] = $this->Custom->getResponseMessage($result);
                }
            } catch (Exception $e) {
                $response['status'] = 0;
                $response['message'] = $e->getMessage();
            }

            echo json_encode($response);
            exit;

        } else {
            exit();
        }
    }

    /* this function add appointment service */
    public function view_appointment_service()
    {
        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        if ($this->request->is('post')) {
            try {

                $response = array();
                $thin_app_id = isset($data['thin_app_id']) ? $data['thin_app_id'] : "";
                $app_key = isset($data['app_key']) ? $data['app_key'] : "";
                if (($result = $this->Custom->CheckIsValidApp($app_key, $thin_app_id)) && ($result == 1)) {
                    $user_id = isset($data['user_id']) ? $data['user_id'] : "";
                    $service_id = isset($data['service_id']) ? $data['service_id'] : "";
                    if (empty($thin_app_id)) {
                        $response['status'] = 0;
                        $response['message'] = 'Invalid thinapp id';
                    } else if (empty($app_key)) {
                        $response['status'] = 0;
                        $response['message'] = 'Invalid app key';
                    } else if (empty($service_id)) {
                        $response['status'] = 0;
                        $response['message'] = 'Enter service id';
                    } else if (empty($user_id)) {
                        $response['status'] = 0;
                        $response['message'] = 'Please enter user_id name';
                    } else {
                        $is_service = $this->AppointmentService->find('first', array(
                            "conditions" => array(
                                "AppointmentService.user_id" => $user_id,
                                "AppointmentService.thinapp_id" => $thin_app_id,
                                "AppointmentService.id" => $service_id
                            ),
                            'contain' => array('AppointmentStaffService', "AppointmentCategoryService")
                        ));
                        if (!empty($is_service)) {

                            $total_booking = $this->Custom->count_service_upcoming_appointment($service_id, $thin_app_id);
                            $data_array = array();
                            /* THIS CODE GET STAFF LIST ASSOCIATED WITH THIS SERVER*/
                            $app_staff_services_ids = array_column($is_service['AppointmentStaffService'], 'id');
                            $total_staff_count = count($app_staff_services_ids);
                            $data_array['staff_count'] = $total_staff_count;
                            if ($total_staff_count > 0) {
                                $staff_ser_list = $this->AppointmentStaffService->find('all', array(
                                    "conditions" => array(
                                        "AppointmentStaffService.id" => $app_staff_services_ids
                                    ),
                                    'contain' => array("AppointmentStaff"),
                                    'fields' => array('AppointmentStaffService.*', 'AppointmentStaff.id', 'AppointmentStaff.name', 'AppointmentStaff.profile_photo')
                                ));

                                foreach ($staff_ser_list as $key => $staff_list) {
                                    $data_array['staff_list'][$key]['id'] = $staff_list['AppointmentStaff']['id'];
                                    $data_array['staff_list'][$key]['name'] = $staff_list['AppointmentStaff']['name'];
                                    $data_array['staff_list'][$key]['profile_photo'] = $staff_list['AppointmentStaff']['profile_photo'];
                                    $data_array['staff_list'][$key]['appointment_staff_service_id'] = $staff_list['AppointmentStaffService']['id'];
                                    $data_array['staff_list'][$key]['appointment_staff_id'] = $staff_list['AppointmentStaffService']['appointment_staff_id'];
                                    $data_array['staff_list'][$key]['appointment_service_id'] = $staff_list['AppointmentStaffService']['appointment_service_id'];
                                }

                            } else {
                                $data_array['staff_list'][0]['id'] = 0;
                                $data_array['staff_list'][0]['appointment_staff_service_id'] = 0;
                                $data_array['staff_list'][0]['appointment_staff_id'] = 0;
                                $data_array['staff_list'][0]['appointment_service_id'] = 0;
                                $data_array['staff_list'][0]['name'] = "";
                                $data_array['staff_list'][0]['profile_photo'] = "";
                            }


                            /* THIS CODE GET CATEGORY LIST ASSOCIATED WITH THIS SERVER*/
                            $app_staff_services_ids = array_column($is_service['AppointmentCategoryService'], 'id');
                            $total_staff_count = count($app_staff_services_ids);
                            $data_array['category_count'] = $total_staff_count;
                            if ($total_staff_count > 0) {
                                $category_list = $this->AppointmentCategoryService->find('all', array(
                                    "conditions" => array(
                                        "AppointmentCategoryService.id" => $app_staff_services_ids
                                    ),
                                    'contain' => array("AppointmentCategory"),
                                    'fields' => array('AppointmentCategory.id', 'AppointmentCategory.name')
                                ));
                                $category = array();
                                foreach ($category_list as $key => $staff_list) {
                                    $category[$key]['id'] = (int)$staff_list['AppointmentCategory']['id'];
                                    $category[$key]['name'] = $staff_list['AppointmentCategory']['name'];
                                }
                                $data_array['category_list'] = $category;
                            } else {
                                $data_array['category_list'][0]['id'] = 0;
                                $data_array['category_list'][0]['name'] = "";
                            }
                            $response['status'] = 1;
                            $response['message'] = "Service detail found";
                            $data_array['id'] = $is_service['AppointmentService']['id'];
                            $data_array['name'] = $is_service['AppointmentService']['name'];
                            $data_array['service_amount'] = $is_service['AppointmentService']['service_amount'];
                            $data_array['video_consulting_amount'] = $is_service['AppointmentService']['video_consulting_amount'];
                            $data_array['audio_consulting_amount'] = $is_service['AppointmentService']['audio_consulting_amount'];
                            $data_array['chat_consulting_amount'] = $is_service['AppointmentService']['chat_consulting_amount'];
                            $data_array['service_image'] = $is_service['AppointmentService']['service_image'];
                            $data_array['service_slot_duration'] = $is_service['AppointmentService']['service_slot_duration'];
                            $data_array['is_online_service'] = $is_service['AppointmentService']['is_online_service'];
                            $data_array['online_service_cost'] = $is_service['AppointmentService']['online_service_cost'];
                            $data_array['update_duration'] = ($total_booking == 0) ? "YES" : "NO";
                            $response['data']['service_detail'] = $data_array;

                        } else {
                            $response['status'] = 0;
                            $response['message'] = "Service not found";
                        }
                    }
                } else {
                    $response['status'] = 0;
                    $response['message'] = $this->Custom->getResponseMessage($result);
                }

            } catch (Exception $e) {
                $response['status'] = 0;
                $response['message'] = $e->getMessage();
            }

            echo json_encode($response);
            exit;

        } else {
            exit();
        }
    }

    /* this function add appointment service */
    public function remove_staff_from_service()
    {
        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        if ($this->request->is('post')) {
            try {
                $response = array();
                $thin_app_id = isset($data['thin_app_id']) ? $data['thin_app_id'] : "";
                $app_key = isset($data['app_key']) ? $data['app_key'] : "";
                if (($result = $this->Custom->CheckIsValidApp($app_key, $thin_app_id)) && ($result == 1)) {
                    $user_id = isset($data['user_id']) ? $data['user_id'] : "";
                    $service_id = isset($data['service_id']) ? $data['service_id'] : "";
                    $status = isset($data['status']) ? $data['status'] : "";
                    $appointment_staff_id = isset($data['appointment_staff_id']) ? $data['appointment_staff_id'] : "";
                    $appointment_staff_service_id = isset($data['appointment_staff_service_id']) ? $data['appointment_staff_service_id'] : "";
                    if (empty($thin_app_id)) {
                        $response['status'] = 0;
                        $response['message'] = 'Invalid thinapp id';
                    } else if (empty($app_key)) {
                        $response['status'] = 0;
                        $response['message'] = 'Invalid app key';
                    } else if (empty($service_id)) {
                        $response['status'] = 0;
                        $response['message'] = 'Enter service id';
                    } else if (empty($user_id)) {
                        $response['status'] = 0;
                        $response['message'] = 'Please enter service name';
                    } else if (empty($status)) {
                        $response['status'] = 0;
                        $response['message'] = 'Please enter status';
                    } else if (empty($appointment_staff_id)) {
                        $response['status'] = 0;
                        $response['message'] = 'Invalid staff id';
                    } else if (empty($appointment_staff_service_id)) {
                        $response['status'] = 0;
                        $response['message'] = 'Invalid appointment staff service id';
                    } else {
                        $is_service = $this->Custom->check_staff_has_upcoming_appointment($appointment_staff_id, $service_id, $thin_app_id);
                        if ($is_service) {
                            $this->AppointmentStaffService->set('id', $appointment_staff_service_id);
                            $this->AppointmentStaffService->set('status', 'INACTIVE');
                            if ($this->AppointmentStaffService->save()) {
                                $response['status'] = 1;
                                $response['message'] = "Staff removed from service successfully";
                            } else {
                                $response['status'] = 0;
                                $response['message'] = "Sorry staff could not removed";
                            }
                        } else {
                            $response['status'] = 0;
                            $response['message'] = "Sorry this staff has upcoming appointment";
                        }
                    }
                } else {
                    $response['status'] = 0;
                    $response['message'] = $this->Custom->getResponseMessage($result);
                }
            } catch (Exception $e) {
                $response['status'] = 0;
                $response['message'] = $e->getMessage();
            }

            echo json_encode($response);
            exit;

        } else {
            exit();
        }
    }

    /* this function add appointment service */
    public function remove_category_from_service()
    {
        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        if ($this->request->is('post')) {
            try {
                $response = array();
                $thin_app_id = isset($data['thin_app_id']) ? $data['thin_app_id'] : "";
                $app_key = isset($data['app_key']) ? $data['app_key'] : "";
                if (($result = $this->Custom->CheckIsValidApp($app_key, $thin_app_id)) && ($result == 1)) {
                    $user_id = isset($data['user_id']) ? $data['user_id'] : "";
                    $service_id = isset($data['service_id']) ? $data['service_id'] : "";
                    $category_id = isset($data['category_id']) ? $data['category_id'] : "";

                    if (empty($service_id)) {
                        $response['status'] = 0;
                        $response['message'] = 'Enter service id';
                    } else if (empty($category_id)) {
                        $response['status'] = 0;
                        $response['message'] = 'Please enter category id';
                    } else if (empty($user_id)) {
                        $response['status'] = 0;
                        $response['message'] = 'Please enter user id';
                    } else {

                        $result = $this->AppointmentCategoryService->updateAll(
                            array('AppointmentCategoryService.status' => "'INACTIVE'"),
                            array(
                                'AppointmentCategoryService.appointment_category_id' => $category_id,
                                'AppointmentCategoryService.appointment_service_id' => $service_id,
                                'AppointmentCategoryService.thinapp_id' => $thin_app_id
                            )
                        );
                        if ($result) {
                            $response['status'] = 1;
                            $response['message'] = "Category removed from service successfully";
                        } else {
                            $response['status'] = 0;
                            $response['message'] = "Sorry category could not removed";
                        }
                    }
                } else {
                    $response['status'] = 0;
                    $response['message'] = $this->Custom->getResponseMessage($result);
                }
            } catch (Exception $e) {
                $response['status'] = 0;
                $response['message'] = $e->getMessage();
            }

            echo json_encode($response);
            exit;

        } else {
            exit();
        }
    }


    /* this function remove place from  appointment service */
    public function remove_place_from_service()
    {
        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        if ($this->request->is('post')) {
            try {
                $response = array();
                $thin_app_id = isset($data['thin_app_id']) ? $data['thin_app_id'] : "";
                $app_key = isset($data['app_key']) ? $data['app_key'] : "";
                if (($result = $this->Custom->CheckIsValidApp($app_key, $thin_app_id)) && ($result == 1)) {
                    $user_id = isset($data['user_id']) ? $data['user_id'] : "";
                    $service_id = isset($data['service_id']) ? $data['service_id'] : "";
                    $place_id = isset($data['place_id']) ? $data['place_id'] : "";

                    if (empty($thin_app_id)) {
                        $response['status'] = 0;
                        $response['message'] = 'Invalid thinapp id';
                    } else if (empty($app_key)) {
                        $response['status'] = 0;
                        $response['message'] = 'Invalid app key';
                    } else if (empty($service_id)) {
                        $response['status'] = 0;
                        $response['message'] = 'Enter service id';
                    } else if (empty($user_id)) {
                        $response['status'] = 0;
                        $response['message'] = 'Please enter service name';
                    } else if (empty($place_id)) {
                        $response['status'] = 0;
                        $response['message'] = 'Invalid place id';
                    } else {
                        $is_service = $this->Custom->check_service_has_upcoming_appointment($service_id, $thin_app_id);
                        if (!$is_service) {

                            $find_place = $this->AppointmentServiceAddress->find('first', array(
                                    'conditions' => array(
                                        'AppointmentServiceAddress.appointment_address_id' => $place_id,
                                        'AppointmentServiceAddress.appointment_service_id' => $service_id,
                                        'AppointmentServiceAddress.thinapp_id' => $thin_app_id
                                    ),
                                    'contain' => false,
                                    'fields' => array('AppointmentServiceAddress.id')
                                )
                            );
                            if (!empty($find_place)) {
                                $this->AppointmentServiceAddress->set('id', $find_place['AppointmentServiceAddress']['id']);
                                $this->AppointmentServiceAddress->set('status', 'INACTIVE');
                                if ($this->AppointmentServiceAddress->save()) {
                                    $response['status'] = 1;
                                    $response['message'] = "Place removed from service successfully";
                                } else {
                                    $response['status'] = 0;
                                    $response['message'] = "Sorry place could not removed";
                                }
                            } else {
                                $response['status'] = 0;
                                $response['message'] = "Sorry place not found";
                            }
                        } else {
                            $response['status'] = 0;
                            $response['message'] = "Sorry this service has upcoming appointment";
                        }
                    }
                } else {
                    $response['status'] = 0;
                    $response['message'] = $this->Custom->getResponseMessage($result);
                }
            } catch (Exception $e) {
                $response['status'] = 0;
                $response['message'] = $e->getMessage();
            }

            echo json_encode($response);
            exit;

        } else {
            exit();
        }
    }


    /* delete service */
    public function delete_appointment_service()
    {
        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        if ($this->request->is('post')) {
            try {
                $response = array();
                $thin_app_id = isset($data['thin_app_id']) ? $data['thin_app_id'] : "";
                $app_key = isset($data['app_key']) ? $data['app_key'] : "";
                if (($result = $this->Custom->CheckIsValidApp($app_key, $thin_app_id)) && ($result == 1)) {
                    $user_id = isset($data['user_id']) ? $data['user_id'] : "";
                    $service_id = isset($data['service_id']) ? $data['service_id'] : "";
                    if (empty($thin_app_id)) {
                        $response['status'] = 0;
                        $response['message'] = 'Invalid thinapp id';
                    } else if (empty($app_key)) {
                        $response['status'] = 0;
                        $response['message'] = 'Invalid app key';
                    } else if (empty($service_id)) {
                        $response['status'] = 0;
                        $response['message'] = 'Invalid service id';
                    } else {
                        $is_service = $this->Custom->check_service_has_upcoming_appointment($service_id, $thin_app_id);
                        if (!$is_service) {
                            $this->AppointmentService->set('id', $service_id);
                            $this->AppointmentService->set('status', 'INACTIVE');
                            if ($this->AppointmentService->save()) {
                                $response['status'] = 1;
                                $response['message'] = "Service deleted successfully";
                            } else {
                                $response['status'] = 0;
                                $response['message'] = "Sorry service could not deleted";
                            }
                        } else {
                            $response['status'] = 0;
                            $response['message'] = "Sorry this service has upcoming appointment";
                        }
                    }
                } else {
                    $response['status'] = 0;
                    $response['message'] = $this->Custom->getResponseMessage($result);
                }
            } catch (Exception $e) {
                $response['status'] = 0;
                $response['message'] = $e->getMessage();
            }

            echo json_encode($response);
            exit;

        } else {
            exit();
        }
    }


    /* this function add address and place or city to service */
    public function add_appointment_place_to_service()
    {
        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        if ($this->request->is('post')) {

            $datasource = $this->AppointmentServiceAddress->getDataSource();
            try {
                $response = array();
                $thin_app_id = isset($data['thin_app_id']) ? $data['thin_app_id'] : "";
                $app_key = isset($data['app_key']) ? $data['app_key'] : "";
                $user_id = isset($data['user_id']) ? $data['user_id'] : "";
                $service_id = isset($data['service_id']) ? $data['service_id'] : "";
                $place_ids_array = isset($data['place_ids_array']) ? $data['place_ids_array'] : "";
                if (empty($thin_app_id)) {
                    $response['status'] = 0;
                    $response['message'] = 'Invalid thinapp id';
                } else if (empty($app_key)) {
                    $response['status'] = 0;
                    $response['message'] = 'Invalid app key';
                } else if (empty($service_id)) {
                    $response['status'] = 0;
                    $response['message'] = 'Please enter service id';
                } else if (empty($user_id)) {
                    $response['status'] = 0;
                    $response['message'] = 'Please enter user id';
                } else if (empty($place_ids_array)) {
                    $response['status'] = 0;
                    $response['message'] = 'Please enter place';
                } else {
                    $datasource->begin();
                    if (($result = $this->Custom->CheckIsValidApp($app_key, $thin_app_id)) && ($result == 1)) {
                        $save_array = array();
                        if (!empty($place_ids_array)) {


                            $del_result = $this->AppointmentServiceAddress->deleteAll(array(
                                'AppointmentServiceAddress.appointment_service_id' => $service_id,
                                'AppointmentServiceAddress.thinapp_id' => $thin_app_id
                            ));

                            foreach ($place_ids_array as $key => $id) {
                                $save_array[$key]['thinapp_id'] = $thin_app_id;
                                $save_array[$key]['appointment_service_id'] = $service_id;
                                $save_array[$key]['appointment_address_id'] = $id;
                            }

                            if ($this->AppointmentServiceAddress->saveAll($save_array)) {
                                $datasource->commit();
                                $response['status'] = 1;
                                $response['message'] = "Place add to service successfully";
                            } else {
                                $response['status'] = 0;
                                $response['message'] = "Sorry place could not add to service";
                            }
                        } else {
                            $response['status'] = 0;
                            $response['message'] = "Please select at least one place";
                        }

                    } else {
                        $response['status'] = 0;
                        $response['message'] = $this->Custom->getResponseMessage($result);
                    }
                }
            } catch (Exception $e) {
                $datasource->rollback();
                $response['status'] = 0;
                $response['message'] = $e->getMessage();
            }

            echo json_encode($response);
            exit;

        } else {
            exit();
        }
    }

    /* this function add category to service */
    public function add_appointment_category_to_service()
    {
        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        if ($this->request->is('post')) {

            $datasource = $this->AppointmentCategoryService->getDataSource();
            try {
                $response = array();
                $thin_app_id = isset($data['thin_app_id']) ? $data['thin_app_id'] : "";
                $app_key = isset($data['app_key']) ? $data['app_key'] : "";
                $user_id = isset($data['user_id']) ? $data['user_id'] : "";
                $service_id = isset($data['service_id']) ? $data['service_id'] : "";
                $category_ids_array = isset($data['category_ids_array']) ? $data['category_ids_array'] : "";
                if (empty($thin_app_id)) {
                    $response['status'] = 0;
                    $response['message'] = 'Invalid thinapp id';
                } else if (empty($app_key)) {
                    $response['status'] = 0;
                    $response['message'] = 'Invalid app key';
                } else if (empty($service_id)) {
                    $response['status'] = 0;
                    $response['message'] = 'Please enter service id';
                } else if (empty($user_id)) {
                    $response['status'] = 0;
                    $response['message'] = 'Please enter user id';
                } else if (empty($category_ids_array)) {
                    $response['status'] = 0;
                    $response['message'] = 'Please enter category';
                } else {

                    $datasource->begin();
                    if (($result = $this->Custom->CheckIsValidApp($app_key, $thin_app_id)) && ($result == 1)) {
                        $save_array = array();
                        if (!empty($category_ids_array)) {


                            $del_result = $this->AppointmentCategoryService->deleteAll(array(
                                'AppointmentCategoryService.appointment_service_id' => $service_id,
                                'AppointmentCategoryService.thinapp_id' => $thin_app_id
                            ));


                            foreach ($category_ids_array as $key => $id) {
                                $save_array[$key]['thinapp_id'] = $thin_app_id;
                                $save_array[$key]['appointment_service_id'] = $service_id;
                                $save_array[$key]['appointment_category_id'] = $id;
                            }
                            if ($this->AppointmentCategoryService->saveAll($save_array)) {
                                $datasource->commit();
                                $response['status'] = 1;
                                $response['message'] = "Category add to service successfully";
                            } else {
                                $response['status'] = 0;
                                $response['message'] = "Sorry category could not add to service";
                            }
                        } else {
                            $response['status'] = 0;
                            $response['message'] = "Please select at least one category";
                        }

                    } else {
                        $response['status'] = 0;
                        $response['message'] = $this->Custom->getResponseMessage($result);
                    }
                }
            } catch (Exception $e) {
                $datasource->rollback();
                $response['status'] = 0;
                $response['message'] = $e->getMessage();
            }

            echo json_encode($response);
            exit;

        } else {
            exit();
        }
    }


    public function appointment_edit_staff_hours()
    {
        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        if ($this->request->is('post')) {
            $datasource = $this->AppointmentStaffHour->getDataSource();
            try {
                $response = array();
                $thin_app_id = isset($data['thin_app_id']) ? $data['thin_app_id'] : "";
                $app_key = isset($data['app_key']) ? $data['app_key'] : "";
                $user_id = isset($data['user_id']) ? $data['user_id'] : "";
                $staff_id = isset($data['staff_id']) ? $data['staff_id'] : "";
                $data_array = isset($data['data_array']) ? $data['data_array'] : array();
                if (empty($thin_app_id)) {
                    $response['status'] = 0;
                    $response['message'] = 'Invalid thinapp id';
                } else if (empty($user_id)) {
                    $response['status'] = 0;
                    $response['message'] = 'Invalid user id';
                } else if (empty($staff_id)) {
                    $response['status'] = 0;
                    $response['message'] = 'Invalid staff id';
                } else if (empty($data_array)) {
                    $response['status'] = 0;
                    $response['message'] = 'Invalid data parameter';
                } else {

                    $datasource->begin();
                    if (($result = $this->Custom->CheckIsValidApp($app_key, $thin_app_id)) && ($result == 1)) {
                        $save_to_array = array();
                        foreach ($data_array as $key => $value) {
                            $save_to_array[$key]['id'] = $value['id'];
                            $save_to_array[$key]['time_from'] = date('h:i A',strtotime($value['time_from']));
                            $save_to_array[$key]['time_to'] = date('h:i A',strtotime($value['time_to']));
                            $save_to_array[$key]['status'] = $value['status'];
                        }
                        if ($this->AppointmentStaffHour->saveMany($save_to_array, array('deep' => true))) {
                            $datasource->commit();
                            $response['status'] = 1;
                            $response['message'] = "Hours saved successfully";
                        	Custom::delete_hospital_cache($thin_app_id);
                        	 Custom::delete_doctor_cache($staff_id,$thin_app_id);
                        } else {
                            $response['status'] = 0;
                            $response['message'] = "Sorry hours could not save";
                        }
                    } else {
                        $response['status'] = 0;
                        $response['message'] = $this->Custom->getResponseMessage($result);
                    }
                }
            } catch (Exception $e) {
                $datasource->rollback();
                $response['status'] = 0;
                $response['message'] = $e->getMessage();
            }

            echo json_encode($response);
            exit;

        } else {
            exit();
        }
    }


    /* this function add appointment cateogry */
    public function add_appointment_category()
    {
        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        if ($this->request->is('post')) {

            $datasource = $this->AppointmentCategory->getDataSource();
            try {
                $datasource->begin();
                $response = array();
                $thin_app_id = isset($data['thin_app_id']) ? $data['thin_app_id'] : "";
                $app_key = isset($data['app_key']) ? $data['app_key'] : "";
                $user_id = isset($data['user_id']) ? $data['user_id'] : "";
                $name = isset($data['name']) ? trim($data['name']) : "";
                $image = isset($data['image']) ? $data['image'] : "";

                if (empty($thin_app_id)) {
                    $response['status'] = 0;
                    $response['message'] = 'Invalid thinapp id';
                } else if (empty($app_key)) {
                    $response['status'] = 0;
                    $response['message'] = 'Invalid app key';
                } else if (empty($name)) {
                    $response['status'] = 0;
                    $response['message'] = 'Please enter category name';
                } else {
                    $user_profile = $this->AppointmentCategory->find('count', array(
                        "conditions" => array(
                            "AppointmentCategory.user_id" => $user_id,
                            "AppointmentCategory.thinapp_id" => $thin_app_id,
                            "UPPER( AppointmentCategory.name )" => strtoupper(trim($name)),
                            "AppointmentCategory.status" => 'ACTIVE'
                        ),
                        'contain' => false
                    ));
                    if ($user_profile == 0) {
                        if (($result = $this->Custom->CheckIsValidApp($app_key, $thin_app_id)) && ($result == 1)) {

                            $this->AppointmentCategory->create();
                            $this->AppointmentCategory->set('user_id', $user_id);
                            $this->AppointmentCategory->set('thinapp_id', $thin_app_id);
                            $this->AppointmentCategory->set('name', $name);
                            $this->AppointmentCategory->set('image', $image);
                            if ($this->AppointmentCategory->save()) {
                                $category_id = $this->AppointmentCategory->getLastInsertId();
                                $datasource->commit();
                                $response['status'] = 1;
                                $response['message'] = "Department saved successfully";
                                $response['data']['category_id'] = $category_id;

                            } else {
                                $response['status'] = 0;
                                $response['message'] = "Sorry department could not save";
                            }
                        } else {
                            $response['status'] = 0;
                            $response['message'] = $this->Custom->getResponseMessage($result);
                        }
                    } else {
                        $response['status'] = 0;
                        $response['message'] = "Department already exists";
                    }
                }
            } catch (Exception $e) {
                $datasource->rollback();
                $response['status'] = 0;
                $response['message'] = $e->getMessage();
            }

            echo json_encode($response);
            exit;

        } else {
            exit();
        }
    }


    public function add_appointment_service_to_category()
    {
        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        if ($this->request->is('post')) {
            try {
                $response = array();
                $thin_app_id = isset($data['thin_app_id']) ? $data['thin_app_id'] : "";
                $app_key = isset($data['app_key']) ? $data['app_key'] : "";
                $user_id = isset($data['user_id']) ? $data['user_id'] : "";
                $category_id = isset($data['category_id']) ? $data['category_id'] : "";
                $services_ids_array = isset($data['services_ids_array']) ? $data['services_ids_array'] : "";
                if (empty($thin_app_id)) {
                    $response['status'] = 0;
                    $response['message'] = 'Invalid thinapp id';
                } else if (empty($app_key)) {
                    $response['status'] = 0;
                    $response['message'] = 'Invalid app key';
                } else if (empty($category_id)) {
                    $response['status'] = 0;
                    $response['message'] = 'Please enter category id';
                } else if (empty($user_id)) {
                    $response['status'] = 0;
                    $response['message'] = 'Please enter user id';
                } else if (empty($services_ids_array)) {
                    $response['status'] = 0;
                    $response['message'] = 'Invalid service parameter';
                } else {

                    if (($result = $this->Custom->CheckIsValidApp($app_key, $thin_app_id)) && ($result == 1)) {
                        $save_array = array();
                        $services_ids_array = array_unique($services_ids_array);
                        if (!empty($services_ids_array)) {
                            foreach ($services_ids_array as $key => $id) {
                                $save_array[$key]['thinapp_id'] = $thin_app_id;
                                $save_array[$key]['appointment_service_id'] = $id;
                                $save_array[$key]['appointment_category_id'] = $category_id;
                            }
                            if ($this->AppointmentCategoryService->saveAll($save_array)) {
                                $response['status'] = 1;
                                $response['message'] = "Service add to category successfully";
                            } else {
                                $response['status'] = 0;
                                $response['message'] = "Sorry service could not add to category";
                            }
                        } else {
                            $response['status'] = 0;
                            $response['message'] = "Please select at least one service";
                        }

                    } else {
                        $response['status'] = 0;
                        $response['message'] = $this->Custom->getResponseMessage($result);
                    }
                }
            } catch (Exception $e) {
                $response['status'] = 0;
                $response['message'] = $e->getMessage();
            }

            echo json_encode($response);
            exit;

        } else {
            exit();
        }
    }


    /* this function add appointment service */
    public function edit_appointment_category()
    {
        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        if ($this->request->is('post')) {
            try {
                $response = array();
                $thin_app_id = isset($data['thin_app_id']) ? $data['thin_app_id'] : "";
                $app_key = isset($data['app_key']) ? $data['app_key'] : "";
                if (($result = $this->Custom->CheckIsValidApp($app_key, $thin_app_id)) && ($result == 1)) {
                    $user_id = isset($data['user_id']) ? $data['user_id'] : "";
                    $category_id = isset($data['category_id']) ? $data['category_id'] : "";
                    $name = isset($data['name']) ? trim($data['name']) : "";
                    $image = isset($data['image']) ? trim($data['image']) : "";
                    if (empty($thin_app_id)) {
                        $response['status'] = 0;
                        $response['message'] = 'Invalid thinapp id';
                    } else if (empty($app_key)) {
                        $response['status'] = 0;
                        $response['message'] = 'Invalid app key';
                    } else if (empty($name)) {
                        $response['status'] = 0;
                        $response['message'] = 'Please enter category name';
                    } else if (empty($category_id)) {
                        $response['status'] = 0;
                        $response['message'] = 'Invalid category id';
                    } else {

                        $cat_data = $this->AppointmentCategory->find('count', array(
                            "conditions" => array(
                                "AppointmentCategory.thinapp_id" => $thin_app_id,
                                "AppointmentCategory.id" => $category_id
                            ),
                            'contain' => false
                        ));
                        if ($cat_data == 1) {

                            $dup_cnt = $this->AppointmentCategory->find('first', array(
                                "conditions" => array(
                                    "AppointmentCategory.thinapp_id" => $thin_app_id,
                                    "UPPER( AppointmentCategory.name )" => strtoupper(trim($name))
                                ),
                                'contain' => false,
                                'limit'=>1
                            ));
                            if ( empty($dup_cnt) || ( !empty($dup_cnt) && $dup_cnt['AppointmentCategory']['id'] == $category_id )  ) {
                                $this->AppointmentCategory->set('id', $category_id);
                                $this->AppointmentCategory->set('name', trim($name));
                                   if(!empty($image)){
                                       $exp = explode("/",$image);
                                       if(end($exp) != "null" ){
                                           $this->AppointmentCategory->set('image', $image);
                                       }
                                   }

                                if ($this->AppointmentCategory->save()) {
                                    $response['status'] = 1;
                                    $response['message'] = "Category update successfully";
                                } else {
                                    $response['status'] = 0;
                                    $response['message'] = "Sorry category could not update";
                                }
                            } else {
                                $response['status'] = 0;
                                $response['message'] = "Category name already exist";
                            }
                        } else {
                            $response['status'] = 0;
                            $response['message'] = "Category not found";
                        }
                    }
                } else {
                    $response['status'] = 0;
                    $response['message'] = $this->Custom->getResponseMessage($result);
                }
            } catch (Exception $e) {
                $response['status'] = 0;
                $response['message'] = $e->getMessage();
            }

            echo json_encode($response);
            exit;

        } else {
            exit();
        }
    }


    /* this function add  appointment customer */
    public function appointment_add_customer()
    {
        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        if ($this->request->is('post')) {
            $datasource = $this->AppointmentCustomer->getDataSource();
            try {
                $response = array();
                $thin_app_id = isset($data['thin_app_id']) ? $data['thin_app_id'] : "";
                $app_key = isset($data['app_key']) ? $data['app_key'] : "";
                $user_id = isset($data['user_id']) ? $data['user_id'] : "";
                $first_name = isset($data['first_name']) ? trim($data['first_name']) : "";
                $last_name = isset($data['last_name']) ? trim($data['last_name']) : "";
                $email = isset($data['email']) ? trim($data['email']) : "";
                $profile_photo = isset($data['profile_photo']) ? $data['profile_photo'] : "";
                $mobile = isset($data['mobile']) ? $data['mobile'] : "";
                $country_code = isset($data['country_code']) ? $data['country_code'] : "";
                $address = isset($data['address']) ? $data['address'] : "";

                if (empty($first_name)) {
                    $response['status'] = 0;
                    $response['message'] = 'Please enter first name';
                } else if (empty($thin_app_id)) {
                    $response['status'] = 0;
                    $response['message'] = 'Invalid thinapp';
                } else if (empty($app_key)) {
                    $response['status'] = 0;
                    $response['message'] = 'Invalid app key';
                } else if (empty($user_id)) {
                    $response['status'] = 0;
                    $response['message'] = 'Invalid user id';
                } else if (empty($mobile)) {
                    $response['status'] = 0;
                    $response['message'] = 'Please enter mobile number';
                } else if (empty($country_code)) {
                    $response['status'] = 0;
                    $response['message'] = 'Please enter country code';
                } else if (empty($address)) {
                    $response['status'] = 0;
                    $response['message'] = 'Please enter address';
                } else {

                    $datasource->begin();
                    if (($result = $this->Custom->CheckIsValidApp($app_key, $thin_app_id)) && ($result == 1)) {
                        $user_profile = $this->AppointmentCustomer->find('first', array(
                            "conditions" => array(
                                "AppointmentCustomer.mobile" => $mobile,
                                "AppointmentCustomer.country_code" => $country_code,
                                "AppointmentCustomer.thinapp_id" => $thin_app_id
                            ),
                            'contain' => false
                        ));
                        if (empty($user_profile)) {
                            $app_user_id = $this->Custom->getUserId($thin_app_id, $country_code . $mobile);
                            $this->AppointmentCustomer->create();
                            $this->AppointmentCustomer->set('user_id', $app_user_id);
                            $this->AppointmentCustomer->set('thinapp_id', $thin_app_id);
                            $this->AppointmentCustomer->set('customer_created_by', $user_id);
                            $this->AppointmentCustomer->set('first_name', $first_name);
                            $this->AppointmentCustomer->set('last_name', $last_name);
                            $this->AppointmentCustomer->set('email', $email);
                            if (!empty($profile_photo)) {
                                $exp = explode("/",$profile_photo);
                                if(end($exp) != "null" ){
                                    $this->AppointmentCustomer->set('profile_photo', $profile_photo);
                                }

                            }

                            $this->AppointmentCustomer->set('mobile', $country_code.$mobile);
                            $this->AppointmentCustomer->set('country_code', $country_code);
                            $this->AppointmentCustomer->set('address', $address);

                            if ($this->AppointmentCustomer->save()) {
                                $app_staff_id = $this->AppointmentCustomer->getLastInsertId();
                                $datasource->commit();
                                $response['status'] = 1;
                                $response['message'] = "Customer add successfully";
                                $response['customer_id'] = $app_staff_id;

                            } else {
                                $response['status'] = 0;
                                $response['message'] = "Sorry customer could not add";
                            }
                        } else {
                            $response['status'] = 0;
                            $response['message'] = "Number already registered";
                        }

                    } else {
                        $response['status'] = 0;
                        $response['message'] = $this->Custom->getResponseMessage($result);
                    }

                }
            } catch (Exception $e) {
                $datasource->rollback();
                $response['status'] = 0;
                $response['message'] = $e->getMessage();
            }

            echo json_encode($response);
            exit;

        } else {
            exit();
        }
    }

    public function appointment_edit_customer()
    {
        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        if ($this->request->is('post')) {
            $datasource = $this->AppointmentCustomer->getDataSource();
            try {
                $response = array();
                $thin_app_id = isset($data['thin_app_id']) ? $data['thin_app_id'] : "";
                $app_key = isset($data['app_key']) ? $data['app_key'] : "";
                $user_id = isset($data['user_id']) ? $data['user_id'] : "";
                $first_name = isset($data['first_name']) ? trim($data['first_name']) : "";
                $last_name = isset($data['last_name']) ? trim($data['last_name']) : "";
                $email = isset($data['email']) ? trim($data['email']) : "";
                $profile_photo = isset($data['image']) ? $data['image'] : "";
                $mobile = isset($data['mobile']) ? $data['mobile'] : "";
                $country_code = isset($data['country_code']) ? $data['country_code'] : "";
                $address = isset($data['address']) ? $data['address'] : "";
                $customer_id = isset($data['customer_id']) ? $data['customer_id'] : "";

                if (empty($first_name)) {
                    $response['status'] = 0;
                    $response['message'] = 'Please enter first name';
                } else if (empty($thin_app_id)) {
                    $response['status'] = 0;
                    $response['message'] = 'Invalid thinapp';
                } else if (empty($app_key)) {
                    $response['status'] = 0;
                    $response['message'] = 'Invalid app key';
                } else if (empty($user_id)) {
                    $response['status'] = 0;
                    $response['message'] = 'Invalid user id';
                } else if (empty($mobile)) {
                    $response['status'] = 0;
                    $response['message'] = 'Please enter mobile number';
                } else if (empty($country_code)) {
                    $response['status'] = 0;
                    $response['message'] = 'Please enter country code';
                } else if (empty($customer_id)) {
                    $response['status'] = 0;
                    $response['message'] = 'Please enter customer id';
                } else {

                    $datasource->begin();
                    if (($result = $this->Custom->CheckIsValidApp($app_key, $thin_app_id)) && ($result == 1)) {


                        $is_customer = $this->AppointmentCustomer->find('count', array(
                            "conditions" => array(
                                "AppointmentCustomer.id" => $customer_id,
                                "AppointmentCustomer.status" => 'ACTIVE',
                                "AppointmentCustomer.thinapp_id" => $thin_app_id
                            ),
                            'contain' => false
                        ));


                        if ($is_customer == 1) {

                            $user_profile = $this->AppointmentCustomer->find('first', array(
                                "conditions" => array(
                                    "AppointmentCustomer.mobile" => $mobile,
                                    "AppointmentCustomer.country_code" => $country_code,
                                    "AppointmentCustomer.thinapp_id" => $thin_app_id
                                ),
                                'contain' => false
                            ));

                            if (empty($user_profile) || (isset($user_profile) && $user_profile['AppointmentCustomer']['id'] == $customer_id)) {

                                $this->AppointmentCustomer->set('id', $customer_id);
                                $this->AppointmentCustomer->set('first_name', $first_name);
                                $this->AppointmentCustomer->set('last_name', $last_name);
                                $this->AppointmentCustomer->set('email', $email);

                                if (!empty($profile_photo)) {
                                    $exp = explode("/",$profile_photo);
                                    if(end($exp) != "null" ){
                                        $this->AppointmentCustomer->set('profile_photo', $profile_photo);
                                    }

                                }
                                $this->AppointmentCustomer->set('mobile', $country_code.$mobile);
                                $this->AppointmentCustomer->set('country_code', $country_code);
                                $this->AppointmentCustomer->set('address', $address);

                                if ($this->AppointmentCustomer->save()) {
                                    $app_staff_id = $this->AppointmentCustomer->getLastInsertId();
                                    $datasource->commit();
                                    $response['status'] = 1;
                                    $response['message'] = "Customer save successfully";
                                    $response['customer_id'] = $app_staff_id;

                                } else {
                                    $response['status'] = 0;
                                    $response['message'] = "Sorry customer could not save";
                                }
                            } else {
                                $response['status'] = 0;
                                $response['message'] = "Number already registered";
                            }


                        } else {
                            $response['status'] = 0;
                            $response['message'] = "Customer not found";
                        }

                    } else {
                        $response['status'] = 0;
                        $response['message'] = $this->Custom->getResponseMessage($result);
                    }

                }
            } catch (Exception $e) {
                $datasource->rollback();
                $response['status'] = 0;
                $response['message'] = $e->getMessage();
            }

            echo json_encode($response);
            exit;

        } else {
            exit();
        }
    }


    public function edit_service_from_category()
    {
        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        if ($this->request->is('post')) {

            $datasource = $this->AppointmentCategory->getDataSource();
            try {
                $response = array();
                $thin_app_id = isset($data['thin_app_id']) ? $data['thin_app_id'] : "";
                $app_key = isset($data['app_key']) ? $data['app_key'] : "";
                if (($result = $this->Custom->CheckIsValidApp($app_key, $thin_app_id)) && ($result == 1)) {
                    $user_id = isset($data['user_id']) ? $data['user_id'] : "";
                    $services_ids_array = isset($data['services_ids_array']) ? $data['services_ids_array'] : "";
                    $category_id = isset($data['category_id']) ? $data['category_id'] : "";
                    $name = isset($data['name']) ? $data['name'] : "";
                    if (empty($services_ids_array)) {
                        $response['status'] = 0;
                        $response['message'] = 'Enter service id';
                    } else if (empty($category_id)) {
                        $response['status'] = 0;
                        $response['message'] = 'Please enter category id';
                    } else if (empty($user_id)) {
                        $response['status'] = 0;
                        $response['message'] = 'Please enter user id';
                    } else if (empty($name)) {
                        $response['status'] = 0;
                        $response['message'] = 'Please enter category name';
                    } else {

                        $datasource->begin();
                        $dup_cnt = $this->AppointmentCategory->find('first', array(
                            "conditions" => array(
                                "AppointmentCategory.thinapp_id" => $thin_app_id,
                                "UPPER( AppointmentCategory.name )" => strtoupper(trim($name))
                            ),
                            'contain' => false,
                            'fields' => array('AppointmentCategory.id')
                        ));
                        if (count($dup_cnt) == 0 || (isset($dup_cnt) && $dup_cnt['AppointmentCategory']['id'] == $category_id)) {
                            $this->AppointmentCategory->set('id', $category_id);
                            $this->AppointmentCategory->set('name', trim($name));
                            if ($this->AppointmentCategory->save()) {
                                $del_result = $this->AppointmentCategoryService->deleteAll(array(
                                    'AppointmentCategoryService.appointment_category_id' => $category_id,
                                    'AppointmentCategoryService.thinapp_id' => $thin_app_id
                                ));

                                foreach ($services_ids_array as $key => $id) {
                                    $save_array[$key]['thinapp_id'] = $thin_app_id;
                                    $save_array[$key]['appointment_service_id'] = $id;
                                    $save_array[$key]['appointment_category_id'] = $category_id;
                                }
                                if ($this->AppointmentCategoryService->saveAll($save_array)) {
                                    $datasource->commit();
                                    $response['status'] = 1;
                                    $response['message'] = "Service save successfully";
                                } else {
                                    $response['status'] = 0;
                                    $response['message'] = "Error when save service";
                                }

                            } else {
                                $response['status'] = 0;
                                $response['message'] = "Sorry category could not save";
                            }
                        } else {
                            $response['status'] = 0;
                            $response['message'] = "Category name already exist";
                        }

                    }
                } else {
                    $response['status'] = 0;
                    $response['message'] = $this->Custom->getResponseMessage($result);
                }
            } catch (Exception $e) {
                $datasource->rollback();
                $response['status'] = 0;
                $response['message'] = $e->getMessage();
            }

            echo json_encode($response);
            exit;

        } else {
            exit();
        }
    }


    public function appointment_edit_staff_service()
    {
        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        if ($this->request->is('post')) {

            $datasource = $this->AppointmentStaffService->getDataSource();
            try {
                $response = array();
                $thin_app_id = isset($data['thin_app_id']) ? $data['thin_app_id'] : "";
                $app_key = isset($data['app_key']) ? $data['app_key'] : "";
                if (($result = $this->Custom->CheckIsValidApp($app_key, $thin_app_id)) && ($result == 1)) {
                    $user_id = isset($data['user_id']) ? $data['user_id'] : "";
                    $services_ids_array = isset($data['services_ids_array']) ? $data['services_ids_array'] : "";
                    $staff_id = isset($data['staff_id']) ? $data['staff_id'] : "";

                    if (empty($services_ids_array)) {
                        $response['status'] = 0;
                        $response['message'] = 'Enter service id';
                    } else if (empty($staff_id)) {
                        $response['status'] = 0;
                        $response['message'] = 'Please enter staff id';
                    } else if (empty($user_id)) {
                        $response['status'] = 0;
                        $response['message'] = 'Please enter user id';
                    } else {
                        $datasource->begin();
                        $del_result = $this->AppointmentStaffService->deleteAll(array(
                            'AppointmentStaffService.appointment_staff_id' => $staff_id,
                            'AppointmentStaffService.thinapp_id' => $thin_app_id
                        ));
                        $services_ids_array = array_unique($services_ids_array);
                        foreach ($services_ids_array as $key => $id) {
                            $save_array[$key]['thinapp_id'] = $thin_app_id;
                            $save_array[$key]['appointment_service_id'] = $id;
                            $save_array[$key]['appointment_staff_id'] = $staff_id;
                        }
                        if ($this->AppointmentStaffService->saveAll($save_array)) {
                            $datasource->commit();
                            $response['status'] = 1;
                            $response['message'] = "Service saved successfully";
                        } else {
                            $response['status'] = 0;
                            $response['message'] = "Sorry address could not save";
                        }

                    }
                } else {
                    $response['status'] = 0;
                    $response['message'] = $this->Custom->getResponseMessage($result);
                }
            } catch (Exception $e) {
                $datasource->rollback();
                $response['status'] = 0;
                $response['message'] = $e->getMessage();
            }

            echo json_encode($response);
            exit;

        } else {
            exit();
        }
    }


    public function appointment_edit_staff_breaks()
    {
        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        if ($this->request->is('post')) {

            $datasource = $this->AppointmentCategory->getDataSource();
            try {
                $response = array();
                $thin_app_id = isset($data['thin_app_id']) ? $data['thin_app_id'] : "";
                $app_key = isset($data['app_key']) ? $data['app_key'] : "";
                if (($result = $this->Custom->CheckIsValidApp($app_key, $thin_app_id)) && ($result == 1)) {
                    $user_id = isset($data['user_id']) ? $data['user_id'] : "";
                    $data_array = isset($data['data_array']) ? $data['data_array'] : -1;
                    $staff_id = isset($data['staff_id']) ? $data['staff_id'] : "";

                    if ($data_array == -1) {
                        $response['status'] = 0;
                        $response['message'] = 'Invalid data';
                    } else if (empty($staff_id)) {
                        $response['status'] = 0;
                        $response['message'] = 'Please enter staff id';
                    } else if (empty($user_id)) {
                        $response['status'] = 0;
                        $response['message'] = 'Please enter user id';
                    } else {

                        $del_result = $this->AppointmentStaffBreakSlot->deleteAll(array(
                            'AppointmentStaffBreakSlot.appointment_staff_id' => $staff_id,
                            'AppointmentStaffBreakSlot.thinapp_id' => $thin_app_id
                        ));

                        foreach ($data_array[0] as $key => $value) {
                            $save_array[$key]['thinapp_id'] = $thin_app_id;
                            $save_array[$key]['user_id'] = $user_id;
                            $save_array[$key]['appointment_staff_id'] = $staff_id;
                            $save_array[$key]['appointment_day_time_id'] = $value['appointment_day_time_id'];
                            $save_array[$key]['time_from'] = date('h:i A',strtotime($value['time_from']));
                            $save_array[$key]['time_to'] = date('h:i A',strtotime($value['time_to']));
                        }
                        if ($this->AppointmentStaffBreakSlot->saveAll($save_array)) {
                            $datasource->commit();
                            $response['status'] = 1;
                            $response['message'] = "Breaks saved successfully";
                        	Custom::delete_hospital_cache($thin_app_id);
                        	Custom::delete_doctor_cache($staff_id,$thin_app_id);
                        } else {
                            $response['status'] = 0;
                            $response['message'] = "Sorry breaks could not save";
                        }

                    }
                } else {
                    $response['status'] = 0;
                    $response['message'] = $this->Custom->getResponseMessage($result);
                }
            } catch (Exception $e) {
                $datasource->rollback();
                $response['status'] = 0;
                $response['message'] = $e->getMessage();
            }

            echo json_encode($response);
            exit;

        } else {
            exit();
        }
    }


    public function appointment_edit_staff_address()
    {
        
        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        if ($this->request->is('post')) {

            $datasource = $this->AppointmentStaffAddress->getDataSource();
            try {
                $response = array();
                $thin_app_id = isset($data['thin_app_id']) ? $data['thin_app_id'] : "";
                $app_key = isset($data['app_key']) ? $data['app_key'] : "";
                if (($result = $this->Custom->CheckIsValidApp($app_key, $thin_app_id)) && ($result == 1)) {
                    $user_id = isset($data['user_id']) ? $data['user_id'] : "";
                    $address_ids_array = isset($data['address_ids_array']) ? $data['address_ids_array'] : "";
                    $staff_id = isset($data['staff_id']) ? $data['staff_id'] : "";

                    if (empty($address_ids_array)) {
                        $response['status'] = 0;
                        $response['message'] = 'Enter address id';
                    } else if (empty($staff_id)) {
                        $response['status'] = 0;
                        $response['message'] = 'Please enter staff id';
                    } else if (empty($user_id)) {
                        $response['status'] = 0;
                        $response['message'] = 'Please enter user id';
                    } else {
                        $datasource->begin();
                        $del_result = $this->AppointmentStaffAddress->deleteAll(array(
                            'AppointmentStaffAddress.appointment_staff_id' => $staff_id,
                            'AppointmentStaffAddress.thinapp_id' => $thin_app_id
                        ));
                        $address_ids_array = array_unique($address_ids_array);
                        foreach ($address_ids_array as $key => $id) {
                            $save_array[$key]['thinapp_id'] = $thin_app_id;
                            $save_array[$key]['appointment_address_id'] = $id;
                            $save_array[$key]['appointment_staff_id'] = $staff_id;
                        }
                        if ($this->AppointmentStaffAddress->saveAll($save_array)) {
                            $datasource->commit();
                            $response['status'] = 1;
                            $response['message'] = "Address saved successfully";
                        	Custom::delete_hospital_cache($thin_app_id);
                        } else {
                            $response['status'] = 0;
                            $response['message'] = "Sorry address could not save";
                        }
                    }
                } else {
                    $response['status'] = 0;
                    $response['message'] = $this->Custom->getResponseMessage($result);
                }
            } catch (Exception $e) {
                $datasource->rollback();
                $response['status'] = 0;
                $response['message'] = $e->getMessage();
            }

            echo json_encode($response);
            exit;

        } else {
            exit();
        }
    }


    public function reschedule_appointment()
    {
        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        if ($this->request->is('post')) {

            $datasource = $this->AppointmentCategory->getDataSource();
            try {
                $response = array();
                $thin_app_id = isset($data['thin_app_id']) ? $data['thin_app_id'] : "";
                $app_key = isset($data['app_key']) ? $data['app_key'] : "";
                if (($result = $this->Custom->CheckIsValidApp($app_key, $thin_app_id)) && ($result == 1)) {
                    $user_id = isset($data['user_id']) ? $data['user_id'] : "";
                    $appointment_id = isset($data['appointment_id']) ? $data['appointment_id'] : "";
                    $booking_date = isset($data['booking_date']) ? $data['booking_date'] : "";
                    $slot_time = isset($data['slot_time']) ? $data['slot_time'] : "";
                    $staff_id = isset($data['staff_id']) ? $data['staff_id'] : "";
                    $background =array();

                    if (empty($appointment_id)) {
                        $response['status'] = 0;
                        $response['message'] = 'Invalid appointment id';
                    } else if (empty($booking_date)) {
                        $response['status'] = 0;
                        $response['message'] = 'Invalid appointment date';
                    } else if (empty($user_id)) {
                        $response['status'] = 0;
                        $response['message'] = 'Please enter user id';
                    } else if (empty($thin_app_id)) {
                        $response['status'] = 0;
                        $response['message'] = 'Invalid thinapp';
                    } else if (empty($app_key)) {
                        $response['status'] = 0;
                        $response['message'] = 'Invalid app';
                    } else if (empty($slot_time)) {
                        $response['status'] = 0;
                        $response['message'] = 'Invalid slot time';
                    } else if (empty($staff_id)) {
                        $response['status'] = 0;
                        $response['message'] = 'Invalid staff id';
                    } else {

                        $appointment_data = WebservicesFunction::get_appointment_all_data_id($appointment_id);
                        if (!empty($appointment_data)) {

                                $slot_array = WebservicesFunction::get_booking_slots($booking_date, $staff_id, $appointment_data['service_slot_duration']);
                                if (!empty($slot_array) && (array_key_exists($slot_time, $slot_array) && $slot_array[$slot_time] == 'AVAILABLE')) {
                                    $datasource->begin();
                                    $this->AppointmentCustomerStaffService->set('id', $appointment_id);
                                    $booking_date = date('Y-m-d', strtotime($booking_date));
                                    $date_time = date('Y-m-d h:i:s', strtotime($booking_date . ' ' . $slot_time));
                                    $this->AppointmentCustomerStaffService->set('booking_date', $booking_date);
                                    $this->AppointmentCustomerStaffService->set('appointment_datetime', $date_time);
                                    $this->AppointmentCustomerStaffService->set('slot_time', $slot_time);
                                    $this->AppointmentCustomerStaffService->set('status', 'RESCHEDULE');
                                    if ($this->AppointmentCustomerStaffService->save()) {
                                        $datasource->commit();
                                        $response['status'] = 1;
                                        $response['message'] = "Appointment rescheduled successfully";

                                        if($appointment_data['customer_user_id'] !=0){

                                                $app_date = date('d-m-Y h:i A', strtotime($date_time));
                                                $last_date = date('d-m-Y h:i A', strtotime($appointment_data['appointment_datetime']));
                                                /* send appoinment notifincation to staff */
                                                $message = "Your appointment with " . $appointment_data['staff_name']." on ". $last_date . ' for ' . $appointment_data['service_name'].' has been reschedule on '.$app_date;
                                                $option = array(
                                                    'thinapp_id' => $thin_app_id,
                                                    'customer_id' => $appointment_data['appointment_customer_id'],
                                                    'service_id' => $appointment_data['appointment_service_id'],
                                                    'channel_id' => 0,
                                                    'role' => "STAFF",
                                                    'flag' => 'APPOINTMENT_RESCHEDULE',
                                                    'title' => "Appointment rescheduled",
                                                    'message' => mb_strimwidth($message, 0, 80, '...'),
                                                    'description' => mb_strimwidth($message, 0, 100, '...'),
                                                    'chat_reference' => '',
                                                    'module_type' => 'APPOINTMENT',
                                                    'module_type_id' => $appointment_id,
                                                    'firebase_reference' => ""
                                                );
                                                $background['notification']['data'] =$option;
                                                $background['notification']['user_id'] =$appointment_data['customer_user_id'];
                                                $background['sms']['message'] =$message;
                                                $background['sms']['mobile'] =$appointment_data['customer_mobile'];
                                        }
                                    } else {
                                        $response['status'] = 0;
                                        $response['message'] = "Error when rescheduled appointment";
                                    }
                                } else {
                                    $response['status'] = 0;
                                    $response['message'] = "This slot is not available";
                                }

                        } else {
                            $response['status'] = 0;
                            $response['message'] = "Sorry this is not valid appointment";
                        }

                    }
                } else {
                    $response['status'] = 0;
                    $response['message'] = $this->Custom->getResponseMessage($result);
                }
            } catch (Exception $e) {
                $datasource->rollback();
                $response['status'] = 0;
                $response['message'] = $e->getMessage();
            }

            Custom::sendResponse($response);
            if (!empty($background)) {
                Custom::send_process_to_background();
                if (isset($background['notification'])) {
                        $option = $background['notification']['data'];
                        $user_id = $background['notification']['user_id'];
                        Custom::send_notification_by_user_id($option, array($user_id),$thin_app_id);
                }

                if (isset($background['sms'])) {
                        $message =$background['sms']['message'];
                        $mobile =$background['sms']['mobile'];
                        Custom::send_single_sms($mobile, $message, $thin_app_id);
                }
            }
            exit;

        } else {
            exit();
        }
    }

    public function add_new_appointment($data=null)
    {


        if(empty($data)){
            $request = file_get_contents("php://input");
            $data = json_decode($request, true);
        }


        if ($this->request->is('post')) {
            $datasource = $this->AppointmentCategory->getDataSource();
            try {
                $response = array();
                $thin_app_id = isset($data['thin_app_id']) ? $data['thin_app_id'] : "";
                $app_key = isset($data['app_key']) ? $data['app_key'] : "";
                if (($result = $this->Custom->CheckIsValidApp($app_key, $thin_app_id)) && ($result == 1)) {
                    $user_id = isset($data['user_id']) ? $data['user_id'] : "";
                    $booking_date = isset($data['booking_date']) ? $data['booking_date'] : "";
                    $slot_time = isset($data['slot_time']) ? $data['slot_time'] : "";
                    $staff_id = isset($data['staff_id']) ? $data['staff_id'] : "";
                    $category_id = isset($data['category_id']) ? $data['category_id'] : "";
                    $service_id = isset($data['service_id']) ? $data['service_id'] : "";
                    $customer_id = isset($data['customer_id']) ? $data['customer_id'] : "";
                    $address_id = isset($data['address_id']) ? $data['address_id'] : "";
                    $background =array();

                    if (empty($booking_date)) {
                        $response['status'] = 0;
                        $response['message'] = 'Invalid appointment date';
                    } else if (empty($user_id)) {
                        $response['status'] = 0;
                        $response['message'] = 'Invalid user';
                    } else if (empty($thin_app_id)) {
                        $response['status'] = 0;
                        $response['message'] = 'Invalid thinapp';
                    } else if (empty($app_key)) {
                        $response['status'] = 0;
                        $response['message'] = 'Invalid app';
                    } else if (empty($slot_time)) {
                        $response['status'] = 0;
                        $response['message'] = 'Invalid slot time';
                    } else if (empty($staff_id)) {
                        $response['status'] = 0;
                        $response['message'] = 'Invalid staff';
                    } else if (empty($address_id)) {
                        $response['status'] = 0;
                        $response['message'] = 'Invalid address';
                    } else if (empty($customer_id)) {
                        $response['status'] = 0;
                        $response['message'] = 'Invalid customer';
                    } else if (empty($category_id)) {
                        $response['status'] = 0;
                        $response['message'] = 'Invalid category';
                    } else {

                        $service_data = WebservicesFunction::get_service_data_by_id($service_id);
                        if (!empty($service_data)) {
                            $slot_array = WebservicesFunction::get_booking_slots($booking_date, $staff_id, $service_data['service_slot_duration']);
                            if (!empty($slot_array) && (array_key_exists($slot_time, $slot_array) && $slot_array[$slot_time] == 'AVAILABLE')) {
                                $datasource->begin();
                                $save_data = array();
                                $booking_date = date('Y-m-d', strtotime($booking_date));
                                $save_data['appointment_customer_id'] = $customer_id;
                                $save_data['appointment_category_id'] = $category_id;
                                $save_data['appointment_address_id'] = $address_id;
                                $save_data['thinapp_id'] = $thin_app_id;
                                $save_data['appointment_staff_id'] = $staff_id;
                                $save_data['appointment_service_id'] = $service_id;
                                $day_time_id = date('N', strtotime($booking_date));
                                $save_data['appointment_day_time_id'] = $day_time_id;
                                $save_data['booking_date'] = $booking_date;
                                $date_time = date('Y-m-d h:i:s', strtotime($booking_date . ' ' . $slot_time));
                                $save_data['appointment_datetime'] = $date_time;
                                $save_data['slot_duration'] = $service_data['service_slot_duration'];
                                $save_data['slot_time'] = $slot_time;
                                $save_data['amount'] = $service_data['service_amount'];
                                if($service_data['service_amount'] == 0){
                                    $save_data['status'] = 'CONFIRM';
                                    $save_data['payment_status'] = 'SUCCESS';
                                }

                                $this->AppointmentCustomerStaffService->create();
                                if ($this->AppointmentCustomerStaffService->save($save_data)) {
                                    $datasource->commit();
                                    $appointment_id = $this->AppointmentCustomerStaffService->getLastInsertId();
                                    $total_sms = Custom::get_total_sms_thinapp($thin_app_id,'T');

                                    if($service_data['service_amount']==0){

                                        $appointment_data = WebservicesFunction::get_appointment_all_data_id($appointment_id);

                                        if(!empty($appointment_data)){

                                               $app_date = date('d-m-Y h:i A', strtotime($appointment_data['appointment_datetime']));
                                                /* send appoinment notifincation to staff */
                                                $message = "New appointment scheduled on " . $appointment_data['appointment_datetime'] . ' for service ' . $appointment_data['service_name'];
                                                $sms_message = "You have an appointment with " . $appointment_data['staff_name'] . ' on ' . $app_date . ' for ' . $appointment_data['service_name'];
                                                $option = array(
                                                    'thinapp_id' => $thin_app_id,
                                                    'customer_id' =>0,
                                                    'staff_id' => $appointment_data['appointment_staff_id'],
                                                    'service_id' => $appointment_data['appointment_service_id'],
                                                    'channel_id' => 0,
                                                    'role' => "STAFF",
                                                    'flag' => 'APPOINTMENT',
                                                    'title' => "New Appointment Request",
                                                    'message' => mb_strimwidth($message, 0, 80, '...'),
                                                    'description' => mb_strimwidth($message, 0, 100, '...'),
                                                    'chat_reference' => '',
                                                    'module_type' => 'APPOINTMENT',
                                                    'module_type_id' => $appointment_id,
                                                    'firebase_reference' => ""
                                                );
                                                $background['notification'][0]['data'] = $option;
                                                $background['notification'][0]['user_id'] = $appointment_data['staff_user_id'];

                                                if($total_sms >= 1){
                                                    $background['sms'][] =  array(
                                                        'message'=>$message,
                                                        'mobile'=>$appointment_data['staff_mobile']
                                                    );
                                                    $total_sms--;
                                                }


                                                $option = array(
                                                    'thinapp_id' => $thin_app_id,
                                                    'staff_id' =>0,
                                                    'customer_id' => $appointment_data['appointment_customer_id'],
                                                    'service_id' => $appointment_data['appointment_service_id'],
                                                    'channel_id' => 0,
                                                    'role' => "CUSTOMER",
                                                    'flag' => 'APPOINTMENT',
                                                    'title' => "New Appointment Request",
                                                    'message' => mb_strimwidth($sms_message, 0, 80, '...'),
                                                    'description' => mb_strimwidth($sms_message, 0, 100, '...'),
                                                    'chat_reference' => '',
                                                    'module_type' => 'APPOINTMENT',
                                                    'module_type_id' => $appointment_id,
                                                    'firebase_reference' => ""
                                                );
                                            $background['notification'][1]['data'] = $option;
                                            $background['notification'][1]['user_id'] = $appointment_data['customer_user_id'];


                                            if($total_sms >= 1){
                                                $background['sms'][] =  array(
                                                    'message'=>$sms_message,
                                                    'mobile'=>$appointment_data['customer_mobile']
                                                );
                                                $total_sms--;
                                            }
                                        }


                                    }
                                    $response['status'] = 1;
                                    $response['data']['appointment_id'] = $appointment_id;
                                    $response['data']['flag'] = ($service_data['service_amount']==0)?"FREE":"PAID";
                                    $response['message'] = "Appointment scheduled successfully";
                                } else {
                                    $response['status'] = 0;
                                    $response['message'] = "Error when scheduled appointment";
                                }

                            } else {
                                $response['status'] = 0;
                                $response['message'] = "This slot is not available";
                            }
                        } else {
                            $response['status'] = 0;
                            $response['message'] = "Sorry this service not available";
                        }

                    }
                } else {
                    $response['status'] = 0;
                    $response['message'] = $this->Custom->getResponseMessage($result);
                }
            } catch (Exception $e) {
                $datasource->rollback();
                $response['status'] = 0;
                $response['message'] = $e->getMessage();
            }

            Custom::sendResponse($response);
            if (!empty($background)) {
                Custom::send_process_to_background();
                if (isset($background['notification'])) {

                    foreach($background['notification'] as $key => $value){
                        $option = $value['data'];
                        $user_id = $value['user_id'];
                        Custom::send_notification_by_user_id($option, array($user_id),$thin_app_id);
                    }
                }

                if (isset($background['sms'])) {
                    foreach($background['sms'] as $key => $value){
                        $message =$value['message'];
                        $mobile =$value['mobile'];
                        Custom::send_single_sms($mobile, $message, $thin_app_id);
                    }
                }
            }

            exit;

        } else {
            exit();
        }
    }


    public function update_appointment_payment_status()
    {


       /* this method will continue when user lost connection*/
        ignore_user_abort(true);
        set_time_limit(0);
        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        if ($this->request->is('post')) {

            $datasource = $this->AppointmentCategory->getDataSource();
            try {
                $response = array();
                $thin_app_id = isset($data['thin_app_id']) ? $data['thin_app_id'] : "";
                $app_key = isset($data['app_key']) ? $data['app_key'] : "";
                if (($result = $this->Custom->CheckIsValidApp($app_key, $thin_app_id)) && ($result == 1)) {
                    $user_id = isset($data['user_id']) ? $data['user_id'] : "";
                    $appointment_id = isset($data['appointment_id']) ? $data['appointment_id'] : "";
                    $status = isset($data['status']) ? $data['status'] : "";
                    $background =array();

                    if (empty($appointment_id)) {
                        $response['status'] = 0;
                        $response['message'] = 'Invalid appointment id';
                    } else if (empty($status)) {
                        $response['status'] = 0;
                        $response['message'] = 'Invalid status';
                    } else if (empty($user_id)) {
                        $response['status'] = 0;
                        $response['message'] = 'Please enter user id';
                    } else if (empty($thin_app_id)) {
                        $response['status'] = 0;
                        $response['message'] = 'Invalid thinapp';
                    } else if (empty($app_key)) {
                        $response['status'] = 0;
                        $response['message'] = 'Invalid app';
                    } else {

                        $status = strtoupper(strtolower($status));
                        $status_array = array('SUCCESS','FAILURE');
                        if(in_array($status,$status_array)){
                            $is_appointment = WebservicesFunction::is_appointment($appointment_id);
                            if ($is_appointment) {
                                $datasource->begin();
                                $response['message']="";
                                $this->AppointmentCustomerStaffService->set('id', $appointment_id);
                                if($status=='SUCCESS'){
                                    $response['message'] = "Appointment booked successfully";
                                    $this->AppointmentCustomerStaffService->set('status', 'CONFIRM');
                                    $this->AppointmentCustomerStaffService->set('payment_by_user_id', $user_id);
                                }else if($status=='FAILURE'){
                                    $response['message'] = "Sorry, appointment not booked";
                                    $this->AppointmentCustomerStaffService->set('status', 'CANCELED');
                                }
                                $this->AppointmentCustomerStaffService->set('payment_status', $status);
                                if ($this->AppointmentCustomerStaffService->save()) {
                                    $datasource->commit();
                                    $response['status'] = 1;
                                    $appointment_data = WebservicesFunction::get_appointment_all_data_id($appointment_id);
                                    $total_sms = Custom::get_total_sms_thinapp($thin_app_id,'T');

                                        if(!empty($appointment_data) && $appointment_data['staff_user_id'] !=0  ){

                                            $app_date = date('d-m-Y h:i A', strtotime($appointment_data['appointment_datetime']));
                                            if ($status == 'SUCCESS') {

                                                /* send appoinment notifincation to staff */

                                                $message = "New appointment scheduled on " . $appointment_data['appointment_datetime'] . ' for service ' . $appointment_data['service_name'];
                                                $sms_message = "You have an appointment with " . $appointment_data['staff_name'] . ' on ' . $app_date . ' for ' . $appointment_data['service_name'];
                                                $option = array(
                                                    'thinapp_id' => $thin_app_id,
                                                    'customer_id' =>0,
                                                    'staff_id' => $appointment_data['appointment_staff_id'],
                                                    'service_id' => $appointment_data['appointment_service_id'],
                                                    'channel_id' => 0,
                                                    'role' => "STAFF",
                                                    'flag' => 'APPOINTMENT',
                                                    'title' => "New Appointment Request",
                                                    'message' => mb_strimwidth($message, 0, 80, '...'),
                                                    'description' => mb_strimwidth($message, 0, 100, '...'),
                                                    'chat_reference' => '',
                                                    'module_type' => 'APPOINTMENT',
                                                    'module_type_id' => $appointment_id,
                                                    'firebase_reference' => ""
                                                );
                                                $background['notification'][0]['data'] = $option;
                                                $background['notification'][0]['user_id'] = $appointment_data['staff_user_id'];

                                                if($total_sms >=1){

                                                    $background['sms'][] =  array(
                                                        'message'=>$message,
                                                        'mobile'=>$appointment_data['staff_mobile']
                                                    );

                                                    $total_sms--;
                                                }


                                                $option = array(
                                                    'thinapp_id' => $thin_app_id,
                                                    'staff_id' =>0,
                                                    'customer_id' => $appointment_data['appointment_customer_id'],
                                                    'service_id' => $appointment_data['appointment_service_id'],
                                                    'channel_id' => 0,
                                                    'role' => "CUSTOMER",
                                                    'flag' => 'APPOINTMENT',
                                                    'title' => "New Appointment Request",
                                                    'message' => mb_strimwidth($sms_message, 0, 80, '...'),
                                                    'description' => mb_strimwidth($sms_message, 0, 100, '...'),
                                                    'chat_reference' => '',
                                                    'module_type' => 'APPOINTMENT',
                                                    'module_type_id' => $appointment_id,
                                                    'firebase_reference' => ""
                                                );
                                                $background['notification'][1]['data'] = $option;
                                                $background['notification'][1]['user_id'] = $appointment_data['customer_user_id'];

                                                if($total_sms >=1) {
                                                    $background['sms'][] =  array(
                                                        'message'=>$sms_message,
                                                        'mobile'=>$appointment_data['customer_mobile']
                                                    );
                                                }


                                            } else if ($status == 'FAILURE') {

                                                if($total_sms >=1) {
                                                    $sms_message = "Your appointment on " . $app_date . " is not confirmed due to payment failure";
                                                    $background['sms'][] =  array(
                                                        'message'=>$sms_message,
                                                        'mobile'=>$appointment_data['customer_mobile']
                                                    );

                                                }
                                                $option = array(
                                                    'thinapp_id' => $thin_app_id,
                                                    'staff_id' => 0,
                                                    'customer_id' => $appointment_data['appointment_customer_id'],
                                                    'service_id' => $appointment_data['appointment_service_id'],
                                                    'channel_id' => 0,
                                                    'role' => "STAFF",
                                                    'flag' => 'APPOINTMENT',
                                                    'title' => "Appointment rescheduled",
                                                    'message' => mb_strimwidth($sms_message, 0, 80, '...'),
                                                    'description' => mb_strimwidth($sms_message, 0, 100, '...'),
                                                    'chat_reference' => '',
                                                    'module_type' => 'APPOINTMENT',
                                                    'module_type_id' => $appointment_id,
                                                    'firebase_reference' => ""
                                                );
                                                $background['notification'][0]['data'] = $option;
                                                $background['notification'][0]['user_id'] = $appointment_data['customer_user_id'];
                                            }
                                    }

                                } else {
                                    $response['status'] = 0;
                                    $response['message'] = "Error when appointment payment";
                                }
                            } else {
                                $response['status'] = 0;
                                $response['message'] = "Sorry this is not valid appointment";
                            }
                        }else{
                            $response['status'] = 0;
                            $response['message'] = "Invalid payment status";
                        }

                    }
                } else {
                    $response['status'] = 0;
                    $response['message'] = $this->Custom->getResponseMessage($result);
                }
            } catch (Exception $e) {
                $datasource->rollback();
                $response['status'] = 0;
                $response['message'] = $e->getMessage();
            }

            Custom::sendResponse($response);
            if (!empty($background)) {
                Custom::send_process_to_background();
                if (isset($background['notification'])) {
                    foreach($background['notification'] as $key => $value){
                        $option = $value['data'];
                        $user_id = $value['user_id'];
                        Custom::send_notification_by_user_id($option, array($user_id),$thin_app_id);
                    }
                }

                if (isset($background['sms'])) {
                    foreach($background['sms'] as $key => $value){
                        $message =$value['message'];
                        $mobile =$value['mobile'];
                        Custom::send_single_sms($mobile, $message, $thin_app_id);
                    }
                }
            }
            exit;

        } else {
            exit();
        }
    }



    /* appointment section end from here */


    /*==========================================APPOINTMENT END====================================================*/

    /**********PAYMENT ITEM STARTS**********/

    public function add_payment_item(){
        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        if ($this->request->is('post')) {
            $response = array();
            $thin_app_id = $data['thin_app_id'];
            $app_key = $data['app_key'];
            $user_id = $data['user_id'];
            $image_path = $data['image_path'];
            $title = $data['title'];
            $description = $data['description'];
            $payment_type = $data['payment_type'];
            $amount_type = $data['amount_type'];

            if($amount_type == 'USER_DEFINED')
            {
                $data['discount_enable'] = 'NO';
            }

            $amount = $data['amount'];
            $quantity_type = $data['quantity_type'];
            $quantity = $data['quantity'];
            $ask_for_quanitiy = $data['ask_for_quanitiy'];
            $show_last_date = $data['show_last_date'];
            $last_date = $data['last_date'];
            $question_to_ask = $data['question_to_ask'];
            $home_delivery = $data['home_delivery'];
            $discount_enable = $data['discount_enable'];
            $discount_type = $data['discount_type'];
            $discount_value = $data['discount_value'];
            $maximum_order_quantity = isset($data['maximum_order_quantity'])?$data['maximum_order_quantity']:1;
            $total_amount = $amount;
            if($discount_enable == 'YES')
            {
                if($discount_type == 'PERCENTAGE')
                {
                    $total_amount = round(($amount - ($amount*($discount_value/100))));
                }
                else
                {
                    $total_amount = round(($amount - $discount_value));
                }
            }
            $share_on = $data['share_on'];
            $channel_id = $data['channel_id'];

            if ( $user_id != '' && $thin_app_id != '' && $title != '' && $payment_type !='' && $quantity_type !='' && $ask_for_quanitiy !='' && $show_last_date !='' && $discount_enable !='' ) {

                if (($result = $this->Custom->CheckIsValidApp($app_key,$thin_app_id)) && ($result == 1) ) {

                    if($share_on == 'CHANNEL')
                    {

                        //    $total_sms = $this->Custom->getTotalRemainingSms($thin_app_id,"P");
                        //   $total_sub = $this->Custom->totalSmsSubscriber($channel_id,$thin_app_id);
                        $total_sms = 15; $total_sub=10;
                        if( $total_sms >= $total_sub ){

                            $datasource = $this->PaymentItem->getDataSource();
                            try {
                                $datasource->begin();
                                $this->PaymentItem->create();
                                $this->PaymentItem->set('user_id', $user_id);
                                $this->PaymentItem->set('thinapp_id', $thin_app_id);
                                $this->PaymentItem->set('image_path', $image_path);
                                $this->PaymentItem->set('title', $title);
                                $this->PaymentItem->set('description', $description);
                                $this->PaymentItem->set('payment_type', $payment_type);
                                $this->PaymentItem->set('amount_type', $amount_type);
                                $this->PaymentItem->set('amount', $amount);
                                $this->PaymentItem->set('quantity_type', $quantity_type);
                                $this->PaymentItem->set('quantity', $quantity);
                                $this->PaymentItem->set('ask_for_quanitiy', $ask_for_quanitiy);
                                $this->PaymentItem->set('show_last_date', $show_last_date);
                                $this->PaymentItem->set('last_date', $last_date);
                                $this->PaymentItem->set('question_to_ask', $question_to_ask);
                                $this->PaymentItem->set('home_delivery', $home_delivery);
                                $this->PaymentItem->set('discount_enable',$discount_enable);
                                $this->PaymentItem->set('discount_type',$discount_type);
                                $this->PaymentItem->set('discount_value',$discount_value);
                                $this->PaymentItem->set('share_on',$share_on);
                                $this->PaymentItem->set('channel_id',$channel_id);
                                $this->PaymentItem->set('total_amount',$total_amount);
                                $this->PaymentItem->set('maximum_order_quantity',$maximum_order_quantity);
                                if ($this->PaymentItem->save()) {
                                    $datasource->commit();
                                    $response['status'] = 1;
                                    $response['message'] = "Payment item added successfully";
                                }else{
                                    $response['status'] = 0;
                                    $response['message'] = "Sorry,Payment item could be not add";
                                }
                            } catch(Exception $e) {
                                $datasource->rollback();
                                $response['status'] = 0;
                                $response['message'] = "Sorry,Payment item could be not add";
                            }

                        }else{
                            $response['status'] = 0;
                            $response['message'] = "System error found. Please try later";
                        }
                    }
                    else
                    {
                        $datasource = $this->PaymentItem->getDataSource();
                        try {
                            $datasource->begin();
                            $this->PaymentItem->create();
                            $this->PaymentItem->set('user_id', $user_id);
                            $this->PaymentItem->set('thinapp_id', $thin_app_id);
                            $this->PaymentItem->set('image_path', $image_path);
                            $this->PaymentItem->set('title', $title);
                            $this->PaymentItem->set('description', $description);
                            $this->PaymentItem->set('payment_type', $payment_type);
                            $this->PaymentItem->set('amount_type', $amount_type);
                            $this->PaymentItem->set('amount', $amount);
                            $this->PaymentItem->set('quantity_type', $quantity_type);
                            $this->PaymentItem->set('quantity', $quantity);
                            $this->PaymentItem->set('ask_for_quanitiy', $ask_for_quanitiy);
                            $this->PaymentItem->set('show_last_date', $show_last_date);
                            $this->PaymentItem->set('last_date', $last_date);
                            $this->PaymentItem->set('question_to_ask', $question_to_ask);
                            $this->PaymentItem->set('home_delivery', $home_delivery);
                            $this->PaymentItem->set('discount_enable',$discount_enable);
                            $this->PaymentItem->set('discount_type',$discount_type);
                            $this->PaymentItem->set('discount_value',$discount_value);
                            $this->PaymentItem->set('share_on',$share_on);
                            $this->PaymentItem->set('channel_id',$channel_id);
                            $this->PaymentItem->set('total_amount',$total_amount);
                            $this->PaymentItem->set('maximum_order_quantity',$maximum_order_quantity);
                            if ($this->PaymentItem->save()) {
                                $last_id = $this->PaymentItem->getLastInsertId();
                                $datasource->commit();
                                $response['data']['insertID'] = $last_id;
                                $response['status'] = 1;
                                $response['message'] = "Payment item added successfully";
                            }else{
                                $response['status'] = 0;
                                $response['message'] = "Sorry,Payment item could be not add";
                            }
                        } catch(Exception $e) {
                            $datasource->rollback();
                            $response['status'] = 0;
                            $response['message'] = "Sorry,Payment item could be not add";
                        }
                    }
                } else {
                    $response['status'] = 0;
                    $response['message'] = $this->Custom->getResponseMessage($result);
                }
            } else {
                $response['status'] = 0;
                $response['message'] = "Invalid request parameter";
            }
            echo json_encode($response);
            exit;
        }
    }

    public function sendPaymentNotification($thinAppID,$paymentItemData,$userID,$mobile,$message_user,$message_admin,$transactionStatus,$paymentItemID,$last_id){

        $option = array(
            'thinapp_id' => $thinAppID,
            'customer_id' =>0,
            'channel_id' => 0,
            'payment_id' => $paymentItemID,
            'transaction_id' => $last_id,
            'role' => 'USER',
            'flag' => 'PAYMENT_ITEM',
            'title' => "New Payment Request",
            'message' => mb_strimwidth($message_user, 0, 80, '...'),
            'description' => mb_strimwidth($message_user, 0, 100, '...'),
            'chat_reference' => '',
            'module_type' => 'PAYMENT',
            'module_type_id' => 0,
            'firebase_reference' => ""
        );
        Custom::send_notification_by_user_id($option,array($userID),$thinAppID);
        Custom::send_single_sms($mobile,$message_user,$thinAppID);

        if($transactionStatus == 'SUCCESS' || $transactionStatus == 'SUSPECT'){
            /* send appoinment notifincation to item owner */
            $option = array(
                'thinapp_id' => $thinAppID,
                'customer_id' =>0,
                'payment_id' => $paymentItemID,
                'transaction_id' => $last_id,
                'channel_id' => 0,
                'role' => 'OWNER',
                'flag' => 'PAYMENT_ITEM',
                'title' => "New Payment Request",
                'message' => mb_strimwidth($message_admin, 0, 80, '...'),
                'description' => mb_strimwidth($message_admin, 0, 100, '...'),
                'chat_reference' => '',
                'module_type' => 'PAYMENT',
                'module_type_id' => 0,
                'firebase_reference' => ""
            );
            Custom::send_notification_by_user_id($option,array($paymentItemData['PaymentItem']['user_id']),$thinAppID);
            $user_data = WebservicesFunction::get_user_data_by_id($paymentItemData['PaymentItem']['user_id']);
            if(!empty($user_data)){
                Custom::send_single_sms($user_data['mobile'],$message_admin,$thinAppID);
            }
        }

    }


    public function add_app_payment_transaction(){
        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        if ($this->request->is('post')) {
            $response = array();
            $userID = $data['user_id'];
            $thinAppID = $data['thin_app_id'];
            $app_key = $data['app_key'];
            $paymentItemID = $data['payment_item_id'];
            $mobile = $data['mobile'];
            $quantity = $data['quantity'];
            $totalAmount = ((int)$data['total_amount']/100);
            $answerOfQuestion = $data['answer_of_question'];
            $deliveryAddress = $data['delivery_address'];
            $discountType = $data['discount_type'];
            $discountValue = $data['discount_value'];
            $transactionStatus = $data['transaction_status'];
            $amt = isset($data['amount'])?$data['amount']:'';
            $transactionID = isset($data['transaction_id'])?$data['transaction_id']:'';
            $totalOrders = $quantity;
            $suspect = false;
            $reason = '';
            $background =array();
            $paymentItemData = $this->PaymentItem->find('first',array('conditions'=>array('PaymentItem.id'=>$paymentItemID),'contain'=>false));

            if($transactionStatus == 'SUCCESS')
            {

                $paymentFileAmountData = array();
                if($paymentItemData['PaymentItem']['payment_type'] == 'BY_FILE')
                {
                    $paymentFileAmountData = $this->PaymentFileAmount->find('first',array(
                            'conditions'=>array(
                                'PaymentFileAmount.payment_item_id'=>$paymentItemID,
                                'PaymentFileAmount.user_id'=>$userID,
                            ),
                            'contain'=>false
                        )
                    );
                    $paymentItemData['PaymentItem']['total_amount'] = $paymentFileAmountData['PaymentFileAmount']['amount'];
                }
                $totalAmountByDB = ($paymentItemData['PaymentItem']['total_amount'] * $quantity);

                if($paymentItemData['PaymentItem']['amount_type'] != 'USER_DEFINED')
                {
                    if((int)$totalAmount <> (int)$totalAmountByDB)
                    {
                        $suspect = true;
                        $reason = 'Total paid amount is different from total amount';
                    }
                    else if( !empty($paymentItemData['PaymentItem']['maximum_order_quantity']) && ( $paymentItemData['PaymentItem']['maximum_order_quantity'] < $quantity ) )
                    {
                        $suspect = true;
                        $reason = 'Quantity is greater then maximum order quantity';
                    }
                }

                if($suspect === true)
                {
                    $transactionStatus = 'SUSPECT';
                }
                $totalOrders = ( (int)$quantity + (int)$paymentItemData['PaymentItem']['total_orders'] );
            }

            $datasource = $this->AppPaymentTransaction->getDataSource();
            try {
                $datasource->begin();
                $this->AppPaymentTransaction->create();
                $this->AppPaymentTransaction->set('payment_item_id', $paymentItemID);
                $this->AppPaymentTransaction->set('thinapp_id', $thinAppID);
                $this->AppPaymentTransaction->set('user_id', $userID);
                $this->AppPaymentTransaction->set('mobile', $mobile);
                $this->AppPaymentTransaction->set('quantity', $quantity);
                $this->AppPaymentTransaction->set('total_amount', $totalAmount);
                $this->AppPaymentTransaction->set('answer_of_question', $answerOfQuestion);
                $this->AppPaymentTransaction->set('delivery_address', $deliveryAddress);
                $this->AppPaymentTransaction->set('discount_type', $discountType);
                $this->AppPaymentTransaction->set('discount_value', $discountValue);
                $this->AppPaymentTransaction->set('transaction_status', $transactionStatus);
                $this->AppPaymentTransaction->set('transaction_id', $transactionID);
                $this->AppPaymentTransaction->set('reason', $reason);
                // $this->AppPaymentTransaction->set('redeem_time', date("Y-m-d H:i:s"));

                if($paymentItemData['PaymentItem']['amount_type'] == 'USER_DEFINED')
                {
                    $this->AppPaymentTransaction->set('amount', $amt);
                }
                else
                {
                    $this->AppPaymentTransaction->set('amount', $paymentItemData['PaymentItem']['total_amount']);
                }


                if ($this->AppPaymentTransaction->save()) {
                    $dataToUpdatePaymentItem = array();
                    $last_id = $this->AppPaymentTransaction->getLastInsertId();

                    $dataToUpdatePaymentItem["PaymentItem.id"] = $paymentItemID;
                    $dataToUpdatePaymentItem["PaymentItem.total_orders"] = $totalOrders;
                    if( $paymentItemData['PaymentItem']['quantity_type'] == 'LIMITED' )
                    {
                        if( $totalOrders >= $paymentItemData['PaymentItem']['quantity'])
                        {
                            $this->PaymentItem->set('quantity_status','SOLD');
                        }
                        else
                        {
                            $this->PaymentItem->set('quantity_status','UNSOLD');
                        }
                    }
                    else
                    {
                        $this->PaymentItem->set('quantity_status','UNSOLD');
                    }

                    //$this->PaymentItem->id = $paymentItemID;
                    $this->PaymentItem->set('id',$paymentItemID);
                    $this->PaymentItem->set('total_orders',$totalOrders);
                    $updateTotalOrder = $this->PaymentItem->save();

                    $lastRowID = time().$last_id;
                    $lastRowID = substr($lastRowID, -9);
                    $lastRowIDArr = str_split($lastRowID,3);
                    $uniqueID = implode("-", $lastRowIDArr);

                    $this->AppPaymentTransaction->id = $last_id;
                    $updateUniqueID = $this->AppPaymentTransaction->saveField('unique_id', $uniqueID);


                    if(!empty($paymentFileAmountData))
                    {
                        $this->AppPaymentTransaction->saveField('payment_file_amount_id', $paymentFileAmountData['PaymentFileAmount']['id']);
                        $paymentFileAmountData['PaymentFileAmount']['payment_status'] = 'PAID';
                        if($this->PaymentFileAmount->saveAll($paymentFileAmountData) && $updateTotalOrder && $updateUniqueID)
                        {
                            $datasource->commit();
                            $response['status'] = 1;
                            $response['message'] = "Successfully Completed";
                            $response['data']['app_payment_transaction_id'] = $last_id;
                            $response['data']['payment_item_id'] = $paymentItemID;

                            $total_amt = $paymentItemData['PaymentItem']['total_amount'];
                            if($transactionStatus == 'SUCCESS' || $transactionStatus == 'SUSPECT'){

                                $userData = $this->User->find('first',array('conditions'=>array('User.id'=>$userID),'fields' => array("User.username"),'contain'=>false));

                                $message_user = "Your transaction for " . $paymentItemData['PaymentItem']['title'] . " Amount - Rs ".$totalAmount." done successfully";
                                $message_admin = "User ".$userData['User']['username']." has completed transaction for " . $paymentItemData['PaymentItem']['title'] . " Amount - Rs ".$totalAmount;


                            }else if($transactionStatus == 'FAILED'){
                                $message_user = "Your transaction for " . $paymentItemData['PaymentItem']['title'] . " Amount - Rs ".$totalAmount." failed";
                                $message_admin = "";
                            }

                            $background['notification'] =array(
                                'payment_item_data'=>$paymentItemData,
                                'user_id'=>$userID,
                                'mobile'=>$mobile,
                                'message_user'=>$message_user,
                                'message_admin'=>$message_admin,
                                'transaction_status'=>$transactionStatus,
                                'payment_itme_id'=>$paymentItemID,
                                'last_id'=>$last_id
                            );
                        }
                        else
                        {
                            $datasource->rollback();
                            $response['status'] = 0;
                            $response['message'] = "Sorry,Payment transaction could not be added";
                        }
                    }
                    else
                    {
                        if($updateTotalOrder && $updateUniqueID)
                        {
                            $datasource->commit();
                            $response['status'] = 1;
                            $response['message'] = "Successfully Completed";
                            $response['data']['app_payment_transaction_id'] = $last_id;
                            $response['data']['payment_item_id'] = $paymentItemID;

                            $total_amt = $paymentItemData['PaymentItem']['total_amount'];
                            if($transactionStatus == 'SUCCESS' || $transactionStatus == 'SUSPECT'){
                                $message_user = "Your transaction for " . $paymentItemData['PaymentItem']['title'];
                                $message_user .= ($paymentItemData['PaymentItem']['payment_type'] != 'FREE')?" Amount - Rs ".$totalAmount:"";
                                $message_user .= " done successfully";
                                $userData = $this->User->find('first',array('conditions'=>array('User.id'=>$userID),'fields' => array("User.username"),'contain'=>false));
                                $message_admin = "User ".$userData['User']['username']." has completed transaction for " . $paymentItemData['PaymentItem']['title'];
                                $message_admin .= ($paymentItemData['PaymentItem']['payment_type'] != 'FREE')?" Amount - Rs ".$totalAmount:"";
                            }else if($transactionStatus == 'FAILED'){
                                $message_user = "Your transaction for " . $paymentItemData['PaymentItem']['title'];
                                $message_user .=  ($paymentItemData['PaymentItem']['payment_type'] != 'FREE')?" Amount - Rs ".$totalAmount." failed.":"";
                                $message_admin = "";
                            }

                            $background['notification'] =array(
                                'payment_item_data'=>$paymentItemData,
                                'user_id'=>$userID,
                                'mobile'=>$mobile,
                                'message_user'=>$message_user,
                                'message_admin'=>$message_admin,
                                'transaction_status'=>$transactionStatus,
                                'payment_itme_id'=>$paymentItemID,
                                'last_id'=>$last_id
                            );

                        }
                        else
                        {
                            $datasource->rollback();
                            $response['status'] = 0;
                            $response['message'] = "Sorry,Payment transaction could not be add";
                        }
                    }

                }
                else
                {
                    $datasource->rollback();
                    $response['status'] = 0;
                    $response['message'] = "Sorry,Payment transaction could be not add";
                }
            }
            catch(Exception $e) {
                $datasource->rollback();
                $response['status'] = 0;
                $response['message'] = "Sorry,Payment transaction could be not add";
            }
            Custom::sendResponse($response);
            if(!empty($background)){
                Custom::send_process_to_background();
                $data = $background['notification'];
                $this->sendPaymentNotification($thinAppID,$data['payment_item_data'],$data['user_id'],$data['mobile'],$data['message_user'],$data['message_admin'],$data['transaction_status'],$data['payment_itme_id'],$data['last_id']);
            }
            exit;
        }
    }

    public function get_list_all_payment_item(){

        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        if ($this->request->is('post')) {
            $app_key = $data['app_key'];
            $user_id = $data['user_id'];
            $thin_app_id = $data['thin_app_id'];
            $offset = $data['offset'];
            $limit = APP_PAGINATION_LIMIT;

            $response = array();
            if ($user_id != '' && $app_key != '' && $thin_app_id != '') {
                if (($result = $this->Custom->CheckIsValidApp($app_key,$thin_app_id)) && ($result == 1) ) {
                    $paymentItem = $this->PaymentItem->find('all',array(
                        'conditions' => array(
                            'PaymentItem.thinapp_id'=>$thin_app_id,
                            'PaymentItem.status'=>'ACTIVE',
                            'PaymentItem.payment_type !='=>'BY_FILE'
                        ),
                        'contain' =>false,
                        'order' => array("PaymentItem.id"=> "DESC"),
                        'offset'=>$offset * $limit,
                        'limit'=> $limit
                    ));
                    $dataToSend = array();
                    $arr = array();
                    if(!empty($paymentItem)) {
                        foreach ($paymentItem as $key => $paymentData) {
                            $arr['id'] = $paymentData['PaymentItem']['id'];
                            $arr['title'] = $paymentData['PaymentItem']['title'];
                            $arr['description'] =  $paymentData['PaymentItem']['description'];
                            $arr['payment_type'] = $paymentData['PaymentItem']['payment_type'];
                            $arr['amount_type'] = $paymentData['PaymentItem']['amount_type'];
                            $arr['amount'] = $paymentData['PaymentItem']['amount'];
                            $arr['quantity_type'] = $paymentData['PaymentItem']['quantity_type'];
                            $arr['quantity'] = $paymentData['PaymentItem']['quantity'];
                            $arr['ask_for_quanitiy'] = $paymentData['PaymentItem']['ask_for_quanitiy'];
                            $arr['show_last_date'] = $paymentData['PaymentItem']['show_last_date'];
                            $arr['last_date'] = $paymentData['PaymentItem']['last_date'];
                            $arr['question_to_ask'] = $paymentData['PaymentItem']['question_to_ask'];
                            $arr['home_delivery'] = $paymentData['PaymentItem']['home_delivery'];
                            $arr['discount_enable'] = $paymentData['PaymentItem']['discount_enable'];
                            $arr['discount_type'] = $paymentData['PaymentItem']['discount_type'];
                            $arr['discount_value'] = $paymentData['PaymentItem']['discount_value'];
                            $arr['total_amount'] = $paymentData['PaymentItem']['total_amount'];
                            $arr['share_on'] = $paymentData['PaymentItem']['share_on'];
                            $arr['channel_id'] = $paymentData['PaymentItem']['channel_id'];
                            $arr['status'] = $paymentData['PaymentItem']['status'];
                            $arr['is_owner'] =  ($paymentData['PaymentItem']['user_id']==$user_id)?"YES":"NO";
                            array_push($dataToSend,$arr);
                        }
                        $response['status'] = 1;
                        $response['message'] = "Payment item list found";
                        $response['data']['payment_list'] = $dataToSend;
                    }
                    else {
                        $response['status'] = 0;
                        $response['message'] = "There is no payment item";
                    }
                } else {
                    $response['status'] = 0;
                    $response['message'] = $this->Custom->getResponseMessage($result);
                }
            } else {
                $response['status'] = 0;
                $response['message'] = "Invalid request parameter";
            }
            echo  json_encode($response);
            exit;
        }
    }

    public function get_my_dues_list(){

        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        if ($this->request->is('post')) {
            $app_key = $data['app_key'];
            $user_id = $data['user_id'];
            $thin_app_id = $data['thin_app_id'];
            $offset = $data['offset'];
            $limit = APP_PAGINATION_LIMIT;

            $response = array();
            if ($user_id != '' && $app_key != '' && $thin_app_id != '') {
                if (($result = $this->Custom->CheckIsValidApp($app_key,$thin_app_id)) && ($result == 1) ) {
                    $paymentItem = $this->PaymentFileAmount->find('all',array(
                        'conditions' => array(
                            'PaymentFileAmount.thinapp_id'=>$thin_app_id,
                            'PaymentFileAmount.status'=>'ACTIVE',
                            'PaymentFileAmount.user_id'=>$user_id,
                        ),
                        'contain' =>('PaymentItem'),
                        'order' => array("PaymentFileAmount.id"=>"DESC"),
                        'offset'=>$offset * $limit,
                        'limit'=> $limit
                    ));
                    $dataToSend = array();
                    $arr = array();
                    if(!empty($paymentItem)) {
                        foreach ($paymentItem as $key => $paymentData) {
                            $arr['id'] = $paymentData['PaymentItem']['id'];
                            $arr['title'] = $paymentData['PaymentItem']['title'];
                            $arr['description'] =  $paymentData['PaymentItem']['description'];
                            $arr['payment_type'] = $paymentData['PaymentItem']['payment_type'];
                            $arr['amount_type'] = $paymentData['PaymentItem']['amount_type'];
                            $arr['amount'] = $paymentData['PaymentItem']['amount'];
                            $arr['quantity_type'] = $paymentData['PaymentItem']['quantity_type'];
                            $arr['quantity'] = $paymentData['PaymentItem']['quantity'];
                            $arr['ask_for_quanitiy'] = $paymentData['PaymentItem']['ask_for_quanitiy'];
                            $arr['show_last_date'] = $paymentData['PaymentItem']['show_last_date'];
                            $arr['last_date'] = $paymentData['PaymentItem']['last_date'];
                            $arr['question_to_ask'] = $paymentData['PaymentItem']['question_to_ask'];
                            $arr['home_delivery'] = $paymentData['PaymentItem']['home_delivery'];
                            $arr['discount_enable'] = $paymentData['PaymentItem']['discount_enable'];
                            $arr['discount_type'] = $paymentData['PaymentItem']['discount_type'];
                            $arr['discount_value'] = $paymentData['PaymentItem']['discount_value'];
                            $arr['total_amount'] = $paymentData['PaymentItem']['total_amount'];
                            $arr['share_on'] = $paymentData['PaymentItem']['share_on'];
                            $arr['channel_id'] = $paymentData['PaymentItem']['channel_id'];
                            $arr['status'] = $paymentData['PaymentItem']['status'];
                            $arr['is_owner'] =  ($paymentData['PaymentItem']['user_id']==$user_id)?"YES":"NO";
                            array_push($dataToSend,$arr);
                        }
                        $response['status'] = 1;
                        $response['message'] = "Payment item list found";
                        $response['data']['payment_list'] = $dataToSend;
                    }
                    else
                    {
                        $response['status'] = 0;
                        $response['message'] = "There is no payment item";
                    }
                }
                else
                {
                    $response['status'] = 0;
                    $response['message'] = $this->Custom->getResponseMessage($result);
                }
            } else {
                $response['status'] = 0;
                $response['message'] = "Invalid request parameter";
            }
            echo  json_encode($response);
            exit;
        }
    }

    public function change_order_redeem_status(){
        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        if ($this->request->is('post')) {
            $response = array();
            $app_key = $data['app_key'];
            $user_id = $data['user_id'];
            $thinapp_id = $data['thin_app_id'];
            $appPaymentTransactionID = $data['app_payment_transaction_id'];


            $datasource = $this->AppPaymentTransaction->getDataSource();
            try {
                $datasource->begin();

                $tranData = $this->AppPaymentTransaction->find('first',
                    array(
                        'conditions'=>array(
                            'AppPaymentTransaction.id' => $appPaymentTransactionID,
                            'AppPaymentTransaction.thinapp_id' => $thinapp_id,
                        ),
                        'contain'=>array('PaymentItem'),
                        'fields'=>array('AppPaymentTransaction.*','PaymentItem.title','PaymentItem.user_id')
                    )
                );

                if($tranData['AppPaymentTransaction']['redeem_status'] == 'NO')
                {
                    $this->AppPaymentTransaction->id = $appPaymentTransactionID;
                    $checkUpdate = $this->AppPaymentTransaction->saveField("redeem_status", 'YES');
                    $timeUpdate = $this->AppPaymentTransaction->saveField("redeem_time", date("Y-m-d H:i:s"));
                    if($checkUpdate && $timeUpdate)
                    {
                        $datasource->commit();
                        $response['status'] = 1;
                        $response['message'] = "Order redeemed successfully";

                        $total_sms = Custom::get_total_sms_thinapp($thinapp_id,'T');
                        /* send appoinment notifincation to payer */
                        $message = "You have successfully redeemed " . $tranData['PaymentItem']['title'];
                        $user_data = WebservicesFunction::get_user_data_by_id($user_id);
                        $option = array(
                            'thinapp_id' => $thinapp_id,
                            'customer_id' =>0,
                            'channel_id' => 0,
                            'role' => 'USER',
                            'flag' => 'ORDER_REDEEM',
                            'title' => "Order redeem",
                            'message' => mb_strimwidth($message, 0, 80, '...'),
                            'description' => mb_strimwidth($message, 0, 100, '...'),
                            'chat_reference' => '',
                            'module_type' => 'ORDER_REDEEM',
                            'module_type_id' => 0,
                            'firebase_reference' => ""
                        );

                        Custom::send_notification_by_user_id($option,array($user_id),$thinapp_id);
                        Custom::send_single_sms($user_data['mobile'],$message,$thinapp_id);


                        /* send appoinment notifincation to item owner */
                        $message = "User, ".$user_data['mobile']." has redeemed " . $tranData['PaymentItem']['title'];
                        $user_data = WebservicesFunction::get_user_data_by_id($tranData['PaymentItem']['user_id']);
                        $option = array(
                            'thinapp_id' => $thinapp_id,
                            'customer_id' =>0,
                            'channel_id' => 0,
                            'role' => 'OWNER',
                            'flag' => 'ORDER_REDEEM',
                            'title' => "Order redeem",
                            'message' => mb_strimwidth($message, 0, 80, '...'),
                            'description' => mb_strimwidth($message, 0, 100, '...'),
                            'chat_reference' => '',
                            'module_type' => 'ORDER_REDEEM',
                            'module_type_id' => 0,
                            'firebase_reference' => ""
                        );

                        Custom::send_notification_by_user_id($option,array( $user_data['id']),$thinapp_id);
                        Custom::send_single_sms($user_data['mobile'],$message,$thinapp_id);



                    }
                    else
                    {
                        $response['status'] = 0;
                        $response['message'] = "Order Could not be redeemed";
                    }
                }
                else
                {
                    $response['status'] = 2;
                    $response['message'] = "Order is already redeemed";
                }
            }
            catch(Exception $e) {
                $datasource->rollback();
                $response['status'] = 0;
                $response['message'] = "Sorry,Payment transaction could be not add";
            }
            echo json_encode($response);
            exit;
        }
    }


    /**********PAYMENT ITEM END**********/





    /*****************DRIVE STARTS***************/

    public function change_folder_type()
    {
        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        if ($this->request->is('post')) {

            $response = array();
            $user_id = $data['user_id'];
            $thin_app_id = $data['thin_app_id'];
            $app_key = $data['app_key'];
            $drive_folder_id = $data['drive_folder_id'];
            $folder_type = $data['folder_type'];

            if ($user_id != '' && $app_key != '' && $thin_app_id!='') {

                if (($result = $this->Custom->CheckIsValidApp($app_key,$thin_app_id)) && ($result == 1) ) {

                    $update = $this->DriveFolder->updateAll(array(
                        'DriveFolder.folder_type' => "'".$folder_type."'"),
                        array('DriveFolder.id' => $drive_folder_id)
                    );

                    if ($update) {
                        $response['status'] = 1;
                        $response['message'] = "Success";
                    }else{
                        $response['status'] = 0;
                        $response['message'] = "Fail";
                    }

                } else {
                    $response['status'] = 0;
                    $response['message'] = $this->Custom->getResponseMessage($result);
                }

            } else {
                $response['status'] = 0;
                $response['message'] = "Invalid request parameter";
            }
            echo json_encode($response);
            exit;
        }
    }



    public function update_download_count()
    {
        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        if ($this->request->is('post')) {
            $response = array();
            $user_id = $data['user_id'];
            $thin_app_id = $data['thin_app_id'];
            $app_key = $data['app_key'];
            $drive_file_id = $data['drive_file_id'];

            if ($user_id != '' && $app_key != '' && $thin_app_id!='') {
                if (($result = $this->Custom->CheckIsValidApp($app_key,$thin_app_id)) && ($result == 1) ) {

                    $update = $this->DriveFile->updateAll(
                        array('DriveFile.download_count' => 'DriveFile.download_count + 1'),
                        array('DriveFile.id' => $drive_file_id)
                    );

                    if ($update) {
                        $response['status'] = 1;
                        $response['message'] = "Download count updated successfully";
                    }else{
                        $response['status'] = 0;
                        $response['message'] = "Download count could not be updated";
                    }
                } else {
                    $response['status'] = 0;
                    $response['message'] = $this->Custom->getResponseMessage($result);
                }
            } else {
                $response['status'] = 0;
                $response['message'] = "Invalid request parameter";
            }
            echo json_encode($response);
            exit;
        }
    }

    public function delete_share()
    {
        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        if ($this->request->is('post')) {
            $response = array();
            $user_id = $data['user_id'];
            $thin_app_id = $data['thin_app_id'];
            $app_key = $data['app_key'];
            $drive_share_id = $data['drive_share_id'];

            if ($user_id != '' && $app_key != '' && $thin_app_id!='') {

                if (($result = $this->Custom->CheckIsValidApp($app_key,$thin_app_id)) && ($result == 1) ) {
                    $share_data = WebservicesFunction::get_share_data_by_id($drive_share_id);

                    if($share_data) {
                        $shared_object = $share_data['shared_object'];
                        $delete = $this->DriveShare->delete(array('DriveShare.id' => $drive_share_id));
                        if ($delete) {
                            $response['status'] = 1;
                            $response['message'] = "Object successfully unshared";


                            $total_sms = Custom::get_total_sms_thinapp($thin_app_id, 'T');

                            if (!empty($share_data)) {
                                $share_with_mobile = $share_data['share_with_mobile'];
                                $share_from_mobile = $share_data['share_from_mobile'];

                                $user_data = WebservicesFunction::get_user_data_by_mobile($share_from_mobile, $thin_app_id);
                                $file_name = $share_data['name'];


                                /* send appoinment notifincation to staff */
                                if (!empty($user_data)) {

                                    $message = $share_with_mobile . " has unshared " . strtolower($shared_object) . " $file_name";
                                    $option = array(
                                        'thinapp_id' => $thin_app_id,
                                        'user_id' => $user_data['id'],
                                        'data' => array(
                                            'thinapp_id' => $thin_app_id,
                                            'channel_id' => 0,
                                            'flag' => 'SHARE_DRIVE',
                                            'title' => "New Appointment Request",
                                            'message' => mb_strimwidth($message, 0, 80, '...'),
                                            'description' => mb_strimwidth($message, 0, 100, '...'),
                                            'chat_reference' => '',
                                            'module_type' => 'SHARE_DRIVE',
                                            'module_type_id' => 0,
                                            'firebase_reference' => ""
                                        )
                                    );
                                    $this->Custom->send_notification_to_device($option);
                                }


                                if ($total_sms >= 1) {
                                    $message = $share_with_mobile . " has unshared " . strtolower($shared_object) . " " . $file_name . "";
                                    $option = array(
                                        'mobile' => $share_from_mobile,
                                        'message' => urlencode($message),
                                        'thinapp_id' => $thin_app_id
                                    );
                                    $this->Custom->send_message_system($option);
                                }
                            }

                        } else {
                            $response['status'] = 0;
                            $response['message'] = ucwords(strtolower($shared_object)) . " could not be unshared";
                        }
                    }else{
                            $response['status'] = 0;
                            $response['message'] =  "You can not unshare this file";
                    }

                } else {
                    $response['status'] = 0;
                    $response['message'] = $this->Custom->getResponseMessage($result);
                }

            } else {
                $response['status'] = 0;
                $response['message'] = "Invalid request parameter";
            }

            echo json_encode($response);
            exit;
        }
    }



    /*****************DRIVE ENDS***************/




	
	public function get_organization_type_for_register(){
		$this->layout = false;
        $organization = $this->OrganizationType->find('all', array('fields' => array('org_type','org_type_id')));
		echo json_encode($organization); die;
	}
	
	

	public function register_organization()
    {
        $this->layout = false;

        $current_date = date('Y-m-d H:i:s');
        if (!empty($this->data)) {
            $datasource = $this->User->getDataSource();
            try {
                $datasource->begin();
                    $userdata = null;
                    $userdata = $this->User->find("first", array(
                        "conditions" => array(
                            "User.mobile" => $this->request->data['User']['mobile'],
                            "User.org_unique_url" => $this->request->data['User']['org_unique_url'],
                            "User.role_id" => 5
                        )
                    ));
                    if (empty($userdata)) {

                        $this->User->set($this->request->data['User']);
                        if ($this->User->validates()) {
                                $verification_code = $this->Custom->getRandomString(4);
                                $mobilenumber = $this->request->data['User']['mobile'];
                                $mob_vf_code = $this->Custom->getRandomAppKey(4);

                                $org_name = trim($this->data['User']['org_name']);

                                $org_unique_url = trim($this->data['User']['org_unique_url']);
                                $org_name = strtolower($org_name);
                                $this->request->data['User']['username'] = $org_name;

                                $this->request->data['User']['org_unique_url'] = $org_unique_url;
                                $this->request->data['User']['is_verified'] = 'N';
                                // Role id 5 means guest
                                $this->request->data['User']['role_id'] = '5';
                                $this->request->data['User']['thinapp_id'] = 0;
                                $this->request->data['User']['device_type'] = 'WEB';
                                $this->request->data['User']['verification_code'] = $verification_code;
                                $this->request->data['User']['mob_vf_code'] = $mob_vf_code;
                                $this->request->data['User']['email'] = $this->data['User']['email'];
                                $this->request->data['User']['app_key'] = $this->Custom->getRandomAppKey(8);
                                $this->request->data['User']['mobile'] = $mobilenumber;
                                //  $this->request->data['User']['expiredate'] = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s", strtotime($current_date)) . " + 365 day"));

                                $this->request->data['User']['password'] =  md5($this->request->data['User']['password']);;

                                if($this->User->save( $this->request->data['User'],false)){

                                    $last_insert_id = $this->User->getLastInsertId();

                                    /* insert data inot lead*/
                                    $this->request->data['Leads']['user_id'] = $last_insert_id;
                                    $this->request->data['Leads']['mobile'] = $mobilenumber;
                                    $this->request->data['Leads']['org_name'] = $org_name;
                                    $this->request->data['Leads']['cust_email'] = $this->data['User']['email'];
                                    $this->request->data['Leads']['org_type'] = $this->data['User']['org_type'];
                                    //$this->request->data['Leads']['comments'] = $this->data['User']['comments'];
                                    $this->request->data['Leads']['org_unique_url'] = $org_unique_url;
                                    if($this->Leads->save($this->request->data['Leads'])){
                                        $username = $this->data['User']['username'];
                                        $mobile = $mobilenumber;
                                        $option =array(
                                            'username'=>$username,
                                            'mobile'=>$mobile,
                                            'verification'=>$verification_code,
                                            'thinapp_id'=>-1
                                        );
                                        $this->Custom->send_otp($option);

                                        $userdata = $this->User->find("first", array(
                                            "conditions" => array(
                                                "User.mobile" => $this->request->data['User']['mobile'],
                                                "User.org_unique_url" =>$org_unique_url,
                                                "User.role_id" => 5
                                            )
                                        ));

                                        /* this code will sending email and sms notification to super admin and */

                                        $body = "Hello admin, \n\n New lead request received on website detail are following: <br>";
                                        $body .= "Mobile: ".$mobilenumber."<br>";
                                        $body .= "Email: ".$this->data['User']['email']."<br>";
                                        $body .= "Orgnization: ".$org_name."<br>";
                                        //$body .= "Message: "."New lead request for customer"."<br>";
                                        $subject = "New lead request";
                                        $to = SUPER_ADMIN_EMAIL;
                                        $from = $this->data['User']['email'];
                                        
                                        $this->Custom->sendEmail($to,$from,$subject,$body,$org_name);
                                        $message = "You have received new lead request from ".$mobilenumber." for organization ".$org_name.". User is not verify.";
                                        $super_admin_mobile = SUPER_ADMIN_MOBILE;
                                        $thin_app_id = MBROADCAST_APP_ID;
                                        $option = array(
                                            'mobile' => $super_admin_mobile,
                                            'message' => urlencode($message),
                                            'thinapp_id' => $thin_app_id,
                                            'sender_id' => $last_insert_id
                                        );
                                        $this->Custom->send_message_system($option);

                                        /* end notification*/




                                        $datasource->commit();
                                        //$this->redirect(array('controller' => 'app_admin', 'action' => 'verify',"?"=>array('m_c' => $userdata['User']['mob_vf_code'])));
										//echo "Success";
										$dataToShow = array('status'=>1,'m_c'=>$userdata['User']['mob_vf_code']);
                                    }else{
                                        $datasource->rollback();
                                        //$this->Session->setFlash(__('Sorry user could not registered.'), 'default', array(), 'front_error');
										//echo "Error";
										$dataToShow = array('status'=>0,'error'=>'Sorry user could not registered.');
									}

                                }else{
                                    //$this->Session->setFlash(__('Sorry user could not registered.'), 'default', array(), 'front_error');
									//echo "Error";
									$dataToShow = array('status'=>0,'error'=>'Sorry user could not registered.');
								}
                        }else{
                            $errors = $this->User->validationErrors;
							$error = "";
							foreach($errors AS $key => $err){
								$error = $err[0];
							}
							$dataToShow = array('status'=>0,'error'=>$error);
                        }

                    } else {
                         if ($userdata['User']['is_verified'] == 'N') {
                             $username = $userdata['User']['username'];
                             $mobile = $userdata['User']['mobile'];
                             $verification_code = $userdata['User']['mob_vf_code'];
                             $option =array(
                                 'username'=>$username,
                                 'mobile'=>$mobile,
                                 'verification'=>$verification_code,
                                 'thinapp_id'=>-1
                             );
                             $this->Custom->send_otp($option);
                             //$this->redirect(array('controller' => 'app_admin', 'action' => 'verify',"?"=>array('m_c' => $verification_code)));
							$dataToShow = array('status'=>1,'m_c'=>$verification_code);
						 } else {
                             //$this->Session->setFlash(__('This Mobile number is already registered.'), 'default', array(), 'warning');
							$dataToShow = array('status'=>0,'error'=>'This Mobile number is already registered.');
						 }

                    }


            }catch (Exception $e){
                $datasource->rollback();

                //echo $e->getMessage();die;
				$dataToShow = array('status'=>0,'error'=>$e->getMessage());
            }


        }

        //$this->set('organization_type', $organization);
        //$this->set('organization_type', $this->OrganizationType->find('list', array('conditions' => array('OrganizationType.status' => 'ACTIVE'), 'fields' => array('org_type_id', 'org_type'))));
		echo json_encode($dataToShow);
		die;
    }


    public function savePatientData()
    {

        $mode = MENGAGE_API_ACCESS_MODE;
        $this->autoRendar = false;
        $this->layout = false;
        $header = getallheaders();
        $key = (isset($header['key']) && !empty($header['key']))?$header['key']:"";
        if(!empty($key)) {
            $access_key = @Custom::readAccessKey($key, $mode);
            $tmp_array = @explode("XXX", $access_key);
            $thin_app_id = isset($tmp_array[1])?$tmp_array[1]:0;
            $app_data = Custom::getThinAppData($thin_app_id);
            if ($tmp_array[0] == MENGAGE_API_ACCESS_MODE && $app_data['mengage_api_accss_key'] == $key) {

                $request = file_get_contents("php://input");
                $data = json_decode($request, true);

                $app_admin_data = Custom::get_thinapp_admin_data($thin_app_id);
                $app_key = APP_KEY;
                $user_id = @$app_admin_data['id'];
                $mobile = @$app_admin_data['mobile'];
                $doctor_mobile = isset($data['doctor_mobile']) ? Custom::create_mobile_number($data['doctor_mobile']) : "";
                $patient_name = isset($data['patient_name']) ? $data['patient_name'] : "";
                $patient_mobile = isset($data['patient_mobile']) ? Custom::create_mobile_number($data['patient_mobile']) : "";
                $patient_dob = isset($data['patient_dob']) ? $data['patient_dob'] : "";
                $patient_gender = isset($data['patient_gender']) ? trim($data['patient_gender']) : "";
                $patient_address = isset($data['patient_address']) ? trim($data['patient_address']) : "";
                $patient_uhid = isset($data['patient_uhid']) ? trim($data['patient_uhid']) : "";
                $patient_email = isset($data['patient_email']) ? trim($data['patient_email']) : "";
                $patient_blood_group = isset($data['patient_blood_group']) ? trim($data['patient_blood_group']) : "";

                if (empty($user_id)) {
                    $response['status'] = 0;
                    $response['message'] = 'Invalid user';
                } else if (empty($thin_app_id)) {
                    $response['status'] = 0;
                    $response['message'] = 'Invalid thinapp';
                } else if (empty($app_admin_data)) {
                    $response['status'] = 0;
                    $response['message'] = 'Invalid App';
                } else if (empty($app_key)) {
                    $response['status'] = 0;
                    $response['message'] = 'Invalid app';
                } else if (empty($mobile)) {
                    $response['status'] = 0;
                    $response['message'] = 'Invalid mobile';
                } else if (empty($patient_name)) {
                    $response['status'] = 0;
                    $response['message'] = 'Invalid Patient Name';
                } else if (empty($patient_mobile)) {
                    $response['status'] = 0;
                    $response['message'] = 'Invalid Patient Mobile';
                } else if (empty($doctor_mobile)) {
                    $response['status'] = 0;
                    $response['message'] = 'Invalid Doctor Mobile';
                } else {

                    $connection = ConnectionUtil::getConnection();

                    $query = "SELECT * FROM appointment_staffs WHERE thinapp_id = $thin_app_id and mobile ='$doctor_mobile' and status = 'ACTIVE' and staff_type = 'DOCTOR' limit 1";
                    $service_message_list = $connection->query($query);
                    if ($service_message_list->num_rows) {
                        $doctor = mysqli_fetch_assoc($service_message_list);
                        $doctor_id = $doctor["id"];
                        if (Custom::check_app_enable_permission($thin_app_id, 'NEW_QUICK_APPOINTMENT')) {
                            $doctor_data = Custom::ive_get_doctor_custom_data($thin_app_id, $doctor_id, true);
                        } else {
                            $doctor_data = Custom::ive_get_doctor_custom_data($thin_app_id, $doctor_id);
                        }
                        if (!empty($doctor_data)) {
                            $address_id = $doctor_data['address_id'];
                            $service_id = 0;
                            if (Custom::check_app_enable_permission($thin_app_id, 'NEW_QUICK_APPOINTMENT')) {
                                $service_id = $doctor_data['service_id'];
                            }
                            if (empty($patient_name)) {
                                $customer_id = Custom::get_customer_first_id_by_mobile($thin_app_id, $patient_mobile);
                            } else {
                                $customer_id = Custom::search_customer_name($thin_app_id, $patient_mobile, $patient_name);
                                $customer_id = !empty($customer_id) ? $customer_id['id'] : 0;
                            }

                            $post = array();
                            $post['app_key'] = APP_KEY;
                            $post['thin_app_id'] = $thin_app_id;
                            $post['user_id'] = $doctor_data['user_id'];
                            $post['role_id'] = $doctor_data['role_id'];
                            $post['mobile'] = $doctor_data['mobile'];
                            $post['booking_date'] = date('Y-m-d');
                            $post['slot_time'] = date('h:i A');
                            $post['doctor_id'] = $doctor_id;
                            $post['user_type'] = "CUSTOMER";
                            $post['children_id'] = 0;
                            $post['address_id'] = $address_id;
                            $post['service_id'] = $service_id;
                            $post['customer_id'] = $customer_id;
                            $post['customer_name'] = $patient_name;
                            $post['customer_mobile'] = $patient_mobile;
                            $post['gender'] = $patient_gender;
                            $post['payment_type'] = "CASH";
                            $post['transaction_id'] = "";
                            $post['appointment_user_role'] = "ADMIN";
                            $post['appointment_type'] = "WALK-IN";
                            $post['has_token'] = "NO";
                            $post['address'] = $patient_address;
                            $post['email'] = $patient_email;
                            if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $patient_dob)) {
                                $post['customer_dob'] = $patient_dob;
                            }



                            $post['blood_group'] = $patient_blood_group;
                            $post['third_party_uhid'] = $patient_uhid;
                            $chk_result = WebservicesFunction::check_appointment_validity($post, true);
                            if ($chk_result['status'] == 1) {
                                $result = json_decode(WebservicesFunction::add_new_appointment($post, true, "THIRD_PARTY_API"), true);
                                if ($result['status'] == 1) {
                                    $notification_data = $result['notification_data'];
                                    $appointment_id = $result['data']['appointment_id'];
                                    $token_number = $result['data']['token_number'];
                                    $response['status'] = 1;
                                    $response["message"] = $result['message'];
                                } else {
                                    $response["status"] = 0;
                                    $response["message"] = $result['message'];
                                }
                            } else {
                                $response["status"] = 0;
                                $response["message"] = $chk_result['message'];
                            }
                        } else {
                            $response["status"] = 0;
                            $response["message"] = "Doctor appointment setting not configure";
                        }
                    } else {
                        $response["status"] = 0;
                        $response["message"] = "Doctor not register with this number";
                    }
                }

                unset($response["data"]);

                //Custom::send_process_to_background();
                /*
                if (!empty($notification_data)) {
                    Custom::send_book_appointment_notification($notification_data);
                } else if ($book_type == "SMS" && !empty($thin_app_id)) {
                    Custom::send_single_sms($from, $response["message"], $thin_app_id);
                }
                */

            } else {
                $response['status'] = 0;
                $response['message'] = 'Invalid Access Key';
            }
        }else {
                $response['status'] = 0;
                $response['message'] = 'Invalid Access Key';
        }
        echo json_encode($response);die;
    }


    public function bookAppointment()
    {
        $mode = MENGAGE_API_ACCESS_MODE;
        $this->autoRendar = false;
        $this->layout = false;
        $header = getallheaders();
        $key = (isset($header['key']) && !empty($header['key']))?$header['key']:"";
        if(!empty($key)) {
            $access_key = @Custom::readAccessKey($key, $mode);
            $tmp_array = @explode("XXX", $access_key);
            $thin_app_id = isset($tmp_array[1])?$tmp_array[1]:0;
            $app_data = Custom::getThinAppData($thin_app_id);
            if ($tmp_array[0] == MENGAGE_API_ACCESS_MODE && $app_data['mengage_api_accss_key'] == $key) {

                $request = file_get_contents("php://input");
                $data = json_decode($request, true);
                $app_admin_data = Custom::get_thinapp_admin_data($thin_app_id);
                $app_key = APP_KEY;
                $user_id = @$app_admin_data['id'];
                $mobile = @$app_admin_data['mobile'];
                $doctor_mobile = isset($data['doctor_mobile']) ? Custom::create_mobile_number($data['doctor_mobile']) : "";
                $patient_type = isset($data['patient_type']) ? $data['patient_type'] : "";
                $patient_name = isset($data['patient_name']) ? $data['patient_name'] : "";
                $patient_mobile = isset($data['patient_mobile']) ? Custom::create_mobile_number($data['patient_mobile']) : "";
                $patient_dob = isset($data['patient_dob']) ? $data['patient_dob'] : "";
                $patient_gender = isset($data['patient_gender']) ? trim($data['patient_gender']) : "";
                $patient_address = isset($data['patient_address']) ? trim($data['patient_address']) : "";
                $patient_uhid = isset($data['patient_uhid']) ? trim($data['patient_uhid']) : "";
                $patient_email = isset($data['patient_email']) ? trim($data['patient_email']) : "";
                $patient_blood_group = isset($data['patient_blood_group']) ? trim($data['patient_blood_group']) : "";
                if (empty($user_id)) {
                    $response['status'] = 0;
                    $response['message'] = 'Invalid user';
                } else if (empty($thin_app_id)) {
                    $response['status'] = 0;
                    $response['message'] = 'Invalid thinapp';
                } else if (empty($app_admin_data)) {
                    $response['status'] = 0;
                    $response['message'] = 'Invalid App';
                } else if (empty($app_key)) {
                    $response['status'] = 0;
                    $response['message'] = 'Invalid app';
                } else if (empty($mobile)) {
                    $response['status'] = 0;
                    $response['message'] = 'Invalid mobile';
                } else if (empty($patient_name)) {
                    $response['status'] = 0;
                    $response['message'] = 'Invalid Patient Name';
                } else if (empty($patient_mobile)) {
                    $response['status'] = 0;
                    $response['message'] = 'Invalid Patient Mobile';
                } else if (empty($doctor_mobile)) {
                    $response['status'] = 0;
                    $response['message'] = 'Invalid Doctor Mobile';
                } else if ($patient_type!="OLD" && $patient_type != "NEW" && $patient_type != "APPOINTMENT") {
                    $response['status'] = 0;
                    $response['message'] = 'Invalid Patient Type';
                } else {

                    $connection = ConnectionUtil::getConnection();
                    $booking_date = date('Y-m-d');
                    $query = "SELECT * FROM appointment_staffs WHERE thinapp_id = $thin_app_id and mobile ='$doctor_mobile' and status = 'ACTIVE' and staff_type = 'DOCTOR' limit 1";
                    $service_message_list = $connection->query($query);
                    if ($service_message_list->num_rows) {
                        $doctor = mysqli_fetch_assoc($service_message_list);
                        $doctor_id = $doctor["id"];
                        if (Custom::check_app_enable_permission($thin_app_id, 'NEW_QUICK_APPOINTMENT')) {
                            $doctor_data = Custom::ive_get_doctor_custom_data($thin_app_id, $doctor_id, true);
                        } else {
                            $doctor_data = Custom::ive_get_doctor_custom_data($thin_app_id, $doctor_id);
                        }
                        if (!empty($doctor_data)) {
                            $address_id = $doctor_data['address_id'];
                            $service_id = $customer_id =0;
                            if (Custom::check_app_enable_permission($thin_app_id, 'NEW_QUICK_APPOINTMENT')) {
                                $service_id = $doctor_data['service_id'];
                            }

                            if (!empty($patient_mobile)) {
                                $customer_id = Custom::get_customer_first_id_by_mobile($thin_app_id, $patient_mobile);
                                $customer_id = !empty($customer_id) ? $customer_id : 0;
                            }

                            $appointment_data = array();
                            if (!empty($patient_mobile) && $patient_mobile != '+919999999999') {
                                $appointment_data = Custom::get_patient_appointment_booking_data_by_number($doctor_id,$address_id, $patient_mobile);
                            }
                            if($patient_mobile == '+919999999999'){
                                $customer_id = 0;
                            }
                        	

                            if (empty($appointment_data)) {
                                $slot_time = date('h:i A');
                                $appointment_type = "WALK-IN";
                                $has_token = "NO";
                                $available_slot = $slot_time;
                                $post = array();
                                if($patient_type != 'APPOINTMENT' ){
                                    $default_data = Custom::get_doctor_open_day_data($thin_app_id,$doctor_id,$address_id,$service_id);
                                    if($default_data['status'] =='ACTIVE'){
                                        $available_slot = Custom::get_doctor_next_available_slot($thin_app_id, $doctor_id, $address_id, $booking_date, "CURRENT", false, "ADMIN",$service_id);
                                        if(!empty($available_slot)){
                                            $slot_time = $available_slot;
                                            $appointment_type = "AVAILABLE_TOKEN";
                                            $has_token = "YES";
                                        }else{
                                            $book_more_slot = Custom::load_doctor_slot_by_address(date('Y-m-d'), $doctor_id, $doctor_data['service_slot_duration'], $thin_app_id, $address_id, false, "ADMIN", true, true);
                                            $book_more_slot = end($book_more_slot);
                                            if (!empty($book_more_slot) && $book_more_slot['custom_slot'] == 'YES') {
                                                $available_slot = $slot_time = $book_more_slot['slot'];
                                                $post['queue_number'] = $book_more_slot['queue_number'];
                                                $post['custom_token'] = "YES";
                                                $appointment_type = "ADD_MORE_TOKEN";
                                                $has_token ="YES";
                                            }
                                        }
                                    }else{
                                        $queue_number =!empty($default_data['queue_number'])?$default_data['queue_number']:0;
                                        $available_slot = $slot_time = !empty($default_data['slot_time'])?date("h:i A", strtotime("+".$default_data['service_slot_duration'], strtotime($default_data['slot_time']))):date("h:i A", strtotime($default_data['time_from']));
                                        $post['queue_number'] = ++$queue_number;
                                        $post['custom_token'] = "YES";
                                        $appointment_type = "ADD_MORE_TOKEN";
                                        $has_token ="YES";
                                    }



                                }

                                if(!empty($available_slot)){

                                    $post['app_key'] = APP_KEY;
                                    $post['thin_app_id'] = $thin_app_id;
                                    $post['user_id'] = $doctor_data['user_id'];
                                    $post['role_id'] = $doctor_data['role_id'];
                                    $post['mobile'] = $doctor_data['mobile'];
                                    $post['booking_date'] = $booking_date;
                                    $post['slot_time'] = $slot_time;
                                    $post['doctor_id'] = $doctor_id;
                                    $post['user_type'] = "CUSTOMER";
                                    $post['children_id'] = 0;
                                    $post['address_id'] = $address_id;
                                    $post['service_id'] = $service_id;
                                    $post['customer_id'] = $customer_id;
                                    $post['customer_name'] = $patient_name;
                                    $post['customer_mobile'] = $patient_mobile;
                                    $post['gender'] = $patient_gender;
                                    $post['payment_type'] = "CASH";
                                    $post['transaction_id'] = "";
                                    $post['appointment_user_role'] = "ADMIN";
                                    $post['appointment_type'] = $appointment_type;
                                    $post['has_token'] = $has_token;
                                    $post['address'] = $patient_address;
                                    $post['email'] = $patient_email;
                                    if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $patient_dob)) {
                                        $post['customer_dob'] = $patient_dob;
                                    }



                                    $post['blood_group'] = $patient_blood_group;
                                    $post['third_party_uhid'] = $patient_uhid;
                                    $chk_result = WebservicesFunction::check_appointment_validity($post, true);
                                    if ($chk_result['status'] == 1) {
                                        $result = json_decode(WebservicesFunction::add_new_appointment($post, true, "THIRD_PARTY_API"), true);
                                        if ($result['status'] == 1) {
                                            $notification_data = $result['notification_data'];
                                            $appointment_id = $result['data']['appointment_id'];
                                            if(!empty($appointment_id)){
                                                $post['appointment_id'] =$appointment_id;
                                                $post['status'] ="SUCCESS";
                                                $result = json_decode( WebservicesFunction::update_appointment_payment_status($post,true),true);
                                            }

                                            $response['status'] = 1;
                                            $response['message'] = "Appointment has been booked successfully";
                                        } else {
                                            $response["status"] = 0;
                                            $response["message"] = $result['message'];
                                        }
                                    } else {
                                        $response["status"] = 0;
                                        $response["message"] = $chk_result['message'];
                                    }
                                }else{
                                    $response["status"] = 0;
                                    $response["message"] = "No more appointment token available";
                                }

                            } else {

                                $post = array();
                                $post['app_key'] = APP_KEY;
                                $post['thin_app_id'] = $thin_app_id;
                                $post['user_id'] = $doctor_data['user_id'];
                                $post['role_id'] = $doctor_data['role_id'];
                                $post['mobile'] = $doctor_data['mobile'];
                                $post['appointment_id'] =$appointment_data['id'];
                            	$booked_patient_name = $appointment_data['appointment_patient_name'];
                                $post['status'] ="SUCCESS";
                                $result = json_decode( WebservicesFunction::update_appointment_payment_status($post,true),true);
                               
                            	
                            	if($result['status']==1){
                                    $response["status"] = 1;
                                    $response['message'] = "Appointment has been booked successfully";
                                	if($booked_patient_name!=$patient_name){
                                        if (!empty($patient_mobile) && $patient_mobile !='+919999999999') {
                                            $customer_data =array();
                                            $search_patient_name = '';
                                            if(!empty($appointment_data['children_id']) && $appointment_data['children_id'] > 0){
                                                $customer_data = Custom::create_child_by_name($thin_app_id, $patient_mobile, $patient_name);
                                                $search_patient_name = @$customer_data['child_name'];
                                            }else{
                                                $customer_data = Custom::search_customer_name($thin_app_id, $patient_mobile,$patient_name);
                                                $search_patient_name = @$customer_data['child_name'];
                                            }

                                            $connection = ConnectionUtil::getConnection();
                                        	$connection->autocommit(false);
                                        	
                                           
                                            if(empty($customer_data)){
                                                $sql = "UPDATE appointment_customers as ac join appointment_customer_staff_services  as acss on ac.id = acss.appointment_customer_id SET ac.first_name =?, acss.appointment_patient_name=? where acss.id = ?";
                                                $stmt = $connection->prepare($sql);
                                                $stmt->bind_param('sss',$patient_name,$patient_name,$appointment_data['id'] );
                                                if($stmt->execute()){
                                                    $connection->commit();
                                                }
                                            }else{
                                                $sql = "UPDATE appointment_customer_staff_services set appointment_patient_name=?, appointment_customer_id =? where id = ?";
                                                $stmt = $connection->prepare($sql);
                                                $stmt->bind_param('sss',$search_patient_name,$customer_data['id'],$appointment_data['id'] );
                                                if($stmt->execute()){
                                                    $connection->commit();
                                                }
                                            }
                                        }
                                    }
                                	

                                }else{
                                    $response["status"] = 0;
                                    $response["message"] = "Doctor appointment setting not configure";
                                }
                            }

                        } else {
                            $response["status"] = 0;
                            $response["message"] = "Doctor appointment setting not configure";
                        }
                    } else {
                        $response["status"] = 0;
                        $response["message"] = "Doctor not register with this number";
                    }
                }

                unset($response["data"]);

            } else {
                $response['status'] = 0;
                $response['message'] = 'Invalid Access Key';
            }
        }else {
            $response['status'] = 0;
            $response['message'] = 'Invalid Access Key';
        }

        Custom::sendResponse($response);
        Custom::send_process_to_background();
        if (!empty($notification_data)) {
            Custom::send_book_appointment_notification($notification_data);
        }die;


    }

    public function cancelAppointment()
    {
        $mode = MENGAGE_API_ACCESS_MODE;
        $this->autoRendar = false;
        $this->layout = false;
        $header = getallheaders();
        $key = (isset($header['key']) && !empty($header['key']))?$header['key']:"";
        if(!empty($key)) {
            $access_key = @Custom::readAccessKey($key, $mode);
            $tmp_array = @explode("XXX", $access_key);
            $thin_app_id = isset($tmp_array[1])?$tmp_array[1]:0;
            $app_data = Custom::getThinAppData($thin_app_id);
            if ($tmp_array[0] == MENGAGE_API_ACCESS_MODE && $app_data['mengage_api_accss_key'] == $key) {

                $request = file_get_contents("php://input");
                $data = json_decode($request, true);
                $app_admin_data = Custom::get_thinapp_admin_data($thin_app_id);
                $app_key = APP_KEY;
                $user_id = @$app_admin_data['id'];
                $mobile = @$app_admin_data['mobile'];
                $doctor_mobile = isset($data['doctor_mobile']) ? Custom::create_mobile_number($data['doctor_mobile']) : "";
                $patient_type = isset($data['patient_type']) ? $data['patient_type'] : "";
                $patient_name = isset($data['patient_name']) ? $data['patient_name'] : "";
                $patient_mobile = isset($data['patient_mobile']) ? Custom::create_mobile_number($data['patient_mobile']) : "";
                if (empty($user_id)) {
                    $response['status'] = 0;
                    $response['message'] = 'Invalid user';
                } else if (empty($thin_app_id)) {
                    $response['status'] = 0;
                    $response['message'] = 'Invalid thinapp';
                } else if (empty($app_admin_data)) {
                    $response['status'] = 0;
                    $response['message'] = 'Invalid App';
                } else if (empty($app_key)) {
                    $response['status'] = 0;
                    $response['message'] = 'Invalid app';
                } else if (empty($mobile)) {
                    $response['status'] = 0;
                    $response['message'] = 'Invalid mobile';
                } else if (empty($patient_name)) {
                    $response['status'] = 0;
                    $response['message'] = 'Invalid Patient Name';
                } else if (empty($patient_mobile)) {
                    $response['status'] = 0;
                    $response['message'] = 'Invalid Patient Mobile';
                } else {

                    $connection = ConnectionUtil::getConnection();

                    $query = "SELECT * FROM appointment_staffs WHERE thinapp_id = $thin_app_id and mobile ='$doctor_mobile' and status = 'ACTIVE' and staff_type = 'DOCTOR' limit 1";
                    $service_message_list = $connection->query($query);
                    if ($service_message_list->num_rows) {
                        $doctor_data = mysqli_fetch_assoc($service_message_list);
                        $doctor_id = $doctor_data['id'];
                        $booking_date = date('Y-m-d');
                        $query = "SELECT acss.id FROM appointment_customer_staff_services AS acss LEFT JOIN appointment_customers AS ac ON ac.id  = acss.appointment_customer_id LEFT JOIN childrens AS c ON c.id = acss.children_id WHERE acss.thinapp_id = $thin_app_id and  acss.status IN('NEW','CONFIRM','RESCHEDULE') AND acss.appointment_staff_id = $doctor_id AND DATE(acss.appointment_datetime) = '$booking_date' ORDER BY acss.id DESC LIMIT 1";
                        $service_message_list = $connection->query($query);
                        if ($service_message_list->num_rows) {
                            $appointment_id = mysqli_fetch_assoc($service_message_list)['id'];
                            $post = array();
                            $post['app_key'] = APP_KEY;
                            $post['user_id'] = $user_id;
                            $post['mobile'] = $mobile;
                            $post['thin_app_id'] = $thin_app_id;
                            $post['appointment_id'] = $appointment_id;
                            $post['cancel_by'] = "DOCTOR";
                            $post['message'] = "";
                            $result = json_decode(WebservicesFunction::cancel_appointment($post, true, true), true);
                            if ($result['status'] == 1) {
                                $response["status"] = 1;
                                $response["message"] = "Appointment canceled successfully";
                            } else {
                                $response["status"] = 0;
                                $response["message"] = "Doctor not register with this number";
                            }
                        }else{
                            $response["status"] = 0;
                            $response["message"] = "This patient has no booked appointment";
                        }
                    } else {
                        $response["status"] = 0;
                        $response["message"] = "Doctor not register with this number";
                    }
                }
            } else {
                $response['status'] = 0;
                $response['message'] = 'Invalid Access Key';
            }
        }else {
            $response['status'] = 0;
            $response['message'] = 'Invalid Access Key';
        }
        echo json_encode($response);die;
    }

	public function dialarApi()
    {

        $this->autoRendar = false;
        $this->layout = false;
        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        $notification_data= $response=array();
        $thin_app_id = isset($data['thin_app_id']) ? $data['thin_app_id'] : "";
        $doctor_mobile = isset($data['doctor_mobile']) ? $data['doctor_mobile'] : "";
        $patient_mobile = isset($data['patient_mobile']) ? $data['patient_mobile'] : "";
        $api_name = isset($data['api_name']) ? $data['api_name'] : "";
        if ($api_name !='sendOTP' && $api_name !='bookAppointment') {
            $response['status'] = 0;
            $response['message'] = 'Invalid api name';
        }else if (empty($thin_app_id)) {
            $response['status'] = 0;
            $response['message'] = 'Invalid thinapp id';
        } else if (empty($doctor_mobile)) {
            $response['status'] = 0;
            $response['message'] = 'Invalid doctor mobile number';
        } else if (empty($patient_mobile)) {
            $response['status'] = 0;
            $response['message'] = 'Please enter 10 digit mobile number';
        }else {

            if($api_name =='sendOTP'){
                $code = rand(100000,999999);
                $option = array(
                    'username' => $patient_mobile,
                    'mobile' => $patient_mobile,
                    'verification' => $code,
                    'thinapp_id' => $thin_app_id,
                    'HASH_KEY' => ''
                );
                if (Custom::send_otp($option)) {
                    $response["status"] = 1;
                    $response["message"] = "OTP sent successfully.";
                    $response["OTP"] = $code;
                } else {
                    $response["status"] = 1;
                    $response["message"] = "Sorry, unable to send OTP.";
                }
            }else{

                $doctor_mobile = Custom::create_mobile_number($doctor_mobile);
                $patient_mobile = Custom::create_mobile_number($patient_mobile);
                $doctor_profile = Custom::get_doctor_by_mobile($doctor_mobile,$thin_app_id);
                $doctor_id = !empty($doctor_profile)?$doctor_profile['id']:0;
                if (Custom::check_app_enable_permission($thin_app_id, 'NEW_QUICK_APPOINTMENT')) {
                    $doctor_data = Custom::ive_get_doctor_custom_data($thin_app_id, $doctor_id, true);
                } else {
                    $doctor_data = Custom::ive_get_doctor_custom_data($thin_app_id, $doctor_id);
                }
                if (!empty($doctor_data)) {
                    $available_slot = false;
                    $address_id = $doctor_data['address_id'];
                    $service_id = 0;
                    $time_of_day = 'CURRENT';
                    $booking_date = date('Y-m-d');
                    $from =$patient_mobile;


                    $available_slot = Custom::get_doctor_next_available_slot($thin_app_id, $doctor_id, $address_id, $booking_date, $time_of_day, false, "USER");
                    if ($available_slot !== false) {
                        if (empty($customer_name)) {
                            $customer_id = Custom::get_customer_first_id_by_mobile($thin_app_id, $from);
                        } else {
                            $customer_id = Custom::search_customer_name($thin_app_id, $from, $customer_name);
                            $customer_id = !empty($customer_id) ? $customer_id['id'] : 0;
                        }
                        if (empty($customer_id)) {
                            $customer_mobile = $from;
                            if (empty($customer_name)) {
                                $customer_name = $from;
                            }
                        } else {
                            $customer_mobile = $customer_name = "";
                        }

                        $post = array();
                        $post['app_key'] = APP_KEY;
                        $post['thin_app_id'] = $thin_app_id;
                        $post['user_id'] = $doctor_data['user_id'];
                        $post['role_id'] = $doctor_data['role_id'];
                        $post['mobile'] = $doctor_data['mobile'];
                        $post['booking_date'] = $booking_date;
                        $post['slot_time'] = $available_slot;
                        $post['doctor_id'] = $doctor_id;
                        $post['user_type'] = "CUSTOMER";
                        $post['children_id'] = 0;
                        $post['address_id'] = $address_id;
                        $post['service_id'] = $service_id;
                        $post['customer_id'] = $customer_id;
                        $post['customer_name'] = $customer_name;
                        $post['customer_mobile'] = $customer_mobile;
                        $post['gender'] = "MALE";
                        $post['payment_type'] = "CASH";
                        $post['transaction_id'] = "";
                        $post['appointment_user_role'] = "USER";
                    	$post['checked_in'] = 'YES';
                        $chk_result = WebservicesFunction::check_appointment_validity($post, true);
                        if ($chk_result['status'] == 1) {
                            $book_type = "FACE_READER_TAB";
                            $result = json_decode(WebservicesFunction::add_new_appointment($post, true, $book_type, $time_of_day), true);
                            if ($result['status'] == 1) {
                                $notification_data = $result['notification_data'];
                                $appointment_id = $result['data']['appointment_id'];
                                $token_number = $result['data']['token_number'];
                                $response['status'] = 1;
                                $response["message"] = $result['message'];
                            } else {
                                $response["status"] = 1;
                                $response["message"] = $result['message'];
                            }
                        } else {
                            $response["status"] = 1;
                            $response["message"] = $chk_result['message'];

                        }
                    } else {
                        $response["status"] = 1;
                        $response["message"] = "sabhi token book ho chuke hai";

                    }

                } else {
                    $response["status"] = 1;
                    $response["message"] = "Doctor abhi available nahi hai";
                }

            }
        }

        Custom::sendResponse($response);
        Custom::send_process_to_background();
        if (!empty($notification_data)) {
            Custom::send_book_appointment_notification($notification_data);
        }die;

    }
	
	public function dialar()
    {

        $this->autoRendar = false;
        $this->layout = false;
        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        $notification_data= $response=array();
        $thin_app_id = isset($data['thin_app_id']) ? $data['thin_app_id'] : "";
        $doctor_code = isset($data['doctor_code']) ? $data['doctor_code'] : "";
        $patient_mobile = isset($data['patient_mobile']) ? $data['patient_mobile'] : "";
        $api_name = isset($data['api_name']) ? $data['api_name'] : "";
        if ($api_name !='sendOTP' && $api_name !='bookAppointment') {
            $response['status'] = 0;
            $response['message'] = 'Invalid api name';
        }else if (empty($thin_app_id)) {
            $response['status'] = 0;
            $response['message'] = 'Invalid thinapp id';
        } else if (empty($doctor_code)) {
            $response['status'] = 0;
            $response['message'] = 'Invalid doctor code';
        } else if (empty($patient_mobile)) {
            $response['status'] = 0;
            $response['message'] = 'Please enter 10 digit mobile number';
        }else {

            if($api_name =='sendOTP'){
                $code = rand(100000,999999);
                $option = array(
                    'username' => $patient_mobile,
                    'mobile' => $patient_mobile,
                    'verification' => $code,
                    'thinapp_id' => $thin_app_id,
                    'HASH_KEY' => ''
                );
                if (Custom::send_otp($option)) {
                    $response["status"] = 1;
                    $response["message"] = "OTP sent successfully.";
                    $response["OTP"] = $code;
                } else {
                    $response["status"] = 1;
                    $response["message"] = "Sorry, unable to send OTP.";
                }
            }else{
                $doctor_data= Custom::get_doctor_via_ivr_code($doctor_code,$thin_app_id);
                if(!empty($doctor_data)){
                    $doctor_mobile = Custom::create_mobile_number($doctor_data['mobile']);
                    $patient_mobile = Custom::create_mobile_number($patient_mobile);
                    $doctor_profile = Custom::get_doctor_by_mobile($doctor_mobile,$thin_app_id);
                    $doctor_id = !empty($doctor_profile)?$doctor_profile['id']:0;
                                
                    if (Custom::check_app_enable_permission($thin_app_id, 'NEW_QUICK_APPOINTMENT')) {
                        $doctor_data = Custom::ive_get_doctor_custom_data($thin_app_id, $doctor_id, true);
                    } else {
                        $doctor_data = Custom::ive_get_doctor_custom_data($thin_app_id, $doctor_id);
                    }
                    if (!empty($doctor_data)) {
                        $available_slot = false;
                        $address_id = $doctor_data['address_id'];
                        $service_id = 0;
                        $time_of_day = 'CURRENT';
                        $booking_date = date('Y-m-d');
                        $from =$patient_mobile;
						$post = array();
                    	
                        $appointment_user_role = 'ADMIN';
                        $available_slot = Custom::get_doctor_next_available_slot($thin_app_id, $doctor_id, $address_id, $booking_date, $time_of_day, false, $appointment_user_role,0,true);
                    
                    	if($available_slot===false){
                            $appointment_data = Custom::load_doctor_slot_by_address($booking_date, $doctor_id, $doctor_data['service_slot_duration'], $thin_app_id, $address_id, false, 'ADMIN', true, true);
                            $appointment_data = end($appointment_data);
                            if($appointment_data['custom_slot']=='YES'){
                                $available_slot = $appointment_data['slot'];
                                $post['queue_number'] = $appointment_data['queue_number'];
                                $post['custom_token'] = 'YES';
                                $appointment_user_role ='ADMIN';
                            }
                        }
                    
                        if ($available_slot !== false) {
                            /* update customer data start */
                                $customer_id = 0;
                                $search_patient =Custom::get_first_customer_by_mobile($thin_app_id,$patient_mobile);
                                if(!empty($search_patient)){
                                    $customer_id =$search_patient['id'];
                                }
                                if (empty($customer_id)) {
                                    $customer_mobile = $customer_name = $patient_mobile;
                                }
                            /* update customer data end */

                            
                            $post['app_key'] = APP_KEY;
                            $post['thin_app_id'] = $thin_app_id;
                            $post['user_id'] = $doctor_data['user_id'];
                            $post['role_id'] = $doctor_data['role_id'];
                            $post['mobile'] = $doctor_data['mobile'];
                            $post['booking_date'] = $booking_date;
                            $post['slot_time'] = $available_slot;
                            $post['doctor_id'] = $doctor_id;
                            $post['user_type'] = "CUSTOMER";
                            $post['children_id'] = 0;
                            $post['address_id'] = $address_id;
                            $post['service_id'] = $service_id;
                            $post['customer_id'] = $customer_id;
                            $post['customer_name'] = $customer_name;
                            $post['customer_mobile'] = $customer_mobile;
                            $post['gender'] = "MALE";
                            $post['payment_type'] = "CASH";
                            $post['transaction_id'] = "";
                            $post['appointment_user_role'] = $appointment_user_role;
                            $post['checked_in'] = 'YES';
                        	
                        
                            $chk_result = WebservicesFunction::check_appointment_validity($post, true);
                            if ($chk_result['status'] == 1) {
                                $book_type = "DIALER";
                                $result = json_decode(WebservicesFunction::add_new_appointment($post, true, $book_type, $time_of_day), true);
                                if ($result['status'] == 1) {
                                	$appointment_id = $result['data']['appointment_id'];
                                	if($thin_app_id==CK_BIRLA_APP_ID){
                                    	$res = Custom::create_counter_log($thin_app_id, 'APPOINTMENT_BOOKED',$appointment_id);
                                        $res = Custom::assign_counter_to_next_token($thin_app_id,$doctor_id);
                                    }
                                
                                    $notification_data = $result['notification_data'];
                                    
                                    $token_number = $result['data']['token_number'];
                                    $response['status'] = 1;
                                    $response["message"] = $result['message'];
                                	$tmp["patient_mobile"] = $patient_mobile;
                                    $tmp["token_number"] =$token_number;
                                    $tmp["token_time"] =$token_time= $result['data']['time'];
                                    $tmp["doctor_name"] =$doctor_name= $doctor_data['doctor_name'];
                                    $tmp["date"] = $date= date('d/M/Y',strtotime($booking_date));
                                    $string = "   Booking Confirmed\n\n   Doctor : $doctor_name\n\n\n\n   Mobile : $patient_mobile\n   Token  : $token_number\n   Date   : $date";
                                    $response['print_string'] = $string;
                                    $response['data'] = $tmp;
                                
                                
                                } else {
                                    $response["status"] = 0;
                                    $response["message"] = $result['message'];
                                }
                            } else {
                                $response["status"] = 0;
                                $response["message"] = $chk_result['message'];

                            }
                        } else {
                            $response["status"] = 0;
                            $response["message"] = "sabhi token book ho chuke hai";

                        }

                    } else {
                        $response["status"] = 0;
                        $response["message"] = "Doctor abhi available nahi hai";
                    }
                }else{
                    $response["status"] = 0;
                    $response["message"] = "Doctor is not register with this code";
                }

            }
        }

        Custom::sendResponse($response);
        Custom::send_process_to_background();
        if (!empty($notification_data)) {
            Custom::send_book_appointment_notification($notification_data);
        }die;

    }

	
	public function mq_form_booking()
    {

        $this->autoRendar = false;
        $this->layout = false;
        //$request = file_get_contents("php://input");
        $data = $_POST;
        $notification_data= $response=array();
        $thin_app_id = isset($data['thin_app_id']) ? base64_decode($data['thin_app_id']) : "";
        $doctor_mobile = isset($data['doctor_mobile']) ? base64_decode($data['doctor_mobile']) : "";
        $patient_mobile = isset($data['patient_mobile']) ? $data['patient_mobile'] : "";
        $patient_name = isset($data['patient_name']) ? $data['patient_name'] : "";
        $slot_status = isset($data['status']) ? $data['status'] : "";
        $available_slot = isset($data['slot']) ? $data['slot'] : "";
        $token = isset($data['token']) ? $data['token'] : "";
        $appointment_user_role = isset($data['role']) ? $data['role'] : "ADMIN";
    	$remark = isset($data['remark']) ? $data['remark'] : "";
        if (empty($thin_app_id)) {
            $response['status'] = 0;
            $response['message'] = 'Invalid thinapp id';
        } else if (empty($doctor_mobile)) {
            $response['status'] = 0;
            $response['message'] = 'Invalid doctor mobile';
        } else if (empty($patient_mobile)) {
            $response['status'] = 0;
            $response['message'] = 'Please enter 10 digit mobile number';
        }else {
            $doctor_mobile = Custom::create_mobile_number($doctor_mobile);
            $patient_mobile = Custom::create_mobile_number($patient_mobile);
            $doctor_profile = Custom::get_doctor_by_mobile($doctor_mobile,$thin_app_id);
            $doctor_id = !empty($doctor_profile)?$doctor_profile['id']:0;
            if (Custom::check_app_enable_permission($thin_app_id, 'NEW_QUICK_APPOINTMENT')) {
                $doctor_data = Custom::ive_get_doctor_custom_data($thin_app_id, $doctor_id, true);
            } else {
                $doctor_data = Custom::ive_get_doctor_custom_data($thin_app_id, $doctor_id);
            }
            if (!empty($doctor_data)) {

                $address_id = $doctor_data['address_id'];
                $service_id = $customer_id=0;
                $time_of_day = 'CURRENT';
                $booking_date = date('Y-m-d');
                $from =$patient_mobile;
                $post = array();


                if(empty($available_slot)){
                    $available_slot = Custom::get_doctor_next_available_slot($thin_app_id, $doctor_id, $address_id, $booking_date, $time_of_day, false, $appointment_user_role,0,true);
                }

                if(empty($available_slot)){
                    $appointment_data = Custom::load_doctor_slot_by_address($booking_date, $doctor_id, $doctor_data['service_slot_duration'], $thin_app_id, $address_id, false, 'ADMIN', true, true);
                    $appointment_data = end($appointment_data);
                    if($appointment_data['custom_slot']=='YES'){
                        $available_slot = $appointment_data['slot'];
                        $post['queue_number'] = $appointment_data['queue_number'];
                        $post['custom_token'] = 'YES';
                        $appointment_user_role ='ADMIN';
                    }
                }

                if(!empty($available_slot)){

                    if($patient_mobile!='+919999999999'){
                        if(empty($patient_name)){
                            $search_patient =Custom::get_first_customer_by_mobile($thin_app_id,$patient_mobile);
                            if(!empty($search_patient)){
                                $customer_id =$search_patient['id'];
                                $customer_name =$search_patient['first_name'];
                            }
                        }else{
                            $customer_name = $patient_name;
                            $search_patient =Custom::get_customer_by_name($thin_app_id,$patient_name,$patient_mobile);
                            if(empty($search_patient)){
                                $search_patient =Custom::get_first_customer_by_mobile($thin_app_id,$patient_mobile);
                            }
                            if(!empty($search_patient)){
                                $connection = ConnectionUtil::getConnection();
                                $connection->autocommit(false);
                                $folder_id =$search_patient['folder_id'];
                                $customer_id =$search_patient['id'];

                                $query = "update appointment_customers set first_name =?, modified =? where id = ?";
                                $stmt_patient = $connection->prepare($query);
                                $stmt_patient->bind_param('sss', $patient_name, $created, $customer_id);
                                $query = "update drive_folders set folder_name=?, modified =? where id = ?";
                                $stmt_folder = $connection->prepare($query);
                                $stmt_folder->bind_param('sss', $patient_name, $created, $folder_id);
                                if ($stmt_patient->execute() && $stmt_folder->execute()) {
                                    $connection->commit();
                                }
                            }
                        }
                    }else{
                        $customer_name = $patient_name;
                        $customer_mobile = $patient_mobile;
                    }

                    if (empty($customer_name)) {
                        $customer_mobile = $patient_mobile;
                        if (empty($patient_name)) {
                            $customer_name = $customer_mobile;
                        }
                    }

                    $post['app_key'] = APP_KEY;
                    $post['thin_app_id'] = $thin_app_id;
                    $post['user_id'] = $doctor_data['user_id'];
                    $post['role_id'] = $doctor_data['role_id'];
                    $post['mobile'] = $doctor_data['mobile'];
                    $post['booking_date'] = $booking_date;
                    $post['slot_time'] = $available_slot;
                    $post['doctor_id'] = $doctor_id;
                    $post['user_type'] = "CUSTOMER";
                    $post['children_id'] = 0;
                    $post['address_id'] = $address_id;
                    $post['service_id'] = $service_id;
                    $post['customer_id'] = $customer_id;
                    $post['customer_name'] = $customer_name;
                    $post['customer_mobile'] = $patient_mobile;
                    $post['gender'] = "MALE";
                    $post['payment_type'] = "CASH";
                    $post['transaction_id'] = "";
                    $post['appointment_user_role'] = $appointment_user_role;
                    $post['checked_in'] = 'YES';
                	$post['remark'] = $remark;
                    if($slot_status=='BOOKED'){
                        $post['sub_token'] = 'YES';
                        $post['appointment_type'] = 'SUB_TOKEN';
                    }
                    if(!empty($token)){
                        $post['queue_number'] = $token;
                    }
                    $book_type = "MQ_FORM";
                    $result = json_decode(WebservicesFunction::add_new_appointment($post, true, $book_type, $time_of_day), true);
                    if ($result['status'] == 1) {
                        $notification_data = $result['notification_data'];
                        $appointment_id = $result['data']['appointment_id'];
                        if($thin_app_id==CK_BIRLA_APP_ID){
                            $res = Custom::create_counter_log($thin_app_id, 'APPOINTMENT_BOOKED',$appointment_id);
                            $res = Custom::assign_counter_to_next_token($thin_app_id,$doctor_id);
                        }
                        $token_number = $result['data']['token_number'];
                        $response['status'] = 1;
                        $message = str_replace("Appointment","",$result['message']);
                        $response["message"] = trim($message);
                    } else {
                        $response["status"] = 0;
                        $response["message"] = $result['message'];
                    }
                } else {
                    $response["status"] = 0;
                    $doctor_data = Custom::get_doctor_info($doctor_id);
                    $booking_date = date('d/m/Y',strtotime($booking_date));
                    $message = "Tokens are not available more";
                    $list = Custom::get_doctor_upcoming_blocked_dates($doctor_id,$doctor_data['address_id'],$doctor_data['service_id']);
                    if(!empty($list)){
                        $list = array_column($list,'blocked_date');
                        if(in_array($booking_date,$list)){
                            $message = "Doctor not available";
                        }
                    }
                    $response["message"] = $message;
                }
            } else {
                $response["status"] = 0;
                $response["message"] = "Doctor is not available";
            }
        }

        Custom::sendResponse($response);
        Custom::send_process_to_background();
        if (!empty($notification_data)) {
            Custom::send_book_appointment_notification($notification_data);
        }die;

    }
	
	

	
    
    /* PINE API END */

}
