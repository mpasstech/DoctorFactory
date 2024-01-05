<?php
App::uses('AppController', 'Controller');
include (WWW_ROOT."webservice".DS."ConnectionUtil.php");
include (WWW_ROOT."webservice".DS."WebservicesFunction.php");
include (WWW_ROOT."webservice".DS."WebServicesFunction_2_3.php");
$http_origin = isset($_SERVER['HTTP_ORIGIN'])?$_SERVER['HTTP_ORIGIN']:'';
if ($http_origin == "https://mngz.in:1703" || $http_origin == "https://mpasscheckin.com/")
{
    header("Access-Control-Allow-Origin: $http_origin");
}

class HomesController extends AppController {
	
	public $uses = array('AppointmentStaffService','AppointmentService','AppointmentStaffAddress','LabPharmacyUser','AppEnquiry','NewslettersubScribers','AppointmentCustomerStaffService');
	public $components = array('Custom');
	public function beforeFilter(){
		parent::beforeFilter();
		$this->layout = 'home';
		$this->Auth->allow('chatBotQuery','phonepayCallback', 'ivr_callback','reception_dti','unblockDate','dashboard_chart','ratingList','blogPostData','callToPatient','tud','iot_token_list_modal','validateDashboard','submitQuery','test','dti_logout','updateCounterStatus','dti_login','dti','play_tracker_voice','setCurrentToken','IoT_close_token','IoT_skip_token','IoT_send_to_billing_counter','IoT_current_token','pine_token_list','edit_patient_detail','updateNextToken','doctor_code_list','dti_report','load_health_tip','whats_app_log','send_doctor_alert','sendReminder','qutot_user_booking','update_web_app_token','add_more_staff','mq_form_preview','save_web_app','web_app_signup','app_created','send_otp','create_app','mq_save_setting','get_state_list','get_city_list','mq_setting','subscribe','create_counter_report','assign_counter','update_counter_status', 'mq_counter_list','send_to_doctor','send_to_billing_counter','un_skip_appointment','skip_appointment','load_doctor_time_slot','load_info','load_flag_data','save_flag','flags','send_invoice_alert','load_proforma_invoice','pharmacist','decline_proforma','patient_proforma_invoice','proforma_invoice','checkLogin','cancel_appointment','edit_patient','close_appointment','mq_token_list','mq_form','unsubscribe','home','campaign','update_opd_form','patient_opd_form','lt','video_decline','finish','send_link','video_connected','video','check_connection','r','refund','skype','request_call','create_ticket','index','popup_flash_message','app_store','app_enquiry','contact_us','home','pricing','features','support','usecase','term','privacy','earn','blog','add_to_news_latter','career','doctor','success','contact_us_ajax','generate_request_otp','receipt','get_doctor_current_token');
	}

	 public function validateDashboard($file_name)
    {
    	
        if($userdata = json_decode(WebservicesFunction::readJson($file_name,"temp"),true)){
            $deleted = WebservicesFunction::deleteJson(array($file_name),"temp");
            if ($this->Auth->login($userdata)) {
                $this->redirect(array('controller' => 'app_admin', 'action' => 'dashboard', 'admin' => false));
            }
        }
        $this->redirect('/org-login');
    }



	public function  home(){
	    $this->layout = false;
	    $un = isset($this->request->query['un'])?$this->request->query['un']:'';
	    if(!empty($un)){
            $connection = ConnectionUtil::getConnection();
            $mobile = base64_decode($un);
            $query = "select * from futuristic_subscribers where mobile ='$mobile' limit 1";
            $record = $connection->query($query);
            if ($record->num_rows) {
                $status = mysqli_fetch_assoc($record)['status'];
                if($status=='UNSUBSCRIBED'){
                    $un='';
                }
            }else{
                $un='';
            }
        }
	    $this->set(compact('un'));
	}
    public function  unsubscribe(){
        $this->layout = false;
        $mob = base64_decode($this->request->data['mob']);
        $connection = ConnectionUtil::getConnection();
        $query = "update futuristic_subscribers set status =?, created=? where mobile = ?";
        $stmt_type = $connection->prepare($query);
        $status = 'UNSUBSCRIBED';
        $created = Custom::created();
        $stmt_type->bind_param('sss', $status,$created, $mob);
        if($stmt_type->execute()){
            echo "SUCCESS";
        }else{
            echo "FAIL";
        }
        die;
    }

	public function  test(){
    	echo "<pre>";
        

        die;
    }

	public function  pricing(){}
	public function  features(){}
	public function  support(){}
	public function  usecase(){}
	public function  term(){}
	public function  privacy(){}
	public function  earn(){}
	public function  blog(){}
	public function  refund(){}
	public function  career(){}
	public function  submitQuery(){
        $this->autoRender = false;
        $email_array =explode(",",SEND_ALERT_EMAILS);
        $message = $this->request->data['message'];
        $email = $this->request->data['email'];
        $mobile = $this->request->data['mobile'];
        $name = $this->request->data['name'];
        $city = $this->request->data['city'];
         $source = $this->request->data['source'];
        $html = "<table border='1'><tr><th>Name</th><td>$name</td></tr><tr><th>Mobile</th><td>$mobile</td></tr><tr><th>City</th><td>$city</td></tr><tr><th>Email</th><td>$email</td></tr><tr><th>Request From</th><td>$source</td></tr><tr><th>Message</th><td>$message</td></tr></table>";
       
        $mail = new PHPMailer;
        $mail->isSMTP();
        $mail->Host       = SMTP_HOST;
        $mail->SMTPAuth   = true;
        $mail->Username   = MENGAGE_EMAIL;
        $mail->Password   = MENGAGE_PASSWORD;
        $mail->SMTPSecure = 'ssl';
        $mail->Port       = 465;
        $mail->addCustomHeader("Content-Transfer-Encoding","quoted-printable");
        $mail->addCustomHeader("Content-Type","text/html; charset=\"UTF-8\"; format=flowed");
        $mail->setFrom(MENGAGE_EMAIL,$source." REQUEST ");
        foreach ($email_array as $email){
            $rep = explode("@",$email);
            $mail->addAddress($email, $rep[0]);
        }
        $mail->addReplyTo(MENGAGE_EMAIL, 'Information');
        $mail->Subject = "NEW QUERY REQUEST FROM MPASS";
        $mail->Body    = $html;
        $mail->isHTML(true);
        if($mail->send()){
            echo "success";
        }else{
            echo "fail";
        }
        die;

    }
	
	 public function  chatBotQuery(){
        $this->autoRender = false;
        $email_array =explode(",",SEND_ALERT_EMAILS);
        $this->request->data = $_POST;
        $thin_app_id = $this->request->data['thin_app_id'];

        $app_data = Custom::get_thinapp_data($thin_app_id);
        $message = $this->request->data['reason'];
        $email = $this->request->data['email'];
        $mobile = $this->request->data['mobile'];
        $name = $this->request->data['name'];
        $city = $this->request->data['address'];

        $this->request->data['AppEnquiry']['enquiry_type'] = "CHATBOT";
        $this->request->data['AppEnquiry']['name'] = $name;
        $this->request->data['AppEnquiry']['thinapp_id'] = $thin_app_id;
        $this->request->data['AppEnquiry']['email'] = $email;
        $this->request->data['AppEnquiry']['phone'] = $mobile;
        $this->request->data['AppEnquiry']['message'] = $message;
        if($this->AppEnquiry->save($this->request->data['AppEnquiry'])){
            $html = "<table border='1'><tr><th>Name</th><td>$name</td></tr><tr><th>Mobile</th><td>$mobile</td></tr><tr><th>City</th><td>$city</td></tr><tr><th>Email</th><td>$email</td></tr><tr><th>Source</th><td>mPass Chatbot</td></tr><tr><th>Query For</th><td>".$app_data['name']."</td></tr><tr><th>Message</th><td>$message</td></tr></table>";
            $mail = new PHPMailer;
            $mail->isSMTP();
            $mail->Host       = SMTP_HOST;
            $mail->SMTPAuth   = true;
            $mail->Username   = MENGAGE_EMAIL;
            $mail->Password   = MENGAGE_PASSWORD;
            $mail->SMTPSecure = 'ssl';
            $mail->Port       = 465;
            $mail->addCustomHeader("Content-Transfer-Encoding","quoted-printable");
            $mail->addCustomHeader("Content-Type","text/html; charset=\"UTF-8\"; format=flowed");
            $thin_app_data = Custom::getThinAppData($thin_app_id);
            $mail->setFrom(MENGAGE_EMAIL," NEW REQUEST FOR ".$thin_app_data['name']);
            foreach ($email_array as $email){
                $rep = explode("@",$email);
                $mail->addAddress($email, $rep[0]);
            }
            $mail->addReplyTo(MENGAGE_EMAIL, 'Information');
            $mail->Subject = "NEW REQUEST FROM CHATBOT FOR ".$thin_app_data['name'];
            $mail->Body    = $html;
            $mail->isHTML(true);
            if($mail->send()){
                echo "success";die;
            }
        }

        echo "fail"; die;
        

    }

	public static function ratingList()
    {
        $response = array();
        if (isset($_POST['post_id'])) {
            $post_id = $_POST['post_id'];
            $sql = "select dr.user_name,dr.total_star,DATE_FORMAT(dr.created, '%d %b, %Y %H:%i') as date_str from doctor_reviews as dr join appointment_staffs as staff on staff.id= dr.doctor_id where staff.post_id = $post_id and dr.total_star >= 3 order by dr.id desc limit 5";
            $connection = ConnectionUtil::getConnection();
            $dataRS = $connection->query($sql);
            $dataToSend = mysqli_fetch_all($dataRS, MYSQLI_ASSOC);
            $response['status'] = 1;
            $response['data'] = $dataToSend;
        } else {
            $response['status'] = 0;
            $response['message'] = "No record found";
        }
        echo json_encode($response);
        exit();
    }


	public static function blogPostData()
    {
        $return = array();
        $response['address_list']="";
        $response['hours_list']="";
        $response['doctor_data']="";

        if (isset($_POST['post_id'])) {
            $post_id = $_POST['post_id'];
            $file_name = "blog_post_$post_id";
            if(!$response = json_decode(WebservicesFunction::readJson($file_name,"blog"),true)){

                $doctor_id = $thin_app_id = 0;
                $sql = "select id,thinapp_id from appointment_staffs as staff where staff.post_id = $post_id";
                $connection = ConnectionUtil::getConnection();
                $dataRS = $connection->query($sql);
                if ($dataRS->num_rows) {
                    $docData = mysqli_fetch_assoc($dataRS);
                    $doctor_id = $docData['id'];
                    $thin_app_id = $docData['thinapp_id'];
                }
              
                $sql = "select address.address from appointment_staff_addresses as ass join appointment_addresses as address on address.id = ass.appointment_address_id join appointment_staffs as staff on staff.id= ass.appointment_staff_id where staff.post_id = $post_id and address.status ='ACTIVE'";
                $connection = ConnectionUtil::getConnection();
                $dataRS = $connection->query($sql);
                if ($dataRS->num_rows) {
                    $response['address_list'] = mysqli_fetch_all($dataRS, MYSQLI_ASSOC);
                }
            
                $sql = "select CONCAT(TIME_FORMAT(STR_TO_DATE(ash.time_from, '%l:%i %p' ), '%h %p'),'-',TIME_FORMAT(STR_TO_DATE(ash.time_to, '%l:%i %p' ), '%h %p')) as time_string, ash.status, ash.thinapp_id, ash.appointment_staff_id, ash.appointment_day_time_id from appointment_staff_hours as ash  join appointment_staffs as staff on staff.id= ash.appointment_staff_id where staff.post_id = $post_id";
                $dataRS = $connection->query($sql);
                if ($dataRS->num_rows) {
                    $response['hours_list'] = $list_data = mysqli_fetch_all($dataRS, MYSQLI_ASSOC);
                }
            
            	
                $doctor_data = Custom::get_doctor_by_id($doctor_id);
            	
            	
            		
                if(!empty($doctor_data)){
                    $tmp['mobile']=$doctor_data['mobile'];
                    $tmp['education']=$doctor_data['sub_title'];
                    $tmp['facebook_url']=$doctor_data['facebook_url'];
                    $tmp['twitter_url']=$doctor_data['twitter_url'];
                    $tmp['linkedin_url']=$doctor_data['linkedin_url'];
                    $tmp['instagram_url']=$doctor_data['instagram_url'];
                    $tmp['experience']=$doctor_data['experience'];
                    $tmp['registration_number']=$doctor_data['registration_number'];
                    $tmp['email']=$doctor_data['email'];
                    $tmp['profile_photo']=$doctor_data['profile_photo'];
                    $tmp['description']=$doctor_data['description'];
                    $tmp['blog_map']=$doctor_data['blog_map'];
                    
                    $app_data = Custom::get_thinapp_data($doctor_data['thinapp_id']);
                    $tmp['logo']=$app_data['logo'];
                    $tmp['app_name']=$app_data['name'];
                    $tmp['webapp_link']=Custom::create_web_app_url($doctor_id,$doctor_data['thinapp_id']);
                    $response['doctor_data']= $tmp;
                }
                if(!empty($response['doctor_data'])){
                    WebservicesFunction::createJson($file_name,json_encode($response),"CREATE","blog");
                }
                
            }
            if (!empty($response)) {
                $return['status'] = 1;
                $return['message'] = "Data Found";
                $return['data'] = $response;
            }else{
                $return['status'] = 0;
                $return['message'] = "No record found";
            }

        }else{
            $return['status'] = 0;
            $return['message'] = "Invalid post id";
        }
        echo json_encode($return);
        exit();
    }



	public function  doctor(){
		$this->layout =false;
		if($this->request->is(array('Post','Put')))
		{
			$this->AppEnquiry->set($this->request->data['AppEnquiry']);
			if ($this->AppEnquiry->validates()) {

				$this->request->data['AppEnquiry']['enquiry_type'] = "DOCTOR";
				if($this->AppEnquiry->save($this->request->data['AppEnquiry'])){
					$body = "Hello admin, \n\n New doctor request form website: <br>";
					$body .= "Name: ".$this->request->data['AppEnquiry']['name']."<br>";
					$body .= "Email: ".$this->request->data['AppEnquiry']['email']."<br>";
					$body .= "Phone: ".$this->request->data['AppEnquiry']['phone']."<br>";
					$body .= "Department : ".$this->request->data['AppEnquiry']['department_from']."<br>";
					$subject = "Doctor request received on website";
					$to = "pulkit@mengage.in";
					$from = $this->request->data['AppEnquiry']['email'];
					$name = $this->request->data['AppEnquiry']['name'];
					$this->Custom->sendEmail($to,$from,$subject,$body,$name);
					$body = "Hello admin, \nNew doctor request form website: \n";
					$body .= "Name: ".$this->request->data['AppEnquiry']['name']."\n";
					$body .= "Email: ".$this->request->data['AppEnquiry']['email']."\n";
					$body .= "Phone: ".$this->request->data['AppEnquiry']['phone']."\n";
					$body .= "Department : ".$this->request->data['AppEnquiry']['department_from']."\n";
					Custom::send_single_sms("7014791161",$body,1);
					unset($this->request->data['AppEnquiry']);
					$this->Session->setFlash(__('Thank you for your interest. We will get back to you soon with patient engagement app.'), 'default', array(), 'success');
                    $this->redirect(array('controller' => 'homes', 'action' => 'success'));

                    //$this->redirect('/success');
				}else{
					$this->Session->setFlash(__('Sorry query could not update.'), 'default', array(), 'error');
				}
			}
		}




	}
	public function  success(){
		$this->layout =false;
		if($this->request->is(array('Post','Put')))
		{
			$this->AppEnquiry->set($this->request->data['AppEnquiry']);
			if ($this->AppEnquiry->validates()) {

				$this->request->data['AppEnquiry']['enquiry_type'] = "DOCTOR";
				if($this->AppEnquiry->save($this->request->data['AppEnquiry'])){
					$body = "Hello admin, \n\n New doctor request form website: <br>";
					$body .= "Name: ".$this->request->data['AppEnquiry']['name']."<br>";
					$body .= "Email: ".$this->request->data['AppEnquiry']['email']."<br>";
					$body .= "Phone: ".$this->request->data['AppEnquiry']['phone']."<br>";
					$body .= "Department : ".$this->request->data['AppEnquiry']['department_from']."<br>";
					$subject = "Doctor request received on website";
					$to = SUPPORT_EMAIL;
					$from = $this->request->data['AppEnquiry']['email'];
					$name = $this->request->data['AppEnquiry']['name'];
					$this->Custom->sendEmail($to,$from,$subject,$body,$name);
					$body = "Hello admin, \nNew doctor request form website: \n";
					$body .= "Name: ".$this->request->data['AppEnquiry']['name']."\n";
					$body .= "Email: ".$this->request->data['AppEnquiry']['email']."\n";
					$body .= "Phone: ".$this->request->data['AppEnquiry']['phone']."\n";
					$body .= "Department : ".$this->request->data['AppEnquiry']['department_from']."\n";
					Custom::send_single_sms(SUPER_ADMIN_MOBILE,$body,1);
					unset($this->request->data['AppEnquiry']);
					$this->Session->setFlash(__('Thank you for your interest. We will get back to you soon with patient engagement app.'), 'default', array(), 'success');
					$this->redirect('/patient_engagement');
				}else{
					$this->Session->setFlash(__('Sorry query could not update.'), 'default', array(), 'error');
				}
			}
		}




	}
	public function  vshare(){
		$this->layout =false;
		if($this->request->is(array('Post','Put')))
		{
			$this->AppEnquiry->set($this->request->data['AppEnquiry']);
			if ($this->AppEnquiry->validates()) {

				$this->request->data['AppEnquiry']['enquiry_type'] = "DOCTOR";
				if($this->AppEnquiry->save($this->request->data['AppEnquiry'])){
					$body = "Hello admin, \n\n New doctor request form website: <br>";
					$body .= "Name: ".$this->request->data['AppEnquiry']['name']."<br>";
					$body .= "Email: ".$this->request->data['AppEnquiry']['email']."<br>";
					$body .= "Phone: ".$this->request->data['AppEnquiry']['phone']."<br>";
					$subject = "Doctor request received on website";
					$to = SUPPORT_EMAIL;
					$from = $this->request->data['AppEnquiry']['email'];
					$name = $this->request->data['AppEnquiry']['name'];
					$this->Custom->sendEmail($to,$from,$subject,$body,$name);
					unset($this->request->data['AppEnquiry']);
					$this->Session->setFlash(__('Thank you for your interst. We will get back to you soon with patient engagement app.'), 'default', array(), 'success');
					$this->redirect('/doctor');
				}else{
					$this->Session->setFlash(__('Sorry query could not update.'), 'default', array(), 'error');
				}
			}
		}




	}
	public function index($lat=null, $lng=null) {
    
    	if (!isset($_SERVER['HTTPS'])) {
           // $actual_link = "https://www.mpasscheckin.com" . $_SERVER['REQUEST_URI'];
           // header("Location: $actual_link");
           // die;
        }
    
		$this->layout = false;
        $connection = ConnectionUtil::getConnection();
        $file_name = "all_doctor_list";
        $full_path = LOCAL_PATH . "app/webroot/cache/doctor/" .$file_name.".json";
        if (file_exists($full_path)) {
            $date =  strtotime("+30 minutes", strtotime(date("Y-m-d H:i", filemtime($full_path))));
            $current  =strtotime(date("Y-m-d H:i"));
            if(($current) > ($date)){
                WebservicesFunction::deleteJson(array($file_name),'doctor');
            }
            
        }
        if (!$data_array = WebservicesFunction::readJson($file_name, "doctor")){
               $query = "SELECT ser.video_consulting_amount, COUNT(acss.id) AS total_appointment,  staff.is_offline_consulting, staff.is_online_consulting, staff.is_audio_consulting, staff.is_chat_consulting, t.logo, aa.id as address_id, aa.latitude AS lat, aa.longitude AS lng, aa.address, staff.id, t.name as app_name, staff.name AS doctor_name,dc.category_name,c.name AS city_name FROM appointment_staffs AS staff join thinapps as t on t.id = staff.thinapp_id JOIN appointment_staff_addresses AS asa ON asa.appointment_staff_id = staff.id JOIN appointment_addresses AS aa ON aa.id= asa.appointment_address_id LEFT JOIN appointment_staff_services AS ass ON ass.appointment_staff_id = staff.id LEFT JOIN appointment_services AS ser ON ser.id= ass.appointment_service_id left JOIN cities AS c ON c.id = staff.city_id LEFT JOIN department_categories AS dc ON dc.id = staff.department_category_id LEFT JOIN appointment_customer_staff_services AS acss ON acss.appointment_staff_id = staff.id AND DATE(acss.appointment_datetime) = DATE(NOW()) AND staff.thinapp_id = acss.thinapp_id  WHERE staff.show_time_slot_to_patient ='YES' and t.category_name IN('HOSPITAL','DOCTOR','TEMPLE') AND staff.`status`='ACTIVE' AND staff.staff_type ='DOCTOR' AND( ( t.show_into_doctor_apps ='YES') OR (t.booking_convenience_fee_chat > 0 OR t.booking_convenience_fee_audio > 0 OR t.booking_convenience_fee_video > 0 OR t.booking_convenience_fee > 0)) group by staff.id  ORDER BY total_appointment desc";
           
                       
           
           
            $data_list = $connection->query($query);
            if ($data_list->num_rows) {
                $list = mysqli_fetch_all($data_list, MYSQLI_ASSOC);
                $data_array = $data_row_array =array();

                foreach ($list as $key => $value){
                        $data_array['string'][$key]['id'] =$value['id'];
                		$value['doctor_name'] = str_replace("."," ",$value['doctor_name']);
                        $data_array['string'][$key]['doctor_name'] =$value['doctor_name'];
                        $data_array['string'][$key]['category_name'] =$value['category_name'];
                        $data_array['string'][$key]['app_name'] =$value['app_name'];
                        $data_array['string'][$key]['city_name'] =$value['city_name'];
                		$data_array['string'][$key]['logo'] =$value['logo'];
 						
                		$fee = "";
                        if($value['video_consulting_amount']==0){
                            $fee = "<i style='font-weight:600;' class='fa fa-inr'> Free</i>";
                        }else if($value['video_consulting_amount'] > 0){
                            $fee = "<i style='font-weight:600;' class='fa fa-inr'> ".$value['video_consulting_amount']."</i>";
                        }

                        $data_array['string'][$key]['is_online_consulting'] =($value['is_online_consulting']=='YES')?"<span class='icon_span'><i class='fa fa-video-camera'></i> $fee </span>":'';
                        $data_array['string'][$key]['is_audio_consulting'] =($value['is_audio_consulting']=='YES')?"<span class='icon_span'><i class='fa fa-phone'></i></span>":'';
                        $data_array['string'][$key]['is_chat_consulting'] =($value['is_chat_consulting']=='YES')?"<span class='icon_span'><i class='fa fa-comments-o'></i></span>":'';

                
                
                        $data_array['location'][$value['id']."##".$value['address_id']]['lat'] =$value['lat'];
                        $data_array['location'][$value['id']."##".$value['address_id']]['lng'] =$value['lng'];
                }
                $data_array = json_encode($data_array);
                WebservicesFunction::createJson($file_name, $data_array, 'CREATE', "doctor");
            }
        }

        $send_array =array();
        if(!empty($lat) && !empty($lng)){
            $lat = base64_decode($lat);
            $lnt = base64_decode($lng);
            $base_location = array(
                'lat' => $lat,
                'lng' => $lnt
            );
            $distances = array();
            $data = json_decode($data_array,true);

            foreach ($data['location'] as $string => $location)
            {
                $doctor_id = explode("##",$string);
                $doctor_id = $doctor_id[0];
                $distance = Custom::distance( $location['lat'], $location['lng'], $base_location['lat'], $base_location['lng'],'K');
                if($distance <= 15 ){
                    if($label = array_search("$doctor_id",$data['string'],true)){
                        $send_array[$label] = $doctor_id;
                    }
                }
            }
            $data_array = json_encode($send_array);
        }else{
            $data_array = json_decode($data_array,true);
            if(!empty($data_array['string'])){
                $data_array = json_encode($data_array['string']);
            }else{
                $data_array = json_encode(array());
            }


        }

        $this->set(compact('data_array','lat','lng'));



	}


	public function download($filename = null,$ext = null) {
		
		$this->viewClass = 'Media';
		// Download app/outside_webroot_dir/example.zip
		$params = array(
		    'id'        => $filename.".".$ext,
		    'name'      => $filename,
		    'download'  => true,
		    'extension' => $ext,
		    'path'      => APP . 'webroot/samples' . DS
		);
		$this->set($params);
	}
	public function popup_flash_message($type = null,$auto_close = null,$redirect_on = null)
	{
		$message = $this->Session->read('message');
		$this->Session->setFlash($message, "default", array("class" => $type,"auto_close" => $auto_close), "popup_flash");
		$this->Session->delete('message');
		if($redirect_on == "home"){
			$this->redirect(SITE_URL);	
		}elseif($redirect_on == "signup"){
			$this->redirect(SITE_URL."signup");
		}else{
			$this->redirect(SITE_URL."users/".$redirect_on);
		}
		
	}
	public function app_store()
	{
            
		if(isset($_SERVER['HTTP_USER_AGENT'])){
			
			if(strstr($_SERVER['HTTP_USER_AGENT'],'iPhone') || strstr($_SERVER['HTTP_USER_AGENT'],'iPad')){
				
				$this->redirect(APP_STORE_LINK);
				
			}elseif(strstr($_SERVER['HTTP_USER_AGENT'],'Android') || strstr($_SERVER['HTTP_USER_AGENT'],'android')){
				
				$this->redirect(PLAY_STORE_LINK);
			}else{
				$message = "Application Download links available here.";
				$this->Session->write('message',$message);
				$this->redirect(SITE_URL."homes/popup_flash_message/success/Y/home");
			}
		}else{
			$message = "Application Download links available here.";
			$this->Session->write('message',$message);
			$this->redirect(SITE_URL."homes/popup_flash_message/success/Y/home");
		}
	}


	public function skype(){
		if($this->request->is(array('Post','Put')))
		{

			$this->AppEnquiry->set($this->request->data['AppEnquiry']);
			if ($this->AppEnquiry->validates()) {
				$this->request->data['AppEnquiry']['enquiry_type'] = 'SKYPE';
				if($this->AppEnquiry->save($this->request->data['AppEnquiry'])){

					$subject = "Skype request received on website";
					$msg =  $this->request->data['AppEnquiry']['message'];
					$to = SUPPORT_EMAIL;
					$body = "Hello admin, \n\n Skype request received on website from: <br>";
					$body .= "Name: ".$this->request->data['AppEnquiry']['name']."<br>";
					$body .= "Email: ".$this->request->data['AppEnquiry']['email']."<br>";
					$body .= "Phone: ".$this->request->data['AppEnquiry']['phone']."<br>";
					$body .= "Skype ID: ".$this->request->data['AppEnquiry']['skype_id']."<br>";
					$body .= "On Time: ".$this->request->data['AppEnquiry']['on_time']."<br>";
					$body .= "On Date: ".$this->request->data['AppEnquiry']['on_date']."<br>";
					$body .= "Message: ".$this->request->data['AppEnquiry']['message']."<br>";
					//$this->smtp_mail($to,$subject,$body);
					$from = $this->request->data['AppEnquiry']['email'];
					$name = $this->request->data['AppEnquiry']['name'];
					$this->Custom->sendEmail($to,$from,$subject,$body,$name);

					unset($this->request->data['AppEnquiry']);
					$this->Session->setFlash(__('Thanks for your request! We will contact you soon.'), 'default', array(), 'success');
				}else{
					$this->Session->setFlash(__('Sorry request could not be posted.'), 'default', array(), 'error');
				}
			}
		}
	}


	public function contact_us(){

		$this->layout = 'home';

		if($this->request->is(array('Post','Put')))
		{
			$this->AppEnquiry->set($this->request->data['AppEnquiry']);
			if ($this->AppEnquiry->validates()) {

				
				if($this->AppEnquiry->save($this->request->data['AppEnquiry'])){

					$body = "Hello admin, \n\n Contact request received on website from: <br>";
					$body .= "Name: ".$this->request->data['AppEnquiry']['name']."<br>";
					$body .= "Email: ".$this->request->data['AppEnquiry']['email']."<br>";
					$body .= "Phone: ".$this->request->data['AppEnquiry']['phone']."<br>";
					$body .= "Message: ".$this->request->data['AppEnquiry']['message']."<br>";
					$subject = "Contact request received on website";
					$to = SUPPORT_EMAIL;
					$from = $this->request->data['AppEnquiry']['email'];
					$name = $this->request->data['AppEnquiry']['name'];
					$this->Custom->sendEmail($to,$from,$subject,$body,$name);
					unset($this->request->data['AppEnquiry']);
					$this->Session->setFlash(__('Your enquiry post successfully.'), 'default', array(), 'success');
				}else{
					$this->Session->setFlash(__('Sorry enquiry could not update.'), 'default', array(), 'error');
				}
			}



		}
	}


	public function app_enquiry(){
		$this->layout = 'home';

		if($this->request->is(array('Post','Put')))
		{
			$this->AppEnquiry->set($this->request->data['AppEnquiry']);
			if ($this->AppEnquiry->validates()) {
				$logo = $this->request->data['AppEnquiry']['attachment'];
				$this->request->data['AppEnquiry']['attachment'] = "";
				$this->request->data['AppEnquiry']['enquiry_type'] = "ENQUIRY";
				if(isset($logo['tmp_name']) && !empty($logo['tmp_name'])){
					$name = explode('.', $logo['name']);
					$ext = end($name);
					$rand_name = "app".rand(100000,1000000).".".$ext;
					$uploaddir = WWW_ROOT."uploads/enquiry/";
					$file = $uploaddir.$rand_name;

					if(move_uploaded_file($logo['tmp_name'] , $file)) {
						$this->request->data['AppEnquiry']['attachment'] = $rand_name;
					}
				}

				if($this->AppEnquiry->save($this->request->data['AppEnquiry'])){

					$body = "Hello admin, \n\n Custom development request received on website from: <br>";
					$body .= "Name: ".$this->request->data['AppEnquiry']['name']."<br>";
					$body .= "Email: ".$this->request->data['AppEnquiry']['email']."<br>";
					$body .= "Phone: ".$this->request->data['AppEnquiry']['phone']."<br>";
					$body .= "Message: ".$this->request->data['AppEnquiry']['message']."<br>";
					$subject = "Custom development request received on website";
					$to = SUPPORT_EMAIL;
					$from = $this->request->data['AppEnquiry']['email'];
					$name = $this->request->data['AppEnquiry']['name'];
					$this->Custom->sendEmail($to,$from,$subject,$body,$name);

					unset($this->request->data['AppEnquiry']);
					$this->Session->setFlash(__('Your custom app development enquiry successfully posted.'), 'default', array(), 'success');
				}else{
					$this->Session->setFlash(__('Sorry enquiry could not posted.'), 'default', array(), 'error');
				}
			}



		}




	}
	public function create_ticket(){}
	public function request_call(){}
	public function add_to_news_latter(){
		$this->autoRender = false;
		if($this->request->is(array('Post','Put')))
		{
			$response = array();
			$this->NewslettersubScribers->set($this->request->data);

			if($this->NewslettersubScribers->validates())
			{
				if($this->NewslettersubScribers->save($this->request->data)){
					$response['status'] = 1;
				}else{
					$response['status'] = 0;
					$response['message'] = 'Sorry, Could not subscribe.';
				}
			}
			else
			{
				$errors = $this->NewslettersubScribers->validationErrors;
				$response['status'] = 0;
				$response['message'] = $errors['email'][0];
			}


		}
		$response = json_encode($response, true);
		echo $response;
		exit();
	}

