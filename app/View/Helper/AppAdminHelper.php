<?php
/**
 * Application level View Helper
 *
 * This file is application-wide helper file. You can put all
 * application-wide helper-related methods here.
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
 * @package       app.View.Helper
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */



App::uses('Helper', 'View');

App::uses('SessionComponent', 'Controller/Component');

/**
 * Application helper
 *
 * Add your application-wide methods in the class below, your helpers
 * will inherit them.
 *
 * @package       app.View.Helper
 */
class AppAdminHelper extends AppHelper {

    public function getLeadPaymentStatus($app_url)
    {
        $user = ClassRegistry::init('Leads');
        $user_data = $user->find('first',array('conditions'=>array('Leads.org_unique_url'=>$app_url)));
        $user_image = $user_data['Leads']['app_payment'];
        return $user_image;
    }

    public function is_default_channel($user_id)
    {
        $user = ClassRegistry::init('Channel');
        $user_data = $user->find('count',array('conditions'=>array('Channel.channel_status'=>"DEFAULT",'Channel.user_id'=>$user_id)));
        return $user_data;
    }

    public function app_has_default_channel($thin_app_id)
    {
        $user = ClassRegistry::init('Channel');
        $user_data = $user->find('count',array('conditions'=>array('Channel.channel_status'=>"DEFAULT",'Channel.app_id'=>$thin_app_id)));
        return $user_data;
    }

    public function default_channel_data($thin_app_id)
    {
        $user = ClassRegistry::init('Channel');
        $user_data = $user->find('first',array('contain'=>false,'conditions'=>array('Channel.channel_status'=>"DEFAULT",'Channel.app_id'=>$thin_app_id)));
        return $user_data['Channel'];
    }
   


    public function getAppQueries($lead_id)
    {
        $user = ClassRegistry::init('AppQueries');
        $queries = $user->find('all',array(
            'conditions'=>array(
                'AppQueries.customer_lead_id'=>$lead_id
            ),
            "fields"=>array("AppQueries.attachment","AppQueries.sender_id","AppQueries.message","AppQueries.created","Sender.username","UploadApk.name","UploadApk.version"),
            "contain"=>array("Sender","UploadApk"),

            'order'=>array("AppQueries.id" =>'DESC')
        ));
        return $queries;
    }


    public function getThemeDropDown()
    {
        $user = ClassRegistry::init('AppTheme');
        $organization = $user->find('list', array('fields' => array('id','theme_name'),array('status'=>true)));
        return $organization;
    }
    public function getHospitalServiceCategoryArray($thin_app_id)
    {

        $condition = array('status'=>'ACTIVE','thinapp_id'=>$thin_app_id);
        $session  = new SessionComponent(new ComponentCollection());

        $loginLab = $session->read('Auth.User');
        $roleLab = $loginLab['USER_ROLE'];
        if($roleLab =="LAB" || $roleLab =='PHARMACY'){

            $isInhouse = $loginLab['LabPharmacyUser']['is_inhouse'];

            if($isInhouse == 'YES')
            {
                $condition['HospitalServiceCategory.lab_pharmacy_user_id'] = array($loginLab['LabPharmacyUser']['id'],0);
            }
            else
            {
                $condition['HospitalServiceCategory.lab_pharmacy_user_id'] = $loginLab['LabPharmacyUser']['id'];
            }


        }
        else
        {
            $condition['OR'] = array('HospitalServiceCategory.lab_pharmacy_user_id' => 0,"(HospitalServiceCategory.lab_pharmacy_type = 'LAB' AND HospitalServiceCategory.lab_pharmacy_is_inhouse = 'YES')",);
        }

        $user = ClassRegistry::init('HospitalServiceCategory');
        $organization = $user->find('list', array('fields' => array('id','name'),'conditions'=>$condition,'order'=>array('HospitalServiceCategory.name'=>'ASC')));
        return $organization;
    }
    public function getHospitalTaxRateArray($thin_app_id)
    {

        $return =array();
        $user = ClassRegistry::init('HospitalTaxRate');
        $condition = array('status'=>'ACTIVE','thinapp_id'=>$thin_app_id);
        $session  = new SessionComponent(new ComponentCollection());

        $loginLab = $session->read('Auth.User');
        $roleLab = $loginLab['USER_ROLE'];
        if($roleLab =="LAB" || $roleLab =='PHARMACY'){
                $condition['HospitalTaxRate.lab_pharmacy_user_id'] = $loginLab['LabPharmacyUser']['id'];
        }
        else
        {
            $condition['OR'] = array('HospitalTaxRate.lab_pharmacy_user_id' => 0,"(HospitalTaxRate.lab_pharmacy_type = 'LAB' AND HospitalTaxRate.lab_pharmacy_is_inhouse = 'YES')",);
        }
        $organization = $user->find('all', array('fields' => array('id',"name",'rate'),'conditions'=>$condition,'order'=>array('HospitalTaxRate.name'=>'ASC')));

        if(!empty($organization)){
            foreach ($organization as $key =>$val){
                $val = $val['HospitalTaxRate'];
                $return[$val['id']] = $val['name']." - ".$val['rate']."%";
            }
        }
        return $return;
    }
    public function getHospitalPaymentTypeArray($thin_app_id)
    {

        $condition = array('HospitalPaymentType.status'=>'ACTIVE','HospitalPaymentType.thinapp_id'=>$thin_app_id);
        $session  = new SessionComponent(new ComponentCollection());

        $loginLab = $session->read('Auth.User');
        $roleLab = $loginLab['USER_ROLE'];
        if($roleLab =="LAB" || $roleLab =='PHARMACY'){
            $condition['HospitalPaymentType.lab_pharmacy_user_id'] = $loginLab['LabPharmacyUser']['id'];
        }
        else
        {
            $condition['OR'] = array('HospitalPaymentType.lab_pharmacy_user_id' => 0,"(HospitalPaymentType.lab_pharmacy_type = 'LAB' AND HospitalPaymentType.lab_pharmacy_is_inhouse = 'YES')",);
        }

        $condition = array(
            "OR"=> array(
                $condition,array('HospitalPaymentType.status'=>'ACTIVE','HospitalPaymentType.thinapp_id'=>0)
            )
        );

        $return =array();
        $user = ClassRegistry::init('HospitalPaymentType');
        $organization = $user->find('list', array('fields' => array('id',"name"),'conditions'=>$condition,'order'=>array('HospitalPaymentType.name'=>'ASC')));

        return $organization;
    }


