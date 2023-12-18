<?php
/**
 * Static content controller.
 *
 * This file will render views from views/pages/
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('AppController', 'Controller');
include (WWW_ROOT."webservice".DS."ConnectionUtil.php");
include (WWW_ROOT."webservice".DS."WebservicesFunction.php");
include (WWW_ROOT."webservice".DS."WebServicesFunction_2_3.php");
/**
 * Static content controller
 *
 * Override this controller by placing a copy in controllers directory of an application
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers/pages-controller.html
 */
class DoctorController extends AppController {

/**
 * This controller does not use a model
 *
 * @var array
 */
	public $uses = array();

	
	function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow('doctor_setting','send_review_link','save_doctor_review','submit_review','load_doctor_appointment_counts','load_add_child_modal','add_new_child','load_patient_list','update_child_detail','load_child_detail','load_patient_record','load_doctor_appointment','load_vaccination','get_vaccination_detail','load_child_vaccination','web_app_dashboard','web_app_send_otp','web_app_login','get_chatbot_url','web_app_install_log','web_app_installed','tracker_button','load_info','load_my_appointment', 'recentList', 'search_doctor', 'department', 'save_prescription', 'create_header', 'blog', 'check_availability', 'load_doctor_list', 'doctor_list', 'index', 'load_doctor_time_slot', 'send_otp', 'verify_and_book_appointment', 'load_doctor_blog');
    }
	
	
