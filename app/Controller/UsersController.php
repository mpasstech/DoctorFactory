<?php


App::uses('AppController', 'Controller');


class UsersController extends AppController
{

    public $uses = array('User','Channel', 'ChannelResponse', 'Subscriber', 'Message', 'MessageQueue', 'Location', 'CreditHistory', 'OrganizationType', 'Leads');

    public $helper = array('Custom','AppAdmin','Session');
    public $components = array('Custom','Session');

    public function beforeFilter()
    {
        parent::beforeFilter();
        //$this->layout = 'home';
    $this->layout = 'app_admin_home';
        $this->Auth->allow('login','admin_login', 'signin','get_org', 'signup','org_login', 'register_organization', 'admin_login', 'verify', 'resend_code', 'forgot_pasword', 'send_password', 'getpassword', 'send_otp', 'reset_password', 'credithistory','submit_register_organization');
    }


    public function admin_login()
    {
        $this->autoRender = false;
        $this->redirect('/admin');

    }

    public function signin()
    {

        $this->autoRender = false;
        $response = array('status' => 0);
        ////echo "<pre>";
        //print_r($this->request->data['User']);exit;
        if (!empty($this->data)) {
            //pr($this->data); die;
            //$username = trim($this->request->data['User']['username']);
            //echo $this->request->data['User']['mobile_number'];exit;

            $mobile = trim($this->request->data['User']['mobile_number']);
            $password = trim($this->request->data['User']['password_1']);
            if ($mobile != '' || $password != '') {


                $password = md5(trim($this->request->data['User']['password_1']));
                $user = $this->User->find('first', array('conditions' => array('User.mobile' => $mobile)));
                //pr($user); die;
                if (!empty($user) && isset($user)) {

                    if (!empty($user['User']['password']) && isset($user['User']['password'])) {

                        if ($user['User']['password'] == $password) {

                            if ($user['User']['is_verified'] == "N") {
                                $response['status'] = 0;
                                $response['message'] = 'Account not verified';
                                $verify_url = SITE_URL . "users/verify/" . $user['User']['mob_vf_code'];
                                $message = "Your Account is not verified. <br>Click <a href=" . $verify_url . ">here</a> for verify your Account";
                                $this->Session->write('message', $message);
                                $response['popup_msg'] = 'Y';
                                $response['url_redirect'] = SITE_URL . "homes/popup_flash_message/error/N/home";
                            } else if ($user['User']['status'] == "N") {
                                $response['status'] = 0;
                                $response['popup_msg'] = 'N';
                                $response['message'] = 'Your account is not activated';
                                $message = "Account not activated";
                                $response['url_redirect'] = SITE_URL . "homes/popup_flash_message/error/N/home";
                            } else {
                                if ($this->Auth->login($user['User'])) {
                                    if (!empty($this->request->data['remember'])) {
                                        $this->Cookie->write('remember_me_cookie', $user['User'], true, '2 weeks');
                                    }
                                    $response['status'] = 1;
                                    $response['message'] = 'Login sucessfully';
                                }
                            }
                        } else {
                            $response['status'] = 0;
                            $response['message'] = 'Either the mobile or password you entered is incorrect.';
                        }
                    } else {
                        $response['status'] = 0;
                        $response['popup_msg'] = 'Y';
                        $get_password_url = SITE_URL . "users/getpassword/" . base64_encode($user['User']['id']);
                        $message = "You are registered with mBroadcast app. for access web dashboard you have to enter your password. <br> Click <a href=" . $get_password_url . ">here</a> to get your password.";
                        $this->Session->write('message', $message);
                        $response['url_redirect'] = SITE_URL . "homes/popup_flash_message/error/N/home";
                    }
                } else {
                    $response['status'] = 0;
                    $response['message'] = 'Either the mobile or password you entered is incorrect.';
                }
            } else {
                $response['status'] = 0;
                $response['message'] = 'Either the mobile or password field is blank.';
            }
        }
        echo json_encode($response);
    }

    public function send_otp()
    {

        $this->autoRender = false;
        if ($this->request->data) {
            $response = array();

            $mobile = trim($this->request->data['mobile']);

            if ($mobile != '') {
                $userdata = $this->User->find('first', array('conditions' => array('User.mobile' => $mobile),'contain'=>false));
                if (!empty($userdata)) {
                    //$username="mBroadcast";
                    $verification_code = $this->Custom->getRandomString(4);
                    $mob_vf_code = $this->Custom->getRandomAppKey(4);
                    //$verification_code = '2222';
                    //$this->data['User']['id'] = $userdata['User']['id'];
                    //$this->data['User']['verification_code'] = $verification_code;
                    //$this->data['User']['mob_vf_code'] = $mob_vf_code;

                    $this->User->set('id', $userdata['User']['id']);
                    $this->User->set('verification_code', $verification_code);
                    $this->User->set('is_verified', 'N');
                    $this->User->set('mob_vf_code', $mob_vf_code);

                    if ($this->User->save()) {
                        $username = "user";

                        $option =array(
                            'username'=>$userdata['User']['username'],
                            'mobile'=>$mobile,
                            'verification'=>$userdata['User']['verification_code'],
                            'thinapp_id'=>0
                        );
                        $this->Custom->send_otp($option);

                        //$sms_rsppnse = json_decode($sms_resp, true);
                        $response['status'] = 1;
                        $response['message'] = "OTP sent Successfully";
                    } else {
                        $response['status'] = 0;
                        $response['message'] = "Something went wrong";
                    }
                } else {
                    $response['status'] = 0;
                    $response['message'] = "User does not Exists";
                }
            } else {
                $response['status'] = 0;
                $response['message'] = 'Please Enter Mobile number.';
            }
        } else {

            $response['status'] = 0;
            $response['message'] = 'Request Not Found.';
        }
        echo json_encode($response);
        exit;
    }

