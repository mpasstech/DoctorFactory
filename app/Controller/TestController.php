<?php

App::uses('Controller', 'Controller');
App::uses('CakeEmail', 'Network/Email');
date_default_timezone_set("Asia/Kolkata");
ini_set('memory_limit', '-1');

class TestController extends AppController {

    public $name = "services";
    public $uses = array('EventCategory','Event','Gullak','AppEnquiry','QuestionChannel','ActionType','QuestionChoice','ActionQuestion','ChannelMessage','MessageStatic','MessageAction','Collaborator','CmsPage', 'User', 'Like', 'Transaction', 'Thinapp', 'Channel', 'Message', 'MessageQueue', 'Subscriber', 'Setting', 'ActionType', 'ActionResponse', 'SubscriberMessages');
    public $components = array('Custom');

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow();
    }


  /* testing function */
    public function add_subscriber_to_topic(){




        $this->autoRender = false;



       $sub =  $this->Subscriber->find('all', array(
           'conditions' => array(
               'Subscriber.mobile' => "+918890720687",
               'Subscriber.app_id' => 15
           ),
           'contain'=>array('Channel')
           ));

        $filed = array();


        foreach ($sub as $key => $sss){


            $thinapp =  ClassRegistry::init('Thinapp')->find('first',array(
                'conditions'=>array(
                    'Thinapp.id'=>$sss['Subscriber']['app_id']
                )
            ));

            $thin_app_id =$sss['Subscriber']['app_id'];
            $topic_name=$sss['Channel']['topic_name'];
            $user_firebase_token="ehbtMnU_aYA:APA91bHEFLbBde-If-01PDgPkWap4FvBS88Lr2wPN2gHioOcgWuyhpkP1s6Ea88JSzPivJiHI5KYR_1KqfrJ1ePx6ef2GKidRsuM_-3mLEjB8pn4R7sBrP0McDhJYWgOjPLzPPVw_hZV";
            if (!empty($thinapp)){
                if(!empty($thinapp['Thinapp']['firebase_server_key'])){
                    $server_key =$thinapp['Thinapp']['firebase_server_key'];
                    $path_to_firebase_cm = 'https://iid.googleapis.com/iid/v1:batchAdd';
                    $fields = array(
                        'to' => '/topics/'.$topic_name,
                        'registration_tokens' => array($user_firebase_token),
                    );
                    $headers = array(
                        'Authorization:key='.$server_key,
                        'Content-Type:application/json'
                    );
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $path_to_firebase_cm);
                    curl_setopt($ch, CURLOPT_POST, true);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
                    $result = curl_exec($ch);
                    //curl_close($ch);
                    //return $result;
                    if(curl_errno($ch)){
                        echo 'Curl error: ' . curl_error($ch);
                    }
                    pr(curl_getinfo($ch));

                    curl_close($ch);die;

                }else{
                    return false;
                }

            }else{
                return false;
            }


        }










    }

    public function remove_subscriber_from_topic(){


        $this->autoRender = false;

        $thin_app_id ="15";
        $topic_name="channel9";
        $user_firebase_token="cPXZVD7YYaM:APA91bFVGD1IjUGgoa6MwNRNuAfVzJodHAMSOOeaUp3NBwPv1MDzDfy2twZdRkKQJxLcE2xLWu4xtdriF3T9Rn4e9S5IYy3W0LyixUwxcxauMR3l67pjgoH6A5jX2mFUlk26cdtgSH_n";

        $filed = array();
        $thinapp =  ClassRegistry::init('Thinapp')->find('first',array(
            'conditions'=>array(
                'Thinapp.id'=>$thin_app_id
            )
        ));
        if (!empty($thinapp)){
            if(!empty($thinapp['Thinapp']['firebase_server_key']) && !empty($thinapp['User']['firebase_token'])){
                $server_key =$thinapp['Thinapp']['firebase_server_key'];
                $path_to_firebase_cm = 'https://iid.googleapis.com/iid/v1:batchRemove';
                $fields = array(
                    'to' => '/topics/'.$topic_name,
                    'registration_tokens' => array($user_firebase_token),
                );
                $headers = array(
                    'Authorization:key='.$server_key,
                    'Content-Type:application/json'
                );
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $path_to_firebase_cm);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
                $result = curl_exec($ch);
                //curl_close($ch);
                //return $result;
                if(curl_errno($ch)){
                    echo 'Curl error: ' . curl_error($ch);
                }
                pr(curl_getinfo($ch));

                curl_close($ch);die;

            }else{
                return false;
            }

        }else{
            return false;
        }
    }


    /* testing function */
    public function get_topic(){


        $filed = array();
        $server_key ="AIzaSyCWdw6-oJX6Jkp2UfSHGvuhlDvozj6RQUI";
        //$server_key ="AIzaSyCWdw6-oJX6Jkp2UfSHGvuhlDvozj6RQUI";
        $iid_token ="eLnVGh8EXUc:APA91bFzi966FtjG69x0JVtM5Q3_atWm5uJIktxZujatjjaBgMfbYdNsUZzILrcH4qd8UyUkYbt17GMYjZISrz3-P9Btd_1ZwHB_rZB7bXEX5bl9UNAazroyFtO80VPULDhOS_8No-uT";
        $iid_token ="cr_134sTSXM:APA91bGodSef9kgkzQU0HzAi6UnFaTIy6a1LT5kJhZk_U4prCRQZtjH63d2WiF9MeaWAzGpZXCdsRBNRKQbFDMS2Q1LIu_hXcx18-FLRAc4GQHmcvUSj-aBVIaBzA-zJKU8-Njypdttm";
        $path_to_firebase_cm = "https://iid.googleapis.com/iid/v1/".$iid_token."/rel/topics/4";
        $headers = array(
            'Content-Type:application/json',
            'Authorization:key='.$server_key
        );

        $path_to_firebase_cm = "https://iid.googleapis.com/iid/info/".$iid_token."?details=true";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $path_to_firebase_cm);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($filed));
        $result = curl_exec($ch);

        pr($result);die;
        if(curl_errno($ch)){
            echo 'Curl error: ' . curl_error($ch);
        }
        //pr(curl_getinfo($ch));
        pr(json_decode($result));die;
        curl_close($ch);



        return $result;


    }







    public function test(){

        echo $today = date("Y-m-d H:i:s");die;;
      //  $this->Custom->googleUrlShortner("http://stackoverflow.com/questions/13066919/google-api-url-shortener-with-php");die;
      //  $this->Custom->sendSimpleEmail("","","");die;

        $string = "    Acute Kidney Injury Clinic,    Adaptive Sports Physical Therapy Program,    Admitting,    Adolescent Bariatric Program,    Adolescent and Young Adult Cancer Program,    Adolescent Behavioral Health,    Adolescent Gynecology,    Adolescent Medicine,    Adolescent Substance Abuse Program,    Adolescent Wellness Clinics,    Alyssa Burnett Adult Life Center,    Ambulatory Infusion,    Anesthesiology,    Audiology,    Augmentative and Alternative Communication,    Autism Center,    Autoimmune Kidney Disease Clinic,    Bellevue Clinic and Surgery Center,    Billing,    Biobehavioral Program,    Biochemical Genetics,    Biofeedback,    Bioethics Consultation Service,    Blood Disorders,    Brachial Plexus Clinic,    Brain Tumor Program,    Cancer and Blood Disorders Center,    Cancer Care Unit,    Cardiac Intensive Care Unit (CICU),    Cardiology and Cardiac Surgery,    Center for Diversity and Health Equity,    Childhood Communication Center,    Child Life,    Child Wellness Clinics,    Children's at Overlake,    Children's Resource Line,    Children with Special Healthcare Needs,    CICU,    Corporate Compliance,    Craniofacial Center,    Critical Care Medicine,    Cystic Fibrosis Program,    Dentistry,    Dermatology,    Diabetes,    Dialysis,    Differences in Sex Development,    Digital Health,    Diversity and Health Equity,    Eating Disorders,    Emergency Department,    Endocrinology,    ENT (Otolaryngology),    Epilepsy Program,    Ethics Consultation Service and Ethics Committee,    Everett,    Family Advisory Council,    Family Conversations Program,    Family Resource Center,    Financial Assistance,    Gastroenterology and Hepatology,    Gender Clinic,    General Surgery,    Genetic Counseling,    Genetics,    Genetics, Biochemical,    Greeter Services,    Grief and Loss,    Growth and Feeding Dynamics Clinic,    Guest Services,    Gynecology,    Health Equity,    Health Information Management,    Hearing Loss Clinic,    Heart Center,    Hematology (Blood Disorders),    Hepatology,    Home Care Services,    Hospitalist Service,    Hypertension,    Immunology,    Infectious Diseases and Virology,    Inflammatory Bowel Disease Center,    Insurance,    International Medical Services,    Interpreter Services,    Interventional Radiology,    Journey Program (Grief and Loss),    Kidney Stones Clinic,    Laboratories,    Library and Information Commons,    Locations,    Lower Limb Differences Clinic,    Medical Records,    Mill Creek Clinic,    Mitochondrial Medicine and Metabolism,    Neonatology,    Neonatal Intensive Care Unit (NICU),    Nephrology,    Neuroblastoma Program,    Neurodevelopmental,    Neurology,    Neuropsychology,    Neurosurgery,    NICU,    Nutrition,    Obesity,    Occupational Therapy,    Odessa Brown Children's Clinic,    Olympia Clinic,    Oncology,    Ophthalmology,    Oral and Maxillofacial Surgery,    Orthopedics,    Orthotics and Prosthetics,    Otolaryngology,    Pain Medicine,    Pancreatitis Clinic,    Parent and Family Support Groups,    Patient and Family Relations,    Patient Navigation Program,    Paying for Care,    Pediatric Advanced Care Team,    Pediatric and Adolescent Gynecology,    Pediatric Cardiology of Alaska,    Pediatric Hypertension,    Pediatric Intensive Care Unit (PICU),    Pediatric General and Thoracic Surgery,    Pharmacy,    Physical Therapy,    PICU,    Plastic Surgery,    Prader-Willi Syndrome Clinic,    Prenatal Diagnosis and Treatment,    Protection Program,    Psychiatry and Behavioral Medicine,    Psychiatry and Behavioral Medicine Unit,    Pulmonary and Sleep Medicine,    Radiology,    Radiology, Interventional,    Reconstructive Pelvic Medicine,    Regional Services (Seattle Children's Locations),    Rehabilitation Medicine,    Rehabilitation Psychology,    Resource Line,    Respiratory Therapy,    Rheumatology,    School Services,    Security Services,    Sleep Disorders,    Social Work,    South Clinic in Federal Way,    South Sound Cardiology Clinics,    Speech and Language,    Spiritual Care,    Sports Concussion Program,    Sports Medicine,    Sports Physical Therapy,    Stanley Stamm Summer Camp,    Support Groups,    Surgery,    Surgery Pulmonary Follow-Up Clinic,    Telemedicine,    Therapy Pool,    Thyroid Program,    Transplant Center,    Treuman Katz Center for Pediatric Bioethics,    Tri-Cities Clinic,    Urgent Care Clinics,    Urology,    Vascular Anomalies,    Wenatchee Clinic";
        $exp = explode(",",$string);
        pr($exp);die;



        $message = 'You are subscribed to channel ' . 'test' . ' by ' . 'mbroadcast';
        $option = array(
            'thinapp_id' => 1,
            'customer_id' =>0,
            'staff_id' => 14,
            'service_id' => 1,
            'channel_id' => 0,
            'role' => "STAFF",
            'flag' => 'APPOINTMENT',
            'title' => "New Appointment Request",
            'message' => mb_strimwidth($message, 0, 80, '...'),
            'description' => mb_strimwidth($message, 0, 100, '...'),
            'chat_reference' => '',
            'module_type' => 'APPOINTMENT',
            'module_type_id' => 23,
            'firebase_reference' => ""
        );
        Custom::send_notification_by_user_id($option,array(14),1);die;
     //   $this->Custom->send_notification_or_sms_to_device($option,$message);
        

    //    die;




        $user_firebase_token_array =array(
            'eN7pO2GcdWU:APA91bH3TZiSXLioBOnx8gSh9U3DCdg7R99syWLJTvfHOwz0OA5y7wJNNetrf2ta2RmokoP47QZ5OONIj_aKY2tzix47K9PyG64nhpOljOywdcI6Y5cFv-k0U1FAbk_hDWcQk-q1DrH0'
        );
        pr(json_decode($this->Custom->send_notification_to_multiple_device($option,$user_firebase_token_array)));;die;

        $number_array=array();
        $number_array[] ="+918890720687";
        for($i=9801;$i<9801+1500;$i++){
            $number_array[] ="+91".$i;
        }
        $number_array[] ="+918890720687";

         pr($this->Custom->addSubscriberSendBlukSms(1,"test message",$number_array,3));





        die;
        $string = $this->EventCategory->find('list',array('order'=>array('EventCategory.title'=>'asc')));
        pr($string);die;


        pr($this->Custom->check_user_permission(1,'CREATE_CHANNEL_PERMISSION'));

        die;
        $url = Router::url('/',true);
        echo $url = rtrim($url,'/');die;




        echo strtoupper("15 Minutes");die;

        $event = $this->Event->find('all',array(
            'conditions' => array(
                'Event.thinapp_id'=>1,
                'Event.status'=>'ACTIVE',
            ),
            'contain' =>array('CoverImage'),
            'order' => array("Event.id"=> "DESC"),
            'fields'=>array('CoverImage.media_path','Event.id','Event.title','Event.description','Event.venue','Event.start_datetime','Event.end_datetime')
        ));

        pr($event);die;

        pr($headers = apache_request_headers()["Accept"]);die;


        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        echo finfo_file($finfo, DEFAULT_COVER_IMAGE);
        finfo_close($finfo);
        die;
        $path  = DEFAULT_COVER_IMAGE;
       ECHO  $ext = pathinfo($path, PATHINFO_TYPE);
DIE;

        $new_arr[]= unserialize(file_get_contents('http://www.geoplugin.net/php.gp?ip='.$_SERVER['REMOTE_ADDR']));
        echo "Latitude:".$new_arr[0]['geoplugin_latitude']." and Longitude:".$new_arr[0]['geoplugin_longitude'];

        die;

        pr($this->Custom->totalSubscriberForChannel(1));die;
        pr($_FILES);

        $res = $this->Custom->uploadFileAws($_FILES['file']);

        pr($res);die;
        pr($this->Custom->send_otp("Test","+918890720687", '1234','1'));
        die;
    }


    public function getAppStatus() {
        $this->autoRender = false;
        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        if ($this->request->is('post')) {
            $thin_app_id = $data['thin_app_id'];
            $app_key = $data['app_key'];
            if ($app_key != '' &&  $thin_app_id!='' ) {
                if (($result = $this->Custom->CheckIsValidApp($app_key,$thin_app_id)) && ($result == 1) ) {
                    $statusArr = $this->Thinapp->find('first',array(
                        "conditions"=>array(
                            "Thinapp.id"=>$thin_app_id
                        ),
                        'fields'=>array("Thinapp.status"),
                        'contain'=>false
                    ));
                    if(!empty($statusArr)){
                        if($statusArr['Thinapp']['status'] == 'ACTIVE')
                        {
                            $response['status'] = 1;
                            $response['data']['status'] = $statusArr['Thinapp']['status'];
                            $response['message'] = "App is active.";
                        }
                        else
                        {
                            $response['status'] = 0;
                            $response['data']['status'] = $statusArr['Thinapp']['status'];
                            $response['message'] = "Your app is inactive.";
                        }
                    }
                    else
                    {
                        $response['status'] = 0;
                        $response['message'] = "App is not found.";
                    }
                }
                else
                {
                    $response['status'] = 0;
                    $response['message'] = $this->Custom->getResponseMessage($result);
                }
            }
            else
            {
                $response['status'] = 0;
                $response['message'] = "Invalid request parameter.";
            }
            echo json_encode($response);
            exit;
        }
    }

    /* function add by mahendra*/
    public function send_notification_to_device(){


        $this->autoRender = false;

        $send_array=array('message'=>'Hi','title'=>'yes','flag'=>'NEW_EVENT','channel_id'=>'6');
        $filed = array();


                $server_key ="AIzaSyCWdw6-oJX6Jkp2UfSHGvuhlDvozj6RQUI";
                $path_to_firebase_cm = 'https://fcm.googleapis.com/fcm/send';
                $fields = array(
                    'to' => "clTkR7Ynlx0:APA91bHqjqrY93b1M0QNHTAZXWsjbNOrJISGYeW7e9snJA0Hwilyd1dNx2j4IzTCPe_6PKBApRzYKF04LzIp52Nx0vPlWvG5arOhLe6JZ_QljblCLq-XlmrCtKi2ejIt7VkJ1Spvhgh0",
                    'data' => $send_array
                );
                $headers = array(
                    'Authorization:key='.$server_key,
                    'Content-Type:application/json'
                );
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $path_to_firebase_cm);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
                $result = curl_exec($ch);
                curl_close($ch);
                return $result;
                 if(curl_errno($ch)){
                   echo 'Curl error: ' . curl_error($ch);
               }
                  pr(curl_getinfo($ch));


        die;


    }
    public function send_notification_to_topic(){


        $this->autoRender = false;

        $send_array=array('message'=>'Hi','title'=>'yes','flag'=>'NEWPOST','channel_id'=>'6');

        $sendArray = array(
            'channel_id'=>6,
            'thinapp_id'=>1,
            'flag'=>'NEWPOST',
            'title'=> strtoupper('Title'),
            'message' => mb_strimwidth("Test message", 0, 50, '...')
        );
        $this->Custom->send_topic_notification($sendArray);

        die;


        $filed = array();

        $server_key ="AIzaSyCWdw6-oJX6Jkp2UfSHGvuhlDvozj6RQUI";
        $path_to_firebase_cm = 'https://fcm.googleapis.com/fcm/send';
        $fields = array(
            'to' => '/topics/channel_6_20161017182234_12045',
            'data' => $send_array
        );
        $headers = array(
            'Authorization:key='.$server_key,
            'Content-Type:application/json'
        );


                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $path_to_firebase_cm);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
                $result = curl_exec($ch);
                curl_close($ch);
                return $result;
                 if(curl_errno($ch)){
                   echo 'Curl error: ' . curl_error($ch);
               }
                  pr(curl_getinfo($ch));


        die;


    }



        public function get_sbu(){
          $this->autoRender = false;
            $topic_name =  ClassRegistry::init('Subscriber')->find('list',array(
                'conditions'=>array(
                    'Subscriber.status'=>'SUBSCRIBED',
                    'AppUser.firebase_token !='=>''
                ),
                'fields'=>array('AppUser.id','AppUser.firebase_token'),
                'contain'=>'AppUser'
            ));
            pr($topic_name);die;

        }

    public  function get_post(){
        $this->autoRender =false;

       /* $fbId = '';

        $fbid = "100000485630146";
        $fbLimit = 10;
        date_default_timezone_set("America/Chicago");
*/
        $app_id = "208597029563827";
        $app_secret="cb310591c3acddafd2da0a0b868c9339";

        $token = 'https://graph.facebook.com/oauth/access_token?client_id='.$app_id.'&client_secret='.$app_secret.'&grant_type=client_credentials';
        $token = file_get_contents($token);

        //$url = "https://graph.facebook.com/".$fbid."/feed?permissions=grant&limit=3";
        // Update by MC Vooges 11jun 2014: Access token is now required:
     //   $url.= "&".$token;





        //$token = "EAAC9t8fZALbMBAN5wfbG3ShchwrhHWWGhZBp7s0Vs3lqQuSryZALmZCdA06F6wQJD3Fzv87aI92T7uWTYi6AQoP6TQK154v7ExFWQlH3VBHds4ybxSeEw0Am6QcDpdzpyuU5njQ4sZBwyVgRogFG4y9r2L5EMJ5TXQZApGGAd4D2D9QJZAs3ZB0euoT0ZCdZAJIOO8Dc7TVZBzFPVJlCGfUnpWH";
        $url ="https://graph.facebook.com/".$app_id."/posts?fields=id,message,created_time,type,object_id&".$token;

        echo $url;
       // $url ="https://graph.facebook.com/me/posts?fields=id,message,created_time,type,object_id&access_token=EAAC9t8fZALbMBAN5wfbG3ShchwrhHWWGhZBp7s0Vs3lqQuSryZALmZCdA06F6wQJD3Fzv87aI92T7uWTYi6AQoP6TQK154v7ExFWQlH3VBHds4ybxSeEw0Am6QcDpdzpyuU5njQ4sZBwyVgRogFG4y9r2L5EMJ5TXQZApGGAd4D2D9QJZAs3ZB0euoT0ZCdZAJIOO8Dc7TVZBzFPVJlCGfUnpWH";
        //load and setup CURL
        $c = curl_init($url);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
        //get data from facebook and decode JSON
        $page = json_decode(curl_exec($c));
        //close the connection
        curl_close($c);
        pr($page);die;
        //return the data as an object
        pr($page->data);die;
        return $page->data;

    }
    public function firebase(){

       // echo date('Y-m-d H:i:s');die;

        //$this->Custom->refresh_firebase_token(25,"+918560824829");die;
        $this->Custom->refresh_firebase_token(3,"+918560824829");die;


        

            $tokent ="dEcmoAI993A:APA91bE4dGKbig2vdAD5AZzgM-BnyS1Bcid_9p4LtEMS0QEiN2n_L9khpBC0_QWXUpj8TOgOmHIqQ3z5SsCrWV6NOIAptR9DY0yprZk3Q_zPmbBl_iwvXEXcTWzkl163fD27W6lqnsKo";
        $this->Custom->refresh_firebase_token_new(15,21,"+919610639103",$tokent);die;


        $str = "+919610639103,+919610639103,+919610639103,+919610639103,+919610639103,+919610639103,+919610639103,+919610639103,+919610639103,+919610639103,+919610639103,+919610639103";


        $this->Custom->sendBulkSms(9,15,"Test Message",40,21);die;




        $this->Custom->send_message_system_bulk();die;



       


    }



    public function add_subscriber_to_multiple_topic(){

                    $this->autoRender = false;
                    $server_key ="AIzaSyBNn7VP3btbQ4mM-tiGggIkpKWJlHf48jw";
                    $path_to_firebase_cm = 'https://iid.googleapis.com/iid/v1:batchAdd';
                    $use_firebase_token  = "d3KetbmMPFM:APA91bECYFV8i1g3LEpmyo6OLEla7SGOFbHdEwP-XezNtZYxOXeBSeU4XDiiVlRAqUpOufIBcu4Iml0HE8Cc1M_oNRgAYlAWYlo__VmKw6gWsHFAoT8eMwmAMXVH34RJdoBZoY54U-bS";
                    $topi_arry =array(
                        'channel25',
                        'channel26'
                    );
                    $fields = array(
                        'to' => '/topics/'.array('channel251','channel26'),
                        'registration_tokens' => array($use_firebase_token),
                    );
                    $headers = array(
                        'Authorization:key='.$server_key,
                        'Content-Type:application/json'
                    );
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $path_to_firebase_cm);
                    curl_setopt($ch, CURLOPT_POST, true);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
                    $result = curl_exec($ch);
                    //curl_close($ch);
                    //return $result;


                     if(curl_errno($ch)){
                       echo 'Curl error: ' . curl_error($ch);die;
                   }
                    if(curl_errno($ch)){
                        echo 'Curl error: ' . curl_error($ch);
                    }
                    pr(curl_getinfo($ch));
                    pr($result);

                    curl_close($ch);die;












    }



}

