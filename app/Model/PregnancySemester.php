<?php

class PregnancySemester extends AppModel
{
    public $useTable = 'pregnancy_semesters';
	public $actsAs = array('Containable');
	public $belongsTo = array(
        'Thinapp',
        'AppointmentCustomer',
		'AppointmentStaff' => array(
            'className' => 'AppointmentStaff',
            'foreignKey' => 'doctor_id',
        )
    );
	
}