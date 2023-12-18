<?php
class DoctorRefferal extends AppModel {
	public $useTable = 'doctor_refferals';
    public $actsAs = array('Containable');
	
	public $belongsTo = array(
			'User' => array(
				'className' => 'User',
				'foreignKey' => 'user_id',
			),
			'Thinapp' => array(
				'className' => 'Thinapp',
				'foreignKey' => 'thinapp_id',
			),
			'DoctorRefferalUser' => array(
				'foreignKey'=>false,
				'className' => 'DoctorRefferalUser',
				'conditions'=>array('`DoctorRefferalUser`.`user_id` = `DoctorRefferal`.`user_id`')
			),				
    );
	
}
