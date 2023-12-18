<?php
class PatientTag extends AppModel
{
    public $useTable = 'patient_tags';
	public $actsAs = array('Containable');
	public $belongsTo = array(
        'AppointmentCustomer' => array(
            'className' => 'AppointmentCustomer',
            'foreignKey' => 'appointment_customer_id',
        ),
		'Children' => array(
            'className' => 'Children',
            'foreignKey' => 'children_id',
        ),
		'PatientIllnessTag' => array(
            'className' => 'PatientIllnessTag',
            'foreignKey' => 'patient_illness_tag_id',
        ),		
    );
	
}