    public function logout()
    {

        $this->Session->destroy();
        $this->Cookie->delete('Auth.User');
        $this->Cookie->delete('remember_me_cookie');
        $this->redirect(array('controller' => 'homes'));
    }

    public function signup()
    {

        $this->autoRender = false;
        //$request = file_get_contents("php://input");
        //$data = json_decode($request, true);
        if ($this->request->data) {
            $response = array();
            $username = $this->request->data['username'];
            $mobile = $this->request->data['mobile'];
            $password = $this->request->data['password'];

            if ($mobile != '' && $password != '' && $username != '') {

                $userdata = $this->User->find("first", array("conditions" => array("User.mobile" => $mobile)));
                if (empty($userdata)) {
                    //$username="mBroadcast";
                    $verification_code = $this->Custom->getRandomString(4);
                    $mob_vf_code = $this->Custom->getRandomAppKey(4);
                    //$verification_code = '2222';
                    $this->User->create();
                    $this->User->set('role_id', 1);
                    $this->User->set('username', $username);
                    $this->User->set('password', md5($password));
                    $this->User->set('mobile', $mobile);

                    $this->User->set('image', SITE_URL . "img/profile/profile_default.png");
                    $this->User->set('verification_code', $verification_code);
                    $this->User->set('device_type', "WEB");
                    $this->User->set('mob_vf_code', $mob_vf_code);
                    $this->User->set('credit', SIGNUP_FREE_CREDIT);
                    $this->User->set('app_key', $this->Custom->getRandomAppKey(8));
                    if ($this->User->save()) {

                        $this->Subscriber->create();
                        $this->Subscriber->set('channel_id', 1);
                        $this->Subscriber->set('user_id', 1);
                        $this->Subscriber->set('app_user_id', $this->User->getLastInsertId());
                        $this->Subscriber->set('name', $username);
                        $this->Subscriber->set('mobile', $mobile);
                        $this->Subscriber->set('status', 'SUBSCRIBED');
                        $this->Subscriber->save();

                        $last_inser_id = $this->User->getLastInsertId();
                        $response['status'] = 1;
                        $response['message'] = "Signup successfully.";
                        $response['data']['is_exist'] = 0;
                        $response['data']['user_id'] = $this->User->getLastInsertId();
                        $response['data']['username'] = $username;
                        $response['data']['mobile'] = $mobile;

                        $option =array(
                            'username'=>$username,
                            'mobile'=>$mobile,
                            'verification'=>$verification_code,
                            'thinapp_id'=>0
                        );
                        $this->Custom->send_otp($option);

                        $this->MessageQueue->create();
                        $this->MessageQueue->set('message_id', 1);
                        $this->MessageQueue->set('subscriber_id', $this->Subscriber->getLastInsertId());
                        $this->MessageQueue->set('channel_id', 1);
                        $this->MessageQueue->set('status', 'SENT');
                        $this->MessageQueue->save();
                    } else {
                        $response['status'] = 0;
                        $response['message'] = "Some error Occurred.";
                    }
                } else {
                    $is_active = $this->User->find("first", array("conditions" => array("User.mobile" => $mobile, "User.is_verified" => 'Y')));

                    if (!empty($is_active)) {
                        $response['status'] = 0;
                        $response['message'] = "User already exists and verified.";
                    } else {
                        $is_active = $this->User->find("first", array("conditions" => array("User.mobile" => $mobile, "User.is_verified" => 'N')));

                        if (!empty($is_active)) {
                            $verification_code = $this->Custom->getRandomString(4);
                            $this->User->set('id', $is_active['User']['id']);
                            $this->User->set('username', $username);
                            $username = $is_active['User']['username'];

                            $response['status'] = 1;
                            $response['message'] = "User exists but not verified. OTP sent.";
                            $response['data']['user_id'] = $is_active['User']['id'];
                            $response['data']['username'] = $username;
                            $response['data']['mobile'] = $mobile;

                            $option =array(
                                'username'=>$username,
                                'mobile'=>$mobile,
                                'verification'=>$verification_code,
                                'thinapp_id'=>0
                            );
                            $this->Custom->send_otp($option);

                        }
                    }
                }
            } else {
                $response['status'] = 0;
                $response['message'] = "Parameters Missing.";
            }
        } else {
            $response['status'] = 0;
            $response['message'] = "Request not found.";
        }
        echo json_encode($response);
        exit;
    }

