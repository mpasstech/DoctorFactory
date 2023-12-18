<?php

class AppSmsStatic extends AppModel
{
    public $useTable = 'app_sms_statics';
	public $actsAs = array('Containable');
	
	 public $belongsTo = array(
        'Thinapp' => array(
            'className' => 'Thinapp',
            'foreignKey' => 'thinapp_id',
        ),
		'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id',
        ),
    );
	
}