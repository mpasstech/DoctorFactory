<?php
App::uses('AppController', 'Controller');
include (WWW_ROOT."webservice".DS."ConnectionUtil.php");
include (WWW_ROOT."webservice".DS."WebservicesFunction.php");
include (WWW_ROOT."webservice".DS."WebServicesFunction_2_3.php");
session_start();
class QutotController extends AppController {
	
	public $uses = array();
	public $components = array('Custom');
	public function beforeFilter(){
		parent::beforeFilter();
		$this->layout = 'home';
		$this->Auth->allow();
	}


   
public function index($lat=null, $lng=null) {

        if (!isset($_SERVER['HTTPS'])) {
            $actual_link = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
            header("Location: $actual_link");
            die;
        }


        $this->layout = false;
        $connection = ConnectionUtil::getConnection();
        $file_name = "all_doctor_list";
        $full_path = LOCAL_PATH . "app/webroot/cache/qutot/" .$file_name.".json";
        if (file_exists($full_path)) {
            $date =  strtotime("+0 minutes", strtotime(date("Y-m-d H:i", filemtime($full_path))));
            $current  =strtotime(date("Y-m-d H:i"));
            if(($current) > ($date)){
                WebservicesFunction::deleteJson(array($file_name),'qutot');
            }
        }
        if (!$data_array = WebservicesFunction::readJson($file_name, "qutot")){
            $query = "SELECT ser.video_consulting_amount, COUNT(acss.id) AS total_appointment,  staff.is_offline_consulting, staff.is_online_consulting, staff.is_audio_consulting, staff.is_chat_consulting, t.logo, aa.id as address_id, aa.latitude AS lat, aa.longitude AS lng, aa.address, staff.id, t.name as app_name, staff.name AS doctor_name,dc.category_name,c.name AS city_name FROM appointment_staffs AS staff join thinapps as t on t.id = staff.thinapp_id JOIN appointment_staff_addresses AS asa ON asa.appointment_staff_id = staff.id JOIN appointment_addresses AS aa ON aa.id= asa.appointment_address_id LEFT JOIN appointment_staff_services AS ass ON ass.appointment_staff_id = staff.id LEFT JOIN appointment_services AS ser ON ser.id= ass.appointment_service_id left JOIN cities AS c ON c.id = staff.city_id LEFT JOIN department_categories AS dc ON dc.id = staff.department_category_id LEFT JOIN appointment_customer_staff_services AS acss ON acss.appointment_staff_id = staff.id AND DATE(acss.appointment_datetime) = DATE(NOW()) AND staff.thinapp_id = acss.thinapp_id  WHERE t.category_name IN('HOSPITAL','DOCTOR','TEMPLE') AND staff.`status`='ACTIVE' AND staff.staff_type ='DOCTOR' AND staff.show_into_pine_device ='NO' AND( ( t.show_into_doctor_apps ='YES') OR (t.booking_convenience_fee_chat > 0 OR t.booking_convenience_fee_audio > 0 OR t.booking_convenience_fee_video > 0 OR t.booking_convenience_fee > 0)) group by staff.id  ORDER BY total_appointment desc";
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
                WebservicesFunction::createJson($file_name, $data_array, 'CREATE', "qutot");
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


    public function login($username='') {
        $this->layout = false;
        if ($user = $this->Session->read('Qutot')) {
            $role_id = $user['User']['role_id'];
            if ($role_id == 5) {
                //$this->redirect(array('controller' => 'qutot', 'action' => 'dashboard'));
            }
        }
        if ($this->request->is(array('post', 'put'))) {
            if (!empty($this->request->data)) {
                $userdata = $this->AppointmentStaff->find("first",
                array(
                    "conditions" => array(
                        "AppointmentStaff.mobile" => Custom::create_mobile_number($this->request->data['User']['mobile']),
                        "AppointmentStaff.password" => md5($this->request->data['User']['password']),
                        "User.role_id" => 5,
                    	"Thinapp.status"=>"ACTIVE"
                    ),
                    'contain'=>array("User","Thinapp"),
                    'fields'=>array('User.role_id','User.id','User.thinapp_id','AppointmentStaff.id','AppointmentStaff.name','AppointmentStaff.mobile')
                ));
                if (!empty($userdata)) {
                    if ($this->Session->write('Qutot',$userdata)) {
                        $this->redirect(array('controller' => 'qutot', 'action' => 'dashboard'));
                        $this->Session->setFlash(__('login successfully.'), 'default', array(), 'success');
                    }
                } else {
                    $this->Session->setFlash(__('Invalid mobile number or password.'), 'default', array(), 'error');
                }
            }
        }

    }

    public function dashboard(){
        if ($user = $this->Session->read('Qutot.User')) {
            $thin_app_id = $user['thinapp_id'];
            $this->layout = false;
            $connection = ConnectionUtil::getConnection();
            $query = "SELECT staff.password, t.address as app_address, staff.thinapp_id, staff.id, t.name as app_name, staff.mobile, staff.name as doctor_name, staff.staff_type FROM appointment_staffs AS staff JOIN thinapps AS t ON t.id = staff.thinapp_id WHERE staff.thinapp_id = $thin_app_id AND staff.`status` = 'ACTIVE' order by staff.id asc";
            $data = $connection->query($query);
            $app_data =array();
            if ($data->num_rows) {
                $app_data = mysqli_fetch_all($data, MYSQLI_ASSOC);
            }
            if(empty($app_data)){
                die('Invalid Request');
            }
            $this->set(compact('app_data','thin_app_id'));

        }else{
            $this->redirect(array('controller' => 'qutot', 'action' => 'login'));
        }
    }


    public function web_app($doctor = null)
    {

        if (!isset($_SERVER['HTTPS'])) {
            $actual_link = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
            header("Location: $actual_link");
            die;
        }
        $doctor_id = $this->request->query['t'];
        $wa = @$this->request->query['wa'];
        $doctor_data = Custom::get_doctor_info(base64_decode($doctor_id));
        $department_search = isset($this->request->query['d']) ? $this->request->query['d'] : 'NO';
        $success = array();
        if (isset($this->request->query['a']) && !empty($this->request->query['a'])) {
            $success = json_decode(base64_decode($this->request->query['a']), true);
            $success['doctor_id'] = base64_encode($success['appointment_staff_id']);
            $success['dialog_type'] = "error";
            $success['title'] = "Token could not booked";
            $success['message'] = "Your token could not book due to payment failure";
            if ($success['status_type'] == 'success') {
                $success['dialog_type'] = "success";
                $success['title'] = "Token Booked Successfully";
                $lbl_date = date('d-m-Y', strtotime($success['appointment_datetime']));
                $lbl_time = date('h:i A', strtotime($success['appointment_datetime']));
                $time_string = ($success['show_appointment_time'] == "YES" && $success['emergency_appointment'] == "NO" && $success['custom_token'] == "NO") ? " Time:$lbl_time," : "";
                $queue_number = ($success['show_appointment_token'] == "NO") ? "" : $success['queue_number'];
                $name = Custom::get_string_first_name($success['cus_name']);
                if($doctor_data['allow_only_mobile_number_booking']=='NO'){
                    $message = "<li><label>Name :</label>$name</li>";
                }
                $message .= "<li><label>Token No :</label>$queue_number</li>";
                if (!empty($time_string)) {
                    $message .= "<li><label>Time :</label>$time_string</li>";
                }
                $message .= "<li><label>Date :</label>$lbl_date</li>";
                $success['message'] = $message;
            } else {
                $success['dialog_type'] = "error";
                $success['title'] = "Sorry, token could not booked.";
                $lbl_date = date('d-m-Y', strtotime($success['appointment_datetime']));
                $lbl_time = date('h:i A', strtotime($success['appointment_datetime']));
                $time_string = ($success['show_appointment_time'] == "YES" && $success['emergency_appointment'] == "NO" && $success['custom_token'] == "NO") ? " Time:$lbl_time," : "";
                $queue_number = ($success['show_appointment_token'] == "NO") ? "" : $success['queue_number'];
                $name = Custom::get_string_first_name($success['cus_name']);
                if($doctor_data['allow_only_mobile_number_booking']=='NO'){
                    $message = "<li><label>Name :</label>$name</li>";
                }
                $message .= "<li><label>Token No :</label>$queue_number</li>";
                if (!empty($time_string)) {
                    $message .= "<li><label>Time :</label>$time_string</li>";
                }
                $message .= "<li><label>Date :</label>$lbl_date</li>";
                $success['message'] = $message;
            }

        }

        $this->layout = false;

        //pr($doctor_data); die;
        if (!empty($doctor_data)) {

            $show_add_banner = Custom::check_app_enable_permission($doctor_data['thinapp_id'], "ADVERTISEMENT_BANNER");


            $category_name = $doctor_data['category_name'];
            $thin_app_id = $doctor_data['thinapp_id'];
            /* add to home icon functionality start*/
            $dir = false;
            $icon_folder = LOCAL_PATH . 'app/webroot/add_home_screen/' . $doctor_data['doctor_id'] . "/icons";
            $manifest = LOCAL_PATH . 'app/webroot/add_home_screen/' . $doctor_data['doctor_id'] . "/manifest.json";
            $default_manifest = LOCAL_PATH . 'app/webroot/add_home_screen/doctorapps/manifest.json';
            if (!is_dir($icon_folder)) {
                if (Custom::createIconFolder($icon_folder)) {
                    $dir = true;
                }
            } else {
                $dir = true;
            }
            if ($dir) {
                if (!file_exists($manifest)) {
                    $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                    $json_data = json_decode(file_get_contents($default_manifest), true);
                    $json_data['name'] = $doctor_data['app_name'];
                    $json_data['short_name'] = $doctor_data['app_name'];
                    $json_data['start_url'] = $actual_link;
                    $json_data['theme_color'] = "#0952EE";
                    $json_data['background_color'] = "#ffffff";
                    $has_manifest = file_put_contents($manifest, json_encode($json_data));
                    $logo = $doctor_data['logo'];
                    $array = array("72", "96", "128", "144", "152", "192", "384", "512");
                    foreach ($array as $key => $value) {
                        $res = Custom::download_image_from_url($logo, $value, $value, $icon_folder . "/icon-$value" . "x" . "$value.png");
                    }
                }
            }
            /* add to home icon functionality end*/

            $channel_id = base64_encode(Custom::get_app_default_channel_id($doctor_data['thin_app_id']));
            $_SERVER['REQUEST_METHOD'] = 'POST';
            $post = array();
            $post['app_key'] = APP_KEY;
            $post['role_id'] = $doctor_data['role_id'];
            $post['user_id'] = $doctor_data['user_id'];
            $post['thin_app_id'] = $thin_app_id = $doctor_data['thin_app_id'];
            $post['mobile'] = $doctor_data['mobile'];
            $post['appointment_user_role'] = "USER";
            $post['doctor_id'] = base64_decode($doctor_id);
            $post['app_main_category'] = $doctor_data['category_name'];
            $post['customer_list'] = 'NO';
            $appointment_data = false;
            if (Custom::check_app_enable_permission($thin_app_id, 'NEW_QUICK_APPOINTMENT') || Custom::check_app_enable_permission($thin_app_id, 'QUICK_APPOINTMENT')) {
                $appointment_data = WebservicesFunction::load_quick_appointment($post, true);
                $appointment_data = json_decode($appointment_data, true);
                $appointment_data = @$appointment_data['data'];
            }


            $dob = $doctor_data['dob'];
            $age = '';
            if (!empty($dob) && $dob != '1970-01-01' && $dob != '0000-00-00') {
                $age = Custom::dob_elapsed_string($dob, false, false);
            }
            $this->set(compact('show_add_banner','category_name', 'thin_app_id', 'wa', 'department_search', 'success', 'userCount', 'doctor_id', 'doctor_data', 'appointment_data', 'channel_id', 'age'));
            // pr($appointment_data);die;
        } else {
            exit();
        }

    }


    public function  dti($thin_app_id,$staff_id){

	    $this->layout =false;
        $thin_app_id =base64_decode($thin_app_id);
        $query = "SELECT t.allow_only_mobile_number_booking, ser.service_slot_duration,  asa.appointment_address_id as address_id, t.logo, staff.profile_photo, staff.id as doctor_id, staff.name AS doctor_name, staff.mobile FROM appointment_staffs AS staff JOIN thinapps AS t ON t.id= staff.thinapp_id join appointment_staff_addresses as asa on asa.appointment_staff_id = staff.id join appointment_staff_services as ass on ass.appointment_staff_id = staff.id join appointment_services as ser on ser.id=ass.appointment_service_id  WHERE staff.`status` ='ACTIVE' AND staff.staff_type='DOCTOR' AND staff.thinapp_id = $thin_app_id group by(staff.id)";
        $connection = ConnectionUtil::getConnection();
        $data = $connection->query($query);
        if ($data->num_rows) {
            $staff_member_id = base64_decode($staff_id);
            $app_data = Custom::get_thinapp_data($thin_app_id);
            if(!empty($app_data)){

                /* add to home icon functionality start*/
                $dir= false;
                $icon_folder = LOCAL_PATH . 'app/webroot/add_home_screen/moq/'.$staff_member_id."/icons";
                $manifest = LOCAL_PATH . 'app/webroot/add_home_screen/moq/'.$staff_member_id."/manifest.json";
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
                        $json_data['name'] = 'Daily Token';
                        $json_data['short_name'] = 'Daily Token';
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
            $thin_app_id =base64_encode($thin_app_id);
            $category_name = $app_data['category_name'];
            $this->set(compact('app_data','category_name','data','staff_member_id','staff_id','thin_app_id'));
        }else{
            echo "<h4>Token Setting Not Configured</h4>";
        }


    }

    public function setting(){
        $this->layout = false;
        $doctor_id = base64_decode($this->request->data('di'));
        if ($this->request->is(array('ajax'))) {

            if(!empty($doctor_id)){
                $doctor_data = Custom::get_doctor_info($doctor_id);
                $thin_app_id = $doctor_data['thinapp_id'];
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


                $this->set(compact('doctor_data','hours_list','break_array'));
            }else{
                exit();
            }
        }else{
            exit();
        }

    }

    public function save_setting(){
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
                $thin_app_id = $doctor_data['thinapp_id'];
                $data['id'] = $doctor_id;
                $data['mobile'] =Custom::create_mobile_number($data['mobile']);
                $allow_doctor_to_save = false;
                $get_doctor = Custom::get_doctor_by_mobile($data['mobile'],$thin_app_id);
                if(empty($get_doctor)){
                    $allow_doctor_to_save =true;
                    $create_user = Custom::create_user($thin_app_id,$data['mobile'],$data['name']);
                    if(!empty($create_user)){
                        $data['user_id'] = $create_user;
                    }
                }else if($get_doctor['id']==$doctor_id){
                    $allow_doctor_to_save =true;
                }

                if($allow_doctor_to_save===true){
                    $service['id'] = base64_decode($data['si']);
                    $service['name'] = trim($data['service_name']);
                    $service['service_slot_duration'] = $data['service_duration'];

                    $address_ass['id'] = base64_decode($data['aai']);
                    $address_ass['from_time'] = date('h:i A',strtotime($data['from_time']));
                    $address_ass['to_time'] = date('h:i A',strtotime($data['to_time']));

                    $address['id'] = base64_decode($data['ai']);
                    $address['address'] = $data['address'];


                    $datasource = $this->AppointmentStaff->getDataSource();
                    try {
                        $datasource->begin();
                        if ($this->AppointmentAddress->save($address) && $this->AppointmentStaff->save($data) && $this->AppointmentService->save($service) && $this->AppointmentStaffAddress->save($address_ass)) {
                            $datasource->commit();
                            $response['status'] = 1;
                            $response['message'] = 'Profile update successfully';
                            Custom::delete_doctor_cache($doctor_id);
                        }else{
                            $response['status'] = 0;
                            $response['message'] = 'Sorry, breaks setting could not update';
                        }
                    }catch (Exception $e){
                        $datasource->rollback();
                        $response['status'] = 0;
                        $response['message'] = 'Sorry, profile could not update';
                    }
                }else{
                    $response['status'] = 0;
                    $response['message'] = 'This mobile number already registered. Please use different number';
                }

            }else if($setting_type=='password'){

                $doctor['id'] = base64_decode($data['di']);

                if(!empty($data['new_password'])){
                    $doctor['password'] = md5($data['new_password']);
                    if ($this->AppointmentStaff->save($doctor)) {
                        $response['status'] = 1;
                        $response['message'] = 'Password update successfully';
                        Custom::delete_doctor_cache($doctor_id);
                    }else{
                        $response['status'] = 0;
                        $response['message'] = 'Sorry, password  could not update';
                    }
                }else{
                    $response['status'] = 0;
                    $response['message'] = 'Please enter password';
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


                $doctor_id = base64_decode($this->request->data['di']);
                $service_id = base64_decode($this->request->data['si']);
                $address_id = base64_decode($this->request->data['ai']);
                $from_date = ($this->request->data['from_date']);
                $to_date = ($this->request->data['to_date']);

                $result =array();
                $connection = ConnectionUtil::getConnection();
                $connection->autocommit(false);
                $sql = "delete from appointment_bloked_slots where doctor_id =?";
                $stmt_delete_date = $connection->prepare($sql);
                $stmt_delete_date->bind_param('s', $doctor_id);
                $result[] = $stmt_delete_date->execute();

                $dates_array = Custom::createDateRange($from_date,$to_date);
                $is_date_blocked ="YES";
                foreach ($dates_array as $key => $blocked_date){
                    $created = Custom::created();
                    $query = "INSERT INTO appointment_bloked_slots (thinapp_id,created_by_user_id, doctor_id, address_id, service_id, is_date_blocked, book_date, created,modified) values (?,?,?,?,?,?,?,?,?)";
                    $stmt = $connection->prepare($query);
                    $stmt->bind_param('sssssssss', $doctor_data['thinapp_id'], $doctor_data['user_id'], $doctor_id, $address_id, $service_id, $is_date_blocked,$blocked_date, $created, $created);
                    $result[] = $stmt->execute();
                }

                if(!in_array(false,$result)){
                    $connection->commit();
                    $response['status'] = 1;
                    $response['message'] = 'Date blocked successfully';
                }else{
                    $connection->rollback();
                    $response['status'] = 0;
                    $response['message'] = 'Sorry, dates could not blocked';
                }
            }else{
                die('Invalid Request');
            }
            echo json_encode($response);die;
        }else{
            exit();
        }

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

    public function  token_list(){
        $this->layout = 'ajax';
        if($this->request->is('ajax')) {
            $data = $this->request->data;
            $thin_app_id = isset($data['thin_app_id']) ? base64_decode($data['thin_app_id']) : "";
            $doctor_id = isset($data['doctor_id']) ? base64_decode($data['doctor_id']) : "";
            $query = "SELECT t.logo, acss.drive_folder_id as folder_id,  acss.referred_by as counter, acss.appointment_staff_id, acss.patient_queue_type, acss.thinapp_id, acss.send_to_lab_datetime, acss.skip_tracker, acss.consulting_type, acss.id AS appointment_id, acss.payment_status, acss.appointment_patient_name AS patient_name, IFNULL(ac.id,c.id) AS patient_id, IF(ac.id IS NOT NULL, 'CUSTOMER','CHILDREN') AS patient_type, IFNULL(ac.mobile,c.mobile) AS patient_mobile, acss.queue_number, acss.status FROM appointment_customer_staff_services AS acss left join thinapps as t on t.id = acss.thinapp_id LEFT JOIN appointment_customers AS ac ON ac.id=acss.appointment_customer_id LEFT JOIN childrens AS c ON c.id= acss.children_id WHERE acss.appointment_staff_id = $doctor_id and acss.thinapp_id=$thin_app_id AND DATE(acss.appointment_datetime) = DATE(NOW()) AND acss.status !='DELETED' AND acss.is_paid_booking_convenience_fee !='NO' ORDER BY acss.appointment_datetime desc, acss.send_to_lab_datetime ASC";
            $connection = ConnectionUtil::getConnection();
            $data =array();
            $list = $connection->query($query);
            if ($list->num_rows) {
                $data = mysqli_fetch_all($list,MYSQLI_ASSOC);
                $thin_app_id =base64_encode($thin_app_id);
                $doctor_id =base64_encode($doctor_id);
            }
            $this->set(compact('data','thin_app_id','doctor_id'));
        }else{
            die();
        }

    }

    public function close_appointment()
    {
        $this->autoRender = false;
        if ($this->request->is('ajax')) {
            $appointment_id = base64_decode($this->request->data['id']);
              $login_mobile= base64_decode($this->request->data['m']);
            $appointment_data = Custom::get_appointment_by_id($appointment_id);
            if($appointment_data){
                $post = array();
                $admin_data = Custom::get_thinapp_admin_data($appointment_data['thinapp_id']);
                $post['app_key'] = APP_KEY;
                $post['user_id'] = $admin_data['id'];
                $post['thin_app_id'] = $admin_data['thinapp_id'];
                $post['appointment_id'] = $appointment_id;
                $result = json_decode(WebservicesFunction::close_appointment($post, true), true);
                if ($result['status'] == 1) {

                    $thin_app_id = $appointment_data['thinapp_id'];
                    $doctor_id = $appointment_data['appointment_staff_id'];
                    if($appointment_data['thinapp_id']==CK_BIRLA_APP_ID){
                        $res = Custom::assign_counter_to_next_token($thin_app_id,$doctor_id);
                        $res = Custom::create_counter_log($thin_app_id, 'CLOSED',$appointment_id);
                    }

                    $notification_array = $result['notification_array'];
                    unset($result['notification_array']);
                    Custom::sendResponse($result);
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
        if ($this->request->is('ajax')) {
            $appointment_id = base64_decode($this->request->data['id']);
            $appointment_data = Custom::get_appointment_by_id($appointment_id);
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
        if ($this->request->is('ajax')) {
            $appointment_id = base64_decode($this->request->data['id']);
            $appointment_data = Custom::get_appointment_by_id($appointment_id);
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
                    if($appointment_data['thinapp_id']==CK_BIRLA_APP_ID){
                        $res = Custom::assign_counter_to_next_token($thin_app_id,$doctor_id);
                        $res = Custom::create_counter_log($thin_app_id, 'SKIPPED',$appointment_id);
                    }
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

    public function un_skip_appointment()
    {
        $this->autoRender = false;
        if ($this->request->is('ajax')) {
            $appointment_id = base64_decode($this->request->data['id']);
            $appointment_data = Custom::get_appointment_by_id($appointment_id);
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
                    $logo = QUTOT_LOGO;
                    $app_singup_from = 'QUTOT';
                    $query = "update thinapps set app_singup_from=?, logo=?, booking_convenience_fee_restrict_ivr =?, booking_convenience_fee=?, booking_convenience_fee_video=?, booking_convenience_fee_audio=?, booking_convenience_fee_chat=? where id = ?";
                    $stmt_thinapp = $connection->prepare($query);
                    $stmt_thinapp->bind_param('ssssssss', $app_singup_from, $logo, $booking_convenience_fee_restrict_ivr, $booking_convenience_fee, $booking_convenience_fee, $booking_convenience_fee, $booking_convenience_fee, $thin_app_id);
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
                    $web_app_url = Custom::create_web_app_url($first_doctor_id,$thin_app_id,'QUTOT');

                    $qr_code_url = SITE_PATH."qutot_row/qr-code-generate.php?t=".base64_encode($thin_app_id);
                    $qr_code_url = Custom::short_url($qr_code_url,$thin_app_id);
                    $app_created_detail_url = SITE_PATH."qutot/app_created/".base64_encode($thin_app_id);
                    $app_created_detail_url = Custom::short_url($app_created_detail_url,$thin_app_id);
                    $password = $username = substr($admin_mobile_number,-10);


                    $message = "Congratulations!\n\nYour web apps are ready. Please read instructions before you get started using following link\n\nAdmin Link $app_created_detail_url\nUser name : $username\nPassword : $password\n\n1.0 QuTOT Walk-in\nPurpose : For users  to book token on site\n\n$qr_code_url \n\nYou can email QR Code by selecting above link and take print on your PC. Paste this QR Code at prominent place which is easily accessible by users.\n\nThe above QR Code linked interface is for users.\n\n2.0 QuTOT Schedule\nPurpose : For users  to book token  from home\n\nUsers app to book Token online from home or anywhere.\n\n3.0 QuTOT Counter\nPurpose : For Counter 1 to manage token on Mobile / Desktop to  manage Queue\n\nYou can also upload your logo and change profile settings on upper right corner.\n\n4.0 Users can search your company on QuTOT search portal to book token\n\nTeam  QuTOT";

                    $send_sms['message'] = $message;
                    $send_sms['mobile'] = $admin_mobile_number;
                    $send_sms['email'] = $email;

                    $response['status'] = 1;
                    $response['message'] = 'App Created Successfully';
                    $response['thin_app_id'] = $thin_app_id;
                    $response['app_name'] = $clinic_name;
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
                $res = Custom::sendWhatsappSms($send_sms['mobile'],$send_sms['message']);
                $message = "QuTOT Schedule App of $clinic_name\n\nWebapp Link $web_app_url\n\nUsers can book Token online from home or anywhere.";
                //$res = Custom::sendWhatsappSms($send_sms['mobile'],$message);
                if(!empty($email)){
                    $subject = "WEB APP CREATED";
                    $this->Custom->sendEmail($email,'engage@mengage.in',$subject,$message);
                }
            }

            die;
        }
    }

    public function add_more_staff(){
        $this->autoRender = false;
        if ($this->request->is('ajax')) {

            $data_array = $this->request->data;
            $staff_name = trim($data_array['sn']);
            $staff_type = trim($data_array['staff_type']);
            $doctor_id = @$data_array['di'];
            $thin_app_id = base64_decode($data_array['t']);

            $mobile_number =$admin_mobile_number= Custom::create_mobile_number($data_array['sm']);
            $country_id = !empty($data_array['country_id'])?$data_array['country_id']:0;
            $state_id = !empty($data_array['state_id'])?$data_array['state_id']:0;;
            $city_id = !empty($data_array['city_id'])?$data_array['city_id']:0;

            $service_name = @($data_array['service_name']);
            $offline = !empty($data_array['service_amount'])?$data_array['service_amount']:0;
            $video = !empty($data_array['video_consulting_amount'])?$data_array['video_consulting_amount']:0;
            $audio = !empty($data_array['audio_consulting_amount'])?$data_array['audio_consulting_amount']:0;
            $chat = !empty($data_array['chat_consulting_amount'])?$data_array['chat_consulting_amount']:0;
            $service_duration = !empty($data_array['service_duration'])?$data_array['service_duration']:"";

            $time_from = !empty($data_array['from_time'])?$data_array['from_time']:APPOINTMENT_WORKING_START_TIME;
            $time_to = !empty($data_array['from_time'])?$data_array['to_time']:APPOINTMENT_WORKING_END_TIME;

            $time_from = date('h:i A', strtotime($time_from));
            $time_to = date('h:i A', strtotime($time_to));

            $created = Custom::created();
            $connection = ConnectionUtil::getConnection();
            $connection->autocommit(false);
            $query = "SELECT t.user_id, t.id, aa.id AS address_id,aa.address, ser.id AS service_id FROM thinapps AS t LEFT JOIN appointment_addresses AS aa ON aa.thinapp_id = t.id AND aa.`status` ='ACTIVE' LEFT JOIN appointment_services AS ser ON ser.thinapp_id = t.id AND ser.`status` ='ACTIVE' WHERE t.id = $thin_app_id and t.status='ACTIVE' LIMIT 1";
            $connection = ConnectionUtil::getConnection();
            $data_list = $connection->query($query);
            if ($data_list->num_rows) {

                $data =  mysqli_fetch_assoc($data_list);
                $address_id = $data['address_id'];
                //$service_id = $data['service_id'];
                $address = $data['address'];
                $email = '';
                $save_array =array();
                $country_code = "+91";
                $is_offline_consulting = !empty($offline)?"YES":"NO";
                $is_online_consulting = !empty($video)?"YES":"NO";
                $is_audio_consulting = !empty($audio)?"YES":"NO";
                $is_chat_consulting = !empty($chat)?"YES":"NO";
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


                            $sql = "INSERT INTO appointment_services (thinapp_id, user_id, name,  service_amount, video_consulting_amount, audio_consulting_amount, chat_consulting_amount, service_slot_duration, created, modified) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                            $stmt_service = $connection->prepare($sql);
                            $stmt_service->bind_param('ssssssssss', $thin_app_id, $data['user_id'], $service_name, $offline, $video, $audio, $chat, $service_slot_duration, $created, $created);
                            if($stmt_service->execute()){
                                $save_array[] = true;
                                $service_id = $stmt_service->insert_id;

                            }else{
                                $save_array[] = false;
                                $service_id = 0;
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

                            $doctor_id = @$data_array['ass_di'];
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




    public function send_otp(){
        $this->autoRender = false;
        if($this->request->is(array('Post','Put'))) {
            $phone = Custom::create_mobile_number($this->request->data['m']);
            if($phone){
                $verification_code = Custom::getRandomString(6);
                //$verification_code = 123456;
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

    public function form_preview($flag)
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
            $this->set(compact('data_array','service','data','category_name'));
            if($flag=='WALK-IN'){
                $this->render('form_preview_walk', 'ajax');
            }else if($flag=='WEB_APP'){
                $this->render('form_web_app_preview', 'ajax');
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
                return $stmt_thinapp->execute();
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


    public function load_doctor_time_slot()
    {
        $this->layout = 'ajax';
        $this->autoRender = false;
        if ($this->request->is('ajax')) {
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
            $post['appointment_user_role'] = 'USER';
            $response = json_decode(WebservicesFunction::get_doctor_time_slot($post, true),true);
            if($response['status']==1){
                unset($response['data']['day_list']);
                unset($response['data']['blocked_slot']);
            }
            echo  json_encode($response);die;



        } else {
            exit();
        }

    }


}