	public function contact_us_ajax(){
		$this->layout = false;

		if($this->request->is(array('Post','Put')))
		{
        	
                $from = $this->request->data['AppEnquiry']['email'];
                if(empty($from)){
                    $this->request->data['AppEnquiry']['email'] =$from= 'no@email.com';
                }
        
			$this->AppEnquiry->set($this->request->data['AppEnquiry']);
			if ($this->AppEnquiry->validates()) {
				
				
				if($this->AppEnquiry->save($this->request->data['AppEnquiry'])){

					$body = "Hello admin, \n\n Contact request received on website from: <br>";
					$body .= "Name: ".$this->request->data['AppEnquiry']['name']."<br>";
					$body .= "Email: ".$this->request->data['AppEnquiry']['email']."<br>";
					$body .= "Phone: ".$this->request->data['AppEnquiry']['phone']."<br>";
					$body .= "Message: ".$this->request->data['AppEnquiry']['message']."<br>";
					$subject = "Contact request received on website";
					$to = SUPPORT_EMAIL;
					$from = $this->request->data['AppEnquiry']['email'];
					$name = $this->request->data['AppEnquiry']['name'];
					if($from!='no@email.com'){
                        $this->Custom->sendEmail($to,$from,$subject,$body,$name);
                    }


					//unset($this->request->data['AppEnquiry']);
					//$this->Session->setFlash(__('Your enquiry post successfully.'), 'default', array(), 'success');
					echo "Success";
				}else{
					//$this->Session->setFlash(__('Sorry enquiry could not update.'), 'default', array(), 'error');
					echo "Error";
				}
			}



		}
		die;
	}

	public function generate_request_otp(){

	    $this->layout = false;
        if($this->request->is(array('Post','Put'))) {

            $phone = $this->request->data['phoneVal'];
            $phone = Custom::create_mobile_number($phone);
            $verification_code = Custom::getRandomString(4);
            $body = "Hi, Your one time password for phone verification is ".$verification_code;
            Custom::send_single_sms($phone,$body,1);
            echo $verification_code;
            die;

        }
        die;

    }

    public function receipt($appointmentID=null){
        $this->layout = false;
	    if(!empty($appointmentID))
        {

            $appointmentID = base64_decode($appointmentID);

            if($appointmentID > 0)
            {

                $appointmentData = $this->AppointmentCustomerStaffService->find("first",
                    array(
                        "contain"=>array("AppointmentStaff","AppointmentCustomer","Children"),
                        "conditions"=>array("AppointmentCustomerStaffService.id" => $appointmentID)
                    )
                );
                if(empty($appointmentData)){
                    die("You are not allowed to view this page!");
                }
                else
                {
                    $connection = ConnectionUtil::getConnection();
                    $query = "SELECT t.name as app_name, t.logo, bcfd.*, bco.convence_for  FROM booking_convenience_fee_details as bcfd join booking_convenience_orders as bco on bco.appointment_customer_staff_service_id  =  bcfd.appointment_customer_staff_service_id join thinapps as t on t.id = bcfd.thinapp_id WHERE bcfd.appointment_customer_staff_service_id = '".$appointmentID."' LIMIT 1";
                    $rs = $connection->query($query);
                    if($rs->num_rows)
                    {
                        $bookingData = mysqli_fetch_assoc($rs);
                        $this->set(compact('bookingData','appointmentData'));
                    }
                    else
                    {
                        die("You are not allowed to view this page!");
                    }
                }
            }
            else
            {
                die("You are not allowed to view this page!");
            }




        }
        else
        {
            die("You are not allowed to view this page!");
        }
    }

    public function r($short){
        $this->layout=false;
        try{
            $url = Custom::get_long_url($short);
            if(!empty($url)){
                $url = str_replace("mengage.in", "mpasscheckin.com", $url);
                header("Location: ".$url);die;
            }
        }catch (Exception $e){
            exit();
        }
        
    }

    public function corona_result(){}

    public function check_connection(){
    $response['api_status']= 0;
    $response['join_status']= "NOT_JOINED";
    $response['message']= 'Wait Doctor Will Start Consultation Soon';
    $appointment_id = isset($this->request->data['ai'])?base64_decode($this->request->data['ai']):'';
    $room = isset($this->request->data['room'])?($this->request->data['room']):'';
    if(!empty($appointment_id)){
        if($appointment_id){
            $file_name = "join_$appointment_id";
            if ($join = json_decode(WebservicesFunction::readJson($file_name,"video_join"), true)) {
                if($join['status']=='JOINED' && $room==$join['room']){
                    $response['join_status']= $join['status'];
                    $response['api_status']= "1";
                    $response['message']= 'Connecting...';
                }else if($join['status']=='JOINED' && $room != $join['room']){
                    $response['message']= 'This link has been exipred. Please click on new link sent on whatssap';
                }else if($join['status']=='DISCONNECTED'){
                    $response['message']= 'Participant Disconnected';
                }else if($join['status']=='END'){
                    $response['message']= 'Call Ended';
                }

            }
        }
        echo json_encode($response);die;
    }
    return false;
}

    public function video($param,$finish=0){
        $this->layout = false;
        $data=array();
        $paramData =  explode("##",base64_decode($param));
        $appointment_id = $paramData[0];
        $request_from = $paramData[1];
        $video_base = $room = "";
        if(!empty($appointment_id)){
           $query = "SELECT t.id as thin_app_id, t.name as app_name, t.logo, acss.appointment_datetime,acss.`status`,acss.appointment_patient_name, IFNULL(ac.mobile,c.mobile) AS patient_mobile, staff.sub_title AS eduction, staff.name AS doctor_name,acss.queue_number, IF(staff.profile_photo !='',staff.profile_photo,t.logo) AS photo FROM appointment_customer_staff_services AS acss JOIN thinapps AS t ON t.id = acss.thinapp_id JOIN appointment_staffs AS staff ON staff.id= acss.appointment_staff_id LEFT JOIN appointment_customers AS ac ON ac.id = acss.appointment_customer_id LEFT JOIN childrens AS c ON c.id = acss.children_id  WHERE acss.id = $appointment_id and acss.status IN('NEW','CONFIRM','RESCHEDULE','CLOSED') limit 1";
           
           
            $connection = ConnectionUtil::getConnection();
            $data_ = $connection->query($query);
            if ($data_->num_rows) {
                $data = mysqli_fetch_assoc($data_);
                $thin_app_id = $data['thin_app_id'];
                $file_name = "join_$appointment_id";
                if ($join = json_decode(WebservicesFunction::readJson($file_name,"video_join"), true)) {
                    if(!empty($join['room'])){
                        $room =$join['room'];
                    }
                }
                if(empty($room)){
                    $room = Custom::createRoomName($thin_app_id,$appointment_id);
                }
                $identity = $data['appointment_patient_name'];
                $app_name = $data['app_name'];
                $logo = $data['logo'];
                $callback = SITE_PATH."homes/finish/$appointment_id";
                $doctorCallback = "";
                $reconnect = SITE_PATH."homes/video/$param";
                if($request_from=='DOCTOR'){
                    $identity = $data['doctor_name'];
                }
                $video_base = WEB_VIDEO_CALL_URL;
                $parameter_string = "identity=$identity&app_name=$app_name&logo=$logo&callback=$callback&ai=$appointment_id&rf=$request_from&reconnect=$reconnect";
                $video_base = $video_base."?room=$room&data=".base64_encode($parameter_string);
            }
        }
        $this->set(compact('appointment_id','data','request_from','video_base','room','parameter_string','finish'));
    }

    public function video_connected($appointment_id,$request_from){
        $response['status']= true;
        $this->autoRender = false;
        $appointment_id = base64_decode($appointment_id);
        $request_from = base64_decode($request_from);
        if($request_from=='DOCTOR'){
            echo 'true';die;
        }else{
            if(!empty($appointment_id)){
                $file_name = "join_$appointment_id";
                if ($join = json_decode(WebservicesFunction::readJson($file_name,"video_join"), true)) {
                    if($join['status']=='JOINED'){
                        echo 'true';die;
                    }
                }
            }
        }
        echo 'false'; die;
    }


    public function video_decline($appointment_id){
        $response['status']= false;
        $this->autoRender = false;
        $appointment_id = base64_decode($appointment_id);
        if(!empty($appointment_id)){
            $file_name = "join_$appointment_id";
            if ($join = json_decode(WebservicesFunction::readJson($file_name,"video_join"), true)) {
                if(!empty($join['decline']) && $join['decline']=='true'){
                    $response['status']= true;
                	$RoomName =  Custom::getAppointmentRoomName($appointment_id);
                    if(!empty($RoomName)){
                        $appointment_id = base64_decode($appointment_id);
                        $msg_data = Custom::getAppointmentMessageData($appointment_id);
                        $to_mobile = $msg_data['patient_mobile'];
                        $from_mobile = $msg_data['doctor_mobile'];
                        $thin_app_id = $msg_data['thinapp_id'];
                        $doctor_id = $msg_data['doctor_id'];
                        $send_one  = Custom::sendVideoDisconnectNotification($thin_app_id,$appointment_id,$from_mobile,$to_mobile,$RoomName,$doctor_id);
                        $send_two = Custom::sendVideoDisconnectNotification($thin_app_id,$appointment_id,$to_mobile,$from_mobile,$RoomName,$doctor_id);
                    }
                }
            }
        }
        echo json_encode($response);die;
    }

    public function finish($appointment_id=0){
        $this->layout = false;
    }
    public function send_link($appointment_id){
       $this->autoRender = false;
       if(!empty($appointment_id)){
            $response['status'] = 1;
            $response['message'] = "Message Sent";
            Custom::sendResponse($response);
            Custom::send_process_to_background();
            $appointment_id = base64_decode($appointment_id);
            $msg_data = Custom::getAppointmentMessageData($appointment_id);
            $patient_mobile = $msg_data['patient_mobile'];
            $thin_app_id = $msg_data['thinapp_id'];
            $doctor_name = $msg_data['doctor_name'];
            //$response = Custom::sendWhatsapp(3,$appointment_id,$thin_app_id,'PATIENT',$patient_mobile);
            $param = base64_encode($appointment_id."##".'PATIENT');
            $video_link = Custom::short_url(SITE_PATH."homes/video/$param");
            $body = "*Important information - अतिआवश्यक सुचना !*\n\n$doctor_name has started video call consultation, now you can also take video call consultation by clicking on the link below.\nPlease copy paste below link in any of the  browser like Chrome, Firefox or Safari.\n\n$doctor_name ने वीडियो कॉल परामर्श शुरू कर दिया है अब आप निचे लिंक पर क्लिक करके भी वीडियो कॉल परामर्श ले सकते है |\nकृपया नीचे दिये हुए लिंक को कट एंड पेस्ट करके किसी एक इंटरनेट ब्राउज़र Chrome, Firefox या Safari में खोंले |\n".$video_link;
            $response = Custom::sendWhatsappSms($patient_mobile,$body,$body,$thin_app_id);
            $room = Custom::createRoomName($thin_app_id,$appointment_id,'APPOINTMENT',$msg_data['doctor_mobile'],$patient_mobile);
            Custom::manageRoomFile('DELETE',$appointment_id);
            Custom::manageRoomFile('CREATE',$appointment_id,'JOINED',$room);
            Custom::sendVideoConnectNotification($thin_app_id,$appointment_id,$msg_data['doctor_mobile'],$patient_mobile,$room,$msg_data['doctor_id']);

        }else{
           die('Invalid Request');
       }
    }


    public function lt($appointment_id){
        $this->layout = false;
        $appointment_id = base64_decode($appointment_id);
        $connection = ConnectionUtil::getConnection();
        $query = "SELECT t.name as app_name, staff.name AS doctor_name, t.logo, acss.sub_token, acss.slot_time,acss.appointment_datetime,acss.appointment_patient_name,acss.queue_number, acss.emergency_appointment,acss.sub_token,acss.has_token,acss.custom_token FROM appointment_customer_staff_services AS acss JOIN thinapps AS t ON t.id = acss.thinapp_id JOIN appointment_staffs AS staff ON staff.id = acss.appointment_staff_id WHERE acss.id = $appointment_id  LIMIT 1";
        $rs = $connection->query($query);
        if($rs->num_rows)
        {
            $data = mysqli_fetch_assoc($rs);
            $token = Custom::create_queue_number($data);
            $this->set(compact('data','token'));


        }else{
            $this->autoRender = false;
            echo "<h1>Invalid Request</h1>";
        }


    }

	  public function  patient_opd_form($appointment_id){
        $this->layout =false;
        $appointment_id =base64_decode($appointment_id);
        $query = "SELECT acss.children_id, t.patient_single_field_form, t.logo, staff.profile_photo, staff.name AS doctor_name, acss.`status` AS appointment_status, IFNULL(ac.first_name,c.child_name) AS patient_name, IFNULL(ac.mobile,c.mobile) AS patient_mobile, ac.age,IFNULL(ac.dob,c.dob) AS dob, IFNULL(ac.address,c.patient_address) AS address, IFNULL(ac.email,c.email) AS email, IFNULL(ac.city_name,c.city_name) AS city_name,IFNULL(ac.gender,c.gender) AS gender, IFNULL(ac.parents_mobile,c.parents_mobile) AS parents_mobile FROM appointment_customer_staff_services AS acss JOIN thinapps AS t ON t.id =acss.thinapp_id JOIN appointment_staffs AS staff ON staff.id = acss.appointment_staff_id LEFT JOIN appointment_customers AS ac ON ac.id= acss.appointment_customer_id LEFT JOIN childrens AS c ON c.id= acss.children_id WHERE acss.id=$appointment_id  and acss.status IN('NEW','CONFIRM','RESCHEDULE') LIMIT 1";
        $connection = ConnectionUtil::getConnection();
        $data = $connection->query($query);
        if ($data->num_rows) {
            $data = mysqli_fetch_assoc($data);
            $appointment_id =base64_encode($appointment_id);
            $this->set(compact('data','appointment_id'));
        }
    }
    public function  update_opd_form(){
        $this->layout =false;
        $this->autoRender = false;
        $response =array();
        $appointment_id = base64_decode($this->request->data['ai']);
        $patient_name = $this->request->data['patient_name'];
        $patient_mobile = Custom::create_mobile_number($this->request->data['patient_mobile']);
        $gender = $this->request->data['gender'];
        $age = $this->request->data['age'];
        $dob = $this->request->data['dob'];
        if(!empty($dob)){
            $dob =  date('Y-m-d',strtotime($dob));
        }

        $address = $this->request->data['address'];
        $city_name = $this->request->data['city_name'];
        $parents_mobile = $this->request->data['parents_mobile'];
        $email = $this->request->data['email'];

        $query = "SELECT acss.`status`, acss.thinapp_id, acss.drive_folder_id, acss.appointment_customer_id, acss.children_id FROM appointment_customer_staff_services AS acss where acss.id=$appointment_id and status IN('NEW','CONFIRM','RESCHEDULE') LIMIT 1";
        $connection = ConnectionUtil::getConnection();
        $connection->autocommit(false);
        $data = $connection->query($query);
        $created = Custom::created();
        if ($data->num_rows) {
            $data = mysqli_fetch_assoc($data);
            $appointment_customer_id =$data['appointment_customer_id'];
            $children_id =$data['children_id'];
            $folder_id =$data['drive_folder_id'];
            $thin_app_id =$data['thinapp_id'];
            $allow_add = false;
            if(!empty($appointment_customer_id)){
                $patient =Custom::get_customer_by_name($thin_app_id,$patient_name,$patient_mobile);
                if(empty($patient) || $patient['id']== $appointment_customer_id){
                    $query = "update appointment_customers set first_name =?, gender =?, age =?, dob=?, address=?, city_name=?, parents_mobile=?, email=?, modified =? where id = ?";
                    $stmt_patient = $connection->prepare($query);
                    $stmt_patient->bind_param('ssssssssss', $patient_name, $gender, $age, $dob, $address, $city_name, $parents_mobile, $email, $created, $appointment_customer_id);
                    $allow_add =true;
                }else{
                    $response['status'] = 2;
                    $response['message']="Patient with name '$patient_name' already register in our system. Please use different name for save information";

                }
            }else{
                $patient =Custom::search_child_name($thin_app_id, $patient_mobile, $patient_name);
                if(empty($patient) || $patient['id']== $children_id){
                    $query = "update childrens set child_name =?, gender =?,  dob=?, patient_address=?, city_name=?, parents_mobile=?, email=?, modified =? where id = ?";
                    $stmt_patient = $connection->prepare($query);
                    $stmt_patient->bind_param('sssssssss', $patient_name, $gender, $dob, $address, $city_name, $parents_mobile, $email, $created, $children_id);
                    $allow_add =true;
                }else{
                    $response['status'] = 2;
                    $response['message']="Patient with name '$patient_name' already register in our system. Please use different name for save information";
                }
            }

            if($allow_add===true){
                $query = "update appointment_customer_staff_services set appointment_patient_name=?, modified =? where id = ?";
                $stmt_appointment = $connection->prepare($query);
                $stmt_appointment->bind_param('sss', $patient_name, $created, $appointment_id);

                $query = "update drive_folders set folder_name=?, modified =? where id = ?";
                $stmt_folder = $connection->prepare($query);
                $stmt_folder->bind_param('sss', $patient_name, $created, $folder_id);

                if ($stmt_patient->execute() && $stmt_appointment->execute() && $stmt_folder->execute()) {
                    $connection->commit();
                    $response['status'] = 1;
                    $response['message'] = "For giving us your valuable time.";
                } else {
                    $response['status'] = 0;
                    $response['message'] = "Sorry we are unable to save your information";
                }
            }




        }else{
            $response['status'] = 0;
            $response['message']='This request has been expired.';
        }

        Custom::sendResponse($response);
        die;


    }
	
	public function  campaign(){
        $this->layout =false;
        if($this->request->is(array('Post','Put')))
        {
            $this->AppEnquiry->set($this->request->data['AppEnquiry']);
            if ($this->AppEnquiry->validates()) {

                $this->request->data['AppEnquiry']['enquiry_type'] = "DOCTOR";
                if($this->AppEnquiry->save($this->request->data['AppEnquiry'])){
                    $body = "Hello admin, \n\n New doctor request form website: <br>";
                    $body .= "Name: ".$this->request->data['AppEnquiry']['name']."<br>";
                    $body .= "Email: ".$this->request->data['AppEnquiry']['email']."<br>";
                    $body .= "Phone: ".$this->request->data['AppEnquiry']['phone']."<br>";
                    $body .= "Department : ".$this->request->data['AppEnquiry']['department_from']."<br>";
                    $subject = "Doctor request received on website";
                    $to = "pulkit@mengage.in";
                    $from = $this->request->data['AppEnquiry']['email'];
                    $name = $this->request->data['AppEnquiry']['name'];
                    $this->Custom->sendEmail($to,$from,$subject,$body,$name);
                    $body = "Hello admin, \nNew doctor request form website: \n";
                    $body .= "Name: ".$this->request->data['AppEnquiry']['name']."\n";
                    $body .= "Email: ".$this->request->data['AppEnquiry']['email']."\n";
                    $body .= "Phone: ".$this->request->data['AppEnquiry']['phone']."\n";
                    $body .= "Department : ".$this->request->data['AppEnquiry']['department_from']."\n";
                    Custom::send_single_sms("7014791161",$body,1);
                    unset($this->request->data['AppEnquiry']);
                    $this->Session->setFlash(__('Thank you for your interest. We will get back to you soon with patient engagement app.'), 'default', array(), 'success');
                    $this->redirect(array('controller' => 'homes', 'action' => 'success'));

                    //$this->redirect('/success');
                }else{
                    $this->Session->setFlash(__('Sorry query could not update.'), 'default', array(), 'error');
                }
            }
        }




    }

    public function  mq_form($thin_app_id,$staff_id,$iot_screen='no',$patient_booking_form='no'){
	    $this->layout =false;
	    $thin_app_id =base64_decode($thin_app_id);
        $staff_plan_id =base64_decode($staff_id);
        $condition = $query ="";
        $loginUserRole = "DOCTOR";
        $setTokenBeforeClose = "NO";
        if($thin_app_id==EHCC_APP_ID){
            $setTokenBeforeClose = "YES";
        }
        $app_data = Custom::get_thinapp_data($thin_app_id);
        $staffData = Custom::get_doctor_by_id($staff_plan_id);
        $loginUrl = SITE_PATH."dti/".$app_data['slug'];
        $file_name = "dti_login_".$staff_plan_id;
        $login_data = json_decode(WebservicesFunction::readJson($file_name,"dti"),true);
        $token = isset($_COOKIE[$login_data['token_name']])?$_COOKIE[$login_data['token_name']]:'';
    
    
    	
    
        $valid_token = false;
       
    	$doctorData = Custom::get_doctor_by_id($login_data['doctor_id']);
        if(isset($doctorData['allow_multiple_login']) && $doctorData['allow_multiple_login'] == 'YES'){
            $valid_token = true;
        }else{
            if(!empty($login_data['token'])){
                if(!empty($token)) {
                    $valid_token = ($login_data['token']==$token)?true:false;
                }else{
                    $valid_token = (!empty($login_data) || $login_data['token']=='pass')?true:false;
                }
            }
        }
    
        if($patient_booking_form=='y'){
            $valid_token =true;
            $login_data['password'] =$staffData['password'];
        }
        if($valid_token==false){
            header("Location: ".$loginUrl);
            die;
        }else{
            $login_id =$login_data['login_id'];
            $staff_member_id =0;
            $settingConfigure = false;
            $roleData = Custom::getRoleDataByDoctorId($thin_app_id,$staff_plan_id);
            $loginUserRole = $roleData['staff_type'];
            if(Custom::check_user_permission($thin_app_id,"SHOW_ASSOCIATED_DOCTOR")=="YES"){
                if( $roleData['staff_type']=='DOCTOR' || $roleData['staff_type']=='ADMIN'){
                    if($roleData['staff_type']=='DOCTOR' || $patient_booking_form=='y' || (Custom::isCustomizedAppId($thin_app_id) && $roleData['staff_type']!='ADMIN')){
                        $condition = " AND staff.id = $staff_plan_id ";
                    }
                    $query = "SELECT staff.show_token_into_digital_tud as associate_counter_id, staff.show_token_list_on_digitl_tud as token_visible, staff.counter_booking_type, t.allow_only_mobile_number_booking, ser.service_slot_duration,  asa.appointment_address_id as address_id, t.logo, staff.profile_photo, staff.id as doctor_id, staff.name AS doctor_name, staff.mobile FROM appointment_staffs AS staff JOIN thinapps AS t ON t.id= staff.thinapp_id join appointment_staff_addresses as asa on asa.appointment_staff_id = staff.id join appointment_staff_services as ass on ass.appointment_staff_id = staff.id join appointment_services as ser on ser.id=ass.appointment_service_id  WHERE staff.`status` ='ACTIVE' AND staff.staff_type='DOCTOR' AND staff.thinapp_id = $thin_app_id $condition group by(staff.id)";
                }else{

                    $query = "SELECT staff.show_token_into_digital_tud as associate_counter_id, staff.show_token_list_on_digitl_tud as token_visible, staff.counter_booking_type, t.allow_only_mobile_number_booking, ser.service_slot_duration,  asa.appointment_address_id as address_id, t.logo, staff.profile_photo, staff.id as doctor_id, staff.name AS doctor_name, staff.mobile FROM appointment_staffs AS staff JOIN thinapps AS t ON t.id= staff.thinapp_id join appointment_staff_addresses as asa on asa.appointment_staff_id = staff.id join appointment_staff_services as ass on ass.appointment_staff_id = staff.id join appointment_services as ser on ser.id=ass.appointment_service_id join doctor_associates_receptionists dar on dar.doctor_id = staff.id  WHERE dar.receptionist_id = $login_id and  staff.`status` ='ACTIVE' AND staff.staff_type='DOCTOR' AND staff.thinapp_id = $thin_app_id $condition group by(staff.id)";
                }
            }else{
                if($roleData['staff_type']=='DOCTOR' || $patient_booking_form=='y'){
                    $condition = " AND staff.id = $staff_plan_id ";
                }
                $query = "SELECT staff.show_token_into_digital_tud as associate_counter_id, staff.show_token_list_on_digitl_tud as token_visible, staff.counter_booking_type, t.allow_only_mobile_number_booking, ser.service_slot_duration,  asa.appointment_address_id as address_id, t.logo, staff.profile_photo, staff.id as doctor_id, staff.name AS doctor_name, staff.mobile FROM appointment_staffs AS staff JOIN thinapps AS t ON t.id= staff.thinapp_id join appointment_staff_addresses as asa on asa.appointment_staff_id = staff.id join appointment_staff_services as ass on ass.appointment_staff_id = staff.id join appointment_services as ser on ser.id=ass.appointment_service_id  WHERE staff.`status` ='ACTIVE' AND staff.staff_type='DOCTOR' AND staff.thinapp_id = $thin_app_id $condition group by(staff.id)";
            }
            $connection = ConnectionUtil::getConnection();
            $data = $connection->query($query);
            if ($data->num_rows) {
                $staff_member_id = base64_decode($staff_id);

                if(!empty($app_data)){

                    $folderName = ($iot_screen=='iot')?"tud":"moq";
                    /* add to home icon functionality start*/
                    $dir= false;
                    $icon_folder = LOCAL_PATH . "app/webroot/add_home_screen/$folderName/$staff_member_id/icons";
                    $manifest = LOCAL_PATH . "app/webroot/add_home_screen/$folderName/$staff_member_id/manifest.json";
                    $default_manifest = LOCAL_PATH . 'app/webroot/add_home_screen/doctorapps/manifest.json';
                    if(!is_dir($icon_folder)){
                        if(Custom::createIconFolder($icon_folder)){
                            $dir =true;
                        }
                    }else{
                        $dir = true;
                    }
                    if($dir){
                        if (!file_exists($manifest)) {
                            $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                            if($iot_screen=='iot'){
                                $default_manifest = LOCAL_PATH . 'app/webroot/add_home_screen/doctorapps/tud_manifest.json';
                                $json_data = json_decode(file_get_contents($default_manifest), true);
                                $json_data['name'] = 'mToken';
                                $json_data['short_name'] = 'mToken';
                                $json_data['start_url'] = $actual_link;
                                $json_data['url_handlers'][] = array('origin'=>$loginUrl);
                                $json_data['handle_links'] = 'auto';
                                $json_data['theme_color'] = "#5D54FF";
                                $json_data['background_color'] = "#ffffff";
                                $has_manifest =  file_put_contents($manifest,json_encode($json_data));
                            }else{
                                $json_data = json_decode(file_get_contents($default_manifest), true);
                                $json_data['name'] = 'Daily Token';
                                $json_data['short_name'] = 'Daily Token';
                                $json_data['start_url'] = $actual_link;
                                $json_data['url_handlers'][] = array('origin'=>$loginUrl);
                                $json_data['handle_links'] = 'auto';
                                $json_data['theme_color'] = "#5D54FF";
                                $json_data['background_color'] = "#ffffff";
                                $has_manifest =  file_put_contents($manifest,json_encode($json_data));
                                $logo = $app_data['logo'];
                                $array =array("72","96","128","144","152","192","384","512");
                                foreach ($array as $key =>$value){
                                    $res = Custom::download_image_from_url($logo,$value,$value,$icon_folder."/icon-$value"."x"."$value.png");
                                }
                            }

                        }
                    }
                    /* add to home icon functionality end*/
                }
                $data = mysqli_fetch_all($data,MYSQLI_ASSOC);
                $thin_app_id =base64_encode($thin_app_id);
                $category_name = $app_data['category_name'];

                $doctorData = Custom::get_doctor_by_id($staff_member_id);
                $settingConfigure =true;

            }


            $tmp_list = Custom::get_all_app_doctor_id_name(base64_decode($thin_app_id));
            $doctor_ids ="";
            $ehcc_counter_ids ="";
            foreach ($tmp_list as $doc_id => $doc_name){
                $doctor_ids[]=$doc_id;
            }
            $doctor_ids = implode(",",$doctor_ids);
            $is_custom_app = "NO";
            if(Custom::isCustomizedAppId(base64_decode($thin_app_id))){
                $is_custom_app = "YES";
                $ehcc_counter_ids = Custom::getPaymentCounterId(base64_decode($thin_app_id));
            }
        
        	$billingCounterData = Custom::get_doctor_by_id($doctorData['show_token_into_digital_tud']);
            $counter_type = $billingCounterData["counter_booking_type"];
            $send_to_counter =array();
            if($counter_type=="BILLING"){
                $query = "select  staff.id,staff.name from appointment_staffs as staff  where staff.`status`='ACTIVE' and staff.staff_type='DOCTOR' and staff.invoice_address_id =".$billingCounterData['id'];
                $data = $connection->query($query);
                if ($data->num_rows) {
                    $send_to_counter = mysqli_fetch_all($data, MYSQLI_ASSOC);
                }
            }
        
        	$login_doctor =$login_data['doctor_id'];

            	$this->set(compact('counter_type','send_to_counter','login_doctor','login_id','is_custom_app','ehcc_counter_ids','doctor_ids','patient_booking_form','setTokenBeforeClose','settingConfigure','loginUserRole','iot_screen','doctorData','app_data','category_name','data','staff_member_id','staff_id','thin_app_id'));
        }
    }

	public function  reception_dti($thin_app_id,$reception_id){
        $this->layout =false;
        $thin_app_id =base64_decode($thin_app_id);
        $reception_id =base64_decode($reception_id);
        $reception_data = Custom::get_doctor_by_id($reception_id);
        $setTokenBeforeClose = "NO";
        $condition = $query ="";
        $app_data = Custom::get_thinapp_data($thin_app_id);
        $loginUrl = SITE_PATH."dti/".$app_data['slug'];
        $file_name = "dti_login_".$reception_id;
        $login_data = json_decode(WebservicesFunction::readJson($file_name,"dti"),true);
        $token = isset($_COOKIE[$login_data['token_name']])?$_COOKIE[$login_data['token_name']]:'';
        $valid_token = false;
        $final_array =array();
        if(!empty($login_data['token'])){
            if(!empty($token)) {
                $valid_token = ($login_data['token']==$token)?true:false;
            }else{
                $valid_token = (!empty($login_data) || $login_data['token']=='pass')?true:false;
            }
        }
        if($valid_token==false){
            header("Location: ".$loginUrl);
            die;
        }else{
            $login_id =$login_data['login_id'];
            $query = "select staff.show_token_list_on_digitl_tud, staff.id, staff.name, app_staff.id as counter_id, app_staff.name as counter_name  from doctor_associates_receptionists as dar join appointment_staffs as staff on dar.doctor_id = staff.id join appointment_staffs as app_staff on app_staff.show_token_into_digital_tud = dar.doctor_id and app_staff.id != dar.doctor_id where dar.receptionist_id = $reception_id";
            $connection = ConnectionUtil::getConnection();
            $data = $connection->query($query);
            if ($data->num_rows) {
                $data = mysqli_fetch_all($data,MYSQLI_ASSOC);
                foreach ($data as $key =>$val){
                    $final_array[$val['id']]['associated_doctor_id'] = $val['id'];
                    $final_array[$val['id']]['associated_doctor_name'] = $val['name'];
                    $final_array[$val['id']]['show_token_wise'] = $val['show_token_list_on_digitl_tud'];
                    $final_array[$val['id']]['counter'][] = array("counter_id"=>$val["counter_id"],"counter_name"=>$val["counter_name"]);
                }
            }
            $this->set(compact('login_id','thin_app_id','setTokenBeforeClose','login_data','app_data','final_array'));
        }
    }

    public function  dti($slug){
        $this->layout =false;
        $thin_app_id = 0;
        $data = Custom::get_thin_app_data_by_slug($slug);
        if(!empty($data)){
            $thin_app_id = base64_encode($data['id']);
        }
        $this->set(compact('data','thin_app_id'));
    }

	public function  tud(){

        $this->layout =false;
        $thin_app_id = 0;

    }

