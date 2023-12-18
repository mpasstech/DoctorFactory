<?php
class HospitalDepositAmount extends AppModel
{

	public $actsAs = array('Containable');

	public $belongsTo = array(
	    'HospitalPaymentType',
	    'HospitalIpd',
        'Children' => array(
            'className' => 'Children',
            'foreignKey' => 'patient_id',
            'conditions'=>array(
                "HospitalDepositAmount.patient_type" =>"CHILDREN"
            )
        ),
        'AppointmentCustomer' => array(
            'className' => 'AppointmentCustomer',
            'foreignKey' => 'patient_id',
            'conditions'=>array(
                "HospitalDepositAmount.patient_type" =>"CUSTOMER"
            )
        )
    );
	public $hasOne = array(
	    'MedicalProductOrder' => array(
            'foreignKey' => 'hospital_deposit_amount_id'
        )
    );
}