    public function login() {

        if ($this->Auth->loggedIn()) {
            $this->redirect(array('controller' => 'homes', 'action' => 'index'));
        }
        $current_date = date('Y-m-d H:i:s');
        // echo'<pre>'; print_r($current_date);  exit();
        if (!empty($this->data)) {

            $this->User->set($this->request->data['User']);

            if ($this->User->validates()) {

                $userdata = $this->User->find("first", array("conditions" => array("User.mobile" => $this->request->data['User']['mobile'])));
                if (isset($userdata) && !empty($userdata)) {
                    if ($userdata['User']['is_verified'] == 'N') {
                        $verify_url = SITE_URL . "users/verify/" . $userdata['User']['mob_vf_code'];
                        $this->Session->setFlash(__('This Mobile number is already registered but not Verified. Click <a href=' . $verify_url . '>here</a> for Verify Mobile number.'), 'default', array(), 'front_error');
                        $this->redirect(array('controller' => 'users', 'action' => 'login'));
                    } else {
                        //$verify_url = SITE_URL."users/new_password/".$this->request->data['User']['mobile'];
                        $this->Session->setFlash(__('This Mobile number is already registered.'), 'default', array(), 'front_error');
                        $this->redirect(array('controller' => 'users', 'action' => 'login'));
                    }
                }
                $verification_code = $this->Custom->getRandomString(4);
                $mobilenumber = $this->data['User']['mobile'];
                $mob_vf_code = $this->Custom->getRandomAppKey(4);
                $this->request->data['User']['password'] = md5($this->request->data['User']['password']);
                $this->request->data['User']['is_verified'] = 'N';
                $this->request->data['User']['role_id'] = '1';
                $this->request->data['User']['device_type'] = 'WEB';
                $this->request->data['User']['verification_code'] = $verification_code;
                $this->request->data['User']['mob_vf_code'] = $mob_vf_code;
                $this->request->data['User']['credit'] = SIGNUP_FREE_CREDIT;
                $this->request->data['User']['app_key'] = $this->Custom->getRandomAppKey(8);
                $this->request->data['User']['mobile'] = $this->data['User']['mobile'];
                $this->request->data['User']['expiredate'] = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s", strtotime($current_date)) . " + 365 day"));
                //echo'<pre>'; print_r($this->data);  exit();
                if ($this->User->save($this->data, false)) {

                    $last_insert_id = $this->User->getLastInsertId();
                    $username = $this->data['User']['username'];
                    $mobile = $mobilenumber;

                    /* add default channel code start */
                    //$this->Channel->create();
                    //$this->Channel->set('user_id', $last_insert_id);
                    //$this->Channel->set('channel_name', $username);
                    //$this->Channel->set('is_default', 'Y');
                    //$this->Channel->save();
                    /* add default channel code end */
                    $option =array(
                        'username'=>$username,
                        'mobile'=>$mobile,
                        'verification'=>$verification_code,
                        'thinapp_id'=>0
                    );
                    $this->Custom->send_otp($option);

                    /* For make subscriber default channel */
                    $this->Subscriber->create();
                    $this->Subscriber->set('channel_id', 1);
                    $this->Subscriber->set('user_id', 1);
                    $this->Subscriber->set('name', $username);
                    $this->Subscriber->set('mobile', $mobile);
                    $this->Subscriber->set('status', 'SUBSCRIBED');
                    $this->Subscriber->set('app_user_id', $last_insert_id);

                    $this->Subscriber->save();
                    $message = "Welcome to the mBroadcast";
                    $sms_resp_s = $this->Custom->send_message($mobile, str_replace(' ', '%20', $message));
                    //echo "<pre>";print_r($sms_resp);exit;
                    $sms_rsppnse_s = json_decode($sms_resp_s, true);
                    if ($sms_rsppnse_s['response']['status'] == 'success') {
                        /* save message history code */
                        $this->MessageQueue->create();
                        $this->MessageQueue->set('message_id', 1);
                        $this->MessageQueue->set('subscriber_id', $this->Subscriber->getLastInsertId());
                        $this->MessageQueue->set('channel_id', 1);
                        $this->MessageQueue->set('status', 'SENT');
                        $this->MessageQueue->save();
                    } else {
                        /* save message history code */
                        $this->MessageQueue->create();
                        $this->MessageQueue->set('message_id', 1);
                        $this->MessageQueue->set('subscriber_id', $this->Subscriber->getLastInsertId());
                        $this->MessageQueue->set('channel_id', 1);
                        $this->MessageQueue->set('status', 'FAILED');
                        $this->MessageQueue->set('reason', $sms_rsppnse_s['response']['details']);
                        $this->MessageQueue->save();
                    }
                    $this->Session->setFlash(__('Please check SMS in your mobile for verification code.'), 'default', array(), 'front_success');
                    $this->redirect(array('controller' => 'users', 'action' => 'verify', '?' => array('m_c' => $mob_vf_code)));
                }
            }
        }
        $locations = $this->Location->find('all', array('conditions' => array('Location.status' => 'Y'), 'fields' => array('Location.id', 'Location.slug', 'Location.name')));
        $this->set('locations', $locations);
    }



    public function resend_code($vf_code = null)
    {
        $this->autoRender = false;
        if (isset($vf_code) && !empty($vf_code)) {
            $response = array();
            $userdata = $this->User->find("first", array("conditions" => array("User.mob_vf_code" => $vf_code)));
            if (!empty($userdata)) {

                $option =array(
                    'username'=>$userdata['User']['username'],
                    'mobile'=> $userdata['User']['mobile'],
                    'verification'=>$userdata['User']['verification_code'],
                    'thinapp_id'=>0
                );
                $sms_resp = $this->Custom->send_otp($option);
                $sms_rsppnse = json_decode($sms_resp, true);
                //echo "<pre>"; print_r($sms_rsppnse); die;
                if ($sms_rsppnse['response']['status'] == 'success') {
                    $this->Session->setFlash(__('Verification code resend successfully'), 'default', array(), 'front_success');
                    $this->redirect(array('controller' => 'users', 'action' => 'verify', $vf_code));
                } else {
                    $this->Session->setFlash(__('Something went wrong. Please Try again later'), 'default', array(), 'front_error');
                    $this->redirect(array('controller' => 'users', 'action' => 'verify', $vf_code));
                }
            } else {
                $this->Session->setFlash(__('Mobile number not registered.'), 'default', array(), 'front_error');
                $this->redirect(array('controller' => 'users', 'action' => 'verify', $vf_code));
            }
        } else {
            $this->Session->setFlash(__('Mobile Number not found.'), 'default', array(), 'front_error');
            $this->redirect(array('controller' => 'users', 'action' => 'verify'));
        }
    }


