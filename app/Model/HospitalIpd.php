<?php

class HospitalIpd extends AppModel
{

    public $actsAs = array('Containable');
    public $useTable = 'hospital_ipd';
    public $hasOne = array('HospitalDischarge',
        'PregnancySemester' => array(
            'className' => 'PregnancySemester',
            'foreignKey' => false,
            'conditions'=>array(
                "HospitalIpd.patient_type" =>"CUSTOMER",
                "HospitalIpd.status" =>"ACTIVE",
                "HospitalIpd.patient_id = PregnancySemester.appointment_customer_id",
                "PregnancySemester.id =(select max(id) from pregnancy_semesters where appointment_customer_id = HospitalIpd.patient_id )"
            )
        )
        );
    public $belongsTo = array(
        'Thinapp',
        'HospitalServiceCategory',
        'AppointmentStaff',
        'Children' => array(
            'className' => 'Children',
            'foreignKey' => 'patient_id',
            'conditions'=>array(
                "HospitalIpd.patient_type" =>"CHILDREN"
            )
        ),
        'AppointmentCustomer' => array(
            'className' => 'AppointmentCustomer',
            'foreignKey' => 'patient_id',
            'conditions'=>array(
                "HospitalIpd.patient_type" =>"CUSTOMER"
            )
        )
    );
}