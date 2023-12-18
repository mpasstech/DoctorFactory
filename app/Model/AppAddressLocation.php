<?php

class AppAddressLocation extends AppModel {
	public $actsAs = array('Containable');
	public $belongsTo = array(
		'Thinapp',
	);

	var $validate = array(
		'city' => array(
			'notEmpty' => array ('rule' => 'notEmpty','message' => 'Please enter city name.')
		),
		'address' => array(
			'notEmpty' => array ('rule' => 'notEmpty','message' => 'Please enter address.')
		),
		'latitude' => array(
			'notEmpty' => array ('rule' => 'notEmpty','message' => 'Please select address.')
		),
		'longitude' => array(
			'notEmpty' => array ('rule' => 'notEmpty','message' => 'Please select address.')
		)

	);
}
