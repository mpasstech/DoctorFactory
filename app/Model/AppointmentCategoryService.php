<?php

class AppointmentCategoryService extends AppModel {

    public $actsAs = array('Containable');

    public $belongsTo = array(
        'AppointmentService' => array(
            'className' => 'AppointmentService',
            'foreignKey' => 'appointment_service_id',
            'conditions'=>array(
                'AppointmentService.status' => 'ACTIVE'
            )
        ),
        'AppointmentCategory' => array(
            'className' => 'AppointmentCategory',
            'foreignKey' => 'appointment_category_id',
            'conditions'=>array(
                'AppointmentCategory.status' => 'ACTIVE'
            )
        )
    );
	
  
}