    public function getLeadData($app_url)
    {
        $user = ClassRegistry::init('Leads');
        $user_data = $user->find('first',array('conditions'=>array('Leads.org_unique_url'=>$app_url)));
        return $user_data['Leads'];
    }


    public function getMembership()
    {
        $user = ClassRegistry::init('Membership');
        $organization = $user->find('all',array('status'=>'ACTIVE'));
        return $organization;
    }



    public function getPaymentHistory($user_id=null)
    {
        $user = ClassRegistry::init('Payment');
        $organization = $user->find('all',array('user_id'=>$user_id));
        return $organization;
    }


    public function get_theme_image($theme_id=0)
    {
            $AppTheme = ClassRegistry::init('AppTheme');
            $theme = $AppTheme->find("first", array("conditions" => array(
                "AppTheme.id" =>$theme_id
            )));
            if(isset($theme['AppTheme']['theme_image']) && !empty($theme['AppTheme']['theme_image'])){
                return $theme['AppTheme']['theme_image'];
            }else{
                return "default.png";
            }

    }

    public function getCurrentMembership($user_id=null)
    {
        $user = ClassRegistry::init('Payments');
        $queries = $user->find('first',array(
            'conditions'=>array(
                'Payments.user_id'=>$user_id
            ),
            'order'=>array("Payments.id" =>'DESC')
        ));
        if(!empty($queries)){
            return $queries;
        }else{
            return false;
        }
    }

    public function getAppointmentAddress($thin_app_id=null)
    {
        $list =array();
        $app_add = ClassRegistry::init('AppointmentAddress');
        $list = $app_add->find('list',array(
            'conditions'=>array(
                'AppointmentAddress.thinapp_id'=>$thin_app_id,
                'AppointmentAddress.status'=>'ACTIVE'
            ),
            'fields'=>array('place','place'),
            'contain'=>false,
            'order'=>array("AppointmentAddress.place" =>'ASC')
        ));
        if(!empty($list)){
            $list = array_unique($list);
        }
        return $list;

    }


    public function getDriveFolderList($thin_app_id=null,$user_id=null)
    {
        $list = $condition= array();
        $session  = new SessionComponent(new ComponentCollection());
        $loginLab = $session->read('Auth.User');
        $login_role = $loginLab['USER_ROLE'];
        //return array();
        return Custom::get_folder_list_for_web($login_role,$thin_app_id,$user_id);


    }

    public function getFileCategory()
    {
        $list =array();
        $app_add = ClassRegistry::init('FileCategoryMaster');
        $list = $app_add->find('list',array(
            'conditions'=>array(
                'FileCategoryMaster.status'=>'ACTIVE',
                'FileCategoryMaster.id !='=>1
            ),
            'fields'=>array('id','category_name'),
            'contain'=>false,
            'order'=>array("FileCategoryMaster.id" =>'DESC')
        ));
        if(!empty($list)){
            $list = $list;
        }
        return $list;

    }

    public function getFileAllCategory()
    {
        $list =array();
        $app_add = ClassRegistry::init('FileCategoryMaster');
        $list = $app_add->find('list',array(
            'conditions'=>array(
                'FileCategoryMaster.status'=>'ACTIVE'
            ),
            'fields'=>array('id','category_name'),
            'contain'=>false,
            'order'=>array("FileCategoryMaster.id" =>'DESC')
        ));
        if(!empty($list)){
            $list = $list;
        }
        return $list;

    }

    public function getChannelList($user_id=null,$thin_app_id=null)
    {
        $user = ClassRegistry::init('Channel');
        $list = $user->find('list',array(
            'conditions'=>array(
                'Channel.user_id'=>$user_id,
                'Channel.app_id'=>$thin_app_id,
                'Channel.status'=>'Y',
                'Channel.channel_status'=>'DEFAULT'
            ),
            'fields'=>array('id','channel_name'),
            'contain'=>false,
            'order'=>array("Channel.channel_name" =>'ASC')
        ));
       return $list;
    }

    public function isSubcribeChannel($app_user_id=null,$thin_app_id=null,$channel_id)
    {
        $user = ClassRegistry::init('Subscriber');
        $list = $user->find('first',array(
            'conditions'=>array(
                'Subscriber.channel_id'=>$channel_id,
                'Subscriber.app_user_id'=>$app_user_id
            ),
            'fields'=>array('id','status'),
            'contain'=>false
        ));
        if(!empty($list)){
            return array('subscriberID'=>$list['Subscriber']['id'],'status'=>$list['Subscriber']['status']);
        }else{
            return false;
        }

    }


    public function getPollDurationOption($user_id=null,$thin_app_id=null)
    {

        $type = ClassRegistry::init('ActionQuestion')->getColumnType('poll_duration');
        preg_match_all("/'(.*?)'/", $type, $enums);
        $enum_array=array();
        foreach ($enums[1] as $key => $val){
            $enum_array[$val]=$val;
        }
        return $enum_array;
    }




    public function getLetestApk($lead_id)
    {
        $apkTable = ClassRegistry::init('UploadApk');
        $queries = $apkTable->find('first',array(
            'conditions'=>array(
                'UploadApk.customer_lead_id'=>$lead_id
            ),
            "fields"=>array("UploadApk.name"),
            "contain"=>false,
            'order'=>array("UploadApk.id" =>'DESC')
        ));

        if(!empty($queries)){
            return $queries;
        }
        return false;
    }