    public function  dti_login(){
        $this->autoRender=false;
        $thin_app_id =base64_decode($this->request->data['ti']);
        $login_with =$this->request->data['login_with'];
        $password =isset($this->request->data['password'])?md5($this->request->data['password']):'';
        $mobile =isset($this->request->data['mobile'])?Custom::create_mobile_number($this->request->data['mobile']):'';
        $otp =isset($this->request->data['otp'])?md5(base64_encode($this->request->data['otp'])):'';
        $row_id =isset($this->request->data['row_id'])?$this->request->data['row_id']:'';
        $staff_data = Custom::get_staff_by_mobile($mobile,$thin_app_id);

        if(empty($mobile)){
            $response['status'] = 0;
            $response['message'] = "Please enter mobile number";
        }else if(empty($password) && empty($otp)){
            $response['status'] = 0;
            $response['message'] = "Please enter OTP or Password";
        }else if(empty($staff_data)){
            $response['status'] = 0;
            $response['message'] = "Mobile number is not registerd";
        }else if($login_with=='password' && $staff_data['password']!=$password){
            $response['status'] = 0;
            $response['message'] = "Invalid password";
        }else if($login_with=='otp' && $row_id!=$otp){
            $response['status'] = 0;
            $response['message'] = "Please enter valid OTP";
        }else{
            $response['status'] = 1;
            $response['message'] = "SUCCESS";
            $login_id =$staff_data['id'];

            $roleData = Custom::getRoleDataByDoctorId($thin_app_id,$staff_data['id']);
            if(Custom::isCustomizedAppId($thin_app_id) && $roleData['staff_type']=='RECEPTIONIST'){
               
            
                if(Custom::check_app_enable_permission($thin_app_id, 'SINGLE_TOKEN_BOOKING_APP')){
                    $response['data'] = Custom::createDtiLoginFile($thin_app_id,$login_id,$staff_data['id'],true);
                    $response['data']['li'] = "";
                }else{
                            	$update = HomesController::counterSelection($thin_app_id,$staff_data['id']);
                  				  $response['data']['li'] = $update->body();
            
                            }
             	
            
            	

            }else{
                if($roleData['staff_type']=='DOCTOR'){
                    $response['data'] = Custom::createDtiLoginFile($thin_app_id,$login_id,$staff_data['id'],true);
                }else{
                    $response['data'] = Custom::createDtiLoginFile($thin_app_id,$login_id,$staff_data['id']);
                }
            	$response['data']['li'] = "";
            }

        }
        Custom::sendResponse($response);
        Custom::send_process_to_background();
        $file_name = "dti_login_".$login_id;
        if(empty($response['data']['li']) && $login_data = json_decode(WebservicesFunction::readJson($file_name,"dti"),true)){
            
        	$counter_id = $login_data['doctor_id'];
        	$doctorData = Custom::get_doctor_by_id($counter_id);
            if(!isset($doctorData['allow_multiple_login']) || $doctorData['allow_multiple_login'] != 'YES'){
            $token = (Custom::isCustomizedAppId($thin_app_id))?'OPEN':0;
            $res = Custom::fortisSetTokenMenual($thin_app_id,$counter_id,$token);
        
        $res = Custom::emitSocet(array('thin_app_id'=>$thin_app_id,'doctor_id'=>$login_data['doctor_id'],'reload'=>true,'dti_token'=>$login_data['token']));
        
            }
        
        }
        die('Ok');
    }

    public function  dti_logout($thin_app_id,$doctor_id){
        $this->autoRender=false;
        $thin_app_id =base64_decode($thin_app_id);
        $doctor_id =base64_decode($doctor_id);
        $app_data = Custom::getThinAppData($thin_app_id);
        $file_name = "dti_login_".$doctor_id;
        if(Custom::isCustomizedAppId($thin_app_id)){
            $login_data = json_decode(WebservicesFunction::readJson($file_name,"dti"),true);
            $token = isset($_COOKIE[$login_data['token_name']])?$_COOKIE[$login_data['token_name']]:'';
            if(!empty($login_data)) {
                if(true){
                    $login_id =$login_data['login_id'];
                    $roleData = Custom::getRoleDataByDoctorId($thin_app_id,$login_id);
                    if($roleData['staff_type']=='RECEPTIONIST'){
                        $query = "select doctor_id from doctor_associates_receptionists where receptionist_id = $login_id order by id desc  limit 1";
                        $connection = ConnectionUtil::getConnection();
                        $service_message_list = $connection->query($query);
                        if ($service_message_list->num_rows) {
                            $counter_id =  mysqli_fetch_assoc($service_message_list)['doctor_id'];
                            $data['id'] = base64_encode($counter_id);
                            $data['ti'] = base64_encode($thin_app_id);
                            $data['si'] = base64_encode($login_id);
                            $data['status'] = 'CLOSED';
                            $update = HomesController::updateCounterStatus($data);
                            $ehcc_counter_ids=Custom::getPaymentCounterId($thin_app_id);
                            $token = "";
                            if($login_data = json_decode(WebservicesFunction::readJson($file_name,"dti"),true)){
                                $token = $login_data['token'];
                            }
                            $res = Custom::emitSocet(array('thin_app_id'=>$thin_app_id,'doctor_id'=>$counter_id,'reload'=>true,'dti_token'=>$token,'doctor_ids'=>$ehcc_counter_ids));   
                        
                        }
                        WebservicesFunction::deleteJson(array($file_name),"dti");
                    
                    	if($thin_app_id != 902){
                        $sql = "delete from doctor_associates_receptionists where receptionist_id=? and thinapp_id=?";
                        $stmt_del = $connection->prepare($sql);
                        $stmt_del->bind_param('ss', $login_id,$thin_app_id );
                        $result[] = $stmt_del->execute();
                    
                        }
                    	
                    }
                	else{
                        $counter_id = $login_data['doctor_id'];
                        $res  = Custom::changeCounterLoginLogoutStatus($thin_app_id,$counter_id,$login_id,"CLOSED");
                    }
                }
            }
        }
        WebservicesFunction::deleteJson(array($file_name),"dti");

        $url = SITE_PATH."dti/".$app_data['slug'];
        header("Location: ".$url);
        die;
    }

    public function counterSelection($thin_app_id,$login_staff_id)
    {
        $this->layout = 'ajax';
        $connection = ConnectionUtil::getConnection();
        $query = "select recepiton.name as reception_mobile, recepiton.name as recepiton_name, staff.room_number,staff.id,staff.name, IF(dar.id IS NOT NULL,'Open','Closed') as counter_status from appointment_staffs as staff left join doctor_associates_receptionists as dar on dar.doctor_id = staff.id left join appointment_staffs as recepiton on dar.receptionist_id = recepiton.id where staff.`status`='ACTIVE' and staff.staff_type='DOCTOR' and staff.thinapp_id =$thin_app_id and staff.counter_booking_type!='' group by staff.id";
        
        $data = $connection->query($query);
        $list =array();
        if ($data->num_rows) {
            foreach (mysqli_fetch_all($data, MYSQLI_ASSOC) as $key => $val){
                $list[$val['room_number']][] =$val;
            }
        }
        $thin_app_id = base64_encode($thin_app_id);
        $login_staff_id = base64_encode($login_staff_id);
        $this->set(compact('list','thin_app_id','login_staff_id'));
        return $this->render('counter_selection', 'ajax');
    }

    public function  updateCounterStatus($data=null){
        $this->autoRender=false;
        if(!empty($data)){
            $this->request->data = $data;
        }
        $thin_app_id =isset($this->request->data['ti'])?base64_decode($this->request->data['ti']):'';
        $counter_id =isset($this->request->data['id'])?base64_decode($this->request->data['id']):'';
        $login_id =isset($this->request->data['si'])?base64_decode($this->request->data['si']):'';
        $flag =isset($this->request->data['status'])?($this->request->data['status']):'';

        if(empty($flag) || empty($counter_id) || empty($thin_app_id) || empty($login_id)){
            $response['status'] = 0;
            $response['message'] = "Invalid Parameter";
        }else{
            $connection = ConnectionUtil::getConnection();
            $connection->autocommit(false);
            $created = Custom::created();
            $result =array();
            if(true){
                /* delete if same record exist into database */
                $sql = "delete from doctor_associates_receptionists where receptionist_id=? and thinapp_id=?";
                $stmt_del = $connection->prepare($sql);
                $stmt_del->bind_param('ss', $login_id,$thin_app_id );
                $result[] = $stmt_del->execute();

                /* insert new counter relation into database */
                $sql = "INSERT INTO doctor_associates_receptionists (thinapp_id,doctor_id,receptionist_id,created) VALUES (?, ?, ?, ?)";
                $stmt_sub = $connection->prepare($sql);
                $stmt_sub->bind_param('ssss', $thin_app_id, $counter_id, $login_id,$created );
                $result[] = $stmt_sub->execute();

            }else{
                $sql = "delete from doctor_associates_receptionists where doctor_id = ? and receptionist_id=? and thinapp_id=?";
                $stmt_sub = $connection->prepare($sql);
                $stmt_sub->bind_param('sss', $counter_id, $login_id,$thin_app_id );
                $result[] = $stmt_sub->execute();
            }
            if (!in_array(false,$result)) {
                $counterData = Custom::get_doctor_by_id($counter_id);
                $counter = $counterData['name'];
                $remark = "COUNTER UPDATE";

                $sql = "INSERT INTO counter_patient_logs (thinapp_id,created_by,appointment_id,flag,counter,remark,created) VALUES (?, ?, ?, ?, ?, ?, ?)";
                $stmt = $connection->prepare($sql);
                $stmt->bind_param('sssssss', $thin_app_id,$login_id,$counter_id,$flag,$counter,$remark,$created);
                if ($stmt->execute()) {
                    $connection->commit();
                    $response['status'] = 1;
                    $response['message'] = "Counter update successfully";
                    $status = isset($data['status'])?$data['status']:'OPEN';
                    $token = (Custom::isCustomizedAppId($thin_app_id))?$status:0;
                    $res = Custom::fortisSetTokenMenual($thin_app_id,$counter_id,$token);
                    $response['data'] = Custom::createDtiLoginFile($thin_app_id,$login_id,$counter_id,true);
                    $file_name = "dti_login_".$login_id;
                    $token = "";
                    if($login_data = json_decode(WebservicesFunction::readJson($file_name,"dti"),true)){
                        $token = $login_data['token'];
                    }
                    $res = Custom::emitSocet(array('thin_app_id'=>$thin_app_id,'doctor_id'=>$counter_id,'reload'=>true,'dti_token'=>$token));

                }else{
                    $response['status'] = 0;
                    $response['message'] = "Sorry, counter could not update";
                }
            }else{
                $response['status'] = 0;
                $response['message'] = "Sorry, counter could not update";
            }

        }

        if(!empty($data)){
            return $response;
        }else{
            echo json_encode($response);die;
        }

    }

    public function mq_setting(){
        $this->layout = false;
        $doctor_id = base64_decode($this->request->data('di'));
        $type = $this->request->data('type');
        if ($this->request->is(array('ajax'))) {

            if(!empty($doctor_id)){
                $doctor_data = Custom::get_doctor_info($doctor_id);
                $thin_app_id = $doctor_data['thinapp_id'];
                $roleData = Custom::getRoleDataByDoctorId($thin_app_id,$doctor_id);
                /* hours list */
                $hours_list = $this->AppointmentStaffHour->find("all", array(
                    "conditions" => array(
                        "AppointmentStaffHour.appointment_staff_id" => $doctor_id,
                    ),
                    'contain' => false
                ));

                /* breaks list*/

                $break_array[1] =array('appointment_day_time_id'=>1,'data_list'=>'');
                $break_array[2] =array('appointment_day_time_id'=>2,'data_list'=>'');
                $break_array[3] =array('appointment_day_time_id'=>3,'data_list'=>'');
                $break_array[4] =array('appointment_day_time_id'=>4,'data_list'=>'');
                $break_array[5] =array('appointment_day_time_id'=>5,'data_list'=>'');
                $break_array[6] =array('appointment_day_time_id'=>6,'data_list'=>'');
                $break_array[7] =array('appointment_day_time_id'=>7,'data_list'=>'');

                $query =    $query = "SELECT asbs.appointment_day_time_id, asbs.time_from, asbs.time_to FROM appointment_staff_break_slots AS asbs WHERE asbs.appointment_staff_id = $doctor_id AND asbs.thinapp_id = $thin_app_id AND asbs.status='OPEN'";
                $connection = ConnectionUtil::getConnection();
                $service_message_list = $connection->query($query);
                if ($service_message_list->num_rows) {
                    $breaks = mysqli_fetch_all($service_message_list,MYSQLI_ASSOC);
                    foreach ($breaks as $key =>$list){
                        $break_array[$list['appointment_day_time_id']]['data_list'][] =$list;
                    }
                }
            
            	$block_array=array();
                $query = "SELECT id,book_date as blocked_date FROM `appointment_bloked_slots` WHERE `book_date` >= DATE(NOW()) AND is_date_blocked = 'YES' and doctor_id = $doctor_id";
                $connection = ConnectionUtil::getConnection();
                $service_message_list = $connection->query($query);
                if ($service_message_list->num_rows) {
                    $block_array = mysqli_fetch_all($service_message_list,MYSQLI_ASSOC);
                }
            
                $this->set(compact('type','roleData','doctor_data','hours_list','break_array','block_array'));
            }else{
                exit();
            }
        }else{
            exit();
        }

    }

   public function mq_save_setting(){
        $this->layout = false;
        $this->autoRender = false;
        $doctor_id = base64_decode($this->request->data('di'));
        $setting_type = ($this->request->data('st'));
        $response =array();
        if ($this->request->is(array('ajax'))) {
            $doctor_data = Custom::get_doctor_by_id($doctor_id);
            $response =array();
            $data = $this->request->data;
            if($setting_type=='logo'){
                if(isset($data['file']) && !empty($data['file'])){
                    $file = $data['file'];
                    $file_type = $file['type'];
                    if (isset($file['tmp_name']) && !empty($file['tmp_name'])) {
                        $mimeAarray = array('image/png', 'image/jpeg', 'image/jpeg', 'image/jpeg', 'image/gif', 'image/bmp');
                        if (in_array($file_type, $mimeAarray)) {
                            $data['logo'] = $this->Custom->uploadFileToAws($file);
                        }
                    }
                }
                unset($data['file']);
                $data['id']= $doctor_data['thinapp_id'];
                if(!empty($data['logo'])){
                    if($this->Thinapp->save($data)){
                        $response['status'] = 1;
                        $response['message'] = 'Logo update successfully';
                        $doctor_list = Custom::get_all_doctor_list($doctor_data['thinapp_id']);
                        if(!empty($doctor_list)){
                            foreach ($doctor_list as $key =>$doc_data){
                                $icon_folder = LOCAL_PATH . 'app/webroot/add_home_screen/' . $doc_data['id'];
                                $res = Custom::deleteDir($icon_folder);
                            }
                        }
                    }else{
                        $response['status'] = 0;
                        $response['message'] = 'Sorry, logo could not update';
                    }
                }else{
                    $response['status'] = 0;
                    $response['message'] = 'Please upload logo';
                }

            }else if($setting_type=='app_setting'){
                $data['website_url'] = $this->request->data('website_url');
                $data['tune_tracker_media'] = $this->request->data('tune_tracker_media');
                $data['id']= $doctor_data['thinapp_id'];
                if($this->Thinapp->save($data)){
                    $response['status'] = 1;
                    $response['message'] = 'Setting update successfully';
                }else{
                    $response['status'] = 0;
                    $response['message'] = 'Sorry, setting could not update';
                }

            }else if($setting_type=='profile'){

                if(isset($data['file']) && !empty($data['file'])){
                    $file = $data['file'];
                    $file_type = $file['type'];
                    if (isset($file['tmp_name']) && !empty($file['tmp_name'])) {
                        $mimeAarray = array('image/png', 'image/jpeg', 'image/jpeg', 'image/jpeg', 'image/gif', 'image/bmp');
                        if (in_array($file_type, $mimeAarray)) {
                            $data['profile_photo'] = $this->Custom->uploadFileToAws($file);
                        }
                    }
                }
                unset($data['file']);
                $data['id']= $doctor_id;
                if($this->AppointmentStaff->save($data)){
                    $response['status'] = 1;
                    $response['message'] = 'Profile update successfully';
                    Custom::delete_doctor_cache($doctor_id);
                }else{
                    $response['status'] = 1;
                    $response['message'] = 'Sorry, profile could not update';
                }
            }else if($setting_type=='service'){
                $service_id = base64_decode($this->request->data('si'));
                $data['id']= $service_id;
                if($this->AppointmentService->save($data)){
                    $response['status'] = 1;
                    $response['message'] = 'Service update successfully';
                    Custom::delete_doctor_cache($doctor_id);
                }else{
                    $response['status'] = 1;
                    $response['message'] = 'Sorry, service could not update';
                }
            }else if($setting_type=='hours'){
                foreach ($data['from_time'] as $id => $ft){
                    $ft  = date('h:i A',strtotime($ft));
                    $tt  = date('h:i A',strtotime($data['to_time'][$id]));
                    $save_array[] =array('id'=>$id,'time_from'=>$ft,'time_to'=>$tt,'status'=>$data['status'][$id]);
                }
                if ($this->AppointmentStaffHour->saveAll($save_array)) {
                    $response['status'] = 1;
                    $response['message'] = 'Hours update successfully';
                    Custom::delete_doctor_cache($doctor_id);
                }else{
                    $response['status'] = 0;
                    $response['message'] = 'Sorry, hours setting could not update';
                }
            }else if($setting_type=='breaks'){

                $thin_app_id = $doctor_data['thinapp_id'];
                foreach ($data as $appointment_day_time_id => $inner_list){
                    foreach ($inner_list['from_time'] as $key => $ft){
                        $ft  = date('h:i A',strtotime($ft));
                        $tt  = date('h:i A',strtotime($data[$appointment_day_time_id]['to_time'][$key]));
                        $save_array[] =array(
                            'thinapp_id'=>$thin_app_id,
                            'appointment_staff_id'=>$doctor_id,
                            'user_id'=>$doctor_data['user_id'],
                            'appointment_day_time_id'=>$appointment_day_time_id,
                            'time_from'=>$ft,
                            'time_to'=>$tt
                        );
                    }
                }
                $datasource = $this->AppointmentStaffBreakSlot->getDataSource();
                try {
                    $datasource->begin();
                    $deleted = $this->AppointmentStaffBreakSlot->deleteAll(array('AppointmentStaffBreakSlot.thinapp_id' => $thin_app_id,'AppointmentStaffBreakSlot.appointment_staff_id' => $doctor_id));
                    if ($deleted && $this->AppointmentStaffBreakSlot->saveAll($save_array)) {
                        $datasource->commit();
                        $response['status'] = 1;
                        $response['message'] = 'Breaks setting update successfully';
                        Custom::delete_doctor_cache($doctor_id);
                    }else{
                        $response['status'] = 0;
                        $response['message'] = 'Sorry, breaks setting could not update';
                    }
                }catch (Exception $e){
                    $datasource->rollback();
                    $response['status'] = 0;
                    $response['message'] = 'Sorry, breaks setting could not update';
                }
            }else if($setting_type=='address_time'){

                $thin_app_id = $doctor_data['thinapp_id'];
                $service['id'] = base64_decode($data['si']);
                $service['name'] = trim($data['service_name']);
                $service['service_slot_duration'] = $data['service_duration'];


                $address['id'] = base64_decode($data['aai']);
                $address['from_time'] = date('h:i A',strtotime($data['from_time']));
                $address['to_time'] = date('h:i A',strtotime($data['to_time']));
                if(!empty($service['name'])){
                    $datasource = $this->AppointmentService->getDataSource();
                    try {
                        $datasource->begin();
                        if ($this->AppointmentService->save($service) && $this->AppointmentStaffAddress->save($address)) {
                            $datasource->commit();
                            $response['status'] = 1;
                            $response['message'] = 'Setting update successfully';
                            Custom::delete_doctor_cache($doctor_id);
                        }else{
                            $response['status'] = 0;
                            $response['message'] = 'Sorry, setting could not update';
                        }
                    }catch (Exception $e){
                        $datasource->rollback();
                        $response['status'] = 0;
                        $response['message'] = 'Sorry, setting could not update';
                    }
                }else{
                    $response['status'] = 0;
                    $response['message'] = 'Please enter nature of service';
                }

            }else if($setting_type=='block_date'){

                $doctor_service = Custom::get_doctor_default_data($doctor_id);
                $date = $data['blockDate'];

                $post = array();
                $login = $this->Session->read('Auth.User.User');
                $post['app_key'] = APP_KEY;
                $post['user_id'] = $doctor_data['user_id'];
                $post['thin_app_id'] =$doctor_data['thinapp_id'];
                $post['mobile'] = $doctor_data['mobile'];
                $post['action_from'] ='WEB';
                $post['message'] = $message = "Doctor is on leave today";
                $post['role_id'] = 5;
                $post['slot_string'] = '';
                $post['block_by'] ="DATE";
                $post['address_id'] =  $doctor_service['address_id'];
                $post['doctor_id'] = $doctor_id;
                $post['service_id'] =  $doctor_service['service_id'];
                $post['date'] = $date;
                $date = DateTime::createFromFormat('d/m/Y', $data['blockDate']);
                $date = $date->format('Y-m-d');
                if ($doctor_service) {
                    $doctor_slots = Custom::load_doctor_slot_by_address($date, $doctor_id, $doctor_service['service_slot_duration'], $doctor_data['thinapp_id'], $doctor_service['address_id'],false,"ADMIN",true,true);
                    if ($doctor_slots) {
                        $doctor_slots = array_column($doctor_slots, 'slot');
                        $post['slot_string'] = implode(',', $doctor_slots);
                    }
                    $response =  json_decode(WebservicesFunction::blocked_appointment_slot($post),true);
                    if($response['status']==1){
                        $response['status'] = 1;
                        $response['message'] = 'Date blocked successfully';
                    }else{
                        $response['status'] = 0;
                        $response['message'] = 'Sorry, date could not blocked';
                    }

                }else{
                    $response['status'] = 0;
                    $response['message'] = 'Sorry, doctor setting not found';
                }



            }else if($setting_type=='appointment_address'){

                $thin_app_id = $doctor_data['thinapp_id'];
                foreach ($data['box'] as $address_id => $status){
                    $save_array[] =array('appointment_address_id'=>$address_id, 'thinapp_id'=>$thin_app_id,'appointment_staff_id'=>$doctor_id,'from_time'=>$data['from_time'][$address_id],'to_time'=>$data['to_time'][$address_id]);
                }

                $datasource = $this->AppointmentStaffAddress->getDataSource();
                try {
                    $datasource->begin();
                    $deleted = $this->AppointmentStaffAddress->deleteAll(array('AppointmentStaffAddress.thinapp_id' => $thin_app_id,'AppointmentStaffAddress.appointment_staff_id' => $doctor_id));
                    if ($deleted && $this->AppointmentStaffAddress->saveAll($save_array)) {
                        $datasource->commit();
                        $response['status'] = 1;
                        $response['message'] = 'Setting update successfully';
                        Custom::delete_doctor_cache($doctor_id);
                    } else {
                        $response['status'] = 0;
                        $response['message'] = 'Please enter nature of service';
                    }
                }catch (Exception $e){
                    $datasource->rollback();
                    $response['status'] = 0;
                    $response['message'] = 'Please enter nature of service';
                }


            }else if($setting_type=='appointment_service'){

                $service_id = $data['selected_service'];
                $thin_app_id = $doctor_data['thinapp_id'];
                $save_array =array('thinapp_id'=>$thin_app_id,'appointment_service_id'=>$service_id,'appointment_staff_id'=>$doctor_id);
                $service_amount = $data['service'][$service_id]['service_amount'];
                $video_consulting_amount = $data['service'][$service_id]['video_consulting_amount'];
                $audio_consulting_amount = $data['service'][$service_id]['audio_consulting_amount'];
                $service_array =array(
                    'id'=>$service_id,
                    'service_amount'=>$service_amount,
                    'audio_consulting_amount'=>$audio_consulting_amount,
                    'video_consulting_amount'=>$video_consulting_amount
                );
               
                $datasource = $this->AppointmentStaffService->getDataSource();
                try {
                    $datasource->begin();
                    // pr($save_array);die;
                    $deleted = $this->AppointmentStaffService->deleteAll(array('AppointmentStaffService.thinapp_id' => $thin_app_id,'AppointmentStaffService.appointment_staff_id' => $doctor_id));
                    if ($deleted && $this->AppointmentStaffService->save($save_array) && $this->AppointmentService->save($service_array)) {
                        $datasource->commit();
                        $response['status'] = 1;
                        $response['message'] = 'Setting update successfully';
                        Custom::delete_doctor_cache($doctor_id);
                    } else {
                        $response['status'] = 0;
                         $response['message'] = 'Please enter nature of service';
                    }
                }catch (Exception $e){
                    $datasource->rollback();
                    $response['status'] = 0;
                    $response['message'] = 'Please select service';
                }




            }else{
                die('Invalid Request');
            }
            echo json_encode($response);die;
        }else{
            exit();
        }

    }


	public function  unblockDate(){
        $this->layout =false;
        $this->autoRender = false;
        $response =array();
        $block_id = base64_decode($this->request->data['bi']);
        if(!empty($block_id)){
            $connection = ConnectionUtil::getConnection();
            $query = "delete from appointment_bloked_slots where id = ?";
            $stmt_patient = $connection->prepare($query);
            $stmt_patient->bind_param('s',$block_id);

            if($stmt_patient->execute()){
                $response['status'] = 1;
                $response['message']="Date un-block successfully";
            }else{
                $response['status'] = 0;
                $response['message']="Sorry, date could not un-block";
            }

        }else{
            $response['status'] = 0;
            $response['message']="Invalid block id";

        }

        Custom::sendResponse($response);
        die;
    }
	

    public function  checkLogin(){
	    $this->autoRender=false;
        $staff_id =base64_decode($this->request->data['si']);
        $password =($this->request->data['p']);
        $convert =($this->request->data['convert']);
        if($convert=='yes'){
            $password =md5($this->request->data['p']);
        }

        $allow_login = Custom::check_doctor_active_login_status($staff_id, $password);
        if ($allow_login) {
            $response['status'] = 1;
            $response['message'] = "SUCCESS";
            $data = Custom::get_doctor_by_id($staff_id);;
            $tmp['id']= $data['id'];
            $tmp['name']= $data['name'];
            $tmp['mobile']= $data['mobile'];
            $tmp['password']= $data['password'];
            $response['data'] = $tmp;

        }else{
            $response['status'] = 0;
            $response['message'] = "FAIL";
        }
        echo json_encode($response);die;
    }

   public function  mq_token_list(){

        if ($this->request->is(array('ajax','post'))) {
            if(!isset($this->request->data['doctor_id'])){
                $request = file_get_contents("php://input");
                $this->request->data = json_decode($request, true);
                $return =true;
                $this->layout = false;
            }else{
                $this->layout = 'ajax';
                $return =false;
            }
            $data = $this->request->data;
            $thin_app_id = $t_id = isset($data['thin_app_id']) ? base64_decode($data['thin_app_id']) : "";
            $doctor_id =$d_id = isset($data['doctor_id']) ? base64_decode($data['doctor_id']) : "";
            $query = "SELECT t.logo, acss.drive_folder_id as folder_id,  acss.referred_by as counter, acss.appointment_staff_id, acss.patient_queue_type, acss.thinapp_id, acss.send_to_lab_datetime, acss.skip_tracker, acss.consulting_type, acss.id AS appointment_id, acss.payment_status, acss.appointment_patient_name AS patient_name, IFNULL(ac.id,c.id) AS patient_id, IF(ac.id IS NOT NULL, 'CUSTOMER','CHILDREN') AS patient_type, IFNULL(ac.mobile,c.mobile) AS patient_mobile, acss.queue_number, acss.status FROM appointment_customer_staff_services AS acss left join thinapps as t on t.id = acss.thinapp_id LEFT JOIN appointment_customers AS ac ON ac.id=acss.appointment_customer_id LEFT JOIN childrens AS c ON c.id= acss.children_id WHERE acss.appointment_staff_id = $doctor_id and acss.thinapp_id=$thin_app_id AND DATE(acss.appointment_datetime) = DATE(NOW()) AND acss.status !='DELETED' AND acss.is_paid_booking_convenience_fee !='NO' ORDER BY CAST(acss.queue_number  AS DECIMAL(10,2)) desc, acss.send_to_lab_datetime ASC";
            $connection = ConnectionUtil::getConnection();
            $data =$response = $final_array =array();
            $list = $connection->query($query);
            if ($list->num_rows) {
                $data = mysqli_fetch_all($list,MYSQLI_ASSOC);
                $thin_app_id =base64_encode($thin_app_id);
                $doctor_id =base64_encode($doctor_id);
                if($return==true) {
                    foreach ($data as $key => $list) {
                        $tmp=array();
                        $tmp['appointment_id'] =base64_encode($list['appointment_id']);
                        $tmp['thinapp_id'] =base64_encode($list['thinapp_id']);
                        $tmp['patient_name'] =$list['patient_name'];
                        $tmp['patient_mobile'] =$list['patient_mobile'];
                        $tmp['consulting_type'] =$list['consulting_type'];
                    	$tmp['queue_number'] = $list['queue_number'];
                        if ($list['thinapp_id'] == CK_BIRLA_APP_ID) {
                            $token = Custom::get_ck_birla_token($list['appointment_staff_id'], $list['queue_number']);
                            if (($list['status'] == 'RESCHEDULE' || $list['status'] == 'NEW' || $list['status'] == 'CONFIRM') && !empty($list['counter'])) {
                                $token = $token . " / " . $list['counter'];
                            } else if (in_array($list['status'], array('RESCHEDULE', 'NEW', 'CONFIRM')) && $list['patient_queue_type'] == 'DOCTOR_CHECKIN') {
                                $token = $token . " / At Doctor";
                            }
                            $tmp['queue_number'] = $token;
                        }
                        if ($list['status'] == 'RESCHEDULE' || $list['status'] == 'NEW' || $list['status'] == 'CONFIRM') {
                            $status = ($list['skip_tracker'] == 'YES') ? 'Skipped' : 'Booked';
                            $tmp['status'] = ucfirst(strtolower($status));
                        }else{
                            $tmp['status'] = ucfirst(strtolower($list['status']));
                        }

                        $tmp['send_to_doctor'] = 'NO';
                        $tmp['skip_token'] = 'NO';
                        $tmp['unskip_token'] = 'NO';
                        $tmp['send_to_billing'] = 'NO';
                        $tmp['assign_counter'] = 'NO';
                        $tmp['medical_record'] = "";
                        $tmp['cancel'] = "NO";
                        $tmp['close'] = "NO";


                        if ($list['status'] == 'RESCHEDULE' || $list['status'] == 'NEW' || $list['status'] == 'CONFIRM') {
                            if ($list['thinapp_id'] == CK_BIRLA_APP_ID && $list['patient_queue_type'] != 'DOCTOR_CHECKIN') {
                                $tmp['send_to_doctor'] = 'YES';
                            }
                            if ($list['skip_tracker'] == 'NO') {
                                $tmp['skip_token'] = 'YES';
                            } else {
                                $tmp['unskip_token'] = 'YES';
                            }
                            if ($list['thinapp_id'] == CK_BIRLA_APP_ID ) {
                                if ($list['patient_queue_type'] == 'DOCTOR_CHECKIN') {
                                    $tmp['send_to_billing'] = 'YES';
                                }
                                $tmp['assign_counter'] = 'YES';
                            }
                            if ($list['consulting_type'] != 'OFFLINE') {
                                $string = $list['folder_id'] . "##FOLDER##" . $list['patient_mobile'];
                                $folder_url = FOLDER_PATH . 'record/' . Custom::encodeVariable($string);
                                $tmp['medical_record'] = $folder_url;
                            }

                            $tmp['cancel'] = "YES";
                            $tmp['close'] = "YES";
                        }
                        $final_array[$key] = $tmp;
                    }
                }
            }
            if($return==true){
                if(!empty($final_array)){
                    $response['status']=1;
                    $response['message']="List Fount";
                    $response['data']=$final_array;
                }else{
                    $response['status']=0;
                    $response['message']="No List Found";
                }
                echo json_encode($response);die;
            }else{

                $print_string = "";
                $current_appointment_id = 0;
                if(Custom::check_app_enable_permission($t_id, 'QUEUE_MANAGEMENT_APP')){
                    $res = Custom::fortisGetCurrentToken($t_id,$d_id);
                    $print_string=Custom::fortisShowResponseString($res);
                    $current_appointment_id = !empty($res['current']['appointment_id'])?$res['current']['appointment_id']:0;
                    $current_appointment_id = base64_encode($current_appointment_id);
                }
                $this->set(compact('data','thin_app_id','doctor_id','print_string','current_appointment_id'));
            }

        }else{
            die();
        }

    }

	
public function  iot_token_list_modal(){
        if ($this->request->is(array('ajax','post'))) {
            $this->layout = 'ajax';
            $data = $this->request->data;
            $doctor_id = isset($data['di']) ? base64_decode($data['di']) : "";
            $thin_app_id = isset($data['ti']) ? base64_decode($data['ti']) : "";

            $doctorData = Custom::get_doctor_by_id($doctor_id);
            $bookingCounterId = $doctorData['show_token_into_digital_tud'];
            $query = "SELECT staff.google_review_link, staff.counter_booking_type, ( select icl.call_back_status from ivr_call_log as icl where icl.appointment_id = acss.id order by icl.id desc limit 1 ) as call_status, acss.id, acss.reason_of_appointment, acss.skip_tracker, acss.queue_number, acss.status, ac.first_name as patient_name, ac.mobile as patient_mobile FROM appointment_customer_staff_services AS acss join appointment_staffs as staff on staff.id = acss.appointment_staff_id  LEFT JOIN appointment_customers AS ac ON ac.id=acss.appointment_customer_id WHERE DATE(acss.appointment_datetime) = DATE(NOW()) AND acss.appointment_staff_id = $bookingCounterId and acss.thinapp_id =$thin_app_id";

            $connection = ConnectionUtil::getConnection();
            $data =$response = $final_array =array();
            $list = $connection->query($query);
            if ($list->num_rows) {
                $data = mysqli_fetch_all($list,MYSQLI_ASSOC);
                foreach ($data as $key => $list) {
                    $tmp=array();
                    $tmp['appointment_id'] =base64_encode($list['appointment_id']);
                    $tmp['patient_name'] =$list['patient_name'];
                	$tmp['google_review_link'] =$list['google_review_link'];
                    $tmp['patient_mobile'] =$list['patient_mobile'];
                    $tmp['queue_number'] = $list['queue_number'];
                    $tmp['reason_of_appointment'] = $list['reason_of_appointment'];
                    $tmp['call_status'] = $list['call_status'];
                    $tmp['appointment_id'] = $list['id'];
                	$tmp['counter_booking_type'] = $list['counter_booking_type'];
                    if ($list['status'] == 'RESCHEDULE' || $list['status'] == 'NEW' || $list['status'] == 'CONFIRM') {
                        $status = ($list['skip_tracker'] == 'YES') ? 'Skipped' : 'Booked';
                        $tmp['status'] = ucfirst(strtolower($status));
                    }else{
                        $tmp['status'] = ucfirst(strtolower($list['status']));
                    }
                    $final_array[$key] = $tmp;
                }
            }
            $this->set(compact('final_array','thin_app_id','doctor_id'));
        }else{
            die();
        }

    }