    public function change_password()
    {
        $this->layout = 'home';
        if (!empty($this->request->data)) {

            /*             * * TO DO ** */
            $id = $this->Auth->User('id');
            $UserInfo = $this->User->find('first', array('conditions' => array('User.id' => $id), 'fields' => array('id', 'password')));
            //pr($this->request->data);die;
            $currentPWD = md5($this->request->data['User']['currentpassword']);
            $password = $this->request->data['User']['password'];
            $confirm = $this->request->data['User']['confirm_passwd'];
            if ($password == '') {
                // $this->User->validationErrors['password']  = "Please Enter New Password";
                $this->Session->setFlash(__('Please Enter New Password.'), 'default', array(), 'front_error');
                $this->Session->setFlash(__('Please Enter Confirm Password.'), 'default', array(), 'front_error');
                //$this->User->validationErrors['confirm_passwd']  = "Please Enter Confirm Password";
                $this->redirect(array('controller' => 'users', 'action' => 'settings'));
            }
            if ($UserInfo['User']['password'] != $currentPWD) {
                //$this->User->validationErrors['currentpassword']  = "Current password does not match";
                $this->Session->setFlash(__('Old password does not match'), 'default', array(), 'front_error');
                $this->redirect(array('controller' => 'users', 'action' => 'settings'));
            }
            if ($password != $confirm) {
                // $this->User->validationErrors['confirm_passwd']  = "Password And confirm password does not match";
                $this->Session->setFlash(__('Password And confirm password does not match'), 'default', array(), 'front_error');
                $this->redirect(array('controller' => 'users', 'action' => 'settings'));
            }
            if (empty($this->User->validationErrors)) {
                $this->User->id = $id;
                /*                 * * TO DO ** */
                $this->User->saveField('password', md5($password));
                $this->Session->setFlash(__('Password changed successfully.'), 'default', array(), 'front_success');
                $this->redirect(array('controller' => 'users', 'action' => 'settings'));
                //echo "<script>setTimeout(function(){parent.$.fancybox.close(); parent.location.reload();},800);</script>";
            }
        }
    }

    public function update_mobile()
    {
        $this->layout = false;
        if (!empty($this->request->data)) {
            $response = array();
            /*             * * TO DO ** */
            $id = $this->Auth->User('id');
            $UserInfo = $this->User->find('first', array('conditions' => array('User.id' => $id), 'fields' => array('id', 'mobile', 'username')));
            $already_used = $this->User->find('first', array('conditions' => array('User.mobile' => $this->request->data['User']['new_mobile']), 'fields' => array('id', 'mobile')));
            $new_mobile = $this->request->data['User']['new_mobile'];
            if ($new_mobile == '') {
                $response['status'] = 0;
                $response['message'] = "Please Enter New Mobile Number";
            } elseif ($UserInfo['User']['mobile'] == $new_mobile) {

                $response['status'] = 0;
                $response['message'] = "This is same as previous number.";
            } elseif (!empty($already_used)) {

                $response['status'] = 0;
                $response['message'] = "This Mobile number already used.";
            } elseif (!preg_match('/^(NA|[0-9+-]+)$/', $new_mobile) || (strlen($new_mobile) < 11)) {
                $response['status'] = 0;
                $response['message'] = "Invalid mobile number.";
            } else {
                $verification_code = $this->Custom->getRandomString(4);
                $this->request->data['User']['id'] = $id;
                $this->request->data['User']['mobile'] = $new_mobile;
                $this->request->data['User']['is_verified'] = 'N';
                $this->request->data['User']['verification_code'] = $verification_code;
                //pr($this->request->data); die;
                if ($this->User->save($this->data, false)) {

                    $last_insert_id = $this->User->getLastInsertId();
                    $username = $UserInfo['User']['username'];
                    $sms_resp = $this->Custom->send_sms($username, $new_mobile, $verification_code);
                    //echo $sms_resp; die;
                    $sms_rsppnse = json_decode($sms_resp, true);
                    if ($sms_rsppnse['response']['status'] == 'success') {

                        $response['status'] = 1;
                        $response['message'] = "Mobile Number updated sucessfully. Verification code sent on your new mobile number. please check and verify <br><br>If you not received verification code Then Click <a href='javascript:void(0)' id='resend_v_code'>Here</a> to Resend code</p>";
                    } else {
                        $response['status'] = 0;
                        $response['message'] = $sms_rsppnse['response']['details'];
                    }
                }
            }
            echo json_encode($response);
            die;
        }
        $userdata = $this->User->find('first', array('conditions' => array('User.id' => $this->Auth->User('id')), 'fields' => array('id', 'username', 'mobile', 'is_verified')));
        $this->set('userdata', $userdata);
    }

    public function update_image()
    {
        $this->layout = 'home';
        $user_id = $this->Auth->User('id');
        if (!empty($this->data)) {
            $this->User->set($this->request->data['User']);
            if ($this->User->validates()) {
                $verification_code = $this->Custom->getRandomString(4);
                if ($this->User->save($this->data, false)) {

                    $this->Session->setFlash(__('Profile updated successfully.'), 'default', array(), 'front_success');
                    $this->redirect(array('controller' => 'users', 'action' => 'settings'));
                }
            }
        }
        $locations = $this->Location->find('list', array('conditions' => array('Location.status' => 'Y'), 'fields' => array('slug', 'name')));
        $this->set('locations', $locations);
        $user_data = $this->User->findById($this->Auth->User('id'));
        $this->set('userdata', $user_data);
        $this->request->data = $user_data;
    }

