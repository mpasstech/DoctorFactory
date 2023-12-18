<?php

class AppointmentWithoutToken extends AppModel {

    public $actsAs = array('Containable');
	public $useTable = 'appointment_without_token';
	public $belongsTo = array(
        'AppointmentStaff',
        'AppointmentCustomer',
        'AppointmentAddress',
        'Thinapp'
    );
}
