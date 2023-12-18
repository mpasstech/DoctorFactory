<?php

class AppointmentService extends AppModel {

    public $actsAs = array('Containable');
    public $hasMany = array(
        'AppointmentStaffService' => array(
            'className' => 'AppointmentStaffService',
            'foreignKey' => 'appointment_service_id',
            'conditions'=>array(
                'AppointmentStaffService.status' => 'ACTIVE'
            )
        ),
        'AppointmentCategoryService' => array(
            'className' => 'AppointmentCategoryService',
            'foreignKey' => 'appointment_service_id',
            'conditions'=>array(
                'AppointmentCategoryService.status' => 'ACTIVE'
            )
        )
    );

	
	
	
  
}