	public function  ivr_callback(){
        if(isset( $_REQUEST['Status']) && isset( $_REQUEST['CallSid']) && isset( $_REQUEST['DateUpdated']) ){
            $Status = $_REQUEST['Status'];
            $CallSid = $_REQUEST['CallSid'];
            $DateUpdated = $_REQUEST['DateUpdated'];
            $call_back_status = 'NONE';
            if($Status=="no-answer"){
                $call_back_status = 'NO_ANSWERD';
            }else if($Status=="completed"){
                $call_back_status = 'COMPLETED';
            }
            $connection = ConnectionUtil::getConnection();
            $sql = "UPDATE ivr_call_log SET call_back_status = ?, call_end = ?, modified =? where call_sid = ?";
            $stmt = $connection->prepare($sql);
            $stmt->bind_param('ssss', $call_back_status, $DateUpdated,$DateUpdated, $CallSid);
            if ($stmt->execute()) {
                echo "SUCCESS";
            } else {
                echo "FAIL";
            }
        }
        die;

    }

    public function  mq_counter_list(){
        $this->layout = 'ajax';
        $pine_api = false;
        if ($this->request->is(array('ajax','post'))) {
            if(!isset($this->request->data['ti'])){
                $request = file_get_contents("php://input");
                $this->request->data = json_decode($request, true);
                $pine_api =true;
            }

            $data = $this->request->data;
            $thin_app_id = isset($data['ti']) ? base64_decode($data['ti']) : "";
            $json_str = isset($data['json_str']) ? $data['json_str']:"NO";
            $data = Custom::get_billing_counter_list_data($thin_app_id);
            if($json_str=='YES'){
                $return =array();
                if(!empty($data)){
                    $return['status'] = 1;
                    $return['list'] = $data;
                    $return['message'] = 'Counter list found';
                }else{
                    $return['status'] = 1;
                    $return['message'] = 'No counter list added';
                }
                echo json_encode($return);die;
            }else{
                if($pine_api==true){
                    $final =$return =array();

                    if(!empty($data)){
                        foreach ($data as $key =>$val){
                            $val['id'] = base64_encode($val['id']);
                        	$val['thinapp_id'] = base64_encode($thin_app_id);
                            $final[$key]=$val;
                        }
                        $return['status'] = 1;
                        $return['list'] = $final;
                        $return['message'] = 'Counter list found';
                    }else{
                        $return['status'] = 1;
                        $return['message'] = 'No counter list added';
                    }
                    echo json_encode($return);die;

                }else{
                    $this->set(compact('data','thin_app_id'));
                }

            }
        }else{
            die();
        }

    }

    public function update_counter_status()
    {
        $this->autoRender = false;
        if ($this->request->is(array('ajax','post'))) {
            if(!isset($this->request->data['id'])){
                $request = file_get_contents("php://input");
                $this->request->data = json_decode($request, true);
           	
            }
            $id = base64_decode($this->request->data['id']);
            $thin_app_id = base64_decode($this->request->data['t']);
            $counter = ($this->request->data['c']);
            $status = $this->request->data['s'];
            $status = (strtoupper($status)=='OPEN')?'CLOSED':'OPEN';
            $created =  Custom::created();
            $query = "update doctor_counters set status =?, modified=? where id = ?";
            $connection = ConnectionUtil::getConnection();
            $stmt = $connection->prepare($query);
            $stmt->bind_param('sss', $status, $created, $id);
            if($stmt->execute()) {
                $file_name = "doctor_counter_".$thin_app_id;
                WebservicesFunction::deleteJson(array("counter_$thin_app_id",$file_name),'counter');
                if($status=='CLOSED'){
                	$res = Custom::create_counter_log($thin_app_id, 'COUNTER_CLOSED',0,$counter);
                    $current_token = Custom::load_counter_tracker_data($thin_app_id,true);
                    if(isset($current_token[$counter]) && !empty($current_token[$counter]['appointment_id'])){
                        $appointment_id = $current_token[$counter]['appointment_id'];
                        $send_to_lab_datetime = Custom::created();
                        $checkin_type = 'DOCTOR_CHECKIN';
                    	$counter ='';
                        $sql  = "UPDATE appointment_customer_staff_services SET referred_by=?, send_to_lab_datetime = ?, patient_queue_type =? where id = ?";
                        $stmt = $connection->prepare($sql);
                        $stmt->bind_param('ssss', $counter, $send_to_lab_datetime,$checkin_type, $appointment_id);
                        $res  = $stmt->execute();
                    }
                }
            	if($status=='OPEN'){
                	$res = Custom::create_counter_log($thin_app_id, 'COUNTER_OPEN',0,$counter);
                    $current_token = Custom::load_counter_tracker_data($thin_app_id,true);
                    if(isset($current_token[$counter]) && !empty($current_token[$counter]['doctor_id'])){
                        $doctor_id = $current_token[$counter]['doctor_id'];
                        if(!empty($doctor_id) && $thin_app_id==CK_BIRLA_APP_ID){
                            $res = Custom::assign_counter_to_next_token($thin_app_id,$doctor_id);
                        }
                    }
                }
            
                $result['status'] = 1;
                $result['c_status'] = $status;
                $result['message']='Status update successfully';
            }else{
                $result['status'] = 1;
                $result['message']='Sorry, unable to update status';
            }
            echo json_encode($result);die;
        } else {
            exit();
        }

    }

    public function close_appointment()
    {
        $this->autoRender = false;
    	$send_active_token  = false;
        if ($this->request->is(array('ajax','post'))) {
            if(!isset($this->request->data['id'])){
                $request = file_get_contents("php://input");
                $this->request->data = json_decode($request, true);
                if(isset($this->request->data['id'])){
                    $appointment_id = base64_decode($this->request->data['id']);
                    $appointment_data = Custom::get_appointment_by_id($appointment_id);
                }else{
                    $thin_app_id = $this->request->data['thin_app_id'];
                    $doctor_id =$this->request->data['doctor_id'];
                    $appointment_data = Custom::counter_get_doctor_active_appointment_data($thin_app_id,$doctor_id);
                	$send_active_token  = true;
                }
            }else{
                $appointment_id = base64_decode($this->request->data['id']);
                $appointment_data = Custom::get_appointment_by_id($appointment_id);
            }
        
            if($appointment_data){
                $appointment_id = empty($appointment_id)?$appointment_data['id']:$appointment_id;
                $post = array();
                $admin_data = Custom::get_thinapp_admin_data($appointment_data['thinapp_id']);
                $post['app_key'] = APP_KEY;
                $post['user_id'] = $admin_data['id'];
                $post['thin_app_id'] = $admin_data['thinapp_id'];
                $post['appointment_id'] = $appointment_id;
                $result = json_decode(WebservicesFunction::close_appointment($post, true), true);
            	$result['current_token'] = "0";
                if ($result['status'] == 1) {
                	
                	$thin_app_id = $appointment_data['thinapp_id'];
                    $doctor_id = $appointment_data['appointment_staff_id'];
                    
                	if($send_active_token==true){
                        $appointment_data = Custom::counter_get_doctor_active_appointment_data($thin_app_id,$doctor_id);
                        
                        if(!empty($appointment_data)){
                            $result['current_token'] = $appointment_data['queue_number'];
                        }
                    }
                    $notification_array = $result['notification_array'];
                    unset($result['notification_array']);
                	if(Custom::check_app_enable_permission($thin_app_id, 'QUEUE_MANAGEMENT_APP')){
                    	$log = Custom::insertTokenLog($thin_app_id,$doctor_id,$doctor_id,"CLOSED");
                        $res = Custom::fortisGetCurrentToken($thin_app_id,$doctor_id);
                        $result['string']=Custom::fortisShowResponseString($res);
                    }
                    Custom::sendResponse($result);
                	Custom::emitSocet(array('thin_app_id'=>$thin_app_id,'doctor_id'=>$doctor_id));
                    Custom::update_tracker_time_difference($appointment_id);
                    Custom::close_appointment_notification($notification_array);
                } else {
                    Custom::sendResponse($result);
                }
            }else{
                $response['status'] = 0;
                $response['message'] = "Appointment not found";
                echo json_encode($response);die;
            }
        } else {
            exit();
        }

    }
	
	public function cancel_appointment()
    {
        $this->autoRender = false;
        if ($this->request->is(array('ajax','post'))) {
            if(!isset($this->request->data['id'])){
                $request = file_get_contents("php://input");
                $this->request->data = json_decode($request, true);
                $appointment_id = base64_decode($this->request->data['id']);
                $appointment_data = Custom::get_appointment_by_id($appointment_id);
            }else{
                $appointment_id = base64_decode($this->request->data['id']);
                $appointment_data = Custom::get_appointment_by_id($appointment_id);
            }
            if($appointment_data){
                $post = array();
                $admin_data = Custom::get_thinapp_admin_data($appointment_data['thinapp_id']);
                $post['app_key'] = APP_KEY;
                $post['user_id'] = $admin_data['id'];
                $post['thin_app_id'] = $admin_data['thinapp_id'];
                $post['appointment_id'] = $appointment_id;
                $post['cancel_by'] = "DOCTOR";
                $post['message'] = isset($this->request->data['message']) ? $this->request->data['message'] : "";
                $result = json_decode(WebservicesFunction::cancel_appointment($post, true, true), true);
                if ($result['status'] == 1) {
                	$thin_app_id = $appointment_data['thinapp_id'];
                    $doctor_id = $appointment_data['appointment_staff_id'];
                    if($appointment_data['thinapp_id']==CK_BIRLA_APP_ID){
                        $res = Custom::assign_counter_to_next_token($thin_app_id,$doctor_id);
                    }
                    $notification_array = $result['notification_array'];
                    unset($result['notification_array']);
                    Custom::sendResponse($result);
                	Custom::emitSocet(array('thin_app_id'=>$thin_app_id,'doctor_id'=>$doctor_id));
                    Custom::cancel_appointment_notification($notification_array);
                } else {
                    Custom::sendResponse($result);
                }
            }else{
                $response['status'] = 0;
                $response['message'] = "Appointment not found";
                echo json_encode($response);die;
            }
        } else {
            exit();
        }

    }

	public function skip_appointment()
    {
        $this->autoRender = false;
        if ($this->request->is(array('ajax','post'))) {
            if(!isset($this->request->data['id'])){
                $request = file_get_contents("php://input");
                $this->request->data = json_decode($request, true);
                $appointment_id = base64_decode($this->request->data['id']);
                $appointment_data = Custom::get_appointment_by_id($appointment_id);
            }else{
                $appointment_id = base64_decode($this->request->data['id']);
                $appointment_data = Custom::get_appointment_by_id($appointment_id);
            }
            if($appointment_data){
                $connection = ConnectionUtil::getConnection();
                $skip_tracker ='YES';
                $counter = '';
                $sql  = "UPDATE appointment_customer_staff_services SET referred_by=?, skip_tracker = ? where id = ?";
                $stmt = $connection->prepare($sql);
                $stmt->bind_param('sss', $counter,$skip_tracker, $appointment_id);
                if($stmt->execute()) {
                    $result['status'] = 1;
                    $result['message']='Appointment skipped successfully';
                	$thin_app_id = $appointment_data['thinapp_id'];
                    $doctor_id = $appointment_data['appointment_staff_id'];
                    $res =  Custom::updateTokenOnAction($thin_app_id,$doctor_id,$appointment_data['queue_number']);
                	Custom::emitSocet(array('thin_app_id'=>$thin_app_id,'doctor_id'=>$doctor_id));
                
                }else{
                    $result['status'] = 1;
                    $result['message']='Sorry, appointment could not skipped';
                }
                echo json_encode($result);die;
            }else{
                $response['status'] = 0;
                $response['message'] = "Appointment not found";
                echo json_encode($response);die;
            }
        } else {
            exit();
        }
    }
	
	public function edit_patient_detail()
    {
        $this->autoRender = false;
        if ($this->request->is(array('ajax','post'))) {
			
        	if(!$this->request->data['ai']){
                $request = file_get_contents("php://input");
                $this->request->data = json_decode($request, true);
            }
        
            $appointment_id = base64_decode($this->request->data['ai']);
            $patient_name = ($this->request->data['pn']);
            $patient_mobile = Custom::create_mobile_number($this->request->data['pm']);
            $appointment_data = Custom::get_appointment_by_id($appointment_id);
            if (empty($patient_name)) {
                $response['status'] = 0;
                $response['message'] = 'Please enter  name';
            } else if (empty($patient_mobile)) {
                $response['status'] = 0;
                $response['message'] = 'Enter patient 10 digit mobile number';
            }else{
                if($appointment_data){

                    $connection = ConnectionUtil::getConnection();
                    $patient_id = $appointment_data['appointment_customer_id'];
                    $thin_app_id = $appointment_data['thinapp_id'];

                    $customer_data = Custom::get_customer_data($patient_id, $thin_app_id);
                    if ($customer_data) {
                        $search_customer_data = Custom::search_customer_name($thin_app_id, $patient_mobile, $patient_name);
                        $patient_id = !empty($search_customer_data)?$search_customer_data['id']:$patient_id;
                    }
                    $sql  = "UPDATE appointment_customer_staff_services SET appointment_customer_id=?, appointment_patient_name=? where id = ?";
                    $stmt = $connection->prepare($sql);
                    $stmt->bind_param('sss', $patient_id, $patient_name, $appointment_id);

                    $sql  = "UPDATE appointment_customers SET first_name=?, mobile = ? where id = ?";
                    $stmt_pat = $connection->prepare($sql);
                    $stmt_pat->bind_param('sss', $patient_name,$patient_mobile, $patient_id);
                    if($stmt->execute() && $stmt_pat->execute()) {
                        $result['status'] = 1;
                        $result['message']='Patient update successfully';
                        $doctor_id = $appointment_data['appointment_staff_id'];
                        $file_name = "fortis_tracker_".$doctor_id."_".date('Y-m-d');;
                        if(Custom::check_app_enable_permission($thin_app_id, 'QUEUE_MANAGEMENT_APP')){
                            $current_token = Custom::fortisGetCurrentToken($thin_app_id,$doctor_id);
                            if(empty($token_number) || ($current_token['current']['appointment_id']==$appointment_id)){
                                $current_token['current']['patient_name']= $patient_name;
                                WebservicesFunction::createJson($file_name,json_encode($current_token),"CREATE","fortis");
                            }
                        }
                    }else{
                        $result['status'] = 1;
                        $result['message']='Sorry, appointment could not skipped';
                    }
                    echo json_encode($result);die;
                }else{
                    $response['status'] = 0;
                    $response['message'] = "Appointment not found";

                }
            }

            echo json_encode($response);die;

        } else {
            exit();
        }
    }

    public function un_skip_appointment()
    {
        $this->autoRender = false;
        if ($this->request->is(array('ajax','post'))) {
            if(!isset($this->request->data['id'])){
                $request = file_get_contents("php://input");
                $this->request->data = json_decode($request, true);
                $appointment_id = base64_decode($this->request->data['id']);
                $appointment_data = Custom::get_appointment_by_id($appointment_id);
            }else{
                $appointment_id = base64_decode($this->request->data['id']);
                $appointment_data = Custom::get_appointment_by_id($appointment_id);
            }
            if($appointment_data){
                $connection = ConnectionUtil::getConnection();
                $skip_tracker ='NO';
                $sql  = "UPDATE appointment_customer_staff_services SET skip_tracker = ? where id = ?";
                $stmt = $connection->prepare($sql);
                $stmt->bind_param('ss', $skip_tracker, $appointment_id);
                if($stmt->execute()) {
                    $result['status'] = 1;
                    $result['message']='Appointment un-skip successfully';
                }else{
                    $result['status'] = 1;
                    $result['message']='Sorry, appointment could not un-skip';
                }
                echo json_encode($result);die;
            }else{
                $response['status'] = 0;
                $response['message'] = "Appointment not found";
                echo json_encode($response);die;
            }
        } else {
            exit();
        }

    }

   public function send_to_billing_counter()
    {
        $this->autoRender = false;
   		$send_active_token  = false;
        $thin_app_id = $doctor_id = 0;
        if ($this->request->is(array('ajax','post'))) {
            if(!isset($this->request->data['id'])){
                $request = file_get_contents("php://input");
                $this->request->data = json_decode($request, true);
                if(isset($this->request->data['id'])){
                    $appointment_id = base64_decode($this->request->data['id']);
                    $appointment_data = Custom::get_appointment_by_id($appointment_id);
                }else{
                    $thin_app_id = $this->request->data['thin_app_id'];
                    $doctor_id =$this->request->data['doctor_id'];
                    $appointment_data = Custom::counter_get_doctor_active_appointment_data($thin_app_id,$doctor_id);
                	
                	$send_active_token = true;
                }
            }else{
                $appointment_id = base64_decode($this->request->data['id']);
                $appointment_data = Custom::get_appointment_by_id($appointment_id);
            }
        	$result['current_token'] = "0";
            if($appointment_data){
            	$appointment_id = empty($appointment_id)?$appointment_data['id']:$appointment_id;
                $connection = ConnectionUtil::getConnection();
                $skip_tracker ='NO';
                $counter_data  =  Custom::get_ck_birla_less_queue_counter($appointment_data['thinapp_id'],0);
                $counter = $counter_data['counter'];
                if(!empty($counter)){
                    $checkin_type = 'BILLING_CHECKIN';
                    $send_to_lab_datetime = Custom::created();
                    $sql  = "UPDATE appointment_customer_staff_services SET send_to_lab_datetime = ?, referred_by =?, patient_queue_type =? where id = ?";
                    $stmt = $connection->prepare($sql);
                    $stmt->bind_param('ssss', $send_to_lab_datetime,$counter,$checkin_type,  $appointment_id);
                    if($stmt->execute()) {
                    	$res = Custom::create_counter_log($appointment_data['thinapp_id'], 'SEND_TO_BILLING_COUNTER',$appointment_id,$counter);
                        
                    	if($send_active_token==true){
                            $appointment_data = Custom::counter_get_doctor_active_appointment_data($thin_app_id,$doctor_id);
                            $result['current_token'] = "";
                            if(!empty($appointment_data)){
                                $result['current_token'] = $appointment_data['queue_number'];
                            }
                        }
                    
                        $result['status'] = 1;
                        $result['message']='Token sent to billing counter successfully';
                    }else{
                        $result['status'] = 0;
                        $result['message']='Sorry, unable to send appointment to billing counter';
                    }
                }else{
                    $result['status'] = 0;
                    $result['message']='All billing counters are closed';
                }

                echo json_encode($result);die;
            }else{
                $result['status'] = 0;
                $result['message'] = "Appointment not found";
                echo json_encode($result);die;
            }
        } else {
            exit();
        }

    }

	public function get_doctor_current_token()
    {
        $request = file_get_contents("php://input");
        $this->request->data = json_decode($request, true);
        $thin_app_id = $this->request->data['thin_app_id'];
        $doctor_id =$this->request->data['doctor_id'];
        $appointment_data = Custom::counter_get_doctor_active_appointment_data($thin_app_id,$doctor_id);
        if(!empty($appointment_data)){
            $response['status'] = 1;
            $response['message'] = "Current Token Found";
            $response['current_token'] = $appointment_data['queue_number'];
        }else{
            $response['status'] = 0;
            $response['message'] = "Current token not available";
        	$response['current_token'] = "0" ;
        }
        echo json_encode($response);die;


    }

    public function assign_counter()
    {
        $this->autoRender = false;
        if ($this->request->is(array('ajax','post'))) {
            if(!isset($this->request->data['id'])){
                $request = file_get_contents("php://input");
                $this->request->data = json_decode($request, true);
            }
            $appointment_id = base64_decode($this->request->data['ai']);
            $counter = ($this->request->data['c']);
            $counter_type = ($this->request->data['ct']);
            $appointment_data = Custom::get_appointment_by_id($appointment_id);
            if($appointment_data){
                $connection = ConnectionUtil::getConnection();
                if(!empty($counter)){
                    $checkin_type = 'NONE';
                    if($counter_type=='BILLING'){
                        $checkin_type = 'BILLING_CHECKIN';
                    }


                    $send_to_lab_datetime = Custom::get_manual_assign_token_queue_datetime($appointment_data['thinapp_id'],$appointment_data['appointment_staff_id'],$counter);
                    if(!empty($send_to_lab_datetime)){
                        //$send_to_lab_datetime = Custom::created();

                        $skip_tracker = 'NO';
                        $sql  = "UPDATE appointment_customer_staff_services SET skip_tracker =?, send_to_lab_datetime = ?, referred_by =?, patient_queue_type =? where id = ?";
                        $stmt = $connection->prepare($sql);
                        $stmt->bind_param('sssss', $skip_tracker, $send_to_lab_datetime,$counter,$checkin_type,  $appointment_id);
                        if($stmt->execute()) {
                            $result['status'] = 1;
                            $result['message']='Appointment sent to billing counter successfully';
                            $thin_app_id = $appointment_data['thinapp_id'];
                            $doctor_id = $appointment_data['appointment_staff_id'];
                            if($appointment_data['thinapp_id']==CK_BIRLA_APP_ID){
                                $res = Custom::create_counter_log($thin_app_id, 'ASSIGN_COUNTER',$appointment_id,$counter,'MANUAL ASSIGN');
                                $res = Custom::assign_counter_to_next_token($thin_app_id,$doctor_id);
                            }
                        }else{
                            $result['status'] = 0;
                            $result['message']='Sorry, unable to send appointment to billing counter';
                        }
                    }else{
                        $result['status'] = 0;
                        $result['message']='Sorry, You can only assign one counter after current token, You can try again after skip the current token';
                    }
                }else{
                    $result['status'] = 0;
                    $result['message']='All billing counters are closed';
                }

                echo json_encode($result);die;
            }else{
                $response['status'] = 0;
                $response['message'] = "Appointment not found";
                echo json_encode($response);die;
            }
        } else {
            exit();
        }
    }

    public function send_to_doctor()
    {
        $this->autoRender = false;
        if ($this->request->is(array('ajax','post'))) {
            if(!isset($this->request->data['id'])){
                $request = file_get_contents("php://input");
                $this->request->data = json_decode($request, true);
                $appointment_id = base64_decode($this->request->data['id']);
                $appointment_data = Custom::get_appointment_by_id($appointment_id);
            }else{
                $appointment_id = base64_decode($this->request->data['id']);
                $appointment_data = Custom::get_appointment_by_id($appointment_id);
            }
            if($appointment_data){
                $connection = ConnectionUtil::getConnection();
                $send_to_lab_datetime = Custom::created();
                $checkin_type = 'DOCTOR_CHECKIN';
                $counter = '';
                $sql  = "UPDATE appointment_customer_staff_services SET referred_by=?, send_to_lab_datetime = ?, patient_queue_type =? where id = ?";
                $stmt = $connection->prepare($sql);
                $stmt->bind_param('ssss', $counter, $send_to_lab_datetime,$checkin_type, $appointment_id);
                if($stmt->execute()) {
                    $result['status'] = 1;
                    $result['message']='Patient sent to doctor successfully';
                    $thin_app_id = $appointment_data['thinapp_id'];
                    $doctor_id = $appointment_data['appointment_staff_id'];
                    if($appointment_data['thinapp_id']==CK_BIRLA_APP_ID){
                        $thin_app_id = $appointment_data['thinapp_id'];
                        $doctor_id = $appointment_data['appointment_staff_id'];
                        $res = Custom::assign_counter_to_next_token($thin_app_id,$doctor_id);
                    	$res = Custom::create_counter_log($thin_app_id, 'SEND_TO_DOCTOR',$appointment_id);
                    }
                }else{
                    $result['status'] = 1;
                    $result['message']='Sorry, unable to sent patient to the doctor';
                }
                echo json_encode($result);die;
            }else{
                $response['status'] = 0;
                $response['message'] = "Appointment not found";
                echo json_encode($response);die;
            }
        } else {
            exit();
        }

    }

   public function edit_patient()
    {
        $this->autoRender = false;
        if ($this->request->is('ajax')) {
            $patient_id = base64_decode($this->request->data['pi']);
            $appointment_id = base64_decode($this->request->data['ai']);
            $patient_name = ($this->request->data['pn']);
            $patient_data= Custom::get_customer_by_id($patient_id);
            if($patient_data){
                $patient_mobile = $patient_data['mobile'];
                $thin_app_id = $patient_data['thinapp_id'];
                $search_patient =Custom::get_customer_by_name($thin_app_id,$patient_name,$patient_mobile);
                $created= Custom::created();
                if(empty($search_patient) || $search_patient['id'] == $patient_id){
                    if(!empty($search_patient)){
                        $patient = $search_patient;
                    }else{
                        $patient = $patient_data;
                    }
                    $connection = ConnectionUtil::getConnection();
                    $connection->autocommit(false);
                    $folder_id =$patient['folder_id'];
                    $query = "update appointment_customers set first_name =?, modified =? where id = ?";
                    $stmt_patient = $connection->prepare($query);
                    $stmt_patient->bind_param('sss', $patient_name, $created, $patient_id);
                    $query = "update drive_folders set folder_name=?, modified =? where id = ?";
                    $stmt_folder = $connection->prepare($query);
                    $stmt_folder->bind_param('sss', $patient_name, $created, $folder_id);

                    $query = "update appointment_customer_staff_services set appointment_patient_name=?, modified =? where id = ?";
                    $stmt_app = $connection->prepare($query);
                    $stmt_app->bind_param('sss', $patient_name, $created, $appointment_id);

                    if ($stmt_patient->execute() && $stmt_folder->execute() && $stmt_app->execute()) {
                        $connection->commit();
                        $response['status'] = 1;
                        $response['message'] = "Patient name update successfully";
                        echo json_encode($response);die;
                    }
                }else{
                    $response['status'] = 0;
                    $response['message'] = "Patient already register with '$patient_name' name";
                    echo json_encode($response);die;
                }
            }else{
                $response['status'] = 0;
                $response['message'] = "This is invalid patient";
                echo json_encode($response);die;
            }
        } else {
            exit();
        }

    }

	public function proforma_invoice($pi)
    {
        $this->layout = 'app_admin_home';

            $proforma_invoice = base64_decode($pi);
            $query = "select * from proforma_invoices as pi where pi.id =$proforma_invoice and status = 'ACTIVE' limit 1";
            $connection = ConnectionUtil::getConnection();
            $data = $connection->query($query);
            if ($data->num_rows) {
                $data = mysqli_fetch_assoc($data);
                $doctor_id = $data['doctor_id'];
                $thinappID = $data['thinapp_id'];
                $pharmacist = $this->Session->read('pharmacist');
                if(!empty($pharmacist)){

                    $appointment_customer_id = $data['appointment_customer_id'];
                    $children_id = $data['children_id'];
                    $drive_file_id = $data['drive_file_id'];

                    $userdata = $this->AppointmentStaff->find("first", array(
                        "conditions" => array(
                            "AppointmentStaff.id" =>($doctor_id),
                        ),
                        'contain' => array('Thinapp' => array('Leads'), 'User'),
                        'order' => array('AppointmentStaff.id' => 'desc')
                    ));

                    $get_user_id = !empty($userdata['User']['id']) ? $userdata['User']['id'] : 0;
                    if ($get_user_id == 0 && !empty($userdata['AppointmentStaff'])) {
                        $new_user_id = $get_user_id = Custom::create_user($userdata['AppointmentStaff']['thinapp_id'], $userdata['AppointmentStaff']['mobile'], $userdata['AppointmentStaff']['name']);
                        $this->AppointmentStaff->updateAll(array('AppointmentStaff.user_id' => $new_user_id), array('AppointmentStaff.id' => $userdata['AppointmentStaff']['id']));
                    }
                    $userdata = $this->User->find("first", array(
                        "conditions" => array(
                            "User.id" => $get_user_id,
                            "AppointmentStaff.id" => $doctor_id
                        ),
                        'contain' => array('Leads', 'Thinapp', 'AppointmentStaff')
                    ));
                    $userdata['USER_ROLE'] = 'DOCTOR';
                    $login =$userdata;
                    $patientData = $this->AppointmentCustomer->find("first", array("conditions" => array("AppointmentCustomer.id" => $appointment_customer_id, "AppointmentCustomer.thinapp_id" => $thinappID), "contain" => false));
                    if (empty($appointment_customer_id)) {
                        $patientData = $this->Children->find("first", array("conditions" => array("Children.id" => $children_id, "Children.thinapp_id" => $thinappID), "contain" => false));
                    } else {
                        $dob = $patientData['AppointmentCustomer']['dob'];
                        if (!empty($dob) && $dob != '1970-01-01' && $dob != '0000-00-00') {
                            $age = Custom::dob_elapsed_string($dob, false, false);
                            if ($age['year'] > 0) {
                                $age = $age['year'] . 'Y';
                            } else if ($age['month'] > 0) {
                                $age = $age['month'] . 'M';
                            } else {
                                $age = $age['day'] . 'D';
                            }
                        } else {
                            $age = $patientData['AppointmentCustomer']['age'];
                        }
                        $patientData['AppointmentCustomer']['age'] = $age;
                    }
                    $condition2 = array("HospitalPaymentType.thinapp_id" => $thinappID);
                    $hospitalPaymentType = $this->HospitalPaymentType->find("list", array("conditions" => $condition2));
                    $file_path='';
                    $query = "select file_path from drive_files as d_file where d_file.id=$drive_file_id";
                    $connection = ConnectionUtil::getConnection();
                    $list_obj = $connection->query($query);
                    if ($list_obj->num_rows) {
                        $file_path = mysqli_fetch_assoc($list_obj)['file_path'];
                    }
                    $doctor_id = base64_encode($doctor_id);
                    $proforma_invoice = base64_encode($proforma_invoice);
                    $pharmacist = $pharmacist['LabPharmacyUser'];
                    $this->set(compact('pharmacist','login','file_path','thinappID','doctor_id','patientData', 'hospitalPaymentType','proforma_invoice'));

                }else{
                    $url = SITE_PATH."homes/pharmacist/".base64_encode($thinappID);
                    header("Location: ".$url);
                    die;
                }
            } else {
                die('Invalid Request');
            }



    }

