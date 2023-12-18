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
App::import('Controller', 'Services');
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
class PrescriptionController extends AppController {

/**
 * This controller does not use a model
 *
 * @var array
 */
	public $uses = array();

	
	function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow('*');
        if (!$this->Auth->loggedIn()) {
            $this->Auth->logout();
            $this->redirect('/org-login');
        }
    }
	
	
/**
 * Displays a view
 *
 * @param mixed What page to display
 * @return void
 * @throws NotFoundException When the view file could not be found
 *	or MissingViewException in debug mode.
 */


    public function index($doctor_id=null)
    {
        $this->layout = false;
        $this->autoRender = true;
        $login = $this->Session->read('Auth.User.User');
        if(!empty($doctor_id)){
            $doctor_id = $this->Session->read('Auth.User.AppointmentStaff.id');
        }else{
            $doctor_id = base64_decode($doctor_id);
        }



        $thin_app_id =$login['thinapp_id'];
        $doctor = Custom::get_doctor_by_id($doctor_id,$thin_app_id);
        if(!empty($doctor_id) && !empty($thin_app_id)){
            Custom::clone_master_tab_steps($thin_app_id,$doctor_id);
        }
        $steps = Custom::get_doctor_steps($doctor_id);
        $final_array =array();
        if(!empty($steps)){
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
        }
        $this->layout = false;
        $login = $this->Session->read('Auth.User.User');
        $thinappID = $login['thinapp_id'];
        $statsUrl = array();
        $statsUrl['appointment'] = Router::url('/chart/appointment_stats_graph.php?p=' . $thin_app_id,true);
        $statsUrl['medical_record'] = Router::url( '/chart/medical_record_stats_graph.php?p=' . $thin_app_id,true);
        $statsUrl['app_download'] = Router::url( '/chart/app_download_stats_graph.php?p=' . $thin_app_id,true);
        $statsUrl['sms'] = Router::url('/chart/sms_stats_graph.php?p=' . $thin_app_id,true);
        $statsUrl['refer_doc'] =Router::url( '/chart/refer_doc_stats_graph.php?p=' . $thin_app_id,true);
        $statsUrl['blog'] = Router::url( '/chart/blog_stats_graph.php?p=' . $thin_app_id,true);
        $total_records = Custom::tab_get_dashboard_counts($thin_app_id,$doctor_id,date('Y-m-d'),date('Y-m-d'));
        $this->set(compact('doctor','final_array','all_patient','prescription_setting','statsUrl','total_records'));


    }


    public function patient_list($data=null)
    {
        $return = false;
        $single_id =0;

        if(!empty($data)){
            $this->request->data = $data;
            $single_id = $data['single_id'];
            $return =true;
        }
        $this->layout = 'ajax';
        if($this->request->is('ajax') || $return===true) {

            $login = $this->Session->read('Auth.User.User');
            $doctor_data = @$this->Session->read('Auth.User.AppointmentStaff');
            $post['app_key'] = APP_KEY;
            $post['user_id'] =$login['id'];
            $post['thin_app_id'] =$login['thinapp_id'];
            $post['doctor_id'] =@$doctor_data['id'];
            $post['role_id'] =$login['role_id'];
            $post['mobile'] =$login['mobile'];
            $post['address_id'] =  @($this->request->data['ai']);
            $post['service_id'] =  @($this->request->data['si']);
            $post['request_for'] =  $action_for = ($this->request->data['request_for']);
            $post['single_id'] =  $single_id;
            $post['send_app_data'] =  "NO";
            $date =  @$this->request->data['date'];
            $post['date'] = date('Y-m-d');
            if(!empty($date)){
                $date = DateTime::createFromFormat('d/m/Y', $date);
                $post['date']  =$date->format('Y-m-d');
            }
            $post['search']  = isset($this->request->data['s'])?$this->request->data['s']:'';
            $post['offset']  = $offset = isset($this->request->data['o'])?$this->request->data['o']:0;
            $post_data =  json_decode(WebServicesFunction_2_3::tab_get_children_list($post),true);
            $stats_url = @$post_data['stats_url'];
            if($post_data['status']==0){
                if($action_for=="APPOINTMENT"){
                    $post_data['message'] = ($offset==0)?'No appointment available':'No more appointment';
                }else{
                    $post_data['message'] = ($offset==0)?'No patient available':'No more patient';
                }
            }
            $this->set(compact('offset','post_data','action_for','stats_url'));
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
            $doctor_data = @$this->Session->read('Auth.User.AppointmentStaff');
            $post['app_key'] = APP_KEY;
            $post['user_id'] = $login['id'];
            $post['thin_app_id'] = $login['thinapp_id'];
            $post['role_id'] = $login['role_id'];
            $post['mobile'] = $login['mobile'];
            $post['template_id_string'] = "[]";
            $post['add_via'] = "WEB";
            $post['doctor_id'] = @$doctor_data['id'];
            $post['patient_type'] =  ($this->request->data['pt']);
            $post['patient_id'] =  base64_decode($this->request->data['pi']);
            $custom_data = json_decode($this->request->data['cd'],true);
            $post['prescription_string'] =$this->request->data['ps'];
            $post['prescription_html'] =($this->request->data['ph']);
            $post['prescription_image'] =$pre_base64 =  ($this->request->data['pre_base64']);;
            $post['folder_id'] =base64_decode($this->request->data['fi']);
            $appointment_id =$this->request->data['ai'];
            $result = json_decode(WebServicesFunction_2_3::tab_save_prescription($post,true),true);
            if($result['status']==1){
                $prescription_id = $result['server_id'];
                $notification_array =array();
                if(!empty($appointment_id)){
                    $post['appointment_id'] =base64_decode($appointment_id);
                    $send_post = $post;
                    $close_response = json_decode(WebservicesFunction::close_appointment($send_post,true,'CLINIC'),true);
                    if($close_response['status']==1){
                        $notification_array = $close_response['notification_array'];
                        $send['single_id']=base64_decode($appointment_id);
                        $send['date']=date('d/m/Y');
                        $send['di']=base64_encode($doctor_data['id']);
                        $send['request_for']="APPOINTMENT";
                        $update = PrescriptionController::patient_list($send);
                        $result['li']= $update->body();
                    }
                }

                Custom::sendResponse($result);
                Custom::send_process_to_background();
                $post['base64'] =$pre_base64;
                Custom::upload_web_prescription($post,true,$prescription_id);
                if(!empty($custom_data) && count($custom_data) > 0){
                    foreach($custom_data as $key => $custom){
                        if(strtoupper(key($custom)) =="VITALS"){
                            $post['vital_array'] = json_encode(array('vital_array'=>json_decode($custom[key($custom)],true)));
                            $result = WebServicesFunction_2_3::tab_update_vital_info($post);
                        }else if(strtoupper(key($custom)) =="FOLLOW_UP"){
                            $follow_up_data = json_decode($custom[key($custom)],true)[0];
                            $post['action_type'] = "ADD";
                            $post['message'] = $follow_up_data['message'];
                            if(!empty($follow_up_data['date'])){
                                $date = DateTime::createFromFormat('d/m/Y', $follow_up_data['date']);
                                $post['reminder_date'] = $date->format("Y-m-d");
                                $result = WebServicesFunction_2_3::tab_manage_reminder($post);
                            }

                        }
                    }
                }
                if(!empty($notification_array)){
                    Custom::close_appointment_notification($notification_array);
                }

            }else{
                return json_encode($result);
            }



        }else{
            exit();
        }

    }


    public function update_detail(){


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
        $gender = @$params['m_gender'];
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
                    $data['id'] = $patient_id;
                    $data['mobile'] = $patient_mobile;
                    $user_data = Custom::get_user_by_mobile($login['thinapp_id'],$patient_mobile);
                    $data['user_id'] = !empty($user_data)?$user_data['id']:0;
                    $data['child_name'] = $patient_name;
                    $dob_editable= Custom::is_dob_editable($thin_app_id,$patient_id);
                    if($dob_editable===true){
                        $data['dob'] = $dob;
                        $data['age'] = $age;
                        $data['gender'] = $gender;
                    }

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
                    $send['single_id']=$response['data']['appointment_id'];
                    $send['request_for']="APPOINTMENT";
                    $send['di']=base64_encode($doctor_id);
                    $update = PrescriptionController::patient_list($send);
                    unset($response['data']);
                    unset($response['notification_data']);
                    $response['data']['li']= $update->body();

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
                $send['single_id']=$appointment_id;
                $send['date']=$date;
                $send['di']=base64_encode($doctor_id);
                $send['request_for']="APPOINTMENT";
                $update = PrescriptionController::patient_list($send);
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
                $send['single_id']=$appointment_id;
                $send['date']=$date;
                $send['di']=base64_encode($doctor_id);
                $send['request_for']="APPOINTMENT";
                $update = PrescriptionController::patient_list($send);
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


    public function add_new_patient()
    {
        $this->autoRender =false;
        if($this->request->is('ajax')) {
            $post=array();
            $login = $this->Session->read('Auth.User.User');
            $post['app_key'] = APP_KEY;
            $post['user_id'] =$login['id'];
            $post['thin_app_id'] =$login['thinapp_id'];
            $post['mobile'] =$login['mobile'];
            $post['patient_type'] ='CUSTOMER';
            $post['patient_name'] =($this->request->data['patient_name']);
            $post['patient_mobile'] =($this->request->data['patient_mobile']);
            $post['dob'] =($this->request->data['dob']);
            $post['age'] =@($this->request->data['age']);
            $post['gender'] =($this->request->data['gender']);
            $response =  json_decode( WebServicesFunction_2_3::tab_add_patient($post),true);
            if($response['status']==1){
                $post['request_for'] = "CUSTOMER";
                $post['single_id'] = $response['data']['patient_id'];
                $update = PrescriptionController::patient_list($post);
                $response['data']['li']= ($update->body());
            }
            echo json_encode($response);die;
        }else{
            exit();
        }

    }

    public function add_new_child()
    {
        $this->autoRender =false;
        if($this->request->is('ajax')) {
            $post=array();
            $login = $this->Session->read('Auth.User.User');
            $post['app_key'] = APP_KEY;
            $post['user_id'] =$login['id'];
            $post['thin_app_id'] =$login['thinapp_id'];
            $post['mobile'] =$login['mobile'];
            $post['patient_type'] ='CHILDREN';
            $post['patient_name'] =($this->request->data['patient_name']);
            $post['patient_mobile'] =($this->request->data['patient_mobile']);
            $post['dob'] =($this->request->data['dob']);
            $post['gender'] =($this->request->data['gender']);
            $response =  json_decode( WebServicesFunction_2_3::tab_add_patient($post),true);
            if($response['status']==1){
                $post['request_for'] = "CHILDREN";
                $post['single_id'] = $response['data']['child_id'];
                $update = PrescriptionController::patient_list($post);
                $response['data']['li']= ($update->body());
            }
            echo json_encode($response);die;
        }else{
            exit();
        }

    }

    public function sms_template_list_modal($return=false)
    {
        if($this->request->is('ajax')) {
            $post=array();
            $login = $this->Session->read('Auth.User.User');
            $doctor_data = @$this->Session->read('Auth.User.AppointmentStaff');
            $post['app_key'] = APP_KEY;
            $post['user_id'] =$login['id'];
            $post['thin_app_id'] =$login['thinapp_id'];
            $post['mobile'] =$login['mobile'];
            $post['doctor_id'] =@$doctor_data['id'];
            $data = json_decode(WebServicesFunction_2_3::get_sms_template_list($post),true);
            $this->set(compact( 'data'));
            if($return===true){
                return $this->render('sms_template_list_modal', 'ajax');
            }else{
                $this->render('sms_template_list_modal', 'ajax');
            }

        }else{
            exit();
        }
    }

    public function manage_sms_template()
    {
        $this->autoRender = false;
        if($this->request->is('ajax')) {
            $post=array();
            $login = $this->Session->read('Auth.User.User');
            $doctor_data = @$this->Session->read('Auth.User.AppointmentStaff');
            $post['app_key'] = APP_KEY;
            $post['user_id'] =$login['id'];
            $post['thin_app_id'] =$login['thinapp_id'];
            $post['mobile'] =$login['mobile'];
            $post['doctor_id'] =@$doctor_data['id'];
            $post['action_type'] =($this->request->data['action_type']);
            $post['message'] =($this->request->data['message']);
            if(!empty($this->request->data['ti'])){
                $post['template_id'] =base64_decode($this->request->data['ti']);
            }
            $response = json_decode(WebServicesFunction_2_3::manage_sms_template($post),true);
            if($response['status']==1){
                $update = PrescriptionController::sms_template_list_modal(true);
                $response['data']['li']= ($update->body());
            }
            echo json_encode($response);die;
        }else{
            exit();
        }
    }

    public function doctor_list_modal($data=null)
    {
        $return = false;
        if(!empty($data)){
            $this->request->data = $data;
            $return =true;
        }
        $this->layout = 'ajax';
        if($this->request->is('ajax') || $return===true) {
            $post=array();
            $login = $this->Session->read('Auth.User.User');
            $post['app_key'] = APP_KEY;
            $post['user_id'] =$login['id'];
            $post['thin_app_id'] =$login['thinapp_id'];
            $post['role_id'] =$login['role_id'];
            $post['mobile'] =$login['mobile'];
            $data = json_decode(WebServicesFunction::appointment_get_staff_list($post),true);
            $this->set(compact( 'data'));
            if($return===true){
                return $this->render('doctor_list_modal', 'ajax');;
            }else{
                $this->render('doctor_list_modal', 'ajax');
            }


        }else{
            exit();
        }
    }

    public function doctor_manage_modal()
    {
        if($this->request->is('ajax')) {
            $post=array();
            $login = $this->Session->read('Auth.User.User');
            $post['app_key'] = APP_KEY;
            $post['user_id'] =$login['id'];
            $post['thin_app_id'] =$login['thinapp_id'];
            $post['role_id'] =$login['role_id'];
            $post['mobile'] =$login['mobile'];
            $data =array();
            if(!empty($this->request->data['di'])){
                $doctor_id = base64_decode($this->request->data['di']);
                $data = Custom::get_doctor_by_id($doctor_id,$login['thinapp_id']);
            }
            $this->set(compact( 'data'));
            $this->render('doctor_manage_modal', 'ajax');

        }else{
            exit();
        }
    }

    public function manage_doctor()
    {
        $this->autoRender = false;
        if($this->request->is('ajax')) {
            $post=array();
            $login = $this->Session->read('Auth.User.User');
            $post['app_key'] = APP_KEY;
            $post['user_id'] =$login['id'];
            $post['thin_app_id'] =$login['thinapp_id'];
            $post['mobile'] =$login['mobile'];
            $action_type =($this->request->data['at']);
            $service = new ServicesController();
            $service->request = $this->request;
            $service->AppointmentStaff = $this->AppointmentStaff;
            $service->Custom = $this->Custom;
            $service->AppointmentDayTime = $this->AppointmentDayTime;
            $service->AppointmentStaff = $this->AppointmentStaff;
            $service->AppointmentStaffHour = $this->AppointmentStaffHour;
            if($action_type=='ADD'){
                $post['name'] =@$this->request->data['doctor_name'];
                $post['sub_title'] =@$this->request->data['education'];
                $post['mobile'] =@$this->request->data['doctor_mobile'];
                $response =  json_decode($service->appointment_add_staff_profile($post),true);
                $update = PrescriptionController::doctor_list_modal($post);
                $response['data']['update']= ($update->body());
                echo json_encode($response);die;

            }else if($action_type=='UPDATE'){
                $post['name'] =@$this->request->data['doctor_name'];
                $post['sub_title'] =@$this->request->data['education'];
                $post['mobile'] =@$this->request->data['doctor_mobile'];
                $post['staff_id'] =@base64_decode($this->request->data['di']);
                $response =  json_decode($service->appointment_edit_staff_profile($post),true);
                $update = PrescriptionController::doctor_list_modal($post);
                $response['data']['update']= ($update->body());
                echo json_encode($response);die;

            }else{
                $post['doctor_id'] =@base64_decode($this->request->data['di']);
                $response =  json_decode(WebServicesFunction::delete_appointment_doctor($post),true);
                $update = PrescriptionController::doctor_list_modal($post);
                $response['data']['update']= ($update->body());
                echo json_encode($response);die;
            }
        }else{
            exit();
        }
    }


    public function address_list_modal()
    {
        if($this->request->is('ajax')) {
            $post=array();
            $login = $this->Session->read('Auth.User.User');
            $post['app_key'] = APP_KEY;
            $post['user_id'] =$login['id'];
            $post['thin_app_id'] =$login['thinapp_id'];
            $post['role_id'] =$login['role_id'];
            $post['mobile'] =$login['mobile'];
            $data = json_decode(WebServicesFunction::get_appointment_address_list($post),true);
            $this->set(compact( 'data'));
            $this->render('address_list_modal', 'ajax');

        }else{
            exit();
        }
    }

    public function address_manage_modal()
    {
        if($this->request->is('ajax')) {
            $post=array();
            $login = $this->Session->read('Auth.User.User');
            $post['app_key'] = APP_KEY;
            $post['user_id'] =$login['id'];
            $post['thin_app_id'] =$login['thinapp_id'];
            $post['role_id'] =$login['role_id'];
            $post['mobile'] =$login['mobile'];
            $data =array();
            if(!empty($this->request->data['ai'])){
                $address_id = base64_decode($this->request->data['ai']);
                $data = Custom::get_address_by_id($address_id,$login['thinapp_id']);
            }
            $this->set(compact( 'data'));
            $this->render('address_manage_modal', 'ajax');

        }else{
            exit();
        }
    }

    public function manage_address()
    {
        $this->autoRender = false;
        if($this->request->is('ajax')) {
            $post=array();
            $login = $this->Session->read('Auth.User.User');
            $post['app_key'] = APP_KEY;
            $post['user_id'] =$login['id'];
            $post['thin_app_id'] =$login['thinapp_id'];
            $post['mobile'] =$login['mobile'];
            $action_type =($this->request->data['at']);
            if($action_type=='ADD'){
                $post['address'] =@$this->request->data['address'];
                $post['clinic_name'] =@$this->request->data['clinic_name'];
                $post['country_id'] =@$this->request->data['country_id'];
                $post['state_id'] =@$this->request->data['state_id'];
                $post['city_id'] =@$this->request->data['city_id'];
                $post['latitude'] =@$this->request->data['latitude'];
                $post['longitude'] =@$this->request->data['longitude'];
                $post['palace'] =@$this->request->data['city_name'];
                $post['pincode'] =@$this->request->data['pincode'];
                return WebServicesFunction::add_appointment_address($post);
            }else if($action_type=='UPDATE'){
                $post['address'] =@$this->request->data['address'];
                $post['clinic_name'] =@$this->request->data['clinic_name'];
                $post['country_id'] =@$this->request->data['country_id'];
                $post['state_id'] =@$this->request->data['state_id'];
                $post['city_id'] =@$this->request->data['city_id'];
                $post['latitude'] =@$this->request->data['latitude'];
                $post['longitude'] =@$this->request->data['longitude'];
                $post['palace'] =@$this->request->data['city_name'];
                $post['pincode'] =@$this->request->data['pincode'];
                $post['addres_id'] =@base64_decode($this->request->data['ai']);
                return WebServicesFunction::edit_appointment_address($post);
            }else{
                $post['address_id'] =@base64_decode($this->request->data['ai']);
                return WebServicesFunction::delete_appointment_address($post);
            }
        }else{
            exit();
        }
    }

    public function service_list_modal($data=null)
    {
        $return = false;
        if(!empty($data)){
            $this->request->data = $data;
            $return =true;
        }
        $this->layout = 'ajax';
        if($this->request->is('ajax') || $return===true) {
            $post=array();
            $login = $this->Session->read('Auth.User.User');
            $doctor_data = @$this->Session->read('Auth.User.AppointmentStaff');
            $post['app_key'] = APP_KEY;
            $post['user_id'] =$login['id'];
            $post['thin_app_id'] =$login['thinapp_id'];
            $post['mobile'] =$login['mobile'];
            $post['doctor_id'] =@$doctor_data['id'];
            $data = json_decode(WebServicesFunction_2_3::tab_service_category_list($post),true);
            $this->set(compact( 'data'));
            if($return===true){
                return $this->render('service_list_modal', 'ajax');;
            }else{
                $this->render('service_list_modal', 'ajax');
            }
        }else{
            exit();
        }
    }

    public function manage_category_and_service()
    {
        $this->autoRender = false;
        if($this->request->is('ajax')) {
            $post=array();
            $login = $this->Session->read('Auth.User.User');
            $doctor_data = @$this->Session->read('Auth.User.AppointmentStaff');
            $post['app_key'] = APP_KEY;
            $post['user_id'] =$login['id'];
            $post['thin_app_id'] =$login['thinapp_id'];
            $post['mobile'] =$login['mobile'];
            $post['doctor_id'] =@$doctor_data['id'];
            $post['category_id'] =@base64_decode($this->request->data['ci']);
            $post['service_id'] =@base64_decode($this->request->data['si']);
            $post['name'] =@$this->request->data['name'];
            $post['price'] =@$this->request->data['price'];
            $post['action_for'] =@$this->request->data['af'];
            $post['action_type'] =@$this->request->data['at'];
            $response =  json_decode(WebServicesFunction_2_3::tab_manage_category_and_service($post),true);
            $update = PrescriptionController::service_list_modal($post);
            $response['data']['update']= ($update->body());
            echo json_encode($response);die;
        }else{
            exit();
        }
    }

    public function follow_up_list_modal()
    {
        if($this->request->is('ajax')) {
            $post=array();
            $login = $this->Session->read('Auth.User.User');
            $doctor_data = @$this->Session->read('Auth.User.AppointmentStaff');
            $post['app_key'] = APP_KEY;
            $post['user_id'] =$login['id'];
            $post['thin_app_id'] =$login['thinapp_id'];
            $post['doctor_id'] =@$doctor_data['id'];
            $post['role_id'] =$login['role_id'];
            $post['mobile'] =$login['mobile'];
            $post['from_date'] =date('d-m-Y');
            $post['end_date'] =date('d-m-Y');
            $data = PrescriptionController::follow_up_list_template($post)->body();
            $this->set(compact( 'data'));
            $this->render('follow_up_list_modal', 'ajax');

        }else{
            exit();
        }
    }

    public function follow_up_list_template($data=null)
    {
        $return = false;
        if(!empty($data)){
            $this->request->data = $data;
            $return =true;
        }
        $this->layout = 'ajax';
        if($this->request->is('ajax') || $return===true) {
            $post=array();
            $login = $this->Session->read('Auth.User.User');
            $post['app_key'] = APP_KEY;
            $post['user_id'] =$login['id'];
            $post['thin_app_id'] =$login['thinapp_id'];
            $post['role_id'] =$login['role_id'];
            $post['mobile'] =$login['mobile'];
            $post['name'] =@$this->request->data['name'];
            $post['from_date'] =@$this->request->data['from_date'];
            $post['end_date'] =@$this->request->data['to_date'];
            $post['offset'] = $offset = @$this->request->data['offset'];
            $limit = PAGINATION_LIMIT;
            $data = json_decode(WebServicesFunction::get_appointment_reminder($post),true);

            $this->set(compact( 'data','offset','limit'));
            if($return===true){
                return $this->render('follow_up_list_template', 'ajax');;
            }else{
                $this->render('follow_up_list_template', 'ajax');
            }

        }else{
            exit();
        }
    }

    public function send_follow_up_alert()
    {
        if($this->request->is('ajax')) {
            $post=array();
            $login = $this->Session->read('Auth.User.User');
            $doctor_data = @$this->Session->read('Auth.User.AppointmentStaff');
            $post['app_key'] = APP_KEY;
            $post['user_id'] =$login['id'];
            $post['thin_app_id'] =$login['thinapp_id'];
            $post['doctor_id'] =@$doctor_data['id'];
            $post['role_id'] =$login['role_id'];
            $post['mobile'] =$login['mobile'];
            $post['follow_up_id'] =@base64_decode($this->request->data['fi']);
            return WebServicesFunction_2_3::send_follow_up_alert($post);
        }else{
            exit();
        }
    }

    public function payment_modal($data=null)
    {
        $return = false;
        if(!empty($data)){
            $this->request->data = $data;
            $return =true;
        }
        $this->layout = 'ajax';
        if($this->request->is('ajax') || $return===true) {
            $post=array();
            $login = $this->Session->read('Auth.User.User');
            $doctor_data = @$this->Session->read('Auth.User.AppointmentStaff');
            $patient_id = @base64_decode($this->request->data['pi']);
            $address_id = @base64_decode($this->request->data['ai']);
            $service_id = @base64_decode($this->request->data['si']);
            $appointment_id = @base64_decode($this->request->data['app_i']);
            $patient_type = @base64_decode($this->request->data['pt']);
            $post['app_key'] = APP_KEY;
            $post['user_id'] =$login['id'];
            $post['thin_app_id'] =$login['thinapp_id'];
            $post['doctor_id'] =@$doctor_data['id'];
            $post['role_id'] =$login['role_id'];
            $post['mobile'] =$login['mobile'];
            $post['from_date'] =date('d-m-Y');
            $post['end_date'] =date('d-m-Y');
            $history = Custom::tab_get_payment_history($post['doctor_id'],$patient_id,$patient_type);
            $opd_amount = 0;
            if(!empty($appointment_id)){
                $appointment_data = Custom::get_appointment_by_id($appointment_id);
                $opd_amount = !empty($appointment_data)?$appointment_data['amount']:0;
            }
            $data = Custom::tab_payment_modal_data($patient_id,$patient_type);
            $this->set(compact( 'opd_amount','doctor_data','login','appointment_id','patient_id','patient_type','history','data','service_id','address_id'));
            if($return===true){
                return $this->render('payment_modal', 'ajax');
            }else{
                $this->render('payment_modal', 'ajax');
            }

        }else{
            exit();
        }
    }

    public function save_payment()
    {
        $this->autoRender = false;
        if($this->request->is('ajax')) {
            $post=array();
            $login = $this->Session->read('Auth.User.User');
            $doctor_data = @$this->Session->read('Auth.User.AppointmentStaff');
            $post['app_key'] = APP_KEY;
            $post['user_id'] =$login['id'];
            $post['thin_app_id'] =$login['thinapp_id'];
            $post['doctor_id'] =@$doctor_data['id'];
            $post['role_id'] =$login['role_id'];
            $post['mobile'] =$login['mobile'];
            $post['patient_type'] = ($this->request->data['pt']);
            $array['service_array'] = ($this->request->data['sa']);
            $post['service_array'] = json_encode($array);
            $post['patient_id'] = base64_decode($this->request->data['pi']);
            $appointment_id =0;
            if(!empty($this->request->data['app_i'])){
                $appointment_id = base64_decode($this->request->data['app_i']);
            }
            $post['address_id'] = base64_decode($this->request->data['ai']);
            $post['charged_amount'] = ($this->request->data['ca']);
            $post['total_amount'] = ($this->request->data['ta']);
            $post['paid_amount'] = ($this->request->data['pa']);
            $post['appointment_id'] =$appointment_id;
            $post['pi'] = $this->request->data['pi'];
            $post['pt'] = $this->request->data['pt'];
            $response =  json_decode(WebServicesFunction_2_3::add_patient_payment($post),true);
            if($response['status']==1){
                $post['pt'] = base64_encode($this->request->data['pt']);
                $update = PrescriptionController::payment_modal($post);
                $response['data']['update']= ($update->body());
                if(!empty($appointment_id)){
                    $post['request_for'] = "APPOINTMENT";
                    $post['single_id'] = $appointment_id;
                    $update = PrescriptionController::patient_list($post);
                    $response['data']['appointment']= ($update->body());
                }
            }
            echo json_encode($response);die;


        }else{
            exit();
        }
    }

    public function print_invoice($order_id)
    {
        $this->layout = false;
        $invoiceData = array();
        $invoice_type ='IPD';
        $order_id = base64_decode($order_id);
        if ($order_id){
            $query = "select aa.address, aa.clinic_name, mpo.id as order_id, app_staff.sub_title as education, IFNULL(ac.uhid,c.uhid) as uhid,  mpo.charged_amount, mpo.total_amount as total_paid,   (SELECT pda.amount FROM patient_due_amounts AS pda WHERE pda.settlement_by_order_id = mpo.id AND pda.paid_via_patient ='YES' AND pda.payment_status = 'PAID' AND pda.status='ACTIVE' order by pda.id desc limit 1) AS due_paid_amount ,  (SELECT SUM(pda.amount) FROM patient_due_amounts AS pda WHERE  pda.medical_product_order_id = mpo.id and pda.paid_via_patient = 'NO' ) AS total_due_amount ,  mpo.created as billing_date,  IFNULL(mp.name,service) as service_name, mpod.total_amount as service_paid_amount, t.name as app_name, t.logo,  aa.address, app_staff.name as doctor_name, IFNULL(ac.first_name,c.child_name) as patient_name, IFNULL(ac.mobile,c.mobile) as mobile FROM medical_product_orders as mpo   LEFT  join appointment_customers as ac on ac.id = mpo.appointment_customer_id left join childrens as c on c.id = mpo.children_id left join hospital_payment_types as hpt on hpt.id = mpo.hospital_payment_type_id  left join medical_product_order_details as mpod on mpod.medical_product_order_id = mpo.id  left join medical_product_quantities as mpq on mpod.medical_product_quantity_id = mpq.id  left join appointment_staffs as app_staff on app_staff.id = mpo.appointment_staff_id left join hospital_ipd  as hi on hi.id = mpo.hospital_ipd_id left join appointment_staffs as biller on biller.user_id =mpo.created_by_user_id and biller.status = 'ACTIVE' and biller.thinapp_id = mpo.thinapp_id and biller.staff_type IN('DOCTOR','RECEPTIONIST') left join medical_products as mp on mp.id = mpod.medical_product_id left join appointment_categories as department on department.id= app_staff.appointment_category_id join thinapps as t on t.id = mpo.thinapp_id left join appointment_addresses as aa on aa.id= mpo.appointment_address_id left JOIN appointment_categories AS category ON category.id = app_staff.id where mpo.id = $order_id and mpo.status = 'ACTIVE' GROUP BY mpod.id";
            $connection = ConnectionUtil::getConnection();
            $list_obj = $connection->query($query);
            if ($list_obj->num_rows) {
                $invoiceData = mysqli_fetch_all($list_obj, MYSQLI_ASSOC);
            }
        }

        if(!empty($invoiceData)){
            $this->set(compact('invoiceData','invoice_type'));
        }
        else{
            die('Invalid Request');
        }
        $this->render('print_invoice', 'ajax');
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


    public function get_dashboard_total()
    {
        $this->autoRender = false;
        if($this->request->is('ajax')) {
            $post=array();
            $login = $this->Session->read('Auth.User.User');
            $doctor_data = @$this->Session->read('Auth.User.AppointmentStaff');
            $post['app_key'] = APP_KEY;
            $post['user_id'] =$login['id'];
            $thin_app_id =$login['thinapp_id'];
            $doctor_id =@$doctor_data['id'];
            $from_date = ($this->request->data['fd']);
            $to_date = ($this->request->data['td']);
            $from_date = DateTime::createFromFormat('d/m/Y', $from_date);
            $from_date = $from_date->format('Y-m-d');
            $to_date = DateTime::createFromFormat('d/m/Y', $to_date);
            $to_date = $to_date->format('Y-m-d');
            return json_encode(Custom::tab_get_dashboard_counts($thin_app_id,$doctor_id,$from_date,$to_date));

        }else{
            exit();
        }
    }

    public function patient_detail(){
        $this->layout = false;
        $login = $this->Session->read('Auth.User.User');
        $post['app_key'] = APP_KEY;
        $post['user_id'] =$login['id'];
        $post['mobile'] =$login['mobile'];
        $post['thin_app_id'] =$login['thinapp_id'];
        $post['patient_type'] = $patient_type = $this->request->data['pt'];
        $post['patient_id'] = $patient_id = base64_decode($this->request->data['pi']);
        $appointment_id = !empty($this->request->data['ai'])?base64_decode($this->request->data['ai']):0;
        $doctor_data = @$this->Session->read('Auth.User.AppointmentStaff');
        $post['doctor_id'] =  @$doctor_data['id'];
        $data =  json_decode(WebServicesFunction_2_3::tab_get_general_info($post),true)['data'];
        if(!empty($data['general_info'])){
            $data['general_info']['appointment_id'] = $appointment_id;
        }if(!empty($data['vitals'])){
            foreach($data['vitals'] as $key => $vital){
                $data['vitals'][$key]['value'] = base64_encode($vital['value']);
            }
        }

        $master_vital_other_notes = MASTER_VITAL_OTHER_NOTES;

        $this->set(compact('data','master_vital_other_notes','patient_id','patient_type'));
    }



     public function update_patient_detail(){
            $this->layout = false;
            $this->autoRender =false;
            $login = $this->Session->read('Auth.User.User');
            $post['app_key'] = APP_KEY;
            $post['user_id'] =$login['id'];
            $post['mobile'] =$login['mobile'];
            $post['thin_app_id'] =$login['thinapp_id'];
            $post['patient_type'] = $this->request->data['pt'];
            $post['patient_id'] = base64_decode($this->request->data['pi']);
            $post['patient_mobile'] = ($this->request->data['pm']);
            $request_for = ($this->request->data['rf']);
            $post['patient_age'] = ($this->request->data['age']);
            $post['gender'] = ($this->request->data['gender']);
            $post['patient_name'] = ($this->request->data['name']);
            $post['address'] = ($this->request->data['address']);
            $post['dob'] = ($this->request->data['dob']);
            $post['medical_history'] = ($this->request->data['history']);
            $post['other_mobile'] = ($this->request->data['parent_mobile']);
            $post['update_from'] = "WEB";
            $response =  json_decode(WebServicesFunction_2_3::tab_update_general_info($post),true);
             if($response['status']==1){
                 $post['request_for'] = $request_for;
                 $post['single_id'] = $post['patient_id'];
                 $update = PrescriptionController::patient_list($post);
                 $response['data']['update']= ($update->body());
             }

             echo json_encode($response);die;

        }

    public function prescription_window(){
        $this->layout = false;
        $login = $this->Session->read('Auth.User.User');
        $post['app_key'] = APP_KEY;
        $post['user_id'] =$login['id'];
        $post['mobile'] =$login['mobile'];
        $post['thin_app_id'] =$login['thinapp_id'];
        $post['patient_type'] = $patient_type = $this->request->data['pt'];
        $post['patient_id'] = $patient_id = base64_decode($this->request->data['pi']);
        $address_id = base64_decode($this->request->data['ai']);
        $appointment_id = base64_decode($this->request->data['app_id']);

        $doctor_data = @$this->Session->read('Auth.User.AppointmentStaff');
        $post['doctor_id'] =  @$doctor_data['id'];
        $data =  json_decode(WebServicesFunction_2_3::tab_get_prescription_category_list($post),true);
        $prescription_id = @$data['prescription_id'];
        $data = @$data['data'];
        $category_id =@$data['list'][0]['id'];
        $post['ci'] = base64_encode($category_id);
        $post['pt'] = $patient_type ;
        $post['pi'] = base64_encode($patient_id);
        $update = PrescriptionController::templates_list($post);
        $template_html= $update->body();

        $internal_notes_master_category_id = INTERNAL_NOTES_MASTER_CATEGORY_ID;
        $vitals_master_category_id = VITALS_MASTER_CATEGORY_ID;
        $prescription_setting_master_category_id = PRESCRIPTION_SETTING_MASTER_CATEGORY_ID;
        $prescription_template_master_category_id = PRESCRIPTION_TEMPLATE_MASTER_CATEGORY_ID;
        $follow_up_master_category_id = FOLLOW_UP_MASTER_CATEGORY_ID;
        $vaccination_master_category_id = VACCINATION_MASTER_CATEGORY_ID;
        $master_vital_other_notes = MASTER_VITAL_OTHER_NOTES;
    	$master_flags = MASTER_FLAGS;
        $master_allergy = MASTER_ALLERGY;

    	$doctor_data = @$this->Session->read('Auth.User.AppointmentStaff');
        $post['app_key'] = APP_KEY;
        $post['user_id'] =$login['id'];
        $post['mobile'] =$login['mobile'];
        $post['thin_app_id'] =$login['thinapp_id'];
        $post['doctor_id'] =  @$doctor_data['id'];
        $prescription_setting = Custom::get_web_prescription_data($doctor_data,$login['thinapp_id'],$patient_id,$patient_type,$address_id,$appointment_id);

        $symptoms_steps_list = $flag_list = $allergy_list =array();
        if(!empty($prescription_setting['notes'])){
            $post['category_id'] = MASTER_SYMPTOMS_CATEGORY_ID;
            $symptoms_steps_list =  json_encode(json_decode(WebServicesFunction_2_3::tab_get_category_step($post),true)['data']['step_list']);
        }
        if(!empty($prescription_setting['allergy'])){
            $post['category_id'] = MASTER_ALLERGY;
            $allergy_list =  json_encode(json_decode(WebServicesFunction_2_3::tab_get_category_step($post),true)['data']['step_list']);
        }
        if(!empty($prescription_setting['flag'])){
            $post['category_id'] = MASTER_FLAGS;
            $flag_list =  json_encode(json_decode(WebServicesFunction_2_3::tab_get_category_step($post),true)['data']['step_list']);
        }
    
       
        $vitals =Custom::pad_get_patient_vitals($login['thinapp_id'],$patient_id,$patient_type);
        $this->set(compact('flag_list','allergy_list', 'master_flags', 'master_allergy','symptoms_steps_list','master_vital_other_notes','address_id','vaccination_master_category_id','follow_up_master_category_id','prescription_template_master_category_id','prescription_setting_master_category_id','prescription_setting','vitals_master_category_id','vitals','internal_notes_master_category_id','data','patient_id','patient_type','template_html','category_id','prescription_id','doctor_data'));

        $this->render('prescription_window', 'ajax');


     }


    public function templates_list($data=null){
        $this->layout = false;
        $this->autoRender = true;
        $return = false;
        if(!empty($data)){
            $return = true;
            $this->autoRender =false;
            $this->request->data = $data;
        }
        $login = $this->Session->read('Auth.User.User');
        $post['app_key'] = APP_KEY;
        $post['user_id'] =$login['id'];
        $post['mobile'] =$login['mobile'];
        $post['thin_app_id'] =$login['thinapp_id'];
        $post['category_id'] = $category_id = base64_decode($this->request->data['ci']);
        $category_name = @$this->request->data['cn'];
        $post['patient_id'] = @base64_decode($this->request->data['pi']);
        $post['patient_type'] = @$this->request->data['pt'];
        $doctor_data = @$this->Session->read('Auth.User.AppointmentStaff');
        $post['doctor_id'] =  @$doctor_data['id'];
        $follow_up_master_category_id = FOLLOW_UP_MASTER_CATEGORY_ID;
        $prescription_template_master_category_id = PRESCRIPTION_TEMPLATE_MASTER_CATEGORY_ID;
        $sms_template_list = $template_list = $patient_reminder_list = array();
        if($category_id==$follow_up_master_category_id){
            $patient_reminder_list = Custom::pad_get_patient_reminder_list($login['thinapp_id'], $post['patient_id'], $post['patient_type']);
            $patient_reminder_list = empty($patient_reminder_list) ? array() : $patient_reminder_list;
            $sms_template_list = json_decode(WebServicesFunction_2_3::get_sms_template_list($post),true);
            if(!empty($sms_template_list['data']['list'])){
                $sms_template_list = array_column($sms_template_list['data']['list'],"message","id");
            }else{
                $sms_template_list =array();
            }
        }else{
            $template_list =  @json_decode(WebServicesFunction_2_3::tab_get_prescription_template_list($post),true);
            $step_modal=null;
            if($template_list['status']==0){
                $template_list = null;
                if($prescription_template_master_category_id != $category_id){
                    if(empty($category_name)){
                        $category_data = @Custom::tab_get_category_data($doctor_data['id'],$category_id);
                        $category_name = !empty($category_data)?$category_data['name']:'';
                    }
                    $post['cn'] = $category_name;
                    $post['ci'] = base64_encode($category_id);
                    $update = PrescriptionController::step_modal($post);
                    $step_modal= ($update->body());
                }

            }else{
                $template_list = $template_list['data']['list'];
            }
        }

        $this->set(compact('step_modal','prescription_template_master_category_id','patient_reminder_list','sms_template_list','follow_up_master_category_id','data','patient_id','patient_type','template_list','category_id','category_name'));
        if($return===true){
            return $this->render('templates_list', 'ajax');
        }else{
            $this->render('templates_list', 'ajax');
        }

    }



    public function step_modal($data=null){

        $return = false;
        if(!empty($data)){
            $return =true;
            $this->request->data = $data;
        }
        $this->layout = false;
        $login = $this->Session->read('Auth.User.User');
        $post['app_key'] = APP_KEY;
        $post['user_id'] =$login['id'];
        $post['mobile'] =$login['mobile'];
        $post['thin_app_id'] =$login['thinapp_id'];
        //$post['patient_type'] = $patient_type = $this->request->data['pt'];
        //$post['patient_id'] = $patient_id = base64_decode($this->request->data['pi']);
        $post['category_id'] = $category_id = base64_decode($this->request->data['ci']);
        $category_name = ($this->request->data['cn']);
        $doctor_data = @$this->Session->read('Auth.User.AppointmentStaff');
        $post['doctor_id'] =  @$doctor_data['id'];
        $steps_list =  @json_decode(WebServicesFunction_2_3::tab_get_category_step($post),true)['data']['step_list'];
        $internal_notes_master_category_id = INTERNAL_NOTES_MASTER_CATEGORY_ID;
        $master_medicine_name_step_id = MASTER_MEDICINE_NAME_STEP_ID;
        $prescription_controller = $this;
        $this->set(compact('prescription_controller','data','patient_id','patient_type','steps_list','category_id','category_name','internal_notes_master_category_id','master_medicine_name_step_id'));
        if($return === true){
            return $this->render('step_modal', 'ajax');
        }else{
            $this->render('step_modal', 'ajax');
        }

    }


    public function save_template(){
        $this->layout = false;
        $this->autoRender = false;
        $login = $this->Session->read('Auth.User.User');
        $post['app_key'] = APP_KEY;
        $post['user_id'] =$login['id'];
        $post['mobile'] =$login['mobile'];
        $post['thin_app_id'] =$login['thinapp_id'];
        $post['category_id'] = $category_id = base64_decode($this->request->data['ci']);
        $post['patient_id'] = $patient_id = base64_decode($this->request->data['pi']);
        $post['patient_type'] =  $this->request->data['pt'];
        $post['template_name'] = ($this->request->data['tn']);
        $post['data_array'] = ($this->request->data['da']);
        $doctor_data = @$this->Session->read('Auth.User.AppointmentStaff');
        $post['doctor_id'] =  @$doctor_data['id'];
        $post['template_for'] =  "WEB";
        $data =  json_decode(WebServicesFunction_2_3::tab_add_template($post),true);
        $post['ci'] = base64_encode($category_id);
        $post['pi'] = base64_encode($patient_id);
        $post['pt'] =  $post['patient_type'];
        $update = PrescriptionController::templates_list($post);
        $template_html= $update->body();
        $data['template_list'] = $template_html;
        echo json_encode($data);die;

    }

    public function manage_template(){
        $this->layout = false;
        $this->autoRender = false;
        $login = $this->Session->read('Auth.User.User');
        $post['app_key'] = APP_KEY;
        $post['user_id'] =$login['id'];
        $post['mobile'] =$login['mobile'];
        $post['thin_app_id'] =$login['thinapp_id'];
        $post['category_id'] = $category_id = base64_decode($this->request->data['ci']);
        $post['template_id'] = $template_id = base64_decode($this->request->data['ti']);
        $post['template_name'] = @($this->request->data['tn']);
        $post['action_type'] = $this->request->data['at'];
        $doctor_data = @$this->Session->read('Auth.User.AppointmentStaff');
        $post['doctor_id'] =  @$doctor_data['id'];
        $data =  json_decode(WebServicesFunction_2_3::tab_mangage_template($post),true);
        $post['ci'] = base64_encode($category_id);
        $post['pi'] = @$this->request->data['pi'];
        $post['pt'] = @$this->request->data['pt'];

        $update = PrescriptionController::templates_list($post);
        $template_html= $update->body();
        $data['template_list'] = $template_html;
        echo json_encode($data);die;

    }

    public function manage_tag(){
        $this->layout = false;
        $this->autoRender = false;
        $login = $this->Session->read('Auth.User.User');
        $post['app_key'] = APP_KEY;
        $post['user_id'] =$login['id'];
        $post['mobile'] =$login['mobile'];
        $post['thin_app_id'] =$login['thinapp_id'];
        $post['step_id'] = $category_id = @base64_decode($this->request->data['si']);
        $post['category_master_id'] = @($this->request->data['cmi']);
        $post['tag_id']  = @base64_decode($this->request->data['ti']);
        $post['tag_name'] = @$this->request->data['tn'];
        $post['action_type'] = $this->request->data['at'];
        $doctor_data = @$this->Session->read('Auth.User.AppointmentStaff');
        $post['doctor_id'] =  @$doctor_data['id'];
        $result =  json_decode(WebServicesFunction_2_3::tab_manage_step_tag($post),true);
        if($result['status']==1){
            $html_string = "";
            $tag_id_array =array();
            if($post['action_type']=="ADD" || $post['action_type']=="UPDATE"){
                if($post['action_type']=="ADD"){
                    $tag_id_array = explode(',',$result['tag_id']);
                }else{
                    $tag_id_array = array($post['tag_id']);
                }
                $list = Custom::get_tag_by_ids($tag_id_array);
                foreach ($list as $key =>$tag){
                    $html_string .= Custom::create_tag_layout($tag,$tag['step_id']);
                }
                $result['html'] =$html_string;
            }
        }
        echo json_encode($result);die;


    }


    public function manage_medicine_modal(){
        $this->layout = false;
        $medicine_type_list=array();
        $company_list =array(
            array('company_name'=>'Cipla'),
            array('company_name'=>'Readdy'),
            array('company_name'=>'Tiger'),
            array('company_name'=>'Volini')
        );

        $composition_list =array(
            array('composition'=>'Each 40 mg/0.8 mL HYRIMOZ'),
            array('composition'=>'polysorbate 80 (0.8 mg), sodium chloride (4.93 mg)'),
            array('composition'=>'citric acid monohydrate (0.206 mg)'),
            array('composition'=>'Each 0.8 mL of HYRIMOZ')
        );

        $tmp_list = Custom::get_medicine_type_list();
        foreach ($tmp_list as $key => $type){
            $medicine_type_list[$key]['type'] =$type;
        }
        $step_id = @base64_decode($this->request->data['si']);
        $tag_id = @base64_decode($this->request->data['ti']);
        $label = "Add";
        if(!empty($tag_id)){
            $this->request->data = @Custom::get_tag_by_ids(array($tag_id))[0];
            $label = "Update";
        }


        $this->set(compact('step_id','label','company_list','medicine_type_list','composition_list'));
        $this->render('manage_medicine_modal', 'ajax');


    }



    public function manage_medicine(){
        $this->layout = false;
        $this->autoRender = false;
        $login = $this->Session->read('Auth.User.User');
        $post['app_key'] = APP_KEY;
        $post['user_id'] =$login['id'];
        $post['mobile'] =$login['mobile'];
        $post['thin_app_id'] =$login['thinapp_id'];
        $post['step_id'] = MASTER_MEDICINE_NAME_STEP_ID;
        $post['tag_id']  = @base64_decode($this->request->data['ti']);
        $post['step_id'] = @base64_decode($this->request->data['si']);
        $post['tag_name'] = @$this->request->data['tag_name'];
        $post['tag_type'] = @$this->request->data['tag_type'];
        $post['company_name'] = @$this->request->data['company_name'];
        $post['composition'] = @$this->request->data['composition'];
        $post['tag_notes'] = @$this->request->data['tag_notes'];
        $post['action_type'] = $this->request->data['at'];
        $post['category_master_id'] =MEDICINE_MASTER_CATEGORY_ID;
        $doctor_data = @$this->Session->read('Auth.User.AppointmentStaff');
        $post['doctor_id'] =  @$doctor_data['id'];
        $result =  json_decode(WebServicesFunction_2_3::tab_manage_step_tag($post),true);
        if($result['status']==1){
            $html_string = "";
            $tag_id_array =array();
            if($post['action_type']=="ADD" || $post['action_type']=="UPDATE"){
                if($post['action_type']=="ADD"){
                    $tag_id_array = explode(',',$result['tag_id']);
                }else{
                    $tag_id_array = array($post['tag_id']);
                }
                $list = Custom::get_tag_by_ids($tag_id_array);
                foreach ($list as $key =>$tag){
                    $html_string .= Custom::create_tag_layout($tag,$tag['step_id']);
                }
                $result['html'] =$html_string;
            }
        }
        echo json_encode($result);die;


    }



    public function create_tag_layout($tag_data,$step_id){
        $this->layout = false;
        $master_medicine_name_step_id = MASTER_MEDICINE_NAME_STEP_ID;
        $this->set(compact('tag_data','step_id','master_medicine_name_step_id'));
        $result  = $this->render('create_tag_layout', 'ajax');
        return $result->body();

    }


    public function delete_patient_reminder(){
        $this->layout = false;
        $this->autoRender = false;
        $login = $this->Session->read('Auth.User.User');
        $post['app_key'] = APP_KEY;
        $post['user_id'] =$login['id'];
        $post['mobile'] =$login['mobile'];
        $post['thin_app_id'] =$login['thinapp_id'];
        $post['reminder_id'] = @base64_decode($this->request->data['ri']);
        $post['patient_id'] = @base64_decode($this->request->data['pi']);
        $post['patient_type'] = @($this->request->data['pt']);
        $post['action_type'] = "DELETE";
        $doctor_data = @$this->Session->read('Auth.User.AppointmentStaff');
        $post['doctor_id'] =  @$doctor_data['id'];
        return WebServicesFunction_2_3::tab_manage_reminder($post);
    }

    public function prescription_list_modal()
    {
        if($this->request->is('ajax')) {
            $post=array();
            $login = $this->Session->read('Auth.User.User');
            $doctor_data = @$this->Session->read('Auth.User.AppointmentStaff');
            $post['app_key'] = APP_KEY;
            $post['user_id'] =$login['id'];
            $post['thin_app_id'] =$login['thinapp_id'];
            $post['mobile'] =$login['mobile'];
            $post['doctor_id'] =@$doctor_data['id'];
            $folder_id = @base64_decode($this->request->data['fi']);
            $data = Custom::tab_get_folder_prescription__list($login['thinapp_id'],$folder_id);
            $this->set(compact( 'data'));
            $this->render('prescription_list_modal', 'ajax');

        }else{
            exit();
        }
    }


    public function delete_prescription(){
        $this->layout = false;
        $login = $this->Session->read('Auth.User.User');
        $post['app_key'] = APP_KEY;
        $post['user_id'] =$login['id'];
        $post['mobile'] =$login['mobile'];
        $post['thin_app_id'] =$login['thinapp_id'];
        $post['drive_file_id'] = $patient_id = base64_decode($this->request->data['fi']);
        $doctor_data = @$this->Session->read('Auth.User.AppointmentStaff');
        $post['doctor_id'] =  @$doctor_data['id'];
        return WebServicesFunction::delete_file($post);
    }


    public function load_certificate()
    {
        $this->layout = false;
        $data= @$this->Session->read('Auth.User.AppointmentStaff');
        $this->set(compact( 'data'));
        $this->render('load_certificate', 'ajax');
    }

    public function save_certificate(){
        $this->layout = false;
        $this->autoRender = false;
        $login = $this->Session->read('Auth.User.User');
        $post['app_key'] = APP_KEY;
        $post['user_id'] =$login['id'];
        $post['mobile'] =$login['mobile'];
        $post['thin_app_id'] =$login['thinapp_id'];
        $post['patient_id'] = @base64_decode($this->request->data['pi']);
        $post['address_id'] = @base64_decode($this->request->data['ai']);
        $post['folder_id'] = @base64_decode($this->request->data['fi']);
        $post['patient_type'] = @($this->request->data['pt']);
        $post['patient_name'] = @($this->request->data['pn']);
        $post['disease'] = @($this->request->data['disease']);
        $post['start_date'] = @($this->request->data['sd']);
        $post['end_date'] = @($this->request->data['ed']);
        $post['start_treatment'] = @($this->request->data['st']);
        $post['end_treatment'] = @($this->request->data['et']);
        $post['location'] = @($this->request->data['l']);
        $post['date'] = @($this->request->data['d']);
        $post['base64'] = @($this->request->data['base64']);
        $post['clinic_name'] = @($this->request->data['cn']);
        $post['certificate_html'] = @($this->request->data['ch']);
        $doctor_data = @$this->Session->read('Auth.User.AppointmentStaff');
        $post['doctor_id'] =  @$doctor_data['id'];
        $post['doctor_name'] =  @$doctor_data['name'];
        WebServicesFunction_2_3::tab_save_medical_certificate($post);
    }


    public function payment_service_modal()
    {
        $this->layout = 'ajax';
        if($this->request->is('ajax')) {
            $doctor_data = @$this->Session->read('Auth.User.AppointmentStaff');
            $appointment_id =$this->request->data['ai'];
            $appointment_data=array();
            if(!empty($appointment_id)){
                $appointment_data = Custom::get_appointment_by_id($appointment_id);
            }
            $payment_service_list = Custom::tab_get_service_list($doctor_data['id'],$appointment_id);
            $this->set(compact( 'appointment_data','payment_service_list'));
            $this->render('payment_service_modal', 'ajax');
        }else{
            exit();
        }
    }

    public function add_new_appointment()
    {
        $this->layout = 'ajax';
        if($this->request->is('ajax')) {
            $doctor = @$this->Session->read('Auth.User.AppointmentStaff');
            $all_patient = Custom::get_patient_list_autocomplete($doctor['thinapp_id']);
            $this->set(compact( 'doctor','all_patient'));
            $this->render('add_new_appointment', 'ajax');
        }else{
            exit();
        }
    }

}