    public function resend_verification_code()
    {
        $this->autoRender = false;
        $response = array();
        $id = $this->Auth->User('id');
        $userdata = $this->User->find("first", array("conditions" => array("User.id" => $id)));
        if (!empty($userdata)) {
            $sms_resp = $this->Custom->send_sms($userdata['User']['username'], $userdata['User']['mobile'], $userdata['User']['verification_code']);
            $sms_rsppnse = json_decode($sms_resp, true);
            if ($sms_rsppnse['response']['status'] == 'success') {
                $response['status'] = 1;
                $response['message'] = "Verification code resend successfully";
            } else {
                $response['status'] = 0;
                $response['message'] = "Something went wrong. Please Try again later";
            }
        } else {
            $response['status'] = 0;
            $response['message'] = "Mobile number not registered";
        }
        echo json_encode($response);
        die;
    }

    public function verify_mobile()
    {

        $response = array();
        if ($this->request->is("post")) {

            $id = $this->Auth->User('id');
            $code = $this->data['v_code'];
            if ($code != '') {
                $userdata = $this->User->find("first", array("conditions" => array("User.id" => $id, "User.verification_code" => $code)));
                if (!empty($userdata)) {
                    $this->User->id = $userdata['User']['id'];
                    if ($this->User->saveField('is_verified', 'Y')) {
                        $response['status'] = 1;
                        $response['message'] = "Verification successfully";
                        $this->Session->setFlash(__('Verification successfully.'), 'default', array(), 'front_success');
                    } else {
                        $response['status'] = 0;
                        $response['message'] = "Falied";
                    }
                } else {
                    $response['status'] = 0;
                    $response['message'] = "Wrong Verification code";
                }
            } else {
                $response['status'] = 0;
                $response['message'] = 'Please Enter Verification code.';
            }
        }
        echo json_encode($response);
        die;
    }

    public function dashboard($channel_id = null)
    {

        $this->layout = 'home';
        $user_id = $this->Auth->User('id');
        $conditions = array("Channel.user_id" => $user_id);

        /*------------ishwar------------*/

        $this->loadModel('Thinapp');

        $PageShow = ($this->Thinapp->hasAny(array('Thinapp.user_id' => $user_id))) ? 1 : 0;

        /*------------ishwar------------*/


        $this->Channel->unbindModel(array('hasMany' => array('Subscriber')));
        $this->Channel->unbindModel(array('hasMany' => array('Message')));
        $this->Channel->unbindModel(array('hasMany' => array('MessageQueue')));
        $this->Channel->bindModel(array('hasMany' => array('ChannelResponse')));
        $chdata = $this->Channel->find('all', array('conditions' => $conditions/* ,'fields' => array('user_id', 'id', 'channel_name', 'image', 'channel_desc', 'created') */, 'order' => 'Channel.created desc'));
        // echo "<pre>";print_r($chdata);exit;
        $this->set('channels', $chdata);
        //echo $channel_id;exit;
        if (isset($channel_id) && !empty($channel_id)) {
            $messges = $this->Message->find('all', array('conditions' => array('Message.channel_id' => $channel_id, 'Message.user_id' => $user_id), 'order' => 'Message.created desc'));
            $this->set('messges', $messges);
            $conditions = array("Subscriber.channel_id" => $channel_id);
            $subscribers = $this->Subscriber->find('count', array('conditions' => $conditions, 'fields' => array('name', 'mobile', 'created'), 'order' => 'Subscriber.created desc'));

            $this->set('subscribers_count', $subscribers);
            $conditions = array("ChannelResponse.channel_id" => $channel_id);
            $responses = $this->ChannelResponse->find('count', array('conditions' => $conditions));
            $this->set('responses_count', $responses);
        } else {
            if (!empty($chdata) && isset($chdata)) {
                $channel_id = $chdata[0]['Channel']['id'];
            }
            ini_set("memory_limit", "-1");
            $messges = $this->Message->find('all', array('conditions' => array('Message.channel_id' => $channel_id, 'Message.user_id' => $user_id), 'order' => 'Message.created desc'));
            $this->set('messges', $messges);
            $conditions = array("Subscriber.channel_id" => $channel_id);
            $subscribers = $this->Subscriber->find('count', array('conditions' => $conditions, 'fields' => array('name', 'mobile', 'created'), 'order' => 'Subscriber.created desc'));
            $this->set('subscribers_count', $subscribers);
            $conditions = array("ChannelResponse.channel_id" => $channel_id);
            $responses = $this->ChannelResponse->find('count', array('conditions' => $conditions));
            $this->set('responses_count', $responses);
        }
        $mobile = $_SESSION['Auth']['User']['mobile'];
        $ChannelData = $this->Channel->getMyAllChannels();
        //echo "<pre>";print_r($ChannelData);exit;
        $this->set('channel_id', $channel_id);
        $this->set('channelData', $ChannelData);
        $this->set(compact('PageShow'));
    }

    public function get_messages()
    {

        $this->layout = false;
        $channel_id = $this->data['channel_id'];
        $user_id = $this->Auth->User('id');
        $messges = $this->Message->find('all', array('conditions' => array('Message.channel_id' => $channel_id, 'Message.user_id' => $user_id), 'order' => 'Message.created desc'));
        $this->set('messges', $messges);
        $this->set('channel_id', $channel_id);
    }