    public function patient_proforma_invoice($proforma_invoice_id,$order_node=false,$view_by_camist=false)
    {
        $this->layout = false;
        $invoiceData = array();
        $proforma_invoice_id = base64_decode($proforma_invoice_id);
        $txStatus ='NONE';
        if(!empty($order_node)){
            $txStatus = $_POST['txStatus'];
            $referenceId = $_POST['referenceId'];
            if($txStatus == 'SUCCESS'){
                $orderId = $_POST['orderId'];
                $orderAmount = $_POST['orderAmount'];
                $paymentMode = $_POST['paymentMode'];
                $txMsg = $_POST['txMsg'];
                $txTime = $_POST['txTime'];
                $created = Custom::created();
                $query = "update proforma_invoices set  cashfree_order_id=?, reference_id=?, tx_status=?, payment_mode=?, payment_datetime=?, tx_msg=?, tx_time=?, modified=? where id = ?";
                $connection = ConnectionUtil::getConnection();
                $stmt = $connection->prepare($query);
                $stmt->bind_param('sssssssss', $orderId, $referenceId, $txStatus, $paymentMode,$created,$txMsg, $txTime, $created, $proforma_invoice_id);
                $stmt->execute();
            }else{
                $query = "update proforma_invoices set transaction_id =?, tx_status=?, payment_datetime=?, modified=? where id = ?";
                $connection = ConnectionUtil::getConnection();
                $stmt = $connection->prepare($query);
                $stmt->bind_param('sssss', $referenceId,  $txStatus, $created, $proforma_invoice_id);
                $stmt->execute();
            }
        }

        if ($proforma_invoice_id){
            $query = "SELECT pi.invoice_order_id, pi.delivery_charges,  pi.thinapp_id, pi.patient_mobile,  pi.pharmacist_address, lpu.mobile as chemist_mobile, lpu.name AS chemist_name,  pi.chemist_lng, pi.chemist_lat, pi.payment_datetime, pi.patient_response, pi.delivery_address, pi.id as proforma_id, pi.tx_status, pi.payment_datetime, pi.amount as paid_amount, mpo.receipt_id, mpo.is_refunded, t.show_time_on_receipt, t.show_date_on_receipt, t.show_paid_user_order_number_on_receipt, mpo.paid_receipt_number, t.show_department_on_receipt, t.show_patient_mobile_on_receipt, mpq.expiry_date, mpo.bill_id, mpq.batch, t.show_token_on_receipt, t.show_token_time_on_receipt, t.show_doctor_on_receipt, mpo.reffered_by_name, t.show_referrer_on_receipt, mpo.total_amount as total_paid, 'YES' AS show_into_receipt, mpo.payment_status, mpo.hospital_ipd_id, mpo.is_package, mpo.id as medical_product_id, mpod.id, mpod.created, mpod.thinapp_id, mpod.tax_value, mpod.discount_amount, IFNULL(ac.address,c.patient_address) as patient_address, IFNULL(ac.height,c.height) as height, IFNULL(ac.weight,c.weight) as weight, IFNULL(ac.gender,c.gender) as gender, IFNULL(hpt.name,'Cash') as payment_type_name, IFNULL(ac.uhid,c.uhid) as uhid, mpo.created as billing_date, IFNULL(ac.relation_prefix,c.relation_prefix) as relation_prefix, IFNULL(ac.parents_name,c.parents_name) as parents_name, IFNULL(mp.name,service) as service_name, mpod.product_price, mpod.total_amount, mpod.tax_type, mpod.quantity, mpo.created AS created , t.name as app_name, t.logo, t.receipt_top_left_title, t.receipt_header_title, t.receipt_footer_title, app_staff.name as consult_with, CONCAT(IFNULL(ac.title,c.title),' ',IFNULL(ac.first_name,c.child_name)) as patient_name, IFNULL(ac.mobile,c.mobile) as mobile, IFNULL(IF(ac.dob !='0000-00-00' AND ac.dob !='' ,ac.dob, age),c.dob) as age FROM medical_product_orders as mpo join proforma_invoices as pi on pi.medical_product_order_id = mpo.id join lab_pharmacy_users as lpu on lpu.id = pi.lab_pharmacy_user_id  LEFT join appointment_customers as ac on ac.id = mpo.appointment_customer_id left join childrens as c on c.id = mpo.children_id left join hospital_payment_types as hpt on hpt.id = mpo.hospital_payment_type_id left join medical_product_order_details as mpod on mpod.medical_product_order_id = mpo.id left join medical_product_quantities as mpq on mpod.medical_product_quantity_id = mpq.id left join appointment_staffs as app_staff on app_staff.id = mpo.appointment_staff_id  left join medical_products as mp on mp.id = mpod.medical_product_id  join thinapps as t on t.id = mpo.thinapp_id where pi.id = $proforma_invoice_id and mpo.status = 'ACTIVE' GROUP BY mpod.id";
            $connection = ConnectionUtil::getConnection();
            $list_obj = $connection->query($query);
            if ($list_obj->num_rows) {
                $invoiceData = mysqli_fetch_all($list_obj, MYSQLI_ASSOC);
            }
        }
        $invoice_type ='IPD';
        $delivery_charges =DELIVERY_CHARGES;
        if(!empty($invoiceData)){
            $proforma_invoice_id = base64_encode($proforma_invoice_id);
            $this->set(compact('delivery_charges','invoiceData','invoice_type','proforma_invoice_id','order_node','txStatus','view_by_camist'));
        }
        else{
            die('Invalid Request');
        }
    }

    public function send_invoice_alert($proforma_invoice_id)
    {
        $this->layout = false;
        $proforma_invoice_id = base64_decode($proforma_invoice_id);
        $txStatus = $_POST['txStatus'];
        if($txStatus == 'SUCCESS'){
            if ($proforma_invoice_id){
                $query = "SELECT pi.thinapp_id, pi.invoice_order_id,  pi.patient_mobile, lpu.mobile AS cahemist_number from proforma_invoices AS pi JOIN lab_pharmacy_users AS lpu ON lpu.id = pi.lab_pharmacy_user_id  where pi.id = $proforma_invoice_id limit 1";
                $connection = ConnectionUtil::getConnection();
                $list_obj = $connection->query($query);
                if ($list_obj->num_rows) {
                    $invoiceData = mysqli_fetch_assoc($list_obj);
                    $thin_app_id = $invoiceData['thinapp_id'];
                    $patient_mobile = $invoiceData['patient_mobile'];
                    $cahemist_number = $invoiceData['cahemist_number'];
                    $invoice_order_number = $invoiceData['invoice_order_id'];
                    $support = Custom::create_mobile_number(SUPPORT_MOBILE);
                    $pi = base64_encode($proforma_invoice_id);
                    $order_link = Custom::short_url(SITE_PATH . "homes/patient_proforma_invoice/$pi");
                    $message = "Your order number $invoice_order_number has been sent further for processing.You will receive the message of order delivery.\nPayment receipt\n$order_link";
                    $res = Custom::send_single_sms($patient_mobile, $message, $thin_app_id,false,false);
                    $message = "Order number $invoice_order_number payment has received. Please sent further for processing this order.\nOrder Invoice\n$order_link";
                    $res = Custom::send_single_sms($cahemist_number, $message, $thin_app_id,false,false);
                    $res = Custom::send_single_sms($support, $message, $thin_app_id,false,false);
                }
            }
        }
        die('Ok');
    }

    public function decline_proforma()
    {
        $this->layout = 'ajax';
        if($this->request->is('ajax')) {
            $proforma_invoice_id = base64_decode($this->request->data['pi']);
            $patient_response = 'DECLINE';
            $query = "update proforma_invoices set patient_response =?, modified=? where id = ?";
            $connection = ConnectionUtil::getConnection();
            $stmt = $connection->prepare($query);
            $stmt->bind_param('sss', $patient_response,  $created, $proforma_invoice_id);
            if($stmt->execute()){
                $response['status']=1;
                $response['message']='Request decline successfully';
            }else{
                $response['status']=0;
                $response['message']='Unable to decline request';
            }
            echo json_encode($response);die;
        }else{
            die();
        }



    }

    public function pharmacist($thin_app_id)
    {
       $this->layout = false;
       $thin_app_id= base64_decode($thin_app_id);
       $app_data = Custom::get_thinapp_data($thin_app_id);
       if ($this->request->is(array('post', 'put'))) {
           $mobile =  Custom::create_mobile_number($this->request->data['User']['mobile']);
           $password =  $this->request->data['User']['password'];
            $user = $this->LabPharmacyUser->find("first", array(
                "conditions" => array(
                    "LabPharmacyUser.thinapp_id" => $thin_app_id,
                    "LabPharmacyUser.mobile" => $mobile,
                    "LabPharmacyUser.password" => md5($password),
                    "LabPharmacyUser.status" => "ACTIVE",
                ),
                'contain' => false
            ));
            if(!empty($user)){
                $this->Session->write('pharmacist', $user);
            }else{
                $message = "Invalid mobile or password";
                $this->Session->setFlash(__($message), 'default', array(), 'warning');
            }
        }
       $pharmacist = $this->Session->read('pharmacist');
        $thin_app_id= base64_encode($thin_app_id);
       $this->set(compact('pharmacist','app_data','thin_app_id'));
    }

    public function load_proforma_invoice()
    {
        $this->layout = false;

        $thinappID = base64_decode($this->request->data['t']);
        $pat_res = @$this->request->data['pat_res'];
        $payment_status = @$this->request->data['ps'];
        $delivery_status = @$this->request->data['d_staus'];
        $invoice_status = @$this->request->data['is'];
        $from_date = $this->request->data['fd'];
        $to_date = $this->request->data['td'];

        $from_date = DateTime::createFromFormat('d/m/Y', $from_date);
        $from_date = $from_date->format('Y-m-d');

        $to_date = DateTime::createFromFormat('d/m/Y', $to_date);
        $to_date = $to_date->format('Y-m-d');


        $condition = " and DATE(inv.created) BETWEEN '$from_date' AND '$to_date'";
        if(!empty($pat_res)){
            $condition .= " and inv.patient_response='$pat_res'";
        }
        if(!empty($payment_status)){
            $condition .= " and inv.tx_status='$payment_status'";
        }

        if(!empty($delivery_status)){
            $condition .= " and inv.deliverd_staus='$delivery_status'";
        }

        if(!empty($invoice_status)){
            if($invoice_status > 0){
                $condition .= " and inv.medical_product_order_id > 0";
            }else{
                $condition .= " and inv.medical_product_order_id = 0";
            }

        }


        $query = "SELECT inv.invoice_order_id, inv.created, inv.medical_product_order_id,  inv.deliverd_staus,  inv.patient_name, inv.patient_mobile,  inv.id, lab_pharmacy_user_id, inv.delivery_address,inv.amount, IF(inv.patient_response='NONE','NO Action',inv.patient_response) AS patient_response, IF(inv.tx_status='NONE','PENDING',inv.tx_status) as tx_status, inv.created, inv.delivery_address  FROM proforma_invoices AS inv WHERE inv.thinapp_id = $thinappID AND inv.`status`='ACTIVE' $condition ORDER BY inv.id desc ";
        $connection = ConnectionUtil::getConnection();
        $list = $connection->query($query);
        $inv_list =array();
        if ($list->num_rows) {
            $inv_list = mysqli_fetch_all($list, MYSQLI_ASSOC);
        }
        $this->set(compact('inv_list'));
    }

	public function  flags($appointment_id){
        $this->layout =false;
        $appointment_id =base64_decode($appointment_id);
        $query = "select acss.id as appointment_id, acss.notes, acss.appointment_staff_id, IFNULL(ac.medical_history,c.medical_history) as medical_history, IFNULL(ac.o_saturation,c.o_saturation) as o_saturation, IFNULL(ac.temperature,c.temperature) as temperature, IFNULL(ac.bp_systolic,c.bp_systolic) as bp_systolic, IFNULL(ac.weight,c.weight) as weight, IFNULL(ac.height,c.height) as height, IFNULL(ac.first_name,c.child_name) as name, acss.appointment_customer_id,acss.children_id, acss.id,IFNULL(ac.flag,c.flag) as flag,IFNULL(ac.allergy,c.allergy) as allergy,IFNULL(ac.medical_history,c.medical_history) as history  from appointment_customer_staff_services as acss left join appointment_customers as ac on ac.id=acss.appointment_customer_id left join childrens as c on c.id = acss.children_id where acss.id =$appointment_id limit 1";
        $connection = ConnectionUtil::getConnection();
        $data = $connection->query($query);
        if ($data->num_rows) {
            $data = mysqli_fetch_assoc($data);
            $doctor_data = Custom::get_doctor_by_id($data['appointment_staff_id']);
            $post['app_key'] = APP_KEY;
            $post['user_id'] =$doctor_data['user_id'];
            $post['mobile'] =$doctor_data['mobile'];
            $post['thin_app_id'] =$doctor_data['thinapp_id'];
            $post['category_id'] = 1;
            $post['doctor_id'] =  $doctor_data['id'];
            $steps_list =  json_decode(WebServicesFunction_2_3::tab_get_category_step($post),true)['data']['step_list'];
            $symptoms = implode(array_column($steps_list[0]['tag_list'],'tag_name'),',');
            $duration = implode(array_column($steps_list[1]['tag_list'],'tag_name'),',');
            $this->set(compact('data','symptoms','duration'));
        }else{
            echo "Invalid request";die;
        }
    }

    public function  load_flag_data($type){

	    $return =array();
	    if($type=='flag'){
	        $return=array('Dangu','Maleriya');
        }else if($type=='allergy'){
            $return=array('Diabetes', 'Sugar', 'Alcohol', 'Blood Pressure(B.P)');
        }
        echo json_encode($return);die;

    }

    public function save_flag()
    {
        $this->autoRender = false;
        if ($this->request->is('ajax')) {
            $appointment_id = base64_decode($this->request->data['ai']);
            $flags = ($this->request->data['flags']);
            $allergy = ($this->request->data['allergy']);
            $symptoms = ($this->request->data['symptoms']);
            $duration = ($this->request->data['duration']);
            $history = ($this->request->data['history']);
            $height = ($this->request->data['height']);
            $weight = ($this->request->data['weight']);
            $bp_systolic = ($this->request->data['bp_systolic']);
            $temperature = ($this->request->data['temperature']);
            $o_saturation = ($this->request->data['o_saturation']);

            $ac = @($this->request->data['ac']);
            $c = @($this->request->data['c']);
            $connection = ConnectionUtil::getConnection();
            $created = Custom::created();
            $notes = $symptoms."###".$duration;
            if(!empty($ac)){
                $query = "update appointment_customers set height=?, weight=?,bp_systolic=?,temperature=?,  o_saturation=?,  flag =?, allergy=?, medical_history=?, modified =? where id = ?";
                $stmt_patient = $connection->prepare($query);
                $stmt_patient->bind_param('ssssssssss',  $height, $weight, $bp_systolic, $temperature, $o_saturation, $flags, $allergy, $history, $created, $ac);
            }else{
                $query = "update childrens set height=?, weight=?,bp_systolic=?,temperature=?,  o_saturation=?,  flag =?, allergy=?, medical_history=?, modified =? where id = ?";
                $stmt_patient = $connection->prepare($query);
                $stmt_patient->bind_param('ssssssssss', $height, $weight, $bp_systolic, $temperature, $o_saturation, $flags, $allergy, $history, $created, $c);
            }

            $query = "update appointment_customer_staff_services set notes=?  where id = ?";
            $stmt_app = $connection->prepare($query);
            $stmt_app->bind_param('ss',  $notes,$appointment_id);

            if ($stmt_patient->execute() && $stmt_app->execute()) {
                $response['status'] = 1;
                $response['message'] = "Information update successfully";
                echo json_encode($response);die;
            }
        } else {
            exit();
        }

    }


	public function load_doctor_time_slot()
    {
        $this->layout = 'ajax';
        $this->autoRender = false;
        if ($this->request->is(array('ajax','POST'))) {
        
        	if(!$this->request->data['ti']){
                $request = file_get_contents("php://input");
                $this->request->data = json_decode($request, true);
            }
        
            $post['app_key'] = APP_KEY;
            $thin_app_id =base64_decode($this->request->data['ti']);
            $row = Custom::get_thinapp_admin_data($thin_app_id);
            $post['role_id'] = 1;//$row['role_id'];
            $post['user_id'] = $row['id'];
            $post['thin_app_id'] = $row['thinapp_id'];
            $post['mobile'] = $row['mobile'];
            $post['doctor_id'] = base64_decode($this->request->data['di']);
            $post['address_id'] = base64_decode($this->request->data['ai']);
            $post['booking_date'] = $booking_date = date('Y-m-d');
            $post['service_slot_duration'] = $this->request->data['dur'];
            $post['appointment_user_role'] = 'RECEPTIONIST';
            $response = json_decode(WebservicesFunction::get_doctor_time_slot($post, true),true);
        	if(Custom::check_app_enable_permission($thin_app_id, 'SINGLE_TOKEN_BOOKING_APP')){
                $slotList = $response['data']['slot_list'];
                $bookedTokenList = Custom::get_all_booked_token_of_app($thin_app_id);
                $tmpArray =array();
                foreach ($slotList as $slot => $slot_av_data){
                    if(!array_key_exists($slot_av_data['queue_number'],$bookedTokenList)){
                        $tmpArray[] = $slot_av_data;
                    }
                }
                $response['data']['slot_list'] = $tmpArray;
            }
            if($response['status']==1){
                unset($response['data']['day_list']);
                unset($response['data']['blocked_slot']);
            }
            echo  json_encode($response);die;



        } else {
            exit();
        }

    }

	public function subscribe(){
        $this->layout = false;
        if ($this->request->is(array('post', 'put'))) {

            echo "<pre>";
            $data_array = $this->request->data;
            $mobile_number = $data_array['mobile_number'];
            $app_name = $data_array['clinic_name'];
            $doctor_name = $data_array['doctor_name'];
            $email = $data_array['email'];




            print_r($data_array);die;
        }

        $hours_list =array();
        for ($i = 0; $i < 7; $i++) {
            $day_name = jddayofweek($i,1);
            $hours_list[] =array('number'=>$i+1,'status'=>'OPEN','day'=>$day_name,'time_from'=>'08:00 AM','time_to'=>'08:00 PM');
        }
        $this->set(compact('hours_list'));


    }

    public function create_app(){
        $this->autoRender = false;
        if ($this->request->is('ajax')) {

            $data_array = $this->request->data;
            $clinic_name = trim($data_array['clinic_name']);
            $mobile_number = Custom::create_mobile_number($data_array['mobile_number']);
            $doctor_name = trim($data_array['doctor_name']);
            $email = $data_array['email'];
            $department_category_id = $data_array['department_category_id'];

            $service_name = $data_array['service']['name'];
            $offline = $data_array['service']['offline'];
            $video = $data_array['service']['video'];
            $audio = $data_array['service']['audio'];
            $chat = $data_array['service']['chat'];

            $address = $data_array['address'];
            $country_id = !empty($data_array['country_id'])?$data_array['country_id']:0;
            $state_id = !empty($data_array['state_id'])?$data_array['state_id']:0;;
            $city_id = !empty($data_array['city_id'])?$data_array['city_id']:0;
            $latitude = '27.0238';
            $longitude = '74.2179';


            $created = Custom::created();
            $connection = ConnectionUtil::getConnection();
            $connection->autocommit(false);

            $post_data['name']    = $clinic_name;
            $post_data['category_name']    = 'HOSPITAL';
            $post_data['email']    = $email;
            $post_data['phone']    = $mobile_number;
            $post_data['address']    = $address;

            $app_id = str_replace(" ", "_", strtoupper($clinic_name));
            $query =    $query = "select id from thinapps where  app_id = '$app_id' and phone = '$mobile_number' limit 1";
            $connection = ConnectionUtil::getConnection();
            $service_message_list = $connection->query($query);
            if (!$service_message_list->num_rows) {
                $response = json_decode(WebservicesFunction::addThiappFromLocal($connection,$post_data),true);
                if($response['status']==1){

                    $thin_app_id = $response['thin_app_id'];
                    $user_id = $response['user_id'];
                    $user_id = $response['user_id'];
                    $password = md5(substr($mobile_number, -10));
                    $country_code = "+91";
                    $is_offline_consulting = !empty($offline)?"YES":"NO";
                    $is_online_consulting = !empty($video)?"YES":"NO";
                    $is_audio_consulting = !empty($audio)?"YES":"NO";
                    $is_chat_consulting = !empty($chat)?"YES":"NO";


                    $save_array =array();
                    $address_id = $service_id = 0;
                    $sql = "INSERT INTO appointment_staffs (thinapp_id, user_id, department_category_id, name, mobile, password, email, country_code, country_id, state_id, city_id, address, is_offline_consulting, is_online_consulting, is_audio_consulting, is_chat_consulting, created, modified) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?,  ?, ?, ?, ?, ?, ?, ?, ?)";
                    $stmt_staff = $connection->prepare($sql);
                    $stmt_staff->bind_param('ssssssssssssssssss', $thin_app_id, $user_id, $department_category_id, $doctor_name, $mobile_number, $password, $email, $country_code, $country_id, $state_id, $city_id, $address, $is_offline_consulting, $is_online_consulting, $is_audio_consulting, $is_chat_consulting, $created, $created);
                    if ($stmt_staff->execute()) {
                        $save_array[] =true;
                        $app_staff_id = $stmt_staff->insert_id;
                        $staff_hours = array();
                        $number=1;
                        foreach ($data_array['day']['from_time'] as $key => $time_from) {

                            $appointment_day_time_id = $number++;
                            $time_to = $data_array['day']['to_time'][$key];
                            $status = $data_array['day']['status'][$key];

                            $sql = "INSERT INTO appointment_staff_hours (thinapp_id, user_id, appointment_staff_id,  appointment_day_time_id, time_from, time_to, status, created, modified) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
                            $stmt_hours = $connection->prepare($sql);
                            $stmt_hours->bind_param('sssssssss', $thin_app_id, $user_id, $app_staff_id, $appointment_day_time_id, $time_from, $time_to, $status, $created, $created);
                            $save_array[] = $stmt_hours->execute();
                        }

                        $sql = "INSERT INTO appointment_addresses (thinapp_id, country_id, state_id,  city_id, address, latitude, longitude, created, modified) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
                        $stmt_address = $connection->prepare($sql);
                        $stmt_address->bind_param('sssssssss', $thin_app_id, $country_id, $state_id, $city_id, $address, $latitude, $longitude,  $created, $created);
                        if($stmt_address->execute()){
                            $save_array[] = true;
                            $address_id = $stmt_address->insert_id;
                            $time_from = APPOINTMENT_WORKING_START_TIME;
                            $time_to = APPOINTMENT_WORKING_END_TIME;
                            $sql = "INSERT INTO appointment_staff_addresses (thinapp_id, appointment_address_id, appointment_staff_id,  from_time, to_time, created) VALUES (?, ?, ?, ?, ?, ?)";
                            $stmt_staff_address = $connection->prepare($sql);
                            $stmt_staff_address->bind_param('ssssss', $thin_app_id, $address_id, $app_staff_id, $time_from, $time_to,  $created);
                            if($stmt_staff_address->execute()){
                                $save_array[] = true;
                            }
                        }else{
                            $save_array[] = false;
                        }

                        $service_slot_duration = '3 MINUTES';
                        $sql = "INSERT INTO appointment_services (thinapp_id, user_id, name,  service_amount, video_consulting_amount, audio_consulting_amount, chat_consulting_amount, service_slot_duration, created, modified) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                        $stmt_service = $connection->prepare($sql);
                        $stmt_service->bind_param('ssssssssss', $thin_app_id, $user_id, $service_name, $offline, $video, $audio, $chat, $service_slot_duration, $created, $created);
                        if($stmt_service->execute()){
                            $save_array[] = true;
                            $service_id = $stmt_service->insert_id;
                            $sql = "INSERT INTO appointment_staff_services (thinapp_id, appointment_service_id, appointment_staff_id, created) VALUES (?, ?, ?, ?)";
                            $stmt_staff_service = $connection->prepare($sql);
                            $stmt_staff_service->bind_param('ssss', $thin_app_id, $service_id, $app_staff_id, $created);
                            if($stmt_staff_service->execute()){
                                $save_array[] = true;
                            }
                        }else{
                            $save_array[] = false;
                        }



                        /* update booking convinience fee task */
                        $booking_convenience_fee = 30;
                        $booking_convenience_fee_restrict_ivr ='YES';
                        $query = "update thinapps set booking_convenience_fee_restrict_ivr =?, booking_convenience_fee=?, booking_convenience_fee_video=?, booking_convenience_fee_audio=?, booking_convenience_fee_chat=? where id = ?";
                        $stmt_thinapp = $connection->prepare($query);
                        $stmt_thinapp->bind_param('ssssss', $booking_convenience_fee_restrict_ivr, $booking_convenience_fee, $booking_convenience_fee, $booking_convenience_fee, $booking_convenience_fee, $thin_app_id);
                        if($stmt_thinapp->execute()){
                            $save_array[] = true;
                        }else{
                            $save_array[] = false;
                        }

                        /* UPDATE LEAD STATUS */

                        $status = 'DONE';
                        $query = "update customer_lead set status =? where app_id = ?";
                        $stmt_lead = $connection->prepare($query);
                        $stmt_lead->bind_param('ss', $status, $thin_app_id);
                        if($stmt_lead->execute()){
                            $save_array[] = true;
                        }else{
                            $save_array[] = false;
                        }
                        /* app permission and user permission */


                        $function_array =array();
                        $query = "select aft.id AS function_id, aft.label_value, uft.id AS user_function_id, uft.label_text from app_functionality_types as aft LEFT JOIN user_functionality_types AS uft  on aft.id = uft.app_functionality_type_id AND uft.`status`='Y' where  aft.label_key IN('QUICK_APPOINTMENT','DOCUMENT_MANAGEMENT','WHATS_APP','SMART_CLINIC','WEB_VIDEO_CONSULTATION','B2C_CLIENT') AND aft.`status`='Y'";
                        $function_list = $connection->query($query);
                        if ($function_list->num_rows) {
                            $function_list = mysqli_fetch_all($function_list, MYSQLI_ASSOC);
                            foreach ($function_list as $key =>$value){
                                $function_array[$value['function_id']][] =$value['user_function_id'];
                            }

                            foreach ($function_array as $app_functionality_type_id =>$user_permission_array){
                                $sql = "INSERT INTO app_enable_functionalities (thinapp_id, app_functionality_type_id) VALUES (?, ?)";
                                $stmt_app_function = $connection->prepare($sql);
                                $stmt_app_function->bind_param('ss', $thin_app_id, $app_functionality_type_id);
                                if($stmt_app_function->execute()){
                                    $save_array[] = true;
                                    $app_enable_functionality_id = $stmt_app_function->insert_id;
                                    if(!empty($user_permission_array)){
                                        $permission = 'YES';
                                        foreach ($user_permission_array as $user_key =>$user_functionality_type_id) {

                                            if(!empty($user_functionality_type_id)){
                                                $sql = "INSERT INTO user_enabled_fun_permissions (thinapp_id, app_functionality_type_id, app_enable_functionality_id,permission) VALUES (?, ?, ?, ?)";
                                                $stmt_user_function = $connection->prepare($sql);
                                                $stmt_user_function->bind_param('ssss', $thin_app_id, $app_functionality_type_id, $app_enable_functionality_id, $permission) ;
                                                if ($stmt_user_function->execute()) {
                                                    $save_array[] = true;
                                                }else{
                                                    $save_array[] = false;
                                                }
                                            }


                                        }
                                    }else{
                                        $save_array[] = false;
                                    }
                                }else{
                                    $save_array[] = false;
                                }
                            }
                        }


                    }

                }else{
                    $response['status'] = 0;
                    $response['message'] = 'Sorry, we you web app could not created due to some reason. Please try again after sometime.';
                }
                if(!empty($save_array) && !in_array(false,$save_array)){
                    $connection->commit();
                    $response['status'] = 1;
                    $response['message'] = 'App Created Successfully';
                    $response['thin_app_id'] = $thin_app_id;
                    try{
                        $post_field['thin_app_id'] =$thin_app_id;
                        $post_field['app_key'] ='MENGAGE';
                        $post_field['username'] =$doctor_name;
                        $post_field['mobile'] =$mobile_number;
                        $post_field['package_name'] ='';
                        $post_field['device_unique_id'] ="AUTOMATON_DEVICE";
                        $post_field['automation'] ="YES";
                        $data_json = json_encode($post_field);
                        $ch = curl_init();

                        curl_setopt($ch, CURLOPT_URL,SITE_PATH.'services/signup_revised');
                        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Content-Length: ' . strlen($data_json)));
                        curl_setopt($ch, CURLOPT_POST, 1);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
                        curl_setopt( $ch,CURLOPT_SSL_VERIFYHOST, false );
                        curl_setopt($ch, CURLOPT_POSTFIELDS,$data_json);
                        $serverOutput = curl_exec ($ch);
                        $serverOutput = json_decode($serverOutput, true);
                        curl_close ($ch);
                    }catch (Exception $e){

                    }



                }else{
                    $connection->rollback();
                    $response['status'] = 0;
                    $response['message'] = 'Sorry, we you web app could not created due to some reason. Please try again after sometime.';
                }
            }else{
                $response['status'] = 0;
                $response['message'] ="Sorry You can not create this app with this mobile number";
            }

            echo json_encode($response);die;
        }
    }

	
	
