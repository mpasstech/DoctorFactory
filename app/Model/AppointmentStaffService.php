<?php

class AppointmentStaffService extends AppModel {

    public $actsAs = array('Containable');
    public $belongsTo = array(
        'AppointmentService' => array(
            'className' => 'AppointmentService',
            'foreignKey' => 'appointment_service_id',
            'conditions'=>array(
                'AppointmentService.status' => 'ACTIVE'
            )
        ),
        'AppointmentStaff' => array(
            'className' => 'AppointmentStaff',
            'foreignKey' => 'appointment_staff_id',
            'conditions'=>array(
                'AppointmentStaff.status' => 'ACTIVE'
            )
        )
    );

  
}