    public function get_subscribers()
    {

        $this->layout = false;
        $channel_id = $this->data['channel_id'];
        $user_id = $this->Auth->User('id');
        $conditions = array("Subscriber.channel_id" => $channel_id);
        $data = $this->Subscriber->find('all', array('conditions' => $conditions, 'fields' => array('id', 'name', 'mobile', 'created'), 'order' => 'Subscriber.created desc'));
        $this->set('subscribers', $data);
        $this->set('channel_id', $channel_id);
    }

    public function get_responses()
    {

        $this->layout = false;
        $this->loadModel('ChannelResponse');
        $channel_id = $this->data['channel_id'];
        $user_id = $this->Auth->User('id');
        $this->ChannelResponse->bindModel(array('belongsTo' => array('Channel')));
        $this->ChannelResponse->bindModel(array('belongsTo' => array('Subscriber')));
        $data = $this->ChannelResponse->find('all', array('conditions' => array('ChannelResponse.channel_id' => $channel_id), 'fields' => array('ChannelResponse.id', 'ChannelResponse.response', 'Subscriber.name', 'Channel.channel_name', 'Subscriber.mobile', 'ChannelResponse.created'), 'order' => 'ChannelResponse.created desc'));
        $this->set('data', $data);
        $this->set('channel_id', $channel_id);
    }

    public function msg_history($msg_id = null)
    {

        $this->layout = false;
        $user_id = $this->Auth->User('id');
        $this->MessageQueue->bindModel(array('belongsTo' => array('Subscriber')));
        $messages = $this->MessageQueue->find("all", array('joins' =>
            array(
                array('table' => 'users', 'alias' => 'User', 'type' => 'left', 'foreignKey' => false,
                    'conditions' => array('User.mobile = Subscriber.mobile')
                )),
            "conditions" => array("MessageQueue.message_id" => $msg_id, "MessageQueue.is_deleted" => 'N'),
            "fields" => array("MessageQueue.id", "MessageQueue.status", "MessageQueue.reason", "MessageQueue.route", "MessageQueue.sent_via", "Subscriber.id", "Subscriber.name", "Subscriber.mobile", "Subscriber.status", "User.id", "User.username", "User.mobile"), 'recursive' => 2
        ));
        //pr($messages); die;
        $this->set('data', $messages);
        $this->set('msg_id', $msg_id);
    }

    public function settings()
    {

        $this->layout = 'home';
        $user_id = $this->Auth->User('id');
        if (!empty($this->data)) {
            //pr($this->data); die;
            $image_data = $this->data['User']['image'];
            $this->request->data['Channel']['user_id'] = $user_id;
            $this->request->data['User']['image'] = $this->data['User']['old_image'];
            $this->request->data['User']['about'] = $this->data['User']['about'];
            $this->User->set($this->request->data['User']);
            if ($this->User->validates()) {
                if ($this->User->save($this->data, false)) {

                    if (!empty($image_data['name']) && isset($image_data['name'])) {
                        $extension = substr(strrchr($image_data['name'], '.'), 1);
                        $image_name = "user_" . $user_id . "." . $extension;
                        $new_file_name = "users/" . $image_name;
                        $resp = $this->Custom->s3upload($image_data['tmp_name'], $new_file_name);
                        if ($resp) {
                            $this->User->id = $user_id;
                            $this->User->saveField('image', $resp);
                        }
                    }
                    $this->Session->setFlash(__('Profile updated successfully.'), 'default', array(), 'front_success');
                    $this->redirect(array('controller' => 'users', 'action' => 'settings'));
                }
            }
            //echo "hello";exit;
        }
        $locations = $this->Location->find('list', array('conditions' => array('Location.status' => 'Y'), 'fields' => array('slug', 'name')));
        $this->set('locations', $locations);
        $user_data = $this->User->findById($this->Auth->User('id'));
        $mychannel = $this->Channel->getMyChannels();
        // echo "<pre>";print_r($mychannel);exit;
        $this->set('mychannel', $mychannel);
        $this->set('userdata', $user_data);

        //echo "<pre>";print_r($user_data);exit;

        $this->set('creditdata', $this->credithistory_1($user_id));
        $this->set('user', $this->credithistory_reseller());
        $this->request->data = $user_data;

    }

    /* public function userprofile()
      {
      $this->layout = 'home';
      $user_id = $this->Auth->User('id');
      if(!empty($this->data)){
      echo "<pre>";
      print_r($this->data);exit;

      }
      } */

    public function genearte_new_token()
    {
        $this->autoRender = false;
        $user_id = $this->Auth->User('id');
        $token = $this->Custom->getRandomAppKey(8);
        $this->User->id = $user_id;
        if ($this->User->saveField('app_key', $token)) {
            echo $token;
            die;
        }
    }

    public function forgot_pasword()
    {
        if($this->request->is(array('post','put')))
        {
            $to = $this->request->data['User']['email'];
            $subject = "Reset Password";
            $user = $this->User->find("first",array(
                "conditions" => array(
                    "User.email" => $to,
                ),
                'contain'=>false
            ));
            if(!empty($user)){
                $update_status = $this->User->updateAll(array('User.mob_vf_code' =>"'".base64_encode(rand(1000,999999))."'"),array('User.id' => $user['User']['id']));
                $reset_link = Router::url('/reset/',true).urlencode(base64_encode($user['User']['id']))."/".urlencode(base64_encode($user['User']['email']));
                $message = "Click here to reset password :-".$reset_link;
                $this->Custom->sendSimpleEmail($to,$subject,$message);
                $this->Session->setFlash(__('Email sent successfully.'), 'default', array(), 'success');
            }
        }

    }