	    public function save_web_app(){
        $this->autoRender = false;
        if ($this->request->is('ajax')) {

            $data_array = $this->request->data;

            $clinic_name = trim($data_array['entity_name']);
            $category_name = trim($data_array['entity_type']);
            $mobile_number =$admin_mobile_number= Custom::create_mobile_number($data_array['mobile_number']);
            $email = $data_array['email'];

            $service_name = $data_array['service']['name'];
            $offline = $data_array['service']['offline'];
            $video = $data_array['service']['video'];
            $audio = $data_array['service']['audio'];
            $chat = $data_array['service']['chat'];
            $service_slot_duration = $data_array['service']['service_duration'];
            $time_from = date('h:i A',strtotime($data_array['service']['time_from']));
            $time_to = date('h:i A',strtotime($data_array['service']['time_to']));
            $address = $data_array['address'];
            $country_id = !empty($data_array['country_id'])?$data_array['country_id']:0;
            $state_id = !empty($data_array['state_id'])?$data_array['state_id']:0;;
            $city_id = !empty($data_array['city_id'])?$data_array['city_id']:0;
            $latitude = '27.0238';
            $longitude = '74.2179';


            $created = Custom::created();
            $connection = ConnectionUtil::getConnection();
            $connection->autocommit(false);

            $post_data['name']    = $clinic_name;
            $post_data['category_name']    = $category_name;
            $post_data['email']    = $email;
            $post_data['phone']    = $mobile_number;
            $post_data['address']    = $address;

            $app_id = str_replace(" ", "_", strtoupper($clinic_name));
            $query =    $query = "select id from thinapps where  app_id = '$app_id' and phone = '$mobile_number' limit 1";
            $connection = ConnectionUtil::getConnection();
            $service_message_list = $connection->query($query);
            if (!$service_message_list->num_rows) {
                $response = json_decode(WebservicesFunction::addThiappFromLocal($connection,$post_data),true);
                if($response['status']==1){

                    $thin_app_id = $response['thin_app_id'];
                    $user_id = $response['user_id'];
                    $address_id = $service_id = 0;
                    /* add new address*/

                    $sql = "INSERT INTO appointment_addresses (thinapp_id, country_id, state_id,  city_id, address, latitude, longitude, created, modified) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
                    $stmt_address = $connection->prepare($sql);
                    $stmt_address->bind_param('sssssssss', $thin_app_id, $country_id, $state_id, $city_id, $address, $latitude, $longitude,  $created, $created);
                    if($stmt_address->execute()){
                        $save_array[] = true;
                        $address_id = $stmt_address->insert_id;

                    }else{
                        $save_array[] = false;
                    }
                    /* add new service */

                    $sql = "INSERT INTO appointment_services (thinapp_id, user_id, name,  service_amount, video_consulting_amount, audio_consulting_amount, chat_consulting_amount, service_slot_duration, created, modified) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                    $stmt_service = $connection->prepare($sql);
                    $stmt_service->bind_param('ssssssssss', $thin_app_id, $user_id, $service_name, $offline, $video, $audio, $chat, $service_slot_duration, $created, $created);
                    if($stmt_service->execute()){
                        $save_array[] = true;
                        $service_id = $stmt_service->insert_id;

                    }else{
                        $save_array[] = false;
                    }

                    $staff_array =array();
                    foreach ($data_array['executive']['counter_name'] as $key =>$doctor_name){
                            $doc_mobile = $data_array['executive']['counter_number'][$key];
                            $staff_array[] =array('staff_type'=>'DOCTOR','name'=>$doctor_name,'mobile'=>$doc_mobile);
                    }

                    foreach ($data_array['assistant']['assistant_name'] as $key =>$recptionist_name){
                        $doc_mobile = $data_array['assistant']['assistant_number'][$key];
                        $associative = Custom::create_mobile_number($data_array['assistant']['associate_with'][$key]);
                        $staff_array[] =array('associative'=>$associative,'staff_type'=>'RECEPTIONIST','name'=>$recptionist_name,'mobile'=>$doc_mobile);
                    }

                    $save_array =array();

                    $country_code = "+91";
                    $is_offline_consulting = !empty($offline)?"YES":"NO";
                    $is_online_consulting = !empty($video)?"YES":"NO";
                    $is_audio_consulting = !empty($audio)?"YES":"NO";
                    $is_chat_consulting = !empty($chat)?"YES":"NO";
                    $department_category_id = 39;
                    $doctor_id_array=array();

                    foreach ($staff_array as $staff_key => $doctor_data){

                        $mobile_number = Custom::create_mobile_number($doctor_data['mobile']);


                        $doctor_name = $doctor_data['name'];
                        $staff_type = $doctor_data['staff_type'];
                        $password = md5(substr($mobile_number, -10));
                        $doctor_user_id = Custom::create_user($thin_app_id,$mobile_number,$doctor_name);
                        $sql = "INSERT INTO appointment_staffs (thinapp_id, user_id, department_category_id, name, mobile, password, email, country_code, country_id, state_id, city_id, address, is_offline_consulting, is_online_consulting, is_audio_consulting, is_chat_consulting, staff_type, created, modified) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,  ?, ?, ?, ?, ?, ?, ?, ?)";
                        $stmt_staff = $connection->prepare($sql);
                        $stmt_staff->bind_param('sssssssssssssssssss', $thin_app_id, $doctor_user_id , $department_category_id, $doctor_name, $mobile_number, $password, $email, $country_code, $country_id, $state_id, $city_id, $address, $is_offline_consulting, $is_online_consulting, $is_audio_consulting, $is_chat_consulting, $staff_type, $created, $created);
                        if ($stmt_staff->execute()) {
                            $save_array[] =true;
                            $app_staff_id = $stmt_staff->insert_id;
                            $doctor_id_array[$mobile_number]=$app_staff_id;
                            for ($number=1;$number<=7;$number++) {
                                $appointment_day_time_id = $number;
                                $status = 'OPEN';
                                $sql = "INSERT INTO appointment_staff_hours (thinapp_id, user_id, appointment_staff_id,  appointment_day_time_id, time_from, time_to, status, created, modified) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
                                $stmt_hours = $connection->prepare($sql);
                                $stmt_hours->bind_param('sssssssss', $thin_app_id, $user_id, $app_staff_id, $appointment_day_time_id, $time_from, $time_to, $status, $created, $created);
                                $save_array[] = $stmt_hours->execute();
                            }

                            if($doctor_data['staff_type']=='DOCTOR'){
                                /* doctor associative address*/
                                $sql = "INSERT INTO appointment_staff_addresses (thinapp_id, appointment_address_id, appointment_staff_id,  from_time, to_time, created) VALUES (?, ?, ?, ?, ?, ?)";
                                $stmt_staff_address = $connection->prepare($sql);
                                $stmt_staff_address->bind_param('ssssss', $thin_app_id, $address_id, $app_staff_id, $time_from, $time_to,  $created);
                                if($stmt_staff_address->execute()){
                                    $save_array[] = true;
                                }

                                /* doctor associative service*/
                                $sql = "INSERT INTO appointment_staff_services (thinapp_id, appointment_service_id, appointment_staff_id, created) VALUES (?, ?, ?, ?)";
                                $stmt_staff_service = $connection->prepare($sql);
                                $stmt_staff_service->bind_param('ssss', $thin_app_id, $service_id, $app_staff_id, $created);
                                if($stmt_staff_service->execute()){
                                    $save_array[] = true;
                                }
                            }else{
                                if(!empty($doctor_data['associative'])){
                                    $doctor_id = $doctor_id_array[$doctor_data['associative']];
                                    /* doctor associative address*/
                                    $sql = "INSERT INTO doctor_associates_receptionists (thinapp_id, doctor_id, receptionist_id, created) VALUES (?, ?, ?, ?)";
                                    $stmt_staff_address = $connection->prepare($sql);
                                    $stmt_staff_address->bind_param('ssss', $thin_app_id, $doctor_id, $app_staff_id, $created);
                                    if($stmt_staff_address->execute()){
                                        $save_array[] = true;
                                    }
                                }
                            }

                        }


                    }

                    /* update booking convinience fee task */
                    $booking_convenience_fee = 30;
                    $booking_convenience_fee_restrict_ivr ='YES';
                    $query = "update thinapps set booking_convenience_fee_restrict_ivr =?, booking_convenience_fee=?, booking_convenience_fee_video=?, booking_convenience_fee_audio=?, booking_convenience_fee_chat=? where id = ?";
                    $stmt_thinapp = $connection->prepare($query);
                    $stmt_thinapp->bind_param('ssssss', $booking_convenience_fee_restrict_ivr, $booking_convenience_fee, $booking_convenience_fee, $booking_convenience_fee, $booking_convenience_fee, $thin_app_id);
                    if($stmt_thinapp->execute()){
                        $save_array[] = true;
                    }else{
                        $save_array[] = false;
                    }

                    /* UPDATE LEAD STATUS */

                    $status = 'DONE';
                    $query = "update customer_lead set status =? where app_id = ?";
                    $stmt_lead = $connection->prepare($query);
                    $stmt_lead->bind_param('ss', $status, $thin_app_id);
                    if($stmt_lead->execute()){
                        $save_array[] = true;
                    }else{
                        $save_array[] = false;
                    }

                    /* app permission and user permission */
                    $function_array =array();
                    $query = "select aft.id AS function_id, aft.label_value, uft.id AS user_function_id, uft.label_text from app_functionality_types as aft LEFT JOIN user_functionality_types AS uft  on aft.id = uft.app_functionality_type_id AND uft.`status`='Y' where  aft.label_key IN('QUICK_APPOINTMENT','DOCUMENT_MANAGEMENT','WHATS_APP','SMART_CLINIC','WEB_VIDEO_CONSULTATION','B2C_CLIENT') AND aft.`status`='Y'";
                    $function_list = $connection->query($query);
                    if ($function_list->num_rows) {
                        $function_list = mysqli_fetch_all($function_list, MYSQLI_ASSOC);
                        foreach ($function_list as $key =>$value){
                            $function_array[$value['function_id']][] =$value['user_function_id'];
                        }
                        foreach ($function_array as $app_functionality_type_id =>$user_permission_array){
                            $sql = "INSERT INTO app_enable_functionalities (thinapp_id, app_functionality_type_id) VALUES (?, ?)";
                            $stmt_app_function = $connection->prepare($sql);
                            $stmt_app_function->bind_param('ss', $thin_app_id, $app_functionality_type_id);
                            if($stmt_app_function->execute()){
                                $save_array[] = true;
                                $app_enable_functionality_id = $stmt_app_function->insert_id;
                                if(!empty($user_permission_array)){
                                    $permission = 'YES';
                                    foreach ($user_permission_array as $user_key =>$user_functionality_type_id) {

                                        if(!empty($user_functionality_type_id)){
                                            $sql = "INSERT INTO user_enabled_fun_permissions (thinapp_id, app_functionality_type_id, app_enable_functionality_id,permission,user_functionality_type_id) VALUES (?, ?, ?, ?, ?)";
                                            $stmt_user_function = $connection->prepare($sql);
                                            $stmt_user_function->bind_param('sssss', $thin_app_id, $app_functionality_type_id, $app_enable_functionality_id, $permission,$user_functionality_type_id) ;
                                            if ($stmt_user_function->execute()) {
                                                $save_array[] = true;
                                            }else{
                                                $save_array[] = false;
                                            }
                                        }


                                    }
                                }else{
                                    $save_array[] = false;
                                }
                            }else{
                                $save_array[] = false;
                            }
                        }


                        $description = "This is first channel";
                        $default = "Y";
                        $channel_status = "DEFAULT";
                        $sql = "INSERT INTO channels (user_id, channel_name, channel_desc, app_id, is_searchable, is_publish_mbroadcast, channel_status, created, modified ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
                        $stmt = $connection->prepare($sql);
                        $stmt->bind_param('sssssssss', $user_id, $clinic_name, $description, $thin_app_id, $default, $default, $channel_status, $created, $created);
                        if ($stmt->execute()) {
                            $last_inser_id = $stmt->insert_id;
                            $topic_name = Custom::create_topic_name($last_inser_id);
                            $sql = "UPDATE channels set topic_name = ? where id = ?";
                            $stmt = $connection->prepare($sql);
                            $stmt->bind_param('ss', $topic_name, $last_inser_id);
                            $save_array[] = $stmt->execute();

                            $status = 'SUBSCRIBED';
                            $sql = "INSERT INTO subscribers (channel_id, user_id, app_user_id, mobile, app_id, status, created, modified) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                            $stmt = $connection->prepare($sql);
                            $stmt->bind_param('ssssssss', $last_inser_id, $user_id, $user_id, $admin_mobile_number, $thin_app_id, $status, $created, $created);
                            if ($stmt->execute()) {
                                $save_array[] = true;
                                $response_data[] = WebservicesFunction::fun_get_subscriber_list($thin_app_id, $user_id, PAGINATION_LIMIT, 0);
                                WebservicesFunction::createJson('get_subscriber_list_app' . $thin_app_id . "_user" . $user_id, $response_data, 'CREATE', 'subscriber');
                            }else{
                                $save_array[] = false;
                            }
                        }else{
                            $save_array[] = false;
                        }


                    }

                }else{
                    $response['status'] = 0;
                    $response['message'] = 'Sorry, we you web app could not created due to some reason. Please try again after sometime.';
                }

                $send_sms =false;

                if(!empty($save_array) && !in_array(false,$save_array)){
                    $connection->commit();
                    $doctor_id_array = array_values($doctor_id_array);
                    $first_doctor_id = $doctor_id_array[0];
                    $web_app_url = Custom::create_web_app_url($first_doctor_id,$thin_app_id);
                    $web_app_detail_url = SITE_PATH."homes/app_created/".base64_encode($thin_app_id);
                    $web_app_detail_url = Custom::short_url($web_app_detail_url,$thin_app_id);
                    $message = "Dear Sirs,\nCongratulations!\n\nYour web app is ready with $clinic_name.\n\n1.0 For the use by Patients on mobile : Please use following link to share app with your patients on whatsapp.\n$web_app_url\nThe above interface will only be used by patients.\n2.0 For the Use by Doctor / Assistant of Doctor on Mobile / Desktop : Use following link to  manage Queue / OPD and interactions with patients.\n$web_app_detail_url\nYou can also upload your photo, make changes your schedule and availability etc with the option of profile settings on upper right corner.\n\nThanks\n\nTeam MEngage";

                    $send_sms['message'] = $message;
                    $send_sms['mobile'] = $admin_mobile_number;
                    $send_sms['email'] = $email;

                    $response['status'] = 1;
                    $response['message'] = 'App Created Successfully';
                    $response['thin_app_id'] = $thin_app_id;
                }else{
                    $connection->rollback();
                    $response['status'] = 0;
                    $response['message'] = 'Sorry, we you web app could not created due to some reason. Please try again after sometime.';
                }
            }else{
                $response['status'] = 0;
                $response['message'] ="Sorry You can not create this app with this mobile number";
            }

            Custom::sendResponse($response);
            Custom::send_process_to_background();
            if(!empty($send_sms)){
                $res = Custom::sendWhatsappSms($send_sms['mobile'],$send_sms['message'],null,$thin_app_id);
            }

            echo json_encode($response);die;
        }
    }

    public function add_more_staff(){
        $this->autoRender = false;
        if ($this->request->is('ajax')) {

            $data_array = $this->request->data;
            $staff_name = trim($data_array['sn']);
            $staff_type = trim($data_array['st']);
            $doctor_id = @$data_array['di'];
            $thin_app_id = base64_decode($data_array['t']);



            $mobile_number =$admin_mobile_number= Custom::create_mobile_number($data_array['sm']);
            $time_from = APPOINTMENT_WORKING_START_TIME;
            $time_to = APPOINTMENT_WORKING_END_TIME;
            $country_id = !empty($data_array['country_id'])?$data_array['country_id']:0;
            $state_id = !empty($data_array['state_id'])?$data_array['state_id']:0;;
            $city_id = !empty($data_array['city_id'])?$data_array['city_id']:0;
            $created = Custom::created();
            $connection = ConnectionUtil::getConnection();
            $connection->autocommit(false);
            $query = "SELECT t.id, aa.id AS address_id,aa.address, ser.id AS service_id FROM thinapps AS t LEFT JOIN appointment_addresses AS aa ON aa.thinapp_id = t.id AND aa.`status` ='ACTIVE' LEFT JOIN appointment_services AS ser ON ser.thinapp_id = t.id AND ser.`status` ='ACTIVE' WHERE t.id = $thin_app_id and t.status='ACTIVE' LIMIT 1";
            $connection = ConnectionUtil::getConnection();
            $data_list = $connection->query($query);
            if ($data_list->num_rows) {

                $data =  mysqli_fetch_assoc($data_list);
                $address_id = $data['address_id'];
                $service_id = $data['service_id'];
                $address = $data['address'];
                $email = '';
                $save_array =array();
                $country_code = "+91";
                $is_offline_consulting = "YES";
                $is_online_consulting = "YES";
                $is_audio_consulting = "NO";
                $is_chat_consulting = "NO";
                $department_category_id = 39;
                $doctor_id_array=array();
                $doctor_name = $staff_name;
                $query = "SELECT id FROM appointment_staffs staff WHERE staff.thinapp_id =$thin_app_id AND staff.staff_type='$staff_type' AND staff.mobile = '$mobile_number' LIMIT 1";
                $connection = ConnectionUtil::getConnection();
                $data_st = $connection->query($query);
                if(!$data_st->num_rows) {
                    $password = md5(substr($mobile_number, -10));
                    $doctor_user_id = Custom::create_user($thin_app_id,$mobile_number,$doctor_name);
                    $sql = "INSERT INTO appointment_staffs (thinapp_id, user_id, department_category_id, name, mobile, password, email, country_code, country_id, state_id, city_id, address, is_offline_consulting, is_online_consulting, is_audio_consulting, is_chat_consulting, staff_type, created, modified) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,  ?, ?, ?, ?, ?, ?, ?, ?)";
                    $stmt_staff = $connection->prepare($sql);
                    $stmt_staff->bind_param('sssssssssssssssssss', $thin_app_id, $doctor_user_id , $department_category_id, $doctor_name, $mobile_number, $password, $email, $country_code, $country_id, $state_id, $city_id, $address, $is_offline_consulting, $is_online_consulting, $is_audio_consulting, $is_chat_consulting, $staff_type, $created, $created);
                    if ($stmt_staff->execute()) {
                        $save_array[] =true;
                        $app_staff_id = $stmt_staff->insert_id;
                        $doctor_id_array[$mobile_number]=$app_staff_id;
                        for ($number=1;$number<=7;$number++) {
                            $appointment_day_time_id = $number;
                            $status = 'OPEN';
                            $sql = "INSERT INTO appointment_staff_hours (thinapp_id, user_id, appointment_staff_id,  appointment_day_time_id, time_from, time_to, status, created, modified) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
                            $stmt_hours = $connection->prepare($sql);
                            $stmt_hours->bind_param('sssssssss', $thin_app_id, $doctor_user_id, $app_staff_id, $appointment_day_time_id, $time_from, $time_to, $status, $created, $created);
                            $save_array[] = $stmt_hours->execute();
                        }

                        if($staff_type=='DOCTOR'){
                            /* doctor associative address*/
                            if(!empty($address_id)){
                                $sql = "INSERT INTO appointment_staff_addresses (thinapp_id, appointment_address_id, appointment_staff_id,  from_time, to_time, created) VALUES (?, ?, ?, ?, ?, ?)";
                                $stmt_staff_address = $connection->prepare($sql);
                                $stmt_staff_address->bind_param('ssssss', $thin_app_id, $address_id, $app_staff_id, $time_from, $time_to,  $created);
                                if($stmt_staff_address->execute()){
                                    $save_array[] = true;
                                }
                            }
                            if(!empty($service_id)){
                                /* doctor associative service*/
                                $sql = "INSERT INTO appointment_staff_services (thinapp_id, appointment_service_id, appointment_staff_id, created) VALUES (?, ?, ?, ?)";
                                $stmt_staff_service = $connection->prepare($sql);
                                $stmt_staff_service->bind_param('ssss', $thin_app_id, $service_id, $app_staff_id, $created);
                                if($stmt_staff_service->execute()){
                                    $save_array[] = true;
                                }
                            }


                        }else{
                            if(!empty($doctor_id)){

                                /* doctor associative address*/
                                $sql = "INSERT INTO doctor_associates_receptionists (thinapp_id, doctor_id, receptionist_id, created) VALUES (?, ?, ?, ?)";
                                $stmt_staff_address = $connection->prepare($sql);
                                $stmt_staff_address->bind_param('ssss', $thin_app_id, $doctor_id, $app_staff_id, $created);
                                if($stmt_staff_address->execute()){
                                    $save_array[] = true;
                                }
                            }
                        }

                    }
                    if(!empty($save_array) && !in_array(false,$save_array)){
                        $connection->commit();
                        $response['status'] = 1;
                        $response['message'] = 'Successfully';
                    }else{
                        $connection->rollback();
                        $response['status'] = 0;
                        $response['message'] = 'Sorry, We unable to update record.';
                    }
                }else{
                    $response['status'] = 0;
                    $response['message'] ="Sorry, This mobile number already register";
                }

            }else{
                $response['status'] = 0;
                $response['message'] ="Sorry, Your application is not valid";
            }
            echo json_encode($response);die;
        }
    }

    public function app_created($thin_app_id){
	    $this->layout = false;
        $thin_app_id = base64_decode($thin_app_id);
        $connection = ConnectionUtil::getConnection();
        $query = "SELECT t.address as app_address, staff.thinapp_id, staff.id, t.name as app_name, staff.mobile, staff.name as doctor_name, staff.staff_type FROM appointment_staffs AS staff JOIN thinapps AS t ON t.id = staff.thinapp_id WHERE staff.thinapp_id = $thin_app_id AND staff.`status` = 'ACTIVE'";
        $data = $connection->query($query);
        $app_data =array();
        if ($data->num_rows) {
            $app_data = mysqli_fetch_all($data, MYSQLI_ASSOC);
        }
        if(empty($app_data)){
            die('Invalid Request');
        }
        $this->set(compact('app_data','thin_app_id'));
    }






    public function send_otp(){
        $this->autoRender = false;
        if($this->request->is(array('Post','Put'))) {
            $phone = Custom::create_mobile_number($this->request->data['m']);
            if($phone){
                $verification_code = Custom::getRandomString(6);
                //$verification_code = '123456';
                $option = array(
                    'username' => 'New User',
                    'mobile' => $phone,
                    'verification' => $verification_code,
                    'thinapp_id' => 134
                );
                if(Custom::send_otp($option)){
                    return base64_encode($verification_code);
                }

            }
        }

        return false;
    }



    public function get_state_list()
    {
        $this->autoRender = false;
        $response = "";
        if ($this->request->is('ajax')) {
            $country_id = $this->request->data['country_id'];
            if (!$state_list = json_decode(WebservicesFunction::readJson("state_list_$country_id"), true)) {
                $state_list = Custom::getStateList($country_id, true);
                WebservicesFunction::createJson("state_list_$country_id", json_encode($state_list), "CREATE");
            }
            if (!empty($state_list)) {
                foreach ($state_list as $key => $value) {
                    $response .= "<option value='" . $value['id'] . "'>" . $value['name'] . "</option>";
                }
            }
        }
        return $response;


    }

    public function get_city_list()
    {
        $this->autoRender = false;
        $response = "";
        if ($this->request->is('ajax')) {
            $state_id = $this->request->data['state_id'];

            if (!$city_list = json_decode(WebservicesFunction::readJson("city_list_$state_id"), true)) {
                $city_list = Custom::getCityList($state_id, true);
                WebservicesFunction::createJson("city_list_$state_id", json_encode($city_list), "CREATE");
            }
            if (!empty($city_list)) {
                foreach ($city_list as $key => $value) {
                    $response .= "<option value='" . $value['id'] . "'>" . $value['name'] . "</option>";
                }
            }
        }
        return $response;
    }
 
	
	public function create_counter_report($thin_app_id,$fd,$td)
    {
        $this->layout = false;
        $thin_app_id = base64_decode($thin_app_id);
    	$title = "Token Log Report $fd To $td";
        $from_date = DateTime::createFromFormat('d-m-Y', $fd);
        $from_date = $from_date->format('Y-m-d');
        $to_date = DateTime::createFromFormat('d-m-Y', $td);
        $to_date = $to_date->format('Y-m-d');
        $log_array =array();
        $connection = ConnectionUtil::getConnection();
        $query = "SELECT acss.appointment_patient_name, acss.created, acss.queue_number, CONCAT('[', GROUP_CONCAT(JSON_OBJECT('counter', cpl.counter, 'time', cpl.created , 'flag', cpl.flag) ORDER BY cpl.created asc SEPARATOR ','), ']')  as log  FROM appointment_customer_staff_services as acss join counter_patient_logs as cpl on cpl.appointment_id = acss.id where cpl.thinapp_id = $thin_app_id and DATE(cpl.created) BETWEEN '$from_date' AND '$to_date' GROUP BY  cpl.appointment_id order by acss.appointment_datetime asc";
        $list = $connection->query($query);
        $final_array = array();
        if ($list->num_rows) {
            $final_array = mysqli_fetch_all($list, MYSQLI_ASSOC);
        }

        $this->set(compact('final_array','title'));

    }

	 public function dashboard_chart()
    {
        $this->layout = false;
        if ($this->request->is('ajax')) {
            $thin_app_id = base64_decode($this->request->data['ti']);
            $from_date = $this->request->data['sd'];
            $to_date = $this->request->data['ed'];
       
            $connection = ConnectionUtil::getConnection();
        
        	$query = "(SELECT acss.appointment_patient_name, acss.created, acss.queue_number, CONCAT('[', GROUP_CONCAT(JSON_OBJECT('counter', cpl.counter, 'time', cpl.created , 'flag', cpl.flag) ORDER BY cpl.created asc SEPARATOR ','), ']')  as log  FROM appointment_customer_staff_services as acss join counter_patient_logs as cpl on cpl.appointment_id = acss.id where cpl.thinapp_id = $thin_app_id and DATE(cpl.created) BETWEEN '$from_date' AND '$to_date' GROUP BY  cpl.appointment_id order by acss.appointment_datetime asc)";
            $query .= " UNION ALL ";
            $query .= "(SELECT acss.appointment_patient_name, acss.created, acss.queue_number, CONCAT('[', GROUP_CONCAT(JSON_OBJECT('counter', cpl.counter, 'time', cpl.created , 'flag', cpl.flag) ORDER BY cpl.created asc SEPARATOR ','), ']')  as log  FROM appointment_customer_staff_services_archive as acss join counter_patient_logs as cpl on cpl.appointment_id = acss.id where cpl.thinapp_id = $thin_app_id and DATE(cpl.created) BETWEEN '$from_date' AND '$to_date' GROUP BY  cpl.appointment_id order by acss.appointment_datetime asc)";
            
        	
            $list = $connection->query($query);
            $final_array = $chart_result_array = $patient_count_array= $counter_wise_array = $counter_array = array();
            if ($list->num_rows) {
                $final_array = mysqli_fetch_all($list, MYSQLI_ASSOC);
                //$total_patient = count($final_array);
                foreach($final_array as $key =>$list) {
                    $data_date = date('Y-m-d',strtotime($list['created']));

                    $tmp = isset( $patient_count_array[$data_date] )? $patient_count_array[$data_date]:0;
                    $patient_count_array[$data_date] = $tmp+1;

                    $last_time = $counter_name = "";
                    $tokenCloseTime = $tokenGenerateTime = $waiting_time = $patient_delay = $lastScreenTime = $firstScreenTime = "";
                    $log_array = json_decode($list['log'], true);
                    foreach ($log_array as $k => $val) {
                        $flag = $val['flag'];
                        $counter_name = $val['counter'];
                        $time = date('h:i:s A', strtotime($val['time']));
                        if ($flag == "APPOINTMENT_BOOKED") {
                            $tokenGenerateTime = $time;
                        }else if ($flag == "CLOSED") {
                            $tokenCloseTime = $time;

                        }
                    }
                    if(!empty($tokenCloseTime) && !empty($tokenGenerateTime)){
                        $date1 = new DateTime($tokenGenerateTime);
                        $date2 = new DateTime($tokenCloseTime);
                        $diff = $date1->diff($date2);
                        $chart_result_array[$data_date][] =  $diff->format('%h:%i:%s');
                        $counter_wise_array[$data_date][$counter_name][] =  $diff->format('%h:%i:%s');
                        $counter_array[$counter_name]=$counter_name;
                    }

                }
            }

            $dateRangeArray = Custom::createDateRange($from_date,$to_date);
            $patient_visitor_tmp = $patient_tat_avg_tmp = $counter_wise_tat = $counter_wise_tat_avg = $patient_visitor = $patient_tat_avg = $label_array =  array();
            $label = ($rt=='PATIENT_VISITOR')?"Total Patient":"Patient TAT (In Minutes)";

            foreach ($dateRangeArray as $key =>$date){
                $label_array[] =$date;
                $patient_visitor_tmp[] = Custom::get_avg_time_from_array($chart_result_array[$date]);
                $patient_tat_avg_tmp[] =$patient_count_array[$date];
                if(isset($counter_wise_array[$date])){
                    foreach ($counter_wise_array[$date] as $counter =>$avg_array){
                        $counter_wise_tat[$counter][] = Custom::get_avg_time_from_array($avg_array);
                    }
                }else{
                    foreach ($counter_array as $counter =>$cont_name){
                        $counter_wise_tat[$counter][] = 0;
                    }
                }
            }
            $patient_visitor[] =array('label'=>"Total Patient",'data'=>$patient_visitor_tmp, 'borderWidth'=>1);
            $patient_tat_avg[] =array('label'=>"Patient TAT",'data'=>$patient_tat_avg_tmp, 'borderWidth'=>1);
            foreach ($counter_wise_tat as $counter =>$avg_array){
                $counter_wise_tat_avg[] = array('label'=>"$counter",'data'=>$avg_array, 'borderWidth'=>1);
            }
            $this->set(compact('counter_wise_tat_avg','patient_visitor','patient_tat_avg','label_array'));
            $this->render('dashboard_chart', 'ajax');

        }


    }

	public function web_app_signup(){
        $this->layout = false;
        if ($this->request->is(array('post', 'put'))) {
            echo "<pre>";
            $data_array = $this->request->data;
            $mobile_number = $data_array['mobile_number'];
            $app_name = $data_array['clinic_name'];
            $doctor_name = $data_array['doctor_name'];
            $email = $data_array['email'];
            print_r($data_array);die;
        }
        $hours_list =array();
        for ($i = 0; $i < 7; $i++) {
            $day_name = jddayofweek($i,1);
            $hours_list[] =array('number'=>$i+1,'status'=>'OPEN','day'=>$day_name,'time_from'=>'08:00 AM','time_to'=>'08:00 PM');
        }
        $this->set(compact('hours_list'));


    }

    public function mq_form_preview($flag)
    {

        $this->layout=false;
        $data =array();
        if (true || $this->request->is('ajax')) {
            $data_array = $this->request->data;
            $profile_photo = "https://s3-ap-south-1.amazonaws.com/mengage/logo/app_logo_827.png";
            $clinic_name = trim($data_array['entity_name']);
            $category_name = trim($data_array['entity_type']);
            $staff_array =array();
            $service = $data_array['service'];
            foreach ($data_array['executive']['counter_name'] as $key =>$doctor_name){
                $doc_mobile = $data_array['executive']['counter_number'][$key];
                $data[] =array('profile_photo'=>$profile_photo,'doctor_name'=>$doctor_name,'mobile'=>$doc_mobile);
            }
            $this->set(compact('service','data','category_name'));
            if($flag=='WALK-IN'){
                $this->render('mq_form_preview_walk', 'ajax');
            }else if($flag=='WEB_APP'){
                $this->render('mq_form_web_app_preview', 'ajax');
            }




        }

    }

    public function update_web_app_token()
    {
        $this->autoRender=false;
        $data =array();
        if ($this->request->is('ajax')) {
            $data_array = $this->request->data;
            $token = $data_array['token'];
            $thin_app_id = base64_decode($data_array['t']);
            $mobile = Custom::create_mobile_number(base64_decode($data_array['m']));
            $user_id = Custom::create_user($thin_app_id,$mobile,$mobile);
            if(!empty($user_id)){
                $connection = ConnectionUtil::getConnection();
                $query = "update users set web_app_token =? where id = ?";
                $stmt_thinapp = $connection->prepare($query);
                $stmt_thinapp->bind_param('ss',$token, $user_id);
                $res = $stmt_thinapp->execute();
                if($res){
                    $file_name = Custom::encrypt_decrypt('encrypt',"user_$user_id");
                    WebservicesFunction::deleteJson(array($file_name),"user");
                }
                return $res;
            }
        }
    }

    public function  qutot_user_booking($thin_app_id){

        $this->layout =false;
        $thin_app_id =base64_decode($thin_app_id);
        $query = "SELECT t.allow_only_mobile_number_booking, ser.service_slot_duration,  asa.appointment_address_id as address_id, t.logo, staff.profile_photo, staff.id as doctor_id, staff.name AS doctor_name, staff.mobile FROM appointment_staffs AS staff JOIN thinapps AS t ON t.id= staff.thinapp_id join appointment_staff_addresses as asa on asa.appointment_staff_id = staff.id join appointment_staff_services as ass on ass.appointment_staff_id = staff.id join appointment_services as ser on ser.id=ass.appointment_service_id  WHERE staff.`status` ='ACTIVE' AND staff.staff_type='DOCTOR' AND staff.thinapp_id = $thin_app_id group by(staff.id)";
        $connection = ConnectionUtil::getConnection();
        $data = $connection->query($query);
        if ($data->num_rows) {
            $app_data = Custom::get_thinapp_data($thin_app_id);
            if(!empty($app_data)){
                /* add to home icon functionality start*/
                $dir= false;
                $icon_folder = LOCAL_PATH . 'app/webroot/add_home_screen/qutot/'.$thin_app_id."/icons";
                $manifest = LOCAL_PATH . 'app/webroot/add_home_screen/qutot/'.$thin_app_id."/manifest.json";
                $default_manifest = LOCAL_PATH . 'app/webroot/add_home_screen/doctorapps/manifest.json';
                if(!is_dir($icon_folder)){
                    if(Custom::createIconFolder($icon_folder)){
                        $dir =true;
                    }
                }else{
                    $dir = true;
                }
                if($dir){
                    if (!file_exists($manifest)) {
                        $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                        $json_data = json_decode(file_get_contents($default_manifest), true);
                        //$json_data['name'] = $app_data['name'];
                        $json_data['name'] = 'QuTot Token';
                        $json_data['short_name'] = 'QuTot Token';
                        $json_data['start_url'] = $actual_link;
                        $json_data['theme_color'] = "#5D54FF";
                        $json_data['background_color'] = "#ffffff";
                        $has_manifest =  file_put_contents($manifest,json_encode($json_data));
                        $logo = $app_data['logo'];
                        $array =array("72","96","128","144","152","192","384","512");
                        foreach ($array as $key =>$value){
                            $res = Custom::download_image_from_url($logo,$value,$value,$icon_folder."/icon-$value"."x"."$value.png");
                        }
                    }
                }
                /* add to home icon functionality end*/
            }

            $data = mysqli_fetch_all($data,MYSQLI_ASSOC);
            $app_id = $thin_app_id;
            $thin_app_id =base64_encode($thin_app_id);
            $category_name = $app_data['category_name'];
            $this->set(compact('app_data','app_id','category_name','data','thin_app_id'));
        }else{
            echo "<h4>Token Setting Not Configured</h4>";
        }


    }	

