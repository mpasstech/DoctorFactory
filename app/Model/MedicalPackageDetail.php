<?php

class MedicalPackageDetail extends AppModel
{

    public $useTable = 'medical_package_details';
	public $actsAs = array('Containable');
    
    public $belongsTo=array(
        'Thinapp',
        'Children',
        'AppointmentCustomer',
        'AppointmentStaff',
        'AppointmentAddress',
		'MedicalProduct',
        'CreatedBy' => array(
            'className' => 'User',
            'foreignKey' => 'created_by_user_id',
        )
    );



}