    public function reset_password($id,$email)
    {



        if(empty($id) || empty($email)){
            $this->redirect("/home");
        }else{




            $user_id = urldecode(base64_decode($id));
            $UserInfo = $this->User->find("first",array(
                "conditions" => array(
                    "User.id" => $user_id,
                    "User.email" => urldecode(base64_decode($email)),
                ),
                'contain'=>false
            ));

            if(!empty($UserInfo)){

                if(!empty(trim($UserInfo['User']['mob_vf_code']))){

                    if($this->request->is(array('post','put')))
                    {

                            $this->User->set($this->request->data['User']);
                            if ($this->User->validates()) {
                                $update_status = $this->User->updateAll(
                                    array(
                                        'User.password' =>"'".md5($this->request->data['User']['password'])."'",
                                        'User.mob_vf_code' =>"'".""."'"
                                    ),
                                    array(
                                        'User.id' => $user_id
                                    )
                                );
                                if($update_status){
                                    $this->Session->setFlash(__('Password reset successfully'), 'default', array(), 'success');
                                    $this->redirect("/org-login");
                                }else{
                                    $this->Session->setFlash(__('Sorry password could not reset.'), 'default', array(), 'error');
                                }
                            }

                    }
                }else{
                    $this->Session->setFlash(__('Your link has been expired. Please try again.'), 'default', array(), 'warning');
                    $this->redirect("/home");
                }
            }else{
                $this->Session->setFlash(__('Invalid reset link.'), 'default', array(), 'warning');
            }

        }

    }


    public function send_password()
    {

        $this->autoRender = false;
        $response = array();
        if ($this->request->is("post")) {

            $mobile = $this->data['mobile'];
            $username = '';
            if ($mobile != '') {
                $userdata = $this->User->find("first", array("conditions" => array("User.mobile" => $mobile)));

                if (!empty($userdata)) {
                    $username = $userdata['User']['username'];
                    if ($userdata['User']['status'] == 'N') {
                        $response['status'] = 0;
                        $response['message'] = "Mobile number not activated.";
                    } else if ($userdata['User']['is_verified'] == 'N') {
                        $response['status'] = 0;
                        $response['message'] = "Mobile number not verified.";

                        $verify_url = SITE_URL . "users/verify/" . $userdata['User']['mob_vf_code'];
                        $message = "Your Account is not verified. <br>Click <a href=" . $verify_url . ">here</a> for verify your Account";
                        $this->Session->write('message', $message);
                    } else {
                        $password = $this->Custom->getUnqiePassword(5);
                        $md5_password = md5($password);

                        //$message = "Dear".$username.",your password is ".$password. ". Thanks mBroadcast.";
                        $message = "Dear " . $username . ", your password is " . $password . ". Thanks mBroadcast";
                        $sms_resp = $this->Custom->send_message_system($userdata['User']['mobile'], urlencode($message));
                        $sms_rsppnse = json_decode($sms_resp, true);

                        if ($sms_rsppnse['response']['status'] == 'success') {   //echo "hello";exit;
                            $this->User->id = $userdata['User']['id'];
                            if ($this->User->saveField('password', $md5_password)) {
                                $response['status'] = 1;
                                $response['message'] = "New Password sent on your mobile number.";
                            } else {
                                $response['status'] = 0;
                                $response['message'] = "Something went wrong. please try again later";
                            }
                        } else {
                            $response['status'] = 0;
                            $response['message'] = "Something went wrong. please try again later";
                        }
                    }
                } else {
                    $response['status'] = 0;
                    $response['message'] = "Please Enter Mobile Number.";
                }
            } else {
                $response['status'] = 0;
                $response['message'] = 'Mobile Number not registered.';
            }
        }
        echo json_encode($response);
        die;
    }

    public function getpassword($user_id = null)
    {

        $this->autoRender = false;
        if ($user_id != '') {
            $user_id = base64_decode($user_id);
            $userdata = $this->User->find("first", array("conditions" => array("User.id" => $user_id)));
            if (!empty($userdata)) {
                $password = $this->Custom->getUnqiePassword(8);
                $md5_password = md5($password);
                $message = "mBroadcast: Your Password is " . $password;
                $sms_resp = $this->Custom->send_message($userdata['User']['mobile'], $message);
                $sms_rsppnse = json_decode($sms_resp, true);
                //pr($sms_rsppnse); die;
                if ($sms_rsppnse['response']['status'] == 'success') {
                    $this->User->id = $userdata['User']['id'];
                    if ($this->User->saveField('password', $md5_password)) {
                        $message = "Password sent success on your mobile.";
                        $this->Session->write('message', $message);
                        $this->redirect(SITE_URL . "homes/popup_flash_message/success/Y/home");
                    } else {
                        $message = "Something went wrong. please try again later";
                        $this->Session->write('message', $message);
                        $this->redirect(SITE_URL . "homes/popup_flash_message/error/N/home");
                    }
                } else {
                    $message = "Something went wrong. please try again later";
                    $this->Session->write('message', $message);
                    $this->redirect(SITE_URL . "homes/popup_flash_message/error/N/home");
                }
            } else {
                $message = "Mobile Number not registered.";
                $this->Session->write('message', $message);
                $this->redirect(SITE_URL . "homes/popup_flash_message/error/N/home");
            }
        } else {
            $message = "Invalid URL!";
            $this->Session->write('message', $message);
            $this->redirect(SITE_URL . "homes/popup_flash_message/error/N/home");
        }
    }

