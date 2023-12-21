<?php

class AppointmentCustomerStaffServiceArchive extends AppModel {

    public $actsAs = array('Containable');
    public $useTable = 'appointment_customer_staff_services_archive';
    public $hasOne = array(
        'CustomerAdmitDetail' => array(
            'className' => 'HospitalIpd',
            'foreignKey' => false,
            'conditions'=>array(
                "CustomerAdmitDetail.thinapp_id = AppointmentCustomerStaffServiceArchive.thinapp_id",
                "CustomerAdmitDetail.patient_id = AppointmentCustomerStaffServiceArchive.appointment_customer_id",
                "CustomerAdmitDetail.patient_type = 'CUSTOMER'",
                "CustomerAdmitDetail.admit_status = 'ADMIT'",
                "CustomerAdmitDetail.status = 'ACTIVE'",
            )
        ),
        'ChildrenAdmitDetail' => array(
            'className' => 'HospitalIpd',
            'foreignKey' => false,
            'conditions'=>array(
                "ChildrenAdmitDetail.thinapp_id = AppointmentCustomerStaffServiceArchive.thinapp_id",
                "ChildrenAdmitDetail.patient_id = AppointmentCustomerStaffServiceArchive.children_id",
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
        'MedicalProductOrder' => array(
            'className' => 'MedicalProductOrderArchive',
            'foreignKey' => 'medical_product_order_id'

        ),

    );

  
}