    public function sendReminder($appointment_id){
	    $this->layout = false;
	    $appointment_id = base64_decode($appointment_id);
	    $query= "SELECT  t.id as thin_app_id, IFNULL(arc.counts,'NO_RECORD') AS tot_alert_send, t.logo, acss.status as appointment_status, t.name as app_name, staff.id as doctor_id, acss.medical_product_order_id, acss.booking_validity_attempt, acss.drive_folder_id, t.video_calling_on_web, acss.thinapp_id, acss.appointment_booked_from, acss.consulting_type, staff.mobile as doctor_mobile, acss.appointment_patient_name AS patient_name, acss.sub_token, acss.queue_number,acss.has_token,acss.emergency_appointment,acss.sub_token,acss.custom_token,acss.appointment_datetime, t.apk_url, staff.name AS doctor_name FROM appointment_customer_staff_services AS acss JOIN thinapps AS t ON t.id = acss.thinapp_id LEFT JOIN appointment_staffs AS staff ON staff.id= acss.appointment_staff_id LEFT JOIN appointment_reminder_counts AS arc ON arc.appointment_id = acss.id WHERE acss.id = $appointment_id and acss.consulting_type IN ('AUDIO','VIDEO') LIMIT 1";
        $connection = ConnectionUtil::getConnection();
        $data = $connection->query($query);
        $created = Custom::created();
        if ($data->num_rows ) {
            $data = mysqli_fetch_assoc($data);
            if($data['tot_alert_send']=='NO_RECORD'){
                $sql = "INSERT INTO appointment_reminder_counts (thinapp_id, appointment_id, created) VALUES (?, ?, ?)";
                $stmt = $connection->prepare($sql);
                $stmt->bind_param('sss', $data['thin_app_id'], $appointment_id, $created);
                $res =$stmt->execute();
            }
            $this->set(compact('data','appointment_id'));
        }else{
	        echo "Invalid Request";die;
        }
    }

    public function send_doctor_alert(){
        $this->layout = false;
        $this->autoRender=false;
        $response=array();
        $appointment_id = base64_decode($this->request->data['ai']);
        $msg_data = Custom::getAppointmentMessageData($appointment_id);
        if (!empty($msg_data)) {
            $consulting_type = $msg_data['consulting_type'];
            $consulting_type_small = strtolower($msg_data['consulting_type']);
            $consulting_type_cam = ucfirst(strtolower($msg_data['consulting_type']));
            $doctor_mobile = $msg_data['doctor_mobile'];
            $patient_mobile = $msg_data['patient_mobile'];
            $patient_name = $msg_data['patient_name'];
            $time = date('h:i A', strtotime($msg_data['appointment_datetime']));
            $token = Custom::create_queue_number($msg_data);
            $param = base64_encode($appointment_id."##".$patient_mobile);
            $video_link = Custom::short_url(SITE_PATH."homes/video/$param");
            $whats_app_link ="https://api.whatsapp.com/send?phone=$patient_mobile";
            $whats_app_link = Custom::short_url($whats_app_link);
            $message = "PATIENT $consulting_type CALL REMINDER\nName - $patient_name\nToken No - $token     Time -$time\nStart $consulting_type_cam Call from App Using Link \n$video_link\nIn case you wish to make $consulting_type_small call from whatsapp to use  following link\n$whats_app_link\nNote : It is better to use first link through app to better organise patient interaction";
            $thin_app_id = $msg_data['thinapp_id'];
            if(Custom::sendWhatsappSms($doctor_mobile,$message,null,$thin_app_id)){
                $created = Custom::created();
                $connection = ConnectionUtil::getConnection();
                $con_str = ", first_reminder =?";
                $total_send = Custom::getTotalAlert($appointment_id);
                if($total_send==1){
                    $con_str = ", second_reminder =?";
                }
                $sql  = "UPDATE appointment_reminder_counts SET counts = counts + 1 $con_str where appointment_id = ?";

                $stmt = $connection->prepare($sql);
                $stmt->bind_param('ss', $created, $appointment_id);
                $res = $stmt->execute();
                $response['status'] = 1;
                $response['message'] = 'Reminder send successfully.';
            }else{
                $response['status'] = 0;
                $response['message'] = 'Sorry, we are unable to send reminder please try again letter.';
            }
        }else{
            $response['status'] = 0;
            $response['message'] = 'Sorry, this is not valid token.';
        }
        echo json_encode($response);die;
    }

	public function whats_app_log($appointment_id,$thin_app_id){
	    $this->autoRender=false;
	    $appointment_id = base64_decode($appointment_id);
        $thin_app_id = base64_decode($thin_app_id);
        $query = "SELECT id from whatsapp_call_logs where appointment_id=$appointment_id and thinapp_id = $thin_app_id limit 1";
        $connection = ConnectionUtil::getConnection();
        $flush = $connection->query($query);
        if (!$flush->num_rows) {
            $created = Custom::created();
            $sql = "INSERT INTO whatsapp_call_logs (appointment_id,thinapp_id, created) VALUES (?, ?, ?)";
            $stmt_user = $connection->prepare($sql);
            $stmt_user->bind_param('sss', $appointment_id, $thin_app_id, $created);
            return $stmt_user->execute();
        }
    }

	public function load_health_tip(){

        $offset = ($this->request->data['offset']);
        $limit = 15;
        $offset = $limit * $offset;
        if ($this->request->is(array('ajax'))) {
            $query = "SELECT description, id, title, image, like_count, view_count,share_count, created FROM cms_doc_dashboards WHERE thinapp_id IN('1','134') AND visible_for IN('ALL','USER')  AND STATUS = 'ACTIVE' AND category = 'HEALTH_TIP' ORDER BY id DESC  LIMIT $offset , $limit";
            $list_data=array();
            $connection = ConnectionUtil::getConnection();
            $list = $connection->query($query);
            if($list->num_rows) {
                $list_data = mysqli_fetch_all($list,MYSQL_ASSOC);
            }
            $this->set(compact('list_data'));
            $this->render('load_health_tip', 'ajax');
        }else{
            die;
        }
    }

	public function dti_report($thin_app_id,$fd,$td,$amount=0,$payment_type_id=0)
    {
        $this->layout = false;
        $thin_app_id = base64_decode($thin_app_id);
        //$thin_app_id = 134;

        $from_date = DateTime::createFromFormat('d-m-Y', $fd);
        $from_date = $from_date->format('Y-m-d');
        $to_date = DateTime::createFromFormat('d-m-Y', $td);
        $to_date = $to_date->format('Y-m-d');
        $condition = "";
        if(!empty($amount)){
            $condition .= " and mpo.total_amount = $amount ";
        }else if(!empty($payment_type_id)){
            $condition .= " and mpo.hospital_payment_type_id = $payment_type_id ";
        }

        $log_array =array();
        $connection = ConnectionUtil::getConnection();
        $query = "SELECT mpo.payment_type_name, IFNULL(ac.mobile,c.mobile) as patient_mobile,  staff.name as doctor_name, acss.status, acss.appointment_datetime, acss.slot_time, acss.appointment_patient_name, acss.queue_number as token, mpo.total_amount FROM appointment_customer_staff_services AS acss left join appointment_customers as ac on ac.id = acss.appointment_customer_id left join childrens as c on c.id = acss.children_id  left join medical_product_orders as mpo  on  mpo.id = acss.medical_product_order_id join appointment_staffs as staff on staff.id = acss.appointment_staff_id where acss.thinapp_id = $thin_app_id and DATE(acss.appointment_datetime) BETWEEN '$from_date' and '$to_date' $condition order by acss.appointment_datetime asc";
        $list = $connection->query($query);
        $data_array = $counter_array= $save_data= array();
        if ($list->num_rows) {
            $data_array = mysqli_fetch_all($list, MYSQLI_ASSOC);
        }
        $this->set(compact('data_array'));

    }

	public function doctor_code_list($ivr_number=null){
        $app_data =array();
        if (!empty($ivr_number)) {
            $query = "select staff.thinapp_id, staff.name as doctor_name, di.doctor_code from doctors_ivr as di join appointment_staffs as staff on staff.id = di.doctor_id and staff.staff_type='DOCTOR' and staff.status='ACTIVE' AND di.ivr_number ='$ivr_number'  ORDER BY staff.name asc";
            $list_data=array();
            $connection = ConnectionUtil::getConnection();
            $list = $connection->query($query);
            $thin_app_id=0;
            if($list->num_rows) {
                $list_data = mysqli_fetch_all($list,MYSQL_ASSOC);
                $thin_app_id = $list_data[0]['thinapp_id'];
            }
            $app_data = Custom::get_thinapp_data($thin_app_id);
            $this->set(compact('list_data','app_data','ivr_number'));

        }else{
            $this->set(compact('app_data','ivr_number'));
        }
        $this->render('doctor_code_list', 'ajax');
    }

	public function updateNextToken()
    {
        $this->autoRender = false;
        if ($this->request->is(array('ajax','post'))) {
        	
        	$return_array = false;
            if(!$this->request->data['di'] && !$this->request->data['ti']){
                $request = file_get_contents("php://input");
                $this->request->data = json_decode($request, true);
            	$this->request->data['di'] = base64_decode($this->request->data['di']);
                $return_array=true;
            }
        
            $doctor_id = ($this->request->data['di']);
            $thin_app_id = base64_decode($this->request->data['ti']);
            $result['status'] = 1;
            $res = Custom::fortisUpdateToken($thin_app_id,$doctor_id);
        	$current_appointment_id = !empty($res['current']['appointment_id'])?$res['current']['appointment_id']:'';
            $result['current_appointment_id'] = base64_encode($current_appointment_id);
            $result['string']=Custom::fortisShowResponseString($res,$return_array);
        	Custom::emitSocet(array('thin_app_id'=>$thin_app_id,'doctor_id'=>$doctor_id));
            echo json_encode($result);die;
        } else {
            exit();
        }
    }

	
	public function  pine_token_list_back(){
        $request = file_get_contents("php://input");
        $this->request->data = json_decode($request, true);
        $data = $this->request->data;
        $thin_app_id = $t_id = isset($data['thin_app_id']) ? base64_decode($data['thin_app_id']) : "";
        $doctor_id =$d_id = isset($data['doctor_id']) ? base64_decode($data['doctor_id']) : "";
        $query = "SELECT staff.name as doctor_name, acss.appointment_datetime, t.logo, acss.drive_folder_id as folder_id,  acss.referred_by as counter, acss.appointment_staff_id, acss.patient_queue_type, acss.thinapp_id, acss.send_to_lab_datetime, acss.skip_tracker, acss.consulting_type, acss.id AS appointment_id, acss.payment_status, acss.appointment_patient_name AS patient_name, IFNULL(ac.id,c.id) AS patient_id, IF(ac.id IS NOT NULL, 'CUSTOMER','CHILDREN') AS patient_type, IFNULL(ac.mobile,c.mobile) AS patient_mobile, acss.queue_number, acss.status FROM appointment_customer_staff_services AS acss left join thinapps as t on t.id = acss.thinapp_id LEFT JOIN appointment_customers AS ac ON ac.id=acss.appointment_customer_id LEFT JOIN childrens AS c ON c.id= acss.children_id left join appointment_staffs as staff on staff.id = acss.appointment_staff_id WHERE acss.appointment_staff_id = $doctor_id and acss.thinapp_id=$thin_app_id AND DATE(acss.appointment_datetime) = DATE(NOW()) AND acss.status !='DELETED' AND acss.is_paid_booking_convenience_fee !='NO' ORDER BY CAST(acss.queue_number  AS DECIMAL(10,2)) desc, acss.send_to_lab_datetime ASC";
        $connection = ConnectionUtil::getConnection();
        $data =$response = $final_array =array();
        $list = $connection->query($query);
        if ($list->num_rows) {
            $data = mysqli_fetch_all($list,MYSQLI_ASSOC);
            $thin_app_id =base64_encode($thin_app_id);
            $doctor_id =base64_encode($doctor_id);
            foreach ($data as $key => $list) {
                $tmp=array();
                $patient_array = explode("-",$list['patient_name']);
                $patient_name = $patient_array[0];
                $tmp['appointment_id'] =base64_encode($list['appointment_id']);
                $tmp['thinapp_id'] =base64_encode($list['thinapp_id']);
                $tmp['patient_name'] = $patient_name;
                $tmp['patient_mobile'] =substr($list['patient_mobile'], -10);
                $tmp['consulting_type'] =$list['consulting_type'];
                $tmp['queue_number'] =$token_number= $list['queue_number'];
                if ($list['status'] == 'RESCHEDULE' || $list['status'] == 'NEW' || $list['status'] == 'CONFIRM') {
                    $status = ($list['skip_tracker'] == 'YES') ? 'Skipped' : 'Booked';
                    $tmp['status'] = ucfirst(strtolower($status));
                }else{
                    $tmp['status'] = ucfirst(strtolower($list['status']));
                }

                $tmp['send_to_doctor'] = 'NO';
                $tmp['skip_token'] = 'NO';
                $tmp['unskip_token'] = 'NO';
                $tmp['send_to_billing'] = 'NO';
                $tmp['assign_counter'] = 'NO';
                $tmp['medical_record'] = "";
                $tmp['cancel'] = "NO";
                $tmp['close'] = "NO";
                $date = date('d-M-Y',strtotime($list['appointment_datetime']));
                $doctor_name = $list['doctor_name'];
                
                $string = "Name :- $patient_name\nDate   :- $date\n\n\n\n";
                $tmp['print_string'] = $string;
                $tmp['doctor_name'] = $doctor_name;
                $tmp['token_number'] = $token_number;
                if ($list['status'] == 'RESCHEDULE' || $list['status'] == 'NEW' || $list['status'] == 'CONFIRM') {
                    if ($list['thinapp_id'] == CK_BIRLA_APP_ID && $list['patient_queue_type'] != 'DOCTOR_CHECKIN') {
                        $tmp['send_to_doctor'] = 'YES';
                    }
                    if ($list['skip_tracker'] == 'NO') {
                        $tmp['skip_token'] = 'YES';
                    } else {
                        $tmp['unskip_token'] = 'YES';
                    }
                    if ($list['thinapp_id'] == CK_BIRLA_APP_ID ) {
                        if ($list['patient_queue_type'] == 'DOCTOR_CHECKIN') {
                            $tmp['send_to_billing'] = 'YES';
                        }
                        $tmp['assign_counter'] = 'YES';
                    }
                    if ($list['consulting_type'] != 'OFFLINE') {
                        $string = $list['folder_id'] . "##FOLDER##" . $list['patient_mobile'];
                        $folder_url = FOLDER_PATH . 'record/' . Custom::encodeVariable($string);
                        $tmp['medical_record'] = $folder_url;
                    }

                    $tmp['cancel'] = "YES";
                    $tmp['close'] = "YES";
                }
                $final_array[$key] = $tmp;
            }
        }
        if(!empty($final_array)){
            $response['status']=1;
            $response['message']="List Fount";
            $response['data']=$final_array;
            $res = Custom::fortisGetCurrentToken($t_id,$d_id);
            $response['string']=Custom::fortisShowResponseString($res,true);
        }else{
            $response['status']=0;
            $response['message']="No List Found";
        }
        echo json_encode($response);die;

    }

	public function  pine_token_list(){
        $request = file_get_contents("php://input");
        $this->request->data = json_decode($request, true);
        $data = $this->request->data;
        $thin_app_id = $t_id = isset($data['thin_app_id']) ? base64_decode($data['thin_app_id']) : "";
        $doctor_id =$d_id = isset($data['doctor_id']) ? base64_decode($data['doctor_id']) : "";
        $query = "SELECT t.name as app_name, staff.name as doctor_name, acss.appointment_datetime, t.logo, acss.drive_folder_id as folder_id,  acss.referred_by as counter, acss.appointment_staff_id, acss.patient_queue_type, acss.thinapp_id, acss.send_to_lab_datetime, acss.skip_tracker, acss.consulting_type, acss.id AS appointment_id, acss.payment_status, acss.appointment_patient_name AS patient_name, IFNULL(ac.id,c.id) AS patient_id, IF(ac.id IS NOT NULL, 'CUSTOMER','CHILDREN') AS patient_type, IFNULL(ac.mobile,c.mobile) AS patient_mobile, acss.queue_number, acss.status FROM appointment_customer_staff_services AS acss left join thinapps as t on t.id = acss.thinapp_id LEFT JOIN appointment_customers AS ac ON ac.id=acss.appointment_customer_id LEFT JOIN childrens AS c ON c.id= acss.children_id left join appointment_staffs as staff on staff.id = acss.appointment_staff_id WHERE acss.appointment_staff_id = $doctor_id and acss.thinapp_id=$thin_app_id AND DATE(acss.appointment_datetime) = DATE(NOW()) AND acss.status !='DELETED' AND acss.is_paid_booking_convenience_fee !='NO' ORDER BY CAST(acss.queue_number  AS DECIMAL(10,2)) desc, acss.send_to_lab_datetime ASC";
        $connection = ConnectionUtil::getConnection();
        $data =$response = $final_array =array();
        $list = $connection->query($query);
        if ($list->num_rows) {
            $data = mysqli_fetch_all($list,MYSQLI_ASSOC);
            $thin_app_id =base64_encode($thin_app_id);
            $doctor_id =base64_encode($doctor_id);
            foreach ($data as $key => $list) {
                $tmp=array();
                $patient_array = explode("-",$list['patient_name']);
                $patient_name = $patient_array[0];
                $tmp['appointment_id'] =base64_encode($list['appointment_id']);
                $tmp['thinapp_id'] =base64_encode($list['thinapp_id']);
                $tmp['patient_name'] = $patient_name;
                $tmp['patient_mobile'] =substr($list['patient_mobile'], -10);
                $tmp['consulting_type'] =$list['consulting_type'];
                $tmp['queue_number'] =$token_number= $list['queue_number'];
                if ($list['status'] == 'RESCHEDULE' || $list['status'] == 'NEW' || $list['status'] == 'CONFIRM') {
                    $status = ($list['skip_tracker'] == 'YES') ? 'Skipped' : 'Booked';
                    $tmp['status'] = ucfirst(strtolower($status));
                }else{
                    $tmp['status'] = ucfirst(strtolower($list['status']));
                }

                $tmp['send_to_doctor'] = 'NO';
                $tmp['skip_token'] = 'NO';
                $tmp['unskip_token'] = 'NO';
                $tmp['send_to_billing'] = 'NO';
                $tmp['assign_counter'] = 'NO';
                $tmp['medical_record'] = "";
                $tmp['cancel'] = "NO";
                $tmp['close'] = "NO";
                $date = date('d-M-Y',strtotime($list['appointment_datetime']));
            	$tokenTime = date('h:i:A',strtotime($list['appointment_datetime']));
                $doctor_name = $list['doctor_name'];

                $doctor_name = $list['doctor_name'];
                $customer_name = $list['patient_name'];
                $tmp['doctor_name'] = $doctor_name;
                if(Custom::isCustomizedAppId($list['thinapp_id'])){
                    $time = date('h:i A');
                    $tmp['doctor_name'] = $list['app_name'];
                    $string = "Service :- $doctor_name\n";
                    if(strpos(strtolower($customer_name), "patient") === false){
                        $string .= "Name :- $customer_name\n";
                    }
                    if($list['thinapp_id']==607){
                        $string .= "Date   :- $date\nAppointment Time   :- $tokenTime\nBooked Time   :- $time\n\n\n\n";
                    }else{
                        $string .= "Date   :- $date\nTime   :- $time\n\n\n\n";
                    }
                }else{
                    $string = "Name :- $customer_name\nDate   :- $date\n\n\n\n";
                }

                $tmp['print_string'] = $string;
                $tmp['token_number'] = $token_number;
                if ($list['status'] == 'RESCHEDULE' || $list['status'] == 'NEW' || $list['status'] == 'CONFIRM') {
                    if ($list['thinapp_id'] == CK_BIRLA_APP_ID && $list['patient_queue_type'] != 'DOCTOR_CHECKIN') {
                        $tmp['send_to_doctor'] = 'YES';
                    }
                    if ($list['skip_tracker'] == 'NO') {
                        $tmp['skip_token'] = 'YES';
                    } else {
                        $tmp['unskip_token'] = 'YES';
                    }
                    if ($list['thinapp_id'] == CK_BIRLA_APP_ID ) {
                        if ($list['patient_queue_type'] == 'DOCTOR_CHECKIN') {
                            $tmp['send_to_billing'] = 'YES';
                        }
                        $tmp['assign_counter'] = 'YES';
                    }
                    if ($list['consulting_type'] != 'OFFLINE') {
                        $string = $list['folder_id'] . "##FOLDER##" . $list['patient_mobile'];
                        $folder_url = FOLDER_PATH . 'record/' . Custom::encodeVariable($string);
                        $tmp['medical_record'] = $folder_url;
                    }

                    $tmp['cancel'] = "YES";
                    $tmp['close'] = "YES";
                }
                $final_array[$key] = $tmp;
            }
        }
        if(!empty($final_array)){
            $response['status']=1;
            $response['message']="List Fount";
            $response['data']=$final_array;
            $res = Custom::fortisGetCurrentToken($t_id,$d_id);
            $response['string']=Custom::fortisShowResponseString($res,true);
        }else{
            $response['status']=0;
            $response['message']="No List Found";
        }
        echo json_encode($response);die;

    }
	
	public function setCurrentToken()
    {
        $this->autoRender = false;
        if($this->request->is(array('ajax','post'))) {
            $doctor_id = base64_decode($this->request->data['di']);
            $thin_app_id = base64_decode($this->request->data['ti']);
            $token = trim($this->request->data['token']);
        	$assignTokenTo = isset($this->request->data['assignTokenTo'])?$this->request->data['assignTokenTo']:0;
        	$assign_remark = isset($this->request->data['assign_remark'])?$this->request->data['assign_remark']:"";
            $login_id = isset($this->request->data['user_id'])?$this->request->data['user_id']:0;
            $close_active_token = isset($this->request->data['close_active_token'])?$this->request->data['close_active_token']:'';
            $last_token_data = Custom::fortisGetCurrentToken($thin_app_id,$doctor_id);
        
        	$connection = ConnectionUtil::getConnection();
            $reminder_add_by_id = 0;
            $created = Custom::created();
            /* close active token */
             if(Custom::isCustomizedAppId($thin_app_id) && !empty($last_token_data['current']['token_number'])){
                $last_token = $last_token_data['current']['token_number'];
                $appointmentId =Custom::getPharmacyAppointmentId($doctor_id,$last_token);
                if(!empty($appointmentId)){
                    if($close_active_token=="yes"){
                        $status = "CLOSED";
                        $sql = "update appointment_customer_staff_services set reminder_add_by_id = ?, status = ?, modified=? where id = ?";
                        $stmt_df = $connection->prepare($sql);
                        $stmt_df->bind_param('ssss', $reminder_add_by_id, $status, $created, $appointmentId);
                        $update =$stmt_df->execute();
                        $log = Custom::insertTokenLog($thin_app_id,$doctor_id,$login_id,$status);
                    }else if($close_active_token=="no"){
                        if(!empty($assignTokenTo)){

                            $sql = "update appointment_customer_staff_services set reason_of_appointment=?, last_assign_doctor_id = ?, appointment_staff_id = ?, modified=? where id = ?";
                            $stmt_df = $connection->prepare($sql);
                            $stmt_df->bind_param('sssss', $assign_remark,$doctor_id, $assignTokenTo, $created, $appointmentId);
                            $update =$stmt_df->execute();
                           
                        }else{
                            $status = "YES";
                            $sql = "update appointment_customer_staff_services set reminder_add_by_id = ?, skip_tracker = ?, modified=? where id = ?";
                            $stmt_df = $connection->prepare($sql);
                            $stmt_df->bind_param('ssss', $reminder_add_by_id, $status, $created, $appointmentId);
                            $update =$stmt_df->execute();
                        }
                    }



                }
            }
            /* close active token */
        
        	if($last_token_data['current']['token_number']==$token){
                    $token = "";
                }
        	 $res = Custom::fortisSetTokenMenual($thin_app_id,$doctor_id,$token);
           

            $doctorData = Custom::get_doctor_by_id($doctor_id);
            $counterType  =$doctorData['counter_booking_type'];
            if($counterType=='BILLING'){
                $status ="SEND_TO_BILLING_COUNTER";
            }if($counterType=='PAYMENT'){
                $status ="CHANGE_TO_PAYMENT_COUNTER";
            }else{
                $status ="ASSIGN_COUNTER";
            }
            $response['current_token'] = $res['current']['token_number'];
            $response['patient_name'] = $res['current']['patient_name'];
            $response['token_id'] = base64_encode($res['current']['appointment_id']);

            $log = Custom::insertTokenLog($thin_app_id,$doctor_id,$login_id,$status);
            $billingTokenString="";
            $play=true;

           


            /* CODE ONLY FOR EHCC CASE START */
            if(EHCC_APP_ID==$thin_app_id){
                $appointmentId =Custom::getPharmacyAppointmentId($doctor_id,$response['current_token']);
                if(!empty($appointmentId)){
                    $created = Custom::created();
                   
                    $sql = "update appointment_customer_staff_services set reminder_add_by_id = ?, modified=? where id = ?";
                    $stmt_df = $connection->prepare($sql);
                    $stmt_df->bind_param('sss', $reminder_add_by_id, $created, $appointmentId);
                    $update =$stmt_df->execute();
                    $reloadTracker=true;
                }
                if($counterType=='BILLING'){
                    $play=false;
                    /* this case work for EHCC only */
                    $created = Custom::created();
                    $connection = ConnectionUtil::getConnection();
                    $appointmentId =Custom::getPharmacyAppointmentId($doctor_id,$token);
                    if(!empty($appointmentId)){
                        $sql = "update appointment_customer_staff_services set reminder_add_by_id = ?, modified=? where id = ?";
                        $stmt_df = $connection->prepare($sql);
                        $created = Custom::created();
                        $stmt_df->bind_param('sss', $doctor_id, $created, $appointmentId);
                        if ($stmt_df->execute()){
                            $reloadTracker=true;
                            $playSound = false;
                            $doctor_ids = isset($data['doctor_ids'])?$data['doctor_ids']:0;
                            if(!empty($doctor_ids)){
                                $doctor_ids = explode(",",$doctor_ids);
                                foreach ($doctor_ids as $key => $d_id){
                                    if($doctor_id != $d_id){
                                        $res = Custom::fortisGetCurrentToken($thin_app_id,$d_id);
                                        if($token==$res['current']['token_number']){
                                            $res = Custom::fortisSetTokenMenual($thin_app_id,$d_id,0);
                                            $res = Custom::emitSocet(array('doctor_id'=>$d_id,'thin_app_id'=>$thin_app_id,'play'=>false));
                                        }
                                    }

                                }
                            }

                        }
                    }
                }

            }
            /* update next token end  */

            if($play===true){
                $log = Custom::insertTokenLog($thin_app_id,$doctor_id,$login_id,"PLAY");
            }

            Custom::sendResponse($response);
            Custom::send_process_to_background();
        	  $play = !empty($token)?$play:false;
            $doctor_ids =isset($this->request->data['doctor_ids'])?$this->request->data['doctor_ids']:"";
            Custom::emitSocet(array('thin_app_id'=>$thin_app_id,'doctor_id'=>$doctor_id,'play'=>$play,'doctor_ids'=>$doctor_ids));
        if(!empty($appointmentId)){
                Custom::emitSocet(array('thin_app_id'=>$thin_app_id,'doctor_id'=>$assignTokenTo,'play'=>false,'doctor_ids'=>$doctor_ids));
            }
        	

        } else {
            exit();
        }
    }
	
	public function callToPatient()
    {
        $this->autoRender = false;
        if($this->request->is(array('ajax','post'))) {
            $mobile = trim($this->request->data['mobile']);
            $thin_app_id = $this->request->data['thin_app_id'];
            $doctor_id = $this->request->data['doctor_id'];
            $appointment_id = trim($this->request->data['appointment_id']);
            return Custom::callToPatient($mobile,$thin_app_id,$doctor_id,$appointment_id);
        } else {
            exit();
        }
    }

	 /* IoT button api start */

  	public function IoT_close_token()
    {
        $this->autoRender = false;
        if ($this->request->is(array('ajax','post'))) {
            $request = file_get_contents("php://input");
            $this->request->data = json_decode($request, true);
            $thin_app_id = ($this->request->data['thin_app_id']);
            $doctor_id = ($this->request->data['doctor_id']);

            $appointment_id = base64_decode($this->request->data['token_id']);
            if($appointment_id==0){
                $res = Custom::fortisUpdateToken($thin_app_id,$doctor_id);
                $response['current_token'] = $res['current']['token_number'];
                $response['patient_name'] = $res['current']['patient_name'];
                $response['token_id'] = base64_encode($res['current']['appointment_id']);
                $response['status'] = 1;
                $response['message'] = "Current Token Found";
                echo json_encode($response);die;
            }

            $appointment_data = Custom::get_appointment_by_id($appointment_id);
            if($appointment_data){
                $thin_app_id = $appointment_data['thinapp_id'];
                $doctor_id = $appointment_data['appointment_staff_id'];
                $post = array();
                $admin_data = Custom::get_thinapp_admin_data($appointment_data['thinapp_id']);
                $post['app_key'] = APP_KEY;
                $post['user_id'] = $admin_data['id'];
                $post['thin_app_id'] = $admin_data['thinapp_id'];
                $post['appointment_id'] = $appointment_id;
                $result = json_decode(WebservicesFunction::close_appointment($post, true), true);
                $result['current_token'] = "0";
                if ($result['status'] == 1) {

                   $res = Custom::fortisGetCurrentToken($thin_app_id,$doctor_id);
                    $response['status'] = 1;
                    $response['message'] = "Appointment not found";
                    $response['current_token'] = $res['current']['token_number'];
                    $response['token_id'] = base64_encode($res['current']['appointment_id']);
                    $response['patient_name'] = $res['current']['patient_name'];
                    Custom::sendResponse($response);
                	 Custom::emitSocet(array('doctor_id'=>$doctor_id,'thin_app_id'=>$thin_app_id));
                    Custom::close_appointment_notification($result['notification_array']);
                } else {
                    Custom::sendResponse($result);
                }
            }else{

                $res = Custom::fortisGetCurrentToken($thin_app_id,$doctor_id);
                $result['current_token'] = $res['current']['token_number'];
                $result['token_id'] = base64_encode($res['current']['appointment_id']);

                $response['status'] = 1;
                $response['message'] = "Appointment not found";
                echo json_encode($response);die;
            }
        } else {
            exit();
        }

    }

    public function IoT_skip_token()
    {
        $this->autoRender = false;
        if ($this->request->is(array('ajax','post'))) {
            $request = file_get_contents("php://input");
            $this->request->data = json_decode($request, true);
            $thin_app_id = ($this->request->data['thin_app_id']);
            $doctor_id = ($this->request->data['doctor_id']);
            $appointment_id = base64_decode($this->request->data['token_id']);
            if($appointment_id==0){
                $res = Custom::fortisUpdateToken($thin_app_id,$doctor_id);
                $response['current_token'] = $res['current']['token_number'];
                $response['patient_name'] = $res['current']['patient_name'];
                $response['token_id'] = base64_encode($res['current']['appointment_id']);
                $response['status'] = 1;
                $response['message'] = "Current Token Found";
                echo json_encode($response);die;
            }
            $appointment_data = Custom::get_appointment_by_id($appointment_id);
            if($appointment_data){
                $thin_app_id = $appointment_data['thinapp_id'];
                $doctor_id = $appointment_data['appointment_staff_id'];

                $connection = ConnectionUtil::getConnection();
                $skip_tracker ='YES';
                $counter = '';
                $sql  = "UPDATE appointment_customer_staff_services SET referred_by=?, skip_tracker = ? where id = ?";
                $stmt = $connection->prepare($sql);
                $stmt->bind_param('sss', $counter,$skip_tracker, $appointment_id);
                if($stmt->execute()) {
                    $result['status'] = 1;
                    $result['message']='Appointment skipped successfully';
                    $is_update =  Custom::updateTokenOnAction($thin_app_id,$doctor_id,$appointment_data['queue_number']);
                    $res = Custom::fortisGetCurrentToken($thin_app_id,$doctor_id);
                    $result['current_token'] = $res['current']['token_number'];
                    $result['token_id'] = base64_encode($res['current']['appointment_id']);
                    $result['patient_name'] = $res['current']['patient_name'];
                	 Custom::emitSocet(array('doctor_id'=>$doctor_id,'thin_app_id'=>$thin_app_id));
                }else{
                    $result['status'] = 1;
                    $result['message']='Sorry, token could not skipped';
                }


                echo json_encode($result);die;
            }else{
                $response['status'] = 0;
                $response['message'] = "Appointment not found";
                echo json_encode($response);die;
            }
        } else {
            exit();
        }
    }

