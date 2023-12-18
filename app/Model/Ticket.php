<?php

class Ticket extends AppModel
{
    public $useTable = 'tickets';
    public $actsAs = array('Containable');
	 public $belongsTo = array(
        'CreatedBy' => array(
            'className' => 'User',
            'foreignKey' => 'created_by_user_id',
        ),
		'AppAdmin' => array(
            'className' => 'User',
            'foreignKey' => 'app_admin_id',
        ),
		'Thinapp' => array(
            'className' => 'Thinapp',
            'foreignKey' => 'app_id',
        ),		
    );
}