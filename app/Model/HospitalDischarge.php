<?php

class HospitalDischarge extends AppModel
{

    public $useTable = 'hospital_discharge';

    public $belongsTo = array(
        'HospitalIpd',
        'Thinapp',
        'AppointmentStaff',
        'Children' => array(
            'className' => 'Children',
            'foreignKey' => 'patient_id',
            'conditions'=>array(
                "HospitalDischarge.patient_type" =>"CHILDREN"
            )
        ),
        'AppointmentCustomer' => array(
            'className' => 'AppointmentCustomer',
            'foreignKey' => 'patient_id',
            'conditions'=>array(
                "HospitalDischarge.patient_type" =>"CUSTOMER"
            )
        )
    );
}