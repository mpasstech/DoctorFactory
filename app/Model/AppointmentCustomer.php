<?php
App::uses('CakeSession', 'Model/Datasource');
class AppointmentCustomer extends AppModel {

    public $actsAs = array('Containable');
    public $useTable = 'appointment_customers';
    public $belongsTo =array('Thinapp');
    public $hasMany =array('MedicalProductOrder','PatientTag');


    public $hasOne = array(
        'DriveFolder' => array(
            'className' => 'DriveFolder',
            'foreignKey' => false,
            'conditions'=>array(
                "DriveFolder.thinapp_id = AppointmentCustomer.thinapp_id",
                "DriveFolder.folder_add_from_number = AppointmentCustomer.mobile",
            )
        ),
        'CustomerAdmitDetail' => array(
            'className' => 'HospitalIpd',
            'foreignKey' => false,
            'conditions'=>array(
                "CustomerAdmitDetail.thinapp_id = AppointmentCustomer.thinapp_id",
                "CustomerAdmitDetail.patient_id = AppointmentCustomer.id",
                "CustomerAdmitDetail.patient_type = 'CUSTOMER'",
                "CustomerAdmitDetail.admit_status = 'ADMIT'",
                "CustomerAdmitDetail.status = 'ACTIVE'",
            )
        )
    );


    var $validate = array(

        'first_name' => array(
            'notEmpty' => array ('rule' => 'notEmpty','message' => 'Please enter patient name.')
        ),
        'mobile' => array(
            'notEmpty' => array ('rule' => 'notEmpty','message' => 'Please enter patient mobile.'),
            'minlimit'=>array('rule'=>array('minLength', '10'),'message'=>"Please enter valid 10 digit mobile number."),
            'maxlimit'=>array('rule'=>array('maxLength', '13'),'message'=>"Please enter valid 10 digit mobile number."),
            /*'isNumber' => array ('rule' => array('checkNumber'),'message' => 'Please enter valid 10 digit mobile number.'),*/
            'isUnique' => array ('rule' => array('checkUniquePatient'),'message' => 'This patient with mobile and name already exists.')
        ),

    );

    function checkUniquePatient()
    {
        $thinapp_id = CakeSession::read("Auth.User.Thinapp.id");
        $name = trim($this->data['AppointmentCustomer']['first_name']);
        $mobile = $this->data['AppointmentCustomer']['mobile'];

        $user_data = $this->find('first',array(
            'conditions'=>array(
                'AppointmentCustomer.thinapp_id'=>$thinapp_id,
                'RIGHT(AppointmentCustomer.mobile,10)'=>substr($mobile, -10),
                'AppointmentCustomer.first_name'=>$name,
                'AppointmentCustomer.status'=>'ACTIVE'
            ),
            'contain'=>false
        ));
        if(!empty($user_data)){
            if(isset($this->data['AppointmentCustomer']['id'])){
                if($this->data['AppointmentCustomer']['id'] == $user_data['AppointmentCustomer']['id']){
                    return true;
                }else{
                    return false;
                }
            }else{
                return false;
            }
        }
        return true;
    }
    function checkNumber()
    {
        $mobile = substr($this->data['AppointmentCustomer']['mobile'], -10);
        return is_numeric($mobile );
    }




}
