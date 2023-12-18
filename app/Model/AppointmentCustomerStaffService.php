<?php

class AppointmentCustomerStaffService extends AppModel {

    public $actsAs = array('Containable');

    public $hasOne = array(

        'CustomerAdmitDetail' => array(
            'className' => 'HospitalIpd',
            'foreignKey' => false,
            'conditions'=>array(
                "CustomerAdmitDetail.thinapp_id = AppointmentCustomerStaffService.thinapp_id",
                "CustomerAdmitDetail.patient_id = AppointmentCustomerStaffService.appointment_customer_id",
                "CustomerAdmitDetail.patient_type = 'CUSTOMER'",
                "CustomerAdmitDetail.admit_status = 'ADMIT'",
                "CustomerAdmitDetail.status = 'ACTIVE'",
            )
        ),
        'ChildrenAdmitDetail' => array(
            'className' => 'HospitalIpd',
            'foreignKey' => false,
            'conditions'=>array(
                "ChildrenAdmitDetail.thinapp_id = AppointmentCustomerStaffService.thinapp_id",
                "ChildrenAdmitDetail.patient_id = AppointmentCustomerStaffService.children_id",
                "ChildrenAdmitDetail.patient_type = 'CHILDREN'",
                "ChildrenAdmitDetail.admit_status = 'ADMIT'",
                "ChildrenAdmitDetail.status = 'ACTIVE'"
            )
        )
    );



    public $belongsTo = array(
        'AppointmentCategory',
        'AppointmentStaff',
        'AppointmentCustomer',
        'AppointmentAddress',
        'AppointmentService',
        'Children',
        'Thinapp',
        'HospitalPaymentType',
        'MedicalProductOrder'
    );

  
}