    public function getTotalSms($user_id)
    {

        $apkTable = ClassRegistry::init('AppSmsStatic');
        $queries = $apkTable->find('first',array(
            'conditions'=>array(
                'AppSmsStatic.user_id'=>$user_id
            ),
            "contain"=>false
        ));
        if(!empty($queries)){
            return $queries;
        }
        return false;
    }


    public function paymentStatus($customer_lead_id)
    {
        $user = ClassRegistry::init('Leads');
        $user_data = $user->find('first',array('conditions'=>array('Leads.customer_id'=>$customer_lead_id)));
        if(!empty($user_data['Leads']['app_payment'])){
            return $user_data['Leads']['app_payment'];
        }
        return false;
    }

    public  function  check_app_user_permission($thin_app_id,$user_fun_type_id){

        /* work for user functionlary*/
        $permissionObj = ClassRegistry::init('UserEnabledFunPermission');
        $enable_fun = $permissionObj->find('first',array(
            "conditions"=>array(
                "UserEnabledFunPermission.thinapp_id"=>$thin_app_id,
                "UserEnabledFunPermission.user_functionality_type_id"=>$user_fun_type_id,
            ),
            'contain'=>false
        ));
        if(!empty($enable_fun)){
            return $enable_fun['UserEnabledFunPermission'];
        }else{
            return false;
        }

    }


    public  function  check_app_fun_enable_permission($thin_app_id,$app_fun_type_id){

        /* work for user functionlary*/
        $permissionObj = ClassRegistry::init('AppEnableFunctionality');
        $enable_fun = $permissionObj->find('first',array(
            "conditions"=>array(
                "AppEnableFunctionality.thinapp_id"=>$thin_app_id,
                "AppEnableFunctionality.app_functionality_type_id"=>$app_fun_type_id,
            ),
            'contain'=>false
        ));
        if(!empty($enable_fun)){
                return $enable_fun;
        }else{
            return false;
        }

    }


    public  function  get_user_function_type_list($app_fun_type_id){

        /* work for user functionlary*/
        $permissionObj = ClassRegistry::init('UserFunctionalityType');
        /* work for user functionlary*/
        $fun_list = $permissionObj->find('all',array(
            "conditions"=>array(
                "UserFunctionalityType.status"=>'Y',
                "UserFunctionalityType.app_functionality_type_id"=>$app_fun_type_id,
            ),
            'contain'=>false
        ));
        if(!empty($fun_list)){
            return $fun_list;
        }else{
            return false;
        }

    }

    public function check_app_enable_permission($thin_app_id,$lable_key) {
        $result =  Custom::check_app_enable_permission($thin_app_id,$lable_key);
        $result = ($result=='YES')?true:false;
        return $result;

    }


    public function staff_appointment_count($staff_id, $thin_app_id,$date,$flag,$address_id)
    {
        $format = "Y-m-d";
        if(!empty($date)) {
            $date = date($format,strtotime($date));
        }else{
            $date =date($format);
        }
        $user = ClassRegistry::init('AppointmentCustomerStaffService');
        $conditions =array();
        if(!empty($address_id)){
            $conditions['AppointmentCustomerStaffService.appointment_address_id'] =$address_id;
        }
        $conditions['AppointmentCustomerStaffService.delete_status !=']=array("DELETED","FOLLOW_UP");
        if($flag=="NEW" || $flag=="CONFIRM" || $flag=="RESCHEDULE" || $flag=="CLOSED" || $flag=="CANCELED" ){
                $conditions['AppointmentCustomerStaffService.thinapp_id']=$thin_app_id;
                $conditions['AppointmentCustomerStaffService.appointment_staff_id']=$staff_id;
                $conditions['DATE(AppointmentCustomerStaffService.appointment_datetime)']=$date;
                $conditions['AppointmentCustomerStaffService.status']=$flag;



        }else if($flag=="EXPIRED"){
                $conditions['AppointmentCustomerStaffService.thinapp_id']=$thin_app_id;
                $conditions['AppointmentCustomerStaffService.appointment_staff_id']=$staff_id;
                $conditions['DATE(AppointmentCustomerStaffService.appointment_datetime)']=$date;
                $conditions['AppointmentCustomerStaffService.status']=array('OR'=>array('CONFIRM','RESCHEDULE'));
                $conditions['TIME(AppointmentCustomerStaffService.appointment_datetime) < ']=date('H:i:s');

        }else{
            $conditions = array(
                'AppointmentCustomerStaffService.thinapp_id'=>$thin_app_id,
                'AppointmentCustomerStaffService.appointment_staff_id'=>$staff_id,
            );
        }
        $user_data = $user->find('count',array('conditions'=>$conditions));
        return $user_data;
    }


    public function getServiceMenuCategoryDropdown($thin_app_id)
    {
        $user = ClassRegistry::init('ServiceMenuCategory');
        $organization = $user->find('list', array('fields' => array('id','name'),array('thinapp_id'=>$thin_app_id,'status'=>"ACTIVE")));
        return $organization;
    }



    public function countryDropdown($webService=false)
    {
        return Custom::getCountryList($webService);
    }

    public function stateDropdown($country_id,$web_service=false)
    {
            return Custom::getStateList($country_id,$web_service);
    }


    public function cityDropdown($state_id,$web_service=false)
    {
        return Custom::getCityList($state_id,$web_service);
    }


    public function getDepartmentList($category)
    {
        return Custom::getDepartmentListByCategory($category);
    }


    public  function  get_folder_owner_id($folder_id){
        /* work for user functionlary*/
        $permissionObj = ClassRegistry::init('DriveFolder');
        $enable_fun = $permissionObj->find('first',array(
            "conditions"=>array(
                "DriveFolder.id"=>$folder_id
            ),
            'contain'=>false
        ));
        if(!empty($enable_fun)){
            return $enable_fun['DriveFolder']['user_id'];
        }else{
            return false;
        }

    }


