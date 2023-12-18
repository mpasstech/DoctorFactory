<?php

class Coin extends AppModel
{
    public $useTable = 'coins';
	public $actsAs = array('Containable');
	 public $belongsTo = array(
        'Owner' => array(
            'className' => 'User',
            'foreignKey' => 'owner_user_id',
        ),
		'ActionTakenBy' => array(
            'className' => 'User',
            'foreignKey' => 'action_taken_by_user_id',
        ),
		'Message' => array(
            'className' => 'Message',
            'foreignKey' => 'message_id',
        ),
		'Thinapp' => array(
            'className' => 'Thinapp',
            'foreignKey' => 'thinapp_id',
        ),		
    );




}