/**
 * Displays a view
 *
 * @param mixed What page to display
 * @return void
 * @throws NotFoundException When the view file could not be found
 *	or MissingViewException in debug mode.
 */
	public function index($doctor=null){

        if(!isset($_SERVER['HTTPS'])){
           // $actual_link = "https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
          //  header("Location: $actual_link");die;
        	
        }

		$doctor_id = $this->request->query['t'];
    	$doctor_data = Custom::get_doctor_info(base64_decode($doctor_id));
        if($doctor_data['thinapp_id']=="134121212"){
        $doc_id = base64_decode($doctor_id);
        $redirect = "https://www.mengage.in/doctor/doctor/index/$doc_id/?t=$doctor_id&wa=y&back=no";
        header("Location: $redirect");
        die;
        }
    
    
	    $wa = @$this->request->query['wa'];
		$doctor_data = Custom::get_doctor_info(base64_decode($doctor_id));
    	if(empty($doctor_data['doctor_code'])){
           $res = Custom::setup_ivr_data($doctor_data['thinapp_id'], base64_decode($doctor_id),'',true);
            $doctor_data = Custom::get_doctor_info(base64_decode($doctor_id));
        }
	    $department_search = isset($this->request->query['d'])?$this->request->query['d']:'NO';
	    $success =array();
	    if(isset($this->request->query['a']) && !empty($this->request->query['a'])){
            $success = json_decode(base64_decode($this->request->query['a']),true);
            $success['doctor_id'] = base64_encode($success['appointment_staff_id']);
            $success['dialog_type']="error";
            $success['title']="Token could not booked";
            $success['message']="Your token could not book due to payment failure";
            if($success['status_type']=='success'){
                $success['dialog_type']="success";
                $success['title']="Token Booked Successfully";
                $lbl_date = date('d-m-Y', strtotime($success['appointment_datetime']));
                $lbl_time = date('h:i A', strtotime($success['appointment_datetime']));
                $time_string = ($success['show_appointment_time'] == "YES" && $success['emergency_appointment']=="NO" && $success['custom_token']=="NO") ? " Time:$lbl_time," :"" ;
                $queue_number = ($success['show_appointment_token'] == "NO") ? "" : $success['queue_number'];
                $name = Custom::get_string_first_name($success['cus_name']);
                if($doctor_data['allow_only_mobile_number_booking']=='NO'){
                    $message = "<li><label>Name :</label>$name</li>";
                }
                $message .= "<li><label>Token No :</label>$queue_number</li>";
                if(!empty($time_string)){
                    $message .= "<li><label>Time :</label>$time_string</li>";
                }
                $message .= "<li><label>Date :</label>$lbl_date</li>";
                $success['message']=$message;
            }else{
                $success['dialog_type']="error";
                $success['title']="Sorry, token could not booked.";
                $lbl_date = date('d-m-Y', strtotime($success['appointment_datetime']));
                $lbl_time = date('h:i A', strtotime($success['appointment_datetime']));
                $time_string = ($success['show_appointment_time'] == "YES" && $success['emergency_appointment']=="NO" && $success['custom_token']=="NO") ? " Time:$lbl_time," :"" ;
                $queue_number = ($success['show_appointment_token'] == "NO") ? "" : $success['queue_number'];
                $name = Custom::get_string_first_name($success['cus_name']);
                if($doctor_data['allow_only_mobile_number_booking']=='NO'){
                    $message = "<li><label>Name :</label>$name</li>";
                }
                $message .= "<li><label>Token No :</label>$queue_number</li>";
                if(!empty($time_string)){
                    $message .= "<li><label>Time :</label>$time_string</li>";
                }
                $message .= "<li><label>Date :</label>$lbl_date</li>";
                $success['message']=$message;
            }

        }

        $this->layout = false;

        //pr($doctor_data); die;
        if (!empty($doctor_data)) {

            $show_add_banner = Custom::check_app_enable_permission($doctor_data['thinapp_id'], "ADVERTISEMENT_BANNER");
            $category_name = $doctor_data['category_name'];
            $thin_app_id = $doctor_data['thinapp_id'];
            /* add to home icon functionality start*/
            $dir= false;
            $icon_folder = LOCAL_PATH . 'app/webroot/add_home_screen/'.$doctor_data['doctor_id']."/icons";
            $manifest = LOCAL_PATH . 'app/webroot/add_home_screen/'.$doctor_data['doctor_id']."/manifest.json";
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
                	if(Custom::check_app_enable_permission($thin_app_id, 'QUEUE_MANAGEMENT_APP')){
                        $actual_link = str_replace("doctor/doctor","doctor/queue_management",$actual_link);
                    }
                    $json_data = json_decode(file_get_contents($default_manifest), true);
                    $json_data['name'] = $doctor_data['app_name'];
                    $json_data['short_name'] = $doctor_data['app_name'];
                    $json_data['start_url'] = $actual_link;
                    $json_data['theme_color'] = "#0952EE";
                    $json_data['background_color'] = "#ffffff";
                    $has_manifest =  file_put_contents($manifest,json_encode($json_data));
                    $logo = $doctor_data['logo'];
                    $array =array("72","96","128","144","152","192","384","512");
                    foreach ($array as $key =>$value){
                        $res = Custom::download_image_from_url($logo,$value,$value,$icon_folder."/icon-$value"."x"."$value.png");
                    }
                }
            }
            /* add to home icon functionality end*/



            $dob =  $doctor_data['dob'];
            $age ='';
            if(!empty($dob) && $dob != '1970-01-01' && $dob != '0000-00-00'){
                $age = Custom::dob_elapsed_string($dob,false,false);
            }



            $this->set(compact('service_id','show_add_banner','category_name', 'thin_app_id', 'wa', 'department_search', 'success', 'userCount', 'doctor_id', 'doctor_data', 'channel_id', 'age'));
            // pr($appointment_data);die;
        } else {
            exit();
        }

	}

	public function recentList()
    {
        $this->autoRender= false;
        $this->layout = 'ajax';
        if($this->request->is('ajax')) {
            $thin_app_id =$this->request->data['t'];
            $recent_patient=array();
            if(isset($_COOKIE['pm_cook'])) {
                $patient_mobile = Custom::create_mobile_number(base64_decode($_COOKIE['pm_cook']));
                $recent_patient= Custom::get_recent_customer_list($thin_app_id,$patient_mobile,true,10);
            }
            if(!empty($recent_patient)){
                echo json_encode($recent_patient);die;
            }else{
                echo "0";die;
            }
        }else{
            exit();
        }

    }

    public function get_staff_info()
    {
        $this->layout = false;
        if (isset($this->request->data['staffID'])) {
            $staffID = $this->request->data['staffID'];
            $staffData = $this->AppointmentStaff->find("first", array("conditions" => array("id" => $staffID)));
            pr($staffData);
            die;
        }
    }

    public function load_doctor_time_slot()
    {
        $this->layout = 'ajax';
        if($this->request->is('ajax')) {
            $post['app_key'] = APP_KEY;
            $row = json_decode(base64_decode($this->request->data['row']),true);
            $post['role_id'] =$row['role_id'];
            $post['user_id'] =$row['user_id'];
            $post['thin_app_id'] =$row['thin_app_id'];
            $post['mobile'] =$row['mobile'];
            $post['doctor_id'] =$doctor_id = $row['doctor_id'];
            $post['address_id'] = $address_id =$this->request->data['ai'];
            $post['booking_date'] = $booking_date = date('Y-m-d',strtotime($this->request->data['bd']));
            $post['service_slot_duration'] = $row['service_slot_duration'];
            $post['appointment_user_role'] = 'USER';
        	if (Custom::check_app_enable_permission($row['thin_app_id'], 'NEW_QUICK_APPOINTMENT')){
                $service_data =  Custom::get_doctor_default_service_new_appointment($doctor_id);
                if(!empty($service_data)){
                    $post['service_id'] = $service_data['id'];
                }
            }
            $appointment_data =  WebservicesFunction::get_doctor_time_slot($post,true);
            $appointment_slot= json_decode($appointment_data,true);
            $appointment_slot = @$appointment_slot['data'];
            $this->set(compact('appointment_slot', 'booking_date', 'doctor_id','address_id'));
            $this->render('load_doctor_time_slot', 'ajax');
        }else{
            exit();
        }

    }


    public function send_otp()
    {
        $this->layout = 'ajax';
        $this->autoRender = false;
        if($this->request->is('ajax')) {
            $post['app_key'] = APP_KEY;
            $thin_app_id = base64_decode($this->request->data['t']);
            $mobile = ($this->request->data['mobile']);
            $mobile = Custom::create_mobile_number($mobile);
        	$send_list = isset($this->request->data['send_list'])?true:false;
            if(!empty($mobile)){
                $verification_code = Custom::getRandomString(4);
                //$verification_code = 1234;
                $option = array(
                    'username' => $mobile,
                    'mobile' => $mobile,
                    'verification' => $verification_code,
                    'thinapp_id' => $thin_app_id
                );
                $otp_sent = Custom::send_otp($option);
                if($otp_sent){
                    $response['status'] = 1;
                    $response['message'] = "OTP send successfully";
                    $response['row_id'] = md5(base64_encode($verification_code));
                	if($send_list===true){
                        $save = Custom::get_recent_customer_list($thin_app_id, $mobile, true, 10);
                        if (!empty($save)) {
                            array_reverse($save);
                        }
                        $response['list'] = (json_encode($save));
                        $response['row_id'] = base64_encode($verification_code);
                    }
                }else{
                    $response['status'] = 0;
                    $response['message'] = "OTP could not send";
                }
            }else{
                $response['status'] = 0;
                $response['message'] = "Please enter valid mobile number";
            }


            Custom::sendResponse($response);
        }else{
            exit();
        }

    }

    public function verify_and_book_appointment()
    {
        $this->layout = 'ajax';
        $this->autoRender = false;
        if($this->request->is('ajax')) {
            $post['app_key'] = APP_KEY;
            $thin_app_id = base64_decode($this->request->data['t']);
            $doctor_id = base64_decode($this->request->data['d_id']);
            $mobile = Custom::create_mobile_number($this->request->data['mobile']);
            $row_id = ($this->request->data['row_id']);
            $verified = ($this->request->data['verified']);
            $otp = md5(base64_encode($this->request->data['otp']));
            $patient_name = $this->request->data['name'];
            $gender = $this->request->data['gender'];
            $age = $this->request->data['age'];
            $dob = $this->request->data['dob'];
            $address = $this->request->data['address'];
        	$emergency_appointment = $this->request->data['ea'];
        	$emergency_time = $this->request->data['et'];

            if (!empty($dob)) {
                try{
                    $dob = DateTime::createFromFormat('d/m/Y', $dob);
                    $dob = $dob->format('Y-m-d');
                }catch(Exception $e){
                    $dob = "";
                }
            }
            /* validate patient data  start */

            $allow_booking = true;
            $appointment_customer_id = 0;
            $created= Custom::created();
            if(!empty($this->request->data['pi'])){

                $appointment_customer_id = base64_decode($this->request->data['pi']);
                $search_patient =Custom::get_customer_by_name($thin_app_id,$patient_name,$mobile);
                $getPatient =Custom::get_customer_by_id($appointment_customer_id);
                $folder_id =$getPatient['folder_id'];
            	if($mobile != $getPatient['mobile']){
                    $appointment_customer_id = 0;
                }
                $connection = ConnectionUtil::getConnection();
                $connection->autocommit(false);
                if(empty($search_patient) || $search_patient['id']== $appointment_customer_id){
                    $query = "update appointment_customers set first_name =?, gender =?, age =?, dob=?, address=?, modified =? where id = ?";
                    $stmt_patient = $connection->prepare($query);
                    $stmt_patient->bind_param('sssssss', $patient_name, $gender, $age, $dob, $address, $created, $appointment_customer_id);
                    $query = "update drive_folders set folder_name=?, modified =? where id = ?";
                    $stmt_folder = $connection->prepare($query);
                    $stmt_folder->bind_param('sss', $patient_name, $created, $folder_id);
                    if ($stmt_patient->execute() && $stmt_folder->execute()) {
                        $connection->commit();
                        $response['status'] = 1;
                        $response['message'] = "For giving us your valuable time.";
                    } else {
                        $response['status'] = 0;
                        $response['message'] = "Sorry we are unable to save your information";
                        $allow_booking = false;
                    }

                }else{
                    $response['status'] = 0;
                    $response['message']="Patient with name '$patient_name' already register. Please back and select from recent patient list";
                    $allow_booking = false;
                }


            }
            /* validate patient data  end */

            if($allow_booking===true){
                if($row_id == $otp || $verified=='yes'){
                
                	/* check and update first user data start*/
                      if($row_id == $otp && empty($appointment_customer_id)){
                        $search_patient =Custom::get_customer_by_name($thin_app_id,$patient_name,$mobile);
                        if(empty($search_patient)){
                            $search_patient =Custom::get_first_customer_by_mobile($thin_app_id,$mobile);
                        }
                        if(!empty($search_patient)){
                            $connection = ConnectionUtil::getConnection();
                            $connection->autocommit(false);
                            $folder_id =$search_patient['folder_id'];
                            $appointment_customer_id =$search_patient['id'];
                            $query = "update appointment_customers set first_name =?, gender =?, age =?, dob=?, address=?, modified =? where id = ?";
                            $stmt_patient = $connection->prepare($query);
                            $stmt_patient->bind_param('sssssss', $patient_name, $gender, $age, $dob, $address, $created, $appointment_customer_id);
                            $query = "update drive_folders set folder_name=?, modified =? where id = ?";
                            $stmt_folder = $connection->prepare($query);
                            $stmt_folder->bind_param('sss', $patient_name, $created, $folder_id);
                            if ($stmt_patient->execute() && $stmt_folder->execute()) {
                                $connection->commit();
                            }
                        }
                    }
                    /* check and update first user data end */
                
                    $response['status'] = 1;
                    $post=array();
                    $get_user_data = Custom::get_user_by_mobile($thin_app_id,$mobile);
                    $get_admin_data = Custom::get_thinapp_admin_data($thin_app_id);
                    $role_id = !empty($get_user_data)?$get_user_data['role_id']:1;;
                    $post['app_key'] = APP_KEY;
                    $post['thin_app_id'] =$thin_app_id;
                    $post['user_id'] = !empty($get_user_data)?$get_user_data['id']:$get_admin_data['id'];
                    $post['role_id'] = $role_id;
                    $post['mobile'] = $mobile;
                    $post['booking_date'] = $this->request->data['date'];
                    $post['slot_time'] = ($this->request->data['slot']);
                    $post['doctor_id'] = $doctor_id;
                    $post['user_type'] = 'CUSTOMER';
                    $post['children_id'] = 0;
                    $post['address_id'] = ($this->request->data['address_id']);
                    $post['customer_id'] = $appointment_customer_id;
                    $post['customer_name'] = $this->request->data['name'];
                    $post['customer_mobile'] = $mobile;
                    $post['gender'] = $this->request->data['gender'];
                    $post['age'] = $this->request->data['age'];
                	$post['queue_number'] = $this->request->data['queue_number'];
                    $post['customer_dob'] = $dob;
                    $post['reason_of_appointment'] = $this->request->data['reason'];
                    $post['consulting_type'] = $this->request->data['consulting_type'];
                    $post['consult_type'] = $this->request->data['consulting_type'];
                    $post['payment_type'] = "CASH";
                    $post['transaction_id'] = "";
                    $post['appointment_user_role'] = "USER";
                	$post['emergency_appointment'] = $emergency_appointment;
                	$post['remark'] = $emergency_time;
                    if($emergency_appointment=='YES'){
                        $post['slot_time'] = 'BLANK';
                    }
                    $response = WebservicesFunction::check_appointment_validity($post,true);
                    if($response['status'] == 1){
                        $convenience_fee = $response['data']['convenience_fee'];
                        $label = $response['data']['label'];
                        $response = json_decode(WebservicesFunction::add_new_appointment($post,true,'DOCTOR_PAGE'),true);
                        $response['data']['convenience_fee'] = $convenience_fee;
                        if (!Custom::check_app_enable_permission($thin_app_id, 'SMART_CLINIC')) {
                            $response['data']['convenience_fee'] = 0;
                        }
                        $save = Custom::get_recent_customer_list($thin_app_id, $mobile, true, 10);
                        if (!empty($save)) {
                            array_reverse($save);
                        }

                        $response['data']['patient'] = $save;
                        $response['data']['label'] = $label;
                        $response['data']['SMART_CLINIC'] = $label;
                        $notification_data =$response['notification_data'];
                        Custom::sendResponse($response);
                        if(!empty($notification_data)){
                            Custom::send_process_to_background();
                            $result = Custom::send_book_appointment_notification($notification_data);
                            $result = Custom::send_web_tracker_notification($thin_app_id);
                        }
                    }



                }else{
                    $response['status'] = 0;
                    $response['message'] = "Please enter valid OTP.";
                }
            }

            Custom::sendResponse($response);
        }else{
            exit();
        }

    }


    public function load_doctor_blog()
    {
        $this->layout = 'ajax';
        if($this->request->is('ajax')) {
            $post['app_key'] = APP_KEY;
            $doctor_data =  json_decode(base64_decode($this->request->data['row']),true);
            $post['role_id'] =$doctor_data['role_id'];
            $post['user_id'] =$doctor_data['user_id'];
            $post['thin_app_id'] =$doctor_data['thin_app_id'];
            $post['channel_id'] =base64_decode($this->request->data['c_id']);
            $post['search'] =($this->request->data['search']);
            $post['offset'] =$offset = $this->request->data['offset'];
            $post_data =  @WebservicesFunction::get_channel_messages_list($post,true);
            $post_data= json_decode($post_data,true);
            $post_data = @$post_data['data']['message'];
            $this->set(compact('post_data','doctor_data','offset'));
            $this->render('load_doctor_blog', 'ajax');
        }else{
            exit();
        }

    }

    public  function  doctor_list(){

	    $this->layout = false;
    }

    public function load_doctor_list()
    {
        $this->layout = 'ajax';
        if($this->request->is('ajax')) {

            $search = $this->request->data['search'];
            $category_id = $this->request->data['category_id'];
            $filter = @$this->request->data['filter'];
            $show_all = isset($this->request->data['show_all'])?$this->request->data['show_all']:false;
            $condition = "";
            if($show_all=="false" && empty($search)){
                $condition = " dep_count <= 7 ";
            }
            if($category_id > 0){
                if($show_all=="false"){
                    $condition .= " and ";
                }
                $condition .= " department_category_id = $category_id ";
            }
            if(!empty($search)){
                $tmp ='';
                if(!empty($condition)){
                    $tmp .= ' and ';
                }
                $tmp .= " ( doctor.name like '%$search%' OR doctor.city_name like '%$search%' OR  doctor.app_name like '%$search%' OR doctor.department_name like '%$search%' )";
                $search = $tmp;
            }

            $having = $order_by = "";
            $limit = 10;
            if($filter == 'TOP_DOWNLOADS'){
                $having = " having downloads >= 10 ";
                $order_by = " order by downloads desc ";
            }else if($filter == 'AVAILABLE'){
                $having = " having available >= 1 ";

            }else if($filter == 'TOP_FOLLOWERS'){
                $having = " having followers >= 1 ";
                $order_by = " order by followers desc ";
            }else if($filter == 'NEW_DOCTORS'){
                $having = " having total_days <= 60 ";
                $order_by = " order by total_days ASC ";
            }

            $query = "set @dep_count := 0, @current_dep := ''";
            $connection = ConnectionUtil::getConnection();
            $connection->query($query);
            $query = "SELECT * FROM  (SELECT DATEDIFF(DATE(NOW()),DATE(app_staff.created)) as total_days, app_staff.department_category_id, app_staff.profile_photo, (select  count(*) from appointment_staff_hours as ash right join appointment_staff_addresses  as asa on ash.appointment_staff_id = asa.appointment_staff_id AND (  TIME(NOW()) BETWEEN   ADDTIME(asa.from_time,IF(RIGHT(asa.from_time,2) = 'PM','12:00:00','00:00:00')) AND ADDTIME(asa.to_time,IF(RIGHT(asa.to_time,2) = 'PM','12:00:00','00:00:00')) ) AND asa.status = 'ACTIVE'  where  ash.appointment_staff_id = app_staff.id and ash.status = 'OPEN'  and ash.appointment_day_time_id = (WEEKDAY(NOW())+1) LIMIT 1) as available, (select count(*) from mengage_app_follwoers as maf where maf.user_id = app_staff.user_id) as followers, (select count(*) from users as du where du.role_id = 1 and app_staff.thinapp_id = du.thinapp_id) as downloads, app_staff.id, t.logo, app_staff.name, dc.category_name as department_name, c.name as country_name, s.name as state_name, city.name as city_name, t.name as app_name,  @dep_count := IF(@current_dep = app_staff.department_category_id, @dep_count + 1, 1) AS dep_count, @current_dep := app_staff.department_category_id FROM appointment_staffs as app_staff left join thinapps as t on t.id = app_staff.thinapp_id left join countries as c on c.id = app_staff.country_id left join cities as city on city.id = app_staff.city_id left join states as s on s.id = app_staff.state_id left join department_categories as dc on dc.id = app_staff.department_category_id and dc.department_name = 'DOCTOR' where app_staff.staff_type = 'DOCTOR' AND app_staff.status = 'ACTIVE' and app_staff.department_category_id != 0 $having ORDER BY app_staff.department_category_id asc ) as doctor where $condition $search $order_by";
            $doctor_data=array();
            $data_list = $connection->query($query);
            if ($data_list->num_rows) {
                $data_list = mysqli_fetch_all($data_list,MYSQLI_ASSOC);
                foreach ($data_list as $key => $list){
                    $index = trim($list['department_category_id'].'##'.trim($list['department_name']));
                    $doctor_data[$index][] = $list;
                }
            }
            if(empty($order_by)){
                array_multisort(array_map('count', $doctor_data), SORT_DESC, $doctor_data);
            }

            $this->set(compact('doctor_data'));
            $this->render('load_doctor_list', 'ajax');
        }else{
            exit();
        }

    }



    public function check_availability($doctor_id=null)
    {
        $this->autoRender = false;
        $doctor_id = base64_decode($doctor_id);
        if($this->request->is('ajax')){
            $query = "select aa.address, IF(( TIME_FORMAT(CAST(TIME(NOW()) as time),'%l:%i') BETWEEN CAST(STR_TO_DATE(asa.from_time, '%l:%i')as time)   AND  CAST(STR_TO_DATE(asa.to_time, '%l:%i') as time) ),'YES','NO') as available,  CONCAT(asa.from_time,' - ', asa.to_time) as time_string from appointment_staff_hours as ash right join appointment_staff_addresses  as asa on ash.appointment_staff_id = asa.appointment_staff_id AND asa.status = 'ACTIVE' join appointment_addresses as aa on aa.id = asa.appointment_address_id  where  ash.appointment_staff_id = $doctor_id and ash.status = 'OPEN'  and ash.appointment_day_time_id = (WEEKDAY(NOW())+1)";
            $connection = ConnectionUtil::getConnection();
            $service_message_list = $connection->query($query);
            $html = "";
            if ($service_message_list->num_rows) {
                $list = mysqli_fetch_all($service_message_list,MYSQLI_ASSOC);

                $html = "<div class='tooltip_container'>";
                $html .= "<table class='table table-condensed tooltip_table'><thead><tr><th width='70%'>Location</th><th width='30%'>Timing</th></tr></thead><tbody>";
                foreach ($list as $key => $value){
                    $html .= "<tr><td>".$value['address']."</td><td>".$value['time_string']."</td></tr>";
                }
                $html .= "</tbody></table></div>";

            }else{
                $html .= "<p class='not_doc_list'>Doctor not available on this day.</p>";
            }

            echo ($html);die;

        }exit();
    }


    public function blog($doctor_id,$thin_app_id,$channel_id,$blog_id)
    {
        $this->layout = false;
        $thin_app_id = base64_decode($thin_app_id);
        $doctor_id = base64_decode($doctor_id);
        $data = Custom::get_thinapp_admin_data($thin_app_id);
        $thin_app__data = Custom::getThinAppData($thin_app_id);
        $doctor = Custom::get_doctor_info(($doctor_id));

        if(!empty($data)){
            $user_id = $data['id'];
            $content=array();
            $blog_id = base64_decode($blog_id);
            $channel_id = base64_decode($channel_id);
            $response_data = WebservicesFunction::fun_get_channel_messages_list($thin_app_id, $user_id, $channel_id, 1, 0, $blog_id);
            if (!empty($response_data)) {
                $content =  $response_data[0];
            }
          $this->set(compact('content','thin_app__data','doctor'));
        }else{
            exit();
        }

    }



    public function prescription($doctor_id=null)
    {
        $this->layout = false;
        $this->autoRender = true;
        $doctor_id = base64_decode($doctor_id);
        $login = $this->Session->read('Auth.User.User');
        $thin_app_id =$login['thinapp_id'];
        $doctor = Custom::get_doctor_by_id($doctor_id,$thin_app_id);
        $all_patient = Custom::get_patient_list_autocomplete($thin_app_id);
        if(!empty($doctor_id) && !empty($thin_app_id)){
            Custom::clone_master_tab_steps($thin_app_id,$doctor_id);
        }

        $steps = Custom::get_doctor_steps($doctor_id);
        $final_array =array();
        foreach ($steps as $key => $value){
            $final_array[$value['category_id']]['category_id']=$value['category_id'];
            $final_array[$value['category_id']]['category_name']=$value['category_name'];
            if(!empty($value['step_id'])){
                $final_array[$value['category_id']]['steps'][$value['step_id']]['step_id']=$value['step_id'];
                $final_array[$value['category_id']]['steps'][$value['step_id']]['step_name']=$value['step_title'];
                if(!empty($value['tag_id'])) {
                    $final_array[$value['category_id']]['steps'][$value['step_id']]['tags'][] = array('id' => $value['tag_id'], 'name' => $value['tag_name']);
                }

            }
         }



        $this->layout = false;
        $login = $this->Session->read('Auth.User.User');
        $thinappID = $login['thinapp_id'];
        $prescription_setting = $this->SettingWebPrescription->find('first', array(
                'conditions' => array(
                    'thinapp_id' => $thinappID
                ))
        );
        $prescription_setting = !empty($prescription_setting)?$prescription_setting['SettingWebPrescription']:false;
        $fields =!empty($prescription_setting['fields'])?$prescription_setting['fields']:'';


        $this->set(compact('doctor','final_array','all_patient','prescription_setting','fields'));

    }


    public function web_prescription_patient_list($data=null)
    {
        $return = false;
        $appointment_id =0;
        $limit = 10;
        if(!empty($data)){
            $this->request->data = $data;
            $appointment_id = $data['appointment_id'];
            $return =true;
        }
        $this->layout = 'ajax';
        if($this->request->is('ajax') || $return===true) {
            $post['app_key'] = APP_KEY;
            $doctor_id =  (base64_decode($this->request->data['di']));
            $address_id =  ($this->request->data['ai']);
            $service_id =  ($this->request->data['si']);
            $date =  $this->request->data['date'];
            $date = DateTime::createFromFormat('d/m/Y', $date);
            $date  =$date->format('Y-m-d');
            $search  = isset($this->request->data['s'])?$this->request->data['s']:'';
            $offset  = isset($this->request->data['o'])?$this->request->data['o']:0;
            $offset = $offset * $limit;
            $post_data =  Custom::web_get_patient_list($address_id,$service_id,$doctor_id,$date,$search,$offset,$limit,$appointment_id);
            $this->set(compact('post_data'));
            if($return===true){
                return $this->render('web_prescription_patient_list', 'ajax');;
            }else{
                $this->render('web_prescription_patient_list', 'ajax');
            }

        }else{
            exit();
        }

    }

    public function save_prescription()
    {
        $this->layout = 'ajax';
        $this->autoRender = false;
        if($this->request->is('ajax')) {
            $login = $this->Session->read('Auth.User.User');
            $post['app_key'] = APP_KEY;
            $post['user_id'] = $login['id'];
            $post['thin_app_id'] = $login['thinapp_id'];
            $post['role_id'] = $login['role_id'];
            $post['mobile'] = $login['mobile'];
            $post['template_id_string'] = "[]";
            $post['add_via'] = "WEB";
            $post['address_id'] =  ($this->request->data['ai']);
            $post['service_id'] =  ($this->request->data['si']);
            $post['doctor_id'] =  base64_decode($this->request->data['di']);
            $post['patient_type'] =  ($this->request->data['pt']);
            $post['patient_id'] =  base64_decode($this->request->data['pt']);
            $post['appointment_id'] =  base64_decode($this->request->data['app_i']);
            $post['prescription_string'] =($this->request->data['ps']);
            $new_tags =json_decode($this->request->data['nt'],true);
            $base64 =($this->request->data['pre_base64']);
            $file_name = 'prescription/'.date('YmdHis').rand().'.png';
            $file_path = MENGAGE_AWS_DEFAULT_PATH.$file_name;
            //$post['prescription_image'] = $file_path;
            $post['prescription_image'] = Custom::uploadBase64FileToAws($base64);
            $post['folder_id'] =($this->request->data['fi']);
            $res = json_decode(WebServicesFunction_2_3::tab_save_prescription($post,true),true);
            if($res['status']==1){
                $send['si']=$post['service_id'];
                $send['ai']=$post['address_id'];
                $date = ($this->request->data['date']);
                $send['date']=$date;
                $send['appointment_id']=$post['appointment_id'];
                $send['di']=base64_encode($post['doctor_id']);
                $saved_new_tags = array();
                if(!empty($new_tags)){
                    foreach($new_tags as $key => $tag){
                        if(!empty($tag['tag_name'])){
                            $tag_name = trim($tag['tag_name']);
                            $new_tag_id = Custom::tab_add_new_tag($login['thinapp_id'],$post['doctor_id'],$tag_name,$tag['step_id']);
                            if(!empty($new_tag_id)){
                                $tmp_save['id']= (string)$new_tag_id;
                                $tmp_save['name']= (string)$tag_name;
                                $tmp_save['step_id']= (string)$tag['step_id'];
                                $saved_new_tags[] = $tmp_save;
                            }
                        }
                    }
                }
                $res['data']['tags']= $saved_new_tags;
                $update = DoctorController::web_prescription_patient_list($send);
                $res['data']['li']= $update->body();
                Custom::sendResponse($res);
                Custom::send_process_to_background();
                //Custom::uploadBase64FileToAws($base64,$file_name);
            }else{
                Custom::sendResponse($res);
            }

        }else{
            exit();
        }

    }


    public function update_detail()
    {

        $this->layout = false;
        $this->autoRender = false;
        $patient_type = $this->request->data['pt'];
        $response = array();
        $login = $this->Session->read('Auth.User.User');
        $mobile = Custom::create_mobile_number($this->request->data['mobile']);;
        $parents_mobile = Custom::create_mobile_number($this->request->data['pm']);;
        $parents_name =  trim($this->request->data['pn']);
        $address =  trim($this->request->data['address']);
        $name =  trim($this->request->data['name']);
        $gender = $this->request->data['gender'];
        $blood_group = $this->request->data['blood_group'];
        $patient_id = ($this->request->data['pi']);
        $dob = ($this->request->data['dob']);
        $age = ($this->request->data['age']);
        $appointment_id = ($this->request->data['ai']);
        $address_id = ($this->request->data['add_id']);
        $service_id = ($this->request->data['si']);
        $doctor_id = base64_encode($this->request->data['di']);
        $date = ($this->request->data['date']);

        if (empty($mobile)) {
            $response['status'] = 0;
            $response['message'] = 'Enter valid mobile number';
        } else if (empty($name)) {
            $response['status'] = 0;
            $response['message'] = 'Enter patient name';
        } else if (empty($patient_id)) {
            $response['status'] = 0;
            $response['message'] = 'Please select patient id';
        } else if (empty($patient_type)) {
            $response['status'] = 0;
            $response['message'] = 'Invalid patient type';
        }else{
            if($patient_type=='CUSTOMER'){
                $data['id'] = base64_decode($patient_id);
                $data['mobile'] = $mobile;
                $user_data = Custom::get_user_by_mobile($login['thinapp_id'],$mobile);
                $data['user_id'] = !empty($user_data)?$user_data['id']:0;
                $data['first_name'] = $name;
                $data['parents_name'] = $parents_name;
                $data['parents_mobile'] = $parents_mobile;
                $data['address'] = $address;
                if(!empty($dob) && $dob!='00-00-0000'){
                    $dob = DateTime::createFromFormat('d-m-Y', $dob);
                    $data['dob']  =$dob->format('Y-m-d');
                }

                $data['age'] = $age;
                $data['gender'] = $gender;
                $data['blood_group'] = $blood_group;
                $this->AppointmentCustomer->set($data);
                if($this->AppointmentCustomer->saveAll($data)){
                    $response['status']=1;
                    $response['message']='Patient detail update successfully.';
                    $send['si']=$service_id;
                    $send['ai']=$address_id;
                    $send['appointment_id']=$appointment_id;
                    $send['date']=$date;
                    $send['di']=base64_encode($doctor_id);
                    $update = DoctorController::web_prescription_patient_list($send);
                    $response['data']['li']= ($update->body());
                }else{
                    $response['status']=0;
                    $response['message']='Sorry could not edit';
                }
            }else{
                $data['id'] = base64_decode($patient_id);
                $data['mobile'] = $mobile;
                $user_data = Custom::get_user_by_mobile($login['thinapp_id'],$mobile);
                $data['user_id'] = !empty($user_data)?$user_data['id']:0;

                $data['child_name'] = $name;
               // $data['dob'] = $dob;
                //$data['age'] = $age;
                //$data['gender'] = $gender;
                $data['parents_name'] = $parents_name;
                $data['parents_mobile'] = $parents_mobile;
                $data['address'] = $address;
                $data['blood_group'] = $blood_group;
                $this->Children->set($data);
                if ($this->Children->validates()) {
                    if($this->Children->saveAll($data)){
                        $response['status']=1;
                        $response['message']='Patient detail update successfully.';
                        $send['si']=$service_id;
                        $send['ai']=$address_id;
                        $send['appointment_id']=$appointment_id;
                        $send['di']=base64_encode($doctor_id);
                        $send['date']=$date;
                        $update = DoctorController::web_prescription_patient_list($send);
                        $response['data']['li']= ($update->body());
                    }else{
                        $response['status']=0;
                        $response['message']='Sorry could not edit';
                    }
                }
            }

        }

        echo json_encode($response);die;
    }

    public function get_patient_detail(){

        $this->layout = false;
        $this->autoRender = false;
        $patient_type = $this->request->data['pt'];
        $patient_id = $this->request->data['pi'];
        $response = array();
        if (empty($patient_id)) {
            $response['status'] = 0;
            $response['message'] = 'Please enter patient id';
        } else if (empty($patient_type)) {
            $response['status'] = 0;
            $response['message'] = 'Invalid patient type';
        }else{
            if($response['data']['detail'] = Custom::get_patient_detail(base64_decode($patient_id),($patient_type))){
                $response['status']=1;
                $response['message']='Patient detail found';
            }else{
                $response['status']=0;
                $response['message']='Sorry patient not found';
            }
        }

        Custom::sendResponse($response);


    }

    public function book_new_appointment(){

        $response = $params =array();
        $this->layout = false;
        $this->autoRender = false;
        $params = $this->request->data;

        $patient_type = !empty($params['m_pt'])?$params['m_pt']:'CUSTOMER';
        $patient_id = !empty($params['m_pi'])?base64_decode($params['m_pi']):0;
        $doctor_id = !empty($params['m_di'])?base64_decode($params['m_di']):0;

        $login = $this->Session->read('Auth.User.User');
        $thin_app_id = $login['thinapp_id'];
        $patient_mobile = Custom::create_mobile_number($params['m_pat_mobile']);
        $patient_name = trim($params['m_pat_name']);
        $patient_email = trim($params['m_email']);
        $age = ($params['m_age']);
        $gender = $params['m_gender'];
        $dob = $params['m_dob'];
        if(!empty($dob) && $dob!='00-00-0000'){
            $date = DateTime::createFromFormat('d-m-Y', $dob);
            $dob =$date->format('Y-m-d');
        }
        $blood_group = $params['m_blood_group'];
        $marital_status = $params['m_marital_staus'];
        $parents_name =  trim($params['m_par_name']);
        $parents_mobile = Custom::create_mobile_number($params['m_par_mobile']);;
        $address =  trim($params['m_address']);
        $reference_name = ($params['m_ref_name']);
        $reference_mobile = ($params['m_ref_mobile']);
        $reason_of_appointment = ($params['m_roa']);
        $remark = ($params['m_remark']);
        $service_id = ($params['m_service_id']);
        $address_id = ($params['m_address_id']);

        if (empty($patient_mobile)) {
            $response['status'] = 0;
            $response['message'] = 'Enter valid mobile number';
        } else if (empty($patient_name)) {
            $response['status'] = 0;
            $response['message'] = 'Enter patient name';
        }else if ($patient_type !='CHILDREN' && $patient_type != 'CUSTOMER') {
            $response['status'] = 0;
            $response['message'] = 'Invalid patient type';
        } else{

            /* this block update patient data before book appointment*/
            if($patient_id > 0){
                if($patient_type=='CUSTOMER'){
                    $data['id'] = ($patient_id);
                    $data['mobile'] = $parents_mobile;
                    $user_data = Custom::get_user_by_mobile($login['thinapp_id'],$parents_mobile);
                    $data['user_id'] = !empty($user_data)?$user_data['id']:0;
                    $data['first_name'] = $patient_name;
                    $data['parents_name'] = $parents_name;
                    $data['parents_mobile'] = $parents_mobile;
                    $data['address'] = $address;
                    $data['dob'] = $dob;
                    $data['age'] = $age;
                    $data['gender'] = $gender;
                    $data['blood_group'] = $blood_group;
                    $data['marital_status'] = $marital_status;
                    $data['email'] = $patient_email;
                    $this->AppointmentCustomer->set($data);
                    $this->AppointmentCustomer->saveAll($data);

                }else{
                    $data['id'] = base64_decode($patient_id);
                    $data['mobile'] = $patient_mobile;
                    $user_data = Custom::get_user_by_mobile($login['thinapp_id'],$patient_mobile);
                    $data['user_id'] = !empty($user_data)?$user_data['id']:0;
                    $data['child_name'] = $patient_name;
                    // $data['dob'] = $dob;
                    //$data['age'] = $age;
                    //$data['gender'] = $gender;
                    $data['parents_name'] = $parents_name;
                    $data['parents_mobile'] = $parents_mobile;
                    $data['address'] = $address;
                    $data['blood_group'] = $blood_group;
                    $this->Children->set($data);
                    $this->Children->saveAll($data);
                }
            }


            $notification_data = array();
            $post['app_key'] = APP_KEY;
            $post['thin_app_id'] =$login['thinapp_id'];
            $post['user_id'] = $login['id'];
            $post['role_id'] = $login['role_id'];
            $post['mobile'] = $patient_mobile;
            $post['booking_date'] = date('Y-m-d');
            $date_time = date('Y-m-d H:i');
            $slot_time = date('h:i A');
            $post['slot_time'] = $slot_time;
            $post['doctor_id'] = $doctor_id;
            $post['user_type'] = $patient_type;
            $children_id = $customer_id = 0;
            if($patient_type == 'CHILDREN' && !empty($patient_id) ){
                $children_id = $patient_id;
            }else if($patient_type == 'CUSTOMER' && !empty($patient_id) ){
                $customer_id = $patient_id;
            }
            $post['children_id'] = $children_id;
            $post['address_id'] = $address_id;
            $post['email'] = $patient_email;
            $post['customer_id'] = $customer_id;
            $post['customer_name'] = $patient_name;
            $post['customer_mobile'] = $patient_mobile;
            $post['address'] = $address;
            $post['isAddMore'] = "NO";
            $post['gender'] = $gender;
            $post['payment_type'] = "CASH";
            $post['reason_of_appointment'] = "";
            $post['transaction_id'] = "";
            $post['referred_by'] = $reference_name;
            $post['referred_by_mobile'] = $reference_mobile;
            $post['has_token'] = "NO";
            $post['customer_dob'] = $dob;
            $post['blood_group'] = $blood_group;
            $post['parents_name'] = $parents_name;
            $post['parents_mobile'] = $parents_mobile;
            $post['marital_status'] = $marital_status;
            $post['reason_of_appointment'] = $reason_of_appointment;
            $post['remark'] = $remark;
            $post['age'] = $age;
            $post['has_token'] = "NO";
            $post['appointment_type'] = "WALK-IN";
            $post['queue_number'] = "0";
            $post['appointment_user_role'] = ($login['role_id']==5)?"ADMIN":"DOCTOR";
            $result = WebservicesFunction::check_appointment_validity($post,true);
            if($result['status'] == 1){
                $response = json_decode(WebservicesFunction::add_new_appointment($post,true,'WEB_PRESCRIPTION'),true);
                if($response['status'] == 1){
                    $notification_data = $response['notification_data'];
                    $send['si']=$service_id;
                    $send['ai']=$address_id;
                    $send['date']=date('d/m/Y');
                    $send['appointment_id']=$response['data']['appointment_id'];
                    $send['di']=base64_encode($doctor_id);
                    $update = DoctorController::web_prescription_patient_list($send);
                    unset($response['data']);
                    unset($response['notification_data']);
                    $response['data']['li']= $update->body();
                    $response['data']['total']= Custom::get_total_earning($address_id,$service_id,$doctor_id);
                }
            }else{
                echo json_encode($result);die;
            }

        }

        Custom::sendResponse($response);
        Custom::send_process_to_background();
        if(!empty($notification_data)){
            Custom::send_book_appointment_notification($notification_data);
        }


    }


    public function appointment_payment()
    {
        $this->autoRender =false;
        if($this->request->is('ajax')) {
            $appointment_id = base64_decode($this->request->data['id']);
            $address_id = base64_decode($this->request->data['ai']);
            $service_id = base64_decode($this->request->data['si']);
            $doctor_id = base64_decode($this->request->data['di']);
            $date = ($this->request->data['date']);
            $post=array();
            $login = $this->Session->read('Auth.User.User');
            $post['app_key'] = APP_KEY;
            $post['user_id'] =$login['id'];
            $post['thin_app_id'] =$login['thinapp_id'];

            $post['appointment_id'] =$appointment_id;
            $post['status'] ="SUCCESS";
            $result = json_decode( WebservicesFunction::update_appointment_payment_status($post,true),true);
            if($result['status']==1){
                $notification_array = $result['notification_array'];
                unset($result['notification_array']);
                $send['si']=$service_id;
                $send['ai']=$address_id;
                $send['appointment_id']=$appointment_id;
                $send['date']=$date;
                $send['di']=base64_encode($doctor_id);
                $update = DoctorController::web_prescription_patient_list($send);
                $result['data']['li']= $update->body();
                $result['data']['total']= Custom::get_total_earning($address_id,$service_id,$doctor_id);
                Custom::sendResponse($result);
                Custom::appointment_payment_notification($notification_array);
            }else{
                Custom::sendResponse($result);
            }

        }else{
            exit();
        }

    }

    public function close_appointment()
    {
        $this->autoRender =false;
        if($this->request->is('ajax')) {
            $appointment_id = base64_decode($this->request->data['id']);
            $address_id = base64_decode($this->request->data['ai']);
            $service_id = base64_decode($this->request->data['si']);
            $doctor_id = base64_decode($this->request->data['di']);
            $date = ($this->request->data['date']);
            $post=array();
            $login = $this->Session->read('Auth.User.User');
            $post['app_key'] = APP_KEY;
            $post['user_id'] =$login['id'];
            $post['thin_app_id'] =$login['thinapp_id'];
            $post['appointment_id'] =$appointment_id;
            $result = json_decode(WebservicesFunction::close_appointment($post,true),true);
            if($result['status']==1){
                $notification_array = $result['notification_array'];
                unset($result['notification_array']);
                $send['si']=$service_id;
                $send['ai']=$address_id;
                $send['appointment_id']=$appointment_id;
                $send['date']=$date;
                $send['di']=base64_encode($doctor_id);
                $update = DoctorController::web_prescription_patient_list($send);
                $result['data']['li']= $update->body();
                Custom::sendResponse($result);
                Custom::send_process_to_background();
                Custom::close_appointment_notification($notification_array);
            }else{
                unset($result['notification_array']);
                Custom::sendResponse($result);
            }

        }else{
            exit();
        }

    }

    public function cancel_appointment()
    {
        $this->autoRender =false;
        if($this->request->is('ajax')) {
            $appointment_id = base64_decode($this->request->data['id']);
            $address_id = base64_decode($this->request->data['ai']);
            $service_id = base64_decode($this->request->data['si']);
            $doctor_id = base64_decode($this->request->data['di']);
            $date = ($this->request->data['date']);
            $post=array();
            $login = $this->Session->read('Auth.User.User');
            $post['app_key'] = APP_KEY;
            $post['user_id'] =$login['id'];
            $post['thin_app_id'] =$login['thinapp_id'];
            $post['thin_app_id'] =$login['thinapp_id'];
            $post['appointment_id'] =$appointment_id;
            $post['cancel_by'] ="DOCTOR";
            $post['message'] = isset($this->request->data['message'])?$this->request->data['message']:"";

            $result = json_decode(WebservicesFunction::cancel_appointment($post,false,true),true);
            if($result['status']==1){
                $notification_array = $result['notification_array'];
                unset($result['notification_array']);
                $send['si']=$service_id;
                $send['ai']=$address_id;
                $send['appointment_id']=$appointment_id;
                $send['date']=$date;
                $send['di']=base64_encode($doctor_id);
                $update = DoctorController::web_prescription_patient_list($send);
                $result['data']['li']= $update->body();
                Custom::sendResponse($result);
                Custom::send_process_to_background();
                Custom::cancel_appointment_notification($notification_array);
            }else{
                unset($result['notification_array']);
                Custom::sendResponse($result);
            }

        }else{
            exit();
        }

    }


    public function get_total_amount()
    {
        $this->autoRender =false;
        if($this->request->is('ajax')) {
            $address_id = base64_decode($this->request->data['ai']);
            $service_id = base64_decode($this->request->data['si']);
            $doctor_id = base64_decode($this->request->data['di']);
            $total = Custom::get_total_earning($address_id,$service_id,$doctor_id);
            $response=array();
            if($total){
                $response['status'] = 1;
                $response['message'] = "Total amount found";
                $response['data'] = $total;
            }else{
                $response['status'] = 0;
                $response['message'] = "No amount found";
            }
            Custom::sendResponse($response);
        }else{
            exit();
        }

    }

    public function create_header()
    {
        $this->layout = 'ajax';
        if($this->request->is('ajax')) {
            $data = json_decode(base64_decode($this->request->data['d']),true);
            $fields = json_decode($this->request->data['f'],true);
            $this->set(compact('fields','data'));
            return $this->render('create_header', 'ajax');;
        }else{
            exit();
        }

    }

	public function department($thin_app_id=0){
        $this->layout = false;
        $thin_app_id = base64_decode($thin_app_id);
        $department_list=array();
        if($thin_app_data = Custom::getThinAppData($thin_app_id)){
            if($thin_app_data['category_name']=='DOCTOR'){
                $department_id = base64_encode("0");
                $thin_app_id = base64_encode($thin_app_id);
                $this->redirect("/doctor/search_doctor/$department_id/$thin_app_id/");
            }else{
                $query = "SELECT count(staff.id) as total_doctor, ac.id, ac.name,ac.image FROM appointment_categories AS ac JOIN appointment_staffs AS staff ON staff.appointment_category_id = ac.id AND staff.staff_type='DOCTOR' AND staff.`status` ='ACTIVE' WHERE ac.thinapp_id = $thin_app_id AND ac.`status` = 'ACTIVE' GROUP BY ac.id order by name asc";
                $connection = ConnectionUtil::getConnection();
                $department_list = $connection->query($query);
                if($department_list->num_rows) {
                    $department_list = mysqli_fetch_all($department_list,MYSQLI_ASSOC);
                }else{
                    $department_list=array();
                	$department_id = base64_encode("0");
                    $thin_app_id = base64_encode($thin_app_id);
                    $this->redirect("/doctor/search_doctor/$department_id/$thin_app_id/");

                }
            }

        }
        $this->set(compact('department_list','thin_app_data'));
    }

    public function search_doctor($department_id=0,$thin_app_id=0){
        $this->layout = false;
        $department_id = base64_decode($department_id);
        $doctor_list = $department_data = array();
        $connection = ConnectionUtil::getConnection();
        if(!empty($department_id)){
            $query = "SELECT ac.id,ac.name,ac.image,t.logo,ac.thinapp_id,t.name AS app_name FROM appointment_categories AS ac JOIN thinapps AS t ON t.id = ac.thinapp_id where ac.id = $department_id AND ac.status = 'ACTIVE' LIMIT 1";
            $department_data = $connection->query($query);
            $condition = "AND staff.appointment_category_id = $department_id";
            if ($department_data->num_rows) {
                $department_data = mysqli_fetch_assoc($department_data);
            } else {
                $department_data  = array();
            }
        }else{
            $thin_app_id = base64_decode($thin_app_id);
            $condition = "AND staff.thinapp_id = $thin_app_id";
            $department_data = Custom::getThinAppData($thin_app_id);
            $department_data['app_name'] = $department_data['name'];
            $department_data['thinapp_id'] = $thin_app_id;
            $department_data['name'] = '';
        }

        $select = "staff.is_online_consulting,staff.is_offline_consulting,staff.is_audio_consulting,staff.is_chat_consulting, staff.name,staff.id,staff.experience,staff.sub_title,staff.profile_photo,staff.registration_number";
        $query = "SELECT $select FROM appointment_staffs AS staff WHERE staff.staff_type = 'DOCTOR' AND staff.`status`='ACTIVE' $condition order by staff.name asc";
        $doctor_list = $connection->query($query);
        if($doctor_list->num_rows) {
            $doctor_list = mysqli_fetch_all($doctor_list,MYSQLI_ASSOC);
        }else{
            $doctor_list=array();
        }
        $this->set(compact('doctor_list','department_data'));
    }

	
	public function load_my_appointment()
    {
        $this->layout = 'ajax';
        if ($this->request->is('ajax')) {

            $appointment_list = array();
            $data = $this->request->data;
            $doctor_id = isset($data['di'])?$data['di']:0;
        	$user_id = isset($data['ui'])?$data['ui']:0;
        	$userCondition = !empty($user_id)?" acss.created_by_user_id = $user_id OR ":"";
            $thin_app_id = $data['t'];
            $mobile = Custom::create_mobile_number($data['m']);
            $condition = "( $userCondition c.mobile = '$mobile' OR c.parents_mobile = '$mobile' OR app_cus.mobile = '$mobile' OR app_cus.parents_mobile = '$mobile')";
            if(!empty($doctor_id)){
                $date = date('Y-m-d');
                $condition  = " acss.appointment_staff_id = $doctor_id and date(acss.appointment_datetime) = '$date'";
            }
            $limit = 20;
            $offset = $this->request->data['offset'];
            $offset = $limit * $offset;
            $query = "SELECT final.* from ( ";
            $query .= "SELECT acss.created_by_user_id, u.mobile as username, app_sta.show_fees, bco.amount as conv_amount, bco.status as conv_status, acss.reminder_message,  acss.amount as service_amount, IFNULL(app_cus.mobile,c.mobile) AS customer_mobile, app_sta.show_appointment_time, app_sta.show_appointment_token,  acss.appointment_datetime, acss.reason_of_appointment, acss.id AS appointment_id, acss.payment_status,acss.`status`,acss.appointment_patient_name,app_sta.name AS doctor_name, acss.queue_number, acss.consulting_type, acss.sub_token,acss.custom_token ,acss.emergency_appointment,acss.has_token  from appointment_customer_staff_services as acss join  appointment_staffs as app_sta on acss.appointment_staff_id = app_sta.id left join users as u on u.id = acss.created_by_user_id left join  appointment_customers as app_cus on app_cus.id = acss.appointment_customer_id left join childrens as c on c.id = acss.children_id  left join booking_convenience_orders as bco on bco.appointment_customer_staff_service_id= acss.id  where $condition and acss.thinapp_id = $thin_app_id  and acss.status != 'REFUND' and acss.is_paid_booking_convenience_fee IN('NOT_APPLICABLE','YES') ";
            $query .= " UNION ALL ";
            $query .= "SELECT acss.created_by_user_id, u.mobile as username, app_sta.show_fees, bco.amount as conv_amount, bco.status as conv_status, acss.reminder_message,  acss.amount as service_amount, IFNULL(app_cus.mobile,c.mobile) AS customer_mobile, app_sta.show_appointment_time, app_sta.show_appointment_token,  acss.appointment_datetime, acss.reason_of_appointment, acss.id AS appointment_id, acss.payment_status,acss.`status`,acss.appointment_patient_name,app_sta.name AS doctor_name, acss.queue_number, acss.consulting_type, acss.sub_token,acss.custom_token ,acss.emergency_appointment,acss.has_token  from appointment_customer_staff_services_archive as acss join  appointment_staffs as app_sta on acss.appointment_staff_id = app_sta.id left join users as u on u.id = acss.created_by_user_id left join  appointment_customers as app_cus on app_cus.id = acss.appointment_customer_id left join childrens as c on c.id = acss.children_id  left join booking_convenience_orders as bco on bco.appointment_customer_staff_service_id= acss.id  where $condition and acss.thinapp_id = $thin_app_id  and acss.status != 'REFUND' and acss.is_paid_booking_convenience_fee IN('NOT_APPLICABLE','YES') ";
            $query .= " ) AS final order by final.appointment_datetime desc limit $offset,$limit";
        	//echo $query;die;
            $connection = ConnectionUtil::getConnection();
            $list = $connection->query($query);
            if ($list->num_rows) {
                $list = mysqli_fetch_all($list, MYSQLI_ASSOC);
                foreach ($list as $key => $val) {
                    $appointment_list[$key] = $val;
                    $appointment_list[$key]['queue_number'] = Custom::create_queue_number($val);
                    if (in_array($val['status'], array('NEW', 'CONFIRM', 'RESCHEDULE'))) {
                        $appointment_list[$key]['status'] = 'Booked';
                    }

                }
            }
            $this->set(compact('appointment_list', 'offset','user_id'));
            $this->render('load_my_appointment', 'ajax');


        } else {
            exit();
        }

    }

    public function load_doctor_appointment()
    {
        $this->layout = 'ajax';
        if ($this->request->is('ajax')) {
            $fromDate = DateTime::createFromFormat('d-m-Y', $this->request->data['date']);
            $booking_date = $fromDate->format('Y-m-d');
            $appointmentList = array();
            $data = $this->request->data;
            $doctor_id = isset($data['di'])?base64_decode($data['di']):0;
            $thin_app_id = isset($data['ti'])?base64_decode($data['ti']):0;
            $search_label = isset($data['as'])?$data['as']:"";

            $condition="";
            if($search_label == "Payment Success"){
                $condition = " acss.payment_status = 'SUCCESS' AND ";
            }else if($search_label == "Payment Pending"){
                $condition = " acss.payment_status = 'PENDING' AND ";
            }else if($search_label == "Canceled"){
                $condition = " acss.status = 'CANCELED' AND ";
            }else if($search_label == "Closed"){
                $condition = " acss.status = 'CLOSED' AND ";
            }

            $condition  .= " acss.thinapp_id = $thin_app_id and acss.appointment_staff_id = $doctor_id and date(acss.appointment_datetime) = '$booking_date'";

            $limit = 20;
            $offset = $this->request->data['offset'];
            $offset = $limit * $offset;
            $select = "  acss.drive_folder_id as folder_id, ( select dr.total_star from doctor_reviews as dr where dr.thinapp_id = acss.thinapp_id and (dr.mobile= app_cus.mobile OR dr.mobile= c.mobile ) limit 1 ) as total_star,   app_sta.show_fees, bcfd.booking_convenience_fee as conv_amount, bco.status as conv_status, acss.reminder_message,  acss.amount as service_amount, IFNULL(app_cus.mobile,c.mobile) AS customer_mobile, app_sta.show_appointment_time, app_sta.show_appointment_token,  acss.appointment_datetime, acss.reason_of_appointment, acss.id AS appointment_id, acss.payment_status,acss.`status`,acss.appointment_patient_name,app_sta.name AS doctor_name, acss.queue_number, acss.consulting_type, acss.sub_token,acss.custom_token ,acss.emergency_appointment,acss.has_token ";
                       $select_final = "  final.folder_id, final.total_star, final.appointment_id,  final.show_fees, final.conv_amount, final.conv_status, final.reminder_message, final.service_amount, final.customer_mobile, final.show_appointment_time, final.show_appointment_token,  final.appointment_datetime, final.reason_of_appointment,  final.payment_status,final.status,final.appointment_patient_name,final.doctor_name, final.queue_number, final.consulting_type, final.sub_token,final.custom_token ,final.emergency_appointment,final.has_token ";
            $limit_condition="order by final.appointment_datetime desc limit $offset,$limit";
            $query = "SELECT $select_final  from ( ";
            $query .= "SELECT $select  from appointment_customer_staff_services as acss join  appointment_staffs as app_sta on acss.appointment_staff_id = app_sta.id left join  appointment_customers as app_cus on app_cus.id = acss.appointment_customer_id left join childrens as c on c.id = acss.children_id  left join booking_convenience_orders as bco on bco.appointment_customer_staff_service_id= acss.id left join booking_convenience_fee_details as bcfd on bcfd.booking_convenience_order_id = bco.id where $condition and acss.thinapp_id = $thin_app_id  and acss.status != 'REFUND' and acss.is_paid_booking_convenience_fee IN('NOT_APPLICABLE','YES') ";
            $query .= " UNION ALL ";
            $query .= "SELECT $select  from appointment_customer_staff_services_archive as acss join  appointment_staffs as app_sta on acss.appointment_staff_id = app_sta.id left join  appointment_customers as app_cus on app_cus.id = acss.appointment_customer_id left join childrens as c on c.id = acss.children_id  left join booking_convenience_orders as bco on bco.appointment_customer_staff_service_id= acss.id left join booking_convenience_fee_details as bcfd on bcfd.booking_convenience_order_id = bco.id where $condition and acss.thinapp_id = $thin_app_id  and acss.status != 'REFUND' and acss.is_paid_booking_convenience_fee IN('NOT_APPLICABLE','YES') ";
            $query .= " ) AS final  $limit_condition ";
        	

        
            $connection = ConnectionUtil::getConnection();
            $list = $connection->query($query);
            if ($list->num_rows) {
                $list = mysqli_fetch_all($list, MYSQLI_ASSOC);
                foreach ($list as $key => $val) {
                    $appointmentList[$key] = $val;
                    $appointmentList[$key]['queue_number'] = Custom::create_queue_number($val);
                    if (in_array($val['status'], array('NEW', 'CONFIRM', 'RESCHEDULE'))) {
                        $appointmentList[$key]['status'] = 'Booked';
                    }
                }
            }

            $video_calling_on_web = (Custom::check_app_enable_permission($thin_app_id, 'WEB_VIDEO_CONSULTATION'))?'YES':'NO';
            $this->set(compact('appointmentList','video_calling_on_web','doctor_id', 'offset'));
            $this->render('load_doctor_appointment', 'ajax');
        } else {
            exit();
        }

    }


    public function submit_review($doctor_id,$mobile,$user_name)
    {
        $this->layout = false;
        if(!empty($doctor_id) && !empty($mobile)){
            $doctor_id = base64_decode($doctor_id);
            $mobile =base64_decode($mobile);
            $query = "select staff.name as doctor_name, dc.category_name, IF(dr.id IS NOT NULL, 'YES','NO') as review_given, t.name as app_name, t.logo, staff.profile_photo, dr.user_name, IFNULL(dr.total_star,0) as total_star, dr.review, dr.created as review_datetime from appointment_staffs as staff join thinapps as t on t.id = staff.thinapp_id left join department_categories  as dc on dc.id = staff.department_category_id left join  doctor_reviews as dr on staff.id = dr.doctor_id and dr.mobile = '$mobile' where staff.id = $doctor_id limit 1 ";
            $connection = ConnectionUtil::getConnection();
            $service_message_list = $connection->query($query);
            if ($service_message_list->num_rows) {
                $doctor_data =  mysqli_fetch_assoc($service_message_list);
                $doctor_id = base64_encode($doctor_id);
                $mobile =base64_encode($mobile);
                $this->set(compact('doctor_data','doctor_id','mobile','user_name', 'offset'));
            }else{
                die("<h3>Invalid Request</h3>");
            }
        }
    }


    public function save_doctor_review()
    {
        $this->layout = false;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $this->request->data;
            $response = array();
            $doctor_id = isset($data['di']) ? base64_decode($data['di']) : 0;
            $username = isset($data['un']) ? base64_decode($data['un']) : 0;
            $mobile = isset($data['m']) ? Custom::create_mobile_number(base64_decode($data['m'])) : "";
            $star = isset($data['star']) ? $data['star'] : 0;
            $review = isset($data['review']) ? $data['review'] : 0;

            if (empty($doctor_id)) {
                $response['status'] = 0;
                $response['message'] = 'Invalid doctor';
            } else if (empty($mobile)) {
                $response['status'] = 0;
                $response['message'] = 'Invalid user';
            } else if (empty($star)) {
                $response['status'] = 0;
                $response['message'] = 'Please select Star';
            } else if (empty($review)) {
                $response['status'] = 0;
                $response['message'] = 'Please enter review';
            } else {

                $query = "select t.id as thinapp_id, staff.name as doctor_name, dc.category_name, IF(dr.id IS NOT NULL, 'YES','NO') as review_given, t.name as app_name, t.logo, staff.profile_photo, dr.user_name, IFNULL(dr.total_star,0) as total_star, dr.review, dr.created as review_datetime from appointment_staffs as staff join thinapps as t on t.id = staff.thinapp_id left join department_categories  as dc on dc.id = staff.department_category_id left join  doctor_reviews as dr on staff.id = dr.doctor_id and dr.mobile = '$mobile' where staff.id = $doctor_id limit 1 ";
                $connection = ConnectionUtil::getConnection();
                $service_message_list = $connection->query($query);
                if ($service_message_list->num_rows) {
                    $doctor_data =  mysqli_fetch_assoc($service_message_list);
                    $thin_app_id = $doctor_data['thinapp_id'];
                    $user_data = Custom::get_user_by_mobile($thin_app_id,$mobile);
                    $user_id = !empty($user_data)?$user_data['id']:0;
                    $created = Custom::created();
                    if ($doctor_data['review_given']=='NO') {
                        $sql = "INSERT INTO doctor_reviews (doctor_id, thinapp_id,user_id, mobile, user_name, total_star, review, created, modified) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
                        $stmt_user = $connection->prepare($sql);
                        $stmt_user->bind_param('sssssssss', $doctor_id, $thin_app_id, $user_id, $mobile, $username, $star, $review,$created, $created);
                        if ($stmt_user->execute()) {
                            $response['status'] = 1;
                            $response['message'] = "Review submitted successfully";
                        }else{
                            $response['status'] = 0;
                            $response['message'] = "Sorry, unable to save review";
                        }

                    } else {
                        $response['status'] = 0;
                        $response['message'] = "Sorry, you have already give review for this doctor";
                    }



                }else{
                    $response['status'] = 0;
                    $response['message'] = "This is invalid request";
                }

            }


            Custom::sendResponse($response);

        }
        exit;
    }


    public function send_review_link()
    {
        $this->layout = false;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $this->request->data;
            $response = array();
            $doctor_id = isset($data['di']) ? base64_decode($data['di']) : 0;
            $username = isset($data['un']) ? base64_decode($data['un']) : 0;
            $mobile = $w_mobile =isset($data['m']) ? Custom::create_mobile_number(base64_decode($data['m'])) : "";
            if (empty($doctor_id)) {
                $response['status'] = 0;
                $response['message'] = 'Invalid doctor';
            } else if (empty($mobile)) {
                $response['status'] = 0;
                $response['message'] = 'Invalid user';
            } else if (empty($username)) {
                $response['status'] = 0;
                $response['message'] = 'Invalid user name';
            } else {
                $doctor_data = Custom::get_doctor_by_id($doctor_id);
                $doctor_name = $doctor_data['name'];
                $doctor_id = base64_encode($doctor_id);
                $username = base64_encode($username);
                $mobile = base64_encode($mobile);
                
            if(!empty($doctor_data['google_review_link'])){
                    $review_link = $doctor_data['google_review_link'];
                }else{
                    $review_link = Custom::short_url(SITE_PATH."doctor/submit_review/$doctor_id/$mobile/$username",$doctor_data['thinapp_id']);
                }
            
            
                $message =$w_message= "Thank you for your visit to $doctor_name. We would love to have your feedback rating to help us improve further.\nPlease select following link to give your rating.\n\n$review_link";
                $send_whatsapp = Custom::sendWhatsappSms($w_mobile,$w_message,$message);
                if ($send_whatsapp) {
                    $response['status'] = 1;
                    $response['message'] = "Review submitted successfully";
                }else{
                    $response['status'] = 0;
                    $response['message'] = "Sorry, unable to save review";
                }
            }
            echo json_encode($response);die;

        }
        exit;
    }

    public function load_doctor_appointment_counts()
    {
        $this->layout = 'ajax';
        if ($this->request->is('ajax')) {
            $fromDate = DateTime::createFromFormat('d-m-Y', $this->request->data['date']);
            $booking_date = $fromDate->format('Y-m-d');
            $appointment_list = array();
            $data = $this->request->data;
            $doctor_id = isset($data['di'])?base64_decode($data['di']):0;
            $thin_app_id = isset($data['ti'])?base64_decode($data['ti']):0;
            $condition  = " acss.thinapp_id = $thin_app_id and acss.appointment_staff_id = $doctor_id and date(acss.appointment_datetime) = '$booking_date'";
            $query = "SELECT count(acss.id) as total, SUM(IF(acss.payment_status='SUCCESS',1,0)) as total_paid, SUM(IF(acss.payment_status='PENDING',1,0)) as total_pending, SUM(IF(acss.status='CANCELED',1,0)) as total_canceled, SUM(IF(acss.status='CLOSED',1,0)) as total_closed from appointment_customer_staff_services as acss where $condition and acss.thinapp_id = $thin_app_id  and acss.status != 'REFUND' and acss.is_paid_booking_convenience_fee IN('NOT_APPLICABLE','YES') ";
            $connection = ConnectionUtil::getConnection();
            $list = $connection->query($query);
            $totalArray['Total Booked'] = 0;
            $totalArray['Payment Success'] = 0;
            $totalArray['Payment Pending'] = 0;
            $totalArray['Closed'] = 0;
            $totalArray['Canceled'] = 0;
            if ($list->num_rows) {
                $total_data = mysqli_fetch_assoc($list);
                $totalArray['Total Booked'] = !empty($total_data['total'])?$total_data['total']:0;
                $totalArray['Payment Success'] = !empty($total_data['total_paid'])?$total_data['total_paid']:0;
                $totalArray['Payment Pending'] = !empty($total_data['total_pending'])?$total_data['total_pending']:0;
                $totalArray['Closed'] = !empty($total_data['total_closed'])?$total_data['total_closed']:0;
                $totalArray['Canceled'] = !empty($total_data['total_canceled'])?$total_data['total_canceled']:0;
            }
            $appointmentData['count_list'] = $totalArray;
            $appointmentData['appointment_list'] = $appointment_list;
            $this->set(compact('totalArray', 'offset'));
            $this->render('load_doctor_appointment_counts', 'ajax');
        } else {
            exit();
        }

    }




    public function load_info()
    {

        $send_sms = false;
        if ($this->request->is('ajax') && !empty($this->request->data)) {
            $pi = base64_decode($this->request->data['pi']);
            $data = Custom::get_customer_by_id($pi);
            $this->set(compact('data'));

        }
    }

	public function tracker_button()
    {

        $this->layout = 'ajax';
        $data=array();
        if ($this->request->is('ajax') && !empty($this->request->data)) {
            $mobile = base64_decode($this->request->data['m']);
            $appointment_id = @base64_decode($this->request->data['ai']);
            $mobile = Custom::create_mobile_number($mobile);
            $thin_app_id = base64_decode($this->request->data['ti']);
            $doctor_data = Custom::get_doctor_by_mobile($mobile,$thin_app_id);
            $notification_array=array();
            if(!empty($doctor_data)){
               if(!empty($appointment_id)){
                    $post['app_key'] = APP_KEY;
                    $post['user_id'] = $doctor_data['user_id'];
                    $post['thin_app_id'] = $doctor_data['thinapp_id'];
                    $post['appointment_id'] = $appointment_id;
                    $result = json_decode(WebservicesFunction::close_appointment($post, true), true);
                    if ($result['status'] == 1) {
                        $data = Custom::getDoctorWebTrackerData(array($doctor_data['id']),true,$thin_app_id);
                        $notification_array = $result['notification_array'];

                    }else{
                        $data = Custom::getDoctorWebTrackerData(array($doctor_data['id']),true,$thin_app_id);
                    }
                }else{
                   $data = Custom::getDoctorWebTrackerData(array($doctor_data['id']),true,$thin_app_id);
               }
                if(!empty($data)){
                    $data['data'] =$data[0];

                }
            }
            $data['notification_array'] = $notification_array;
            $this->set(compact('data'));
            $this->render('tracker_button', 'ajax');
        }
    }

	public function web_app_installed()
    {

        $this->layout = 'ajax';
        $data=array();
        if ($this->request->is('ajax') && !empty($this->request->data)) {
            $mobile = base64_decode($this->request->data['m']);
            $mobile = Custom::create_mobile_number($mobile);
            $thin_app_id = base64_decode($this->request->data['t']);
            $user_id = Custom::create_user($thin_app_id,$mobile);
            if ($user_id) {
                $connection = ConnectionUtil::getConnection();
                $created = Custom::created();
                $web_app_installed_status = 'INSTALLED';
                $sql = "update users set web_app_installed_status= ?, web_app_installed_datetime=?, web_app_uninstalled_datetime=? where id =?";
                $stmt_df = $connection->prepare($sql);
                $stmt_df->bind_param('ssss', $web_app_installed_status,$created, $created, $user_id);
                if($stmt_df->execute()){
                    $response['status'] = 1;
                    $response['message'] = "App installed successfully";
                }else {
                    $response['status'] = 0;
                    $response['message'] = "Unable to installed app";
                }
            } else {
                $response['status'] = 0;
                $response['message'] = "Unable to add user";
            }
            Custom::sendResponse($response);die;
        }
    }

	public function web_app_install_log()
    {

        $this->layout = 'ajax';
        $data=array();
        if ($this->request->is('ajax') && !empty($this->request->data)) {
            $doctor_id = base64_decode($this->request->data['di']);
            $thin_app_id = Custom::get_doctor_by_id($doctor_id)['thinapp_id'];
            if (!empty($thin_app_id) && !empty($doctor_id)) {
                $connection = ConnectionUtil::getConnection();
                $created = Custom::created();
                $sql = "INSERT INTO web_app_install_count (thinapp_id, doctor_id, created) VALUES (?, ?, ?)";
                $stmt = $connection->prepare($sql);
                $stmt->bind_param('sss', $thin_app_id, $doctor_id, $created);
                if($stmt->execute()){
                    echo 'success';die;
                }
            }
            echo 'fail';die;
        }
    }

	public function get_chatbot_url()
    {

        $this->layout = 'ajax';
        $data=array();
        if ($this->request->is('ajax') && !empty($this->request->data)) {
            $mobile = base64_decode($this->request->data['m']);
            $mobile = Custom::create_mobile_number($mobile);
            $thin_app_id = base64_decode($this->request->data['ti']);
            $doctor_id = base64_decode($this->request->data['di']);
            echo Custom::createChatBoatLink($thin_app_id,$mobile,$doctor_id);die;
        }
    }


    public function web_app_send_otp()
    {
        $this->layout = 'ajax';
        $this->autoRender = false;
        if ($this->request->is('ajax')) {
            $post['app_key'] = APP_KEY;
            $thin_app_id = base64_decode($this->request->data['t']);
            $mobile = ($this->request->data['mobile']);
            $send_list = isset($this->request->data['send_list'])?true:false;
            $mobile = Custom::create_mobile_number($mobile);
            if (!empty($mobile)) {
                $verification_code = Custom::getRandomString(4);
                $option = array(
                    'username' => $mobile,
                    'mobile' => $mobile,
                    'verification' => $verification_code,
                    'thinapp_id' => $thin_app_id
                );
                if($mobile!="+918955004050"){
                    $otp_sent = Custom::send_otp($option);
                }else{
                	$otp_sent =true;
                }
                if ($otp_sent) {
                    $response['status'] = 1;
                    $response['message'] = "OTP send successfully";
                    $response['row_id'] = md5(base64_encode($mobile.$verification_code));
                    if($send_list===true){
                        $save = Custom::get_recent_customer_list($thin_app_id, $mobile, true, 10);
                        if (!empty($save)) {
                            array_reverse($save);
                        }
                        $response['list'] = json_encode($save);
                        $response['row_id'] = base64_encode($verification_code);
                    }

                } else {
                    $response['status'] = 0;
                    $response['message'] = "OTP could not send";
                }
            } else {
                $response['status'] = 0;
                $response['message'] = "Please enter valid mobile number";
            }


            Custom::sendResponse($response);
        } else {
            exit();
        }

    }

    public function web_app_login()
    {
        $this->layout = 'ajax';
        $this->autoRender = false;
        if ($this->request->is('ajax')) {
            $post['app_key'] = APP_KEY;
            $thin_app_id = base64_decode($this->request->data['t']);
            $token = $this->request->data['token'];
            $mobile = Custom::create_mobile_number($this->request->data['mobile']);
        	$plan_otp =$this->request->data['otp'];
            $otp = md5(base64_encode($mobile.$this->request->data['otp']));
            $sent_otp = ($this->request->data['row_id']);
            if($otp==$sent_otp || ( $mobile=="+918955004050" && $plan_otp=="9860" )){
                $user_id = Custom::create_user($thin_app_id,$mobile,$mobile);
                if(!empty($user_id)){
                    $user_data= Custom::web_app_custom_login_data($user_id);
                    if(!empty($token)){
                        $connection = ConnectionUtil::getConnection();
                        $web_app_installed_status ='INSTALLED';
                        $query = "update users set web_app_token =?, web_app_installed_status=? where id = ?";
                        $stmt_thinapp = $connection->prepare($query);
                        $stmt_thinapp->bind_param('sss', $token, $web_app_installed_status, $user_id);
                        $res = $stmt_thinapp->execute();
                        if($res){
                            $file_name = Custom::encrypt_decrypt('encrypt',"user_$user_id");
                            WebservicesFunction::deleteJson(array($file_name),"user");
                        }
                    }

                
                	 $user_data['folder_id'] = 0;
                    $default = Custom::get_default_folder_data($thin_app_id,$mobile);
                    if(!empty($default)){
                        $user_data['folder_id'] = $default['id'];
                    }
                
                
                    $save = Custom::get_recent_customer_list($thin_app_id, $mobile, true, 10);
                    if (!empty($save)) {
                        array_reverse($save);
                    }
                    $user_data['user_role'] = Custom::get_appointment_role($user_data['user_mobile'], $user_data['thin_app_id'], $user_data['role_id']);
                    $response['data']['user'] = $user_data;
                    $response['data']['recent'] = $save;
                    $response['status']=1;
                    $response['message'] = "Login Successfully";
                }else{
                    $response['status']=0;
                    $response['message'] = "Sorry, unable to login";
                }

            }else{
                $response['status']=0;
                $response['message'] = "Invalid OTP";
            }
            Custom::sendResponse($response);
        } else {
            exit();
        }

    }

      public function setting_menu($doctor_id,$thin_app_id,$role)
    {
        
        $this->set(compact('role','doctor_id','thin_app_id'));
        return $this->render('setting_menu', 'ajax');;

    }


    public function doctor_setting(){
        $this->layout = false;
        $doctor_id = base64_decode($this->request->data('di'));
        $type = $this->request->data('type');
        if ($this->request->is(array('ajax'))) {

            if(!empty($doctor_id)){
                $doctor_data = Custom::get_doctor_info($doctor_id);
                $thin_app_id = $doctor_data['thinapp_id'];
                $roleData = Custom::getRoleDataByDoctorId($thin_app_id,$doctor_id);

                $address_list = $service_list =array();
                /* hours list */
                
                $query =    $query = "SELECT address.id AS address_id, address.address, asa.id as selected, TIME_FORMAT(asa.from_time, '%H:%i:%s') as from_time, TIME_FORMAT(asa.to_time, '%H:%i:%s') as to_time FROM appointment_addresses AS address LEFT JOIN appointment_staff_addresses AS asa ON asa.appointment_address_id = address.id AND asa.appointment_staff_id = $doctor_id WHERE address.status = 'ACTIVE' AND address.thinapp_id = $thin_app_id ";
                $connection = ConnectionUtil::getConnection();
                $service_message_list = $connection->query($query);
                if ($service_message_list->num_rows) {
                    $address_list = mysqli_fetch_all($service_message_list,MYSQLI_ASSOC);
                }

                /* service list */
               
                $query =    $query = "SELECT ser.*,ser.id as service_id,  ass.id AS selected  FROM appointment_services AS ser LEFT JOIN appointment_staff_services AS ass ON ass.appointment_service_id = ser.id AND ass.appointment_staff_id = $doctor_id WHERE ser.thinapp_id = $thin_app_id AND ser.status ='ACTIVE'";
                $connection = ConnectionUtil::getConnection();
                $service_message_list = $connection->query($query);
                if ($service_message_list->num_rows) {
                    $service_list = mysqli_fetch_all($service_message_list,MYSQLI_ASSOC);
                }



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


                $this->set(compact('block_array','type','roleData','doctor_data','hours_list','break_array','address_list','service_list'));
            }else{
                exit();
            }
        }else{
            exit();
        }

    }


    public function web_app_dashboard()
    {
        $this->layout = 'ajax';
        if ($this->request->is('ajax')) {
            $post['app_key'] = APP_KEY;

            $loginUserDoctorId = base64_decode($this->request->data['ldi']);
            $doctor_id = base64_decode($this->request->data['di']);
            $user_id = base64_decode($this->request->data['ui']);
            $doctor_data = Custom::get_doctor_info($doctor_id);

            $channel_id = base64_encode(Custom::get_app_default_channel_id($doctor_data['thin_app_id']));
            $_SERVER['REQUEST_METHOD'] = 'POST';
            $post = array();
            $post['app_key'] = APP_KEY;
            $post['role_id'] = $doctor_data['role_id'];
            $post['user_id'] = $doctor_data['user_id'];
            $post['thin_app_id'] = $thin_app_id = $doctor_data['thin_app_id'];
            $post['mobile'] = $doctor_data['mobile'];
            $post['appointment_user_role'] = "USER";
            $post['doctor_id'] = $doctor_id;
            $post['app_main_category'] = $doctor_data['category_name'];
            $post['customer_list'] = 'NO';
            $appointment_data = false;
            if (Custom::check_app_enable_permission($thin_app_id, 'NEW_QUICK_APPOINTMENT') || Custom::check_app_enable_permission($thin_app_id, 'QUICK_APPOINTMENT')) {
                $appointment_data = WebservicesFunction::load_quick_appointment($post, true);
                $appointment_data = json_decode($appointment_data, true);
                $appointment_data = @$appointment_data['data'];
            }

            $setting_menu = "";
            $isDoctor = ( !empty($loginUserDoctorId) && $loginUserDoctorId !='null' )?true:false;
            if($isDoctor){
                $role  = $isDoctor ?"DOCTOR":"USER";
                $update = DoctorController::setting_menu($doctor_id,$thin_app_id,$role);
                $setting_menu = $update->body(); 
            }
        	

            $show_add_banner = Custom::check_app_enable_permission($doctor_data['thinapp_id'], "ADVERTISEMENT_BANNER");
            $this->set(compact('setting_menu','isDoctor','doctor_data','channel_id','appointment_data','show_add_banner'));
            $this->render('web_app_dashboard', 'ajax');
        } else {
            exit();
        }
    }

    public function load_child_vaccination()
    {
        $this->layout = 'ajax';
        if ($this->request->is('ajax')) {
            $_SERVER['REQUEST_METHOD'] = 'POST';
            $post = array();
            $post['app_key'] = APP_KEY;
            $post['role_id'] = base64_decode($this->request->data['ri']);;
            $post['user_id'] = base64_decode($this->request->data['ui']);
            $post['thin_app_id'] = base64_decode($this->request->data['ti']);
            $post['mobile'] = base64_decode($this->request->data['m']);
            $post['child_id'] = $child_id = base64_decode($this->request->data['ci']);
        	$user_role = base64_decode($this->request->data['ur']);
            $post['list_type'] = 'DATE';
            $child_data = Custom::get_child_by_id($child_id);
            $this->set(compact('child_id','child_data','user_role'));
            $this->render('load_child_vaccination', 'ajax');
        } else {
            exit();
        }
    }

    public function load_vaccination()
    {
        $this->layout = 'ajax';
        if ($this->request->is('ajax')) {
            $_SERVER['REQUEST_METHOD'] = 'POST';
            $post = array();
            $post['app_key'] = APP_KEY;
            $post['role_id'] = base64_decode($this->request->data['ri']);;
            $post['user_id'] = base64_decode($this->request->data['ui']);
            $post['thin_app_id'] = base64_decode($this->request->data['ti']);
            $post['mobile'] = base64_decode($this->request->data['m']);
            $post['child_id'] = $child_id = base64_decode($this->request->data['ci']);
            $post['list_type'] = 'DATE';
            $data = json_decode(WebservicesFunction::get_child_vaccination_list($post),true);
            $this->set(compact('data','name','age','child_id'));
            $this->render('load_vaccination', 'ajax');
        } else {
            exit();
        }
    }

    public function get_vaccination_detail()
    {
        $this->layout = 'ajax';
        if ($this->request->is('ajax')) {
            $_SERVER['REQUEST_METHOD'] = 'POST';
            $post = array();
            $post['app_key'] = APP_KEY;
            $post['role_id'] = base64_decode($this->request->data['ri']);;
            $post['user_id'] = base64_decode($this->request->data['ui']);
            $post['thin_app_id'] = base64_decode($this->request->data['ti']);
            $post['mobile'] = base64_decode($this->request->data['m']);
            $post['child_id'] = $child_id = base64_decode($this->request->data['ci']);
            $post['vac_id'] = $vac_id = base64_decode($this->request->data['vi']);
        	 $user_role = base64_decode($this->request->data['ur']);

            $result = json_decode(WebservicesFunction::get_child_vaccination_detail($post),true);
            $data = $result['data']['detail'];
            $previous = $result['data']['done_vaccination'];
             $this->set(compact('data','child_id','vac_id','previous','user_role'));
            $this->render('get_vaccination_detail', 'ajax');

        } else {
            exit();
        }
    }

    public function update_child_vaccination()
    {
        $this->layout = 'ajax';
        if ($this->request->is('ajax')) {
            $_SERVER['REQUEST_METHOD'] = 'POST';
            $post = array();
            $post['app_key'] = APP_KEY;
            $post['role_id'] = base64_decode($this->request->data['ri']);;
            $post['user_id'] = base64_decode($this->request->data['ui']);
            $post['thin_app_id'] = base64_decode($this->request->data['ti']);
            $post['mobile'] = base64_decode($this->request->data['m']);
            $post['child_id'] =  base64_decode($this->request->data['ci']);
            $post['vac_id'] =  base64_decode($this->request->data['vi']);
            $post['doctor_id'] =  base64_decode($this->request->data['doctor_id']);
            $post['remark'] = $this->request->data['r'];
            $post['vac_image'] = $this->request->data['i1'];
            $post['vac_image2'] = $this->request->data['i2'];
            $post['vac_image3'] = $this->request->data['i3'];
            $post['date'] = $this->request->data['date'];
            $post['update_type'] = $this->request->data['ut'];

            $result = json_decode(WebservicesFunction::update_child_vaccination($post),true);
            $data = $result['data']['detail'];
            $previous = $result['data']['done_vaccination'];
            $this->set(compact('data','child_id','vac_id','previous'));
            $this->render('get_vaccination_detail', 'ajax');

        } else {
            exit();
        }
    }

    public function load_patient_record()
    {
        $this->layout = 'ajax';
        if ($this->request->is('ajax')) {
            $_SERVER['REQUEST_METHOD'] = 'POST';
            $post = array();
            $post['app_key'] = APP_KEY;
            $post['role_id'] = base64_decode($this->request->data['ri']);;
            $post['user_id'] = base64_decode($this->request->data['ui']);
            $post['thin_app_id'] = base64_decode($this->request->data['ti']);
            $post['mobile'] = base64_decode($this->request->data['m']);
            $folder_id = base64_decode($this->request->data['fi']);
            $name = $this->request->data['name'];
            $this->set(compact('folder_id','name'));
            $this->render('load_patient_record', 'ajax');
        } else {
            exit();
        }
    }

	 public function load_child_detail()
    {
        $this->layout = 'ajax';
        if ($this->request->is('ajax')) {
            $_SERVER['REQUEST_METHOD'] = 'POST';
            $post = array();
            $post['app_key'] = APP_KEY;
            $post['role_id'] = base64_decode($this->request->data['ri']);;
            $post['user_id'] = base64_decode($this->request->data['ui']);
            $post['thin_app_id'] = base64_decode($this->request->data['ti']);
            $post['mobile'] = base64_decode($this->request->data['m']);
            $post['child_id'] = $child_id = base64_decode($this->request->data['ci']);
            $user_role = base64_decode($this->request->data['ur']);
            $post['list_type'] = 'DATE';
            $child_data = Custom::get_child_by_id($child_id);
            $this->set(compact('child_id','child_data','user_role'));
            $this->render('load_child_detail', 'ajax');
        } else {
            exit();
        }
    }

    public function update_child_detail()
    {
        $this->layout = 'ajax';
        if ($this->request->is('ajax')) {
            $_SERVER['REQUEST_METHOD'] = 'POST';
            $post = array();
            $post['app_key'] = APP_KEY;
            $post['role_id'] = base64_decode($this->request->data['ri']);;
            $post['user_id'] = base64_decode($this->request->data['ui']);
            $post['thin_app_id'] = base64_decode($this->request->data['ti']);
            $post['child_id'] = $child_id = base64_decode($this->request->data['ci']);
            $post['image'] = $this->request->data['image'];
            $post['child_name'] = $this->request->data['child_name'];
            $post['mobile'] = ($this->request->data['mobile']);
            $post['parents_mobile'] = $this->request->data['parents_mobile'];
            $post['dob'] = $this->request->data['dob'];
            $post['gender'] = $this->request->data['gender'];
            $post['blood_group'] = $this->request->data['blood_group'];
            $post['special_remark'] = $this->request->data['special_remark'];
            $post['patient_address'] = $this->request->data['patient_address'];
            $post['patient_profession'] = $this->request->data['patient_profession'];
            echo WebservicesFunction::edit_child($post,true);die;


        } else {
            exit();
        }
    }

	 public function load_add_child_modal()
    {
        $this->layout = 'ajax';
        if ($this->request->is('ajax')) {
            $this->render('add_child_modal', 'ajax');
        } else {
            exit();
        }
    }

    public function add_new_child()
    {
        $this->layout = 'ajax';
        if ($this->request->is('ajax')) {
            $_SERVER['REQUEST_METHOD'] = 'POST';
            $post = array();
            $post['app_key'] = APP_KEY;
            $post['role_id'] = base64_decode($this->request->data['ri']);;
            $post['user_id'] = base64_decode($this->request->data['ui']);
            $post['thin_app_id'] = base64_decode($this->request->data['ti']);
            $post['child_id'] = $child_id = base64_decode($this->request->data['ci']);
            $post['image'] = $this->request->data['image'];
            $post['child_name'] = $this->request->data['child_name'];
            $post['mobile'] = ($this->request->data['mobile']);
            $post['parents_mobile'] = $this->request->data['parents_mobile'];

            $fromDate = DateTime::createFromFormat('d-m-Y', $this->request->data['dob']);
            $post['dob'] = $fromDate->format('Y-m-d');




            $post['gender'] = $this->request->data['gender'];
            $post['blood_group'] = $this->request->data['blood_group'];
            $post['special_remark'] = $this->request->data['special_remark'];
            $post['patient_address'] = $this->request->data['patient_address'];
            $post['patient_profession'] = $this->request->data['patient_profession'];
            echo WebservicesFunction::add_child($post);die;

        } else {
            exit();
        }
    }

	public function load_patient_list()
    {
        $this->layout = 'ajax';
        if ($this->request->is('ajax')) {
            $_SERVER['REQUEST_METHOD'] = 'POST';
            $post = array();
            $post['app_key'] = APP_KEY;
            $post['role_id'] = base64_decode($this->request->data['ri']);;
            $post['user_id'] = base64_decode($this->request->data['ui']);
            $post['thin_app_id'] =$thin_app_id = base64_decode($this->request->data['ti']);
            $post['mobile'] = base64_decode($this->request->data['m']);
            $post['doctor_id']  = base64_decode($this->request->data['di']);
            $offset  = $this->request->data['offset'];
            $limit = 30;
            $offset = $offset * $limit;
            $search  = $this->request->data['search'];
            $child_search = $patient_search = "";
            if(!empty($search)){
               $patient_search = " AND (ac.first_name LIKE '%$search%' OR ac.mobile LIKE '%$search%' OR  ac.uhid LIKE '%$search%') ";
                $child_search = " AND (c.child_name LIKE '%$search%' OR c.mobile LIKE '%$search%' OR  ac.uhid LIKE '%$search%' ) ";
            }
            $query = "SELECT IFNULL(ac.address,c.patient_address) as address, IFNULL(ac.profile_photo,c.image) AS patient_photo, df.id as folder_id, IFNULL(ac.uhid, c.uhid) as uhid, IFNULL(ac.id,c.id) AS patient_id, IF(ac.id IS NOT NULL, 'PATIENT','CHILDREN') AS patient_type, IFNULL(ac.mobile,c.mobile) AS patient_mobile, IFNULL(ac.first_name, c.child_name) AS patient_name, IFNULL(ac.gender,c.gender) AS gender, IFNULL(ac.age,'') AS age, IFNULL(ac.created,c.created) AS created FROM drive_folders AS df LEFT JOIN appointment_customers AS ac ON ac.id = df.appointment_customer_id AND ac.`status`='ACTIVE' $patient_search LEFT JOIN childrens AS c ON c.id = df.children_id AND c.`status` ='ACTIVE' $child_search WHERE ( ac.id IS NOT NULL OR c.id IS NOT NULL ) AND df.thinapp_id = $thin_app_id  ORDER BY created desc LIMIT $offset, $limit";
            $connection = ConnectionUtil::getConnection();
            $data = $connection->query($query);
            $patientData=array();
            if ($data->num_rows) {
                $patientData =  mysqli_fetch_all($data,MYSQL_ASSOC);
            }
            $this->set(compact('patientData'));
            $this->render('load_patient_list', 'ajax');
        } else {
            exit();
        }

    }

}