    public function getVaccinationDurationOption()
    {
        $type = ClassRegistry::init('MasterVaccination')->getColumnType('vac_time');
        preg_match_all("/'(.*?)'/", $type, $enums);
        $enum_array=array();
        foreach ($enums[1] as $key => $val){
            $enum_array[$val]=$val;
        }
        return $enum_array;
    }


   


    public function getVaccinationTypeOption()
    {
        $type = ClassRegistry::init('MasterVaccination')->getColumnType('vac_type');
        preg_match_all("/'(.*?)'/", $type, $enums);
        $enum_array=array();
        foreach ($enums[1] as $key => $val){
            $enum_array[$val]=$val;
        }
        return $enum_array;
    }



    public function getChildMilestonePeriod()
    {
        $milestone =array(
            '1 MONTH'=>'1 Month',
            '2 MONTHS'=>'2 Months',
            '3 MONTHS'=>'3 Months',
            '4 MONTHS'=>'4 Months',
            '5 MONTHS'=>'5 Months',
            '6 MONTHS'=>'6 Months',
            '7 MONTHS'=>'7 Months',
            '8 MONTHS'=>'8 Months',
            '9 MONTHS'=>'9 Months',
            '10 MONTHS'=>'10 Months',
            '11 MONTHS'=>'11 Months',
            '12 MONTHS'=>'12 Months',
            '1 YEAR'=>'1 Year',
            '2 YEARS'=>'2 Years',
            '3 YEARS'=>'3 Years',
            '4 YEARS'=>'4 Years',
            '5 YEARS'=>'5 Years',
            '6 YEARS'=>'6 Years',
            '7 YEARS'=>'7 Years',
            '8 YEARS'=>'8 Years',
            '9 YEARS'=>'9 Years',
            '10 YEARS'=>'10 Years',
            '11 YEARS'=>'11 Years',
            '12 YEARS'=>'12 Years',
            '13 YEARS'=>'13 Years',
            '14 YEARS'=>'14 Years',
            '15 YEARS'=>'15 Years',
            '16 YEARS'=>'16 Years',
            '17 YEARS'=>'17 Years',
            '18 YEARS'=>'18 Years'
         );
        return $milestone;
    }

    public function getAgeStringFromDob($dob,$return_array = false,$short_unit=false)
    {
        return Custom::get_age_from_dob($dob,$return_array,$short_unit);
    }

    public function getAgeStringFromAge($age_string,$return_age_string = false)
    {
        return Custom::create_age_array($age_string,$return_age_string);

    }

    public function getHospitalDoctorList($thin_app_id)
    {
        $return =array();
        $user = ClassRegistry::init('AppointmentStaff');
        return $user->find('list', array('fields' => array('id',"name"),'conditions'=>array('status'=>'ACTIVE','staff_type'=>'DOCTOR','thinapp_id'=>$thin_app_id),'order'=>array('AppointmentStaff.name'=>'ASC')));


    }

    public function getHospitalUserRole($thin_app_id,$mobile,$role_id){
       return Custom::hospital_get_user_role($mobile,$thin_app_id,$role_id);

    }

    public function getDoctorCategoryDropdown($thin_app_id)
    {
        $user = ClassRegistry::init('AppointmentCategory');
        return $user->find('list', array('fields' => array('id',"name"),'conditions'=>array('status'=>'ACTIVE','thinapp_id'=>$thin_app_id),'order'=>array('AppointmentCategory.name'=>'ASC')));
    }


    public function getHospitalCategoryType()
    {
        $user = ClassRegistry::init('HospitalServiceCategoryType');
        return $user->find('list', array('fields' => array('id',"name"),'conditions'=>array('status'=>'ACTIVE'),'order'=>array('HospitalServiceCategoryType.id'=>'ASC')));
    }


    public function getHospitalWardList($thin_app_id)
    {
        $user = ClassRegistry::init('HospitalServiceCategory');
        $organization = $user->find('list', array('fields' => array('id','name'),'conditions'=>array('thinapp_id'=>$thin_app_id,'status'=>'ACTIVE','hospital_service_category_type_id'=>array(3,4)),'order'=>array('HospitalServiceCategory.name'=>'ASC')));
        return $organization;
    }


    public function totalAdmitPateint($thin_app_id,$admit_status)
    {
        $user = ClassRegistry::init('HospitalIpd');
        $user_data = $user->find('count',array('conditions'=>array('HospitalIpd.thinapp_id'=>$thin_app_id,'HospitalIpd.status'=>"ACTIVE",'HospitalIpd.admit_status'=>$admit_status)));
        return $user_data;
    }

    public function totalToDischargePateint($thin_app_id)
    {
        $user = ClassRegistry::init('HospitalIpd');
        $user_data = $user->find('count',array('conditions'=>array('HospitalIpd.thinapp_id'=>$thin_app_id,'HospitalIpd.status'=>"ACTIVE",'HospitalIpd.to_discharge_date <>'=>'0000-00-00',"HospitalIpd.admit_status !=" => 'DISCHARGE')));
        return $user_data;
    }

    public function dateFormat($dob,$time=false){

            if($dob != '1970-01-01' && $dob != '0000-00-00 00:00:00' && $dob != '0000-00-00' && !empty($dob)){
                if($time ===true){
                    $date = DateTime::createFromFormat('Y-m-d H:i:s', $dob);
                    if($date){
                        return $date->format('d/m/Y h:i A');
                    }else{
                        return $dob;
                    }
                }else{
                    $date = DateTime::createFromFormat('Y-m-d', $dob);
                    if($date){
                        return $date->format('d/m/Y');;
                    }else{
                        return $dob;
                    }
                }
            }
            return '';
    }


    public function showIpdStatus($status){
        if($status == 'ADMIT'){
            return 'Admitted';
        }else if($status == 'DISCHARGE'){
            return 'Dischared';
        }else{
            return 'Not Admitted';
        }

    }


