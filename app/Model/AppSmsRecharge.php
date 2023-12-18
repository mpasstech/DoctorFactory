<?php

class AppSmsRecharge extends AppModel
{
    public $useTable = 'app_sms_recharges';
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
		'SupportAdmin' => array(
            'className' => 'User',
            'foreignKey' => 'support_admin_id',
        ),
    );
	
}