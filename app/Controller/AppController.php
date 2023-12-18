<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
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

App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {
    
    public $components = array('Auth','Cookie','Session','RequestHandler','Email','Custom');
    public $uses = array('MedicalProductOrderDetailArchive','AppointmentCustomerStaffServiceArchive','MedicalProductOrderArchive','HospitalDischargeTemplate','BookingConvenienceFeeDetail','AppStackTrace','IpdBedHistory','Countries','States','Cities','Cities','Cities','FollowUpReminder','PregnancySemester','MedicalProductForm','MedicalProductQuantity','HospitalIpdSettlement','HospitalDepositAmount','LabPharmacyUser','HospitalDischarge','HospitalIpd','HospitalTaxRate','HospitalServiceCategory','HospitalService','HospitalPaymentType','UserPayment','PrescriptionTag','AppUserStatic','Country','State','City','AppMasterVaccination','CmsDocHealthTipSubCategory','CmsDocDashboard','CustomDriveFolder','ServiceMenu','ServiceMenuCategory','AppServiceOffer', 'AppAddressLocation','ActionQuestion', 'ActionRespnse', 'ActionType', 'AppEnableFunctionality', 'AppEnquiryReply', 'AppFunctionalityType', 'AppModel', 'AppQueries', 'AppSmsRecharge', 'AppSmsStatic', 'AppStaff', 'AppTheme', 'Channel', 'ChannelLoseObject', 'ChannelMessage', 'CmsPage', 'Coin', 'CoinRedeem', 'CoinTransfer', 'Collaborator', 'Conference', 'ConferenceParticipate', 'Constant', 'CreditHistory', 'Event', 'EventAgenda', 'EventCategory', 'EventMedia', 'EventOrganizer', 'EventPost', 'EventResponse', 'EventShow', 'EventSpeaker', 'EventTicket', 'EventTicketSell', 'Gift', 'Gullak', 'Leads', 'LoseObject', 'LostFoundComment', 'Membership', 'Message', 'MessageAction', 'MessageStatic', 'NewsLetter', 'NewslettersubScribers', 'OrganizationType', 'Page', 'Payments', 'PaymentTransactions', 'Quest', 'QuestCategory', 'QuestionChannel', 'QuestionChoice', 'QuestLike', 'QuestReply', 'QuestReplyThank', 'QuestShare', 'RedeemGift', 'SellImage', 'SellItem', 'SellItemCategory', 'SellLike', 'SellWishlist', 'SentSmsDetail', 'Setting', 'SmsQueue', 'Subscriber', 'SubscriberMessages', 'Thinapp', 'Ticket', 'TicketComment', 'Transaction', 'UploadApk', 'User', 'UserEnabledFunPermission', 'UserFunctionalityType', 'AppointmentCategory','AppointmentCategoryService','AppointmentCustomer','AppointmentCustomerStaffService','AppointmentDayTime','AppointmentService','AppointmentStaff','AppointmentStaffBreakSlot','AppointmentStaffHour','AppointmentStaffService','AppointmentServiceAddress', 'AppointmentStaffAddress', 'AppointmentAddress','PaymentItem','PaymentFileAmount','AppPaymentTransaction', 'DriveFolder', 'DriveFile', 'DriveShare','Children','MedicalProduct','MedicalProductOrder','MedicalProductOrderDetail','SettingWebPrescription','HospitalEmergency','AppointmentWithoutToken','PatientTag','PatientIllnessTag','MedicalPackageDetail','MedicalProductQuantityDead','DentalSupplier','TeethAttachment','TeethAttachmentOrder','TeethImageCategory','TeethImageCategoryOrder','TeethImageSubCategory','TeethNumber','TeethNumberOrder','TeethOrder','TeethShade','TeethShadeOrder','TeethTextCategory','TeethTextCategoryOrder','TeethTextSubCategory','TeethOrderStatusSupplire','Supplier','SupplierAttachmentField','SupplierAttachmentOrder','SupplierCheckboxRadioField','SupplierCheckboxRadioOrder','SupplierHospital','SupplierOrder','SupplierOrderDetail','SupplierOrderForm','SupplierTeethNumber','SupplierTeethNumberOrder','SupplierTextTextareaField','SupplierTitleField','SupplierTextTextareaFieldOrder','SupplireOrderStatus','SpeechMessage','SpeechMessageToPlay','MediaMessage','AppointmentSlotColor','CmsPageBody','HospitalPaymentTypeDetail','AppSmsTemplate','SmartClinicMediator','SmartClinicMediatorAssociate' );
    public $helper = array('AppAdmin','Session',"Custom");


    function _setErrorLayout() {
        if ($this->name == 'CakeError') {
            $this->layout = 'home';
        }
    }

    public function beforeRender () {
        $this->_setErrorLayout();
    }


    public function notFound($error) {
        $this->_outputMessage('error404');
    }
    
    public function beforeFilter(){

        if(!$this->Auth->user())
        {
            if($this->Cookie->check('remember_me_cookie'))
            {
                $cookie = $this->Cookie->read('remember_me_cookie');
                if (!empty($cookie)) {
                    if ($this->Auth->login($cookie)){
                        $this->redirect(array('controller'=>'users','action'=>'dashboard'));
                    }
                }
            }
        }
        $this->set('loggedIn',$this->Auth->loggedIn());
        if ($this->Auth->loggedIn()) {
            $this->set("username", $this->Auth->User('username'));
            $this->loadModel('User');
            $this->set("userdata", $this->User->findById($this->Auth->User('id')));
            
            /*if(($this->Auth->User('role_id')!=2 && $this->Auth->User('role_id')!=4) && $this->params['prefix']=='admin')
            {
                //$this->Session->setFlash(__('You are not authorized to access this location.', true), 'default', array('class' => 'not-authorize'));
                //popup_flash_message($message = null,$type = null,$auto_close = null,$redirect_on = array())
                $message = "You are not authorized to access this location";
                $this->Session->write('message',$message);
                $this->redirect(SITE_URL."homes/popup_flash_message/error/Y/home");
            }*/
        }

        $this->set('app_key',$this->Auth->user('app_key'));
        //$this->Auth->loginAction = array('controller'=>'homes','action'=>'index');
       /* $this->loadModel('Setting');
        $settings = $this->Setting->find("all", array("conditions" => array("Setting.status" => 'A')));
        if(isset($settings) && !empty($settings))
        {
            foreach ($settings as $setting) {
                define($setting['Setting']['attribute'], $setting['Setting']['value']);
            }
        }*/
        
    }


    function error404($params) {
        $this->controller->layout = 'home';
        parent::error404($params);
    }
}