    public function getHospitalServiceList($service_category_id)
    {
        $user = ClassRegistry::init('MedicalProduct');
        $organization = $user->find('list', array('fields' => array('id','name'),'conditions'=>array('MedicalProduct.hospital_service_category_id'=>$service_category_id,'MedicalProduct.status'=>'ACTIVE'),'order'=>array('MedicalProduct.name'=>'ASC')));
        return $organization;
    }
    public function isAllowEditAdmitDate($ipdID){

        $MedicalProductOrder = ClassRegistry::init('MedicalProductOrder');
        $billing = $MedicalProductOrder->find("count",array('conditions'=>array('MedicalProductOrder.hospital_ipd_id' => $ipdID),"contain"=>false));
        if($billing > 0)
        {
            return false;
        }

        $HospitalDepositAmount = ClassRegistry::init('HospitalDepositAmount');
        $deposit = $HospitalDepositAmount->find("count",array('conditions'=>array('HospitalDepositAmount.hospital_ipd_id' => $ipdID),"contain"=>false));
        if($deposit > 0)
        {
            return false;
        }

        $IpdBedHistory = ClassRegistry::init('IpdBedHistory');
        $bedHistory = $IpdBedHistory->find("count",array('conditions'=>array('IpdBedHistory.hospital_ipd_id' => $ipdID),"contain"=>false));
        if($bedHistory > 1)
        {
            return false;
        }
        return true;
    }

    public function getDoctorAddressList($docID,$thinappID)
    {
        $list =  Custom::get_doctor_address_list_drp($docID,$thinappID);
        if(!empty($list)){
            return  array_column($list,'address','id');
        }else{
            return array();
        }
    }

    public function doctorCurrentAvailableTime($doctor_id){
        return Custom::getDoctorCurrentAvailableTime($doctor_id);
    }


    public function getDepartmentCategoryDropdown()
    {
        $user = ClassRegistry::init('DepartmentCategory');
        return $user->find('list', array('fields' => array('id',"category_name"),'conditions'=>array('department_name'=>'DOCTOR'),'order'=>array('DepartmentCategory.category_name'=>'ASC')));
    }



    public function getDoctorCategoryList()
    {
        $user = ClassRegistry::init('AppointmentStaff');
        return $user->find('list', array('fields' => array('DepartmentCategory.id',"DepartmentCategory.category_name"),'conditions'=>array('AppointmentStaff.status'=>'ACTIVE'),'contain'=>array('DepartmentCategory'),'order'=>array('DepartmentCategory.category_name'=>'ASC'),'group'=>array('AppointmentStaff.department_category_id')));
    }

    public function getMedicalProductOrderList($thinappID){
        $MedicalProduct = ClassRegistry::init('MedicalProduct');
        $medicalProduct = $MedicalProduct->find("all",array("conditions"=>array("MedicalProduct.thinapp_id"=>$thinappID,"MedicalProduct.status"=>"ACTIVE"),'recursive'  => 2));
        return $medicalProduct;
    }


    public function getDoctorServieListNewAppointment($thin_app_id,$doctor_id)
    {
        $return =array();
        $user = ClassRegistry::init('AppointmentStaff');
        return $user->find('list', array('fields' => array('id',"name"),'conditions'=>array('status'=>'ACTIVE','staff_type'=>'DOCTOR','thinapp_id'=>$thin_app_id),'order'=>array('AppointmentStaff.name'=>'ASC')));


    }

    public function getHospitalBillerList($thin_app_id)
    {
        $return =array();
        $user = ClassRegistry::init('AppointmentStaff');
        return $user->find('list', array('fields' => array('id',"name"),'conditions'=>array('status'=>'ACTIVE','thinapp_id'=>$thin_app_id),'order'=>array('AppointmentStaff.name'=>'ASC')));


    }

    public function getStaffNameByUserID($userID,$thinapp_id){

        $staff = ClassRegistry::init('AppointmentStaff');
        $staffData =  $staff->find('first', array('fields' => array("AppointmentStaff.name"),'conditions'=>array('AppointmentStaff.thinapp_id'=>$thinapp_id,'AppointmentStaff.user_id' => $userID),"contain"=>false));
        if(!empty($staffData['AppointmentStaff']['name']))
        {
            return $staffData['AppointmentStaff']['name'];
        }
        else
        {
            $user = ClassRegistry::init('User');
            $userData =  $user->find('first', array('fields' => array("User.username"),'conditions'=>array('User.thinapp_id'=>$thinapp_id,'User.id' => $userID),"contain"=>false));
            if(!empty($userData['User']['username']))
            {
                return $userData['User']['username'];
            }
            else
            {
                return "";
            }

        }
    }

    public function get_app_medical_product_list($thin_app_id,$send_out_of_stock_data=false){
        $list  = Custom::get_app_medical_product_list($thin_app_id,$send_out_of_stock_data);
        return $list;
    }

    public function get_app_default_medical_product_list($thin_app_id,$send_out_of_stock_data=false){
        $list  = Custom::get_app_default_medical_product_list($thin_app_id,$send_out_of_stock_data);
        return $list;
    }

    public function calculateBmi($patient_id,$patient_type){
        $list  = Custom::calculateBmi($patient_id,$patient_type);
        return $list;
    }




    public function custom_dropdown_list($doctor_id, $thin_app_id,$list_for)
    {

        $list_for = strtoupper($list_for);
        if(Custom::check_app_enable_permission($thin_app_id,"NEW_QUICK_APPOINTMENT") =="YES"){
            if($list_for =="SERVICE"){
                $list =  Custom::new_appointment_service_list($doctor_id);
                if(!empty($list)){
                    return  array_column($list,'name','id');
                }
            }else if($list_for=="ADDRESS"){
                $list =  Custom::new_appointment_address_list($doctor_id);
                if(!empty($list)){
                    return  array_column($list,'address','id');
                }
            }
        }else{

            if($list_for =="SERVICE"){
                $list =  Custom::old_appointment_service_list($doctor_id);
                if(!empty($list)){
                    return  array_column($list,'name','id');
                }
            }else if($list_for=="ADDRESS"){
                $list =  Custom::get_doctor_address_list_drp($doctor_id,$thin_app_id);
                if(!empty($list)){
                    return  array_column($list,'address','id');
                }
            }

        }
        return array();

    }