    public function IoT_send_to_billing_counter()
    {
        $this->autoRender = false;
        if ($this->request->is(array('ajax','post'))) {
            $request = file_get_contents("php://input");
            $this->request->data = json_decode($request, true);
            $thin_app_id = ($this->request->data['thin_app_id']);
            $doctor_id = ($this->request->data['doctor_id']);
            $appointment_id = base64_decode($this->request->data['token_id']);
            if($appointment_id==0){
                $res = Custom::fortisUpdateToken($thin_app_id,$doctor_id);
                $response['current_token'] = $res['current']['token_number'];
                $response['patient_name'] = $res['current']['patient_name'];
                $response['token_id'] = base64_encode($res['current']['appointment_id']);
                $response['status'] = 1;
                $response['message'] = "Current Token Found";
                echo json_encode($response);die;
            }
            $appointment_data = Custom::get_appointment_by_id($appointment_id);
            if($appointment_data){

                $thin_app_id = $appointment_data['thinapp_id'];
                $doctor_id = $appointment_data['appointment_staff_id'];
                $counter = '';
                $connection = ConnectionUtil::getConnection();
                $checkin_type = 'BILLING_CHECKIN';
                $send_to_lab_datetime = Custom::created();
                $sql  = "UPDATE appointment_customer_staff_services SET send_to_lab_datetime = ?, referred_by =?, patient_queue_type =? where id = ?";
                $stmt = $connection->prepare($sql);
                $stmt->bind_param('ssss', $send_to_lab_datetime,$counter,$checkin_type,  $appointment_id);
                if($stmt->execute()) {
                    $result['status'] = 1;
                    $result['message']='Token sent to billing counter successfully';
                    $is_update =  Custom::updateTokenOnAction($thin_app_id,$doctor_id,$appointment_data['queue_number']);
                    $res = Custom::fortisGetCurrentToken($thin_app_id,$doctor_id);
                    $result['current_token'] = $res['current']['token_number'];
                    $result['token_id'] = base64_encode($res['current']['appointment_id']);
                    $result['patient_name'] = $res['current']['patient_name'];
                	 Custom::emitSocet(array('doctor_id'=>$doctor_id,'thin_app_id'=>$thin_app_id));
                }else{
                    $result['status'] = 0;
                    $result['message']='Sorry, unable to send appointment to billing counter';
                }


                echo json_encode($result);die;
            }else{
                $result['status'] = 0;
                $result['message'] = "Appointment not found";
                echo json_encode($result);die;
            }
        } else {
            exit();
        }
    }

    public function IoT_current_token()
    {
        $request = file_get_contents("php://input");
        $this->request->data = json_decode($request, true);
        $thin_app_id = $this->request->data['thin_app_id'];
        $doctor_id =$this->request->data['doctor_id'];
        $res = Custom::fortisGetCurrentToken($thin_app_id,$doctor_id);
        $response['current_token'] = $res['current']['token_number'];
        $response['patient_name'] = $res['current']['patient_name'];
        $response['token_id'] = base64_encode($res['current']['appointment_id']);
        $response['status'] = 1;
        $response['message'] = "Current Token Found";
    	$response['activeTokens']  =  "";
        if(isset($this->request->data['doctor_ids'])){
            $doctor_ids = explode(",",$this->request->data['doctor_ids']);
            $tmp_array =array();
            foreach ($doctor_ids as $key => $d_id){
                $res = Custom::fortisGetCurrentToken($thin_app_id,$d_id);
                $tmp[] =$res['current']['token_number'];
            }
            $response['activeTokens']  =  implode(',',$tmp);
        }
        echo json_encode($response);die;
    }


    /* IoT button api end */

	public function play_tracker_voice()
    {
        $this->autoRender = false;
        if ($this->request->is(array('ajax','post'))) {
            $doctor_id = $this->request->data['doctor_id'];
            $thin_app_id = base64_decode($this->request->data['t']);
        	 $log = Custom::insertTokenLog($thin_app_id,$doctor_id,$doctor_id,"PLAY");
            Custom::emitSocet(array('thin_app_id'=>$thin_app_id,'doctor_id'=>$doctor_id));
            $response['status'] = 1;
            $response['message'] = "Voice played";
            echo json_encode($response);die;

        } else {
            exit();
        }

    }

	public function phonepayCallback(){
    
    	 $file_name="test";
    	 WebservicesFunction::createJson($file_name,json_encode($_REQUEST),"CREATE","phonepay");
    
    
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $baseUrl = SITE_PATH;
            $orderIdRow = $orderId = $_POST['transactionId'];
            $orderIdArr = explode('-',$orderId);
            $orderId = $orderIdArr[0];
            $orderAmount = ($_POST['amount']/100);
            $referenceId = $_POST['providerReferenceId'];
            $txStatus = $_POST['code'];
            $paymentMode = 'CASH';
            $txMsg = "";
            $txTime = date('Y-m-d H:i:s');
            $request_from = (isset($_REQUEST['rf']) && !empty($_REQUEST['rf']))?$_REQUEST['rf']:'doctor';
            if($txStatus == 'PAYMENT_SUCCESS'){
                
                $connection = ConnectionUtil::getConnection();
                $appointment_refund = false;
                $refundId = '';
                $sql = "SELECT t.pay_clinic_visit_fee_online, acss.appointment_booked_from, t.booking_convenience_fee_emergency, acss.sub_token,acss.custom_token,acss.has_token, acss.emergency_appointment, acss.drive_folder_id, staff.mobile AS doctor_mobile, acss.appointment_booked_from, acss.consulting_type, t.booking_convenience_fee_video, t.booking_convenience_fee_audio, t.booking_convenience_fee_chat, acss.emergency_appointment, acss.custom_token, staff.show_appointment_token, staff.show_appointment_time,  acss.amount, IFNULL(ac.mobile,c.mobile) as patient_mobile, IFNULL(bco.convence_for,'OFFLINE') AS convence_for , acss.booking_validity_attempt, staff.user_id, service.service_amount AS consulting_fee, staff.is_online_consulting, acss.appointment_datetime,acss.queue_number, acss.id, acss.thinapp_id, t.booking_convenience_gst_percentage, acss.appointment_booked_from,acss.appointment_customer_id,acss.children_id,acss.appointment_datetime,acss.queue_number,IFNULL(ac.mobile,c.mobile) AS mobile, acss.appointment_patient_name,acss.`status` ,acss.appointment_staff_id,acss.appointment_address_id,acss.booking_date,acss.slot_time,acss.appointment_service_id,  t.booking_convenience_fee, t.booking_doctor_share_percentage,t.booking_payment_getway_fee_percentage,t.booking_convenience_fee_restrict_ivr, t.booking_convenience_fee_terms_condition, scma.primary_owner_id,scma.primary_owner_share_percentage,scma.primary_mediator_id,scma.primary_mediator_share_percentage,scma.secondary_owner_id,scma.secondary_owner_share_percentage,scma.secondary_mediator_id,scma.secondary_mediator_share_percentage FROM appointment_customer_staff_services AS acss JOIN  thinapps AS t ON t.id = acss.thinapp_id JOIN appointment_staffs AS staff ON staff.id = acss.appointment_staff_id JOIN appointment_services AS service ON service.id = acss.appointment_service_id LEFT JOIN appointment_customers AS ac ON ac.id = acss.appointment_customer_id LEFT JOIN childrens AS c ON c.id = acss.children_id left join smart_clinic_mediator_associate as scma on t.id = scma.thinapp_id LEFT JOIN booking_convenience_orders AS bco ON bco.appointment_customer_staff_service_id = acss.id where acss.id = $orderId limit 1";
                $list = $connection->query($sql);
                if ($list->num_rows) {
    
                    $appointmentData = mysqli_fetch_assoc($list);
                    $date = date("Y-m-d H:i:s");
                    $thin_app_id = $thinappID = $appointmentData['thinapp_id'];
                    $smart_clinic = (Custom::check_app_enable_permission($thin_app_id, 'SMART_CLINIC'))?"YES":"NO";
                    $payment_account_mode = 'PRODUCTION';
                    $testAppArray = Custom::getTestModeApp($thin_app_id);
                    if(in_array($thin_app_id,$testAppArray)){
                        $secretKey = PHONE_PAY_SECRATE_KEY_TEST;
                        $postUrl = PHONE_PAY_API_URL_TEST;
                        $merchantId = PHONE_PAY_MERCHANT_ID_TEST;
                        $payment_account_mode = 'TEST';
                    }
    
    
    
                    $appointment_id = $appointmentCustomerStaffServiceID = $appointmentData['id'];
                    $appointment_booked_from = $appointmentData['appointment_booked_from'];
                    $convenience_for = $appointmentData['convence_for'];
                    $appointmentCustomerID = $appointmentData['appointment_customer_id'];
                    $childrenID = $appointmentData['children_id'];
                    $appointment_datetime = $appointmentData['appointment_datetime'];
                    $queue_number1 = $queue_number = $appointmentData['queue_number'];
                    $mobile = $appointmentData['mobile'];
                    $status = $appointmentData['status'];
                    $appointmentStaffID = $appointmentData['appointment_staff_id'];
                    $appointmentAddressID = $appointmentData['appointment_address_id'];
                    $bookingDate = $appointmentData['booking_date'];
                    $slotTime = $appointmentData['slot_time'];
                    $appointmentServiceID = $appointmentData['appointment_service_id'];
                    $primaryOwnerId = !empty($appointmentData['primary_owner_id'])?$appointmentData['primary_owner_id']:0;
                    $secondaryOwnerId = !empty($appointmentData['secondary_owner_id'])?$appointmentData['secondary_owner_id']:0;
                    $primaryMediatorId = !empty($appointmentData['primary_mediator_id'])?$appointmentData['primary_mediator_id']:0;
                    $secondaryMediatorId = !empty($appointmentData['secondary_mediator_id'])?$appointmentData['secondary_mediator_id']:0;
                    $bookingConvenienceFee = $appointmentData['booking_convenience_fee'];
                    $emergency_appointment = $appointmentData['emergency_appointment'];
                    $doctor_online_consulting_fee = 0;
    
                    if($appointmentData['consulting_type']=='VIDEO'){
                        $bookingConvenienceFee =  $appointmentData['booking_convenience_fee_video'];
                    }else if($appointmentData['consulting_type']=='AUDIO'){
                        $bookingConvenienceFee = $appointmentData['booking_convenience_fee_audio'];
                    }else if($appointmentData['consulting_type']=='CHAT'){
                        $bookingConvenienceFee = $appointmentData['booking_convenience_fee_chat'];
                    }else if($emergency_appointment=='YES'){
                        $bookingConvenienceFee = $appointmentData['booking_convenience_fee_emergency'];
                    }
    
    
                    $bookingDoctorSharePercentage= $bookingMengageShareFee =$bookingDoctorShareFee =0;
                    $bookingPaymentGetwayFeePercentage = $bookingPaymentGetwayFee =$bookingConvenienceGSTPercentage=$bookingConvenienceGSTFee=0;
                    $primaryOwnerShareFee = $secondaryOwnerShareFee = $primaryMediatorShareFee = $secondaryMediatorShareFee=0;
                    $primaryOwnerSharePercentage = $secondaryOwnerSharePercentage = $primaryMediatorSharePercentage = $secondaryMediatorSharePercentage=0;
                    if($appointmentData['pay_clinic_visit_fee_online'] =='YES' || $convenience_for=="ONLINE" || $emergency_appointment=='YES' ){
                        if($appointmentData['booking_validity_attempt']==1){
                            $doctor_online_consulting_fee = $bookingDoctorShareFee = $appointmentData['amount'];
                        }
                        $bookingMengageShareFee  = $bookingConvenienceFee;
                    }else{
    
                        /* PAYMENT GETWAY FEE CALCULATION */
                        $bookingPaymentGetwayFeePercentage = $appointmentData['booking_payment_getway_fee_percentage'];
                        $bookingPaymentGetwayFee = (($appointmentData['booking_payment_getway_fee_percentage'] / 100) * $bookingConvenienceFee);
                        $bookingPaymentGetwayFee = Custom::splitAfterDecimal($bookingPaymentGetwayFee);
    
    
                        /* GST CALCULATION  FEE*/
                        $bookingConvenienceGSTPercentage = $appointmentData['booking_convenience_gst_percentage'];
                        $left_amount = ($bookingConvenienceFee-$bookingPaymentGetwayFee);
                        $bookingConvenienceGSTFee = Custom::splitAfterDecimal((($bookingConvenienceGSTPercentage / 100) * $left_amount));
    
    
    
                        $baseAmount = $bookingConvenienceFee-($bookingPaymentGetwayFee+$bookingConvenienceGSTFee);
                        /* DOCTOR SHARE CALCULATION */
                        $bookingDoctorSharePercentage = $appointmentData['booking_doctor_share_percentage'];
                        $bookingDoctorShareFee = Custom::splitAfterDecimal((($bookingDoctorSharePercentage / 100) * $baseAmount));
    
    
    
                        /* PRIMARY OWNER SHARE CALCULATION */
                        if(!empty($primaryOwnerId)){
                            $primaryOwnerSharePercentage = $appointmentData['primary_owner_share_percentage'];
                            $primaryOwnerShareFee = Custom::splitAfterDecimal((($primaryOwnerSharePercentage / 100) * $baseAmount));
                        }
    
                        /* SECONDARY OWNER SHARE CALCULATION */
                        if(!empty($secondaryOwnerId)){
                            $secondaryOwnerSharePercentage = $appointmentData['secondary_owner_share_percentage'];
                            $secondaryOwnerShareFee = Custom::splitAfterDecimal((($secondaryOwnerSharePercentage / 100) * $baseAmount));
                        }
    
                        /* PRIMARY MEDIATOR SHARE CALCULATION */
                        if(!empty($primaryMediatorId)){
                            $primaryMediatorSharePercentage = $appointmentData['primary_mediator_share_percentage'];
                            $primaryMediatorShareFee = Custom::splitAfterDecimal((($primaryMediatorSharePercentage / 100) * $baseAmount));
                        }
    
                        /* SECONDARY MEDIATOR SHARE CALCULATION */
                        if(!empty($primaryMediatorId)){
                            $secondaryMediatorSharePercentage = $appointmentData['secondary_mediator_share_percentage'];
                            $secondaryMediatorShareFee = Custom::splitAfterDecimal((($secondaryMediatorSharePercentage / 100) * $baseAmount));
                        }
    
                        $total_remaining_amount = ($bookingDoctorShareFee + $primaryOwnerShareFee+$secondaryOwnerShareFee+$primaryMediatorShareFee+$secondaryMediatorShareFee);
                        $bookingMengageShareFee = ($baseAmount - $total_remaining_amount);
    
                    }
    
                    if($status == 'CANCELED')
                    {
                        $chkUniqueSql = "SELECT acss.id FROM appointment_customer_staff_services AS acss WHERE acss.appointment_staff_id = $appointmentStaffID AND acss.appointment_address_id =$appointmentAddressID AND acss.appointment_service_id = $appointmentServiceID AND acss.queue_number = '$queue_number1' AND acss.slot_time ='$slotTime' AND acss.status IN('NEW','CONFIRM','RESCHEDULE') AND DATE(acss.appointment_datetime) = '$bookingDate' AND acss.thinapp_id = $thin_app_id LIMIT 1";
                        $chkUniqueRS = $connection->query($chkUniqueSql);
                        if($chkUniqueRS->num_rows)
                        {
                            if($thin_app_id==607 || $thin_app_id==550 || $thin_app_id==134){
                                $appointment_refund = true;
                            }else{
                                $bookingDate = date('Y-m-d',strtotime($appointment_datetime));
                                $count = Custom::get_sub_token_number($appointmentStaffID, $appointmentServiceID, $appointmentAddressID, $slotTime, $bookingDate);
                                $queue_number1 = $queue_number = $queue_number + (($count + 1) / 10);
                                $updateSql = "UPDATE `appointment_customer_staff_services` SET `cancel_date_time` = '0000-00-00 00:00:00',`cancel_by_user_id` = '0',`status` = 'NEW',`queue_number`='".$queue_number."',`sub_token` = 'YES',`modified` = '".$date."' WHERE `id` = '".$appointmentCustomerStaffServiceID."'";
                                $connection->query($updateSql);
                            }
                        }
                        else
                        {
                            $updateSql = "UPDATE `appointment_customer_staff_services` SET  `cancel_date_time` = '0000-00-00 00:00:00',`cancel_by_user_id` = '0',`status` = 'NEW',`modified` = '".$date."' WHERE `id` = '".$appointmentCustomerStaffServiceID."'";
                            $connection->query($updateSql);
    
                        }
                    }
    
                    $selectSql = "SELECT * FROM `booking_convenience_fee_details` WHERE `appointment_customer_staff_service_id` = '".$appointmentCustomerStaffServiceID."' LIMIT 1";
                    $selectRS = $connection->query($selectSql);
                    if($selectRS->num_rows)
                    {
    
                        $prm = array(
                            "bookingConvenienceFee"=>$orderAmount,
                            "datetime"=>$appointment_datetime,
                            "queue"=>Custom::create_queue_number($appointmentData),
                            "tx_time"=>$txTime,
                            "is_online_consulting"=>$appointmentData['is_online_consulting'],
                            "emergency_appointment"=>$appointmentData['emergency_appointment'],
                            "appointment_booked_from"=>$appointmentData['appointment_booked_from'],
                            "mobile"=>$appointmentData['mobile'],
                            "thin_app_id"=>$appointmentData['thinapp_id'],
                            "doctor_id"=>$appointmentData['appointment_staff_id'],
                            "appointment_id"=>$appointment_id,
                            "convenience_for"=>$convenience_for,
                            'smart_clinic'=>$smart_clinic
                        );
                        $prm = base64_encode(json_encode($prm));
    
                    }
                    else
                    {
    
                        $error = false;
    
                        if (Custom::check_app_enable_permission($thin_app_id, 'SMART_CLINIC')) {
                            $connection->autocommit(false);
                            /* UPDATE APPOINTMENT TABLE PAYMENT STATUS FOR BOOKING CONVENIENCE FEE */
                            $query = "update appointment_customer_staff_services set is_paid_booking_convenience_fee=?, modified=? where id = ?";
                            $stmt_appointment = $connection->prepare($query);
                            $is_paid_booking_convenience_fee ="YES";
                            $stmt_appointment->bind_param('sss',$is_paid_booking_convenience_fee,$date,$appointmentCustomerStaffServiceID );
    
                            /* INSERT BOOKING ORDER DETAIL */
                            $sql = "SELECT id from booking_convenience_fee_details where  appointment_customer_staff_service_id = $appointment_id and thinapp_id = $thinappID limit 1";
                            $list = $connection->query($sql);
                            if (!$list->num_rows) {
    
                                $booking_convenience_order_id =0;
                                $sql = "SELECT id from booking_convenience_orders where  appointment_customer_staff_service_id = $appointment_id and thinapp_id = $thinappID limit 1";
                                $select_data = $connection->query($sql);
                                if ($select_data->num_rows) {
                                    $booking_convenience_order_id = mysqli_fetch_assoc($select_data)['id'];
                                }
    
                                $created_by_user_id =0;
                                $payment_account='MENGAGE';
                                $is_settled='NO';
                                $status='ACTIVE';
                                $payment_getway ='PHONEPAY';
                                
                                $query = "insert into booking_convenience_fee_details  (payment_getway, payment_account_mode, booking_convenience_order_id,thinapp_id, appointment_customer_staff_service_id, appointment_customer_id, children_id, amount, booking_convenience_fee, booking_doctor_share_percentage, booking_doctor_share_fee, booking_payment_getway_fee_percentage, booking_payment_getway_fee, booking_mengage_share_fee, reference_id, tx_status, payment_mode, tx_msg, tx_time, created_by_user_id, payment_account, is_settled, status, created, modified, primary_owner_id, primary_owner_share_percentage, primary_owner_share_fee, primary_mediator_id, primary_mediator_share_percentage, primary_mediator_share_fee, secondary_owner_id, secondary_owner_share_percentage, secondary_owner_share_fee, secondary_mediator_id, secondary_mediator_share_percentage, secondary_mediator_share_fee,booking_convenience_gst_percentage,booking_convenience_gst_fee,doctor_online_consulting_fee) values (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
                                $stmt_booking_detail = $connection->prepare($query);
                                $stmt_booking_detail->bind_param('ssssssssssssssssssssssssssssssssssssssss', $payment_getway, $payment_account_mode, $booking_convenience_order_id,$thinappID,$appointment_id, $appointmentCustomerID,$childrenID,$orderAmount,$bookingConvenienceFee,$bookingDoctorSharePercentage,$bookingDoctorShareFee,$bookingPaymentGetwayFeePercentage,$bookingPaymentGetwayFee,$bookingMengageShareFee,$referenceId,$txStatus,$paymentMode,$txMsg,$txTime,$created_by_user_id,$payment_account,$is_settled,$status,$date,$date,$primaryOwnerId, $primaryOwnerSharePercentage, $primaryOwnerShareFee,$primaryMediatorId,$primaryMediatorSharePercentage,$primaryMediatorShareFee,$secondaryOwnerId,$secondaryOwnerSharePercentage,$secondaryOwnerShareFee,$secondaryMediatorId,$secondaryMediatorSharePercentage,$secondaryMediatorShareFee,$bookingConvenienceGSTPercentage,$bookingConvenienceGSTFee,$doctor_online_consulting_fee);
                                if($stmt_appointment->execute() && $stmt_booking_detail->execute()){
    
                                    /* UPDATE UNIQUE ID FOR ORDER DETAIL TABLE */
                                    $insertID = $stmt_booking_detail->insert_id;
                                    $uniqueID = str_pad($insertID, 10, "0", STR_PAD_LEFT);
                                    $query = "update booking_convenience_fee_details set unique_id =? where id = ?";
                                    $stmt_update_detail = $connection->prepare($query);
                                    $stmt_update_detail->bind_param('ss', $uniqueID,  $insertID);
    
                                    /* UPDATE ORDER TABLE STATUS */
                                    $order_status='PAID';
                                    $query = "update booking_convenience_orders set status =?,modified=? where id = ?";
                                    $stmt_update_order = $connection->prepare($query);
                                    $stmt_update_order->bind_param('sss', $order_status, $currDate, $booking_convenience_order_id);
                                    if($stmt_update_detail->execute() && $stmt_update_order->execute()){
                                        $connection->commit();
                                        $connection->autocommit(true);
                                        $user_id = $appointmentData['user_id'];
                                        if(empty($user_id)){
                                            $user_id = Custom::get_thinapp_admin_data($thin_app_id)['id'];
                                        }
    
    
    
                                        if($appointmentData['pay_clinic_visit_fee_online'] =='YES' || $convenience_for=="ONLINE" || $emergency_appointment=='YES' ){
                                            $post = array();
                                            $post['app_key'] = APP_KEY;
                                            $post['user_id'] = $user_id;
                                            $post['thin_app_id'] = $thin_app_id;
                                            $post['appointment_id'] = $appointment_id;
                                            $post['status'] = "SUCCESS";
                                            $post['transaction_id'] = $referenceId;
                                            $result = json_decode(WebservicesFunction::update_appointment_payment_status($post, true), true);
                                            if (!empty($result['notification_array'])) {
                                                //Custom::appointment_payment_notification($result['notification_array']);
                                            }
                                        }
    
                                        if($appointment_refund===true){
                                            $refund_reason = 'Your token booking time is expired';
                                            $detail_data = Custom::getCashFreeOnlineAmount($appointmentCustomerStaffServiceID,$thin_app_id);
                                            if (!empty($detail_data)) {
                                                $referenceId = $detail_data['refrence_id'];
                                                $medical_product_order_id = $detail_data['medical_product_order_id'];
                                                $order_id = $detail_data['order_id'];
                                                $amount = $detail_data['amount'];
                                                if(!empty($referenceId) && !empty($amount)){
                                                    $result = json_decode(Custom::phonepayRefund($connection,$thin_app_id,$user_id,$medical_product_order_id, $referenceId,$amount,$detail_data['booking_convenience_order_detail_id'],$order_id, $refund_reason,$detail_data['payment_mode']),true);
                                                    if($result['success']==true){
                                                        $refundId =$result['data']['transactionId'];
                                                    }
                                                }
                                            }
    
    
                                        }else{
                                            $background['sms'][] = array(
                                                'message' => '',
                                                'mobile' => $appointmentData['patient_mobile'],
                                                'send_to' => "USER"
                                            );
                                            $background['sms'][] = array(
                                                'message' => '',
                                                'mobile' => $appointmentData['doctor_mobile'],
                                                'send_to' => "DOCTOR"
                                            );
                                            $notification_data = array(
                                                'background' => $background,
                                                'thin_app_id' => $thin_app_id,
                                                'doctor_id' => $appointmentData['appointment_staff_id'],
                                                'user_type' => !empty($appointmentCustomerID)?'CUSTOMER':'CHILDREN',
                                                'patient_id' => !empty($appointmentCustomerID)?$appointmentCustomerID:$childrenID,
                                                'booking_request_from'=>$appointmentData['appointment_booked_from'],
                                                'address_id'=>$appointmentData['appointment_address_id'],
                                                'appointment_id'=>$appointment_id,
                                                'consulting_type'=>$appointmentData['consulting_type'],
                                                'drive_folder_id'=>$appointmentData['drive_folder_id']
                                            );
                                            Custom::send_process_to_background();
                                            $result = Custom::send_book_appointment_notification($notification_data);
                                        }
    
    
    
                                    }else{
                                        $error = true;
                                        $connection->rollback();
                                    }
                                }else{
                                    $error = true;
                                    $connection->rollback();
                                }
    
    
                            }
    
                        }else{
    
                            $user_id = $appointmentData['user_id'];
                            if(empty($user_id)){
                                $user_id = Custom::get_thinapp_admin_data($thin_app_id)['id'];
                            }
                            $post = array();
                            $post['app_key'] = APP_KEY;
                            $post['user_id'] = $user_id;
                            $post['thin_app_id'] = $thin_app_id;
                            $post['appointment_id'] = $appointment_id;
                            $post['status'] = "SUCCESS";
                            $post['transaction_id'] = $referenceId;
                            $result = json_decode(WebservicesFunction::update_appointment_payment_status($post, true), true);
                            if ($appointment_refund===false && !empty($result['notification_array'])) {
                                Custom::sendSmartClinicNotification($appointment_booked_from,$appointmentData, $thin_app_id,$appointment_id,$convenience_for,$appointmentCustomerID,$childrenID);
                            }
    
                            if($appointment_refund===true){
                                $refund_reason = 'Your token booking time is expired';
                                $detail_data = Custom::getCashFreeOnlineAmount($appointmentCustomerStaffServiceID,$thin_app_id);
                                if (!empty($detail_data)) {
                                    $referenceId = $detail_data['refrence_id'];
                                    $medical_product_order_id = $detail_data['medical_product_order_id'];
                                    $order_id = $detail_data['order_id'];
                                    $amount = $detail_data['amount'];
                                    if(!empty($referenceId) && !empty($amount)){
                                        $result = json_decode(Custom::phonepayRefund($connection,$thin_app_id,$user_id,$medical_product_order_id, $referenceId,$amount,$detail_data['booking_convenience_order_detail_id'],$order_id, $refund_reason,$detail_data['payment_mode']),true);
                                        if($result['success']==true){
                                            $refundId =$result['data']['transactionId'];
                                        }
                                    }
                                }
    
    
                            }
                        }
    
                        
    
                    }
    
                    
                    if($appointment_refund===true){
                        if($thin_app_id==607){
                            $callRes = Custom::tokenCanceledCall($mobile,"01414937900","589419");    
                        }
                    
                        $doctorData = Custom::get_doctor_by_id($appointmentData['appointment_staff_id']);
                        $doctor_name = $doctorData['name'];
                        $token_number = $appointmentData['queue_number'];
                        $app_date =date('d/m/Y',strtotime($appointmentData['appointment_datetime']));
                        $cancelWhats = "अपॉइंटमेंट टोकन निरस्त\n\nडॉक्टर का नाम  :- $doctor_name\nदिनांक   :- $app_date\nटोकन  :- $token_number\n\nशमा करे  डॉक्टर $doctor_name का अपॉइंटमेंट टोकन बुक नहीं हो पाया हैं ! अगर अपने टोकन फी का भुक्तान किया हैं तो  कृपया  निश्चिन्त रहिये आपको टोकन फी  सात दिनों के भीतर वापस कर दी जाएगी |\nधन्यवाद|";
                        $res = Custom::sendWhatsappSms($mobile,$cancelWhats,$cancelWhats,$thin_app_id);
                        
                    }
                   
                    die();
    
                }else{
                    die('Invalid Appointment');
                }
    
            }
            else
            {
                $connection = ConnectionUtil::getConnection();
                $sql = "SELECT acss.sub_token, acss.has_token, acss.emergency_appointment, acss.custom_token, staff.show_appointment_token, staff.show_appointment_time,  acss.amount, IFNULL(ac.mobile,c.mobile) as patient_mobile, IFNULL(bco.convence_for,'OFFLINE') AS convence_for , acss.booking_validity_attempt, staff.user_id, service.service_amount AS consulting_fee, staff.is_online_consulting, acss.appointment_datetime,acss.queue_number, acss.id, acss.thinapp_id, t.booking_convenience_gst_percentage, acss.appointment_booked_from,acss.appointment_customer_id,acss.children_id,acss.appointment_datetime,acss.queue_number,IFNULL(ac.mobile,c.mobile) AS mobile, acss.appointment_patient_name,acss.`status` ,acss.appointment_staff_id,acss.appointment_address_id,acss.booking_date,acss.slot_time,acss.appointment_service_id,  t.booking_convenience_fee, t.booking_doctor_share_percentage,t.booking_payment_getway_fee_percentage,t.booking_convenience_fee_restrict_ivr, t.booking_convenience_fee_terms_condition, scma.primary_owner_id,scma.primary_owner_share_percentage,scma.primary_mediator_id,scma.primary_mediator_share_percentage,scma.secondary_owner_id,scma.secondary_owner_share_percentage,scma.secondary_mediator_id,scma.secondary_mediator_share_percentage FROM appointment_customer_staff_services AS acss JOIN  thinapps AS t ON t.id = acss.thinapp_id JOIN appointment_staffs AS staff ON staff.id = acss.appointment_staff_id JOIN appointment_services AS service ON service.id = acss.appointment_service_id LEFT JOIN appointment_customers AS ac ON ac.id = acss.appointment_customer_id LEFT JOIN childrens AS c ON c.id = acss.children_id left join smart_clinic_mediator_associate as scma on t.id = scma.thinapp_id LEFT JOIN booking_convenience_orders AS bco ON bco.appointment_customer_staff_service_id = acss.id where acss.id = $orderId limit 1";
                $list = $connection->query($sql);
                $appointmentData = mysqli_fetch_assoc($list);
                $appointment_datetime = $appointmentData['appointment_datetime'];
                $queue_number1 = $queue_number = $appointmentData['queue_number'];
                $thinapp_id = $appointmentData['thinapp_id'];
                $appointment_id = $appointmentData['id'];
                $currDate = date('Y-m-d H:i:s');
                $updateBookingOrder = "UPDATE `booking_convenience_orders` SET `status` = 'FAILED', `modified`= '".$currDate."' WHERE `order_id` = '".$orderIdRow."'";
                $connection->query($updateBookingOrder);
            }
        }
        else{
            die("Invalid Request");
        }
    }

}