    public function credithistory_1($user_id = null)
    {

        $query = "SELECT * FROM `credit_histories` WHERE `user_id`=$user_id order by created desc";
        $creditdata = $this->CreditHistory->query($query);

        return $creditdata;
    }

    public function credithistory_reseller()
    {

        $id = $this->Auth->User('id');
        $query = "SELECT `is_reseller` FROM `users` WHERE id=$id";
        $user = $this->User->query($query);

        return $user;
    }

    public function credithistory()
    {

        $this->autoRender = false;

        if (!empty($this->request->data)) {

            $response = array();

            $id = $this->Auth->User('id');
            $mobile = $this->Auth->User('mobile');
            $new_mobile = $this->request->data['CreditHistory']['mobile'];

            if ($new_mobile == '') {
                $response['status'] = 0;
                $response['message'] = "Please Enter New Mobile Number";

            } else if (preg_match('/^[1-9]{1}[0-9]{9}$/', $new_mobile) && (strlen($new_mobile) == 10)) {
                $new_mobile = "+91" . $new_mobile;

            }

            $UserInfo = $this->User->find('first', array('conditions' => array('User.mobile' => $new_mobile), 'fields' => array('id', 'username', 'credit')));

            if ($mobile != $new_mobile) {
                if ($UserInfo) {
                    $user_id = $UserInfo['User']['id'];
                    $name = trim($UserInfo['User']['username']);

                    $user_credit = $this->request->data['CreditHistory']['credit'];
                    $user_credit_us = $this->Custom->GetField('User', 'credit', $id);
                    if ($user_credit_us < $user_credit) {
                        $response['status'] = 0;
                        $response['message'] = $this->Session->setFlash(__('You Do not have sufficient balance to transfer.'), 'default', array(), 'front_error');
                        $this->redirect(array('controller' => 'users', 'action' => 'settings'));
                    }
                    //Value inserted into account of given user
                    //
                    $creditToBeAdded = $UserInfo['User']['credit'] + $user_credit;
                    $creditToBeDeduct = $user_credit_us - $user_credit;
                    $this->User->set('id', $user_id);
                    $this->User->set('credit', $creditToBeAdded);
                    if ($this->User->save()) {

                        $this->User->set('id', $id);
                        $this->User->set('credit', $creditToBeDeduct);
                        $this->User->save();

                        $this->CreditHistory->create();
                        $this->CreditHistory->set('user_id', $id);
                        $this->CreditHistory->set('transfer_user_id', $user_id);
                        $this->CreditHistory->set('name', $name);
                        $this->CreditHistory->set('mobile', $new_mobile);
                        $this->CreditHistory->set('credit', $user_credit);

                        if ($this->CreditHistory->save()) {
                            // $last_insert_id = $this->CreditHistory->getLastInsertId();
                            $this->Session->setFlash(__('Credit Transfer sucessfully.'), 'default', array(), 'front_success');
                            $this->redirect(array('controller' => 'users', 'action' => 'settings'));

                            //$data = $this->set('creditdata', $this->CreditHistory->find('first', array('conditions' => array('CreditHistory.user_id' => $id), 'fields'=> array('name','mobile','credit','created'),'order' => 'CreditHistory.created desc')));
                            //$this->set('creditdata',$data);


                        }

                    }

                } else {
                    $response['status'] = 0;
                    $response['error-message'] = $this->Session->setFlash(__('User Does not exist.'), 'default', array(), 'front_error');
                    $this->redirect(array('controller' => 'users', 'action' => 'settings'));
                }
            } else {
                $response['status'] = 0;
                $response['error-message'] = $this->Session->setFlash(__('You Do not have transfer credit to own number.'), 'default', array(), 'front_error');
                $this->redirect(array('controller' => 'users', 'action' => 'settings'));
            }

        }
    }

    ///****************************************///
    //*********** ADMIN FUNCTIONS START*********//
    ///****************************************///





    // Registration Organization Start

    public function register_organization()
    {

        //$this->layout = 'home';
    $this->layout = 'app_admin_home';    
    $organization = $this->OrganizationType->find('all', array('fields' => array('org_type',)));
        //set products data and pass to the view


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
                                        $this->redirect(array('controller' => 'app_admin', 'action' => 'verify',"?"=>array('m_c' => $userdata['User']['mob_vf_code'])));

                                    }else{
                                        $datasource->rollback();
                                        $this->Session->setFlash(__('Sorry user could not registered.'), 'default', array(), 'front_error');
                                    }

                                }else{
                                    $this->Session->setFlash(__('Sorry user could not registered.'), 'default', array(), 'front_error');
                                }
                        }else{
                            $errors = $this->User->validationErrors;
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
                             $this->redirect(array('controller' => 'app_admin', 'action' => 'verify',"?"=>array('m_c' => $verification_code)));
                         } else {

                             $this->Session->setFlash(__('This Mobile number is already registered.'), 'default', array(), 'warning');
                         }

                    }


            }catch (Exception $e){
                $datasource->rollback();

                echo $e->getMessage();die;

            }


        }

        $this->set('organization_type', $organization);
        $this->set('organization_type', $this->OrganizationType->find('list', array('conditions' => array('OrganizationType.status' => 'ACTIVE'), 'fields' => array('org_type_id', 'org_type'))));

    }

    // Registration Organization End






}