    public function get_app_address($thin_app_id){
        return Custom::get_address_list_drp($thin_app_id);
    }
     public function get_address_list($thin_app_id){
        return Custom::get_address_list($thin_app_id);
    }


    public function get_app_product_list_drp($thin_app_id){
        return Custom::get_product_list_drp($thin_app_id);
    }

    public function billing_report_service_list($thin_app_id){
        $condition = " MedicalProduct.thinapp_id = $thin_app_id AND MedicalProduct.status ='ACTIVE'";
        $session  = new SessionComponent(new ComponentCollection());
        $loginLab = $session->read('Auth.User');
        $roleLab = $loginLab['USER_ROLE'];
        if($roleLab =="LAB" || $roleLab =='PHARMACY'){
            $condition .= " and MedicalProduct.lab_pharmacy_user_id = ".$loginLab['LabPharmacyUser']['id'];
        }else{
            $condition .= " AND  ( ( MedicalProduct.lab_pharmacy_user_id = 0) OR (MedicalProduct.lab_pharmacy_type='LAB' and MedicalProduct.lab_pharmacy_is_inhouse ='YES') )";
        }
        $query = "select MedicalProduct.id,MedicalProduct.name from medical_products as MedicalProduct where $condition order by MedicalProduct.name asc ";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            return array_column(mysqli_fetch_all($service_message_list,MYSQLI_ASSOC),'name','id');
        }else{
            return array();
        }
    }
    public function billing_report_payment_type_list($thin_app_id){
        $condition = " ( HospitalPaymentType.thinapp_id = $thin_app_id AND HospitalPaymentType.status ='ACTIVE'";
        $session  = new SessionComponent(new ComponentCollection());
        $loginLab = $session->read('Auth.User');
        $roleLab = $loginLab['USER_ROLE'];
        if($roleLab =="LAB" || $roleLab =='PHARMACY'){
            $condition .= " and HospitalPaymentType.lab_pharmacy_user_id = ".$loginLab['LabPharmacyUser']['id'];
        }else{
            $condition .= " AND  ( ( HospitalPaymentType.lab_pharmacy_user_id = 0) OR (HospitalPaymentType.lab_pharmacy_type='LAB' and HospitalPaymentType.lab_pharmacy_is_inhouse ='YES') )";
        }
        $condition .= " ) OR ( HospitalPaymentType.thinapp_id = '0' AND HospitalPaymentType.status ='ACTIVE' )";
        $query = "select HospitalPaymentType.id,HospitalPaymentType.name from hospital_payment_types as HospitalPaymentType where $condition";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);

        if ($service_message_list->num_rows) {
            $list = array_column(mysqli_fetch_all($service_message_list,MYSQLI_ASSOC),'name','id');
            $list[-1] = 'Cash';
            return $list;
        }else{
            $list[-1] = 'Cash';
            return $list;
        }
    }

    public function billing_report_category_list($thin_app_id){
        $condition = " HospitalServiceCategory.thinapp_id = $thin_app_id AND HospitalServiceCategory.status ='ACTIVE'";
        $session  = new SessionComponent(new ComponentCollection());
        $loginLab = $session->read('Auth.User');
        $roleLab = $loginLab['USER_ROLE'];
        if($roleLab =="LAB" || $roleLab =='PHARMACY'){
            $condition .= " and HospitalServiceCategory.lab_pharmacy_user_id = ".$loginLab['LabPharmacyUser']['id'];
        }else{
            $condition .= " AND  ( ( HospitalServiceCategory.lab_pharmacy_user_id = 0) OR (HospitalServiceCategory.lab_pharmacy_type='LAB' and HospitalServiceCategory.lab_pharmacy_is_inhouse ='YES') )";
        }
        $query = "select HospitalServiceCategory.id,HospitalServiceCategory.name from hospital_service_categories as HospitalServiceCategory where $condition order by HospitalServiceCategory.name";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            return array_column(mysqli_fetch_all($service_message_list,MYSQLI_ASSOC),'name','id');
        }else{
            return array();
        }
    }
    public function billing_report_biller_list($thin_app_id){
        $condition = " u.thinapp_id = $thin_app_id and u.status ='Y'";
        $session  = new SessionComponent(new ComponentCollection());
        $loginLab = $session->read('Auth.User');
        $roleLab = $loginLab['USER_ROLE'];
         $query = " select * from ( (select CONCAT(u.id,'#',app_staff.staff_type) as id, CONCAT(IFNULL(app_staff.name,u.username), ' (******', RIGHT(IFNULL(app_staff.mobile,u.mobile),4), ') ', ' - ',app_staff.staff_type) as name from  users as u right join appointment_staffs as app_staff on app_staff.user_id = u.id  and ( app_staff.staff_type='RECEPTIONIST' OR u.role_id = 5 ) where   u.thinapp_id = $thin_app_id) UNION ALL (select CONCAT(lpu.id,'#',lpu.role_type) as id, CONCAT(IFNULL(lpu.lab_name,lpu.name),' - ',lpu.role_type) as name from lab_pharmacy_users as lpu left join users u on u.id = lpu.user_id where lpu.status='ACTIVE' and lpu.is_verify='YES' and lpu.thinapp_id = $thin_app_id ) ) as final order by name asc ";
       
        
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            return array_column(mysqli_fetch_all($service_message_list,MYSQLI_ASSOC),'name','id');
        }else{
            return array();
        }
    }

    public function getRefundedAmount($appointmentID){
        $appointment = ClassRegistry::init('AppointmentCustomerStaffService');
        $appointmentData =  $appointment->find('first', array('fields' => array("AppointmentCustomerStaffService.refund_amount"),'conditions'=>array('AppointmentCustomerStaffService.id' => $appointmentID),"contain"=>false));
        if(!empty($appointmentData['AppointmentCustomerStaffService']['refund_amount']))
        {
            return (int)$appointmentData['AppointmentCustomerStaffService']['refund_amount'];
        }
        else
        {
            return 0;
        }
    }


    Public function ipdTotalBalance($ipd_id){
        $query = "select hi.admit_date, hi.ipd_unique_id, SUM((select mpo_in.total_amount from medical_product_orders as mpo_in where mpo_in.id = mpo.id and mpo_in.is_advance = 'Y' and mpo_in.status='ACTIVE' )) as advance, SUM((select mpo_in.total_amount from medical_product_orders as mpo_in where mpo_in.id = mpo.id and mpo_in.is_expense = 'Y' and mpo_in.payment_status ='PENDING' )) as expense  from  hospital_ipd as hi left join medical_product_orders as mpo on hi.id = mpo.hospital_ipd_id where hi.id = $ipd_id ";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            return mysqli_fetch_assoc($service_message_list);
        }else{
            return array();
        }
    }

    public function getHospitalPrescriptionFields($thinapp_id){
        $query = "SELECT `fields` FROM `setting_web_prescriptions` WHERE `thinapp_id` = '".$thinapp_id."' LIMIT 1";
        $connection = ConnectionUtil::getConnection();
        $fieldList = $connection->query($query);
        if ($fieldList->num_rows) {
            $dataToSend = mysqli_fetch_assoc($fieldList);
            $data = json_decode($dataToSend['fields'],true);

            return $data;
        }else{
            return array();
        }
    }

    public function check_user_enable_functionlity($thinapp_id,$label_key){
        return Custom::check_user_permission($thinapp_id,$label_key);
    }

    public function get_receipt_id_by_order_id($orderID){
        return Custom::get_receipt_id_by_order_id($orderID);
    }

    public function get_thin_app_admin_data($thin_app_id){
        return Custom::get_thinapp_admin_data($thin_app_id);
    }
    public function has_category($thin_app_id,$category_ids_array){
        return Custom::has_category($thin_app_id,$category_ids_array);
    }

    public function get_patient_data_name_list($thin_app_id){
        return Custom::get_patient_data_name_list($thin_app_id);
    }

    public function get_patient_data_address_list($thin_app_id){
        return Custom::get_patient_data_address_list($thin_app_id);
    }

    public function get_doctor_filter_appointment_array($thin_app_id){
        return Custom::get_doctor_filter_appointment_array($thin_app_id,false);
    }
    public function create_age_array($string){
        return Custom::create_age_array($string);
    }
    public function get_billing_table_column($thin_app_id){
        return Custom::get_billing_table_column($thin_app_id);
    }
    public function get_total_refund_by_payment_type($user_id,$from_date,$to_date,$address_id=0,$order_ids=''){
        return Custom::get_total_refund_by_payment_type($user_id,$from_date,$to_date,$address_id,$order_ids);
    }
    public function get_dashboard_icon(){
        return Custom::get_dashboard_icon();
    }
    public function get_refferer_name_list($thin_app_id){
        return Custom::get_refferer_name_list($thin_app_id);
    }
    public function get_refferer_name_list_appointment($thin_app_id){
        return Custom::get_refferer_name_list_appointment($thin_app_id);
    }
    public function get_patient_due_amount($patient_id, $patient_type,$medical_product_order_id=0){
        return Custom::get_patient_due_amount($patient_id, $patient_type,$medical_product_order_id);
    }
    public function get_patient_amount_by_settle_id($medical_product_order_id){
        return Custom::get_patient_amount_by_settle_id($medical_product_order_id);
    }
    public function get_patient_amount_by_order_id($medical_product_order_id){
        return Custom::get_patient_amount_by_order_id($medical_product_order_id);
    }
     public function array_order_by($final_array,$value){
        return Custom::array_order_by($final_array, $value, SORT_ASC);
    }

    public function get_app_prescription_fields($thin_app_id){
        return Custom::get_app_prescription_fields($thin_app_id);
    }
    public function get_receptionist_by_id($receptionist_id){
        return Custom::get_receptionist_by_id($receptionist_id);
    }

    public function get_doctor_by_id($doctor_id,$thin_app_id){
        return Custom::get_doctor_by_id($doctor_id,$thin_app_id);
    }

    public function get_reason_of_appointment_list($thin_app_id){
        return Custom::get_reason_of_appointment_list($thin_app_id);
    }

    public function get_doctor_address_time($doctor_id, $address_id){
        return Custom::get_doctor_address_time($doctor_id, $address_id);
    }

    public function get_localization_by_id($id, $list_for){
        return Custom::get_localization_by_id($id, $list_for);
    }


    public function get_thin_app_data($thin_app_id){
        return Custom::getThinAppData($thin_app_id);
    }

    public function getAllCountryList(){
        return Custom::getAllCountryList();
    }

    public function getAllStateList(){
        return Custom::getAllStateList();
    }
    public function getAllCityList(){
        return Custom::getAllCityList();
    }
    public function tab_get_service_list($doctor_id){
        return Custom::tab_get_service_list($doctor_id);
    }
    public function create_tag_layout($tag_data,$step_id){
        return Custom::create_tag_layout($tag_data,$step_id);
    }
    public function create_queue_number($appointment_data){
        return Custom::create_queue_number($appointment_data);
    }

    public function get_patient_category_list_drp($thin_app_id){
         return Custom::get_patient_category_list_drp($thin_app_id);
    }
    public function update_page_type_id($cms_id,$dashboard_icon_url){
         return Custom::update_page_type_id($cms_id,$dashboard_icon_url);
    }
    public function get_tmp_appointment_data($ids){
         return Custom::get_tmp_appointment_data($ids);
    }


    public function randomColorPicker(){
        $part1 = str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT);
        $part2= str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT);
        $part3 = str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT);
        return $part1 . $part2. $part3;
    }

    public function beforeLodeImage(){
        return Router::url('/images/before_load.gif');
    }

    public function get_app_template($thinappID){
        $app_data = Custom::getThinAppData($thinappID);
        return $app_data['web_theme'];
    }




    public function get_slot_color($thin_app_id){
        $AppointmentSlotColor = ClassRegistry::init('AppointmentSlotColor');
        return  $AppointmentSlotColor->find('all',array(
            "fields"=>array("AppointmentSlotColor.type","AppointmentSlotColor.color_code"),
            "conditions"=>array("AppointmentSlotColor.thinapp_id" =>$thin_app_id),
            "contain"=>false));
    }

    public function tracker_labels($check_in_type,$sub_token){
        return Custom::tracker_labels($check_in_type,$sub_token);
    }
    public function visible_live_tracker_data($appointment_data,$user_role){
        return Custom::visible_live_tracker_data($appointment_data,$user_role);
    }
    public function create_appointment_button_array($thin_app_id,$user_role,$appointment){
        return Custom::create_appointment_button_array($thin_app_id,$user_role,$appointment);
    }

	public function get_next_walk_in_time_after_break($doctor_id, $address_id, $date,$break_start,$break_end){
        return Custom::get_next_walk_in_time_after_break($doctor_id, $address_id, $date,$break_start,$break_end);
    }

	public function walk_in_button_position($thin_app_id,$doctor_id, $address_id, $service_id,$date){
        return Custom::walk_in_button_position($thin_app_id,$doctor_id, $address_id, $service_id,$date);
    }

	public function has_ivr_call_number($doctor_id,$thin_app_id = 0){
        $query = "select ivr_number from doctors_ivr where thinapp_id = $thin_app_id and doctor_status= 'Active' limit 1";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            return mysqli_fetch_assoc($service_message_list)['ivr_number'];
        }else{
            return false;
        }
    }

	public function getDayOpenStatus($doctor_id,$date,$address_id){
        $number = date('N',strtotime($date));
        $query = "SELECT IFNULL(abs.is_date_blocked,'NO') as is_date_blocked, ash.time_from,ash.time_to,ash.status FROM appointment_staff_hours as ash left join appointment_bloked_slots as abs on abs.doctor_id = ash.appointment_staff_id and abs.book_date = '$date' and abs.address_id = $address_id and abs.is_date_blocked ='YES'  WHERE ash.appointment_staff_id = $doctor_id AND ash.appointment_day_time_id=$number LIMIT 1";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            return mysqli_fetch_assoc($service_message_list);
        }else{
            return array();
        }
    }

	public function getAddBanner(){
        $banner_array[0]['title']="First";
        //$banner_array[0]['path'] = "https://s3-ap-south-1.amazonaws.com/mengage/202103031024421495133824.png";
    	$banner_array[0]['path'] = "https://s3-ap-south-1.amazonaws.com/mengage/202104071942461818815388.jpg";
        return $banner_array;
    }

	public function getIPDDischargeDate($ipd_id,$thin_app_id)
    {
        $user = ClassRegistry::init('HospitalDischarge');
        $discharge_data = $user->find('first',array('conditions'=>array('HospitalDischarge.hospital_ipd_id'=>$ipd_id,'HospitalDischarge.thinapp_id'=>$thin_app_id),'contain'=>false));
        if(!empty($discharge_data)){
            return $discharge_data['HospitalDischarge']['discharge_date'];
        }
        return false;

    }

	
    public static function get_ck_birla_token($doctor_id,$token){
        return Custom::get_ck_birla_token($doctor_id,$token);
    }
    public static function get_billing_counter_list_data($thin_app_id){
        return Custom::get_billing_counter_list_data($thin_app_id);
    }

	 public static function check_label($object){
        if(isset($object['label'])){
            return implode(", ",$object['label']);
        }
        return '';
    }

    public static function createAvgLabel($data_array){
      //  echo  $data_array['sum'] ."/". $data_array['total'].'--';
        $min = round($data_array['sum'] / $data_array['total']);
        return ($min < 2)?"Avg. $min Minute":"Avg. $min Minutes";
    }

    public static function getPatientCountFromArray($data_array){
        if(!empty($data_array)){
            $pos = array_search('Skipped', $data_array);
            if($pos !== false){
                unset($data_array[$pos]);
            }
            return count($data_array);
        }
        return 0;

    }

    public static function getTotalMinuteFromLabel($label_array){
        if(!empty($label_array)){
            $return = array('minutes'=>0,'second'=>0);
            foreach ($label_array as $key =>$label){
                $total_minutes = explode(" ",$label);
                $return['minutes'] = $return['minutes']+@(int)$total_minutes[0];
                $return['second'] = $return['second']+@(int)$total_minutes[1];
            }
            return $return;

        }
        return array('minutes'=>0,'second'=>0);
    }


    public static function calculateTotalMinutes($array){
        return $array['minutes']+(round($array['seconds']/60));
    }

    public static function short_url($url,$thin_app_id){
        return Custom::short_url($url,$thin_app_id);
    }

	public static function get_all_doctor_list($thin_app_id,$doctor_id){

        $query = "select ac.name as department_name,  app_staffs.* from appointment_staffs app_staffs left join appointment_categories as ac on ac.id = app_staffs.appointment_category_id where app_staffs.staff_type= 'DOCTOR' AND app_staffs.status = 'ACTIVE' AND app_staffs.thinapp_id =$thin_app_id and app_staffs.id != $doctor_id order by app_staffs.name asc  ";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $staff_data = mysqli_fetch_all($service_message_list,MYSQLI_ASSOC);
            return $staff_data;
        }else{
            return false;
        }


    }

}
